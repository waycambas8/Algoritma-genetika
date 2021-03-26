/*
SQLyog Ultimate v9.50 
MySQL - 5.5.5-10.1.29-MariaDB : Database - ag_tsp
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`app_genetika` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `app_genetika`;

/*Table structure for table `tb_admin` */

DROP TABLE IF EXISTS `tb_admin`;

CREATE TABLE `tb_admin` (
  `user` varchar(16) NOT NULL,
  `pass` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tb_admin` */

insert  into `tb_admin`(`user`,`pass`) values ('admin','admin');

/*Table structure for table `tb_bobot` */

DROP TABLE IF EXISTS `tb_bobot`;

CREATE TABLE `tb_bobot` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID1` varchar(16) DEFAULT NULL,
  `ID2` varchar(16) DEFAULT NULL,
  `bobot` double DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

/*Data for the table `tb_bobot` */

insert  into `tb_bobot`(`ID`,`ID1`,`ID2`,`bobot`) values (1,'T001','T001',0),(2,'T001','T002',9.983),(3,'T002','T002',0),(4,'T002','T001',10.056),(14,'T003','T002',13.416),(13,'T003','T001',18.962),(12,'T003','T003',0),(11,'T002','T003',9.144),(10,'T001','T003',14.233),(15,'T001','T004',11.106),(16,'T002','T004',11.3),(17,'T003','T004',23.133),(18,'T004','T004',0),(19,'T004','T001',11.239),(20,'T004','T002',11.323),(21,'T004','T003',21.266),(22,'T001','T005',22.033),(23,'T002','T005',20.058),(24,'T003','T005',17.709),(25,'T004','T005',29.066),(26,'T005','T005',0),(27,'T005','T001',22.153),(28,'T005','T002',20.157),(29,'T005','T003',18.734),(30,'T005','T004',29.086);

/*Table structure for table `tb_kelompok` */

DROP TABLE IF EXISTS `tb_kelompok`;

CREATE TABLE `tb_kelompok` (
  `kode_kelompok` varchar(16) NOT NULL,
  `nama_kelompok` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`kode_kelompok`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tb_kelompok` */

insert  into `tb_kelompok`(`kode_kelompok`,`nama_kelompok`) values ('K01','Pantai di Bali');

/*Table structure for table `tb_options` */

DROP TABLE IF EXISTS `tb_options`;

CREATE TABLE `tb_options` (
  `option_name` varchar(16) NOT NULL,
  `option_value` text,
  PRIMARY KEY (`option_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tb_options` */

insert  into `tb_options`(`option_name`,`option_value`) values ('cost_per_kilo','1500'),('default_lat','-0.789275'),('default_lng','113.92132700000002'),('default_zoom','5');

/*Table structure for table `tb_titik` */

DROP TABLE IF EXISTS `tb_titik`;

CREATE TABLE `tb_titik` (
  `kode_titik` varchar(16) NOT NULL,
  `nama_titik` varchar(255) DEFAULT NULL,
  `kode_kelompok` varchar(16) DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  PRIMARY KEY (`kode_titik`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tb_titik` */

insert  into `tb_titik`(`kode_titik`,`nama_titik`,`kode_kelompok`,`lat`,`lng`) values ('T001','Pantai Nusa Dua','K01',-8.795761599999999,115.23282130000007),('T002','Pantai Jimbaran','K01',-8.7765946,115.1663701),('T003','Pantai Kuta','K01',-8.7202498,115.16917620000004),('T004','Pantai Pandawa','K01',-8.845280200000001,115.18706789999999),('T005','Pantai Sanur','K01',-8.674670299999999,115.26403290000007);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
