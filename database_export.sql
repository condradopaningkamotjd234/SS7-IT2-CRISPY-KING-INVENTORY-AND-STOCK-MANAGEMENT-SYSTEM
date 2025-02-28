-- MySQL dump 10.19  Distrib 10.3.39-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: mariadb
-- ------------------------------------------------------
-- Server version	10.3.39-MariaDB-0ubuntu0.20.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `OrderDetails`
--

DROP TABLE IF EXISTS `OrderDetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OrderDetails` (
  `OrderDetailID` varchar(40) NOT NULL,
  `OrderID` varchar(40) NOT NULL,
  `productID` varchar(40) NOT NULL,
  `Quantity` varchar(40) DEFAULT NULL,
  `Cost` varchar(40) NOT NULL,
  `UserID` varchar(40) NOT NULL,
  PRIMARY KEY (`OrderDetailID`,`UserID`),
  KEY `Orders_OrderDetails` (`OrderID`),
  CONSTRAINT `Orders_OrderDetails` FOREIGN KEY (`OrderID`) REFERENCES `Orders` (`OrderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `OrderDetails`
--

LOCK TABLES `OrderDetails` WRITE;
/*!40000 ALTER TABLE `OrderDetails` DISABLE KEYS */;
/*!40000 ALTER TABLE `OrderDetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Orders`
--

DROP TABLE IF EXISTS `Orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Orders` (
  `OrderID` varchar(40) NOT NULL,
  `SupplierID` varchar(40) NOT NULL,
  `OrderDate` varchar(40) DEFAULT NULL,
  `TotalCost` varchar(40) DEFAULT NULL,
  `Status` varchar(40) DEFAULT NULL,
  `UserID` varchar(40) NOT NULL,
  PRIMARY KEY (`OrderID`),
  KEY `Users_Orders` (`UserID`),
  CONSTRAINT `Users_Orders` FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`),
  CONSTRAINT `Users_Orders_Relations` FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Orders`
--

LOCK TABLES `Orders` WRITE;
/*!40000 ALTER TABLE `Orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `Orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Product`
--

DROP TABLE IF EXISTS `Product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product` (
  `ProductID` varchar(40) NOT NULL,
  `Name` varchar(40) DEFAULT NULL,
  `Category` varchar(40) DEFAULT NULL,
  `UnitPrice` varchar(40) DEFAULT NULL,
  `StockLevel` varchar(40) DEFAULT NULL,
  `ExpriyDate` varchar(40) DEFAULT NULL,
  `OrderDetailID` varchar(40) NOT NULL,
  `OrderID` varchar(40) NOT NULL,
  `UserID` varchar(40) NOT NULL,
  PRIMARY KEY (`ProductID`,`OrderID`,`UserID`),
  KEY `OrderDetails_Product` (`OrderDetailID`,`UserID`),
  CONSTRAINT `OrderDetails_Product` FOREIGN KEY (`OrderDetailID`, `UserID`) REFERENCES `OrderDetails` (`OrderDetailID`, `UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Product`
--

LOCK TABLES `Product` WRITE;
/*!40000 ALTER TABLE `Product` DISABLE KEYS */;
/*!40000 ALTER TABLE `Product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SaleDetails`
--

DROP TABLE IF EXISTS `SaleDetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SaleDetails` (
  `SaleDetailID` varchar(40) NOT NULL,
  `SaleID` varchar(40) NOT NULL,
  `Quantity` varchar(40) DEFAULT NULL,
  `Price` varchar(40) DEFAULT NULL,
  `OrderID` varchar(40) NOT NULL,
  `EmployedID` varchar(40) NOT NULL,
  PRIMARY KEY (`SaleDetailID`,`EmployedID`),
  KEY `Sales_SaleDetails` (`SaleID`,`OrderID`),
  CONSTRAINT `Sales_SaleDetails` FOREIGN KEY (`SaleID`, `OrderID`) REFERENCES `Sales` (`SaleID`, `OrderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SaleDetails`
--

LOCK TABLES `SaleDetails` WRITE;
/*!40000 ALTER TABLE `SaleDetails` DISABLE KEYS */;
/*!40000 ALTER TABLE `SaleDetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Sales`
--

DROP TABLE IF EXISTS `Sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Sales` (
  `SaleID` varchar(40) NOT NULL,
  `Date` varchar(40) DEFAULT NULL,
  `TotalAmount` varchar(40) DEFAULT NULL,
  `EmployedID` varchar(40) DEFAULT NULL,
  `UserID` varchar(40) NOT NULL,
  `ProductID` varchar(40) NOT NULL,
  `OrderDetailID` varchar(40) NOT NULL,
  `OrderID` varchar(40) NOT NULL,
  PRIMARY KEY (`SaleID`,`OrderID`),
  KEY `Users_Sales` (`UserID`),
  CONSTRAINT `Users_Sales` FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Sales`
--

LOCK TABLES `Sales` WRITE;
/*!40000 ALTER TABLE `Sales` DISABLE KEYS */;
/*!40000 ALTER TABLE `Sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Stock`
--

DROP TABLE IF EXISTS `Stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Stock` (
  `StockID` varchar(40) NOT NULL,
  `ProductID` varchar(40) NOT NULL,
  `Quantity` varchar(40) DEFAULT NULL,
  `Status` varchar(40) DEFAULT NULL,
  `DateAdded` varchar(40) DEFAULT NULL,
  `LastUpdated` varchar(40) DEFAULT NULL,
  `UserID` varchar(40) NOT NULL,
  PRIMARY KEY (`StockID`,`UserID`),
  KEY `Users_Stock` (`UserID`),
  CONSTRAINT `Users_Stock` FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Stock`
--

LOCK TABLES `Stock` WRITE;
/*!40000 ALTER TABLE `Stock` DISABLE KEYS */;
/*!40000 ALTER TABLE `Stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `StockAlerts`
--

DROP TABLE IF EXISTS `StockAlerts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `StockAlerts` (
  `AlertID` varchar(40) NOT NULL,
  `AlertType` varchar(40) DEFAULT NULL,
  `AlertDate` varchar(40) DEFAULT NULL,
  `Status` varchar(40) DEFAULT NULL,
  `StockID` varchar(40) NOT NULL,
  `UserID` varchar(40) NOT NULL,
  PRIMARY KEY (`AlertID`,`UserID`),
  KEY `Stock_StockAlerts` (`StockID`,`UserID`),
  CONSTRAINT `Monitors_MonitorsAlerts` FOREIGN KEY (`StockID`, `UserID`) REFERENCES `Stock` (`StockID`, `UserID`),
  CONSTRAINT `Stock_StockAlerts` FOREIGN KEY (`StockID`, `UserID`) REFERENCES `Stock` (`StockID`, `UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `StockAlerts`
--

LOCK TABLES `StockAlerts` WRITE;
/*!40000 ALTER TABLE `StockAlerts` DISABLE KEYS */;
/*!40000 ALTER TABLE `StockAlerts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Suppliers`
--

DROP TABLE IF EXISTS `Suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Suppliers` (
  `SuplierID` varchar(40) NOT NULL,
  `Name` varchar(40) DEFAULT NULL,
  `ContactInfo` varchar(40) DEFAULT NULL,
  `Address` varchar(40) DEFAULT NULL,
  `OrderID` varchar(40) NOT NULL,
  `UserID` varchar(40) NOT NULL,
  PRIMARY KEY (`SuplierID`,`OrderID`,`UserID`),
  KEY `Orders_Suppliers` (`OrderID`),
  CONSTRAINT `Orders_Suppliers` FOREIGN KEY (`OrderID`) REFERENCES `Orders` (`OrderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Suppliers`
--

LOCK TABLES `Suppliers` WRITE;
/*!40000 ALTER TABLE `Suppliers` DISABLE KEYS */;
/*!40000 ALTER TABLE `Suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Users` (
  `UserID` varchar(40) NOT NULL,
  `Name` varchar(40) DEFAULT NULL,
  `Role` varchar(40) DEFAULT NULL,
  `Username` varchar(40) DEFAULT NULL,
  `Password` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_form`
--

DROP TABLE IF EXISTS `user_form`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_form` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_form`
--

LOCK TABLES `user_form` WRITE;
/*!40000 ALTER TABLE `user_form` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_form` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-02-28  6:46:46
