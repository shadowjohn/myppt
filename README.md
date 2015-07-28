# myppt
評選簡報致勝方程式

<pre>

目前需要的環境如下：
1. Linux Server (Ubuntu or Fedora 都可以)
2. Requires:
      mariadb or mysql 任何版本
      libreoffice >= 4.2.8.2
      poppler-utils >= 0.24 (為了用 pdftotext 功能)
      xorg-x11-server-Xvfb >= 1.14.4
      php >= 5.3
      ImageMagick >= 6.8.6.3 (為了 PDF -> PNG)
      apache >= 2.4
3. 預設安裝路徑：/var/www/ppt
　　請詳見 inc/config.php、inc/conn.php
4. DB Schema：詳見 DB/*.sql
5. Crontab 排程：
　　詳見：crontab/readme.txt 
　　目前有二支排程：
　　　（一）1min_ppt_to_pdf.php (把上傳的 doc、docx、ppt、pptx轉成pdf)
　　　（二）1min_pdf_to_png_text.php (把pdf轉成png、把文字內容存入DB)
      
</pre>
