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
  
  if(!is_file("{$base_dir}/../ppt_config.php"))
  {
    /*
      $DB_HOST="localhost";
      $DB_LOGIN="ppt";
      $DB_PASSWORD="*******";
      $DB_NAME="myppt";
      $DB_KIND="mysql";
    */
    echo "{$base_dir}/../ppt_config.php not found...";
    exit();
  }
  include "{$base_dir}/../ppt_config.php";
  
  include 'conn.php';
  include 'include.php';
                           
  
  $p=9999999;  //每頁顯示５筆
  $px=5;   //每頁顯示跳頁用的５筆  
  if(isset($page))
  {
    $page=(int)$page;
  }
  else if(isset($_GET['page']))
  {
    $page=(int)$_GET['page'];
  }
  else
  {
    $page=0;
  }
