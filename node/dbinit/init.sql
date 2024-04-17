-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2024 at 08:58 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shopdb`
--
CREATE DATABASE IF NOT EXISTS `shopdb` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `shopdb`;

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `Username` varchar(50) NOT NULL,
  `Amphure` varchar(20) NOT NULL,
  `Province` varchar(20) NOT NULL,
  `Zip` varchar(10) NOT NULL,
  `AddressID` varchar(10) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Tel` varchar(10) NOT NULL,
  `Detail` varchar(50) NOT NULL,
  `District` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`Username`, `Amphure`, `Province`, `Zip`, `AddressID`, `Name`, `Tel`, `Detail`, `District`) VALUES
('GUEST', 'บางกรวย', 'นนทบุรี', '11130', 'A28', 'sfd', 'sdfds', 'dsfsdf', 'บางสีทอง'),
('64050080@kmitl.ac.th', 'เมืองนครนายก', 'นครนายก', '26000', 'A44', 'ณัฐพล แซ่เตียว', '0987054513', '123', 'พรหมณี'),
('GUEST', 'บ้านนา', 'นครนายก', '26110', 'A45', 'asd', 'asd', 'asd', 'บ้านพริก'),
('007', 'บางบัวทอง', 'นนทบุรี', '11110', 'A46', 'สมศัก', '0846531553', '154', 'บางบัวทอง'),
('GUEST', 'เมืองฉะเชิงเทรา', 'ฉะเชิงเทรา', '24000', 'A47', 'สมศัก', '0846531553', '154', 'คลองเปรง'),
('007', 'เขตบางกอกใหญ่', 'กรุงเทพมหานคร', '10600', 'A48', 'ldf', '0604564564', '6465', 'วัดอรุณ');

-- --------------------------------------------------------

--
-- Table structure for table `addresslist`
--

CREATE TABLE `addresslist` (
  `AddressID` varchar(4) NOT NULL,
  `OrderID` varchar(4) NOT NULL,
  `Type` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addresslist`
--

INSERT INTO `addresslist` (`AddressID`, `OrderID`, `Type`) VALUES
('A46', 'OR2', 'Buy'),
('A46', 'OR2', 'Bill'),
('A46', 'OR2', 'Ship'),
('A46', 'OR3', 'Buy'),
('A46', 'OR3', 'Bill'),
('A46', 'OR3', 'Ship'),
('A46', 'OR4', 'Buy'),
('A46', 'OR4', 'Bill'),
('A46', 'OR4', 'Ship'),
('A46', 'OR5', 'Buy'),
('A46', 'OR5', 'Bill'),
('A46', 'OR5', 'Ship'),
('A46', 'OR6', 'Buy'),
('A46', 'OR6', 'Bill'),
('A46', 'OR6', 'Ship'),
('A48', 'OR7', 'Buy'),
('A48', 'OR7', 'Bill'),
('A48', 'OR7', 'Ship');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `Username` varchar(50) NOT NULL,
  `ProductID` varchar(4) NOT NULL,
  `Qty` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`Username`, `ProductID`, `Qty`) VALUES
('007', 'P003', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `FName` varchar(20) NOT NULL,
  `LName` varchar(20) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Tel` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`FName`, `LName`, `Password`, `Username`, `Email`, `Tel`) VALUES
('Emily', 'Brown', '$2y$10$ED5g9kHzRUEe1/JKQZfHQ.aLOpy/VolVQKg7zyratrikvZlxXMKhC', '007', '', ''),
('asd', 'asd', '$argon2i$v=19$m=65536,t=4,p=1$QUxHc3VxTDBFMWxaSHl4RA$7OVGtMJtOdjU+g06ao+NHdWGMRgYfhVL8sgsx3JK51k', '007asdsa', '', '0846531553'),
('สมชาย', 'Doe', '$2y$10$HiETyBSz524J64r0MZXb0uYfHVnLmk2yxmlh7/eLZZvJhi6v2Ue/q', '1111', '', ''),
('Jane', 'Smith', '$2y$10$sGmoJXfmq1sEk1a9/eixjOJDo7ojKyZrIM.OhL16wD/U8yiL7eV1.', '2222', '', ''),
('080 NATTHAPHON', 'SAETIAO', '', '64050080@kmitl.ac.th', '64050080@kmitl.ac.th', ''),
('David', 'Wilson', '$2y$10$oV6iUzz08M5XZca1qzXRaOIVd9UMgEhkBblqoHeCUR4ZnfyfwnDG6', '7777', '', ''),
('Michael', 'Johnson', '$2y$10$ZT0calowJOV/tvfV4UMlVeXXjrLxRe1O6u4wa7gk79qFq1rLpJspW', 'as12', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `FName` varchar(20) NOT NULL,
  `LName` varchar(20) NOT NULL,
  `EmployeeID` varchar(50) NOT NULL,
  `Role` varchar(20) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`FName`, `LName`, `EmployeeID`, `Role`, `Password`) VALUES
