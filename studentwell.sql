-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2025 at 11:58 AM
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
-- Database: `studentwell`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_posts`
--

CREATE TABLE `forum_posts` (
  `ForumPostID` int(11) NOT NULL,
  `UserID` bigint(20) UNSIGNED NOT NULL,
  `PostTitle` varchar(50) NOT NULL,
  `PostCategory` varchar(20) NOT NULL,
  `Content` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forum_posts`
--

INSERT INTO `forum_posts` (`ForumPostID`, `UserID`, `PostTitle`, `PostCategory`, `Content`, `created_at`, `updated_at`) VALUES
(1, 1, 'Struggling with Motivation', 'Mental Health', 'Lately I\'ve been finding it hard to stay motivated. Any tips?', '2025-05-04 10:38:57', '2025-05-03 22:36:12'),
(2, 2, 'Best Morning Routines?', 'Lifestyle', 'What morning routines help you start your day right?', '2025-05-04 10:36:16', '2025-05-03 22:36:16'),
(3, 3, 'Dealing with Anxiety', 'Mental Health', 'How do you all manage anxiety in stressful situations?', '2025-05-04 10:37:17', '2025-05-03 22:33:13'),
(4, 4, 'Exercise Recommendations', 'Fitness', 'Looking for beginner-friendly workouts to build consistency.', '2025-05-04 10:23:50', '2025-05-03 22:23:50'),
(5, 1, 'Sleep Tips Needed', 'Sleep', 'I\'m not sleeping well. Any natural tips that helped you?', '2025-05-04 10:37:06', '2025-05-03 22:30:42'),
(9, 1, 'Welcome to the Forum', 'General', 'This is the first post on our forum!', '2025-05-11 07:50:16', '2025-05-11 07:50:16'),
(17, 1, 'Upcoming Events?', 'General', 'Any hackathons or coding events?', '2025-05-03 07:50:16', '2025-05-11 07:50:16'),
(18, 2, 'Route Troubleshooting', 'Help', 'Why is my route not working?', '2025-05-02 07:50:16', '2025-05-11 07:50:16'),
(19, 3, 'MySQL Basics', 'Tech', 'Simple MySQL joins explained.', '2025-05-01 07:50:16', '2025-05-11 07:50:16'),
(20, 4, 'Student Discounts', 'Student', 'Any good discounts for developers?', '2025-04-30 07:50:16', '2025-05-11 07:50:16'),
(23, 3, 'Forum Guidelines', 'General', 'Let’s stay respectful and on-topic.', '2025-04-27 07:50:16', '2025-05-11 07:50:16'),
(28, 4, 'Forum Feedback', 'General', 'Let me know what you think of the forum.', '2025-04-22 07:50:16', '2025-05-11 07:50:16'),
(30, 1, 'Starting a daily meditation practice', 'Mental Health', 'I\'ve been struggling with anxiety lately and want to start meditating. Has anyone tried using meditation apps like Headspace or Calm? Any tips for beginners who find it hard to sit still?', '2025-05-11 09:05:50', NULL),
(31, 1, 'Best running shoes for beginners', 'Exercise', 'I\'m planning to start running this summer but don\'t want to spend a fortune on shoes. Any recommendations for comfortable running shoes under $80 that would be good for a beginner?', '2025-05-11 09:05:50', NULL),
(32, 1, 'Study techniques for long reading assignments', 'Study', 'I have several 100+ page readings for my literature class. Anyone have effective strategies for taking notes and retaining information when dealing with long reading assignments?', '2025-05-11 09:05:50', NULL),
(33, 1, 'Meal prep ideas for busy professionals', 'Nutrition', 'I work 50+ hours a week and find myself ordering takeout too often. Looking for simple meal prep ideas that I can prepare on Sundays for the entire work week. Bonus points for high-protein options!', '2025-05-11 09:05:50', NULL),
(34, 1, 'Tips for maintaining a consistent sleep schedule', 'Sleep', 'I\'ve noticed my sleep schedule is all over the place. Some nights I go to bed at 10pm, others at 2am. How do you maintain a consistent sleep schedule when life gets busy?', '2025-05-11 09:05:50', NULL),
(35, 2, 'Fitness tracker recommendations', 'Exercise', 'I\'m looking to buy my first fitness tracker. Should I go with Fitbit, Garmin, or Apple Watch? Mainly interested in step counting, heart rate, and sleep tracking features.', '2025-05-11 09:05:50', NULL),
(36, 2, 'Benefits of journaling for mental clarity', 'Mental Health', 'I\'ve been journaling every morning for the past 3 months and it\'s made a huge difference in my mental clarity. Anyone else practice journaling? What benefits have you noticed?', '2025-05-11 09:05:50', NULL),
(37, 2, 'How to stay motivated during online courses', 'Study', 'I\'m taking several online courses and find it hard to stay motivated. Do you have any tips for maintaining focus and completing online courses without procrastinating?', '2025-05-11 09:05:50', NULL),
(38, 2, 'Simple stretches for desk workers', 'Physical Health', 'Working from home has caused me some back pain. Can anyone recommend simple stretches I can do during short breaks to prevent stiffness from sitting all day?', '2025-05-11 09:05:50', NULL),
(39, 2, 'My experience with intermittent fasting', 'Nutrition', 'I\'ve been doing 16:8 intermittent fasting for 6 weeks now. So far I\'ve noticed improved energy levels and better focus in the mornings. Anyone else try this eating pattern?', '2025-05-11 09:05:50', NULL),
(40, 3, 'Dealing with seasonal allergies naturally', 'Physical Health', 'Allergy season is hitting me hard this year. Before I resort to daily antihistamines, does anyone have natural remedies that actually work for seasonal allergies?', '2025-05-11 09:05:50', NULL),
(41, 3, 'How to build a morning routine', 'General', 'I want to create a solid morning routine to start my days right. What do your morning routines look like? Looking for ideas beyond just \"drink water and exercise\".', '2025-05-11 09:05:50', NULL),
(42, 3, 'Book recommendations for personal growth', 'Other', 'Looking for book recommendations on personal development and growth. Recently finished \"Atomic Habits\" and loved it. What should I read next?', '2025-05-11 09:05:50', NULL),
(43, 3, 'Need advice on digital detox', 'Mental Health', 'I realized I spend over 6 hours daily on my phone. Has anyone successfully done a digital detox? How did you do it and what benefits did you notice?', '2025-05-11 09:05:50', NULL),
(44, 3, 'Favorite quick healthy breakfast ideas', 'Nutrition', 'I often skip breakfast because I\'m rushing in the mornings. What are your go-to quick and healthy breakfast options that take less than 5 minutes to prepare?', '2025-05-11 09:05:50', NULL),
(45, 4, 'Tips for studying with ADHD', 'Study', 'Recently diagnosed with ADHD as an adult. Finding it challenging to focus on studying. What strategies work for others with ADHD when it comes to focused study sessions?', '2025-05-11 09:05:50', NULL),
(46, 4, 'Dealing with work-related stress', 'Mental Health', 'My job has become increasingly stressful over the past few months. What are some effective ways you manage work-related stress that don\'t involve quitting?', '2025-05-11 09:05:50', NULL),
(47, 4, 'Home workout routine without equipment', 'Exercise', 'Can\'t afford a gym membership right now. Can anyone share an effective full-body workout routine that requires no equipment and can be done in a small apartment?', '2025-05-11 09:05:50', NULL),
(48, 4, 'How to track water intake effectively', 'Physical Health', 'I know I don\'t drink enough water daily. What methods or apps do you use to track and remind yourself to stay hydrated throughout the day?', '2025-05-11 09:05:50', NULL),
(49, 4, 'Tips for reducing screen time before bed', 'Sleep', 'I struggle with falling asleep because I\'m on my phone until bedtime. What strategies have worked for you to reduce screen time in the evening for better sleep?', '2025-05-11 09:05:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `forum_replies`
--

CREATE TABLE `forum_replies` (
  `ReplyID` int(11) NOT NULL,
  `UserID` bigint(20) UNSIGNED NOT NULL,
  `PostID` int(11) NOT NULL,
  `Content` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goals`
--

CREATE TABLE `goals` (
  `GoalID` int(11) NOT NULL,
  `UserID` bigint(20) UNSIGNED NOT NULL,
  `GoalTitle` varchar(30) NOT NULL,
  `GoalCategory` varchar(20) NOT NULL,
  `GoalStartDate` date NOT NULL,
  `GoalTargetDate` date NOT NULL,
  `Notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goal_logs`
--

CREATE TABLE `goal_logs` (
  `GoalLogID` int(11) NOT NULL,
  `GoalID` int(11) NOT NULL,
  `GoalLogDate` date NOT NULL,
  `GoalStatus` varchar(15) NOT NULL,
  `Notes` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `forum_post_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logged_exercises`
--

CREATE TABLE `logged_exercises` (
  `LoggedExerciseID` int(11) NOT NULL,
  `UserID` bigint(20) UNSIGNED NOT NULL,
  `PlannedExerciseID` int(11) DEFAULT NULL,
  `Status` varchar(15) NOT NULL,
  `ExerciseDateTime` datetime NOT NULL,
  `ExerciseType` varchar(20) NOT NULL,
  `ExerciseIntensity` varchar(25) NOT NULL,
  `DurationMinutes` int(11) NOT NULL,
  `Notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_05_01_123603_add_role_to_users_table', 2),
(5, '2025_05_01_131325_update_users_name', 3),
(6, '2025_05_01_133310_update_users_table_remove_cols', 4);

-- --------------------------------------------------------

--
-- Table structure for table `mood_logs`
--

CREATE TABLE `mood_logs` (
  `MoodLogID` int(11) NOT NULL,
  `UserID` bigint(20) UNSIGNED NOT NULL,
  `MoodRating` int(11) NOT NULL,
  `Emotions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`Emotions`)),
  `Reflection` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `planned_exercises`
--

CREATE TABLE `planned_exercises` (
  `PlannedExerciseID` int(11) NOT NULL,
  `UserID` bigint(20) UNSIGNED NOT NULL,
  `ExerciseDateTime` datetime NOT NULL,
  `ExerciseType` varchar(20) NOT NULL,
  `ExerciseIntensity` varchar(25) NOT NULL,
  `DurationMinutes` int(11) NOT NULL,
  `Notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reply_likes`
--

CREATE TABLE `reply_likes` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `forum_reply_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resource_categories`
--

CREATE TABLE `resource_categories` (
  `ResourceCategoryID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('wV1zfq4U68YimiXn3Krrf1DLS68Yrj3RRSHJ1E7i', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTW1rcENQZnR6Rzl3c1NMSnNWaEtCclNlQktBM2pHZVR5c29QNXFieCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9mb3J1bT9wYWdlPTIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo0O30=', 1746957353);

-- --------------------------------------------------------

--
-- Table structure for table `sleep_logs`
--

CREATE TABLE `sleep_logs` (
  `SleepLogID` int(11) NOT NULL,
  `UserID` bigint(20) UNSIGNED NOT NULL,
  `SleepDate` date NOT NULL,
  `SleepDurationMinutes` int(11) NOT NULL,
  `SleepQuality` int(11) NOT NULL,
  `Notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_resources`
--

CREATE TABLE `support_resources` (
  `SupportResourceID` int(11) NOT NULL,
  `ResourceTitle` varchar(50) NOT NULL,
  `ResourceCategory` varchar(25) NOT NULL,
  `Phone` varchar(13) DEFAULT NULL,
  `Location` varchar(100) DEFAULT NULL,
  `Description` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(30) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(15) NOT NULL DEFAULT 'Student',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Mike', 'Perry', 'email@address.com', '$2y$12$vluG8jOUF2t3gLYQjMg7NO1I7Nis236xWlVbeY8r8Bx5WYftlFYeG', 'Student', '2025-05-01 00:49:23', '2025-05-01 00:49:23'),
(2, 'Phil', 'Carter', 'email@example.com', '$2y$12$kjKl1ECUqkrSLuaBUXJbKeyEqD9kDmRXsSVeJYQf//dZKPh0XM0yq', 'Student', '2025-05-01 01:19:49', '2025-05-01 01:19:49'),
(3, 'Bob', 'Ross', 'yyy@example.com', '$2y$12$18AW695v16.JtzolBepW6O9boRSZFqvm19AfjJBugHW0ysWhYQsYW', 'Admin', '2025-05-01 01:29:28', '2025-05-01 01:29:28'),
(4, 'Alex', 'Scott', 'alexscott200020@gmail.com', '$2y$12$WqUIY6tX00WKSIQHTkW9wO6.6Y23xZbnyQsw2BnCY7BQJIZveSS/.', 'Admin', '2025-05-03 16:27:18', '2025-05-05 07:50:52'),
(5, 'New', 'Account', 'my2@gmail.com', '$2y$12$Fsz2g5GmUQUmwRIH1hnQSu7G9YlVVx6AOJ7Q1ZE1yiYRskeBgMr2i', 'Student', '2025-05-05 04:33:21', '2025-05-05 04:33:21'),
(7, 'wu', 'wu', 'w@w.com', '$2y$12$1m43qr3ciXG82hK4trTZu.hEIT.UIJG2lmSHVq28yT7e9amjcEMci', 'Student', '2025-05-05 04:36:17', '2025-05-05 04:36:17'),
(8, 'rrrr', 'rrr', 'r@r.com', '$2y$12$hZ3VvtopMgb.ANC2g1RyYuZUxZznGz98QMShbR59HE/lV84PoFdjm', 'Student', '2025-05-05 04:38:02', '2025-05-05 04:38:02'),
(9, 'rrrr333', 'rrrr3', '3@3.com', '$2y$12$ixwa1XOTDkrq34TSVd7eMuiQqwJyXplzTSMoZcooxCY8nIY2gcepK', 'Student', '2025-05-05 04:39:33', '2025-05-05 04:39:33'),
(10, 'df', 'df', 'd@d.com', '$2y$12$TMcyOk4aGjdJRN5.GcQBQ.WsP5bOS3/V1V/qc8x.Lqg.Hiux9umiO', 'Student', '2025-05-05 06:15:42', '2025-05-05 06:15:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD PRIMARY KEY (`ForumPostID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `forum_replies`
--
ALTER TABLE `forum_replies`
  ADD PRIMARY KEY (`ReplyID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `forum_replies_postid_foreign` (`PostID`);

--
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`GoalID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `goal_logs`
--
ALTER TABLE `goal_logs`
  ADD PRIMARY KEY (`GoalLogID`),
  ADD KEY `GoalID` (`GoalID`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_post_unique` (`user_id`,`forum_post_id`),
  ADD KEY `forum_post_id` (`forum_post_id`);

--
-- Indexes for table `logged_exercises`
--
ALTER TABLE `logged_exercises`
  ADD PRIMARY KEY (`LoggedExerciseID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `PlannedExerciseID` (`PlannedExerciseID`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mood_logs`
--
ALTER TABLE `mood_logs`
  ADD PRIMARY KEY (`MoodLogID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `planned_exercises`
--
ALTER TABLE `planned_exercises`
  ADD PRIMARY KEY (`PlannedExerciseID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `reply_likes`
--
ALTER TABLE `reply_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_reply_unique` (`user_id`,`forum_reply_id`),
  ADD KEY `forum_reply_id` (`forum_reply_id`);

--
-- Indexes for table `resource_categories`
--
ALTER TABLE `resource_categories`
  ADD PRIMARY KEY (`ResourceCategoryID`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sleep_logs`
--
ALTER TABLE `sleep_logs`
  ADD PRIMARY KEY (`SleepLogID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `support_resources`
--
ALTER TABLE `support_resources`
  ADD PRIMARY KEY (`SupportResourceID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_posts`
--
ALTER TABLE `forum_posts`
  MODIFY `ForumPostID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `forum_replies`
--
ALTER TABLE `forum_replies`
  MODIFY `ReplyID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals`
  MODIFY `GoalID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goal_logs`
--
ALTER TABLE `goal_logs`
  MODIFY `GoalLogID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `logged_exercises`
--
ALTER TABLE `logged_exercises`
  MODIFY `LoggedExerciseID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `mood_logs`
--
ALTER TABLE `mood_logs`
  MODIFY `MoodLogID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `planned_exercises`
--
ALTER TABLE `planned_exercises`
  MODIFY `PlannedExerciseID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reply_likes`
--
ALTER TABLE `reply_likes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `resource_categories`
--
ALTER TABLE `resource_categories`
  MODIFY `ResourceCategoryID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sleep_logs`
--
ALTER TABLE `sleep_logs`
  MODIFY `SleepLogID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_resources`
--
ALTER TABLE `support_resources`
  MODIFY `SupportResourceID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD CONSTRAINT `forum_posts_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`id`);

--
-- Constraints for table `forum_replies`
--
ALTER TABLE `forum_replies`
  ADD CONSTRAINT `forum_replies_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `forum_replies_postid_foreign` FOREIGN KEY (`PostID`) REFERENCES `forum_posts` (`ForumPostID`) ON DELETE CASCADE;

--
-- Constraints for table `goals`
--
ALTER TABLE `goals`
  ADD CONSTRAINT `goals_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`id`);

--
-- Constraints for table `goal_logs`
--
ALTER TABLE `goal_logs`
  ADD CONSTRAINT `goal_logs_ibfk_1` FOREIGN KEY (`GoalID`) REFERENCES `goals` (`GoalID`);

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`forum_post_id`) REFERENCES `forum_posts` (`ForumPostID`) ON DELETE CASCADE;

--
-- Constraints for table `logged_exercises`
--
ALTER TABLE `logged_exercises`
  ADD CONSTRAINT `logged_exercises_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `logged_exercises_ibfk_2` FOREIGN KEY (`PlannedExerciseID`) REFERENCES `planned_exercises` (`PlannedExerciseID`);

--
-- Constraints for table `mood_logs`
--
ALTER TABLE `mood_logs`
  ADD CONSTRAINT `mood_logs_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`id`);

--
-- Constraints for table `planned_exercises`
--
ALTER TABLE `planned_exercises`
  ADD CONSTRAINT `planned_exercises_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`id`);

--
-- Constraints for table `reply_likes`
--
ALTER TABLE `reply_likes`
  ADD CONSTRAINT `reply_likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reply_likes_ibfk_2` FOREIGN KEY (`forum_reply_id`) REFERENCES `forum_replies` (`ReplyID`) ON DELETE CASCADE;

--
-- Constraints for table `sleep_logs`
--
ALTER TABLE `sleep_logs`
  ADD CONSTRAINT `sleep_logs_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
