CREATE TABLE IF NOT EXISTS `ppt_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `ppt_id` int(11) NOT NULL COMMENT '對應ppt 流水號',
  `filename` varchar(255) NOT NULL COMMENT '檔名',
  `page` int(11) NOT NULL COMMENT '第幾頁，第一頁是 1',
  `contents` text NOT NULL COMMENT '文字內容',
  `keyword` text NOT NULL COMMENT '額外關鍵字',
  `kind` int(11) NOT NULL COMMENT '分類',
  `grades` int(11) NOT NULL COMMENT '評分',
  PRIMARY KEY (`id`),
  KEY `ppt_id` (`ppt_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='ppt內頁' AUTO_INCREMENT=1 ;
