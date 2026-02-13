CREATE TABLE `tblhealthtracking` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`user_id` INT(10) NOT NULL DEFAULT '0',
	`entry_date` DATE NOT NULL,
	`steps` INT(10) NOT NULL DEFAULT '0',
	`calorie_intake` INT(10) NOT NULL DEFAULT '0',
	`sleep_minutes` INT(10) NOT NULL DEFAULT '0',
	`exercise_minutes` INT(10) NOT NULL DEFAULT '0',
	`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `FK__tblusers` (`user_id`) USING BTREE,
	CONSTRAINT `FK__tblusers` FOREIGN KEY (`user_id`) REFERENCES `tblusers` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
;
