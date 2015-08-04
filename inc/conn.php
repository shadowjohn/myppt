<?php 
  @error_reporting(E_ALL & ~E_NOTICE);
  @ini_set("memory_limit","1024M");
  @ini_set('post_max_size', '800M');  
  @ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
  @ini_set('session.save_path', '/tmp');
  @session_start();    
  @header("Content-Type: text/html; charset=utf-8");          
  date_default_timezone_set("Asia/Taipei");
  
  if((strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'))
  {
    if(!is_dir("C:\\tmp"))
    {
      @mkdir("C:\\tmp");      
    }
    @ini_set('session.save_path', 'C:\\tmp');
  }
  else
  {
    @ini_set('session.save_path', '/tmp');
  }
  

  try{    
    $pdo = new PDO("{$DB_KIND}:dbname={$DB_NAME};host={$DB_HOST}",$DB_LOGIN,$DB_PASSWORD);
  }catch(PDOException $Exception){
    echo "資料庫未連線...";
    //print_r($Exception);
    exit();
  }
  $pdo->query("SET NAMES UTF8");
  $pdo->query("SET time_zone = '+8:00'");
  $pdo->query("SET CHARACTER_SET_CLIENT=utf8");
  $pdo->query("SET CHARACTER_SET_RESULTS=utf8");  
  
  
  mb_http_output("UTF-8");
  mb_internal_encoding('UTF-8'); 
  mb_regex_encoding("UTF-8");
  
  
  // 羽山流，強制默認 magic_quotes_gpc = on，未來咱的 Code 就會乾淨了
  function sanitizeVariables(&$item, $key)
  {
    if (!is_array($item))
    {
      if (get_magic_quotes_gpc())
          $item = stripcslashes($item);
      $item = addslashes($item);
    }
  }
  // escaping and slashing all POST and GET variables. you may add $_COOKIE and $_REQUEST if you want them sanitized.
  array_walk_recursive($_POST, 'sanitizeVariables');
  array_walk_recursive($_GET, 'sanitizeVariables');
  array_walk_recursive($_COOKIE, 'sanitizeVariables');
  array_walk_recursive($_REQUEST, 'sanitizeVariables');
  
  
