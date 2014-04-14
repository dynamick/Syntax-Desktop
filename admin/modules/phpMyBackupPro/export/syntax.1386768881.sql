# MySQL dump of database 'syntax' on host 'localhost'
# backup date and time: 04/14/14 14:52:40
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
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COMMENT='Elementi che compongono un contenitore' AUTO_INCREMENT=31;


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
  PRIMARY KEY (`id`),
  KEY `group` (`group`)
) ENGINE=MyISAM AUTO_INCREMENT=233 DEFAULT CHARSET=utf8 AUTO_INCREMENT=233;


### data of table `aa_group_services` ###

insert into `aa_group_services` values ('9', '3010', '18', '112', '1', '2', '', '1', '1', '1', '', 'user.png');
insert into `aa_group_services` values ('15', '3020', '18', '113', '1', '3', '', '1', '1', '1', '', 'accept.png');
insert into `aa_group_services` values ('18', '30', '0', '111', '1', '0', '', '1', '1', '1', '', 'star.png');
insert into `aa_group_services` values ('54', '1010', '152', '108', '1', '116', '', '1', '1', '1', '', 'application_double.png');
insert into `aa_group_services` values ('64', '3030', '18', '109', '1', '124', '', '1', '1', '1', '', 'image.png');
insert into `aa_group_services` values ('76', '1020', '152', 'News', '1', '127', '', '1', '1', '1', '', 'email.png');
insert into `aa_group_services` values ('125', '10', '128', '603', '2', '2', '', '', '', '', '', '.svn');
insert into `aa_group_services` values ('128', '40', '0', '819', '2', '0', '', '', '', '', '', 'accept.png');
insert into `aa_group_services` values ('129', '10', '131', 'Pagine', '2', '116', '', '', '', '', '', '');
insert into `aa_group_services` values ('130', '20', '131', 'Template', '2', '124', '', '', '', '', '', '');
insert into `aa_group_services` values ('131', '10', '0', 'Gestione Pagine', '2', '0', '', '', '', '', '', '');
insert into `aa_group_services` values ('135', '3040', '18', '114', '1', '5', '', '1', '1', '1', '', 'pencil.png');
insert into `aa_group_services` values ('151', '3050', '18', '115', '1', '136', '', '1', '1', '1', '', 'bricks.png');
insert into `aa_group_services` values ('152', '10', '0', '106', '1', '0', '', '', '', '', '', 'page_white_edit.png');
insert into `aa_group_services` values ('153', '40', '0', '117', '1', '0', '', '', '', '', '', 'wrench_orange.png');
insert into `aa_group_services` values ('159', '4020', '153', '123', '1', '0', '', '', '', '', 'modules/phpMyBackupPro/', 'database_save.png');
insert into `aa_group_services` values ('169', '50', '0', '133', '1', '0', '', '', '', '', '', 'help.png');
insert into `aa_group_services` values ('170', '5010', '169', '134', '1', '0', '', '', '', '', 'modules/help/doc.html', 'help.png');
insert into `aa_group_services` values ('171', '5020', '169', '135', '1', '0', '', '', '', '', 'modules/credits/index.php', 'bricks.png');
insert into `aa_group_services` values ('172', '3060', '18', '116', '1', '137', '', '1', '1', '1', '', 'world.png');
insert into `aa_group_services` values ('175', '5030', '169', '372', '1', '0', '', '', '', '', '', 'star.png');
insert into `aa_group_services` values ('176', '503010', '175', '373', '1', '0', '', '', '', '', 'http://www.dynamick.it/syntax-desktop/UI_dsl.html', 'image.png');
insert into `aa_group_services` values ('177', '503020', '175', '374', '1', '0', '', '', '', '', 'http://www.dynamick.it/syntax-desktop/serviceomatic_dsl.html', 'cog.png');
insert into `aa_group_services` values ('184', '1030', '152', '451', '1', '142', '', '1', '1', '1', '', '');
insert into `aa_group_services` values ('186', '3070', '18', '481', '1', '0', '', '', '', '', '/admin/modules/aa/plugins', 'plugin.png');
insert into `aa_group_services` values ('187', '20', '0', '484', '1', '0', '', '1', '1', '1', '', 'seasons.png');
insert into `aa_group_services` values ('188', '30', '0', '485', '2', '0', '', '1', '1', '1', '/admin/modules/aa/custom/media_upload.php', 'seasons.png');
insert into `aa_group_services` values ('189', '5010', '187', '502', '1', '144', '', '1', '1', '1', '', 'images.png');
insert into `aa_group_services` values ('190', '20', '188', '503', '2', '144', '', '1', '1', '1', '', '');
insert into `aa_group_services` values ('191', '5020', '187', '504', '1', '0', '', '1', '1', '1', '/admin/modules/aa/custom/media_upload.php', 'folder_page.png');
insert into `aa_group_services` values ('192', '10', '188', '505', '2', '0', '', '1', '1', '1', '/admin/modules/aa/custom/media_upload.php', 'folder_page.png');
insert into `aa_group_services` values ('193', '5030', '187', '512', '1', '145', '', '1', '1', '1', '', '');
insert into `aa_group_services` values ('194', '30', '188', '513', '2', '145', '', '1', '1', '1', '', '');
insert into `aa_group_services` values ('195', '5040', '187', '522', '1', '146', '', '1', '1', '1', '', '.svn');
insert into `aa_group_services` values ('196', '40', '188', '523', '2', '146', '', '1', '1', '1', '', '');
insert into `aa_group_services` values ('197', '104020', '205', '530', '1', '147', '', '1', '1', '1', '', 'accept.png');
insert into `aa_group_services` values ('198', '20', '207', '531', '2', '147', '', '1', '1', '1', '', 'accept.png');
insert into `aa_group_services` values ('199', '104010', '205', '564', '1', '148', '', '1', '1', '1', '', 'accept.png');
insert into `aa_group_services` values ('200', '10', '207', '565', '2', '148', '', '1', '1', '1', '', 'accept.png');
insert into `aa_group_services` values ('201', '105010', '206', '588', '1', '149', '', '1', '1', '1', '', '.svn');
insert into `aa_group_services` values ('202', '0', '208', '589', '2', '149', '', '1', '1', '1', '', '.svn');
insert into `aa_group_services` values ('203', '105020', '206', '598', '1', '150', '', '1', '1', '1', '', '.svn');
insert into `aa_group_services` values ('204', '20', '208', '599', '2', '150', '', '1', '1', '1', '', '.svn');
insert into `aa_group_services` values ('205', '1040', '152', '600', '1', '0', '', '1', '1', '1', '', 'group.png');
insert into `aa_group_services` values ('206', '1050', '152', '601', '1', '0', '', '1', '1', '1', '', 'application_double.png');
insert into `aa_group_services` values ('207', '20', '0', '602', '2', '0', '', '1', '1', '1', '', 'group.png');
insert into `aa_group_services` values ('208', '15', '0', '604', '2', '0', '', '1', '1', '1', '', 'arrow_rotate_anticlockwise.png');
insert into `aa_group_services` values ('209', '10', '210', '603', '3', '2', '', '', '1', '', '', '.svn');
insert into `aa_group_services` values ('210', '40', '0', '821', '3', '0', '', '', '', '', '', 'accept.png');
insert into `aa_group_services` values ('211', '10', '213', '820', '3', '116', '', '', '', '', '', 'accept.png');
insert into `aa_group_services` values ('213', '10', '0', 'Gestione Pagine', '3', '0', '', '', '', '', '', '');
insert into `aa_group_services` values ('225', '1070', '152', '619', '1', '151', '', '1', '1', '1', '', '');
insert into `aa_group_services` values ('226', '0', '213', '620', '3', '151', '', '1', '1', '1', '', '');
insert into `aa_group_services` values ('227', '0', '131', '621', '2', '151', '', '1', '1', '1', '', '');
insert into `aa_group_services` values ('228', '4030', '153', '625', '1', '0', '', '', '', '', '/admin/modules/sitemap/sitemap.php', 'chart_organisation.png');
insert into `aa_group_services` values ('229', '1060', '152', '661', '1', '152', '', '1', '1', '1', '', '');
insert into `aa_group_services` values ('230', '4030', '153', '761', '1', '0', '', '1', '1', '1', '/admin/modules/export/export_xml.php', 'accept.png');
insert into `aa_group_services` values ('231', '4040', '153', '762', '1', '0', '', '', '', '', '/admin/modules/import/import_xml.php', 'wand.png');
insert into `aa_group_services` values ('232', '3080', '18', '893', '1', '156', '', '1', '1', '1', '', '');


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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;


### data of table `aa_lang` ###

insert into `aa_lang` values ('1', 'italiano', 'it', 'italy.png', '1', '10', '1');
insert into `aa_lang` values ('2', 'english', 'en', 'greatbritain.png', '1', '20', '');


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
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 AUTO_INCREMENT=57;


### data of table `aa_page` ###

insert into `aa_page` values ('22', '92', '93', '0', '14', '1|2', '5', '1', '', '833', '834', '835', '898');
insert into `aa_page` values ('39', '398', '399', '22', '4', '1|2', '10', '1', '', '851', '852', '853', '899');
insert into `aa_page` values ('40', '400', '401', '22', '4', '', '20', '1', '', '901', '902', '903', '900');
insert into `aa_page` values ('41', '402', '403', '22', '4', '1|2', '40', '1', '', '854', '855', '856', '904');
insert into `aa_page` values ('42', '404', '405', '22', '13', '1|2', '50', '1', '', '857', '858', '859', '905');
insert into `aa_page` values ('43', '406', '407', '22', '4', '1|2', '15', '1', '', '860', '861', '862', '906');
insert into `aa_page` values ('44', '408', '409', '41', '4', '1|2', '10', '1', '', '872', '873', '874', '922');
insert into `aa_page` values ('45', '410', '411', '41', '4', '1|2', '20', '1', '', '875', '876', '877', '923');
insert into `aa_page` values ('46', '412', '413', '41', '4', '1|2', '15', '1', '', '878', '879', '880', '924');
insert into `aa_page` values ('50', '479', '480', '22', '8', '', '1000', '1', '', '908', '909', '910', '907');
insert into `aa_page` values ('51', '482', '483', '22', '4', '', '1999', '1', '', '912', '913', '914', '911');
insert into `aa_page` values ('52', '605', '606', '22', '9', '', '60', '1', '', '916', '917', '918', '915');
insert into `aa_page` values ('53', '607', '608', '22', '10', '1|2', '70', '1', '', '863', '864', '865', '919');
insert into `aa_page` values ('54', '609', '610', '22', '11', '1|2', '80', '1', '', '866', '867', '868', '920');
insert into `aa_page` values ('55', '750', '751', '22', '12', '1|2', '45', '1', '', '869', '870', '871', '921');
insert into `aa_page` values ('56', '828', '829', '41', '4', '1|2', '330', '1', '', '830', '831', '832', '925');


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
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 AUTO_INCREMENT=24;


### data of table `aa_service_joins` ###

insert into `aa_service_joins` values ('17', 'menu', '54', '72', '', '3');
insert into `aa_service_joins` values ('18', 'Joins', '45', '425', 'Join between two services', '5');
insert into `aa_service_joins` values ('19', 'Elements', '45', '410', 'List of the elements of this service', '5');
insert into `aa_service_joins` values ('20', 'Photo', '467', '475', 'List of the related photos', '142');
insert into `aa_service_joins` values ('21', 'Fieldset', '528', '549', '', '152');
insert into `aa_service_joins` values ('22', 'Campi', '528', '539', '', '152');
insert into `aa_service_joins` values ('23', 'Opzioni', '538', '553', '', '153');


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
) ENGINE=MyISAM AUTO_INCREMENT=157 DEFAULT CHARSET=utf8 PACK_KEYS=1 AUTO_INCREMENT=157;


### data of table `aa_services` ###

insert into `aa_services` values ('2', '144', '', 'images/service_icon/user.png', '145', '2', 'aa_users', '10', '1', '39', '');
insert into `aa_services` values ('3', '146', '', 'images/service_icon/group.png', '147', '2', 'aa_groups', '20', '1', '477', '');
insert into `aa_services` values ('4', '152', '', 'images/service_icon/chart_organisation.png', '153', '2', 'aa_group_services', '60', '1', '74', '1');
insert into `aa_services` values ('5', '138', '', 'images/service_icon/lightning.png', '139', '2', 'aa_services', '30', '1', '52', '1');
insert into `aa_services` values ('116', '154', '', 'images/service_icon/page_white_stack.png', '155', '2', 'aa_page', '70', '1', '350', '1');
insert into `aa_services` values ('124', '156', '', 'images/service_icon/layout.png', '157', '2', 'aa_template', '80', '1', '378', '');
insert into `aa_services` values ('127', '158', '', 'images/service_icon/newspaper.png', '159', '2', 'news', '100', '1', '-460', '1');
insert into `aa_services` values ('128', '150', '', 'images/service_icon/table_relationship.png', '151', '2', 'aa_service_joins', '50', '1', '405', '');
insert into `aa_services` values ('129', '148', '', 'images/service_icon/table.png', '149', '2', 'aa_services_element', '40', '1', '423', '1');
insert into `aa_services` values ('136', '140', '', 'images/service_icon/bricks.png', '141', '2', 'aa_lang', '120', '1', '560', '');
insert into `aa_services` values ('137', '142', '', 'images/service_icon/table.png', '143', '2', 'aa_translation', '110', '1', '454', '');
insert into `aa_services` values ('142', '441', '', 'images/service_icon/report.png', '442', '0', 'album', '130', '1', '-469', '');
insert into `aa_services` values ('143', '452', '', 'images/service_icon/picture.png', '453', '0', 'photos', '140', '1', '0', '');
insert into `aa_services` values ('144', '486', '', 'images/service_icon/images.png', '487', '0', 'media', '150', '1', '-488', '');
insert into `aa_services` values ('145', '506', '', 'images/service_icon/tag_blue.png', '507', '0', 'tags', '160', '1', '490', '');
insert into `aa_services` values ('146', '514', '', 'images/service_icon/vcard.png', '515', '0', 'tagged', '170', '1', '492', '');
insert into `aa_services` values ('147', '524', '', 'images/service_icon/group.png', '525', '2', 'groups', '180', '1', '494', '');
insert into `aa_services` values ('148', '532', '', 'images/service_icon/user_gray.png', '533', '2', 'users', '190', '1', '508', '');
insert into `aa_services` values ('149', '566', '', 'images/service_icon/layout.png', '567', '0', 'documents', '200', '1', '515', '1');
insert into `aa_services` values ('150', '590', '', 'images/service_icon/chart_organisation.png', '591', '0', 'categories', '210', '1', '523', '1');
insert into `aa_services` values ('151', '611', '', 'images/service_icon/book_open.png', '612', '0', 'dictionary', '5', '1', '525', '1');
insert into `aa_services` values ('152', '639', '', 'images/service_icon/application_form_edit.png', '640', '0', 'forms', '220', '1', '-536', '1');
insert into `aa_services` values ('153', '664', '', 'images/service_icon/table_relationship.png', '665', '2', 'form_fields', '240', '1', '547', '1');
insert into `aa_services` values ('154', '689', '', 'images/service_icon/application_double.png', '690', '0', 'form_fieldsets', '230', '1', '551', '1');
insert into `aa_services` values ('155', '700', '', 'images/service_icon/chart_organisation.png', '701', '2', 'field_options', '250', '1', '557', '1');
insert into `aa_services` values ('156', '883', '', 'images/service_icon/plugin.png', '884', '2', 'aa_element', '0', '1', '569', '');


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
) ENGINE=MyISAM AUTO_INCREMENT=580 DEFAULT CHARSET=utf8 COMMENT='Containers' AUTO_INCREMENT=580;


### data of table `aa_services_element` ###

