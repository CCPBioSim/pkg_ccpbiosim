CREATE TABLE IF NOT EXISTS `#__ccpbiosim_core_team` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`state` TINYINT(1)  NULL  DEFAULT 1,
`ordering` INT(11)  NULL  DEFAULT 0,
`checked_out` INT(11)  UNSIGNED,
`checked_out_time` DATETIME NULL  DEFAULT NULL ,
`created_by` INT(11)  NULL  DEFAULT 0,
`modified_by` INT(11)  NULL  DEFAULT 0,
`title` VARCHAR(255)  NOT NULL ,
`firstname` VARCHAR(30)  NOT NULL ,
`surname` VARCHAR(30)  NOT NULL ,
`role` TEXT NOT NULL ,
`profilephoto` TEXT NULL ,
`groupwebsite` VARCHAR(255)  NULL  DEFAULT "",
`social` VARCHAR(255)  NULL  DEFAULT "",
`chair` VARCHAR(255)  NULL  DEFAULT "",
`cosecprojectlead` VARCHAR(255)  NULL  DEFAULT "",
`adminassistant` VARCHAR(255)  NULL  DEFAULT "",
`programme` VARCHAR(255)  NULL  DEFAULT "",
`insitution` VARCHAR(255)  NULL  DEFAULT "",
PRIMARY KEY (`id`)
,KEY `idx_state` (`state`)
,KEY `idx_checked_out` (`checked_out`)
,KEY `idx_created_by` (`created_by`)
,KEY `idx_modified_by` (`modified_by`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__ccpbiosim_management_team` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`state` TINYINT(1)  NULL  DEFAULT 1,
`ordering` INT(11)  NULL  DEFAULT 0,
`checked_out` INT(11)  UNSIGNED,
`checked_out_time` DATETIME NULL  DEFAULT NULL ,
`created_by` INT(11)  NULL  DEFAULT 0,
`modified_by` INT(11)  NULL  DEFAULT 0,
`title` VARCHAR(255)  NOT NULL ,
`firstname` VARCHAR(30)  NOT NULL ,
`surname` VARCHAR(30)  NOT NULL ,
`role` TEXT NOT NULL ,
`profilephoto` TEXT NULL ,
`groupwebsite` VARCHAR(255)  NULL  DEFAULT "",
`social` VARCHAR(255)  NULL  DEFAULT "",
`chair` VARCHAR(255)  NULL  DEFAULT "",
`cosecprojectlead` VARCHAR(255)  NULL  DEFAULT "",
`adminassistant` VARCHAR(255)  NULL  DEFAULT "",
`insitution` VARCHAR(255)  NULL  DEFAULT "",
`secretary` VARCHAR(255)  NULL  DEFAULT "",
PRIMARY KEY (`id`)
,KEY `idx_state` (`state`)
,KEY `idx_checked_out` (`checked_out`)
,KEY `idx_created_by` (`created_by`)
,KEY `idx_modified_by` (`modified_by`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `rules`, `field_mappings`, `content_history_options`)
SELECT * FROM ( SELECT 'Core team Member','com_ccpbiosim.coreteammember','{"special":{"dbtable":"#__ccpbiosim_core_team","key":"id","type":"CoreteammemberTable","prefix":"Joomla\\\\Component\\\\Ccpbiosim\\\\Administrator\\\\Table\\\\"}}', CASE 
                                    WHEN 'rules' is null THEN ''
                                    ELSE ''
                                    END as rules, CASE 
                                    WHEN 'field_mappings' is null THEN ''
                                    ELSE ''
                                    END as field_mappings, '{"formFile":"administrator\/components\/com_ccpbiosim\/forms\/coreteammember.xml", "hideFields":["checked_out","checked_out_time","params","language" ,"role"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_ccpbiosim.coreteammember')
) LIMIT 1;

INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `rules`, `field_mappings`, `content_history_options`)
SELECT * FROM ( SELECT 'Management team','com_ccpbiosim.managementteam','{"special":{"dbtable":"#__ccpbiosim_management_team","key":"id","type":"ManagementteamTable","prefix":"Joomla\\\\Component\\\\Ccpbiosim\\\\Administrator\\\\Table\\\\"}}', CASE 
                                    WHEN 'rules' is null THEN ''
                                    ELSE ''
                                    END as rules, CASE 
                                    WHEN 'field_mappings' is null THEN ''
                                    ELSE ''
                                    END as field_mappings, '{"formFile":"administrator\/components\/com_ccpbiosim\/forms\/managementteam.xml", "hideFields":["checked_out","checked_out_time","params","language" ,"role"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_ccpbiosim.managementteam')
) LIMIT 1;
