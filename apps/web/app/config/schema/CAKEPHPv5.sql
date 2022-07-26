-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost:8889
-- 生成日時: 2022 年 7 月 20 日 06:36
-- サーバのバージョン： 5.7.34
-- PHP のバージョン: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `blocksmith`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `admins`
--

CREATE TABLE `admins` (
  `id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `name` varchar(40) NOT NULL DEFAULT '',
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `admins`
--

INSERT INTO `admins` (`id`, `created`, `modified`, `name`, `username`, `password`, `role`) VALUES
(1, '2022-01-06 16:02:41', '2022-01-06 16:02:41', '管理者', 'caters_admin', '$2y$10$7X.icRPhUBnFrsoBR784y.VMC9IrXxbbinEff3WMGa0N.WG3D8kH6', 0);

-- --------------------------------------------------------

--
-- テーブルの構造 `append_items`
--

CREATE TABLE `append_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `page_config_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `position` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(40) NOT NULL DEFAULT '',
  `slug` varchar(30) NOT NULL DEFAULT '',
  `value_type` decimal(2,0) NOT NULL DEFAULT '0',
  `max_length` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `is_required` decimal(1,0) UNSIGNED NOT NULL DEFAULT '0',
  `use_option_list` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `value_default` varchar(100) NOT NULL DEFAULT '',
  `value_placeholder` varchar(500) NOT NULL DEFAULT '',
  `attention` varchar(100) NOT NULL DEFAULT '',
  `editable_role` varchar(100) NOT NULL DEFAULT 'staff',
  `viewable_role` varchar(100) NOT NULL DEFAULT 'staff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `page_config_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `parent_category_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `position` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `status` enum('draft','publish') NOT NULL DEFAULT 'publish',
  `name` varchar(40) NOT NULL DEFAULT '',
  `identifier` varchar(30) NOT NULL DEFAULT '',
  `image` varchar(100) NOT NULL DEFAULT '',
  `image_sp` varchar(100) NOT NULL DEFAULT '',
  `multiple_level` int(11) NOT NULL DEFAULT '1' COMMENT '階層',
  `yomigana` varchar(100) NOT NULL DEFAULT '',
  `yomigana_initial` varchar(5) NOT NULL DEFAULT '' COMMENT 'あかさたな他',
  `color` varchar(100) NOT NULL DEFAULT '',
  `value_text` varchar(100) NOT NULL DEFAULT '',
  `value_text2` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `contacts`
--

CREATE TABLE `contacts` (
  `id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `position` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `status` enum('draft','publish') NOT NULL DEFAULT 'publish',
  `contact_type_ids` varchar(100) NOT NULL DEFAULT '' COMMENT 'お問合種別',
  `relationship_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL,
  `furi` varchar(100) NOT NULL,
  `gender_id` int(11) NOT NULL DEFAULT '0',
  `zip` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `tel` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `pref` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `infos`
--

CREATE TABLE `infos` (
  `id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `page_config_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `position` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `status` enum('draft','publish') NOT NULL DEFAULT 'draft',
  `slug` varchar(100) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `notes` mediumtext NOT NULL,
  `start_datetime` datetime DEFAULT NULL,
  `end_datetime` datetime DEFAULT NULL,
  `image` varchar(100) NOT NULL DEFAULT '',
  `meta_description` varchar(200) NOT NULL DEFAULT '',
  `meta_keywords` varchar(200) NOT NULL DEFAULT '',
  `regist_user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `category_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `index_type` decimal(1,0) NOT NULL DEFAULT '0',
  `multi_position` bigint(20) NOT NULL DEFAULT '0',
  `is_top` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'トップ表示',
  `view_table_content` tinyint(4) NOT NULL DEFAULT '0',
  `course_category_ids` varchar(500) NOT NULL DEFAULT '',
  `relation_info_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `infos`
--

INSERT INTO `infos` (`id`, `created`, `modified`, `page_config_id`, `position`, `status`, `slug`, `title`, `notes`, `start_datetime`, `end_datetime`, `image`, `meta_description`, `meta_keywords`, `regist_user_id`, `category_id`, `index_type`, `multi_position`, `is_top`, `view_table_content`, `course_category_ids`, `relation_info_id`) VALUES
(7, '2022-07-19 15:22:38', '2022-07-19 15:22:38', 1, 11, 'draft', '', 'Demo', '', '2022-07-19 00:00:00', NULL, '', '', '', 1, 0, '0', 0, 0, 0, '', 0),
(8, '2022-07-19 15:31:56', '2022-07-19 15:33:13', 1, 10, 'publish', '', 'ブロックチェーン関連事業子会社「株式会社BLOCKSMITH&Co.」設立のお知らせ', '', '2022-07-19 00:00:00', NULL, '', '', '', 1, 0, '0', 0, 0, 0, '', 0),
(9, '2022-07-20 12:47:27', '2022-07-20 14:05:28', 1, 9, 'publish', '', '<script>alert(123)</script>', '', '2022-07-19 00:00:00', NULL, '', '', '', 3, 0, '0', 0, 0, 0, '', 0),
(10, '2022-07-20 14:05:36', '2022-07-20 14:05:36', 1, 8, 'draft', '', '1', '', '2022-07-20 00:00:00', NULL, '', '', '', 3, 0, '0', 0, 0, 0, '', 0),
(11, '2022-07-20 14:05:41', '2022-07-20 14:05:41', 1, 7, 'draft', '', '2', '', '2022-07-20 00:00:00', NULL, '', '', '', 3, 0, '0', 0, 0, 0, '', 0),
(12, '2022-07-20 14:06:55', '2022-07-20 14:06:55', 1, 6, 'draft', '', '5', '', '2022-07-20 00:00:00', NULL, '', '', '', 3, 0, '0', 0, 0, 0, '', 0),
(13, '2022-07-20 14:07:01', '2022-07-20 14:07:01', 1, 5, 'draft', '', '6', '', '2022-07-20 00:00:00', NULL, '', '', '', 3, 0, '0', 0, 0, 0, '', 0),
(14, '2022-07-20 14:10:56', '2022-07-20 14:10:56', 1, 4, 'draft', '', '7', '', '2022-07-20 00:00:00', NULL, '', '', '', 3, 0, '0', 0, 0, 0, '', 0),
(15, '2022-07-20 14:11:01', '2022-07-20 14:11:01', 1, 3, 'draft', '', '8', '', '2022-07-20 00:00:00', NULL, '', '', '', 3, 0, '0', 0, 0, 0, '', 0),
(16, '2022-07-20 14:11:06', '2022-07-20 14:11:06', 1, 2, 'draft', '', '9', '', '2022-07-20 00:00:00', NULL, '', '', '', 3, 0, '0', 0, 0, 0, '', 0),
(17, '2022-07-20 14:11:12', '2022-07-20 14:11:12', 1, 1, 'draft', '', '10', '', '2022-07-20 00:00:00', NULL, '', '', '', 3, 0, '0', 0, 0, 0, '', 0);

-- --------------------------------------------------------

--
-- テーブルの構造 `info_append_items`
--

CREATE TABLE `info_append_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `info_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `append_item_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `value_text` varchar(200) NOT NULL DEFAULT '',
  `value_text2` varchar(500) NOT NULL DEFAULT '',
  `value_text3` varchar(500) NOT NULL DEFAULT '',
  `value_textarea` mediumtext NOT NULL,
  `value_textarea2` mediumtext NOT NULL,
  `value_date` date NOT NULL DEFAULT '0000-00-00',
  `value_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `value_time` time NOT NULL DEFAULT '00:00:00',
  `value_int` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `value_decimal` decimal(3,0) UNSIGNED NOT NULL DEFAULT '0',
  `file` varchar(100) NOT NULL DEFAULT '',
  `file_size` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `file_name` varchar(100) NOT NULL DEFAULT '',
  `file_extension` varchar(10) NOT NULL DEFAULT '',
  `image` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `info_categories`
--

CREATE TABLE `info_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `info_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `category_id` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `info_contents`
--

CREATE TABLE `info_contents` (
  `id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `info_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `block_type` decimal(2,0) NOT NULL DEFAULT '0',
  `position` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `content` mediumtext NOT NULL,
  `image` varchar(100) NOT NULL DEFAULT '',
  `image_pos` varchar(10) NOT NULL DEFAULT '',
  `file` varchar(100) NOT NULL DEFAULT '',
  `file_size` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `file_name` varchar(100) NOT NULL DEFAULT '',
  `file_extension` varchar(10) NOT NULL DEFAULT '',
  `section_sequence_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `option_value` varchar(255) NOT NULL DEFAULT '',
  `option_value2` varchar(40) NOT NULL DEFAULT '',
  `option_value3` varchar(40) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `info_contents`
--

INSERT INTO `info_contents` (`id`, `created`, `modified`, `info_id`, `block_type`, `position`, `title`, `content`, `image`, `image_pos`, `file`, `file_size`, `file_name`, `file_extension`, `section_sequence_id`, `option_value`, `option_value2`, `option_value3`) VALUES
(1, '2022-07-19 14:58:47', '2022-07-19 14:58:47', 5, '18', 1, 'aaaaaaaaaaaaaaaaaaaaaaa', '', '', '', '', 0, '', '', 0, '', '', ''),
(2, '2022-07-19 14:58:47', '2022-07-19 14:58:47', 5, '1', 2, 'bbbbbbbbbbbb', '', '', '', '', 0, '', '', 0, '', '', ''),
(3, '2022-07-19 15:02:00', '2022-07-19 15:07:20', 6, '9', 1, '', '', '', '', '', 0, '', '', 0, '', '', ''),
(4, '2022-07-19 15:22:38', '2022-07-19 15:22:38', 7, '18', 1, 'test h2', '', '', '', '', 0, '', '', 0, '', '', ''),
(5, '2022-07-19 15:22:38', '2022-07-19 15:22:38', 7, '2', 2, '', '<p>test honbun</p>', '', '', '', 0, '', '', 0, '', '', ''),
(6, '2022-07-19 15:22:38', '2022-07-19 15:22:38', 7, '1', 3, 'test h3', '', '', '', '', 0, '', '', 0, '', '', ''),
(7, '2022-07-19 15:22:38', '2022-07-19 15:22:38', 7, '5', 4, 'test h4', '', '', '', '', 0, '', '', 0, '', '', ''),
(8, '2022-07-19 15:31:56', '2022-07-19 15:33:13', 8, '2', 1, '', '<p><span style=\"color:rgb(0,0,0);\">KLab株式会社(本社:東京都港区、代表取締役社長:森田英克、以下「KLab」)は、Web3関連事業を管轄する子会社を設立し、ブロックチェーン関連事業に参入することをお知らせいたします。</span><br><span style=\"color:rgb(0,0,0);\">2022年3月開催の取締役会において「株式会社BLOCKSMITH&amp;Co.」(以下「BLOCKSMITH」)の設立を決議いたしました。代表取締役社長にはKLabの取締役会長ファウンダーである真田哲弥が就任いたします。</span><br>&nbsp;</p>', '', '', '', 0, '', '', 0, '', '', ''),
(9, '2022-07-19 15:31:56', '2022-07-19 15:33:13', 8, '18', 2, 'ブロックチェーン関連事業子会社「株式会社BLOCKSMITH&Co.」設立のお知らせ', '', '', '', '', 0, '', '', 0, '', '', ''),
(10, '2022-07-19 15:31:56', '2022-07-19 15:33:13', 8, '2', 3, '', '<p>&nbsp;</p><p><span style=\"color:rgb(0,0,0);\">産業史を振り返ると1980年代のPC、1990年代のインターネット、2010年代のSNSとクラウドなど、約10年周期でイノベーションが起こり、新たな産業と企業が勃興してきました。2020年代に入り、ブロックチェーン技術を活用したDeFiやNFT、或いはWeb3やメタバースなどサービスや概念が出現しました。私達は、ブロックチェーンこそがインターネット革命以来の新潮流だと考えています。</span></p>', '', '', '', 0, '', '', 0, '', '', ''),
(11, '2022-07-19 15:31:56', '2022-07-19 15:33:13', 8, '1', 4, 'ブロックチェーンとトークンエコノミーによって実現（H3）', '', '', '', '', 0, '', '', 0, '', '', ''),
(12, '2022-07-19 15:31:56', '2022-07-19 15:33:13', 8, '2', 5, '', '<p><span style=\"color:rgb(0,0,0);\">ゲーム産業においては、2010年代にモバイルというデバイスとFree to Playというビジネスモデルが新たなユーザーと新たな市場を開拓しゲーム産業の規模を拡大しましたが、今度はGameFiと呼ばれる新たなゲームモデルが再び新たなユーザーと新たなゲーム市場を開拓すると考えています。</span><br><span style=\"color:rgb(0,0,0);\">GameFiとは「Game+Finance をブロックチェーンとトークンエコノミーによって実現しようとする新しいゲームカテゴリー」、簡単に言うと「遊んで稼げるゲーム」です。</span></p>', '', '', '', 0, '', '', 0, '', '', ''),
(13, '2022-07-19 15:31:56', '2022-07-19 15:33:13', 8, '5', 6, '背景（H4）', '', '', '', '', 0, '', '', 0, '', '', ''),
(14, '2022-07-19 15:31:56', '2022-07-19 15:33:13', 8, '2', 7, '', '<p><span style=\"color:rgb(0,0,0);\">産業史を振り返ると1980年代のPC、1990年代のインターネット、2010年代のSNSとクラウドなど、約10年周期でイノベーションが起こり、新たな産業と企業が勃興してきました。2020年代に入り、ブロックチェーン技術を活用したDeFiやNFT、或いはWeb3やメタバースなどサービスや概念が出現しました。私達は、ブロックチェーンこそがインターネット革命以来の新潮流だと考えています。</span></p>', '', '', '', 0, '', '', 0, '', '', '');

-- --------------------------------------------------------

--
-- テーブルの構造 `info_tags`
--

CREATE TABLE `info_tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `info_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `tag_id` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `info_tops`
--

CREATE TABLE `info_tops` (
  `id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `page_config_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `position` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `info_id` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `mst_lists`
--

CREATE TABLE `mst_lists` (
  `id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `position` decimal(3,0) NOT NULL COMMENT '表示順',
  `status` enum('publish','draft') NOT NULL DEFAULT 'publish',
  `use_target_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `ltrl_nm` varchar(60) DEFAULT NULL,
  `ltrl_val` varchar(60) DEFAULT NULL,
  `ltrl_sub_val` mediumtext,
  `ltrl_slug` varchar(100) NOT NULL DEFAULT '',
  `list_name` varchar(100) NOT NULL DEFAULT '',
  `list_slug` varchar(100) NOT NULL DEFAULT '',
  `sys_cd` decimal(2,0) NOT NULL DEFAULT '0',
  `option_value1` varchar(100) NOT NULL DEFAULT '',
  `option_value2` varchar(100) NOT NULL DEFAULT '',
  `option_value3` varchar(100) NOT NULL DEFAULT '',
  `option_value4` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `page_configs`
--

CREATE TABLE `page_configs` (
  `id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `site_config_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `position` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `page_title` varchar(100) NOT NULL DEFAULT '',
  `slug` varchar(40) NOT NULL DEFAULT '',
  `header` mediumtext NOT NULL,
  `footer` mediumtext NOT NULL,
  `editable_role` enum('develop','admin','staff','demo') NOT NULL DEFAULT 'demo' COMMENT '編集権限',
  `deletable_role` enum('develop','admin','staff','demo') NOT NULL DEFAULT 'demo' COMMENT '削除権限',
  `addable_role` enum('develop','admin','staff','demo') NOT NULL DEFAULT 'demo' COMMENT '新規追加権限',
  `is_public_date` decimal(1,0) NOT NULL DEFAULT '0',
  `is_public_time` decimal(1,0) NOT NULL DEFAULT '0',
  `page_template_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `description` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `need_infotops` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'トップ表示機能',
  `is_category` enum('Y','N') NOT NULL DEFAULT 'N',
  `category_name_1` varchar(100) NOT NULL DEFAULT 'カテゴリー',
  `category_name_2` varchar(100) NOT NULL DEFAULT 'カテゴリー',
  `category_name_3` varchar(100) NOT NULL DEFAULT 'カテゴリー',
  `category_is_need_identifier_1` tinyint(4) NOT NULL DEFAULT '0',
  `category_is_need_identifier_2` tinyint(4) NOT NULL DEFAULT '0',
  `category_is_need_identifier_3` tinyint(4) NOT NULL DEFAULT '0',
  `is_category_sort` enum('Y','N') NOT NULL DEFAULT 'Y',
  `is_category_multiple` decimal(1,0) NOT NULL DEFAULT '0',
  `is_category_multilevel` decimal(1,0) NOT NULL DEFAULT '0',
  `need_all_category_select` tinyint(4) NOT NULL DEFAULT '0' COMMENT '管理画面上ですべて選択可能に',
  `category_editable_role` enum('develop','admin','staff','demo') NOT NULL DEFAULT 'staff',
  `max_multilevel` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `before_save_callback` varchar(40) NOT NULL DEFAULT '',
  `after_save_callback` varchar(40) NOT NULL DEFAULT '',
  `after_enable_callback` varchar(40) NOT NULL DEFAULT '',
  `ad_find_order_callback` varchar(40) NOT NULL DEFAULT '',
  `disable_position_order` decimal(1,0) NOT NULL DEFAULT '0',
  `disable_preview` decimal(1,0) NOT NULL DEFAULT '0',
  `list_style` decimal(2,0) NOT NULL DEFAULT '1',
  `root_dir_type` decimal(1,0) NOT NULL DEFAULT '0',
  `link_color` varchar(10) NOT NULL DEFAULT '',
  `index_url` varchar(100) NOT NULL DEFAULT '',
  `detail_url` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `page_configs`
--

INSERT INTO `page_configs` (`id`, `created`, `modified`, `site_config_id`, `position`, `page_title`, `slug`, `header`, `footer`, `editable_role`, `deletable_role`, `addable_role`, `is_public_date`, `is_public_time`, `page_template_id`, `description`, `keywords`, `need_infotops`, `is_category`, `category_name_1`, `category_name_2`, `category_name_3`, `category_is_need_identifier_1`, `category_is_need_identifier_2`, `category_is_need_identifier_3`, `is_category_sort`, `is_category_multiple`, `is_category_multilevel`, `need_all_category_select`, `category_editable_role`, `max_multilevel`, `before_save_callback`, `after_save_callback`, `after_enable_callback`, `ad_find_order_callback`, `disable_position_order`, `disable_preview`, `list_style`, `root_dir_type`, `link_color`, `index_url`, `detail_url`) VALUES
(1, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 1, '新着情報', 'news', '', '', 'demo', 'demo', 'demo', '0', '0', 0, '', '', 0, 'N', 'カテゴリー', 'カテゴリー', 'カテゴリー', 0, 0, 0, 'N', '0', '0', 0, 'staff', 0, '', '', '', '', '0', '0', '1', '0', '#000000', '', '');

-- --------------------------------------------------------

--
-- テーブルの構造 `page_config_extensions`
--

CREATE TABLE `page_config_extensions` (
  `id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `page_config_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  `status` enum('publish','draft') NOT NULL DEFAULT 'publish',
  `type` decimal(2,0) NOT NULL DEFAULT '0',
  `option_value` varchar(100) NOT NULL DEFAULT '',
  `name` varchar(40) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `page_config_items`
--

CREATE TABLE `page_config_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `page_config_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `position` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `parts_type` enum('main','block','section') NOT NULL DEFAULT 'main',
  `item_key` varchar(40) NOT NULL DEFAULT '',
  `status` enum('Y','N') NOT NULL DEFAULT 'Y',
  `memo` varchar(40) NOT NULL DEFAULT '',
  `title` varchar(30) NOT NULL DEFAULT '',
  `sub_title` varchar(30) NOT NULL DEFAULT '',
  `editable_role` varchar(100) NOT NULL DEFAULT 'staff',
  `viewable_role` varchar(100) NOT NULL DEFAULT 'staff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `page_config_items`
--

INSERT INTO `page_config_items` (`id`, `created`, `modified`, `page_config_id`, `position`, `parts_type`, `item_key`, `status`, `memo`, `title`, `sub_title`, `editable_role`, `viewable_role`) VALUES
(1, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 1, 'main', 'date', 'Y', '', '', '', 'staff', 'staff'),
(2, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 2, 'main', 'slug', 'N', '', '', '', 'staff', 'staff'),
(3, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 3, 'main', 'category', 'N', '', '', '', 'staff', 'staff'),
(4, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 4, 'main', 'title', 'Y', '', '', '', 'staff', 'staff'),
(5, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 5, 'main', 'notes', 'N', '', '', '', 'staff', 'staff'),
(6, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 6, 'main', 'image', 'N', '', '', '', 'staff', 'staff'),
(7, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 7, 'main', 'image_title', 'N', '', '', '', 'staff', 'staff'),
(8, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 8, 'main', 'view_table_content', 'N', '', '', '', 'staff', 'staff'),
(9, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 9, 'main', 'top_info', 'N', '', '', '', 'staff', 'staff'),
(10, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 10, 'main', 'index_type', 'N', '', '', '', 'staff', 'staff'),
(11, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 11, 'main', 'hash_tag', 'N', '', '', '', 'staff', 'staff'),
(12, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 12, 'main', 'meta', 'N', '', '', '', 'staff', 'staff'),
(13, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 13, 'main', 'status', 'Y', '', '', '', 'staff', 'staff'),
(14, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 14, 'block', 'all', 'Y', '', '', '', 'staff', 'staff'),
(15, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 16, 'block', 'title_h2', 'Y', '', '', '', 'staff', 'staff'),
(16, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 15, 'block', 'title', 'Y', '', '', '', 'staff', 'staff'),
(17, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 17, 'block', 'title_h4', 'Y', '', '', '', 'staff', 'staff'),
(18, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 18, 'block', 'title_h5', 'Y', '', '', '', 'staff', 'staff'),
(19, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 19, 'block', 'content', 'Y', '', '', '', 'staff', 'staff'),
(20, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 20, 'block', 'wysiwyg_old', 'N', '', '', '', 'staff', 'staff'),
(21, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 21, 'block', 'image', 'N', '', '', '', 'staff', 'staff'),
(22, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 22, 'block', 'file', 'N', '', '', '', 'staff', 'staff'),
(23, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 23, 'block', 'button', 'Y', '', '', '', 'staff', 'staff'),
(24, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 24, 'block', 'button2', 'Y', '', '', '', 'staff', 'staff'),
(25, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 25, 'block', 'line', 'Y', '', '', '', 'staff', 'staff'),
(26, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 26, 'block', 'with_image', 'N', '', '', '', 'staff', 'staff'),
(27, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 27, 'block', 'dialogue', 'N', '', '', '', 'staff', 'staff'),
(28, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 28, 'block', 'information', 'N', '', '', '', 'staff', 'staff'),
(29, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 29, 'section', 'all', 'N', '', '', '', 'staff', 'staff'),
(30, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 30, 'section', 'section_relation', 'N', '', '', '', 'staff', 'staff'),
(31, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 31, 'section', 'section', 'N', '', '', '', 'staff', 'staff'),
(32, '2022-07-19 13:08:34', '2022-07-19 13:08:34', 1, 32, 'section', 'section_file', 'N', '', '', '', 'staff', 'staff'),
(33, '2022-07-19 13:13:11', '2022-07-19 13:13:11', 1, 33, 'main', 'date', 'Y', '', '', '', 'staff', 'staff'),
(34, '2022-07-19 13:14:01', '2022-07-19 13:14:01', 1, 34, 'main', 'date', 'Y', '', '', '', 'staff', 'staff');

-- --------------------------------------------------------

--
-- テーブルの構造 `page_templates`
--

CREATE TABLE `page_templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `status` enum('draft','publish') NOT NULL DEFAULT 'draft',
  `position` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(60) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `page_templates`
--

INSERT INTO `page_templates` (`id`, `created`, `modified`, `status`, `position`, `name`) VALUES
(1, '2022-01-06 16:02:41', '2022-01-06 16:02:41', 'publish', 1, '標準');

-- --------------------------------------------------------

--
-- テーブルの構造 `preview_infos`
--

CREATE TABLE `preview_infos` (
  `id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `page_config_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `position` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `status` enum('draft','publish') NOT NULL DEFAULT 'draft',
  `title` varchar(100) NOT NULL DEFAULT '',
  `notes` mediumtext NOT NULL,
  `start_date` date NOT NULL DEFAULT '0000-00-00',
  `start_time` decimal(4,0) NOT NULL DEFAULT '0',
  `end_date` date NOT NULL DEFAULT '0000-00-00',
  `end_time` decimal(4,0) NOT NULL DEFAULT '0',
  `image` varchar(100) NOT NULL DEFAULT '',
  `meta_description` varchar(200) NOT NULL DEFAULT '',
  `meta_keywords` varchar(200) NOT NULL DEFAULT '',
  `regist_user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `category_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `index_type` decimal(1,0) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `preview_info_contents`
--

CREATE TABLE `preview_info_contents` (
  `id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `info_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `block_type` decimal(2,0) NOT NULL DEFAULT '0',
  `position` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `content` mediumtext NOT NULL,
  `image` varchar(100) NOT NULL DEFAULT '',
  `image_pos` varchar(10) NOT NULL DEFAULT '',
  `file` varchar(100) NOT NULL DEFAULT '',
  `file_size` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `file_name` varchar(100) NOT NULL DEFAULT '',
  `file_extension` varchar(10) NOT NULL DEFAULT '',
  `section_sequence_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `option_value` varchar(255) NOT NULL DEFAULT '',
  `option_value2` varchar(40) NOT NULL DEFAULT '',
  `option_value3` varchar(40) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `preview_info_tags`
--

CREATE TABLE `preview_info_tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `info_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `tag_id` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `redactor_images`
--

CREATE TABLE `redactor_images` (
  `id` int(11) NOT NULL,
  `model` varchar(100) NOT NULL DEFAULT '',
  `image` varchar(500) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `info_id` int(11) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `section_sequences`
--

CREATE TABLE `section_sequences` (
  `id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `info_content_id` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `site_configs`
--

CREATE TABLE `site_configs` (
  `id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `position` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `status` enum('draft','publish') NOT NULL DEFAULT 'draft',
  `site_name` varchar(100) NOT NULL DEFAULT '',
  `slug` varchar(40) NOT NULL DEFAULT '',
  `is_root` decimal(1,0) NOT NULL DEFAULT '0',
  `page_editable_role` enum('develop','admin','staff','demo') NOT NULL DEFAULT 'develop'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `site_configs`
--

INSERT INTO `site_configs` (`id`, `created`, `modified`, `position`, `status`, `site_name`, `slug`, `is_root`, `page_editable_role`) VALUES
(1, '2022-01-06 16:09:24', '2022-01-06 16:09:24', 1, 'publish', 'BLOCKSMITH&Co.', '', '1', 'develop');

-- --------------------------------------------------------

--
-- テーブルの構造 `tags`
--

CREATE TABLE `tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `tag` varchar(40) NOT NULL DEFAULT '',
  `status` enum('publish','draft') NOT NULL DEFAULT 'publish',
  `position` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `page_config_id` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `useradmins`
--

CREATE TABLE `useradmins` (
  `id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `email` varchar(200) NOT NULL DEFAULT '',
  `username` varchar(30) NOT NULL DEFAULT '',
  `password` varchar(200) NOT NULL DEFAULT '',
  `temp_password` varchar(40) NOT NULL DEFAULT '',
  `temp_pass_expired` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `temp_key` varchar(200) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `status` enum('publish','draft') NOT NULL DEFAULT 'publish',
  `role` int(10) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `useradmins`
--

INSERT INTO `useradmins` (`id`, `created`, `modified`, `email`, `username`, `password`, `temp_password`, `temp_pass_expired`, `temp_key`, `name`, `status`, `role`) VALUES
(1, '2022-01-06 16:11:10', '2022-01-19 10:55:17', '', 'develop', '', 'caters040917', '0000-00-00 00:00:00', '', '開発者', 'publish', 0),
(2, '2022-01-06 16:11:10', '2022-01-19 10:55:17', '', 'caters_admin', 'caterscaters', 'caterscaters', '0000-00-00 00:00:00', '', '管理者', 'publish', 1),
(3, '2022-01-06 16:11:10', '2022-01-19 10:55:17', '', 'admin', 'admin1#', 'admin1#', '0000-00-00 00:00:00', '', 'ユーザー', 'publish', 11),
(4, '2022-01-06 16:11:10', '2022-01-19 10:55:17', '', 'admin', 'g05kHonV', 'g05kHonV', '0000-00-00 00:00:00', '', '管理者', 'publish', 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `useradmin_sites`
--

CREATE TABLE `useradmin_sites` (
  `id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `useradmin_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `site_config_id` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- テーブルのインデックス `append_items`
--
ALTER TABLE `append_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_config_id` (`page_config_id`);

--
-- テーブルのインデックス `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_config_id` (`page_config_id`),
  ADD KEY `parent_category_id` (`parent_category_id`);

--
-- テーブルのインデックス `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `infos`
--
ALTER TABLE `infos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_config_id` (`page_config_id`),
  ADD KEY `category_id` (`category_id`);

--
-- テーブルのインデックス `info_append_items`
--
ALTER TABLE `info_append_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `info_id` (`info_id`),
  ADD KEY `append_item_id` (`append_item_id`);

--
-- テーブルのインデックス `info_categories`
--
ALTER TABLE `info_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `info_id` (`info_id`),
  ADD KEY `category_id` (`category_id`);

--
-- テーブルのインデックス `info_contents`
--
ALTER TABLE `info_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `info_id` (`info_id`),
  ADD KEY `section_sequence_id` (`section_sequence_id`);

--
-- テーブルのインデックス `info_tags`
--
ALTER TABLE `info_tags`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `info_tops`
--
ALTER TABLE `info_tops`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `mst_lists`
--
ALTER TABLE `mst_lists`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `page_configs`
--
ALTER TABLE `page_configs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `site_config_id` (`site_config_id`);

--
-- テーブルのインデックス `page_config_extensions`
--
ALTER TABLE `page_config_extensions`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `page_config_items`
--
ALTER TABLE `page_config_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_config_id` (`page_config_id`);

--
-- テーブルのインデックス `page_templates`
--
ALTER TABLE `page_templates`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `preview_infos`
--
ALTER TABLE `preview_infos`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `preview_info_contents`
--
ALTER TABLE `preview_info_contents`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `preview_info_tags`
--
ALTER TABLE `preview_info_tags`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `redactor_images`
--
ALTER TABLE `redactor_images`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `section_sequences`
--
ALTER TABLE `section_sequences`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `site_configs`
--
ALTER TABLE `site_configs`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `useradmins`
--
ALTER TABLE `useradmins`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `useradmin_sites`
--
ALTER TABLE `useradmin_sites`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- テーブルの AUTO_INCREMENT `append_items`
--
ALTER TABLE `append_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `infos`
--
ALTER TABLE `infos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- テーブルの AUTO_INCREMENT `info_append_items`
--
ALTER TABLE `info_append_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `info_categories`
--
ALTER TABLE `info_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `info_contents`
--
ALTER TABLE `info_contents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- テーブルの AUTO_INCREMENT `info_tags`
--
ALTER TABLE `info_tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `info_tops`
--
ALTER TABLE `info_tops`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `mst_lists`
--
ALTER TABLE `mst_lists`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `page_configs`
--
ALTER TABLE `page_configs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- テーブルの AUTO_INCREMENT `page_config_extensions`
--
ALTER TABLE `page_config_extensions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `page_config_items`
--
ALTER TABLE `page_config_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- テーブルの AUTO_INCREMENT `page_templates`
--
ALTER TABLE `page_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- テーブルの AUTO_INCREMENT `preview_infos`
--
ALTER TABLE `preview_infos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `preview_info_contents`
--
ALTER TABLE `preview_info_contents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `preview_info_tags`
--
ALTER TABLE `preview_info_tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `redactor_images`
--
ALTER TABLE `redactor_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `section_sequences`
--
ALTER TABLE `section_sequences`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `site_configs`
--
ALTER TABLE `site_configs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- テーブルの AUTO_INCREMENT `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `useradmins`
--
ALTER TABLE `useradmins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- テーブルの AUTO_INCREMENT `useradmin_sites`
--
ALTER TABLE `useradmin_sites`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
