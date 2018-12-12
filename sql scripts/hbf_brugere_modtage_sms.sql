ALTER TABLE `hbf_brugere` ADD `modtage_sms` TINYINT NOT NULL DEFAULT '1' AFTER `opdateret_medlemskab`;
ALTER TABLE `hbf_kampe` ADD `sms_sent` TINYINT NOT NULL DEFAULT '0' AFTER `startet`;