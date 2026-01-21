-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Jan 21, 2026 at 09:36 PM
-- Server version: 8.4.7
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DROP DATABASE IF EXISTS `UniCook`;
CREATE DATABASE `UniCook`;
USE `UniCook`;
DROP USER IF EXISTS 'unicook_appuser' @'%';
CREATE USER 'unicook_appuser' @'%' IDENTIFIED BY 'unicook_app_user_passwd!';
GRANT ALL PRIVILEGES ON * TO 'unicook_appuser' @'%';
-- set timezone to UTC
SET time_zone = '+00:00';


-- --------------------------------------------------------

--
-- Table structure for table `AuthSessions`
--

DROP TABLE IF EXISTS `AuthSessions`;
CREATE TABLE `AuthSessions` (
  `id` char(36) NOT NULL,
  `keyHash` char(128) NOT NULL,
  `userId` char(36) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `RecipeIngredients`
--

DROP TABLE IF EXISTS `RecipeIngredients`;
CREATE TABLE `RecipeIngredients` (
  `recipeId` char(36) NOT NULL,
  `ingredientId` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `quantity` varchar(50) NOT NULL,
  `barcode` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `RecipeIngredients`
--

INSERT INTO `RecipeIngredients` (`recipeId`, `ingredientId`, `name`, `quantity`, `barcode`) VALUES
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 1, 'Quinoa', '1 cup', NULL),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 2, 'Sweet potato', '1 large', NULL),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 3, 'Chickpeas', '1 can (400g)', NULL),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 4, 'Broccoli', '1 head', NULL),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 5, 'Avocado', '1 ripe', NULL),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 6, 'Mixed greens', '2 cups', NULL),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 7, 'Tahini', '3 tbsp', NULL),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 8, 'Lemon juice', '2 tbsp', NULL),
('2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', 1, 'Spaghetti', '400g', NULL),
('2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', 2, 'Guanciale', '150g', NULL),
('2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', 3, 'Eggs', '4 large', NULL),
('2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', 4, 'Pecorino Romano cheese', '100g', NULL),
('2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', 5, 'Black pepper', 'to taste', NULL),
('4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 1, 'Pizza dough', '500g', NULL),
('4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 2, 'Tomato sauce', '200ml', NULL),
('4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 3, 'Fresh mozzarella', '250g', NULL),
('4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 4, 'Fresh basil', '1 bunch', NULL),
('4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 5, 'Extra virgin olive oil', '2 tbsp', NULL),
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 1, 'Fresh tomatoes', '3 large', NULL),
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 2, 'Fresh mozzarella', '250g', NULL),
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 3, 'Fresh basil', '1 bunch', NULL),
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 4, 'Extra virgin olive oil', '3 tbsp', NULL),
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 5, 'Balsamic glaze', '2 tbsp', NULL),
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 6, 'Salt', 'to taste', NULL),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 1, 'Pasta (fusilli or penne)', '400g', NULL),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 2, 'Fresh basil', '2 cups packed', NULL),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 3, 'Pine nuts', '50g', NULL),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 4, 'Garlic', '2 cloves', NULL),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 5, 'Parmesan cheese', '80g', NULL),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 6, 'Extra virgin olive oil', '100ml', NULL),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 1, 'Ladyfinger cookies', '300g', NULL),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 2, 'Mascarpone cheese', '500g', NULL),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 3, 'Eggs', '6 large', NULL),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 4, 'Sugar', '150g', NULL),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 5, 'Espresso coffee', '300ml', NULL),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 6, 'Cocoa powder', 'for dusting', NULL),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 7, 'Coffee liqueur', '2 tbsp (optional)', NULL),
('a1b2c3d4-5e6f-7a8b-9c0d-1e2f3a4b5c6d', 1, 'Spaghetti', '400g', NULL),
('a1b2c3d4-5e6f-7a8b-9c0d-1e2f3a4b5c6d', 2, 'Garlic', '6 cloves', NULL),
('a1b2c3d4-5e6f-7a8b-9c0d-1e2f3a4b5c6d', 3, 'Extra virgin olive oil', '100ml', NULL),
('a1b2c3d4-5e6f-7a8b-9c0d-1e2f3a4b5c6d', 4, 'Red pepper flakes', '1 tsp', NULL),
('a1b2c3d4-5e6f-7a8b-9c0d-1e2f3a4b5c6d', 5, 'Fresh parsley', '2 tbsp', NULL),
('a1b2c3d4-5e6f-7a8b-9c0d-1e2f3a4b5c6d', 6, 'Salt', 'to taste', NULL),
('a2b3c4d5-6e7f-8a9b-0c1d-2e3f4a5b6c7d', 1, 'Instant ramen noodles', '1 pack', NULL),
('a2b3c4d5-6e7f-8a9b-0c1d-2e3f4a5b6c7d', 2, 'Egg', '1 large', NULL),
('a2b3c4d5-6e7f-8a9b-0c1d-2e3f4a5b6c7d', 3, 'Frozen vegetables or spinach', '1/2 cup', NULL),
('a2b3c4d5-6e7f-8a9b-0c1d-2e3f4a5b6c7d', 4, 'Sesame oil', '1 tsp', NULL),
('a2b3c4d5-6e7f-8a9b-0c1d-2e3f4a5b6c7d', 5, 'Green onions', '1 stalk', NULL),
('a5b6c7d8-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 0, 'Ripe bananas', '2 large', NULL),
('a5b6c7d8-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 1, 'Eggs', '2 large', NULL),
('a5b6c7d8-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 2, 'Maple syrup', 'for serving', NULL),
('a6b7c8d9-0e1f-2a3b-4c5d-6e7f8a9b0c1d', 1, 'Pasta (any shape)', '400g', NULL),
('a6b7c8d9-0e1f-2a3b-4c5d-6e7f8a9b0c1d', 2, 'Canned crushed tomatoes', '400g', NULL),
('a6b7c8d9-0e1f-2a3b-4c5d-6e7f8a9b0c1d', 3, 'Garlic', '3 cloves', NULL),
('a6b7c8d9-0e1f-2a3b-4c5d-6e7f8a9b0c1d', 4, 'Fresh basil', '1 bunch', NULL),
('a6b7c8d9-0e1f-2a3b-4c5d-6e7f8a9b0c1d', 5, 'Extra virgin olive oil', '3 tbsp', NULL),
('a6b7c8d9-0e1f-2a3b-4c5d-6e7f8a9b0c1d', 6, 'Salt', 'to taste', NULL),
('a9b0c1d2-3e4f-5a6b-7c8d-9e0f1a2b3c4d', 0, 'Greek yogurt', '200g', NULL),
('a9b0c1d2-3e4f-5a6b-7c8d-9e0f1a2b3c4d', 1, 'Honey', '1 tbsp', NULL),
('a9b0c1d2-3e4f-5a6b-7c8d-9e0f1a2b3c4d', 2, 'Mixed berries', '1/2 cup', NULL),
('a9b0c1d2-3e4f-5a6b-7c8d-9e0f1a2b3c4d', 3, 'Granola', '3 tbsp', NULL),
('a9b0c1d2-3e4f-5a6b-7c8d-9e0f1a2b3c4d', 4, 'Nuts', '2 tbsp', NULL),
('b1c2d3e4-5f6a-7b8c-9d0e-1f2a3b4c5d6e', 0, 'Rolled oats', '1/2 cup', NULL),
('b1c2d3e4-5f6a-7b8c-9d0e-1f2a3b4c5d6e', 1, 'Milk', '3/4 cup', NULL),
('b1c2d3e4-5f6a-7b8c-9d0e-1f2a3b4c5d6e', 2, 'Honey or maple syrup', '1 tbsp', NULL),
('b1c2d3e4-5f6a-7b8c-9d0e-1f2a3b4c5d6e', 3, 'Chia seeds', '1 tsp (optional)', NULL),
('b1c2d3e4-5f6a-7b8c-9d0e-1f2a3b4c5d6e', 4, 'Fresh fruit', 'for topping', NULL),
('b3c4d5e6-7f8a-9b0c-1d2e-3f4a5b6c7d8e', 0, 'Baguette or Italian bread', '1 loaf', NULL),
('b3c4d5e6-7f8a-9b0c-1d2e-3f4a5b6c7d8e', 1, 'Butter', '100g', NULL),
('b3c4d5e6-7f8a-9b0c-1d2e-3f4a5b6c7d8e', 2, 'Garlic', '4 cloves', NULL),
('b3c4d5e6-7f8a-9b0c-1d2e-3f4a5b6c7d8e', 3, 'Fresh parsley', '2 tbsp', NULL),
('c1d2e3f4-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 0, 'Canned tuna', '1 can (150g)', NULL),
('c1d2e3f4-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 1, 'Mayonnaise', '2 tbsp', NULL),
('c1d2e3f4-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 2, 'Bread', '4 slices', NULL),
('c1d2e3f4-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 3, 'Lettuce', '2 leaves', NULL),
('c1d2e3f4-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 4, 'Tomato', '1 medium', NULL),
('c1d2e3f4-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 5, 'Celery', '1 stalk', NULL),
('c2d3e4f5-6a7b-8c9d-0e1f-2a3b4c5d6e7f', 1, 'Cooked rice (day-old)', '3 cups', NULL),
('c2d3e4f5-6a7b-8c9d-0e1f-2a3b4c5d6e7f', 2, 'Eggs', '2 large', NULL),
('c2d3e4f5-6a7b-8c9d-0e1f-2a3b4c5d6e7f', 3, 'Mixed vegetables', '1 cup', NULL),
('c2d3e4f5-6a7b-8c9d-0e1f-2a3b4c5d6e7f', 4, 'Soy sauce', '2 tbsp', NULL),
('c2d3e4f5-6a7b-8c9d-0e1f-2a3b4c5d6e7f', 5, 'Sesame oil', '1 tsp', NULL),
('c2d3e4f5-6a7b-8c9d-0e1f-2a3b4c5d6e7f', 6, 'Vegetable oil', '2 tbsp', NULL),
('c2d3e4f5-6a7b-8c9d-0e1f-2a3b4c5d6e7f', 7, 'Green onions', '2 stalks', NULL),
('c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f', 0, 'Canned tomatoes', '400g', NULL),
('c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f', 1, 'Canned beans', '1 can (400g)', NULL),
('c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f', 2, 'Small pasta', '100g', NULL),
('c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f', 3, 'Vegetable broth', '4 cups', NULL),
('c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f', 4, 'Onion', '1 medium', NULL),
('c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f', 5, 'Carrot', '1 large', NULL),
('c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f', 6, 'Celery', '2 stalks', NULL),
('c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f', 7, 'Zucchini', '1 medium', NULL),
('c8d9e0f1-2a3b-4c5d-6e7f-8a9b0c1d2e3f', 1, 'Canned chickpeas', '1 can (400g)', NULL),
('c8d9e0f1-2a3b-4c5d-6e7f-8a9b0c1d2e3f', 2, 'Cucumber', '1 medium', NULL),
('c8d9e0f1-2a3b-4c5d-6e7f-8a9b0c1d2e3f', 3, 'Cherry tomatoes', '1 cup', NULL),
('c8d9e0f1-2a3b-4c5d-6e7f-8a9b0c1d2e3f', 4, 'Red onion', '1/4 small', NULL),
('c8d9e0f1-2a3b-4c5d-6e7f-8a9b0c1d2e3f', 5, 'Fresh parsley', '1/4 cup', NULL),
('c8d9e0f1-2a3b-4c5d-6e7f-8a9b0c1d2e3f', 6, 'Lemon juice', '2 tbsp', NULL),
('c8d9e0f1-2a3b-4c5d-6e7f-8a9b0c1d2e3f', 7, 'Olive oil', '2 tbsp', NULL),
('d5e6f7a8-9b0c-1d2e-3f4a-5b6c7d8e9f0a', 0, 'Bread', '2 slices', NULL),
('d5e6f7a8-9b0c-1d2e-3f4a-5b6c7d8e9f0a', 1, 'Ripe avocado', '1 large', NULL),
('d5e6f7a8-9b0c-1d2e-3f4a-5b6c7d8e9f0a', 2, 'Lemon juice', '1 tsp', NULL),
('d5e6f7a8-9b0c-1d2e-3f4a-5b6c7d8e9f0a', 3, 'Salt and pepper', 'to taste', NULL),
('e0f1a2b3-4c5d-6e7f-8a9b-0c1d2e3f4a5b', 1, 'Flour tortillas', '2 large', NULL),
('e0f1a2b3-4c5d-6e7f-8a9b-0c1d2e3f4a5b', 2, 'Shredded cheese', '1 cup', NULL),
('e0f1a2b3-4c5d-6e7f-8a9b-0c1d2e3f4a5b', 3, 'Butter or oil', '1 tbsp', NULL),
('e3f4a5b6-7c8d-9e0f-1a2b-3c4d5e6f7a8b', 0, 'Large tortilla', '2 wraps', NULL),
('e3f4a5b6-7c8d-9e0f-1a2b-3c4d5e6f7a8b', 1, 'Hummus', '1/2 cup', NULL),
('e3f4a5b6-7c8d-9e0f-1a2b-3c4d5e6f7a8b', 2, 'Cucumber', '1 medium', NULL),
('e3f4a5b6-7c8d-9e0f-1a2b-3c4d5e6f7a8b', 3, 'Carrot', '1 large', NULL),
('e3f4a5b6-7c8d-9e0f-1a2b-3c4d5e6f7a8b', 4, 'Bell pepper', '1/2 medium', NULL),
('e3f4a5b6-7c8d-9e0f-1a2b-3c4d5e6f7a8b', 5, 'Lettuce', '2 leaves', NULL),
('e4f5a6b7-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 1, 'Tomatoes', '3 medium', NULL),
('e4f5a6b7-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 2, 'Eggs', '4 large', NULL),
('e4f5a6b7-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 3, 'Sugar', '1 tsp', NULL),
('e4f5a6b7-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 4, 'Salt', 'to taste', NULL),
('e4f5a6b7-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 5, 'Vegetable oil', '2 tbsp', NULL),
('e9f0a1b2-3c4d-5e6f-7a8b-9c0d1e2f3a4b', 0, 'Canned black beans', '1 can (400g)', NULL),
('e9f0a1b2-3c4d-5e6f-7a8b-9c0d1e2f3a4b', 1, 'Soft tortillas', '6 small', NULL),
('e9f0a1b2-3c4d-5e6f-7a8b-9c0d1e2f3a4b', 2, 'Cumin', '1 tsp', NULL),
('e9f0a1b2-3c4d-5e6f-7a8b-9c0d1e2f3a4b', 3, 'Chili powder', '1 tsp', NULL),
('e9f0a1b2-3c4d-5e6f-7a8b-9c0d1e2f3a4b', 4, 'Tomato', '1 large', NULL),
('e9f0a1b2-3c4d-5e6f-7a8b-9c0d1e2f3a4b', 5, 'Lettuce', '1 cup shredded', NULL),
('e9f0a1b2-3c4d-5e6f-7a8b-9c0d1e2f3a4b', 6, 'Salsa', '1/2 cup', NULL),
('f7a8b9c0-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 0, 'Eggs', '4 large', NULL),
('f7a8b9c0-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 1, 'Canned tomatoes', '400g', NULL),
('f7a8b9c0-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 2, 'Onion', '1 medium', NULL),
('f7a8b9c0-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 3, 'Bell pepper', '1 medium', NULL),
('f7a8b9c0-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 4, 'Garlic', '2 cloves', NULL),
('f7a8b9c0-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 5, 'Cumin', '1 tsp', NULL),
('f7a8b9c0-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 6, 'Paprika', '1 tsp', NULL),
('f7a8b9c0-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 7, 'Olive oil', '2 tbsp', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Recipes`
--

DROP TABLE IF EXISTS `Recipes`;
CREATE TABLE `Recipes` (
  `id` char(36) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `photoId` char(36) NOT NULL,
  `difficulty` int NOT NULL,
  `prepTime` int NOT NULL,
  `cost` int NOT NULL,
  `servings` int NOT NULL,
  `userId` char(36) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Recipes`
--

INSERT INTO `Recipes` (`id`, `title`, `description`, `photoId`, `difficulty`, `prepTime`, `cost`, `servings`, `userId`, `createdAt`) VALUES
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 'Quinoa Buddha Bowl', 'A nutritious and colorful bowl packed with quinoa, roasted vegetables, chickpeas, avocado, and tahini dressing.', 'c29effc1-29d1-4fed-bbb1-d6ef07f17382', 1, 35, 2, 2, '9e8d7c6b-5a4f-3e2d-1c0b-9a8f7e6d5c4b', '2024-04-08 13:15:00'),
('2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', 'Spaghetti Carbonara', 'A traditional Roman pasta dish made with eggs, cheese, guanciale, and black pepper. Simple ingredients come together to create a creamy, delicious meal.', 'e9db1b6f-b5c2-4d09-8508-f364e33dcd87', 1, 25, 1, 4, 'a3f5c8d1-4b2e-4a1c-9f3d-7e8b2c4a6d1f', '2024-01-20 14:30:00'),
('4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 'Margherita Pizza', 'The classic Neapolitan pizza with tomato sauce, fresh mozzarella, basil, and extra virgin olive oil.', '67828596-42a6-460d-9c7f-a9602d52c188', 2, 30, 0, 4, '6d7e8f9a-0b1c-2d3e-4f5a-6b7c8d9e0f1a', '2024-05-20 18:00:00'),
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 'Caprese Salad', 'A fresh and vibrant Italian salad featuring ripe tomatoes, creamy mozzarella, fresh basil, and a drizzle of extra virgin olive oil.', 'e4144f13-3d8b-46a3-a8ad-0e1a6a0eb3d8', 0, 10, 0, 2, 'a3f5c8d1-4b2e-4a1c-9f3d-7e8b2c4a6d1f', '2024-02-15 11:20:00'),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 'Pesto Pasta', 'Quick and flavorful pasta tossed with homemade basil pesto, pine nuts, garlic, and Parmesan cheese.', '3b8a7b59-094a-49db-b432-bc21394f4cf6', 0, 20, 0, 4, '4b5c6d7e-8f9a-0b1c-2d3e-4f5a6b7c8d9e', '2024-06-25 12:30:00'),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 'Tiramisù', 'The iconic Italian dessert with layers of coffee-soaked ladyfingers and mascarpone cream, dusted with cocoa powder.', '4d3e2efe-6d27-4bc6-b094-3fd5374fa1a9', 2, 45, 1, 8, '1f2e3d4c-5b6a-7f8e-9d0c-1b2a3f4e5d6c', '2024-03-12 16:45:00'),
('a1b2c3d4-5e6f-7a8b-9c0d-1e2f3a4b5c6d', 'Aglio e Olio', 'Classic Italian pasta with garlic, olive oil, and chili flakes. Ready in 15 minutes with just a handful of pantry staples.', '32fa7462-752f-4d8a-89c9-2e7df3e558cd', 0, 15, 0, 4, 'a3f5c8d1-4b2e-4a1c-9f3d-7e8b2c4a6d1f', '2024-12-01 10:00:00'),
('a2b3c4d5-6e7f-8a9b-0c1d-2e3f4a5b6c7d', 'Instant Noodle Upgrade', 'Transform instant ramen into a real meal with eggs, vegetables, and sesame oil.', '28ef6e1a-2d33-4621-a6a6-07d0b00669aa', 0, 8, 0, 1, '2e3f4a5b-6c7d-8e9f-0a1b-2c3d4e5f6a7b', '2024-12-15 20:00:00'),
('a5b6c7d8-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 'Banana Pancakes', 'Two-ingredient pancakes made with just banana and eggs. Naturally sweet and gluten-free.', '0d6402c4-f789-4ec5-9bff-4a64729b24bd', 0, 12, 0, 2, 'c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f', '2024-12-30 09:00:00'),
('a6b7c8d9-0e1f-2a3b-4c5d-6e7f8a9b0c1d', 'Pasta al Pomodoro', 'The ultimate quick pasta with fresh tomato sauce, basil, and garlic. A staple of Italian home cooking.', '3a7b1031-0465-4921-ac4f-860e650b5a70', 0, 20, 0, 4, '9e8d7c6b-5a4f-3e2d-1c0b-9a8f7e6d5c4b', '2024-12-08 12:45:00'),
('a9b0c1d2-3e4f-5a6b-7c8d-9e0f1a2b3c4d', 'Greek Yogurt Bowl', 'Protein-rich breakfast with yogurt, honey, nuts, and fresh fruit.', '3c7898cb-782c-40d1-bca9-f1c7ad6dd614', 0, 5, 0, 1, '5c6d7e8f-9a0b-1c2d-3e4f-5a6b7c8d9e0f', '2024-12-24 07:45:00'),
('b1c2d3e4-5f6a-7b8c-9d0e-1f2a3b4c5d6e', 'Overnight Oats', 'No-cook breakfast prepared the night before. Creamy oats with your favorite toppings.', '2e0ca962-c817-434d-af13-da65da7a8063', 0, 5, 0, 1, '6d7e8f9a-0b1c-2d3e-4f5a-6b7c8d9e0f1a', '2025-01-08 21:00:00'),
('b3c4d5e6-7f8a-9b0c-1d2e-3f4a5b6c7d8e', 'Garlic Bread', 'Crispy, buttery bread with garlic and herbs. Perfect as a side dish or snack.', 'a8cfc488-4089-4c62-8b16-92589fa01bad', 0, 10, 0, 4, '8d9e0f1a-2b3c-4d5e-6f7a-8b9c0d1e2f3a', '2024-12-18 18:00:00'),
('c1d2e3f4-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 'Tuna Sandwich', 'Classic sandwich with canned tuna, mayo, and crisp lettuce. Perfect for lunch.', 'a1712d0f-27f3-4952-98aa-aefeb215d8a9', 0, 8, 0, 2, '2e3f4a5b-6c7d-8e9f-0a1b-2c3d4e5f6a7b', '2024-12-26 12:00:00'),
('c2d3e4f5-6a7b-8c9d-0e1f-2a3b4c5d6e7f', 'Fried Rice', 'Transform leftover rice into a delicious meal with eggs, vegetables, and soy sauce. Perfect for using up what you have in the fridge.', '10747848-d377-49c9-a6cd-49280fff781c', 0, 20, 0, 3, 'c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f', '2024-12-03 14:30:00'),
('c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f', 'Minestrone Soup', 'Hearty Italian vegetable soup with pasta and beans. One pot, full of flavor.', '880cb841-5845-4139-b217-420ac24499e1', 1, 30, 0, 4, '1f2e3d4c-5b6a-7f8e-9d0c-1b2a3f4e5d6c', '2025-01-02 17:30:00'),
('c8d9e0f1-2a3b-4c5d-6e7f-8a9b0c1d2e3f', 'Chickpea Salad', 'Protein-packed salad with canned chickpeas, fresh vegetables, and lemon dressing. No cooking required!', 'bbac6cb0-2a2c-4964-bc3a-cf86604b1dd7', 0, 10, 0, 2, '6d7e8f9a-0b1c-2d3e-4f5a-6b7c8d9e0f1a', '2024-12-10 11:15:00'),
('d5e6f7a8-9b0c-1d2e-3f4a-5b6c7d8e9f0a', 'Avocado Toast', 'Simple and healthy breakfast with mashed avocado on crispy toast. Ready in 5 minutes!', 'a87de97c-c425-4f19-8be5-972fc1f17eab', 0, 5, 0, 2, '0f1a2b3c-4d5e-6f7a-8b9c-0d1e2f3a4b5c', '2024-12-20 08:30:00'),
('e0f1a2b3-4c5d-6e7f-8a9b-0c1d2e3f4a5b', 'Cheese Quesadilla', 'Crispy tortilla filled with melted cheese. Ready in 10 minutes and endlessly customizable.', '1c58df39-939b-4027-991f-d8313091369e', 0, 10, 0, 2, '4b5c6d7e-8f9a-0b1c-2d3e-4f5a6b7c8d9e', '2024-12-12 16:30:00'),
('e3f4a5b6-7c8d-9e0f-1a2b-3c4d5e6f7a8b', 'Hummus and Veggie Wrap', 'Fresh vegetables and creamy hummus wrapped in a soft tortilla. Healthy and quick.', '732825dc-fe8a-4f09-98dc-5532a7f78eca', 0, 10, 0, 2, '4b5c6d7e-8f9a-0b1c-2d3e-4f5a6b7c8d9e', '2024-12-28 13:20:00'),
('e4f5a6b7-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 'Tomato and Egg Scramble', 'A quick Chinese-inspired dish combining scrambled eggs with fresh tomatoes. Simple, nutritious, and budget-friendly.', '37b6915a-c9c4-429c-8fa7-8f09713537ca', 0, 12, 0, 2, '1f2e3d4c-5b6a-7f8e-9d0c-1b2a3f4e5d6c', '2024-12-05 09:20:00'),
('e9f0a1b2-3c4d-5e6f-7a8b-9c0d1e2f3a4b', 'Black Bean Tacos', 'Spiced black beans in soft tortillas with fresh toppings. Quick, cheap, and delicious.', 'b786e753-9c43-429c-8695-92a431f92bab', 0, 15, 0, 3, '9e8d7c6b-5a4f-3e2d-1c0b-9a8f7e6d5c4b', '2025-01-05 19:45:00'),
('f7a8b9c0-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 'Shakshuka', 'North African dish of eggs poached in spiced tomato sauce. Flavorful and filling.', 'c68a58ee-5d3f-4dc3-98e3-59faf920f79f', 1, 25, 0, 2, '7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d', '2024-12-22 10:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `RecipeSaves`
--

DROP TABLE IF EXISTS `RecipeSaves`;
CREATE TABLE `RecipeSaves` (
  `recipeId` char(36) NOT NULL,
  `userId` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `RecipeSaves`
--

INSERT INTO `RecipeSaves` (`recipeId`, `userId`) VALUES
('4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', '1f2e3d4c-5b6a-7f8e-9d0c-1b2a3f4e5d6c'),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', '1f2e3d4c-5b6a-7f8e-9d0c-1b2a3f4e5d6c'),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', '6d7e8f9a-0b1c-2d3e-4f5a-6b7c8d9e0f1a'),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', '9e8d7c6b-5a4f-3e2d-1c0b-9a8f7e6d5c4b'),
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', '9e8d7c6b-5a4f-3e2d-1c0b-9a8f7e6d5c4b'),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 'a3f5c8d1-4b2e-4a1c-9f3d-7e8b2c4a6d1f'),
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 'a3f5c8d1-4b2e-4a1c-9f3d-7e8b2c4a6d1f'),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 'a3f5c8d1-4b2e-4a1c-9f3d-7e8b2c4a6d1f'),
('2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', 'c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f'),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 'c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f');

-- --------------------------------------------------------

--
-- Table structure for table `RecipeSteps`
--

DROP TABLE IF EXISTS `RecipeSteps`;
CREATE TABLE `RecipeSteps` (
  `recipeId` char(36) NOT NULL,
  `stepNumber` int NOT NULL,
  `instruction` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `RecipeSteps`
--

INSERT INTO `RecipeSteps` (`recipeId`, `stepNumber`, `instruction`) VALUES
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 0, 'Rinse quinoa thoroughly. Cook in vegetable broth according to package directions.'),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 1, 'Preheat oven to 400°F (200°C). Toss sweet potato, chickpeas, and broccoli with olive oil, salt, and pepper. Roast for 25-30 minutes.'),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 2, 'Make tahini dressing by whisking tahini, lemon juice, garlic, and water until smooth.'),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 3, 'Assemble bowls with quinoa, roasted vegetables, sliced avocado, and mixed greens. Drizzle with tahini dressing.'),
('2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', 0, 'Bring a large pot of salted water to boil. Cook spaghetti according to package directions until al dente.'),
('2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', 1, 'While pasta cooks, cut guanciale into small strips. Cook in a large pan over medium heat until crispy, about 8-10 minutes.'),
('2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', 2, 'In a bowl, whisk together eggs, grated Pecorino Romano, and freshly ground black pepper.'),
('2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', 3, 'Reserve 1 cup of pasta water, then drain the spaghetti. Add hot pasta to the pan with guanciale.'),
('2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', 4, 'Remove from heat and quickly mix in the egg mixture, tossing continuously. Add pasta water as needed to create a creamy sauce. Serve immediately.'),
('4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 0, 'Preheat oven to 475°F (245°C) with a pizza stone inside for at least 30 minutes.'),
('4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 1, 'Stretch pizza dough into a 12-inch round. Place on parchment paper.'),
('4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 2, 'Spread tomato sauce evenly, leaving a 1-inch border. Top with torn mozzarella pieces.'),
('4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 3, 'Transfer pizza (with parchment) to the hot stone. Bake for 10-12 minutes until crust is golden and cheese is bubbly.'),
('4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 4, 'Remove from oven, top with fresh basil leaves and drizzle with olive oil. Let rest 2 minutes before slicing.'),
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 0, 'Slice tomatoes and mozzarella into 1/4 inch thick rounds.'),
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 1, 'Arrange tomato and mozzarella slices alternately on a serving plate.'),
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 2, 'Tuck fresh basil leaves between the slices. Drizzle with extra virgin olive oil and balsamic glaze. Season with salt and pepper to taste.'),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 0, 'Cook pasta in salted boiling water until al dente. Reserve 1 cup pasta water before draining.'),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 1, 'In a food processor, blend basil, pine nuts, garlic, and Parmesan with olive oil until smooth.'),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 2, 'Toss hot pasta with pesto, adding pasta water as needed to create a silky sauce. Serve with extra Parmesan.'),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 0, 'Brew strong espresso and let it cool to room temperature. Add a splash of coffee liqueur if desired.'),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 1, 'Separate eggs. Whisk egg yolks with sugar until pale and creamy. Add mascarpone and mix until smooth.'),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 2, 'Beat egg whites until stiff peaks form. Gently fold into mascarpone mixture.'),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 3, 'Quickly dip ladyfinger cookies into espresso and arrange in a single layer in a dish.'),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 4, 'Spread half of the mascarpone cream over the ladyfingers. Repeat with another layer. Refrigerate for at least 4 hours.'),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 5, 'Before serving, dust generously with cocoa powder.'),
('a1b2c3d4-5e6f-7a8b-9c0d-1e2f3a4b5c6d', 0, 'Cook spaghetti in salted boiling water until al dente. Reserve 1 cup of pasta water before draining.'),
('a1b2c3d4-5e6f-7a8b-9c0d-1e2f3a4b5c6d', 1, 'While pasta cooks, heat olive oil in a large pan over medium-low heat. Add sliced garlic and cook until golden, about 2-3 minutes.'),
('a1b2c3d4-5e6f-7a8b-9c0d-1e2f3a4b5c6d', 2, 'Add red pepper flakes to the oil. Toss in the drained pasta and add pasta water gradually until you have a silky coating. Season with salt and garnish with parsley.'),
('a2b3c4d5-6e7f-8a9b-0c1d-2e3f4a5b6c7d', 0, 'Boil water and cook instant noodles according to package directions, but use only half the seasoning packet.'),
('a2b3c4d5-6e7f-8a9b-0c1d-2e3f4a5b6c7d', 1, 'In the last minute, add frozen vegetables or fresh spinach and a beaten egg, stirring to create egg ribbons.'),
('a2b3c4d5-6e7f-8a9b-0c1d-2e3f4a5b6c7d', 2, 'Drain most of the water, drizzle with sesame oil, and top with green onions and a soft-boiled egg if desired.'),
('a5b6c7d8-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 0, 'Mash 2 ripe bananas in a bowl. Beat in 2 eggs until well combined.'),
('a5b6c7d8-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 1, 'Heat a non-stick pan over medium heat. Pour small circles of batter into the pan.'),
('a5b6c7d8-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 2, 'Cook for 2-3 minutes until bubbles form, then flip and cook another 2 minutes. Serve with maple syrup.'),
('a6b7c8d9-0e1f-2a3b-4c5d-6e7f8a9b0c1d', 0, 'Cook pasta in salted boiling water according to package directions.'),
('a6b7c8d9-0e1f-2a3b-4c5d-6e7f8a9b0c1d', 1, 'Meanwhile, heat olive oil in a pan. Add minced garlic and cook for 1 minute. Add crushed tomatoes, season with salt, and simmer for 10 minutes.'),
('a6b7c8d9-0e1f-2a3b-4c5d-6e7f8a9b0c1d', 2, 'Toss drained pasta with the sauce. Add fresh basil leaves and a drizzle of olive oil. Serve with grated Parmesan if desired.'),
('a9b0c1d2-3e4f-5a6b-7c8d-9e0f1a2b3c4d', 0, 'Spoon Greek yogurt into a bowl.'),
('a9b0c1d2-3e4f-5a6b-7c8d-9e0f1a2b3c4d', 1, 'Drizzle with honey and top with fresh berries, sliced banana, granola, and chopped nuts.'),
('b1c2d3e4-5f6a-7b8c-9d0e-1f2a3b4c5d6e', 0, 'In a jar, combine rolled oats with milk (or plant milk) in a 1:1.5 ratio.'),
('b1c2d3e4-5f6a-7b8c-9d0e-1f2a3b4c5d6e', 1, 'Add a pinch of salt, honey or maple syrup, and any mix-ins like chia seeds or cinnamon.'),
('b1c2d3e4-5f6a-7b8c-9d0e-1f2a3b4c5d6e', 2, 'Refrigerate overnight. In the morning, top with fresh fruit, nuts, or nut butter and enjoy cold or warmed.'),
('b3c4d5e6-7f8a-9b0c-1d2e-3f4a5b6c7d8e', 0, 'Preheat oven to 400°F (200°C). Slice a baguette or Italian bread in half lengthwise.'),
('b3c4d5e6-7f8a-9b0c-1d2e-3f4a5b6c7d8e', 1, 'Mix softened butter with minced garlic and chopped parsley. Spread generously on both halves.'),
('b3c4d5e6-7f8a-9b0c-1d2e-3f4a5b6c7d8e', 2, 'Bake for 8-10 minutes until golden and crispy. Slice and serve warm.'),
('c1d2e3f4-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 0, 'Drain canned tuna and mix with mayonnaise, diced celery, salt, and pepper.'),
('c1d2e3f4-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 1, 'Spread tuna mixture on bread. Add lettuce, tomato slices, and top with another slice of bread.'),
('c2d3e4f5-6a7b-8c9d-0e1f-2a3b4c5d6e7f', 0, 'Heat oil in a large wok or pan over high heat. Add diced vegetables (carrots, peas, corn) and stir-fry for 2-3 minutes.'),
('c2d3e4f5-6a7b-8c9d-0e1f-2a3b4c5d6e7f', 1, 'Push vegetables to the side. Crack eggs into the pan and scramble them until just cooked.'),
('c2d3e4f5-6a7b-8c9d-0e1f-2a3b4c5d6e7f', 2, 'Add day-old rice, breaking up any clumps. Stir-fry for 3-4 minutes. Add soy sauce and sesame oil, mix well. Garnish with green onions.'),
('c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f', 0, 'Heat olive oil in a large pot. Sauté diced onion, carrot, and celery until soft, about 5 minutes.'),
('c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f', 1, 'Add garlic, canned tomatoes, vegetable broth, and Italian herbs. Bring to a boil.'),
('c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f', 2, 'Add small pasta, canned beans, and chopped zucchini. Simmer for 15 minutes. Season with salt and pepper.'),
('c8d9e0f1-2a3b-4c5d-6e7f-8a9b0c1d2e3f', 0, 'Drain and rinse canned chickpeas. Place in a large bowl.'),
('c8d9e0f1-2a3b-4c5d-6e7f-8a9b0c1d2e3f', 1, 'Add diced cucumber, cherry tomatoes, red onion, and parsley.'),
('c8d9e0f1-2a3b-4c5d-6e7f-8a9b0c1d2e3f', 2, 'Whisk together lemon juice, olive oil, salt, and pepper. Pour over salad and toss well. Let sit 5 minutes before serving.'),
('d5e6f7a8-9b0c-1d2e-3f4a-5b6c7d8e9f0a', 0, 'Toast bread until golden and crispy.'),
('d5e6f7a8-9b0c-1d2e-3f4a-5b6c7d8e9f0a', 1, 'Mash ripe avocado with salt, pepper, and a squeeze of lemon juice.'),
('d5e6f7a8-9b0c-1d2e-3f4a-5b6c7d8e9f0a', 2, 'Spread avocado on toast. Top with red pepper flakes or everything bagel seasoning if desired.'),
('e0f1a2b3-4c5d-6e7f-8a9b-0c1d2e3f4a5b', 0, 'Heat a large pan over medium heat. Place one tortilla in the pan.'),
('e0f1a2b3-4c5d-6e7f-8a9b-0c1d2e3f4a5b', 1, 'Sprinkle shredded cheese evenly over half the tortilla. Fold in half and press gently.'),
('e0f1a2b3-4c5d-6e7f-8a9b-0c1d2e3f4a5b', 2, 'Cook for 2-3 minutes per side until golden and cheese is melted. Cut into wedges and serve with salsa or sour cream.'),
('e3f4a5b6-7c8d-9e0f-1a2b-3c4d5e6f7a8b', 0, 'Spread hummus generously over a large tortilla.'),
('e3f4a5b6-7c8d-9e0f-1a2b-3c4d5e6f7a8b', 1, 'Layer with sliced cucumber, shredded carrots, bell pepper strips, and lettuce.'),
('e3f4a5b6-7c8d-9e0f-1a2b-3c4d5e6f7a8b', 2, 'Roll up tightly, tucking in the sides. Cut in half and serve.'),
('e4f5a6b7-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 0, 'Cut tomatoes into wedges. Beat eggs with a pinch of salt and sugar.'),
('e4f5a6b7-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 1, 'Heat oil in a pan over medium-high heat. Pour in eggs and scramble until just set. Remove to a plate.'),
('e4f5a6b7-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 2, 'In the same pan, cook tomatoes until soft and slightly caramelized, about 3-4 minutes. Return eggs to pan, mix gently, and serve over rice.'),
('e9f0a1b2-3c4d-5e6f-7a8b-9c0d1e2f3a4b', 0, 'Drain and rinse canned black beans. Heat in a pan with cumin, chili powder, and a splash of water.'),
('e9f0a1b2-3c4d-5e6f-7a8b-9c0d1e2f3a4b', 1, 'Mash some of the beans with a fork to create a creamy texture. Warm tortillas in a dry pan.'),
('e9f0a1b2-3c4d-5e6f-7a8b-9c0d1e2f3a4b', 2, 'Fill tortillas with beans and top with diced tomatoes, shredded lettuce, and salsa.'),
('f7a8b9c0-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 0, 'Heat olive oil in a large pan. Sauté diced onion and bell pepper until soft, about 5 minutes.'),
('f7a8b9c0-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 1, 'Add minced garlic, cumin, and paprika. Cook for 1 minute, then add canned tomatoes. Simmer for 10 minutes.'),
('f7a8b9c0-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 2, 'Make wells in the sauce and crack eggs into them. Cover and cook until eggs are set, about 8 minutes. Garnish with parsley.');

-- --------------------------------------------------------

--
-- Table structure for table `RecipeTags`
--

DROP TABLE IF EXISTS `RecipeTags`;
CREATE TABLE `RecipeTags` (
  `recipeId` char(36) NOT NULL,
  `tagId` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `RecipeTags`
--

INSERT INTO `RecipeTags` (`recipeId`, `tagId`) VALUES
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', '1e2d3c4b-5a69-4f8e-9d7c-6b5a4e3d2c1b'),
('a5b6c7d8-9e0f-1a2b-3c4d-5e6f7a8b9c0d', '1e2d3c4b-5a69-4f8e-9d7c-6b5a4e3d2c1b'),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', '3d6f91f4-6e2e-4e5f-8d9a-1c3b5e7f9d2a'),
('a2b3c4d5-6e7f-8a9b-0c1d-2e3f4a5b6c7d', '61655a70-6f83-45cd-bf00-2ac8a9789e0c'),
('c2d3e4f5-6a7b-8c9d-0e1f-2a3b4c5d6e7f', '61655a70-6f83-45cd-bf00-2ac8a9789e0c'),
('e4f5a6b7-8c9d-0e1f-2a3b-4c5d6e7f8a9b', '61655a70-6f83-45cd-bf00-2ac8a9789e0c'),
('e9f0a1b2-3c4d-5e6f-7a8b-9c0d1e2f3a4b', '61655a70-6f83-45cd-bf00-2ac8a9789e0c'),
('f7a8b9c0-1d2e-3f4a-5b6c-7d8e9f0a1b2c', '61655a70-6f83-45cd-bf00-2ac8a9789e0c'),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', '6ba7b810-9dad-11d1-80b4-00c04fd430c8'),
('4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', '6ba7b810-9dad-11d1-80b4-00c04fd430c8'),
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', '6ba7b810-9dad-11d1-80b4-00c04fd430c8'),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', '6ba7b810-9dad-11d1-80b4-00c04fd430c8'),
('a1b2c3d4-5e6f-7a8b-9c0d-1e2f3a4b5c6d', '6ba7b810-9dad-11d1-80b4-00c04fd430c8'),
('a5b6c7d8-9e0f-1a2b-3c4d-5e6f7a8b9c0d', '6ba7b810-9dad-11d1-80b4-00c04fd430c8'),
('a6b7c8d9-0e1f-2a3b-4c5d-6e7f8a9b0c1d', '6ba7b810-9dad-11d1-80b4-00c04fd430c8'),
('a9b0c1d2-3e4f-5a6b-7c8d-9e0f1a2b3c4d', '6ba7b810-9dad-11d1-80b4-00c04fd430c8'),
('b1c2d3e4-5f6a-7b8c-9d0e-1f2a3b4c5d6e', '6ba7b810-9dad-11d1-80b4-00c04fd430c8'),
('c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f', '6ba7b810-9dad-11d1-80b4-00c04fd430c8'),
('c8d9e0f1-2a3b-4c5d-6e7f-8a9b0c1d2e3f', '6ba7b810-9dad-11d1-80b4-00c04fd430c8'),
('d5e6f7a8-9b0c-1d2e-3f4a-5b6c7d8e9f0a', '6ba7b810-9dad-11d1-80b4-00c04fd430c8'),
('e0f1a2b3-4c5d-6e7f-8a9b-0c1d2e3f4a5b', '6ba7b810-9dad-11d1-80b4-00c04fd430c8'),
('e3f4a5b6-7c8d-9e0f-1a2b-3c4d5e6f7a8b', '6ba7b810-9dad-11d1-80b4-00c04fd430c8'),
('e9f0a1b2-3c4d-5e6f-7a8b-9c0d1e2f3a4b', '6ba7b810-9dad-11d1-80b4-00c04fd430c8'),
('f7a8b9c0-1d2e-3f4a-5b6c-7d8e9f0a1b2c', '6ba7b810-9dad-11d1-80b4-00c04fd430c8'),
('2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', '7c9e6679-7425-40de-944b-e07fc1f90ae7'),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', '7c9e6679-7425-40de-944b-e07fc1f90ae7'),
('a1b2c3d4-5e6f-7a8b-9c0d-1e2f3a4b5c6d', '7c9e6679-7425-40de-944b-e07fc1f90ae7'),
('a6b7c8d9-0e1f-2a3b-4c5d-6e7f8a9b0c1d', '7c9e6679-7425-40de-944b-e07fc1f90ae7'),
('2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', '8f7e6d5c-4b3a-2918-7654-fedcba098765'),
('4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', '8f7e6d5c-4b3a-2918-7654-fedcba098765'),
('b3c4d5e6-7f8a-9b0c-1d2e-3f4a5b6c7d8e', '8f7e6d5c-4b3a-2918-7654-fedcba098765'),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', '9b8a7c6d-5e4f-3a2b-1c0d-9e8f7a6b5c4d'),
('c8d9e0f1-2a3b-4c5d-6e7f-8a9b0c1d2e3f', '9b8a7c6d-5e4f-3a2b-1c0d-9e8f7a6b5c4d'),
('e3f4a5b6-7c8d-9e0f-1a2b-3c4d5e6f7a8b', '9b8a7c6d-5e4f-3a2b-1c0d-9e8f7a6b5c4d'),
('e9f0a1b2-3c4d-5e6f-7a8b-9c0d1e2f3a4b', '9b8a7c6d-5e4f-3a2b-1c0d-9e8f7a6b5c4d'),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 'a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'),
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 'a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'),
('a9b0c1d2-3e4f-5a6b-7c8d-9e0f1a2b3c4d', 'a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'),
('b1c2d3e4-5f6a-7b8c-9d0e-1f2a3b4c5d6e', 'a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'),
('c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f', 'a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'),
('c8d9e0f1-2a3b-4c5d-6e7f-8a9b0c1d2e3f', 'a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'),
('d5e6f7a8-9b0c-1d2e-3f4a-5b6c7d8e9f0a', 'a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'),
('e3f4a5b6-7c8d-9e0f-1a2b-3c4d5e6f7a8b', 'a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'),
('e4f5a6b7-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 'a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'),
('f7a8b9c0-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 'a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'),
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 'c9bf9e57-1685-4c89-bafb-ff5af830be8a'),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 'c9bf9e57-1685-4c89-bafb-ff5af830be8a'),
('a1b2c3d4-5e6f-7a8b-9c0d-1e2f3a4b5c6d', 'c9bf9e57-1685-4c89-bafb-ff5af830be8a'),
('a2b3c4d5-6e7f-8a9b-0c1d-2e3f4a5b6c7d', 'c9bf9e57-1685-4c89-bafb-ff5af830be8a'),
('a5b6c7d8-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 'c9bf9e57-1685-4c89-bafb-ff5af830be8a'),
('a6b7c8d9-0e1f-2a3b-4c5d-6e7f8a9b0c1d', 'c9bf9e57-1685-4c89-bafb-ff5af830be8a'),
('a9b0c1d2-3e4f-5a6b-7c8d-9e0f1a2b3c4d', 'c9bf9e57-1685-4c89-bafb-ff5af830be8a'),
('b1c2d3e4-5f6a-7b8c-9d0e-1f2a3b4c5d6e', 'c9bf9e57-1685-4c89-bafb-ff5af830be8a'),
('b3c4d5e6-7f8a-9b0c-1d2e-3f4a5b6c7d8e', 'c9bf9e57-1685-4c89-bafb-ff5af830be8a'),
('c1d2e3f4-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 'c9bf9e57-1685-4c89-bafb-ff5af830be8a'),
('c2d3e4f5-6a7b-8c9d-0e1f-2a3b4c5d6e7f', 'c9bf9e57-1685-4c89-bafb-ff5af830be8a'),
('c8d9e0f1-2a3b-4c5d-6e7f-8a9b0c1d2e3f', 'c9bf9e57-1685-4c89-bafb-ff5af830be8a'),
('d5e6f7a8-9b0c-1d2e-3f4a-5b6c7d8e9f0a', 'c9bf9e57-1685-4c89-bafb-ff5af830be8a'),
('e0f1a2b3-4c5d-6e7f-8a9b-0c1d2e3f4a5b', 'c9bf9e57-1685-4c89-bafb-ff5af830be8a'),
('e3f4a5b6-7c8d-9e0f-1a2b-3c4d5e6f7a8b', 'c9bf9e57-1685-4c89-bafb-ff5af830be8a'),
('e4f5a6b7-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 'c9bf9e57-1685-4c89-bafb-ff5af830be8a'),
('e9f0a1b2-3c4d-5e6f-7a8b-9c0d1e2f3a4b', 'c9bf9e57-1685-4c89-bafb-ff5af830be8a'),
('2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', 'f47ac10b-58cc-4372-a567-0e02b2c3d479'),
('4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 'f47ac10b-58cc-4372-a567-0e02b2c3d479'),
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 'f47ac10b-58cc-4372-a567-0e02b2c3d479'),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 'f47ac10b-58cc-4372-a567-0e02b2c3d479'),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 'f47ac10b-58cc-4372-a567-0e02b2c3d479'),
('a1b2c3d4-5e6f-7a8b-9c0d-1e2f3a4b5c6d', 'f47ac10b-58cc-4372-a567-0e02b2c3d479'),
('a6b7c8d9-0e1f-2a3b-4c5d-6e7f8a9b0c1d', 'f47ac10b-58cc-4372-a567-0e02b2c3d479'),
('b3c4d5e6-7f8a-9b0c-1d2e-3f4a5b6c7d8e', 'f47ac10b-58cc-4372-a567-0e02b2c3d479'),
('c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f', 'f47ac10b-58cc-4372-a567-0e02b2c3d479'),
('a2b3c4d5-6e7f-8a9b-0c1d-2e3f4a5b6c7d', 'fd01cc1f-6f1a-4d01-bd38-4ec70349840c'),
('c1d2e3f4-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 'fd01cc1f-6f1a-4d01-bd38-4ec70349840c'),
('c2d3e4f5-6a7b-8c9d-0e1f-2a3b4c5d6e7f', 'fd01cc1f-6f1a-4d01-bd38-4ec70349840c'),
('e0f1a2b3-4c5d-6e7f-8a9b-0c1d2e3f4a5b', 'fd01cc1f-6f1a-4d01-bd38-4ec70349840c');

-- --------------------------------------------------------

--
-- Table structure for table `Reviews`
--

DROP TABLE IF EXISTS `Reviews`;
CREATE TABLE `Reviews` (
  `id` char(36) NOT NULL,
  `userId` char(36) NOT NULL,
  `recipeId` char(36) NOT NULL,
  `rating` int NOT NULL,
  `body` text NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `Reviews`
--

INSERT INTO `Reviews` (`id`, `userId`, `recipeId`, `rating`, `body`, `createdAt`) VALUES
('a3b5c7d9-4e1f-4a2b-3c4d-5e6f7a8b9c0d', 'c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f', '7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 5, 'So quick and flavorful! This is my go-to weeknight dinner now.', '2024-07-05 18:30:00'),
('a7b9c1d3-4e5f-4a6b-7c8d-9e0f1a2b3c4d', '6d7e8f9a-0b1c-2d3e-4f5a-6b7c8d9e0f1a', '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 4, 'Quick and easy appetizer. I added a bit of sea salt flakes on top - highly recommend!', '2024-03-05 18:15:00'),
('b1c3d5e7-4f8a-4b9c-0d1e-2f3a4b5c6d7e', '0f1a2b3c-4d5e-6f7a-8b9c-0d1e2f3a4b5c', '1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 4, 'Great combination of flavors. I added some hemp seeds for extra protein.', '2024-05-10 11:50:00'),
('b4f7d8e2-3c5a-4d9f-8e2b-1a6c9d4f7e3a', 'c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f', '2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', 5, 'Absolutely delicious! The carbonara turned out perfectly creamy. My family loved it!', '2024-01-25 19:30:00'),
('b9c1d3e5-4f7a-4b8c-9d0e-1f2a3b4c5d6e', '9e8d7c6b-5a4f-3e2d-1c0b-9a8f7e6d5c4b', '2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', 3, 'Good recipe but a bit tricky to get the sauce consistency right. Took me a couple tries.', '2024-08-12 15:40:00'),
('c5d7e9f1-3a4b-4c5d-6e7f-8a9b0c1d2e3f', '2e3f4a5b-6c7d-8e9f-0a1b-2c3d4e5f6a7b', '8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 4, 'Delicious dessert! Make sure to let it chill for the full time - it makes a big difference.', '2024-04-02 16:30:00'),
('c7d9e1f3-4a5b-4c6d-7e8f-9a0b1c2d3e4f', '6d7e8f9a-0b1c-2d3e-4f5a-6b7c8d9e0f1a', '1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 5, 'Love this bowl! So colorful and nutritious. The tahini dressing is amazing!', '2024-09-01 13:25:00'),
('d3e5f7a9-2b4c-4d6e-8f0a-1b2c3d4e5f6a', '4b5c6d7e-8f9a-0b1c-2d3e-4f5a6b7c8d9e', '8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 5, 'Best tiramisu recipe ever! I made it for a dinner party and everyone asked for the recipe.', '2024-03-18 21:00:00'),
('d5e7f9a1-3b4c-4d5e-6f7a-8b9c0d1e2f3a', 'a3f5c8d1-4b2e-4a1c-9f3d-7e8b2c4a6d1f', '4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 5, 'Classic Margherita done right! The simplicity really lets the quality ingredients shine.', '2024-06-10 19:45:00'),
('e1f3a5b7-4c9d-4e0f-1a2b-3c4d5e6f7a8b', '1f2e3d4c-5b6a-7f8e-9d0c-1b2a3f4e5d6c', '7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 4, 'Really good pesto! I toasted the pine nuts first which added a nice depth of flavor.', '2024-07-20 12:00:00'),
('e8a3b5c7-2d4f-4e8a-9b1c-3d5e7f9a2b4c', '1f2e3d4c-5b6a-7f8e-9d0c-1b2a3f4e5d6c', '2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', 4, 'Great recipe! I used pancetta instead of guanciale and it was still amazing.', '2024-02-01 14:20:00'),
('e9f1a3b5-4c6d-4e7f-8a9b-0c1d2e3f4a5b', '8d9e0f1a-2b3c-4d5e-6f7a-8b9c0d1e2f3a', '1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 5, 'Such a healthy and filling meal! I meal prep these bowls every Sunday now.', '2024-04-15 13:20:00'),
('f2c4d6e8-5a7b-4c9d-8e1f-2a3b4c5d6e7f', '9e8d7c6b-5a4f-3e2d-1c0b-9a8f7e6d5c4b', '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 5, 'So fresh and simple! Perfect for summer. The quality of ingredients really matters here.', '2024-02-20 12:45:00'),
('f5a7b9c1-4d3e-4f5a-6b7c-8d9e0f1a2b3c', '8d9e0f1a-2b3c-4d5e-6f7a-8b9c0d1e2f3a', '8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 5, 'Incredible! This tastes just like the tiramisu I had in Rome. Thank you for sharing!', '2024-09-22 17:50:00'),
('f7a9b1c3-4d5e-4f6a-7b8c-9d0e1f2a3b4c', '7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d', '4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 5, 'Perfect pizza! The crust was crispy and the toppings were spot on. Will make again!', '2024-05-28 20:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `Tags`
--

DROP TABLE IF EXISTS `Tags`;
CREATE TABLE `Tags` (
  `id` char(36) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Tags`
--

INSERT INTO `Tags` (`id`, `name`) VALUES
('1e2d3c4b-5a69-4f8e-9d7c-6b5a4e3d2c1b', 'Gluten-Free'),
('3d6f91f4-6e2e-4e5f-8d9a-1c3b5e7f9d2a', 'Dessert'),
('4d3e2f1a-0b9c-8d7e-6f5a-4b3c2d1e0f9a', 'Low-Carb'),
('61655a70-6f83-45cd-bf00-2ac8a9789e0c', 'International'),
('6ba7b810-9dad-11d1-80b4-00c04fd430c8', 'Vegetarian'),
('7c9e6679-7425-40de-944b-e07fc1f90ae7', 'Pasta'),
('8f7e6d5c-4b3a-2918-7654-fedcba098765', 'Comfort Food'),
('9b8a7c6d-5e4f-3a2b-1c0d-9e8f7a6b5c4d', 'Vegan'),
('a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11', 'Healthy'),
('c9bf9e57-1685-4c89-bafb-ff5af830be8a', 'Few Ingredients'),
('f47ac10b-58cc-4372-a567-0e02b2c3d479', 'Italian'),
('fd01cc1f-6f1a-4d01-bd38-4ec70349840c', 'Night Snacks');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users` (
  `id` char(36) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `passwordHash` char(128) NOT NULL,
  `avatarId` char(36) DEFAULT NULL,
  `isAdmin` bit(1) NOT NULL DEFAULT b'0',
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`id`, `email`, `username`, `passwordHash`, `avatarId`, `isAdmin`, `createdAt`) VALUES
('0f1a2b3c-4d5e-6f7a-8b9c-0d1e2f3a4b5c', 'matteo.conti@alice.it', 'Matteo Conti', '$2y$10$KJx0D5SrTIG9QrMOSqUJzutF6sN6kky1Q9z9YY.3yoT4wfv56xWB.', NULL, b'0', '2024-09-14 10:25:00'),
('1f2e3d4c-5b6a-7f8e-9d0c-1b2a3f4e5d6c', 'luca.ferrari@libero.it', 'Luca Ferrari', '$2y$10$wjHMd743r6EBhXnY9WVsDOtycuxkNvzz1uCk.U91ZF1a.QBeIowwq', NULL, b'0', '2024-03-10 09:15:00'),
('2e3f4a5b-6c7d-8e9f-0a1b-2c3d4e5f6a7b', 'andrea.moretti@email.it', 'Andrea Moretti', '$2y$10$T83iELMalutiYeWiyqcfOO0FswdYO492MWfuv/UEe2VSrkj65Oc8a', NULL, b'0', '2024-07-22 13:45:00'),
('4b5c6d7e-8f9a-0b1c-2d3e-4f5a6b7c8d9e', 'sara.ricci@hotmail.it', 'Sara Ricci', '$2y$10$e6UbdAaouJeQ7vPZc6F8fO4fsg/TZqF4uiGgomkFpoeUduXiICxvu', NULL, b'0', '2024-06-18 08:30:00'),
('5c6d7e8f-9a0b-1c2d-3e4f-5a6b7c8d9e0f', 'davide.bruno@outlook.it', 'Davide Bruno', '$2y$10$PWtFPggoNrYcUqullnJNMeJHip4hX80pysKKP0k24zSlVna91lFZW', NULL, b'0', '2024-11-20 09:40:00'),
('6d7e8f9a-0b1c-2d3e-4f5a-6b7c8d9e0f1a', 'f.colombo@virgilio.it', 'Francesco Colombo', '$2y$10$5xsF1dxomLUUKwmg9ZJGbOGl6X0ifL3LYfF/7UcEEzV/XjRtESiwq', NULL, b'0', '2024-05-12 11:00:00'),
('7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d', 'elena.greco@yahoo.it', 'Elena Greco', '$2y$10$do4df7cUbVISo7nTtNxgqeNwjAvKxOpbajLCrmowZODGsNP9Iawly', '9349d82e-6f04-416f-a129-577954e1c98d', b'0', '2024-10-08 12:50:00'),
('8d9e0f1a-2b3c-4d5e-6f7a-8b9c0d1e2f3a', 'chiara.gallo@tiscali.it', 'Chiara Gallo', '$2y$10$eGkPiTKjm0CdQKQQfexTY.l0fkE0oC9jLQMeZKqkvwsh.dkfmy4ZC', NULL, b'0', '2024-08-30 15:10:00'),
('9e8d7c6b-5a4f-3e2d-1c0b-9a8f7e6d5c4b', 'alessandra.romano@gmail.com', 'Alessandra Romano', '$2y$10$0aiDlSjIIs//jGwLrrSIKus8drgqtmTYj3nO9COAzsA0al6o7GziS', NULL, b'0', '2024-04-05 16:20:00'),
('a3f5c8d1-4b2e-4a1c-9f3d-7e8b2c4a6d1f', 'marco.rossi@email.it', 'Marco Rossi', '$2y$10$Ns2nFX4rYeGH5ylRi7Hb6e6AUr6IemUimByopvZL.caKfkOXQzQgm', '3a15870e-3634-41c7-8ddc-e1ccf71a9a97', b'1', '2024-01-15 10:30:00'),
('c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f', 'giulia.bianchi@posta.it', 'Giulia Bianchi', '$2y$10$JSXkD94ndydfeD/YPlGhHO00nICiIPiWKrzWtLhC7ospvpr9Qo9fS', NULL, b'0', '2024-02-20 14:45:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `AuthSessions`
--
ALTER TABLE `AuthSessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `keyHash` (`keyHash`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `RecipeIngredients`
--
ALTER TABLE `RecipeIngredients`
  ADD PRIMARY KEY (`recipeId`,`ingredientId`);

--
-- Indexes for table `Recipes`
--
ALTER TABLE `Recipes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `RecipeSaves`
--
ALTER TABLE `RecipeSaves`
  ADD PRIMARY KEY (`recipeId`,`userId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `RecipeSteps`
--
ALTER TABLE `RecipeSteps`
  ADD PRIMARY KEY (`recipeId`,`stepNumber`);

--
-- Indexes for table `RecipeTags`
--
ALTER TABLE `RecipeTags`
  ADD PRIMARY KEY (`recipeId`,`tagId`),
  ADD KEY `tagId` (`tagId`);

--
-- Indexes for table `Reviews`
--
ALTER TABLE `Reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`),
  ADD KEY `recipeId` (`recipeId`);

--
-- Indexes for table `Tags`
--
ALTER TABLE `Tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `AuthSessions`
--
ALTER TABLE `AuthSessions`
  ADD CONSTRAINT `AuthSessions_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `Users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `RecipeIngredients`
--
ALTER TABLE `RecipeIngredients`
  ADD CONSTRAINT `RecipeIngredients_ibfk_1` FOREIGN KEY (`recipeId`) REFERENCES `Recipes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Recipes`
--
ALTER TABLE `Recipes`
  ADD CONSTRAINT `Recipes_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `Users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `RecipeSaves`
--
ALTER TABLE `RecipeSaves`
  ADD CONSTRAINT `RecipeSaves_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `Users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `RecipeSaves_ibfk_2` FOREIGN KEY (`recipeId`) REFERENCES `Recipes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `RecipeSteps`
--
ALTER TABLE `RecipeSteps`
  ADD CONSTRAINT `RecipeSteps_ibfk_1` FOREIGN KEY (`recipeId`) REFERENCES `Recipes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `RecipeTags`
--
ALTER TABLE `RecipeTags`
  ADD CONSTRAINT `RecipeTags_ibfk_1` FOREIGN KEY (`recipeId`) REFERENCES `Recipes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `RecipeTags_ibfk_2` FOREIGN KEY (`tagId`) REFERENCES `Tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Reviews`
--
ALTER TABLE `Reviews`
  ADD CONSTRAINT `Reviews_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `Users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Reviews_ibfk_2` FOREIGN KEY (`recipeId`) REFERENCES `Recipes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

