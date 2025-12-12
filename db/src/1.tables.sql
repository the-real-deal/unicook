USE `UniCook`;

-- The deleted field is to prevent giving delete permissions to the user

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
