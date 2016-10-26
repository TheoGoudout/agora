
--
-- TABLE: PetitionSignature
-- 
--  

CREATE TABLE PetitionSignature (
  id int NOT NULL ,
  pid int NOT NULL ,
  creationDate DATETIME NOT NULL  DEFAULT CURRENT_TIMESTAMP,
  lastModified DATETIME NOT NULL  DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  gender bool NOT NULL ,
  firstName varchar(50) NOT NULL ,
  lastName varchar(50) NOT NULL ,
  address1 varchar(200) NOT NULL ,
  address2 varchar(200),
  address3 varchar(200),
  zipCode varchar(10) NOT NULL ,
  city varchar(200) NOT NULL 
);

CREATE INDEX PetitionSignature_id_index  ON PetitionSignature(id);
ALTER TABLE PetitionSignature CHANGE id id int   NOT NULL AUTO_INCREMENT ;

-- 
ALTER TABLE PetitionSignature ADD CONSTRAINT new_unique_constraint PRIMARY KEY (id);

CREATE INDEX Petition_id_index  ON PetitionSignature(id);

-- 
ALTER TABLE PetitionSignature ADD CONSTRAINT  FOREIGN KEY (pid) REFERENCES Petition(id) ON UPDATE NO ACTION ON DELETE NO ACTION;

-- CHECK Constraints are not supported in Mysql (as of version 5.x)
-- But it'll parse the statements without error 
