/* r112 Title Mask Enhancments */
UPDATE `sed_config` SET `config_cat` = 'title' WHERE `config_cat` = 'main' AND `config_name` IN ('maintitle', 'subtitle') LIMIT 2;
UPDATE `sed_config` SET `config_order` = '01' WHERE `config_cat` = 'main' AND `config_name` = 'mainurl' LIMIT 1;
UPDATE `sed_config` SET `config_order` = '02' WHERE `config_cat` = 'main' AND `config_name` = 'adminemail' LIMIT 1;
UPDATE `sed_config` SET `config_order` = '03' WHERE `config_cat` = 'main' AND `config_name` = 'hostip' LIMIT 1;
UPDATE `sed_config` SET `config_order` = '04' WHERE `config_cat` = 'main' AND `config_name` = 'cache' LIMIT 1;
UPDATE `sed_config` SET `config_order` = '05' WHERE `config_cat` = 'main' AND `config_name` = 'clustermode' LIMIT 1;
UPDATE `sed_config` SET `config_order` = '06' WHERE `config_cat` = 'main' AND `config_name` = 'gzip' LIMIT 1;
UPDATE `sed_config` SET `config_order` = '07' WHERE `config_cat` = 'main' AND `config_name` = 'devmode' LIMIT 1;

INSERT INTO `sed_config` (`config_owner` ,`config_cat` ,`config_order` ,`config_name` ,`config_type` ,`config_value` ,`config_default` ,`config_text`) VALUES ('core', 'title', '03', 'title_forum_main', '01', '{FORUM}', '', ''),('core', 'title', '04', 'title_forum_topics', '01', '{FORUM} - {SECTION}', '', ''),('core', 'title', '05', 'title_forum_posts', '01', '{FORUM} - {TITLE}', '', ''),('core', 'title', '06', 'title_forum_newtopic', '01', '{FORUM} - {SECTION}', '', ''),('core', 'title', '07', 'title_forum_editpost', '01', '{FORUM} - {SECTION}', '', ''),('core', 'title', '08', 'title_list', '01', '{TITLE}', '', ''),('core', 'title', '09', 'title_page', '01', '{TITLE}', '', ''),('core', 'title', '10', 'title_pfs', '01', '{PFS}', '', ''),('core', 'title', '11', 'title_pm_main', '01', '{PM}', '', ''),('core', 'title', '12', 'title_pm_send', '01', '{PM}', '', ''),('core', 'title', '13', 'title_users_main', '01', '{USERS}', '', ''),('core', 'title', '14', 'title_users_details', '01', '{USER} : {NAME}', '', ''),('core', 'title', '15', 'title_users_profile', '01', '{PROFILE}', '', ''),('core', 'title', '16', 'title_users_edit', '01', '{NAME}', '', ''),('core', 'title', '17', 'title_header', '01', '{MAINTITLE} - {SUBTITLE}', '', ''),('core', 'title', '18', 'title_header_index', '01', '{MAINTITLE} - {DESCRIPTION}', '', '');

/* r124 Subforum enhancements */
CREATE TABLE `sed_forum_subforums` (
  `fm_id` smallint(5) NOT NULL default '0',
  `fm_masterid` smallint(5) NOT NULL default '0',
  `fm_title` varchar(128) NOT NULL,
  `fm_lt_id` int(11) NOT NULL default '0',
  `fm_lt_title` varchar(64) NOT NULL default '',
  `fm_lt_date` int(11) NOT NULL default '0',
  `fm_lt_posterid` int(11) NOT NULL default '-1',
  `fm_lt_postername` varchar(24) NOT NULL default ''
) TYPE=MyISAM;

/* r128 Extra fields */
CREATE TABLE `sed_pages_extra_fields` (
  `field_name` varchar(255) NOT NULL,
  `field_type` varchar(255) NOT NULL,
  `field_html` text NOT NULL,
  `field_variants` text NOT NULL,
  UNIQUE KEY `field_name` (`field_name`)
) TYPE=MyISAM