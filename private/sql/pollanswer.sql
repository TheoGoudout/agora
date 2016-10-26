
--
-- TABLE: PollAnswer
-- 
--  

CREATE TABLE PollAnswer (
  id int NOT NULL ,
  pid int NOT NULL ,
  creationDate DATETIME NOT NULL  DEFAULT CURRENT_TIMESTAMP,
  lastModified DATETIME NOT NULL  DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  author varchar(50) NOT NULL ,
  content varchar(200) NOT NULL 
);

CREATE INDEX PollAnswer_id_index  ON PollAnswer(id);
ALTER TABLE PollAnswer CHANGE id id int   NOT NULL AUTO_INCREMENT ;

-- 
ALTER TABLE PollAnswer ADD CONSTRAINT new_unique_constraint PRIMARY KEY (id);

CREATE INDEX Poll_id_index  ON PollAnswer(id);

-- 
ALTER TABLE PollAnswer ADD CONSTRAINT  FOREIGN KEY (pid) REFERENCES Poll(id) ON UPDATE NO ACTION ON DELETE CASCADE;

-- CHECK Constraints are not supported in Mysql (as of version 5.x)
-- But it'll parse the statements without error 
