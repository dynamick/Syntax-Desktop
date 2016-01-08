# MySQL dump of database 'syntax' on host 'localhost'
# backup date and time: 01/08/16 18:29:42
# built by phpMyBackupPro v.2.1
# http://www.phpMyBackupPro.net



### structure of table `aa_element` ###

DROP TABLE IF EXISTS `aa_element`;

CREATE TABLE `aa_element` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `classname` varchar(255) NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`classname`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='Elementi che compongono un contenitore' AUTO_INCREMENT=33;


### data of table `aa_element` ###

insert into `aa_element` values ('1', 'Key', 'synKey', '1');
insert into `aa_element` values ('2', 'TextField', 'synText', '10');
insert into `aa_element` values ('3', 'TextField Numeric', 'synTextNumeric', '15');
insert into `aa_element` values ('4', 'Password', 'synPassword', '20');
insert into `aa_element` values ('5', 'File', 'synInputfile', '30');
insert into `aa_element` values ('6', 'TextArea', 'synTextArea', '40');
insert into `aa_element` values ('8', 'Textarea simple with Countdown', 'synTextAreaSimple', '45');
insert into `aa_element` values ('9', 'CheckBox', 'synCheck', '50');
insert into `aa_element` values ('10', 'Image Uploader', 'synUpload', '55');
insert into `aa_element` values ('11', 'Select', 'synSelect', '60');
insert into `aa_element` values ('12', 'Select Qry', 'synSelectQry', '70');
insert into `aa_element` values ('13', 'Select File', 'synSelectFile', '75');
insert into `aa_element` values ('14', 'Date', 'synDate', '80');
insert into `aa_element` values ('15', 'Date and Time', 'synDateTime', '90');
insert into `aa_element` values ('16', 'Tree', 'synTree', '100');
insert into `aa_element` values ('17', 'Tree Access Point', 'synTreeGroup', '102');
insert into `aa_element` values ('18', 'Owner', 'synOwner', '108');
insert into `aa_element` values ('19', 'Preview', 'synPreview', '110');
insert into `aa_element` values ('20', 'Last Update', 'synLastUpdate', '120');
insert into `aa_element` values ('21', 'User Create', 'synUserCreate', '130');
insert into `aa_element` values ('22', 'User Modified', 'synUserModified', '140');
insert into `aa_element` values ('23', 'Radio buttons', 'synRadio', '95');
insert into `aa_element` values ('24', 'Multi Checkbox', 'synSelectMultiCheck', '98');
insert into `aa_element` values ('25', 'Choose Pictures from tags', 'synPictureTag', '57');
insert into `aa_element` values ('26', 'Choose multi Pictures from tags', 'synMultiPictureTag', '57');
insert into `aa_element` values ('27', 'TextField Numeric Decimal', 'synTextDecimal', '150');
insert into `aa_element` values ('28', 'Text Multiple Join', 'synTextJoin', '160');
insert into `aa_element` values ('29', 'Page Slug', 'synSlug', '170');
insert into `aa_element` values ('30', 'Date and Time (read-only)', 'synDateTimeReadonly', '91');
insert into `aa_element` values ('31', 'Icon picker', 'synIcon', '140');
insert into `aa_element` values ('32', 'Address Picker', 'synAddressPicker', '180');


### structure of table `aa_group_services` ###

DROP TABLE IF EXISTS `aa_group_services`;

CREATE TABLE `aa_group_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(11) NOT NULL DEFAULT '0',
  `parent` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `group` int(11) NOT NULL DEFAULT '0',
  `service` int(11) NOT NULL DEFAULT '0',
  `filter` varchar(255) NOT NULL,
  `insert` varchar(255) NOT NULL,
  `modify` varchar(255) NOT NULL,
  `delete` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `ip` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group` (`group`)
) ENGINE=MyISAM AUTO_INCREMENT=237 DEFAULT CHARSET=utf8 AUTO_INCREMENT=237;


### data of table `aa_group_services` ###

insert into `aa_group_services` values ('9', '3010', '18', '10', '1', '2', '', '1', '1', '1', '', 'user.png', '');
insert into `aa_group_services` values ('15', '3020', '18', '11', '1', '3', '', '1', '1', '1', '', 'accept.png', '');
insert into `aa_group_services` values ('18', '30', '0', '9', '1', '0', '', '1', '1', '1', '', 'star.png', '');
insert into `aa_group_services` values ('54', '1010', '152', '7', '1', '116', '', '1', '1', '1', '', 'application_double.png', '');
insert into `aa_group_services` values ('64', '3030', '18', '8', '1', '124', '', '1', '1', '1', '', 'image.png', '');
insert into `aa_group_services` values ('76', '1020', '152', '740', '1', '127', '', '1', '1', '1', '', 'email.png', '');
insert into `aa_group_services` values ('125', '10', '128', '603', '2', '2', '', '', '', '', '', '.svn', '');
insert into `aa_group_services` values ('128', '40', '0', '555', '2', '0', '', '', '', '', '', 'accept.png', '');
insert into `aa_group_services` values ('129', '10', '131', '741', '2', '116', '', '', '', '', '', '', '');
insert into `aa_group_services` values ('130', '20', '131', '742', '2', '124', '', '', '', '', '', '', '');
insert into `aa_group_services` values ('131', '10', '0', '743', '2', '0', '', '', '', '', '', '', '');
insert into `aa_group_services` values ('135', '3040', '18', '12', '1', '5', '', '1', '1', '1', '', 'pencil.png', '');
insert into `aa_group_services` values ('151', '3050', '18', '13', '1', '136', '', '1', '1', '1', '', 'bricks.png', '');
insert into `aa_group_services` values ('152', '10', '0', '6', '1', '0', '', '', '', '', '', 'page_white_edit.png', '');
insert into `aa_group_services` values ('153', '40', '0', '15', '1', '0', '', '', '', '', '', 'wrench_orange.png', '');
insert into `aa_group_services` values ('159', '4020', '153', '16', '1', '0', '', '', '', '', 'modules/phpMyBackupPro/', 'database_save.png', 'fa-database');
insert into `aa_group_services` values ('169', '50', '0', '17', '1', '0', '', '', '', '', '', 'help.png', '');
insert into `aa_group_services` values ('170', '5010', '169', '18', '1', '0', '', '', '', '', 'modules/help/doc.html', 'help.png', '');
insert into `aa_group_services` values ('171', '5020', '169', '19', '1', '0', '', '', '', '', 'modules/credits/index.php', 'bricks.png', '');
insert into `aa_group_services` values ('172', '3060', '18', '14', '1', '137', '', '1', '1', '1', '', 'world.png', '');
insert into `aa_group_services` values ('175', '5030', '169', '196', '1', '0', '', '', '', '', '', 'star.png', '');
insert into `aa_group_services` values ('176', '503010', '175', '197', '1', '0', '', '', '', '', 'http://www.dynamick.it/syntax-desktop/UI_dsl.html', 'image.png', '');
insert into `aa_group_services` values ('177', '503020', '175', '198', '1', '0', '', '', '', '', 'http://www.dynamick.it/syntax-desktop/serviceomatic_dsl.html', 'cog.png', '');
insert into `aa_group_services` values ('184', '1030', '152', '251', '1', '142', '', '1', '1', '1', '', '', '');
insert into `aa_group_services` values ('187', '20', '0', '279', '1', '0', '', '1', '1', '1', '', 'seasons.png', '');
insert into `aa_group_services` values ('188', '30', '0', '280', '2', '0', '', '1', '1', '1', '/admin/modules/aa/custom/media_upload.php', 'seasons.png', '');
insert into `aa_group_services` values ('189', '5010', '187', '297', '1', '144', '', '1', '1', '1', '', 'images.png', 'fa-file-picture-o');
insert into `aa_group_services` values ('190', '20', '188', '298', '2', '144', '', '1', '1', '1', '', '', '');
insert into `aa_group_services` values ('191', '5020', '187', '299', '1', '0', '', '1', '1', '1', '/admin/modules/aa/custom/media_upload.php', 'folder_page.png', '');
insert into `aa_group_services` values ('192', '10', '188', '300', '2', '0', '', '1', '1', '1', '/admin/modules/aa/custom/media_upload.php', 'folder_page.png', '');
insert into `aa_group_services` values ('193', '5030', '187', '307', '1', '145', '', '1', '1', '1', '', '', '');
insert into `aa_group_services` values ('194', '30', '188', '308', '2', '145', '', '1', '1', '1', '', '', '');
insert into `aa_group_services` values ('195', '5040', '187', '317', '1', '146', '', '1', '1', '1', '', '.svn', '');
insert into `aa_group_services` values ('196', '40', '188', '318', '2', '146', '', '1', '1', '1', '', '', '');
insert into `aa_group_services` values ('197', '104020', '205', '325', '1', '147', '', '1', '1', '1', '', 'accept.png', '');
insert into `aa_group_services` values ('198', '20', '207', '326', '2', '147', '', '1', '1', '1', '', 'accept.png', '');
insert into `aa_group_services` values ('199', '104010', '205', '359', '1', '148', '', '1', '1', '1', '', 'accept.png', '');
insert into `aa_group_services` values ('200', '10', '207', '360', '2', '148', '', '1', '1', '1', '', 'accept.png', '');
insert into `aa_group_services` values ('201', '105010', '206', '377', '1', '149', '', '1', '1', '1', '', '.svn', '');
insert into `aa_group_services` values ('202', '0', '208', '378', '2', '149', '', '1', '1', '1', '', '.svn', '');
insert into `aa_group_services` values ('203', '105020', '206', '387', '1', '150', '', '1', '1', '1', '', '.svn', '');
insert into `aa_group_services` values ('204', '20', '208', '388', '2', '150', '', '1', '1', '1', '', '.svn', '');
insert into `aa_group_services` values ('205', '1040', '152', '389', '1', '0', '', '1', '1', '1', '', 'group.png', '');
insert into `aa_group_services` values ('206', '1050', '152', '390', '1', '0', '', '1', '1', '1', '', 'application_double.png', '');
insert into `aa_group_services` values ('207', '20', '0', '391', '2', '0', '', '1', '1', '1', '', 'group.png', '');
insert into `aa_group_services` values ('208', '15', '0', '393', '2', '0', '', '1', '1', '1', '', 'arrow_rotate_anticlockwise.png', '');
insert into `aa_group_services` values ('209', '10', '210', '392', '3', '2', '', '', '1', '', '', '.svn', '');
insert into `aa_group_services` values ('210', '40', '0', '557', '3', '0', '', '', '', '', '', 'accept.png', '');
insert into `aa_group_services` values ('211', '10', '213', '556', '3', '116', '', '', '', '', '', 'accept.png', '');
insert into `aa_group_services` values ('213', '10', '0', '744', '3', '0', '', '', '', '', '', '', '');
insert into `aa_group_services` values ('225', '1070', '152', '408', '1', '151', '', '1', '1', '1', '', '', '');
insert into `aa_group_services` values ('226', '0', '213', '409', '3', '151', '', '1', '1', '1', '', '', '');
insert into `aa_group_services` values ('227', '0', '131', '410', '2', '151', '', '1', '1', '1', '', '', '');
insert into `aa_group_services` values ('228', '4030', '153', '413', '1', '0', '', '', '', '', '/admin/modules/sitemap/sitemap.php', 'chart_organisation.png', '');
insert into `aa_group_services` values ('229', '1060', '152', '437', '1', '152', '', '1', '1', '1', '', '', '');
insert into `aa_group_services` values ('230', '4030', '153', '500', '1', '0', '', '1', '1', '1', '/admin/modules/export/export_xml.php', 'accept.png', '');
insert into `aa_group_services` values ('231', '4040', '153', '501', '1', '0', '', '', '', '', '/admin/modules/import/import_xml.php', 'wand.png', '');
insert into `aa_group_services` values ('232', '3080', '18', '627', '1', '156', '', '1', '1', '1', '', '', '');
insert into `aa_group_services` values ('233', '3060', '18', '728', '1', '157', '', '1', '1', '1', '', 'accept.png', '');
insert into `aa_group_services` values ('234', '3095', '18', '739', '1', '158', '', '', '1', '1', '', 'accept.png', '');
insert into `aa_group_services` values ('235', '3105', '18', '802', '1', '159', '', '1', '1', '1', '', '', '');
insert into `aa_group_services` values ('236', '3115', '18', '803', '1', '160', '', '1', '1', '1', '', '', '');


### structure of table `aa_groups` ###

DROP TABLE IF EXISTS `aa_groups`;

CREATE TABLE `aa_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 AUTO_INCREMENT=4;


### data of table `aa_groups` ###

insert into `aa_groups` values ('1', 'Admins Group', '0');
insert into `aa_groups` values ('2', 'Editors Group', '1');
insert into `aa_groups` values ('3', 'Authors Group', '2');


### structure of table `aa_lang` ###

DROP TABLE IF EXISTS `aa_lang`;

CREATE TABLE `aa_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang` varchar(255) NOT NULL,
  `initial` varchar(10) NOT NULL,
  `flag` varchar(255) NOT NULL,
  `active` varchar(255) NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  `default` varchar(255) NOT NULL,
  `domain` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;


### data of table `aa_lang` ###

insert into `aa_lang` values ('1', 'italiano', 'it', 'italy.png', '1', '10', '1', '');
insert into `aa_lang` values ('2', 'english', 'en', 'greatbritain.png', '1', '20', '', '');


### structure of table `aa_logs` ###

DROP TABLE IF EXISTS `aa_logs`;

CREATE TABLE `aa_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `request_uri` varchar(255) NOT NULL,
  `response` text NOT NULL,
  `redirect_id` int(11) NOT NULL DEFAULT '0',
  `dispatched` varchar(255) NOT NULL,
  `referer` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;


### data of table `aa_logs` ###



### structure of table `aa_page` ###

DROP TABLE IF EXISTS `aa_page`;

