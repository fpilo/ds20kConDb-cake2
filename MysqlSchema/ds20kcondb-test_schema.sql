CREATE DATABASE  IF NOT EXISTS `ds20kcondb-test` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `ds20kcondb-test`;
-- MySQL dump 10.13  Distrib 5.6.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: ds20kcondb-test
-- ------------------------------------------------------
-- Server version	5.6.20

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `acos`
--

DROP TABLE IF EXISTS `acos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=378 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aros`
--

DROP TABLE IF EXISTS `aros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aros` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aros_acos`
--

DROP TABLE IF EXISTS `aros_acos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aros_acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `aro_id` int(10) NOT NULL,
  `aco_id` int(10) NOT NULL,
  `_create` varchar(2) NOT NULL DEFAULT '0',
  `_read` varchar(2) NOT NULL DEFAULT '0',
  `_update` varchar(2) NOT NULL DEFAULT '0',
  `_delete` varchar(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ARO_ACO_KEY` (`aro_id`,`aco_id`)
) ENGINE=InnoDB AUTO_INCREMENT=397 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `checklists`
--

DROP TABLE IF EXISTS `checklists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `checklists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` text,
  `item_id` int(11) NOT NULL,
  `cl_template_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ItemID` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cl_action_attachments`
--

DROP TABLE IF EXISTS `cl_action_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cl_action_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `cl_action_id` int(11) NOT NULL,
  `uploaded` bit(1) DEFAULT b'0',
  PRIMARY KEY (`id`),
  KEY `ClActionID` (`cl_action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cl_actions`
--

DROP TABLE IF EXISTS `cl_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cl_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` text,
  `checklist_id` int(11) DEFAULT NULL,
  `cl_template_id` int(11) DEFAULT NULL,
  `hierarchy_level` int(11) DEFAULT NULL,
  `list_number` int(11) DEFAULT NULL,
  `list_subnumber` int(11) DEFAULT NULL,
  `status_code` bit(16) DEFAULT NULL,
  `updated_by` varchar(45) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `notes` text,
  `has_subactions` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ChecklistID` (`checklist_id`),
  KEY `ClTemplateID` (`cl_template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cl_states`
--

DROP TABLE IF EXISTS `cl_states`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cl_states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` text,
  `cl_action_id` int(11) DEFAULT NULL,
  `type` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ClActionID` (`cl_action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cl_templates`
--

DROP TABLE IF EXISTS `cl_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cl_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` text,
  `checklist_id` int(11) DEFAULT NULL,
  `cl_action_id` int(11) DEFAULT NULL,
  `item_subtype_id` int(11) DEFAULT NULL,
  `default` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `compositions_states`
--

DROP TABLE IF EXISTS `compositions_states`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compositions_states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `composition_id` int(11) NOT NULL COMMENT 'item_subtype_versions_compositions',
  `state_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `composition_id` (`composition_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `db_files`
--

DROP TABLE IF EXISTS `db_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `db_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `real_name` varchar(255) NOT NULL,
  `comment` varchar(512) DEFAULT NULL,
  `size` int(11) NOT NULL,
  `type` varchar(45) NOT NULL,
  `created` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `real_name_UNIQUE` (`real_name`),
  KEY `db_files.userID` (`user_id`),
  CONSTRAINT `db_files.userID` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `db_files_item_subtype_versions`
--

DROP TABLE IF EXISTS `db_files_item_subtype_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `db_files_item_subtype_versions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `db_file_id` int(11) NOT NULL,
  `item_subtype_version_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `db_files_item_subtype_versions.db_fileID` (`db_file_id`),
  KEY `db_files_item_subtype_versions.item_subtype_versionID` (`item_subtype_version_id`),
  CONSTRAINT `db_files_item_subtype_versions.db_fileID` FOREIGN KEY (`db_file_id`) REFERENCES `db_files` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `db_files_item_subtype_versions.item_subtype_versionID` FOREIGN KEY (`item_subtype_version_id`) REFERENCES `item_subtype_versions` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `db_files_item_subtypes`
--

DROP TABLE IF EXISTS `db_files_item_subtypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `db_files_item_subtypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `db_file_id` int(11) NOT NULL,
  `item_subtype_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `db_files_item_subtypes.db_fileID` (`db_file_id`),
  KEY `db_files_item_subtypes.item_subtypeID` (`item_subtype_id`),
  CONSTRAINT `db_files_item_subtypes.db_fileID` FOREIGN KEY (`db_file_id`) REFERENCES `db_files` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `db_files_item_subtypes.item_subtypeID` FOREIGN KEY (`item_subtype_id`) REFERENCES `item_subtypes` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `db_files_items`
--

DROP TABLE IF EXISTS `db_files_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `db_files_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `db_file_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `db_files_items.db_fileID` (`db_file_id`),
  KEY `db_files_items.itemID` (`item_id`),
  CONSTRAINT `db_files_items.db_fileID` FOREIGN KEY (`db_file_id`) REFERENCES `db_files` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `db_files_items.itemID` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `db_files_projects`
--

DROP TABLE IF EXISTS `db_files_projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `db_files_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `db_file_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `db_files_projects?db_fileId_idx` (`db_file_id`),
  KEY `db_files_projects?project_Id_idx` (`project_id`),
  CONSTRAINT `db_files_projects?db_fileId` FOREIGN KEY (`db_file_id`) REFERENCES `db_files` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `db_files_projects?project_Id` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `deliverers`
--

DROP TABLE IF EXISTS `deliverers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deliverers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `homepage` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `devices`
--

DROP TABLE IF EXISTS `devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Devices_LocationID` (`location_id`),
  CONSTRAINT `Devices_Location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `devices_measurement_types`
--

DROP TABLE IF EXISTS `devices_measurement_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `devices_measurement_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) NOT NULL,
  `measurement_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `device_id` (`device_id`),
  KEY `measurement_type_id` (`measurement_type_id`),
  CONSTRAINT `device_id` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`),
  CONSTRAINT `measurement_type_id` FOREIGN KEY (`measurement_type_id`) REFERENCES `measurement_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Assigns each Device the measurement types it can perform';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `from_locations_transfers`
--

DROP TABLE IF EXISTS `from_locations_transfers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `from_locations_transfers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_location_id` int(11) NOT NULL,
  `transfer_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FromLocationsTransfers_FromLocation` (`from_location_id`),
  KEY `FromLocationsTransfers_Transfer` (`transfer_id`),
  CONSTRAINT `FromLocationsTransfers_FromLocation` FOREIGN KEY (`from_location_id`) REFERENCES `locations` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FromLocationsTransfers_Transfer` FOREIGN KEY (`transfer_id`) REFERENCES `transfers` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `histories`
--

DROP TABLE IF EXISTS `histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `histories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `EventID` (`event_id`),
  KEY `ItemID` (`item_id`),
  KEY `History_Item` (`item_id`),
  KEY `History_Event` (`event_id`),
  CONSTRAINT `History_Event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `History_Item` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2056 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `item_compositions`
--

DROP TABLE IF EXISTS `item_compositions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_compositions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL COMMENT 'Parent',
  `component_id` int(11) DEFAULT NULL COMMENT 'Child',
  `stock_id` int(11) DEFAULT NULL,
  `valid` tinyint(4) NOT NULL DEFAULT '1',
  `position` varchar(45) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `composite_items.parent_id` (`item_id`),
  KEY `composite_items.child_id` (`component_id`),
  CONSTRAINT `composite_items.parent_id` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=664 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `item_qualities`
--

DROP TABLE IF EXISTS `item_qualities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_qualities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(46) NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `item_stocks`
--

DROP TABLE IF EXISTS `item_stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_stocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `locationID` (`location_id`),
  CONSTRAINT `Location in use` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`),
  CONSTRAINT `Remove stock information on item removal` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Contains the additional information for stock items';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `item_subtype_version_view`
--

DROP TABLE IF EXISTS `item_subtype_version_view`;
/*!50001 DROP VIEW IF EXISTS `item_subtype_version_view`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `item_subtype_version_view` (
  `manufacturer_id` tinyint NOT NULL,
  `manufacturer_name` tinyint NOT NULL,
  `manufacturers_projects_id` tinyint NOT NULL,
  `manufacturers_projects_manufacturer_id` tinyint NOT NULL,
  `manufacturers_projects_project_id` tinyint NOT NULL,
  `project_id` tinyint NOT NULL,
  `project_name` tinyint NOT NULL,
  `item_subtype_version_id` tinyint NOT NULL,
  `item_subtype_id` tinyint NOT NULL,
  `item_subtype_version_version` tinyint NOT NULL,
  `item_subtype_version_name` tinyint NOT NULL,
  `item_subtype_version_has_components` tinyint NOT NULL,
  `item_subtype_version_comment` tinyint NOT NULL,
  `item_subtype_name` tinyint NOT NULL,
  `item_type_name` tinyint NOT NULL,
  `item_type_id` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `item_subtype_versions`
--

DROP TABLE IF EXISTS `item_subtype_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_subtype_versions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '',
  `item_subtype_id` int(11) NOT NULL,
  `version` int(11) NOT NULL,
  `manufacturer_id` int(11) NOT NULL,
  `has_components` tinyint(4) NOT NULL DEFAULT '0',
  `comment` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `item_subtype_versions_compositions`
--

DROP TABLE IF EXISTS `item_subtype_versions_compositions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_subtype_versions_compositions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_subtype_version_id` int(11) NOT NULL,
  `component_id` int(11) NOT NULL,
  `position` varchar(45) DEFAULT NULL,
  `position_name` varchar(45) NOT NULL DEFAULT ' ',
  `attached` tinyint(4) NOT NULL DEFAULT '1',
  `project_id` int(11) NOT NULL,
  `is_stock` tinyint(4) NOT NULL DEFAULT '0',
  `states_id` int(11) DEFAULT NULL,
  `all_versions` tinyint(4) NOT NULL DEFAULT '0',
  `status_code` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2406 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `item_subtype_versions_projects`
--

DROP TABLE IF EXISTS `item_subtype_versions_projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_subtype_versions_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `item_subtype_version_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `item_subtypes`
--

DROP TABLE IF EXISTS `item_subtypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_subtypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `item_type_id` int(11) NOT NULL,
  `comment` varchar(512) DEFAULT NULL,
  `shortname` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `item_tags`
--

DROP TABLE IF EXISTS `item_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(35) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `item_tags_items`
--

DROP TABLE IF EXISTS `item_tags_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_tags_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `item_tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `item_tags_projects_item_types`
--

DROP TABLE IF EXISTS `item_tags_projects_item_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_tags_projects_item_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_tag_id` int(11) NOT NULL,
  `projects_item_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ItemTag` (`item_tag_id`),
  KEY `ProjectItemType` (`projects_item_type_id`),
  CONSTRAINT `Item Tag in Use` FOREIGN KEY (`item_tag_id`) REFERENCES `item_tags` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `item_tags_projects_item_types_ibfk_1` FOREIGN KEY (`projects_item_type_id`) REFERENCES `projects_item_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `item_tags_stocks`
--

DROP TABLE IF EXISTS `item_tags_stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_tags_stocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_id` int(11) NOT NULL,
  `item_tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_id` (`stock_id`,`item_tag_id`),
  KEY `item_tag_id` (`item_tag_id`),
  CONSTRAINT `item_tags_stocks_ibfk_3` FOREIGN KEY (`stock_id`) REFERENCES `stocks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `item_tags_stocks_ibfk_4` FOREIGN KEY (`item_tag_id`) REFERENCES `item_tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `item_types`
--

DROP TABLE IF EXISTS `item_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `item_view`
--

DROP TABLE IF EXISTS `item_view`;
/*!50001 DROP VIEW IF EXISTS `item_view`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `item_view` (
  `id` tinyint NOT NULL,
  `location_id` tinyint NOT NULL,
  `code` tinyint NOT NULL,
  `state_id` tinyint NOT NULL,
  `project_id` tinyint NOT NULL,
  `manufacturer_id` tinyint NOT NULL,
  `item_type_id` tinyint NOT NULL,
  `item_subtype_id` tinyint NOT NULL,
  `item_subtype_version_id` tinyint NOT NULL,
  `item_quality_id` tinyint NOT NULL,
  `comment` tinyint NOT NULL,
  `item_subtype_version` tinyint NOT NULL,
  `item_subtype_version_name` tinyint NOT NULL,
  `location_name` tinyint NOT NULL,
  `state_name` tinyint NOT NULL,
  `state_description` tinyint NOT NULL,
  `project_name` tinyint NOT NULL,
  `manufacturer_name` tinyint NOT NULL,
  `item_type_name` tinyint NOT NULL,
  `item_subtype_name` tinyint NOT NULL,
  `item_quality_name` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) NOT NULL,
  `item_subtype_version_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `manufacturer_id` int(11) DEFAULT NULL,
  `item_type_id` int(11) DEFAULT NULL,
  `item_subtype_id` int(11) DEFAULT NULL,
  `item_quality_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `Type` (`item_subtype_version_id`),
  KEY `Location` (`location_id`),
  KEY `Status` (`state_id`),
  KEY `Project` (`project_id`),
  KEY `Item_ItemSubtypeVersion` (`item_subtype_version_id`),
  KEY `Item_ItemSubtype` (`item_subtype_id`),
  KEY `Item_ItemType` (`item_type_id`),
  KEY `Item_Manufacturer` (`manufacturer_id`),
  CONSTRAINT `Item_ItemSubtype` FOREIGN KEY (`item_subtype_id`) REFERENCES `item_subtypes` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `Item_ItemSubtypeVersion` FOREIGN KEY (`item_subtype_version_id`) REFERENCES `item_subtype_versions` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `Item_ItemType` FOREIGN KEY (`item_type_id`) REFERENCES `item_types` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `Item_Manufacturer` FOREIGN KEY (`manufacturer_id`) REFERENCES `manufacturers` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `Item_Project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `Item_Status` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `Location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `Project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `Status` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=700 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `items_parameters`
--

DROP TABLE IF EXISTS `items_parameters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items_parameters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `parameter_id` int(11) NOT NULL,
  `value` float NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `itemID` (`item_id`),
  KEY `parameterID` (`parameter_id`),
  CONSTRAINT `Parameter in Use` FOREIGN KEY (`parameter_id`) REFERENCES `parameters` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `items_parameters_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `items_transfers`
--

DROP TABLE IF EXISTS `items_transfers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items_transfers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `transfer_id` int(11) NOT NULL,
  `is_part_of` int(11) DEFAULT NULL,
  `from_location_id` int(11) NOT NULL,
  `to_location_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ItemsTransfers_From` (`from_location_id`),
  KEY `ItemsTransfers_To` (`to_location_id`),
  KEY `transfer_id` (`transfer_id`),
  CONSTRAINT `Delete Items Associated with transfer on transfer deletion` FOREIGN KEY (`transfer_id`) REFERENCES `transfers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `address` varchar(512) DEFAULT NULL,
  `phone_number` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `contact` varchar(45) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `locations_stocks`
--

DROP TABLE IF EXISTS `locations_stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `locations_stocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `locations_stocks_location_id` (`location_id`),
  KEY `locations_stocks_stock_id` (`stock_id`),
  CONSTRAINT `locations_stocks_location_id` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `locations_stocks_stock_id` FOREIGN KEY (`stock_id`) REFERENCES `stocks` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `locations_users`
--

DROP TABLE IF EXISTS `locations_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `locations_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `locations_users.location_id` (`location_id`),
  KEY `locations_users.user_id` (`user_id`),
  CONSTRAINT `locations_users.location_id` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `locations_users.user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_events`
--

DROP TABLE IF EXISTS `log_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `log_event_id` int(11) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Log_User` (`user_id`),
  KEY `Log_LogEvent` (`log_event_id`),
  CONSTRAINT `Log_LogEvent` FOREIGN KEY (`log_event_id`) REFERENCES `log_events` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6127 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `manufacturers`
--

DROP TABLE IF EXISTS `manufacturers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manufacturers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `address` varchar(256) DEFAULT NULL,
  `phone_number` varchar(256) DEFAULT NULL,
  `email` varchar(256) DEFAULT NULL,
  `contact` varchar(256) DEFAULT NULL,
  `comment` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `manufacturers_projects`
--

DROP TABLE IF EXISTS `manufacturers_projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manufacturers_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manufacturer_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ManufacturersProjects_Manufacturer` (`manufacturer_id`),
  KEY `ManufacturersProjects_Project` (`project_id`),
  CONSTRAINT `ManufacturersProjects_Manufacturer` FOREIGN KEY (`manufacturer_id`) REFERENCES `manufacturers` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ManufacturersProjects_Project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `matching`
--

DROP TABLE IF EXISTS `matching`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matching` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `parameter_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Name` (`name`) COMMENT 'Can only match unique names to parameters',
  KEY `parameter_id` (`parameter_id`),
  CONSTRAINT `Parameter used as target in matching table` FOREIGN KEY (`parameter_id`) REFERENCES `parameters` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table to match custom parameters to database parameters';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `measurement_files`
--

DROP TABLE IF EXISTS `measurement_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `measurement_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `originalFileName` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=latin1 COMMENT='Stores the file association for each measurement file';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `measurement_parameters`
--

DROP TABLE IF EXISTS `measurement_parameters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `measurement_parameters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `measurement_id` int(11) NOT NULL,
  `parameter_id` int(11) NOT NULL,
  `value` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `measurement_id` (`measurement_id`),
  KEY `parameter_id` (`parameter_id`),
  CONSTRAINT `Parameter is still in use` FOREIGN KEY (`parameter_id`) REFERENCES `parameters` (`id`),
  CONSTRAINT `measurement_parameters_ibfk_1` FOREIGN KEY (`measurement_id`) REFERENCES `measurements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `measurement_queue`
--

DROP TABLE IF EXISTS `measurement_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `measurement_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `measurement_id` int(11) NOT NULL,
  `file_path` varchar(128) NOT NULL COMMENT 'Path to the file containing the data for this measurement (depending on status)',
  `status` int(11) NOT NULL DEFAULT '0',
  `parameters` text,
  PRIMARY KEY (`id`),
  KEY `measurement_id` (`measurement_id`),
  CONSTRAINT `measurement_queue_ibfk_1` FOREIGN KEY (`measurement_id`) REFERENCES `measurements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `measurement_sets`
--

DROP TABLE IF EXISTS `measurement_sets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `measurement_sets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `comment` text,
  `parameter_table` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `measurement_sets_measurements`
--

DROP TABLE IF EXISTS `measurement_sets_measurements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `measurement_sets_measurements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `measurement_id` int(11) DEFAULT NULL,
  `measurement_set_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `measurement_tags`
--

DROP TABLE IF EXISTS `measurement_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `measurement_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `measurement_tags_measurements`
--

DROP TABLE IF EXISTS `measurement_tags_measurements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `measurement_tags_measurements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `measurement_id` int(11) NOT NULL,
  `measurement_tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `measurement_id` (`measurement_id`),
  KEY `measurement_tag_id` (`measurement_tag_id`),
  CONSTRAINT `Measurement Tag in use` FOREIGN KEY (`measurement_tag_id`) REFERENCES `measurement_tags` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `measurement_tags_measurements_ibfk_1` FOREIGN KEY (`measurement_id`) REFERENCES `measurements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `measurement_types`
--

DROP TABLE IF EXISTS `measurement_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `measurement_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `marker` varchar(64) NOT NULL COMMENT 'The marker for the section in the CSV file that corresponds to this measurement type',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `measurements`
--

DROP TABLE IF EXISTS `measurements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `measurements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `history_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `device_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `measurement_type_id` int(11) NOT NULL COMMENT 'To select the right table for the measurement data: dim=1 ==> 1D_measurement_data; dim=2 ==> 2D_measurement_data; ...',
  `measurement_file_id` int(11) DEFAULT NULL,
  `start` datetime DEFAULT NULL,
  `stop` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `MeasurementSeries_HistoryID` (`history_id`),
  KEY `MeasurementSeries_DeviceID` (`device_id`),
  KEY `MeasurementSeries_UserID` (`user_id`),
  KEY `MeasurementSeries_ItemID` (`item_id`),
  KEY `MeasurementSeries_MeasurementTypeID` (`measurement_type_id`),
  KEY `measurement_file_id` (`measurement_file_id`),
  CONSTRAINT `Measurement File Set` FOREIGN KEY (`measurement_file_id`) REFERENCES `measurement_files` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `MeasurementSeries_DeviceID` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `MeasurementSeries_HistoryID` FOREIGN KEY (`history_id`) REFERENCES `histories` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `MeasurementSeries_ItemID` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `MeasurementSeries_MeasurementTypeID` FOREIGN KEY (`measurement_type_id`) REFERENCES `measurement_types` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `MeasurementSeries_UserID` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `measuring_points`
--

DROP TABLE IF EXISTS `measuring_points`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `measuring_points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `measurement_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `MeasuringPoints_MeasurementID` (`measurement_id`),
  KEY `MeasuringPoints.MeasurementID` (`measurement_id`),
  CONSTRAINT `measuring_points_ibfk_1` FOREIGN KEY (`measurement_id`) REFERENCES `measurements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `parameters`
--

DROP TABLE IF EXISTS `parameters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parameters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `projects_item_types`
--

DROP TABLE IF EXISTS `projects_item_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects_item_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `item_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `item_type_id` (`item_type_id`),
  CONSTRAINT `Item Type in use by project` FOREIGN KEY (`item_type_id`) REFERENCES `item_types` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `Project Id in use by Item Type` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='Associates item types to projects. One ItemType can be used in multiple projects';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `projects_stocks`
--

DROP TABLE IF EXISTS `projects_stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects_stocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `projects_stocks_project_id` (`project_id`),
  KEY `projects_stocks_stock_id` (`stock_id`),
  CONSTRAINT `projects_stocks_project_id` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `projects_stocks_stock_id` FOREIGN KEY (`stock_id`) REFERENCES `stocks` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `projects_users`
--

DROP TABLE IF EXISTS `projects_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `projects_users.project_id` (`project_id`),
  KEY `projects_users.user_id` (`user_id`),
  CONSTRAINT `projects_users.project_id` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `projects_users.user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `states`
--

DROP TABLE IF EXISTS `states`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `stock_view`
--

DROP TABLE IF EXISTS `stock_view`;
/*!50001 DROP VIEW IF EXISTS `stock_view`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `stock_view` (
  `id` tinyint NOT NULL,
  `item_subtype_version_id` tinyint NOT NULL,
  `item_subtype_id` tinyint NOT NULL,
  `item_type_id` tinyint NOT NULL,
  `amount` tinyint NOT NULL,
  `item_id` tinyint NOT NULL,
  `item_quality_id` tinyint NOT NULL,
  `item_quality_name` tinyint NOT NULL,
  `location_name` tinyint NOT NULL,
  `location_id` tinyint NOT NULL,
  `stock_item_code` tinyint NOT NULL,
  `manufacturer_id` tinyint NOT NULL,
  `comment` tinyint NOT NULL,
  `item_type_name` tinyint NOT NULL,
  `item_subtype_name` tinyint NOT NULL,
  `version` tinyint NOT NULL,
  `item_subtype_version_name` tinyint NOT NULL,
  `manufacturer_name` tinyint NOT NULL,
  `project_id` tinyint NOT NULL,
  `project_name` tinyint NOT NULL,
  `item_tags_ids` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `stocks`
--

DROP TABLE IF EXISTS `stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_subtype_version_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `stock_quality_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '0',
  `parent_item_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stocks_item_subtype_version_id` (`item_subtype_version_id`),
  KEY `stocks_state_id` (`state_id`),
  KEY `stock_quality_id` (`stock_quality_id`),
  CONSTRAINT `stocks_ibfk_1` FOREIGN KEY (`stock_quality_id`) REFERENCES `item_qualities` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `stocks_item_subtype_version_id` FOREIGN KEY (`item_subtype_version_id`) REFERENCES `item_subtype_versions` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `stocks_state_id` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transfers`
--

DROP TABLE IF EXISTS `transfers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transfers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shipping_date` datetime DEFAULT NULL,
  `status` int(11) NOT NULL,
  `from_location_id` int(11) NOT NULL,
  `to_location_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `tracking_number` varchar(100) DEFAULT NULL,
  `deliverer_id` int(11) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Transfers_ToLocationID` (`to_location_id`),
  KEY `Transfer_To` (`to_location_id`),
  KEY `Transfer_Deliverer` (`deliverer_id`),
  KEY `status` (`status`),
  KEY `from_location_id` (`from_location_id`),
  KEY `recipient_id` (`recipient_id`),
  CONSTRAINT `Transfer_Deliverer` FOREIGN KEY (`deliverer_id`) REFERENCES `deliverers` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `Transfer_From` FOREIGN KEY (`from_location_id`) REFERENCES `locations` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `Transfer_To` FOREIGN KEY (`to_location_id`) REFERENCES `locations` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `User is recipient of a transfer` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(40) NOT NULL,
  `group_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `comment` varchar(512) DEFAULT NULL,
  `add_locations` tinyint(4) DEFAULT '0',
  `add_projects` tinyint(4) DEFAULT '0',
  `standard_location_id` int(11) NOT NULL,
  `phone` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `User_Group` (`group_id`),
  KEY `standard_location_id` (`standard_location_id`),
  CONSTRAINT `Location in use by User` FOREIGN KEY (`standard_location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `User_Group` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping events for database 'ds20kcondb-test'
--

--
-- Dumping routines for database 'ds20kcondb-test'
--

--
-- Final view structure for view `item_subtype_version_view`
--

/*!50001 DROP TABLE IF EXISTS `item_subtype_version_view`*/;
/*!50001 DROP VIEW IF EXISTS `item_subtype_version_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`ds20kcondb_cake`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `item_subtype_version_view` AS select `manufacturer`.`id` AS `manufacturer_id`,`manufacturer`.`name` AS `manufacturer_name`,`manufacturersproject`.`id` AS `manufacturers_projects_id`,`manufacturersproject`.`manufacturer_id` AS `manufacturers_projects_manufacturer_id`,`manufacturersproject`.`project_id` AS `manufacturers_projects_project_id`,`project`.`id` AS `project_id`,`project`.`name` AS `project_name`,`itemsubtypeversionproject`.`item_subtype_version_id` AS `item_subtype_version_id`,`itemsubtypeversion`.`item_subtype_id` AS `item_subtype_id`,`itemsubtypeversion`.`version` AS `item_subtype_version_version`,`itemsubtypeversion`.`name` AS `item_subtype_version_name`,`itemsubtypeversion`.`has_components` AS `item_subtype_version_has_components`,`itemsubtypeversion`.`comment` AS `item_subtype_version_comment`,`itemsubtype`.`name` AS `item_subtype_name`,`itemtype`.`name` AS `item_type_name`,`itemtype`.`id` AS `item_type_id` from ((((((`manufacturers` `manufacturer` join `manufacturers_projects` `manufacturersproject` on((`manufacturersproject`.`manufacturer_id` = `manufacturer`.`id`))) join `projects` `project` on((`manufacturersproject`.`project_id` = `project`.`id`))) join `item_subtype_versions_projects` `itemsubtypeversionproject` on((`itemsubtypeversionproject`.`project_id` = `project`.`id`))) join `item_subtype_versions` `itemsubtypeversion` on(((`itemsubtypeversion`.`id` = `itemsubtypeversionproject`.`item_subtype_version_id`) and (`itemsubtypeversion`.`manufacturer_id` = `manufacturer`.`id`)))) join `item_subtypes` `itemsubtype` on((`itemsubtype`.`id` = `itemsubtypeversion`.`item_subtype_id`))) join `item_types` `itemtype` on((`itemtype`.`id` = `itemsubtype`.`item_type_id`))) order by lcase(`manufacturer`.`name`),lcase(`project`.`name`),lcase(`itemtype`.`name`),lcase(`itemsubtype`.`name`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `item_view`
--

/*!50001 DROP TABLE IF EXISTS `item_view`*/;
/*!50001 DROP VIEW IF EXISTS `item_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`ds20kcondb_cake`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `item_view` AS select `item`.`id` AS `id`,if((`itemstocks`.`location_id` is not null),`itemstocks`.`location_id`,`item`.`location_id`) AS `location_id`,if((`itemstocks`.`location_id` is not null),convert(concat('Stock (',`itemstocks`.`amount`,' available)') using latin1),`item`.`code`) AS `code`,`item`.`state_id` AS `state_id`,`item`.`project_id` AS `project_id`,`item`.`manufacturer_id` AS `manufacturer_id`,`item`.`item_type_id` AS `item_type_id`,`item`.`item_subtype_id` AS `item_subtype_id`,`item`.`item_subtype_version_id` AS `item_subtype_version_id`,`item`.`item_quality_id` AS `item_quality_id`,`item`.`comment` AS `comment`,`itemsubtypeversion`.`version` AS `item_subtype_version`,`itemsubtypeversion`.`name` AS `item_subtype_version_name`,`location`.`name` AS `location_name`,`state`.`name` AS `state_name`,`state`.`description` AS `state_description`,`project`.`name` AS `project_name`,`manufacturer`.`name` AS `manufacturer_name`,`itemtype`.`name` AS `item_type_name`,`itemsubtype`.`name` AS `item_subtype_name`,`itemquality`.`name` AS `item_quality_name` from (((((((((`items` `item` left join `item_subtype_versions` `itemsubtypeversion` on((`item`.`item_subtype_version_id` = `itemsubtypeversion`.`id`))) left join `item_stocks` `itemstocks` on((`itemstocks`.`item_id` = `item`.`id`))) left join `locations` `location` on((if((`itemstocks`.`location_id` is not null),`itemstocks`.`location_id`,`item`.`location_id`) = `location`.`id`))) left join `states` `state` on((`item`.`state_id` = `state`.`id`))) left join `projects` `project` on((`item`.`project_id` = `project`.`id`))) left join `manufacturers` `manufacturer` on((`item`.`manufacturer_id` = `manufacturer`.`id`))) left join `item_types` `itemtype` on((`item`.`item_type_id` = `itemtype`.`id`))) left join `item_subtypes` `itemsubtype` on((`item`.`item_subtype_id` = `itemsubtype`.`id`))) left join `item_qualities` `itemquality` on((`item`.`item_quality_id` = `itemquality`.`id`))) group by 1,2 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `stock_view`
--

/*!50001 DROP TABLE IF EXISTS `stock_view`*/;
/*!50001 DROP VIEW IF EXISTS `stock_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`ds20kcondb_cake`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `stock_view` AS select `itemstocks`.`id` AS `id`,`item`.`item_subtype_version_id` AS `item_subtype_version_id`,`item`.`item_subtype_id` AS `item_subtype_id`,`item`.`item_type_id` AS `item_type_id`,`itemstocks`.`amount` AS `amount`,`itemstocks`.`item_id` AS `item_id`,`item`.`item_quality_id` AS `item_quality_id`,`itemquality`.`name` AS `item_quality_name`,`location`.`name` AS `location_name`,`itemstocks`.`location_id` AS `location_id`,`item`.`code` AS `stock_item_code`,`itemsubtypeversion`.`manufacturer_id` AS `manufacturer_id`,`itemsubtypeversion`.`comment` AS `comment`,`itemtype`.`name` AS `item_type_name`,`itemsubtype`.`name` AS `item_subtype_name`,`itemsubtypeversion`.`version` AS `version`,`itemsubtypeversion`.`name` AS `item_subtype_version_name`,`manufacturer`.`name` AS `manufacturer_name`,`project`.`id` AS `project_id`,`project`.`name` AS `project_name`,group_concat(`itemtagsitems`.`item_tag_id` separator ',') AS `item_tags_ids` from (((((((((`item_stocks` `itemstocks` left join `locations` `location` on((`itemstocks`.`location_id` = `location`.`id`))) left join `items` `item` on((`itemstocks`.`item_id` = `item`.`id`))) left join `item_subtype_versions` `itemsubtypeversion` on((`item`.`item_subtype_version_id` = `itemsubtypeversion`.`id`))) left join `item_subtypes` `itemsubtype` on((`item`.`item_subtype_id` = `itemsubtype`.`id`))) left join `item_types` `itemtype` on((`item`.`item_type_id` = `itemtype`.`id`))) left join `manufacturers` `manufacturer` on((`item`.`manufacturer_id` = `manufacturer`.`id`))) left join `item_qualities` `itemquality` on((`item`.`item_quality_id` = `itemquality`.`id`))) left join `projects` `project` on((`item`.`project_id` = `project`.`id`))) left join `item_tags_items` `itemtagsitems` on((`itemtagsitems`.`item_id` = `itemstocks`.`item_id`))) where (1 = 1) group by `itemstocks`.`id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-06-24 17:14:14
