ALTER TABLE `commands` CHANGE `update` `modified` DATETIME NOT NULL;
ALTER TABLE `commands` DROP `user_id`;