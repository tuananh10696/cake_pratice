--
--  公開中のinfos
--
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `public_infos`  AS 
SELECT 
Infos.*,
`PageConfigs`.`index_url` AS `index_url`,
`PageConfigs`.`detail_url` AS `detail_url`,
`PageConfigs`.`slug` AS `page_config_slug`,

`Category`.`id` AS `Category__id`,
`Category`.`name` AS `Category__name`
FROM 
(
  `infos` `Infos` 
  left join `page_configs` `PageConfigs` 
  on
  (
    `PageConfigs`.`id` = `Infos`.`page_config_id`
  )
  left join `categories` `Category` 
  on
  (
    `Category`.`id` = `Infos`.`category_id`
  )
  left join `categories` `ParentCategory` 
  on
  (
    `ParentCategory`.`id` = `Category`.`parent_category_id`
  )
) 
WHERE (
  `Infos`.`status` = 'publish'
  AND
  (
    (
      `PageConfigs`.`is_public_date` = 0
    )
    OR
    (
      (`PageConfigs`.`is_public_date` = 1)
      AND
      (
        (`Infos`.`start_datetime` is null) OR (`Infos`.`start_datetime` <= NOW())
      )
      AND
      (
        (`Infos`.`end_datetime` is null) OR (`Infos`.`end_datetime` >= NOW())
      )
    )
  )
  AND
  (
    (`Category`.`status` = 'publish')
    OR
    (`Category`.`status` is null)
  )
  AND
  (
    (`ParentCategory`.`status` = 'publish')
    OR
    (`ParentCategory`.`status` is null)
  )
);



--
-- 掲載中のinfoが選択しているカテゴリーをまとめる。
--
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `category_groupby_infos`  AS 
SELECT 
Categories.*,
`Categories`.`id` AS `category_id`,
count(`Infos`.`id`) AS `infos_cnt`,
`PageConfigs`.`slug` AS `page_config_slug`
FROM 
(
  `public_infos` `Infos` 
  left join `categories` `Categories` 
  on
  (
    `Categories`.`id` = `Infos`.`category_id`
  )
  left join `page_configs` `PageConfigs` 
  on
  (
    `PageConfigs`.`id` = `Infos`.`page_config_id`
  )
) 
WHERE (
  `Infos`.`category_id` != 0
) 
GROUP BY `Categories`.`id`;



