CREATE TABLE IF NOT EXISTS `ppt` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `kind` varchar(255) NOT NULL COMMENT 'ptt.doc.pdf...',
  `title` varchar(255) NOT NULL COMMENT '標題',
  `orin_filename` varchar(255) NOT NULL COMMENT '原始檔名',
  `filename` varchar(255) NOT NULL COMMENT '新檔名',
  `keyword` text NOT NULL COMMENT '關鍵字',
  `create_datetime` datetime NOT NULL COMMENT '原建立時間',
  `upload_datetime` datetime NOT NULL COMMENT '上傳時間',
  `author` varchar(255) NOT NULL COMMENT '作者',
  `pdf_status` int(11) NOT NULL COMMENT '0:未, 1:轉,2:好',
  `png_status` int(11) NOT NULL COMMENT '0:未, 1:轉,2:好',
  `text_status` int(11) NOT NULL COMMENT '0:未, 1:轉,2:好',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='ppt檔頭資料' AUTO_INCREMENT=1 ;