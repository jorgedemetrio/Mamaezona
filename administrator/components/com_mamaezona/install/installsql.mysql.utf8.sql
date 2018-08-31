-- MySQL dump 10.13  Distrib 5.7.20, for Linux (x86_64)
--
-- Host: localhost    Database: amandaads
-- ------------------------------------------------------
-- Server version	5.7.20

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- STATUS PADRÃO

-- 0 = CADASTRADO PARA VALIDACAO
-- 1 = ATIVADO OU PUBLICADO
-- 2 = BLOQUEADO
-- 3 = REMOVIDO


SELECT @USUARIO_ID :=  MIN(id)  FROM yt_users;

	
CREATE TABLE IF NOT EXISTS `yt_conteudo_mm` ( 
  `id` INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT, 
  `id_usuario` INT(11) NOT NULL, 
  `titulo` VARCHAR(200) NOT NULL, 
  `token` VARCHAR(200) UNIQUE NOT NULL, 
  `token_provedor` VARCHAR(200), 
  `url` VARCHAR(255) NOT NULL, 
  `descricao` TEXT, 
  `tipo` ENUM('Y','F','I','T') NOT NULL,
  
  `created_by_ip` VARCHAR(25) NOT NULL,
  `modified_by_ip` VARCHAR(25) NOT NULL,
  
  `id_tipo_conteudo` INT(11) NULL, 

	
  `alias` varchar(400) NOT NULL DEFAULT '', 	
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `catid` int(10) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `created_by` int(10) NOT NULL,
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NULL,
  `modified_by` int(10) NULL,
  `checked_out` int(10) NULL,
  `checked_out_time` datetime NULL,
  `publish_up` datetime NULL,
  `publish_down` datetime NULL,
  `images` text NOT NULL,
  `urls` text NOT NULL,
  `attribs` varchar(5120) NOT NULL,
  `version` int(10) NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `access` int(10) NOT NULL DEFAULT '0',
  `hits` int(10) NOT NULL DEFAULT '0',
  `metadata` text NOT NULL,
  `language` char(7) NOT NULL COMMENT 'Idioma de cada componente.',
  `xreference` varchar(50) NOT NULL DEFAULT '' COMMENT 'A reference to enable linkages to external data sets.',
	
	FOREIGN KEY (id_tipo_conteudo) REFERENCES yt_grupo_assunto_conteudo_mm(id),	
	FOREIGN KEY (id_usuario) REFERENCES yt_users(id),
	FOREIGN KEY (created_by) REFERENCES yt_users(id),
	FOREIGN KEY (modified_by) REFERENCES yt_users(id)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;

-- Vídeos que já foram assistidos 
CREATE TABLE IF NOT EXISTS `yt_conteudo_assistido_mm` ( 
  `id_conteudo` INT(11) NOT NULL, 
  `id_usuario` INT(11) NOT NULL, 
  PRIMARY KEY (`id_conteudo`, `id_usuario`),
  FOREIGN KEY (id_usuario) REFERENCES yt_users(id),  
  FOREIGN KEY (id_conteudo) REFERENCES yt_conteudo_mm(id) 
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;

-- Vídeos que já carregados para o usuário
CREATE TABLE IF NOT EXISTS `yt_conteudo_listado_mm` ( 
  `id_conteudo` INT(11) NOT NULL, 
  `id_usuario` INT(11) NOT NULL, 
  PRIMARY KEY (`id_conteudo`, `id_usuario`),
  FOREIGN KEY (id_usuario) REFERENCES yt_users(id),  
  FOREIGN KEY (id_conteudo) REFERENCES yt_conteudo_mm(id) 
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `yt_usuario_mm` ( 
		  `id_usuario` INT(11) NOT NULL primary key, 
		  `apelido` VARCHAR(200) NOT NULL, 
		  `primeiro_nome` VARCHAR(200) NOT NULL, 
		  `sobre_nome` VARCHAR(200) NOT NULL, 
		  `token` VARCHAR(200) UNIQUE NOT NULL, 
		  `celular` VARCHAR(12) NULL, 
		  `ddd_celular` VARCHAR(4) NULL, 
		  `cpf` VARCHAR(14) NULL, 
		  `genero` ENUM('M','F') NULL, 
		  `data_nascimento` date NULL, 
		  
		  `idioma` varchar(5) DEFAULT 'pt-BR', 
		  
		  
		  `cep` varchar(10) NULL, 
		  `id_cidade` INT(11) NULL, 
		  
		  
		  `toke_youtube` VARCHAR(255)  NULL, 
		  `toke_facebook` VARCHAR(255)  NULL, 
		  `toke_instagram` VARCHAR(255)  NULL, 
		  
		  `id_tipo_conteudo_principal` INT(11), 
		  
		  `descricao_redesocial` TEXT, 
		  `tipo` ENUM('Y','F','I','T') NOT NULL,
		  
		  
		  `saldo_feijoes` NUMERIC(12,2) DEFAULT 0,
		  `saldo_tutu` NUMERIC(12,2) DEFAULT 0,
		  `nivel` NUMERIC(10) DEFAULT 0,
		  `experiencia_nivel` NUMERIC(10) DEFAULT 0,
		  
		  
		  `created_by_ip` VARCHAR(25) NOT NULL,
		  `modified_by_ip` VARCHAR(25),
		  
				  
		  `status` tinyint(3) DEFAULT '0',
		  `created` datetime,
		  `created_by` int(10) NOT NULL,
		  `modified` datetime NULL,
		  `modified_by` int(10) NULL,
		  `hits` int(10) DEFAULT '0',
				  
		  
			FOREIGN KEY (id_usuario) REFERENCES yt_users(id),
			FOREIGN KEY (created_by) REFERENCES yt_users(id),
			FOREIGN KEY (modified_by) REFERENCES yt_users(id),
			FOREIGN KEY (id_tipo_conteudo_principal) REFERENCES yt_grupo_assunto_conteudo_mm(id)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;
		  

CREATE TABLE IF NOT EXISTS `yt_fotos_perfil_mm` ( 
	`token` VARCHAR(200) PRIMARY KEY NOT NULL, 
	
	`tipo` enum('P','G') NOT NULL DEFAULT 'P',-- P = PRINCPAL , G = GALERIA
	
	`id_usuario` INT(11) NOT NULL, 
	`created` datetime NOT NULL,
	`created_by` int(10) NOT NULL,
	`created_by_ip` VARCHAR(25) NOT NULL,
	`status` tinyint(3) NOT NULL DEFAULT '1',
	
	FOREIGN KEY (created_by) REFERENCES yt_users(id),
	FOREIGN KEY (id_usuario) REFERENCES yt_usuario_mm(id_usuario)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;

               

CREATE TABLE IF NOT EXISTS `yt_usu_assunto_interesse_mm` ( 
	`id_assunto` INT(11) NOT NULL, 
	`id_usuario` INT(11) NOT NULL, 
	`created` datetime NOT NULL,
	`created_by` int(10) NOT NULL,
	
	primary key (id_assunto,id_usuario),
	FOREIGN KEY (created_by) REFERENCES yt_users(id),
	FOREIGN KEY (id_usuario) REFERENCES yt_usuario_mm(id_usuario),
	FOREIGN KEY (id_assunto) REFERENCES yt_assunto_conteudo_mm(id)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `yt_usu_assunto_princ_cont_mm` ( 
	`id_assunto` INT(11) NOT NULL, 
	`id_conteudo` INT(11) NOT NULL, 
	`created` datetime NOT NULL,
	`created_by` int(10) NOT NULL,
	
	primary key (id_assunto,id_conteudo),
	FOREIGN KEY (created_by) REFERENCES yt_users(id),
	FOREIGN KEY (id_conteudo) REFERENCES yt_conteudo_mm(id_usuario),
	FOREIGN KEY (id_assunto) REFERENCES yt_assunto_conteudo_mm(id)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;          



CREATE TABLE IF NOT EXISTS `yt_conteudo_assunto_mm` ( 
	`id_assunto` INT(11) NOT NULL, 
	`id_conteudo` INT(11) NOT NULL, 
	`created` datetime NOT NULL,
	`created_by` int(10) NOT NULL,
	
	primary key (id_assunto,id_conteudo),
	FOREIGN KEY (created_by) REFERENCES yt_users(id),
	FOREIGN KEY (id_conteudo) REFERENCES yt_conteudo_mm(id),
	FOREIGN KEY (id_assunto) REFERENCES yt_assunto_conteudo_mm(id)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;






/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `yt_grupo_assunto_conteudo_mm` ( 
  `id` INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT, 
  `titulo` VARCHAR(200) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT  CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;






/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `yt_assunto_conteudo_mm` ( 
  `id` INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT, 
  `titulo` VARCHAR(200) NOT NULL, 
  `token` VARCHAR(200) UNIQUE NOT NULL, 
  `descricao` varchar(255), 
  
  `id_grupo_assunto` INT(11) NOT NULL, 

  
  `created_by_ip` VARCHAR(25) NOT NULL,
  `modified_by_ip` VARCHAR(25) NULL,
  `aprovado_by_ip` VARCHAR(25) NULL,
	
   `alias` varchar(400) NOT NULL DEFAULT '', 	
   `status` tinyint(3) NOT NULL DEFAULT '0',
   `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
   `created_by` int(10) NOT NULL,
   `modified` datetime NULL,
   `modified_by` int(10) NULL,
   `aprovado_by` int(10) NULL,
   `ordering` int(11) NOT NULL DEFAULT '0',
   `metakey` text NOT NULL,
   `metadesc` text NOT NULL,
   `hits` int(10) NOT NULL DEFAULT '0',
	
	FOREIGN KEY (aprovado_by) REFERENCES yt_users(id),
	FOREIGN KEY (created_by) REFERENCES yt_users(id),
	FOREIGN KEY (modified_by) REFERENCES yt_users(id),
	FOREIGN KEY (id_grupo_assunto) REFERENCES yt_grupo_assunto_conteudo_mm(id)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `yt_tipo_conteudo_mm` ( 
  `id` INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT, 
  `titulo` VARCHAR(200) NOT NULL, 
  `token` VARCHAR(200) UNIQUE NOT NULL, 
  `descricao` varchar(255), 
  
  `created_by_ip` VARCHAR(25) NOT NULL,
  `modified_by_ip` VARCHAR(25) NULL,
  `aprovado_by_ip` VARCHAR(25) NULL,
  
   `id_grupo_assunto` INT(11) NOT NULL, 
   `id_tipo_conteudo_pai` INT(11) NULL, 
	
   `alias` varchar(400) NOT NULL DEFAULT '', 	
   `status` tinyint(3) NOT NULL DEFAULT '0',
   `created` datetime NOT NULL,
   `created_by` int(10) NOT NULL,
   `modified` datetime NULL,
   `modified_by` int(10) NULL,
   `aprovado_by` int(10) NULL,
   `ordering` int(11) NOT NULL DEFAULT '0',
   `metakey` text NOT NULL,
   `metadesc` text NOT NULL,
   `hits` int(10) NOT NULL DEFAULT '0',
	
	FOREIGN KEY (aprovado_by) REFERENCES yt_users(id),
	FOREIGN KEY (created_by) REFERENCES yt_users(id),
	FOREIGN KEY (modified_by) REFERENCES yt_users(id),
	FOREIGN KEY (id_grupo_assunto) REFERENCES yt_grupo_assunto_conteudo_mm(id),
	FOREIGN KEY (id_tipo_conteudo_pai) REFERENCES yt_tipo_conteudo_mm(id)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
-- ) ENGINE = InnoDB   DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;











-- -------------------------------------------------------------------- 
--
-- CRIANDO VIEWS
--
-- --------------------------------------------------------------------

CREATE OR REPLACE VIEW yt_HOME_CONTEUDO_NAO_LOGADO AS 
SELECT 	
		TB.`token`,
		TB.`titulo`,
		TB.`alias`
FROM 
((SELECT 
		conteudo.`token`,
		conteudo.`titulo`,
		conteudo.`alias`
	FROM
		`yt_conteudo_mm` AS conteudo INNER JOIN
		`yt_usuario_mm` AS usuario ON usuario.id_usuario = conteudo.id_usuario
	WHERE
		conteudo.tipo = 'Y' AND 
		usuario.`saldo_feijoes` > 0
	ORDER BY 
		usuario.`saldo_feijoes`,
		usuario.`nivel` ASC,
		usuario.`experiencia_nivel`,
		conteudo.hits ASC
	LIMIT 1 )
UNION ALL
(SELECT 
		conteudo.`token`,
		conteudo.`titulo`,
		conteudo.`alias`
	FROM
		`yt_conteudo_mm` AS conteudo INNER JOIN
		`yt_usuario_mm` AS usuario ON usuario.id_usuario = conteudo.id_usuario
	WHERE
		conteudo.tipo = 'Y' AND 
		usuario.`saldo_feijoes` > 0
	ORDER BY 
		usuario.`saldo_feijoes`,
		usuario.`nivel` ASC,
		usuario.`experiencia_nivel`,
		conteudo.hits DESC
	LIMIT 1 )
UNION ALL
(SELECT 
		conteudo.`token`,
		conteudo.`titulo`,
		conteudo.`alias`
	FROM
		`yt_conteudo_mm` AS conteudo INNER JOIN
		`yt_usuario_mm` AS usuario ON usuario.id_usuario = conteudo.id_usuario
	WHERE
		conteudo.tipo = 'Y' AND 
		usuario.`saldo_feijoes` > 0
	ORDER BY 
		usuario.`saldo_feijoes`,
		usuario.`nivel` DESC,
		usuario.`experiencia_nivel`,
		conteudo.hits ASC
	LIMIT 1 )
UNION ALL
( SELECT 
		conteudo.`token`,
		conteudo.`titulo`,
		conteudo.`alias`
	FROM
		`yt_conteudo_mm` AS conteudo INNER JOIN
		`yt_usuario_mm` AS usuario ON usuario.id_usuario = conteudo.id_usuario
	WHERE
		conteudo.tipo = 'Y' AND 
		usuario.`saldo_feijoes` > 0
	ORDER BY 
		usuario.`saldo_feijoes`,
		usuario.`nivel` DESC,
		usuario.`experiencia_nivel`,
		conteudo.hits DESC
	LIMIT 1)) AS TB
ORDER BY RAND();
		
		
CREATE OR REPLACE VIEW yt_HOME_CONTEUDO_LOGADO AS 
SELECT 
	`token`,
	`titulo`,
	`alias`,
	`id_usuario`,
	`ordem`
FROM 
	(
		SELECT 
			`token`,
			`titulo`,
			`alias`,
			`id_usuario`,
			ordem
		FROM
			( SELECT 
				conteudo.`token`,
				conteudo.`titulo`,
				conteudo.`alias`,
				usuario2.`id_usuario`,
				RAND() AS ordem
			FROM
				`yt_conteudo_mm` AS conteudo INNER JOIN
				`yt_usuario_mm` AS usuario ON usuario.id_usuario = conteudo.id_usuario INNER JOIN
				`yt_usu_assunto_princ_cont_mm` AS assunt_conteudo ON assunt_conteudo.id_conteudo = conteudo.id INNER JOIN 
				`yt_usu_assunto_interesse_mm` AS usuario2 ON usuario2.id_assunto  = assunt_conteudo.id_assunto
			ORDER BY
				conteudo.publish_up DESC
			) AS TB1
	) AS TB2;




-- -------------------------------------------------------------------- 
--
-- CARGA INICIAL
--
-- --------------------------------------------------------------------


LOCK TABLES `yt_content_types` WRITE;
/*!40000 ALTER TABLE `yt_content_types` DISABLE KEYS */;
	INSERT INTO `yt_content_types` (`type_title`,`type_alias`,`table`,`rules`,`field_mappings`,`router`,`content_history_options`) VALUES
('Mamaezona',
'com_mamaezona.conteudo',
'{"special":{"dbtable":"yt_conteudo_mm","key":"id","type":"Conteudo","prefix":"ConteudoTable","config":"array()"},"common":{"dbtable":"yt_ucm_content","key":"ucm_id","type":"Corecontent","prefix":"JTable","config":"array()"}}',
'',
'{"common":{"core_content_item_id":"id","core_title":"titulo","core_state":"state","core_alias":"alias","core_created_time":"created","core_modified_time":"modified","core_body":"descricao", "core_hits":"hits","core_publish_up":"publish_up","core_publish_down":"publish_down","core_access":"access", "core_params":"attribs", "core_featured":"null", "core_metadata":"metadata", "core_language":"language", "core_images":"images", "core_urls":"urls", "core_version":"version", "core_ordering":"ordering", "core_metakey":"metakey", "core_metadesc":"metadesc", "core_catid":"catid", "core_xreference":"xreference", "asset_id":"null"},"special":{"id_usuario":"id_usuario", "id_usuario":"id_usuario", "token":"token", "url":"url", "tipo":"tipo","created_by_ip":"created_by_ip","modified_by_ip":"modified_by_ip"}}',
 'MamaezonaHelperRoute::getConteudoRoute','');
/*!40000 ALTER TABLE `yt_content_types` ENABLE KEYS */;
UNLOCK TABLES;


LOCK TABLES `yt_grupo_assunto_conteudo_mm` WRITE;
/*!40000 ALTER TABLE `yt_grupo_assunto_conteudo_mm` DISABLE KEYS */;
INSERT INTO `yt_grupo_assunto_conteudo_mm` VALUES (1,'Informativo/Notícioso/Comentário/Opnativo'),(2,'Fictício/Cinemático/Televisivo');
/*!40000 ALTER TABLE `yt_grupo_assunto_conteudo_mm` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `yt_tipo_conteudo_mm` WRITE;
/*!40000 ALTER TABLE `yt_tipo_conteudo_mm` DISABLE KEYS */;
INSERT INTO `yt_tipo_conteudo_mm` (`id`, `titulo`, `token`, `descricao`, `created_by_ip`, `id_grupo_assunto`, 
`id_tipo_conteudo_pai`,  `alias`, `status`, `created`, `created_by`, `ordering`, `metakey`, `metadesc`) VALUES 
   (1,'Informativo',UUID(),'Conteúdo com o objetivo de passar informações','127.0.0.1',1,null,'informativo',1,NOW(),@USUARIO_ID,1,'',''),
   (2,'Educação',UUID(),'Conteúdo com de educar','127.0.0.1',1,null,'educacao',1,NOW(),@USUARIO_ID,2,'',''),
   (3,'Entreteimento',UUID(),'Conteúdo com o objetivo de entreter','127.0.0.1',2,null,'entretenimento',1,NOW(),@USUARIO_ID,3,'',''),
   (4,'Notícias',UUID(),'Notícias informações','127.0.0.1',1,1,'noticias',1,NOW(),@USUARIO_ID,4,'',''),
   (5,'Fakenews',UUID(),'Notícias informações','127.0.0.1',1,1,'noticias',1,NOW(),@USUARIO_ID,5,'',''),
   (6,'Treinamentos',UUID(),'Treinamentos','127.0.0.1',1,2,'treinamnto',1,NOW(),@USUARIO_ID,6,'',''),
   (7,'Tutoriais',UUID(),'Tutoriais','127.0.0.1',1,2,'tutoriais',1,NOW(),@USUARIO_ID,7,'',''), 
   (8,'Cursos',UUID(),'Cursos','127.0.0.1',1,2,'cursos',1,NOW(),@USUARIO_ID,8,'',''),    
   (9,'DIY',UUID(),'DIY (Do it your-uself) Faça isso vocês mesmo','127.0.0.1',1,2,'diy',1,NOW(),@USUARIO_ID,9,'',''),  
   (10,'Pegadinha',UUID(),'Pegadinhas e trotes','127.0.0.1',2,2,'pegadinha-trote',1,NOW(),@USUARIO_ID,10,'',''),  
   (11,'Esquetes',UUID(),'Esquetes','127.0.0.1',2,2,'esquetes',1,NOW(),@USUARIO_ID,10,'',''),  
   (12,'Filmes',UUID(),'Filmes de média e longa metragem','127.0.0.1',2,2,'filmes',1,NOW(),@USUARIO_ID,10,'',''),  
   (13,'Curta Metragem',UUID(),'Filmes de curta metragem','127.0.0.1',2,2,'curtametragem',1,NOW(),@USUARIO_ID,10,'',''),  
   (14,'Show ou prgrama de TV',UUID(),'Filmes de curta metragem','127.0.0.1',2,2,'curtametragem',1,NOW(),@USUARIO_ID,10,'',''),  
   (15,'Blog/VídeoLog',UUID(),'Blog e vídeo blog','127.0.0.1',1,null,'blog-vblog',1,NOW(),@USUARIO_ID,10,'','');
/*!40000 ALTER TABLE `yt_tipo_conteudo_mm` ENABLE KEYS */;
UNLOCK TABLES;                          

LOCK TABLES `yt_assunto_conteudo_mm` WRITE;
/*!40000 ALTER TABLE `yt_assunto_conteudo_mm` DISABLE KEYS */;             
INSERT INTO `yt_assunto_conteudo_mm` ( 
  `id`, `titulo`,  `token`,  `descricao`, `id_grupo_assunto`, `created_by_ip`,
  `alias`, `status`, `created`, `created_by`, `ordering`, `metakey`, `metadesc`)
VALUES 
   (1,'Youtube',UUID(),'Conteúdo sobre o youtube ou o universo youtuber', 1,'127.0.0.1','youtube', 1, NOW(), @USUARIO_ID, 1, '',''),
   (2,'Redes Sociais',UUID(),'Conteúdo sobre as redes sociais e o seu universo', 1,'127.0.0.1','redes-sociais', 1, NOW(), @USUARIO_ID, 2, '',''),
   (3,'Tecnologia',UUID(),'Conteúdo sobre tecnologia e o seu universo', 1,'127.0.0.1','tecnologia', 1, NOW(), @USUARIO_ID, 3, '',''),
   (4,'Internet',UUID(),'Conteúdo sobre a internet e o seu universo', 1,'127.0.0.1','internet', 1, NOW(), @USUARIO_ID, 4, '',''),
   (5,'Informática',UUID(),'Conteúdo sobre informática', 1,'127.0.0.1','informatica', 1, NOW(), @USUARIO_ID, 5, '',''),
   (6,'Saúde e bem estar',UUID(),'Conteúdo sobre informática', 1,'127.0.0.1','saude-bem-estar', 1, NOW(), @USUARIO_ID, 6, '',''),
   (7,'Medicia',UUID(),'Conteúdo sobre informática', 1,'127.0.0.1','medicina', 1, NOW(), @USUARIO_ID, 7, '',''),
   (8,'Fitness',UUID(),'Conteúdo sobre fitness', 1,'127.0.0.1','fitness', 1, NOW(), @USUARIO_ID, 8, '',''),
   (9,'Fotografia',UUID(),'Conteúdo sobre fotografia', 1,'127.0.0.1','fotografia', 1, NOW(), @USUARIO_ID, 9, '',''),
   (10,'Filmagem',UUID(),'Conteúdo sobre filmagem', 1,'127.0.0.1','filmagem', 1, NOW(), @USUARIO_ID, 10, '',''),
   (11,'Eletronica & Robotica',UUID(),'Conteúdo sobre eletronica e robotica', 1,'127.0.0.1','eletronica-robotica', 1, NOW(), @USUARIO_ID, 11, '',''),
   (12,'Economia',UUID(),'Conteúdo sobre economia e mercado financeiro', 1,'127.0.0.1','economia', 1, NOW(), @USUARIO_ID, 12, '',''),
   (13,'Mercado',UUID(),'Conteúdo sobre o mercado', 1,'127.0.0.1','mercado', 1, NOW(), @USUARIO_ID, 13, '',''),
   (14,'Marketing/Publicidade',UUID(),'Conteúdo sobre marketing e publicidade', 1,'127.0.0.1','marketing-publicidade', 1, NOW(), @USUARIO_ID, 14, '',''),
   (15,'Relacionamento',UUID(),'Conteúdo sobre relacionamento', 1,'127.0.0.1','relacionamento', 1, NOW(), @USUARIO_ID, 15, '',''),
   (16,'Segurança/Policial',UUID(),'Conteúdo sobre segurança casos policiais', 1,'127.0.0.1','seguranca', 1, NOW(), @USUARIO_ID, 16, '',''),
   (17,'Artezanato',UUID(),'Conteúdo sobre relacionamento', 1,'127.0.0.1','relacionamento', 1, NOW(), @USUARIO_ID, 17, '',''),
   (18,'Corte e costura',UUID(),'Conteúdo sobre cote e costura', 1,'127.0.0.1','corte-costura', 1, NOW(), @USUARIO_ID, 18, '',''),
   (19,'Politica',UUID(),'Conteúdo sobre politica', 1,'127.0.0.1','corte-costura', 1, NOW(), @USUARIO_ID, 19, '',''),
   (20,'Melhor Idade',UUID(),'Conteúdo sobre a pessoas chegam na melhor idade ou a fase da melhor idade', 1,'127.0.0.1','melhor-idade', 1, NOW(), @USUARIO_ID, 20, '',''),
   (21,'Jogos',UUID(),'Conteúdo sobre jogos', 1,'127.0.0.1','jogos', 1, NOW(), @USUARIO_ID, 21, '',''),
   (22,'Infantil',UUID(),'Conteúdo para crianças', 1,'127.0.0.1','jogos', 1, NOW(), @USUARIO_ID, 22, '',''),
   (23,'Opnião pessoal',UUID(),'Conteúdo sobre ponto de vísta', 1,'127.0.0.1','jogos', 1, NOW(), @USUARIO_ID, 23, '',''),
   (24,'Educação',UUID(),'Cursos, tutoriais, treinamento, dicas, workshops, palestras, EAD', 1,'127.0.0.1','educacao', 1, NOW(), @USUARIO_ID, 24, '',''),
   (25,'Colinária',UUID(),'Conteúdo sobre receitas, comida, cozinha, dicas', 1,'127.0.0.1','educacao', 1, NOW(), @USUARIO_ID, 25, '',''),
   (26,'Fisica',UUID(),'Conteúdo sobre fisica', 1,'127.0.0.1','fisica', 1, NOW(), @USUARIO_ID, 26, '',''),
   (27,'Quimica',UUID(),'Conteúdo sobre quimica', 1,'127.0.0.1','quimica', 1, NOW(), @USUARIO_ID, 27, '',''),
   (28,'Astronomia',UUID(),'Conteúdo sobre astronomia', 1,'127.0.0.1','astronomia', 1, NOW(), @USUARIO_ID, 28, '',''),
   (29,'Astrofisica',UUID(),'Conteúdo sobre astrofisica', 1,'127.0.0.1','astrofisica', 1, NOW(), @USUARIO_ID, 29, '',''),
   (30,'Mundo',UUID(),'Conteúdo sobre o mundo, costumes, culturas, geografia', 1,'127.0.0.1','astrofisica', 1, NOW(), @USUARIO_ID, 30, '',''),
   (31,'Natureza e meio ambiente',UUID(),'Conteúdo sobre a natureza, econologia e meio ambiente', 1,'127.0.0.1','natureza-meio-ambiente', 1, NOW(), @USUARIO_ID, 31, '',''),
   (32,'Urbanismo',UUID(),'Conteúdo sobre Urbanimos', 1,'127.0.0.1','urbanismo', 1, NOW(), @USUARIO_ID, 32, '',''),
   (33,'Engenharia',UUID(),'Conteúdo sobre Engenharia', 1,'127.0.0.1','engenharia', 1, NOW(), @USUARIO_ID, 33, '',''),
   (34,'Vegano e vegetariano',UUID(),'Conteúdo sobre veganismo e vegetarianismo', 1,'127.0.0.1','vegano-vegetariano', 1, NOW(), @USUARIO_ID, 34, '',''),
   (35,'Pets',UUID(),'Conteúdo sobre Pets (Animais de estimação)', 1,'127.0.0.1','pets-animais-estimacao', 1, NOW(), @USUARIO_ID, 35, '',''),
   (36,'Lifestyle',UUID(),'Conteúdo sobre lifestyle (Estilo de vida)', 1,'127.0.0.1','lifestyle', 1, NOW(), @USUARIO_ID, 36, '',''),
   (37,'Negocios ',UUID(),'Conteúdo sobre negocios', 1,'127.0.0.1','negcios', 1, NOW(), @USUARIO_ID, 37, '',''), 
   (38,'Empreendedorismo',UUID(),'Conteúdo empreendedorismo', 1,'127.0.0.1','empreendedorismo', 1, NOW(), @USUARIO_ID, 38, '',''),
   (39,'Sexo',UUID(),'Conteúdo sobre sexo', 1,'127.0.0.1','sexo', 1, NOW(), @USUARIO_ID, 39, '',''),
   (40,'Comportamento e sociedade',UUID(),'Conteúdo sobre comportamento e sociedade', 1,'127.0.0.1','comportamento', 1, NOW(), @USUARIO_ID, 40, '',''),
   (41,'Infancia e adolecencia',UUID(),'Conteúdo sobre infancia e adolecencia', 1,'127.0.0.1','infancia-adolecencia', 1, NOW(), @USUARIO_ID, 41, '',''),
   (42,'Dança',UUID(),'Conteúdo sobre dança, shows, dicas, passos, etc', 1,'127.0.0.1','danca', 1, NOW(), @USUARIO_ID, 42, '',''),
   (43,'Turismo/Viagens',UUID(),'Conteúdo sobre turismo, viagens, lugares', 1,'127.0.0.1','turismo', 1, NOW(), @USUARIO_ID, 43, '',''),
   (44,'Religião',UUID(),'Conteúdo sobre religião', 1,'127.0.0.1','turismo', 1, NOW(), @USUARIO_ID, 44, '',''),
   (45,'Decoração',UUID(),'Conteúdo sobre decoração', 1,'127.0.0.1','decoracao', 1, NOW(), @USUARIO_ID, 45, '',''), 
   (46,'Psicologa',UUID(),'Conteúdo sobre psicologia', 1,'127.0.0.1','psicologia', 1, NOW(), @USUARIO_ID, 46, '',''),  
   (47,'Música',UUID(),'Conteúdo sobre musica, shows, dicas, letras, instrumentos', 1,'127.0.0.1','musica', 1, NOW(), @USUARIO_ID, 47, '',''),   
   (48,'Romance',UUID(),'Genero de romance', 1,'127.0.0.1','romance', 2, NOW(), @USUARIO_ID, 1, '',''),
   (49,'Comêdia',UUID(),'Genero de comêdia', 1,'127.0.0.1','comedia', 2, NOW(), @USUARIO_ID, 2, '',''),
   (50,'Documentário',UUID(),'Genero de Documentário', 1,'127.0.0.1','documentario', 2, NOW(), @USUARIO_ID, 3, '',''),
   (51,'Drama',UUID(),'Genero de drama', 1,'127.0.0.1','drama', 2, NOW(), @USUARIO_ID, 4, '',''),
   (52,'Terror',UUID(),'Genero de terror', 1,'127.0.0.1','terror', 2, NOW(), @USUARIO_ID, 5, '',''),
   (53,'Suspence',UUID(),'Conteúdo sobre musica, shows, dicas, letras, ', 1,'127.0.0.1','suspence', 2, NOW(), @USUARIO_ID, 6, '','');
/*!40000 ALTER TABLE `yt_assunto_conteudo_mm` ENABLE KEYS */;
UNLOCK TABLES;               











/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-11-09  1:00:16
