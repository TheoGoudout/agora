
--
-- TABLE: PetitionMailingList
-- 
--  

CREATE TABLE PetitionMailingList (
  id int NOT NULL,
  pid int NOT NULL,
  lastModified DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  email varchar(256) NOT NULL,
  enabled tinyint(1) NOT NULL
);

CREATE INDEX PetitionMailingList_id_index  ON PetitionMailingList(id);
ALTER TABLE PetitionMailingList CHANGE id id int   NOT NULL AUTO_INCREMENT ;

-- 
ALTER TABLE PetitionMailingList ADD CONSTRAINT new_unique_constraint PRIMARY KEY (id);

CREATE INDEX Petition_id_index  ON PetitionMailingList(id);

-- 
ALTER TABLE PetitionMailingList ADD CONSTRAINT  FOREIGN KEY (pid) REFERENCES Petition(id) ON UPDATE NO ACTION ON DELETE NO ACTION;

-- CHECK Constraints are not supported in Mysql (as of version 5.x)
-- But it'll parse the statements without error 
