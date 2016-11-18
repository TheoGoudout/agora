
--
-- TABLE: PetitionStatus
-- 
--  

CREATE TABLE PetitionStatus (
  id int NOT NULL,
  pid int NOT NULL,
  date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  content varchar(50) NOT NULL,
  comment varchar(200)
);

CREATE INDEX PetitionStatus_id_index  ON PetitionStatus(id);
ALTER TABLE PetitionStatus CHANGE id id int   NOT NULL AUTO_INCREMENT ;

-- 
ALTER TABLE PetitionStatus ADD CONSTRAINT new_unique_constraint PRIMARY KEY (id);

CREATE INDEX Petition_id_index  ON PetitionStatus(id);

-- 
ALTER TABLE PetitionStatus ADD CONSTRAINT  FOREIGN KEY (pid) REFERENCES Petition(id) ON UPDATE NO ACTION ON DELETE NO ACTION;

-- CHECK Constraints are not supported in Mysql (as of version 5.x)
-- But it'll parse the statements without error 
