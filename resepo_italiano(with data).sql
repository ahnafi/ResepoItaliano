-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 21, 2024 at 05:44 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `resepo_italiano`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Pizza'),
(2, 'Pasta'),
(3, 'Risotto'),
(4, 'Gelato'),
(5, 'Tiramisu'),
(6, 'Burrata'),
(7, 'Bruschetta');

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `recipe_id` int NOT NULL,
  `name` varchar(200) NOT NULL,
  `ingredients` text NOT NULL,
  `steps` text NOT NULL,
  `note` text,
  `image` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int NOT NULL,
  `category_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`recipe_id`, `name`, `ingredients`, `steps`, `note`, `image`, `created_at`, `user_id`, `category_id`) VALUES
(9, 'pizza panzati', 'satu###dua###tiga', 'satu###dua###tiga', 'catatan', '673d8a3598709.jpg', '2024-11-20 07:05:25', 3, 1),
(10, 'tiramusi kake', 'roti###   aaa', 'masukan royko###lalu rendam###lalu taruh kulkas', 'tiramisu kek adalah makanan es terenak', '673ec6eab74f4.png', '2024-11-21 02:58:07', 3, 1),
(11, 'Risol Mayo', 'Alat###Bahan', 'Lalu masukan royco', 'opsional', '673ea6e85308b.jpeg', '2024-11-21 03:20:08', 5, 2),
(12, 'Gelato Sambal Mangga', 'susu###krim###gula###garam###cabe rawit###terasi###micin###mangga muda', 'Buatlah sambal mangga. Iris-iris mangga kecil-kecil lalu sisihkan. Ulek sambal yang terdiri dari cabai, terasi, gula dan garam secukupnya. Lalu campurkan mangga yang sudah diiris-iris dan aduk hingga merata.###Buat gelato ###Campurkan sambal dan gelato lalu sajikan', 'Kalo gak enak jangan protes', '673ea825060d1.jpg', '2024-11-21 03:25:25', 6, 4),
(13, 'pizza pisang', 'adonan pizza (beli aja di toko banyak)###pisang###coklat (serah mau meses apa coklat selai)###topping apa aja dah, mau keju, mau marshmallow, suka suka dah###udah si ini aja###skm', 'adonannya di kalisin lagi###terus dibikin jadi kaya pizza, tau kan###nah abis itu udah tinggal dihias aja###oven, 10 menit aja cukup, suhunya 180 derajat celcius, pake api bawah. ###nanti klo udah 10 menit, ganti jadi api atas bawah biar atasnya agak crispi gitu (ea), 3 menit keknya cukup###udah, tinggal di potong###selamat berkreasi', 'susu klo ga suka ga usah pake', '673eac0b91a1c.jpeg', '2024-11-21 03:42:03', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `recipe_images`
--

CREATE TABLE `recipe_images` (
  `image_id` int NOT NULL,
  `recipe_id` int NOT NULL,
  `image_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `saved_recipes`
--

CREATE TABLE `saved_recipes` (
  `saved_id` int NOT NULL,
  `recipe_id` int NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `saved_recipes`
--

INSERT INTO `saved_recipes` (`saved_id`, `recipe_id`, `user_id`) VALUES
(5, 9, 4),
(6, 11, 5),
(7, 12, 3);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `session_id` varchar(255) NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`session_id`, `user_id`) VALUES
('673ec61ab1fc7', 3),
('673ea587ec5c3', 4),
('673ea5a066837', 5),
('673ea66ba9b14', 6);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `profile_image` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `profile_image`) VALUES
(3, 'budiono', 'budi@gmail.com', '$2y$10$dthm782h3Shdot/qFz1kwO2mAaEQmCpFicT0yHHVnWuAJL5May0/q', '673d87e9b9f28.jpg'),
(4, 'ime', 'ime@gmail.com', '$2y$10$f/awFMkkDEhicvLRlPnAIe6OKdq16CURUF6dvfksy27MW1oNI814.', NULL),
(5, 'nabila', 'nabila@gmail.com', '$2y$10$iY9GSI3YdLk6dCHg4GTO7.rZ3vqNsPcOLC8AgkGYVnZEJ0zUIzas2', NULL),
(6, 'elsameilia', 'email@gmail.com', '$2y$10$wQhROUE4hZFqZncfR7rE/u862T/6HJzdAQ9gIyLSVCpC/W9AaY2TS', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`recipe_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `recipe_images`
--
ALTER TABLE `recipe_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Indexes for table `saved_recipes`
--
ALTER TABLE `saved_recipes`
  ADD PRIMARY KEY (`saved_id`),
  ADD KEY `recipe_id` (`recipe_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `recipe_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `recipe_images`
--
ALTER TABLE `recipe_images`
  MODIFY `image_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `saved_recipes`
--
ALTER TABLE `saved_recipes`
  MODIFY `saved_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `recipes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recipes_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `recipe_images`
--
ALTER TABLE `recipe_images`
  ADD CONSTRAINT `recipe_images_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`recipe_id`) ON DELETE CASCADE;

--
-- Constraints for table `saved_recipes`
--
ALTER TABLE `saved_recipes`
  ADD CONSTRAINT `saved_recipes_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`recipe_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `saved_recipes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