('FFFF', 'LLLL', 'admin', 'admin', '$2y$10$svhCJwUIwo5Z9acKx9Q4meRgdMHsCY6AwKHtz1i.xooGmuLnXVetG');

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `Date` datetime NOT NULL,
  `Username` varchar(10) NOT NULL,
  `Action` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log`
--

INSERT INTO `log` (`Date`, `Username`, `Action`) VALUES
('2024-04-10 03:59:13', '007', 'Login'),
('2024-04-10 03:59:34', '007', 'Logout'),
('2024-04-10 04:27:39', '007', 'Login'),
('2024-04-10 04:28:00', '007', 'Logout'),
('2024-04-10 04:28:03', '007', 'Login'),
('2024-04-10 10:40:00', '007', 'Login'),
('2024-04-10 11:16:13', '007', 'Logout'),
('2024-04-10 11:16:23', '007', 'Login'),
('2024-04-10 11:42:17', '007', 'Logout'),
('2024-04-10 11:42:35', '64050080@k', 'Logout'),
('2024-04-10 11:42:39', '007', 'Login');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `Username` varchar(50) NOT NULL,
  `Date` datetime NOT NULL,
  `Status` varchar(30) NOT NULL,
  `OrderID` varchar(10) NOT NULL,
  `Payment` varchar(20) NOT NULL,
  `SubTotalPrice` int(10) NOT NULL,
  `ShippingCost` int(10) NOT NULL,
  `Vat` int(11) NOT NULL,
  `TotalPrice` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`Username`, `Date`, `Status`, `OrderID`, `Payment`, `SubTotalPrice`, `ShippingCost`, `Vat`, `TotalPrice`) VALUES
('007', '2024-04-10 04:28:22', '40', 'OR2', 'QRCode', 3050, 40, 214, 3304),
('007', '2024-04-10 04:38:10', '60', 'OR3', 'CashOnDelivery', 100, 40, 7, 147),
('007', '2024-04-10 04:38:38', '30', 'OR4', 'CashOnDelivery', 800, 40, 56, 896),
('007', '2024-04-10 04:43:30', '10', 'OR5', 'QRCode', 500, 40, 35, 575),
('007', '2024-04-10 11:17:07', '20', 'OR6', 'QRCode', 800, 40, 56, 896),
('007', '2024-04-10 11:34:28', '20', 'OR7', 'QRCode', 800, 40, 56, 896);

-- --------------------------------------------------------

--
-- Table structure for table `order_refund_detail`
--

CREATE TABLE `order_refund_detail` (
  `OrderID` varchar(10) NOT NULL,
  `reason` varchar(40) NOT NULL,
  `bank` varchar(20) NOT NULL,
  `account_number` varchar(20) NOT NULL,
  `account_name` varchar(30) NOT NULL,
  `submitted_at` datetime NOT NULL,
  `received_at` datetime NOT NULL,
  `refund_status` varchar(30) NOT NULL,
  `previous_status` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_refund_detail`
--

INSERT INTO `order_refund_detail` (`OrderID`, `reason`, `bank`, `account_number`, `account_name`, `submitted_at`, `received_at`, `refund_status`, `previous_status`) VALUES
('OR2', 'สั่งสินค้าผิด', 'กรุงศรี', '164987451', 'สมศัก', '2024-04-10 04:37:47', '2024-04-10 04:39:03', 'reject', '20');

