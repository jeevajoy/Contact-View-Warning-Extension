CREATE TABLE IF NOT EXISTS `mtl_civicrm_note_is_warning` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Default MySQL primary key',
  `entity_id` int(10) unsigned NOT NULL COMMENT 'Note Id',
  `contact_id` int(10) unsigned NOT NULL COMMENT 'Contact Id',
  `is_warning` int(10) unsigned NOT NULL COMMENT 'Is the Contribution Type Gift-Aidable?',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_entity_id` (`entity_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;