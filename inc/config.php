<?php  
  $base_dir="/var/www/ppt";
  $base_port=(":{$_SERVER['SERVER_PORT']}"==':80')?'':":{$_SERVER['SERVER_PORT']}";  
  $base_url="http://{$_SERVER['SERVER_NAME']}";
  $base_url.=$base_port;
  $base_tmp="{$base_dir}/tmp";
  
  $OUTPUT_PDF = "{$base_dir}/OUTPUT/pdf";
  @mkdir($OUTPUT_PDF,0777,true);
  
  $OUTPUT_PNG = "{$base_dir}/OUTPUT/png";
  @mkdir($OUTPUT_PNG,0777,true); 
  
  include 'conn.php';
  include 'include.php';
                           
