USE `UniCook`;

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
