DROP TABLE `wallet_payments`;

UPDATE `model_has_roles` SET `model_type` = 'App\\Models\\User';
UPDATE `addresses` SET `addressable_type` = 'App\\Models\\User';

ALTER TABLE `package_payments` CHANGE `payment_details` `payment_details` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `service_payments` CHANGE `payment_details` `payment_details` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `service_payments` ADD `refund_percentage` decimal(10,2) DEFAULT NULL AFTER `cancel_requested`;

ALTER TABLE `cancel_projects` ADD `refund_percentage` decimal(10,2) DEFAULT NULL AFTER `viewed`;

CREATE TABLE `wallets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` double(20,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_details` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `wallets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

UPDATE `system_configurations` SET `value` = '2.0' WHERE `system_configurations`.`type` = 'current_version';

COMMIT;