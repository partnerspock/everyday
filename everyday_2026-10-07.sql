# ************************************************************
# Sequel Ace SQL dump
# Version 20109
#
# https://sequel-ace.com/
# https://github.com/Sequel-Ace/Sequel-Ace
#
# Host: 127.0.0.1 (MySQL 26.7.0)
# Database: everyday
# Generation Time: 2026-10-07 06:53:49 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table activity_logs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `activity_logs`;

CREATE TABLE `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` bigint unsigned NOT NULL,
  `module` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `activity_logs_admin_id_foreign` (`admin_id`),
  CONSTRAINT `activity_logs_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;

INSERT INTO `activity_logs` (`id`, `admin_id`, `module`, `action`, `description`, `created_at`)
VALUES
	(1,1,'Auths','Login','Login Users Partner','2026-10-07 13:52:44');

/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admins
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admins`;

CREATE TABLE `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_confirmed` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_confirm',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_username_unique` (`username`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;

INSERT INTO `admins` (`id`, `name`, `username`, `email`, `password`, `is_confirmed`, `last_login_at`, `confirmed_at`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,'Partner','partner','partner@everyday.test','$2y$12$jgtjEuRcheaqDWAadUxr2.hJQCd78KECbu7XLKnTy0kNkUZdkV1oq','confirmed','2026-10-07 13:52:44','2026-10-07 13:51:19',NULL,'2026-10-07 13:51:20','2026-10-07 13:52:44'),
	(2,'Admin','admin','admin@everyday.test','$2y$12$GQjrEjdXKpU4ruuQZXaLwOiM.hftgoj3K68eDbSp102lKZaRK3Y7i','confirmed',NULL,'2026-10-07 13:51:20',NULL,'2026-10-07 13:51:20','2026-10-07 13:51:20');

/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admins_has_role
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admins_has_role`;

CREATE TABLE `admins_has_role` (
  `id_user` bigint unsigned NOT NULL,
  `id_role` bigint unsigned NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_user`,`id_role`),
  KEY `admins_has_role_id_role_foreign` (`id_role`),
  CONSTRAINT `admins_has_role_id_role_foreign` FOREIGN KEY (`id_role`) REFERENCES `admins_roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `admins_has_role_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `admins` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admins_has_role` WRITE;
/*!40000 ALTER TABLE `admins_has_role` DISABLE KEYS */;

INSERT INTO `admins_has_role` (`id_user`, `id_role`, `is_default`)
VALUES
	(1,6,0),
	(2,1,0);

/*!40000 ALTER TABLE `admins_has_role` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admins_permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admins_permissions`;

CREATE TABLE `admins_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `permission_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permission_status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `module_name` enum('general_notes','spaces','admin_users','roles','permissions','activity_logs') COLLATE utf8mb4_unicode_ci NOT NULL,
  `module_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deactivated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admins_permissions` WRITE;
/*!40000 ALTER TABLE `admins_permissions` DISABLE KEYS */;

