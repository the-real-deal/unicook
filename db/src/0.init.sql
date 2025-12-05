DROP DATABASE IF EXISTS `UniCook`;
CREATE DATABASE `UniCook`;

USE `UniCook`;

DROP USER IF EXISTS 'unicook_appuser'@'%';
CREATE USER 'unicook_appuser'@'%' IDENTIFIED BY 'unicook_app_user_passwd!';
GRANT SELECT, INSERT, UPDATE ON * TO 'unicook_appuser'@'%';