-- --------------------------------------------------------

--
-- Table structure for table `order_shipping_detail`
--

CREATE TABLE `order_shipping_detail` (
  `OrderID` varchar(10) NOT NULL,
  `Shipper` varchar(20) NOT NULL,
  `TrackingNumber` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_shipping_detail`
--

INSERT INTO `order_shipping_detail` (`OrderID`, `Shipper`, `TrackingNumber`) VALUES
('OR2', 'ThailandPost', '5415418484lds'),
('OR3', 'ThailandPost', ''),
('OR4', 'KerryExpress', ''),
('OR5', 'ThailandPost', ''),
('OR6', 'ThailandPost', ''),
('OR7', 'ThailandPost', '');

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

CREATE TABLE `order_status` (
  `StatusID` int(11) NOT NULL,
  `Status_Eng` varchar(30) NOT NULL,
  `Status_TH` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_status`
--

INSERT INTO `order_status` (`StatusID`, `Status_Eng`, `Status_TH`) VALUES
(10, 'Waiting for payment', 'รอชำระเงิน'),
(20, 'Waiting for verification', 'รอตรวจสอบการชำระ'),
(30, 'Prepare for delivery', 'เตรียมจัดส่ง'),
(40, 'Shipping', 'กำลังขนส่ง'),
(50, 'Complete', 'เสร็จสิ้น'),
(60, 'Canceled', 'ยกเลิก'),
(70, 'Request refund', 'ขอคืนเงิน'),
(80, 'Refunded', 'คืนเงินแล้ว');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `ProductID` varchar(4) NOT NULL,
  `ProductName` varchar(20) NOT NULL,
  `QtyStock` int(10) NOT NULL,
  `PricePerUnit` float NOT NULL,
  `Detail` varchar(40) NOT NULL,
  `Cost` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`ProductID`, `ProductName`, `QtyStock`, `PricePerUnit`, `Detail`, `Cost`) VALUES
('P001', 'Laptop', 40, 1000, 'Intel Core i7, 16GB RAM, 512GB SSD', 850),
('P002', 'Smartphone', 40, 800, '6.5\" 64GB', 700),
('P003', 'Tablet', 44, 500, '10.1\" IPS Display, 64GB Storage, Android', 400),
('P004', 'Headphones', 101, 100, 'Over-ear, Noise Cancelling, Bluetooth', 90),
('P005', 'Keyboard', 94, 50, 'Mechanical, RGB Backlit, Wired', 40),
('P006', 'Mouse', 60, 100, 'Wireless, Optical Sensor, 6 Programmable', 20),
('P007', 'Monitor', 101, 300, '27\" IPS, 1440p Resolution, HDMI, Display', 280),
('P008', 'Printer', 30, 150, 'Color Laser, Duplex Printing, WiFi Conne', 100),
('P009', 'Hard Drive', 120, 80, '2TB Capacity, USB 3.0, Portable', 75),
('P010', 'USB Flash Drive', 100, 10, '64GB Capacity, USB 3.0, Keychain Design', 5);

-- --------------------------------------------------------

--
-- Table structure for table `productlist`
--

CREATE TABLE `productlist` (
  `ProductID` varchar(4) NOT NULL,
  `OrderID` varchar(4) NOT NULL,
  `Qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productlist`
--

INSERT INTO `productlist` (`ProductID`, `OrderID`, `Qty`) VALUES
('P003', 'OR2', 6),
('P005', 'OR2', 1),
('P004', 'OR3', 1),
('P002', 'OR4', 1),
('P003', 'OR5', 1),
('P002', 'OR6', 1),
('P002', 'OR7', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`AddressID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Username`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`EmployeeID`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`OrderID`);

--
-- Indexes for table `order_refund_detail`
--
ALTER TABLE `order_refund_detail`
  ADD PRIMARY KEY (`OrderID`);

--
-- Indexes for table `order_shipping_detail`
--
ALTER TABLE `order_shipping_detail`
  ADD PRIMARY KEY (`OrderID`);

--
-- Indexes for table `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`StatusID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`ProductID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
