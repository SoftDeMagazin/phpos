-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.41-0ubuntu0.14.04.1 - (Ubuntu)
-- Server OS:                    debian-linux-gnu
-- HeidiSQL Version:             9.1.0.4867
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;



-- Dumping structure for table retail.bonuri
CREATE TABLE IF NOT EXISTS `bonuri` (
  `bon_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `comanda_id` mediumint(8) DEFAULT NULL,
  `user_id` mediumint(8) DEFAULT NULL,
  `casa_id` mediumint(8) DEFAULT '0',
  `numar_bon` mediumint(8) DEFAULT '0',
  `data` date DEFAULT NULL,
  `data_ora` datetime DEFAULT NULL,
  `total` float DEFAULT NULL,
  `inchis` varchar(2) DEFAULT 'NU',
  `zi_economica_id` mediumint(8) DEFAULT '0',
  `discount` float DEFAULT '0',
  `avans` varchar(2) DEFAULT 'NU',
  `suma_avans` float DEFAULT '0',
  `achitat` varchar(2) DEFAULT 'DA',
  PRIMARY KEY (`bon_id`),
  KEY `user` (`user_id`,`bon_id`),
  KEY `casa` (`casa_id`,`bon_id`),
  KEY `zi_economica` (`zi_economica_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table retail.bonuri: ~0 rows (approximately)
/*!40000 ALTER TABLE `bonuri` DISABLE KEYS */;
/*!40000 ALTER TABLE `bonuri` ENABLE KEYS */;


-- Dumping structure for table retail.bonuri_consum
CREATE TABLE IF NOT EXISTS `bonuri_consum` (
  `bon_consum_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `numar_document` int(11) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `data_ora` datetime DEFAULT NULL,
  `user_id` mediumint(8) DEFAULT NULL,
  PRIMARY KEY (`bon_consum_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table retail.bonuri_consum: ~0 rows (approximately)
/*!40000 ALTER TABLE `bonuri_consum` DISABLE KEYS */;
/*!40000 ALTER TABLE `bonuri_consum` ENABLE KEYS */;


-- Dumping structure for table retail.bonuri_consum_continut
CREATE TABLE IF NOT EXISTS `bonuri_consum_continut` (
  `bon_consum_continut_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `bon_consum_id` mediumint(8) DEFAULT NULL,
  `produs_id` mediumint(8) DEFAULT NULL,
  `cantitate` float DEFAULT NULL,
  PRIMARY KEY (`bon_consum_continut_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table retail.bonuri_consum_continut: ~0 rows (approximately)
/*!40000 ALTER TABLE `bonuri_consum_continut` DISABLE KEYS */;
/*!40000 ALTER TABLE `bonuri_consum_continut` ENABLE KEYS */;


-- Dumping structure for table retail.bonuri_continut
CREATE TABLE IF NOT EXISTS `bonuri_continut` (
  `bon_continut_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `bon_id` mediumint(8) DEFAULT NULL,
  `produs_id` mediumint(8) DEFAULT NULL,
  `cantitate` float DEFAULT NULL,
  `valoare` float DEFAULT NULL,
  `discount` float(9,2) DEFAULT '0.00',
  PRIMARY KEY (`bon_continut_id`),
  KEY `bon_id` (`bon_id`),
  KEY `produs` (`produs_id`),
  CONSTRAINT `FK_bonuri_continut` FOREIGN KEY (`bon_id`) REFERENCES `bonuri` (`bon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table retail.bonuri_continut: ~0 rows (approximately)
/*!40000 ALTER TABLE `bonuri_continut` DISABLE KEYS */;
/*!40000 ALTER TABLE `bonuri_continut` ENABLE KEYS */;


-- Dumping structure for table retail.bonuri_plata
CREATE TABLE IF NOT EXISTS `bonuri_plata` (
  `bon_plata_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `bon_id` mediumint(8) DEFAULT NULL,
  `mod_plata_id` mediumint(8) DEFAULT NULL,
  `suma` float DEFAULT NULL,
  PRIMARY KEY (`bon_plata_id`),
  KEY `bon` (`bon_id`,`bon_plata_id`),
  KEY `mod_plata` (`mod_plata_id`,`bon_plata_id`),
  CONSTRAINT `FK_bonuri_plata` FOREIGN KEY (`bon_id`) REFERENCES `bonuri` (`bon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table retail.bonuri_plata: ~0 rows (approximately)
/*!40000 ALTER TABLE `bonuri_plata` DISABLE KEYS */;
/*!40000 ALTER TABLE `bonuri_plata` ENABLE KEYS */;


-- Dumping structure for table retail.bonuri_text
CREATE TABLE IF NOT EXISTS `bonuri_text` (
  `bon_text_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `text` text,
  `text_raspuns` text,
  PRIMARY KEY (`bon_text_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table retail.bonuri_text: ~0 rows (approximately)
/*!40000 ALTER TABLE `bonuri_text` DISABLE KEYS */;
/*!40000 ALTER TABLE `bonuri_text` ENABLE KEYS */;


-- Dumping structure for table retail.case_fiscale
CREATE TABLE IF NOT EXISTS `case_fiscale` (
  `casa_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `serie_fiscala` varchar(25) DEFAULT NULL,
  `id` varchar(2) DEFAULT NULL,
  `nume_casa` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`casa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table retail.case_fiscale: ~3 rows (approximately)
/*!40000 ALTER TABLE `case_fiscale` DISABLE KEYS */;
INSERT INTO `case_fiscale` (`casa_id`, `serie_fiscala`, `id`, `nume_casa`) VALUES
	(1, '000000', '1', 'CASA 1'),
	(2, '001100', '2', 'CASA 2'),
	(3, '001111', '3', 'CASA 3');
/*!40000 ALTER TABLE `case_fiscale` ENABLE KEYS */;


-- Dumping structure for table retail.categorii
CREATE TABLE IF NOT EXISTS `categorii` (
  `categorie_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `denumire_categorie` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`categorie_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Dumping data for table retail.categorii: ~6 rows (approximately)
/*!40000 ALTER TABLE `categorii` DISABLE KEYS */;
INSERT INTO `categorii` (`categorie_id`, `denumire_categorie`) VALUES
	(1, 'REGULAR'),
	(2, 'INFUSIONS'),
	(3, 'REFRESMENTS'),
	(4, 'SUMMER TALES'),
	(5, 'TOP'),
	(6, 'DECAF');
/*!40000 ALTER TABLE `categorii` ENABLE KEYS */;


-- Dumping structure for table retail.clienti
CREATE TABLE IF NOT EXISTS `clienti` (
  `client_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `denumire` varchar(255) DEFAULT NULL,
  `reg_com` varchar(255) DEFAULT NULL,
  `cif` varchar(255) DEFAULT NULL,
  `sediul` text,
  `judetul` varchar(255) DEFAULT NULL,
  `contul` varchar(255) DEFAULT NULL,
  `banca` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table retail.clienti: ~0 rows (approximately)
/*!40000 ALTER TABLE `clienti` DISABLE KEYS */;
/*!40000 ALTER TABLE `clienti` ENABLE KEYS */;


-- Dumping structure for table retail.comenzi
CREATE TABLE IF NOT EXISTS `comenzi` (
  `comanda_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `casa_id` mediumint(8) DEFAULT '0',
  `zi_economica_id` mediumint(8) DEFAULT '0',
  PRIMARY KEY (`comanda_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table retail.comenzi: ~3 rows (approximately)
/*!40000 ALTER TABLE `comenzi` DISABLE KEYS */;
INSERT INTO `comenzi` (`comanda_id`, `user_id`, `data`, `casa_id`, `zi_economica_id`) VALUES
	(1, 2, '2015-05-04 23:58:22', 1, 160),
	(2, 2, '2015-05-05 00:02:37', 1, 5),
	(3, 2, '2015-05-05 00:03:02', 1, 5);
/*!40000 ALTER TABLE `comenzi` ENABLE KEYS */;


-- Dumping structure for table retail.comenzi_continut
CREATE TABLE IF NOT EXISTS `comenzi_continut` (
  `comanda_continut_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `comanda_id` mediumint(8) DEFAULT NULL,
  `produs_id` mediumint(8) DEFAULT NULL,
  `cantitate` float DEFAULT NULL,
  `valoare` float DEFAULT NULL,
  `discount` float(9,2) DEFAULT '0.00',
  PRIMARY KEY (`comanda_continut_id`),
  KEY `comanda` (`comanda_id`,`comanda_continut_id`),
  KEY `produse` (`produs_id`,`comanda_continut_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table retail.comenzi_continut: ~0 rows (approximately)
/*!40000 ALTER TABLE `comenzi_continut` DISABLE KEYS */;
/*!40000 ALTER TABLE `comenzi_continut` ENABLE KEYS */;


-- Dumping structure for table retail.cotetva
CREATE TABLE IF NOT EXISTS `cotetva` (
  `cotatva_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `valoare` float DEFAULT NULL,
  `cod` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`cotatva_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table retail.cotetva: ~1 rows (approximately)
/*!40000 ALTER TABLE `cotetva` DISABLE KEYS */;
INSERT INTO `cotetva` (`cotatva_id`, `valoare`, `cod`) VALUES
	(1, 19, '1');
/*!40000 ALTER TABLE `cotetva` ENABLE KEYS */;


-- Dumping structure for table retail.doc_modificari_pret
CREATE TABLE IF NOT EXISTS `doc_modificari_pret` (
  `doc_modificare_pret_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `data` datetime DEFAULT NULL,
  `numar_document` int(11) DEFAULT NULL,
  `user_id` mediumint(8) DEFAULT NULL,
  PRIMARY KEY (`doc_modificare_pret_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table retail.doc_modificari_pret: ~4 rows (approximately)
/*!40000 ALTER TABLE `doc_modificari_pret` DISABLE KEYS */;
INSERT INTO `doc_modificari_pret` (`doc_modificare_pret_id`, `data`, `numar_document`, `user_id`) VALUES
	(1, '2012-12-24 17:05:59', 1, 8),
	(2, '2013-02-11 14:56:35', 2, 8),
	(3, '2013-02-11 15:00:00', 3, 8),
	(4, '2013-02-11 15:01:00', 4, 8);
/*!40000 ALTER TABLE `doc_modificari_pret` ENABLE KEYS */;


-- Dumping structure for table retail.drepturi
CREATE TABLE IF NOT EXISTS `drepturi` (
  `drept_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `nume_drept` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`drept_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Dumping data for table retail.drepturi: ~9 rows (approximately)
/*!40000 ALTER TABLE `drepturi` DISABLE KEYS */;
INSERT INTO `drepturi` (`drept_id`, `nume_drept`) VALUES
	(1, 'marcaj'),
	(2, 'configurari'),
	(3, 'configurari_produse'),
	(4, 'rapoarte'),
	(5, 'evidenta_bonuri'),
	(6, 'inchidere_zi'),
	(7, 'iesire_program'),
	(8, 'intrari'),
	(9, 'protocol');
/*!40000 ALTER TABLE `drepturi` ENABLE KEYS */;


-- Dumping structure for table retail.drepturi_users
CREATE TABLE IF NOT EXISTS `drepturi_users` (
  `drept_user_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `drept_id` mediumint(8) DEFAULT NULL,
  `user_id` mediumint(8) DEFAULT NULL,
  PRIMARY KEY (`drept_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=228 DEFAULT CHARSET=latin1;

-- Dumping data for table retail.drepturi_users: ~55 rows (approximately)
/*!40000 ALTER TABLE `drepturi_users` DISABLE KEYS */;
INSERT INTO `drepturi_users` (`drept_user_id`, `drept_id`, `user_id`) VALUES
	(59, 5, 1),
	(60, 2, 1),
	(61, 3, 1),
	(62, 4, 1),
	(63, 6, 1),
	(64, 7, 1),
	(65, 8, 1),
	(90, 1, 3),
	(91, 6, 3),
	(92, 7, 3),
	(96, 7, 6),
	(97, 6, 6),
	(98, 1, 6),
	(123, 7, 5),
	(124, 1, 5),
	(125, 6, 5),
	(135, 9, 8),
	(136, 8, 8),
	(137, 7, 8),
	(138, 6, 8),
	(139, 5, 8),
	(140, 4, 8),
	(141, 3, 8),
	(142, 2, 8),
	(143, 1, 8),
	(153, 9, 4),
	(154, 7, 4),
	(155, 1, 4),
	(156, 6, 4),
	(157, 2, 4),
	(158, 3, 4),
	(159, 4, 4),
	(160, 5, 4),
	(161, 8, 4),
	(207, 8, 0),
	(208, 7, 0),
	(209, 6, 0),
	(210, 5, 0),
	(211, 4, 0),
	(212, 3, 0),
	(213, 2, 0),
	(214, 1, 0),
	(215, 9, 0),
	(216, 9, 2),
	(217, 7, 2),
	(218, 6, 2),
	(219, 5, 2),
	(220, 4, 2),
	(221, 3, 2),
	(222, 2, 2),
	(223, 1, 2),
	(224, 8, 2),
	(225, 7, 9),
	(226, 6, 9),
	(227, 1, 9);
/*!40000 ALTER TABLE `drepturi_users` ENABLE KEYS */;


-- Dumping structure for table retail.etichete
CREATE TABLE IF NOT EXISTS `etichete` (
  `eticheta_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `data` datetime DEFAULT NULL,
  `stanga` float DEFAULT '10',
  `dreapta` float DEFAULT '10',
  `sus` float DEFAULT '10',
  `jos` float DEFAULT '10',
  `numar_coloane` int(11) DEFAULT '10',
  `inaltime_eticheta` float DEFAULT NULL,
  PRIMARY KEY (`eticheta_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table retail.etichete: ~0 rows (approximately)
/*!40000 ALTER TABLE `etichete` DISABLE KEYS */;
/*!40000 ALTER TABLE `etichete` ENABLE KEYS */;


-- Dumping structure for table retail.etichete_continut
CREATE TABLE IF NOT EXISTS `etichete_continut` (
  `eticheta_continut_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `eticheta_id` mediumint(8) DEFAULT NULL,
  `produs_id` mediumint(8) DEFAULT NULL,
  `denumire` varchar(255) DEFAULT NULL,
  `cod` varchar(50) DEFAULT NULL,
  `pret` float DEFAULT NULL,
  `numar_etichete` int(8) DEFAULT NULL,
  PRIMARY KEY (`eticheta_continut_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table retail.etichete_continut: ~0 rows (approximately)
/*!40000 ALTER TABLE `etichete_continut` DISABLE KEYS */;
/*!40000 ALTER TABLE `etichete_continut` ENABLE KEYS */;


-- Dumping structure for table retail.facturi
CREATE TABLE IF NOT EXISTS `facturi` (
  `factura_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `serie` varchar(10) DEFAULT NULL,
  `numar` int(11) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `data_emitere` datetime DEFAULT NULL,
  `facturier_id` mediumint(8) DEFAULT NULL,
  `client_id` mediumint(8) DEFAULT NULL,
  `bon_id` mediumint(8) DEFAULT NULL,
  PRIMARY KEY (`factura_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table retail.facturi: ~0 rows (approximately)
/*!40000 ALTER TABLE `facturi` DISABLE KEYS */;
/*!40000 ALTER TABLE `facturi` ENABLE KEYS */;


-- Dumping structure for table retail.facturiere
CREATE TABLE IF NOT EXISTS `facturiere` (
  `facturier_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `serie` varchar(5) DEFAULT NULL,
  `start` int(11) DEFAULT NULL,
  `stop` int(11) DEFAULT NULL,
  `curent` int(11) DEFAULT NULL,
  `inchis` varchar(2) DEFAULT 'NU',
  PRIMARY KEY (`facturier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table retail.facturiere: ~0 rows (approximately)
/*!40000 ALTER TABLE `facturiere` DISABLE KEYS */;
/*!40000 ALTER TABLE `facturiere` ENABLE KEYS */;


-- Dumping structure for table retail.furnizori
CREATE TABLE IF NOT EXISTS `furnizori` (
  `furnizor_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `nume` varchar(100) DEFAULT NULL,
  `adresa` varchar(255) DEFAULT NULL,
  `telefon` varchar(25) DEFAULT NULL,
  `cod_fiscal` varchar(25) DEFAULT NULL,
  `cont` varchar(50) DEFAULT NULL,
  `banca` varchar(50) DEFAULT NULL,
  `cod` varchar(50) DEFAULT NULL,
  `observatii` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`furnizor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table retail.furnizori: ~0 rows (approximately)
/*!40000 ALTER TABLE `furnizori` DISABLE KEYS */;
/*!40000 ALTER TABLE `furnizori` ENABLE KEYS */;


-- Dumping structure for table retail.iesiri
CREATE TABLE IF NOT EXISTS `iesiri` (
  `iesire_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `bon_continut_id` mediumint(8) DEFAULT NULL,
  `intrare_continut_id` mediumint(8) DEFAULT NULL,
  `cantitate` float DEFAULT NULL,
  `tip` varchar(50) DEFAULT 'vanzare',
  PRIMARY KEY (`iesire_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table retail.iesiri: ~0 rows (approximately)
/*!40000 ALTER TABLE `iesiri` DISABLE KEYS */;
/*!40000 ALTER TABLE `iesiri` ENABLE KEYS */;


-- Dumping structure for view retail.iesiri_bon_consum
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `iesiri_bon_consum` (
	`iesire_id` MEDIUMINT(8) NOT NULL,
	`bon_continut_id` MEDIUMINT(8) NULL,
	`bon_consum_id` MEDIUMINT(8) NULL,
	`produs_id` MEDIUMINT(8) NOT NULL,
	`denumire` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`pret_intrare` FLOAT NULL,
	`pret_vanzare` FLOAT NULL,
	`cantitate` FLOAT NULL,
	`data` DATE NULL,
	`tip` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;


-- Dumping structure for view retail.iesiri_group
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `iesiri_group` (
	`produs_id` MEDIUMINT(9) NOT NULL,
	`denumire` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`pret_intrare` FLOAT NULL,
	`pret_vanzare` DOUBLE NULL,
	`cantitate` DOUBLE NULL,
	`data` DATE NULL
) ENGINE=MyISAM;


-- Dumping structure for view retail.iesiri_materii
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `iesiri_materii` (
	`iesire_id` MEDIUMINT(8) NOT NULL,
	`bon_continut_id` MEDIUMINT(8) NOT NULL,
	`produs_id` MEDIUMINT(8) NOT NULL,
	`denumire` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`pret_intrare` FLOAT NULL,
	`pret_vanzare` DOUBLE NULL,
	`cantitate` FLOAT NULL,
	`data` DATE NULL,
	`tip` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;


-- Dumping structure for view retail.iesiri_materii_prime
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `iesiri_materii_prime` (
	`bon_continut_id` MEDIUMINT(8) NULL,
	`produs_id` MEDIUMINT(8) NOT NULL,
	`denumire` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`cantitate_vanduta` FLOAT NULL,
	`valoare` FLOAT NULL,
	`componenta_id` MEDIUMINT(8) NULL,
	`denumire_componenta` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`pret_intrare` FLOAT NULL,
	`cantitate_iesire` FLOAT NULL
) ENGINE=MyISAM;


-- Dumping structure for view retail.iesiri_retete
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `iesiri_retete` (
	`bon_continut_id` MEDIUMINT(8) NULL,
	`produs_id` MEDIUMINT(8) NOT NULL,
	`denumire` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`pret_intrare` DOUBLE NULL,
	`pret_vanzare` FLOAT NULL,
	`cantitate` FLOAT NULL,
	`data` DATE NULL,
	`tip` VARCHAR(14) NOT NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;


-- Dumping structure for view retail.iesiri_union
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `iesiri_union` (
	`iesire_id` BIGINT(20) NOT NULL,
	`bon_continut_id` MEDIUMINT(9) NULL,
	`produs_id` MEDIUMINT(9) NOT NULL,
	`denumire` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`pret_intrare` FLOAT NULL,
	`pret_vanzare` DOUBLE NULL,
	`cantitate` FLOAT NULL,
	`data` DATE NULL,
	`tip` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;


-- Dumping structure for view retail.iesiri_vanzari
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `iesiri_vanzari` (
	`iesire_id` MEDIUMINT(8) NOT NULL,
	`bon_continut_id` MEDIUMINT(8) NULL,
	`produs_id` MEDIUMINT(8) NOT NULL,
	`denumire` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`pret_intrare` FLOAT NULL,
	`pret_vanzare` FLOAT NULL,
	`cantitate` FLOAT NULL,
	`data` DATE NULL,
	`tip` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;


-- Dumping structure for table retail.intrari
CREATE TABLE IF NOT EXISTS `intrari` (
  `intrare_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `nr_document` varchar(15) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `ora` time DEFAULT NULL,
  `user_id` mediumint(8) DEFAULT NULL,
  `zi_economica_id` mediumint(8) DEFAULT NULL,
  `activ` int(1) DEFAULT '0',
  PRIMARY KEY (`intrare_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table retail.intrari: ~0 rows (approximately)
/*!40000 ALTER TABLE `intrari` DISABLE KEYS */;
/*!40000 ALTER TABLE `intrari` ENABLE KEYS */;


-- Dumping structure for table retail.intrari_continut
CREATE TABLE IF NOT EXISTS `intrari_continut` (
  `intrare_continut_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `nir_id` mediumint(8) DEFAULT NULL,
  `nir_componenta_id` mediumint(8) DEFAULT NULL,
  `produs_id` mediumint(8) DEFAULT NULL,
  `cantitate` float DEFAULT NULL,
  `cantitate_ramasa` float DEFAULT NULL,
  `pret_intrare` float DEFAULT NULL,
  `data` date DEFAULT NULL,
  `pret_vanzare` float DEFAULT NULL,
  `adaos_unit` float DEFAULT '0',
  `data_expirare` date DEFAULT '1900-01-01',
  `activ` int(1) DEFAULT '0',
  `tip` varchar(255) DEFAULT 'nir',
  PRIMARY KEY (`intrare_continut_id`),
  KEY `produs` (`produs_id`,`intrare_continut_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table retail.intrari_continut: ~0 rows (approximately)
/*!40000 ALTER TABLE `intrari_continut` DISABLE KEYS */;
/*!40000 ALTER TABLE `intrari_continut` ENABLE KEYS */;


-- Dumping structure for view retail.intrari_group
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `intrari_group` (
	`produs_id` MEDIUMINT(8) NOT NULL,
	`denumire` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`cantitate` DOUBLE NULL,
	`pret_intrare` FLOAT NULL,
	`pret_vanzare` FLOAT NULL,
	`data` DATE NULL
) ENGINE=MyISAM;


-- Dumping structure for view retail.intrari_iesiri
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `intrari_iesiri` (
	`produs_id` MEDIUMINT(9) NOT NULL,
	`denumire` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`pret_intrare` FLOAT NULL,
	`pret_vanzare` DOUBLE NULL,
	`cantitate` DOUBLE NULL,
	`data` DATE NULL,
	`tip` VARCHAR(1) NOT NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;


-- Dumping structure for table retail.inventar
CREATE TABLE IF NOT EXISTS `inventar` (
  `inventar_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `numar_inventar` int(8) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `user_id` mediumint(8) DEFAULT NULL,
  `calculat` varchar(2) DEFAULT 'NU',
  PRIMARY KEY (`inventar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table retail.inventar: ~0 rows (approximately)
/*!40000 ALTER TABLE `inventar` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventar` ENABLE KEYS */;


-- Dumping structure for table retail.inventar_continut
CREATE TABLE IF NOT EXISTS `inventar_continut` (
  `inventar_continut_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `inventar_id` mediumint(8) DEFAULT NULL,
  `produs_id` mediumint(8) DEFAULT NULL,
  `stoc_scriptic` float DEFAULT NULL,
  `stoc_faptic` float DEFAULT NULL,
  PRIMARY KEY (`inventar_continut_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table retail.inventar_continut: ~0 rows (approximately)
/*!40000 ALTER TABLE `inventar_continut` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventar_continut` ENABLE KEYS */;


-- Dumping structure for table retail.modificari_pret
CREATE TABLE IF NOT EXISTS `modificari_pret` (
  `modificare_pret_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `doc_modificare_pret_id` mediumint(8) DEFAULT '0',
  `produs_id` mediumint(8) DEFAULT NULL,
  `pret_vechi` float DEFAULT NULL,
  `pret_nou` float DEFAULT NULL,
  `data_modificare` datetime DEFAULT NULL,
  `user_id` mediumint(9) DEFAULT NULL,
  `stoc` float DEFAULT NULL,
  `activat` varchar(2) DEFAULT 'NU',
  PRIMARY KEY (`modificare_pret_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table retail.modificari_pret: ~0 rows (approximately)
/*!40000 ALTER TABLE `modificari_pret` DISABLE KEYS */;
/*!40000 ALTER TABLE `modificari_pret` ENABLE KEYS */;


-- Dumping structure for table retail.moduri_plata
CREATE TABLE IF NOT EXISTS `moduri_plata` (
  `mod_plata_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `nume_mod` varchar(255) DEFAULT NULL,
  `fiscal` varchar(2) DEFAULT 'DA',
  `final_fiscal` varchar(20) DEFAULT NULL,
  `cash` varchar(2) DEFAULT 'DA',
  PRIMARY KEY (`mod_plata_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table retail.moduri_plata: ~1 rows (approximately)
/*!40000 ALTER TABLE `moduri_plata` DISABLE KEYS */;
INSERT INTO `moduri_plata` (`mod_plata_id`, `nume_mod`, `fiscal`, `final_fiscal`, `cash`) VALUES
	(1, 'NUMERAR', 'DA', '0', 'DA');
/*!40000 ALTER TABLE `moduri_plata` ENABLE KEYS */;


-- Dumping structure for table retail.niruri
CREATE TABLE IF NOT EXISTS `niruri` (
  `nir_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `numar_nir` int(11) DEFAULT '0',
  `furnizor_id` mediumint(8) DEFAULT NULL,
  `numar_factura` varchar(50) DEFAULT NULL,
  `data_factura` date DEFAULT NULL,
  `data_scadenta` date DEFAULT NULL,
  `total_factura` float DEFAULT NULL,
  `total_tva` float DEFAULT NULL,
  `total_fara_tva` float DEFAULT NULL,
  `achitat` varchar(2) DEFAULT 'NU',
  `data_adaugare` datetime DEFAULT NULL,
  `user_id` mediumint(8) DEFAULT NULL,
  `tip_nir` varchar(25) DEFAULT 'marfa',
  PRIMARY KEY (`nir_id`),
  KEY `niruri_ibfk_2` (`furnizor_id`),
  KEY `niruri_ibfk_1` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table retail.niruri: ~0 rows (approximately)
/*!40000 ALTER TABLE `niruri` DISABLE KEYS */;
/*!40000 ALTER TABLE `niruri` ENABLE KEYS */;


-- Dumping structure for table retail.niruri_componente
CREATE TABLE IF NOT EXISTS `niruri_componente` (
  `nir_componenta_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `nir_id` mediumint(8) DEFAULT NULL,
  `produs_id` mediumint(8) DEFAULT NULL,
  `denumire` varchar(255) DEFAULT NULL,
  `unitate_masura_id` mediumint(8) DEFAULT NULL,
  `um` varchar(255) DEFAULT NULL,
  `cant` float DEFAULT NULL,
  `pret_ach` float DEFAULT NULL,
  `val_ach` float DEFAULT NULL,
  `tva_ach` float DEFAULT NULL,
  `total_tva_ach` float DEFAULT NULL,
  `adaos_unit` float DEFAULT NULL,
  `total_adaos` float DEFAULT NULL,
  `tva_vanzare` float DEFAULT NULL,
  `total_tva_vanzare` float DEFAULT NULL,
  `pret_vanzare` float DEFAULT NULL,
  `val_total` float DEFAULT NULL,
  PRIMARY KEY (`nir_componenta_id`),
  KEY `niruri_componente_ibfk_3` (`unitate_masura_id`),
  KEY `niruri_componente_ibfk_1` (`nir_id`),
  KEY `niruri_componente_ibfk_2` (`produs_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table retail.niruri_componente: ~0 rows (approximately)
/*!40000 ALTER TABLE `niruri_componente` DISABLE KEYS */;
/*!40000 ALTER TABLE `niruri_componente` ENABLE KEYS */;


-- Dumping structure for view retail.preturi_ach_retete
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `preturi_ach_retete` (
	`bon_continut_id` MEDIUMINT(8) NULL,
	`pret_ach` DOUBLE NULL
) ENGINE=MyISAM;


-- Dumping structure for table retail.produse
CREATE TABLE IF NOT EXISTS `produse` (
  `produs_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `categorie_id` mediumint(8) DEFAULT NULL,
  `cod` varchar(50) DEFAULT NULL,
  `denumire` varchar(50) DEFAULT NULL,
  `denumire2` varchar(50) DEFAULT NULL,
  `cod_bare` varchar(255) DEFAULT NULL,
  `pret` float DEFAULT NULL,
  `cotatva_id` mediumint(8) DEFAULT '0',
  `la_vanzare` varchar(2) DEFAULT 'DA',
  `tip_produs` varchar(25) DEFAULT 'marfa',
  PRIMARY KEY (`produs_id`),
  KEY `cod_bare` (`cod_bare`),
  KEY `denumire` (`denumire`),
  KEY `categ` (`categorie_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- Dumping data for table retail.produse: ~12 rows (approximately)
/*!40000 ALTER TABLE `produse` DISABLE KEYS */;
INSERT INTO `produse` (`produs_id`, `categorie_id`, `cod`, `denumire`, `denumire2`, `cod_bare`, `pret`, `cotatva_id`, `la_vanzare`, `tip_produs`) VALUES
	(1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'DA', 'marfa'),
	(2, 1, '1000', 'LATTE', NULL, '1000', 7, 0, 'DA', 'marfa'),
	(3, 1, '1001', 'ESPRESSO', NULL, '1001', 4, 0, 'DA', 'marfa'),
	(5, 1, '1002', 'AMERICANO', NULL, '1002', 4, 0, 'DA', 'marfa'),
	(6, 1, '1003', 'DOUBLE SHOT', NULL, '1003', 6, 0, 'DA', 'marfa'),
	(7, 1, '1004', 'MACCHIATO', NULL, '1004', 5, 0, 'DA', 'marfa'),
	(8, 1, '1005', 'CLASIC CAPPUCCINO', NULL, '1005', 6, 0, 'DA', 'marfa'),
	(9, 1, '1006', 'VIENNESE ', NULL, '1006', 6, 0, 'DA', 'marfa'),
	(10, 1, '1007', 'MAROCCHINO', NULL, '1007', 7, 0, 'DA', 'marfa'),
	(11, 1, '1008', 'CARAMEL CAPPUCCINO', NULL, '1008', 7, 0, 'DA', 'marfa'),
	(12, 1, '1009', 'VANILLA ESPERSSO', NULL, '1009', 5, 0, 'DA', 'marfa'),
	(13, 1, '1010', 'CHOCOLATE', NULL, '1010', 8, 0, 'DA', 'marfa');
/*!40000 ALTER TABLE `produse` ENABLE KEYS */;


-- Dumping structure for table retail.registru_casa
CREATE TABLE IF NOT EXISTS `registru_casa` (
  `inregistrare_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `document_id` mediumint(8) DEFAULT '0',
  `suma` float DEFAULT NULL,
  `numar_document` varchar(50) DEFAULT NULL,
  `explicatie_document` varchar(255) DEFAULT NULL,
  `data_document` date DEFAULT NULL,
  `data_adaugare` datetime DEFAULT NULL,
  `tip_document` varchar(50) DEFAULT NULL,
  `tip` varchar(50) DEFAULT NULL,
  `tip_inregistrare` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`inregistrare_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table retail.registru_casa: ~1 rows (approximately)
/*!40000 ALTER TABLE `registru_casa` DISABLE KEYS */;
INSERT INTO `registru_casa` (`inregistrare_id`, `document_id`, `suma`, `numar_document`, `explicatie_document`, `data_document`, `data_adaugare`, `tip_document`, `tip`, `tip_inregistrare`) VALUES
	(1, 1, 977.5, 'bon fiscal', NULL, '2012-11-07', '2012-11-07 15:42:47', 'Chitanta', 'plata', 'factura_furnizor');
/*!40000 ALTER TABLE `registru_casa` ENABLE KEYS */;


-- Dumping structure for table retail.retetar
CREATE TABLE IF NOT EXISTS `retetar` (
  `retetar_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `produs_id` mediumint(8) DEFAULT NULL,
  `componenta_id` mediumint(8) DEFAULT NULL,
  `cantitate` float DEFAULT NULL,
  PRIMARY KEY (`retetar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table retail.retetar: ~0 rows (approximately)
/*!40000 ALTER TABLE `retetar` DISABLE KEYS */;
/*!40000 ALTER TABLE `retetar` ENABLE KEYS */;


-- Dumping structure for view retail.retete_calcul
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `retete_calcul` (
	`bon_continut_id` MEDIUMINT(8) NULL,
	`suma` DOUBLE NULL,
	`cant_ies` DOUBLE NULL
) ENGINE=MyISAM;


-- Dumping structure for table retail.retururi
CREATE TABLE IF NOT EXISTS `retururi` (
  `retur_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `produs_id` mediumint(8) DEFAULT NULL,
  `utilizator_id` mediumint(8) DEFAULT NULL,
  `cantitate` float DEFAULT NULL,
  `valoare` float DEFAULT NULL,
  `data` date DEFAULT NULL,
  `ora` time DEFAULT NULL,
  PRIMARY KEY (`retur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table retail.retururi: ~0 rows (approximately)
/*!40000 ALTER TABLE `retururi` DISABLE KEYS */;
/*!40000 ALTER TABLE `retururi` ENABLE KEYS */;


-- Dumping structure for view retail.rpt_bonuri_emise
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `rpt_bonuri_emise` (
	`bonuri_emise` BIGINT(21) NOT NULL,
	`total` DOUBLE NULL,
	`zi_economica_id` MEDIUMINT(8) NOT NULL,
	`data` DATE NULL,
	`casa_id` MEDIUMINT(8) NOT NULL,
	`nume_casa` VARCHAR(25) NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;


-- Dumping structure for view retail.rpt_moduri_case
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `rpt_moduri_case` (
	`casa_id` MEDIUMINT(8) NULL,
	`nume_casa` VARCHAR(25) NULL COLLATE 'latin1_swedish_ci',
	`nume_mod` VARCHAR(255) NULL COLLATE 'latin1_swedish_ci',
	`suma` DOUBLE NULL,
	`mod_plata_id` MEDIUMINT(8) NOT NULL,
	`zi_economica_id` MEDIUMINT(8) NOT NULL,
	`data` DATE NULL
) ENGINE=MyISAM;


-- Dumping structure for view retail.rpt_moduri_plata
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `rpt_moduri_plata` (
	`nume_mod` VARCHAR(255) NULL COLLATE 'latin1_swedish_ci',
	`total_suma` DOUBLE NULL,
	`zi_economica_id` MEDIUMINT(8) NOT NULL,
	`data` DATE NULL
) ENGINE=MyISAM;


-- Dumping structure for view retail.rpt_utilizatori_moduri
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `rpt_utilizatori_moduri` (
	`user_id` MEDIUMINT(8) NULL,
	`nume` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`nume_mod` VARCHAR(255) NULL COLLATE 'latin1_swedish_ci',
	`suma` DOUBLE NULL,
	`mod_plata_id` MEDIUMINT(8) NOT NULL,
	`zi_economica_id` MEDIUMINT(8) NOT NULL,
	`data` DATE NULL
) ENGINE=MyISAM;


-- Dumping structure for view retail.rpt_vanzari
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `rpt_vanzari` (
	`denumire` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`denumire_categorie` VARCHAR(255) NULL COLLATE 'latin1_swedish_ci',
	`cantitate_vanduta` DOUBLE NULL,
	`valoare_vanduta` DOUBLE NULL,
	`zi_economica_id` MEDIUMINT(8) NOT NULL,
	`data` DATE NULL
) ENGINE=MyISAM;


-- Dumping structure for view retail.test
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `test` (
	`denumire` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`nir_id` MEDIUMINT(8) NULL,
	`pret` FLOAT NULL,
	`pret_vanzare` FLOAT NULL,
	`pret_intrare` FLOAT NULL
) ENGINE=MyISAM;


-- Dumping structure for table retail.transformari
CREATE TABLE IF NOT EXISTS `transformari` (
  `transformare_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `sursa_denumire` varchar(255) DEFAULT NULL,
  `sursa_produs_id` mediumint(8) DEFAULT NULL,
  `sursa_pret` float DEFAULT NULL,
  `sursa_cantitate` float DEFAULT NULL,
  `destinatie_denumire` varchar(255) DEFAULT NULL,
  `destinatie_produs_id` mediumint(8) DEFAULT NULL,
  `destinatie_pret` float DEFAULT NULL,
  `destinatie_cantitate` float DEFAULT NULL,
  `data_transformare` date DEFAULT NULL,
  `user_id` mediumint(8) DEFAULT NULL,
  PRIMARY KEY (`transformare_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table retail.transformari: ~0 rows (approximately)
/*!40000 ALTER TABLE `transformari` DISABLE KEYS */;
/*!40000 ALTER TABLE `transformari` ENABLE KEYS */;


-- Dumping structure for view retail.union
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `union` (
	`iesire_id` MEDIUMINT(9) NOT NULL,
	`bon_continut_id` MEDIUMINT(9) NULL,
	`produs_id` MEDIUMINT(9) NOT NULL,
	`denumire` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`pret_intrare` FLOAT NULL,
	`pret_vanzare` FLOAT NULL,
	`cantitate` FLOAT NULL,
	`tip` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;


-- Dumping structure for table retail.unitati_masura
CREATE TABLE IF NOT EXISTS `unitati_masura` (
  `unitate_masura_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `unitate_masura` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`unitate_masura_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Dumping data for table retail.unitati_masura: ~6 rows (approximately)
/*!40000 ALTER TABLE `unitati_masura` DISABLE KEYS */;
INSERT INTO `unitati_masura` (`unitate_masura_id`, `unitate_masura`) VALUES
	(1, 'kg'),
	(2, 'litru'),
	(3, 'buc'),
	(4, 'ML'),
	(5, 'COLI'),
	(6, 'FOLII');
/*!40000 ALTER TABLE `unitati_masura` ENABLE KEYS */;


-- Dumping structure for table retail.users
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `parola` varchar(20) DEFAULT NULL,
  `nume` varchar(50) DEFAULT NULL,
  `activ` varchar(2) DEFAULT 'DA',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Dumping data for table retail.users: ~4 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`user_id`, `parola`, `nume`, `activ`) VALUES
	(2, '7487638', 'ADMIN', 'DA'),
	(3, '1234', 'CASIER1', 'DA'),
	(8, '1771', 'BOSS', 'DA'),
	(9, '1234', 'CASIER2', 'DA');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;


-- Dumping structure for view retail.valoare_stoc
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `valoare_stoc` (
	`produs_id` MEDIUMINT(8) NOT NULL,
	`denumire` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`stoc` DOUBLE NULL,
	`pret` FLOAT NULL,
	`valoare_stoc` DOUBLE NULL,
	`categorie_id` MEDIUMINT(8) NULL,
	`denumire_categorie` VARCHAR(255) NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;


-- Dumping structure for view retail.view_bonuri_continut
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `view_bonuri_continut` (
	`bon_continut_id` MEDIUMINT(8) NOT NULL,
	`bon_id` MEDIUMINT(8) NULL,
	`produs_id` MEDIUMINT(8) NULL,
	`cantitate` FLOAT NULL,
	`valoare` FLOAT NULL,
	`denumire` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;


-- Dumping structure for view retail.view_bonuri_moduri
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `view_bonuri_moduri` (
	`data_ora` DATETIME NULL,
	`bon_id` MEDIUMINT(8) NOT NULL,
	`numar_bon` MEDIUMINT(8) NULL,
	`total` FLOAT NULL,
	`suma` FLOAT NULL,
	`casa_id` MEDIUMINT(8) NOT NULL,
	`nume_casa` VARCHAR(25) NULL COLLATE 'latin1_swedish_ci',
	`user_id` MEDIUMINT(8) NOT NULL,
	`nume` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`mod_plata_id` MEDIUMINT(8) NOT NULL,
	`nume_mod` VARCHAR(255) NULL COLLATE 'latin1_swedish_ci',
	`cash` VARCHAR(2) NULL COLLATE 'latin1_swedish_ci',
	`data` DATE NULL
) ENGINE=MyISAM;


-- Dumping structure for view retail.view_comenzi_continut
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `view_comenzi_continut` (
	`comanda_continut_id` MEDIUMINT(8) NOT NULL,
	`comanda_id` MEDIUMINT(8) NULL,
	`cantitate` FLOAT NULL,
	`valoare` FLOAT NULL,
	`discount` FLOAT(9,2) NULL,
	`denumire` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;


-- Dumping structure for view retail.view_iesiri_vanzari
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `view_iesiri_vanzari` (
	`iesire_id` MEDIUMINT(8) NOT NULL,
	`bon_continut_id` MEDIUMINT(8) NULL,
	`cantitate` FLOAT NULL,
	`valoare` FLOAT NULL,
	`tip` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`numar_bon` MEDIUMINT(8) NULL,
	`intrare_continut_id` MEDIUMINT(8) NOT NULL,
	`data` DATE NULL,
	`nume` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;


-- Dumping structure for view retail.view_intrari_continut
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `view_intrari_continut` (
	`intrare_continut_id` MEDIUMINT(8) NOT NULL,
	`tip` VARCHAR(255) NULL COLLATE 'latin1_swedish_ci',
	`denumire` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`nir_componenta_id` MEDIUMINT(8) NULL,
	`nir_id` MEDIUMINT(8) NULL,
	`produs_id` MEDIUMINT(8) NULL,
	`cantitate` FLOAT NULL,
	`cantitate_ramasa` FLOAT NULL,
	`pret_intrare` FLOAT NULL,
	`activ` INT(1) NULL,
	`data` DATE NULL,
	`pret_vanzare` FLOAT NULL
) ENGINE=MyISAM;


-- Dumping structure for view retail.view_inventar_continut
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `view_inventar_continut` (
	`inventar_continut_id` MEDIUMINT(8) NOT NULL,
	`inventar_id` MEDIUMINT(8) NULL,
	`produs_id` MEDIUMINT(8) NULL,
	`stoc_scriptic` FLOAT NULL,
	`stoc_faptic` FLOAT NULL,
	`denumire` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;


-- Dumping structure for view retail.view_niruri_detalii
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `view_niruri_detalii` (
	`nir_id` MEDIUMINT(8) NOT NULL,
	`numar_nir` INT(11) NULL,
	`furnizor_id` MEDIUMINT(8) NULL,
	`numar_factura` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`data_factura` DATE NULL,
	`data_scadenta` DATE NULL,
	`total_factura` FLOAT NULL,
	`total_tva` FLOAT NULL,
	`total_fara_tva` FLOAT NULL,
	`data_adaugare` DATETIME NULL,
	`user_id` MEDIUMINT(8) NULL,
	`nume_furnizor` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`nume_user` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;


-- Dumping structure for view retail.view_stocuri
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `view_stocuri` (
	`produs_id` MEDIUMINT(8) NOT NULL,
	`denumire` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`stoc` DOUBLE NULL
) ENGINE=MyISAM;


-- Dumping structure for view retail.view_stocuri_produse
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `view_stocuri_produse` (
	`produs_id` MEDIUMINT(8) NOT NULL,
	`denumire` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`categorie_id` MEDIUMINT(8) NULL,
	`cod` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`denumire2` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`cod_bare` VARCHAR(255) NULL COLLATE 'latin1_swedish_ci',
	`pret` FLOAT NULL,
	`cotatva_id` MEDIUMINT(8) NULL,
	`stoc` DOUBLE NULL,
	`la_vanzare` VARCHAR(2) NULL COLLATE 'latin1_swedish_ci',
	`tip_produs` VARCHAR(25) NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;


-- Dumping structure for view retail.view_vanzari
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `view_vanzari` (
	`denumire` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`cantitate` DOUBLE(19,2) NULL,
	`valoare` FLOAT NULL,
	`nume_mod` VARCHAR(255) NULL COLLATE 'latin1_swedish_ci',
	`mod_plata_id` MEDIUMINT(8) NOT NULL,
	`denumire_categorie` VARCHAR(255) NULL COLLATE 'latin1_swedish_ci',
	`casa_id` MEDIUMINT(8) NOT NULL,
	`nume_casa` VARCHAR(25) NULL COLLATE 'latin1_swedish_ci',
	`user_id` MEDIUMINT(8) NOT NULL,
	`nume` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`data` DATE NULL,
	`zi_economica_id` MEDIUMINT(8) NOT NULL
) ENGINE=MyISAM;


-- Dumping structure for table retail.zile_economice
CREATE TABLE IF NOT EXISTS `zile_economice` (
  `zi_economica_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `data` date DEFAULT NULL,
  `inchis` varchar(2) DEFAULT 'NU',
  `ora_inchidere` time DEFAULT '00:00:00',
  `user_id` mediumint(8) DEFAULT '0',
  PRIMARY KEY (`zi_economica_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table retail.zile_economice: ~2 rows (approximately)
/*!40000 ALTER TABLE `zile_economice` DISABLE KEYS */;
INSERT INTO `zile_economice` (`zi_economica_id`, `data`, `inchis`, `ora_inchidere`, `user_id`) VALUES
	(1, '2015-05-04', 'DA', '00:02:35', 2),
	(5, '2015-05-05', 'NU', '00:00:00', 0);
/*!40000 ALTER TABLE `zile_economice` ENABLE KEYS */;


-- Dumping structure for view retail.iesiri_bon_consum
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `iesiri_bon_consum`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `iesiri_bon_consum` AS (select `iesiri`.`iesire_id` AS `iesire_id`,`iesiri`.`bon_continut_id` AS `bon_continut_id`,`bonuri_consum_continut`.`bon_consum_id` AS `bon_consum_id`,`produse`.`produs_id` AS `produs_id`,`produse`.`denumire` AS `denumire`,`intrari_continut`.`pret_intrare` AS `pret_intrare`,`intrari_continut`.`pret_intrare` AS `pret_vanzare`,`iesiri`.`cantitate` AS `cantitate`,`bonuri_consum`.`data` AS `data`,`iesiri`.`tip` AS `tip` from ((((`iesiri` join `intrari_continut` on((`iesiri`.`intrare_continut_id` = `intrari_continut`.`intrare_continut_id`))) join `produse` on((`intrari_continut`.`produs_id` = `produse`.`produs_id`))) join `bonuri_consum_continut` on((`iesiri`.`bon_continut_id` = `bonuri_consum_continut`.`bon_consum_continut_id`))) join `bonuri_consum` on((`bonuri_consum`.`bon_consum_id` = `bonuri_consum_continut`.`bon_consum_id`))) where (`iesiri`.`tip` = _latin1'bon_consum'));


-- Dumping structure for view retail.iesiri_group
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `iesiri_group`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `iesiri_group` AS (select `iesiri_union`.`produs_id` AS `produs_id`,`iesiri_union`.`denumire` AS `denumire`,`iesiri_union`.`pret_intrare` AS `pret_intrare`,`iesiri_union`.`pret_vanzare` AS `pret_vanzare`,sum(`iesiri_union`.`cantitate`) AS `cantitate`,`iesiri_union`.`data` AS `data` from `iesiri_union` group by `iesiri_union`.`produs_id`,`iesiri_union`.`denumire`,`iesiri_union`.`pret_intrare`,`iesiri_union`.`pret_vanzare`,`iesiri_union`.`data` order by `iesiri_union`.`data`,`iesiri_union`.`pret_intrare`,`iesiri_union`.`pret_vanzare`);


-- Dumping structure for view retail.iesiri_materii
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `iesiri_materii`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `iesiri_materii` AS (select `iesiri`.`iesire_id` AS `iesire_id`,`bonuri_continut`.`bon_continut_id` AS `bon_continut_id`,`produse`.`produs_id` AS `produs_id`,`produse`.`denumire` AS `denumire`,`intrari_continut`.`pret_intrare` AS `pret_intrare`,(`intrari_continut`.`pret_intrare` * (`bonuri_continut`.`valoare` / `preturi_ach_retete`.`pret_ach`)) AS `pret_vanzare`,`iesiri`.`cantitate` AS `cantitate`,`bonuri`.`data` AS `data`,`iesiri`.`tip` AS `tip` from (((((`iesiri` join `intrari_continut` on((`intrari_continut`.`intrare_continut_id` = `iesiri`.`intrare_continut_id`))) join `bonuri_continut` on((`bonuri_continut`.`bon_continut_id` = `iesiri`.`bon_continut_id`))) join `produse` on((`produse`.`produs_id` = `intrari_continut`.`produs_id`))) join `preturi_ach_retete` on((`preturi_ach_retete`.`bon_continut_id` = `iesiri`.`bon_continut_id`))) join `bonuri` on((`bonuri_continut`.`bon_id` = `bonuri`.`bon_id`))));


-- Dumping structure for view retail.iesiri_materii_prime
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `iesiri_materii_prime`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `iesiri_materii_prime` AS (select `iesiri`.`bon_continut_id` AS `bon_continut_id`,`produse`.`produs_id` AS `produs_id`,`produse`.`denumire` AS `denumire`,`bonuri_continut`.`cantitate` AS `cantitate_vanduta`,`bonuri_continut`.`valoare` AS `valoare`,`view_intrari_continut`.`produs_id` AS `componenta_id`,`view_intrari_continut`.`denumire` AS `denumire_componenta`,`view_intrari_continut`.`pret_intrare` AS `pret_intrare`,`iesiri`.`cantitate` AS `cantitate_iesire` from ((((`iesiri` join `bonuri_continut` on((`iesiri`.`bon_continut_id` = `bonuri_continut`.`bon_continut_id`))) join `produse` on((`produse`.`produs_id` = `bonuri_continut`.`produs_id`))) join `view_intrari_continut` on((`view_intrari_continut`.`intrare_continut_id` = `iesiri`.`intrare_continut_id`))) join `retetar` on(((`view_intrari_continut`.`produs_id` = `retetar`.`componenta_id`) and (`produse`.`produs_id` = `retetar`.`produs_id`)))) where (`iesiri`.`tip` = _latin1'vanzare_reteta'));


-- Dumping structure for view retail.iesiri_retete
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `iesiri_retete`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `iesiri_retete` AS (select distinct `iesiri`.`bon_continut_id` AS `bon_continut_id`,`produse`.`produs_id` AS `produs_id`,`produse`.`denumire` AS `denumire`,`preturi_ach_retete`.`pret_ach` AS `pret_intrare`,`bonuri_continut`.`valoare` AS `pret_vanzare`,`bonuri_continut`.`cantitate` AS `cantitate`,`bonuri`.`data` AS `data`,_utf8'vanzare_reteta' AS `tip` from ((((`iesiri` join `preturi_ach_retete` on((`preturi_ach_retete`.`bon_continut_id` = `iesiri`.`bon_continut_id`))) join `bonuri_continut` on((`bonuri_continut`.`bon_continut_id` = `iesiri`.`bon_continut_id`))) join `produse` on((`produse`.`produs_id` = `bonuri_continut`.`produs_id`))) join `bonuri` on((`bonuri`.`bon_id` = `bonuri_continut`.`bon_id`))) where (`iesiri`.`tip` = _latin1'vanzare_reteta'));


-- Dumping structure for view retail.iesiri_union
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `iesiri_union`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `iesiri_union` AS select `iesiri_bon_consum`.`iesire_id` AS `iesire_id`,`iesiri_bon_consum`.`bon_continut_id` AS `bon_continut_id`,`iesiri_bon_consum`.`produs_id` AS `produs_id`,`iesiri_bon_consum`.`denumire` AS `denumire`,`iesiri_bon_consum`.`pret_intrare` AS `pret_intrare`,`iesiri_bon_consum`.`pret_vanzare` AS `pret_vanzare`,`iesiri_bon_consum`.`cantitate` AS `cantitate`,`iesiri_bon_consum`.`data` AS `data`,`iesiri_bon_consum`.`tip` AS `tip` from `iesiri_bon_consum` union select `iesiri_vanzari`.`iesire_id` AS `iesire_id`,`iesiri_vanzari`.`bon_continut_id` AS `bon_continut_id`,`iesiri_vanzari`.`produs_id` AS `produs_id`,`iesiri_vanzari`.`denumire` AS `denumire`,`iesiri_vanzari`.`pret_intrare` AS `pret_intrare`,`iesiri_vanzari`.`pret_vanzare` AS `pret_vanzare`,`iesiri_vanzari`.`cantitate` AS `cantitate`,`iesiri_vanzari`.`data` AS `data`,`iesiri_vanzari`.`tip` AS `tip` from `iesiri_vanzari` union select 0 AS `iesire_id`,`iesiri_materii`.`bon_continut_id` AS `bon_continut_id`,`iesiri_materii`.`produs_id` AS `produs_id`,`iesiri_materii`.`denumire` AS `denumire`,`iesiri_materii`.`pret_intrare` AS `pret_intrare`,`iesiri_materii`.`pret_vanzare` AS `pret_vanzare`,`iesiri_materii`.`cantitate` AS `cantitate`,`iesiri_materii`.`data` AS `data`,`iesiri_materii`.`tip` AS `tip` from `iesiri_materii`;


-- Dumping structure for view retail.iesiri_vanzari
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `iesiri_vanzari`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `iesiri_vanzari` AS (select `iesiri`.`iesire_id` AS `iesire_id`,`iesiri`.`bon_continut_id` AS `bon_continut_id`,`produse`.`produs_id` AS `produs_id`,`produse`.`denumire` AS `denumire`,`intrari_continut`.`pret_intrare` AS `pret_intrare`,`bonuri_continut`.`valoare` AS `pret_vanzare`,`iesiri`.`cantitate` AS `cantitate`,`bonuri`.`data` AS `data`,`iesiri`.`tip` AS `tip` from ((((`iesiri` join `intrari_continut` on((`iesiri`.`intrare_continut_id` = `intrari_continut`.`intrare_continut_id`))) join `produse` on((`intrari_continut`.`produs_id` = `produse`.`produs_id`))) join `bonuri_continut` on((`iesiri`.`bon_continut_id` = `bonuri_continut`.`bon_continut_id`))) join `bonuri` on((`bonuri`.`bon_id` = `bonuri_continut`.`bon_id`))) where (`iesiri`.`tip` = _latin1'vanzare'));


-- Dumping structure for view retail.intrari_group
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `intrari_group`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `intrari_group` AS (select `produse`.`produs_id` AS `produs_id`,`produse`.`denumire` AS `denumire`,sum(`intrari_continut`.`cantitate`) AS `cantitate`,`intrari_continut`.`pret_intrare` AS `pret_intrare`,`intrari_continut`.`pret_vanzare` AS `pret_vanzare`,`intrari_continut`.`data` AS `data` from (`intrari_continut` join `produse` on((`intrari_continut`.`produs_id` = `produse`.`produs_id`))) group by `produse`.`produs_id`,`produse`.`denumire`,`intrari_continut`.`pret_intrare`,`intrari_continut`.`pret_vanzare`,`intrari_continut`.`data` order by `intrari_continut`.`data`,`intrari_continut`.`pret_intrare`,`intrari_continut`.`pret_vanzare`);


-- Dumping structure for view retail.intrari_iesiri
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `intrari_iesiri`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `intrari_iesiri` AS select `intrari_group`.`produs_id` AS `produs_id`,`intrari_group`.`denumire` AS `denumire`,`intrari_group`.`pret_intrare` AS `pret_intrare`,`intrari_group`.`pret_vanzare` AS `pret_vanzare`,`intrari_group`.`cantitate` AS `cantitate`,`intrari_group`.`data` AS `data`,_utf8'a' AS `tip` from `intrari_group` union select `iesiri_group`.`produs_id` AS `produs_id`,`iesiri_group`.`denumire` AS `denumire`,`iesiri_group`.`pret_intrare` AS `pret_intrare`,`iesiri_group`.`pret_vanzare` AS `pret_vanzare`,`iesiri_group`.`cantitate` AS `cantitate`,`iesiri_group`.`data` AS `data`,_utf8'b' AS `tip` from `iesiri_group`;


-- Dumping structure for view retail.preturi_ach_retete
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `preturi_ach_retete`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `preturi_ach_retete` AS select `retete_calcul`.`bon_continut_id` AS `bon_continut_id`,(`retete_calcul`.`suma` / `bonuri_continut`.`cantitate`) AS `pret_ach` from (`retete_calcul` join `bonuri_continut` on((`retete_calcul`.`bon_continut_id` = `bonuri_continut`.`bon_continut_id`)));


-- Dumping structure for view retail.retete_calcul
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `retete_calcul`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `retete_calcul` AS (select `iesiri_materii_prime`.`bon_continut_id` AS `bon_continut_id`,sum((`iesiri_materii_prime`.`pret_intrare` * `iesiri_materii_prime`.`cantitate_iesire`)) AS `suma`,sum(`iesiri_materii_prime`.`cantitate_iesire`) AS `cant_ies` from `iesiri_materii_prime` group by `iesiri_materii_prime`.`bon_continut_id`);


-- Dumping structure for view retail.rpt_bonuri_emise
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `rpt_bonuri_emise`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `rpt_bonuri_emise` AS (select count(`bonuri`.`bon_id`) AS `bonuri_emise`,sum(`bonuri`.`total`) AS `total`,`zile_economice`.`zi_economica_id` AS `zi_economica_id`,`zile_economice`.`data` AS `data`,`case_fiscale`.`casa_id` AS `casa_id`,`case_fiscale`.`nume_casa` AS `nume_casa` from ((`bonuri` join `zile_economice` on((`zile_economice`.`zi_economica_id` = `bonuri`.`zi_economica_id`))) join `case_fiscale` on((`bonuri`.`casa_id` = `case_fiscale`.`casa_id`))) group by `zile_economice`.`zi_economica_id`,`zile_economice`.`data`,`case_fiscale`.`casa_id`,`case_fiscale`.`nume_casa`);


-- Dumping structure for view retail.rpt_moduri_case
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `rpt_moduri_case`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `rpt_moduri_case` AS (select `bonuri`.`casa_id` AS `casa_id`,`case_fiscale`.`nume_casa` AS `nume_casa`,`moduri_plata`.`nume_mod` AS `nume_mod`,sum(`bonuri_plata`.`suma`) AS `suma`,`moduri_plata`.`mod_plata_id` AS `mod_plata_id`,`zile_economice`.`zi_economica_id` AS `zi_economica_id`,`zile_economice`.`data` AS `data` from ((((`bonuri` join `bonuri_plata` on((`bonuri_plata`.`bon_id` = `bonuri`.`bon_id`))) join `moduri_plata` on((`moduri_plata`.`mod_plata_id` = `bonuri_plata`.`mod_plata_id`))) join `case_fiscale` on((`bonuri`.`casa_id` = `case_fiscale`.`casa_id`))) join `zile_economice` on((`zile_economice`.`zi_economica_id` = `bonuri`.`zi_economica_id`))) group by `zile_economice`.`zi_economica_id`,`bonuri`.`casa_id`,`case_fiscale`.`nume_casa`,`moduri_plata`.`nume_mod`,`moduri_plata`.`mod_plata_id`,`zile_economice`.`data`);


-- Dumping structure for view retail.rpt_moduri_plata
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `rpt_moduri_plata`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `rpt_moduri_plata` AS (select `moduri_plata`.`nume_mod` AS `nume_mod`,sum(`bonuri_plata`.`suma`) AS `total_suma`,`zile_economice`.`zi_economica_id` AS `zi_economica_id`,`zile_economice`.`data` AS `data` from (((`bonuri_plata` join `bonuri` on((`bonuri_plata`.`bon_id` = `bonuri`.`bon_id`))) join `zile_economice` on((`bonuri`.`zi_economica_id` = `zile_economice`.`zi_economica_id`))) join `moduri_plata` on((`bonuri_plata`.`mod_plata_id` = `moduri_plata`.`mod_plata_id`))) group by `zile_economice`.`zi_economica_id`,`moduri_plata`.`nume_mod`,`zile_economice`.`data`);


-- Dumping structure for view retail.rpt_utilizatori_moduri
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `rpt_utilizatori_moduri`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `rpt_utilizatori_moduri` AS (select `bonuri`.`user_id` AS `user_id`,`users`.`nume` AS `nume`,`moduri_plata`.`nume_mod` AS `nume_mod`,sum(`bonuri_plata`.`suma`) AS `suma`,`moduri_plata`.`mod_plata_id` AS `mod_plata_id`,`zile_economice`.`zi_economica_id` AS `zi_economica_id`,`zile_economice`.`data` AS `data` from ((((`bonuri` join `bonuri_plata` on((`bonuri_plata`.`bon_id` = `bonuri`.`bon_id`))) join `moduri_plata` on((`moduri_plata`.`mod_plata_id` = `bonuri_plata`.`mod_plata_id`))) join `users` on((`bonuri`.`user_id` = `users`.`user_id`))) join `zile_economice` on((`zile_economice`.`zi_economica_id` = `bonuri`.`zi_economica_id`))) group by `zile_economice`.`zi_economica_id`,`bonuri`.`user_id`,`users`.`nume`,`moduri_plata`.`nume_mod`,`moduri_plata`.`mod_plata_id`,`zile_economice`.`data`);


-- Dumping structure for view retail.rpt_vanzari
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `rpt_vanzari`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `rpt_vanzari` AS (select `produse`.`denumire` AS `denumire`,`categorii`.`denumire_categorie` AS `denumire_categorie`,sum(`bonuri_continut`.`cantitate`) AS `cantitate_vanduta`,sum((`bonuri_continut`.`cantitate` * `bonuri_continut`.`valoare`)) AS `valoare_vanduta`,`ze`.`zi_economica_id` AS `zi_economica_id`,`ze`.`data` AS `data` from ((((`bonuri_continut` join `bonuri` on((`bonuri_continut`.`bon_id` = `bonuri`.`bon_id`))) join `produse` on((`bonuri_continut`.`produs_id` = `produse`.`produs_id`))) join `categorii` on((`produse`.`categorie_id` = `categorii`.`categorie_id`))) join `zile_economice` `ze` on((`ze`.`zi_economica_id` = `bonuri`.`zi_economica_id`))) group by `produse`.`denumire`,`ze`.`zi_economica_id`,`ze`.`data`,`produse`.`produs_id`,`categorii`.`denumire_categorie`,`categorii`.`categorie_id`);


-- Dumping structure for view retail.test
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `test`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `test` AS (select `produse`.`denumire` AS `denumire`,`intrari_continut`.`nir_id` AS `nir_id`,`produse`.`pret` AS `pret`,`intrari_continut`.`pret_vanzare` AS `pret_vanzare`,`intrari_continut`.`pret_intrare` AS `pret_intrare` from (`produse` join `intrari_continut` on((`produse`.`produs_id` = `intrari_continut`.`produs_id`))));


-- Dumping structure for view retail.union
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `union`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `union` AS select `iesiri_bon_consum`.`iesire_id` AS `iesire_id`,`iesiri_bon_consum`.`bon_continut_id` AS `bon_continut_id`,`iesiri_bon_consum`.`produs_id` AS `produs_id`,`iesiri_bon_consum`.`denumire` AS `denumire`,`iesiri_bon_consum`.`pret_intrare` AS `pret_intrare`,`iesiri_bon_consum`.`pret_vanzare` AS `pret_vanzare`,`iesiri_bon_consum`.`cantitate` AS `cantitate`,`iesiri_bon_consum`.`tip` AS `tip` from `iesiri_bon_consum` union select `iesiri_vanzari`.`iesire_id` AS `iesire_id`,`iesiri_vanzari`.`bon_continut_id` AS `bon_continut_id`,`iesiri_vanzari`.`produs_id` AS `produs_id`,`iesiri_vanzari`.`denumire` AS `denumire`,`iesiri_vanzari`.`pret_intrare` AS `pret_intrare`,`iesiri_vanzari`.`pret_vanzare` AS `pret_vanzare`,`iesiri_vanzari`.`cantitate` AS `cantitate`,`iesiri_vanzari`.`tip` AS `tip` from `iesiri_vanzari`;


-- Dumping structure for view retail.valoare_stoc
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `valoare_stoc`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `valoare_stoc` AS (select `view_stocuri_produse`.`produs_id` AS `produs_id`,`view_stocuri_produse`.`denumire` AS `denumire`,`view_stocuri_produse`.`stoc` AS `stoc`,`view_stocuri_produse`.`pret` AS `pret`,(`view_stocuri_produse`.`stoc` * `view_stocuri_produse`.`pret`) AS `valoare_stoc`,`view_stocuri_produse`.`categorie_id` AS `categorie_id`,`categorii`.`denumire_categorie` AS `denumire_categorie` from (`view_stocuri_produse` join `categorii` on((`view_stocuri_produse`.`categorie_id` = `categorii`.`categorie_id`))));


-- Dumping structure for view retail.view_bonuri_continut
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `view_bonuri_continut`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `view_bonuri_continut` AS (select `bonuri_continut`.`bon_continut_id` AS `bon_continut_id`,`bonuri_continut`.`bon_id` AS `bon_id`,`bonuri_continut`.`produs_id` AS `produs_id`,`bonuri_continut`.`cantitate` AS `cantitate`,`bonuri_continut`.`valoare` AS `valoare`,`produse`.`denumire` AS `denumire` from (`bonuri_continut` join `produse` on((`bonuri_continut`.`produs_id` = `produse`.`produs_id`))));


-- Dumping structure for view retail.view_bonuri_moduri
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `view_bonuri_moduri`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `view_bonuri_moduri` AS (select `bonuri`.`data_ora` AS `data_ora`,`bonuri`.`bon_id` AS `bon_id`,`bonuri`.`numar_bon` AS `numar_bon`,`bonuri`.`total` AS `total`,`bonuri_plata`.`suma` AS `suma`,`case_fiscale`.`casa_id` AS `casa_id`,`case_fiscale`.`nume_casa` AS `nume_casa`,`users`.`user_id` AS `user_id`,`users`.`nume` AS `nume`,`moduri_plata`.`mod_plata_id` AS `mod_plata_id`,`moduri_plata`.`nume_mod` AS `nume_mod`,`moduri_plata`.`cash` AS `cash`,`zile_economice`.`data` AS `data` from (((((`bonuri` join `bonuri_plata` on((`bonuri`.`bon_id` = `bonuri_plata`.`bon_id`))) join `moduri_plata` on((`bonuri_plata`.`mod_plata_id` = `moduri_plata`.`mod_plata_id`))) join `zile_economice` on((`bonuri`.`zi_economica_id` = `zile_economice`.`zi_economica_id`))) join `users` on((`bonuri`.`user_id` = `users`.`user_id`))) join `case_fiscale` on((`bonuri`.`casa_id` = `case_fiscale`.`casa_id`))));


-- Dumping structure for view retail.view_comenzi_continut
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `view_comenzi_continut`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `view_comenzi_continut` AS (select `comenzi_continut`.`comanda_continut_id` AS `comanda_continut_id`,`comenzi_continut`.`comanda_id` AS `comanda_id`,`comenzi_continut`.`cantitate` AS `cantitate`,`comenzi_continut`.`valoare` AS `valoare`,`comenzi_continut`.`discount` AS `discount`,`produse`.`denumire` AS `denumire` from (`comenzi_continut` join `produse` on((`comenzi_continut`.`produs_id` = `produse`.`produs_id`))));


-- Dumping structure for view retail.view_iesiri_vanzari
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `view_iesiri_vanzari`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `view_iesiri_vanzari` AS (select `iesiri`.`iesire_id` AS `iesire_id`,`iesiri`.`bon_continut_id` AS `bon_continut_id`,`iesiri`.`cantitate` AS `cantitate`,`bonuri_continut`.`valoare` AS `valoare`,`iesiri`.`tip` AS `tip`,`bonuri`.`numar_bon` AS `numar_bon`,`view_intrari_continut`.`intrare_continut_id` AS `intrare_continut_id`,`zile_economice`.`data` AS `data`,`users`.`nume` AS `nume` from (((((`iesiri` join `view_intrari_continut` on((`iesiri`.`intrare_continut_id` = `view_intrari_continut`.`intrare_continut_id`))) join `bonuri_continut` on((`bonuri_continut`.`bon_continut_id` = `iesiri`.`bon_continut_id`))) join `bonuri` on((`bonuri`.`bon_id` = `bonuri_continut`.`bon_id`))) join `zile_economice` on((`zile_economice`.`zi_economica_id` = `bonuri`.`zi_economica_id`))) join `users` on((`users`.`user_id` = `bonuri`.`user_id`))) where (`iesiri`.`tip` = _latin1'vanzare'));


-- Dumping structure for view retail.view_intrari_continut
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `view_intrari_continut`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `view_intrari_continut` AS (select `intrari_continut`.`intrare_continut_id` AS `intrare_continut_id`,`intrari_continut`.`tip` AS `tip`,`produse`.`denumire` AS `denumire`,`intrari_continut`.`nir_componenta_id` AS `nir_componenta_id`,`intrari_continut`.`nir_id` AS `nir_id`,`intrari_continut`.`produs_id` AS `produs_id`,`intrari_continut`.`cantitate` AS `cantitate`,`intrari_continut`.`cantitate_ramasa` AS `cantitate_ramasa`,`intrari_continut`.`pret_intrare` AS `pret_intrare`,`intrari_continut`.`activ` AS `activ`,`intrari_continut`.`data` AS `data`,`intrari_continut`.`pret_vanzare` AS `pret_vanzare` from (`intrari_continut` join `produse` on((`produse`.`produs_id` = `intrari_continut`.`produs_id`))));


-- Dumping structure for view retail.view_inventar_continut
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `view_inventar_continut`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `view_inventar_continut` AS (select `inventar_continut`.`inventar_continut_id` AS `inventar_continut_id`,`inventar_continut`.`inventar_id` AS `inventar_id`,`inventar_continut`.`produs_id` AS `produs_id`,`inventar_continut`.`stoc_scriptic` AS `stoc_scriptic`,`inventar_continut`.`stoc_faptic` AS `stoc_faptic`,`produse`.`denumire` AS `denumire` from (`inventar_continut` join `produse` on((`inventar_continut`.`produs_id` = `produse`.`produs_id`))));


-- Dumping structure for view retail.view_niruri_detalii
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `view_niruri_detalii`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `view_niruri_detalii` AS (select `niruri`.`nir_id` AS `nir_id`,`niruri`.`numar_nir` AS `numar_nir`,`niruri`.`furnizor_id` AS `furnizor_id`,`niruri`.`numar_factura` AS `numar_factura`,`niruri`.`data_factura` AS `data_factura`,`niruri`.`data_scadenta` AS `data_scadenta`,`niruri`.`total_factura` AS `total_factura`,`niruri`.`total_tva` AS `total_tva`,`niruri`.`total_fara_tva` AS `total_fara_tva`,`niruri`.`data_adaugare` AS `data_adaugare`,`niruri`.`user_id` AS `user_id`,`furnizori`.`nume` AS `nume_furnizor`,`users`.`nume` AS `nume_user` from ((`niruri` join `furnizori` on((`niruri`.`furnizor_id` = `furnizori`.`furnizor_id`))) join `users` on((`niruri`.`user_id` = `users`.`user_id`))));


-- Dumping structure for view retail.view_stocuri
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `view_stocuri`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `view_stocuri` AS (select `produse`.`produs_id` AS `produs_id`,`produse`.`denumire` AS `denumire`,sum(`intrari_continut`.`cantitate_ramasa`) AS `stoc` from (`produse` join `intrari_continut` on((`intrari_continut`.`produs_id` = `produse`.`produs_id`))) where (`intrari_continut`.`cantitate_ramasa` <> 0) group by `produse`.`produs_id`,`produse`.`denumire`);


-- Dumping structure for view retail.view_stocuri_produse
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `view_stocuri_produse`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `view_stocuri_produse` AS (select `produse`.`produs_id` AS `produs_id`,`produse`.`denumire` AS `denumire`,`produse`.`categorie_id` AS `categorie_id`,`produse`.`cod` AS `cod`,`produse`.`denumire2` AS `denumire2`,`produse`.`cod_bare` AS `cod_bare`,`produse`.`pret` AS `pret`,`produse`.`cotatva_id` AS `cotatva_id`,`view_stocuri`.`stoc` AS `stoc`,`produse`.`la_vanzare` AS `la_vanzare`,`produse`.`tip_produs` AS `tip_produs` from (`produse` left join `view_stocuri` on(((`produse`.`produs_id` = `view_stocuri`.`produs_id`) and (`produse`.`denumire` = `view_stocuri`.`denumire`)))));


-- Dumping structure for view retail.view_vanzari
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `view_vanzari`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `view_vanzari` AS (select `p`.`denumire` AS `denumire`,sum(round((`bc`.`cantitate` * (`bp`.`suma` / `b`.`total`)),2)) AS `cantitate`,`bc`.`valoare` AS `valoare`,`m`.`nume_mod` AS `nume_mod`,`m`.`mod_plata_id` AS `mod_plata_id`,`c`.`denumire_categorie` AS `denumire_categorie`,`cf`.`casa_id` AS `casa_id`,`cf`.`nume_casa` AS `nume_casa`,`u`.`user_id` AS `user_id`,`u`.`nume` AS `nume`,`z`.`data` AS `data`,`z`.`zi_economica_id` AS `zi_economica_id` from ((((((((`bonuri_continut` `bc` join `bonuri` `b` on((`bc`.`bon_id` = `b`.`bon_id`))) join `bonuri_plata` `bp` on((`bc`.`bon_id` = `bp`.`bon_id`))) join `moduri_plata` `m` on((`bp`.`mod_plata_id` = `m`.`mod_plata_id`))) join `produse` `p` on((`bc`.`produs_id` = `p`.`produs_id`))) join `categorii` `c` on((`p`.`categorie_id` = `c`.`categorie_id`))) join `users` `u` on((`b`.`user_id` = `u`.`user_id`))) join `case_fiscale` `cf` on((`b`.`casa_id` = `cf`.`casa_id`))) join `zile_economice` `z` on((`b`.`zi_economica_id` = `z`.`zi_economica_id`))) group by `p`.`denumire`,`bc`.`valoare`,`m`.`nume_mod`,`m`.`mod_plata_id`,`z`.`data`,`z`.`zi_economica_id`,`c`.`denumire_categorie`,`u`.`nume`,`u`.`user_id`,`cf`.`casa_id`,`cf`.`nume_casa`);
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
