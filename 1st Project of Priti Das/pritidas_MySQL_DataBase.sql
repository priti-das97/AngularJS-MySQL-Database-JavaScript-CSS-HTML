-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2025 at 08:42 PM
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
-- Database: `pritidas`
--

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `bill_id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `services_tax` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `paid_bill` decimal(10,2) DEFAULT NULL,
  `remaining_bill` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill`
--

INSERT INTO `bill` (`bill_id`, `patient_id`, `services_tax`, `total_amount`, `paid_bill`, `remaining_bill`) VALUES
(101, 1, 100, 1000.00, 500.00, 600.00),
(102, 2, 150, 1500.00, 1000.00, 650.00),
(103, 3, 90, 900.00, 450.00, 540.00),
(104, 4, 200, 2000.00, 1200.00, 1000.00),
(105, 5, 180, 1800.00, 900.00, 1080.00),
(106, 6, 120, 1200.00, 600.00, 720.00),
(107, 7, 160, 1600.00, 800.00, 960.00),
(108, 8, 130, 1300.00, 300.00, 1000.00),
(109, 9, 110, 1100.00, 550.00, 660.00),
(110, 10, 170, 1700.00, 850.00, 1020.00),
(111, 11, 18, 5900.00, 5900.00, 0.00),
(112, 12, 5, 500.00, 500.00, 0.00),
(113, 13, 18, 1180.00, 1180.00, 0.00),
(114, 14, 9, 545.00, 545.00, 0.00),
(115, 115, 18, 590.00, 1620.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `doctor_id` int(11) NOT NULL,
  `dr_name` varchar(100) DEFAULT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `contact_info` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`doctor_id`, `dr_name`, `specialization`, `contact_info`, `email`) VALUES
(1, 'Dr. Priti Dey', 'Cardiology', '123-456-7890', 'pritidas@gmail.com'),
(2, 'Dr. John Smith', 'Neurology', '987-654-3210', 'johnsmith@gmail.com'),
(3, 'Dr. Emily Davis', 'Pediatrics', '555-123-4567', 'emilydavis@gmail.com'),
(4, 'Dr. Michael Brown', 'Orthopedics', '444-555-6666', 'michaelbrown@gmail.com'),
(5, 'Dr. Sarah Wilson', 'Dermatology', '333-444-5555', 'sarahwilson@gmail.com'),
(6, 'Dr. David Lee', 'Oncology', '222-333-4444', 'davidlee@gmail.com'),
(7, 'Dr. Laura Martinez', 'Gynecology', '111-222-3333', 'lauramartinez@gmail.com'),
(8, 'Dr. Robert Taylor', 'Psychiatry', '999-888-7777', 'roberttaylor@gmail.com'),
(9, 'Dr. Anna White', 'Endocrinology', '888-777-6666', 'annawhite@gmail.com'),
(10, 'Dr. James Clark', 'General Surgery', '777-666-5555', 'jamesclark@gmail.com'),
(11, 'Sipra Dey', 'gynecologist', '9820568597', 'sipradey@gmail.com'),
(12, 'Pakhi Das', 'gynecologist', '3216549870', 'pakhidas@gmail.com'),
(13, 'Puja Naskar', 'Cardiology', '321456890', 'PujaNaskar@gmail.com'),
(14, 'Puja Naskar', 'crd', '3214567890', 'pujanaskar@gmail.com'),
(15, 'Piyush Jain', 'Eye specialist', '2354698712', 'piyushjain@gmail.com'),
(16, 'Puja Naskar', 'eye', '3216547890', 'pujanaskar@gmail.com'),
(17, 'piya', 'dental specialist', '4578963210', 'piya@gmail.com'),
(18, 'riya', 'teeth', '1234567890', 'riya@gmail.com'),
(19, 'Abhisek', 'eye', '236547', 'abshesk@gmail.com'),
(20, 'Abhisek', 'Dental', '2345698702', 'abhisek@gmail.com'),
(21, 'Rahul', 'Teeth', '8974563210', 'rahul@gmail');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `patient_id` int(11) NOT NULL,
  `p_name` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `contact_info` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`patient_id`, `p_name`, `age`, `gender`, `contact_info`, `address`, `doctor_id`, `room_id`) VALUES
