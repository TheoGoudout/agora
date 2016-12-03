--
-- Petition with mailing list, signatures and statuses
--
-- Petition 1
INSERT INTO
    Petition (author, title, content, startDate)
VALUES
    ('Théo Goudout', 'Interdiction du cumul des mandats éléctoraux', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2017-01-01 00:00:00');


-- Petition status 1
INSERT INTO
    PetitionStatus (pid, date, content)
VALUES
    (1, '2016-09-01 00:00:00', 'VOTED');

-- Petition status 2
INSERT INTO
    PetitionStatus (pid, date, content)
VALUES
    (1, '2016-10-01 00:00:00', 'WRITTEN');

-- Petition status 3
INSERT INTO
    PetitionStatus (pid, date, content)
VALUES
    (1, '2016-11-01 00:00:00', 'PUBLISHED');

-- Petition status 4
INSERT INTO
    PetitionStatus (pid, date, content)
VALUES
    (1, '2016-11-02 00:00:00', 'WAITING E-SIGNATURES');


-- Petition mailing list 1
INSERT INTO
    PetitionMailingList (pid, email, enabled)
VALUES
    (1, 'john.do@gmail.com', 1);

-- Petition mailing list 2
INSERT INTO
    PetitionMailingList (pid, email, enabled)
VALUES
    (1, 'jane.do@gmail.com', 1);

-- Petition mailing list 3
INSERT INTO
    PetitionMailingList (pid, email, enabled)
VALUES
    (1, 'foo.bar@gmail.com', 0);


-- Petition signature 1
INSERT INTO
    PetitionSignature (pid, gender, firstName, lastName, address1, zipCode, city)
VALUES
    (1, 1, 'John', 'Do', '15, First Street', '123456', 'New York City');

-- Petition signature 2
INSERT INTO
    PetitionSignature (pid, gender, firstName, lastName, address1, zipCode, city)
VALUES
    (1, 0, 'Jane', 'Do', '15, First Street', '123456', 'New York City');



--
-- Petition with mailing list, signatures and no statuses
--
-- Petition 2
INSERT INTO
    Petition (author, title, content, startDate)
VALUES
    ('Théo Goudout', 'Utilisation d\'un système de vote pondéré', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2017-01-01 00:00:00');


-- Petition mailing list 1
INSERT INTO
    PetitionMailingList (pid, email, enabled)
VALUES
    (2, 'john.do@gmail.com', 1);

-- Petition mailing list 2
INSERT INTO
    PetitionMailingList (pid, email, enabled)
VALUES
    (2, 'jane.do@gmail.com', 1);

-- Petition mailing list 3
INSERT INTO
    PetitionMailingList (pid, email, enabled)
VALUES
    (2, 'foo.bar@gmail.com', 0);


-- Petition signature 1
INSERT INTO
    PetitionSignature (pid, gender, firstName, lastName, address1, zipCode, city)
VALUES
    (2, 1, 'John', 'Do', '15, First Street', '123456', 'New York City');

-- Petition signature 2
INSERT INTO
    PetitionSignature (pid, gender, firstName, lastName, address1, zipCode, city)
VALUES
    (2, 0, 'Jane', 'Do', '15, First Street', '123456', 'New York City');



--
-- Petition with signatures, statuses and no mailing list
--
-- Petition 3
INSERT INTO
    Petition (author, title, content, startDate)
VALUES
    ('Théo Goudout', 'Interdiction au détenteurs d\'un casier judiciaire d\'effectuer un mandat éléctoral', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2017-01-01 00:00:00');


-- Petition status 1
INSERT INTO
    PetitionStatus (pid, date, content)
VALUES
    (3, '2016-09-01 00:00:00', 'VOTED');

-- Petition status 2
INSERT INTO
    PetitionStatus (pid, date, content)
VALUES
    (3, '2016-10-01 00:00:00', 'WRITTEN');

-- Petition status 3
INSERT INTO
    PetitionStatus (pid, date, content)
VALUES
    (3, '2016-11-01 00:00:00', 'PUBLISHED');

-- Petition status 4
INSERT INTO
    PetitionStatus (pid, date, content)
VALUES
    (3, '2016-11-02 00:00:00', 'WAITING E-SIGNATURES');


-- Petition signature 1
INSERT INTO
    PetitionSignature (pid, gender, firstName, lastName, address1, zipCode, city)
VALUES
    (3, 1, 'John', 'Do', '15, First Street', '123456', 'New York City');

-- Petition signature 2
INSERT INTO
    PetitionSignature (pid, gender, firstName, lastName, address1, zipCode, city)
VALUES
    (3, 0, 'Jane', 'Do', '15, First Street', '123456', 'New York City');



--
-- Petition with mailing list, statuses and no signatures
--
-- Petition 4
INSERT INTO
    Petition (author, title, content, startDate)
VALUES
    ('Théo Goudout', 'Mise en place d\'un septenat à mandat unique', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2017-01-01 00:00:00');


-- Petition status 1
INSERT INTO
    PetitionStatus (pid, date, content)
VALUES
    (4, '2016-09-01 00:00:00', 'VOTED');

-- Petition status 2
INSERT INTO
    PetitionStatus (pid, date, content)
VALUES
    (4, '2016-10-01 00:00:00', 'WRITTEN');

-- Petition status 3
INSERT INTO
    PetitionStatus (pid, date, content)
VALUES
    (4, '2016-11-01 00:00:00', 'PUBLISHED');

-- Petition status 4
INSERT INTO
    PetitionStatus (pid, date, content)
VALUES
    (4, '2016-11-02 00:00:00', 'WAITING E-SIGNATURES');


-- Petition mailing list 1
INSERT INTO
    PetitionMailingList (pid, email, enabled)
VALUES
    (4, 'john.do@gmail.com', 1);

-- Petition mailing list 2
INSERT INTO
    PetitionMailingList (pid, email, enabled)
VALUES
    (4, 'jane.do@gmail.com', 1);

-- Petition mailing list 3
INSERT INTO
    PetitionMailingList (pid, email, enabled)
VALUES
    (4, 'foo.bar@gmail.com', 0);


--
-- Petition with signatures
--
-- Petition 5
INSERT INTO
    Petition (author, title, content, startDate)
VALUES
    ('Théo Goudout', 'Comptabilisation du vote blanc', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2017-01-01 00:00:00');


-- Petition signature 1
INSERT INTO
    PetitionSignature (pid, gender, firstName, lastName, address1, zipCode, city)
VALUES
    (5, 1, 'John', 'Do', '15, First Street', '123456', 'New York City');

-- Petition signature 2
INSERT INTO
    PetitionSignature (pid, gender, firstName, lastName, address1, zipCode, city)
VALUES
    (5, 0, 'Jane', 'Do', '15, First Street', '123456', 'New York City');

--
-- Petition with mailing list
--
-- Petition 6
INSERT INTO
    Petition (author, title, content, startDate)
VALUES
    ('Théo Goudout', 'Suppression des retraites ministerielles', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2017-01-01 00:00:00');



-- Petition mailing list 1
INSERT INTO
    PetitionMailingList (pid, email, enabled)
VALUES
    (6, 'john.do@gmail.com', 1);

-- Petition mailing list 2
INSERT INTO
    PetitionMailingList (pid, email, enabled)
VALUES
    (6, 'jane.do@gmail.com', 1);

-- Petition mailing list 3
INSERT INTO
    PetitionMailingList (pid, email, enabled)
VALUES
    (6, 'foo.bar@gmail.com', 0);


--
-- Petition with statuses
--
-- Petition 7
INSERT INTO
    Petition (author, title, content, startDate)
VALUES
    ('Théo Goudout', 'Rendre le vote obligatoire', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2017-01-01 00:00:00');


-- Petition status 1
INSERT INTO
    PetitionStatus (pid, date, content)
VALUES
    (7, '2016-09-01 00:00:00', 'VOTED');

-- Petition status 2
INSERT INTO
    PetitionStatus (pid, date, content)
VALUES
    (7, '2016-10-01 00:00:00', 'WRITTEN');

-- Petition status 3
INSERT INTO
    PetitionStatus (pid, date, content)
VALUES
    (7, '2016-11-01 00:00:00', 'PUBLISHED');

-- Petition status 4
INSERT INTO
    PetitionStatus (pid, date, content)
VALUES
    (7, '2016-11-02 00:00:00', 'WAITING E-SIGNATURES');



--
-- Petition with nothing
--
-- Petition 8
INSERT INTO
    Petition (author, title, content, startDate)
VALUES
    ('Théo Goudout', 'Une pétition quelconque', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2017-01-01 00:00:00');

