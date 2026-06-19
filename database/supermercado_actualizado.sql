-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: localhost    Database: supermercado
-- ------------------------------------------------------
-- Server version	8.0.41

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Temporary view structure for view `alerta_stock`
--

DROP TABLE IF EXISTS `alerta_stock`;
/*!50001 DROP VIEW IF EXISTS `alerta_stock`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `alerta_stock` AS SELECT 
 1 AS `nombre`,
 1 AS `stock`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `bitacora`
--

DROP TABLE IF EXISTS `bitacora`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bitacora` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `id_empleado` int DEFAULT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `accion` varchar(150) DEFAULT NULL,
  `modulo` varchar(50) DEFAULT NULL,
  `metodo` varchar(10) DEFAULT NULL,
  `ruta` varchar(200) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacora`
--

LOCK TABLES `bitacora` WRITE;
/*!40000 ALTER TABLE `bitacora` DISABLE KEYS */;
INSERT INTO `bitacora` VALUES (1,1,'steve','Inició sesión','Acceso','POST','/login','127.0.0.1','2026-06-15 16:45:27'),(2,1,'steve','Actualizó en Configuracion','Configuracion','PUT','/configuracion','127.0.0.1','2026-06-15 16:50:30'),(3,1,'steve','Actualizó en Configuracion','Configuracion','PUT','/configuracion','127.0.0.1','2026-06-15 16:50:41'),(4,1,'steve','Cerró sesión','Acceso','POST','/logout','127.0.0.1','2026-06-15 16:50:47'),(5,5,'alejandro','Inició sesión','Acceso','POST','/login','127.0.0.1','2026-06-15 16:51:01'),(6,5,'alejandro','Actualizó en Productos','Productos','PUT','/productos/1','127.0.0.1','2026-06-15 16:51:25'),(7,5,'alejandro','Actualizó en Productos','Productos','PUT','/productos/1','127.0.0.1','2026-06-15 16:51:26'),(8,5,'alejandro','Cerró sesión','Acceso','POST','/logout','127.0.0.1','2026-06-15 16:52:01'),(9,3,'rudy','Inició sesión','Acceso','POST','/login','127.0.0.1','2026-06-15 16:52:15'),(10,3,'rudy','Cerró sesión','Acceso','POST','/logout','127.0.0.1','2026-06-15 16:52:36'),(11,3,'rudy','Inició sesión','Acceso','POST','/login','127.0.0.1','2026-06-15 16:53:23'),(12,3,'rudy','Creó / registró en Pos','Pos','POST','/pos/cobrar','127.0.0.1','2026-06-15 16:56:26'),(13,3,'rudy','Creó / registró en Pos','Pos','POST','/pos/cobrar','127.0.0.1','2026-06-15 16:59:21'),(14,3,'rudy','Cerró sesión','Acceso','POST','/logout','127.0.0.1','2026-06-15 17:01:20'),(15,1,'steve','Inició sesión','Acceso','POST','/login','127.0.0.1','2026-06-15 17:01:39'),(16,1,'steve','Actualizó en Productos','Productos','PUT','/productos/2','127.0.0.1','2026-06-15 17:03:13'),(17,1,'steve','Actualizó en Productos','Productos','PUT','/productos/2','127.0.0.1','2026-06-15 17:03:14'),(18,1,'steve','Actualizó en Productos','Productos','PUT','/productos/2','127.0.0.1','2026-06-15 17:03:26'),(19,1,'steve','Actualizó en Productos','Productos','PUT','/productos/2','127.0.0.1','2026-06-15 17:03:50'),(20,1,'steve','Creó / registró en Inventario','Inventario','POST','/inventario/reabastecer','127.0.0.1','2026-06-15 17:04:41'),(21,1,'steve','Creó / registró en Clientes','Clientes','POST','/clientes','127.0.0.1','2026-06-15 17:15:51'),(22,1,'steve','Eliminó en Clientes','Clientes','DELETE','/clientes/58','127.0.0.1','2026-06-15 17:17:52'),(23,1,'steve','Inició sesión','Acceso','POST','/login','127.0.0.1','2026-06-16 15:32:50'),(24,1,'steve','Inició sesión','Acceso','POST','/login','127.0.0.1','2026-06-18 16:34:55'),(25,1,'steve','Inició sesión','Acceso','POST','/login','127.0.0.1','2026-06-18 17:29:09'),(26,1,'steve','Inició sesión','Acceso','POST','/login','127.0.0.1','2026-06-19 02:28:51'),(27,1,'steve','Inició sesión','Acceso','POST','/login','127.0.0.1','2026-06-19 02:29:27');
/*!40000 ALTER TABLE `bitacora` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `id_cliente` int NOT NULL AUTO_INCREMENT,
  `codigo_cliente` varchar(20) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text,
  `puntos` int NOT NULL DEFAULT '0',
  `nivel_fidelidad` varchar(20) NOT NULL DEFAULT 'Bronce',
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,'CL0001','Juan','Perez','juan@gmail.com',NULL,NULL,10,'Bronce'),(2,'CL0002','Jose','Sosa','jose@gmail.com',NULL,NULL,0,'Bronce'),(3,'CL0003','Eduardo','Bautista','eduardo@gmail.com',NULL,NULL,0,'Bronce'),(4,'CL0004','Armando','Escobar','armando@gmail.com',NULL,NULL,0,'Bronce'),(5,'CL0005','Alexandra','Ramos','alexandra@gmail.com',NULL,NULL,0,'Bronce'),(6,'CL0006','Eduardo','Mancia','eduardo@gmail.com',NULL,NULL,0,'Bronce'),(7,'CL0007','Saul','Benitez','saul@gmail.com',NULL,NULL,0,'Bronce'),(8,'CL0008','Carlos','Lopez','carlos8@gmail.com',NULL,NULL,0,'Bronce'),(9,'CL0009','Ana','Martinez','ana9@gmail.com',NULL,NULL,0,'Bronce'),(10,'CL0010','Luis','Perez','luis10@gmail.com',NULL,NULL,0,'Bronce'),(11,'CL0011','Sofia','Ramirez','sofia11@gmail.com',NULL,NULL,0,'Bronce'),(12,'CL0012','Jorge','Hernandez','jorge12@gmail.com',NULL,NULL,0,'Bronce'),(13,'CL0013','Maria','Castro','maria13@gmail.com',NULL,NULL,0,'Bronce'),(14,'CL0014','Pedro','Gomez','pedro14@gmail.com',NULL,NULL,0,'Bronce'),(15,'CL0015','Lucia','Torres','lucia15@gmail.com',NULL,NULL,0,'Bronce'),(16,'CL0016','Andres','Vega','andres16@gmail.com',NULL,NULL,0,'Bronce'),(17,'CL0017','Elena','Morales','elena17@gmail.com',NULL,NULL,0,'Bronce'),(18,'CL0018','Raul','Ortega','raul18@gmail.com',NULL,NULL,0,'Bronce'),(19,'CL0019','Diana','Rivas','diana19@gmail.com',NULL,NULL,0,'Bronce'),(20,'CL0020','Mario','Silva','mario20@gmail.com',NULL,NULL,0,'Bronce'),(21,'CL0021','Paola','Cruz','paola21@gmail.com',NULL,NULL,0,'Bronce'),(22,'CL0022','Ricardo','Flores','ricardo22@gmail.com',NULL,NULL,0,'Bronce'),(23,'CL0023','Valeria','Mejia','valeria23@gmail.com',NULL,NULL,0,'Bronce'),(24,'CL0024','Oscar','Reyes','oscar24@gmail.com',NULL,NULL,0,'Bronce'),(25,'CL0025','Fernanda','Campos','fernanda25@gmail.com',NULL,NULL,0,'Bronce'),(26,'CL0026','Hugo','Mendoza','hugo26@gmail.com',NULL,NULL,0,'Bronce'),(27,'CL0027','Karla','Aguilar','karla27@gmail.com',NULL,NULL,0,'Bronce'),(28,'CL0028','Diego','Navarro','diego28@gmail.com',NULL,NULL,0,'Bronce'),(29,'CL0029','Daniela','Pineda','daniela29@gmail.com',NULL,NULL,0,'Bronce'),(30,'CL0030','Ruben','Salazar','ruben30@gmail.com',NULL,NULL,0,'Bronce'),(31,'CL0031','Patricia','Escobar','patricia31@gmail.com',NULL,NULL,0,'Bronce'),(32,'CL0032','Kevin','Castillo','kevin32@gmail.com',NULL,NULL,0,'Bronce'),(33,'CL0033','Camila','Ruiz','camila33@gmail.com',NULL,NULL,0,'Bronce'),(34,'CL0034','Samuel','Luna','samuel34@gmail.com',NULL,NULL,0,'Bronce'),(35,'CL0035','Adriana','Santos','adriana35@gmail.com',NULL,NULL,0,'Bronce'),(36,'CL0036','Esteban','Fuentes','esteban36@gmail.com',NULL,NULL,0,'Bronce'),(37,'CL0037','Marta','Delgado','marta37@gmail.com',NULL,NULL,0,'Bronce'),(38,'CL0038','Roberto','Guerrero','roberto38@gmail.com',NULL,NULL,0,'Bronce'),(39,'CL0039','Gloria','Chavez','gloria39@gmail.com',NULL,NULL,0,'Bronce'),(40,'CL0040','Julio','Zelaya','julio40@gmail.com',NULL,NULL,0,'Bronce'),(41,'CL0041','Cecilia','Benitez','cecilia41@gmail.com',NULL,NULL,0,'Bronce'),(42,'CL0042','Ivan','Montoya','ivan42@gmail.com',NULL,NULL,0,'Bronce'),(43,'CL0043','Silvia','Cortez','silvia43@gmail.com',NULL,NULL,0,'Bronce'),(44,'CL0044','Alfredo','Quinteros','alfredo44@gmail.com',NULL,NULL,0,'Bronce'),(45,'CL0045','Brenda','Figueroa','brenda45@gmail.com',NULL,NULL,0,'Bronce'),(46,'CL0046','Cristian','Portillo','cristian46@gmail.com',NULL,NULL,0,'Bronce'),(47,'CL0047','Lorena','Arias','lorena47@gmail.com',NULL,NULL,0,'Bronce'),(48,'CL0048','Victor','Sanchez','victor48@gmail.com',NULL,NULL,0,'Bronce'),(49,'CL0049','Rosa','Alvarado','rosa49@gmail.com',NULL,NULL,0,'Bronce'),(50,'CL0050','Pablo','Nolasco','pablo50@gmail.com',NULL,NULL,0,'Bronce'),(51,'CL0051','Carlos','Mendoza','carlos.mendoza@gmail.com',NULL,NULL,0,'Bronce'),(52,'CL0052','Gustavo','Martinez','gustavo@gmail.com',NULL,NULL,0,'Bronce'),(53,'CL0053','Danilo','Burgos','Danilo@gmail.com',NULL,NULL,0,'Bronce'),(55,'CL0055','Neftaly','Garcia','garcia@gmail.com',NULL,NULL,0,'Bronce'),(56,'CL0056','Franklyn','Mancia','mancia@gmail.com',NULL,NULL,0,'Bronce'),(57,'CL0057','Santiago','Bautista','santi@gmail.com',NULL,NULL,0,'Bronce');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configuracion`
--

DROP TABLE IF EXISTS `configuracion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `configuracion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_empresa` varchar(150) NOT NULL DEFAULT 'Vortex Epix',
  `direccion` varchar(200) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `nit` varchar(50) DEFAULT NULL,
  `iva_porcentaje` decimal(5,2) NOT NULL DEFAULT '13.00',
  `moneda` varchar(10) NOT NULL DEFAULT '$',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configuracion`
--

LOCK TABLES `configuracion` WRITE;
/*!40000 ALTER TABLE `configuracion` DISABLE KEYS */;
INSERT INTO `configuracion` VALUES (1,'Vortex Epix','Acajutla, Sonsonate, El Salvador','7000-0000','contacto@vortexepix.com','0000-000000-000-0',13.00,'$');
/*!40000 ALTER TABLE `configuracion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_venta`
--

DROP TABLE IF EXISTS `detalle_venta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_venta` (
  `id_detalle` int NOT NULL AUTO_INCREMENT,
  `id_venta` int DEFAULT NULL,
  `id_producto` int DEFAULT NULL,
  `cantidad` int DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_detalle`),
  KEY `id_producto` (`id_producto`),
  KEY `idx_detalle_venta` (`id_venta`),
  CONSTRAINT `detalle_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`),
  CONSTRAINT `detalle_venta_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_venta`
--

LOCK TABLES `detalle_venta` WRITE;
/*!40000 ALTER TABLE `detalle_venta` DISABLE KEYS */;
INSERT INTO `detalle_venta` VALUES (1,1,1,2,1.50,3.00),(2,1,5,1,1.25,1.25),(3,2,1,3,1.50,4.50),(4,2,5,1,1.25,1.25),(5,2,3,1,1.00,1.00),(6,3,1,3,1.50,4.50),(7,3,5,1,1.25,1.25),(8,3,3,1,1.00,1.00),(9,4,3,3,1.00,3.00),(10,5,5,3,1.25,3.75),(11,5,34,1,0.95,0.95),(12,5,18,5,1.75,8.75),(13,6,6,2,2.50,5.00),(14,7,19,4,1.20,4.80),(15,8,5,2,1.25,2.50),(16,9,3,1,1.00,1.00),(17,10,7,3,1.10,3.30),(18,11,8,2,2.20,4.40),(19,12,10,1,1.40,1.40),(20,13,12,5,0.50,2.50),(21,14,15,2,1.15,2.30),(22,15,18,3,1.75,5.25),(23,16,20,1,1.50,1.50),(24,17,22,4,1.30,5.20),(25,18,25,2,1.10,2.20),(26,19,28,1,2.30,2.30),(27,20,30,3,1.80,5.40),(28,21,33,2,1.00,2.00),(29,22,35,1,1.25,1.25),(30,23,38,4,2.10,8.40),(31,24,40,2,1.30,2.60),(32,25,42,3,0.90,2.70),(33,26,45,1,1.10,1.10),(34,27,47,2,1.15,2.30),(35,28,50,3,1.60,4.80),(36,29,6,2,2.50,5.00),(37,30,9,1,1.30,1.30),(38,31,11,4,0.90,3.60),(39,32,13,2,0.75,1.50),(40,33,14,3,0.70,2.10),(41,34,16,1,2.60,2.60),(42,35,17,2,0.85,1.70),(43,36,21,3,1.10,3.30),(44,37,23,1,1.80,1.80),(45,38,24,2,1.70,3.40),(46,39,26,3,2.40,7.20),(47,40,27,2,1.15,2.30),(48,41,29,1,1.60,1.60),(49,42,31,4,1.20,4.80),(50,43,32,2,1.10,2.20),(51,44,34,1,0.95,0.95),(52,45,36,3,2.70,8.10),(53,46,37,2,1.20,2.40),(54,47,39,1,1.00,1.00),(55,48,41,2,0.80,1.60),(56,49,43,3,1.00,3.00),(57,50,44,2,0.95,1.90),(58,51,1,2,1.50,3.00),(59,52,30,5,1.80,9.00),(60,53,NULL,6,NULL,NULL),(61,54,1,5,1.50,7.50),(62,54,3,5,1.00,5.00),(63,55,3,4,1.00,4.00),(64,55,1,4,1.50,6.00),(65,57,1,1,1.50,1.50),(66,58,6,5,2.50,12.50),(67,59,2,1,1.20,1.20),(68,59,3,1,1.00,1.00),(69,59,5,2,1.25,2.50),(70,59,1,1,1.50,1.50),(71,59,9,1,1.30,1.30),(72,60,1,1,1.50,1.50),(73,61,1,1,1.50,1.50),(74,62,1,1,1.50,1.50),(75,63,1,1,1.50,1.50),(76,64,3,1,1.00,1.00),(77,65,2,1,1.20,1.20),(78,65,1,3,1.50,4.50),(79,66,5,1,1.25,1.25),(80,67,5,1,1.25,1.25),(81,68,14,5,0.70,3.50),(82,69,1,1,1.50,1.50),(83,70,1,1,1.50,1.50);
/*!40000 ALTER TABLE `detalle_venta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `devoluciones`
--

DROP TABLE IF EXISTS `devoluciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `devoluciones` (
  `id_devolucion` int NOT NULL AUTO_INCREMENT,
  `id_venta` int DEFAULT NULL,
  `cantidad` int DEFAULT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_devolucion`),
  KEY `id_venta` (`id_venta`),
  CONSTRAINT `devoluciones_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devoluciones`
--

LOCK TABLES `devoluciones` WRITE;
/*!40000 ALTER TABLE `devoluciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `devoluciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empleados`
--

DROP TABLE IF EXISTS `empleados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empleados` (
  `id_empleado` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'Activo',
  `departamento` varchar(100) DEFAULT NULL,
  `salario` decimal(10,2) DEFAULT NULL,
  `fecha_contratacion` date DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(100) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_empleado`),
  UNIQUE KEY `empleados_usuario_unique` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empleados`
--

LOCK TABLES `empleados` WRITE;
/*!40000 ALTER TABLE `empleados` DISABLE KEYS */;
INSERT INTO `empleados` VALUES (1,'Steve','Hercules','steve','$2y$12$jVI6dMoYKVs6Uk1b2xlXF.dOc7UHII0uMIpsGSwkl88wjkq5KSBbS','Administrador','Activo',NULL,NULL,NULL,'steve@empresa.com','7000-0001',NULL),(2,'Diego','Ramirez','diego','$2y$12$jVI6dMoYKVs6Uk1b2xlXF.dOc7UHII0uMIpsGSwkl88wjkq5KSBbS','Cajero','Activo',NULL,NULL,NULL,'diego@empresa.com','7000-0002',NULL),(3,'Rudy','Aguilar','rudy','$2y$12$jVI6dMoYKVs6Uk1b2xlXF.dOc7UHII0uMIpsGSwkl88wjkq5KSBbS','Cajero','Activo',NULL,NULL,NULL,'rudy@empresa.com','7000-0003',NULL),(4,'Alberto','Orellana','alberto','$2y$12$jVI6dMoYKVs6Uk1b2xlXF.dOc7UHII0uMIpsGSwkl88wjkq5KSBbS','Supervisor','Activo',NULL,NULL,NULL,'alberto@empresa.com','7000-0004',NULL),(5,'Alejandro','Flores','alejandro','$2y$12$jVI6dMoYKVs6Uk1b2xlXF.dOc7UHII0uMIpsGSwkl88wjkq5KSBbS','Inventario','Activo',NULL,NULL,NULL,'alejandro@empresa.com','7000-0005',NULL);
/*!40000 ALTER TABLE `empleados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `factura`
--

DROP TABLE IF EXISTS `factura`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `factura` (
  `id_factura` int NOT NULL AUTO_INCREMENT,
  `metodo_pago` varchar(50) NOT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `numero_factura` int DEFAULT NULL,
  `fecha` date NOT NULL,
  `id_cliente` int DEFAULT NULL,
  `id_empleado` int DEFAULT NULL,
  PRIMARY KEY (`id_factura`),
  KEY `id_cliente` (`id_cliente`),
  KEY `id_empleado` (`id_empleado`),
  CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  CONSTRAINT `factura_ibfk_2` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `factura`
--

LOCK TABLES `factura` WRITE;
/*!40000 ALTER TABLE `factura` DISABLE KEYS */;
INSERT INTO `factura` VALUES (1,'Efectivo',4.25,NULL,'2026-04-12',1,2),(2,'Efectivo',6.75,NULL,'2026-04-12',1,2),(3,'Efectivo',6.75,NULL,'2026-04-12',1,2),(4,'Efectivo',3.00,NULL,'2026-04-12',4,2),(5,'Tarjeta',13.45,NULL,'2026-04-12',5,2),(6,'Efectivo',5.00,NULL,'2026-04-13',6,2),(7,'Efectivo',4.80,NULL,'2026-04-13',7,2),(8,'Tarjeta',2.50,NULL,'2026-04-13',8,3),(9,'Efectivo',1.00,NULL,'2026-04-13',9,2),(10,'Tarjeta',3.30,NULL,'2026-04-13',10,2),(11,'Efectivo',4.40,NULL,'2026-04-13',11,3),(12,'Tarjeta',1.40,NULL,'2026-04-13',12,2),(13,'Efectivo',2.50,NULL,'2026-04-13',13,3),(14,'Efectivo',2.30,NULL,'2026-04-13',14,2),(15,'Tarjeta',5.25,NULL,'2026-04-13',15,2),(16,'Efectivo',1.50,NULL,'2026-04-13',16,3),(17,'Tarjeta',5.20,NULL,'2026-04-13',17,3),(18,'Efectivo',2.20,NULL,'2026-04-13',18,2),(19,'Tarjeta',2.30,NULL,'2026-04-13',19,2),(20,'Efectivo',5.40,NULL,'2026-04-13',20,3),(21,'Tarjeta',2.00,NULL,'2026-04-13',21,2),(22,'Efectivo',1.25,NULL,'2026-04-13',22,3),(23,'Tarjeta',8.40,NULL,'2026-04-13',23,2),(24,'Efectivo',2.60,NULL,'2026-04-13',24,2),(25,'Tarjeta',2.70,NULL,'2026-04-13',25,3),(26,'Efectivo',1.10,NULL,'2026-04-13',26,2),(27,'Tarjeta',2.30,NULL,'2026-04-13',27,3),(28,'Efectivo',4.80,NULL,'2026-04-13',28,2),(29,'Tarjeta',5.00,NULL,'2026-04-13',29,2),(30,'Efectivo',1.30,NULL,'2026-04-13',30,3),(31,'Tarjeta',3.60,NULL,'2026-04-13',31,2),(32,'Efectivo',1.50,NULL,'2026-04-13',32,2),(33,'Tarjeta',2.10,NULL,'2026-04-13',33,3),(34,'Efectivo',2.60,NULL,'2026-04-13',34,2),(35,'Tarjeta',1.70,NULL,'2026-04-13',35,3),(36,'Efectivo',3.30,NULL,'2026-04-13',36,2),(37,'Tarjeta',1.80,NULL,'2026-04-13',37,2),(38,'Efectivo',3.40,NULL,'2026-04-13',38,3),(39,'Tarjeta',7.20,NULL,'2026-04-13',39,3),(40,'Efectivo',2.30,NULL,'2026-04-13',40,2),(41,'Tarjeta',1.60,NULL,'2026-04-13',41,2),(42,'Efectivo',4.80,NULL,'2026-04-13',42,3),(43,'Tarjeta',2.20,NULL,'2026-04-13',43,2),(44,'Efectivo',0.95,NULL,'2026-04-13',44,3),(45,'Tarjeta',8.10,NULL,'2026-04-13',45,2),(46,'Efectivo',2.40,NULL,'2026-04-13',46,2),(47,'Tarjeta',1.00,NULL,'2026-04-13',47,3),(48,'Efectivo',1.60,NULL,'2026-04-13',48,2),(49,'Tarjeta',3.00,NULL,'2026-04-13',49,3),(50,'Efectivo',1.90,NULL,'2026-04-13',50,2),(51,'Tarjeta',3.00,NULL,'2026-04-14',51,2),(52,'Efectivo',9.00,NULL,'2026-04-14',52,3),(53,'Efectivo',NULL,NULL,'2026-04-14',53,3),(54,'Efectivo',12.50,NULL,'2026-04-14',56,2),(55,'Efectivo',10.00,NULL,'2026-04-14',56,3),(57,'Efectivo',1.50,NULL,'2026-04-14',57,3),(58,'Efectivo',12.50,1001,'2026-06-08',NULL,1),(59,'Tarjeta',7.50,1002,'2026-06-08',NULL,1),(60,'Efectivo',1.50,1003,'2026-06-08',NULL,2),(61,'Efectivo',1.50,1004,'2026-06-08',NULL,1),(62,'Efectivo',1.50,1005,'2026-06-08',NULL,1),(63,'Efectivo',1.50,1006,'2026-06-08',NULL,1),(64,'Efectivo',1.00,1007,'2026-06-11',NULL,1),(65,'Efectivo',5.70,1008,'2026-06-12',NULL,1),(66,'Efectivo',1.25,1009,'2026-06-12',NULL,1),(67,'Efectivo',1.25,1010,'2026-06-13',NULL,1),(68,'Efectivo',3.50,1011,'2026-06-13',NULL,1),(69,'Tarjeta',1.50,1012,'2026-06-15',NULL,3),(70,'Efectivo',1.50,1013,'2026-06-15',NULL,3);
/*!40000 ALTER TABLE `factura` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `historial_cambios`
--

DROP TABLE IF EXISTS `historial_cambios`;
/*!50001 DROP VIEW IF EXISTS `historial_cambios`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `historial_cambios` AS SELECT 
 1 AS `id_venta`,
 1 AS `fecha`,
 1 AS `total`,
 1 AS `impuesto`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos` (
  `id_producto` int NOT NULL AUTO_INCREMENT,
  `categoria` varchar(100) DEFAULT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `id_proveedor` int DEFAULT NULL,
  `stock` int DEFAULT '0',
  `precio_con_iva` decimal(10,2) DEFAULT NULL,
  `codigo_barras` varchar(13) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `stock_minimo` int NOT NULL DEFAULT '20',
  `stock_maximo` int NOT NULL DEFAULT '100',
  PRIMARY KEY (`id_producto`),
  UNIQUE KEY `codigo_barras` (`codigo_barras`),
  KEY `id_proveedor` (`id_proveedor`),
  KEY `idx_producto` (`nombre`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (1,'Alimentos','Diana','2026-09-03','Arroz',1.50,1,28,1.70,'750100000001','Arroz.jpg',20,100),(2,'Alimentos','Diana','2026-09-20','Frijoles',1.20,2,28,1.36,'750100000002','Frijol.jpg',20,100),(3,'Bebidas','CocaCola','2026-10-07','Soda 1L',1.00,3,33,1.13,'750100000003','Soda.jpg',20,100),(4,'Bebidas','Pepsi','2026-10-24','Soda Pepsi',0.95,4,50,1.07,'750100000004','Pepsi.jpg',20,100),(5,'Snacks','Lays','2026-11-10','Papas',1.25,5,38,1.41,'750100000005','Papaslays.jpg',20,100),(6,'Snacks','Pringles','2026-11-27','Pringles',2.50,6,41,2.83,'750100000006','pringles.jpg',20,100),(7,'Lacteos','Foremost','2026-12-14','Leche',1.10,7,47,1.24,'750100000007','foremost-leche.jpg',20,100),(8,'Lacteos','DosPinos','2026-12-31','Queso',2.20,8,48,2.49,'750100000008','Queso.jpg',20,100),(9,'Panaderia','Bimbo','2027-01-17','Pan',1.30,9,48,1.47,'750100000009','bimbo.jpg',20,100),(10,'Panaderia','Bimbo','2027-02-03','Pan Integral',1.40,10,49,1.58,'750100000010','Bimbo-integral.jpg',20,100),(11,'Alimentos','Diana','2027-02-20','Azucar',0.90,1,46,1.02,'750100000011','Azucar.jpg',20,100),(12,'Alimentos','Diana','2027-03-09','Sal',0.50,2,45,0.57,'750100000012','Sal.jpg',20,100),(13,'Bebidas','CocaCola','2027-03-26','Coca Cola Lata',0.75,3,48,0.85,'750100000013','Lata.jpg',20,100),(14,'Bebidas','Pepsi','2027-04-12','Pepsi Lata',0.70,4,42,0.79,'750100000014','Pepsilata.jpg',20,100),(15,'Snacks','Lays','2027-04-29','Nachos',1.15,5,48,1.30,'750100000015','Nachos.jpg',20,100),(16,'Snacks','Pringles','2027-05-16','Papitas BBQ',2.60,6,49,2.94,'750100000016','BBQ.jpg',20,100),(17,'Lacteos','Foremost','2027-06-02','Yogurt',0.85,7,48,0.96,'750100000017','Yogurt.jpg',20,100),(18,'Lacteos','DosPinos','2027-06-19','Crema',1.75,8,42,1.98,'750100000018','Crema.jpg',20,100),(19,'Panaderia','Bimbo','2027-07-06','Pan Dulce',1.20,9,46,1.36,'750100000019','Pandulce.jpg',20,100),(20,'Panaderia','Bimbo','2027-07-23','Croissant',1.50,10,49,1.70,'750100000020','bimbo-croissant.jpg',20,100),(21,'Alimentos','Diana','2027-08-09','Harina',1.10,1,47,1.24,'750100000021','Harina.jpg',20,100),(22,'Alimentos','Diana','2027-08-26','Avena',1.30,2,46,1.47,'750100000022','Avena.jpg',20,100),(23,'Bebidas','CocaCola','2027-09-12','Coca Cola 2L',1.80,3,49,2.03,'750100000023','Coca2l.jpg',20,100),(24,'Bebidas','Pepsi','2027-09-29','Pepsi 2L',1.70,4,48,1.92,'750100000024','Pepsi2ljpg.jpg',20,100),(25,'Snacks','Lays','2027-10-16','Chips',1.10,5,48,1.24,'750100000025','Chips.jpg',20,100),(26,'Snacks','Pringles','2027-11-02','Pringles Original',2.40,6,47,2.71,'750100000026','67g-Pringles-original-potato-chips-2048x2048.jpeg',20,100),(27,'Lacteos','Foremost','2027-11-19','Leche Descremada',1.15,7,48,1.30,'750100000027','lescheDecremadaForemost.png',20,100),(28,'Lacteos','DosPinos','2027-12-06','Queso Fresco',2.30,8,49,2.60,'750100000028','quesofrescoDosPinos.jpg',20,100),(29,'Panaderia','Bimbo','2027-12-23','Pan Molde',1.60,9,49,1.81,'750100000029','PanMoldeBimbo.jpg',20,100),(30,'Panaderia','Bimbo','2028-01-09','Donas',1.80,10,42,2.03,'750100000030','donasBimbo.jpeg',20,100),(31,'Alimentos','Diana','2028-01-26','Lentejas',1.20,1,46,1.36,'750100000031','Lenteja-DIANA.jpg',20,100),(32,'Alimentos','Diana','2028-02-12','Maiz',1.10,2,48,1.24,'750100000032','maiz_diana.png',20,100),(33,'Bebidas','CocaCola','2028-02-29','Sprite',1.00,3,48,1.13,'750100000033','sprite-coca-cola.png',20,100),(34,'Bebidas','Pepsi','2028-03-17','7UP',0.95,4,48,1.07,'750100000034','pepsi7up.png',20,100),(35,'Snacks','Lays','2028-04-03','Doritos',1.25,5,49,1.41,'750100000035','doritos1.jpg',20,100),(36,'Snacks','Pringles','2028-04-20','Pringles Queso',2.70,6,47,3.05,'750100000036','pringles-queso.png',20,100),(37,'Lacteos','Foremost','2028-05-07','Leche Entera',1.20,7,48,1.36,'750100000037','leche-entera-foremost.png',20,100),(38,'Lacteos','DosPinos','2026-08-23','Mantequilla',2.10,8,46,2.37,'750100000038','mantequilla-dos-pinos.png',20,100),(39,'Panaderia','Bimbo','2026-09-09','Pan Frances',1.00,9,49,1.13,'750100000039','panfrancesbimbo.png',20,100),(40,'Panaderia','Bimbo','2026-09-26','Pan Blanco',1.30,10,48,1.47,'750100000040','pan-blanco-bimbo.png',20,100),(41,'Alimentos','Diana','2026-10-13','Sopa',0.80,1,48,0.90,'750100000041','sopa-diana.jpg',20,100),(42,'Alimentos','Diana','2026-10-30','Fideos',0.90,2,47,1.02,'750100000042','fideos-diana-7-4.jpg',20,100),(43,'Bebidas','CocaCola','2026-11-16','Fanta',1.00,3,47,1.13,'750100000043','fanta.png',20,100),(44,'Bebidas','Pepsi','2026-12-03','Mirinda',0.95,4,48,1.07,'750100000044','mirinda.png',20,100),(45,'Snacks','Lays','2026-12-20','Churritos',1.10,5,49,1.24,'750100000045','churritos.png',20,100),(46,'Snacks','Pringles','2027-01-06','Pringles Picante',2.80,6,50,3.16,'750100000046','pringles-picante.png',20,100),(47,'Lacteos','Foremost','2027-01-23','Leche Light',1.15,7,48,1.30,'750100000047','leche-ligh.png',20,100),(48,'Lacteos','DosPinos','2027-02-09','Queso Cheddar',2.50,8,50,2.83,'750100000048','queso-chedar-dos-pinos.png',20,100),(49,'Panaderia','Bimbo','2027-02-26','Pan Hamburguesa',1.70,9,39,1.92,'750100000049','pan-hamburguesa-bimbo.png',20,100),(50,'Panaderia','Bimbo','2027-03-15','Pan Hotdog',1.60,10,51,1.81,'750100000050','Pan-Bimbo-Boller-Hot-Dog3-30474.jpg',20,100);
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `productos_bajo_stock`
--

DROP TABLE IF EXISTS `productos_bajo_stock`;
/*!50001 DROP VIEW IF EXISTS `productos_bajo_stock`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `productos_bajo_stock` AS SELECT 
 1 AS `id_producto`,
 1 AS `categoria`,
 1 AS `marca`,
 1 AS `fecha_vencimiento`,
 1 AS `nombre`,
 1 AS `precio`,
 1 AS `id_proveedor`,
 1 AS `stock`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `proveedores` (
  `id_proveedor` int NOT NULL AUTO_INCREMENT,
  `nombre_empresa` varchar(100) DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `telefono` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_proveedor`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedores`
--

LOCK TABLES `proveedores` WRITE;
/*!40000 ALTER TABLE `proveedores` DISABLE KEYS */;
INSERT INTO `proveedores` VALUES (1,'Distribuidora Central',NULL,'San Salvador','22223333','prov1@gmail.com'),(2,'Alimentos SA',NULL,'Santa Tecla','22224444','prov2@gmail.com'),(3,'Productos del Valle',NULL,'Soyapango','22225555','prov3@gmail.com'),(4,'Importadora Norte',NULL,'Apopa','22226666','prov4@gmail.com'),(5,'Global Foods',NULL,'Ilopango','22227777','prov5@gmail.com'),(6,'Distribuidora Sur',NULL,'San Miguel','22228888','prov6@gmail.com'),(7,'Market Supply',NULL,'Santa Ana','22229999','prov7@gmail.com'),(8,'Comercializadora Uno',NULL,'La Libertad','22330000','prov8@gmail.com'),(9,'Distribuciones Express',NULL,'Cuscatl?n','22331111','prov9@gmail.com'),(10,'Proveedor Max',NULL,'Sonsonate','22332222','prov10@gmail.com');
/*!40000 ALTER TABLE `proveedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `reporte_mensual`
--

DROP TABLE IF EXISTS `reporte_mensual`;
/*!50001 DROP VIEW IF EXISTS `reporte_mensual`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `reporte_mensual` AS SELECT 
 1 AS `total_ventas`,
 1 AS `ingresos`,
 1 AS `impuestos`,
 1 AS `ganancias`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `ventas`
--

DROP TABLE IF EXISTS `ventas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ventas` (
  `id_venta` int NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `id_factura` int DEFAULT NULL,
  `id_empleado` int DEFAULT NULL,
  `impuesto` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_venta`),
  KEY `id_factura` (`id_factura`),
  KEY `id_empleado` (`id_empleado`),
  KEY `idx_ventas_fecha` (`fecha`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `factura` (`id_factura`),
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas`
--

LOCK TABLES `ventas` WRITE;
/*!40000 ALTER TABLE `ventas` DISABLE KEYS */;
INSERT INTO `ventas` VALUES (1,'2026-04-12 11:02:09',4.25,1,2,0.55),(2,'2026-04-12 11:24:57',6.75,2,2,0.88),(3,'2026-04-12 12:11:53',6.75,3,2,0.88),(4,'2026-04-12 14:48:09',3.00,4,2,0.39),(5,'2026-04-12 15:20:30',13.45,5,2,1.75),(6,'2026-04-13 19:16:24',5.00,6,2,0.65),(7,'2026-04-13 19:35:33',4.80,7,2,0.62),(8,'2026-04-13 21:51:03',2.50,8,3,0.33),(9,'2026-04-13 21:51:04',1.00,9,2,0.13),(10,'2026-04-13 21:51:04',3.30,10,2,0.43),(11,'2026-04-13 21:51:04',4.40,11,3,0.57),(12,'2026-04-13 21:51:04',1.40,12,2,0.18),(13,'2026-04-13 21:51:04',2.50,13,3,0.33),(14,'2026-04-13 21:51:04',2.30,14,2,0.30),(15,'2026-04-13 21:51:04',5.25,15,2,0.68),(16,'2026-04-13 21:51:04',1.50,16,3,0.20),(17,'2026-04-13 21:51:04',5.20,17,3,0.68),(18,'2026-04-13 21:51:04',2.20,18,2,0.29),(19,'2026-04-13 21:51:04',2.30,19,2,0.30),(20,'2026-04-13 21:51:04',5.40,20,3,0.70),(21,'2026-04-13 21:51:04',2.00,21,2,0.26),(22,'2026-04-13 21:51:04',1.25,22,3,0.16),(23,'2026-04-13 21:51:04',8.40,23,2,1.09),(24,'2026-04-13 21:51:04',2.60,24,2,0.34),(25,'2026-04-13 21:51:04',2.70,25,3,0.35),(26,'2026-04-13 21:51:04',1.10,26,2,0.14),(27,'2026-04-13 21:51:04',2.30,27,3,0.30),(28,'2026-04-13 21:51:04',4.80,28,2,0.62),(29,'2026-04-13 21:51:04',5.00,29,2,0.65),(30,'2026-04-13 21:51:04',1.30,30,3,0.17),(31,'2026-04-13 21:51:04',3.60,31,2,0.47),(32,'2026-04-13 21:51:04',1.50,32,2,0.20),(33,'2026-04-13 21:51:04',2.10,33,3,0.27),(34,'2026-04-13 21:51:04',2.60,34,2,0.34),(35,'2026-04-13 21:51:04',1.70,35,3,0.22),(36,'2026-04-13 21:51:04',3.30,36,2,0.43),(37,'2026-04-13 21:51:04',1.80,37,2,0.23),(38,'2026-04-13 21:51:04',3.40,38,3,0.44),(39,'2026-04-13 21:51:04',7.20,39,3,0.94),(40,'2026-04-13 21:51:04',2.30,40,2,0.30),(41,'2026-04-13 21:51:04',1.60,41,2,0.21),(42,'2026-04-13 21:51:04',4.80,42,3,0.62),(43,'2026-04-13 21:51:04',2.20,43,2,0.29),(44,'2026-04-13 21:51:04',0.95,44,3,0.12),(45,'2026-04-13 21:51:04',8.10,45,2,1.05),(46,'2026-04-13 21:51:04',2.40,46,2,0.31),(47,'2026-04-13 21:51:04',1.00,47,3,0.13),(48,'2026-04-13 21:51:04',1.60,48,2,0.21),(49,'2026-04-13 21:51:04',3.00,49,3,0.39),(50,'2026-04-13 21:51:21',1.90,50,2,0.25),(51,'2026-04-14 00:01:46',3.00,51,2,0.39),(52,'2026-04-14 08:26:35',9.00,52,3,1.17),(53,'2026-04-14 09:35:31',NULL,53,3,NULL),(54,'2026-04-14 11:14:20',12.50,54,2,1.63),(55,'2026-04-14 11:16:51',10.00,55,3,1.30),(57,'2026-04-14 11:40:51',1.50,57,3,0.20),(58,'2026-06-08 00:49:36',12.50,58,1,1.63),(59,'2026-06-08 01:11:56',7.50,59,1,0.98),(60,'2026-06-08 13:46:37',1.50,60,2,0.20),(61,'2026-06-08 15:15:18',1.50,61,1,0.20),(62,'2026-06-08 15:19:21',1.50,62,1,0.20),(63,'2026-06-08 15:23:51',1.50,63,1,0.20),(64,'2026-06-11 20:52:31',1.00,64,1,0.13),(65,'2026-06-12 13:23:35',5.70,65,1,0.74),(66,'2026-06-12 13:29:08',1.25,66,1,0.16),(67,'2026-06-13 03:02:54',1.25,67,1,0.16),(68,'2026-06-13 21:18:46',3.50,68,1,0.46),(69,'2026-06-15 16:56:26',1.50,69,3,0.20),(70,'2026-06-15 16:59:21',1.50,70,3,0.20);
/*!40000 ALTER TABLE `ventas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `vista_alerta_productos`
--

DROP TABLE IF EXISTS `vista_alerta_productos`;
/*!50001 DROP VIEW IF EXISTS `vista_alerta_productos`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vista_alerta_productos` AS SELECT 
 1 AS `id_producto`,
 1 AS `nombre`,
 1 AS `categoria`,
 1 AS `stock`,
 1 AS `fecha_vencimiento`,
 1 AS `dias_restantes`,
 1 AS `estado`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `alerta_stock`
--

/*!50001 DROP VIEW IF EXISTS `alerta_stock`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `alerta_stock` AS select `productos`.`nombre` AS `nombre`,`productos`.`stock` AS `stock` from `productos` where (`productos`.`stock` <= 5) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `historial_cambios`
--

/*!50001 DROP VIEW IF EXISTS `historial_cambios`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `historial_cambios` AS select `ventas`.`id_venta` AS `id_venta`,`ventas`.`fecha` AS `fecha`,`ventas`.`total` AS `total`,`ventas`.`impuesto` AS `impuesto` from `ventas` order by `ventas`.`fecha` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `productos_bajo_stock`
--

/*!50001 DROP VIEW IF EXISTS `productos_bajo_stock`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `productos_bajo_stock` AS select `productos`.`id_producto` AS `id_producto`,`productos`.`categoria` AS `categoria`,`productos`.`marca` AS `marca`,`productos`.`fecha_vencimiento` AS `fecha_vencimiento`,`productos`.`nombre` AS `nombre`,`productos`.`precio` AS `precio`,`productos`.`id_proveedor` AS `id_proveedor`,`productos`.`stock` AS `stock` from `productos` where (`productos`.`stock` <= 5) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `reporte_mensual`
--

/*!50001 DROP VIEW IF EXISTS `reporte_mensual`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `reporte_mensual` AS select count(`ventas`.`id_venta`) AS `total_ventas`,sum(`ventas`.`total`) AS `ingresos`,sum(`ventas`.`impuesto`) AS `impuestos`,sum((`ventas`.`total` - `ventas`.`impuesto`)) AS `ganancias` from `ventas` where ((month(`ventas`.`fecha`) = month(curdate())) and (year(`ventas`.`fecha`) = year(curdate()))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vista_alerta_productos`
--

/*!50001 DROP VIEW IF EXISTS `vista_alerta_productos`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vista_alerta_productos` AS select `productos`.`id_producto` AS `id_producto`,`productos`.`nombre` AS `nombre`,`productos`.`categoria` AS `categoria`,`productos`.`stock` AS `stock`,`productos`.`fecha_vencimiento` AS `fecha_vencimiento`,(to_days(`productos`.`fecha_vencimiento`) - to_days(curdate())) AS `dias_restantes`,(case when (`productos`.`fecha_vencimiento` < curdate()) then 'VENCIDO' when (`productos`.`fecha_vencimiento` <= (curdate() + interval 7 day)) then 'POR VENCER' else 'VIGENTE' end) AS `estado` from `productos` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-18 21:16:28