CREATE TABLE `aa_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `parent` int(11) NOT NULL,
  `template` int(11) NOT NULL,
  `visible` varchar(255) NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  `owner` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `metatitle` varchar(75) NOT NULL,
  `metadescription` varchar(150) NOT NULL,
  `metakeywords` varchar(175) NOT NULL,
  `slug` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=utf8 AUTO_INCREMENT=60;


### data of table `aa_page` ###

insert into `aa_page` values ('22', '1', '2', '0', '14', '1|2', '5', '1', '', '575', '576', '577', '632');
insert into `aa_page` values ('39', '203', '204', '22', '4', '1|2', '10', '1', '', '4', '5', '587', '633');
insert into `aa_page` values ('40', '206', '207', '22', '4', '', '20', '1', '', '635', '636', '637', '634');
insert into `aa_page` values ('41', '209', '210', '22', '4', '1|2', '40', '1', '', '588', '589', '590', '638');
insert into `aa_page` values ('42', '211', '212', '22', '13', '1|2', '50', '1', '', '591', '592', '593', '639');
insert into `aa_page` values ('43', '213', '214', '22', '4', '1|2', '15', '1', '', '594', '595', '596', '640');
insert into `aa_page` values ('44', '216', '217', '41', '4', '1|2', '10', '1', '', '606', '607', '608', '656');
insert into `aa_page` values ('45', '219', '220', '41', '4', '1|2', '20', '1', '', '609', '610', '611', '657');
insert into `aa_page` values ('46', '221', '222', '41', '4', '1|2', '15', '1', '', '612', '613', '614', '658');
insert into `aa_page` values ('50', '274', '275', '57', '8', '', '1010', '1', '', '642', '643', '644', '641');
insert into `aa_page` values ('51', '277', '278', '57', '4', '', '1020', '1', '', '646', '647', '648', '645');
insert into `aa_page` values ('52', '394', '395', '57', '9', '', '90', '1', '', '650', '651', '652', '649');
insert into `aa_page` values ('53', '396', '397', '22', '10', '1|2', '70', '1', '', '597', '598', '599', '653');
insert into `aa_page` values ('54', '398', '399', '22', '11', '1|2', '80', '1', '', '600', '601', '602', '654');
insert into `aa_page` values ('55', '489', '490', '22', '12', '1|2', '45', '1', '', '603', '604', '605', '655');
insert into `aa_page` values ('56', '564', '565', '41', '4', '1|2', '330', '1', '', '572', '573', '574', '659');
insert into `aa_page` values ('57', '688', '690', '0', '4', '', '1000', '1', '', '691', '692', '693', '689');
insert into `aa_page` values ('58', '566', '568', '57', '15', '', '9090', '1', '', '569', '570', '571', '567');
insert into `aa_page` values ('59', '745', '747', '57', '4', '', '3030', '1', '', '748', '749', '750', '746');


### structure of table `aa_service_joins` ###

DROP TABLE IF EXISTS `aa_service_joins`;

CREATE TABLE `aa_service_joins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `from` int(11) NOT NULL DEFAULT '0',
  `to` int(11) NOT NULL DEFAULT '0',
  `description` varchar(255) NOT NULL,
  `container` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `container` (`container`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 AUTO_INCREMENT=26;


### data of table `aa_service_joins` ###

insert into `aa_service_joins` values ('17', 'menu', '54', '72', '', '3');
insert into `aa_service_joins` values ('18', 'Joins', '45', '425', 'Join between two services', '5');
insert into `aa_service_joins` values ('19', 'Elements', '45', '410', 'List of the elements of this service', '5');
insert into `aa_service_joins` values ('20', 'Photo', '467', '475', 'List of the related photos', '142');
insert into `aa_service_joins` values ('21', 'Fieldset', '528', '549', '', '152');
insert into `aa_service_joins` values ('22', 'Campi', '528', '539', '', '152');
insert into `aa_service_joins` values ('23', 'Opzioni', '538', '553', '', '153');
insert into `aa_service_joins` values ('24', 'Dati inviati', '528', '587', '', '152');
insert into `aa_service_joins` values ('25', 'Occurences', '598', '608', '', '159');


### structure of table `aa_services` ###

DROP TABLE IF EXISTS `aa_services`;

CREATE TABLE `aa_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `syntable` varchar(255) NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  `dbsync` varchar(255) NOT NULL,
  `initOrder` int(8) NOT NULL DEFAULT '0',
  `multilang` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=161 DEFAULT CHARSET=utf8 PACK_KEYS=1 AUTO_INCREMENT=161;


### data of table `aa_services` ###

insert into `aa_services` values ('2', '26', '', 'images/service_icon/user.png', '27', '2', 'aa_users', '10', '1', '39', '');
insert into `aa_services` values ('3', '28', '', 'images/service_icon/group.png', '29', '2', 'aa_groups', '20', '1', '477', '');
insert into `aa_services` values ('4', '34', '', 'images/service_icon/chart_organisation.png', '35', '2', 'aa_group_services', '60', '1', '74', '');
insert into `aa_services` values ('5', '20', '', 'images/service_icon/lightning.png', '21', '2', 'aa_services', '30', '1', '52', '1');
insert into `aa_services` values ('116', '36', '', 'images/service_icon/page_white_stack.png', '37', '2', 'aa_page', '70', '1', '350', '1');
insert into `aa_services` values ('124', '38', '', 'images/service_icon/layout.png', '39', '2', 'aa_template', '80', '1', '378', '');
insert into `aa_services` values ('127', '40', '', 'images/service_icon/newspaper.png', '41', '2', 'news', '100', '1', '-460', '1');
insert into `aa_services` values ('128', '32', '', 'images/service_icon/table_relationship.png', '33', '2', 'aa_service_joins', '50', '1', '405', '');
insert into `aa_services` values ('129', '30', '', 'images/service_icon/table.png', '31', '2', 'aa_services_element', '40', '1', '423', '1');
insert into `aa_services` values ('136', '22', '', 'images/service_icon/bricks.png', '23', '2', 'aa_lang', '120', '1', '560', '');
insert into `aa_services` values ('137', '24', '', 'images/service_icon/table.png', '25', '2', 'aa_translation', '110', '1', '454', '');
insert into `aa_services` values ('142', '241', '', 'images/service_icon/report.png', '242', '0', 'album', '130', '1', '-469', '');
insert into `aa_services` values ('143', '252', '', 'images/service_icon/picture.png', '253', '0', 'photos', '140', '1', '471', '');
insert into `aa_services` values ('144', '281', '', 'images/service_icon/images.png', '282', '0', 'media', '150', '1', '-488', '');
insert into `aa_services` values ('145', '301', '', 'images/service_icon/tag_blue.png', '302', '0', 'tags', '160', '1', '490', '');
insert into `aa_services` values ('146', '309', '', 'images/service_icon/vcard.png', '310', '0', 'tagged', '170', '1', '492', '');
insert into `aa_services` values ('147', '319', '', 'images/service_icon/group.png', '320', '2', 'groups', '180', '1', '494', '');
insert into `aa_services` values ('148', '327', '', 'images/service_icon/user_gray.png', '328', '2', 'users', '190', '1', '508', '');
insert into `aa_services` values ('149', '361', '', 'images/service_icon/layout.png', '362', '0', 'documents', '200', '1', '515', '1');
insert into `aa_services` values ('150', '379', '', 'images/service_icon/chart_organisation.png', '380', '0', 'categories', '210', '1', '523', '1');
insert into `aa_services` values ('151', '400', '', 'images/service_icon/book_open.png', '401', '0', 'dictionary', '5', '1', '525', '1');
insert into `aa_services` values ('152', '417', '', 'fa-edit', '418', '0', 'forms', '220', '1', '536', '');
insert into `aa_services` values ('153', '438', '', 'images/service_icon/table_relationship.png', '439', '2', 'form_fields', '240', '1', '547', '1');
insert into `aa_services` values ('154', '460', '', 'images/service_icon/application_double.png', '461', '0', 'form_fieldsets', '230', '1', '551', '1');
insert into `aa_services` values ('155', '470', '', 'images/service_icon/chart_organisation.png', '471', '2', 'field_options', '250', '1', '557', '1');
insert into `aa_services` values ('156', '617', '', 'images/service_icon/plugin.png', '618', '2', 'aa_element', '0', '1', '569', '');
insert into `aa_services` values ('157', '718', '', 'images/service_icon/emoticon_smile.png', '719', '0', 'social_network', '0', '1', '583', '');
insert into `aa_services` values ('158', '729', '', 'images/service_icon/page_white_edit.png', '730', '2', 'dati_inviati', '0', '1', '-589', '');
insert into `aa_services` values ('159', '772', '', 'fa-refresh', '773', '0', 'redirect', '0', '1', '598', '');
insert into `aa_services` values ('160', '783', '', 'fa-paper-plane', '784', '0', 'aa_logs', '0', '1', '603', '');


### structure of table `aa_services_element` ###

DROP TABLE IF EXISTS `aa_services_element`;

CREATE TABLE `aa_services_element` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `container` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `type` int(255) NOT NULL DEFAULT '0',
  `iskey` varchar(1) NOT NULL,
  `isvisible` varchar(1) NOT NULL,
  `iseditable` varchar(1) NOT NULL,
  `label` varchar(255) NOT NULL,
  `size` int(8) NOT NULL DEFAULT '0',
  `help` text NOT NULL,
  `path` varchar(255) NOT NULL,
  `qry` text NOT NULL,
  `value` varchar(255) NOT NULL,
  `joins` varchar(255) NOT NULL,
  `order` int(8) NOT NULL DEFAULT '0',
  `filter` varchar(255) NOT NULL,
  `ismultilang` varchar(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `container` (`container`)
) ENGINE=MyISAM AUTO_INCREMENT=611 DEFAULT CHARSET=utf8 COMMENT='Containers' AUTO_INCREMENT=611;


### data of table `aa_services_element` ###

insert into `aa_services_element` values ('39', '2', 'id', '1', '1', '', '', '92', '0', '93', '', '', '', '', '5', '', '');
insert into `aa_services_element` values ('40', '2', 'login', '2', '', '1', '1', '94', '255', '95', '', '', '', '', '15', '', '');
insert into `aa_services_element` values ('41', '2', 'passwd', '4', '', '1', '', '96', '255', '97', '', '', '', '', '25', '', '');
insert into `aa_services_element` values ('42', '2', 'id_group', '11', '', '1', '', '98', '255', '99', '', 'SELECT * FROM aa_groups', '', '', '35', '', '');
insert into `aa_services_element` values ('44', '2', 'lang', '11', '', '1', '', '100', '11', '101', '', 'SELECT * FROM aa_lang', '', '', '55', '', '');
insert into `aa_services_element` values ('45', '5', 'id', '1', '1', '1', '', '106', '0', '107', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('46', '5', 'name', '2', '', '1', '1', '108', '255', '109', '', '', '', '', '10', '', '1');
insert into `aa_services_element` values ('47', '5', 'path', '2', '', '', '1', '110', '255', '111', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('48', '5', 'icon', '2', '', '', '', '112', '255', '113', '', '', '', '', '30', '', '');
insert into `aa_services_element` values ('49', '5', 'description', '8', '', '', '', '114', '150', '115', '', '', '', '', '40', '', '1');
insert into `aa_services_element` values ('50', '5', 'parent', '12', '', '', '', '116', '11', '117', '', 'select * from aa_services', '', '', '50', '', '');
insert into `aa_services_element` values ('51', '5', 'syntable', '2', '', '1', '', '118', '255', '119', '', '', '', '', '60', '', '');
insert into `aa_services_element` values ('52', '5', 'order', '3', '', '1', '1', '120', '11', '121', '', '', '', '', '70', '', '');
insert into `aa_services_element` values ('53', '5', 'dbsync', '9', '', '1', '', '122', '255', '123', '', '', '1', '', '80', '', '');
insert into `aa_services_element` values ('54', '3', 'id', '1', '1', '', '', '102', '0', '103', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('55', '3', 'name', '2', '', '1', '1', '104', '255', '105', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('70', '4', 'id', '1', '1', '1', '', '140', '0', '141', '', '', '', '', '5', '', '');
insert into `aa_services_element` values ('71', '4', 'name', '2', '', '1', '1', '142', '255', '143', '', '', '', '', '15', '', '1');
insert into `aa_services_element` values ('72', '4', 'group', '3', '', '', '', '152', '11', '153', '', '', '', '', '55', '', '');
insert into `aa_services_element` values ('73', '4', 'service', '12', '', '1', '', '144', '11', '145', '1', 'select * from aa_services', '', '', '25', '', '');
insert into `aa_services_element` values ('74', '4', 'order', '3', '', '1', '1', '148', '11', '149', '', '', '', '', '35', '', '');
insert into `aa_services_element` values ('75', '4', 'parent', '16', '', '1', '', '154', '255', '155', 'name', '', '', '', '65', '', '');
insert into `aa_services_element` values ('80', '4', 'filter', '2', '', '1', '1', '150', '255', '151', '', '', '', '', '45', '', '');
insert into `aa_services_element` values ('141', '5', 'initOrder', '3', '', '', '', '126', '8', '127', '', '', '', '', '90', '', '');
insert into `aa_services_element` values ('347', '116', 'id', '1', '1', '', '', '164', '0', '165', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('348', '116', 'title', '2', '', '1', '', '166', '255', '167', '', '', '', '', '10', '', '1');
insert into `aa_services_element` values ('349', '116', 'text', '6', '', '', '', '168', '350', '169', 'Default', '', '', '', '20', '', '1');
insert into `aa_services_element` values ('350', '116', 'parent', '16', '', '1', '', '170', '11', '171', 'title', '', '', '', '30', '', '');
insert into `aa_services_element` values ('372', '116', 'template', '11', '', '1', '', '172', '11', '173', '', 'SELECT * FROM aa_template', '', '', '40', '', '');
insert into `aa_services_element` values ('378', '124', 'id', '1', '1', '', '', '178', '0', '179', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('379', '124', 'title', '2', '', '1', '1', '180', '255', '181', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('380', '124', 'timestamp', '15', '', '1', '', '182', '0', '183', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('381', '124', 'filename', '13', '', '1', '', '184', '255', '185', '1', '/public/templates', '', '', '30', '', '');
insert into `aa_services_element` values ('478', '116', 'owner', '18', '', '1', '', '266', '0', '267', '', '', '', '', '80', '', '');
insert into `aa_services_element` values ('397', '116', 'visible', '24', '', '1', '', '174', '255', '175', '', 'SELECT id, lang FROM `aa_lang` ', '1', '', '50', '', '');
insert into `aa_services_element` values ('398', '127', 'id', '1', '1', '', '', '76', '0', '77', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('403', '116', 'order', '3', '', '1', '1', '176', '11', '177', '', '', '', '', '60', '', '');
insert into `aa_services_element` values ('404', '128', 'id', '1', '1', '', '', '128', '0', '129', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('405', '128', 'title', '2', '', '1', '1', '130', '255', '131', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('406', '128', 'from', '12', '', '1', '', '132', '11', '133', '', 'SELECT e.id, concat( \'(\',s.name,  \') - \', e.name,  \'\'  )  FROM  `aa_services_element` e,  `aa_services` s WHERE e.container = s.id ORDER  BY s.name, e.order', '', '', '20', '', '');
insert into `aa_services_element` values ('407', '128', 'to', '12', '', '1', '', '134', '11', '135', '', 'SELECT e.id, concat( \'(\',t.en,  \') - \', e.name,  \'\'  )  FROM  `aa_services_element` e,  `aa_services` s,   `aa_translation` t WHERE e.container = s.id AND t.id=s.name ORDER  BY s.name, e.order', '', '', '30', '', '');
insert into `aa_services_element` values ('408', '128', 'description', '2', '', '1', '1', '136', '255', '137', '', '', '', '', '40', '', '');
insert into `aa_services_element` values ('409', '129', 'id', '1', '1', '', '', '42', '0', '43', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('410', '129', 'container', '3', '', '', '', '44', '11', '45', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('411', '129', 'name', '2', '', '1', '', '46', '255', '47', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('412', '129', 'type', '12', '', '1', '', '48', '255', '49', '', 'select * from aa_element', '', '', '30', '', '');
insert into `aa_services_element` values ('413', '129', 'iskey', '9', '', '', '', '50', '1', '51', '', '', '1', '', '40', '', '');
insert into `aa_services_element` values ('414', '129', 'isvisible', '9', '', '', '', '52', '1', '53', '', '', '1', '', '50', '', '');
insert into `aa_services_element` values ('415', '129', 'iseditable', '9', '', '', '', '54', '1', '55', '', '', '1', '', '60', '', '');
insert into `aa_services_element` values ('416', '129', 'label', '2', '', '1', '', '58', '255', '59', '', '', '', '', '70', '', '1');
insert into `aa_services_element` values ('417', '129', 'size', '3', '', '', '', '60', '8', '61', '', '', '', '', '80', '', '');
insert into `aa_services_element` values ('418', '129', 'help', '8', '', '1', '', '62', '0', '63', '', '', '', '', '90', '', '1');
insert into `aa_services_element` values ('419', '129', 'path', '2', '', '', '', '64', '255', '65', '', '', '', '', '100', '', '');
insert into `aa_services_element` values ('420', '129', 'qry', '8', '', '', '', '66', '0', '67', '', '', '', '', '110', '', '');
insert into `aa_services_element` values ('421', '129', 'value', '2', '', '', '', '68', '255', '69', '', '', '', '', '120', '', '');
insert into `aa_services_element` values ('422', '129', 'joins', '2', '', '', '', '70', '255', '71', '', '', '', '', '130', '', '');
insert into `aa_services_element` values ('423', '129', 'order', '3', '', '1', '1', '72', '8', '73', '', '', '', '', '140', '', '');
insert into `aa_services_element` values ('424', '129', 'filter', '2', '', '', '', '74', '255', '75', '', '', '', '', '150', '', '');
insert into `aa_services_element` values ('425', '128', 'container', '3', '', '', '', '138', '11', '139', '', '', '', '', '50', '', '');
insert into `aa_services_element` values ('436', '136', 'id', '1', '1', '', '', '78', '0', '79', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('437', '136', 'lang', '2', '', '1', '1', '80', '255', '81', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('438', '136', 'initial', '2', '', '1', '1', '82', '10', '83', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('440', '4', 'insert', '9', '', '1', '', '156', '255', '157', '', '', '1', '', '70', '', '');
insert into `aa_services_element` values ('441', '4', 'modify', '9', '', '1', '', '158', '255', '159', '', '', '1', '', '80', '', '');
insert into `aa_services_element` values ('442', '4', 'delete', '9', '', '1', '', '160', '255', '161', '', '', '1', '', '90', '', '');
insert into `aa_services_element` values ('443', '4', 'link', '2', '', '', '', '146', '255', '147', '', '', '', '', '30', '', '');
insert into `aa_services_element` values ('444', '4', 'icon', '13', '', '1', '', '162', '255', '163', '', '/admin/modules/aa/images/service_icon/', '', '', '110', '', '');
insert into `aa_services_element` values ('449', '129', 'ismultilang', '9', '', '', '', '56', '1', '57', '', '', '1', '', '65', '', '');
insert into `aa_services_element` values ('451', '5', 'multilang', '9', '', '', '', '124', '255', '125', '', '', '1', '', '85', '', '');
insert into `aa_services_element` values ('453', '136', 'flag', '13', '', '1', '', '84', '255', '85', '', '/public/mat/flag', '', '', '30', '', '');
insert into `aa_services_element` values ('454', '137', 'id', '1', '1', '1', '', '86', '0', '87', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('455', '137', 'it', '8', '', '1', '1', '88', '0', '89', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('456', '137', 'en', '8', '', '1', '1', '90', '0', '91', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('457', '127', 'title', '2', '', '1', '', '186', '255', '187', '', '', '', '', '10', '', '1');
insert into `aa_services_element` values ('458', '127', 'text', '6', '', '', '', '188', '255', '189', 'Default', '', '', '', '20', '', '1');
insert into `aa_services_element` values ('459', '127', 'image', '5', '', '1', '', '190', '0', '191', '/public/mat', '', '', '', '30', '', '');
insert into `aa_services_element` values ('460', '127', 'date', '15', '', '1', '', '194', '0', '195', '', '', '', '', '40', '', '');
insert into `aa_services_element` values ('461', '140', 'id', '1', '1', '', '', '227', '0', '228', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('462', '140', 'title', '2', '', '1', '', '229', '255', '230', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('463', '140', 'text', '8', '', '', '', '231', '0', '232', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('464', '140', 'photo', '5', '', '1', '', '233', '0', '234', '', '', '', '', '30', '', '');
insert into `aa_services_element` values ('465', '140', 'date', '15', '', '1', '', '235', '0', '236', '', '', '', '', '40', '', '');
insert into `aa_services_element` values ('466', '141', 'id', '1', '1', '', '', '239', '0', '240', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('467', '142', 'id', '1', '1', '', '', '243', '0', '244', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('468', '142', 'title', '2', '', '1', '', '245', '255', '246', '', '', '', '', '10', '', '1');
insert into `aa_services_element` values ('469', '142', 'date', '15', '', '1', '', '247', '0', '248', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('470', '142', 'photo', '10', '', '', '', '249', '0', '250', '/public/mat/album', '', 'title|ordine|photos|photo|album', '', '30', '', '');
insert into `aa_services_element` values ('471', '143', 'id', '1', '1', '', '', '254', '0', '255', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('472', '143', 'title', '2', '', '1', '', '256', '255', '257', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('474', '143', 'photo', '5', '', '1', '', '258', '0', '259', '/public/mat/album/#{join|value|id_join=20}', '', '', '', '30', '', '');
insert into `aa_services_element` values ('475', '143', 'album', '11', '', '1', '', '260', '11', '261', '', 'SELECT * FROM album', '', '', '40', '', '');
insert into `aa_services_element` values ('476', '143', 'ordine', '3', '', '1', '1', '262', '0', '263', '', '', '', '', '50', '', '');
insert into `aa_services_element` values ('477', '3', 'parent_id', '16', '', '1', '', '264', '11', '265', 'name', '', '', '', '20', '', '');
insert into `aa_services_element` values ('480', '116', 'url', '2', '', '1', '1', '270', '255', '271', '', '', '', '', '70', '', '');
insert into `aa_services_element` values ('481', '2', 'owner', '18', '', '1', '', '272', '0', '273', '', '', '', '', '60', '', '');
insert into `aa_services_element` values ('482', '144', 'id', '1', '1', '', '', '283', '0', '284', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('483', '144', 'filename', '2', '', '1', '', '285', '255', '286', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('484', '144', 'path', '2', '', '', '', '287', '255', '288', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('485', '144', 'title', '2', '', '1', '', '289', '255', '290', '', '', '', '', '30', '', '');
insert into `aa_services_element` values ('486', '144', 'caption', '2', '', '1', '', '291', '255', '292', '', '', '', '', '40', '', '');
insert into `aa_services_element` values ('487', '144', 'author', '2', '', '1', '', '293', '255', '294', '', '', '', '', '50', '', '');
insert into `aa_services_element` values ('488', '144', 'modified_at', '20', '', '1', '', '295', '0', '296', '', '', '', '', '60', '', '');
insert into `aa_services_element` values ('489', '145', 'id', '1', '1', '', '', '303', '0', '304', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('490', '145', 'tag', '2', '', '1', '1', '305', '255', '306', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('491', '146', 'id', '1', '1', '', '', '311', '0', '312', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('492', '146', 'media_id', '11', '', '1', '', '313', '11', '314', '', 'SELECT * FROM media', '', '', '10', '', '');
insert into `aa_services_element` values ('493', '146', 'tag_id', '11', '', '1', '', '315', '11', '316', '', 'SELECT * FROM tags', '', '', '20', '', '');
insert into `aa_services_element` values ('494', '147', 'id', '1', '1', '', '', '321', '0', '322', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('495', '147', 'group', '2', '', '1', '1', '323', '255', '324', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('496', '148', 'id', '1', '1', '', '', '329', '0', '330', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('497', '148', 'name', '2', '', '1', '', '331', '255', '332', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('498', '148', 'surname', '2', '', '1', '', '333', '255', '334', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('499', '148', 'company', '2', '', '', '', '335', '255', '336', '', '', '', '', '30', '', '');
insert into `aa_services_element` values ('500', '148', 'email', '2', '', '1', '', '337', '255', '338', '', '', '', '', '40', '', '');
insert into `aa_services_element` values ('501', '148', 'address', '2', '', '', '', '339', '255', '340', '', '', '', '', '50', '', '');
insert into `aa_services_element` values ('502', '148', 'city', '2', '', '', '', '341', '255', '342', '', '', '', '', '60', '', '');
insert into `aa_services_element` values ('503', '148', 'zip', '2', '', '', '', '343', '255', '344', '', '', '', '', '70', '', '');
insert into `aa_services_element` values ('504', '148', 'province', '2', '', '', '', '345', '255', '346', '', '', '', '', '80', '', '');
insert into `aa_services_element` values ('505', '148', 'confirmation_code', '2', '', '', '', '347', '255', '348', '', '', '', '', '90', '', '');
insert into `aa_services_element` values ('506', '148', 'activated', '9', '', '1', '', '349', '255', '350', '', '', '1', '', '100', '', '');
insert into `aa_services_element` values ('507', '148', 'group', '24', '', '1', '', '351', '11', '352', '', 'SELECT * FROM groups', '', '', '110', '', '');
insert into `aa_services_element` values ('508', '148', 'created_at', '30', '', '1', '', '353', '0', '354', '', '', '', '', '145', '', '');
insert into `aa_services_element` values ('509', '148', 'password', '4', '', '', '', '355', '255', '356', '', '', '', '', '130', '', '');
insert into `aa_services_element` values ('510', '148', 'newsletter', '9', '', '', '', '357', '255', '358', '', '', '1', '', '140', '', '');
insert into `aa_services_element` values ('511', '149', 'id', '1', '1', '', '', '363', '0', '364', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('512', '149', 'title', '2', '', '1', '', '365', '255', '366', '', '', '', '', '10', '', '1');
insert into `aa_services_element` values ('514', '149', 'description', '2', '', '', '', '367', '150', '368', '', '', '', '', '30', '', '1');
insert into `aa_services_element` values ('515', '149', 'date', '14', '', '1', '', '369', '0', '370', '', '', '', '', '40', '', '');
insert into `aa_services_element` values ('517', '149', 'file', '5', '', '1', '', '371', '0', '372', '/public/mat/documents', '', '', '', '60', '', '');
insert into `aa_services_element` values ('518', '149', 'category_id', '12', '', '1', '', '373', '11', '374', '', 'SELECT * FROM categories', '', '', '70', '', '');
insert into `aa_services_element` values ('519', '149', 'enabled_groups', '24', '', '1', '', '375', '255', '376', '', 'SELECT * FROM groups', '', '', '90', '', '');
insert into `aa_services_element` values ('521', '150', 'id', '1', '1', '', '', '381', '0', '382', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('522', '150', 'category', '2', '', '1', '', '383', '255', '384', '', '', '', '', '10', '', '1');
insert into `aa_services_element` values ('523', '150', 'order', '3', '', '1', '1', '385', '11', '386', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('524', '151', 'id', '1', '1', '', '', '402', '0', '403', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('525', '151', 'label', '2', '', '1', '', '404', '255', '405', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('526', '151', 'value', '8', '', '1', '', '406', '0', '407', '', '', '', '', '20', '', '1');
insert into `aa_services_element` values ('527', '149', 'status', '23', '', '1', '', '411', '0', '412', '', 'public|protected|private|secret|suspended', '', '', '80', '', '');
insert into `aa_services_element` values ('528', '152', 'id', '1', '1', '1', '', '419', '0', '420', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('529', '152', 'pagina', '12', '', '1', '', '421', '255', '422', '1', 'SELECT id,title FROM `aa_page` ORDER BY `order`', '', '', '5', '', '');
insert into `aa_services_element` values ('530', '152', 'descrizione', '6', '', '', '', '423', '150', '424', 'Default', '', '', '', '20', '', '1');
insert into `aa_services_element` values ('531', '152', 'destinatario', '2', '', '1', '', '425', '255', '426', '', '', '', '', '30', '', '');
insert into `aa_services_element` values ('533', '152', 'privacy', '9', '', '', '', '427', '255', '428', '', '', '1', '', '50', '', '');
insert into `aa_services_element` values ('534', '152', 'captcha', '23', '', '', '', '429', '0', '430', '', 'nessuno|basic|synCaptcha|honeypot', '', '', '60', '', '');
insert into `aa_services_element` values ('535', '152', 'risposta', '6', '', '', '', '431', '150', '432', 'Default', '', '', '', '70', '', '1');
insert into `aa_services_element` values ('536', '152', 'data', '15', '', '1', '', '433', '0', '434', '', '', '', '', '80', '', '');
insert into `aa_services_element` values ('537', '152', 'visibile', '9', '', '1', '', '435', '255', '436', '', '', '1', '', '90', '', '');
insert into `aa_services_element` values ('538', '153', 'id', '1', '1', '', '', '440', '0', '441', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('539', '153', 'id_form', '3', '', '', '', '442', '11', '443', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('540', '153', 'titolo', '2', '', '1', '', '444', '255', '445', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('541', '153', 'label', '2', '', '1', '', '446', '255', '447', '', '', '', '', '30', '', '1');
insert into `aa_services_element` values ('542', '153', 'tipo', '23', '', '1', '', '448', '0', '449', '', 'text|textarea|checkbox|radio|select|file|password|hidden', '', '', '40', '', '');
insert into `aa_services_element` values ('543', '153', 'value', '2', '', '', '', '450', '255', '451', '', '', '', '', '50', '', '');
insert into `aa_services_element` values ('544', '153', 'formato', '23', '', '', '', '452', '255', '453', '', 'text|date|digits|email', '', '', '60', '', '');
insert into `aa_services_element` values ('545', '153', 'obbligatorio', '9', '', '1', '', '454', '255', '455', '', '', '1', '', '70', '', '');
insert into `aa_services_element` values ('546', '153', 'fieldset', '12', '', '1', '', '456', '11', '457', '1', 'select id,titolo from `form_fieldsets` WHERE id_form=#{join|value|id_join=22} ORDER BY ordine', '', '', '80', '', '');
insert into `aa_services_element` values ('547', '153', 'ordine', '3', '', '1', '1', '458', '11', '459', '', '', '', '', '90', '', '');
insert into `aa_services_element` values ('548', '154', 'id', '1', '1', '', '', '462', '0', '463', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('549', '154', 'id_form', '3', '', '', '', '464', '11', '465', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('550', '154', 'titolo', '2', '', '1', '', '466', '255', '467', '', '', '', '', '20', '', '1');
insert into `aa_services_element` values ('551', '154', 'ordine', '2', '', '1', '1', '468', '255', '469', '', '', '', '', '30', '', '');
insert into `aa_services_element` values ('552', '155', 'id', '1', '1', '', '', '472', '0', '473', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('553', '155', 'id_field', '3', '', '', '', '474', '11', '475', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('554', '155', 'label', '2', '', '1', '', '476', '255', '477', '', '', '', '', '20', '', '1');
insert into `aa_services_element` values ('555', '155', 'value', '2', '', '1', '', '478', '255', '479', '', '', '', '', '30', '', '');
insert into `aa_services_element` values ('556', '155', 'selezionato', '9', '', '1', '', '480', '255', '481', '', '', '1', '', '40', '', '');
insert into `aa_services_element` values ('557', '155', 'ordine', '3', '', '1', '1', '482', '11', '483', '', '', '', '', '50', '', '');
insert into `aa_services_element` values ('558', '152', 'titolo', '2', '', '1', '', '491', '255', '492', '', '', '', '', '10', '', '1');
insert into `aa_services_element` values ('559', '136', 'active', '9', '', '1', '', '503', '255', '504', '', '', '1', '', '40', '', '');
insert into `aa_services_element` values ('560', '136', 'order', '3', '', '1', '1', '505', '11', '506', '', '', '', '', '50', '', '');
insert into `aa_services_element` values ('561', '116', 'metatitle', '2', '', '', '', '558', '75', '559', '', '', '', '', '90', '', '1');
insert into `aa_services_element` values ('562', '116', 'metadescription', '2', '', '', '', '560', '150', '561', '', '', '', '', '100', '', '1');
insert into `aa_services_element` values ('563', '116', 'metakeywords', '2', '', '', '', '562', '175', '563', '', '', '', '', '110', '', '1');
insert into `aa_services_element` values ('565', '127', 'visible', '24', '', '1', '', '615', '0', '616', '', 'SELECT id, lang FROM `aa_lang`', '', '', '50', '', '');
insert into `aa_services_element` values ('566', '156', 'id', '1', '1', '', '', '619', '0', '620', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('567', '156', 'classname', '13', '', '1', '', '621', '255', '622', '', '/admin/modules/aa/classes', '', '', '10', '', '');
insert into `aa_services_element` values ('568', '156', 'name', '2', '', '1', '', '623', '255', '624', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('569', '156', 'order', '3', '', '1', '', '625', '11', '626', '', '', '', '', '30', '', '');
insert into `aa_services_element` values ('570', '136', 'default', '9', '', '1', '', '628', '255', '629', '', '', '1', '', '60', '', '');
insert into `aa_services_element` values ('571', '116', 'slug', '29', '', '', '', '630', '1024', '631', '', '', '', '', '15', '', '1');
insert into `aa_services_element` values ('572', '148', 'last_update', '15', '', '', '', '660', '0', '661', '', '', '', '', '150', '', '');
insert into `aa_services_element` values ('573', '148', 'last_access', '30', '', '', '', '662', '0', '663', '', '', '', '', '160', '', '');
insert into `aa_services_element` values ('574', '148', 'last_ip', '2', '', '', '', '664', '255', '665', '', '', '', '', '170', '', '');
insert into `aa_services_element` values ('575', '148', 'new_password_key', '2', '', '', '', '666', '255', '667', '', '', '', '', '180', '', '');
insert into `aa_services_element` values ('576', '148', 'new_password_requested', '2', '', '', '', '668', '255', '669', '', '', '', '', '190', '', '');
insert into `aa_services_element` values ('577', '148', 'new_email', '2', '', '', '', '670', '255', '671', '', '', '', '', '200', '', '');
insert into `aa_services_element` values ('578', '148', 'new_email_key', '2', '', '', '', '672', '255', '673', '', '', '', '', '210', '', '');
insert into `aa_services_element` values ('579', '148', 'hashed_id', '2', '', '', '', '674', '255', '675', '', '', '', '', '220', '', '');
insert into `aa_services_element` values ('580', '148', 'login_attempts', '3', '', '', '', '223', '11', '224', '', '', '', '', '230', '', '');
insert into `aa_services_element` values ('581', '148', 'login_count', '3', '', '', '', '225', '11', '226', '', '', '', '', '240', '', '');
insert into `aa_services_element` values ('582', '157', 'id', '1', '1', '', '', '720', '0', '721', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('583', '157', 'social', '23', '', '1', '', '722', '0', '723', '', 'facebook|google-plus|linkedin|pinterest|twitter|flickr|youtube|instagram', '', '', '10', '', '');
insert into `aa_services_element` values ('584', '157', 'url', '2', '', '', '', '724', '255', '725', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('585', '157', 'visible', '9', '', '1', '', '726', '255', '727', '', '', '1', '', '30', '', '');
insert into `aa_services_element` values ('586', '158', 'id', '1', '1', '', '', '731', '0', '732', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('587', '158', 'id_form', '3', '', '1', '', '733', '11', '734', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('588', '158', 'hash', '8', '', '', '', '735', '0', '736', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('589', '158', 'timestamp', '20', '', '1', '', '737', '0', '738', '', '', '', '', '30', '', '');
insert into `aa_services_element` values ('590', '143', 'date', '30', '', '', '', '753', '0', '754', '', '', '', '', '50', '', '');
insert into `aa_services_element` values ('591', '143', 'autore', '21', '', '', '', '755', '255', '756', '', '', '', '', '60', '', '');
insert into `aa_services_element` values ('592', '143', 'format', '23', '', '', '', '757', '0', '758', '', 'portrait|landscape', '', '', '70', '', '');
insert into `aa_services_element` values ('593', '142', 'visible', '24', '', '1', '', '760', '0', '761', '', 'SELECT id, lang FROM `aa_lang`', '', '', '25', '', '');
insert into `aa_services_element` values ('595', '4', 'ip', '31', '', '1', '', '764', '30', '765', '', '', '', '', '120', '', '');
insert into `aa_services_element` values ('596', '136', 'domain', '2', '', '1', '', '766', '255', '767', '', '', '', '', '5', '', '');
insert into `aa_services_element` values ('597', '152', 'privacy_page', '12', '', '', '', '769', '11', '770', '1', 'SELECT id,title FROM `aa_page` ORDER BY `order`', '', '', '55', '', '');
insert into `aa_services_element` values ('598', '159', 'id', '1', '1', '1', '', '774', '0', '775', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('599', '159', 'from', '2', '', '1', '', '776', '255', '777', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('600', '159', 'to', '2', '', '1', '', '778', '255', '779', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('601', '159', 'header', '23', '', '1', '', '780', '0', '781', '', '301|302|404', '', '', '30', '', '');
insert into `aa_services_element` values ('602', '160', 'id', '1', '1', '1', '', '785', '0', '786', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('603', '160', 'timestamp', '15', '', '1', '', '787', '0', '788', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('604', '160', 'ip_address', '2', '', '', '', '789', '255', '790', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('605', '160', 'user_agent', '2', '', '1', '', '791', '255', '792', '', '', '', '', '30', '', '');
insert into `aa_services_element` values ('606', '160', 'request_uri', '2', '', '1', '', '793', '255', '794', '', '', '', '', '40', '', '');
insert into `aa_services_element` values ('607', '160', 'response', '8', '', '', '', '795', '0', '796', '', '', '', '', '50', '', '');
insert into `aa_services_element` values ('608', '160', 'redirect_id', '3', '', '', '', '797', '11', '798', '', '', '', '', '60', '', '');
insert into `aa_services_element` values ('609', '160', 'dispatched', '9', '', '1', '', '799', '255', '800', '', '', '1', '', '70', '', '');
insert into `aa_services_element` values ('610', '160', 'referer', '2', '', '', '', '804', '255', '805', '', '', '', '', '45', '', '');


### structure of table `aa_template` ###

DROP TABLE IF EXISTS `aa_template`;

CREATE TABLE `aa_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `timestamp` datetime NOT NULL,
  `filename` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 AUTO_INCREMENT=16;


### data of table `aa_template` ###

insert into `aa_template` values ('4', 'standard template', '2004-04-28 16:27:54', 'default.tpl');
insert into `aa_template` values ('10', 'Documents', '2010-03-24 15:51:19', 'documents.tpl');
insert into `aa_template` values ('8', 'RSS', '2008-06-18 18:02:16', 'rss.tpl');
insert into `aa_template` values ('9', 'Account', '2010-03-23 16:19:38', 'account.tpl');
insert into `aa_template` values ('11', 'Gallery', '2010-03-24 16:22:14', 'gallery.tpl');
insert into `aa_template` values ('12', 'news', '2010-12-21 16:13:44', 'news.tpl');
insert into `aa_template` values ('13', 'form', '2010-12-21 16:13:51', 'form.tpl');
insert into `aa_template` values ('14', 'index', '2013-12-11 14:33:08', 'index.tpl');
insert into `aa_template` values ('15', 'search results', '2015-03-03 11:13:30', 'search.tpl');


### structure of table `aa_translation` ###

DROP TABLE IF EXISTS `aa_translation`;

CREATE TABLE `aa_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `it` text NOT NULL,
  `en` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=806 DEFAULT CHARSET=utf8 AUTO_INCREMENT=806;


### data of table `aa_translation` ###

insert into `aa_translation` values ('1', 'Home', 'Home');
insert into `aa_translation` values ('2', '<p><img alt=\"Syntax Desktop package\" src=\"/public/mat/image/syntax-box.gif\" style=\"border-width: 0px; border-style: solid; float: right; width: 170px; height: 221px;\" />Syntax Desktop è un sistema professionale di gestione dei contenuti Open Source. In pratica, è uno strumento che permette agli utenti di poter modificare il proprio sito come, dove e quando vogliono.</p>\r\n\r\n<p>Syntax Desktop ti aiuta a creare e gestire complessi siti web senza conoscere l\'HTML. Un editor WYSIWYG integrato con un\'interfaccia utente simile a quella delle ben note applicazioni office aiuta l\'utente a creare i contenuti, mentre un template engine gestisce tutte le parti del sito per avere un controllo completo dell\'applicazione.</p>\r\n\r\n<p>Syntax Desktop è basato su tecnologia PHP. É per questo motivo che funziona sulla gran parte delle moderne infrastrutture IT esistenti. Syntax Desktop gira su ambienti completamente \"open source\" (p.e. Linux, Apache, MySQL), ma funziona tranquillamente anche su componenti commerciali (p.e. Windows, IIS, Oracle DB, MS Access).</p>\r\n\r\n<p>Inoltre una grande virtù di Syntax è la sua predisposizione ad essere indicizzato dai motori di ricerca. Syntax è stato infatti progettato nell\'ottica di generare pagine amiche dei motori di ricerca.</p>\r\n\r\n<p>Per conoscere di più Syntax Desktop, visualizza il <a href=\"http://www.dynamick.it/syntax-desktop/tour.php\">tour guidato</a> che ti mostrerà&nbsp; le principali funzionalità dell\'applicazione. Per un\'approfondimento ulteriore, leggi il <a href=\"http://www.syntaxdesktop.com/docs\">manuale utente</a>.</p>\r\n', '<p><img alt=\"Syntax Desktop package\" src=\"/public/mat/image/syntax-box.gif\" style=\"border-width: 0px; border-style: solid; float: right; width: 170px; height: 221px;\" /></p>\r\n\r\n<p><strong>Syntax Desktop</strong> is a professional open source <strong>Content Management System</strong> (CMS).&nbsp; It is a tool that allows users to modify the web site contents how, when and where they want.<br />\r\n<br />\r\nSyntax Desktop helps you to create and manage large web sites <strong>without any knowledge</strong> of HTML. There\'s an integrated WYSIWYG editor with a friendly user interface similar to common office applications. You can create the contents and a template engine manages all the parts of the site allowing complete control of the application.<br />\r\n<br />\r\nSyntax Desktop is based on<strong> PHP technology</strong>. It is a web application, so it works on a wide range of modern existing IT infrastructures.&nbsp; Syntax Desktop runs with other \"open source\" technologies (i.e. Linux, Apache, MySQL), but it works also on commercial products (i.e. Windows, IIS, Oracle DB, MS Access).<br />\r\n<br />\r\nMoreover, a great virtue of Syntax is its predisposition to being indexed from <strong>search engines</strong>.&nbsp; Syntax is constructed to generate pages optimized for search engines.<br />\r\n<br />\r\nFor more information about Syntax Desktop, you can watch the flash <a href=\"http://www.dynamick.it/syntax-desktop/tour.php\">guided tour</a>. You can find other information by reading the <a href=\"http://www.syntaxdesktop.com/docs/\">user manual</a>.</p>\r\n');
insert into `aa_translation` values ('3', 'Token non valido!', 'Invalid token!');
insert into `aa_translation` values ('4', '', '');
insert into `aa_translation` values ('5', '', '');
insert into `aa_translation` values ('6', 'Gestione Contenuti', 'Content Management');
insert into `aa_translation` values ('7', 'Pagine', 'Pages');
insert into `aa_translation` values ('8', 'Template', 'Template');
insert into `aa_translation` values ('9', 'Amministrazione', 'Administration');
insert into `aa_translation` values ('10', 'Utenti backend', 'Backend users');
insert into `aa_translation` values ('11', 'Gruppi/Menu', 'Groups/Menu');
insert into `aa_translation` values ('12', 'Servizi', 'Service');
insert into `aa_translation` values ('13', 'Lingue', 'Language');
insert into `aa_translation` values ('14', 'Traduzioni', 'Translations');
insert into `aa_translation` values ('15', 'Strumenti', 'Tools');
insert into `aa_translation` values ('16', 'Backup Manager', 'Database Manager');
insert into `aa_translation` values ('17', '?', '?');
insert into `aa_translation` values ('18', 'Manuale', 'Manual (only in Italian)');
insert into `aa_translation` values ('19', 'Informazioni su', 'About');
insert into `aa_translation` values ('20', 'Servizi', 'Service');
insert into `aa_translation` values ('21', 'Servizi disponibili', 'Service management');
insert into `aa_translation` values ('22', 'Lingue', 'Language');
insert into `aa_translation` values ('23', 'Elenco delle lingue disponibili', 'User language');
insert into `aa_translation` values ('24', 'Traduzioni', 'Translations');
insert into `aa_translation` values ('25', 'Elenco di tutte le traduzioni', 'contains the traslation string of everything in this db');
insert into `aa_translation` values ('26', 'Utenti di sistema', 'System users');
insert into `aa_translation` values ('27', 'Lista degli utenti di sistema', 'System users list');
insert into `aa_translation` values ('28', 'Gruppi di sistema', 'System Group');
insert into `aa_translation` values ('29', 'Lista dei gruppi di utenti di sistema', 'System users group management');
insert into `aa_translation` values ('30', 'Service-o-matic', 'Service-o-matic');
insert into `aa_translation` values ('31', 'Assistente per la creazione dei servizi', 'Wizard for service creation');
insert into `aa_translation` values ('32', 'Joins', 'Joins');
insert into `aa_translation` values ('33', 'Joins (collegamenti) tra servizi', 'Service joins');
insert into `aa_translation` values ('34', 'Menu', 'Menu');
insert into `aa_translation` values ('35', 'Menu relativi ai gruppi', 'Groups menu');
insert into `aa_translation` values ('36', 'Pagine', 'Pages');
insert into `aa_translation` values ('37', 'Gerarchia delle pagine del sito', 'Page hierarchy');
insert into `aa_translation` values ('38', 'Template', 'Template');
insert into `aa_translation` values ('39', 'gestione dei template', 'Template management');
insert into `aa_translation` values ('40', 'News', 'News');
insert into `aa_translation` values ('41', 'News Management', 'News Management');
insert into `aa_translation` values ('42', 'Id', 'Id');
insert into `aa_translation` values ('43', '', '');
insert into `aa_translation` values ('44', 'Servizio', 'Container');
insert into `aa_translation` values ('45', 'Scegli il servizio di appartenenza', '');
insert into `aa_translation` values ('46', 'Nome', 'Name');
insert into `aa_translation` values ('47', '', '');
insert into `aa_translation` values ('48', 'Tipo', 'Type');
insert into `aa_translation` values ('49', '', '');
insert into `aa_translation` values ('50', 'Chiave', 'Iskey');
insert into `aa_translation` values ('51', '', '');
insert into `aa_translation` values ('52', 'Visibile', 'Isvisible');
insert into `aa_translation` values ('53', '', '');
insert into `aa_translation` values ('54', 'Editabile', 'Iseditable');
insert into `aa_translation` values ('55', '', '');
insert into `aa_translation` values ('56', 'Multilingua', 'Ismultilang');
insert into `aa_translation` values ('57', '', '');
insert into `aa_translation` values ('58', 'Etichetta', 'Label');
insert into `aa_translation` values ('59', '', '');
insert into `aa_translation` values ('60', 'Dimensione', 'Size');
insert into `aa_translation` values ('61', '', '');
insert into `aa_translation` values ('62', 'Aiuto', 'Help');
insert into `aa_translation` values ('63', '', '');
insert into `aa_translation` values ('64', 'Path', 'Path');
insert into `aa_translation` values ('65', '', '');
insert into `aa_translation` values ('66', 'Qry', 'Qry');
insert into `aa_translation` values ('67', '', '');
insert into `aa_translation` values ('68', 'Valore', 'Value');
insert into `aa_translation` values ('69', '', '');
insert into `aa_translation` values ('70', 'Joins', 'Joins');
insert into `aa_translation` values ('71', '', '');
insert into `aa_translation` values ('72', 'Posizione', 'Position');
insert into `aa_translation` values ('73', '', '');
insert into `aa_translation` values ('74', 'Filtro', 'Filter');
insert into `aa_translation` values ('75', '', '');
insert into `aa_translation` values ('76', 'Id', 'Id');
insert into `aa_translation` values ('77', '', '');
insert into `aa_translation` values ('78', 'Id', 'Id');
insert into `aa_translation` values ('79', '', '');
insert into `aa_translation` values ('80', 'Lingua', 'Language');
insert into `aa_translation` values ('81', 'Inserisci il nome della lingua per esteso', 'Insert the full name of language');
insert into `aa_translation` values ('82', 'Iniziali', 'Initial');
insert into `aa_translation` values ('83', '2 lettere', 'insert 2 chars');
insert into `aa_translation` values ('84', 'Bandiera', 'Flag');
insert into `aa_translation` values ('85', '', '');
insert into `aa_translation` values ('86', 'Id', 'Id');
insert into `aa_translation` values ('87', '', '');
insert into `aa_translation` values ('88', 'It', 'It');
insert into `aa_translation` values ('89', '', '');
insert into `aa_translation` values ('90', 'En', 'En');
insert into `aa_translation` values ('91', '', '');
insert into `aa_translation` values ('92', 'Id', 'Id');
insert into `aa_translation` values ('93', '', '');
insert into `aa_translation` values ('94', 'Login', 'Login');
insert into `aa_translation` values ('95', '', '');
insert into `aa_translation` values ('96', 'Passwd', 'Passwd');
insert into `aa_translation` values ('97', '', '');
insert into `aa_translation` values ('98', 'Gruppo', 'Gruppo');
insert into `aa_translation` values ('99', 'Scegli il gruppo di appartenza', 'Scegli il gruppo di appartenza');
insert into `aa_translation` values ('100', 'Lingua', 'Lingua');
insert into `aa_translation` values ('101', 'Scegli la lingua', 'Scegli la lingua');
insert into `aa_translation` values ('102', 'Id', 'Id');
insert into `aa_translation` values ('103', '', '');
insert into `aa_translation` values ('104', 'Nome', 'Name');
insert into `aa_translation` values ('105', '', '');
insert into `aa_translation` values ('106', 'Id', 'Id');
insert into `aa_translation` values ('107', '', '');
insert into `aa_translation` values ('108', 'Nome', 'Name');
insert into `aa_translation` values ('109', '', '');
insert into `aa_translation` values ('110', 'Path', 'Path');
insert into `aa_translation` values ('111', '', '');
insert into `aa_translation` values ('112', 'Icona', 'Icon');
insert into `aa_translation` values ('113', 'Scegli l\'icona per il menu', 'Choose the menu icon');
insert into `aa_translation` values ('114', 'Descrizione', 'Description');
insert into `aa_translation` values ('115', '', '');
insert into `aa_translation` values ('116', 'Padre', 'Parent');
insert into `aa_translation` values ('117', '', '');
insert into `aa_translation` values ('118', 'Syntable', 'Syntable');
insert into `aa_translation` values ('119', '', '');
insert into `aa_translation` values ('120', 'Ordinamento', 'Order');
insert into `aa_translation` values ('121', 'Scegli il campo che detterà l\'ordine', '');
insert into `aa_translation` values ('122', 'Dbsync', 'Dbsync');
insert into `aa_translation` values ('123', 'Crea/aggiorna le tabelle sul database?', 'Create/update database table?');
insert into `aa_translation` values ('124', 'Multilingua', 'Multilang');
insert into `aa_translation` values ('125', 'Questo servizio è multilingua?', 'Is a multilang service? This field is auto calculated by service-o-matic');
insert into `aa_translation` values ('126', 'Posizione', 'InitOrder');
insert into `aa_translation` values ('127', 'Posizione dell\'elemento rispetto agli altri', '');
insert into `aa_translation` values ('128', 'Id', 'Id');
insert into `aa_translation` values ('129', '', '');
insert into `aa_translation` values ('130', 'Titolo', 'Title');
insert into `aa_translation` values ('131', 'Inserisci il titolo del collegamento', 'join title');
insert into `aa_translation` values ('132', 'da', 'From');
insert into `aa_translation` values ('133', 'Scegli il campo origine', 'Origin field');
insert into `aa_translation` values ('134', 'A', 'To');
insert into `aa_translation` values ('135', 'Scegli il campo destinazione', 'Destination field');
insert into `aa_translation` values ('136', 'Description', 'Descrizione');
insert into `aa_translation` values ('137', 'join description', 'Eventuali commenti');
insert into `aa_translation` values ('138', 'Servizio', 'Service');
insert into `aa_translation` values ('139', 'servizio di riferimento', 'reference service');
insert into `aa_translation` values ('140', '140', 'Id');
insert into `aa_translation` values ('141', '', '');
insert into `aa_translation` values ('142', 'Nome', 'Name');
insert into `aa_translation` values ('143', '', '');
insert into `aa_translation` values ('144', 'Servizio', 'Service');
insert into `aa_translation` values ('145', 'Lasciare vuoto per creare una cartella', 'Keep blank to create a directory');
insert into `aa_translation` values ('146', 'Link', 'Link');
insert into `aa_translation` values ('147', 'Collegamento diretto ad una pagina', 'URL to a page');
insert into `aa_translation` values ('148', 'Posizione', 'Order');
insert into `aa_translation` values ('149', '', '');
insert into `aa_translation` values ('150', 'Sql filter', 'Sql filteer');
insert into `aa_translation` values ('151', '', '');
insert into `aa_translation` values ('152', 'Gruppo', 'Group');
insert into `aa_translation` values ('153', '', '');
insert into `aa_translation` values ('154', 'Parent', 'Parent');
insert into `aa_translation` values ('155', '', '');
insert into `aa_translation` values ('156', 'Ins', 'Insert');
insert into `aa_translation` values ('157', 'Permette l\'inserimento di nuovi record', 'Permit add new records');
insert into `aa_translation` values ('158', 'Mod', 'Modify');
insert into `aa_translation` values ('159', 'Permette la modifica dei record', 'Permit modify records');
insert into `aa_translation` values ('160', 'Canc', 'Delete');
insert into `aa_translation` values ('161', 'Permette la cancellazione dei record', 'Permit delete records');
insert into `aa_translation` values ('162', 'Icona', 'Icon');
insert into `aa_translation` values ('163', 'Icona per il menu', 'Icon');
insert into `aa_translation` values ('164', 'Id', 'Id');
insert into `aa_translation` values ('165', '', '');
insert into `aa_translation` values ('166', 'Titolo', 'Title');
insert into `aa_translation` values ('167', 'Inserire il titolo della pagina', '');
insert into `aa_translation` values ('168', 'Testo', 'Text');
insert into `aa_translation` values ('169', 'Testo visualizzato sulla pagina', 'The page\'s text');
insert into `aa_translation` values ('170', 'Appartenenza', 'Parent');
insert into `aa_translation` values ('171', 'Scegli la sezione a cui appartiene questa pagina', '');
insert into `aa_translation` values ('172', 'Template', 'Template');
insert into `aa_translation` values ('173', 'Scegli il template grafico della pagina', 'Choose the template for this page');
insert into `aa_translation` values ('174', 'Visibile', 'Visible');
insert into `aa_translation` values ('175', 'Spunta per rendere visibile la pagina', 'Check to make visible this page');
insert into `aa_translation` values ('176', 'Posizione', 'Order');
insert into `aa_translation` values ('177', 'Inserisci la posizione che deve avere la pagina tra le altre della stessa sezione', 'Order between page on the same level');
insert into `aa_translation` values ('178', 'Id', 'Id');
insert into `aa_translation` values ('179', '', '');
insert into `aa_translation` values ('180', 'Titolo', 'Title');
insert into `aa_translation` values ('181', '', '');
insert into `aa_translation` values ('182', 'Timestamp', 'Timestamp');
insert into `aa_translation` values ('183', '', '');
insert into `aa_translation` values ('184', 'Nome del file', 'Filename');
insert into `aa_translation` values ('185', 'Scegli un template da file', 'Choose an already done template');
insert into `aa_translation` values ('186', 'Title', 'Title');
insert into `aa_translation` values ('187', '', '');
insert into `aa_translation` values ('188', 'Text', 'Text');
insert into `aa_translation` values ('189', '', '');
insert into `aa_translation` values ('190', 'Image', 'Image');
insert into `aa_translation` values ('191', '', '');
insert into `aa_translation` values ('192', 'Benvenuto in Syntax Desktop!', 'Welcome in Syntax!');
insert into `aa_translation` values ('193', '<p>\r\n  Questo è il testo della prima news!</p>\r\n<p>\r\n  Lorem ipsum dolor sit amet consectetuer neque a elit dui suscipit. Vestibulum Sed risus pretium orci Pellentesque nunc montes ut leo mauris. Habitant Pellentesque felis cursus interdum non Maecenas pede semper Ut In. Volutpat nunc Curabitur condimentum et interdum hendrerit dictum elit eu habitasse. Quis netus sit commodo mus consectetuer a at tellus urna justo. Condimentum.<br />\r\n <br />\r\n  Justo Nam et Vivamus Mauris tristique felis a adipiscing eu Fusce. Fringilla ac ipsum neque Curabitur condimentum elit morbi malesuada Sed urna. Pretium faucibus sit Sed auctor magna pellentesque fringilla Praesent dolor convallis. Tincidunt venenatis fringilla justo In amet tellus auctor penatibus Suspendisse Mauris. Tellus metus Vivamus id ac Phasellus tellus Morbi Suspendisse Aliquam orci. Laoreet laoreet justo mus.</p>\r\n', '<p>This is the first news.</p>\r\n<p>Lorem ipsum dolor sit amet consectetuer neque a elit dui suscipit. Vestibulum Sed risus pretium orci Pellentesque nunc montes ut leo mauris. Habitant Pellentesque felis cursus interdum non Maecenas pede semper Ut In. Volutpat nunc Curabitur condimentum et interdum hendrerit dictum elit eu habitasse. Quis netus sit commodo mus consectetuer a at tellus urna justo. Condimentum.<br />\r\n<br />\r\nJusto Nam et Vivamus Mauris tristique felis a adipiscing eu Fusce. Fringilla ac ipsum neque Curabitur condimentum elit morbi malesuada Sed urna. Pretium faucibus sit Sed auctor magna pellentesque fringilla Praesent dolor convallis. Tincidunt venenatis fringilla justo In amet tellus auctor penatibus Suspendisse Mauris. Tellus metus Vivamus id ac Phasellus tellus Morbi Suspendisse Aliquam orci. Laoreet laoreet justo mus.</p>');
insert into `aa_translation` values ('194', 'Date', 'Date');
insert into `aa_translation` values ('195', '', '');
insert into `aa_translation` values ('196', 'Tour Guidati', 'Guided Tour');
insert into `aa_translation` values ('197', 'Intefaccia utente', 'User Interface');
insert into `aa_translation` values ('198', 'Servizi', 'Services');
insert into `aa_translation` values ('199', 'Supporto alle Foreign Keys nella Versione 2', 'Version 2 support Foreign Keys');
insert into `aa_translation` values ('200', '<p>\r\n  Se hai innoDB, Syntax Desktop userà le foreign keys. Questa caratteristica mantiene le tabelle del database pulite e logicamente coerenti.</p>\r\n', 'If you have innoDB, Syntax Desktop will use the foreign keys. This feature will keep your database tables clean and logically coerent.');
insert into `aa_translation` values ('201', 'Il primo concorso italiano open source', 'The first italian open source contest');
insert into `aa_translation` values ('202', '<p>\r\n  Syntax Desktop è stato ammesso alla seconda fase del concorso italiano Open Source Contest. La sfida si concluderà il 31 dicembre 2004.</p>\r\n', 'Syntax Desktop is admitted to the second phase of the italian contest \"Open Source Contest\". The competition will finish on December, 31 (2004).\r\n');
insert into `aa_translation` values ('203', 'Intro', 'Intro');
insert into `aa_translation` values ('204', '<p><strong>Introduzione</strong></p>\r\n\r\n<p>Syntax Desktop è un sistema di gestione dei contenuti semplice e flessibile. Nato come strumento di aiuto per il lavoro, ora è un pacchetto pubblico usato&nbsp;in numerosi siti.</p>\r\n\r\n<p>Il nome ha un\'origine bizzarra, come capita spesso in questi casi. Syntax deriva da una coincidenza di 3 fatti:</p>\r\n\r\n<ul>\r\n  <li><strong>Syntax</strong> sarebbe stato il nome dell\'azienda, mai nata, che doveva sorgere dalle macerie della webagency&nbsp;presso cui lavoravo</li>\r\n <li><strong>Syntax supervisor</strong> è la carica che mi è stata data in un esame universitario&nbsp;di gruppo in cui avevo partecipato diciamo così, <em>marginalmente</em>... da allora i miei colleghi d\'università mi hanno chiamato così!</li>\r\n <li>Il nome Syntax mi piace, finisce con la <strong>X</strong>, molto di moda in questi tempi (vd. windows xp, dreamweaver mx, Mac OsX, ecc...)</li>\r\n</ul>\r\n\r\n<p>Ovviamente poi, essendo l\'interfaccia simile ad una scrivania virtuale, il nome finale è risultato: \"Syntax Desktop\".</p>\r\n\r\n<p>Il lavoro nasce da un\'idea di un mio ex-collega, <strong>Dimitri Giardina</strong>, che mi ha insegnato inizialmente l\'uso di PHP. Ovviamente del vecchio progetto non esiste più nulla, ma una citazione ritengo sia giusto averla fatta.</p>\r\n\r\n<p>Il progetto vuole essere un sistema completo per la gestione di siti web. É per questo motivo che alcune funzioni sono state implementate, altre invece sono lasciate all\'utente finale. Syntax contiene infatti un <strong>motore di generazione di pagine</strong> di amministrazione che permette di gestire qualsiasi tipo di dato strutturato.</p>\r\n', '<p><strong>Introduction</strong></p>\r\n\r\n<p>Syntax Desktop is a Content Management System simple and flexible. It was born as a support tool in my office work, but now became a very used software in many web sites.</p>\r\n\r\n<p>The name born from three ideas:</p>\r\n\r\n<ul>\r\n <li>Syntax&nbsp;should be the name of a company never created</li>\r\n  <li>Some of my friend call me Syntax Supervisor, because of a university exams project, where I do very little</li>\r\n <li>I like the name \"syntax\" beacuse it ends with an X, very cool in this period (i.e. windows xp, dreamweaver mx, Mac OsX, etc...)</li>\r\n</ul>\r\n\r\n<p>Obviously,&nbsp;the&nbsp;name&nbsp;was extended with a \"desktop\" because the&nbsp;is similar to a virtual writing desk.</p>\r\n\r\n<p>The&nbsp;project&nbsp;was born from an idea of&nbsp;my former-connects, <strong>Dimitri Giardina </strong>,&nbsp;the person who&nbsp;initially taught the use of PHP.&nbsp;</p>\r\n\r\n<p>Syntax Desktop&nbsp;wants to be a complete system for the web content manegement.&nbsp;For this reason&nbsp;some functions have been implemented, others instead are left the final customer. Syntax contains in fact&nbsp;an<strong>&nbsp;administration&nbsp;pages builder engine</strong> that allow to manage whichever type of structured data.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><font color=\"#ff0000\">HELP ME!<br />\r\nIf you want to help me, send the right english translation of this page at </font><a href=\"mailto:info@dynamick.it\"><font color=\"#ff0000\">info@dynamick.it</font></a><font color=\"#ff0000\"> .<br />\r\nThank you!</font></p>\r\n');
insert into `aa_translation` values ('205', 'Il tuo codice di attivazione é errato o scaduto. Per favore controlla la tua e-mail e segui le istruzioni.', 'Your activation code is either incorrect or outdated. Please check your e-mail and follow instructions.');
insert into `aa_translation` values ('206', 'Installazione', 'Installation');
insert into `aa_translation` values ('207', '<p><strong>Configurazione di php.ini</strong></p>\r\n\r\n<p>Assicuratevi di avere questi parametri settati nel file php.ini:</p>\r\n\r\n<ul>\r\n <li>error_reporting = E_ALL &amp; ~E_NOTICE&nbsp;</li>\r\n  <li>short_open_tag = On</li>\r\n  <li>register_globals = On&nbsp;</li>\r\n  <li>allow_call_time_pass_reference = On</li>\r\n</ul>\r\n\r\n<h4><strong>Permessi sui file</strong></h4>\r\n\r\n<p>Dopo aver copiato Syntax Deskto nella root del vostro Server, verificate di avere i permessi di scrittura (777) sulle seguenti cartelle:</p>\r\n\r\n<ul>\r\n <li>/admin/config/</li>\r\n <li>/admin/modules/phpMyBackupPro/export/</li>\r\n  <li>/public/mat/</li>\r\n <li>/cache/</li>\r\n</ul>\r\n\r\n<h4><strong>Installazione</strong></h4>\r\n\r\n<ol>\r\n  <li>Occorre prima di tutto creare un database mysql, possibilmente di tipo InnoDB, in modo da poter gestire le Foreign keys;</li>\r\n <li>Navigate in <u>www.miosito/admin/setup.php</u> e seguite le istruzioni. Se tutti i parametri sono corretti, Syntax creerà tutte le tabelle e i file di configurazione necessari.</li>\r\n <li>Syntax Desktop è pronto per essere usato! Si raccomanda di eliminare setup.php e rimuovere i permessi di scrittura su /admin/config/.</li>\r\n</ol>\r\n', '<p>\r\n  <strong>Installation</strong></p>\r\n<p>\r\n  The installation simply require&nbsp;adjusting the /syntax desktop/config/cfg.php file</p>\r\n<p>\r\n <strong>Configuring database&nbsp;</strong><br />\r\n It is necessary first to create a&nbsp;mysql database (possibly&nbsp;an InnoDB). After that, you have to adjust these few lines in the cfg.php:</p>\r\n<p>\r\n  <font color=\"#006600\">//ACCOUNT </font><br />\r\n $synDbHost=\"localhost\";<br />\r\n $synDbUser=\"root\";<br />\r\n  $synDbPassword=\"\";<br />\r\n  $synDbName=\"syntax\";</p>\r\n<p>\r\n <strong>Other configuration values</strong><br />\r\n You have to configure other parameters:</p>\r\n<p>\r\n  <font color=\"#006600\">//Upload image directory<br />\r\n  //YOU MUST PUT TRAILING SLASH;<br />\r\n  //the initial relative path is syntax desktop installation dir<br />\r\n  //(i.e. relative path=\"/syntax desktop/\" $mat=\"../mat\" ---&gt; result \"/syntax desktop/\"+\"../mat/\")</font><br />\r\n  $mat=\"../mat/\";<br />\r\n $thumb=\"../mat/thumb/\";</p>\r\n<p>\r\n  <font color=\"#006600\">//admin email</font><br />\r\n  $synAdministrator=\"info@dynamick.it\";</p>\r\n<p>\r\n  <font color=\"#006600\">//site address \"http://www.dynamick.it\"</font><br />\r\n  $synWebsite=\"/\";</p>\r\n<p>\r\n <font color=\"#006600\">//rows per page</font><br />\r\n  $synRowsPerPage=17;</p>\r\n<p>\r\n  <font color=\"#006600\">//version</font><br />\r\n  $synVersion=\"2 Beta\";</p>\r\n<p>\r\n  <strong>php.ini parameters</strong><br />\r\n You have to&nbsp;verify to have these values in your php.ini<br />\r\n  <br />\r\n  error_reporting = E_ALL &amp; ~E_NOTICE&nbsp;<br />\r\n register_globals = On&nbsp;<br />\r\n allow_call_time_pass_reference = On&nbsp;</p>\r\n<p>\r\n  <strong>Changing write permission</strong><br />\r\n  Give the write rights to these files:<br />\r\n /syntax desktop/config/cfg.php<br />\r\n  /syntax desktop/public/configs/files.txt<br />\r\n  /syntax desktop/includes/php/smarty/templates_c<br />\r\n /syntax desktop/modules/dump/backup/</p>\r\n<p>\r\n <strong>Run Syntax Desktop!</strong><br />\r\n  You can now launch Syntax Desktop in your browser.&nbsp;Syntax will ask you to choose the dump to load on yours database. Otherwise it will ask you to reshape the parameters of database connection.</p>\r\n<p>\r\n  &nbsp;</p>\r\n<p>\r\n <font color=\"#ff0000\">HELP ME!<br />\r\n  If you want to help me, send the right english translation of this page at </font><a href=\"mailto:info@dynamick.it\"><font color=\"#ff0000\">info@dynamick.it</font></a><font color=\"#ff0000\"> .<br />\r\n Thank you!</font></p>\r\n');
insert into `aa_translation` values ('208', 'Email non valida', 'Invalid email address');
insert into `aa_translation` values ('209', 'Personalizzazioni', 'Customization');
insert into `aa_translation` values ('210', '<p><strong>Personalizzazioni</strong></p>\r\n\r\n<p>Per poter adattare syntax al proprio sito, si devono avere conoscenze, se pur minime,&nbsp;di html e php. La cartella public all\'interno di Syntax contiene tutte le informazioni che dovete modificare per poter personalizzare il vostro sito.</p>\r\n\r\n<p>La gestione dei template è affidata al sistema <a href=\"http://smarty.php.net/\">smarty</a>. Quindi per poter costruire il proprio template occorre avere conoscenze di questo intuitivo sistema. Trovate documentazione presso il sito ufficiale di smarty: <a href=\"http://smarty.php.net/\">http://smarty.php.net/</a></p>\r\n\r\n<p>Nello specifico, la cartella public/ contiene:</p>\r\n\r\n<ul>\r\n <li>configs - attualmente in costruzione</li>\r\n <li>css - cartella preposta a contenere i fogli di stile del sito</li>\r\n  <li>img - la cartella delle immagini</li>\r\n <li>plugin - questa cartella contiene tutti i plugin che rendono dinamico il sito. Sono plugin di <a href=\"http://smarty.php.net/\">smarty</a>.</li>\r\n <li>templates - i file che contengono i template sono localizzati in questa cartella</li>\r\n</ul>\r\n\r\n<p>Queste sono le cartelle di default, ma nulla vieta di crearne di nuove.</p>\r\n', '<p>\r\n Now you have to customize your Syntax Desktop to adapt your requirements. It is necessary some HTML and PHP basis to administrate completely this cms.</p>\r\n<p>\r\n The \"public\" folder&nbsp;inside syntax path contains all the information&nbsp;you&nbsp;can modify for create your new site.</p>\r\n<p>\r\n  The&nbsp;template system is based&nbsp;on&nbsp;<a href=\"http://216.239.39.104/translate_c?hl=en&amp;u=http://smarty.php.net/\"><font color=\"#000000\">smarty </font></a>.&nbsp;You can&nbsp;find some documentation&nbsp;at the official smarty web site: <a href=\"http://216.239.39.104/translate_c?hl=en&amp;u=http://smarty.php.net/\"><font color=\"#000000\">http://smarty.php.net/</font></a></p>\r\n<p>\r\n The&nbsp; \"public\" folder contains this directories:</p>\r\n<ul>\r\n  <li>\r\n    configs - currently under construction</li>\r\n <li>\r\n    css - contains the cascading style sheets&nbsp;of your site&nbsp;</li>\r\n  <li>\r\n    img - this folder&nbsp;contains the&nbsp;images</li>\r\n  <li>\r\n    plugin - this folder contains all the smarty plugins.</li>\r\n  <li>\r\n    templates - this folder contains the templates of your website</li>\r\n <li>\r\n    mat - contains the uploaded document</li>\r\n <li>\r\n    backup - mysql dump repository</li>\r\n</ul>\r\n<p>\r\n These are some default directories&nbsp;but you to create new ones.</p>\r\n<p>\r\n  &nbsp;</p>\r\n<p>\r\n <font color=\"#ff0000\">HELP ME!<br />\r\n  If you want to help me, send the right english translation of this page at </font><a href=\"mailto:info@dynamick.it\"><font color=\"#ff0000\">info@dynamick.it</font></a><font color=\"#ff0000\"> .<br />\r\n Thank you!</font></p>\r\n');
insert into `aa_translation` values ('211', 'Contatti', 'Contacts');
insert into `aa_translation` values ('212', '<p>Per problemi o suggerimenti, scrivete sul forum presente su <a href=\"http://sourceforge.net/forum/?group_id=107986\" target=\"_blank\">sourceforge</a>.</p>\r\n\r\n<p>Per contattarmi direttamente, scrivete pure a <a href=\"mailto:info@dynamick.it\">info_AT_dynamick.it</a></p>\r\n', '<p>\r\n If you find some bug, or you have to ask something, write your message on the&nbsp;<a href=\"http://sourceforge.net/forum/?group_id=107986\" target=\"_blank\">sourceforge</a> forum.</p>\r\n<p>\r\n  You can also contact me&nbsp;via email at &nbsp;<a href=\"mailto:info@dynamick.it\">info_AT_dynamick.it</a></p>\r\n<p>\r\n  Have a good work!</p>\r\n');
insert into `aa_translation` values ('213', 'Requisiti', 'Requirements');
insert into `aa_translation` values ('214', '<p><strong>Requisiti</strong></p>\r\n\r\n<p>Syntax Desktop è un\'applicazione web-based che necessita di questi requisiti per poter funzionare.</p>\r\n\r\n<p><strong>Lato server</strong></p>\r\n\r\n<ol>\r\n <li>Webserver - Attualmente l\'applicazione è stata testata esclusivamente con Apache, ma questo non implica che funzioni solo con questo webserver</li>\r\n  <li>PHP - Interprete php. Testato con la versione 4</li>\r\n  <li>MySql - Testato con database mysql. Syntax usa il wrapper AdoDB, un layer che si occupa dell\'accesso al db. Questo implica che anche altri tipi di database potrebbero funzionare.<br />\r\n É preferito l\'uso di tabelle di tipo innoDb per poter utilizzare le foreign keys.</li>\r\n</ol>\r\n\r\n<p><strong>Lato client</strong></p>\r\n\r\n<p><strike>Purtroppo syntax funziona correttamente usando solamente&nbsp;Internet Explorer. Gli altri browser non sono mai stati testati completamente. Mi scuso con questa grave limitazione. Cercherò di aumentare la compatibilità il più presto possibile.</strike> Syntax Desktop funziona correttamente su tutti i moderni browser.</p>\r\n', '<p><strong>Requirement </strong></p>\r\n\r\n<p>Syntax Desktop&nbsp;needs these requirement for being able to work.</p>\r\n\r\n<p><strong>Server side </strong></p>\r\n\r\n<ol>\r\n  <li>Webserver - Currently the application has been&nbsp;tested exclusively with Apache, but this does not imply that other webservers can work.&nbsp;&nbsp;</li>\r\n  <li>PHP - Tested with version 4</li>\r\n  <li>MySql - Tested with MySql database. Syntax it uses the AdoDB library, a layer that&nbsp;take care of the access to the db. This implies that also other types of database could work.<br />\r\n Is preferred the use of innoDb tables&nbsp;type&nbsp;enabling use the foreign keys.</li>\r\n</ol>\r\n\r\n<p><strong>Side client </strong><br />\r\n<strike>Unfortunately syntax works correctly only using Internet Explorer. The others browsers are never&nbsp;been tested&nbsp;completely. In the near future I&nbsp;will try to increase the compatibility.</strike> Syntax Desktop works correctly with all modern browsers.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><font color=\"#ff0000\">HELP ME!<br />\r\nIf you want to help me, send the right english translation of this page at </font><a href=\"mailto:info@dynamick.it\"><font color=\"#ff0000\">info@dynamick.it</font></a><font color=\"#ff0000\"> .<br />\r\nThank you!</font></p>\r\n');
insert into `aa_translation` values ('215', 'Input non valido!', 'Invalid input!');
insert into `aa_translation` values ('216', 'Template', 'Template');
insert into `aa_translation` values ('217', '<p><strong>Costruzione dei Template</strong></p>\r\n\r\n<p>La prima cosa da fare per costruire il proprio sito è definire i template (o modelli grafici) che racchiuderanno i testi del sito. In SyntaxDesktop, la parte dedicata ai template è gestita dal motore <strong>smarty</strong>. Per maggiori informazioni vistate il sito <a href=\"http://www.smarty.net\">http://www.smarty.net</a>.</p>\r\n\r\n<p>Un template non è altro che un semplice file html o xhtml, statico, che contiene tutti i riferimenti alle immagini e script. All\'interno di questo file è però possibile inserire tag speciali. Questi tag si caraterizzano dal fatto che hanno come carattere di delimitazione la parentesi graffa. Un esempio di tag è questo:<br />\r\n<font color=\"#006600\">{news}</font><br />\r\noppure<br />\r\n<font color=\"#006600\">{$title}</font></p>\r\n\r\n<p>Distinguiamo 2 tipi di tag. Il tag che richiama funzioni ed il tag che richiama variabili. La distinzione tra i due tipi è denotata dal segno dollaro ($) prefissa al nome:</p>\r\n\r\n<ul>\r\n <li>Nel primo caso (<font color=\"#006600\">{news}</font> ) si fa riferimento ad un <strong>plugin</strong>, cioè si richiama una funzione php presente nella cartella <strong>/public/plugins/</strong> dal nome <strong>function.news.php</strong> .</li>\r\n <li>Nel secondo caso (<font color=\"#006600\">{$title}</font>)&nbsp;si fa riferimento ad una <strong>variabile</strong> che si chiama $title.</li>\r\n</ul>\r\n\r\n<p>A template ultimato, lo si deve salvare nella cartella /syntax desktop/public/template/ con l\'estensione .tpl. Per esempio, un nome corretto per un file template potrebbe essere <em>homepage.tpl</em> oppure <em>genericpage.tpl</em>.</p>\r\n\r\n<p>A questo punto attraverso Syntax Desktop si dovrà definire un nuovo template (amministrazione-&gt;template) cliccando il bottone \"nuovo documento\" nella toolbar di destra. Per definire un template occorre spedificare il nome ed il file, scegliendolo dal menu a tendina che compare alla voce files. Per esempio, per definire il template dell\'homepage, scriveremo \"Homepage\" nel campo del nome e sceglieremo il file homepage.tpl nel menu a tendina files.</p>\r\n', '<p><strong>Template creation</strong></p>\r\n\r\n<p>The first step you\'ve to do is the creation of your own template.</p>\r\n\r\n<p>A template&nbsp;is a simple&nbsp;HTML&nbsp;file that it contains all the references to the images and script. Inside a template you can also insert&nbsp;some special tags,&nbsp;smarty tags. These tags&nbsp;are charaterized&nbsp;by curly brakes. Here an example of this kind of tags:<br />\r\n<font color=\"#006600\">{news} </font><br />\r\nor<br />\r\n<font color=\"#006600\">{$title} </font></p>\r\n\r\n<p>We distinguish 2 tag types. The tag that it recalls functions and the tag that recalls variables. The distinction between the two types is denoted from the sign dollar ($) prefixed to the name.<br />\r\nIn the first case (<font color=\"#006600\"> {news} </font>) we reference&nbsp;a plugin, that&nbsp;launch <strong>function.news.php</strong>,<strong> </strong>a php function situated in the <strong>/syntax desktop/public/plugins/</strong>&nbsp;directory.<br />\r\nThe other row&nbsp;(<font color=\"#006600\"> {$title} </font>) reference&nbsp;a variable&nbsp;called $title.</p>\r\n\r\n<p>At the ending, you&nbsp;the template&nbsp;have to be saved in the /syntax desktop/public/template/ folder with&nbsp;<strong>.tpl</strong> extension. As an example, a correct name for a template&nbsp;could be homepage.tpl or genericpage.tpl.</p>\r\n\r\n<p>We have to define a new template inside Syntax Desktop database. Open contents-&gt;section-&gt;template and click&nbsp;\"new document\" in the right toolbar. In order to define template it is necessary to&nbsp;specify the name and the related template file. You have to choose it from the drop-down menu. As an example, in order to define the homepage template, we&nbsp; write \"homepage\" in the name field and&nbsp;choose the rows homepage.tpl in the dropdown menu.</p>\r\n\r\n<p><font color=\"#ff0000\">HELP ME!<br />\r\nIf you want to help me, send the right english translation of this page at </font><a href=\"mailto:info@dynamick.it\"><font color=\"#ff0000\">info@dynamick.it</font></a><font color=\"#ff0000\"> .<br />\r\nThank you!</font></p>\r\n');
insert into `aa_translation` values ('218', 'Logout eseguito correttamente.', 'Logout eseguito correttamente.');
insert into `aa_translation` values ('219', 'Pagine', 'Pages');
insert into `aa_translation` values ('220', '<p><strong>Generazione delle Pagine</strong></p>\r\n\r\n<p>Siamo pronti a definire le pagine del sito.</p>\r\n\r\n<p>Apriamo la sezione delle Pagine cliccando andando su Gestione Contenuti-&gt;Pagine.</p>\r\n\r\n<p>Nel centro della pagina vediamo l\'elenco delle pagine già&nbsp; definite, mentre sulla sinistra viene visualizzata la struttura ad albero delle pagine. Da questa schermata si possono modificare, cancellare le pagine esistenti oppure crearne di nuove.</p>\r\n\r\n<p>Ogni pagina è caratterizzata da un titolo, che verrà&nbsp; valorizzato nella variabile {$synPageTitle}, da un testo, utilizzato dal plugin {page} e da un template (vd. sezioni precedenti). É possibile anche definire un gruppo di utenti preposti alla gestione della pagina. Il checkbox \"visibile\" serve per visualizzare o meno la pagina all\'interno del menu di navigazione.</p>\r\n\r\n<p><strike>Per visualizzare il sito, non ci resta altro che compilare le pagine, cioè creare sul file system la struttura che abbiamo scelto per il nostro sito.&nbsp;Per ogni pagina verrà creata una cartella col nome della pagina e all\'\'interno verrà inserito un file index.php. Questo in modo ricorsivo, per ricreare la struttura ad albero definita in precedenza</strike>. Per esempio, per visualizzare una pagina che si chiama \"<strong>ultimapagina</strong>\" inserita nella \"<strong>sezione2</strong>\" che a sua volta è sottosta a \"<strong>sezione1</strong>\" sarà sufficiente andare su: <strong>www.miosito.it/sezione1/sezione2/ultimapagina/</strong>. Questa tecnica è stata ideata per evitare url troppo lunghi e con infiniti parametri passati via GET. Il vantaggio è quello di poter essere indicizzati agevolmente dai motori di ricerca, caratteristica fondamentale per i siti di successo.</p>\r\n', '<p>\r\n  <strong>Pages generation</strong></p>\r\n<p>\r\n  We are ready to&nbsp;start the page creation process.<br />\r\n You have to open the Content Management-&gt;Site section-&gt;Pages menu in Syntax Desktop.</p>\r\n<p>\r\n In the center of the page we see the already defined pages, while on the left&nbsp; you can see the same pages in a tree structure. These pages can be modified, deleted or can be created new ones.</p>\r\n<p>\r\n Every page is characterized&nbsp;by a title, ( you can retrieve this field in&nbsp;every template&nbsp;through&nbsp;the smarty variable {$synPageTitle}),&nbsp;by a text, ( you can retrieve this field in every template through the smarty&nbsp;plugin&nbsp;{page}) and by a template (see previous sections).&nbsp;It is also&nbsp;possible&nbsp;define a group of&nbsp;users&nbsp;that can manage the page. The \"visible\" checkbox&nbsp;specify either to show or to hide the page&nbsp;inside the&nbsp;navigation menu.</p>\r\n<p>\r\n In order to complete the page creation you have&nbsp;to compile the pages clicking the red button on the left of page.&nbsp;For every page it will&nbsp;created a folder with the name of the page and&nbsp;it will put inside&nbsp;an index.php file. This will done in a recursive way in order to recreate the page structure defined in the page tree visualization. As&nbsp;example, to&nbsp;display a page&nbsp;named&nbsp;<strong> \"lastpage</strong>\" in \"<strong> section2 </strong>\" that&nbsp;resides inside&nbsp;\"<strong>section1</strong>\" you have to go at <strong>www.mysite.com/section1/section2/lastpage/</strong>. This technique&nbsp;avoid&nbsp;too much long URL and&nbsp;fill with&nbsp;infinites GET parameters. In this way the search engines can easily index your pages,&nbsp;the main&nbsp;characteristic for the success web site.</p>\r\n<p>\r\n &nbsp;</p>\r\n<p>\r\n <font color=\"#ff0000\">HELP ME!<br />\r\n  If you want to help me, send the right english translation of this page at </font><a href=\"mailto:info@dynamick.it\"><font color=\"#ff0000\">info@dynamick.it</font></a><font color=\"#ff0000\"> .<br />\r\n Thank you!</font></p>\r\n');
insert into `aa_translation` values ('221', 'Tag Predefiniti', 'Predefined tags');
insert into `aa_translation` values ('222', '<p><strong>Tag Predefiniti</strong></p>\r\n\r\n<p>Elenchiamo di seguito i tag pronti all\'uso definiti da SyntaxDesktop.</p>\r\n\r\n<p><strong>Variabili</strong></p>\r\n\r\n<p>{$synPageTitle}</p>\r\n\r\n<ul>\r\n  <li><strong><font color=\"#009900\">{$synPageTitle}</font></strong>: restituisce il nome della pagina. Utile nell\'\'head della pagina quando si deve specificare il titolo. Per esempio:</li>\r\n  <li><strong><font color=\"#009900\">{$synPageId}</font></strong>: restituisce l\'\'id della pagina</li>\r\n <li><font color=\"#009900\"><strong>{$synPath}</strong></font>: restituisce il path di installazione di syntax desktop. Normalmente è \"syntax desktop\" ma il path di installazione è lasciato a discrezione dell\'\'utente. Questo tag è utile quando si devono puntare le immagini. Le immagini infatti, per pulizia della document root, è meglio riporle nella cartella /public/img/ . Per esempio, nei tag img, usiamo:</li>\r\n  <li><strong><font color=\"#009900\">{$synAbsPath}</font></strong>: contiene il path assoluto della cartella di installazione di syntax sul file system del web server.</li>\r\n</ul>\r\n\r\n<p><strong>Plugins</strong></p>\r\n\r\n<div><font color=\"#0099ff\">{page}</font></div>\r\n\r\n<ul>\r\n <li><font color=\"#009900\"><strong>{page}</strong></font>: funzione fondamentale, da porre in ogni template, che ha il compito di restituire il&nbsp;testo della pagina. Per esempio:<br />\r\n  In questo caso abbiamo definito un template banale in cui tutto il testo verrà racchiuso all\'interno di un.</li>\r\n</ul>\r\n', '<p>\r\n <strong>Predefined tags</strong></p>\r\n<p>\r\n We list&nbsp;the predefined&nbsp;tag you can use in your templates.</p>\r\n<p>\r\n  <strong>Variables</strong></p>\r\n<ul>\r\n  <li>\r\n    <strong><font color=\"#009900\">{$synPageTitle}</font></strong>: it returns the page name. Useful when used in the title tag in the head of the page. I.e.:</li>\r\n  <li>\r\n    <strong><font color=\"#009900\">{$synPageId}</font></strong>: it returns the page id</li>\r\n <li>\r\n    <font color=\"#009900\"><strong>{$synPath}</strong></font>: it returns the installation path of Syntax Desktop. Usually this variable contains&nbsp;\"syntax desktop\", but the installation path can be changed. This tag is very usefull when you use images or scripts. Images, in fact, have to be saved in the /syntax desktop/public/img/ directory, so you don\'t waste the site root. I.e.:<br />\r\n   <font color=\"#0099ff\"><img alt=\"example image\" src=\"{$synPath}/public/img/esempio.jpg\" /></font></li>\r\n <li>\r\n    <strong><font color=\"#009900\">{$synAbsPath}</font></strong>: it returns the absolute installation path of Syntax Desktop.</li>\r\n</ul>\r\n<p>\r\n  <strong>Plugins</strong></p>\r\n<ul>\r\n  <li>\r\n    <font color=\"#009900\"><strong>{page}</strong></font>: it returns the page contents. You must&nbsp;put this predefined plugin where you want the page text to be displayed. I.e.:<br />\r\n    In this example, we\'ve created a simple template where the page text is displayed inside a div tag.</li>\r\n</ul>\r\n<p>\r\n &nbsp;</p>\r\n<p>\r\n <font color=\"#ff0000\">HELP ME!<br />\r\n  If you want to help me, send the right english translation of this page at </font><a href=\"mailto:info@dynamick.it\"><font color=\"#ff0000\">info@dynamick.it</font></a><font color=\"#ff0000\"> .<br />\r\n Thank you!</font></p>\r\n');
insert into `aa_translation` values ('223', 'Tentativi di login', 'Login_attempts');
insert into `aa_translation` values ('224', 'Login falliti consecutivamente. Un numero alto indica un tentativo di violazione dell\'account.', '');
insert into `aa_translation` values ('225', 'Conteggio login', 'Conteggio login');
insert into `aa_translation` values ('226', 'Numero di login riusciti dalla creazione dell\\\'account.', 'Numero di login riusciti dalla creazione dell\\\'account.');
insert into `aa_translation` values ('227', 'Id', 'Id');
insert into `aa_translation` values ('228', '', '');
insert into `aa_translation` values ('229', 'Title', 'Title');
insert into `aa_translation` values ('230', '', '');
insert into `aa_translation` values ('231', 'Text', 'Text');
insert into `aa_translation` values ('232', '', '');
insert into `aa_translation` values ('233', 'Photo', 'Photo');
insert into `aa_translation` values ('234', '', '');
insert into `aa_translation` values ('235', 'Date', 'Date');
insert into `aa_translation` values ('236', '', '');
insert into `aa_translation` values ('237', 'Open Source Contest 2004', 'Open Source Contest 2004');
insert into `aa_translation` values ('238', '<p>Syntax Desktop vince nella categoria Business il primo concorso italiano per i progetti Open source. Per informazioni visitare il sito www.opensourcecontest.it</p>\r\n', 'Syntax Desktop won the first italian contest for the open source projects in the business category. You can read more informations at www.opensourcecontest.it');
insert into `aa_translation` values ('239', 'Id', 'Id');
insert into `aa_translation` values ('240', '', '');
insert into `aa_translation` values ('241', 'Album', 'Album');
insert into `aa_translation` values ('242', '', '');
insert into `aa_translation` values ('243', 'Id', 'Id');
insert into `aa_translation` values ('244', '', '');
insert into `aa_translation` values ('245', 'Title', 'Title');
insert into `aa_translation` values ('246', '', '');
insert into `aa_translation` values ('247', 'Date', 'Date');
insert into `aa_translation` values ('248', '', '');
insert into `aa_translation` values ('249', 'Photo', 'Photo');
insert into `aa_translation` values ('250', '', '');
insert into `aa_translation` values ('251', 'Album', 'Album');
insert into `aa_translation` values ('252', 'Photos', 'Photos');
insert into `aa_translation` values ('253', '', '');
insert into `aa_translation` values ('254', 'Id', 'Id');
insert into `aa_translation` values ('255', '', '');
insert into `aa_translation` values ('256', 'Title', 'Title');
insert into `aa_translation` values ('257', '', '');
insert into `aa_translation` values ('258', 'Photo', 'Photo');
insert into `aa_translation` values ('259', '', '');
insert into `aa_translation` values ('260', 'Album', 'Album');
insert into `aa_translation` values ('261', '', '');
insert into `aa_translation` values ('262', 'Ordine', 'Order');
insert into `aa_translation` values ('263', '', '');
insert into `aa_translation` values ('264', 'Parent_id', 'Parent_id');
insert into `aa_translation` values ('265', 'Seleziona il gruppo padre', 'choose the parent group');
insert into `aa_translation` values ('266', 'Owner', 'Owner');
insert into `aa_translation` values ('267', 'Il proprietario della pagina', 'Il proprietario della pagina');
insert into `aa_translation` values ('268', 'Owner', 'Owner');
insert into `aa_translation` values ('269', 'indica il proprietario del tempalte', 'indica il proprietario del tempalte');
insert into `aa_translation` values ('270', 'Url', 'Url');
insert into `aa_translation` values ('271', 'Eventuale collegamento ad un sito esterno(p.e. http://www.dynamick.it)', 'You can specify a link to an external site.\r\nI.e. http://www.dynamick.it');
insert into `aa_translation` values ('272', 'Owner', 'Owner');
insert into `aa_translation` values ('273', 'Se volete che l\'utente possa modificare la propria password, dovete scegliere il gruppo a cui appartiene.', 'If you want the user can change himself the password, you\'ve to choose the group he belongs to.');
insert into `aa_translation` values ('274', 'rss', 'rss');
insert into `aa_translation` values ('275', '', '');
insert into `aa_translation` values ('276', 'Plugin', 'Plugin');
insert into `aa_translation` values ('277', '404', '404');
insert into `aa_translation` values ('278', '', '');
insert into `aa_translation` values ('279', 'Media', 'Media');
insert into `aa_translation` values ('280', 'Media', 'Media');
insert into `aa_translation` values ('281', 'Media', 'Media');
insert into `aa_translation` values ('282', 'Upload media con interfaccia drag&drop', 'Upload media with drag&drop');
insert into `aa_translation` values ('283', 'Id', 'Id');
insert into `aa_translation` values ('284', '', '');
insert into `aa_translation` values ('285', 'Filename', 'Filename');
insert into `aa_translation` values ('286', '', '');
insert into `aa_translation` values ('287', 'Path', 'Path');
insert into `aa_translation` values ('288', '', '');
insert into `aa_translation` values ('289', 'Title', 'Title');
insert into `aa_translation` values ('290', '', '');
insert into `aa_translation` values ('291', 'Caption', 'Caption');
insert into `aa_translation` values ('292', '', '');
insert into `aa_translation` values ('293', 'Author', 'Author');
insert into `aa_translation` values ('294', '', '');
insert into `aa_translation` values ('295', 'Modificato il', 'Modified_at');
insert into `aa_translation` values ('296', '', '');
insert into `aa_translation` values ('297', 'Media', 'Media');
insert into `aa_translation` values ('298', 'Media', 'Media');
insert into `aa_translation` values ('299', 'Media Upload', 'Media Upload');
insert into `aa_translation` values ('300', 'Media Upload', 'Media Upload');
insert into `aa_translation` values ('301', 'Tags', 'Tags');
insert into `aa_translation` values ('302', 'tags list', 'tags list');
insert into `aa_translation` values ('303', 'Id', 'Id');
insert into `aa_translation` values ('304', '', '');
insert into `aa_translation` values ('305', 'Tag', 'Tag');
insert into `aa_translation` values ('306', '', '');
insert into `aa_translation` values ('307', 'Tags', 'Tags');
insert into `aa_translation` values ('308', 'Tags', 'Tags');
insert into `aa_translation` values ('309', 'Tagged', 'Tagged');
insert into `aa_translation` values ('310', 'Relazione tra media e tag', 'Relationship between media and tags');
insert into `aa_translation` values ('311', 'Id', 'Id');
insert into `aa_translation` values ('312', '', '');
insert into `aa_translation` values ('313', 'Media_id', 'Media_id');
insert into `aa_translation` values ('314', '', '');
insert into `aa_translation` values ('315', 'Tag_id', 'Tag_id');
insert into `aa_translation` values ('316', '', '');
insert into `aa_translation` values ('317', 'Tagged', 'Tagged');
insert into `aa_translation` values ('318', 'Tagged', 'Tagged');
insert into `aa_translation` values ('319', 'Gruppi', 'Groups');
insert into `aa_translation` values ('320', 'Gruppi utenti web', 'Web users groups');
insert into `aa_translation` values ('321', 'Id', 'Id');
insert into `aa_translation` values ('322', '', '');
insert into `aa_translation` values ('323', 'Gruppo', 'Group');
insert into `aa_translation` values ('324', '', '');
insert into `aa_translation` values ('325', 'Gruppi utenti web', 'Web users groups');
insert into `aa_translation` values ('326', 'Gruppi utenti web', 'Web users groups');
insert into `aa_translation` values ('327', 'Utenti', 'Users');
insert into `aa_translation` values ('328', 'Utenti web', 'Web users');
insert into `aa_translation` values ('329', 'Id', 'Id');
insert into `aa_translation` values ('330', '', '');
insert into `aa_translation` values ('331', 'Nome', 'Name');
insert into `aa_translation` values ('332', '', '');
insert into `aa_translation` values ('333', 'Cognome', 'Surname');
insert into `aa_translation` values ('334', '', '');
insert into `aa_translation` values ('335', 'Azienda', 'Company');
insert into `aa_translation` values ('336', '', '');
insert into `aa_translation` values ('337', 'Email', 'Email');
insert into `aa_translation` values ('338', '', '');
insert into `aa_translation` values ('339', 'Indirizzo', 'Address');
insert into `aa_translation` values ('340', '', '');
insert into `aa_translation` values ('341', 'Città', 'City');
insert into `aa_translation` values ('342', '', '');
insert into `aa_translation` values ('343', 'CAP', 'Zip');
insert into `aa_translation` values ('344', '', '');
insert into `aa_translation` values ('345', 'Provincia', 'Province');
insert into `aa_translation` values ('346', '', '');
insert into `aa_translation` values ('347', 'Confirmation_code', 'Confirmation_code');
insert into `aa_translation` values ('348', '', '');
insert into `aa_translation` values ('349', 'Attivo', 'Active');
insert into `aa_translation` values ('350', '', '');
insert into `aa_translation` values ('351', 'Gruppi', 'Groups');
insert into `aa_translation` values ('352', '', '');
insert into `aa_translation` values ('353', 'Timestamp', 'Timestamp');
insert into `aa_translation` values ('354', '', '');
insert into `aa_translation` values ('355', 'Password', 'Password');
insert into `aa_translation` values ('356', '', '');
insert into `aa_translation` values ('357', 'Newsletter', 'Newsletter');
insert into `aa_translation` values ('358', '', '');
insert into `aa_translation` values ('359', 'Utenti web', 'Web users');
insert into `aa_translation` values ('360', 'Utenti web', 'Web users');
insert into `aa_translation` values ('361', 'Documents', 'Documents');
insert into `aa_translation` values ('362', 'available documents', 'available documents');
insert into `aa_translation` values ('363', 'Id', 'Id');
insert into `aa_translation` values ('364', '', '');
insert into `aa_translation` values ('365', 'Title', 'Title');
insert into `aa_translation` values ('366', '', '');
insert into `aa_translation` values ('367', 'Description', 'Description');
insert into `aa_translation` values ('368', '', '');
insert into `aa_translation` values ('369', 'Date', 'Date');
insert into `aa_translation` values ('370', '', '');
insert into `aa_translation` values ('371', 'File', 'File');
insert into `aa_translation` values ('372', '', '');
insert into `aa_translation` values ('373', 'Category_id', 'Category_id');
insert into `aa_translation` values ('374', '', '');
insert into `aa_translation` values ('375', 'Gruppi abilitati', 'Enabled_groups');
insert into `aa_translation` values ('376', 'Limita l\'accesso al documento ai gruppi selezionati', '');
insert into `aa_translation` values ('377', 'Documenti', 'Documents');
insert into `aa_translation` values ('378', 'Documenti', 'Documents');
insert into `aa_translation` values ('379', 'Categorie', 'Categories');
insert into `aa_translation` values ('380', '', '');
insert into `aa_translation` values ('381', 'Id', 'Id');
insert into `aa_translation` values ('382', '', '');
insert into `aa_translation` values ('383', 'Category', 'Category');
insert into `aa_translation` values ('384', '', '');
insert into `aa_translation` values ('385', 'Order', 'Order');
insert into `aa_translation` values ('386', '', '');
insert into `aa_translation` values ('387', 'Categories', 'Categories');
insert into `aa_translation` values ('388', 'Categories', 'Categories');
insert into `aa_translation` values ('389', 'Users & Groups', 'Users & Groups');
insert into `aa_translation` values ('390', 'Docs', 'Docs');
insert into `aa_translation` values ('391', 'Users & Groups', 'Users & Groups');
insert into `aa_translation` values ('392', 'Utenti Backend', 'Users Backend');
insert into `aa_translation` values ('393', 'Documents', 'Docs');
insert into `aa_translation` values ('394', 'Area riservata', 'Private area');
insert into `aa_translation` values ('395', '', '<p>Please fill all the fields.</p>\r\n');
insert into `aa_translation` values ('396', 'Documents', 'Documents');
insert into `aa_translation` values ('397', '', '');
insert into `aa_translation` values ('398', 'Gallery', 'Gallery');
insert into `aa_translation` values ('399', '', '');
insert into `aa_translation` values ('400', 'Dictionary', 'Dictionary');
insert into `aa_translation` values ('401', 'User labels', 'User labels');
insert into `aa_translation` values ('402', 'Id', 'Id');
insert into `aa_translation` values ('403', '', '');
insert into `aa_translation` values ('404', 'Etichetta', 'Label');
insert into `aa_translation` values ('405', 'NON MODIFICARE!!!', '');
insert into `aa_translation` values ('406', 'Valore', 'Value');
insert into `aa_translation` values ('407', '', '');
insert into `aa_translation` values ('408', 'Dizionario', 'Dictionary');
insert into `aa_translation` values ('409', 'Dizionario', 'Dictionary');
insert into `aa_translation` values ('410', 'Dizionario', 'Dictionary');
insert into `aa_translation` values ('411', 'Status', 'Status');
insert into `aa_translation` values ('412', '<ul> <li>Public = visibile e scaricabile da chiunque</li> <li>Protected = visibile a tutti, scaricabile solo dagli utenti registrati</li> <li>Private = visibile agli utenti registrati, scaricabile solo da chi fa parte dei gruppi selezionati</li> <li>Secret = visibile e scaricabile solo da chi fa parte dei gruppi selezionati</li> <li>Suspended = non visibile e non scaricabile da nessuno</li> </ul>', '');
insert into `aa_translation` values ('413', 'Genera Sitemap', 'Generate sitemap');
insert into `aa_translation` values ('414', 'Generale', 'General');
insert into `aa_translation` values ('415', 'Primo documento', 'First Document');
insert into `aa_translation` values ('416', 'Documento di test', 'This is a test document');
insert into `aa_translation` values ('417', 'Forms', 'Forms');
insert into `aa_translation` values ('418', '', '');
insert into `aa_translation` values ('419', 'Id', 'Id');
insert into `aa_translation` values ('420', '', '');
insert into `aa_translation` values ('421', 'Pagina', 'Titolo');
insert into `aa_translation` values ('422', 'pagina a cui è associato il form', '');
insert into `aa_translation` values ('423', 'Descrizione', 'Descrizione');
insert into `aa_translation` values ('424', '', '');
insert into `aa_translation` values ('425', 'Destinatario', 'Destinatario');
insert into `aa_translation` values ('426', 'Casella di posta a cui inviare i dati, es. info@tuosito.com', 'Casella di posta a cui inviare i dati, es. info@tuosito.com');
insert into `aa_translation` values ('427', 'Mostra informativa Privacy', 'Privacy');
insert into `aa_translation` values ('428', '', 'Il testo dell\'informativa si trova nel dizionario');
insert into `aa_translation` values ('769', 'Pagina testo privacy', 'Pagina testo privacy');
insert into `aa_translation` values ('429', 'Captcha', 'Captcha');
insert into `aa_translation` values ('430', '', '');
insert into `aa_translation` values ('431', 'Risposta', 'Risposta');
insert into `aa_translation` values ('432', 'Messaggio da visualizzare dopo che il form è stato inviato con successo', 'Messaggio da visualizzare dopo che il form è stato inviato con successo');
insert into `aa_translation` values ('433', 'Data', 'Data');
insert into `aa_translation` values ('434', '', '');
insert into `aa_translation` values ('435', 'Visibile', 'Visibile');
insert into `aa_translation` values ('436', '', '');
insert into `aa_translation` values ('437', 'Forms', 'Forms');
insert into `aa_translation` values ('438', 'Campi dei form', 'Form fields');
insert into `aa_translation` values ('439', 'Campi del form selezionato', 'Fields of the selected form');
insert into `aa_translation` values ('440', 'Id', 'Id');
insert into `aa_translation` values ('441', '', '');
insert into `aa_translation` values ('442', 'Id_form', 'Id_form');
insert into `aa_translation` values ('443', '', '');
insert into `aa_translation` values ('444', 'Titolo', 'Titolo');
insert into `aa_translation` values ('445', '', '');
insert into `aa_translation` values ('446', 'Label', 'Label');
insert into `aa_translation` values ('447', '', '');
insert into `aa_translation` values ('448', 'Tipo', 'Tipo');
insert into `aa_translation` values ('449', 'Se di tipo select, impostare le opzioni nel servizio collegato', 'Se di tipo select, impostare le opzioni nel servizio collegato');
insert into `aa_translation` values ('450', 'Value', 'Value');
insert into `aa_translation` values ('451', 'Valore predefinito', 'Valore predefinito');
insert into `aa_translation` values ('452', 'Formato', 'Formato');
insert into `aa_translation` values ('453', 'Indicare il tipo di tato che si vuole accettare', 'Indicare il tipo di tato che si vuole accettare');
insert into `aa_translation` values ('454', 'Obbligatorio', 'Obbligatorio');
insert into `aa_translation` values ('455', '', '');
insert into `aa_translation` values ('456', 'Fieldset', 'Fieldset');
insert into `aa_translation` values ('457', '', '');
insert into `aa_translation` values ('458', 'Ordine', 'Ordine');
insert into `aa_translation` values ('459', '', '');
insert into `aa_translation` values ('460', 'Fieldsets', 'Fieldsets');
insert into `aa_translation` values ('461', '', '');
insert into `aa_translation` values ('462', 'Id', 'Id');
insert into `aa_translation` values ('463', '', '');
insert into `aa_translation` values ('464', 'Id_form', 'Id_form');
insert into `aa_translation` values ('465', '', '');
insert into `aa_translation` values ('466', 'Titolo', 'Titolo');
insert into `aa_translation` values ('467', '', '');
insert into `aa_translation` values ('468', 'Ordine', 'Ordine');
insert into `aa_translation` values ('469', '', '');
insert into `aa_translation` values ('470', 'Opzioni campo', 'Options field');
insert into `aa_translation` values ('471', 'Opzioni del campo selezionato (solo se di tipo select, checkbox o radio) ', 'Options selected fields (only for select, checkbox or radio) ');
insert into `aa_translation` values ('472', 'Id', 'Id');
insert into `aa_translation` values ('473', '', '');
insert into `aa_translation` values ('474', 'Id_field', 'Id_field');
insert into `aa_translation` values ('475', '', '');
insert into `aa_translation` values ('476', 'Label', 'Label');
insert into `aa_translation` values ('477', 'testo visibile all\'utente', 'testo visibile all\'utente');
insert into `aa_translation` values ('478', 'Value', 'Value');
insert into `aa_translation` values ('479', 'Valore trasmesso dal form', 'Valore trasmesso dal form');
insert into `aa_translation` values ('480', 'Selezionato', 'Selezionato');
insert into `aa_translation` values ('481', 'Opzione pre-selezionata di default', 'Opzione pre-selezionata di default');
insert into `aa_translation` values ('482', 'Ordine', 'Ordine');
insert into `aa_translation` values ('483', '', '');
insert into `aa_translation` values ('484', '<p>Compila il form per inviarci un messaggio:</p>\r\n', 'Compila il form per inviarci un messaggio:');
insert into `aa_translation` values ('485', '<h3>Messaggio inviato</h3>\r\n\r\n<p>La tua richiesta è stata inviata, il nostro staff risponderà appena possibile.<br />\r\nGrazie</p>\r\n', '<h3>Messaggio inviato</h3>\r\n<p>La tua richiesta &egrave; stata inviata, il nostro staff risponder&agrave; appena possibile.<br />\r\nGrazie</p>');
insert into `aa_translation` values ('486', 'Nome', 'Nome');
insert into `aa_translation` values ('487', 'Email', 'Email');
insert into `aa_translation` values ('488', 'Messaggio', 'Messaggio');
insert into `aa_translation` values ('489', 'News', 'News');
insert into `aa_translation` values ('490', '', '');
insert into `aa_translation` values ('491', 'Titolo', 'Titolo');
insert into `aa_translation` values ('492', '', '');
insert into `aa_translation` values ('493', 'Contattaci', '');
insert into `aa_translation` values ('494', 'Presa visione dell\'informativa fornita, acconsento al trattamento dei dati personali', 'I agree to the given terms.');
insert into `aa_translation` values ('495', 'Ai sensi dell\'articolo 10 della ex-legge 31.12.1996 n. 675, in ottemperanza all\'art. 13 del Nuovo Codice Privacy (dlgs 30 Giugno 2003 n° 196/2003) Le forniamo le seguenti informazioni:\r\nIl trattamento dei dati raccolti sarà effettuato mediante elaborazioni manuali, strumenti informatici e telematici e avrà le seguenti finalità:\r\n - dare esecuzione all\'invio delle email verso la casella postale degli utenti sottoscritti;\r\n - raccogliere le essenziali informazioni demografiche che ci permettano di perfezionare e promuovere i servizi promozionali e commerciali;\r\nI dati da Lei conferiti non verranno prestati, venduti o scambiati con altre organizzazioni, se non chiedendoLe espressamente il consenso.\r\nLa informiamo che il conferimento dei dati non è obbligatorio per eseguire l\'invio delle e-mail stesse.\r\nAl titolare del trattamento Lei potrà rivolgersi per far valere i suoi diritti così come previsti dall\'articolo13 della ex-legge n. 675/96 (accesso, rettifica, integrazione, cancellazione, opposizione ecc.), in ottemperanza all\'art. 7 del Nuovo Codice Privacy (dlgs 30 Giugno 2003 n°196).', 'Following the article 10 ex-law 12.31.1996 no. 675, in conformity with the article 13 of the New Code Privacy ( Dlgs June 30th, 2003 n° 196/2003 ) we give you the following information:\r\nThe treatment of the gathered data will be made by manual elaborations, data processing tools and for the following aims:\r\n          - dispatch of the Email to e-amail address of the subscribed users;\r\n          - gather the essential demographic information that let us perfect and advise the promotional and commercial offers;\r\nThe data conferred by you will not be lent, sold or exchanged with other organizations, if not expressly asking you the assent.\r\nWe inform you that the conferment of the data is optional and their possible non-conferment does not have any consequence.\r\nYou say request the holder of the data treatment as is permited by the law, article 13 ex-law no. 675/96 (accessing, reviewing, integration, deleting personal data), and as is permitted by article 7 New Code Privacy ( Dlgs June 30th, 2003 n° 196 ). ');
insert into `aa_translation` values ('496', 'Verificare i seguenti campi:', 'Check the following fields:');
insert into `aa_translation` values ('497', 'Campo obbligatorio', 'This field is required.');
insert into `aa_translation` values ('498', 'inserire un indirizzo valido', 'enter a valid email');
insert into `aa_translation` values ('499', 'verifica questo campo', 'check this field');
insert into `aa_translation` values ('500', 'Esporta servizi', 'Services export');
insert into `aa_translation` values ('501', 'Importa servizi', 'Services import');
insert into `aa_translation` values ('502', 'Cancella', 'Reset');
insert into `aa_translation` values ('503', 'Active', 'Active');
insert into `aa_translation` values ('504', '', '');
insert into `aa_translation` values ('505', 'Order', 'Order');
insert into `aa_translation` values ('506', '', '');
insert into `aa_translation` values ('507', 'Hai dimenticato la password?', 'Forgot your password?');
insert into `aa_translation` values ('508', 'Nuovo utente? Registrati qui', 'New user? Sign up here');
insert into `aa_translation` values ('509', 'Se possiedi gi&agrave; i dati per l\'autenticazione, inseriscili qui sotto', 'If you already have the data for authentication, enter them below');
insert into `aa_translation` values ('510', 'Email', 'Email');
insert into `aa_translation` values ('511', 'Password', 'Password');
insert into `aa_translation` values ('512', 'Invia', 'Send');
insert into `aa_translation` values ('513', 'Nome', 'Name');
insert into `aa_translation` values ('514', 'Cognome', 'Surname');
insert into `aa_translation` values ('515', 'Ragione sociale', 'Company name');
insert into `aa_translation` values ('516', 'Indirizzo', 'Address');
insert into `aa_translation` values ('517', 'Citt&agrave;', 'City');
insert into `aa_translation` values ('518', 'CAP', 'ZIP Code');
insert into `aa_translation` values ('519', 'Provincia', 'Province');
insert into `aa_translation` values ('520', 'Conferma la password', 'Confirm the password');
insert into `aa_translation` values ('521', 'Newsletter', 'Newsletter');
insert into `aa_translation` values ('522', 'desidero registrarmi al servizio', 'I wish to register to the service');
insert into `aa_translation` values ('523', 'Privacy', 'Privacy');
insert into `aa_translation` values ('524', 'ho letto e accettato', 'I have read and accepted');
insert into `aa_translation` values ('525', 'l\'informativa', 'The information');
insert into `aa_translation` values ('526', 'Annulla', 'Cancel');
insert into `aa_translation` values ('527', '5 caratteri', '5 characters');
insert into `aa_translation` values ('528', 'Per poter accedere ai file protetti di &egrave; necessario registrarsi. I campi marcati con * sono obbligatori:', 'In order to access protected files, you must register. Fields marked with * are required:');
insert into `aa_translation` values ('529', 'Dati personali', 'Personal data');
insert into `aa_translation` values ('530', 'Il vostro account', 'Your account');
insert into `aa_translation` values ('531', 'desidero registrarmi al servizio', 'I wish to register to the service');
insert into `aa_translation` values ('532', 'Scegli almeno 5 caratteri', 'Choose at least 5 characters');
insert into `aa_translation` values ('533', 'Le password non coincidono', 'Passwords do not match');
insert into `aa_translation` values ('534', 'I dati sono stati salvati', 'The data were saved');
insert into `aa_translation` values ('535', 'Il tuo account &egrave; pronto per essere attivato, clicca sul link che ti &egrave; appena stato spedito all\'indirizzo specificato per confermare la tua registrazione.', 'Your account is ready to be activated, click on the link that has just been sended to the email specified to confirm your registration.');
insert into `aa_translation` values ('536', 'Il tuo account &egrave; stato attivato correttamente.', 'Your account has been activated successfully.');
insert into `aa_translation` values ('537', 'Effettua il login', 'Login');
insert into `aa_translation` values ('538', 'Nome utente o password errati.', 'Username or password incorrect.');
insert into `aa_translation` values ('539', 'Sei autenticato come', 'You are logged in as');
insert into `aa_translation` values ('540', 'Effettua il logout', 'Logout');
insert into `aa_translation` values ('541', 'Login', 'Login');
insert into `aa_translation` values ('542', 'Registrati', 'Register');
insert into `aa_translation` values ('543', 'Logout', 'Logout');
insert into `aa_translation` values ('544', 'Benvenuto', 'Welcome');
insert into `aa_translation` values ('545', 'I tuoi dati sono stati aggiornati correttamente', 'Your information has been updated correctly');
insert into `aa_translation` values ('546', 'Inserisci la tua email, e i tuoi dati, se richiesti, ti verranno recapitati nuovamente al tuo account di posta', 'Enter your email address, and your data, if required, you will be redelivered to your email account');
insert into `aa_translation` values ('547', 'Password rigenerata', 'Password regenerated');
insert into `aa_translation` values ('548', 'Una mail con i tuoi nuovi dati di accesso &egrave; stata spedita all\'indirizzo', 'An email with your new login information was sent to your email address');
insert into `aa_translation` values ('549', 'Indirizzo non riconosciuto', 'Email not recognized');
insert into `aa_translation` values ('550', 'L\'indirizzo inserito non &egrave; presente nel database.', 'The address you entered is not in the database.');
insert into `aa_translation` values ('551', 'Riprova', 'Retry');
insert into `aa_translation` values ('552', 'Codice di sicurezza', 'Security Code');
insert into `aa_translation` values ('553', 'Non sei abilitato per scaricare questo file', 'You aren\'t allowed to download this file');
insert into `aa_translation` values ('554', 'Documento riservato', 'Confidential document');
insert into `aa_translation` values ('555', 'Amministrazione', 'Administration');
insert into `aa_translation` values ('556', 'Pagine', 'Pages');
insert into `aa_translation` values ('557', 'Amministrazione', 'Administration');
insert into `aa_translation` values ('558', 'Metatitle', 'Metatitle');
insert into `aa_translation` values ('559', '', '');
insert into `aa_translation` values ('560', 'Metadescription', 'Metadescription');
insert into `aa_translation` values ('561', '', '');
insert into `aa_translation` values ('562', 'Metakeywords', 'Metakeywords');
insert into `aa_translation` values ('563', '', '');
insert into `aa_translation` values ('564', 'Stili', 'Stili');
insert into `aa_translation` values ('565', '<h1>Titolo 1</h1>\r\n\r\n<h2>Titolo 2</h2>\r\n\r\n<h3>Titolo 3</h3>\r\n\r\n<p>Lorem ipsum dolor sit amet consectetuer lacinia auctor fringilla urna ligula. Sed at et dis odio lorem nibh Ut est <strong>neque Curabitur</strong>. Orci Proin ac semper consectetuer sed rutrum gravida vitae interdum congue. Neque Curabitur elit faucibus morbi est convallis congue eros convallis Sed. Nam felis justo nisl Vestibulum Curabitur Phasellus porttitor convallis tristique ridiculus. Massa quis dui Vestibulum enim scelerisque ac wisi id lacus ut. <a href=\"http://www.google.com\">Ante semper tellus</a>.</p>\r\n\r\n<p><img alt=\"\" src=\"/public/mat/image/syntax-box.gif\" style=\"width: 77px; height: 100px; float: left;\" />Dictumst id penatibus morbi <strong>parturient Vivamus</strong> orci et ligula feugiat dui. Massa habitasse pretium Suspendisse tincidunt laoreet id felis Pellentesque tellus eu. Ac Phasellus montes Morbi sodales Aenean sociis pellentesque Vestibulum sagittis volutpat. Consectetuer et ante et ac velit quis malesuada est Aenean est. Donec Nulla Sed tellus quis semper tellus consectetuer et nascetur et. Tellus fames mus nulla Curabitur eu eget Curabitur magna metus Vestibulum. <a href=\"http://www.google.com\">Nisl consequat</a>.</p>\r\n\r\n<p><img alt=\"\" src=\"/public/mat/image/syntax-box.gif\" style=\"width: 77px; height: 100px; float: right;\" />Ut scelerisque <em>Proin porttitor Quisque</em> gravida facilisis dignissim euismod at leo. Ante eu condimentum nonummy sem sagittis ut cursus amet senectus Aliquam. Nam vitae risus Nullam interdum at dolor dapibus laoreet Praesent auctor. Lobortis Vestibulum porttitor nibh nunc eu lacinia Curabitur Maecenas condimentum lorem. <strike>Volutpat sem justo ipsum nibh semper tempor</strike>.</p>\r\n\r\n<h4>Titolo 4</h4>\r\n\r\n<ul>\r\n  <li>Orci Curabitur feugiat mauris convallis</li>\r\n  <li>porttitor sagittis condimentum vitae Praesent</li>\r\n  <li>Neque cursus nisl Phasellus laoreet habitasse tristique</li>\r\n  <li>lacus adipiscing hendrerit velit. Phasellus vel fames commodo</li>\r\n  <li>Fermentum a sit egestas dolor tincidunt libero</li>\r\n <li>Curabitur Nulla tincidunt pellentesque augue at penatibus</li>\r\n  <li>Phasellus eros lorem tempus libero tortor Lorem wisi felis</li>\r\n</ul>\r\n\r\n<hr />\r\n<h5>Titolo 5</h5>\r\n\r\n<ol>\r\n <li>Quis elit nonummy laoreet et dolor</li>\r\n <li>Sed lorem Curabitur at a</li>\r\n <li>Libero odio id congue pretium convallis tristique</li>\r\n  <li>Platea lacinia molestie lacinia congue a interdum</li>\r\n  <li>Ultrices fringilla vel Morbi vitae at nascetur tellus</li>\r\n</ol>\r\n\r\n<p>Mi quis laoreet Vivamus malesuada lacinia dapibus nibh augue Lorem tellus. Mauris wisi et venenatis nec purus sapien lacus hendrerit dictum amet. Pellentesque orci Curabitur commodo ultrices mauris Vestibulum tincidunt in dui et. Pellentesque sit habitasse orci mi volutpat dis sit lorem quis tellus. Dolor adipiscing leo nibh Suspendisse pretium malesuada netus enim condimentum iaculis. Mauris interdum Pellentesque et a urna.</p>\r\n\r\n<table border=\"1\" cellpadding=\"1\" cellspacing=\"1\" style=\"width: 100%;\">\r\n <tbody>\r\n   <tr>\r\n      <td>massa condimentum</td>\r\n      <td>1000</td>\r\n     <td>150</td>\r\n      <td>1000000</td>\r\n    </tr>\r\n   <tr>\r\n      <td>pharetra quis Aenean</td>\r\n     <td>2000</td>\r\n     <td>250</td>\r\n      <td>2000000</td>\r\n    </tr>\r\n   <tr>\r\n      <td>Curabitur congue lacinia</td>\r\n     <td>3000</td>\r\n     <td>350</td>\r\n      <td>3000000</td>\r\n    </tr>\r\n   <tr>\r\n      <td>Phasellus ut malesuada</td>\r\n     <td>4000</td>\r\n     <td>450</td>\r\n      <td>4000000</td>\r\n    </tr>\r\n   <tr>\r\n      <td>Ultrices fringilla</td>\r\n     <td>5000</td>\r\n     <td>550</td>\r\n      <td>5000000</td>\r\n    </tr>\r\n </tbody>\r\n</table>\r\n\r\n<h6>Titolo 6</h6>\r\n\r\n<blockquote>\r\n<p>There are painters who transform the sun to a yellow spot, but there are others who with the help of their art and their intelligence, transform a yellow spot into the sun.<br />\r\n-Pablo Picasso</p>\r\n</blockquote>\r\n\r\n<pre>\r\n&nbsp; global $db;\r\n&nbsp; $ret = array();\r\n&nbsp; while(list($l)=$res-&gt;FetchRow()) \r\n    $ret[] = $l;\r\n&nbsp; return $ret;\r\n</pre>\r\n\r\n<address>Quis elit nonummy laoreet et dolor<br />\r\nSed lorem Curabitur at a<br />\r\nLibero odio id congue pretium convallis tristique</address>\r\n', '<h1>\r\n  Titolo 1</h1>\r\n<h2>\r\n Titolo 2</h2>\r\n<h3>\r\n Titolo 3</h3>\r\n<p>\r\n  Lorem ipsum dolor sit amet consectetuer lacinia auctor fringilla urna ligula. Sed at et dis odio lorem nibh Ut est <strong>neque Curabitur</strong>. Orci Proin ac semper consectetuer sed rutrum gravida vitae interdum congue. Neque Curabitur elit faucibus morbi est convallis congue eros convallis Sed. Nam felis justo nisl Vestibulum Curabitur Phasellus porttitor convallis tristique ridiculus. Massa quis dui Vestibulum enim scelerisque ac wisi id lacus ut. <a href=\"http://www.google.com\">Ante semper tellus</a>.</p>\r\n<p>\r\n Dictumst id penatibus morbi <strong>parturient Vivamus</strong> orci et ligula feugiat dui. Massa habitasse pretium Suspendisse tincidunt laoreet id felis Pellentesque tellus eu. Ac Phasellus montes Morbi sodales Aenean sociis pellentesque Vestibulum sagittis volutpat. Consectetuer et ante et ac velit quis malesuada est Aenean est. Donec Nulla Sed tellus quis semper tellus consectetuer et nascetur et. Tellus fames mus nulla Curabitur eu eget Curabitur magna metus Vestibulum. <a href=\"http://www.google.com\">Nisl consequat</a>.</p>\r\n<p>\r\n  Ut scelerisque <em>Proin porttitor Quisque</em> gravida facilisis dignissim euismod at leo. Ante eu condimentum nonummy sem sagittis ut cursus amet senectus Aliquam. Nam vitae risus Nullam interdum at dolor dapibus laoreet Praesent auctor. Lobortis Vestibulum porttitor nibh nunc eu lacinia Curabitur Maecenas condimentum lorem. <strike>Volutpat sem justo ipsum nibh semper tempor</strike>.</p>\r\n<h4>\r\n  Titolo 4</h4>\r\n<ul>\r\n <li>\r\n    Orci Curabitur feugiat mauris convallis</li>\r\n  <li>\r\n    porttitor sagittis condimentum vitae Praesent</li>\r\n  <li>\r\n    Neque cursus nisl Phasellus laoreet habitasse tristique</li>\r\n  <li>\r\n    lacus adipiscing hendrerit velit. Phasellus vel fames commodo</li>\r\n  <li>\r\n    Fermentum a sit egestas dolor tincidunt libero</li>\r\n <li>\r\n    Curabitur Nulla tincidunt pellentesque augue at penatibus</li>\r\n  <li>\r\n    Phasellus eros lorem tempus libero tortor Lorem wisi felis</li>\r\n</ul>\r\n<hr />\r\n<h5>\r\n  Titolo 5</h5>\r\n<ol>\r\n <li>\r\n    Quis elit nonummy laoreet et dolor</li>\r\n <li>\r\n    Sed lorem Curabitur at a</li>\r\n <li>\r\n    Libero odio id congue pretium convallis tristique</li>\r\n  <li>\r\n    Platea lacinia molestie lacinia congue a interdum</li>\r\n  <li>\r\n    Ultrices fringilla vel Morbi vitae at nascetur tellus</li>\r\n</ol>\r\n<p>\r\n  Mi quis laoreet Vivamus malesuada lacinia dapibus nibh augue Lorem tellus. Mauris wisi et venenatis nec purus sapien lacus hendrerit dictum amet. Pellentesque orci Curabitur commodo ultrices mauris Vestibulum tincidunt in dui et. Pellentesque sit habitasse orci mi volutpat dis sit lorem quis tellus. Dolor adipiscing leo nibh Suspendisse pretium malesuada netus enim condimentum iaculis. Mauris interdum Pellentesque et a urna.</p>\r\n<table border=\"1\" cellpadding=\"1\" cellspacing=\"1\" style=\"width: 100%;\">\r\n <tbody>\r\n   <tr>\r\n      <td>\r\n        massa condimentum</td>\r\n      <td>\r\n        1000</td>\r\n     <td>\r\n        150</td>\r\n      <td>\r\n        1000000</td>\r\n    </tr>\r\n   <tr>\r\n      <td>\r\n        pharetra quis Aenean</td>\r\n     <td>\r\n        2000</td>\r\n     <td>\r\n        250</td>\r\n      <td>\r\n        2000000</td>\r\n    </tr>\r\n   <tr>\r\n      <td>\r\n        Curabitur congue lacinia</td>\r\n     <td>\r\n        3000</td>\r\n     <td>\r\n        350</td>\r\n      <td>\r\n        3000000</td>\r\n    </tr>\r\n   <tr>\r\n      <td>\r\n        Phasellus ut malesuada</td>\r\n     <td>\r\n        4000</td>\r\n     <td>\r\n        450</td>\r\n      <td>\r\n        4000000</td>\r\n    </tr>\r\n   <tr>\r\n      <td>\r\n        Ultrices fringilla</td>\r\n     <td>\r\n        5000</td>\r\n     <td>\r\n        550</td>\r\n      <td>\r\n        5000000</td>\r\n    </tr>\r\n </tbody>\r\n</table>\r\n<p>\r\n &nbsp;</p>\r\n');
insert into `aa_translation` values ('566', 'Ricerca', 'Ricerca');
insert into `aa_translation` values ('567', 'ricerca', 'ricerca');
insert into `aa_translation` values ('568', '', '');
insert into `aa_translation` values ('569', '', '');
insert into `aa_translation` values ('570', '', '');
insert into `aa_translation` values ('571', '', '');
insert into `aa_translation` values ('572', '', '');
insert into `aa_translation` values ('573', '', '');
insert into `aa_translation` values ('574', '', '');
insert into `aa_translation` values ('575', '', '');
insert into `aa_translation` values ('576', '', '');
insert into `aa_translation` values ('577', '', '');
insert into `aa_translation` values ('578', 'Lavora con noi', 'Lavora con noi');
insert into `aa_translation` values ('579', '<p>Vuoi far parte di un gruppo di lavoro giovane e dinamico? Inviaci il tuo curriculum vitae: ti richiameremo non appena si apriranno posizioni inerenti il tuo profilo.</p>\r\n', '<p>\r\n  Vuoi far parte di un gruppo di lavoro giovane e dinamico? Inviaci il tuo curriculum vitae: ti richiameremo non appena si apriranno posizioni inerenti il tuo profilo.</p>\r\n');
insert into `aa_translation` values ('580', '<p>I tuoi dati sono stati inviati correttamente. Grazie per la disponibilità!</p>\r\n', '<p>\r\n  I tuoi dati sono stati inviati correttamente. Grazie per la disponibilit&agrave;!</p>\r\n');
insert into `aa_translation` values ('581', 'Nome', 'Nome');
insert into `aa_translation` values ('582', 'Cognome', 'Cognome');
insert into `aa_translation` values ('583', 'E-mail', 'E-mail');
insert into `aa_translation` values ('584', 'Telefono', 'Telefono');
insert into `aa_translation` values ('585', 'Curriculum Vitae', 'Curriculum Vitae');
insert into `aa_translation` values ('586', 'Note', 'Note');
insert into `aa_translation` values ('587', '', '');
insert into `aa_translation` values ('588', '', '');
insert into `aa_translation` values ('589', '', '');
insert into `aa_translation` values ('590', '', '');
insert into `aa_translation` values ('591', '', '');
insert into `aa_translation` values ('592', '', '');
insert into `aa_translation` values ('593', '', '');
insert into `aa_translation` values ('594', '', '');
insert into `aa_translation` values ('595', '', '');
insert into `aa_translation` values ('596', '', '');
insert into `aa_translation` values ('597', '', '');
insert into `aa_translation` values ('598', '', '');
insert into `aa_translation` values ('599', '', '');
insert into `aa_translation` values ('600', '', '');
insert into `aa_translation` values ('601', '', '');
insert into `aa_translation` values ('602', '', '');
insert into `aa_translation` values ('603', '', '');
insert into `aa_translation` values ('604', '', '');
insert into `aa_translation` values ('605', '', '');
insert into `aa_translation` values ('606', '', '');
insert into `aa_translation` values ('607', '', '');
insert into `aa_translation` values ('608', '', '');
insert into `aa_translation` values ('609', '', '');
insert into `aa_translation` values ('610', '', '');
insert into `aa_translation` values ('611', '', '');
insert into `aa_translation` values ('612', '', '');
insert into `aa_translation` values ('613', '', '');
insert into `aa_translation` values ('614', '', '');
insert into `aa_translation` values ('615', 'Visible', 'Visible');
insert into `aa_translation` values ('616', '', '');
insert into `aa_translation` values ('617', 'Elementi', 'Elementi');
insert into `aa_translation` values ('618', 'Elementi dei servizi di Syntax', 'Elementi dei servizi di Syntax');
insert into `aa_translation` values ('619', 'Id', 'Id');
insert into `aa_translation` values ('620', '', '');
insert into `aa_translation` values ('621', 'Classname', 'Classname');
insert into `aa_translation` values ('622', '', '');
insert into `aa_translation` values ('623', 'Name', 'Name');
insert into `aa_translation` values ('624', '', '');
insert into `aa_translation` values ('625', 'Order', 'Order');
insert into `aa_translation` values ('626', '', '');
insert into `aa_translation` values ('627', 'Elementi', 'Elementi');
insert into `aa_translation` values ('628', 'Default', '0');
insert into `aa_translation` values ('629', 'Lingua di default', '0');
insert into `aa_translation` values ('630', 'Slug', 'Slug');
insert into `aa_translation` values ('631', 'Path della pagina', 'Path della pagina');
insert into `aa_translation` values ('632', '', '');
insert into `aa_translation` values ('633', 'intro', 'intro');
insert into `aa_translation` values ('634', 'installazione', 'installation');
insert into `aa_translation` values ('635', '', '');
insert into `aa_translation` values ('636', '', '');
insert into `aa_translation` values ('637', '', '');
insert into `aa_translation` values ('638', 'personalizzazioni', 'customization');
insert into `aa_translation` values ('639', 'contatti', 'contacts');
insert into `aa_translation` values ('640', 'requisiti', 'requirements');
insert into `aa_translation` values ('641', 'rss', 'rss');
insert into `aa_translation` values ('642', '', '');
insert into `aa_translation` values ('643', '', '');
insert into `aa_translation` values ('644', '', '');
insert into `aa_translation` values ('645', '404', '404');
insert into `aa_translation` values ('646', '', '');
insert into `aa_translation` values ('647', '', '');
insert into `aa_translation` values ('648', '', '');
insert into `aa_translation` values ('649', 'area-riservata', 'private-area');
insert into `aa_translation` values ('650', '', '');
insert into `aa_translation` values ('651', '', '');
insert into `aa_translation` values ('652', '', '');
insert into `aa_translation` values ('653', 'documents', 'documents');
insert into `aa_translation` values ('654', 'gallery', 'gallery');
insert into `aa_translation` values ('655', 'news', 'news');
insert into `aa_translation` values ('656', 'template', 'template');
insert into `aa_translation` values ('657', 'pagine', 'pages');
insert into `aa_translation` values ('658', 'tag-predefiniti', 'predefined-tags');
insert into `aa_translation` values ('659', 'stili', 'stili');
insert into `aa_translation` values ('660', 'Last_update', 'Last_update');
insert into `aa_translation` values ('661', '', '');
insert into `aa_translation` values ('662', 'Last_access', 'Last_access');
insert into `aa_translation` values ('663', '', '');
insert into `aa_translation` values ('664', 'Last_ip', 'Last_ip');
insert into `aa_translation` values ('665', '', '');
insert into `aa_translation` values ('666', 'New_password_key', 'New_password_key');
insert into `aa_translation` values ('667', '', '');
insert into `aa_translation` values ('668', 'New_password_requested', 'New_password_requested');
insert into `aa_translation` values ('669', '', '');
insert into `aa_translation` values ('670', 'New_email', 'New_email');
insert into `aa_translation` values ('671', '', '');
insert into `aa_translation` values ('672', 'New_email_key', 'New_email_key');
insert into `aa_translation` values ('673', '', '');
insert into `aa_translation` values ('674', 'Hashed_id', 'Hashed_id');
insert into `aa_translation` values ('675', '', '');
insert into `aa_translation` values ('676', 'Utente autenticato come <strong>%s</strong>.', 'User logged in as <strong>%s</strong>.');
insert into `aa_translation` values ('677', 'Logout eseguito correttamente.', 'Logout succesful.');
insert into `aa_translation` values ('678', '<strong>Errore:</strong> password non corretta.', '<strong>Error:</strong> incorrect password.');
insert into `aa_translation` values ('679', '<strong>Errore:</strong> account non valido.', '<strong>Error:</strong> account not validated.');
insert into `aa_translation` values ('680', '<strong>Errore:</strong> utente %s non riconosciuto. Verificare di aver inserito correttamente il nome utente.', '<strong>Error:</strong> user %s not recognized. Please check your username.');
insert into `aa_translation` values ('681', 'Accedi', 'Sign in');
insert into `aa_translation` values ('682', 'Accedi', 'Login');
insert into `aa_translation` values ('683', 'Registrati', 'Sign up');
insert into `aa_translation` values ('684', 'Inserisci la tua email e ti spediremo le istruzioni necessarie:', 'Enter your email address and we\'ll send you the necessary instructions:');
insert into `aa_translation` values ('685', 'Nuovo utente? Registrati qui', 'New user? Register here');
insert into `aa_translation` values ('686', 'Scrivi la parola che vedi qui sopra', 'Write the word you see above');
insert into `aa_translation` values ('687', 'Salva', 'Save');
insert into `aa_translation` values ('688', 'Pagine di servizio', 'Pagine di servizio');
insert into `aa_translation` values ('689', '', '');
insert into `aa_translation` values ('690', '', '');
insert into `aa_translation` values ('691', '', '');
insert into `aa_translation` values ('692', '', '');
insert into `aa_translation` values ('693', '', '');
insert into `aa_translation` values ('694', 'Benvenuto', 'Welcome');
insert into `aa_translation` values ('695', 'I tuoi dati', 'Yours data');
insert into `aa_translation` values ('696', 'Cambia password', 'Change password');
insert into `aa_translation` values ('697', 'Cambia email', 'Change email');
insert into `aa_translation` values ('698', 'Esci senza salvare', 'Exit without save');
insert into `aa_translation` values ('699', 'Veccchia password', 'Old password');
insert into `aa_translation` values ('700', 'Inserisci la password corrente per poter modificare i dati', 'Enter your current password to apply the change');
insert into `aa_translation` values ('701', 'Inserici la nuova password', 'Insert new password');
insert into `aa_translation` values ('702', 'Conferma la nuova password', 'Confirm new password');
insert into `aa_translation` values ('703', 'Salva', 'Save');
insert into `aa_translation` values ('704', 'Annulla', 'Reset');
insert into `aa_translation` values ('705', 'Nuova email', 'New e-mail');
insert into `aa_translation` values ('706', 'Cambio email', 'E-mail change');
insert into `aa_translation` values ('707', 'Modifica password', 'Password change');
insert into `aa_translation` values ('708', 'Invia', 'Send');
insert into `aa_translation` values ('709', 'Campo obbligatorio', 'Required field');
insert into `aa_translation` values ('710', 'Le due password non coincidono', 'Passwords don\'t match');
insert into `aa_translation` values ('711', 'Impossibile aggiornare la password.', 'Can\'t update password');
insert into `aa_translation` values ('712', 'E-mail non riconosciuta', 'Unknown email');
insert into `aa_translation` values ('713', 'L\'account risulta inesistente.', 'Unknown account');
insert into `aa_translation` values ('714', 'La password non è corretta', 'Incorrect password');
insert into `aa_translation` values ('715', 'La vecchia password non è corretta', 'The old password isn\'t correct');
insert into `aa_translation` values ('716', 'Email già in uso', 'Mail address already in use');
insert into `aa_translation` values ('717', 'L\'account risulta già attivato.', 'This account is already active');
insert into `aa_translation` values ('718', 'Social Network', 'Social Network');
insert into `aa_translation` values ('719', '', '');
insert into `aa_translation` values ('720', 'Id', 'Id');
insert into `aa_translation` values ('721', '', '');
insert into `aa_translation` values ('722', 'Social', 'Social');
insert into `aa_translation` values ('723', '', '');
insert into `aa_translation` values ('724', 'Url', 'Url');
insert into `aa_translation` values ('725', '', '');
insert into `aa_translation` values ('726', 'Visible', 'Visible');
insert into `aa_translation` values ('727', '', '');
insert into `aa_translation` values ('728', 'Social networks', 'Social networks');
insert into `aa_translation` values ('729', 'Dati inviati', 'Dati inviati');
insert into `aa_translation` values ('730', '', '');
insert into `aa_translation` values ('731', 'Id', 'Id');
insert into `aa_translation` values ('732', '', '');
insert into `aa_translation` values ('733', 'Id_form', 'Id_form');
insert into `aa_translation` values ('734', '', '');
insert into `aa_translation` values ('735', 'Hash', 'Hash');
insert into `aa_translation` values ('736', '', '');
insert into `aa_translation` values ('737', 'Timestamp', 'Timestamp');
insert into `aa_translation` values ('738', '', '');
insert into `aa_translation` values ('739', 'Dati inviati', 'Dati inviati');
insert into `aa_translation` values ('740', 'News', 'News');
insert into `aa_translation` values ('741', 'Pagine', 'Pagine');
insert into `aa_translation` values ('742', 'Template', 'Template');
insert into `aa_translation` values ('743', 'Gestione Pagine', 'Gestione Pagine');
insert into `aa_translation` values ('744', 'Gestione Pagine', 'Gestione Pagine');
insert into `aa_translation` values ('745', 'Privacy', 'Privacy');
insert into `aa_translation` values ('746', 'privacy', 'privacy');
insert into `aa_translation` values ('747', '<h3>Informativa sulla privacy</h3>\r\n\r\n<p>I dati personali trattati da <strong>Syntax Demo</strong> (da qui in avanti: \"il Titolare\"), sono raccolti direttamente presso il soggetto interessato oppure presso terzi nell\'ipotesi in cui il Titolare acquisisca dati da società esterne per informazioni commerciali, ricerche di mercato, offerte dirette di prodotti o servizi. Se i dati non sono raccolti presso l\'interessato, l\'informativa, comprensiva delle categorie dei dati trattati, è data al medesimo interessato, all\'atto della registrazione dei dati o, quando è prevista la loro comunicazione, non oltre la prima comunicazione. In ogni caso, tutti questi dati vengono trattati nel rispetto della legge e degli obblighi di riservatezza cui è ispirata l\'attività del Titolare.</p>\r\n\r\n<h4>Finalità e modalità del trattamento.</h4>\r\n\r\n<p>I dati personali forniti dall\'interessato saranno oggetto di trattamento informatico e manuale da parte del Titolare, e/o di società di fiducia del Titolare per le seguenti finalità: raccolta di informazioni precontrattuali, inclusa la valutazione economico finanziaria e di prodotto; corretta gestione del rapporto contrattuale e delle relative obbligazioni, ove presenti; adempimento degli obblighi di legge, contabili, fiscali e di ogni altra natura comunque connessi alle finalità di cui ai precedenti punti; raccolta di informazioni utili per migliorare i servizi e i prodotti del Titolare attraverso, per esempio, attività di rilevazione del grado di soddisfazione dei Clienti sulla qualità dei servizi resi; conoscenza di nuovi servizi e prodotti attraverso, per esempio, l\'invio di materiale pubblicitario concernente prodotti o servizi propri o di terzi. Il Titolare potrà avvalersi di soggetti di propria fiducia che svolgono compiti di natura tecnica od organizzativa quali, a titolo esemplificativo: la prestazione di servizi di stampa, imbustamento, trasmissione, trasporto e smistamento di comunicazioni. I trattamenti avvengono mediante elaborazioni manuali o strumenti elettronici, o comunque automatizzati, secondo logiche strettamente correlate alle finalità stesse e comunque in modo tale da garantire la riservatezza e la sicurezza dei dati personali.</p>\r\n\r\n<h4>Categorie di soggetti ai quali i dati possono essere comunicati e trasferiti</h4>\r\n\r\n<p>Oltre che ai soggetti indicati nel paragrafo precedente, i dati personali relativi al soggetto interessato potranno essere comunicati e trasferiti: a soggetti terzi autorizzati dal Titolare&nbsp; ad espletare attività di marketing e di promozione per conto del Titolare.</p>\r\n\r\n<h4>Diritti dell\'interessato</h4>\r\n\r\n<p>L\'art. 7, del d.lgs. 196/2003 conferisce agli interessati l\'esercizio di specifici diritti. In particolare, l\'interessato può ottenere dal Titolare la conferma dell\'esistenza o meno di propri dati personali che lo riguardano e la loro comunicazione in forma intelligibile.<br />\r\nL\'interessato può altresì chiedere di conoscere l\'origine dei dati, le finalità e modalità del trattamento, nonchè la logica applicata in caso di trattamento effettuato con l\'ausilio di strumenti elettronici, l\'indicazione degli estremi identificativi del titolare, dei responsabili nominati e dei soggetti o delle categorie di soggetti ai quali i dati possono essere comunicati o che possono venirne a conoscenza. L\'interessato ha diritto di ottenere l\'aggiornamento, la rettificazione, l\'integrazione, la cancellazione, la trasformazione in via anonima o il blocco dei dati trattati in violazione di legge.</p>\r\n\r\n<p>L\'interessato ha diritto di opporsi, in tutto o in parte, per motivi legittimi al trattamento dei dati personali che lo riguardano. I diritti in oggetto potranno essere esercitati, anche per il tramite di un incaricato, mediante richiesta con lettera raccomandata inviata al Titolare o con posta elettronica (vedere pagina \"Contatti\").</p>\r\n\r\n<p>Il Titolare, per garantire l\'effettivo esercizio dei diritti dell\'interessato, adotta misure idonee volte ad agevolare l\'accesso ai dati personali da parte dell\'interessato medesimo e a semplificare le modalità e a ridurre i tempi per il riscontro al richiedente.</p>\r\n', '<h3>Privacy Policy</h3>\r\n\r\n<p>The personal data processed by Syntax Demo (henceforth: \"the Holder\"), are collected from the person concerned or from third parties in the event that the Holder acquires data from external companies for commercial information, market research, direct offers of products or services. If the data are not collected from the subject, the information, including the categories of processed data, is given subject at the time of registration of the data or, when their communication is envisaged, no later than the first communication. In any case, all this data is processed in accordance with the law and the obligations of confidentiality which inspires the activities of the Holder.</p>\r\n\r\n<h4>Purposes and methods of treatment.</h4>\r\n\r\n<p>The personal informations will be supplied to the Holder, and / or trusted company of the Holder for the following purposes: collection of pre-contractual information, including financial and economic evaluation of the product; proper management of the contract and its obligations, if any; fulfillment of legal obligations, accounting, tax and any other nature related to the purposes mentioned above; collection of useful information to improve services and products of the Holder through, for example, activity for detection of the degree of customer satisfaction on the quality of services rendered; knowledge of new services and products through, for example, sendindg advertising material concerning products or services or third parties. The Holder may use, subject to its confidence that perform tasks of a technical or organizational, but not limited to: the provision of printing services, packaging, transmission, transport and sorting of communications. The treatments will be processed manually or electronically, or automated, according to logic strictly related to the purposes and in any event so as to ensure the confidentiality and security of personal data.</p>\r\n\r\n<h4>Categories of persons to whom the data may be communicated and transferred</h4>\r\n\r\n<p>In addition to the persons specified in the preceding paragraph, the personal data concerning the subject may be disclosed and transferred: to third parties authorized by the Holder to carry out marketing and promotions on behalf of the Holder.</p>\r\n\r\n<h4>Rights of the person concerned</h4>\r\n\r\n<p>Article. 7 of Legislative Decree no. 196/2003 grants the parties concerned the exercise of certain rights. In particular, the user can get from the confirmation of the existence of personal data concerning him and their communication in intelligible form.</p>\r\n\r\n<p>The party can also ask to know the origin of the data, the purposes and methods of treatment, and the logic applied in case of treatment with the help of electronic means, the indication of the identity of the owner, manager appointed and the persons or classes of persons to whom the data may be communicated or who can learn about them. You have the right to obtain the updating, rectification, integration, cancellation, transformation in anonymous form or blocking of data processed in violation of the law.</p>\r\n\r\n<p>You have the right to object, in whole or in part, for legitimate reasons the processing of personal data concerning him. These rights may be exercised, including by means of a person, by registered letter sent to the Holder or by e-mail (please refer to the \"Contacts\" page).</p>\r\n\r\n<p>The Holder, to ensure the effective exercise of the rights of the person concerned, shall take appropriate measures to facilitate access to personal data by the interested parties and to streamline the procedures and reduce the time for replying to the applicant.</p>\r\n');
insert into `aa_translation` values ('748', '', '');
insert into `aa_translation` values ('749', '', '');
insert into `aa_translation` values ('750', '', '');
insert into `aa_translation` values ('751', 'I cookie ci aiutano a fornire servizi di qualità. Navigando su questo sito accetti il loro utilizzo.', 'Cookies help us offer quality services. By navigating on this website you accept their use.');
insert into `aa_translation` values ('752', 'Maggiori informazioni', 'Read more');
insert into `aa_translation` values ('753', 'Date', 'Date');
insert into `aa_translation` values ('754', '', '');
insert into `aa_translation` values ('755', 'Autore', 'Autore');
insert into `aa_translation` values ('756', '', '');
insert into `aa_translation` values ('757', 'Format', 'Format');
insert into `aa_translation` values ('758', '', '');
insert into `aa_translation` values ('759', 'Album dimostrativo', 'Demo Album');
insert into `aa_translation` values ('760', 'Visible', 'Visible');
insert into `aa_translation` values ('761', '', '');
insert into `aa_translation` values ('762', 'Faicon', 'Faicon');
insert into `aa_translation` values ('763', '', '');
insert into `aa_translation` values ('764', 'Ip', 'Ip');
insert into `aa_translation` values ('765', '', '');
insert into `aa_translation` values ('766', 'Domain', 'Domain');
insert into `aa_translation` values ('767', 'imposta il dominio su cui impostare la lingua (es. www.dominio.it). Lascia vuoto per abilitarla su qualsiasi dominio.', 'imposta il dominio su cui impostare la lingua (es. www.dominio.it). Lascia vuoto per abilitarla su qualsiasi dominio.');
insert into `aa_translation` values ('768', 'Area riservata', 'Private Area');
insert into `aa_translation` values ('770', 'Se non selezionata, usa il testo standard del dizionario', 'Se non selezionata, usa il testo standard del dizionario');
insert into `aa_translation` values ('771', 'Cliccando su <b>%s</b>, confermi di aver letto e accettato la nostra  <a href=\"%s\">privacy policy</a>.', 'By clicking on <b>\"%s\"</b>, you are agreeing to our <a href=\"%s\">privacy policy</a>.');
insert into `aa_translation` values ('772', 'Redirects', 'Redirects');
insert into `aa_translation` values ('773', 'URL da reindirizzare', 'URL da reindirizzare');
insert into `aa_translation` values ('774', 'Id', 'Id');
insert into `aa_translation` values ('775', '', '');
insert into `aa_translation` values ('776', 'From', 'From');
insert into `aa_translation` values ('777', 'Inserire l\'URL da reindirizzare, con dominio (http://miosito/vecchio-url/) o senza (/vecchio-url/).', '');
insert into `aa_translation` values ('778', 'To', 'To');
insert into `aa_translation` values ('779', 'Inserire l\'URL a cui reindirizzare la pagina. Il dominio è opzionale; è anche possibile inserire il segnaposto %server%.', '');
insert into `aa_translation` values ('780', 'Header', 'Header');
insert into `aa_translation` values ('781', 'Codice HTTP da ritornare al client.', '');
insert into `aa_translation` values ('782', 'Redirects', 'Redirects');
insert into `aa_translation` values ('783', 'Logs', 'Logs');
insert into `aa_translation` values ('784', '', '');
insert into `aa_translation` values ('785', 'Id', 'Id');
insert into `aa_translation` values ('786', '', '');
insert into `aa_translation` values ('787', 'Timestamp', 'Timestamp');
insert into `aa_translation` values ('788', '', '');
insert into `aa_translation` values ('789', 'Ip_address', 'Ip_address');
insert into `aa_translation` values ('790', '', '');
insert into `aa_translation` values ('791', 'User_agent', 'User_agent');
insert into `aa_translation` values ('792', '', '');
insert into `aa_translation` values ('793', 'Request_uri', 'Request_uri');
insert into `aa_translation` values ('794', '', '');
insert into `aa_translation` values ('795', 'Response', 'Response');
insert into `aa_translation` values ('796', '', '');
insert into `aa_translation` values ('797', 'Redirect_id', 'Redirect_id');
insert into `aa_translation` values ('798', '', '');
insert into `aa_translation` values ('799', 'Dispatched', 'Dispatched');
insert into `aa_translation` values ('800', '', '');
insert into `aa_translation` values ('801', 'Logs', 'Logs');
insert into `aa_translation` values ('802', 'Redirects', 'Redirects');
insert into `aa_translation` values ('803', 'Logs', 'Logs');
insert into `aa_translation` values ('804', 'Referer', 'Referer');
insert into `aa_translation` values ('805', '', '');


### structure of table `aa_users` ###

DROP TABLE IF EXISTS `aa_users`;

CREATE TABLE `aa_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `id_group` int(255) NOT NULL,
  `lang` int(11) NOT NULL,
  `owner` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8686 DEFAULT CHARSET=utf8 AUTO_INCREMENT=8686;


### data of table `aa_users` ###

insert into `aa_users` values ('8685', 'root', '2e51c0895c310b11347dc020caffa0ab', '1', '1', '1');


### structure of table `album` ###

DROP TABLE IF EXISTS `album`;

CREATE TABLE `album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `photo` varchar(255) NOT NULL,
  `visible` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5;


### data of table `album` ###

insert into `album` values ('4', '759', '2010-03-24 16:25:00', 'title|ordine|photos|photo|album', '1|2');


### structure of table `categories` ###

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;


### data of table `categories` ###

insert into `categories` values ('1', '414', '10');


### structure of table `dati_inviati` ###

DROP TABLE IF EXISTS `dati_inviati`;

CREATE TABLE `dati_inviati` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_form` int(11) NOT NULL DEFAULT '0',
  `hash` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;


### data of table `dati_inviati` ###



### structure of table `dictionary` ###

DROP TABLE IF EXISTS `dictionary`;

CREATE TABLE `dictionary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `label` (`label`)
) ENGINE=InnoDB AUTO_INCREMENT=151 DEFAULT CHARSET=utf8 AUTO_INCREMENT=151;


### data of table `dictionary` ###

insert into `dictionary` values ('1', 'informativa', '494');
insert into `dictionary` values ('2', 'informativa_privacy', '495');
insert into `dictionary` values ('3', 'checkfields', '496');
insert into `dictionary` values ('4', 'campo_obbligatorio', '497');
insert into `dictionary` values ('5', 'email_non_valida', '498');
insert into `dictionary` values ('6', 'verifica_valore', '499');
insert into `dictionary` values ('8', 'cancella', '502');
insert into `dictionary` values ('9', 'password_dimenticata', '507');
insert into `dictionary` values ('10', 'registrati_qui', '508');
insert into `dictionary` values ('11', 'inserisci_dati', '509');
insert into `dictionary` values ('12', 'email', '510');
insert into `dictionary` values ('13', 'password', '511');
insert into `dictionary` values ('14', 'invia', '512');
insert into `dictionary` values ('15', 'nome', '513');
insert into `dictionary` values ('16', 'cognome', '514');
insert into `dictionary` values ('17', 'ragione_sociale', '515');
insert into `dictionary` values ('18', 'indirizzo', '516');
insert into `dictionary` values ('19', 'citta', '517');
insert into `dictionary` values ('20', 'cap', '518');
insert into `dictionary` values ('21', 'provincia', '519');
insert into `dictionary` values ('23', 'account_password_confirm', '520');
insert into `dictionary` values ('24', 'newsletter', '521');
insert into `dictionary` values ('25', 'desidero_registrarmi', '522');
insert into `dictionary` values ('26', 'privacy', '523');
insert into `dictionary` values ('27', 'letto_accettato', '524');
insert into `dictionary` values ('28', 'letto_informativa', '525');
insert into `dictionary` values ('29', 'annulla', '526');
insert into `dictionary` values ('30', 'cinque_caratteri', '527');
insert into `dictionary` values ('31', 'necessaria_registrazione', '528');
insert into `dictionary` values ('32', 'dati_personali', '529');
insert into `dictionary` values ('33', 'vostro_account', '530');
insert into `dictionary` values ('35', 'registrazione_newsletter', '531');
insert into `dictionary` values ('36', 'almeno_cinque_caratteri', '532');
insert into `dictionary` values ('37', 'password_sbagliate', '533');
insert into `dictionary` values ('38', 'dati_salvati', '534');
insert into `dictionary` values ('39', 'conferma_registrazione', '535');
insert into `dictionary` values ('40', 'account_attivo', '536');
insert into `dictionary` values ('41', 'effettua_login', '537');
insert into `dictionary` values ('42', 'utente_password_errati', '538');
insert into `dictionary` values ('43', 'autenticato', '539');
insert into `dictionary` values ('44', 'effettua_logout', '540');
insert into `dictionary` values ('45', 'login', '541');
insert into `dictionary` values ('46', 'registrati', '542');
insert into `dictionary` values ('47', 'logout', '543');
insert into `dictionary` values ('48', 'benvenuto', '544');
insert into `dictionary` values ('49', 'dati_aggiornati', '545');
insert into `dictionary` values ('50', 'inserisci_dati', '546');
insert into `dictionary` values ('51', 'password_rigenerata', '547');
insert into `dictionary` values ('52', 'nuovi_dati_inviati', '548');
insert into `dictionary` values ('53', 'email_sconosciuta', '549');
insert into `dictionary` values ('54', 'indirizzo_non_presente', '550');
insert into `dictionary` values ('55', 'riprova', '551');
insert into `dictionary` values ('56', 'codice_sicurezza', '552');
insert into `dictionary` values ('57', 'doc_no_abilitazione', '553');
insert into `dictionary` values ('58', 'doc_riservato', '554');
insert into `dictionary` values ('59', 'flash_login_success', '676');
insert into `dictionary` values ('60', 'flash_logout', '677');
insert into `dictionary` values ('61', 'flash_error_password', '678');
insert into `dictionary` values ('62', 'flash_error_account', '679');
insert into `dictionary` values ('63', 'flash_error_user', '680');
insert into `dictionary` values ('64', 'sign_in', '681');
insert into `dictionary` values ('65', 'approvazione_disclaimer', '');
insert into `dictionary` values ('66', 'accesso_file_protetti', '');
insert into `dictionary` values ('67', 'account_old_pwd', '699');
insert into `dictionary` values ('68', 'email_recupero_password', '');
insert into `dictionary` values ('69', 'email_attivazione', '');
insert into `dictionary` values ('70', 'info_registrazione', '');
insert into `dictionary` values ('71', 'login_automatico', '');
insert into `dictionary` values ('72', 'reinserire_stesso_valore', '');
insert into `dictionary` values ('74', 'reg_reset', '');
insert into `dictionary` values ('75', 'fiscal_code', '');
insert into `dictionary` values ('76', 'login_submit', '');
insert into `dictionary` values ('77', 'telefono', '');
insert into `dictionary` values ('79', 'auth_incorrect_login', '');
insert into `dictionary` values ('80', 'auth_incorrect_password', '');
insert into `dictionary` values ('81', 'auth_incorrect_input', '');
insert into `dictionary` values ('83', 'message_new_email_activated', '');
insert into `dictionary` values ('84', 'message_new_email_failed', '');
insert into `dictionary` values ('85', 'message_new_password_sent', '');
insert into `dictionary` values ('86', 'message_email_updated', '');
insert into `dictionary` values ('87', 'message_password_updated', '');
insert into `dictionary` values ('88', 'message_update_ok', '');
insert into `dictionary` values ('89', 'message_registration_completed_1', '');
insert into `dictionary` values ('90', 'message_registration_completed_2', '');
insert into `dictionary` values ('91', 'message_activation_email_sent', '');
insert into `dictionary` values ('92', 'message_activation_ok', '');
insert into `dictionary` values ('93', 'message_activation_failed', '');
insert into `dictionary` values ('94', 'message_extension_queued', '');
insert into `dictionary` values ('95', 'email_change_email_subject', '');
insert into `dictionary` values ('96', 'email_activate_subject', '');
insert into `dictionary` values ('97', 'email_change_password_subject', '');
insert into `dictionary` values ('98', 'email_reset_password_subject', '');
insert into `dictionary` values ('99', 'error_email_used', '716');
insert into `dictionary` values ('100', 'error_incorrect_old_password', '715');
insert into `dictionary` values ('101', 'error_incorrect_password', '714');
insert into `dictionary` values ('102', 'error_invalid_email', '208');
insert into `dictionary` values ('103', 'error_invalid_input', '215');
insert into `dictionary` values ('104', 'error_invalid_token', '3');
insert into `dictionary` values ('105', 'error_new_email_failed', '205');
insert into `dictionary` values ('106', 'error_password_match', '710');
insert into `dictionary` values ('107', 'error_password_update', '711');
insert into `dictionary` values ('109', 'error_unknown_email', '712');
insert into `dictionary` values ('110', 'error_unknown_user', '713');
insert into `dictionary` values ('111', 'error_active_user', '717');
insert into `dictionary` values ('112', 'menu_account_login', '');
insert into `dictionary` values ('113', 'menu_account_register', '');
insert into `dictionary` values ('114', 'menu_account_forgot_password', '');
insert into `dictionary` values ('115', 'menu_account_reactivate', '');
insert into `dictionary` values ('116', 'menu_account_index', '');
insert into `dictionary` values ('117', 'menu_account_edit', '');
insert into `dictionary` values ('118', 'menu_account_edit_password', '');
insert into `dictionary` values ('119', 'menu_account_edit_email', '');
insert into `dictionary` values ('120', 'menu_account_exit', '');
insert into `dictionary` values ('121', 'login_submit', '682');
insert into `dictionary` values ('122', 'reg_submit', '683');
insert into `dictionary` values ('123', 'email_recupero_password', '684');
insert into `dictionary` values ('124', 'nuovo_utente', '685');
insert into `dictionary` values ('125', 'captcha', '686');
insert into `dictionary` values ('126', 'salva', '687');
insert into `dictionary` values ('127', 'account_welcome', '694');
insert into `dictionary` values ('128', 'account_dati', '695');
insert into `dictionary` values ('129', 'account_password', '696');
insert into `dictionary` values ('130', 'account_mail', '697');
insert into `dictionary` values ('131', 'account_exit', '698');
insert into `dictionary` values ('133', 'account_old_pwd_hint', '700');
insert into `dictionary` values ('135', 'password_hint', '701');
insert into `dictionary` values ('136', 'vecchia_password', '');
insert into `dictionary` values ('137', 'account_password_confirm_hint', '702');
insert into `dictionary` values ('138', 'conferma_password', '');
insert into `dictionary` values ('139', 'account_save', '703');
insert into `dictionary` values ('140', 'account_reset', '704');
insert into `dictionary` values ('141', 'account_new_email', '705');
insert into `dictionary` values ('142', 'email_change_email_subject', '706');
insert into `dictionary` values ('143', 'email_change_password_subject', '707');
insert into `dictionary` values ('144', 'account_send', '708');
insert into `dictionary` values ('145', 'error_required_field', '709');
insert into `dictionary` values ('146', 'message_logout', '218');
insert into `dictionary` values ('147', 'cookie_disclaimer', '751');
insert into `dictionary` values ('148', 'read_more', '752');
insert into `dictionary` values ('149', 'reserved_area', '768');
insert into `dictionary` values ('150', 'informativa_privacy_link', '771');


### structure of table `documents` ###

DROP TABLE IF EXISTS `documents`;

CREATE TABLE `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` varchar(150) NOT NULL,
  `date` date NOT NULL,
  `file` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `enabled_groups` varchar(255) NOT NULL,
  `status` enum('public','protected','private','secret','suspended') NOT NULL DEFAULT 'public',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;


### data of table `documents` ###

insert into `documents` values ('1', '415', '416', '2010-03-20', 'zip', '1', '1|2', 'private');


### structure of table `field_options` ###

DROP TABLE IF EXISTS `field_options`;

CREATE TABLE `field_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_field` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `selezionato` varchar(255) NOT NULL,
  `ordine` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


### data of table `field_options` ###



### structure of table `form_fields` ###

DROP TABLE IF EXISTS `form_fields`;

CREATE TABLE `form_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_form` int(11) NOT NULL DEFAULT '0',
  `titolo` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `tipo` enum('text','textarea','checkbox','radio','select','file','password','hidden') NOT NULL DEFAULT 'text',
  `value` varchar(255) NOT NULL,
  `formato` enum('text','date','digits','email') NOT NULL DEFAULT 'text',
  `obbligatorio` varchar(255) NOT NULL DEFAULT '',
  `fieldset` int(11) NOT NULL DEFAULT '0',
  `ordine` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 AUTO_INCREMENT=10;


### data of table `form_fields` ###

insert into `form_fields` values ('1', '1', 'nome', '486', 'text', '', 'text', '', '0', '10');
insert into `form_fields` values ('2', '1', 'email', '487', 'text', '', 'email', '1', '0', '20');
insert into `form_fields` values ('3', '1', 'messaggio', '488', 'textarea', '', 'text', '1', '0', '30');
insert into `form_fields` values ('4', '2', 'nome', '581', 'text', '', 'text', '1', '0', '10');
insert into `form_fields` values ('5', '2', 'cognome', '582', 'text', '', 'text', '1', '0', '20');
insert into `form_fields` values ('6', '2', 'email', '583', 'text', '', 'email', '1', '0', '30');
insert into `form_fields` values ('7', '2', 'telefono', '584', 'text', '', 'digits', '', '0', '40');
insert into `form_fields` values ('8', '2', 'curriculum', '585', 'file', '', 'text', '1', '0', '50');
insert into `form_fields` values ('9', '2', 'note', '586', 'textarea', '', 'text', '', '0', '60');


### structure of table `form_fieldsets` ###

DROP TABLE IF EXISTS `form_fieldsets`;

CREATE TABLE `form_fieldsets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_form` int(11) NOT NULL,
  `titolo` varchar(255) NOT NULL,
  `ordine` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


### data of table `form_fieldsets` ###



### structure of table `forms` ###

DROP TABLE IF EXISTS `forms`;

CREATE TABLE `forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descrizione` text NOT NULL,
  `destinatario` varchar(255) NOT NULL,
  `privacy` varchar(255) NOT NULL,
  `captcha` enum('nessuno','basic','synCaptcha','honeypot') NOT NULL DEFAULT 'nessuno',
  `risposta` text NOT NULL,
  `data` datetime NOT NULL,
  `visibile` varchar(255) NOT NULL,
  `pagina` int(255) NOT NULL DEFAULT '0',
  `titolo` varchar(255) NOT NULL,
  `privacy_page` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;


### data of table `forms` ###

insert into `forms` values ('1', '484', 'assistenza@kleis.it', '1', 'honeypot', '485', '2010-12-16 17:18:37', '1', '42', '493', '59');
insert into `forms` values ('2', '579', 'assistenza@kleis.it', '1', 'nessuno', '580', '2012-04-12 15:31:08', '', '0', '578', '0');


### structure of table `groups` ###

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;


### data of table `groups` ###

insert into `groups` values ('1', 'Standard Group');
insert into `groups` values ('2', 'Other Group');


### structure of table `media` ###

DROP TABLE IF EXISTS `media`;

CREATE TABLE `media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


### data of table `media` ###



### structure of table `news` ###

DROP TABLE IF EXISTS `news`;

CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `visible` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5;


### data of table `news` ###

insert into `news` values ('1', '192', '193', '', '2004-11-19 00:00:00', '1|2');
insert into `news` values ('2', '199', '200', '', '2004-12-27 12:00:00', '1|2');
insert into `news` values ('3', '201', '202', '', '2004-12-27 00:00:00', '1|2');
insert into `news` values ('4', '237', '238', '', '2005-04-19 08:30:00', '1|2');


### structure of table `photos` ###

DROP TABLE IF EXISTS `photos`;

CREATE TABLE `photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `album` int(11) NOT NULL,
  `ordine` int(11) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL,
  `autore` varchar(255) NOT NULL,
  `format` enum('portrait','landscape') NOT NULL DEFAULT 'portrait',
  PRIMARY KEY (`id`),
  KEY `album` (`album`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5;


### data of table `photos` ###

insert into `photos` values ('1', '8ab6a787b8ba3df15cb4c458f2f6f8c1087e6166_2048', 'jpg', '4', '10', '2015-03-07 12:16:10', '8685', 'landscape');
insert into `photos` values ('2', '9298626292fbf4371490aad63064dcbddb429f9f_2048', 'jpg', '4', '20', '2015-03-07 12:16:11', '8685', 'landscape');
insert into `photos` values ('3', 'a3bed77987333ce78e33dba9aec6adc103375f9e_2048', 'jpg', '4', '30', '2015-03-07 12:16:11', '8685', 'landscape');
insert into `photos` values ('4', 'fc64058e4f18255d34dbc6a6ece1f6cc961b6254_5', 'jpg', '4', '40', '2015-03-07 12:16:11', '8685', 'landscape');


### structure of table `redirect` ###

DROP TABLE IF EXISTS `redirect`;

CREATE TABLE `redirect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(255) NOT NULL,
  `to` varchar(255) NOT NULL,
  `header` enum('301','302','404') NOT NULL DEFAULT '301',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;


### data of table `redirect` ###



### structure of table `social_network` ###

DROP TABLE IF EXISTS `social_network`;

CREATE TABLE `social_network` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `social` enum('facebook','google-plus','linkedin','pinterest','twitter','flickr','youtube','instagram') NOT NULL DEFAULT 'facebook',
  `url` varchar(255) NOT NULL,
  `visible` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;


### data of table `social_network` ###

insert into `social_network` values ('1', 'facebook', 'https://www.facebook.com/pages/Kleis-Magazine/132067513501913', '1');
insert into `social_network` values ('2', 'google-plus', 'https://plus.google.com/u/0/101091220318592738939/', '1');


### structure of table `tagged` ###

DROP TABLE IF EXISTS `tagged`;

CREATE TABLE `tagged` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `media_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


### data of table `tagged` ###



### structure of table `tags` ###

DROP TABLE IF EXISTS `tags`;

CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


### data of table `tags` ###



### structure of table `users` ###

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `zip` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `confirmation_code` varchar(255) DEFAULT NULL,
  `group` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `newsletter` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `last_update` datetime NOT NULL,
  `last_access` datetime NOT NULL,
  `last_ip` varchar(255) DEFAULT NULL,
  `new_password_key` varchar(255) DEFAULT NULL,
  `new_password_requested` varchar(255) DEFAULT NULL,
  `new_email` varchar(255) DEFAULT NULL,
  `new_email_key` varchar(255) DEFAULT NULL,
  `hashed_id` varchar(255) DEFAULT NULL,
  `activated` varchar(255) DEFAULT NULL,
  `login_attempts` int(11) NOT NULL DEFAULT '0',
  `login_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5;


### data of table `users` ###

insert into `users` values ('4', 'Dummy', 'Dummy', 'SyntaxDesktop', 'info@syntaxdesktop.com', 'via Garibaldi', 'Villafranca', '37069', 'Verona', '', '2', '2ae96aa0273507ab41936958fb4dfd4a', '1', '2010-01-01 00:00:00', '2015-03-02 15:30:41', '2015-03-09 11:18:40', '127.0.0.1', null, null, '', '', '', '1', '0', '6');
