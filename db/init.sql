-- initialization

DROP DATABASE IF EXISTS `UniCook`;
CREATE DATABASE `UniCook`;

USE `UniCook`;

DROP USER IF EXISTS 'unicook_appuser'@'%';
CREATE USER 'unicook_appuser'@'%' IDENTIFIED BY 'unicook_app_user_passwd!';
GRANT SELECT, INSERT, UPDATE ON * TO 'unicook_appuser'@'%';

-- tables

-- The deleted field is present to not give delete permissions to the user

DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users` (
    `ID` CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    `Username` VARCHAR(255) UNIQUE NOT NULL,
    `Email` VARCHAR(255) UNIQUE NOT NULL,
    `PasswordHash` CHAR(128) NOT NULL,
    `AvatarID` CHAR(36),
    `Admin` BOOL NOT NULL DEFAULT false,
    `CreatedAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `Deleted` BOOLEAN NOT NULL DEFAULT false
);

DROP TABLE IF EXISTS `LoginAttempts`;
CREATE TABLE `LoginAttempts` (
    `ID` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `UserID` CHAR(36) NOT NULL,
    `Timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS `LoginSessions`;
CREATE TABLE `LoginSessions` (
    `ID` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `UserID` CHAR(36) NOT NULL,
    `Key` CHAR(36) UNIQUE NOT NULL DEFAULT (UUID()),
    `ExpiresAt` TIMESTAMP NOT NULL,
    `ForceExpired` BOOLEAN NOT NULL DEFAULT false
);

DROP TABLE IF EXISTS `Tags`;
CREATE TABLE `Tags` (
    `ID` CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    `Name` VARCHAR(20) NOT NULL
);

DROP TABLE IF EXISTS `Recipes`;
CREATE TABLE `Recipes` (
    `ID` CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    `Title` VARCHAR(50) NOT NULL,
    `Description` TEXT,
    `PhotoID` CHAR(36),
    `Difficulty` INT NOT NULL,
    `CookingTime` INT NOT NULL,
    `Servings` INT NOT NULL,
    `UserID` CHAR(36) NOT NULL,
    `CreatedAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `Deleted` BOOLEAN NOT NULL DEFAULT false
);

DROP TABLE IF EXISTS `RecipeSteps`;
CREATE TABLE `RecipeSteps` (
    `RecipeID` CHAR(36) NOT NULL,
    `StepNumber` INT NOT NULL,
    `Instruction` TEXT NOT NULL,
    `PhotoID` CHAR(36),
    PRIMARY KEY (`RecipeID`, `StepNumber`)
);

DROP TABLE IF EXISTS `RecipeIngredients`;
CREATE TABLE `RecipeIngredients` (
    `RecipeID` CHAR(36) NOT NULL,
    `IngredientID` INT NOT NULL,
    `Name` VARCHAR(50) NOT NULL,
    `Quantity` VARCHAR(50) NOT NULL,
    `BarCode` VARCHAR(20),
    PRIMARY KEY (`RecipeID`, `IngredientID`)
);

DROP TABLE IF EXISTS `RecipeTags`;
CREATE TABLE `RecipeTags` (
    `RecipeID` CHAR(36) NOT NULL,
    `TagID` CHAR(36) NOT NULL,
    PRIMARY KEY (`RecipeID`, `TagID`)
);

