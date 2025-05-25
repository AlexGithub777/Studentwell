-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2025 at 12:07 PM
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forum_posts`
--

INSERT INTO `forum_posts` (`ForumPostID`, `UserID`, `PostTitle`, `PostCategory`, `Content`, `created_at`) VALUES
(1, 4, 'Struggling with Motivation', 'Mental Health', 'Lately I\'ve been finding it hard to stay motivated. Any tips?', '2025-05-04 10:38:57'),
(2, 2, 'Best Morning Routines?', 'Lifestyle', 'What morning routines help you start your day right?', '2025-05-04 10:36:16'),
(3, 3, 'Dealing with Anxiety', 'Mental Health', 'How do you all manage anxiety in stressful situations?', '2025-05-04 10:37:17'),
(4, 4, 'Exercise Recommendations', 'Fitness', 'Looking for beginner-friendly workouts to build consistency.', '2025-05-04 10:23:50'),
(5, 4, 'Sleep Tips Needed', 'Sleep', 'I\'m not sleeping well. Any natural tips that helped you?', '2025-05-04 10:37:06'),
(9, 4, 'Welcome to the Forum', 'General', 'This is the first post on our forum!', '2025-05-11 07:50:16'),
(17, 4, 'Upcoming Events?', 'General', 'Any hackathons or coding events?', '2025-05-03 07:50:16'),
(18, 2, 'Route Troubleshooting', 'Help', 'Why is my route not working?', '2025-05-02 07:50:16'),
(19, 3, 'MySQL Basics', 'Tech', 'Simple MySQL joins explained.', '2025-05-01 07:50:16'),
(20, 4, 'Student Discounts', 'Student', 'Any good discounts for developers?', '2025-04-30 07:50:16'),
(23, 3, 'Forum Guidelines', 'General', 'Let’s stay respectful and on-topic.', '2025-04-27 07:50:16'),
(28, 4, 'Forum Feedback', 'General', 'Let me know what you think of the forum.', '2025-04-22 07:50:16'),
(30, 4, 'Starting a daily meditation practice', 'Mental Health', 'I\'ve been struggling with anxiety lately and want to start meditating. Has anyone tried using meditation apps like Headspace or Calm? Any tips for beginners who find it hard to sit still?', '2025-05-11 09:05:50'),
(31, 4, 'Best running shoes for beginners', 'Exercise', 'I\'m planning to start running this summer but don\'t want to spend a fortune on shoes. Any recommendations for comfortable running shoes under $80 that would be good for a beginner?', '2025-05-11 09:05:50'),
(32, 4, 'Study techniques for long reading assignments', 'Study', 'I have several 100+ page readings for my literature class. Anyone have effective strategies for taking notes and retaining information when dealing with long reading assignments?', '2025-05-11 09:05:50'),
(33, 4, 'Meal prep ideas for busy professionals', 'Nutrition', 'I work 50+ hours a week and find myself ordering takeout too often. Looking for simple meal prep ideas that I can prepare on Sundays for the entire work week. Bonus points for high-protein options!', '2025-05-11 09:05:50'),
(34, 4, 'Tips for maintaining a consistent sleep schedule', 'Sleep', 'I\'ve noticed my sleep schedule is all over the place. Some nights I go to bed at 10pm, others at 2am. How do you maintain a consistent sleep schedule when life gets busy?', '2025-05-11 09:05:50'),
(35, 2, 'Fitness tracker recommendations', 'Exercise', 'I\'m looking to buy my first fitness tracker. Should I go with Fitbit, Garmin, or Apple Watch? Mainly interested in step counting, heart rate, and sleep tracking features.', '2025-05-11 09:05:50'),
(36, 2, 'Benefits of journaling for mental clarity', 'Mental Health', 'I\'ve been journaling every morning for the past 3 months and it\'s made a huge difference in my mental clarity. Anyone else practice journaling? What benefits have you noticed?', '2025-05-11 09:05:50'),
(37, 2, 'How to stay motivated during online courses', 'Study', 'I\'m taking several online courses and find it hard to stay motivated. Do you have any tips for maintaining focus and completing online courses without procrastinating?', '2025-05-11 09:05:50'),
(38, 2, 'Simple stretches for desk workers', 'Physical Health', 'Working from home has caused me some back pain. Can anyone recommend simple stretches I can do during short breaks to prevent stiffness from sitting all day?', '2025-05-11 09:05:50'),
(39, 2, 'My experience with intermittent fasting', 'Nutrition', 'I\'ve been doing 16:8 intermittent fasting for 6 weeks now. So far I\'ve noticed improved energy levels and better focus in the mornings. Anyone else try this eating pattern?', '2025-05-11 09:05:50'),
(40, 3, 'Dealing with seasonal allergies naturally', 'Physical Health', 'Allergy season is hitting me hard this year. Before I resort to daily antihistamines, does anyone have natural remedies that actually work for seasonal allergies?', '2025-05-11 09:05:50'),
(41, 3, 'How to build a morning routine', 'General', 'I want to create a solid morning routine to start my days right. What do your morning routines look like? Looking for ideas beyond just \"drink water and exercise\".', '2025-05-11 09:05:50'),
(42, 3, 'Book recommendations for personal growth', 'Other', 'Looking for book recommendations on personal development and growth. Recently finished \"Atomic Habits\" and loved it. What should I read next?', '2025-05-11 09:05:50'),
(43, 3, 'Need advice on digital detox', 'Mental Health', 'I realized I spend over 6 hours daily on my phone. Has anyone successfully done a digital detox? How did you do it and what benefits did you notice?', '2025-05-11 09:05:50'),
(44, 3, 'Favorite quick healthy breakfast ideas', 'Nutrition', 'I often skip breakfast because I\'m rushing in the mornings. What are your go-to quick and healthy breakfast options that take less than 5 minutes to prepare?', '2025-05-11 09:05:50'),
(45, 4, 'Tips for studying with ADHD', 'Study', 'Recently diagnosed with ADHD as an adult. Finding it challenging to focus on studying. What strategies work for others with ADHD when it comes to focused study sessions?', '2025-05-11 09:05:50'),
(46, 4, 'Dealing with work-related stress', 'Mental Health', 'My job has become increasingly stressful over the past few months. What are some effective ways you manage work-related stress that don\'t involve quitting?', '2025-05-11 09:05:50'),
(47, 4, 'Home workout routine without equipment', 'Exercise', 'Can\'t afford a gym membership right now. Can anyone share an effective full-body workout routine that requires no equipment and can be done in a small apartment?', '2025-05-11 09:05:50'),
(48, 4, 'How to track water intake effectively', 'Physical Health', 'I know I don\'t drink enough water daily. What methods or apps do you use to track and remind yourself to stay hydrated throughout the day?', '2025-05-11 09:05:50'),
(49, 4, 'Tips for reducing screen time before bed', 'Sleep', 'I struggle with falling asleep because I\'m on my phone until bedtime. What strategies have worked for you to reduce screen time in the evening for better sleep?', '2025-05-11 09:05:50');

-- --------------------------------------------------------

--
-- Table structure for table `forum_replies`
--

CREATE TABLE `forum_replies` (
  `ReplyID` int(11) NOT NULL,
  `UserID` bigint(20) UNSIGNED NOT NULL,
  `PostID` int(11) NOT NULL,
  `Content` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goals`
