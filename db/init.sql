-- initialization

DROP DATABASE IF EXISTS `UniCook`;
CREATE DATABASE `UniCook`;

USE `UniCook`;

DROP USER IF EXISTS 'unicook_appuser'@'%';
CREATE USER 'unicook_appuser'@'%' IDENTIFIED BY 'unicook_app_user_passwd!';
GRANT SELECT, INSERT, UPDATE ON * TO 'unicook_appuser'@'%';

-- set timezone to UTC
SET time_zone = '+00:00';

-- tables

-- the deleted field is present to not give delete permissions to the user

DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users` (
    `id` CHAR(36) PRIMARY KEY,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `username` VARCHAR(255) NOT NULL,
    `passwordHash` CHAR(128) NOT NULL,
    `avatarId` CHAR(36),
    `isAdmin` BIT NOT NULL DEFAULT false,
    `createdAt` DATETIME NOT NULL DEFAULT now(),
    `deleted` BIT NOT NULL DEFAULT false
);

DROP TABLE IF EXISTS `AuthSessions`;
CREATE TABLE `AuthSessions` (
    `id` CHAR(36) PRIMARY KEY,
    `keyHash` CHAR(128) UNIQUE NOT NULL,
    `userId` CHAR(36) NOT NULL,
    `createdAt` DATETIME NOT NULL DEFAULT now(),
    `forceExpired` BIT NOT NULL DEFAULT false
);

DROP TABLE IF EXISTS `Tags`;
CREATE TABLE `Tags` (
    `id` CHAR(36) PRIMARY KEY,
    `name` VARCHAR(20) NOT NULL
);

DROP TABLE IF EXISTS `Recipes`;
CREATE TABLE `Recipes` (
    `id` CHAR(36) PRIMARY KEY,
    `title` VARCHAR(50) NOT NULL,
    `description` TEXT,
    `photoId` CHAR(36),
    `difficulty` INT NOT NULL,
    `cookingTime` INT NOT NULL,
    `servings` INT NOT NULL,
    `userId` CHAR(36) NOT NULL,
    `createdAt` DATETIME NOT NULL DEFAULT now(),
    `deleted` BIT NOT NULL DEFAULT false
);

DROP TABLE IF EXISTS `RecipeSteps`;
CREATE TABLE `RecipeSteps` (
    `recipeId` CHAR(36) NOT NULL,
    `stepNumber` INT NOT NULL,
    `instruction` TEXT NOT NULL,
    PRIMARY KEY (`recipeId`, `stepNumber`)
);

DROP TABLE IF EXISTS `RecipeIngredients`;
CREATE TABLE `RecipeIngredients` (
    `recipeId` CHAR(36) NOT NULL,
    `ingredientId` INT NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `quantity` VARCHAR(50) NOT NULL,
    `barcode` VARCHAR(20),
    PRIMARY KEY (`recipeId`, `ingredientId`)
);

DROP TABLE IF EXISTS `RecipeTags`;
CREATE TABLE `RecipeTags` (
    `recipeId` CHAR(36) NOT NULL,
    `tagId` CHAR(36) NOT NULL,
    PRIMARY KEY (`recipeId`, `tagId`)
);

DROP TABLE IF EXISTS `Reviews`;
CREATE TABLE `Reviews` (
    `id` CHAR(36) PRIMARY KEY,
    `userId` CHAR(36) NOT NULL,
    `recipeId` CHAR(36) NOT NULL,
    `rating` INT NOT NULL,
    `body` TEXT,
    `createdAt` DATETIME NOT NULL DEFAULT now(),
    `deleted` BIT NOT NULL DEFAULT false,
    CHECK (`rating` >= 0 AND `rating` <= 5)
);

DROP TABLE IF EXISTS `RecipeSaves`;
CREATE TABLE `RecipeSaves` (
    `recipeId` CHAR(36) NOT NULL,
    `userId` CHAR(36) NOT NULL,
    `deleted` BIT NOT NULL DEFAULT false,
    PRIMARY KEY (`recipeId`, `userId`)
);

ALTER TABLE `AuthSessions`
ADD FOREIGN KEY (`userId`) REFERENCES `Users`(`id`);

ALTER TABLE `Recipes`
ADD FOREIGN KEY (`userId`) REFERENCES `Users`(`id`);

ALTER TABLE `RecipeSteps`
ADD FOREIGN KEY (`recipeId`) REFERENCES `Recipes`(`id`);

ALTER TABLE `RecipeIngredients`
ADD FOREIGN KEY (`recipeId`) REFERENCES `Recipes`(`id`);

ALTER TABLE `Reviews`
ADD FOREIGN KEY (`userId`) REFERENCES `Users`(`id`);
ALTER TABLE `Reviews`
ADD FOREIGN KEY (`recipeId`) REFERENCES `Recipes`(`id`);

ALTER TABLE `RecipeSaves`
ADD FOREIGN KEY (`userId`) REFERENCES `Users`(`id`);
ALTER TABLE `RecipeSaves`
ADD FOREIGN KEY (`recipeId`) REFERENCES `Recipes`(`id`);

ALTER TABLE `RecipeTags`
ADD FOREIGN KEY (`recipeId`) REFERENCES `Recipes`(`id`);
ALTER TABLE `RecipeTags`
ADD FOREIGN KEY (`tagId`) REFERENCES `Tags`(`id`);

-- data

-- user passwords are <email part before @>123!
-- hashed with php password_hash($password, PASSWORD_DEFAULT)
-- e.g. mario.rossi@gmail.com => mario.rossi123!
INSERT INTO `Users`(
    `id`, 
    `email`, 
    `username`, 
    `passwordHash`, 
    `avatarId`, 
    `isAdmin`, 
    `createdAt`, 
    `deleted`
) VALUES
(
    'a3f5c8d1-4b2e-4a1c-9f3d-7e8b2c4a6d1f', 
    'marco.rossi@email.it', 
    'Marco Rossi', 
    '$2y$10$Ns2nFX4rYeGH5ylRi7Hb6e6AUr6IemUimByopvZL.caKfkOXQzQgm', 
    '9e37fb91-8aed-45f3-89f2-7c33a54bbdac', 
    true, 
    '2024-01-15 10:30:00', 
    false
),
(
    'c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f', 
    'giulia.bianchi@posta.it', 
    'Giulia Bianchi', 
    '$2y$10$JSXkD94ndydfeD/YPlGhHO00nICiIPiWKrzWtLhC7ospvpr9Qo9fS', 
    '7c7da984-a24d-4934-9283-00cedff31138', 
    false, 
    '2024-02-20 14:45:00', 
    false
),
(
    '1f2e3d4c-5b6a-7f8e-9d0c-1b2a3f4e5d6c', 
    'luca.ferrari@libero.it', 
    'Luca Ferrari', 
    '$2y$10$wjHMd743r6EBhXnY9WVsDOtycuxkNvzz1uCk.U91ZF1a.QBeIowwq', 
    NULL, 
    false, 
    '2024-03-10 09:15:00', 
    false
),
(
    '9e8d7c6b-5a4f-3e2d-1c0b-9a8f7e6d5c4b', 
    'alessandra.romano@gmail.com', 
    'Alessandra Romano', 
    '$2y$10$0aiDlSjIIs//jGwLrrSIKus8drgqtmTYj3nO9COAzsA0al6o7GziS', 
    NULL, 
    false, 
    '2024-04-05 16:20:00', 
    false
),
(
    '6d7e8f9a-0b1c-2d3e-4f5a-6b7c8d9e0f1a', 
    'f.colombo@virgilio.it', 
    'Francesco Colombo', 
    '$2y$10$5xsF1dxomLUUKwmg9ZJGbOGl6X0ifL3LYfF/7UcEEzV/XjRtESiwq', 
    NULL, 
    false, 
    '2024-05-12 11:00:00', 
    false
),
(
    '4b5c6d7e-8f9a-0b1c-2d3e-4f5a6b7c8d9e', 
    'sara.ricci@hotmail.it', 
    'Sara Ricci', 
    '$2y$10$e6UbdAaouJeQ7vPZc6F8fO4fsg/TZqF4uiGgomkFpoeUduXiICxvu', 
    NULL, 
    false, 
    '2024-06-18 08:30:00', 
    false
),
(
    '2e3f4a5b-6c7d-8e9f-0a1b-2c3d4e5f6a7b', 
    'andrea.moretti@email.it', 
    'Andrea Moretti', 
    '$2y$10$T83iELMalutiYeWiyqcfOO0FswdYO492MWfuv/UEe2VSrkj65Oc8a', 
    NULL, 
    false, 
    '2024-07-22 13:45:00', 
    false
),
(
    '8d9e0f1a-2b3c-4d5e-6f7a-8b9c0d1e2f3a', 
    'chiara.gallo@tiscali.it', 
    'Chiara Gallo', 
    '$2y$10$eGkPiTKjm0CdQKQQfexTY.l0fkE0oC9jLQMeZKqkvwsh.dkfmy4ZC', 
    NULL, 
    false, 
    '2024-08-30 15:10:00', 
    false
),
(
    '0f1a2b3c-4d5e-6f7a-8b9c-0d1e2f3a4b5c', 
    'matteo.conti@alice.it', 
    'Matteo Conti', 
    '$2y$10$KJx0D5SrTIG9QrMOSqUJzutF6sN6kky1Q9z9YY.3yoT4wfv56xWB.', 
    NULL, 
    false, 
    '2024-09-14 10:25:00', 
    false
),
(
    '7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d', 
    'elena.greco@yahoo.it', 
    'Elena Greco', 
    '$2y$10$do4df7cUbVISo7nTtNxgqeNwjAvKxOpbajLCrmowZODGsNP9Iawly', 
    NULL, 
    false, 
    '2024-10-08 12:50:00', 
    false
),
(
    '5c6d7e8f-9a0b-1c2d-3e4f-5a6b7c8d9e0f', 
    'davide.bruno@outlook.it', 
    'Davide Bruno', 
    '$2y$10$PWtFPggoNrYcUqullnJNMeJHip4hX80pysKKP0k24zSlVna91lFZW', 
    NULL, 
    false, 
    '2024-11-20 09:40:00', 
    true
);

INSERT INTO `Tags`(`id`, `name`) VALUES
('f47ac10b-58cc-4372-a567-0e02b2c3d479', 'Italian'),
('c9bf9e57-1685-4c89-bafb-ff5af830be8a', 'Few Ingredients'),
('6ba7b810-9dad-11d1-80b4-00c04fd430c8', 'Vegetarian'),
('7c9e6679-7425-40de-944b-e07fc1f90ae7', 'Pasta'),
('a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11', 'Healthy'),
('3d6f91f4-6e2e-4e5f-8d9a-1c3b5e7f9d2a', 'Dessert'),
('8f7e6d5c-4b3a-2918-7654-fedcba098765', 'Comfort Food'),
('1e2d3c4b-5a69-4f8e-9d7c-6b5a4e3d2c1b', 'Gluten-Free'),
('9b8a7c6d-5e4f-3a2b-1c0d-9e8f7a6b5c4d', 'Vegan'),
('4d3e2f1a-0b9c-8d7e-6f5a-4b3c2d1e0f9a', 'Low-Carb'),
('61655a70-6f83-45cd-bf00-2ac8a9789e0c', 'International'),
('fd01cc1f-6f1a-4d01-bd38-4ec70349840c', 'Night Snacks');

INSERT INTO `Recipes`(
    `id`, 
    `title`, 
    `description`, 
    `photoId`, 
    `difficulty`, 
    `cookingTime`, 
    `servings`, 
    `userId`, 
    `createdAt`, 
    `deleted`
) VALUES
(
    '2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3',
    'Spaghetti Carbonara',
    'A traditional Roman pasta dish made with eggs, cheese, guanciale, and black pepper. Simple ingredients come together to create a creamy, delicious meal.',
    NULL,
    1,
    25,
    4,
    'a3f5c8d1-4b2e-4a1c-9f3d-7e8b2c4a6d1f',
    '2024-01-20 14:30:00',
    false
),
(
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    'Caprese Salad',
    'A fresh and vibrant Italian salad featuring ripe tomatoes, creamy mozzarella, fresh basil, and a drizzle of extra virgin olive oil.',
    NULL,
    0,
    10,
    2,
    'a3f5c8d1-4b2e-4a1c-9f3d-7e8b2c4a6d1f',
    '2024-02-15 11:20:00',
    false
),
(
    '8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e',
    'Tiramisù',
    'The iconic Italian dessert with layers of coffee-soaked ladyfingers and mascarpone cream, dusted with cocoa powder.',
    NULL,
    2,
    45,
    8,
    '1f2e3d4c-5b6a-7f8e-9d0c-1b2a3f4e5d6c',
    '2024-03-12 16:45:00',
    false
),
(
    '1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f',
    'Quinoa Buddha Bowl',
    'A nutritious and colorful bowl packed with quinoa, roasted vegetables, chickpeas, avocado, and tahini dressing.',
    NULL,
    1,
    35,
    2,
    '9e8d7c6b-5a4f-3e2d-1c0b-9a8f7e6d5c4b',
    '2024-04-08 13:15:00',
    false
),
(
    '4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b',
    'Margherita Pizza',
    'The classic Neapolitan pizza with tomato sauce, fresh mozzarella, basil, and extra virgin olive oil.',
    NULL,
    1,
    30,
    4,
    '6d7e8f9a-0b1c-2d3e-4f5a-6b7c8d9e0f1a',
    '2024-05-20 18:00:00',
    false
),
(
    '7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c',
    'Pesto Pasta',
    'Quick and flavorful pasta tossed with homemade basil pesto, pine nuts, garlic, and Parmesan cheese.',
    NULL,
    0,
    20,
    4,
    '4b5c6d7e-8f9a-0b1c-2d3e-4f5a6b7c8d9e',
    '2024-06-25 12:30:00',
    false
);

INSERT INTO `RecipeSteps`(`recipeId`, `stepNumber`, `instruction`) VALUES
-- Spaghetti Carbonara
(
    '2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3',
    1,
    'Bring a large pot of salted water to boil. Cook spaghetti according to package directions until al dente.'
),
(
    '2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3',
    2,
    'While pasta cooks, cut guanciale into small strips. Cook in a large pan over medium heat until crispy, about 8-10 minutes.'
),
(
    '2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3',
    3,
    'In a bowl, whisk together eggs, grated Pecorino Romano, and freshly ground black pepper.'
),
(
    '2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3',
    4,
    'Reserve 1 cup of pasta water, then drain the spaghetti. Add hot pasta to the pan with guanciale.'
),
(
    '2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3',
    5,
    'Remove from heat and quickly mix in the egg mixture, tossing continuously. Add pasta water as needed to create a creamy sauce. Serve immediately.'
),
-- Caprese Salad
(
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    1,
    'Slice tomatoes and mozzarella into 1/4 inch thick rounds.'
),
(
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    2,
    'Arrange tomato and mozzarella slices alternately on a serving plate.'
),
(
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    3,
    'Tuck fresh basil leaves between the slices. Drizzle with extra virgin olive oil and balsamic glaze. Season with salt and pepper to taste.'
),
-- Tiramisù
(
    '8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e',
    1,
    'Brew strong espresso and let it cool to room temperature. Add a splash of coffee liqueur if desired.'
),
(
    '8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e',
    2,
    'Separate eggs. Whisk egg yolks with sugar until pale and creamy. Add mascarpone and mix until smooth.'
),
(
    '8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e',
    3,
    'Beat egg whites until stiff peaks form. Gently fold into mascarpone mixture.'
),
(
    '8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e',
    4,
    'Quickly dip ladyfinger cookies into espresso and arrange in a single layer in a dish.'
),
(
    '8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e',
    5,
    'Spread half of the mascarpone cream over the ladyfingers. Repeat with another layer. Refrigerate for at least 4 hours.'
),
(
    '8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e',
    6,
    'Before serving, dust generously with cocoa powder.'
),
-- Quinoa Buddha Bowl
(
    '1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f',
    1,
    'Rinse quinoa thoroughly. Cook in vegetable broth according to package directions.'
),
(
    '1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f',
    2,
    'Preheat oven to 400°F (200°C). Toss sweet potato, chickpeas, and broccoli with olive oil, salt, and pepper. Roast for 25-30 minutes.'
),
(
    '1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f',
    3,
    'Make tahini dressing by whisking tahini, lemon juice, garlic, and water until smooth.'
),
(
    '1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f',
    4,
    'Assemble bowls with quinoa, roasted vegetables, sliced avocado, and mixed greens. Drizzle with tahini dressing.'
),
-- Margherita Pizza
(
    '4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b',
    1,
    'Preheat oven to 475°F (245°C) with a pizza stone inside for at least 30 minutes.'
),
(
    '4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b',
    2,
    'Stretch pizza dough into a 12-inch round. Place on parchment paper.'
),
(
    '4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b',
    3,
    'Spread tomato sauce evenly, leaving a 1-inch border. Top with torn mozzarella pieces.'
),
(
    '4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b',
    4,
    'Transfer pizza (with parchment) to the hot stone. Bake for 10-12 minutes until crust is golden and cheese is bubbly.'
),
(
    '4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b',
    5,
    'Remove from oven, top with fresh basil leaves and drizzle with olive oil. Let rest 2 minutes before slicing.'
),
(
    '7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c',
    1,
    'Cook pasta in salted boiling water until al dente. Reserve 1 cup pasta water before draining.'
),
(
    '7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c',
    2,
    'In a food processor, blend basil, pine nuts, garlic, and Parmesan with olive oil until smooth.'
),
(
    '7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c',
    3,
    'Toss hot pasta with pesto, adding pasta water as needed to create a silky sauce. Serve with extra Parmesan.'
);

INSERT INTO `RecipeIngredients`(`recipeId`, `ingredientId`, `name`, `quantity`, `barcode`) VALUES
-- Spaghetti Carbonara
('2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', 1, 'Spaghetti', '400g', '8076809513609'),
('2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', 2, 'Guanciale', '150g', NULL),
('2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', 3, 'Eggs', '4 large', '0001111234567'),
('2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', 4, 'Pecorino Romano cheese', '100g', NULL),
('2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', 5, 'Black pepper', 'to taste', NULL),
-- Caprese Salad
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 1, 'Fresh tomatoes', '3 large', NULL),
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 2, 'Fresh mozzarella', '250g', NULL),
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 3, 'Fresh basil', '1 bunch', NULL),
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 4, 'Extra virgin olive oil', '3 tbsp', NULL),
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 5, 'Balsamic glaze', '2 tbsp', NULL),
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 6, 'Salt', 'to taste', NULL),
-- Tiramisù
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 1, 'Ladyfinger cookies', '300g', '8001120130396'),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 2, 'Mascarpone cheese', '500g', NULL),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 3, 'Eggs', '6 large', '0001111234567'),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 4, 'Sugar', '150g', NULL),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 5, 'Espresso coffee', '300ml', NULL),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 6, 'Cocoa powder', 'for dusting', NULL),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 7, 'Coffee liqueur', '2 tbsp (optional)', NULL),
-- Quinoa Buddha Bowl
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 1, 'Quinoa', '1 cup', NULL),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 2, 'Sweet potato', '1 large', NULL),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 3, 'Chickpeas', '1 can (400g)', '5000112648846'),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 4, 'Broccoli', '1 head', NULL),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 5, 'Avocado', '1 ripe', NULL),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 6, 'Mixed greens', '2 cups', NULL),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 7, 'Tahini', '3 tbsp', NULL),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 8, 'Lemon juice', '2 tbsp', NULL),
-- Margherita Pizza
('4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 1, 'Pizza dough', '500g', NULL),
('4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 2, 'Tomato sauce', '200ml', NULL),
('4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 3, 'Fresh mozzarella', '250g', NULL),
('4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 4, 'Fresh basil', '1 bunch', NULL),
('4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 5, 'Extra virgin olive oil', '2 tbsp', NULL),
-- Pesto Pasta
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 1, 'Pasta (fusilli or penne)', '400g', '8076809513609'),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 2, 'Fresh basil', '2 cups packed', NULL),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 3, 'Pine nuts', '50g', NULL),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 4, 'Garlic', '2 cloves', NULL),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 5, 'Parmesan cheese', '80g', NULL),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 6, 'Extra virgin olive oil', '100ml', NULL);

INSERT INTO `RecipeTags`(`recipeId`, `tagId`) VALUES
-- Spaghetti Carbonara: Italian, Pasta, Comfort Food
('2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', 'f47ac10b-58cc-4372-a567-0e02b2c3d479'),
('2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', '7c9e6679-7425-40de-944b-e07fc1f90ae7'),
('2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', '8f7e6d5c-4b3a-2918-7654-fedcba098765'),
-- Caprese Salad: Italian, Quick, Vegetarian, Healthy
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 'f47ac10b-58cc-4372-a567-0e02b2c3d479'),
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 'c9bf9e57-1685-4c89-bafb-ff5af830be8a'),
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', '6ba7b810-9dad-11d1-80b4-00c04fd430c8'),
('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 'a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'),
-- Tiramisù: Italian, Dessert
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', 'f47ac10b-58cc-4372-a567-0e02b2c3d479'),
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', '3d6f91f4-6e2e-4e5f-8d9a-1c3b5e7f9d2a'),
-- Quinoa Buddha Bowl: Vegetarian, Healthy, Gluten-Free, Vegan
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', '6ba7b810-9dad-11d1-80b4-00c04fd430c8'),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', 'a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', '1e2d3c4b-5a69-4f8e-9d7c-6b5a4e3d2c1b'),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', '9b8a7c6d-5e4f-3a2b-1c0d-9e8f7a6b5c4d'),
-- Margherita Pizza: Italian, Vegetarian, Comfort Food
('4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 'f47ac10b-58cc-4372-a567-0e02b2c3d479'),
('4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', '6ba7b810-9dad-11d1-80b4-00c04fd430c8'),
('4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', '8f7e6d5c-4b3a-2918-7654-fedcba098765'),
-- Pesto Pasta: Italian, Quick, Pasta, Vegetarian
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 'f47ac10b-58cc-4372-a567-0e02b2c3d479'),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 'c9bf9e57-1685-4c89-bafb-ff5af830be8a'),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', '7c9e6679-7425-40de-944b-e07fc1f90ae7'),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', '6ba7b810-9dad-11d1-80b4-00c04fd430c8');

INSERT INTO `Reviews`(
    `id`, 
    `userId`, 
    `recipeId`, 
    `rating`, 
    `body`, 
    `createdAt`, 
    `deleted`
) VALUES
-- Spaghetti Carbonara
(
    'b4f7d8e2-3c5a-4d9f-8e2b-1a6c9d4f7e3a',
    'c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f',
    '2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3',
    5,
    'Absolutely delicious! The carbonara turned out perfectly creamy. My family loved it!',
    '2024-01-25 19:30:00',
    false
),
(
    'e8a3b5c7-2d4f-4e8a-9b1c-3d5e7f9a2b4c',
    '1f2e3d4c-5b6a-7f8e-9d0c-1b2a3f4e5d6c',
    '2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3',
    4,
    'Great recipe! I used pancetta instead of guanciale and it was still amazing.',
    '2024-02-01 14:20:00',
    false
),
(
    'b9c1d3e5-4f7a-4b8c-9d0e-1f2a3b4c5d6e',
    '9e8d7c6b-5a4f-3e2d-1c0b-9a8f7e6d5c4b',
    '2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3',
    3,
    'Good recipe but a bit tricky to get the sauce consistency right. Took me a couple tries.',
    '2024-08-12 15:40:00',
    false
),
-- Caprese Salad
(
    'f2c4d6e8-5a7b-4c9d-8e1f-2a3b4c5d6e7f',
    '9e8d7c6b-5a4f-3e2d-1c0b-9a8f7e6d5c4b',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    5,
    'So fresh and simple! Perfect for summer. The quality of ingredients really matters here.',
    '2024-02-20 12:45:00',
    false
),
(
    'a7b9c1d3-4e5f-4a6b-7c8d-9e0f1a2b3c4d',
    '6d7e8f9a-0b1c-2d3e-4f5a-6b7c8d9e0f1a',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    4,
    'Quick and easy appetizer. I added a bit of sea salt flakes on top - highly recommend!',
    '2024-03-05 18:15:00',
    false
),
-- Tiramisù
(
    'd3e5f7a9-2b4c-4d6e-8f0a-1b2c3d4e5f6a',
    '4b5c6d7e-8f9a-0b1c-2d3e-4f5a6b7c8d9e',
    '8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e',
    5,
    'Best tiramisu recipe ever! I made it for a dinner party and everyone asked for the recipe.',
    '2024-03-18 21:00:00',
    false
),
(
    'c5d7e9f1-3a4b-4c5d-6e7f-8a9b0c1d2e3f',
    '2e3f4a5b-6c7d-8e9f-0a1b-2c3d4e5f6a7b',
    '8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e',
    4,
    'Delicious dessert! Make sure to let it chill for the full time - it makes a big difference.',
    '2024-04-02 16:30:00',
    false
),
(
    'f5a7b9c1-4d3e-4f5a-6b7c-8d9e0f1a2b3c',
    '8d9e0f1a-2b3c-4d5e-6f7a-8b9c0d1e2f3a',
    '8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e',
    5,
    'Incredible! This tastes just like the tiramisu I had in Rome. Thank you for sharing!',
    '2024-09-22 17:50:00',
    false
),
-- Quinoa Buddha Bowl
(
    'e9f1a3b5-4c6d-4e7f-8a9b-0c1d2e3f4a5b',
    '8d9e0f1a-2b3c-4d5e-6f7a-8b9c0d1e2f3a',
    '1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f',
    5,
    'Such a healthy and filling meal! I meal prep these bowls every Sunday now.',
    '2024-04-15 13:20:00',
    false
),
(
    'b1c3d5e7-4f8a-4b9c-0d1e-2f3a4b5c6d7e',
    '0f1a2b3c-4d5e-6f7a-8b9c-0d1e2f3a4b5c',
    '1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f',
    4,
    'Great combination of flavors. I added some hemp seeds for extra protein.',
    '2024-05-10 11:50:00',
    false
),
(
    'c7d9e1f3-4a5b-4c6d-7e8f-9a0b1c2d3e4f',
    '6d7e8f9a-0b1c-2d3e-4f5a-6b7c8d9e0f1a',
    '1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f',
    5,
    'Love this bowl! So colorful and nutritious. The tahini dressing is amazing!',
    '2024-09-01 13:25:00',
    false
),
-- Margherita Pizza
(
    'f7a9b1c3-4d5e-4f6a-7b8c-9d0e1f2a3b4c',
    '7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d',
    '4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b',
    5,
    'Perfect pizza! The crust was crispy and the toppings were spot on. Will make again!',
    '2024-05-28 20:15:00',
    false
),
(
    'd5e7f9a1-3b4c-4d5e-6f7a-8b9c0d1e2f3a',
    'a3f5c8d1-4b2e-4a1c-9f3d-7e8b2c4a6d1f',
    '4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b',
    5,
    'Classic Margherita done right! The simplicity really lets the quality ingredients shine.',
    '2024-06-10 19:45:00',
    false
),
-- Pesto Pasta
(
    'a3b5c7d9-4e1f-4a2b-3c4d-5e6f7a8b9c0d',
    'c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f',
    '7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c',
    5,
    'So quick and flavorful! This is my go-to weeknight dinner now.',
    '2024-07-05 18:30:00',
    false
),
(
    'e1f3a5b7-4c9d-4e0f-1a2b-3c4d5e6f7a8b',
    '1f2e3d4c-5b6a-7f8e-9d0c-1b2a3f4e5d6c',
    '7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c',
    4,
    'Really good pesto! I toasted the pine nuts first which added a nice depth of flavor.',
    '2024-07-20 12:00:00',
    false
);

INSERT INTO `RecipeSaves`(`recipeId`, `userId`, `deleted`) VALUES
-- Marco Rossi saves
(
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', -- Caprese Salad
    'a3f5c8d1-4b2e-4a1c-9f3d-7e8b2c4a6d1f', -- Marco Rossi
    false
),
(
    '1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', -- Quinoa Buddha Bowl
    'a3f5c8d1-4b2e-4a1c-9f3d-7e8b2c4a6d1f', -- Marco Rossi
    false
),
(
    '7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', -- Pesto Pasta
    'a3f5c8d1-4b2e-4a1c-9f3d-7e8b2c4a6d1f', -- Marco Rossi
    false
),
-- Giulia Bianchi saves
(
    '2f8e3a1b-9c4d-4e5f-a6b7-c8d9e0f1a2b3', -- Spaghetti Carbonara
    'c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f', -- Giulia Bianchi
    false
),
(
    '8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', -- Tiramisu
    'c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f', -- Giulia Bianchi
    false
),
-- Luca Ferrari saves
(
    '4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', -- Margherita Pizza
    '1f2e3d4c-5b6a-7f8e-9d0c-1b2a3f4e5d6c', -- Luca Ferrari
    false
),
(
    '7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', -- Pesto Pasta
    '1f2e3d4c-5b6a-7f8e-9d0c-1b2a3f4e5d6c', -- Luca Ferrari
    false
),
-- Alessandra Romano saves
(
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', -- Caprese Salad
    '9e8d7c6b-5a4f-3e2d-1c0b-9a8f7e6d5c4b', -- Alessandra Romano
    false
),
(
    '1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f', -- Quinoa Buddha Bowl
    '9e8d7c6b-5a4f-3e2d-1c0b-9a8f7e6d5c4b', -- Alessandra Romano
    false
),
-- Francesco Colombo saves
(
    '8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e', -- Tiramisu
    '6d7e8f9a-0b1c-2d3e-4f5a-6b7c8d9e0f1a', -- Francesco Colombo
    false
);
