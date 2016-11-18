--
-- Remove all previous tables
--
DROP TABLE IF EXISTS PetitionStatus;
DROP TABLE IF EXISTS PetitionSignature;
DROP TABLE IF EXISTS PetitionMailingList;
DROP TABLE IF EXISTS Petition;

DROP TABLE IF EXISTS PollVote;
DROP TABLE IF EXISTS PollAnswer;
DROP TABLE IF EXISTS Poll;

--
-- Create tables
--
source poll.sql;
source pollanswer.sql;
source pollvote.sql;

source petition.sql;
source petitionmailinglist.sql;
source petitionsignature.sql;
source petitionstatus.sql;
