
--
-- TABLE: Petition
-- 
--  

CREATE TABLE Petition (
  id int NOT NULL ,
  creationDate DATETIME NOT NULL  DEFAULT CURRENT_TIMESTAMP,
  lastModified DATETIME NOT NULL  DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  author varchar(50) NOT NULL ,
  title varchar(200) NOT NULL ,
  content text NOT NULL ,
  startDate DATETIME NOT NULL 
);

CREATE INDEX Petition_id_index  ON Petition(id);
ALTER TABLE Petition CHANGE id id int   NOT NULL AUTO_INCREMENT ;

-- 
ALTER TABLE Petition ADD CONSTRAINT new_unique_constraint PRIMARY KEY (id);

-- CHECK Constraints are not supported in Mysql (as of version 5.x)
-- But it'll parse the statements without error 
