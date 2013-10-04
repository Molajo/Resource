-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 23, 2013 at 12:44 PM
-- Server version: 5.5.29
-- PHP Version: 5.4.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `molajo_user`
--

-- --------------------------------------------------------

--
-- Table structure for table `molajo_actions`
--

CREATE TABLE `molajo_actions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Actions Primary Key',
  `title` varchar(255) NOT NULL DEFAULT ' ',
  `protected` tinyint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_actions_table_title` (`title`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `molajo_actions`
--

INSERT INTO `molajo_actions` (`id`, `title`, `protected`) VALUES
(1, 'Login', 1),
(2, 'Create', 1),
(3, 'Read', 1),
(4, 'Update', 1),
(5, 'Publish', 1),
(6, 'Delete', 1),
(7, 'Administer', 1);

-- --------------------------------------------------------

--
-- Table structure for table `molajo_catalog`
--

CREATE TABLE `molajo_catalog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Catalog Primary Key',
  `primary_key_to_asset` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Key to the Asset',
  `type_of_asset_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Key that represents the Resource, or type of asset, combined with primary_key_to_asset represents a primary key for multiple types of resources',
  `view_group_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Identifies the view_group_id which is a unique key identifying all groups with view access to this content',

PRIMARY KEY (`id`),
  UNIQUE KEY `index_catalog_asset_type_and_primary_id` (`type_of_asset_id`, `primary_key_to_asset`),
  KEY `index_catalog_view_group_id` (`view_group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------


--
-- Table structure for table `molajo_groups`
--

CREATE TABLE `molajo_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Groups Table Primary Key',
  `title` varchar(255) NOT NULL DEFAULT ' ' COMMENT 'Title',
  `subtitle` varchar(255) NOT NULL DEFAULT ' ' COMMENT 'Subtitle',
  `path` varchar(2048) NOT NULL DEFAULT ' ' COMMENT 'URI Path to append to Alias',
  `alias` varchar(255) NOT NULL DEFAULT ' ' COMMENT 'Slug, or alias, associated with Title, must be unique when combined with path.',
  `content_text` longtext COMMENT 'Text field',
  `protected` tinyint(6) unsigned NOT NULL DEFAULT '0' COMMENT 'If activated, represents an important feature required for operations that cannot be removed.',
  `featured` tinyint(6) unsigned NOT NULL DEFAULT '0' COMMENT 'Indicator representing content designated as Featured. Can be used in queries.',
  `stickied` tinyint(6) unsigned NOT NULL DEFAULT '0' COMMENT 'Indicator representing content designated as Stickied. Can be used in queries.',
  `status` tinyint(6) unsigned NOT NULL DEFAULT '0' COMMENT 'Content Status, must be one of the following values: 2 Archived 1 Published 0 Unpublished -1 Trashed -2 Marked as Spam -10 Version',
  `start_publishing_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Publish Begin Date and Time',
  `stop_publishing_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Publish End Date and Time',
  `version` int(11) NOT NULL DEFAULT '1' COMMENT 'Version Number',
  `version_of_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Primary Key for this Version',
  `status_prior_to_version` int(11) NOT NULL DEFAULT '0' COMMENT 'State value prior to creating this version, can be used to determine if content was just published',
  `created_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Created Date and Time',
  `created_by` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Created by User ID',
  `modified_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Modified Date',
  `modified_by` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Modified By User ID',
  `checked_out_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Checked out Date and Time',
  `checked_out_by` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Checked out by User Id',
  `root` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Used with Hierarchical Data to indicate the root node for the tree',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Used with Hierarchical Data to indicate the parent for this node.',
  `lft` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Number which increases from the root node in sequential order until the lowest branch is reached.',
  `rgt` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Number which provides sequence by decreasing in value from the lowest branch in the tree to the highest root level of the tree.',
  `lvl` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Number representing the heirarchical level of the content. The number one is the first level. ',
  `home` tinyint(6) unsigned NOT NULL DEFAULT '0',
  `customfields` longtext COMMENT 'Custom Fields for this Resource Item',
  `parameters` longtext COMMENT 'Custom Parameters for this Resource Item',
  `metadata` longtext COMMENT 'Metadata definitions for this Resource Item',
  `language` char(7) NOT NULL DEFAULT 'en-GB',
  `translation_of_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Ordering',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `molajo_groups`
--

INSERT INTO `molajo_groups` (`id`, `title`, `subtitle`, `path`, `alias`, `content_text`, `protected`, `featured`, `stickied`, `status`, `start_publishing_datetime`, `stop_publishing_datetime`, `version`, `version_of_id`, `status_prior_to_version`, `created_datetime`, `created_by`, `modified_datetime`, `modified_by`, `checked_out_datetime`, `checked_out_by`, `root`, `parent_id`, `lft`, `rgt`, `lvl`, `home`, `customfields`, `parameters`, `metadata`, `language`, `translation_of_id`, `ordering`) VALUES
(1, 'Administrator', ' ', '', 'administrator', '<p>System Administrator has ultimate control over all data, users, and extensions on the site. Use with care and protect against others accessing this account.</p>', 1, 0, 0, 1, '2012-09-13 12:00:00', '0000-00-00 00:00:00', 1, 0, 0, '2012-09-13 12:00:00', 1, '2012-09-13 12:00:00', 1, '0000-00-00 00:00:00', 0, 1, 0, 1, 18, 1, 0, '{\r\n"group_web_address":"",\r\n"group_email_address":"",\r\n"group_owner":"42",\r\n"group_collection_id":"0"\r\n}', '{}', '{}', 'en-GB', 0, 1),
(2, 'Public', ' ', '', 'public', '<p>All visitors, regardless of whether or not the visitor is logged on, are members of the <strong>Public</strong> group.</p>', 1, 0, 0, 1, '2012-09-13 12:00:00', '0000-00-00 00:00:00', 1, 0, 0, '2012-09-13 12:00:00', 1, '2012-09-13 12:00:00', 1, '0000-00-00 00:00:00', 0, 1, 0, 2, 7, 1, 0, '{\n"group_web_address":"",\n"group_email_address":"",\n"group_owner":"0",\n"group_collection_id":"0"\n}', '{}', '{}', 'en-GB', 0, 2),
(3, 'Guest', ' ', '', 'guest', '<p>Visitors who are not logged on are members of the <strong>Guest</strong> group.</p>', 1, 0, 0, 1, '2012-09-13 12:00:00', '0000-00-00 00:00:00', 1, 0, 0, '2012-09-13 12:00:00', 1, '2012-09-13 12:00:00', 1, '0000-00-00 00:00:00', 0, 1, 2, 3, 4, 2, 0, '{\r\n"group_web_address":"",\r\n"group_email_address":"",\r\n"group_owner":"0",\r\n"group_collection_id":"0"\r\n}', '{}', '{}', 'en-GB', 0, 3),
(4, 'Registered', ' ', '', 'registered', '<p>Visitors who are logged on are members of the <strong>Registered</strong> group.</p>', 1, 0, 0, 1, '2012-09-13 12:00:00', '0000-00-00 00:00:00', 1, 0, 0, '2012-09-13 12:00:00', 1, '2012-09-13 12:00:00', 1, '0000-00-00 00:00:00', 0, 1, 2, 5, 6, 2, 0, '{\r\n"group_web_address":"",\r\n"group_email_address":"",\r\n"group_owner":"0",\r\n"group_collection_id":"0"\r\n}', '{}', '{}', 'en-GB', 0, 4),
(5, 'Developer', ' ', '', 'developer', '<p>Developer group has full control over data, users, and extensions just like the System administratrator. Use with caution.</p>', 0, 0, 0, 0, '2012-09-13 12:00:00', '0000-00-00 00:00:00', 1, 0, 0, '2012-09-13 12:00:00', 1, '2012-09-13 12:00:00', 1, '0000-00-00 00:00:00', 0, 1, 1, 8, 9, 2, 0, '{\r\n"group_web_address":"",\r\n"group_email_address":"",\r\n"group_owner":"0",\r\n"group_collection_id":"0"\r\n}', '{}', '{}', 'en-GB', 0, 5),
(6, 'Manager', ' ', '', 'manager', '<p>Manager group, default configuration allows users assigned to the group with the ability to manage all content and user data, but not to manage extensions.</p>', 0, 0, 0, 0, '2012-09-13 12:00:00', '0000-00-00 00:00:00', 1, 0, 0, '2012-09-13 12:00:00', 1, '2012-09-13 12:00:00', 1, '0000-00-00 00:00:00', 0, 1, 1, 10, 17, 2, 0, '{\r\n"group_web_address":"",\r\n"group_email_address":"",\r\n"group_owner":"0",\r\n"group_collection_id":"0"\r\n}', '{}', '{}', 'en-GB', 0, 6),
(7, 'Publisher', ' ', '', 'publisher', '<p>Publisher group, typically configured to provide users assigned to the group with the ability to  <strong>create</strong>, <strong>update</strong> and <strong>publish</strong> content, but not delete or administer it. Note: use the Resource Options to enable all users to manage content they created.</p>', 0, 0, 0, 0, '2012-09-13 12:00:00', '0000-00-00 00:00:00', 1, 0, 0, '2012-09-13 12:00:00', 1, '2012-09-13 12:00:00', 1, '0000-00-00 00:00:00', 0, 1, 6, 11, 16, 3, 0, '{\r\n"group_web_address":"",\r\n"group_email_address":"",\r\n"group_owner":"0",\r\n"group_collection_id":"0"\r\n}', '{}', '{}', 'en-GB', 0, 7),
(8, 'Editor', ' ', '', 'editor', '<p>Editor group, typically configured to provide users assigned to the group with the ability to  <strong>create</strong> and <strong>update</strong> content, but not publish, delete or administer it. Note: use the Resource Options to enable all users to manage content they created.</p>', 0, 0, 0, 0, '2012-09-13 12:00:00', '0000-00-00 00:00:00', 1, 0, 0, '2012-09-13 12:00:00', 1, '2012-09-13 12:00:00', 1, '0000-00-00 00:00:00', 0, 1, 8, 12, 15, 4, 0, '{\r\n"group_web_address":"",\r\n"group_email_address":"",\r\n"group_owner":"0",\r\n"group_collection_id":"0"\r\n}', '{}', '{}', 'en-GB', 0, 8),
(9, 'Author', ' ', '', 'author', '<p>Author group, typically configured to provide users assigned to the group with the ability to  <strong>create</strong> content, but not update, publish, delete or administer it. Note: use the Resource Options to enable all users to manage content they created.</p>', 0, 0, 0, 0, '2012-09-13 12:00:00', '0000-00-00 00:00:00', 1, 0, 0, '2012-09-13 12:00:00', 1, '2012-09-13 12:00:00', 1, '0000-00-00 00:00:00', 0, 1, 9, 13, 14, 5, 0, '{\r\n"group_web_address":"",\r\n"group_email_address":"",\r\n"group_owner":"0",\r\n"group_collection_id":"0"\r\n}', '{}', '{}', 'en-GB', 0, 9);


--
-- Table structure for table `molajo_view_groups`
--

CREATE TABLE `molajo_view_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `view_group_name_list` longtext NOT NULL,
  `view_group_id_list` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `molajo_view_groups`
--

INSERT INTO `molajo_view_groups` (`id`, `view_group_name_list`, `view_group_id_list`) VALUES
(1, 'Public', '1'),
(2, 'Guest', '2'),
(3, 'Registered', '3'),
(4, 'Special', '4,7'),
(5, 'System', '8'),
(6, 'System Administrator', '18');


--
-- Table structure for table `molajo_group_view_groups`
--

CREATE TABLE `molajo_group_view_groups` (
  `group_id` int(11) unsigned NOT NULL COMMENT 'FK to the molajo_group table.',
  `view_group_id` int(11) unsigned NOT NULL COMMENT 'FK to the molajo_view_groups table.',
  PRIMARY KEY (`view_group_id`,`group_id`),
  KEY `fk_group_view_groups_view_groups_index` (`view_group_id`),
  KEY `fk_group_view_groups_groups_index` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `molajo_group_view_groups`
--

INSERT INTO `molajo_group_view_groups` (`group_id`, `view_group_id`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(7, 4),
(8, 5);


--
-- Table structure for table `molajo_users`
--

CREATE TABLE `molajo_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key for Users',
  `username` varchar(255) NOT NULL COMMENT 'Username',
  `alias` varchar(255) NOT NULL COMMENT 'User alias',
  `first_name` varchar(100) DEFAULT '' COMMENT 'First name of User',
  `last_name` varchar(150) DEFAULT '' COMMENT 'Last name of User',
  `full_name` varchar(255) NOT NULL COMMENT 'Full name of User',
  `email` varchar(255) DEFAULT '  ' COMMENT 'Email address of user',
  `content_text` longtext COMMENT 'Text for User',
  `session_key` varchar(255) DEFAULT NULL COMMENT 'Session Key for User',
  `block` tinyint(6) NOT NULL DEFAULT '0' COMMENT 'If activiated, blocks user from logging on',
  `register_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Registration date for User',
  `activation_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Activation date for User',
  `activation_code` varchar(255) DEFAULT NULL,
  `last_visit_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Last visit date for User',
  `last_activity_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Last activity date for User',
  `password_changed_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Password changed date for User',
  `password` varchar(100) NOT NULL DEFAULT '  ' COMMENT 'User password',
  `reset_password_code` varchar(255) DEFAULT NULL,
  `login_attempts` tinyint(3) unsigned NOT NULL,
  `customfields` longtext COMMENT 'Custom Fields for this User',
  `parameters` longtext COMMENT 'Custom Parameters for this User',
  `metadata` longtext COMMENT 'Metadata definitions for this User',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `last_name_first_name` (`last_name`,`first_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


--
-- Dumping data for table `molajo_users`
--

INSERT INTO `molajo_users` (`id`, `username`, `first_name`, `last_name`, `full_name`, `alias`, `content_text`, `email`, `password`, `block`, `activation_code`, `reset_password_code`, `login_attempts`, `last_activity_datetime`, `session_key`, `register_datetime`, `activation_datetime`, `last_visit_datetime`, `password_changed_datetime`, `customfields`, `parameters`, `metadata`) VALUES
(1, 'admin', 'System', 'Administrator', 'System Administrator', 'system-administrator', '', 'admin@example.com', '$2a$08$OJpUtYGvgJZIj81.JCWaqughNq6lNO5Tj2ihJuyG1QmgfwpXy9dAm', 0, NULL, '3iCLTFgMMvHVVS/Z', 0, '2013-05-23 01:54:08', '96eOn2qTUo/VhH7XpMq8os2MsQYzXrIUGpK7g91t2xaq.pzRSTONIYuuDuvSGY7B', '2012-09-13 12:00:00', '2012-09-13 00:00:00', '2013-05-23 01:05:01', '2013-05-23 01:04:19', '{"gender":"",\r\n"about_me":"<p>Bear claw macaroon candy canes topping cheesecake jelly beans macaroon. Wypas chocolate cake cookie jelly beans applicake donut. Tootsie roll danish sesame snaps faworki wypas toffee danish marshmallow bear claw. Candy canes oat cake marzipan powder gummi bears I love pastry. Donut sesame snaps topping chupa chups croissant.</p>",\r\n"phone":"402-555-1212",\r\n"editor":"",\r\n"language":"",\r\n"date_of_birth":"",\r\n"secondary_email":""}', '{"display_gravatar":"1",\r\n"display_birthdate":"1",\r\n"display_phone":"1",\r\n"display_email":"1",\r\n"theme_id":""}', '{"title":"Administrator", \r\n"description":"Administrator Profile", \r\n"keywords":"", \r\n"robots":"", \r\n"author":"", \r\n"content_rights":""}    '),
(2, 'kim', 'Kim', 'Developer', 'Kim Developer', 'kim-developer', '', 'kim@example.com', 'kim', 0, NULL, NULL, 0, '0000-00-00 00:00:00', NULL, '2012-09-13 12:00:00', '0000-00-00 00:00:00', '2012-09-13 12:00:00', '0000-00-00 00:00:00', '{"gender":"",\r\n"about_me":"",\r\n"editor":"",\r\n"language":"",\r\n"date_of_birth":"",\r\n"secondary_email":""}', '{"display_gravatar":"0",\r\n"display_birthdate":"1",\r\n"display_email":"1",\r\n"theme_id":""}', '{}'),
(3, 'pat', 'Pat', 'Manager', 'Pat Manager', 'pat-manager', '', 'pat@example.com', 'kim', 0, NULL, NULL, 0, '0000-00-00 00:00:00', NULL, '2012-09-13 12:00:00', '0000-00-00 00:00:00', '2012-09-13 12:00:00', '0000-00-00 00:00:00', '{"gender":"",\r\n"about_me":"",\r\n"editor":"",\r\n"language":"",\r\n"date_of_birth":"",\r\n"secondary_email":""}', '{"display_gravatar":"0",\r\n"display_birthdate":"1",\r\n"display_email":"1",\r\n"theme_id":""}', '{}'),
(4, 'chris', 'Chris', 'Publisher', 'Chris Publisher', 'chris-publisher', '', 'chris@example.com', 'kim', 0, NULL, NULL, 0, '0000-00-00 00:00:00', NULL, '2012-09-13 12:00:00', '0000-00-00 00:00:00', '2012-09-13 12:00:00', '0000-00-00 00:00:00', '{"gender":"",\r\n"about_me":"",\r\n"editor":"",\r\n"language":"",\r\n"date_of_birth":"",\r\n"secondary_email":""}', '{"display_gravatar":"0",\r\n"display_birthdate":"1",\r\n"display_email":"1",\r\n"theme_id":""}', '{}'),
(5, 'sam', 'Sam', 'Editor', 'Sam Editor', 'sam-editor', '', 'sam@example.com', 'sam', 0, NULL, NULL, 0, '0000-00-00 00:00:00', NULL, '2012-09-13 12:00:00', '0000-00-00 00:00:00', '2012-09-13 12:00:00', '0000-00-00 00:00:00', '{"gender":"",\r\n"about_me":"",\r\n"editor":"",\r\n"language":"",\r\n"date_of_birth":"",\r\n"secondary_email":""}', '{"display_gravatar":"0",\r\n"display_birthdate":"1",\r\n"display_email":"1",\r\n"theme_id":""}', '{}'),
(6, 'vic', 'Vic', 'Author', 'Vic Author', 'vic-author', '', 'vic@example.com', 'vic', 0, NULL, NULL, 0, '0000-00-00 00:00:00', NULL, '2012-09-13 12:00:00', '0000-00-00 00:00:00', '2012-09-13 12:00:00', '0000-00-00 00:00:00', '{"gender":"",\r\n"about_me":"",\r\n"editor":"",\r\n"language":"",\r\n"date_of_birth":"",\r\n"secondary_email":""}', '{"display_gravatar":"0",\r\n"display_birthdate":"1",\r\n"display_email":"1",\r\n"theme_id":""}', '{}'),
(7, 'joss', 'Joss', 'Registered', 'Joss Registered', 'joss-registered', '', 'joss@example.com', 'joss', 0, NULL, NULL, 0, '0000-00-00 00:00:00', NULL, '2012-09-13 12:00:00', '0000-00-00 00:00:00', '2012-09-13 12:00:00', '0000-00-00 00:00:00', '{"gender":"",\r\n"about_me":"",\r\n"editor":"",\r\n"language":"",\r\n"date_of_birth":"",\r\n"secondary_email":""}', '{"display_gravatar":"0",\r\n"display_birthdate":"1",\r\n"display_email":"1",\r\n"theme_id":""}', '{}');

-- --------------------------------------------------------

--
-- Table structure for table `molajo_user_sites`
--

CREATE TABLE `molajo_user_sites` (
  `user_id` int(11) unsigned NOT NULL COMMENT 'User ID Foreign Key',
  `site_id` int(11) unsigned NOT NULL COMMENT 'Site ID Foreign Key',
  PRIMARY KEY (`site_id`,`user_id`),
  KEY `fk_user_sites_users_index` (`user_id`),
  KEY `fk_user_sites_site_index` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `molajo_user_applications`
--

INSERT INTO `molajo_user_sites` (`user_id`, `site_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(3, 1),
(3, 2),
(4, 1),
(4, 2),
(5, 1),
(5, 2),
(6, 1),
(6, 2),
(7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `molajo_user_applications`
--

CREATE TABLE `molajo_user_applications` (
  `user_id` int(11) unsigned NOT NULL COMMENT 'User ID Foreign Key',
  `application_id` int(11) unsigned NOT NULL COMMENT 'Application ID Foreign Key',
  PRIMARY KEY (`application_id`,`user_id`),
  KEY `fk_user_applications_users_index` (`user_id`),
  KEY `fk_user_applications_applications_index` (`application_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `molajo_user_applications`
--

INSERT INTO `molajo_user_applications` (`user_id`, `application_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(3, 1),
(3, 2),
(4, 1),
(4, 2),
(5, 1),
(5, 2),
(6, 1),
(6, 2),
(7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `molajo_user_groups`
--

CREATE TABLE `molajo_user_groups` (
  `user_id` int(11) unsigned NOT NULL COMMENT 'Foreign Key to molajo_users.id',
  `group_id` int(11) unsigned NOT NULL COMMENT 'Foreign Key to molajo_groups.id',
  PRIMARY KEY (`group_id`,`user_id`),
  KEY `fk_molajo_user_groups_molajo_users_index` (`user_id`),
  KEY `fk_molajo_user_groups_molajo_groups_index` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `molajo_user_groups`
--

INSERT INTO `molajo_user_groups` (`user_id`, `group_id`) VALUES
(1, 1),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9);

-- --------------------------------------------------------

--
-- Table structure for table `molajo_user_view_groups`
--

CREATE TABLE `molajo_user_view_groups` (
  `user_id` int(11) unsigned NOT NULL COMMENT 'Foreign Key to molajo_users.id',
  `view_group_id` int(11) unsigned NOT NULL COMMENT 'Foreign Key to molajo_groups.id',
  PRIMARY KEY (`view_group_id`,`user_id`),
  KEY `fk_user_groups_users_index` (`user_id`),
  KEY `fk_user_view_groups_view_groups_index` (`view_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `molajo_user_view_groups`
--

INSERT INTO `molajo_user_view_groups` (`user_id`, `view_group_id`) VALUES
(1, 1),
(1, 3),
(1, 4),
(1, 5),
(1, 6);


-- --------------------------------------------------------


--
-- Table structure for table `molajo_group_permissions`
--

CREATE TABLE `molajo_group_permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) unsigned NOT NULL COMMENT 'Foreign Key to molajo_groups.id',
  `catalog_id` int(11) unsigned NOT NULL COMMENT 'Foreign Key to molajo_catalog.id',
  `action_id` int(11) unsigned NOT NULL COMMENT 'Foreign Key to molajo_actions.id',
  PRIMARY KEY (`id`),
  KEY `fk_group_permissions_actions_index` (`action_id`),
  KEY `fk_group_permissions_group_index` (`group_id`),
  KEY `fk_group_permissions_catalog_index` (`catalog_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `molajo_view_group_permissions`
--

CREATE TABLE `molajo_view_group_permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `view_group_id` int(11) unsigned NOT NULL COMMENT 'Foreign Key to molajo_groups.id',
  `catalog_id` int(11) unsigned NOT NULL COMMENT 'Foreign Key to molajo_catalog.id',
  `action_id` int(11) unsigned NOT NULL COMMENT 'Foreign Key to molajo_actions.id',
  PRIMARY KEY (`id`),
  KEY `fk_view_group_permissions_view_groups_index` (`view_group_id`),
  KEY `fk_view_group_permissions_actions_index` (`action_id`),
  KEY `fk_view_group_permissions_catalog_index` (`catalog_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table structure for table `molajo_user_activity`
--

CREATE TABLE `molajo_user_activity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'User Activity Primary Key',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'User ID Foreign Key',
  `action_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Action ID Foreign Key',
  `catalog_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Catalog ID Foreign Key',
  `session_id`varchar(255) NOT NULL DEFAULT '' COMMENT 'User Session ID',
  `activity_datetime` datetime DEFAULT NULL COMMENT 'Activity Datetime',
  `ip_address` varchar(15) NOT NULL DEFAULT '' COMMENT 'IP Address',
  PRIMARY KEY (`id`),
  KEY `user_activity_catalog_index` (`catalog_id`),
  KEY `user_activity_action_index` (`action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `molajo_sessions`
--

CREATE TABLE `molajo_sessions` (
  `session_id` varchar(255) NOT NULL,
  `site_id` int(11) unsigned NOT NULL,
  `application_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned DEFAULT '0',
  `session_time` datetime DEFAULT NULL,
  `data` longtext,
  `activity_datetime` datetime DEFAULT NULL COMMENT 'Activity Datetime',
  `ip_address` varchar(15) NOT NULL DEFAULT '' COMMENT 'IP Address',
  PRIMARY KEY (`session_id`),
  KEY `fk_sessions_applications_index` (`site_id`, `application_id`, `session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Constraints for dumped tables
--

--
-- Constraints for table `molajo_group_permissions`
--
ALTER TABLE `molajo_group_permissions`
  ADD CONSTRAINT `fk_group_permissions_actions_1` FOREIGN KEY (`action_id`) REFERENCES `molajo_actions` (`id`),
  ADD CONSTRAINT `fk_group_permissions_catalog_1` FOREIGN KEY (`catalog_id`) REFERENCES `molajo_catalog` (`id`),
  ADD CONSTRAINT `fk_group_permissions_groups_1` FOREIGN KEY (`group_id`) REFERENCES `molajo_groups` (`id`);

--
-- Constraints for table `molajo_group_view_groups`
--
ALTER TABLE `molajo_group_view_groups`
  ADD CONSTRAINT `fk_group_view_groups_groups` FOREIGN KEY (`group_id`) REFERENCES `molajo_groups` (`id`),
  ADD CONSTRAINT `fk_group_view_groups_view_groups` FOREIGN KEY (`view_group_id`) REFERENCES `molajo_view_groups` (`id`);

--
-- Constraints for table `molajo_user_activity`
--
ALTER TABLE `molajo_user_activity`
  ADD CONSTRAINT `fk_user_activity_catalog_1` FOREIGN KEY (`catalog_id`) REFERENCES `molajo_catalog` (`id`),
  ADD CONSTRAINT `fk_user_activity_users_1` FOREIGN KEY (`user_id`) REFERENCES `molajo_users` (`id`);

--
-- Constraints for table `molajo_user_applications`
--
ALTER TABLE `molajo_user_applications`
  ADD CONSTRAINT `fk_user_applications_users` FOREIGN KEY (`user_id`) REFERENCES `molajo_users` (`id`);

--
-- Constraints for table `molajo_user_groups`
--
ALTER TABLE `molajo_user_groups`
  ADD CONSTRAINT `fk_user_groups_groups_1` FOREIGN KEY (`group_id`) REFERENCES `molajo_groups` (`id`),
  ADD CONSTRAINT `fk_user_groups_users_1` FOREIGN KEY (`user_id`) REFERENCES `molajo_users` (`id`);

--
-- Constraints for table `molajo_user_view_groups`
--
ALTER TABLE `molajo_user_view_groups`
  ADD CONSTRAINT `fk_user_view_groups_users_1` FOREIGN KEY (`user_id`) REFERENCES `molajo_users` (`id`),
  ADD CONSTRAINT `fk_user_view_groups_view_groups_1` FOREIGN KEY (`view_group_id`) REFERENCES `molajo_view_groups` (`id`);

--
-- Constraints for table `molajo_view_group_permissions`
--
ALTER TABLE `molajo_view_group_permissions`
  ADD CONSTRAINT `fk_view_group_permissions_actions_1` FOREIGN KEY (`action_id`) REFERENCES `molajo_actions` (`id`),
  ADD CONSTRAINT `fk_view_group_permissions_catalog_1` FOREIGN KEY (`catalog_id`) REFERENCES `molajo_catalog` (`id`),
  ADD CONSTRAINT `fk_view_group_permissions_view_groups_1` FOREIGN KEY (`view_group_id`) REFERENCES `molajo_view_groups` (`id`);

