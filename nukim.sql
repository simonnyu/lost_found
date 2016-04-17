-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- 主機: localhost
-- 產生時間： 2016 年 04 月 17 日 11:07
-- 伺服器版本: 5.5.47-0ubuntu0.14.04.1
-- PHP 版本： 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `nukim`
--

-- --------------------------------------------------------

--
-- 資料表結構 `AUTH`
--

CREATE TABLE `AUTH` (
  `U_ID` varchar(15) NOT NULL,
  `U_PWD` varchar(100) NOT NULL,
  `U_INIT` int(1) NOT NULL,
  `U_PRE` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `AUTH`
--

INSERT INTO `AUTH` (`U_ID`, `U_PWD`, `U_INIT`, `U_PRE`) VALUES
('A1033327', '3f6dc85983ff77621ad66318aa7c989a', 0, 0),
('A1033328', '5bb332f03a23a416a608a6038ea540be', 0, 1),
('test', '09fd459a5df5d928cce077444214d09a', 1, 1);

-- --------------------------------------------------------

--
-- 資料表結構 `cat`
--

CREATE TABLE `cat` (
  `CAT_ID` int(3) NOT NULL,
  `CAT_NAME` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `forgot`
--

CREATE TABLE `forgot` (
  `U_ID` varchar(15) CHARACTER SET utf16 NOT NULL,
  `U_EMAIL` varchar(50) NOT NULL,
  `TOKEN` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `history`
--

CREATE TABLE `history` (
  `HIS_ID` int(5) NOT NULL,
  `I_ID` int(10) NOT NULL,
  `F_U_ID` varchar(15) NOT NULL,
  `L_U_ID` varchar(15) NOT NULL,
  `EST_TIME` varchar(15) NOT NULL,
  `RET_TIME` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `item`
--

CREATE TABLE `item` (
  `I_ID` int(10) NOT NULL,
  `CAT_ID` int(3) NOT NULL,
  `LOC_ID` int(3) NOT NULL,
  `P_NO` varchar(12) NOT NULL,
  `TIME` date NOT NULL,
  `U_ID` varchar(15) NOT NULL,
  `I_DICPT` varchar(100) NOT NULL,
  `facebook` int(1) NOT NULL,
  `fb_url` varchar(100) DEFAULT NULL,
  `I_STAT` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `location`
--

CREATE TABLE `location` (
  `LOC_ID` int(3) NOT NULL,
  `LOC_NAME` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `OAUTH`
--

CREATE TABLE `OAUTH` (
  `U_ID` varchar(10) NOT NULL,
  `F_ID` varchar(20) DEFAULT NULL,
  `F_PROFIILE_URL` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `OAUTH_G`
--

CREATE TABLE `OAUTH_G` (
  `U_ID` varchar(10) NOT NULL,
  `G_ID` varchar(22) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `pic`
--

CREATE TABLE `pic` (
  `P_NO` varchar(15) NOT NULL,
  `P_N_LOC` varchar(100) NOT NULL,
  `P_LOC` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `user`
--

CREATE TABLE `user` (
  `U_ID` varchar(15) NOT NULL,
  `U_NAME` varchar(10) NOT NULL,
  `U_BRTH` date DEFAULT NULL,
  `U_EMAIL` varchar(50) DEFAULT NULL,
  `U_CEL` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `user`
--

INSERT INTO `user` (`U_ID`, `U_NAME`, `U_BRTH`, `U_EMAIL`, `U_CEL`) VALUES
('A1033327', '彭德馨', '1996-03-10', 'sal55960310@gmail.com', '0962011202'),
('A1033328', '余嘉翔', '1996-07-05', 'simonnyu@gmail.com', '0918077168'),
('test', '測試者', '2016-04-10', 'test@example.com', '0912345678');

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `AUTH`
--
ALTER TABLE `AUTH`
  ADD PRIMARY KEY (`U_ID`);

--
-- 資料表索引 `cat`
--
ALTER TABLE `cat`
  ADD PRIMARY KEY (`CAT_ID`);

--
-- 資料表索引 `forgot`
--
ALTER TABLE `forgot`
  ADD PRIMARY KEY (`TOKEN`);

--
-- 資料表索引 `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`HIS_ID`),
  ADD KEY `I_ID` (`I_ID`),
  ADD KEY `L_U_ID` (`L_U_ID`),
  ADD KEY `F_U_ID` (`F_U_ID`);

--
-- 資料表索引 `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`I_ID`),
  ADD KEY `CAT_ID_2` (`CAT_ID`),
  ADD KEY `P_NO` (`P_NO`),
  ADD KEY `U_ID` (`U_ID`),
  ADD KEY `LOC_ID` (`LOC_ID`);

--
-- 資料表索引 `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`LOC_ID`);

--
-- 資料表索引 `OAUTH`
--
ALTER TABLE `OAUTH`
  ADD PRIMARY KEY (`U_ID`);

--
-- 資料表索引 `OAUTH_G`
--
ALTER TABLE `OAUTH_G`
  ADD PRIMARY KEY (`U_ID`),
  ADD KEY `U_ID` (`U_ID`);

--
-- 資料表索引 `pic`
--
ALTER TABLE `pic`
  ADD PRIMARY KEY (`P_NO`);

--
-- 資料表索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`U_ID`);

--
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `cat`
--
ALTER TABLE `cat`
  MODIFY `CAT_ID` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- 使用資料表 AUTO_INCREMENT `history`
--
ALTER TABLE `history`
  MODIFY `HIS_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- 使用資料表 AUTO_INCREMENT `item`
--
ALTER TABLE `item`
  MODIFY `I_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- 使用資料表 AUTO_INCREMENT `location`
--
ALTER TABLE `location`
  MODIFY `LOC_ID` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- 已匯出資料表的限制(Constraint)
--

--
-- 資料表的 Constraints `AUTH`
--
ALTER TABLE `AUTH`
  ADD CONSTRAINT `AUTH_ibfk_1` FOREIGN KEY (`U_ID`) REFERENCES `user` (`U_ID`);

--
-- 資料表的 Constraints `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `history_ibfk_1` FOREIGN KEY (`F_U_ID`) REFERENCES `user` (`U_ID`),
  ADD CONSTRAINT `history_ibfk_2` FOREIGN KEY (`L_U_ID`) REFERENCES `user` (`U_ID`);

--
-- 資料表的 Constraints `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`P_NO`) REFERENCES `pic` (`P_NO`),
  ADD CONSTRAINT `item_ibfk_2` FOREIGN KEY (`U_ID`) REFERENCES `AUTH` (`U_ID`);

--
-- 資料表的 Constraints `OAUTH`
--
ALTER TABLE `OAUTH`
  ADD CONSTRAINT `OAUTH_ibfk_1` FOREIGN KEY (`U_ID`) REFERENCES `user` (`U_ID`);

--
-- 資料表的 Constraints `OAUTH_G`
--
ALTER TABLE `OAUTH_G`
  ADD CONSTRAINT `OAUTH_G_ibfk_1` FOREIGN KEY (`U_ID`) REFERENCES `user` (`U_ID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
