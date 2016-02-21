/*
  news_aggregator.sql - first version of News Aggregator sql statements to created db tables
 
  CASCADES: In order to avoid orphaned records in deletion of a Survey, we'll want to get rid of the associated Q & A, etc. 
  We therefore want a 'cascading delete' in which the deletion of a Survey activates a 'cascade' of deletions in an 
  associated table.  Here's what the syntax looks like:  
  
  FOREIGN KEY (SurveyID) REFERENCES news_surveys(SurveyID) ON DELETE CASCADE
    
*/


SET foreign_key_checks = 0; #turn off constraints temporarily

#since constraints cause problems, drop tables first, working backward
DROP TABLE IF EXISTS news_categories;
DROP TABLE IF EXISTS news_feeds;
DROP TABLE IF EXISTS news_list;
  
#all tables must be of type InnoDB to do transactions, foreign key constraints
CREATE TABLE news_categories(
ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
Title VARCHAR(255) DEFAULT '',
PRIMARY KEY (ID)
)ENGINE=INNODB; 

#assigning first three categories to news_categories TABLE
INSERT INTO news_categories VALUES (NULL,'Science'); 
INSERT INTO news_categories VALUES (NULL,'Construction Safety'); 
INSERT INTO news_categories VALUES (NULL,'Technology'); 

#foreign key field must match size and type, hence CategoryID is INT UNSIGNED
CREATE TABLE news_feeds(
ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
CategoryID INT UNSIGNED DEFAULT 0,
Title VARCHAR(255) DEFAULT '',
PRIMARY KEY (ID),
INDEX CategoryID_index(CategoryID),
FOREIGN KEY (CategoryID) REFERENCES news_categories(ID) ON DELETE CASCADE
)ENGINE=INNODB;

INSERT INTO news_feeds VALUES (NULL,1,'NASA');
INSERT INTO news_feeds VALUES (NULL,1,'Albert Einstein');
INSERT INTO news_feeds VALUES (NULL,1,'Mars');
INSERT INTO news_feeds VALUES (NULL,2,'Accidents');
INSERT INTO news_feeds VALUES (NULL,2,'Law');
INSERT INTO news_feeds VALUES (NULL,2,'Training');

CREATE TABLE news_list(
ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
CategoryID INT UNSIGNED DEFAULT 0,
FeedID INT UNSIGNED DEFAULT 0,
Title TEXT DEFAULT '',
Description TEXT DEFAULT '',
URL TEXT DEFAULT '',
DateAdded DATETIME,
LastUpdated TIMESTAMP DEFAULT 0 ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (ID),
INDEX CategoryID_index(CategoryID),
INDEX FeedID_index(FeedID),
FOREIGN KEY (CategoryID) REFERENCES news_categories(ID) ON DELETE CASCADE,
FOREIGN KEY (FeedID) REFERENCES news_feeds(ID) ON DELETE CASCADE
)ENGINE=INNODB;

SET foreign_key_checks = 1; #turn foreign key check back on