DROP TABLE IF EXISTS `Reviews`;
CREATE TABLE `Reviews` (
    `ID` CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    `UserID` CHAR(36) NOT NULL,
    `RecipeID` CHAR(36) NOT NULL,
    `Rating` INT NOT NULL,
    `Body` TEXT,
    `CreatedAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `Deleted` BOOLEAN NOT NULL DEFAULT false,
    CHECK (`Rating` >= 0 AND `Rating` <= 5)
);

DROP TABLE IF EXISTS `RecipeLikes`;
CREATE TABLE `RecipeLikes` (
    `UserID` CHAR(36) NOT NULL,
    `RecipeID` CHAR(36) NOT NULL,
    `Deleted` BOOLEAN NOT NULL DEFAULT false,
    PRIMARY KEY (`RecipeID`, `UserID`)
);

ALTER TABLE `LoginAttempts`
ADD FOREIGN KEY (`UserID`) REFERENCES `Users`(`ID`);

ALTER TABLE `LoginSessions`
ADD FOREIGN KEY (`UserID`) REFERENCES `Users`(`ID`);

ALTER TABLE `Recipes`
ADD FOREIGN KEY (`UserID`) REFERENCES `Users`(`ID`);

ALTER TABLE `RecipeSteps`
ADD FOREIGN KEY (`RecipeID`) REFERENCES `Recipes`(`ID`);

ALTER TABLE `RecipeIngredients`
ADD FOREIGN KEY (`RecipeID`) REFERENCES `Recipes`(`ID`);

ALTER TABLE `Reviews`
ADD FOREIGN KEY (`UserID`) REFERENCES `Users`(`ID`);
ALTER TABLE `Reviews`
ADD FOREIGN KEY (`RecipeID`) REFERENCES `Recipes`(`ID`);

ALTER TABLE `RecipeLikes`
ADD FOREIGN KEY (`UserID`) REFERENCES `Users`(`ID`);
ALTER TABLE `RecipeLikes`
ADD FOREIGN KEY (`RecipeID`) REFERENCES `Recipes`(`ID`);

ALTER TABLE `RecipeTags`
ADD FOREIGN KEY (`RecipeID`) REFERENCES `Recipes`(`ID`);
ALTER TABLE `RecipeTags`
ADD FOREIGN KEY (`TagID`) REFERENCES `Tags`(`ID`);

-- data

-- user passwords are <email part before @>123!
-- e.g. mario.rossi@gmail.com => mario.rossi123!
INSERT INTO `Users`(
    `ID`, 
    `Username`, 
    `Email`, 
    `PasswordHash`, 
    `AvatarID`, 
    `Admin`, 
    `CreatedAt`, 
    `Deleted`
) VALUES
(
    'a3f5c8d1-4b2e-4a1c-9f3d-7e8b2c4a6d1f', 
    'marco.rossi', 
    'marco.rossi@email.it', 
    '$2y$10$Ns2nFX4rYeGH5ylRi7Hb6e6AUr6IemUimByopvZL.caKfkOXQzQgm', 
    '9e37fb91-8aed-45f3-89f2-7c33a54bbdac', 
    true, 
    '2024-01-15 10:30:00', 
    false
),
(
    'c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f', 
    'giulia.bianchi', 
    'giulia.bianchi@posta.it', 
    '$2y$10$JSXkD94ndydfeD/YPlGhHO00nICiIPiWKrzWtLhC7ospvpr9Qo9fS', 
    '7c7da984-a24d-4934-9283-00cedff31138', 
    false, 
    '2024-02-20 14:45:00', 
    false
),
(
    '1f2e3d4c-5b6a-7f8e-9d0c-1b2a3f4e5d6c', 
    'luca.ferrari', 
    'luca.ferrari@libero.it', 
    '$2y$10$wjHMd743r6EBhXnY9WVsDOtycuxkNvzz1uCk.U91ZF1a.QBeIowwq', 
    NULL, 
    false, 
    '2024-03-10 09:15:00', 
    false
),
(
    '9e8d7c6b-5a4f-3e2d-1c0b-9a8f7e6d5c4b', 
    'alessandra.romano', 
    'alessandra.romano@gmail.com', 
    '$2y$10$0aiDlSjIIs//jGwLrrSIKus8drgqtmTYj3nO9COAzsA0al6o7GziS', 
    NULL, 
    false, 
    '2024-04-05 16:20:00', 
    false
),
(
    '6d7e8f9a-0b1c-2d3e-4f5a-6b7c8d9e0f1a', 
    'francesco.colombo', 
    'f.colombo@virgilio.it', 
    '$2y$10$5xsF1dxomLUUKwmg9ZJGbOGl6X0ifL3LYfF/7UcEEzV/XjRtESiwq', 
    NULL, 
    false, 
    '2024-05-12 11:00:00', 
    false
),
(
    '4b5c6d7e-8f9a-0b1c-2d3e-4f5a6b7c8d9e', 
    'sara.ricci', 
    'sara.ricci@hotmail.it', 
    '$2y$10$e6UbdAaouJeQ7vPZc6F8fO4fsg/TZqF4uiGgomkFpoeUduXiICxvu', 
    NULL, 
    false, 
    '2024-06-18 08:30:00', 
    false
),
(
    '2e3f4a5b-6c7d-8e9f-0a1b-2c3d4e5f6a7b', 
    'andrea.moretti', 
    'andrea.moretti@email.it', 
    '$2y$10$T83iELMalutiYeWiyqcfOO0FswdYO492MWfuv/UEe2VSrkj65Oc8a', 
    NULL, 
    false, 
    '2024-07-22 13:45:00', 
    false
),
(
    '8d9e0f1a-2b3c-4d5e-6f7a-8b9c0d1e2f3a', 
    'chiara.gallo', 
    'chiara.gallo@tiscali.it', 
    '$2y$10$eGkPiTKjm0CdQKQQfexTY.l0fkE0oC9jLQMeZKqkvwsh.dkfmy4ZC', 
    NULL, 
    false, 
    '2024-08-30 15:10:00', 
    false
),
(
    '0f1a2b3c-4d5e-6f7a-8b9c-0d1e2f3a4b5c', 
    'matteo.conti', 
    'matteo.conti@alice.it', 
    '$2y$10$KJx0D5SrTIG9QrMOSqUJzutF6sN6kky1Q9z9YY.3yoT4wfv56xWB.', 
    NULL, 
    false, 
    '2024-09-14 10:25:00', 
    false
),
(
    '7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d', 
    'elena.greco', 
    'elena.greco@yahoo.it', 
    '$2y$10$do4df7cUbVISo7nTtNxgqeNwjAvKxOpbajLCrmowZODGsNP9Iawly', 
    NULL, 
    false, 
    '2024-10-08 12:50:00', 
    false
),
(
    '5c6d7e8f-9a0b-1c2d-3e4f-5a6b7c8d9e0f', 
    'davide.bruno', 
    'davide.bruno@outlook.it', 
    '$2y$10$PWtFPggoNrYcUqullnJNMeJHip4hX80pysKKP0k24zSlVna91lFZW', 
    NULL, 
    false, 
    '2024-11-20 09:40:00', 
    true
);
