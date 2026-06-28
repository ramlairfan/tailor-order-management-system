-- ============================================================
-- Tailor Management System — FIXED Complete Database
-- Drop old DB and recreate fresh
-- ============================================================
DROP DATABASE IF EXISTS `tailor_management_system`;
CREATE DATABASE `tailor_management_system` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `tailor_management_system`;

-- USERS
CREATE TABLE `users` (
  `id`         INT AUTO_INCREMENT PRIMARY KEY,
  `full_name`  VARCHAR(100) NOT NULL,
  `email`      VARCHAR(150) NOT NULL UNIQUE,
  `password`   VARCHAR(255) NOT NULL,
  `role`       ENUM('admin','user') NOT NULL DEFAULT 'user',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `users` (`full_name`,`email`,`password`,`role`) VALUES
  ('Admin User','admin@tailor.com','admin123','admin'),
  ('John Doe',  'user@tailor.com', 'user123', 'user');

-- CUSTOMERS
CREATE TABLE `customers` (
  `id`            INT AUTO_INCREMENT PRIMARY KEY,
  `customer_name` VARCHAR(100) NOT NULL,
  `phone`         VARCHAR(20)  NOT NULL,
  `email`         VARCHAR(150) DEFAULT NULL,
  `gender`        ENUM('Male','Female','Other') DEFAULT 'Male',
  `address`       TEXT DEFAULT NULL,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `customers` (`customer_name`,`phone`,`email`,`gender`,`address`) VALUES
  ('Ali Hassan', '03001234567','ali@example.com',  'Male',  'House 12, Gulshan, Karachi'),
  ('Sara Khan',  '03111234567','sara@example.com', 'Female','Flat 5, DHA Phase 2, Lahore'),
  ('Bilal Ahmed','03211234567','bilal@example.com','Male',  'Street 7, F-10, Islamabad');

-- MEASUREMENTS
CREATE TABLE `measurements` (
  `id`           INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id`  INT NOT NULL,
  `chest`        DECIMAL(6,2) DEFAULT NULL,
  `waist`        DECIMAL(6,2) DEFAULT NULL,
  `shoulder`     DECIMAL(6,2) DEFAULT NULL,
  `sleeve`       DECIMAL(6,2) DEFAULT NULL,
  `length_value` DECIMAL(6,2) DEFAULT NULL,
  `notes`        TEXT DEFAULT NULL,
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO `measurements` (`customer_id`,`chest`,`waist`,`shoulder`,`sleeve`,`length_value`) VALUES
  (1,40.00,34.00,17.00,25.00,42.00),
  (2,36.00,30.00,15.50,23.00,38.00),
  (3,42.00,36.00,18.00,26.00,44.00);

-- ORDERS
CREATE TABLE `orders` (
  `id`               INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id`      INT NOT NULL,
  `dress_type`       VARCHAR(100) NOT NULL,
  `fabric_type`      VARCHAR(100) DEFAULT NULL,
  `quantity`         INT NOT NULL DEFAULT 1,
  `total_amount`     DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `advance_payment`  DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `remaining_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `order_date`       DATE NOT NULL,
  `delivery_date`    DATE DEFAULT NULL,
  `order_status`     ENUM('Pending','In Progress','Ready','Delivered','Cancelled') NOT NULL DEFAULT 'Pending',
  `notes`            TEXT DEFAULT NULL,
  `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO `orders` (`customer_id`,`dress_type`,`fabric_type`,`quantity`,`total_amount`,`advance_payment`,`remaining_amount`,`order_date`,`delivery_date`,`order_status`) VALUES
  (1,'Sherwani',    'Silk',   1,15000,5000,10000,CURDATE(),DATE_ADD(CURDATE(),INTERVAL 7 DAY), 'Pending'),
  (2,'Shalwar Kameez','Khaddar',2,8000,3000,5000, CURDATE(),DATE_ADD(CURDATE(),INTERVAL 5 DAY), 'In Progress'),
  (3,'Suit',        'Wool',   1,22000,8000,14000,CURDATE(),DATE_ADD(CURDATE(),INTERVAL 10 DAY),'Pending');

-- PAYMENTS
CREATE TABLE `payments` (
  `id`             INT AUTO_INCREMENT PRIMARY KEY,
  `order_id`       INT NOT NULL,
  `payment_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `payment_method` ENUM('Cash','Easypaisa','JazzCash','Bank Transfer') DEFAULT 'Cash',
  `payment_date`   DATE NOT NULL,
  `payment_status` ENUM('Paid','Partial','Unpaid') NOT NULL DEFAULT 'Unpaid',
  `notes`          TEXT DEFAULT NULL,
  `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO `payments` (`order_id`,`payment_amount`,`payment_method`,`payment_date`,`payment_status`) VALUES
  (1,5000.00,'Cash',      CURDATE(),'Partial'),
  (2,3000.00,'Easypaisa', CURDATE(),'Paid'),
  (3,0.00,   'Cash',      CURDATE(),'Unpaid');