(1, 'Alice Johnson', 30, 'Female', 'alice@example.com', '123 Main St', 1, 11),
(2, 'Bob Williams', 45, 'Female', 'bob@example.com', '456 Elm St', 2, 12),
(3, 'Carol Miller', 28, 'Female', 'carol@example.com', '789 Pine St', 3, 13),
(4, 'Daniel Harris', 52, 'Female', 'daniel@example.com', '321 Oak St', 4, 14),
(5, 'Eva Green', 36, 'Female', 'eva@example.com', '654 Maple St', 5, 15),
(6, 'Frank Moore', 40, 'Male', 'frank@example.com', '987 Birch St', 6, 16),
(7, 'Grace Lee', 33, 'Female', 'grace@example.com', '135 Cedar St', 7, 17),
(8, 'Henry Scott', 29, 'Male', 'henry@example.com', '246 Spruce St', 8, 18),
(9, 'Isabel King', 41, 'Female', 'isabel@example.com', '357 Walnut St', 9, 19),
(10, 'Jack Wright', 50, 'Male', 'jack@example.com', '468 Chestnut St', 10, 20),
(11, 'sipra', 40, 'Female', '1234567890', 'kalyani', 5, 2);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `bill_id` int(11) DEFAULT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `bank_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `bill_id`, `amount_paid`, `payment_method`, `payment_date`, `bank_name`) VALUES
(1011, 101, 500.00, 'Credit Card', '2025-08-16', 'Bank of America'),
(1012, 102, 1000.00, 'Cash', '2025-08-17', 'N/A'),
(1013, 103, 450.00, 'Debit Card', '2025-08-18', 'Chase'),
(1014, 104, 1200.00, 'Credit Card', '2025-08-19', 'Wells Fargo'),
(1015, 105, 900.00, 'Cash', '2025-08-20', 'N/A'),
(1016, 106, 600.00, 'Debit Card', '2025-08-21', 'Citi'),
(1017, 107, 800.00, 'Credit Card', '2025-08-22', 'Bank of America'),
(1018, 108, 300.00, 'Cash', '2025-08-23', 'N/A'),
(1019, 109, 550.00, 'Debit Card', '2025-08-24', 'Chase'),
(1020, 110, 850.00, 'Credit Card', '2025-08-25', 'Wells Fargo'),
(1036, 111, 5000.00, 'ONLINE', '2025-10-25', 'ICICI'),
(1038, 111, 900.00, 'ONLINE', '2025-10-25', 'ICICI'),
(1039, 112, 300.00, 'Paytm', '2025-10-31', 'N/A'),
(1040, 112, 200.00, 'Pyment', '2025-11-01', 'N/A'),
(1041, 113, 1180.00, 'GPAY', '2025-11-01', 'N/A'),
(1042, 114, 300.00, 'ONLINE', '2025-10-31', 'N/A'),
(1043, 114, 245.00, 'ONLINE', '2025-11-01', 'N/A'),
(1044, 115, 90.00, 'cash', '2025-11-22', 'na'),
(1045, 115, 90.00, 'cash', '2025-11-30', 'na'),
(1046, 115, 90.00, 'cash', '2025-11-30', 'na'),
(1047, 115, 90.00, 'cash', '2025-11-30', 'na'),
(1048, 115, 90.00, 'cash', '2025-11-30', 'na'),
(1049, 115, 90.00, 'cash', '2025-11-30', 'na'),
(1050, 115, 90.00, 'cash', '2025-11-30', 'na'),
(1051, 115, 90.00, 'cash', '2025-11-30', 'na'),
(1052, 115, 90.00, 'cash', '2025-11-30', 'na'),
(1053, 115, 90.00, 'cash', '2025-11-30', 'na'),
(1054, 115, 90.00, 'cash', '2025-11-30', 'na'),
(1055, 115, 90.00, 'cash', '2025-11-30', 'na'),
(1056, 115, 90.00, 'cash', '2025-11-30', 'a'),
(1057, 115, 90.00, 'cash', '2025-11-30', 'na'),
(1058, 115, 90.00, 'cash', '2025-11-30', 'na'),
(1059, 115, 90.00, 'cash', '2025-11-30', 'na'),
(1060, 115, 90.00, 'na', '2025-11-30', 'a'),
(1061, 115, 90.00, 'na', '2025-11-30', 'a');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `room_id` int(11) NOT NULL,
  `room_number` varchar(20) DEFAULT NULL,
  `room_type` varchar(50) DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT NULL,
  `room_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_id`, `room_number`, `room_type`, `is_available`, `room_price`) VALUES
(11, '101', 'General', 1, 1000.00),
(12, '102', 'ICU', 0, 3000.00),
(13, '103', 'General', 1, 1200.00),
(14, '104', 'Private', 1, 2500.00),
(15, '105', 'ICU', 0, 3200.00),
(16, '106', 'General', 1, 1100.00),
(17, '107', 'Private', 0, 2600.00),
(18, '108', 'General', 1, 1300.00),
(19, '109', 'Private', 1, 2700.00),
(20, '110', 'ICU', 0, 3500.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES
(101, 'alice', 'AdminAlice!123', 'alice@example.com'),
(102, 'bob', 'AdminBob!321', 'bob@example.com'),
(103, 'charlie', 'AdminCharlie!321', 'charlie@example.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`bill_id`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`doctor_id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`patient_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bill`
--
ALTER TABLE `bill`
  MODIFY `bill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1067;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