insert into `aa_services_element` values ('39', '2', 'id', '1', '1', '', '', '222', '0', '223', '', '', '', '', '5', '', '');
insert into `aa_services_element` values ('40', '2', 'login', '2', '', '1', '1', '224', '255', '225', '', '', '', '', '15', '', '');
insert into `aa_services_element` values ('41', '2', 'passwd', '4', '', '1', '', '226', '255', '227', '', '', '', '', '25', '', '');
insert into `aa_services_element` values ('42', '2', 'id_group', '11', '', '1', '', '228', '255', '229', '', 'SELECT * FROM aa_groups', '', '', '35', '', '');
insert into `aa_services_element` values ('44', '2', 'lang', '11', '', '1', '', '232', '11', '233', '', 'SELECT * FROM aa_lang', '', '', '55', '', '');
insert into `aa_services_element` values ('45', '5', 'id', '1', '1', '1', '', '240', '0', '241', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('46', '5', 'name', '2', '', '1', '1', '242', '255', '243', '', '', '', '', '10', '', '1');
insert into `aa_services_element` values ('47', '5', 'path', '2', '', '', '1', '244', '255', '245', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('48', '5', 'icon', '2', '', '', '', '246', '255', '247', '', '', '', '', '30', '', '');
insert into `aa_services_element` values ('49', '5', 'description', '8', '', '', '', '248', '150', '249', '', '', '', '', '40', '', '1');
insert into `aa_services_element` values ('50', '5', 'parent', '12', '', '', '', '250', '11', '251', '', 'select * from aa_services', '', '', '50', '', '');
insert into `aa_services_element` values ('51', '5', 'syntable', '2', '', '1', '', '252', '255', '253', '', '', '', '', '60', '', '');
insert into `aa_services_element` values ('52', '5', 'order', '3', '', '1', '1', '254', '11', '255', '', '', '', '', '70', '', '');
insert into `aa_services_element` values ('53', '5', 'dbsync', '9', '', '1', '', '256', '255', '257', '', '', '1', '', '80', '', '');
insert into `aa_services_element` values ('54', '3', 'id', '1', '1', '', '', '236', '0', '237', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('55', '3', 'name', '2', '', '1', '1', '238', '255', '239', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('70', '4', 'id', '1', '1', '1', '', '274', '0', '275', '', '', '', '', '5', '', '');
insert into `aa_services_element` values ('71', '4', 'name', '2', '', '1', '1', '276', '255', '277', '', '', '', '', '15', '', '1');
insert into `aa_services_element` values ('72', '4', 'group', '3', '', '', '', '286', '11', '287', '', '', '', '', '55', '', '');
insert into `aa_services_element` values ('73', '4', 'service', '12', '', '1', '', '278', '11', '279', '1', 'select * from aa_services', '', '', '25', '', '');
insert into `aa_services_element` values ('74', '4', 'order', '3', '', '1', '1', '282', '11', '283', '', '', '', '', '35', '', '');
insert into `aa_services_element` values ('75', '4', 'parent', '16', '', '1', '', '288', '255', '289', 'name', '', '', '', '65', '', '');
insert into `aa_services_element` values ('80', '4', 'filter', '2', '', '1', '1', '284', '255', '285', '', '', '', '', '45', '', '');
insert into `aa_services_element` values ('141', '5', 'initOrder', '3', '', '', '', '260', '8', '261', '', '', '', '', '90', '', '');
insert into `aa_services_element` values ('347', '116', 'id', '1', '1', '', '', '298', '0', '299', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('348', '116', 'title', '2', '', '1', '', '300', '255', '301', '', '', '', '', '10', '', '1');
insert into `aa_services_element` values ('349', '116', 'text', '6', '', '', '', '302', '350', '303', 'Default', '', '', '', '20', '', '1');
insert into `aa_services_element` values ('350', '116', 'parent', '16', '', '1', '', '304', '11', '305', 'title', '', '', '', '30', '', '');
insert into `aa_services_element` values ('372', '116', 'template', '11', '', '1', '', '306', '11', '307', '', 'SELECT * FROM aa_template', '', '', '40', '', '');
insert into `aa_services_element` values ('378', '124', 'id', '1', '1', '', '', '314', '0', '315', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('379', '124', 'title', '2', '', '1', '1', '316', '255', '317', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('380', '124', 'timestamp', '15', '', '1', '', '318', '0', '319', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('381', '124', 'filename', '13', '', '1', '', '320', '255', '321', '1', '/public/templates', '', '', '30', '', '');
insert into `aa_services_element` values ('478', '116', 'owner', '18', '', '1', '', '469', '0', '470', '', '', '', '', '80', '', '');
insert into `aa_services_element` values ('397', '116', 'visible', '24', '', '1', '', '308', '255', '309', '', 'SELECT id, lang FROM `aa_lang` ', '1', '', '50', '', '');
insert into `aa_services_element` values ('398', '127', 'id', '1', '1', '', '', '200', '0', '201', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('403', '116', 'order', '3', '', '1', '1', '310', '11', '311', '', '', '', '', '60', '', '');
insert into `aa_services_element` values ('404', '128', 'id', '1', '1', '', '', '262', '0', '263', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('405', '128', 'title', '2', '', '1', '1', '264', '255', '265', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('406', '128', 'from', '12', '', '1', '', '266', '11', '267', '', 'SELECT e.id, concat( \'(\',s.name,  \') - \', e.name,  \'\'  )  FROM  `aa_services_element` e,  `aa_services` s WHERE e.container = s.id ORDER  BY s.name, e.order', '', '', '20', '', '');
insert into `aa_services_element` values ('407', '128', 'to', '12', '', '1', '', '268', '11', '269', '', 'SELECT e.id, concat( \'(\',t.en,  \') - \', e.name,  \'\'  )  FROM  `aa_services_element` e,  `aa_services` s,   `aa_translation` t WHERE e.container = s.id AND t.id=s.name ORDER  BY s.name, e.order', '', '', '30', '', '');
insert into `aa_services_element` values ('408', '128', 'description', '2', '', '1', '1', '270', '255', '271', '', '', '', '', '40', '', '');
insert into `aa_services_element` values ('409', '129', 'id', '1', '1', '', '', '166', '0', '167', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('410', '129', 'container', '3', '', '', '', '168', '11', '169', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('411', '129', 'name', '2', '', '1', '', '170', '255', '171', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('412', '129', 'type', '12', '', '1', '', '172', '255', '173', '', 'select * from aa_element', '', '', '30', '', '');
insert into `aa_services_element` values ('413', '129', 'iskey', '9', '', '', '', '174', '1', '175', '', '', '1', '', '40', '', '');
insert into `aa_services_element` values ('414', '129', 'isvisible', '9', '', '', '', '176', '1', '177', '', '', '1', '', '50', '', '');
insert into `aa_services_element` values ('415', '129', 'iseditable', '9', '', '', '', '178', '1', '179', '', '', '1', '', '60', '', '');
insert into `aa_services_element` values ('416', '129', 'label', '2', '', '1', '', '182', '255', '183', '', '', '', '', '70', '', '1');
insert into `aa_services_element` values ('417', '129', 'size', '3', '', '', '', '184', '8', '185', '', '', '', '', '80', '', '');
insert into `aa_services_element` values ('418', '129', 'help', '8', '', '1', '', '186', '0', '187', '', '', '', '', '90', '', '1');
insert into `aa_services_element` values ('419', '129', 'path', '2', '', '', '', '188', '255', '189', '', '', '', '', '100', '', '');
insert into `aa_services_element` values ('420', '129', 'qry', '8', '', '', '', '190', '0', '191', '', '', '', '', '110', '', '');
insert into `aa_services_element` values ('421', '129', 'value', '2', '', '', '', '192', '255', '193', '', '', '', '', '120', '', '');
insert into `aa_services_element` values ('422', '129', 'joins', '2', '', '', '', '194', '255', '195', '', '', '', '', '130', '', '');
insert into `aa_services_element` values ('423', '129', 'order', '3', '', '1', '', '196', '8', '197', '', '', '', '', '140', '', '');
insert into `aa_services_element` values ('424', '129', 'filter', '2', '', '', '', '198', '255', '199', '', '', '', '', '150', '', '');
insert into `aa_services_element` values ('425', '128', 'container', '3', '', '', '', '272', '11', '273', '', '', '', '', '50', '', '');
insert into `aa_services_element` values ('436', '136', 'id', '1', '1', '', '', '208', '0', '209', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('437', '136', 'lang', '2', '', '1', '1', '210', '255', '211', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('438', '136', 'initial', '2', '', '1', '1', '212', '10', '213', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('440', '4', 'insert', '9', '', '1', '', '290', '255', '291', '', '', '1', '', '70', '', '');
insert into `aa_services_element` values ('441', '4', 'modify', '9', '', '1', '', '292', '255', '293', '', '', '1', '', '80', '', '');
insert into `aa_services_element` values ('442', '4', 'delete', '9', '', '1', '', '294', '255', '295', '', '', '1', '', '90', '', '');
insert into `aa_services_element` values ('443', '4', 'link', '2', '', '', '', '280', '255', '281', '', '', '', '', '30', '', '');
insert into `aa_services_element` values ('444', '4', 'icon', '13', '', '1', '', '296', '255', '297', '', '/admin/modules/aa/images/service_icon/', '', '', '110', '', '');
insert into `aa_services_element` values ('449', '129', 'ismultilang', '9', '', '', '', '180', '1', '181', '', '', '1', '', '65', '', '');
insert into `aa_services_element` values ('451', '5', 'multilang', '9', '', '', '', '258', '255', '259', '', '', '1', '', '85', '', '');
insert into `aa_services_element` values ('453', '136', 'flag', '13', '', '1', '', '214', '255', '215', '', '/public/mat/flag', '', '', '30', '', '');
insert into `aa_services_element` values ('454', '137', 'id', '1', '1', '1', '', '216', '0', '217', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('455', '137', 'it', '8', '', '1', '1', '218', '0', '219', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('456', '137', 'en', '8', '', '1', '1', '220', '0', '221', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('457', '127', 'title', '2', '', '1', '', '342', '255', '343', '', '', '', '', '10', '', '1');
insert into `aa_services_element` values ('458', '127', 'text', '6', '', '', '', '344', '255', '345', 'Default', '', '', '', '20', '', '1');
insert into `aa_services_element` values ('459', '127', 'image', '5', '', '1', '', '346', '0', '347', '/public/mat', '', '', '', '30', '', '');
insert into `aa_services_element` values ('460', '127', 'date', '15', '', '1', '', '350', '0', '351', '', '', '', '', '40', '', '');
insert into `aa_services_element` values ('461', '140', 'id', '1', '1', '', '', '419', '0', '420', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('462', '140', 'title', '2', '', '1', '', '421', '255', '422', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('463', '140', 'text', '8', '', '', '', '423', '0', '424', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('464', '140', 'photo', '5', '', '1', '', '425', '0', '426', '', '', '', '', '30', '', '');
insert into `aa_services_element` values ('465', '140', 'date', '15', '', '1', '', '427', '0', '428', '', '', '', '', '40', '', '');
insert into `aa_services_element` values ('466', '141', 'id', '1', '1', '', '', '438', '0', '439', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('467', '142', 'id', '1', '1', '', '', '443', '0', '444', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('468', '142', 'title', '2', '', '1', '', '445', '255', '446', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('469', '142', 'date', '15', '', '1', '', '447', '0', '448', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('470', '142', 'photo', '10', '', '', '', '449', '0', '450', '/public/mat', '', 'title|ordine|photos|photo|album', '', '30', '', '');
insert into `aa_services_element` values ('471', '143', 'id', '1', '1', '', '', '454', '0', '455', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('472', '143', 'title', '2', '', '1', '', '456', '255', '457', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('474', '143', 'photo', '5', '', '1', '', '460', '0', '461', '/public/mat', '', '', '', '30', '', '');
insert into `aa_services_element` values ('475', '143', 'album', '11', '', '1', '', '462', '11', '463', '', 'SELECT * FROM album', '', '', '40', '', '');
insert into `aa_services_element` values ('476', '143', 'ordine', '3', '', '1', '1', '464', '0', '465', '', '', '', '', '50', '', '');
insert into `aa_services_element` values ('477', '3', 'parent_id', '16', '', '1', '', '467', '11', '468', 'name', '', '', '', '20', '', '');
insert into `aa_services_element` values ('479', '124', 'owner', '18', '', '1', '', '471', '0', '472', '', '', '', '', '40', '', '');
insert into `aa_services_element` values ('480', '116', 'url', '2', '', '1', '1', '473', '255', '474', '', '', '', '', '70', '', '');
insert into `aa_services_element` values ('481', '2', 'owner', '18', '', '1', '', '475', '0', '476', '', '', '', '', '60', '', '');
insert into `aa_services_element` values ('482', '144', 'id', '1', '1', '', '', '488', '0', '489', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('483', '144', 'filename', '2', '', '1', '', '490', '255', '491', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('484', '144', 'path', '2', '', '', '', '492', '255', '493', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('485', '144', 'title', '2', '', '1', '', '494', '255', '495', '', '', '', '', '30', '', '');
insert into `aa_services_element` values ('486', '144', 'caption', '2', '', '1', '', '496', '255', '497', '', '', '', '', '40', '', '');
insert into `aa_services_element` values ('487', '144', 'author', '2', '', '1', '', '498', '255', '499', '', '', '', '', '50', '', '');
insert into `aa_services_element` values ('488', '144', 'modified_at', '20', '', '1', '', '500', '0', '501', '', '', '', '', '60', '', '');
insert into `aa_services_element` values ('489', '145', 'id', '1', '1', '', '', '508', '0', '509', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('490', '145', 'tag', '2', '', '1', '1', '510', '255', '511', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('491', '146', 'id', '1', '1', '', '', '516', '0', '517', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('492', '146', 'media_id', '11', '', '1', '', '518', '11', '519', '', 'SELECT * FROM media', '', '', '10', '', '');
insert into `aa_services_element` values ('493', '146', 'tag_id', '11', '', '1', '', '520', '11', '521', '', 'SELECT * FROM tags', '', '', '20', '', '');
insert into `aa_services_element` values ('494', '147', 'id', '1', '1', '', '', '526', '0', '527', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('495', '147', 'group', '2', '', '1', '1', '528', '255', '529', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('496', '148', 'id', '1', '1', '', '', '534', '0', '535', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('497', '148', 'name', '2', '', '1', '', '536', '255', '537', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('498', '148', 'surname', '2', '', '1', '', '538', '255', '539', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('499', '148', 'company', '2', '', '', '', '540', '255', '541', '', '', '', '', '30', '', '');
insert into `aa_services_element` values ('500', '148', 'email', '2', '', '1', '', '542', '255', '543', '', '', '', '', '40', '', '');
insert into `aa_services_element` values ('501', '148', 'address', '2', '', '', '', '544', '255', '545', '', '', '', '', '50', '', '');
insert into `aa_services_element` values ('502', '148', 'city', '2', '', '', '', '546', '255', '547', '', '', '', '', '60', '', '');
insert into `aa_services_element` values ('503', '148', 'zip', '2', '', '', '', '548', '255', '549', '', '', '', '', '70', '', '');
insert into `aa_services_element` values ('504', '148', 'province', '2', '', '', '', '550', '255', '551', '', '', '', '', '80', '', '');
insert into `aa_services_element` values ('505', '148', 'confirmation_code', '2', '', '', '', '552', '255', '553', '', '', '', '', '90', '', '');
insert into `aa_services_element` values ('506', '148', 'active', '9', '', '1', '', '554', '255', '555', '', '', '1', '', '100', '', '');
insert into `aa_services_element` values ('507', '148', 'group', '24', '', '1', '', '556', '11', '557', '', 'SELECT * FROM groups', '', '', '110', '', '');
insert into `aa_services_element` values ('508', '148', 'created_at', '30', '', '1', '', '558', '0', '559', '', '', '', '', '145', '', '');
insert into `aa_services_element` values ('509', '148', 'password', '4', '', '', '', '560', '255', '561', '', '', '', '', '130', '', '');
insert into `aa_services_element` values ('510', '148', 'newsletter', '9', '', '', '', '562', '255', '563', '', '', '1', '', '140', '', '');
insert into `aa_services_element` values ('511', '149', 'id', '1', '1', '', '', '568', '0', '569', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('512', '149', 'title', '2', '', '1', '', '570', '255', '571', '', '', '', '', '10', '', '1');
insert into `aa_services_element` values ('514', '149', 'description', '2', '', '', '', '574', '150', '575', '', '', '', '', '30', '', '1');
insert into `aa_services_element` values ('515', '149', 'date', '14', '', '1', '', '576', '0', '577', '', '', '', '', '40', '', '');
insert into `aa_services_element` values ('517', '149', 'file', '5', '', '1', '', '580', '0', '581', '/public/mat/documents', '', '', '', '60', '', '');
insert into `aa_services_element` values ('518', '149', 'category_id', '12', '', '1', '', '582', '11', '583', '', 'SELECT * FROM categories', '', '', '70', '', '');
insert into `aa_services_element` values ('519', '149', 'enabled_groups', '24', '', '1', '', '584', '255', '585', '', 'SELECT * FROM groups', '', '', '90', '', '');
insert into `aa_services_element` values ('521', '150', 'id', '1', '1', '', '', '592', '0', '593', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('522', '150', 'category', '2', '', '1', '', '594', '255', '595', '', '', '', '', '10', '', '1');
insert into `aa_services_element` values ('523', '150', 'order', '3', '', '1', '1', '596', '11', '597', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('524', '151', 'id', '1', '1', '', '', '613', '0', '614', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('525', '151', 'label', '2', '', '1', '', '615', '255', '616', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('526', '151', 'value', '8', '', '1', '', '617', '0', '618', '', '', '', '', '20', '', '1');
insert into `aa_services_element` values ('527', '149', 'status', '23', '', '1', '', '622', '0', '623', '', 'public|protected|private|secret|suspended', '', '', '80', '', '');
insert into `aa_services_element` values ('528', '152', 'id', '1', '1', '', '', '641', '0', '642', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('529', '152', 'pagina', '12', '', '1', '', '643', '255', '644', '1', 'SELECT id,title FROM `aa_page` ORDER BY `order`', '', '', '5', '', '');
insert into `aa_services_element` values ('530', '152', 'descrizione', '6', '', '', '', '645', '150', '646', 'Default', '', '', '', '20', '', '1');
insert into `aa_services_element` values ('531', '152', 'destinatario', '2', '', '1', '', '647', '255', '648', '', '', '', '', '30', '', '');
insert into `aa_services_element` values ('533', '152', 'privacy', '9', '', '', '', '651', '255', '652', '', '', '1', '', '50', '', '');
insert into `aa_services_element` values ('534', '152', 'captcha', '23', '', '', '', '653', '0', '654', '', 'nessuno|basic|synCaptcha|honeypot', '', '', '60', '', '');
insert into `aa_services_element` values ('535', '152', 'risposta', '6', '', '', '', '655', '150', '656', 'Default', '', '', '', '70', '', '1');
insert into `aa_services_element` values ('536', '152', 'data', '15', '', '1', '', '657', '0', '658', '', '', '', '', '80', '', '');
insert into `aa_services_element` values ('537', '152', 'visibile', '9', '', '1', '', '659', '255', '660', '', '', '1', '', '90', '', '');
insert into `aa_services_element` values ('538', '153', 'id', '1', '1', '', '', '666', '0', '667', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('539', '153', 'id_form', '3', '', '', '', '668', '11', '669', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('540', '153', 'titolo', '2', '', '1', '', '670', '255', '671', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('541', '153', 'label', '2', '', '1', '', '672', '255', '673', '', '', '', '', '30', '', '1');
insert into `aa_services_element` values ('542', '153', 'tipo', '23', '', '1', '', '674', '0', '675', '', 'text|textarea|checkbox|radio|select|file|password', '', '', '40', '', '');
insert into `aa_services_element` values ('543', '153', 'value', '2', '', '', '', '676', '255', '677', '', '', '', '', '50', '', '');
insert into `aa_services_element` values ('544', '153', 'formato', '23', '', '', '', '678', '255', '679', '', 'text|date|digits|email', '', '', '60', '', '');
insert into `aa_services_element` values ('545', '153', 'obbligatorio', '9', '', '1', '', '680', '255', '681', '', '', '1', '', '70', '', '');
insert into `aa_services_element` values ('546', '153', 'fieldset', '12', '', '1', '', '682', '11', '683', '1', 'select id,titolo from `form_fieldsets` WHERE id_form=#{join|value|id_join=22} ORDER BY ordine', '', '', '80', '', '');
insert into `aa_services_element` values ('547', '153', 'ordine', '3', '', '1', '1', '684', '11', '685', '', '', '', '', '90', '', '');
insert into `aa_services_element` values ('548', '154', 'id', '1', '1', '', '', '691', '0', '692', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('549', '154', 'id_form', '3', '', '', '', '693', '11', '694', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('550', '154', 'titolo', '2', '', '1', '', '695', '255', '696', '', '', '', '', '20', '', '1');
insert into `aa_services_element` values ('551', '154', 'ordine', '2', '', '1', '1', '697', '255', '698', '', '', '', '', '30', '', '');
insert into `aa_services_element` values ('552', '155', 'id', '1', '1', '', '', '702', '0', '703', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('553', '155', 'id_field', '3', '', '', '', '704', '11', '705', '', '', '', '', '10', '', '');
insert into `aa_services_element` values ('554', '155', 'label', '2', '', '1', '', '706', '255', '707', '', '', '', '', '20', '', '1');
insert into `aa_services_element` values ('555', '155', 'value', '2', '', '1', '', '708', '255', '709', '', '', '', '', '30', '', '');
insert into `aa_services_element` values ('556', '155', 'selezionato', '9', '', '1', '', '710', '255', '711', '', '', '1', '', '40', '', '');
insert into `aa_services_element` values ('557', '155', 'ordine', '3', '', '1', '1', '712', '11', '713', '', '', '', '', '50', '', '');
insert into `aa_services_element` values ('558', '152', 'titolo', '2', '', '1', '', '752', '255', '753', '', '', '', '', '10', '', '1');
insert into `aa_services_element` values ('559', '136', 'active', '9', '', '1', '', '765', '255', '766', '', '', '1', '', '40', '', '');
insert into `aa_services_element` values ('560', '136', 'order', '3', '', '1', '1', '767', '11', '768', '', '', '', '', '50', '', '');
insert into `aa_services_element` values ('561', '116', 'metatitle', '2', '', '', '', '822', '75', '823', '', '', '', '', '90', '', '1');
insert into `aa_services_element` values ('562', '116', 'metadescription', '2', '', '', '', '824', '150', '825', '', '', '', '', '100', '', '1');
insert into `aa_services_element` values ('563', '116', 'metakeywords', '2', '', '', '', '826', '175', '827', '', '', '', '', '110', '', '1');
insert into `aa_services_element` values ('565', '127', 'visible', '24', '', '1', '', '881', '0', '882', '', 'SELECT id, lang FROM `aa_lang`', '', '', '50', '', '');
insert into `aa_services_element` values ('566', '156', 'id', '1', '1', '', '', '885', '0', '886', '', '', '', '', '0', '', '');
insert into `aa_services_element` values ('567', '156', 'classname', '13', '', '1', '', '887', '255', '888', '', '/admin/modules/aa/classes', '', '', '10', '', '');
insert into `aa_services_element` values ('568', '156', 'name', '2', '', '1', '', '889', '255', '890', '', '', '', '', '20', '', '');
insert into `aa_services_element` values ('569', '156', 'order', '3', '', '1', '', '891', '11', '892', '', '', '', '', '30', '', '');
insert into `aa_services_element` values ('570', '136', 'default', '9', '', '1', '', '894', '255', '895', '', '', '1', '', '60', '', '');
insert into `aa_services_element` values ('571', '116', 'slug', '29', '', '', '', '896', '1024', '897', '', '', '', '', '15', '', '1');
insert into `aa_services_element` values ('572', '148', 'last_update', '15', '', '', '', '926', '0', '927', '', '', '', '', '150', '', '');
insert into `aa_services_element` values ('573', '148', 'last_access', '30', '', '', '', '928', '0', '929', '', '', '', '', '160', '', '');
insert into `aa_services_element` values ('574', '148', 'last_ip', '2', '', '', '', '930', '255', '931', '', '', '', '', '170', '', '');
insert into `aa_services_element` values ('575', '148', 'new_password_key', '2', '', '', '', '932', '255', '933', '', '', '', '', '180', '', '');
insert into `aa_services_element` values ('576', '148', 'new_password_requested', '2', '', '', '', '934', '255', '935', '', '', '', '', '190', '', '');
insert into `aa_services_element` values ('577', '148', 'new_email', '2', '', '', '', '936', '255', '937', '', '', '', '', '200', '', '');
insert into `aa_services_element` values ('578', '148', 'new_email_key', '2', '', '', '', '938', '255', '939', '', '', '', '', '210', '', '');
insert into `aa_services_element` values ('579', '148', 'hashed_id', '2', '', '', '', '940', '255', '941', '', '', '', '', '220', '', '');


### structure of table `aa_template` ###

DROP TABLE IF EXISTS `aa_template`;

CREATE TABLE `aa_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `timestamp` datetime NOT NULL,
  `filename` varchar(255) NOT NULL DEFAULT '',
  `owner` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 AUTO_INCREMENT=15;


### data of table `aa_template` ###

insert into `aa_template` values ('4', 'standard template', '2004-04-28 16:27:54', 'default.tpl', '1');
insert into `aa_template` values ('10', 'Documents', '2010-03-24 15:51:19', 'documents.tpl', '1');
insert into `aa_template` values ('8', 'RSS', '2008-06-18 18:02:16', 'rss.tpl', '1');
insert into `aa_template` values ('9', 'Account', '2010-03-23 16:19:38', 'account.tpl', '1');
insert into `aa_template` values ('11', 'Gallery', '2010-03-24 16:22:14', 'gallery.tpl', '1');
insert into `aa_template` values ('12', 'news', '2010-12-21 16:13:44', 'news.tpl', '1');
insert into `aa_template` values ('13', 'form', '2010-12-21 16:13:51', 'form.tpl', '1');
insert into `aa_template` values ('14', 'index', '2013-12-11 14:33:08', 'index.tpl', '1');


### structure of table `aa_translation` ###

DROP TABLE IF EXISTS `aa_translation`;

CREATE TABLE `aa_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `it` text NOT NULL,
  `en` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=947 DEFAULT CHARSET=utf8 AUTO_INCREMENT=947;


### data of table `aa_translation` ###

insert into `aa_translation` values ('92', 'Home Page', 'Home Page');
insert into `aa_translation` values ('93', '<p>\r\n	<img alt=\"Syntax Desktop package\" src=\"/public/mat/image/syntax-box.gif\" style=\"border-width: 0px; border-style: solid; float: right; width: 170px; height: 221px;\" />Syntax Desktop è un sistema professionale di gestione dei contenuti Open Source. In pratica, è uno strumento che permette agli utenti di poter modificare il proprio sito come, dove e quando vogliono.</p>\r\n<p>\r\n	Syntax Desktop ti aiuta a creare e gestire complessi siti web senza conoscere l\'HTML. Un editor WYSIWYG integrato con un\'interfaccia utente simile a quella delle ben note applicazioni office aiuta l\'utente a creare i contenuti, mentre un template engine gestisce tutte le parti del sito per avere un controllo completo dell\'applicazione.</p>\r\n<p>\r\n	Syntax Desktop è basato su tecnologia PHP. E\' per questo motivo che funziona sulla gran parte delle moderne infrastrutture IT esistenti. Syntax Desktop gira su ambienti completamente \"open source\" (p.e. Linux, Apache, MySQL), ma funziona tranquillamente anche su componenti commerciali (p.e. Windows, IIS, Oracle DB, MS Access).</p>\r\n<p>\r\n	Inoltre una grande virtù di Syntax è la sua predisposizione ad essere indicizzato dai motori di ricerca. Syntax è stato infatti progettato nell\'ottica di generare pagine amiche dei motori di ricerca.</p>\r\n<p>\r\n	Per conoscere di più Syntax Desktop, visualizza il <a href=\"http://www.dynamick.it/syntax-desktop/tour.php\">tour guidato</a> che ti mostrerà le principali funzionalità dell\'applicazione. Per una\'approfondimento ulteriore, leggi il <a href=\"http://www.syntaxdesktop.com/docs\">manuale utente</a>.</p>\r\n', '<p>\r\n	<img alt=\"Syntax Desktop package\" src=\"/public/mat/image/syntax-box.gif\" style=\"border-width: 0px; border-style: solid; float: right; width: 170px; height: 221px;\" /></p>\r\n<p>\r\n	<strong>Syntax Desktop</strong> is a professional open source <strong>Content Management System</strong> (CMS).&nbsp; It is a tool that allows users to modify the web site contents how, when and where they want.<br />\r\n	<br />\r\n	Syntax Desktop helps you to create and manage large web sites <strong>without any knowledge</strong> of HTML. There\'s an integrated WYSIWYG editor with a friendly user interface similar to common office applications. You can create the contents and a template engine manages all the parts of the site allowing complete control of the application.<br />\r\n	<br />\r\n	Syntax Desktop is based on<strong> PHP technology</strong>. It is a web application, so it works on a wide range of modern existing IT infrastructures.&nbsp; Syntax Desktop runs with other \"open source\" technologies (i.e. Linux, Apache, MySQL), but it works also on commercial products (i.e. Windows, IIS, Oracle DB, MS Access).<br />\r\n	<br />\r\n	Moreover, a great virtue of Syntax is its predisposition to being indexed from <strong>search engines</strong>.&nbsp; Syntax is constructed to generate pages optimized for search engines.<br />\r\n	<br />\r\n	For more information about Syntax Desktop, you can watch the flash <a href=\"http://www.dynamick.it/syntax-desktop/tour.php\">guided tour</a>. You can find other information by reading the <a href=\"http://www.syntaxdesktop.com/docs/\">user manual</a>.</p>\r\n');
insert into `aa_translation` values ('851', '', '');
insert into `aa_translation` values ('852', '', '');
insert into `aa_translation` values ('94', 'Chi Siamo', 'About us');
insert into `aa_translation` values ('95', '<P>Smarty allows access to PHP objects through the templates. There are two ways to access them. One way is to register objects to the template, then use access them via syntax similar to custom functions. The other way is to assign objects to the templates and access them much like any other assigned variable. The first method has a much nicer template syntax. It is also more secure, as a registered object can be restricted to certain methods or properties. However, a registered object cannot be looped over or assigned in arrays of objects, etc. The method you choose will be determined by your needs, but use the first method whenever possible to keep template syntax to a minimum. </P>\r\n<P>If security is enabled, no private methods or functions can be accessed (begininning with \"_\"). If a method and property of the same name exist, the method will be used. </P>\r\n<P>You can restrict the methods and properties that can be accessed by listing them in an array as the third registration parameter. </P>\r\n<P>By default, parameters passed to objects through the templates are passed the same way custom functions get them. An associative array is passed as the first parameter, and the smarty object as the second. If you want the parameters passed one at a time for each argument like traditional object parameter passing, set the fourth registration parameter to false. </P>\r\n<P>The optional fifth parameter has only effect with <TT class=parameter><I>format</I></TT> being <TT class=literal>true</TT> and contains a list ob methods that should be treated as blocks. That means these methods have a closing tag in the template (<TT class=literal>{foobar-&gt;meth2}...{/foobar-&gt;meth2}</TT>) and the parameters to the methods have the same synopsis as the parameters for block-function-plugins: They get 4 parameters <TT class=parameter><I>$params</I></TT>, <TT class=parameter><I>$content</I></TT>, <TT class=parameter><I>&amp;$smarty</I></TT> and <TT class=parameter><I>&amp;$repeat</I></TT> and they also behave like block-function-plugin</P>', '<P>Smarty allows access to PHP objects through the templates. There are two ways to access them. One way is to register objects to the template, then use access them via syntax similar to custom functions. The other way is to assign objects to the templates and access them much like any other assigned variable. The first method has a much nicer template syntax. It is also more secure, as a registered object can be restricted to certain methods or properties. However, a registered object cannot be looped over or assigned in arrays of objects, etc. The method you choose will be determined by your needs, but use the first method whenever possible to keep template syntax to a minimum. </P>\r\n<P>If security is enabled, no private methods or functions can be accessed (begininning with \"_\"). If a method and property of the same name exist, the method will be used. </P>\r\n<P>You can restrict the methods and properties that can be accessed by listing them in an array as the third registration parameter. </P>\r\n<P>By default, parameters passed to objects through the templates are passed the same way custom functions get them. An associative array is passed as the first parameter, and the smarty object as the second. If you want the parameters passed one at a time for each argument like traditional object parameter passing, set the fourth registration parameter to false. </P>\r\n<P>The optional fifth parameter has only effect with <TT class=parameter><I>format</I></TT> being <TT class=literal>true</TT> and contains a list ob methods that should be treated as blocks. That means these methods have a closing tag in the template (<TT class=literal>{foobar-&gt;meth2}...{/foobar-&gt;meth2}</TT>) and the parameters to the methods have the same synopsis as the parameters for block-function-plugins: They get 4 parameters <TT class=parameter><I>$params</I></TT>, <TT class=parameter><I>$content</I></TT>, <TT class=parameter><I>&amp;$smarty</I></TT> and <TT class=parameter><I>&amp;$repeat</I></TT> and they also behave like block-function-plugin</P>');
insert into `aa_translation` values ('96', 'Contatti', 'Contacts');
insert into `aa_translation` values ('97', 'Questo template risiede su db', '<P>This template resides on db...</P>');
insert into `aa_translation` values ('98', 'Dove Siamo', 'Company Address');
insert into `aa_translation` values ('99', 'Company Address', '<P>Where we are?</P>');
insert into `aa_translation` values ('100', 'News', 'News');
insert into `aa_translation` values ('102', 'Prodotti', 'Products');
insert into `aa_translation` values ('106', 'Gestione Contenuti', 'Content Management');
insert into `aa_translation` values ('107', 'Sezioni del sito', 'Site section');
insert into `aa_translation` values ('108', 'Pagine', 'Pages');
insert into `aa_translation` values ('109', 'Template', 'Template');
insert into `aa_translation` values ('111', 'Amministrazione', 'Administration');
insert into `aa_translation` values ('112', 'Utenti backend', 'Backend users');
insert into `aa_translation` values ('113', 'Gruppi/Menu', 'Groups/Menu');
insert into `aa_translation` values ('114', 'Servizi', 'Service');
insert into `aa_translation` values ('115', 'Lingue', 'Language');
insert into `aa_translation` values ('116', 'Traduzioni', 'Translations');
insert into `aa_translation` values ('117', 'Strumenti', 'Tools');
insert into `aa_translation` values ('118', 'Anteprima sito', 'View site');
insert into `aa_translation` values ('119', 'Scrivi email', 'Email admin');
insert into `aa_translation` values ('120', 'Shell', 'Shell');
insert into `aa_translation` values ('121', 'Database Log', 'DB Log');
insert into `aa_translation` values ('122', 'Ripristina Database', 'Restore Database');
insert into `aa_translation` values ('123', 'Backup Manager', 'Database Manager');
insert into `aa_translation` values ('124', 'Impostazioni', 'Preferences');
insert into `aa_translation` values ('125', 'Sfondi', 'Wallpaper');
insert into `aa_translation` values ('126', 'Default', 'Default');
insert into `aa_translation` values ('127', 'Blue', 'Blue');
insert into `aa_translation` values ('128', 'Ravazon', 'Ravazon');
insert into `aa_translation` values ('129', 'Notte', 'Night');
insert into `aa_translation` values ('130', 'Deleterix', 'Deleterix');
insert into `aa_translation` values ('131', 'Esci', 'Logout');
insert into `aa_translation` values ('132', 'Chiudi', 'Close');
insert into `aa_translation` values ('133', '?', '?');
insert into `aa_translation` values ('134', 'Manuale', 'Manual (only in Italian)');
insert into `aa_translation` values ('135', 'Informazioni su', 'About');
insert into `aa_translation` values ('136', '', 'Service');
insert into `aa_translation` values ('137', '', 'Service');
insert into `aa_translation` values ('138', 'Servizi', 'Service');
insert into `aa_translation` values ('139', 'Servizi disponibili', 'Service management');
insert into `aa_translation` values ('140', 'Lingue', 'Language');
insert into `aa_translation` values ('141', 'Elenco delle lingue disponibili', 'User language');
insert into `aa_translation` values ('142', 'Traduzioni', 'Translations');
insert into `aa_translation` values ('143', 'Elenco di tutte le traduzioni', 'contains the traslation string of everything in this db');
insert into `aa_translation` values ('144', 'Utenti di sistema', 'System users');
insert into `aa_translation` values ('145', 'Lista degli utenti di sistema', 'System users list');
insert into `aa_translation` values ('146', 'Gruppi di sistema', 'System Group');
insert into `aa_translation` values ('147', 'Lista dei gruppi di utenti di sistema', 'System users group management');
insert into `aa_translation` values ('148', 'Service-o-matic', 'Service-o-matic');
insert into `aa_translation` values ('149', 'Assistente per la creazione dei servizi', 'Wizard for service creation');
insert into `aa_translation` values ('150', 'Joins', 'Joins');
insert into `aa_translation` values ('151', 'Joins (collegamenti) tra servizi', 'Service joins');
insert into `aa_translation` values ('152', 'Menu', 'Menu');
insert into `aa_translation` values ('153', 'Menu relativi ai gruppi', 'Groups menu');
insert into `aa_translation` values ('154', 'Pagine', 'Pages');
insert into `aa_translation` values ('155', 'Gerarchia delle pagine del sito', 'Page hierarchy');
insert into `aa_translation` values ('156', 'Template', 'Template');
insert into `aa_translation` values ('157', 'gestione dei template', 'Template management');
insert into `aa_translation` values ('158', 'News', 'News');
insert into `aa_translation` values ('159', 'News Management', 'News Management');
insert into `aa_translation` values ('166', 'Id', 'Id');
insert into `aa_translation` values ('167', '', '');
insert into `aa_translation` values ('168', 'Servizio', 'Container');
insert into `aa_translation` values ('169', 'Scegli il servizio di appartenenza', '');
insert into `aa_translation` values ('170', 'Nome', 'Name');
insert into `aa_translation` values ('171', '', '');
insert into `aa_translation` values ('172', 'Tipo', 'Type');
insert into `aa_translation` values ('173', '', '');
insert into `aa_translation` values ('174', 'Chiave', 'Iskey');
insert into `aa_translation` values ('175', '', '');
insert into `aa_translation` values ('176', 'Visibile', 'Isvisible');
insert into `aa_translation` values ('177', '', '');
insert into `aa_translation` values ('178', 'Editabile', 'Iseditable');
insert into `aa_translation` values ('179', '', '');
insert into `aa_translation` values ('180', 'Multilingua', 'Ismultilang');
insert into `aa_translation` values ('181', '', '');
insert into `aa_translation` values ('182', 'Etichetta', 'Label');
insert into `aa_translation` values ('183', '', '');
insert into `aa_translation` values ('184', 'Dimensione', 'Size');
insert into `aa_translation` values ('185', '', '');
insert into `aa_translation` values ('186', 'Aiuto', 'Help');
insert into `aa_translation` values ('187', '', '');
insert into `aa_translation` values ('188', 'Path', 'Path');
insert into `aa_translation` values ('189', '', '');
insert into `aa_translation` values ('190', 'Qry', 'Qry');
insert into `aa_translation` values ('191', '', '');
insert into `aa_translation` values ('192', 'Valore', 'Value');
insert into `aa_translation` values ('193', '', '');
insert into `aa_translation` values ('194', 'Joins', 'Joins');
insert into `aa_translation` values ('195', '', '');
insert into `aa_translation` values ('196', 'Posizione', 'Position');
insert into `aa_translation` values ('197', '', '');
insert into `aa_translation` values ('198', 'Filtro', 'Filter');
insert into `aa_translation` values ('199', '', '');
insert into `aa_translation` values ('200', 'Id', 'Id');
insert into `aa_translation` values ('201', '', '');
insert into `aa_translation` values ('202', 'Titolo', 'Title');
insert into `aa_translation` values ('203', 'il titolo della news', 'not multilang');
insert into `aa_translation` values ('204', 'Foto', 'Photo');
insert into `aa_translation` values ('205', 'inserisci la foto', 'insert your photo here');
insert into `aa_translation` values ('206', 'Icona', 'Icon');
insert into `aa_translation` values ('207', 'Seleziona l\'icona', 'Select the icon from the list');
insert into `aa_translation` values ('208', 'Id', 'Id');
insert into `aa_translation` values ('209', '', '');
insert into `aa_translation` values ('210', 'Lingua', 'Language');
insert into `aa_translation` values ('211', 'Inserisci il nome della lingua per esteso', 'Insert the full name of language');
insert into `aa_translation` values ('212', 'Iniziali', 'Initial');
insert into `aa_translation` values ('213', '2 lettere', 'insert 2 chars');
insert into `aa_translation` values ('214', 'Bandiera', 'Flag');
insert into `aa_translation` values ('215', '', '');
insert into `aa_translation` values ('216', 'Id', 'Id');
insert into `aa_translation` values ('217', '', '');
insert into `aa_translation` values ('218', 'It', 'It');
insert into `aa_translation` values ('219', '', '');
insert into `aa_translation` values ('220', 'En', 'En');
insert into `aa_translation` values ('221', '', '');
insert into `aa_translation` values ('222', 'Id', 'Id');
insert into `aa_translation` values ('223', '', '');
insert into `aa_translation` values ('224', 'Login', 'Login');
insert into `aa_translation` values ('225', '', '');
insert into `aa_translation` values ('226', 'Passwd', 'Passwd');
insert into `aa_translation` values ('227', '', '');
insert into `aa_translation` values ('228', 'Gruppo', 'Gruppo');
insert into `aa_translation` values ('229', 'Scegli il gruppo di appartenza', 'Scegli il gruppo di appartenza');
insert into `aa_translation` values ('230', 'Tema dell\'interfaccia', 'Interface theme');
insert into `aa_translation` values ('231', '[funzione disabilitata]', '[disabled function]');
insert into `aa_translation` values ('232', 'Lingua', 'Lingua');
insert into `aa_translation` values ('233', 'Scegli la lingua', 'Scegli la lingua');
insert into `aa_translation` values ('234', 'Padre', 'Parent');
insert into `aa_translation` values ('235', 'Scegli l\'utente responsabile', 'parent user');
insert into `aa_translation` values ('236', 'Id', 'Id');
insert into `aa_translation` values ('237', '', '');
insert into `aa_translation` values ('238', 'Nome', 'Name');
insert into `aa_translation` values ('239', '', '');
insert into `aa_translation` values ('240', 'Id', 'Id');
insert into `aa_translation` values ('241', '', '');
insert into `aa_translation` values ('242', 'Nome', 'Name');
insert into `aa_translation` values ('243', '', '');
insert into `aa_translation` values ('244', 'Path', 'Path');
insert into `aa_translation` values ('245', '', '');
insert into `aa_translation` values ('246', 'Icona', 'Icon');
insert into `aa_translation` values ('247', 'Scegli l\'icona per il menu', 'Choose the menu icon');
insert into `aa_translation` values ('248', 'Descrizione', 'Description');
insert into `aa_translation` values ('249', '', '');
insert into `aa_translation` values ('250', 'Padre', 'Parent');
insert into `aa_translation` values ('251', '', '');
insert into `aa_translation` values ('252', 'Syntable', 'Syntable');
insert into `aa_translation` values ('253', '', '');
insert into `aa_translation` values ('254', 'Ordinamento', 'Order');
insert into `aa_translation` values ('255', 'Scegli il campo che detterà l\'ordine', '');
insert into `aa_translation` values ('256', 'Dbsync', 'Dbsync');
insert into `aa_translation` values ('257', 'Crea/aggiorna le tabelle sul database?', 'Create/update database table?');
insert into `aa_translation` values ('258', 'Multilingua', 'Multilang');
insert into `aa_translation` values ('259', 'Questo servizio è multilingua?', 'Is a multilang service? This field is auto calculated by service-o-matic');
insert into `aa_translation` values ('260', 'Posizione', 'InitOrder');
insert into `aa_translation` values ('261', 'Posizione dell\'elemento rispetto agli altri', '');
insert into `aa_translation` values ('262', 'Id', 'Id');
insert into `aa_translation` values ('263', '', '');
insert into `aa_translation` values ('264', 'Titolo', 'Title');
insert into `aa_translation` values ('265', 'Inserisci il titolo del collegamento', 'join title');
insert into `aa_translation` values ('266', 'da', 'From');
insert into `aa_translation` values ('267', 'Scegli il campo origine', 'Origin field');
insert into `aa_translation` values ('268', 'A', 'To');
insert into `aa_translation` values ('269', 'Scegli il campo destinazione', 'Destination field');
insert into `aa_translation` values ('270', 'Description', 'Descrizione');
insert into `aa_translation` values ('271', 'join description', 'Eventuali commenti');
insert into `aa_translation` values ('272', 'Servizio', 'Service');
insert into `aa_translation` values ('273', 'servizio di riferimento', 'reference service');
insert into `aa_translation` values ('274', 'Id', 'Id');
insert into `aa_translation` values ('275', '', '');
insert into `aa_translation` values ('276', 'Nome', 'Name');
insert into `aa_translation` values ('277', '', '');
insert into `aa_translation` values ('278', 'Servizio', 'Service');
insert into `aa_translation` values ('279', 'Lasciare vuoto per creare una cartella', 'Keep blank to create a directory');
insert into `aa_translation` values ('280', 'Link', 'Link');
insert into `aa_translation` values ('281', 'Collegamento diretto ad una pagina', 'URL to a page');
insert into `aa_translation` values ('282', 'Posizione', 'Order');
insert into `aa_translation` values ('283', '', '');
insert into `aa_translation` values ('284', 'Sql filter', 'Sql filteer');
insert into `aa_translation` values ('285', '', '');
insert into `aa_translation` values ('286', 'Gruppo', 'Group');
insert into `aa_translation` values ('287', '', '');
insert into `aa_translation` values ('288', 'Parent', 'Parent');
insert into `aa_translation` values ('289', '', '');
insert into `aa_translation` values ('290', 'Ins', 'Insert');
insert into `aa_translation` values ('291', 'Permette l\'inserimento di nuovi record', 'Permit add new records');
insert into `aa_translation` values ('292', 'Mod', 'Modify');
insert into `aa_translation` values ('293', 'Permette la modifica dei record', 'Permit modify records');
insert into `aa_translation` values ('294', 'Canc', 'Delete');
insert into `aa_translation` values ('295', 'Permette la cancellazione dei record', 'Permit delete records');
insert into `aa_translation` values ('296', 'Icona', 'Icon');
insert into `aa_translation` values ('297', 'Icona per il menu', 'Icon');
insert into `aa_translation` values ('298', 'Id', 'Id');
insert into `aa_translation` values ('299', '', '');
insert into `aa_translation` values ('300', 'Titolo', 'Title');
insert into `aa_translation` values ('301', 'Inserire il titolo della pagina', '');
insert into `aa_translation` values ('302', 'Testo', 'Text');
insert into `aa_translation` values ('303', 'Testo visualizzato sulla pagina', 'The page\'s text');
insert into `aa_translation` values ('304', 'Appartenenza', 'Parent');
insert into `aa_translation` values ('305', 'Scegli la sezione a cui appartiene questa pagina', '');
insert into `aa_translation` values ('306', 'Template', 'Template');
insert into `aa_translation` values ('307', 'Scegli il template grafico della pagina', 'Choose the template for this page');
insert into `aa_translation` values ('308', 'Visibile', 'Visible');
insert into `aa_translation` values ('309', 'Spunta per rendere visibile la pagina', 'Check to make visible this page');
insert into `aa_translation` values ('310', 'Posizione', 'Order');
insert into `aa_translation` values ('311', 'Inserisci la posizione che deve avere la pagina tra le altre della stessa sezione', 'Order between page on the same level');
insert into `aa_translation` values ('312', 'Gruppo', 'Group');
insert into `aa_translation` values ('313', 'Assegna il gruppo di utenti che hanno diritto di modificare questa pagina', 'Set the user\'s group allowed to edit this page');
insert into `aa_translation` values ('314', 'Id', 'Id');
insert into `aa_translation` values ('315', '', '');
insert into `aa_translation` values ('316', 'Titolo', 'Title');
insert into `aa_translation` values ('317', '', '');
insert into `aa_translation` values ('318', 'Timestamp', 'Timestamp');
insert into `aa_translation` values ('319', '', '');
insert into `aa_translation` values ('320', 'Nome del file', 'Filename');
insert into `aa_translation` values ('321', 'Scegli un template da file', 'Choose an already done template');
insert into `aa_translation` values ('322', 'Template', 'Template');
insert into `aa_translation` values ('323', 'Se non ', 'If you doNt choose a template, you can create a template from the textarea below');
insert into `aa_translation` values ('342', 'Title', 'Title');
insert into `aa_translation` values ('343', '', '');
insert into `aa_translation` values ('344', 'Text', 'Text');
insert into `aa_translation` values ('345', '', '');
insert into `aa_translation` values ('346', 'Image', 'Image');
insert into `aa_translation` values ('347', '', '');
insert into `aa_translation` values ('348', 'Benvenuto in Syntax Desktop!', 'Welcome in Syntax!');
insert into `aa_translation` values ('349', '<p>\r\n  Questo è il testo della prima news!</p>\r\n<p>\r\n  Lorem ipsum dolor sit amet consectetuer neque a elit dui suscipit. Vestibulum Sed risus pretium orci Pellentesque nunc montes ut leo mauris. Habitant Pellentesque felis cursus interdum non Maecenas pede semper Ut In. Volutpat nunc Curabitur condimentum et interdum hendrerit dictum elit eu habitasse. Quis netus sit commodo mus consectetuer a at tellus urna justo. Condimentum.<br />\r\n <br />\r\n  Justo Nam et Vivamus Mauris tristique felis a adipiscing eu Fusce. Fringilla ac ipsum neque Curabitur condimentum elit morbi malesuada Sed urna. Pretium faucibus sit Sed auctor magna pellentesque fringilla Praesent dolor convallis. Tincidunt venenatis fringilla justo In amet tellus auctor penatibus Suspendisse Mauris. Tellus metus Vivamus id ac Phasellus tellus Morbi Suspendisse Aliquam orci. Laoreet laoreet justo mus.</p>\r\n', '<p>This is the first news.</p>\r\n<p>Lorem ipsum dolor sit amet consectetuer neque a elit dui suscipit. Vestibulum Sed risus pretium orci Pellentesque nunc montes ut leo mauris. Habitant Pellentesque felis cursus interdum non Maecenas pede semper Ut In. Volutpat nunc Curabitur condimentum et interdum hendrerit dictum elit eu habitasse. Quis netus sit commodo mus consectetuer a at tellus urna justo. Condimentum.<br />\r\n<br />\r\nJusto Nam et Vivamus Mauris tristique felis a adipiscing eu Fusce. Fringilla ac ipsum neque Curabitur condimentum elit morbi malesuada Sed urna. Pretium faucibus sit Sed auctor magna pellentesque fringilla Praesent dolor convallis. Tincidunt venenatis fringilla justo In amet tellus auctor penatibus Suspendisse Mauris. Tellus metus Vivamus id ac Phasellus tellus Morbi Suspendisse Aliquam orci. Laoreet laoreet justo mus.</p>');
insert into `aa_translation` values ('350', 'Date', 'Date');
insert into `aa_translation` values ('351', '', '');
insert into `aa_translation` values ('352', 'Album', 'Album');
insert into `aa_translation` values ('353', 'List of photo albums', 'List of photo albums');
insert into `aa_translation` values ('354', 'Id', 'Id');
insert into `aa_translation` values ('355', '', '');
insert into `aa_translation` values ('356', 'Title', 'Title');
insert into `aa_translation` values ('357', '', '');
insert into `aa_translation` values ('358', 'Description', 'Description');
insert into `aa_translation` values ('359', '', '');
insert into `aa_translation` values ('360', 'Date', 'Date');
insert into `aa_translation` values ('361', '', '');
insert into `aa_translation` values ('362', 'Album', 'Album');
insert into `aa_translation` values ('363', 'Lista degli album fotografici', 'List of photo albums');
insert into `aa_translation` values ('364', 'Id', 'Id');
insert into `aa_translation` values ('365', '', '');
insert into `aa_translation` values ('366', 'Titolo', 'Title');
insert into `aa_translation` values ('367', '', '');
insert into `aa_translation` values ('368', 'Descrizione', 'Description');
insert into `aa_translation` values ('369', '', '');
insert into `aa_translation` values ('370', 'Data', 'Date');
insert into `aa_translation` values ('371', '', '');
insert into `aa_translation` values ('372', 'Tour Guidati', 'Guided Tour');
insert into `aa_translation` values ('373', 'Intefaccia utente', 'User Interface');
insert into `aa_translation` values ('374', 'Servizi', 'Services');
insert into `aa_translation` values ('375', 'Servizi', 'Servizi');
insert into `aa_translation` values ('376', '', '');
insert into `aa_translation` values ('378', 'Album', 'Album');
insert into `aa_translation` values ('379', 'prova', 'prova');
insert into `aa_translation` values ('380', '', '');
insert into `aa_translation` values ('381', 'Id', 'Id');
insert into `aa_translation` values ('382', '', '');
insert into `aa_translation` values ('383', 'Titolo', 'Titolo');
insert into `aa_translation` values ('384', '', '');
insert into `aa_translation` values ('388', '', '');
insert into `aa_translation` values ('389', 'Id', 'Id');
insert into `aa_translation` values ('390', '', '');
insert into `aa_translation` values ('391', 'Titolo', 'Titolo');
insert into `aa_translation` values ('392', '', '');
insert into `aa_translation` values ('393', 'prova2', 'prova');
insert into `aa_translation` values ('394', 'Supporto alle Foreign Keys nella Versione 2', 'Version 2 support Foreign Keys');
insert into `aa_translation` values ('395', '<p>\r\n  Se hai innoDB, Syntax Desktop userà le foreign keys. Questa caratteristica mantiene le tabelle del database pulite e logicamente coerenti.</p>\r\n', 'If you have innoDB, Syntax Desktop will use the foreign keys. This feature will keep your database tables clean and logically coerent.');
insert into `aa_translation` values ('396', 'Il primo concorso italiano open source', 'The first italian open source contest');
insert into `aa_translation` values ('397', '<p>\r\n  Syntax Desktop è stato ammesso alla seconda fase del concorso italiano Open Source Contest. La sfida si concluderà il 31 dicembre 2004.</p>\r\n', 'Syntax Desktop is admitted to the second phase of the italian contest \"Open Source Contest\". The competition will finish on December, 31 (2004).\r\n');
insert into `aa_translation` values ('398', 'Intro', 'Brief Introduction');
insert into `aa_translation` values ('399', '<p>\r\n	<strong>Introduzione</strong></p>\r\n<p>\r\n	Syntax Desktop è un sistema di gestione dei contenuti semplice e flessibile. Nato come strumento di aiuto per il lavoro, ora è un pacchetto pubblico usato&nbsp;in numerosi siti.</p>\r\n<p>\r\n	Il nome ha un\'origine bizzarra, come capita spesso in questi casi. Syntax deriva da una coincidenza di 3 fatti:</p>\r\n<ul>\r\n	<li>\r\n		<strong>Syntax</strong> sarebbe stato il nome dell\'azienda, mai nata, che doveva sorgere dalle macerie della webagency&nbsp;presso cui lavoravo</li>\r\n	<li>\r\n		<strong>Syntax supervisor</strong> è la carica che mi è stata data in un esame universitario&nbsp;di gruppo in cui avevo partecipato diciamo così, <em>marginalmente</em>... da allora i miei colleghi d\'università mi hanno chiamato così!</li>\r\n	<li>\r\n		Il nome Syntax mi piace, finisce con la <strong>X</strong>, molto di moda in questi tempi (vd. windows xp, dreamweaver mx, Mac OsX, ecc...)</li>\r\n</ul>\r\n<p>\r\n	Ovviamente poi, essendo l\'interfaccia simile ad una scrivania virtuale, il nome finale è risultato: \"Syntax Desktop\".</p>\r\n<p>\r\n	Il lavoro nasce da un\'idea di un mio ex-collega, <strong>Dimitri Giardina</strong>, che mi ha insegnato inizialmente l\'uso di PHP. Ovviamente del vecchio progetto non esiste più nulla, ma una citazione ritengo sia giusto averla fatta.</p>\r\n<p>\r\n	Il progetto vuole essere un sistema completo per la gestione di siti web. E\' per questo motivo che alcune funzioni sono state implementate, altre invece sono lasciate all\'utente finale. Syntax contiene infatti un <strong>motore di generazione di pagine</strong> di amministrazione che permette di gestire qualsiasi tipo di dato strutturato.</p>\r\n', '<p>\r\n	<strong>Introduction</strong></p>\r\n<p>\r\n	Syntax Desktop is a Content Management System simple and flexible. It was born as a support tool in my office work, but now became a very used software in many web sites.</p>\r\n<p>\r\n	The name born from three ideas:</p>\r\n<ul>\r\n	<li>\r\n		Syntax&nbsp;should be the name of a company never created</li>\r\n	<li>\r\n		Some of my friend call me Syntax Supervisor, because of a university exams project, where I do very little</li>\r\n	<li>\r\n		I like the name \"syntax\" beacuse it ends with an X, very cool in this period (i.e. windows xp, dreamweaver mx, Mac OsX, etc...)</li>\r\n</ul>\r\n<p>\r\n	Obviously,&nbsp;the&nbsp;name&nbsp;was extended with a \"desktop\" because the&nbsp;is similar to a virtual writing desk.</p>\r\n<p>\r\n	The&nbsp;project&nbsp;was born from an idea of&nbsp;my former-connects, <strong>Dimitri Giardina </strong>,&nbsp;the person who&nbsp;initially taught the use of PHP.&nbsp;</p>\r\n<p>\r\n	Syntax Desktop&nbsp;wants to be a complete system for the web content manegement.&nbsp;For this reason&nbsp;some functions have been implemented, others instead are left the final customer. Syntax contains in fact&nbsp;an<strong>&nbsp;administration&nbsp;pages builder engine</strong> that allow to manage whichever type of structured data.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	<font color=\"#ff0000\">HELP ME!<br />\r\n	If you want to help me, send the right english translation of this page at </font><a href=\"mailto:info@dynamick.it\"><font color=\"#ff0000\">info@dynamick.it</font></a><font color=\"#ff0000\"> .<br />\r\n	Thank you!</font></p>\r\n');
insert into `aa_translation` values ('400', 'Installazione', 'Installation');
insert into `aa_translation` values ('401', '<p>\r\n	<strong>Configurazione di php.ini</strong></p>\r\n<p>\r\n	Assicuratevi di avere questi parametri settati nel file php.ini:</p>\r\n<p>\r\n	&nbsp;</p>\r\n<ul>\r\n	<li>\r\n		error_reporting = E_ALL &amp; ~E_NOTICE&nbsp;</li>\r\n	<li>\r\n		short_open_tag = On</li>\r\n	<li>\r\n		register_globals = On&nbsp;</li>\r\n	<li>\r\n		allow_call_time_pass_reference = On</li>\r\n</ul>\r\n<h4>\r\n	<strong>Permessi sui file</strong></h4>\r\n<p>\r\n	Dopo aver copiato Syntax Deskto nella root del vostro Server, verificate di avere i permessi di scrittura (777) sulle seguenti cartelle:</p>\r\n<ul>\r\n	<li>\r\n		/admin/config/</li>\r\n	<li>\r\n		/admin/modules/phpMyBackupPro/export/</li>\r\n	<li>\r\n		/public/mat/</li>\r\n	<li>\r\n		/public/templates_c/</li>\r\n</ul>\r\n<h4>\r\n	<strong>Installazione</strong></h4>\r\n<ol>\r\n	<li>\r\n		Occorre prima di tutto creare un database mysql, possibilmente di tipo InnoDB, in modo da poter gestire le Foreign keys;</li>\r\n	<li>\r\n		Navigate in <u>www.miosito/admin/setup.php</u> e seguite le istruzioni. Se tutti i parametri sono corretti, Syntax creerà tutte le tabelle e i file di configurazione necessari.</li>\r\n	<li>\r\n		Syntax Desktop è pronto per essere usato! Si raccomanda di eliminare setup.php e rimuovere i permessi di scrittura su /admin/config/.</li>\r\n</ol>\r\n', '<p>\r\n	<strong>Installation</strong></p>\r\n<p>\r\n	The installation simply require&nbsp;adjusting the /syntax desktop/config/cfg.php file</p>\r\n<p>\r\n	<strong>Configuring database&nbsp;</strong><br />\r\n	It is necessary first to create a&nbsp;mysql database (possibly&nbsp;an InnoDB). After that, you have to adjust these few lines in the cfg.php:</p>\r\n<p>\r\n	<font color=\"#006600\">//ACCOUNT </font><br />\r\n	$synDbHost=\"localhost\";<br />\r\n	$synDbUser=\"root\";<br />\r\n	$synDbPassword=\"\";<br />\r\n	$synDbName=\"syntax\";</p>\r\n<p>\r\n	<strong>Other configuration values</strong><br />\r\n	You have to configure other parameters:</p>\r\n<p>\r\n	<font color=\"#006600\">//Upload image directory<br />\r\n	//YOU MUST PUT TRAILING SLASH;<br />\r\n	//the initial relative path is syntax desktop installation dir<br />\r\n	//(i.e. relative path=\"/syntax desktop/\" $mat=\"../mat\" ---&gt; result \"/syntax desktop/\"+\"../mat/\")</font><br />\r\n	$mat=\"../mat/\";<br />\r\n	$thumb=\"../mat/thumb/\";</p>\r\n<p>\r\n	<font color=\"#006600\">//admin email</font><br />\r\n	$synAdministrator=\"info@dynamick.it\";</p>\r\n<p>\r\n	<font color=\"#006600\">//site address \"http://www.dynamick.it\"</font><br />\r\n	$synWebsite=\"/\";</p>\r\n<p>\r\n	<font color=\"#006600\">//rows per page</font><br />\r\n	$synRowsPerPage=17;</p>\r\n<p>\r\n	<font color=\"#006600\">//version</font><br />\r\n	$synVersion=\"2 Beta\";</p>\r\n<p>\r\n	<strong>php.ini parameters</strong><br />\r\n	You have to&nbsp;verify to have these values in your php.ini<br />\r\n	<br />\r\n	error_reporting = E_ALL &amp; ~E_NOTICE&nbsp;<br />\r\n	register_globals = On&nbsp;<br />\r\n	allow_call_time_pass_reference = On&nbsp;</p>\r\n<p>\r\n	<strong>Changing write permission</strong><br />\r\n	Give the write rights to these files:<br />\r\n	/syntax desktop/config/cfg.php<br />\r\n	/syntax desktop/public/configs/files.txt<br />\r\n	/syntax desktop/includes/php/smarty/templates_c<br />\r\n	/syntax desktop/modules/dump/backup/</p>\r\n<p>\r\n	<strong>Run Syntax Desktop!</strong><br />\r\n	You can now launch Syntax Desktop in your browser.&nbsp;Syntax will ask you to choose the dump to load on yours database. Otherwise it will ask you to reshape the parameters of database connection.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	<font color=\"#ff0000\">HELP ME!<br />\r\n	If you want to help me, send the right english translation of this page at </font><a href=\"mailto:info@dynamick.it\"><font color=\"#ff0000\">info@dynamick.it</font></a><font color=\"#ff0000\"> .<br />\r\n	Thank you!</font></p>\r\n');
insert into `aa_translation` values ('402', 'Personalizzazioni', 'Customization');
insert into `aa_translation` values ('403', '<p>\r\n	<strong>Personalizzazioni</strong></p>\r\n<p>\r\n	Per poter adattare syntax al proprio sito, si devono avere conoscenze, se pur minime,&nbsp;di html e php. La cartella public all\'interno di Syntax contiene tutte le informazioni che dovete modificare per poter personalizzare il vostro sito.</p>\r\n<p>\r\n	La gestione dei template è affidata al sistema <a href=\"http://smarty.php.net/\">smarty</a>. Quindi per poter costruire il proprio template occorre avere conoscenze di questo intuitivo sistema. Trovate documentazione presso il sito ufficiale di smarty: <a href=\"http://smarty.php.net/\">http://smarty.php.net/</a></p>\r\n<p>\r\n	Nello specifico, la cartella public/ contiene:</p>\r\n<ul>\r\n	<li>\r\n		configs - attualmente in costruzione</li>\r\n	<li>\r\n		css - cartella preposta a contenere i fogli di stile del sito</li>\r\n	<li>\r\n		img - la cartella delle immagini</li>\r\n	<li>\r\n		plugin - questa cartella contiene tutti i plugin che rendono dinamico il sito. Sono plugin di <a href=\"http://smarty.php.net/\">smarty</a>.</li>\r\n	<li>\r\n		templates - i file che contengono i template sono localizzati in questa cartella</li>\r\n</ul>\r\n<p>\r\n	Queste sono le cartelle di default, ma nulla vieta di crearne di nuove.</p>\r\n', '<p>\r\n	Now you have to customize your Syntax Desktop to adapt your requirements. It is necessary some HTML and PHP basis to administrate completely this cms.</p>\r\n<p>\r\n	The \"public\" folder&nbsp;inside syntax path contains all the information&nbsp;you&nbsp;can modify for create your new site.</p>\r\n<p>\r\n	The&nbsp;template system is based&nbsp;on&nbsp;<a href=\"http://216.239.39.104/translate_c?hl=en&amp;u=http://smarty.php.net/\"><font color=\"#000000\">smarty </font></a>.&nbsp;You can&nbsp;find some documentation&nbsp;at the official smarty web site: <a href=\"http://216.239.39.104/translate_c?hl=en&amp;u=http://smarty.php.net/\"><font color=\"#000000\">http://smarty.php.net/</font></a></p>\r\n<p>\r\n	The&nbsp; \"public\" folder contains this directories:</p>\r\n<ul>\r\n	<li>\r\n		configs - currently under construction</li>\r\n	<li>\r\n		css - contains the cascading style sheets&nbsp;of your site&nbsp;</li>\r\n	<li>\r\n		img - this folder&nbsp;contains the&nbsp;images</li>\r\n	<li>\r\n		plugin - this folder contains all the smarty plugins.</li>\r\n	<li>\r\n		templates - this folder contains the templates of your website</li>\r\n	<li>\r\n		mat - contains the uploaded document</li>\r\n	<li>\r\n		backup - mysql dump repository</li>\r\n</ul>\r\n<p>\r\n	These are some default directories&nbsp;but you to create new ones.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	<font color=\"#ff0000\">HELP ME!<br />\r\n	If you want to help me, send the right english translation of this page at </font><a href=\"mailto:info@dynamick.it\"><font color=\"#ff0000\">info@dynamick.it</font></a><font color=\"#ff0000\"> .<br />\r\n	Thank you!</font></p>\r\n');
insert into `aa_translation` values ('404', 'Contatti', 'Contacts');
insert into `aa_translation` values ('405', '<p>\r\n	Per problemi o suggerimenti, scrivete sul forum presente su <a href=\"http://sourceforge.net/forum/?group_id=107986\" target=\"_blank\">sourceforge</a>.</p>\r\n<p>\r\n	Per contattarmi direttamente, scrivete pure a <a href=\"mailto:info@dynamick.it\">info_AT_dynamick.it</a></p>\r\n', '<p>\r\n	If you find some bug, or you have to ask something, write your message on the&nbsp;<a href=\"http://sourceforge.net/forum/?group_id=107986\" target=\"_blank\">sourceforge</a> forum.</p>\r\n<p>\r\n	You can also contact me&nbsp;via email at &nbsp;<a href=\"mailto:info@dynamick.it\">info_AT_dynamick.it</a></p>\r\n<p>\r\n	Have a good work!</p>\r\n');
insert into `aa_translation` values ('406', 'Requisiti', 'Requirements');
insert into `aa_translation` values ('407', '<p>\r\n	<strong>Requisiti</strong></p>\r\n<p>\r\n	Syntax Desktop è un\'\'applicazione web-based che necessita di questi requisiti per poter funzionare.</p>\r\n<p>\r\n	<strong>Lato server</strong></p>\r\n<ol>\r\n	<li>\r\n		Webserver - Attualmente l\'\'applicazione è stata testata esclusivamente con Apache, ma questo non implica che funzioni solo con questo webserver</li>\r\n	<li>\r\n		PHP - Interprete php. Testato con la versione 4</li>\r\n	<li>\r\n		MySql - Testato con database mysql. Syntax usa il wrapper AdoDB, un layer che si occupa dell\'accesso al db. Questo implica che anche altri tipi di database potrebbero funzionare.<br />\r\n		É preferito l\'uso di tabelle di tipo innoDb per poter utilizzare le foreign keys.</li>\r\n</ol>\r\n<p>\r\n	<strong>Lato client</strong></p>\r\n<p>\r\n	<strike>Purtroppo syntax funziona correttamente usando solamente&nbsp;Internet Explorer. Gli altri browser non sono mai stati testati completamente. Mi scuso con questa grave limitazione. Cercherò di aumentare la compatibilità il più presto possibile.</strike> Syntax Desktop funziona correttamente su tutti i moderni browser.</p>\r\n', '<p>\r\n	<strong>Requirement </strong></p>\r\n<p>\r\n	Syntax Desktop&nbsp;needs these requirement for being able to work.</p>\r\n<p>\r\n	<strong>Server side </strong></p>\r\n<ol>\r\n	<li>\r\n		Webserver - Currently the application has been&nbsp;tested exclusively with Apache, but this does not imply that other webservers can work.&nbsp;&nbsp;</li>\r\n	<li>\r\n		PHP - Tested with version 4</li>\r\n	<li>\r\n		MySql - Tested with MySql database. Syntax it uses the AdoDB library, a layer that&nbsp;take care of the access to the db. This implies that also other types of database could work.<br />\r\n		Is preferred the use of innoDb tables&nbsp;type&nbsp;enabling use the foreign keys.</li>\r\n</ol>\r\n<p>\r\n	<strong>Side client </strong><br />\r\n	<strike>Unfortunately syntax works correctly only using Internet Explorer. The others browsers are never&nbsp;been tested&nbsp;completely. In the near future I&nbsp;will try to increase the compatibility.</strike> Syntax Desktop works correctly with all modern browsers.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	<font color=\"#ff0000\">HELP ME!<br />\r\n	If you want to help me, send the right english translation of this page at </font><a href=\"mailto:info@dynamick.it\"><font color=\"#ff0000\">info@dynamick.it</font></a><font color=\"#ff0000\"> .<br />\r\n	Thank you!</font></p>\r\n');
insert into `aa_translation` values ('408', 'Template', 'Template');
insert into `aa_translation` values ('409', '<p>\r\n	<strong>Costruzione dei Template</strong></p>\r\n<p>\r\n	La prima cosa da fare per costruire il proprio sito è definire i template (o modelli grafici) che racchiuderanno i testi del sito. In SyntaxDesktop, la parte dedicata ai template è gestita dal motore <strong>smarty</strong>. Per maggiori informazioni vistate il sito <a href=\"http://www.smarty.net\">http://www.smarty.net</a>.</p>\r\n<p>\r\n	Un template non è altro che un semplice file html o xhtml, statico, che contiene tutti i riferimenti alle immagini e script. All\'interno di questo file è però possibile inserire tag speciali. Questi tag si caraterizzano dal fatto che hanno come carattere di delimitazione la parentesi graffa. Un esempio di tag è questo:<br />\r\n	<font color=\"#006600\">{news}</font><br />\r\n	oppure<br />\r\n	<font color=\"#006600\">{$title}</font></p>\r\n<p>\r\n	Distinguiamo 2 tipi di tag. Il tag che richiama funzioni ed il tag che richiama variabili. La distinzione tra i due tipi è denotata dal segno dollaro ($) prefissa al nome:</p>\r\n<ul>\r\n	<li>\r\n		Nel primo caso (<font color=\"#006600\">{news}</font> ) si fa riferimento ad un <strong>plugin</strong>, cioè si richiama una funzione php presente nella cartella <strong>/syntax desktop/public/plugins/</strong> dal nome <strong>function.news.php</strong> .</li>\r\n	<li>\r\n		Nel secondo caso (<font color=\"#006600\">{$title}</font>)&nbsp;si fa riferimento ad una <strong>variabile</strong> che si chiama $title.</li>\r\n</ul>\r\n<p>\r\n	A template ultimato, lo si deve salvare nella cartella /syntax desktop/public/template/ con l\'estensione .tpl. Per esempio, un nome corretto per un file template potrebbe essere <em>homepage.tpl</em> oppure <em>genericpage.tpl</em>.</p>\r\n<p>\r\n	A questo punto attraverso Syntax Desktop si dovrà definire un nuovo template (amministrazione-&gt;template) cliccando il bottone \"nuovo documento\" nella toolbar di destra. Per definire un template occorre spedificare il nome ed il file, scegliendolo dal menu a tendina che compare alla voce files. Per esempio, per definire il template dell\'homepage, scriveremo \"Homepage\" nel campo del nome e sceglieremo il file homepage.tpl nel menu a tendina files.</p>\r\n', '<p>\r\n	<strong>Template creation</strong></p>\r\n<p>\r\n	The first step you\'ve to do is the creation of your own template.</p>\r\n<p>\r\n	A template&nbsp;is a simple&nbsp;HTML&nbsp;file that it contains all the references to the images and script. Inside a template you can also insert&nbsp;some special tags,&nbsp;smarty tags. These tags&nbsp;are charaterized&nbsp;by curly brakes. Here an example of this kind of tags:<br />\r\n	<font color=\"#006600\">{news} </font><br />\r\n	or<br />\r\n	<font color=\"#006600\">{$title} </font></p>\r\n<p>\r\n	We distinguish 2 tag types. The tag that it recalls functions and the tag that recalls variables. The distinction between the two types is denoted from the sign dollar ($) prefixed to the name.<br />\r\n	In the first case (<font color=\"#006600\"> {news} </font>) we reference&nbsp;a plugin, that&nbsp;launch <strong>function.news.php</strong>,<strong> </strong>a php function situated in the <strong>/syntax desktop/public/plugins/</strong>&nbsp;directory.<br />\r\n	The other row&nbsp;(<font color=\"#006600\"> {$title} </font>) reference&nbsp;a variable&nbsp;called $title.</p>\r\n<p>\r\n	At the ending, you&nbsp;the template&nbsp;have to be saved in the /syntax desktop/public/template/ folder with&nbsp;<strong>.tpl</strong> extension. As an example, a correct name for a template&nbsp;could be homepage.tpl or genericpage.tpl.</p>\r\n<p>\r\n	We have to define a new template inside Syntax Desktop database. Open contents-&gt;section-&gt;template and click&nbsp;\"new document\" in the right toolbar. In order to define template it is necessary to&nbsp;specify the name and the related template file. You have to choose it from the drop-down menu. As an example, in order to define the homepage template, we&nbsp; write \"homepage\" in the name field and&nbsp;choose the rows homepage.tpl in the dropdown menu.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	<font color=\"#ff0000\">HELP ME!<br />\r\n	If you want to help me, send the right english translation of this page at </font><a href=\"mailto:info@dynamick.it\"><font color=\"#ff0000\">info@dynamick.it</font></a><font color=\"#ff0000\"> .<br />\r\n	Thank you!</font></p>\r\n');
insert into `aa_translation` values ('410', 'Pagine', 'Pages');
insert into `aa_translation` values ('411', '<p>\r\n	<strong>Generazione delle Pagine</strong></p>\r\n<p>\r\n	Siamo pronti a definire le pagine del sito.</p>\r\n<p>\r\n	Apriamo la sezione delle Pagine cliccando andando su Gestione Contenuti-&gt;Pagine.</p>\r\n<p>\r\n	Nel centro della pagina vediamo l\'elenco delle pagine già definite, mentre sulla sinistra viene visualizzata la struttura ad albero delle pagine. Da questa schermata si possono modificare, cancellare le pagine esistenti oppure crearne di nuove.</p>\r\n<p>\r\n	Ogni pagina è caratterizzata da un titolo, che verrà valorizzato nella variabile {$synPageTitle}, da un testo, utilizzato dal plugin {page} e da un template (vd. sezioni precedenti). É possibile anche definire un gruppo di utenti preposti alla gestione della pagina. Il checkbox \"visibile\" serve per visualizzare o meno la pagina all\'interno del menu di navigazione.</p>\r\n<p>\r\n	<strike>Per visualizzare il sito, non ci resta altro che compilare le pagine, cioè creare sul file system la struttura che abbiamo scelto per il nostro sito.&nbsp;Per ogni pagina verrà creata una cartella col nome della pagina e all\'\'interno verrà inserito un file index.php. Questo in modo ricorsivo, per ricreare la struttura ad albero definita in precedenza</strike>. Per esempio, per visualizzare una pagina che si chiama \"<strong>ultimapagina</strong>\" inserita nella \"<strong>sezione2</strong>\" che a sua volta è sottosta a \"<strong>sezione1</strong>\" sarà sufficiente andare su: <strong>www.miosito.it/sezione1/sezione2/ultimapagina/</strong>. Questa tecnica è stata ideata per evitare url troppo lunghi e con infiniti parametri passati via GET. Il vantaggio è quello di poter essere indicizzati agevolmente dai motori di ricerca, caratteristica fondamentale per i siti di successo.</p>\r\n', '<p>\r\n	<strong>Pages generation</strong></p>\r\n<p>\r\n	We are ready to&nbsp;start the page creation process.<br />\r\n	You have to open the Content Management-&gt;Site section-&gt;Pages menu in Syntax Desktop.</p>\r\n<p>\r\n	In the center of the page we see the already defined pages, while on the left&nbsp; you can see the same pages in a tree structure. These pages can be modified, deleted or can be created new ones.</p>\r\n<p>\r\n	Every page is characterized&nbsp;by a title, ( you can retrieve this field in&nbsp;every template&nbsp;through&nbsp;the smarty variable {$synPageTitle}),&nbsp;by a text, ( you can retrieve this field in every template through the smarty&nbsp;plugin&nbsp;{page}) and by a template (see previous sections).&nbsp;It is also&nbsp;possible&nbsp;define a group of&nbsp;users&nbsp;that can manage the page. The \"visible\" checkbox&nbsp;specify either to show or to hide the page&nbsp;inside the&nbsp;navigation menu.</p>\r\n<p>\r\n	In order to complete the page creation you have&nbsp;to compile the pages clicking the red button on the left of page.&nbsp;For every page it will&nbsp;created a folder with the name of the page and&nbsp;it will put inside&nbsp;an index.php file. This will done in a recursive way in order to recreate the page structure defined in the page tree visualization. As&nbsp;example, to&nbsp;display a page&nbsp;named&nbsp;<strong> \"lastpage</strong>\" in \"<strong> section2 </strong>\" that&nbsp;resides inside&nbsp;\"<strong>section1</strong>\" you have to go at <strong>www.mysite.com/section1/section2/lastpage/</strong>. This technique&nbsp;avoid&nbsp;too much long URL and&nbsp;fill with&nbsp;infinites GET parameters. In this way the search engines can easily index your pages,&nbsp;the main&nbsp;characteristic for the success web site.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	<font color=\"#ff0000\">HELP ME!<br />\r\n	If you want to help me, send the right english translation of this page at </font><a href=\"mailto:info@dynamick.it\"><font color=\"#ff0000\">info@dynamick.it</font></a><font color=\"#ff0000\"> .<br />\r\n	Thank you!</font></p>\r\n');
insert into `aa_translation` values ('412', 'Tag Predefiniti', 'Predefined tags');
insert into `aa_translation` values ('413', '<p>\r\n	<strong>Tag Predefiniti</strong></p>\r\n<p>\r\n	Elenchiamo di seguito i tag pronti all\'uso definiti da SyntaxDesktop.</p>\r\n<p>\r\n	<strong>Variabili</strong></p>\r\n<p>\r\n	{$synPageTitle}</p>\r\n<ul>\r\n	<li>\r\n		<strong><font color=\"#009900\">{$synPageTitle}</font></strong>: restituisce il nome della pagina. Utile nell\'\'head della pagina quando si deve specificare il titolo. Per esempio:</li>\r\n	<li>\r\n		<strong><font color=\"#009900\">{$synPageId}</font></strong>: restituisce l\'\'id della pagina</li>\r\n	<li>\r\n		<font color=\"#009900\"><strong>{$synPath}</strong></font>: restituisce il path di installazione di syntax desktop. Normalmente è \"syntax desktop\" ma il path di installazione è lasciato a discrezione dell\'\'utente. Questo tag è utile quando si devono puntare le immagini. Le immagini infatti, per pulizia della document root, è meglio riporle nella cartella /syntax desktop/public/img/ . Per esempio, nei tag img, usiamo:<br />\r\n		<font color=\"#0099ff\"><img alt=\"esempio\" src=\"{$synPath}/public/img/esempio.jpg\" /></font></li>\r\n	<li>\r\n		<strong><font color=\"#009900\">{$synAbsPath}</font></strong>: contiene il path assoluto della cartella di installazione di syntax sul file system del web server.</li>\r\n</ul>\r\n<p>\r\n	<strong>Plugins</strong></p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	&nbsp;</p>\r\n<div>\r\n	<font color=\"#0099ff\">{page}</font></div>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	&nbsp;</p>\r\n<ul>\r\n	<li>\r\n		<font color=\"#009900\"><strong>{page}</strong></font>: funzione fondamentale, da porre in ogni template, che ha il compito di restituire il&nbsp;testo della pagina. Per esempio:<br />\r\n		<br />\r\n		In questo caso abbiamo definito un template banale in cui tutto il testo verrà racchiuso all\'interno di un\r\n		<div>\r\n			.</div>\r\n	</li>\r\n</ul>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	&nbsp;</p>\r\n', '<p>\r\n	<strong>Predefined tags</strong></p>\r\n<p>\r\n	We list&nbsp;the predefined&nbsp;tag you can use in your templates.</p>\r\n<p>\r\n	<strong>Variables</strong></p>\r\n<ul>\r\n	<li>\r\n		<strong><font color=\"#009900\">{$synPageTitle}</font></strong>: it returns the page name. Useful when used in the title tag in the head of the page. I.e.:</li>\r\n	<li>\r\n		<strong><font color=\"#009900\">{$synPageId}</font></strong>: it returns the page id</li>\r\n	<li>\r\n		<font color=\"#009900\"><strong>{$synPath}</strong></font>: it returns the installation path of Syntax Desktop. Usually this variable contains&nbsp;\"syntax desktop\", but the installation path can be changed. This tag is very usefull when you use images or scripts. Images, in fact, have to be saved in the /syntax desktop/public/img/ directory, so you don\'t waste the site root. I.e.:<br />\r\n		<font color=\"#0099ff\"><img alt=\"example image\" src=\"{$synPath}/public/img/esempio.jpg\" /></font></li>\r\n	<li>\r\n		<strong><font color=\"#009900\">{$synAbsPath}</font></strong>: it returns the absolute installation path of Syntax Desktop.</li>\r\n</ul>\r\n<p>\r\n	<strong>Plugins</strong></p>\r\n<ul>\r\n	<li>\r\n		<font color=\"#009900\"><strong>{page}</strong></font>: it returns the page contents. You must&nbsp;put this predefined plugin where you want the page text to be displayed. I.e.:<br />\r\n		In this example, we\'ve created a simple template where the page text is displayed inside a div tag.</li>\r\n</ul>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	<font color=\"#ff0000\">HELP ME!<br />\r\n	If you want to help me, send the right english translation of this page at </font><a href=\"mailto:info@dynamick.it\"><font color=\"#ff0000\">info@dynamick.it</font></a><font color=\"#ff0000\"> .<br />\r\n	Thank you!</font></p>\r\n');
insert into `aa_translation` values ('414', 'Importa', 'Import');
insert into `aa_translation` values ('415', 'Addons', 'Addons');
insert into `aa_translation` values ('416', 'Export', 'Export');
insert into `aa_translation` values ('417', 'Fotografie', 'Photos');
insert into `aa_translation` values ('418', 'lista di fotografie', 'a list of photos');
insert into `aa_translation` values ('419', 'Id', 'Id');
insert into `aa_translation` values ('420', '', '');
insert into `aa_translation` values ('421', 'Title', 'Title');
insert into `aa_translation` values ('422', '', '');
insert into `aa_translation` values ('423', 'Text', 'Text');
insert into `aa_translation` values ('424', '', '');
insert into `aa_translation` values ('425', 'Photo', 'Photo');
insert into `aa_translation` values ('426', '', '');
insert into `aa_translation` values ('427', 'Date', 'Date');
insert into `aa_translation` values ('428', '', '');
insert into `aa_translation` values ('429', 'Photos', 'Photos');
insert into `aa_translation` values ('430', 'Photo Gallery', 'Photo Gallery');
insert into `aa_translation` values ('431', '', '');
insert into `aa_translation` values ('432', 'Open Source Contest 2004', 'Open Source Contest 2004');
insert into `aa_translation` values ('433', '<p>\r\n  Syntax Desktop vince nella categoria Business il primo concorso italiano per i progetti Open source. Per informazioni visitare il sito www.opensourcecontest.it</p>\r\n', 'Syntax Desktop won the first italian contest for the open source projects in the business category. You can read more informations at www.opensourcecontest.it');
insert into `aa_translation` values ('434', 'Menu di Servizio', 'Service Menu');
insert into `aa_translation` values ('435', '<div id=\"servicemenu\">\r\n<ul>\r\n    <li><a href=\"http://sourceforge.net/project/showfiles.php?group_id=107986\">Download</a></li>\r\n    <li><a href=\"http://sourceforge.net/forum/?group_id=107986\">Forum</a></li>\r\n    <li><a href=\"http://www.dynamick.it/contatti/\">Contattaci</a></li>\r\n</ul>\r\nQuesto sito di demo</div>', '<DIV id=servicemenu>\r\n<UL>\r\n<LI><A href=\"http://sourceforge.net/project/showfiles.php?group_id=107986\">Download</A></LI>\r\n<LI><A href=\"http://sourceforge.net/forum/?group_id=107986\">Forum</A></LI>\r\n<LI><A href=\"http://www.dynamick.it/contatti/\">Contact us</A></LI></UL>This is a demo site demonstrates you how Syntax Desktop works </DIV>');
insert into `aa_translation` values ('436', 'Album', 'Album');
insert into `aa_translation` values ('437', 'Album fotografici', 'Album fotografici');
insert into `aa_translation` values ('438', 'Id', 'Id');
insert into `aa_translation` values ('439', '', '');
insert into `aa_translation` values ('440', 'Titolo', 'Titolo');
insert into `aa_translation` values ('441', 'Album', 'Album');
insert into `aa_translation` values ('442', '', '');
insert into `aa_translation` values ('443', 'Id', 'Id');
insert into `aa_translation` values ('444', '', '');
insert into `aa_translation` values ('445', 'Title', 'Title');
insert into `aa_translation` values ('446', '', '');
insert into `aa_translation` values ('447', 'Date', 'Date');
insert into `aa_translation` values ('448', '', '');
insert into `aa_translation` values ('449', 'Photo', 'Photo');
insert into `aa_translation` values ('450', '', '');
insert into `aa_translation` values ('451', 'Album', 'Album');
insert into `aa_translation` values ('452', 'Photos', 'Photos');
insert into `aa_translation` values ('453', '', '');
insert into `aa_translation` values ('454', 'Id', 'Id');
insert into `aa_translation` values ('455', '', '');
insert into `aa_translation` values ('456', 'Title', 'Title');
insert into `aa_translation` values ('457', '', '');
insert into `aa_translation` values ('458', 'Text', 'Text');
insert into `aa_translation` values ('459', '', '');
insert into `aa_translation` values ('460', 'Photo', 'Photo');
insert into `aa_translation` values ('461', '', '');
insert into `aa_translation` values ('462', 'Album', 'Album');
insert into `aa_translation` values ('463', '', '');
insert into `aa_translation` values ('464', 'Ordine', 'Order');
insert into `aa_translation` values ('465', '', '');
insert into `aa_translation` values ('466', 'Photos', 'Photos');
insert into `aa_translation` values ('467', 'Parent_id', 'Parent_id');
insert into `aa_translation` values ('468', 'Seleziona il gruppo padre', 'choose the parent group');
insert into `aa_translation` values ('469', 'Owner', 'Owner');
insert into `aa_translation` values ('470', 'Il proprietario della pagina', 'Il proprietario della pagina');
insert into `aa_translation` values ('471', 'Owner', 'Owner');
insert into `aa_translation` values ('472', 'indica il proprietario del tempalte', 'indica il proprietario del tempalte');
insert into `aa_translation` values ('473', 'Url', 'Url');
insert into `aa_translation` values ('474', 'Eventuale collegamento ad un sito esterno(p.e. http://www.dynamick.it)', 'You can specify a link to an external site.\r\nI.e. http://www.dynamick.it');
insert into `aa_translation` values ('475', 'Owner', 'Owner');
insert into `aa_translation` values ('476', 'Se volete che l\'utente possa modificare la propria password, dovete scegliere il gruppo a cui appartiene.', 'If you want the user can change himself the password, you\'ve to choose the group he belongs to.');
insert into `aa_translation` values ('477', 'Wiki', 'Wiki');
insert into `aa_translation` values ('478', '', '');
insert into `aa_translation` values ('479', 'rss', 'rss');
insert into `aa_translation` values ('480', '', '');
insert into `aa_translation` values ('481', 'Plugin', 'Plugin');
insert into `aa_translation` values ('482', '404', '404');
insert into `aa_translation` values ('483', '', '');
insert into `aa_translation` values ('484', 'Media', 'Media');
insert into `aa_translation` values ('485', 'Media', 'Media');
insert into `aa_translation` values ('486', 'Media', 'Media');
insert into `aa_translation` values ('487', 'Upload media con interfaccia drag&drop', 'Upload media with drag&drop');
insert into `aa_translation` values ('488', 'Id', 'Id');
insert into `aa_translation` values ('489', '', '');
insert into `aa_translation` values ('490', 'Filename', 'Filename');
insert into `aa_translation` values ('491', '', '');
insert into `aa_translation` values ('492', 'Path', 'Path');
insert into `aa_translation` values ('493', '', '');
insert into `aa_translation` values ('494', 'Title', 'Title');
insert into `aa_translation` values ('495', '', '');
insert into `aa_translation` values ('496', 'Caption', 'Caption');
insert into `aa_translation` values ('497', '', '');
insert into `aa_translation` values ('498', 'Author', 'Author');
insert into `aa_translation` values ('499', '', '');
insert into `aa_translation` values ('500', 'Modificato il', 'Modified_at');
insert into `aa_translation` values ('501', '', '');
insert into `aa_translation` values ('502', 'Media', 'Media');
insert into `aa_translation` values ('503', 'Media', 'Media');
insert into `aa_translation` values ('504', 'Media Upload', 'Media Upload');
insert into `aa_translation` values ('505', 'Media Upload', 'Media Upload');
insert into `aa_translation` values ('506', 'Tags', 'Tags');
insert into `aa_translation` values ('507', 'tags list', 'tags list');
insert into `aa_translation` values ('508', 'Id', 'Id');
insert into `aa_translation` values ('509', '', '');
insert into `aa_translation` values ('510', 'Tag', 'Tag');
insert into `aa_translation` values ('511', '', '');
insert into `aa_translation` values ('512', 'Tags', 'Tags');
insert into `aa_translation` values ('513', 'Tags', 'Tags');
insert into `aa_translation` values ('514', 'Tagged', 'Tagged');
insert into `aa_translation` values ('515', 'Relazione tra media e tag', 'Relationship between media and tags');
insert into `aa_translation` values ('516', 'Id', 'Id');
insert into `aa_translation` values ('517', '', '');
insert into `aa_translation` values ('518', 'Media_id', 'Media_id');
insert into `aa_translation` values ('519', '', '');
insert into `aa_translation` values ('520', 'Tag_id', 'Tag_id');
insert into `aa_translation` values ('521', '', '');
insert into `aa_translation` values ('522', 'Tagged', 'Tagged');
insert into `aa_translation` values ('523', 'Tagged', 'Tagged');
insert into `aa_translation` values ('524', 'Gruppi', 'Groups');
insert into `aa_translation` values ('525', 'Gruppi utenti web', 'Web users groups');
insert into `aa_translation` values ('526', 'Id', 'Id');
insert into `aa_translation` values ('527', '', '');
insert into `aa_translation` values ('528', 'Gruppo', 'Group');
insert into `aa_translation` values ('529', '', '');
insert into `aa_translation` values ('530', 'Gruppi utenti web', 'Web users groups');
insert into `aa_translation` values ('531', 'Gruppi utenti web', 'Web users groups');
insert into `aa_translation` values ('532', 'Utenti', 'Users');
insert into `aa_translation` values ('533', 'Utenti web', 'Web users');
insert into `aa_translation` values ('534', 'Id', 'Id');
insert into `aa_translation` values ('535', '', '');
insert into `aa_translation` values ('536', 'Nome', 'Name');
insert into `aa_translation` values ('537', '', '');
insert into `aa_translation` values ('538', 'Cognome', 'Surname');
insert into `aa_translation` values ('539', '', '');
insert into `aa_translation` values ('540', 'Azienda', 'Company');
insert into `aa_translation` values ('541', '', '');
insert into `aa_translation` values ('542', 'Email', 'Email');
insert into `aa_translation` values ('543', '', '');
insert into `aa_translation` values ('544', 'Indirizzo', 'Address');
insert into `aa_translation` values ('545', '', '');
insert into `aa_translation` values ('546', 'Città', 'City');
insert into `aa_translation` values ('547', '', '');
insert into `aa_translation` values ('548', 'CAP', 'Zip');
insert into `aa_translation` values ('549', '', '');
insert into `aa_translation` values ('550', 'Provincia', 'Province');
insert into `aa_translation` values ('551', '', '');
insert into `aa_translation` values ('552', 'Confirmation_code', 'Confirmation_code');
insert into `aa_translation` values ('553', '', '');
insert into `aa_translation` values ('554', 'Attivo', 'Active');
insert into `aa_translation` values ('555', '', '');
insert into `aa_translation` values ('556', 'Gruppi', 'Groups');
insert into `aa_translation` values ('557', '', '');
insert into `aa_translation` values ('558', 'Timestamp', 'Timestamp');
insert into `aa_translation` values ('559', '', '');
insert into `aa_translation` values ('560', 'Password', 'Password');
insert into `aa_translation` values ('561', '', '');
insert into `aa_translation` values ('562', 'Newsletter', 'Newsletter');
insert into `aa_translation` values ('563', '', '');
insert into `aa_translation` values ('564', 'Utenti web', 'Web users');
insert into `aa_translation` values ('565', 'Utenti web', 'Web users');
insert into `aa_translation` values ('566', 'Documents', 'Documents');
insert into `aa_translation` values ('567', 'available documents', 'available documents');
insert into `aa_translation` values ('568', 'Id', 'Id');
insert into `aa_translation` values ('569', '', '');
insert into `aa_translation` values ('570', 'Title', 'Title');
insert into `aa_translation` values ('571', '', '');
insert into `aa_translation` values ('572', 'Abstract', 'Abstract');
insert into `aa_translation` values ('573', '', '');
insert into `aa_translation` values ('574', 'Description', 'Description');
insert into `aa_translation` values ('575', '', '');
insert into `aa_translation` values ('576', 'Date', 'Date');
insert into `aa_translation` values ('577', '', '');
insert into `aa_translation` values ('578', 'Location', 'Location');
insert into `aa_translation` values ('579', '', '');
insert into `aa_translation` values ('580', 'File', 'File');
insert into `aa_translation` values ('581', '', '');
insert into `aa_translation` values ('582', 'Category_id', 'Category_id');
insert into `aa_translation` values ('583', '', '');
insert into `aa_translation` values ('584', 'Gruppi abilitati', 'Enabled_groups');
insert into `aa_translation` values ('585', 'Limita l\'accesso al documento ai gruppi selezionati', '');
insert into `aa_translation` values ('586', 'Visible', 'Visible');
insert into `aa_translation` values ('587', '', '');
insert into `aa_translation` values ('588', 'Documenti', 'Documents');
insert into `aa_translation` values ('589', 'Documenti', 'Documents');
insert into `aa_translation` values ('590', 'Categorie', 'Categories');
insert into `aa_translation` values ('591', '', '');
insert into `aa_translation` values ('592', 'Id', 'Id');
insert into `aa_translation` values ('593', '', '');
insert into `aa_translation` values ('594', 'Category', 'Category');
insert into `aa_translation` values ('595', '', '');
insert into `aa_translation` values ('596', 'Order', 'Order');
insert into `aa_translation` values ('597', '', '');
insert into `aa_translation` values ('598', 'Categories', 'Categories');
insert into `aa_translation` values ('599', 'Categories', 'Categories');
insert into `aa_translation` values ('600', 'Users & Groups', 'Users & Groups');
insert into `aa_translation` values ('601', 'Docs', 'Docs');
insert into `aa_translation` values ('602', 'Users & Groups', 'Users & Groups');
insert into `aa_translation` values ('603', 'Utenti Backend', 'Users Backend');
insert into `aa_translation` values ('604', 'Documents', 'Docs');
insert into `aa_translation` values ('605', 'Account', 'Account');
insert into `aa_translation` values ('606', '', '<p>\r\n	Please fill all the fields.</p>\r\n');
insert into `aa_translation` values ('607', 'Documents', 'Documents');
insert into `aa_translation` values ('608', '', '');
insert into `aa_translation` values ('609', 'Gallery', 'Gallery');
insert into `aa_translation` values ('610', '', '');
insert into `aa_translation` values ('611', 'Dictionary', 'Dictionary');
insert into `aa_translation` values ('612', 'User labels', 'User labels');
insert into `aa_translation` values ('613', 'Id', 'Id');
insert into `aa_translation` values ('614', '', '');
insert into `aa_translation` values ('615', 'Etichetta', 'Label');
insert into `aa_translation` values ('616', 'NON MODIFICARE!!!', '');
insert into `aa_translation` values ('617', 'Valore', 'Value');
insert into `aa_translation` values ('618', '', '');
insert into `aa_translation` values ('619', 'Dizionario', 'Dictionary');
insert into `aa_translation` values ('620', 'Dizionario', 'Dictionary');
insert into `aa_translation` values ('621', 'Dizionario', 'Dictionary');
insert into `aa_translation` values ('622', 'Status', 'Status');
insert into `aa_translation` values ('623', '<ul> <li>Public = visibile e scaricabile da chiunque</li> <li>Protected = visibile a tutti, scaricabile solo dagli utenti registrati</li> <li>Private = visibile agli utenti registrati, scaricabile solo da chi fa parte dei gruppi selezionati</li> <li>Secret = visibile e scaricabile solo da chi fa parte dei gruppi selezionati</li> <li>Suspended = non visibile e non scaricabile da nessuno</li> </ul>', '');
insert into `aa_translation` values ('624', 'Genera sitemap', 'Generate sitemap');
insert into `aa_translation` values ('625', 'Genera Sitemap', 'Generate sitemap');
insert into `aa_translation` values ('626', 'Generale', 'General');
insert into `aa_translation` values ('627', 'prova', 'prova');
insert into `aa_translation` values ('628', 'prova2', 'prova2');
insert into `aa_translation` values ('629', 'test', 'test');
insert into `aa_translation` values ('630', 'prova', 'prova');
insert into `aa_translation` values ('631', 'prova', 'prova');
insert into `aa_translation` values ('632', 'test', 'test');
insert into `aa_translation` values ('633', 'test', 'test');
insert into `aa_translation` values ('634', 'test4', 'test4');
insert into `aa_translation` values ('635', 'Primo documento', 'First Document');
insert into `aa_translation` values ('636', 'Documento di test', 'This is a test document');
insert into `aa_translation` values ('637', 'Forms', 'Forms');
insert into `aa_translation` values ('638', '', '');
insert into `aa_translation` values ('639', 'Forms', 'Forms');
insert into `aa_translation` values ('640', '', '');
insert into `aa_translation` values ('641', 'Id', 'Id');
insert into `aa_translation` values ('642', '', '');
insert into `aa_translation` values ('643', 'Pagina', 'Titolo');
insert into `aa_translation` values ('644', 'pagina a cui &egrave; associato il form', '');
insert into `aa_translation` values ('645', 'Descrizione', 'Descrizione');
insert into `aa_translation` values ('646', '', '');
insert into `aa_translation` values ('647', 'Destinatario', 'Destinatario');
insert into `aa_translation` values ('648', 'Casella di posta a cui inviare i dati, es. info@tuosito.com', 'Casella di posta a cui inviare i dati, es. info@tuosito.com');
insert into `aa_translation` values ('649', 'Save_to', 'Save_to');
insert into `aa_translation` values ('650', 'Selezionare il servizio in cui salvare i dati. ATTENZIONE: la tabella deve avere tutti i campi del form.', 'Selezionare il servizio in cui salvare i dati. ATTENZIONE: la tabella deve avere tutti i campi del form.');
insert into `aa_translation` values ('651', 'Privacy', 'Privacy');
insert into `aa_translation` values ('652', 'Il testo dell\'informativa si trova nel dizionario', 'Il testo dell\'informativa si trova nel dizionario');
insert into `aa_translation` values ('653', 'Captcha', 'Captcha');
insert into `aa_translation` values ('654', '', '');
insert into `aa_translation` values ('655', 'Risposta', 'Risposta');
insert into `aa_translation` values ('656', 'Messaggio da visualizzare dopo che il form Ã¨ stato inviato con successo', 'Messaggio da visualizzare dopo che il form ï¿½ stato inviato con successo');
insert into `aa_translation` values ('657', 'Data', 'Data');
insert into `aa_translation` values ('658', '', '');
insert into `aa_translation` values ('659', 'Visibile', 'Visibile');
insert into `aa_translation` values ('660', '', '');
insert into `aa_translation` values ('661', 'Forms', 'Forms');
insert into `aa_translation` values ('662', 'Campi dei form', 'Campi dei form');
insert into `aa_translation` values ('663', 'Campi del form selezionato', 'Campi del form selezionato');
insert into `aa_translation` values ('664', 'Campi dei form', 'Form fields');
insert into `aa_translation` values ('665', 'Campi del form selezionato', 'Fields of the selected form');
insert into `aa_translation` values ('666', 'Id', 'Id');
insert into `aa_translation` values ('667', '', '');
insert into `aa_translation` values ('668', 'Id_form', 'Id_form');
insert into `aa_translation` values ('669', '', '');
insert into `aa_translation` values ('670', 'Titolo', 'Titolo');
insert into `aa_translation` values ('671', '', '');
insert into `aa_translation` values ('672', 'Label', 'Label');
insert into `aa_translation` values ('673', '', '');
insert into `aa_translation` values ('674', 'Tipo', 'Tipo');
insert into `aa_translation` values ('675', 'Se di tipo select, impostare le opzioni nel servizio collegato', 'Se di tipo select, impostare le opzioni nel servizio collegato');
insert into `aa_translation` values ('676', 'Value', 'Value');
insert into `aa_translation` values ('677', 'Valore predefinito', 'Valore predefinito');
insert into `aa_translation` values ('678', 'Formato', 'Formato');
insert into `aa_translation` values ('679', 'Indicare il tipo di tato che si vuole accettare', 'Indicare il tipo di tato che si vuole accettare');
insert into `aa_translation` values ('680', 'Obbligatorio', 'Obbligatorio');
insert into `aa_translation` values ('681', '', '');
insert into `aa_translation` values ('682', 'Fieldset', 'Fieldset');
insert into `aa_translation` values ('683', '', '');
insert into `aa_translation` values ('684', 'Ordine', 'Ordine');
insert into `aa_translation` values ('685', '', '');
insert into `aa_translation` values ('686', 'Campi dei form', 'Campi dei form');
insert into `aa_translation` values ('687', 'Fieldsets', 'Fieldsets');
insert into `aa_translation` values ('688', '', '');
insert into `aa_translation` values ('689', 'Fieldsets', 'Fieldsets');
insert into `aa_translation` values ('690', '', '');
insert into `aa_translation` values ('691', 'Id', 'Id');
insert into `aa_translation` values ('692', '', '');
insert into `aa_translation` values ('693', 'Id_form', 'Id_form');
insert into `aa_translation` values ('694', '', '');
insert into `aa_translation` values ('695', 'Titolo', 'Titolo');
insert into `aa_translation` values ('696', '', '');
insert into `aa_translation` values ('697', 'Ordine', 'Ordine');
insert into `aa_translation` values ('698', '', '');
insert into `aa_translation` values ('699', 'Fieldsets', 'Fieldsets');
insert into `aa_translation` values ('700', 'Opzioni campo', 'Options field');
insert into `aa_translation` values ('701', 'Opzioni del campo selezionato (solo se di tipo select, checkbox o radio) ', 'Options selected fields (only for select, checkbox or radio) ');
insert into `aa_translation` values ('702', 'Id', 'Id');
insert into `aa_translation` values ('703', '', '');
insert into `aa_translation` values ('704', 'Id_field', 'Id_field');
insert into `aa_translation` values ('705', '', '');
insert into `aa_translation` values ('706', 'Label', 'Label');
insert into `aa_translation` values ('707', 'testo visibile all\'utente', 'testo visibile all\'utente');
insert into `aa_translation` values ('708', 'Value', 'Value');
insert into `aa_translation` values ('709', 'Valore trasmesso dal form', 'Valore trasmesso dal form');
insert into `aa_translation` values ('710', 'Selezionato', 'Selezionato');
insert into `aa_translation` values ('711', 'Opzione pre-selezionata di default', 'Opzione pre-selezionata di default');
insert into `aa_translation` values ('712', 'Ordine', 'Ordine');
insert into `aa_translation` values ('713', '', '');
insert into `aa_translation` values ('714', 'Opzioni campo', 'Opzioni campo');
insert into `aa_translation` values ('715', 'Contatti', 'Contatti');
insert into `aa_translation` values ('716', 'Compila il form per inviarci un messaggio:', 'Compila il form per inviarci un messaggio:');
insert into `aa_translation` values ('717', '<h3>Messaggio inviato</h3>\r\n<p>La tua richiesta &egrave; stata inviata, il nostro staff risponder&agrave; appena possibile.<br />\r\nGrazie</p>', '<h3>Messaggio inviato</h3>\r\n<p>La tua richiesta &egrave; stata inviata, il nostro staff risponder&agrave; appena possibile.<br />\r\nGrazie</p>');
insert into `aa_translation` values ('718', 'Contatti', 'Contatti');
insert into `aa_translation` values ('719', 'Compila il form per inviarci un messaggio:', 'Compila il form per inviarci un messaggio:');
insert into `aa_translation` values ('720', '<h3>Messaggio inviato</h3>\r\n<p>La tua richiesta &egrave; stata inviata, il nostro staff risponder&agrave; appena possibile.<br />\r\nGrazie</p>', '<h3>Messaggio inviato</h3>\r\n<p>La tua richiesta &egrave; stata inviata, il nostro staff risponder&agrave; appena possibile.<br />\r\nGrazie</p>');
insert into `aa_translation` values ('721', 'Contatti', 'Contatti');
insert into `aa_translation` values ('722', '<p>Compila il form per inviarci un messaggio:</p>', 'Compila il form per inviarci un messaggio:');
insert into `aa_translation` values ('723', '<h3>Messaggio inviato</h3>\r\n<p>La tua richiesta &egrave; stata inviata, il nostro staff risponder&agrave; appena possibile.<br />\r\nGrazie</p>', '<h3>Messaggio inviato</h3>\r\n<p>La tua richiesta &egrave; stata inviata, il nostro staff risponder&agrave; appena possibile.<br />\r\nGrazie</p>');
insert into `aa_translation` values ('741', 'Nome', 'Nome');
insert into `aa_translation` values ('742', 'Email', 'Email');
insert into `aa_translation` values ('743', 'Registrazione', 'Registrazione');
insert into `aa_translation` values ('744', '', '');
insert into `aa_translation` values ('745', '', '');
insert into `aa_translation` values ('746', 'Registrazione', 'Registrazione');
insert into `aa_translation` values ('747', '', '');
insert into `aa_translation` values ('748', '<h3>ok</h3>\r\n<p>aaa</p>', '<h3>ok</h3>\r\n<p>aaa</p>');
insert into `aa_translation` values ('749', 'Messaggio', 'Messaggio');
insert into `aa_translation` values ('750', 'News', 'News');
insert into `aa_translation` values ('751', '', '');
insert into `aa_translation` values ('752', 'Titolo', 'Titolo');
insert into `aa_translation` values ('753', '', '');
insert into `aa_translation` values ('754', 'Contattaci', '');
insert into `aa_translation` values ('755', 'Presa visione dell\'informativa fornita, acconsento al trattamento dei dati personali', 'I agree to the given terms.');
insert into `aa_translation` values ('756', 'Ai sensi dell\'articolo 10 della ex-legge 31.12.1996 n. 675, in ottemperanza all\'art. 13 del Nuovo Codice Privacy (dlgs 30 Giugno 2003 n° 196/2003) Le forniamo le seguenti informazioni:\r\nIl trattamento dei dati raccolti sarà effettuato mediante elaborazioni manuali, strumenti informatici e telematici e avrà le seguenti finalità:\r\n - dare esecuzione all\'invio delle email verso la casella postale degli utenti sottoscritti;\r\n - raccogliere le essenziali informazioni demografiche che ci permettano di perfezionare e promuovere i servizi promozionali e commerciali;\r\nI dati da Lei conferiti non verranno prestati, venduti o scambiati con altre organizzazioni, se non chiedendoLe espressamente il consenso.\r\nLa informiamo che il conferimento dei dati non è obbligatorio per eseguire l\'invio delle e-mail stesse.\r\nAl titolare del trattamento Lei potrà rivolgersi per far valere i suoi diritti così come previsti dall\'articolo13 della ex-legge n. 675/96 (accesso, rettifica, integrazione, cancellazione, opposizione ecc.), in ottemperanza all\'art. 7 del Nuovo Codice Privacy (dlgs 30 Giugno 2003 n° 196).', 'Following the article 10 ex-law 12.31.1996 no. 675, in conformity with the article 13 of the New Code Privacy ( Dlgs June 30th, 2003 n° 196/2003 ) we give you the following information:\r\nThe treatment of the gathered data will be made by manual elaborations, data processing tools and for the following aims:\r\n          - dispatch of the Email to e-amail address of the subscribed users;\r\n          - gather the essential demographic information that let us perfect and advise the promotional and commercial offers;\r\nThe data conferred by you will not be lent, sold or exchanged with other organizations, if not expressly asking you the assent.\r\nWe inform you that the conferment of the data is optional and their possible non-conferment does not have any consequence.\r\nYou say request the holder of the data treatment as is permited by the law, article 13 ex-law no. 675/96 (accessing, reviewing, integration, deleting personal data), and as is permitted by article 7 New Code Privacy ( Dlgs June 30th, 2003 n° 196 ). ');
insert into `aa_translation` values ('757', 'Verificare i seguenti campi:', 'Check the following fields:');
insert into `aa_translation` values ('758', 'Campo obbligatorio', 'This field is required.');
insert into `aa_translation` values ('759', 'inserire un indirizzo valido', 'enter a valid email');
insert into `aa_translation` values ('760', 'verifica questo campo', 'check this field');
insert into `aa_translation` values ('761', 'Esporta servizi', 'Services export');
insert into `aa_translation` values ('762', 'Importa servizi', 'Services import');
insert into `aa_translation` values ('763', 'Invia', 'Submit');
insert into `aa_translation` values ('764', 'Cancella', 'Reset');
insert into `aa_translation` values ('765', 'Active', 'Active');
insert into `aa_translation` values ('766', '', '');
insert into `aa_translation` values ('767', 'Order', 'Order');
insert into `aa_translation` values ('768', '', '');
insert into `aa_translation` values ('769', 'Hai dimenticato la password?', 'Forgot your password?');
insert into `aa_translation` values ('770', 'Nuovo utente? Registrati qui', 'New user? Sign up here');
insert into `aa_translation` values ('771', 'Se possiedi gi&agrave; i dati per l\'autenticazione, inseriscili qui sotto', 'If you already have the data for authentication, enter them below');
insert into `aa_translation` values ('772', 'Email', 'Email');
insert into `aa_translation` values ('773', 'Password', 'Password');
insert into `aa_translation` values ('774', 'Invia', 'Send');
insert into `aa_translation` values ('775', 'Nome', 'Name');
insert into `aa_translation` values ('776', 'Cognome', 'Surname');
insert into `aa_translation` values ('777', 'Ragione sociale', 'Company name');
insert into `aa_translation` values ('778', 'Indirizzo', 'Address');
insert into `aa_translation` values ('779', 'Citt&agrave;', 'City');
insert into `aa_translation` values ('780', 'CAP', 'ZIP Code');
insert into `aa_translation` values ('781', 'Provincia', 'Province');
insert into `aa_translation` values ('782', 'Il vostro account', 'Il vostro account');
insert into `aa_translation` values ('783', 'Conferma la password', 'Confirm the password');
insert into `aa_translation` values ('784', 'Newsletter', 'Newsletter');
insert into `aa_translation` values ('785', 'desidero registrarmi al servizio', 'I wish to register to the service');
insert into `aa_translation` values ('786', 'Privacy', 'Privacy');
insert into `aa_translation` values ('787', 'ho letto e accettato', 'I have read and accepted');
insert into `aa_translation` values ('788', 'l\'informativa', 'The information');
insert into `aa_translation` values ('789', 'Annulla', 'Cancel');
insert into `aa_translation` values ('790', '5 caratteri', '5 characters');
insert into `aa_translation` values ('791', 'Per poter accedere ai file protetti di &egrave; necessario registrarsi. I campi marcati con * sono obbligatori:', 'In order to access protected files, you must register. Fields marked with * are required:');
insert into `aa_translation` values ('792', 'Dati personali', 'Personal data');
insert into `aa_translation` values ('793', 'Il vostro account', 'Your account');
insert into `aa_translation` values ('794', 'Newsletter', 'Newsletter');
insert into `aa_translation` values ('795', 'desidero registrarmi al servizio', 'I wish to register to the service');
insert into `aa_translation` values ('796', 'Scegli almeno 5 caratteri', 'Choose at least 5 characters');
insert into `aa_translation` values ('797', 'Le password non coincidono', 'Passwords do not match');
insert into `aa_translation` values ('798', 'I dati sono stati salvati', 'The data were saved');
insert into `aa_translation` values ('799', 'Il tuo account &egrave; pronto per essere attivato, clicca sul link che ti &egrave; appena stato spedito all\'indirizzo specificato per confermare la tua registrazione.', 'Your account is ready to be activated, click on the link that has just been sended to the email specified to confirm your registration.');
insert into `aa_translation` values ('800', 'Il tuo account &egrave; stato attivato correttamente.', 'Your account has been activated successfully.');
insert into `aa_translation` values ('801', 'Effettua il login', 'Login');
insert into `aa_translation` values ('802', 'Nome utente o password errati.', 'Username or password incorrect.');
insert into `aa_translation` values ('803', 'Sei autenticato come', 'You are logged in as');
insert into `aa_translation` values ('804', 'Effettua il logout', 'Logout');
insert into `aa_translation` values ('805', 'Login', 'Login');
insert into `aa_translation` values ('806', 'Registrati', 'Register');
insert into `aa_translation` values ('807', 'Logout', 'Logout');
insert into `aa_translation` values ('808', 'Benvenuto', 'Welcome');
insert into `aa_translation` values ('809', 'I tuoi dati sono stati aggiornati correttamente', 'Your information has been updated correctly');
insert into `aa_translation` values ('810', 'Inserisci la tua email, e i tuoi dati, se richiesti, ti verranno recapitati nuovamente al tuo account di posta', 'Enter your email address, and your data, if required, you will be redelivered to your email account');
insert into `aa_translation` values ('811', 'Password rigenerata', 'Password regenerated');
insert into `aa_translation` values ('812', 'Una mail con i tuoi nuovi dati di accesso &egrave; stata spedita all\'indirizzo', 'An email with your new login information was sent to your email address');
insert into `aa_translation` values ('813', 'Indirizzo non riconosciuto', 'Email not recognized');
insert into `aa_translation` values ('814', 'L\'indirizzo inserito non &egrave; presente nel database.', 'The address you entered is not in the database.');
insert into `aa_translation` values ('815', 'Riprova', 'Retry');
insert into `aa_translation` values ('816', 'Codice di sicurezza', 'Security Code');
insert into `aa_translation` values ('817', 'Non sei abilitato per scaricare questo file', 'You aren\'t allowed to download this file');
insert into `aa_translation` values ('818', 'Documento riservato', 'Confidential document');
insert into `aa_translation` values ('819', 'Amministrazione', 'Administration');
insert into `aa_translation` values ('820', 'Pagine', 'Pages');
insert into `aa_translation` values ('821', 'Amministrazione', 'Administration');
insert into `aa_translation` values ('822', 'Metatitle', 'Metatitle');
insert into `aa_translation` values ('823', '', '');
insert into `aa_translation` values ('824', 'Metadescription', 'Metadescription');
insert into `aa_translation` values ('825', '', '');
insert into `aa_translation` values ('826', 'Metakeywords', 'Metakeywords');
insert into `aa_translation` values ('827', '', '');
insert into `aa_translation` values ('828', 'Stili', 'Stili');
insert into `aa_translation` values ('829', '<h1>\r\n	Titolo 1</h1>\r\n<h2>\r\n	Titolo 2</h2>\r\n<h3>\r\n	Titolo 3</h3>\r\n<p>\r\n	Lorem ipsum dolor sit amet consectetuer lacinia auctor fringilla urna ligula. Sed at et dis odio lorem nibh Ut est <strong>neque Curabitur</strong>. Orci Proin ac semper consectetuer sed rutrum gravida vitae interdum congue. Neque Curabitur elit faucibus morbi est convallis congue eros convallis Sed. Nam felis justo nisl Vestibulum Curabitur Phasellus porttitor convallis tristique ridiculus. Massa quis dui Vestibulum enim scelerisque ac wisi id lacus ut. <a href=\"http://www.google.com\">Ante semper tellus</a>.</p>\r\n<p>\r\n	<img alt=\"\" src=\"/public/mat/image/syntax-box.gif\" style=\"width: 77px; height: 100px; float: left;\" />Dictumst id penatibus morbi <strong>parturient Vivamus</strong> orci et ligula feugiat dui. Massa habitasse pretium Suspendisse tincidunt laoreet id felis Pellentesque tellus eu. Ac Phasellus montes Morbi sodales Aenean sociis pellentesque Vestibulum sagittis volutpat. Consectetuer et ante et ac velit quis malesuada est Aenean est. Donec Nulla Sed tellus quis semper tellus consectetuer et nascetur et. Tellus fames mus nulla Curabitur eu eget Curabitur magna metus Vestibulum. <a href=\"http://www.google.com\">Nisl consequat</a>.</p>\r\n<p>\r\n	<img alt=\"\" src=\"/public/mat/image/syntax-box.gif\" style=\"width: 77px; height: 100px; float: right;\" />Ut scelerisque <em>Proin porttitor Quisque</em> gravida facilisis dignissim euismod at leo. Ante eu condimentum nonummy sem sagittis ut cursus amet senectus Aliquam. Nam vitae risus Nullam interdum at dolor dapibus laoreet Praesent auctor. Lobortis Vestibulum porttitor nibh nunc eu lacinia Curabitur Maecenas condimentum lorem. <strike>Volutpat sem justo ipsum nibh semper tempor</strike>.</p>\r\n<h4>\r\n	Titolo 4</h4>\r\n<ul>\r\n	<li>\r\n		Orci Curabitur feugiat mauris convallis</li>\r\n	<li>\r\n		porttitor sagittis condimentum vitae Praesent</li>\r\n	<li>\r\n		Neque cursus nisl Phasellus laoreet habitasse tristique</li>\r\n	<li>\r\n		lacus adipiscing hendrerit velit. Phasellus vel fames commodo</li>\r\n	<li>\r\n		Fermentum a sit egestas dolor tincidunt libero</li>\r\n	<li>\r\n		Curabitur Nulla tincidunt pellentesque augue at penatibus</li>\r\n	<li>\r\n		Phasellus eros lorem tempus libero tortor Lorem wisi felis</li>\r\n</ul>\r\n<hr />\r\n<h5>\r\n	Titolo 5</h5>\r\n<ol>\r\n	<li>\r\n		Quis elit nonummy laoreet et dolor</li>\r\n	<li>\r\n		Sed lorem Curabitur at a</li>\r\n	<li>\r\n		Libero odio id congue pretium convallis tristique</li>\r\n	<li>\r\n		Platea lacinia molestie lacinia congue a interdum</li>\r\n	<li>\r\n		Ultrices fringilla vel Morbi vitae at nascetur tellus</li>\r\n</ol>\r\n<p>\r\n	Mi quis laoreet Vivamus malesuada lacinia dapibus nibh augue Lorem tellus. Mauris wisi et venenatis nec purus sapien lacus hendrerit dictum amet. Pellentesque orci Curabitur commodo ultrices mauris Vestibulum tincidunt in dui et. Pellentesque sit habitasse orci mi volutpat dis sit lorem quis tellus. Dolor adipiscing leo nibh Suspendisse pretium malesuada netus enim condimentum iaculis. Mauris interdum Pellentesque et a urna.</p>\r\n<table border=\"1\" cellpadding=\"1\" cellspacing=\"1\" style=\"width: 100%;\">\r\n	<tbody>\r\n		<tr>\r\n			<td>\r\n				massa condimentum</td>\r\n			<td>\r\n				1000</td>\r\n			<td>\r\n				150</td>\r\n			<td>\r\n				1000000</td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n				pharetra quis Aenean</td>\r\n			<td>\r\n				2000</td>\r\n			<td>\r\n				250</td>\r\n			<td>\r\n				2000000</td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n				Curabitur congue lacinia</td>\r\n			<td>\r\n				3000</td>\r\n			<td>\r\n				350</td>\r\n			<td>\r\n				3000000</td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n				Phasellus ut malesuada</td>\r\n			<td>\r\n				4000</td>\r\n			<td>\r\n				450</td>\r\n			<td>\r\n				4000000</td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n				Ultrices fringilla</td>\r\n			<td>\r\n				5000</td>\r\n			<td>\r\n				550</td>\r\n			<td>\r\n				5000000</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<h6>\r\n	Titolo 6</h6>\r\n<blockquote>\r\n	<p>\r\n		There are painters who transform the sun to a yellow spot, but there are others who with the help of their art and their intelligence, transform a yellow spot into the sun.<br />\r\n		-Pablo Picasso</p>\r\n</blockquote>\r\n<pre>\r\n&nbsp; global $db;\r\n&nbsp; $ret = array();\r\n&nbsp; while(list($l)=$res-&gt;FetchRow()) \r\n    $ret[] = $l;\r\n&nbsp; return $ret;\r\n</pre>\r\n<address>\r\n	Quis elit nonummy laoreet et dolor<br />\r\n	Sed lorem Curabitur at a<br />\r\n	Libero odio id congue pretium convallis tristique</address>\r\n', '<h1>\r\n	Titolo 1</h1>\r\n<h2>\r\n	Titolo 2</h2>\r\n<h3>\r\n	Titolo 3</h3>\r\n<p>\r\n	Lorem ipsum dolor sit amet consectetuer lacinia auctor fringilla urna ligula. Sed at et dis odio lorem nibh Ut est <strong>neque Curabitur</strong>. Orci Proin ac semper consectetuer sed rutrum gravida vitae interdum congue. Neque Curabitur elit faucibus morbi est convallis congue eros convallis Sed. Nam felis justo nisl Vestibulum Curabitur Phasellus porttitor convallis tristique ridiculus. Massa quis dui Vestibulum enim scelerisque ac wisi id lacus ut. <a href=\"http://www.google.com\">Ante semper tellus</a>.</p>\r\n<p>\r\n	Dictumst id penatibus morbi <strong>parturient Vivamus</strong> orci et ligula feugiat dui. Massa habitasse pretium Suspendisse tincidunt laoreet id felis Pellentesque tellus eu. Ac Phasellus montes Morbi sodales Aenean sociis pellentesque Vestibulum sagittis volutpat. Consectetuer et ante et ac velit quis malesuada est Aenean est. Donec Nulla Sed tellus quis semper tellus consectetuer et nascetur et. Tellus fames mus nulla Curabitur eu eget Curabitur magna metus Vestibulum. <a href=\"http://www.google.com\">Nisl consequat</a>.</p>\r\n<p>\r\n	Ut scelerisque <em>Proin porttitor Quisque</em> gravida facilisis dignissim euismod at leo. Ante eu condimentum nonummy sem sagittis ut cursus amet senectus Aliquam. Nam vitae risus Nullam interdum at dolor dapibus laoreet Praesent auctor. Lobortis Vestibulum porttitor nibh nunc eu lacinia Curabitur Maecenas condimentum lorem. <strike>Volutpat sem justo ipsum nibh semper tempor</strike>.</p>\r\n<h4>\r\n	Titolo 4</h4>\r\n<ul>\r\n	<li>\r\n		Orci Curabitur feugiat mauris convallis</li>\r\n	<li>\r\n		porttitor sagittis condimentum vitae Praesent</li>\r\n	<li>\r\n		Neque cursus nisl Phasellus laoreet habitasse tristique</li>\r\n	<li>\r\n		lacus adipiscing hendrerit velit. Phasellus vel fames commodo</li>\r\n	<li>\r\n		Fermentum a sit egestas dolor tincidunt libero</li>\r\n	<li>\r\n		Curabitur Nulla tincidunt pellentesque augue at penatibus</li>\r\n	<li>\r\n		Phasellus eros lorem tempus libero tortor Lorem wisi felis</li>\r\n</ul>\r\n<hr />\r\n<h5>\r\n	Titolo 5</h5>\r\n<ol>\r\n	<li>\r\n		Quis elit nonummy laoreet et dolor</li>\r\n	<li>\r\n		Sed lorem Curabitur at a</li>\r\n	<li>\r\n		Libero odio id congue pretium convallis tristique</li>\r\n	<li>\r\n		Platea lacinia molestie lacinia congue a interdum</li>\r\n	<li>\r\n		Ultrices fringilla vel Morbi vitae at nascetur tellus</li>\r\n</ol>\r\n<p>\r\n	Mi quis laoreet Vivamus malesuada lacinia dapibus nibh augue Lorem tellus. Mauris wisi et venenatis nec purus sapien lacus hendrerit dictum amet. Pellentesque orci Curabitur commodo ultrices mauris Vestibulum tincidunt in dui et. Pellentesque sit habitasse orci mi volutpat dis sit lorem quis tellus. Dolor adipiscing leo nibh Suspendisse pretium malesuada netus enim condimentum iaculis. Mauris interdum Pellentesque et a urna.</p>\r\n<table border=\"1\" cellpadding=\"1\" cellspacing=\"1\" style=\"width: 100%;\">\r\n	<tbody>\r\n		<tr>\r\n			<td>\r\n				massa condimentum</td>\r\n			<td>\r\n				1000</td>\r\n			<td>\r\n				150</td>\r\n			<td>\r\n				1000000</td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n				pharetra quis Aenean</td>\r\n			<td>\r\n				2000</td>\r\n			<td>\r\n				250</td>\r\n			<td>\r\n				2000000</td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n				Curabitur congue lacinia</td>\r\n			<td>\r\n				3000</td>\r\n			<td>\r\n				350</td>\r\n			<td>\r\n				3000000</td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n				Phasellus ut malesuada</td>\r\n			<td>\r\n				4000</td>\r\n			<td>\r\n				450</td>\r\n			<td>\r\n				4000000</td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n				Ultrices fringilla</td>\r\n			<td>\r\n				5000</td>\r\n			<td>\r\n				550</td>\r\n			<td>\r\n				5000000</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<p>\r\n	&nbsp;</p>\r\n');
insert into `aa_translation` values ('830', '', '');
insert into `aa_translation` values ('831', '', '');
insert into `aa_translation` values ('832', '', '');
insert into `aa_translation` values ('833', '', '');
insert into `aa_translation` values ('834', '', '');
insert into `aa_translation` values ('835', '', '');
insert into `aa_translation` values ('836', 'Lavora con noi', 'Lavora con noi');
insert into `aa_translation` values ('837', '<p>\r\n  Vuoi far parte di un gruppo di lavoro giovane e dinamico? Inviaci il tuo curriculum vitae: ti richiameremo non appena si apriranno posizioni inerenti il tuo profilo.</p>\r\n', '<p>\r\n  Vuoi far parte di un gruppo di lavoro giovane e dinamico? Inviaci il tuo curriculum vitae: ti richiameremo non appena si apriranno posizioni inerenti il tuo profilo.</p>\r\n');
insert into `aa_translation` values ('838', '<p>\r\n  I tuoi dati sono stati inviati correttamente. Grazie per la disponibilit&agrave;!</p>\r\n', '<p>\r\n  I tuoi dati sono stati inviati correttamente. Grazie per la disponibilit&agrave;!</p>\r\n');
insert into `aa_translation` values ('839', 'Nome', 'Nome');
insert into `aa_translation` values ('840', 'Cognome', 'Cognome');
insert into `aa_translation` values ('841', 'E-mail', 'E-mail');
insert into `aa_translation` values ('842', 'Telefono', 'Telefono');
insert into `aa_translation` values ('843', 'Curriculum Vitae', 'Curriculum Vitae');
insert into `aa_translation` values ('844', 'Note', 'Note');
insert into `aa_translation` values ('845', 'Test', 'Test');
insert into `aa_translation` values ('846', '', '');
insert into `aa_translation` values ('847', 'test', 'test');
insert into `aa_translation` values ('848', '', '');
insert into `aa_translation` values ('849', '', '');
insert into `aa_translation` values ('850', '', '');
insert into `aa_translation` values ('853', '', '');
insert into `aa_translation` values ('854', '', '');
insert into `aa_translation` values ('855', '', '');
insert into `aa_translation` values ('856', '', '');
insert into `aa_translation` values ('857', '', '');
insert into `aa_translation` values ('858', '', '');
insert into `aa_translation` values ('859', '', '');
insert into `aa_translation` values ('860', '', '');
insert into `aa_translation` values ('861', '', '');
insert into `aa_translation` values ('862', '', '');
insert into `aa_translation` values ('863', '', '');
insert into `aa_translation` values ('864', '', '');
insert into `aa_translation` values ('865', '', '');
insert into `aa_translation` values ('866', '', '');
insert into `aa_translation` values ('867', '', '');
insert into `aa_translation` values ('868', '', '');
insert into `aa_translation` values ('869', '', '');
insert into `aa_translation` values ('870', '', '');
insert into `aa_translation` values ('871', '', '');
insert into `aa_translation` values ('872', '', '');
insert into `aa_translation` values ('873', '', '');
insert into `aa_translation` values ('874', '', '');
insert into `aa_translation` values ('875', '', '');
insert into `aa_translation` values ('876', '', '');
insert into `aa_translation` values ('877', '', '');
insert into `aa_translation` values ('878', '', '');
insert into `aa_translation` values ('879', '', '');
insert into `aa_translation` values ('880', '', '');
insert into `aa_translation` values ('881', 'Visible', 'Visible');
insert into `aa_translation` values ('882', '', '');
insert into `aa_translation` values ('883', 'Elementi', 'Elementi');
insert into `aa_translation` values ('884', 'Elementi dei servizi di Syntax', 'Elementi dei servizi di Syntax');
insert into `aa_translation` values ('885', 'Id', 'Id');
insert into `aa_translation` values ('886', '', '');
insert into `aa_translation` values ('887', 'Classname', 'Classname');
insert into `aa_translation` values ('888', '', '');
insert into `aa_translation` values ('889', 'Name', 'Name');
insert into `aa_translation` values ('890', '', '');
insert into `aa_translation` values ('891', 'Order', 'Order');
insert into `aa_translation` values ('892', '', '');
insert into `aa_translation` values ('893', 'Elementi', 'Elementi');
insert into `aa_translation` values ('894', 'Default', '0');
insert into `aa_translation` values ('895', 'Lingua di default', '0');
insert into `aa_translation` values ('896', 'Slug', 'Slug');
insert into `aa_translation` values ('897', 'Path della pagina', 'Path della pagina');
insert into `aa_translation` values ('898', '', '');
insert into `aa_translation` values ('899', 'intro', 'brief-introduction');
insert into `aa_translation` values ('900', 'installazione', 'installation');
insert into `aa_translation` values ('901', '', '');
insert into `aa_translation` values ('902', '', '');
insert into `aa_translation` values ('903', '', '');
insert into `aa_translation` values ('904', 'personalizzazioni', 'customization');
insert into `aa_translation` values ('905', 'contatti', 'contacts');
insert into `aa_translation` values ('906', 'requisiti', 'requirements');
insert into `aa_translation` values ('907', 'rss', 'rss');
insert into `aa_translation` values ('908', '', '');
insert into `aa_translation` values ('909', '', '');
insert into `aa_translation` values ('910', '', '');
insert into `aa_translation` values ('911', '404', '404');
insert into `aa_translation` values ('912', '', '');
insert into `aa_translation` values ('913', '', '');
insert into `aa_translation` values ('914', '', '');
insert into `aa_translation` values ('915', 'account', 'account');
insert into `aa_translation` values ('916', '', '');
insert into `aa_translation` values ('917', '', '');
insert into `aa_translation` values ('918', '', '');
insert into `aa_translation` values ('919', 'documents', 'documents');
insert into `aa_translation` values ('920', 'gallery', 'gallery');
insert into `aa_translation` values ('921', 'news', 'news');
insert into `aa_translation` values ('922', 'template', 'template');
insert into `aa_translation` values ('923', 'pagine', 'pages');
insert into `aa_translation` values ('924', 'tag-predefiniti', 'predefined-tags');
insert into `aa_translation` values ('925', 'stili', 'stili');
insert into `aa_translation` values ('926', 'Last_update', 'Last_update');
insert into `aa_translation` values ('927', '', '');
insert into `aa_translation` values ('928', 'Last_access', 'Last_access');
insert into `aa_translation` values ('929', '', '');
insert into `aa_translation` values ('930', 'Last_ip', 'Last_ip');
insert into `aa_translation` values ('931', '', '');
insert into `aa_translation` values ('932', 'New_password_key', 'New_password_key');
insert into `aa_translation` values ('933', '', '');
insert into `aa_translation` values ('934', 'New_password_requested', 'New_password_requested');
insert into `aa_translation` values ('935', '', '');
insert into `aa_translation` values ('936', 'New_email', 'New_email');
insert into `aa_translation` values ('937', '', '');
insert into `aa_translation` values ('938', 'New_email_key', 'New_email_key');
insert into `aa_translation` values ('939', '', '');
insert into `aa_translation` values ('940', 'Hashed_id', 'Hashed_id');
insert into `aa_translation` values ('941', '', '');
insert into `aa_translation` values ('942', 'Utente autenticato come <strong>%s</strong>.', 'User logged in as <strong>%s</strong>.');
insert into `aa_translation` values ('943', 'Logout eseguito correttamente.', 'Logout succesful.');
insert into `aa_translation` values ('944', '<strong>Errore:</strong> password non corretta.', '<strong>Error:</strong> incorrect password.');
insert into `aa_translation` values ('945', '<strong>Errore:</strong> account non valido.', '<strong>Error:</strong> account not validated.');
insert into `aa_translation` values ('946', '<strong>Errore:</strong> utente %s non riconosciuto. Verificare di aver inserito correttamente il nome utente.', '<strong>Error:</strong> user %s not recognized. Please check your username.');


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

insert into `aa_users` values ('8685', 'root', 'b7d3c0aaac3060bd1ce4faf1764ca88a', '1', '1', '1');


### structure of table `album` ###

DROP TABLE IF EXISTS `album`;

CREATE TABLE `album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `photo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5;


### data of table `album` ###

insert into `album` values ('4', 'Demo Album', '2010-03-24 16:25:00', '');


### structure of table `categories` ###

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;


### data of table `categories` ###

insert into `categories` values ('1', '626', '10');


### structure of table `dictionary` ###

DROP TABLE IF EXISTS `dictionary`;

CREATE TABLE `dictionary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `label` (`label`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 AUTO_INCREMENT=64;


### data of table `dictionary` ###

insert into `dictionary` values ('1', 'informativa', '755');
insert into `dictionary` values ('2', 'informativa_privacy', '756');
insert into `dictionary` values ('3', 'checkfields', '757');
insert into `dictionary` values ('4', 'campo_obbligatorio', '758');
insert into `dictionary` values ('5', 'email_non_valida', '759');
insert into `dictionary` values ('6', 'verifica_valore', '760');
insert into `dictionary` values ('8', 'cancella', '764');
insert into `dictionary` values ('9', 'password_dimenticata', '769');
insert into `dictionary` values ('10', 'registrati_qui', '770');
insert into `dictionary` values ('11', 'inserisci_dati', '771');
insert into `dictionary` values ('12', 'email', '772');
insert into `dictionary` values ('13', 'password', '773');
insert into `dictionary` values ('14', 'invia', '774');
insert into `dictionary` values ('15', 'nome', '775');
insert into `dictionary` values ('16', 'cognome', '776');
insert into `dictionary` values ('17', 'ragione_sociale', '777');
insert into `dictionary` values ('18', 'indirizzo', '778');
insert into `dictionary` values ('19', 'citta', '779');
insert into `dictionary` values ('20', 'cap', '780');
insert into `dictionary` values ('21', 'provincia', '781');
insert into `dictionary` values ('23', 'conferma_password', '783');
insert into `dictionary` values ('24', 'newsletter', '784');
insert into `dictionary` values ('25', 'desidero_registrarmi', '785');
insert into `dictionary` values ('26', 'privacy', '786');
insert into `dictionary` values ('27', 'letto_accettato', '787');
insert into `dictionary` values ('28', 'letto_informativa', '788');
insert into `dictionary` values ('29', 'annulla', '789');
insert into `dictionary` values ('30', 'cinque_caratteri', '790');
insert into `dictionary` values ('31', 'necessaria_registrazione', '791');
insert into `dictionary` values ('32', 'dati_personali', '792');
insert into `dictionary` values ('33', 'vostro_account', '793');
insert into `dictionary` values ('35', 'registrazione_newsletter', '795');
insert into `dictionary` values ('36', 'almeno_cinque_caratteri', '796');
insert into `dictionary` values ('37', 'password_sbagliate', '797');
insert into `dictionary` values ('38', 'dati_salvati', '798');
insert into `dictionary` values ('39', 'conferma_registrazione', '799');
insert into `dictionary` values ('40', 'account_attivo', '800');
insert into `dictionary` values ('41', 'effettua_login', '801');
insert into `dictionary` values ('42', 'utente_password_errati', '802');
insert into `dictionary` values ('43', 'autenticato', '803');
insert into `dictionary` values ('44', 'effettua_logout', '804');
insert into `dictionary` values ('45', 'login', '805');
insert into `dictionary` values ('46', 'registrati', '806');
insert into `dictionary` values ('47', 'logout', '807');
insert into `dictionary` values ('48', 'benvenuto', '808');
insert into `dictionary` values ('49', 'dati_aggiornati', '809');
insert into `dictionary` values ('50', 'inserisci_dati', '810');
insert into `dictionary` values ('51', 'password_rigenerata', '811');
insert into `dictionary` values ('52', 'nuovi_dati_inviati', '812');
insert into `dictionary` values ('53', 'email_sconosciuta', '813');
insert into `dictionary` values ('54', 'indirizzo_non_presente', '814');
insert into `dictionary` values ('55', 'riprova', '815');
insert into `dictionary` values ('56', 'codice_sicurezza', '816');
insert into `dictionary` values ('57', 'doc_no_abilitazione', '817');
insert into `dictionary` values ('58', 'doc_riservato', '818');
insert into `dictionary` values ('59', 'flash_login_success', '942');
insert into `dictionary` values ('60', 'flash_logout', '943');
insert into `dictionary` values ('61', 'flash_error_password', '944');
insert into `dictionary` values ('62', 'flash_error_account', '945');
insert into `dictionary` values ('63', 'flash_error_user', '946');


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

insert into `documents` values ('1', '635', '636', '2010-03-24', 'zip', '1', '1|2', 'public');


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
  `tipo` enum('text','textarea','checkbox','radio','select','file','password') NOT NULL DEFAULT 'text',
  `value` varchar(255) NOT NULL,
  `formato` enum('text','date','digits','email') NOT NULL DEFAULT 'text',
  `obbligatorio` varchar(255) NOT NULL DEFAULT '',
  `fieldset` int(11) NOT NULL DEFAULT '0',
  `ordine` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 AUTO_INCREMENT=10;


### data of table `form_fields` ###

insert into `form_fields` values ('1', '1', 'nome', '741', 'text', '', 'text', '', '0', '10');
insert into `form_fields` values ('2', '1', 'email', '742', 'text', '', 'email', '1', '0', '20');
insert into `form_fields` values ('3', '1', 'messaggio', '749', 'textarea', '', 'text', '1', '0', '30');
insert into `form_fields` values ('4', '2', 'nome', '839', 'text', '', 'text', '1', '0', '10');
insert into `form_fields` values ('5', '2', 'cognome', '840', 'text', '', 'text', '1', '0', '20');
insert into `form_fields` values ('6', '2', 'email', '841', 'text', '', 'email', '1', '0', '30');
insert into `form_fields` values ('7', '2', 'telefono', '842', 'text', '', 'digits', '', '0', '40');
insert into `form_fields` values ('8', '2', 'curriculum', '843', 'file', '', 'text', '1', '0', '50');
insert into `form_fields` values ('9', '2', 'note', '844', 'textarea', '', 'text', '', '0', '60');


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
  `captcha` enum('nessuno','basic','synCaptcha') NOT NULL DEFAULT 'nessuno',
  `risposta` text NOT NULL,
  `data` datetime NOT NULL,
  `visibile` varchar(255) NOT NULL,
  `pagina` int(255) NOT NULL DEFAULT '0',
  `titolo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;


### data of table `forms` ###

insert into `forms` values ('1', '722', 'info@dynamick.it', '', 'synCaptcha', '723', '2010-12-16 17:18:37', '1', '42', '754');
insert into `forms` values ('2', '837', 'info@dynamick.it', '1', 'nessuno', '838', '2012-04-12 15:31:08', '', '0', '836');


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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;


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

insert into `news` values ('1', '348', '349', '', '2004-11-19 00:00:00', '1|2');
insert into `news` values ('2', '394', '395', '', '2004-12-27 12:00:00', '1|2');
insert into `news` values ('3', '396', '397', '', '2004-12-27 00:00:00', '1|2');
insert into `news` values ('4', '432', '433', '', '2005-04-19 00:00:00', '1|2');


### structure of table `photos` ###

DROP TABLE IF EXISTS `photos`;

CREATE TABLE `photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `album` int(11) NOT NULL,
  `ordine` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `album` (`album`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 AUTO_INCREMENT=29;


### data of table `photos` ###

insert into `photos` values ('25', 'business-italy-ruby-on-rail', 'jpg', '4', '10');
insert into `photos` values ('26', 'photofunia_4d281', 'jpg', '4', '20');
insert into `photos` values ('27', '31', 'jpg', '4', '30');
insert into `photos` values ('28', 'business-italy-2 (Custom)', 'jpg', '4', '40');


### structure of table `tagged` ###

DROP TABLE IF EXISTS `tagged`;

CREATE TABLE `tagged` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `media_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5;


### data of table `tagged` ###



### structure of table `tags` ###

DROP TABLE IF EXISTS `tags`;

CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5;


### data of table `tags` ###



### structure of table `users` ###

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `confirmation_code` varchar(255) NOT NULL,
  `active` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `newsletter` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `last_update` datetime NOT NULL,
  `last_access` datetime NOT NULL,
  `last_ip` varchar(255) NOT NULL,
  `new_password_key` varchar(255) NOT NULL,
  `new_password_requested` varchar(255) NOT NULL,
  `new_email` varchar(255) NOT NULL,
  `new_email_key` varchar(255) NOT NULL,
  `hashed_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 AUTO_INCREMENT=11;


### data of table `users` ###

insert into `users` values ('4', 'Dummy', 'Dude', 'SyntaxDesktop', 'info@syntaxdesktop.com', 'via Garibaldi', 'Villafranca', '37069', 'Verona', '', '1', '2', 'ea52ba980ec513579f5a61cb051434c6', '1', '2010-01-01 00:00:00', '2010-01-01 00:00:00', '2010-01-01 00:00:00', '', '', '', '', '', '');
