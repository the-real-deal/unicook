DROP DATABASE IF EXISTS `UniCook`;
CREATE DATABASE `UniCook`;
USE `UniCook`;
DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users` (
    `ID` char(36) PRIMARY KEY,
    `Username` varchar(255) UNIQUE NOT NULL,
    `Email` varchar(255) UNIQUE NOT NULL,
    `Password` char(255) NOT NULL,
    `Avatar` blob,
    `Admin` bool NOT NULL DEFAULT false,
    `Deleted` bool NOT NULL DEFAULT false
);
DROP TABLE IF EXISTS `Recipes`;
CREATE TABLE `Recipes` (
    `ID` char(36) PRIMARY KEY,
    `Title` varchar(50) NOT NULL,
    `Description` text,
    `Difficulty` enum('Easy', 'Medium', 'Hard'),
    `CookingTime` int,
    `Servings` int,
    `UserID` char(36) NOT NULL,
    `CreatedAt` timestamp NOT NULL DEFAULT (CURRENT_TIMESTAMP),
    `Deleted` bool NOT NULL DEFAULT false
);
DROP TABLE IF EXISTS `RecipeSteps`;
CREATE TABLE `RecipeSteps` (
    `RecipeID` char(36) NOT NULL,
    `StepNumber` int NOT NULL,
    `Instruction` text NOT NULL,
    `Photo` blob,
    PRIMARY KEY (`RecipeID`, `StepNumber`)
);
DROP TABLE IF EXISTS `RecipeIngredients`;
CREATE TABLE `RecipeIngredients` (
    `RecipeID` char(36) NOT NULL,
    `IngredientID` integer NOT NULL,
    `Name` varchar(50) NOT NULL,
    `Quantity` varchar(50) NOT NULL,
    `BarCode` varchar(20),
    PRIMARY KEY (`RecipeID`, `IngredientID`)
);
DROP TABLE IF EXISTS `Reviews`;
CREATE TABLE `Reviews` (
    `ID` char(36) PRIMARY KEY,
    `UserID` char(36) NOT NULL,
    `RecipeID` char(36) NOT NULL,
    `Body` text NOT NULL,
    `Rating` int NOT NULL DEFAULT 0 COMMENT 'Valutazione da 1 a 5',
    `CreatedAt` timestamp NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);
DROP TABLE IF EXISTS `RecipePhotos`;
CREATE TABLE `RecipePhotos` (
    `RecipeID` char(36) NOT NULL,
    `ID` integer NOT NULL,
    `Photo` blob NOT NULL,
    PRIMARY KEY (`RecipeID`, `ID`)
);
DROP TABLE IF EXISTS `RecipeLikes`;
CREATE TABLE `RecipeLikes` (
    `UserID` char(36) NOT NULL,
    `RecipeID` char(36) NOT NULL,
    PRIMARY KEY (`RecipeID`, `UserID`)
);
DROP TABLE IF EXISTS `Tags`;
CREATE TABLE `Tags` (
    `ID` char(36) PRIMARY KEY,
    `Name` varchar(20) UNIQUE NOT NULL
);
DROP TABLE IF EXISTS `RecipeTags`;
CREATE TABLE `RecipeTags` (
    `RecipeID` char(36),
    `TagID` char(36),
    PRIMARY KEY (`RecipeID`, `TagID`)
);
ALTER TABLE `Recipes`
ADD FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`);
ALTER TABLE `RecipeSteps`
ADD FOREIGN KEY (`RecipeID`) REFERENCES `Recipes` (`ID`);
ALTER TABLE `RecipeIngredients`
ADD FOREIGN KEY (`RecipeID`) REFERENCES `Recipes` (`ID`);
ALTER TABLE `Reviews`
ADD FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`);
ALTER TABLE `Reviews`
ADD FOREIGN KEY (`RecipeID`) REFERENCES `Recipes` (`ID`);
ALTER TABLE `RecipePhotos`
ADD FOREIGN KEY (`RecipeID`) REFERENCES `Recipes` (`ID`);
ALTER TABLE `RecipeLikes`
ADD FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`);
ALTER TABLE `RecipeLikes`
ADD FOREIGN KEY (`RecipeID`) REFERENCES `Recipes` (`ID`);
ALTER TABLE `RecipeTags`
ADD FOREIGN KEY (`RecipeID`) REFERENCES `Recipes` (`ID`);
ALTER TABLE `RecipeTags`
ADD FOREIGN KEY (`TagID`) REFERENCES `Tags` (`ID`);