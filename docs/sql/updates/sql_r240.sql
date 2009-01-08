/* r240 New universal extra fields system + extra fields for users */
RENAME TABLE sed_pages_extra_fields  TO sed_extra_fields ;
ALTER TABLE `sed_extra_fields` ADD `field_location` VARCHAR( 255 ) NOT NULL FIRST ;
ALTER TABLE `sed_extra_fields` ADD `field_description` TEXT NOT NULL ;
ALTER TABLE `sed_extra_fields` ADD INDEX ( `field_location` ) ; 
ALTER TABLE `sed_extra_fields` DROP INDEX `field_name`  ;
ALTER TABLE `sed_extra_fields` ADD INDEX ( `field_name` )  ;
INSERT INTO `sed_extra_fields` (`field_location`, `field_name`, `field_type`, `field_html`, `field_variants`, `field_description`) VALUES('pages', 'extra1', 'input', '<input class="text" type="text" maxlength="255" size="56" />', '', ''), ('pages', 'extra2', 'input', '<input class="text" type="text" maxlength="255" size="56" />', '', ''), ('pages', 'extra3', 'input', '<input class="text" type="text" maxlength="255" size="56" />', '', ''), ('pages', 'extra4', 'input', '<input class="text" type="text" maxlength="255" size="56" />', '', ''), ('pages', 'extra5', 'input', '<input class="text" type="text" maxlength="255" size="56" />', '', ''), ('users', 'extra1', 'input', '<input class="text" type="text" maxlength="255" size="56" />', '', ''), ('users', 'extra2', 'input', '<input class="text" type="text" maxlength="255" size="56" />', '', ''), ('users', 'extra3', 'input', '<input class="text" type="text" maxlength="255" size="56" />', '', ''), ('users', 'extra4', 'input', '<input class="text" type="text" maxlength="255" size="56" />', '', ''), ('users', 'extra5', 'input', '<input class="text" type="text" maxlength="255" size="56" />', '', ''), ('users', 'extra6', 'textarea', '<textarea cols="80" rows="6" ></textarea>', '', ''), ('users', 'extra7', 'textarea', '<textarea cols="80" rows="6" ></textarea>', '', ''), ('users', 'extra8', 'textarea', '<textarea cols="80" rows="6" ></textarea>', '', ''), ('users', 'extra9', 'textarea', '<textarea cols="80" rows="6" ></textarea>', '', '');
DELETE  FROM sed_config WHERE config_owner = 'core' AND config_cat = 'users' AND config_name LIKE 'extra%';