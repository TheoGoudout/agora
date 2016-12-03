--
-- Poll with answers and votes
--
-- Poll 1
INSERT INTO
    Poll (author, content, startDate, endDate)
VALUES
    ('Théo Goudout', 'Vote pour la pétition de Janvier 2017', '2016-10-01 00:00:00', '2016-11-30 23:59:59');


-- Poll Answer 1
INSERT INTO
    PollAnswer (pid, author, content)
VALUES
    (1, 'Théo Goudout', 'Interdiction du cumul des mandats éléctoraux');

-- Poll Vote 1
INSERT INTO
    PollVote (pid, aid, ipAddress, validationValue, validationStatus)
VALUES
    (1, 1, '192.168.0.1', 0, 1);

-- Poll Vote 2
INSERT INTO
    PollVote (pid, aid, ipAddress, validationValue, validationStatus)
VALUES
    (1, 1, '192.168.0.2', 0, 1);


-- Poll Answer 2
INSERT INTO
    PollAnswer (pid, author, content)
VALUES
    (1, 'Théo Goudout', 'Utilisation d\'un système de vote pondéré');

-- Poll Vote 3
INSERT INTO
    PollVote (pid, aid, ipAddress, validationValue, validationStatus)
VALUES
    (1, 2, '192.168.0.3', 0, 1);


-- Poll Answer 3
INSERT INTO
    PollAnswer (pid, author, content)
VALUES
    (1, 'Théo Goudout', 'Interdiction au détenteurs d\'un casier judiciaire d\'effectuer un mandat éléctoral');

-- Poll Vote 4
INSERT INTO
    PollVote (pid, aid, ipAddress, validationValue, validationStatus)
VALUES
    (1, 3, '192.168.0.4', 0, 1);


-- Poll Answer 4
INSERT INTO
    PollAnswer (pid, author, content)
VALUES
    (1, 'Théo Goudout', 'Mise en place d\'un septenat à mandat unique');

-- Poll Vote 5
INSERT INTO
    PollVote (pid, aid, ipAddress, validationValue, validationStatus)
VALUES
    (1, 4, '192.168.0.5', 0, 1);


-- Poll Answer 5
INSERT INTO
    PollAnswer (pid, author, content)
VALUES
    (1, 'Théo Goudout', 'Comptabilisation du vote blanc');

-- Poll Vote 6
INSERT INTO
    PollVote (pid, aid, ipAddress, validationValue, validationStatus)
VALUES
    (1, 5, '192.168.0.6', 0, 1);

-- Poll Vote 7
INSERT INTO
    PollVote (pid, aid, ipAddress, validationValue, validationStatus)
VALUES
    (1, 5, '192.168.0.7', 0, 1);

-- Poll Vote 8
INSERT INTO
    PollVote (pid, aid, ipAddress, validationValue, validationStatus)
VALUES
    (1, 5, '192.168.0.8', 0, 1);


-- Poll Answer 6
INSERT INTO
    PollAnswer (pid, author, content)
VALUES
    (1, 'Théo Goudout', 'Rendre le vote obligatoire');

-- Poll Vote 9
INSERT INTO
    PollVote (pid, aid, ipAddress, validationValue, validationStatus)
VALUES
    (1, 6, '192.168.0.9', 0, 1);

-- Poll Vote 10
INSERT INTO
    PollVote (pid, aid, ipAddress, validationValue, validationStatus)
VALUES
    (1, 6, '192.168.0.10', 0, 1);



--
-- Poll with answers and no votes
--
-- Poll 2
INSERT INTO
    Poll (author, content, startDate, endDate)
VALUES
    ('Théo Goudout', 'Vote pour la pétition de Mars 2017', '2016-12-01 00:00:00', '2017-01-31 23:59:59');


-- Poll Answer 7
INSERT INTO
    PollAnswer (pid, author, content)
VALUES
    (2, 'Théo Goudout', 'Interdiction du cumul des mandats éléctoraux');


-- Poll Answer 8
INSERT INTO
    PollAnswer (pid, author, content)
VALUES
    (2, 'Théo Goudout', 'Utilisation d\'un système de vote pondéré');


-- Poll Answer 9
INSERT INTO
    PollAnswer (pid, author, content)
VALUES
    (2, 'Théo Goudout', 'Interdiction au détenteurs d\'un casier judiciaire d\'effectuer un mandat éléctoral');


-- Poll Answer 10
INSERT INTO
    PollAnswer (pid, author, content)
VALUES
    (2, 'Théo Goudout', 'Mise en place d\'un septenat à mandat unique');

-- Poll Answer 11
INSERT INTO
    PollAnswer (pid, author, content)
VALUES
    (2, 'Théo Goudout', 'Rendre le vote obligatoire');


--
-- Poll with no answer and no vote
--
-- Poll 3
INSERT INTO
    Poll (author, content, startDate, endDate)
VALUES
    ('Théo Goudout', 'Vote pour la pétition de Mai 2017', '2017-02-01 00:00:00', '2017-03-31 23:59:59');
