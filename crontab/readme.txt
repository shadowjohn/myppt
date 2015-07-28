# 每分鐘執行一次，將 ppt、doc 轉成 pdf
*/1 * * * * /usr/bin/php /var/www/ppt/crontab/1min_ppt_to_pdf.php

# 每分鐘執行一次，將 pdf 轉成 png、text
*/1 * * * * /usr/bin/php /var/www/ppt/crontab/1min_pdf_to_png_text.php