--

INSERT INTO `goals` (`GoalID`, `UserID`, `GoalTitle`, `GoalCategory`, `GoalStartDate`, `GoalTargetDate`, `Notes`, `created_at`) VALUES
(1, 1, 'Finish Semester Project', 'Academic', '2025-05-01', '2025-06-20', 'Capstone project for final year', '2025-05-22 07:08:27'),
(2, 1, 'Apply for Internship', 'Career', '2025-05-10', '2025-06-01', 'Submit applications to tech companies', '2025-05-22 07:08:27'),
(3, 1, 'Save for Laptop', 'Finance', '2025-05-01', '2025-08-01', 'Need a new laptop for school', '2025-05-22 07:08:27'),
(4, 1, 'Learn Guitar', 'Hobbies', '2025-05-01', '2025-09-01', 'Practice chords daily', '2025-05-22 07:08:27'),
(5, 1, 'Practice Meditation', 'Mental Health', '2025-05-05', '2025-06-05', 'Try morning mindfulness', '2025-05-22 07:08:27'),
(6, 1, 'Eat More Vegetables', 'Nutrition', '2025-05-01', '2025-06-15', 'Track daily servings', '2025-05-22 07:08:27'),
(7, 1, 'Workout 3x a Week', 'Physical Health', '2025-05-01', '2025-07-01', 'Gym or home workouts', '2025-05-22 07:08:27'),
(8, 1, 'Daily To-Do List', 'Productivity', '2025-05-01', '2025-06-01', 'Use planner consistently', '2025-05-22 07:08:27'),
(9, 1, 'Fix Sleep Schedule', 'Sleep', '2025-05-01', '2025-06-01', 'Sleep before 11pm', '2025-05-22 07:08:27'),
(10, 1, 'Reconnect with Friends', 'Social', '2025-05-01', '2025-06-30', 'Weekly meetups or calls', '2025-05-22 07:08:27'),
(11, 1, 'Start Journaling', 'Mental Health', '2025-05-01', '2025-06-01', 'Write a daily entry', '2025-05-22 07:08:27'),
(12, 1, 'Donate to Charity', 'Wellness', '2025-05-01', '2025-06-15', 'Find a good cause', '2025-05-22 07:08:27'),
(13, 1, 'Read 3 Books', 'Academic', '2025-05-01', '2025-08-01', 'Books for leisure and study', '2025-05-22 07:08:27'),
(14, 1, 'Get a Summer Job', 'Career', '2025-05-15', '2025-06-30', 'Apply and interview', '2025-05-22 07:08:27'),
(15, 1, 'Start a Budget Plan', 'Finance', '2025-05-01', '2025-05-31', 'Track income and expenses', '2025-05-22 07:08:27'),
(16, 1, 'Sketch Weekly', 'Hobbies', '2025-05-01', '2025-07-01', '1 drawing per week', '2025-05-22 07:08:27'),
(17, 1, 'Travel to Napier', 'Travel', '2025-06-01', '2025-06-10', 'Visit EIT and local sights', '2025-05-22 07:08:27'),
(18, 1, 'Read Spiritual Text', 'Spiritual', '2025-05-01', '2025-06-01', 'Morning reading habit', '2025-05-22 07:08:27'),
(19, 1, 'Weekly Hikes', 'Physical Health', '2025-05-01', '2025-08-01', 'Explore local trails', '2025-05-22 07:08:27'),
(20, 1, 'Miscellaneous Tasks', 'Other', '2025-05-01', '2025-07-01', 'Stuff that doesn’t fit', '2025-05-22 07:08:27'),
(22, 1, 'Finish all my assignments', 'Academic', '2025-05-25', '2025-06-08', 'Get all my school work done', '2025-05-25 10:05:57');

