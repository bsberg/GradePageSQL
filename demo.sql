Select * from Login   ;                       
Select * from Student ;          
call createStudent("Alice", "securepassword123", "Alice Fakeperson", "EE");
Select * from Login   ;                       
Select * from Student ; 

Select * from Exam    ;
call createExam("Quiz77", 10, CURDATE(), Date_add(CURDATE(), INTERVAL 10 DAY));
Select * from Exam    ;

Select * from Question; 
Select * from TestQ   ;
Select * from Choices ; 
call createQuestion("Quiz77", 3, "Which SQL statement selects from the database?");
call createChoice(LAST_INSERT_ID(), "A", "Insert", 0);
call createChoice(LAST_INSERT_ID(), "B", "Update", 0);
call createChoice(LAST_INSERT_ID(), "C", "Select", 1);
call createChoice(LAST_INSERT_ID(), "D", "Delete", 0);

Select * from Question;
Select * from TestQ   ;
Select * from Choices ; 
call createQuestion("Quiz77", 4, "Which SQL statement counts?");
call createChoice(LAST_INSERT_ID(), "A", "Order By", 0);
call createChoice(LAST_INSERT_ID(), "B", "Sum", 0);
call createChoice(LAST_INSERT_ID(), "C", "Sort", 0);
call createChoice(LAST_INSERT_ID(), "D", "Count", 1);

Select * from Question;
Select * from TestQ   ;
Select * from Choices ; 
call createQuestion("Quiz77", 3, "Which custom delimiter is objectively the best?");
call createChoice(LAST_INSERT_ID(), "A", "$$", 0);
call createChoice(LAST_INSERT_ID(), "B", "//", 0);
call createChoice(LAST_INSERT_ID(), "C", "^^", 1);
call createChoice(LAST_INSERT_ID(), "D", "--", 0);

Select * from Question;
Select * from TestQ   ; 
Select * from Choices ; 
--show grades are empty before taking test
Select * from Grades  ;          
Select * from TotalG  ;
