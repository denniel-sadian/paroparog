-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: database:3306
-- Generation Time: Aug 13, 2023 at 08:14 AM
-- Server version: 8.0.33
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `paroparogdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `allowed_animals`
--

CREATE TABLE `allowed_animals` (
  `id` bigint UNSIGNED NOT NULL,
  `animal_id` int NOT NULL,
  `wcp_id` int NOT NULL,
  `quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `allowed_animals`
--

INSERT INTO `allowed_animals` (`id`, `animal_id`, `wcp_id`, `quantity`) VALUES
(17, 94, 16, 5),
(18, 98, 16, 5),
(19, 96, 16, 5);

-- --------------------------------------------------------

--
-- Table structure for table `butterflies`
--

CREATE TABLE `butterflies` (
  `id` bigint UNSIGNED NOT NULL,
  `specie_type` varchar(255) NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `family_name` varchar(255) NOT NULL,
  `common_name` varchar(255) DEFAULT NULL,
  `scientific_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `butterflies`
--

INSERT INTO `butterflies` (`id`, `specie_type`, `class_name`, `family_name`, `common_name`, `scientific_name`) VALUES
(94, 'Butterfly', 'Insecta', 'Nimphalidae', 'Decolores', 'Cethosiabiblis'),
(95, 'Butterfly', 'Insecta', 'Nimphalidae', 'Discopora', 'Doleschalliabisaltide'),
(96, 'Butterfly', 'Insecta', 'Nimphalidae', 'Kaykabayo', 'Danaus chrysippus'),
(97, 'Butterfly', 'Insecta', 'Papilionidae', 'Kiwig', 'Graphium Agamemnon'),
(98, 'Butterfly', 'Insecta', 'Pieridae', 'Glosipe', 'Hebomoia Glaucippe');

-- --------------------------------------------------------

--
-- Table structure for table `ltpapplications`
--

CREATE TABLE `ltpapplications` (
  `id` int NOT NULL,
  `no` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `transport_address` text,
  `transport_date` date DEFAULT NULL,
  `client_id` int DEFAULT NULL,
  `permit_signatory_id` int DEFAULT NULL,
  `issuing_personnel_id` int DEFAULT NULL,
  `releasing_personnel_id` int DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `returned_at` date DEFAULT NULL,
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `release_date` date DEFAULT NULL,
  `validity_date` date DEFAULT NULL,
  `veterinary_quarantine_cert_link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `supporting_docs_link` text,
  `submitted_at` date DEFAULT NULL,
  `accepted_at` date DEFAULT NULL,
  `issuance_date` date DEFAULT NULL,
  `inspection_report_link` text,
  `permit_signature_link` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ltpapplications`
--

INSERT INTO `ltpapplications` (`id`, `no`, `transport_address`, `transport_date`, `client_id`, `permit_signatory_id`, `issuing_personnel_id`, `releasing_personnel_id`, `status`, `created_at`, `updated_at`, `returned_at`, `remarks`, `release_date`, `validity_date`, `veterinary_quarantine_cert_link`, `supporting_docs_link`, `submitted_at`, `accepted_at`, `issuance_date`, `inspection_report_link`, `permit_signature_link`) VALUES
(54, 'PMDQ-LTP-2023-0809-54', 'Moscow, Russia', '2023-08-10', 28, 19, NULL, 18, 'USED', '2023-08-09', '2023-08-13', NULL, '', '2023-08-09', '2023-08-13', '/media/uploads/c722af64-e3f5-45f7-8851-217689f204ea-355761241_215503098069128_5654243279389074138_n.jpg', '/media/uploads/7e26813d-e1a0-4ecb-893f-6e891b641a56-355516844_1000242891013069_493966964726231999_n.jpg', '2023-08-09', '2023-08-09', NULL, '/media/uploads/7d93d4f8-0a8d-4f10-ba2e-bd791cd40c0e-inspect_report.png', '/media/uploads/c6276452-1d18-4185-80e4-146ec77e6537-example-sign.png'),
(55, 'PMDQ-LTP-2023-0813-55', 'Moscow, Russia', '2023-08-14', 28, 19, NULL, NULL, 'USED', '2023-08-13', '2023-08-13', '2023-08-13', 'Gagagaga', NULL, '2023-08-14', '/media/uploads/5d3b85d4-b95e-44c3-ba81-86c4f36dae96-OR.png', '/media/uploads/0f676e64-ac35-467a-a758-4bd65777e7b6-example-sign.png', '2023-08-13', '2023-08-13', NULL, '/media/uploads/8c3c22b1-deb2-4336-83e2-c4ab7877aaba-inspect_report.png', '/media/uploads/600b092a-c9c7-4bcb-a9bc-b6a35d0e46ad-example-sign.png');

-- --------------------------------------------------------

--
-- Table structure for table `payment_order`
--

CREATE TABLE `payment_order` (
  `id` int NOT NULL,
  `ltpapp_id` int NOT NULL,
  `payment_signatory_id` int DEFAULT NULL,
  `amount` int DEFAULT NULL,
  `or_no` varchar(255) DEFAULT NULL,
  `or_link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `signature_link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment_order`
--

INSERT INTO `payment_order` (`id`, `ltpapp_id`, `payment_signatory_id`, `amount`, `or_no`, `or_link`, `signature_link`) VALUES
(17, 54, 17, 200, '1620070-J', '/media/uploads/e79d80be-856e-4b26-8325-dc00880b24b4-OR.png', '/media/uploads/ea71a88d-41cd-4fbe-8664-ebd11ca2f86e-example-sign.png'),
(18, 55, 17, 2000, '1620070-K', '/media/uploads/9e89940c-80aa-4cd6-bd8e-db78d1b54787-OR.png', '/media/uploads/9cd56487-81aa-46d3-9bf5-7ffb03fe8cd6-example-sign.png');

-- --------------------------------------------------------

--
-- Table structure for table `transport_entries`
--

CREATE TABLE `transport_entries` (
  `id` int NOT NULL,
  `animal_id` int DEFAULT NULL,
  `ltpapp_id` int NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transport_entries`
--

INSERT INTO `transport_entries` (`id`, `animal_id`, `ltpapp_id`, `description`, `quantity`) VALUES
(38, 98, 54, 'pupae', 5),
(39, 98, 55, 'pupae', 5),
(40, 98, 55, 'pupae', 5),
(41, 96, 55, 'pupae', 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `type` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `password_changed` tinyint(1) NOT NULL DEFAULT '0',
  `wfpwcp_id` int DEFAULT NULL,
  `address` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `first_name`, `last_name`, `gender`, `type`, `created_at`, `active`, `password_changed`, `wfpwcp_id`, `address`) VALUES
(2, 'denniel', 'denniel@purplme.com', '30acd785754eb8a0986a3c0db0e1d12d', 'Denniel', 'Sadian', 'MALE', 'ADMIN', '2023-06-10 10:27:54', 1, 1, NULL, 'Gasan, Marinduque'),
(17, 'paysignatory', 'payment@gmail.com', '30acd785754eb8a0986a3c0db0e1d12d', 'Anna Marie', 'Tenorio', 'FEMALE', 'PAYMENT_SIGNATORY', '2023-06-13 18:50:17', 1, 1, NULL, NULL),
(18, 'relpersonnel', 'releasing@gmail.com', '30acd785754eb8a0986a3c0db0e1d12d', 'Ria', 'Logatoc', 'FEMALE', 'RELEASING_PERSONNEL', '2023-06-13 20:07:17', 1, 1, NULL, NULL),
(19, 'persignatory', 'permit@gmail.com', '30acd785754eb8a0986a3c0db0e1d12d', 'Janica', 'Garay', 'FEMALE', 'PERMIT_SIGNATORY', '2023-06-13 20:24:00', 1, 1, NULL, NULL),
(28, 'ltpmdq_densadian', 'sadiandenniel@gmail.com', '30acd785754eb8a0986a3c0db0e1d12d', 'Denniel', 'Sadian', 'MALE', 'CLIENT', '2023-08-09 04:46:51', 1, 1, 16, 'Cabugao, Gasan, Marinduque'),
(29, 'valvalidator', 'val@gmail.com', '30acd785754eb8a0986a3c0db0e1d12d', 'Val', 'Validator', 'MALE', 'VALIDATOR', '2023-08-09 14:01:09', 1, 1, NULL, 'Cabugao, Gasan, Marinduque');

-- --------------------------------------------------------

--
-- Table structure for table `wfp_wcp_details`
--

CREATE TABLE `wfp_wcp_details` (
  `id` int NOT NULL,
  `permitee_first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `permitee_last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `permitee_address` varchar(255) NOT NULL,
  `permitee_photo_link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `farm_name` varchar(255) NOT NULL,
  `farm_address` varchar(255) NOT NULL,
  `farm_photo_link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `wfp_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `wfp_photo_link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `wcp_no` varchar(255) NOT NULL,
  `wcp_photo_link` text NOT NULL,
  `issuance_date` date NOT NULL,
  `expiry_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `wfp_wcp_details`
--

INSERT INTO `wfp_wcp_details` (`id`, `permitee_first_name`, `permitee_last_name`, `permitee_address`, `permitee_photo_link`, `farm_name`, `farm_address`, `farm_photo_link`, `wfp_no`, `wfp_photo_link`, `wcp_no`, `wcp_photo_link`, `issuance_date`, `expiry_date`) VALUES
(16, 'Denniel', 'Sadian', 'Cabugao, Gasan, Marinduque', NULL, 'Shit Farm', 'Cabugao, Gasan, Marinduque', NULL, 'DDDDDDD', '/media/uploads/2bee0f06-d52e-42cf-98dc-65eeb3143166-wfp.png', 'DDDDDDD', '/media/uploads/0ffa031b-51d9-4d36-a7eb-89d72f05d77a-wcp.png', '2023-08-09', '2023-08-31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `allowed_animals`
--
ALTER TABLE `allowed_animals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `butterflies`
--
ALTER TABLE `butterflies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `ltpapplications`
--
ALTER TABLE `ltpapplications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `issuing_personnel_id` (`issuing_personnel_id`),
  ADD KEY `releasing_personnel_id` (`releasing_personnel_id`),
  ADD KEY `permit_signatory_id` (`permit_signatory_id`);

--
-- Indexes for table `payment_order`
--
ALTER TABLE `payment_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_order_ibfk_1` (`payment_signatory_id`);

--
-- Indexes for table `transport_entries`
--
ALTER TABLE `transport_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_username_constraint` (`username`),
  ADD UNIQUE KEY `unique_email_constraint` (`email`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `wfpwcp_id_fk` (`wfpwcp_id`);

--
-- Indexes for table `wfp_wcp_details`
--
ALTER TABLE `wfp_wcp_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_wcp_no_constraint` (`wcp_no`),
  ADD UNIQUE KEY `unique_wfp_no_constraint` (`wfp_no`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `wcp_no` (`wcp_no`,`wfp_no`),
  ADD UNIQUE KEY `unique_wfp_no` (`wfp_no`),
  ADD UNIQUE KEY `unique_wcp_no` (`wcp_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `allowed_animals`
--
ALTER TABLE `allowed_animals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `butterflies`
--
ALTER TABLE `butterflies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `ltpapplications`
--
ALTER TABLE `ltpapplications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `payment_order`
--
ALTER TABLE `payment_order`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `transport_entries`
--
ALTER TABLE `transport_entries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `wfp_wcp_details`
--
ALTER TABLE `wfp_wcp_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ltpapplications`
--
ALTER TABLE `ltpapplications`
  ADD CONSTRAINT `ltpapplications_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ltpapplications_ibfk_2` FOREIGN KEY (`issuing_personnel_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ltpapplications_ibfk_3` FOREIGN KEY (`releasing_personnel_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ltpapplications_ibfk_4` FOREIGN KEY (`permit_signatory_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payment_order`
--
ALTER TABLE `payment_order`
  ADD CONSTRAINT `payment_order_ibfk_1` FOREIGN KEY (`payment_signatory_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `wfpwcp_id_fk` FOREIGN KEY (`wfpwcp_id`) REFERENCES `wfp_wcp_details` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
