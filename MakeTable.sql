-- INITIAL TABLE CREATION
use njvelat;
create table if not exists Student(SID varchar(7), name varchar(30),
	major enum('CS', 'ME', 'CPE', 'CE', 'EE'),
	PRIMARY KEY(SID));
grant all on Student to "cs3425gr"@"%";

create table if not exists Exam(exName varchar(30), points int, created Date, due Date,
	PRIMARY KEY(exName));
grant all on Exam to "cs3425gr"@"%";

create table if not exists Question(QID int AUTO_INCREMENT, textQ varchar(100), PRIMARY KEY(QID)); 
grant all on Question to "cs3425gr"@"%";

create table if not exists Login(SID varchar(7), password BINARY(64), salt varchar(25),
	FOREIGN KEY(SID) References Student(SID) ON DELETE CASCADE ON UPDATE CASCADE, PRIMARY KEY(SID));
grant all on Login to "cs3425gr"@"%";     

create table if not exists TotalG(SID varchar(7), exName varchar(30), score int,
	PRIMARY KEY(SID, exName), FOREIGN KEY(SID) References Student(SID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY(exName) References Exam(exName) ON DELETE CASCADE ON UPDATE CASCADE);
grant all on TotalG to "cs3425gr"@"%";

create table if not exists TestQ(exName varchar(30), QID int, points int,
	FOREIGN KEY(exName) References Exam(exName) ON DELETE CASCADE ON UPDATE CASCADE, FOREIGN KEY(QID) References Question(QID) ON DELETE CASCADE ON UPDATE CASCADE, PRIMARY KEY(QID, exName));
grant all on TestQ to "cs3425gr"@"%";

create table if not exists Grades(exName varchar(30), SID varchar(7), QID int, pointsE int,
	PRIMARY KEY(exName, SID, QID), FOREIGN KEY(exName) References Exam(exName) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY(SID) References Student(SID) ON DELETE CASCADE ON UPDATE CASCADE, FOREIGN KEY(QID) References Question(QID) ON DELETE CASCADE ON UPDATE CASCADE);
grant all on Grades to "cs3425gr"@"%";

create table if not exists Choices(QID int, choice char, textC varchar(100), isAnswer BOOL,
	PRIMARY KEY (QID, choice), FOREIGN KEY(QID) References Question(QID) ON DELETE CASCADE ON UPDATE CASCADE);
grant all on Choices to "cs3425gr"@"%";

create table if not exists Response(SID varchar(7), exName varchar(30), QID int, response char,
	PRIMARY KEY(SID, exName, QID), FOREIGN KEY(QID) References Question(QID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (SID) References Student(SID) ON DELETE CASCADE ON UPDATE CASCADE, FOREIGN KEY (exName) References Exam(exName) ON DELETE CASCADE ON UPDATE CASCADE);
grant all on Response to "cs3425gr"@"%";

-- TEACHERS ///////////////////////////////////////////////////////////////////////////////////////////////////
-- CREATE STUDENT WITH INTIAL PASSWORD
-- https://www.mssqltips.com/sqlservertip/3293/add-a-salt-with-the-sql-server-hashbytes-function/
delimiter //
DROP PROCEDURE IF EXISTS createStudent//
create procedure  createStudent (SID varchar(7), password varchar(100), name varchar(30), major enum('CS', 'ME', 'CPE', 'CE', 'EE'))
begin

	

	DECLARE Salt VARCHAR(25);
	DECLARE PwdWithSalt VARCHAR(125);


	-- GENERATE SALT
	DECLARE Seed int;
	DECLARE LCV tinyint;
	DECLARE CTime DATETIME;

	SET CTime = NOW();
	SET Seed = (Hour(Ctime) * 10000000) + (Minute(CTime) * 100000) + (Second(CTime) * 1000) + MicroSecond(CTime);
	SET LCV = 1;
	SET Salt = CHAR(ROUND((RAND(Seed) * 94.0) + 32, 3));

	WHILE (LCV < 25) DO
	SET Salt = Salt + CHAR(ROUND((RAND() * 94.0) + 32, 3));
	SET LCV = LCV + 1;
	END WHILE;

	SET PwdWithSalt = CONCAT(Salt, password);
	-- END GENERATING SALT

	START TRANSACTION;
		insert into Student values(SID, name, major);
		insert into Login values(SID, SHA2(PwdWithSalt,256), Salt);
	COMMIT;
end//


-- CREATE EXAMS
DROP PROCEDURE IF EXISTS createExam//
create procedure createExam(exName varchar(30), points int, created Date, due DATE)
	begin
		insert into Exam values(exName, points, created, due);
	end//

-- CREATE PROBLEMS FOR EXAM 
DROP PROCEDURE IF EXISTS createQuestion//
create procedure createQuestion(exName varchar(30), points int, textQ varchar(100))
begin 

START TRANSACTION; 
insert into Question values(default, textQ);
insert into TestQ values(exName, LAST_INSERT_ID(), points);  
COMMIT;
end//


-- CREATE CHOICES 
DROP PROCEDURE IF EXISTS createChoice//
create procedure createChoice(QID int, choice char, textC varchar(100), isAnswer BOOL)
	begin 
		insert into Choices values(QID, choice, textC, isAnswer);
	end//

DROP TRIGGER IF EXISTS insertGrade//
CREATE TRIGGER insertGrade
AFTER INSERT
ON Grades
FOR EACH ROW
BEGIN
DECLARE x INT;
SET x = (SELECT SUM(pointsE) FROM Grades WHERE exName=NEW.exName AND SID=NEW.SID);
INSERT INTO TotalG Values(NEW.SID, NEW.exName, x) ON DUPLICATE KEY UPDATE TotalG.exName = NEW.exName, TotalG.SID= NEW.SID, TotalG.score = x ;
END//


DROP TRIGGER IF EXISTS updateGrade//
CREATE TRIGGER updateGrade
AFTER UPDATE
ON Grades
FOR EACH ROW
BEGIN
UPDATE TotalG
SET TotalG.score = (SELECT SUM(pointsE)
                    FROM Grades
                    WHERE TotalG.exName = Grades.exName AND TotalG.SID = Grades.SID);
END//

DROP TRIGGER IF EXISTS deleteGrade//
CREATE TRIGGER  deleteGrade
AFTER DELETE
ON Grades
FOR EACH ROW
BEGIN
UPDATE TotalG
SET TotalG.score = (SELECT SUM(pointsE)
                    FROM Grades
                    WHERE TotalG.exName = Grades.exName AND TotalG.SID = Grades.SID);
END//
delimiter ;
