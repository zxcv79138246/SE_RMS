-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- 主機: 127.0.0.1
-- 產生時間： 2016 年 01 月 07 日 12:38
-- 伺服器版本: 10.0.21-MariaDB
-- PHP 版本： 7.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `se_rms`
--
CREATE DATABASE IF NOT EXISTS `se_rms` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `se_rms`;

-- --------------------------------------------------------

--
-- 資料表結構 `project`
--
-- 建立時間: 2016 年 01 月 04 日 07:03
--

DROP TABLE IF EXISTS `project`;
CREATE TABLE `project` (
  `p_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` text,
  `leader` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `project`
--

INSERT INTO `project` (`p_id`, `name`, `date`, `description`, `leader`) VALUES
(130, '專案', '2015-12-21 16:00:00', '222', 327),
(133, 'test', '2015-12-21 16:00:00', '1234561231312312312313', 327),
(135, 'Project1', '0000-00-00 00:00:00', 'Porject1', 332),
(143, 'Test01', '0000-00-00 00:00:00', 'Test01', 322),
(159, 'jfjsdfjoiewurioewporoewjkdsjfkldsjkfdsjfioweuriewprojkfjsdkfjsdf', '0000-00-00 00:00:00', 'fdghfghfgdhfgdjhhfdjiajfddsahfjsdhjfljsdlkfjslkdfjdskljfldsjfksdjkfjdsakfjdksjfiewruiewrjkdfjkdslfsfmnsafdsjifjsdkfjksdlfjlksdfjiowejrkjfksdjfklsdjakfjsdlkfjsdjfsdf', 334),
(168, '測試專案 Liu', '2015-12-31 08:02:47', '測試專案', 334),
(193, 'TEST Liu', '2016-01-02 08:44:46', 'sdfsdfdsafdsfdsaf', 334),
(204, 'Project', '2015-12-16 16:00:00', 'Test', 1),
(208, 'asdf', '2016-01-02 16:58:28', 'qwer', 334),
(209, 'fdf', '2016-01-02 17:11:39', 'sdafsdfsadf', 334),
(292, '1111111', '2016-01-03 16:10:23', '', 334),
(318, 'test001', '2016-01-03 20:47:27', 'test', 322),
(331, 'TestProject', '2015-12-17 16:00:00', 'Testing', 425),
(374, '測試-1/4', '2016-01-04 13:36:59', '2016/1/4', 332),
(382, 'manager測試專案', '2016-01-05 14:45:44', 'test', 494),
(383, 'Chen', '2016-01-06 01:10:02', '1234', 495);

-- --------------------------------------------------------

--
-- 資料表結構 `project_member`
--
-- 建立時間: 2015 年 12 月 30 日 16:42
--

DROP TABLE IF EXISTS `project_member`;
CREATE TABLE `project_member` (
  `p_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `priority` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `project_member`
--

INSERT INTO `project_member` (`p_id`, `u_id`, `priority`) VALUES
(135, 322, 2),
(135, 328, 0),
(135, 332, 2),
(135, 334, 0),
(135, 388, 1),
(135, 425, 1),
(135, 494, 1),
(143, 328, 0),
(143, 332, 0),
(159, 322, 0),
(168, 322, 0),
(168, 327, 0),
(168, 332, 0),
(168, 334, 2),
(168, 388, 1),
(193, 322, 0),
(193, 327, 2),
(193, 328, 0),
(193, 334, 2),
(193, 388, 0),
(208, 322, 0),
(208, 327, 2),
(208, 334, 2),
(209, 322, 1),
(209, 334, 2),
(209, 388, 1),
(292, 327, 1),
(292, 328, 1),
(292, 332, 1),
(292, 334, 2),
(292, 425, 0),
(318, 322, 2),
(318, 327, 1),
(318, 328, 1),
(318, 332, 2),
(318, 334, 1),
(318, 388, 1),
(318, 425, 1),
(318, 492, 1),
(318, 494, 1),
(331, 425, 0),
(374, 327, 1),
(374, 328, 0),
(374, 332, 2),
(382, 322, 0),
(382, 328, 2),
(382, 334, 1),
(382, 492, 0),
(382, 494, 2),
(383, 332, 1),
(383, 495, 2);

-- --------------------------------------------------------

--
-- 資料表結構 `requirement`
--
-- 建立時間: 2016 年 01 月 03 日 07:57
--

DROP TABLE IF EXISTS `requirement`;
CREATE TABLE `requirement` (
  `r_id` int(11) NOT NULL,
  `p_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `functional` tinyint(1) NOT NULL,
  `type` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `version` tinyint(11) NOT NULL,
  `level` int(11) NOT NULL,
  `state` varchar(255) NOT NULL,
  `owner` int(11) NOT NULL,
  `last_edit_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `memo` text,
  `attachment_file` text,
  `target` varchar(255) DEFAULT NULL,
  `precondition` text,
  `postcondition` text,
  `main_flow` text,
  `alter_flow` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `requirement`
--

INSERT INTO `requirement` (`r_id`, `p_id`, `name`, `functional`, `type`, `description`, `version`, `level`, `state`, `owner`, `last_edit_date`, `memo`, `attachment_file`, `target`, `precondition`, `postcondition`, `main_flow`, `alter_flow`) VALUES
(78, 135, 'requirement-002', 0, 'normal', 'requirement-002', 1, 2, '審核中', 332, '2016-01-06 02:11:54', '', NULL, NULL, NULL, NULL, NULL, NULL),
(81, 135, 'requirement-001', 1, 'normal', 'requirement-001', 1, 1, '審核通過', 332, '2016-01-03 18:19:22', '', NULL, NULL, NULL, NULL, NULL, NULL),
(82, 135, 'requirement-003', 0, 'normal', 'requirement-003', 2, 1, '審核通過', 332, '2016-01-03 18:28:08', 'aa', NULL, NULL, NULL, NULL, NULL, NULL),
(83, 193, 'sfgfsdg', 1, 'normal', 'dfgdfsg', 0, 0, '待審核', 334, '2016-01-02 21:29:05', 'dfgfdg', NULL, NULL, NULL, NULL, NULL, NULL),
(84, 193, 'dsfgdsfg', 1, 'normal', 'dfgdfsg', 0, 0, '待審核', 334, '2016-01-02 21:29:15', 'dfgdfg', NULL, NULL, NULL, NULL, NULL, NULL),
(85, 135, 'requirement-004', 1, 'normal', 'test　', 1, 2, '待審核', 332, '2016-01-05 09:44:22', '', NULL, NULL, NULL, NULL, NULL, NULL),
(86, 135, 'requirement-005', 1, 'normal', '1', 2, 3, '審核失敗', 332, '2016-01-04 12:26:32', '', NULL, NULL, NULL, NULL, NULL, NULL),
(95, 168, '1234', 1, 'normal', 'sdsfsadf123', 1, 1, '待審核', 334, '2016-01-05 00:45:47', '123sfgfsdgdfgdfg', NULL, NULL, NULL, NULL, NULL, NULL),
(112, 135, 'requirement-006', 1, 'normal', 'requirement-006\r\n', 2, 3, '審核通過', 332, '2016-01-04 13:36:28', '', NULL, NULL, NULL, NULL, NULL, NULL),
(113, 135, 'requirement-008', 1, 'normal', 'requirement-008', 2, 1, '審核中', 332, '2016-01-03 18:17:21', '', NULL, NULL, NULL, NULL, NULL, NULL),
(148, 318, '123', 1, 'normal', '1', 1, 1, '審核通過', 322, '2016-01-06 01:20:57', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(149, 318, 'test22', 1, 'normal', '2', 2, 2, '待審核', 322, '2016-01-05 10:20:42', '2', NULL, NULL, NULL, NULL, NULL, NULL),
(152, 318, 'asdasdasd', 1, 'usecase', 'sad', 0, 0, '待審核', 322, '2016-01-05 10:20:44', '1sdfs', NULL, '10', '1', '1', '1', '1'),
(162, 318, 'wefknl', 0, 'usecase', 'ed', 1, 1, '待審核', 322, '2016-01-05 10:20:46', 'm;', NULL, 'ml;', 'ml;', 'ml;', 'ml;', 'm;l'),
(163, 318, 'test', 1, 'normal', '1', 1, 1, '待審核', 322, '2016-01-05 10:20:48', '1\r\n', NULL, NULL, NULL, NULL, NULL, NULL),
(164, 318, '123a', 1, 'usecase', '11', 1, 1, '待審核', 322, '2016-01-05 10:11:52', '1', NULL, '5', '5', '5', '5', '5'),
(171, 374, 'login', 1, 'usecase', 'login', 1, 1, '待審核', 332, '2016-01-04 05:52:43', '1', NULL, '1', '1', '1', '1', '1'),
(172, 374, 'requirement', 1, 'normal', 'requirementManage', 1, 1, '審核中', 328, '2016-01-04 13:50:28', '', NULL, NULL, NULL, NULL, NULL, NULL),
(173, 374, 'testcase', 1, 'normal', 'testcasemanage', 1, 1, '待審核', 332, '2016-01-05 09:22:35', '', NULL, NULL, NULL, NULL, NULL, NULL),
(174, 374, 'review', 1, 'normal', 'reviewsystem', 1, 1, '審核中', 328, '2016-01-04 13:50:39', '', NULL, NULL, NULL, NULL, NULL, NULL),
(175, 374, 'report', 1, 'normal', 'traceability-matrix ...', 1, 1, '審核中', 328, '2016-01-04 13:50:40', '', NULL, NULL, NULL, NULL, NULL, NULL),
(176, 374, 'relationmanage', 1, 'normal', 'R&R R&T relation manage', 1, 1, '待審核', 332, '2016-01-05 17:08:09', '', NULL, NULL, NULL, NULL, NULL, NULL),
(177, 374, 'wrong requirement', 1, 'normal', 'wrong requirement (will be deleted)', 1, 1, '審核失敗', 328, '2016-01-04 13:51:18', '', NULL, NULL, NULL, NULL, NULL, NULL),
(178, 374, 'usecaseTest', 1, 'usecase', 'test', 1, 3, '待審核', 332, '2016-01-05 09:27:33', '', NULL, 'a', 'aa', 'bb', 'xxxx', 'ss'),
(181, 159, '123', 1, 'normal', '1', 1, 1, '待審核', 322, '2016-01-04 05:45:57', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(182, 374, 'project', 1, 'normal', 'project', 1, 2, '待審核', 332, '2016-01-05 09:22:47', '', NULL, NULL, NULL, NULL, NULL, NULL),
(186, 318, '123341', 1, 'normal', '1', 1, 1231, '待審核', 322, '2016-01-05 08:38:19', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(188, 135, 'requirement-011', 0, 'normal', 'Just Test', 1, 1, '審核中', 332, '2016-01-05 06:46:56', 'nothing', NULL, NULL, NULL, NULL, NULL, NULL),
(189, 168, 'sdsfdsf', 1, 'usecase', 'sadfsdaf', 1, 0, '待審核', 334, '2016-01-04 23:25:38', 'dsfsdaf', NULL, 'sfsad', 'dafwf', 'dfsdf', 'sdfdsaf', 'sdfsdaf'),
(190, 292, 'qwrwer', 1, 'normal', 'qwewqe', 1, 0, '待審核', 334, '2016-01-05 06:59:10', 'wewerewrwer', NULL, NULL, NULL, NULL, NULL, NULL),
(191, 292, 'werwer', 1, 'normal', 'werwerewr', 0, 0, '待審核', 334, '2016-01-05 06:59:31', 'werwerewrewar', NULL, NULL, NULL, NULL, NULL, NULL),
(192, 135, 'req-441', 1, 'normal', 'sfsdfssdfsafsdfdsfdsafsdfsdaf', 3, 2, '待審核', 332, '2016-01-05 18:09:56', 'sfsdaf', NULL, NULL, NULL, NULL, NULL, NULL),
(193, 135, 'test-req', 1, 'normal', 'sadfsdaf', 1, 2, '審核中', 332, '2016-01-05 15:01:59', 'sdfdsfdsfdasf', NULL, NULL, NULL, NULL, NULL, NULL),
(194, 383, '1', 1, 'normal', '1', 1, 1, '審核中', 495, '2016-01-06 01:13:25', '', NULL, NULL, NULL, NULL, NULL, NULL),
(195, 383, '2', 1, 'normal', '23333', 2, 2, '待審核', 495, '2016-01-05 17:12:51', '', NULL, NULL, NULL, NULL, NULL, NULL),
(196, 135, 'asdasd', 1, 'usecase', 'asd', 1, 1, '待審核', 332, '2016-01-05 18:08:23', 'asdsd', NULL, 'asdsad', 'sadasd', 'sadasd', 'asdasd', 'sadasdasd');

-- --------------------------------------------------------

--
-- 資料表結構 `reviewer`
--
-- 建立時間: 2016 年 01 月 03 日 09:48
--

DROP TABLE IF EXISTS `reviewer`;
CREATE TABLE `reviewer` (
  `u_id` int(11) NOT NULL,
  `r_id` int(11) NOT NULL,
  `p_id` int(11) NOT NULL,
  `decision` tinyint(1) NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `reviewer`
--

INSERT INTO `reviewer` (`u_id`, `r_id`, `p_id`, `decision`, `comment`) VALUES
(327, 172, 374, 2, 'i like it'),
(327, 174, 374, 2, 'oh oh oh'),
(327, 175, 374, 1, 'i dont like report'),
(332, 194, 383, 2, 'XiaoPonHaoPon'),
(388, 78, 135, 1, 'sdfsdfsgs'),
(388, 113, 135, 0, '');

-- --------------------------------------------------------

--
-- 資料表結構 `r_and_r_relation`
--
-- 建立時間: 2016 年 01 月 03 日 07:56
--

DROP TABLE IF EXISTS `r_and_r_relation`;
CREATE TABLE `r_and_r_relation` (
  `r_id1` int(11) NOT NULL,
  `r_id2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `r_and_r_relation`
--

INSERT INTO `r_and_r_relation` (`r_id1`, `r_id2`) VALUES
(81, 85),
(82, 85),
(82, 86),
(82, 113),
(95, 189),
(112, 85),
(112, 86),
(112, 113),
(162, 149),
(176, 172),
(176, 173),
(176, 174),
(176, 175),
(178, 172),
(182, 172),
(182, 173),
(182, 174),
(182, 175),
(182, 176),
(182, 178),
(194, 195);

-- --------------------------------------------------------

--
-- 資料表結構 `r_and_t_relation`
--
-- 建立時間: 2016 年 01 月 05 日 07:03
--

DROP TABLE IF EXISTS `r_and_t_relation`;
CREATE TABLE `r_and_t_relation` (
  `r_id` int(11) NOT NULL,
  `t_id` int(11) NOT NULL,
  `is_validate` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `r_and_t_relation`
--

INSERT INTO `r_and_t_relation` (`r_id`, `t_id`, `is_validate`) VALUES
(81, 6, 1),
(81, 40, 1),
(82, 8, 1),
(85, 6, 1),
(85, 8, 1),
(85, 40, 1),
(95, 22, 1),
(113, 8, 1),
(113, 38, 1),
(148, 72, 1),
(148, 84, 1),
(149, 69, 1),
(149, 72, 1),
(149, 84, 1),
(152, 69, 1),
(152, 72, 1),
(152, 84, 1),
(162, 72, 1),
(162, 84, 1),
(163, 72, 1),
(163, 84, 1),
(164, 72, 1),
(164, 84, 1),
(171, 74, 1),
(172, 75, 1),
(172, 77, 1),
(172, 78, 1),
(173, 78, 1),
(174, 75, 1),
(174, 76, 1),
(174, 78, 1),
(174, 79, 1),
(175, 78, 1),
(176, 75, 1),
(176, 76, 1),
(176, 77, 1),
(176, 78, 1),
(178, 75, 1),
(178, 78, 1),
(182, 79, 1),
(186, 72, 1),
(186, 84, 1),
(188, 8, 1),
(188, 38, 1),
(192, 8, 1);

-- --------------------------------------------------------

--
-- 資料表結構 `test_case`
--
-- 建立時間: 2016 年 01 月 03 日 07:57
--

DROP TABLE IF EXISTS `test_case`;
CREATE TABLE `test_case` (
  `t_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `input_data` text NOT NULL,
  `expected_results` text NOT NULL,
  `owner` int(11) NOT NULL,
  `attachment` text,
  `p_id` int(11) NOT NULL,
  `last_edit_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `test_case`
--

INSERT INTO `test_case` (`t_id`, `name`, `description`, `input_data`, `expected_results`, `owner`, `attachment`, `p_id`, `last_edit_date`) VALUES
(6, '測試2', 'add(a,b)', 'add(a,b)', '3;-1;', 332, '', 135, '2016-01-02 19:32:08'),
(8, '測試3', 'project 135 的測試', 'a;b;c;d;e;', 'A;B;C;D;E;', 328, '', 135, '2016-01-01 13:12:49'),
(22, 'sfsfsfsaf', 'dsafdsafsdaf', 'sdfsaafd', 'dsafsadf', 334, 'asdfdsaf', 168, '2016-01-01 19:33:10'),
(28, 'test', 'easr', 'asd', 'zxc', 322, '', 168, '2016-01-02 08:24:04'),
(38, '測試4', '123', '123', '312', 332, '', 135, '2016-01-02 19:12:31'),
(40, '測試5', '2', '3', '1', 332, '', 135, '2016-01-02 22:44:00'),
(46, 'dfgsfg', 'dfsgdfsg', 'dfgdfg', 'gdfsgdfs', 334, 'dssfdg', 193, '2016-01-03 05:29:27'),
(47, 'werwe', 'sfsadf', 'dgdfg', 'dsfsdaf', 334, 'dgdfg', 193, '2016-01-03 05:29:36'),
(69, 'testcase_001', '123', '123', '123', 322, '123', 318, '2016-01-04 05:52:14'),
(72, 'w;em', 'klvsdn', 'klvsdn', 'dkflsn', 334, '1', 318, '2016-01-05 18:14:50'),
(74, 'test-login', 'test', 'acc pwd', 'login', 327, 'aaaa', 374, '2016-01-04 13:42:28'),
(75, 'test-req', 'requirement', 'req info', 'sssss', 327, '', 374, '2016-01-04 13:42:59'),
(76, 'test-testcase', 'aa', 'testcaseinfo', 'asf', 327, 'testf', 374, '2016-01-04 13:43:15'),
(77, 'test-relation', 'test-relation', 'test-relation', 'test-relation', 327, '', 374, '2016-01-04 13:43:30'),
(78, 'test-report', 'test-report', 'test-report', 'test-report', 327, 'ssss', 374, '2016-01-04 13:43:41'),
(79, 'test-project', 'abc', 'asdf', 'aaaa', 327, 'r', 374, '2016-01-04 13:43:51'),
(80, 'test-wrong', 'test-wrong', 'test-wrong', 'test-wrong', 327, 'test-wrong', 374, '2016-01-04 13:49:10'),
(82, 'testX', 'Just test', 'Just test', '321', 332, '', 135, '2016-01-05 06:47:48'),
(83, 'werwer', 'werwer', 'werewr', 'werewrweewr', 334, 'werewr', 292, '2016-01-05 14:59:21'),
(84, '111', '111', '111', '111', 322, '111', 318, '2016-01-05 16:36:19'),
(85, '11', '12', '13', '4111', 495, '', 383, '2016-01-06 01:11:30');

-- --------------------------------------------------------

--
-- 資料表結構 `user`
--
-- 建立時間: 2015 年 12 月 06 日 07:05
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `u_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `priority` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `user`
--

INSERT INTO `user` (`u_id`, `name`, `email`, `password`, `priority`) VALUES
(1, 'admin', 'admin', '1234', 2),
(322, 'WeiCheng', 'wsx741405@gmail.com', '123456789', 1),
(327, 'zxc', 'zxc', 'zxc', 0),
(328, 'ye', 'ye', 'ye', 1),
(332, 'Liu', 'Liu', '1234', 1),
(334, '測試', 'Test', '1234', 1),
(388, 'user', 'user', '1234', 0),
(425, 'TestManS', 'TestMan@gmail.com', 'passwd', 0),
(492, 'TT', 'QQ@QQ', '1234', 0),
(494, 'manager', 'manager', '1234', 1),
(495, 'XiaoPon', 'XP', 'XP', 1);

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`p_id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `leader` (`leader`);

--
-- 資料表索引 `project_member`
--
ALTER TABLE `project_member`
  ADD PRIMARY KEY (`p_id`,`u_id`),
  ADD KEY `u_id` (`u_id`);

--
-- 資料表索引 `requirement`
--
ALTER TABLE `requirement`
  ADD PRIMARY KEY (`r_id`),
  ADD KEY `owner` (`owner`),
  ADD KEY `p_id` (`p_id`);

--
-- 資料表索引 `reviewer`
--
ALTER TABLE `reviewer`
  ADD PRIMARY KEY (`u_id`,`r_id`),
  ADD KEY `r_id` (`r_id`),
  ADD KEY `reviewer_ibfk_5` (`p_id`);

--
-- 資料表索引 `r_and_r_relation`
--
ALTER TABLE `r_and_r_relation`
  ADD PRIMARY KEY (`r_id1`,`r_id2`),
  ADD KEY `r_id2` (`r_id2`);

--
-- 資料表索引 `r_and_t_relation`
--
ALTER TABLE `r_and_t_relation`
  ADD PRIMARY KEY (`r_id`,`t_id`),
  ADD KEY `t_id` (`t_id`);

--
-- 資料表索引 `test_case`
--
ALTER TABLE `test_case`
  ADD PRIMARY KEY (`t_id`),
  ADD KEY `p_id` (`p_id`),
  ADD KEY `owner` (`owner`);

--
-- 資料表索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`u_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `project`
--
ALTER TABLE `project`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=386;
--
-- 使用資料表 AUTO_INCREMENT `requirement`
--
ALTER TABLE `requirement`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;
--
-- 使用資料表 AUTO_INCREMENT `test_case`
--
ALTER TABLE `test_case`
  MODIFY `t_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;
--
-- 使用資料表 AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=498;
--
-- 已匯出資料表的限制(Constraint)
--

--
-- 資料表的 Constraints `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `leader` FOREIGN KEY (`leader`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表的 Constraints `project_member`
--
ALTER TABLE `project_member`
  ADD CONSTRAINT `project_member_ibfk_2` FOREIGN KEY (`p_id`) REFERENCES `project` (`p_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `project_member_ibfk_3` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表的 Constraints `requirement`
--
ALTER TABLE `requirement`
  ADD CONSTRAINT `owner_foreign` FOREIGN KEY (`owner`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `requirement_ibfk_2` FOREIGN KEY (`p_id`) REFERENCES `project` (`p_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表的 Constraints `reviewer`
--
ALTER TABLE `reviewer`
  ADD CONSTRAINT `reviewer_ibfk_3` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `reviewer_ibfk_4` FOREIGN KEY (`r_id`) REFERENCES `requirement` (`r_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reviewer_ibfk_5` FOREIGN KEY (`p_id`) REFERENCES `project` (`p_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表的 Constraints `r_and_r_relation`
--
ALTER TABLE `r_and_r_relation`
  ADD CONSTRAINT `r_and_r_relation_ibfk_1` FOREIGN KEY (`r_id1`) REFERENCES `requirement` (`r_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_and_r_relation_ibfk_2` FOREIGN KEY (`r_id2`) REFERENCES `requirement` (`r_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表的 Constraints `r_and_t_relation`
--
ALTER TABLE `r_and_t_relation`
  ADD CONSTRAINT `r_and_t_relation_ibfk_4` FOREIGN KEY (`t_id`) REFERENCES `test_case` (`t_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_and_t_relation_ibfk_5` FOREIGN KEY (`r_id`) REFERENCES `requirement` (`r_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表的 Constraints `test_case`
--
ALTER TABLE `test_case`
  ADD CONSTRAINT `test_case_ibfk_1` FOREIGN KEY (`p_id`) REFERENCES `project` (`p_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `test_case_ibfk_2` FOREIGN KEY (`owner`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
