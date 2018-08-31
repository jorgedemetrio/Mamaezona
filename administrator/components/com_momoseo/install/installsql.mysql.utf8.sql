CREATE TABLE IF NOT EXISTS `#__mom_dyna_page` ( 
	`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
	`url` varchar(255) NOT NULL UNIQUE,
	`data_alteracao` datetime NOT NULL,
	`prioridade` DECIMAL(4,2) NOT NULL DEFAULT 0.2,
	`tipo` varchar(4) DEFAULT 'PAGE'
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__mom_crow_page` ( 
	`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
	`url` varchar(255) NOT NULL UNIQUE,
	`data_alteracao` datetime NOT NULL,
	`prioridade` DECIMAL(4,2) NOT NULL DEFAULT 0.2
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__mom_sugest_page` ( 
	`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
	`url` varchar(255) NOT NULL,
	`erro_id` int UNIQUE
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__mom_tips_text` ( 
	`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
	`titulo` varchar(50),
	`texto` varchar(255) not null
) ENGINE = InnoDB DEFAULT CHARSET=utf8;