INSERT INTO `admins_permissions` (`id`, `permission_name`, `permission_status`, `is_default`, `module_name`, `module_key`, `deactivated_at`, `created_at`, `updated_at`)
VALUES
	(1,'View General Notes','active',1,'general_notes','view_notes_key',NULL,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(2,'Create General Notes','active',1,'general_notes','create_notes_key',NULL,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(3,'Edit General Notes','active',1,'general_notes','edit_notes_key',NULL,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(4,'Delete General Notes','active',1,'general_notes','delete_notes_key',NULL,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(5,'View Spaces','active',1,'spaces','view_spaces_key',NULL,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(6,'Create Spaces','active',1,'spaces','create_spaces_key',NULL,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(7,'Edit Spaces','active',1,'spaces','edit_spaces_key',NULL,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(8,'Delete Spaces','active',1,'spaces','delete_spaces_key',NULL,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(9,'View System Activity Logs','active',1,'activity_logs','view_logs_key',NULL,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(10,'Export Activity Logs Data','active',1,'activity_logs','export_logs_key',NULL,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(11,'View Roles','active',1,'roles','view_roles_key',NULL,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(12,'Create Roles','active',1,'roles','create_roles_key',NULL,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(13,'Edit Roles','active',1,'roles','edit_roles_key',NULL,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(14,'Delete Roles','active',1,'roles','delete_roles_key',NULL,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(15,'View Permissions','active',1,'permissions','view_permissions_key',NULL,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(16,'Create Permissions','active',1,'permissions','create_permissions_key',NULL,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(17,'Edit Permissions','active',1,'permissions','edit_permissions_key',NULL,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(18,'View Admin Users','active',1,'admin_users','view_admin_users_key',NULL,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(19,'Create Admin Users','active',1,'admin_users','create_admin_users_key',NULL,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(20,'Edit Admin Users','active',1,'admin_users','edit_admin_users_key',NULL,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(21,'Delete Admin Users','active',1,'admin_users','delete_admin_users_key',NULL,'2026-10-07 13:51:19','2026-10-07 13:51:19');

/*!40000 ALTER TABLE `admins_permissions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admins_role_has_permission
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admins_role_has_permission`;

CREATE TABLE `admins_role_has_permission` (
  `id_role` bigint unsigned NOT NULL,
  `id_permission` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_role`,`id_permission`),
  KEY `admins_role_has_permission_id_permission_foreign` (`id_permission`),
  CONSTRAINT `admins_role_has_permission_id_permission_foreign` FOREIGN KEY (`id_permission`) REFERENCES `admins_permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `admins_role_has_permission_id_role_foreign` FOREIGN KEY (`id_role`) REFERENCES `admins_roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admins_role_has_permission` WRITE;
/*!40000 ALTER TABLE `admins_role_has_permission` DISABLE KEYS */;

INSERT INTO `admins_role_has_permission` (`id_role`, `id_permission`, `created_at`, `updated_at`)
VALUES
	(1,1,NULL,NULL),
	(1,2,NULL,NULL),
	(1,3,NULL,NULL),
	(1,4,NULL,NULL),
	(1,5,NULL,NULL),
	(1,6,NULL,NULL),
	(1,7,NULL,NULL),
	(1,8,NULL,NULL),
	(1,9,NULL,NULL),
	(1,10,NULL,NULL),
	(1,11,NULL,NULL),
	(1,12,NULL,NULL),
	(1,13,NULL,NULL),
	(1,14,NULL,NULL),
	(1,15,NULL,NULL),
	(1,16,NULL,NULL),
	(1,17,NULL,NULL),
	(1,18,NULL,NULL),
	(2,1,NULL,NULL),
	(2,5,NULL,NULL),
	(2,9,NULL,NULL),
	(2,11,NULL,NULL),
	(2,15,NULL,NULL),
	(2,18,NULL,NULL),
	(3,2,NULL,NULL),
	(3,6,NULL,NULL),
	(3,12,NULL,NULL),
	(3,16,NULL,NULL),
	(3,19,NULL,NULL),
	(4,3,NULL,NULL),
	(4,7,NULL,NULL),
	(4,13,NULL,NULL),
	(4,17,NULL,NULL),
	(4,20,NULL,NULL),
	(5,4,NULL,NULL),
	(5,8,NULL,NULL),
	(5,14,NULL,NULL),
	(5,21,NULL,NULL),
	(6,1,NULL,NULL),
	(6,2,NULL,NULL),
	(6,3,NULL,NULL),
	(6,4,NULL,NULL),
	(6,5,NULL,NULL),
	(6,6,NULL,NULL),
	(6,7,NULL,NULL),
	(6,8,NULL,NULL),
	(6,9,NULL,NULL),
	(6,10,NULL,NULL),
	(6,11,NULL,NULL),
	(6,12,NULL,NULL),
	(6,13,NULL,NULL),
	(6,14,NULL,NULL),
	(6,15,NULL,NULL),
	(6,16,NULL,NULL),
	(6,17,NULL,NULL),
	(6,18,NULL,NULL),
	(6,19,NULL,NULL),
	(6,20,NULL,NULL),
	(6,21,NULL,NULL);

/*!40000 ALTER TABLE `admins_role_has_permission` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admins_roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admins_roles`;

CREATE TABLE `admins_roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admins_roles` WRITE;
/*!40000 ALTER TABLE `admins_roles` DISABLE KEYS */;

INSERT INTO `admins_roles` (`id`, `role_name`, `is_default`, `created_at`, `updated_at`)
VALUES
	(1,'Super Admin',1,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(2,'User (View Only)',1,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(3,'User (Create Only)',1,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(4,'User (Edit Only)',1,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(5,'User (Delete Only)',1,'2026-10-07 13:51:19','2026-10-07 13:51:19'),
	(6,'Partner',1,'2026-10-07 13:51:19','2026-10-07 13:51:19');

/*!40000 ALTER TABLE `admins_roles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`id`, `migration`, `batch`)
VALUES
	(1,'2026_01_01_000001_create_admins_table',1),
	(2,'2026_01_01_000002_create_admins_roles_table',1),
	(3,'2026_01_01_000003_create_admins_permissions_table',1),
	(4,'2026_01_01_000004_create_admins_role_has_permission_table',1),
	(5,'2026_01_01_000005_create_admins_has_role_table',1),
	(6,'2026_01_01_000006_create_mod_general_notes_table',1),
	(7,'2026_01_01_000007_create_mod_spaces_table',1),
	(8,'2026_01_01_000008_create_util_projects_status_table',1),
	(9,'2026_01_01_000009_create_mod_sub_projects_table',1),
	(10,'2026_01_01_000009a_create_mod_sub_projects_files_table',1),
	(11,'2026_01_01_000010_create_mod_sub_reminder_table',1),
	(12,'2026_01_01_000011_create_mod_sub_notes_table',1),
	(13,'2026_01_01_000012_create_activity_logs_table',1);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table mod_general_notes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mod_general_notes`;

CREATE TABLE `mod_general_notes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_user` bigint unsigned NOT NULL,
  `title_notes` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mod_general_notes_created_user_foreign` (`created_user`),
  CONSTRAINT `mod_general_notes_created_user_foreign` FOREIGN KEY (`created_user`) REFERENCES `admins` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `mod_general_notes` WRITE;
/*!40000 ALTER TABLE `mod_general_notes` DISABLE KEYS */;

INSERT INTO `mod_general_notes` (`id`, `created_user`, `title_notes`, `password`, `description`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(1,1,'Master System Architecture',NULL,'Standard guidelines for system modules, route naming conventions, and database migrations.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(2,1,'Confidential Server Access','$2y$12$313I5A5SBOpdTs.aDfxKp.uUvng9HYcFA1ywxWSc1PJTPAKtrb/pq','SSH keys, database root credentials, and production environment secrets.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL);

/*!40000 ALTER TABLE `mod_general_notes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table mod_spaces
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mod_spaces`;

CREATE TABLE `mod_spaces` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_user` bigint unsigned NOT NULL,
  `title_spaces` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mod_spaces_created_user_foreign` (`created_user`),
  CONSTRAINT `mod_spaces_created_user_foreign` FOREIGN KEY (`created_user`) REFERENCES `admins` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `mod_spaces` WRITE;
/*!40000 ALTER TABLE `mod_spaces` DISABLE KEYS */;

INSERT INTO `mod_spaces` (`id`, `created_user`, `title_spaces`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(1,2,'Board of Directors','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(2,2,'HC - Management','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(3,2,'HC - Recruitment','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(4,2,'IT - Infrastructure','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(5,2,'IT - Development','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(6,2,'IT - Support','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL);

/*!40000 ALTER TABLE `mod_spaces` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table mod_sub_notes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mod_sub_notes`;

CREATE TABLE `mod_sub_notes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_spaces` bigint unsigned NOT NULL,
  `title_notes` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mod_sub_notes_id_spaces_foreign` (`id_spaces`),
  CONSTRAINT `mod_sub_notes_id_spaces_foreign` FOREIGN KEY (`id_spaces`) REFERENCES `mod_spaces` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `mod_sub_notes` WRITE;
/*!40000 ALTER TABLE `mod_sub_notes` DISABLE KEYS */;

INSERT INTO `mod_sub_notes` (`id`, `id_spaces`, `title_notes`, `description`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(1,1,'Meeting Minutes #1 - Board of Directors','Detailed summary and action plan for meeting item #1.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(2,1,'Meeting Minutes #2 - Board of Directors','Detailed summary and action plan for meeting item #2.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(3,1,'Meeting Minutes #3 - Board of Directors','Detailed summary and action plan for meeting item #3.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(4,1,'Meeting Minutes #4 - Board of Directors','Detailed summary and action plan for meeting item #4.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(5,1,'Meeting Minutes #5 - Board of Directors','Detailed summary and action plan for meeting item #5.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(6,1,'Meeting Minutes #6 - Board of Directors','Detailed summary and action plan for meeting item #6.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(7,1,'Meeting Minutes #7 - Board of Directors','Detailed summary and action plan for meeting item #7.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(8,1,'Meeting Minutes #8 - Board of Directors','Detailed summary and action plan for meeting item #8.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(9,1,'Meeting Minutes #9 - Board of Directors','Detailed summary and action plan for meeting item #9.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(10,1,'Meeting Minutes #10 - Board of Directors','Detailed summary and action plan for meeting item #10.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(11,2,'Meeting Minutes #1 - HC - Management','Detailed summary and action plan for meeting item #1.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(12,2,'Meeting Minutes #2 - HC - Management','Detailed summary and action plan for meeting item #2.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(13,2,'Meeting Minutes #3 - HC - Management','Detailed summary and action plan for meeting item #3.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(14,2,'Meeting Minutes #4 - HC - Management','Detailed summary and action plan for meeting item #4.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(15,2,'Meeting Minutes #5 - HC - Management','Detailed summary and action plan for meeting item #5.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(16,2,'Meeting Minutes #6 - HC - Management','Detailed summary and action plan for meeting item #6.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(17,2,'Meeting Minutes #7 - HC - Management','Detailed summary and action plan for meeting item #7.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(18,2,'Meeting Minutes #8 - HC - Management','Detailed summary and action plan for meeting item #8.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(19,2,'Meeting Minutes #9 - HC - Management','Detailed summary and action plan for meeting item #9.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(20,2,'Meeting Minutes #10 - HC - Management','Detailed summary and action plan for meeting item #10.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(21,3,'Meeting Minutes #1 - HC - Recruitment','Detailed summary and action plan for meeting item #1.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(22,3,'Meeting Minutes #2 - HC - Recruitment','Detailed summary and action plan for meeting item #2.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(23,3,'Meeting Minutes #3 - HC - Recruitment','Detailed summary and action plan for meeting item #3.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(24,3,'Meeting Minutes #4 - HC - Recruitment','Detailed summary and action plan for meeting item #4.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(25,3,'Meeting Minutes #5 - HC - Recruitment','Detailed summary and action plan for meeting item #5.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(26,3,'Meeting Minutes #6 - HC - Recruitment','Detailed summary and action plan for meeting item #6.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(27,3,'Meeting Minutes #7 - HC - Recruitment','Detailed summary and action plan for meeting item #7.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(28,3,'Meeting Minutes #8 - HC - Recruitment','Detailed summary and action plan for meeting item #8.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(29,3,'Meeting Minutes #9 - HC - Recruitment','Detailed summary and action plan for meeting item #9.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(30,3,'Meeting Minutes #10 - HC - Recruitment','Detailed summary and action plan for meeting item #10.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(31,4,'Meeting Minutes #1 - IT - Infrastructure','Detailed summary and action plan for meeting item #1.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(32,4,'Meeting Minutes #2 - IT - Infrastructure','Detailed summary and action plan for meeting item #2.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(33,4,'Meeting Minutes #3 - IT - Infrastructure','Detailed summary and action plan for meeting item #3.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(34,4,'Meeting Minutes #4 - IT - Infrastructure','Detailed summary and action plan for meeting item #4.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(35,4,'Meeting Minutes #5 - IT - Infrastructure','Detailed summary and action plan for meeting item #5.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(36,4,'Meeting Minutes #6 - IT - Infrastructure','Detailed summary and action plan for meeting item #6.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(37,4,'Meeting Minutes #7 - IT - Infrastructure','Detailed summary and action plan for meeting item #7.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(38,4,'Meeting Minutes #8 - IT - Infrastructure','Detailed summary and action plan for meeting item #8.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(39,4,'Meeting Minutes #9 - IT - Infrastructure','Detailed summary and action plan for meeting item #9.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(40,4,'Meeting Minutes #10 - IT - Infrastructure','Detailed summary and action plan for meeting item #10.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(41,5,'Meeting Minutes #1 - IT - Development','Detailed summary and action plan for meeting item #1.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(42,5,'Meeting Minutes #2 - IT - Development','Detailed summary and action plan for meeting item #2.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(43,5,'Meeting Minutes #3 - IT - Development','Detailed summary and action plan for meeting item #3.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(44,5,'Meeting Minutes #4 - IT - Development','Detailed summary and action plan for meeting item #4.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(45,5,'Meeting Minutes #5 - IT - Development','Detailed summary and action plan for meeting item #5.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(46,5,'Meeting Minutes #6 - IT - Development','Detailed summary and action plan for meeting item #6.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(47,5,'Meeting Minutes #7 - IT - Development','Detailed summary and action plan for meeting item #7.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(48,5,'Meeting Minutes #8 - IT - Development','Detailed summary and action plan for meeting item #8.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(49,5,'Meeting Minutes #9 - IT - Development','Detailed summary and action plan for meeting item #9.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(50,5,'Meeting Minutes #10 - IT - Development','Detailed summary and action plan for meeting item #10.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(51,6,'Meeting Minutes #1 - IT - Support','Detailed summary and action plan for meeting item #1.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(52,6,'Meeting Minutes #2 - IT - Support','Detailed summary and action plan for meeting item #2.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(53,6,'Meeting Minutes #3 - IT - Support','Detailed summary and action plan for meeting item #3.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(54,6,'Meeting Minutes #4 - IT - Support','Detailed summary and action plan for meeting item #4.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(55,6,'Meeting Minutes #5 - IT - Support','Detailed summary and action plan for meeting item #5.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(56,6,'Meeting Minutes #6 - IT - Support','Detailed summary and action plan for meeting item #6.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(57,6,'Meeting Minutes #7 - IT - Support','Detailed summary and action plan for meeting item #7.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(58,6,'Meeting Minutes #8 - IT - Support','Detailed summary and action plan for meeting item #8.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(59,6,'Meeting Minutes #9 - IT - Support','Detailed summary and action plan for meeting item #9.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(60,6,'Meeting Minutes #10 - IT - Support','Detailed summary and action plan for meeting item #10.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL);

/*!40000 ALTER TABLE `mod_sub_notes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table mod_sub_projects
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mod_sub_projects`;

CREATE TABLE `mod_sub_projects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_spaces` bigint unsigned NOT NULL,
  `pro_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_started',
  `pro_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pro_start_at` date DEFAULT NULL,
  `pro_end_at` date DEFAULT NULL,
  `pro_description` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mod_sub_projects_id_spaces_foreign` (`id_spaces`),
  CONSTRAINT `mod_sub_projects_id_spaces_foreign` FOREIGN KEY (`id_spaces`) REFERENCES `mod_spaces` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `mod_sub_projects` WRITE;
/*!40000 ALTER TABLE `mod_sub_projects` DISABLE KEYS */;

INSERT INTO `mod_sub_projects` (`id`, `id_spaces`, `pro_status`, `pro_title`, `pro_start_at`, `pro_end_at`, `pro_description`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(1,1,'not_started','System Modernization 2026',NULL,NULL,'Overhauling UI/UX and infrastructure to modern standards.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(2,1,'in_progress','Operational Audit & Security',NULL,NULL,'Comprehensive compliance check and access control audit.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(3,1,'completed','Q1 Team Onboarding Kit',NULL,NULL,'Updating documentation, templates, and workflow charts.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(4,2,'not_started','System Modernization 2026',NULL,NULL,'Overhauling UI/UX and infrastructure to modern standards.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(5,2,'in_progress','Operational Audit & Security',NULL,NULL,'Comprehensive compliance check and access control audit.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(6,2,'completed','Q1 Team Onboarding Kit',NULL,NULL,'Updating documentation, templates, and workflow charts.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(7,3,'not_started','System Modernization 2026',NULL,NULL,'Overhauling UI/UX and infrastructure to modern standards.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(8,3,'in_progress','Operational Audit & Security',NULL,NULL,'Comprehensive compliance check and access control audit.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(9,3,'completed','Q1 Team Onboarding Kit',NULL,NULL,'Updating documentation, templates, and workflow charts.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(10,4,'not_started','System Modernization 2026',NULL,NULL,'Overhauling UI/UX and infrastructure to modern standards.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(11,4,'in_progress','Operational Audit & Security',NULL,NULL,'Comprehensive compliance check and access control audit.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(12,4,'completed','Q1 Team Onboarding Kit',NULL,NULL,'Updating documentation, templates, and workflow charts.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(13,5,'not_started','System Modernization 2026',NULL,NULL,'Overhauling UI/UX and infrastructure to modern standards.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(14,5,'in_progress','Operational Audit & Security',NULL,NULL,'Comprehensive compliance check and access control audit.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(15,5,'completed','Q1 Team Onboarding Kit',NULL,NULL,'Updating documentation, templates, and workflow charts.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(16,6,'not_started','System Modernization 2026',NULL,NULL,'Overhauling UI/UX and infrastructure to modern standards.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(17,6,'in_progress','Operational Audit & Security',NULL,NULL,'Comprehensive compliance check and access control audit.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(18,6,'completed','Q1 Team Onboarding Kit',NULL,NULL,'Updating documentation, templates, and workflow charts.','2026-10-07 13:51:20','2026-10-07 13:51:20',NULL);

/*!40000 ALTER TABLE `mod_sub_projects` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table mod_sub_projects_files
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mod_sub_projects_files`;

CREATE TABLE `mod_sub_projects_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_projects` bigint unsigned NOT NULL,
  `original_filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mod_sub_projects_files_id_projects_foreign` (`id_projects`),
  CONSTRAINT `mod_sub_projects_files_id_projects_foreign` FOREIGN KEY (`id_projects`) REFERENCES `mod_sub_projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table mod_sub_reminder
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mod_sub_reminder`;

CREATE TABLE `mod_sub_reminder` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_spaces` bigint unsigned NOT NULL,
  `row_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `row_description` text COLLATE utf8mb4_unicode_ci,
  `row_status` enum('ticked','not_ticked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_ticked',
  `row_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mod_sub_reminder_id_spaces_foreign` (`id_spaces`),
  CONSTRAINT `mod_sub_reminder_id_spaces_foreign` FOREIGN KEY (`id_spaces`) REFERENCES `mod_spaces` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `mod_sub_reminder` WRITE;
/*!40000 ALTER TABLE `mod_sub_reminder` DISABLE KEYS */;

INSERT INTO `mod_sub_reminder` (`id`, `id_spaces`, `row_title`, `row_description`, `row_status`, `row_order`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(1,1,'Task Reminder #1 - Board of Directors','Scheduled tasks and operational checklist #1.','ticked',1,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(2,1,'Task Reminder #2 - Board of Directors','Scheduled tasks and operational checklist #2.','not_ticked',2,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(3,1,'Task Reminder #3 - Board of Directors','Scheduled tasks and operational checklist #3.','ticked',3,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(4,1,'Task Reminder #4 - Board of Directors','Scheduled tasks and operational checklist #4.','not_ticked',4,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(5,1,'Task Reminder #5 - Board of Directors','Scheduled tasks and operational checklist #5.','ticked',5,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(6,1,'Task Reminder #6 - Board of Directors','Scheduled tasks and operational checklist #6.','not_ticked',6,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(7,1,'Task Reminder #7 - Board of Directors','Scheduled tasks and operational checklist #7.','ticked',7,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(8,1,'Task Reminder #8 - Board of Directors','Scheduled tasks and operational checklist #8.','not_ticked',8,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(9,1,'Task Reminder #9 - Board of Directors','Scheduled tasks and operational checklist #9.','ticked',9,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(10,1,'Task Reminder #10 - Board of Directors','Scheduled tasks and operational checklist #10.','not_ticked',10,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(11,2,'Task Reminder #1 - HC - Management','Scheduled tasks and operational checklist #1.','ticked',1,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(12,2,'Task Reminder #2 - HC - Management','Scheduled tasks and operational checklist #2.','not_ticked',2,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(13,2,'Task Reminder #3 - HC - Management','Scheduled tasks and operational checklist #3.','ticked',3,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(14,2,'Task Reminder #4 - HC - Management','Scheduled tasks and operational checklist #4.','not_ticked',4,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(15,2,'Task Reminder #5 - HC - Management','Scheduled tasks and operational checklist #5.','ticked',5,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(16,2,'Task Reminder #6 - HC - Management','Scheduled tasks and operational checklist #6.','not_ticked',6,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(17,2,'Task Reminder #7 - HC - Management','Scheduled tasks and operational checklist #7.','ticked',7,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(18,2,'Task Reminder #8 - HC - Management','Scheduled tasks and operational checklist #8.','not_ticked',8,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(19,2,'Task Reminder #9 - HC - Management','Scheduled tasks and operational checklist #9.','ticked',9,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(20,2,'Task Reminder #10 - HC - Management','Scheduled tasks and operational checklist #10.','not_ticked',10,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(21,3,'Task Reminder #1 - HC - Recruitment','Scheduled tasks and operational checklist #1.','ticked',1,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(22,3,'Task Reminder #2 - HC - Recruitment','Scheduled tasks and operational checklist #2.','not_ticked',2,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(23,3,'Task Reminder #3 - HC - Recruitment','Scheduled tasks and operational checklist #3.','ticked',3,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(24,3,'Task Reminder #4 - HC - Recruitment','Scheduled tasks and operational checklist #4.','not_ticked',4,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(25,3,'Task Reminder #5 - HC - Recruitment','Scheduled tasks and operational checklist #5.','ticked',5,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(26,3,'Task Reminder #6 - HC - Recruitment','Scheduled tasks and operational checklist #6.','not_ticked',6,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(27,3,'Task Reminder #7 - HC - Recruitment','Scheduled tasks and operational checklist #7.','ticked',7,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(28,3,'Task Reminder #8 - HC - Recruitment','Scheduled tasks and operational checklist #8.','not_ticked',8,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(29,3,'Task Reminder #9 - HC - Recruitment','Scheduled tasks and operational checklist #9.','ticked',9,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(30,3,'Task Reminder #10 - HC - Recruitment','Scheduled tasks and operational checklist #10.','not_ticked',10,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(31,4,'Task Reminder #1 - IT - Infrastructure','Scheduled tasks and operational checklist #1.','ticked',1,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(32,4,'Task Reminder #2 - IT - Infrastructure','Scheduled tasks and operational checklist #2.','not_ticked',2,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(33,4,'Task Reminder #3 - IT - Infrastructure','Scheduled tasks and operational checklist #3.','ticked',3,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(34,4,'Task Reminder #4 - IT - Infrastructure','Scheduled tasks and operational checklist #4.','not_ticked',4,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(35,4,'Task Reminder #5 - IT - Infrastructure','Scheduled tasks and operational checklist #5.','ticked',5,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(36,4,'Task Reminder #6 - IT - Infrastructure','Scheduled tasks and operational checklist #6.','not_ticked',6,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(37,4,'Task Reminder #7 - IT - Infrastructure','Scheduled tasks and operational checklist #7.','ticked',7,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(38,4,'Task Reminder #8 - IT - Infrastructure','Scheduled tasks and operational checklist #8.','not_ticked',8,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(39,4,'Task Reminder #9 - IT - Infrastructure','Scheduled tasks and operational checklist #9.','ticked',9,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(40,4,'Task Reminder #10 - IT - Infrastructure','Scheduled tasks and operational checklist #10.','not_ticked',10,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(41,5,'Task Reminder #1 - IT - Development','Scheduled tasks and operational checklist #1.','ticked',1,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(42,5,'Task Reminder #2 - IT - Development','Scheduled tasks and operational checklist #2.','not_ticked',2,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(43,5,'Task Reminder #3 - IT - Development','Scheduled tasks and operational checklist #3.','ticked',3,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(44,5,'Task Reminder #4 - IT - Development','Scheduled tasks and operational checklist #4.','not_ticked',4,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(45,5,'Task Reminder #5 - IT - Development','Scheduled tasks and operational checklist #5.','ticked',5,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(46,5,'Task Reminder #6 - IT - Development','Scheduled tasks and operational checklist #6.','not_ticked',6,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(47,5,'Task Reminder #7 - IT - Development','Scheduled tasks and operational checklist #7.','ticked',7,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(48,5,'Task Reminder #8 - IT - Development','Scheduled tasks and operational checklist #8.','not_ticked',8,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(49,5,'Task Reminder #9 - IT - Development','Scheduled tasks and operational checklist #9.','ticked',9,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(50,5,'Task Reminder #10 - IT - Development','Scheduled tasks and operational checklist #10.','not_ticked',10,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(51,6,'Task Reminder #1 - IT - Support','Scheduled tasks and operational checklist #1.','ticked',1,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(52,6,'Task Reminder #2 - IT - Support','Scheduled tasks and operational checklist #2.','not_ticked',2,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(53,6,'Task Reminder #3 - IT - Support','Scheduled tasks and operational checklist #3.','ticked',3,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(54,6,'Task Reminder #4 - IT - Support','Scheduled tasks and operational checklist #4.','not_ticked',4,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(55,6,'Task Reminder #5 - IT - Support','Scheduled tasks and operational checklist #5.','ticked',5,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(56,6,'Task Reminder #6 - IT - Support','Scheduled tasks and operational checklist #6.','not_ticked',6,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(57,6,'Task Reminder #7 - IT - Support','Scheduled tasks and operational checklist #7.','ticked',7,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(58,6,'Task Reminder #8 - IT - Support','Scheduled tasks and operational checklist #8.','not_ticked',8,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(59,6,'Task Reminder #9 - IT - Support','Scheduled tasks and operational checklist #9.','ticked',9,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL),
	(60,6,'Task Reminder #10 - IT - Support','Scheduled tasks and operational checklist #10.','not_ticked',10,'2026-10-07 13:51:20','2026-10-07 13:51:20',NULL);

/*!40000 ALTER TABLE `mod_sub_reminder` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table util_projects_status
# ------------------------------------------------------------

DROP TABLE IF EXISTS `util_projects_status`;

CREATE TABLE `util_projects_status` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `colour_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
