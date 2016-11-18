
--
-- TABLE: PollVote
-- 
--  

CREATE TABLE PollVote (
  id int NOT NULL,
  pid int NOT NULL,
  aid int NOT NULL,
  lastModified DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  content varchar(200),
  ipAddress varchar(45) NOT NULL,
  validationValue int(11) NOT NULL,
  validationStatus tinyint(1) NOT NULL DEFAULT 0
);

CREATE INDEX PollVote_id_index  ON PollVote(id);
ALTER TABLE PollVote CHANGE id id int   NOT NULL AUTO_INCREMENT ;

-- 
ALTER TABLE PollVote ADD CONSTRAINT unique_constraint UNIQUE (pid,ipAddress);

-- 
ALTER TABLE PollVote ADD CONSTRAINT new_unique_constraint PRIMARY KEY (id);

CREATE INDEX PollAnswer_id_index  ON PollVote(id);

CREATE INDEX Poll_id_index  ON PollVote(id);

-- 
ALTER TABLE PollVote ADD CONSTRAINT foreign_key_constraint_0 FOREIGN KEY (aid) REFERENCES PollAnswer(id) ON UPDATE NO ACTION ON DELETE CASCADE;

-- 
ALTER TABLE PollVote ADD CONSTRAINT foreign_key_constraint_1 FOREIGN KEY (pid) REFERENCES Poll(id) ON UPDATE NO ACTION ON DELETE CASCADE;

-- CHECK Constraints are not supported in Mysql (as of version 5.x)
-- But it'll parse the statements without error 
