DROP DATABASE IF EXISTS FriendFinder;

CREATE DATABASE FriendFinder;

USE FriendFinder;

CREATE TABLE IF NOT EXISTS Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registration_date DATE NOT NULL,
    pfpUrl VARCHAR(255) DEFAULT '/COP4813_FriendFinder/Backend/images/icons/pink-profile-icon.webp',
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    phone_number VARCHAR(20) UNIQUE,
    email VARCHAR(50) NOT NULL UNIQUE,
    pwd VARCHAR(255) NOT NULL,
    bio VARCHAR(3000),
    bio_approved BOOLEAN DEFAULT FALSE,
    account_active BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(50) NOT NULL UNIQUE,
    pwd VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Quiz_Questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prompt_text VARCHAR(500) NOT NULL UNIQUE,
    category VARCHAR(20) NOT NULL,
    meaning_of_1 VARCHAR(75) NOT NULL,
    meaning_of_5 VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Quiz_Responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    question_id INT NOT NULL,
    response INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES Quiz_Questions(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Quiz_Scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    sociability_score INT NOT NULL,
    adventurousness_score INT NOT NULL,
    reliability_score INT NOT NULL,
    athleticism_score INT NOT NULL,
    availability_score INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Matches (
    user1_id INT NOT NULL,
    user2_id INT NOT NULL,
    FOREIGN KEY (user1_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (user2_id) REFERENCES Users(id) ON DELETE CASCADE,
    PRIMARY KEY (user1_id, user2_id)
) ENGINE=InnoDB;


-- creating quiz questions

INSERT INTO Quiz_Questions (prompt_text, category, meaning_of_1, meaning_of_5)
VALUES
    ('On a scale of 1-5, how outgoing would you consider yourself?', 'Sociability', 'Very Introverted', 'Very Extroverted'),
    ('On a scale of 1-5, how much do you value having multiple acquantances versus a small circle of close friends?', 'Sociability', 'Strongly Prefer a Few Best Friends', 'Strongly Prefer a Wide Circle of Acquaintances'),
    ('On a scale of 1-5, how comfortable are you meeting new people in unfamiliar environments?', 'Sociability', 'Very Uncomfortable', 'Very Comfortable'),
    ('On a scale of 1-5, how likely are you to spend time with others rather than on your own, when you have free time?', 'Sociability', 'Very Unlikely', 'Very Likely'),
    ('On a scale of 1-5, how often do you seek out group activities, parties, or social events?', 'Sociability', 'Almost Never', 'Almost Always'),

    ('On a scale of 1-5, how spontaneous would you consider yourself?', 'Adventurousness', 'Very Calculating', 'Very Impulsive'),
    ('On a scale of 1-5, how much do you value trying new foods, hobbies, or activities versus sticking to what you know and enjoy?', 'Adventurousness', 'Strongly Prefer a Routine', 'Strongly Prefer the Unknown'),
    ('On a scale of 1-5, how comfortable are you with outdoor activities or extreme sports?', 'Adventurousness', 'Very Uncomfortable', 'Very Comfortable'),
    ('On a scale of 1-5, how likely are you to seek out experiences that push you out of your comfort zone?', 'Adventurousness', 'Very Unlikely', 'Very Likely'),
    ('On a scale of 1-5, how often do you like to explore exotic locations or travel to foreign places?', 'Adventurousness', 'Almost Never', 'Almost Always'),

    ('On a scale of 1-5, how consistent would you consider yourself?', 'Reliability', 'Very Undependable', 'Very Dependable'),
    ('On a scale of 1-5, how much do you value keeping your promises and commitments to others?', 'Reliability', 'Strongly Disregard Promises and Commitments', 'Strongly Prioritize Keeping Promises and Commitments'),
    ('On a scale of 1-5, how comfortable are you with following through on plans if something better comes up?', 'Reliability', 'Very Uncomfortable', 'Very Comfortable'),
    ('On a scale of 1-5, how likely are you to be on time for plans with friends?', 'Reliability', 'Very Unlikely', 'Very Likely'),
    ('On a scale of 1-5, how often do you respond to your friends’ calls or messages?', 'Reliability', 'Almost Never', 'Almost Always'),

    ('On a scale of 1-5, how sporty would you consider yourself?', 'Athleticism', 'Very Inactive', 'Very Active'),
    ('On a scale of 1-5, how much do you enjoy participating in competitive sports or games?', 'Athleticism', 'Strongly Dislike Rigorous Physical Activities', 'Strongly Enjoy Rigorous Physical Activities'),
    ('On a scale of 1-5, how comfortable are you with partaking in physical challenges that test your strength and endurance?', 'Athleticism', 'Very Uncomfortable', 'Very Comfortable'),
    ('On a scale of 1-5, how likely are you to suggest some sort of physical activity like hiking or biking when making plans with someone?', 'Athleticism', 'Very Unlikely', 'Very Likely'),
    ('On a scale of 1-5, how often do you engage in vigorous physical exercises or activities?', 'Athleticism', 'Almost Never', 'Almost Always'),

    ('On a scale of 1-5, how reachable would you consider yourself?', 'Availability', 'Very Occupied', 'Very Free'),
    ('On a scale of 1-5, how much notice do you typically need to make plans with people?', 'Availability', 'Strongly Prefer Weeks of Notice', 'Strongly Prefer Last-Minute Plans'),
    ('On a scale of 1-5, how comfortable are you with joining someone on spontaneous plans?', 'Availability', 'Very Uncomfortable', 'Very Comfortable'),
    ('On a scale of 1-5, how likely are you to have large periods of free time to dedicate to social activities?', 'Availability', 'Very Unlikely', 'Very Likely'),
    ('On a scale of 1-5, how often do you have days off from work/school/other responsibilities?', 'Availability', 'Almost Never', 'Almost Always');



-- CREATING FAKE DATA FOR PURPOSE OF PROTOTYPE

-- creating users
-- all users have password 1234

INSERT INTO Users (registration_date, first_name, last_name, phone_number, email, pwd, bio)
VALUES (
    '2025-07-01',
    'Larry',
    'Oppenheimer',
    '+1 (111) 111-1111',
    'larry.oppenheimer@randomsite.com',
    '$2y$10$ccn16b9/IDS.E5vPOLylR.XfAlRVoS4PCK8TjVQ02MCd2Xj1gK4OW',
    'I really like golf and want to meet some new friends.'
);

INSERT INTO Users (registration_date, first_name, last_name, phone_number, email, pwd, bio)
VALUES (
    '2025-07-01',
    'Barry',
    'Kraimer',
    '+2 (222) 222-2222',
    'barry.kraimer@randomsite.com',
    '$2y$10$ccn16b9/IDS.E5vPOLylR.XfAlRVoS4PCK8TjVQ02MCd2Xj1gK4OW',
    'I really like singing and want to meet some new friends.'
);

INSERT INTO Users (registration_date, first_name, last_name, phone_number, email, pwd, bio)
VALUES (
    '2025-07-03',
    'Alice',
    'Johnson',
    '+3 (333) 333-3333',
    'alice.johnson@randomsite.com',
    '$2y$10$ccn16b9/IDS.E5vPOLylR.XfAlRVoS4PCK8TjVQ02MCd2Xj1gK4OW',
    'Avid reader and coffee enthusiast.'
);

INSERT INTO Users (registration_date, first_name, last_name, phone_number, email, pwd, bio)
VALUES (
    '2025-07-04',
    'Bob',
    'Smith',
    '+4 (444) 444-4444',
    'bob.smith@randomsite.com',
    '$2y$10$ccn16b9/IDS.E5vPOLylR.XfAlRVoS4PCK8TjVQ02MCd2Xj1gK4OW',
    'Enjoys hiking and outdoor adventures.'
);

INSERT INTO Users (registration_date, first_name, last_name, phone_number, email, pwd, bio)
VALUES (
    '2024-07-05',
    'Cathy',
    'Lee',
    '+5 (555) 555-5555',
    'cathy.lee@randomsite.com',
    '$2y$10$ccn16b9/IDS.E5vPOLylR.XfAlRVoS4PCK8TjVQ02MCd2Xj1gK4OW',
    'Music lover and aspiring chef.'
);

INSERT INTO Users (registration_date, first_name, last_name, phone_number, email, pwd, bio)
VALUES (
    '2025-07-06',
    'David',
    'Nguyen',
    '+6 (666) 666-6666',
    'david.nguyen@randomsite.com',
    '$2y$10$ccn16b9/IDS.E5vPOLylR.XfAlRVoS4PCK8TjVQ02MCd2Xj1gK4OW',
    'Tech geek and gamer.'
);

INSERT INTO Users (registration_date, first_name, last_name, phone_number, email, pwd, bio)
VALUES (
    '2025-07-07',
    'Ella',
    'Martinez',
    '+7 (777) 777-7777',
    'ella.martinez@randomsite.com',
    '$2y$10$ccn16b9/IDS.E5vPOLylR.XfAlRVoS4PCK8TjVQ02MCd2Xj1gK4OW',
    'Yoga fan and healthy living advocate.'
);

INSERT INTO Users (registration_date, first_name, last_name, phone_number, email, pwd, bio)
VALUES (
    '2025-07-08',
    'Frank',
    'Wright',
    '+8 (888) 888-8888',
    'frank.wright@randomsite.com',
    '$2y$10$ccn16b9/IDS.E5vPOLylR.XfAlRVoS4PCK8TjVQ02MCd2Xj1gK4OW',
    'Movie buff and trivia master.'
);

INSERT INTO Users (registration_date, first_name, last_name, phone_number, email, pwd, bio)
VALUES (
    '2025-07-10',
    'Grace',
    'Kim',
    '+9 (999) 999-9999',
    'grace.kim@randomsite.com',
    '$2y$10$ccn16b9/IDS.E5vPOLylR.XfAlRVoS4PCK8TjVQ02MCd2Xj1gK4OW',
    'Dog lover and marathon runner.'
);

INSERT INTO Users (registration_date, first_name, last_name, phone_number, email, pwd, bio)
VALUES (
    '2025-07-12',
    'Henry',
    'Patel',
    '+10 (101) 010-1010',
    'henry.patel@randomsite.com',
    '$2y$10$ccn16b9/IDS.E5vPOLylR.XfAlRVoS4PCK8TjVQ02MCd2Xj1gK4OW',
    'Board game enthusiast.'
);

INSERT INTO Users (registration_date, first_name, last_name, phone_number, email, pwd, bio)
VALUES (
    '2025-07-12',
    'Ivy',
    'Chen',
    '+11 (202) 020-2020',
    'ivy.chen@randomsite.com',
    '$2y$10$ccn16b9/IDS.E5vPOLylR.XfAlRVoS4PCK8TjVQ02MCd2Xj1gK4OW',
    'Art student and museum goer.'
);

INSERT INTO Users (registration_date, first_name, last_name, phone_number, email, pwd, bio)
VALUES (
    '2025-07-13',
    'Jack',
    'Brown',
    '+12 (303) 030-3030',
    'jack.brown@randomsite.com',
    '$2y$10$ccn16b9/IDS.E5vPOLylR.XfAlRVoS4PCK8TjVQ02MCd2Xj1gK4OW',
    'Soccer player and pizza fan.'
);

INSERT INTO Users (registration_date, first_name, last_name, phone_number, email, pwd, bio)
VALUES (
    '2025-07-13',
    'Kara',
    'Singh',
    '+13 (404) 040-4040',
    'kara.singh@randomsite.com',
    '$2y$10$ccn16b9/IDS.E5vPOLylR.XfAlRVoS4PCK8TjVQ02MCd2Xj1gK4OW',
    'Travel blogger and photographer.'
);

INSERT INTO Users (registration_date, first_name, last_name, phone_number, email, pwd, bio)
VALUES (
    '2025-07-15',
    'Leo',
    'Garcia',
    '+14 (505) 050-5050',
    'leo.garcia@randomsite.com',
    '$2y$10$ccn16b9/IDS.E5vPOLylR.XfAlRVoS4PCK8TjVQ02MCd2Xj1gK4OW',
    'Cyclist and foodie.'
);

INSERT INTO Users (registration_date, first_name, last_name, phone_number, email, pwd, bio)
VALUES (
    '2025-07-15',
    'Mona',
    'Ali',
    '+15 (606) 060-6060',
    'mona.ali@randomsite.com',
    '$2y$10$ccn16b9/IDS.E5vPOLylR.XfAlRVoS4PCK8TjVQ02MCd2Xj1gK4OW',
    'Poet and tea drinker.'
);

INSERT INTO Users (registration_date, first_name, last_name, phone_number, email, pwd, bio)
VALUES (
    '2025-07-17',
    'Nate',
    'Evans',
    '+16 (707) 070-7070',
    'nate.evans@randomsite.com',
    '$2y$10$ccn16b9/IDS.E5vPOLylR.XfAlRVoS4PCK8TjVQ02MCd2Xj1gK4OW',
    'Basketball fan and sneakerhead.'
);

INSERT INTO Users (registration_date, first_name, last_name, phone_number, email, pwd, bio)
VALUES (
    '2025-07-17',
    'Olivia',
    'White',
    '+17 (808) 080-8080',
    'olivia.white@randomsite.com',
    '$2y$10$ccn16b9/IDS.E5vPOLylR.XfAlRVoS4PCK8TjVQ02MCd2Xj1gK4OW',
    'Gardener and animal lover.'
);

INSERT INTO Users (registration_date, first_name, last_name, phone_number, email, pwd, bio)
VALUES (
    '2025-07-19',
    'Paul',
    'Young',
    '+18 (909) 090-9090',
    'paul.young@randomsite.com',
    '$2y$10$ccn16b9/IDS.E5vPOLylR.XfAlRVoS4PCK8TjVQ02MCd2Xj1gK4OW',
    'Runner and podcast addict.'
);

INSERT INTO Users (registration_date, first_name, last_name, phone_number, email, pwd, bio)
VALUES (
    '2025-07-19',
    'Quinn',
    'Davis',
    '+19 (111) 111-2222',
    'quinn.davis@randomsite.com',
    '$2y$10$ccn16b9/IDS.E5vPOLylR.XfAlRVoS4PCK8TjVQ02MCd2Xj1gK4OW',
    'Chess player and coder.'
);

INSERT INTO Users (registration_date, first_name, last_name, phone_number, email, pwd, bio)
VALUES (
    '2025-07-20',
    'Rita',
    'Lopez',
    '+20 (222) 222-3333',
    'rita.lopez@randomsite.com',
    '$2y$10$ccn16b9/IDS.E5vPOLylR.XfAlRVoS4PCK8TjVQ02MCd2Xj1gK4OW',
    'Dancer and language learner.'
);

-- creating one valid admin account; password is password123

INSERT INTO Admins (email, pwd)
VALUES ('jeremysway@admins.com', '$2y$10$MfpFcx72kqsZ3GR.OdgXBOFHjGZjEwdDgMSVC8C0TEWhhyDNKpUyK');

-- creating quiz responses and scores

INSERT INTO Quiz_Responses (user_id, question_id, response)
VALUES
    (1, 1, 3),
    (1, 2, 4),
    (1, 3, 2),
    (1, 4, 5),
    (1, 5, 1),
    (1, 6, 2),
    (1, 7, 3),
    (1, 8, 4),
    (1, 9, 2),
    (1, 10, 5),
    (1, 11, 1),
    (1, 12, 3),
    (1, 13, 2),
    (1, 14, 4),
    (1, 15, 5),
    (1, 16, 2),
    (1, 17, 3),
    (1, 18, 4),
    (1, 19, 1),
    (1, 20, 5),
    (1, 21, 2),
    (1, 22, 3),
    (1, 23, 4),
    (1, 24, 1),
    (1, 25, 5);

INSERT INTO Quiz_Scores (user_id, sociability_score, adventurousness_score, reliability_score, athleticism_score, availability_score)
VALUES
    (1, 50, 55, 50, 50, 50);

INSERT INTO Quiz_Responses (user_id, question_id, response)
VALUES
    (2, 1, 2),
    (2, 2, 3),
    (2, 3, 4),
    (2, 4, 1),
    (2, 5, 5),
    (2, 6, 3),
    (2, 7, 2),
    (2, 8, 4),
    (2, 9, 1),
    (2, 10, 5),
    (2, 11, 2),
    (2, 12, 3),
    (2, 13, 4),
    (2, 14, 1),
    (2, 15, 5),
    (2, 16, 3),
    (2, 17, 2),
    (2, 18, 4),
    (2, 19, 1),
    (2, 20, 5),
    (2, 21, 3),
    (2, 22, 2),
    (2, 23, 4),
    (2, 24, 1),
    (2, 25, 5);

INSERT INTO Quiz_Scores (user_id, sociability_score, adventurousness_score, reliability_score, athleticism_score, availability_score)
VALUES
    (2, 50, 50, 50, 50, 50);

INSERT INTO Quiz_Responses (user_id, question_id, response)
VALUES
    (3, 1, 4),
    (3, 2, 2),
    (3, 3, 3),
    (3, 4, 5),
    (3, 5, 1),
    (3, 6, 4),
    (3, 7, 2),
    (3, 8, 3),
    (3, 9, 5),
    (3, 10, 1),
    (3, 11, 4),
    (3, 12, 2),
    (3, 13, 3),
    (3, 14, 5),
    (3, 15, 1),
    (3, 16, 4),
    (3, 17, 2),
    (3, 18, 3),
    (3, 19, 5),
    (3, 20, 1),
    (3, 21, 4),
    (3, 22, 2),
    (3, 23, 3),
    (3, 24, 5),
    (3, 25, 1);

INSERT INTO Quiz_Scores (user_id, sociability_score, adventurousness_score, reliability_score, athleticism_score, availability_score)
VALUES
    (3, 50, 50, 50, 50, 50);

INSERT INTO Quiz_Responses (user_id, question_id, response)
VALUES
    (4, 1, 1),
    (4, 2, 5),
    (4, 3, 2),
    (4, 4, 3),
    (4, 5, 4),
    (4, 6, 1),
    (4, 7, 5),
    (4, 8, 2),
    (4, 9, 3),
    (4, 10, 4),
    (4, 11, 1),
    (4, 12, 5),
    (4, 13, 2),
    (4, 14, 3),
    (4, 15, 4),
    (4, 16, 1),
    (4, 17, 5),
    (4, 18, 2),
    (4, 19, 3),
    (4, 20, 4),
    (4, 21, 1),
    (4, 22, 5),
    (4, 23, 2),
    (4, 24, 3),
    (4, 25, 4);

INSERT INTO Quiz_Scores (user_id, sociability_score, adventurousness_score, reliability_score, athleticism_score, availability_score)
VALUES
    (4, 50, 50, 50, 50, 50);

INSERT INTO Quiz_Responses (user_id, question_id, response)
VALUES
    (5, 1, 5),
    (5, 2, 1),
    (5, 3, 4),
    (5, 4, 2),
    (5, 5, 3),
    (5, 6, 5),
    (5, 7, 1),
    (5, 8, 4),
    (5, 9, 2),
    (5, 10, 3),
    (5, 11, 5),
    (5, 12, 1),
    (5, 13, 4),
    (5, 14, 2),
    (5, 15, 3),
    (5, 16, 5),
    (5, 17, 1),
    (5, 18, 4),
    (5, 19, 2),
    (5, 20, 3),
    (5, 21, 5),
    (5, 22, 1),
    (5, 23, 4),
    (5, 24, 2),
    (5, 25, 3);

INSERT INTO Quiz_Scores (user_id, sociability_score, adventurousness_score, reliability_score, athleticism_score, availability_score)
VALUES
    (5, 50, 50, 50, 50, 50);

INSERT INTO Quiz_Responses (user_id, question_id, response)
VALUES
    (6, 1, 3),
    (6, 2, 2),
    (6, 3, 5),
    (6, 4, 1),
    (6, 5, 4),
    (6, 6, 3),
    (6, 7, 2),
    (6, 8, 5),
    (6, 9, 1),
    (6, 10, 4),
    (6, 11, 3),
    (6, 12, 2),
    (6, 13, 5),
    (6, 14, 1),
    (6, 15, 4),
    (6, 16, 3),
    (6, 17, 2),
    (6, 18, 5),
    (6, 19, 1),
    (6, 20, 4),
    (6, 21, 3),
    (6, 22, 2),
    (6, 23, 5),
    (6, 24, 1),
    (6, 25, 4);

INSERT INTO Quiz_Scores (user_id, sociability_score, adventurousness_score, reliability_score, athleticism_score, availability_score)
VALUES
    (6, 50, 50, 50, 50, 50);

INSERT INTO Quiz_Responses (user_id, question_id, response)
VALUES
    (7, 1, 2),
    (7, 2, 4),
    (7, 3, 1),
    (7, 4, 3),
    (7, 5, 5),
    (7, 6, 2),
    (7, 7, 4),
    (7, 8, 1),
    (7, 9, 3),
    (7, 10, 5),
    (7, 11, 2),
    (7, 12, 4),
    (7, 13, 1),
    (7, 14, 3),
    (7, 15, 5),
    (7, 16, 2),
    (7, 17, 4),
    (7, 18, 1),
    (7, 19, 3),
    (7, 20, 5),
    (7, 21, 2),
    (7, 22, 4),
    (7, 23, 1),
    (7, 24, 3),
    (7, 25, 5);

INSERT INTO Quiz_Scores (user_id, sociability_score, adventurousness_score, reliability_score, athleticism_score, availability_score)
VALUES
    (7, 50, 50, 50, 50, 50);

INSERT INTO Quiz_Responses (user_id, question_id, response)
VALUES
    (8, 1, 4),
    (8, 2, 3),
    (8, 3, 2),
    (8, 4, 5),
    (8, 5, 1),
    (8, 6, 4),
    (8, 7, 3),
    (8, 8, 2),
    (8, 9, 5),
    (8, 10, 1),
    (8, 11, 4),
    (8, 12, 3),
    (8, 13, 2),
    (8, 14, 5),
    (8, 15, 1),
    (8, 16, 4),
    (8, 17, 3),
    (8, 18, 2),
    (8, 19, 5),
    (8, 20, 1),
    (8, 21, 4),
    (8, 22, 3),
    (8, 23, 2),
    (8, 24, 5),
    (8, 25, 1);

INSERT INTO Quiz_Scores (user_id, sociability_score, adventurousness_score, reliability_score, athleticism_score, availability_score)
VALUES
    (8, 50, 50, 50, 50, 50);

INSERT INTO Quiz_Responses (user_id, question_id, response)
VALUES
    (9, 1, 1),
    (9, 2, 2),
    (9, 3, 3),
    (9, 4, 4),
    (9, 5, 5),
    (9, 6, 1),
    (9, 7, 2),
    (9, 8, 3),
    (9, 9, 4),
    (9, 10, 5),
    (9, 11, 1),
    (9, 12, 2),
    (9, 13, 3),
    (9, 14, 4),
    (9, 15, 5),
    (9, 16, 1),
    (9, 17, 2),
    (9, 18, 3),
    (9, 19, 4),
    (9, 20, 5),
    (9, 21, 1),
    (9, 22, 2),
    (9, 23, 3),
    (9, 24, 4),
    (9, 25, 5);

INSERT INTO Quiz_Scores (user_id, sociability_score, adventurousness_score, reliability_score, athleticism_score, availability_score)
VALUES
    (9, 50, 50, 50, 50, 50);

INSERT INTO Quiz_Responses (user_id, question_id, response)
VALUES
    (10, 1, 5),
    (10, 2, 4),
    (10, 3, 3),
    (10, 4, 2),
    (10, 5, 1),
    (10, 6, 5),
    (10, 7, 4),
    (10, 8, 3),
    (10, 9, 2),
    (10, 10, 1),
    (10, 11, 5),
    (10, 12, 4),
    (10, 13, 3),
    (10, 14, 2),
    (10, 15, 1),
    (10, 16, 5),
    (10, 17, 4),
    (10, 18, 3),
    (10, 19, 2),
    (10, 20, 1),
    (10, 21, 5),
    (10, 22, 4),
    (10, 23, 3),
    (10, 24, 2),
    (10, 25, 1);

INSERT INTO Quiz_Scores (user_id, sociability_score, adventurousness_score, reliability_score, athleticism_score, availability_score)
VALUES
    (10, 50, 50, 50, 50, 50);


-- creating matches

INSERT INTO Matches (user1_id, user2_id)
VALUES
    (1, 2),
    (1, 3),
    (1, 4),
    (2, 3),
    (2, 5),
    (3, 4),
    (3, 6),
    (4, 5),
    (4, 7),
    (5, 6),
    (5, 8),
    (6, 7),
    (6, 9),
    (7, 8),
    (7, 10),
    (8, 9),
    (8, 11),
    (9, 10),
    (9, 12),
    (10, 11),
    (10, 13),
    (11, 12),
    (11, 14),
    (12, 13),
    (12, 15),
    (13, 14),
    (13, 16),
    (14, 15),
    (14, 17),
    (15, 16),
    (15, 18),
    (16, 17),
    (16, 19),
    (17, 18),
    (17, 20),
    (18, 19),
    (19, 20);