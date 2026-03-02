CREATE TABLE IF NOT EXISTS `#__ccpbiosim_event_categories` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`state` TINYINT(1)  NULL  DEFAULT 1,
`ordering` INT(11)  NULL  DEFAULT 0,
`checked_out` INT(11)  UNSIGNED,
`checked_out_time` DATETIME NULL  DEFAULT NULL ,
`created_by` INT(11)  NULL  DEFAULT 0,
`modified_by` INT(11)  NULL  DEFAULT 0,
`eventcategory` VARCHAR(255)  NOT NULL ,
`description` TEXT NOT NULL ,
PRIMARY KEY (`id`)
,KEY `idx_state` (`state`)
,KEY `idx_checked_out` (`checked_out`)
,KEY `idx_created_by` (`created_by`)
,KEY `idx_modified_by` (`modified_by`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__ccpbiosim_events` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`state` TINYINT(1)  NULL  DEFAULT 1,
`ordering` INT(11)  NULL  DEFAULT 0,
`checked_out` INT(11)  UNSIGNED,
`checked_out_time` DATETIME NULL  DEFAULT NULL ,
`created_by` INT(11)  NULL  DEFAULT 0,
`modified_by` INT(11)  NULL  DEFAULT 0,
`title` VARCHAR(255)  NOT NULL ,
`category` TEXT NOT NULL ,
`startdatetime` DATETIME NULL  DEFAULT NULL ,
`enddatetime` DATETIME NULL  DEFAULT NULL ,
`eventdetails` TEXT NOT NULL ,
PRIMARY KEY (`id`)
,KEY `idx_state` (`state`)
,KEY `idx_checked_out` (`checked_out`)
,KEY `idx_created_by` (`created_by`)
,KEY `idx_modified_by` (`modified_by`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `rules`, `field_mappings`, `content_history_options`)
SELECT * FROM ( SELECT 'Event category','com_ccpbiosim.eventcategory','{"special":{"dbtable":"#__ccpbiosim_event_categories","key":"id","type":"EventcategoryTable","prefix":"Joomla\\\\Component\\\\Ccpbiosim\\\\Administrator\\\\Table\\\\"}}', CASE 
                                    WHEN 'rules' is null THEN ''
                                    ELSE ''
                                    END as rules, CASE 
                                    WHEN 'field_mappings' is null THEN ''
                                    ELSE ''
                                    END as field_mappings, '{"formFile":"administrator\/components\/com_ccpbiosim\/forms\/eventcategory.xml", "hideFields":["checked_out","checked_out_time","params","language" ,"description"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_ccpbiosim.eventcategory')
) LIMIT 1;

CREATE INDEX `#__ccpbiosim_events_startdatetime` ON `#__ccpbiosim_events`(`startdatetime`);

CREATE INDEX `#__ccpbiosim_events_enddatetime` ON `#__ccpbiosim_events`(`enddatetime`);

INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `rules`, `field_mappings`, `content_history_options`)
SELECT * FROM ( SELECT 'Event','com_ccpbiosim.event','{"special":{"dbtable":"#__ccpbiosim_events","key":"id","type":"EventTable","prefix":"Joomla\\\\Component\\\\Ccpbiosim\\\\Administrator\\\\Table\\\\"}}', CASE 
                                    WHEN 'rules' is null THEN ''
                                    ELSE ''
                                    END as rules, CASE 
                                    WHEN 'field_mappings' is null THEN ''
                                    ELSE ''
                                    END as field_mappings, '{"formFile":"administrator\/components\/com_ccpbiosim\/forms\/event.xml", "hideFields":["checked_out","checked_out_time","params","language" ,"eventdetails"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_ccpbiosim.event')
) LIMIT 1;
