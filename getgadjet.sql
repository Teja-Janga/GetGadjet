-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 03, 2026 at 05:04 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `getgadjet`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `ID` int(11) NOT NULL,
  `Session_id` varchar(70) NOT NULL,
  `Customer_Name` varchar(100) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Phone` varchar(25) NOT NULL,
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `User_ID` int(11) DEFAULT NULL,
  `Status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`ID`, `Session_id`, `Customer_Name`, `Address`, `Phone`, `Created_at`, `User_ID`, `Status`) VALUES
(1, '6ipsgshrajenl0kus4l4qp6kap', 'Ajax', 'Building Number: 48\r\nStreet Name: Perungudi\r\nStreet Address: Rajiv Gandhi Salai, Kandancavadi\r\nState: Tamil Nadu\r\nCity: Chennai\r\nPost Code: 600096', '9848032919', '2025-10-30 14:26:40', NULL, 'Delivered');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `ID` int(11) NOT NULL,
  `Order_id` int(11) NOT NULL,
  `Product_id` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`ID`, `Order_id`, `Product_id`, `Quantity`, `Price`) VALUES
(1, 1, 3, 1, 115000.00),
(2, 1, 1, 1, 38000.00),
(3, 2, 1, 1, 38000.00),
(4, 3, 1, 1, 38000.00),
(5, 6, 1, 1, 38000.00),
(6, 7, 1, 1, 38000.00),
(7, 8, 1, 2, 38000.00),
(8, 8, 2, 3, 55000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `BrandName` varchar(100) NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Featured` tinyint(4) NOT NULL,
  `Category` varchar(100) NOT NULL,
  `isTrashed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ID`, `Title`, `Price`, `BrandName`, `Image`, `Description`, `Featured`, `Category`, `isTrashed`) VALUES
(1, 'IQOO Neo9 Pro 5G', 38000.00, 'IQOO', '/website/images/IQOO.png', 'IQOO Neo9 Pro 5G (Fiery Red, 8GB RAM, 256GB Storage) | Snapdragon 8 Gen 2 Processor | Supercomputing Chip Q1 | Flagship Level Sony IMX920 Camera.', 1, 'Mobile Phone', 0),
(2, 'HP 15, 13th Gen Intel Core i5', 55000.00, 'HP', '/website/images/HP_Laptop.jpg', 'HP 15, 13th Gen Intel Core i5-1334U (16GB DDR4, 512GB SSD) Anti-Glare, Micro-Edge, 15.6\'\'/39.6cm, FHD, Win 11, Office 24, Silver, 1.59kg, fd0467tu, Iris Xe Graphics, FHD Camera, Backlit KB Laptop.', 1, 'Laptop', 0),
(3, 'Apple 2025 MacBook Air M4', 115000.00, 'Apple', '/website/images/MacM4.jpg', 'Apple 2025 MacBook Air (13-inch, Apple M4 chip with 10-core CPU and 10-core GPU, 16GB Unified Memory, 512GB) - Midnight.', 1, 'Laptop', 0),
(4, 'iPhone 17 Pro Max', 155000.00, 'Apple', '/website/images/Iphone.jpg', 'iPhone 17 Pro 512 GB: 15.93 cm (6.3″) Display with Promotion up to 120Hz, A19 Pro Chip, Breakthrough Battery Life, Pro Fusion Camera System with Center Stage Front Camera; Cosmic Orange.', 1, 'Mobile Phone', 0),
(5, 'Samsung Galaxy A35 5G', 19000.00, 'Samsung', '/website/images/Samsung_A35.jpg', 'Samsung Galaxy A35 5G (Awesome Lilac, 8GB RAM, 128GB Storage) | Premium Glass Back | 50 MP Main Camera (OIS) | Nightography | IP67 | Corning Gorilla Glass Victus+ | sAMOLED with Vision Booster', 1, 'Mobile Phone', 0),
(6, 'OnePlus Nord 5', 35000.00, 'OnePlus', '/website/images/OnePlus_Nord_5.jpg', 'OnePlus Nord 5 | Snapdragon 8s Gen 3 | Stable 144FPS Gaming | Dual 50MP Flagship Camera | Powered by OnePlus AI | 8GB + 256GB | Phantom Grey', 1, 'Mobile Phone', 0),
(7, 'HP Victus, 4GB NVIDIA RTX 3050 Gaming Laptop ', 75000.00, 'HP', '/website/images/HP_Victus.jpg', 'HP Victus, AMD Ryzen 5 5600H, 4GB NVIDIA RTX 3050 Gaming Laptop (8GB DDR4, 512GB SSD) 144Hz, IPS, 15.6\"/39.6cm, Flicker-Free, Win11, Office 21, Mic Silver, 2.37kg, Enhanced Cooling, Backlit, fb0082AX', 1, 'Laptop', 0),
(8, 'Lenovo IdeaPad Slim 3 13th Gen Intel Core i5', 53900.00, 'Lenovo', '/website/images/Lenovo_Ideapad.jpg', 'Lenovo IdeaPad Slim 3 13th Gen Intel Core i5-13420H 15.3\"(38.8cm) WUXGA IPS Laptop(16GB RAM/512GB SSD/Win 11/Office Home 2024/Backlit/1Yr ADP Free/Top Metal Cover & IR Camera/Grey/1.6Kg), 83K100CGIN\r\n', 1, 'Laptop', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password_Hash` varchar(255) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Is_Admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `Email`, `Password_Hash`, `Name`, `Is_Admin`) VALUES
(1, 'brocode@bro.com', '$2y$10$NjLh5blQOJblRi7K1HgTFe/di/t72XsipEicF6CjR/H0N/Ydb5FBG', 'Ajax', 1),
(2, 'unan@gmail.com', '$2y$10$HY4wYyP.8CsfR8E9875dqO58dMqldb6M4oETeMKoOZ9Wvdm2a5CDK', 'unanymous', 0),
(3, 'random@gmail.com', '$2y$10$3vx82jtSA2jiDM7HNWQ0duuO410yvg2E4TNjaiXzqqJgak/rJIxRK', 'random', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Test` (`Order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `Test` FOREIGN KEY (`Order_id`) REFERENCES `orders` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
