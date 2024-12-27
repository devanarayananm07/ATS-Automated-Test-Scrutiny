 TABLE `answers` (
  `test_ID` int(3) NOT NULL,
  `student_ID` char(9) NOT NULL,
  `answer_1` varchar(5000) NOT NULL,
  `answer_2` varchar(5000) NOT NULL,
  `answer_3` varchar(5000) NOT NULL
 )

 TABLE `questions` (
  `question_ID` int(3) NOT NULL,
  `prompt` varchar(1000) NOT NULL,
  `expected_answer` varchar(5000) NOT NULL,
  `marks` int(5) DEFAULT NULL,
  `added` timestamp NOT NULL DEFAULT current_timestamp(),
  `faculty_ID` int(5) DEFAULT NULL,
  `sem_id` varchar(10) NOT NULL,
  `sub_id` varchar(10) NOT NULL
) 

TABLE `faculty` (
  `faculty_ID` int(5) NOT NULL,
  `password` varchar(200) DEFAULT NULL,
  `faculty_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(10) NOT NULL
)


 `scores` (
  `test_id` int(3) NOT NULL,
  `student_ID` char(9) NOT NULL,
  `score` float(6,2) NOT NULL
)


TABLE `semester` (
  `sem_id` varchar(10) NOT NULL,
  `semester` varchar(50) NOT NULL
)


TABLE `students` (
  `student_ID` char(9) NOT NULL,
  `password` varchar(200) DEFAULT NULL,
  `student_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(10) NOT NULL
) 

TABLE `subjects` (
  `sub_id` varchar(10) NOT NULL,
  `subject` varchar(50) DEFAULT NULL,
  `sem_id` varchar(10) DEFAULT NULL
) 


 TABLE `tests` (
  `test_ID` int(3) NOT NULL,
  `test_name` varchar(20) NOT NULL,
  `question1_ID` int(3) NOT NULL,
  `question2_ID` int(3) NOT NULL,
  `question3_ID` int(3) NOT NULL
) 

Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`faculty_ID`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_ID`),
  ADD KEY `fk_faculty` (`faculty_ID`),
  ADD KEY `fk_semester` (`sem_id`),
  ADD KEY `fk_subjects` (`sub_id`);

--
-- Indexes for table `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`sem_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_ID`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`sub_id`),
  ADD KEY `sem_id` (`sem_id`);

--
-- Indexes for table `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`test_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_ID` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
  MODIFY `test_ID` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `fk_faculty` FOREIGN KEY (`faculty_ID`) REFERENCES `faculty` (`faculty_ID`),
  ADD CONSTRAINT `fk_semester` FOREIGN KEY (`sem_id`) REFERENCES `semester` (`sem_id`),
  ADD CONSTRAINT `fk_subjects` FOREIGN KEY (`sub_id`) REFERENCES `subjects` (`sub_id`);

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`sem_id`) REFERENCES `semester` (`sem_id`);
COMMIT;