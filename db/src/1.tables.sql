USE `UniCook`;

DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users` (
    `ID` CHAR(36) PRIMARY KEY,
    `Username` VARCHAR(255) UNIQUE NOT NULL,
    `Email` VARCHAR(255) UNIQUE NOT NULL,
    `Password` CHAR(128) NOT NULL,
    `Salt` CHAR(128) NOT NULL,
    `AvatarID` CHAR(36),
    `Admin` BOOL NOT NULL DEFAULT false,
    `Deleted` BOOLEAN NOT NULL DEFAULT false
);

DROP TABLE IF EXISTS `LoginAttempts`;
CREATE TABLE `LoginAttempts` (
    `UserID` CHAR(36) NOT NULL,
    `Timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (`UserID`, `Timestamp`)
);

DROP TABLE IF EXISTS `Tags`;
CREATE TABLE `Tags` (
    `ID` CHAR(36) PRIMARY KEY,
    `Name` VARCHAR(20) NOT NULL
);

DROP TABLE IF EXISTS `Recipes`;
CREATE TABLE `Recipes` (
    `ID` CHAR(36) PRIMARY KEY,
    `Title` VARCHAR(50) NOT NULL,
    `Description` TEXT,
    `Difficulty` ENUM('Easy', 'Medium', 'Hard') NOT NULL,
    `CookingTime` INT NOT NULL,
    `Servings` INT NOT NULL,
    `UserID` CHAR(36) NOT NULL,
    `CreatedAt` DATETIME NOT NULL DEFAULT (NOW()),
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
Email
DROP TABLE IF EXISTS `RecipePhotos`;
CREATE TABLE `RecipePhotos` (
    `RecipeID` CHAR(36) NOT NULL,
    `ID` INT NOT NULL,
    `PhotoID` CHAR(36),
    PRIMARY KEY (`RecipeID`, `ID`)
);

DROP TABLE IF EXISTS `RecipeTags`;
CREATE TABLE `RecipeTags` (
    `RecipeID` CHAR(36) NOT NULL,
    `TagID` CHAR(36) NOT NULL,
    PRIMARY KEY (`RecipeID`, `TagID`)
);

DROP TABLE IF EXISTS `Reviews`;
CREATE TABLE `Reviews` (
    `ID` CHAR(36) PRIMARY KEY,
    `UserID` CHAR(36) NOT NULL,
    `RecipeID` CHAR(36) NOT NULL,
    `Rating` INT NOT NULL,
    `Body` TEXT,
    `CreatedAt` DATETIME NOT NULL DEFAULT (NOW()),
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

ALTER TABLE `RecipePhotos`
ADD FOREIGN KEY (`RecipeID`) REFERENCES `Recipes`(`ID`);

ALTER TABLE `RecipeLikes`
ADD FOREIGN KEY (`UserID`) REFERENCES `Users`(`ID`);
ALTER TABLE `RecipeLikes`
ADD FOREIGN KEY (`RecipeID`) REFERENCES `Recipes`(`ID`);

ALTER TABLE `RecipeTags`
ADD FOREIGN KEY (`RecipeID`) REFERENCES `Recipes`(`ID`);
ALTER TABLE `RecipeTags`
ADD FOREIGN KEY (`TagID`) REFERENCES `Tags`(`ID`);
