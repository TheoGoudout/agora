
--
-- TABLE: Poll
-- 
--  

CREATE TABLE Poll (
  id int NOT NULL,
  lastModified DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  author varchar(50) NOT NULL,
  content varchar(200) NOT NULL,
  startDate DATETIME NOT NULL,
  endDate DATETIME NOT NULL
);

CREATE INDEX Poll_id_index  ON Poll(id);
ALTER TABLE Poll CHANGE id id int   NOT NULL AUTO_INCREMENT ;

-- 
ALTER TABLE Poll ADD CONSTRAINT new_unique_constraint PRIMARY KEY (id);

-- CHECK Constraints are not supported in Mysql (as of version 5.x)
-- But it'll parse the statements without error 