-- --------------------------------------------------------

--
-- Table structure for table `goal_logs`
--

CREATE TABLE `goal_logs` (
  `GoalLogID` int(11) NOT NULL,
  `GoalID` int(11) NOT NULL,
  `GoalLogDate` date NOT NULL,
  `GoalStatus` varchar(15) NOT NULL,
  `Notes` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `UserID` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goal_logs`
--

INSERT INTO `goal_logs` (`GoalLogID`, `GoalID`, `GoalLogDate`, `GoalStatus`, `Notes`, `created_at`, `UserID`) VALUES
(1, 1, '2025-05-15', 'completed', 'Completed section 1', '2025-05-22 19:08:27', 1),
(2, 2, '2025-05-16', 'incomplete', 'Didn\'t apply to 2 companies', '2025-05-22 19:08:27', 1),
(3, 3, '2025-05-17', 'completed', 'Saved $500', '2025-05-22 19:08:27', 1),
(4, 4, '2025-05-18', 'incomplete', 'Didn\'t learn two chords', '2025-05-22 19:08:27', 1),
(5, 5, '2025-05-19', 'completed', 'Meditated 3 days straight', '2025-05-22 19:08:27', 1),
(6, 6, '2025-05-20', 'completed', 'Vegetables tracked all week', '2025-05-22 19:08:27', 1),
(7, 7, '2025-05-21', 'completed', 'Got sick, resting for a week', '2025-05-22 19:08:27', 1),
(8, 8, '2025-05-22', 'partially', 'Sometimes used to-do list every day', '2025-05-22 19:08:27', 1),
(9, 9, '2025-05-23', 'completed', 'Slept by 10:30pm last night', '2025-05-22 19:08:27', 1),
(10, 10, '2025-05-24', 'completed', 'Had coffee with 3 friends', '2025-05-22 19:08:27', 1);

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

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `forum_post_id`, `created_at`, `updated_at`) VALUES
(51, 4, 30, '2025-05-13 08:03:15', '2025-05-13 08:03:15'),
(58, 1, 30, '2025-05-25 09:46:29', '2025-05-25 09:46:29');

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
  `MoodDate` date DEFAULT NULL,
  `MoodRating` int(11) NOT NULL,
  `Emotions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`Emotions`)),
  `Reflection` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mood_logs`
--

INSERT INTO `mood_logs` (`MoodLogID`, `UserID`, `MoodDate`, `MoodRating`, `Emotions`, `Reflection`, `created_at`) VALUES
(39, 1, '2025-05-21', 4, '[\"Adventurous\",\"Excited\",\"Focused\",\"Motivated\"]', 'Feeling pumped and ready.', '2025-05-21 10:57:19'),
(41, 1, '2025-05-19', 3, '[\"Bored\", \"Lonely\"]', 'Nothing much happened.', '2025-05-21 10:57:19'),
(42, 1, '2025-05-18', 2, '[\"Stressed\", \"Frustrated\"]', 'Too much work piling up.', '2025-05-21 10:57:19'),
(43, 1, '2025-05-17', 1, '[\"Anxious\", \"Tired\"]', 'Hard to get anything done.', '2025-05-21 10:57:19'),
(44, 1, '2025-05-16', 3, '[\"Neutral\", \"Thoughtful\"]', 'Just an average day overall.', '2025-05-21 10:57:19'),
(45, 1, '2025-05-15', 4, '[\"Happy\", \"Energetic\"]', 'Got a lot done today, feeling accomplished.', '2025-05-21 10:57:19'),
(46, 1, '2025-05-14', 3, '[\"Calm\", \"Focused\"]', 'Managed to concentrate on tasks.', '2025-05-21 10:57:19'),
(47, 1, '2025-05-13', 2, '[\"Irritable\", \"Restless\"]', 'Couldn\'t seem to settle down today.', '2025-05-21 10:57:19'),
(48, 1, '2025-05-12', 3, '[\"Content\", \"Peaceful\"]', 'Nothing special but feeling okay.', '2025-05-21 10:57:19'),
(49, 1, '2025-05-11', 5, '[\"Joyful\", \"Inspired\"]', 'Had a breakthrough on a project!', '2025-05-21 10:57:19'),
(50, 1, '2025-05-10', 4, '[\"Satisfied\", \"Grateful\"]', 'Appreciating the little things today.', '2025-05-21 10:57:19'),
(51, 1, '2025-05-09', 3, '[\"Neutral\", \"Distracted\"]', 'Mind was wandering a lot.', '2025-05-21 10:57:19'),
(52, 1, '2025-05-08', 1, '[\"Depressed\", \"Overwhelmed\"]', 'Everything feels too much right now.', '2025-05-21 10:57:19'),
(53, 1, '2025-05-07', 2, '[\"Worried\", \"Tense\"]', 'Concerns about upcoming deadlines.', '2025-05-21 10:57:19'),
(54, 1, '2025-05-06', 3, '[\"Hopeful\", \"Uncertain\"]', 'Things might be looking up, but not sure yet.', '2025-05-21 10:57:19'),
(55, 1, '2025-05-05', 4, '[\"Cheerful\", \"Productive\"]', 'Good day with steady progress.', '2025-05-21 10:57:19'),
(56, 1, '2025-05-04', 4, '[\"Proud\", \"Confident\"]', 'Handled a difficult situation well.', '2025-05-21 10:57:19'),
(57, 1, '2025-05-03', 5, '[\"Ecstatic\", \"Fulfilled\"]', 'One of the best days in a while!', '2025-05-21 10:57:19'),
(58, 1, '2025-05-02', 3, '[\"Mellow\", \"Reflective\"]', 'Taking time to think about goals.', '2025-05-21 10:57:19'),
(59, 1, '2025-05-01', 2, '[\"Disappointed\", \"Low\"]', 'Expected better results from my efforts.', '2025-05-21 10:57:19'),
(60, 1, '2025-04-30', 3, '[\"Okay\", \"Stable\"]', 'Not great, not terrible.', '2025-05-21 10:57:19'),
(61, 1, '2025-04-29', 4, '[\"Optimistic\", \"Refreshed\"]', 'Good night\'s sleep made a difference.', '2025-05-21 10:57:19'),
(62, 1, '2025-04-28', 3, '[\"Indifferent\", \"Tired\"]', 'Just going through the motions today.', '2025-05-21 10:57:19'),
(63, 1, '2025-04-27', 4, '[\"Pleased\", \"Relaxed\"]', 'Nice weekend activities lifted my mood.', '2025-05-21 10:57:19'),
(64, 1, '2025-04-26', 2, '[\"Confused\", \"Doubtful\"]', 'Questioning some decisions I\'ve made.', '2025-05-21 10:57:19'),
(65, 1, '2025-04-25', 3, '[\"Nostalgic\", \"Calm\"]', 'Remembering good times and feeling okay.', '2025-05-21 10:57:19'),
(66, 1, '2025-04-24', 5, '[\"Thrilled\", \"Enthusiastic\"]', 'Great news received today!', '2025-05-21 10:57:19'),
(67, 1, '2025-04-23', 4, '[\"Peaceful\", \"Centered\"]', 'Meditation session really helped.', '2025-05-21 10:57:19'),
(68, 1, '2025-04-22', 3, '[\"Balanced\", \"Quiet\"]', 'Taking things one step at a time.', '2025-05-21 10:57:19'),
(69, 1, '2025-04-21', 4, '[\"Hopeful\", \"Determined\"]', 'Setting new goals for the month ahead.', '2025-05-21 10:57:19'),
(70, 1, '2025-05-20', 2, '[\"Calm\",\"Hopeful\"]', NULL, '2025-05-21 11:43:01'),
(71, 1, '2025-05-22', 3, '[\"Focused\",\"Frustrated\",\"Lonely\",\"Productive\",\"Stressed\"]', NULL, '2025-05-22 09:59:29'),
(72, 1, '2025-05-25', 3, '[\"Content\"]', 'very busy', '2025-05-25 10:03:40');

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

--
-- Dumping data for table `resource_categories`
--

INSERT INTO `resource_categories` (`ResourceCategoryID`, `Name`) VALUES
(1, 'Mental Health'),
(2, 'Physical Health'),
(3, 'Academic Support'),
(4, 'Emergency');

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
('w0nRbpJcFnbKpNjbTxvEaHYQlfZmfUMV1yyDaYZR', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaFh5ZW1kaHBMa3VRUXg3bHlPMW9NcUx6M2xnYVdaMnFYeHZZaTJEWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9nb2FscyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1748167626);

-- --------------------------------------------------------

--
-- Table structure for table `sleep_logs`
--

CREATE TABLE `sleep_logs` (
  `SleepLogID` int(11) NOT NULL,
  `UserID` bigint(20) UNSIGNED NOT NULL,
  `SleepDate` date NOT NULL,
  `BedTime` time DEFAULT NULL,
  `WakeTime` time DEFAULT NULL,
  `SleepDurationMinutes` int(11) NOT NULL,
  `SleepQuality` int(11) NOT NULL,
  `Notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sleep_logs`
--

INSERT INTO `sleep_logs` (`SleepLogID`, `UserID`, `SleepDate`, `BedTime`, `WakeTime`, `SleepDurationMinutes`, `SleepQuality`, `Notes`, `created_at`) VALUES
(127, 1, '2025-05-19', '23:40:00', '07:40:00', 480, 5, 'Great sleep, felt refreshed.', '2025-05-21 08:47:58'),
(128, 1, '2025-05-18', '23:47:00', '07:55:00', 488, 5, 'Great sleep, felt refreshed.', '2025-05-21 08:47:58'),
(129, 1, '2025-05-17', '22:28:00', '06:41:00', 493, 5, 'Great sleep, felt refreshed.', '2025-05-21 08:47:58'),
(130, 1, '2025-05-16', '20:00:00', '06:49:00', 649, 3, 'Okay sleep, could be better.', '2025-05-21 08:47:58'),
(131, 1, '2025-05-15', '23:30:00', '07:25:00', 475, 5, 'Great sleep, felt refreshed.', '2025-05-21 08:47:58'),
(132, 1, '2025-05-14', '21:26:00', '05:31:00', 485, 5, 'Great sleep, felt refreshed.', '2025-05-21 08:47:58'),
(133, 1, '2025-05-13', '20:55:00', '06:50:00', 595, 4, 'Good sleep, fairly rested.', '2025-05-21 08:47:58'),
(134, 1, '2025-05-12', '21:44:00', '08:29:00', 645, 3, 'Okay sleep, could be better.', '2025-05-21 08:47:58'),
(135, 1, '2025-05-11', '22:39:00', '06:06:00', 447, 4, 'Good sleep, fairly rested.', '2025-05-21 08:47:58'),
(136, 1, '2025-05-10', '21:09:00', '08:59:00', 710, 3, 'Okay sleep, could be better.', '2025-05-21 08:47:58'),
(137, 1, '2025-05-09', '21:22:00', '05:14:00', 472, 5, 'Great sleep, felt refreshed.', '2025-05-21 08:47:58'),
(138, 1, '2025-05-08', '23:07:00', '07:06:00', 479, 5, 'Great sleep, felt refreshed.', '2025-05-21 08:47:58'),
(139, 1, '2025-05-07', '21:05:00', '08:37:00', 692, 3, 'Okay sleep, could be better.', '2025-05-21 08:47:58'),
(140, 1, '2025-05-06', '20:56:00', '05:23:00', 507, 5, 'Great sleep, felt refreshed.', '2025-05-21 08:47:58'),
(141, 1, '2025-05-05', '20:54:00', '08:44:00', 710, 3, 'Okay sleep, could be better.', '2025-05-21 08:47:58'),
(142, 1, '2025-05-04', '20:56:00', '07:08:00', 612, 3, 'Okay sleep, could be better.', '2025-05-21 08:47:58'),
(143, 1, '2025-05-03', '23:12:00', '05:37:00', 385, 3, 'Okay sleep, could be better.', '2025-05-21 08:47:58'),
(144, 1, '2025-05-02', '21:56:00', '07:17:00', 561, 4, 'Good sleep, fairly rested.', '2025-05-21 08:47:58'),
(145, 1, '2025-05-01', '22:59:00', '06:20:00', 441, 4, 'Good sleep, fairly rested.', '2025-05-21 08:47:58'),
(146, 1, '2025-04-30', '23:33:00', '05:27:00', 354, 2, 'Poor sleep, not very rested.', '2025-05-21 08:47:58'),
(147, 1, '2025-04-29', '20:48:00', '06:12:00', 564, 4, 'Good sleep, fairly rested.', '2025-05-21 08:47:58'),
(148, 1, '2025-04-28', '20:38:00', '05:23:00', 525, 5, 'Great sleep, felt refreshed.', '2025-05-21 08:47:58'),
(149, 1, '2025-04-27', '22:31:00', '06:31:00', 480, 5, 'Great sleep, felt refreshed.', '2025-05-21 08:47:58'),
(150, 1, '2025-04-26', '21:08:00', '07:53:00', 645, 3, 'Okay sleep, could be better.', '2025-05-21 08:47:58'),
(151, 1, '2025-04-25', '21:35:00', '07:10:00', 575, 4, 'Good sleep, fairly rested.', '2025-05-21 08:47:58'),
(152, 1, '2025-04-24', '20:00:00', '07:32:00', 692, 3, 'Okay sleep, could be better.', '2025-05-21 08:47:58'),
(153, 1, '2025-04-23', '22:09:00', '05:06:00', 417, 3, 'Okay sleep, could be better.', '2025-05-21 08:47:58'),
(154, 1, '2025-04-22', '20:38:00', '07:05:00', 627, 3, 'Okay sleep, could be better.', '2025-05-21 08:47:58'),
(155, 1, '2025-04-21', '22:54:00', '07:35:00', 521, 5, 'Great sleep, felt refreshed.', '2025-05-21 08:47:58'),
(173, 1, '2025-05-20', '23:11:00', '10:11:00', 660, 1, 'updated', '2025-05-22 04:10:40'),
(174, 1, '2025-05-21', '19:46:00', '06:07:00', 621, 3, NULL, '2025-05-22 04:40:59'),
(175, 1, '2025-05-24', '04:30:00', '13:10:00', 520, 4, NULL, '2025-05-25 10:04:23');

-- --------------------------------------------------------

--
-- Table structure for table `support_resources`
--

CREATE TABLE `support_resources` (
  `SupportResourceID` int(11) NOT NULL,
  `ResourceTitle` varchar(100) NOT NULL,
  `ResourceCategory` int(11) DEFAULT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `Location` varchar(255) DEFAULT NULL,
  `Description` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `support_resources`
--

INSERT INTO `support_resources` (`SupportResourceID`, `ResourceTitle`, `ResourceCategory`, `Phone`, `Location`, `Description`) VALUES
(2, 'Campus Counselling Services', 1, '08002255348', '501 Gloucester Street, Taradale, Napier 4112', 'Confidential in person support for stress, anxiety, and mental health'),
(3, 'Student Health Clinic', 2, '0800987654', 'Wellness Building, Level 2', 'On-campus medical services including GP visits, physiotherapy, and vaccinations. Walk-ins welcome.'),
(4, 'Fitness & Recreation Centre', 2, '0800888777', 'Recreation Building, Gym Level', 'Access to gym, group fitness classes, and wellbeing programs.'),
(5, 'Learning Support Hub', 3, '0800456789', 'Building B, Room 10', 'One-on-one support for study skills, writing, and time management.'),
(6, 'Writing & Tutoring Hub', 3, '0800321654', 'Library, Ground Floor', 'One-on-one help with writing and subject-specific tutoring.'),
(7, '24/7 Emergency Services', 4, '111', 'Nationwide', 'For immediate emergencies involving safety, medical help, or serious incidents.'),
(10, 'Student Counseling Services', 1, '0800123456', 'Campus Building A, Room 101', 'Free counseling for enrolled students'),
(11, 'Career Guidance Centre', 2, '0800654321', 'Library Wing, Level 2', 'Career advice and CV preparation support'),
(12, 'IT Helpdesk', 3, '0800987654', 'Tech Hub, Room 303', 'Help with emails, Wi-Fi, and student portals'),
(13, 'Campus Security', 4, '0800111222', 'Security Office, Main Entrance', 'Available 24/7 for emergencies or lost items'),
(20, 'new resource', 1, '0800838383', '123 Wellness Street', 'Resource Description');

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
(1, 'Alex', 'Scott', 'alexscott200020@gmail.com', '$2y$12$a.wetm1aISEyv.NKEotxYeq3yCnp6.qaDISrxZcoC5.IUrIL2DhtC', 'Admin', '2025-05-03 16:27:18', '2025-05-18 10:35:30'),
(2, 'Phil', 'Carter', 'email@example.com', '$2y$12$kjKl1ECUqkrSLuaBUXJbKeyEqD9kDmRXsSVeJYQf//dZKPh0XM0yq', 'Student', '2025-05-01 01:19:49', '2025-05-01 01:19:49'),
(3, 'Bob', 'Ross', 'yyy@example.com', '$2y$12$cPaQjctNirx2eF4KC.WH7O9BRmWYW9l0ccIFCTpbOk1825Xb1bbPK', 'Student', '2025-05-01 01:29:28', '2025-05-18 10:10:05'),
(4, 'Mike', 'Perry', 'email@address.com', '$2y$12$vluG8jOUF2t3gLYQjMg7NO1I7Nis236xWlVbeY8r8Bx5WYftlFYeG', 'Student', '2025-05-01 00:49:23', '2025-05-18 10:37:50'),
(14, 'Alice', 'Smith', 'alice.smith@example.com', '$2y$10$abc123abc123abc123abcuvBo3zZzLl3AhtWc1wEupPg4pK7W52tu', 'Student', '2025-05-18 10:51:54', '2025-05-18 10:51:54'),
(15, 'Bob', 'Johnson', 'bob.johnson@example.com', '$2y$10$abc123abc123abc123abcuvBo3zZzLl3AhtWc1wEupPg4pK7W52tu', 'Admin', '2025-05-18 10:51:54', '2025-05-18 10:51:54'),
(16, 'Carol', 'Williams', 'carol.williams@example.com', '$2y$10$abc123abc123abc123abcuvBo3zZzLl3AhtWc1wEupPg4pK7W52tu', 'Student', '2025-05-18 10:51:54', '2025-05-18 10:51:54'),
(17, 'David', 'Brown', 'david.brown@example.com', '$2y$10$abc123abc123abc123abcuvBo3zZzLl3AhtWc1wEupPg4pK7W52tu', 'Student', '2025-05-18 10:51:54', '2025-05-18 10:51:54'),
(18, 'Eva', 'Jones', 'eva.jones@example.com', '$2y$10$abc123abc123abc123abcuvBo3zZzLl3AhtWc1wEupPg4pK7W52tu', 'Admin', '2025-05-18 10:51:54', '2025-05-18 10:51:54'),
(19, 'Frank', 'Garcia', 'frank.garcia@example.com', '$2y$10$abc123abc123abc123abcuvBo3zZzLl3AhtWc1wEupPg4pK7W52tu', 'Student', '2025-05-18 10:51:54', '2025-05-18 10:51:54'),
(20, 'Grace', 'Martinez', 'grace.martinez@example.com', '$2y$10$abc123abc123abc123abcuvBo3zZzLl3AhtWc1wEupPg4pK7W52tu', 'Student', '2025-05-18 10:51:54', '2025-05-18 10:51:54'),
(21, 'Hank', 'Lopez', 'hank.lopez@example.com', '$2y$10$abc123abc123abc123abcuvBo3zZzLl3AhtWc1wEupPg4pK7W52tu', 'Admin', '2025-05-18 10:51:54', '2025-05-18 10:51:54'),
(22, 'Ivy', 'Gonzalez', 'ivy.gonzalez@example.com', '$2y$10$abc123abc123abc123abcuvBo3zZzLl3AhtWc1wEupPg4pK7W52tu', 'Student', '2025-05-18 10:51:54', '2025-05-18 10:51:54'),
(23, 'Jake', 'Wilson', 'jake.wilson@example.com', '$2y$10$abc123abc123abc123abcuvBo3zZzLl3AhtWc1wEupPg4pK7W52tu', 'Student', '2025-05-18 10:51:54', '2025-05-18 10:51:54'),
(24, 'Kate', 'Anderson', 'kate.anderson@example.com', '$2y$10$abc123abc123abc123abcuvBo3zZzLl3AhtWc1wEupPg4pK7W52tu', 'Admin', '2025-05-18 10:51:54', '2025-05-18 10:51:54'),
(25, 'Leo', 'Thomas', 'leo.thomas@example.com', '$2y$10$abc123abc123abc123abcuvBo3zZzLl3AhtWc1wEupPg4pK7W52tu', 'Student', '2025-05-18 10:51:54', '2025-05-18 10:51:54'),
(26, 'Mia', 'Taylor', 'mia.taylor@example.com', '$2y$10$abc123abc123abc123abcuvBo3zZzLl3AhtWc1wEupPg4pK7W52tu', 'Admin', '2025-05-18 10:51:54', '2025-05-18 10:51:54'),
(27, 'Nick', 'Moore', 'nick.moore@example.com', '$2y$10$abc123abc123abc123abcuvBo3zZzLl3AhtWc1wEupPg4pK7W52tu', 'Student', '2025-05-18 10:51:54', '2025-05-18 10:51:54'),
(28, 'Olivia', 'Jackson', 'olivia.jackson@example.com', '$2y$10$abc123abc123abc123abcuvBo3zZzLl3AhtWc1wEupPg4pK7W52tu', 'Admin', '2025-05-18 10:51:54', '2025-05-18 10:51:54'),
(29, 'Paul', 'Martin', 'paul.martin@example.com', '$2y$10$abc123abc123abc123abcuvBo3zZzLl3AhtWc1wEupPg4pK7W52tu', 'Student', '2025-05-18 10:51:54', '2025-05-18 10:51:54'),
(30, 'Quinn', 'Lee', 'quinn.lee@example.com', '$2y$10$abc123abc123abc123abcuvBo3zZzLl3AhtWc1wEupPg4pK7W52tu', 'Student', '2025-05-18 10:51:54', '2025-05-18 10:51:54'),
(31, 'Rosa', 'Perez', 'rosa.perez@example.com', '$2y$10$abc123abc123abc123abcuvBo3zZzLl3AhtWc1wEupPg4pK7W52tu', 'Admin', '2025-05-18 10:51:54', '2025-05-18 10:51:54'),
(32, 'Sam', 'White', 'sam.white@example.com', '$2y$10$abc123abc123abc123abcuvBo3zZzLl3AhtWc1wEupPg4pK7W52tu', 'Student', '2025-05-18 10:51:54', '2025-05-18 10:51:54'),
(33, 'Tina', 'Harris', 'tina.harris@example.com', '$2y$10$abc123abc123abc123abcuvBo3zZzLl3AhtWc1wEupPg4pK7W52tu', 'Admin', '2025-05-18 10:51:54', '2025-05-18 10:51:54'),
(34, 'Alex', 'Smith', 'alex.smith1@example.com', 'hashedpassword123', 'Student', '2025-05-18 11:05:57', '2025-05-18 11:05:57'),
(35, 'Alex', 'Johnson', 'alex.johnson2@example.com', 'hashedpassword123', 'Student', '2025-05-18 11:05:57', '2025-05-18 11:05:57'),
(36, 'Alex', 'Brown', 'alex.brown3@example.com', 'hashedpassword123', 'Student', '2025-05-18 11:05:57', '2025-05-18 11:05:57'),
(37, 'Alex', 'Taylor', 'alex.taylor4@example.com', 'hashedpassword123', 'Student', '2025-05-18 11:05:57', '2025-05-18 11:05:57'),
(38, 'Alex', 'Wilson', 'alex.wilson5@example.com', 'hashedpassword123', 'Student', '2025-05-18 11:05:57', '2025-05-18 11:05:57'),
(39, 'Alex', 'Lee', 'alex.lee6@example.com', 'hashedpassword123', 'Student', '2025-05-18 11:05:57', '2025-05-18 11:05:57'),
(40, 'Alex', 'Clark', 'alex.clark7@example.com', 'hashedpassword123', 'Student', '2025-05-18 11:05:57', '2025-05-18 11:05:57'),
(41, 'Alex', 'Hall', 'alex.hall8@example.com', 'hashedpassword123', 'Student', '2025-05-18 11:05:57', '2025-05-18 11:05:57'),
(42, 'Alex', 'Wright', 'alex.wright9@example.com', 'hashedpassword123', 'Student', '2025-05-18 11:05:57', '2025-05-18 11:05:57'),
(43, 'Alex', 'Scott', 'alex.scott10@example.com', 'hashedpassword123', 'Student', '2025-05-18 11:05:57', '2025-05-18 11:05:57');

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
  ADD KEY `GoalID` (`GoalID`),
  ADD KEY `fk_goal_logs_user` (`UserID`);

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
  ADD PRIMARY KEY (`SupportResourceID`),
  ADD KEY `fk_support_resources_category` (`ResourceCategory`);

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
  MODIFY `ForumPostID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `forum_replies`
--
ALTER TABLE `forum_replies`
  MODIFY `ReplyID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals`
  MODIFY `GoalID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `goal_logs`
--
ALTER TABLE `goal_logs`
  MODIFY `GoalLogID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

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
  MODIFY `MoodLogID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `planned_exercises`
--
ALTER TABLE `planned_exercises`
  MODIFY `PlannedExerciseID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reply_likes`
--
ALTER TABLE `reply_likes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `resource_categories`
--
ALTER TABLE `resource_categories`
  MODIFY `ResourceCategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sleep_logs`
--
ALTER TABLE `sleep_logs`
  MODIFY `SleepLogID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=176;

--
-- AUTO_INCREMENT for table `support_resources`
--
ALTER TABLE `support_resources`
  MODIFY `SupportResourceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

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
  ADD CONSTRAINT `fk_goal_logs_user` FOREIGN KEY (`UserID`) REFERENCES `users` (`id`) ON DELETE CASCADE,
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

--
-- Constraints for table `support_resources`
--
ALTER TABLE `support_resources`
  ADD CONSTRAINT `fk_support_resources_category` FOREIGN KEY (`ResourceCategory`) REFERENCES `resource_categories` (`ResourceCategoryID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
