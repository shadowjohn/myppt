<?php
  function __($data)
  {
    return $data;
  }   
  function array_htmlspecialchars(&$input)
  {
      if (is_array($input))
      {
          foreach ($input as $key => $value)
          {
              if (is_array($value)) $input[$key] = array_htmlspecialchars($value);
              else $input[$key] = htmlspecialchars($value);
          }
          return $input;
      }
      return htmlspecialchars($input);
  }
  
  function array_htmlspecialchars_decode(&$input)
  {
      if (is_array($input))
      {
          foreach ($input as $key => $value)
          {
              if (is_array($value)) $input[$key] = array_htmlspecialchars_decode($value);
              else $input[$key] = htmlspecialchars_decode($value);
          }
          return $input;
      }
      return htmlspecialchars_decode($input);
  }
  function getGET_POST($inputs,$mode)
  {
    $mode=strtoupper(trim($mode));
    $data=$GLOBALS['_'.$mode];
        
    $data=array_htmlspecialchars($data);
    array_walk_recursive($data, "trim");
    
    $keys=array_keys($data);
    $filters = explode(',',$inputs);
    foreach($keys as $k)
    {
      if(!in_array($k,$filters))
      {
        unset($data[$k]);
      }
    }    
    return $data;
  } 
  function secondtodhis($time)
  {
    //秒數轉成　天時分秒
    //Create by 羽山 
    // 2010-02-07
    $days=sprintf("%02d",$time/(24*60*60));
    $days=($days>=1)?$days.'天':'';
    $hours=sprintf("%02d",($time % (60 * 60 * 24)) / (60 * 60));
    $hours=($days==''&&$hours=='0')?'':$hours."時";
    $mins=sprintf("%02d",($time % (60 * 60)) / (60));
    $mins=($days==''&&$hours==''&&$mins=='0')?'':$mins."分";
    $seconds=sprintf("%02d",($time%60))."秒";
    $output=sprintf("%s%s%s%s",$days,$hours,$mins,$seconds);
    return $output;
  }
  function updateSQL($table,$fields_data,$WHERE_SQL)
  {
    global $pdo;
    $datas=ARRAY();
    $question_marks=ARRAY();
    $m_mix_SQL=array();
    foreach($fields_data as $k=>$v)
    {
       array_push($datas,$v);
       array_push($question_marks,'?');
       array_push($m_mix_SQL,sprintf("`%s`=?",$k));
    }            
    $SQL=sprintf("
              UPDATE `{$table}` 
                  SET %s 
                WHERE 
                  %s",@implode(',',$m_mix_SQL),$WHERE_SQL); 
    $q = $pdo->prepare($SQL);
    for($i=0,$totals=count($question_marks);$i<$totals;$i++)
    {
         $q->bindParam(($i+1), $datas[$i]);
    }
    $q->execute();                      
  }
 
  function deleteSQL($table,$WHERE_SQL)
  {
    global $pdo;
    $SQL=sprintf("DELETE FROM `{$table}` WHERE %s",$WHERE_SQL);
    $pdo->query($SQL) or die("刪除 {$table} 失敗:{$SQL}");
  }
  function insertSQL($table,$fields_data)
  {
     global $pdo;
     $fields=ARRAY();
     $datas=ARRAY();
     $question_marks=ARRAY();
     foreach($fields_data as $k=>$v)
     {
        array_push($fields,$k);
        array_push($datas,$v);
        array_push($question_marks,'?');
     }
     $SQL = sprintf("
                INSERT INTO `{$table}`
                    (`%s`)
                    values
                    (%s)",
                    @implode("`,`",$fields),
                    @implode(",",$question_marks)
                  );
     $q = $pdo->prepare($SQL);
     for($i=0,$totals=count($question_marks);$i<$totals;$i++)
     {
          $q->bindParam(($i+1), $datas[$i]);
     }
     $q->execute(); 
     return $pdo->lastInsertId();      
  } 
  function insertSQLPDO($pdo,$table,$fields_data)
  {
     $fields=ARRAY();
     $datas=ARRAY();
     $question_marks=ARRAY();
     foreach($fields_data as $k=>$v)
     {
        array_push($fields,$k);
        array_push($datas,$v);
        array_push($question_marks,'?');
     }
     $SQL = sprintf("
                INSERT INTO `{$table}`
                    (`%s`)
                    values
                    (%s)",
                    @implode("`,`",$fields),
                    @implode(",",$question_marks)
                  );
     $q = $pdo->prepare($SQL);
     for($i=0,$totals=count($question_marks);$i<$totals;$i++)
     {
          $q->bindParam(($i+1), $datas[$i]);
     }
     $q->execute(); 
     return $pdo->lastInsertId();      
  }     
  function word_appear_times($find_word,$input)
  {
    //找一個字串在另一個字串出現的次數
    $found_times=0;
    $len = strlen($find_word);
    for($i=0,$max_i=strlen($input)-$len;$i<=$max_i;$i++)
    {
      if(substr($input,$i,$len)==$find_word)
      {
        $found_times++;
      }
    }
    return $found_times;
  }
  function selectSQL($SQL)
  {
    global $pdo;   
    $res=$pdo->query($SQL) or die("查詢失敗:{$SQL}");
    return pdo_resulttoassoc($res);
  }
  function selectSQL_SAFE($SQL,$data_arr)
  {
    global $pdo;   
    //找有幾個問號
    $questions = word_appear_times('?',$SQL);
    $max_i=count($data_arr);
    if($questions!=$max_i)
    {
      echo "查詢條件無法匹配...:{$SQL} 
      <br>Questions:{$questions}
      <br>Arrays   :{$max_i}";
      exit();
    }
    $q = $pdo->prepare($SQL);
    for($i=0;$i<$max_i;$i++)
    {
      $q->bindParam(($i+1), $data_arr[$i]);
    }
    $q->execute() or die("查詢失敗:{$SQL}");   
    
    return pdo_resulttoassoc($q);
  }    
  function selectSQL_SAFE_KEY($SQL,$data_arra,$field_name)
  {
    global $pdo;   
    //找有幾個問號
    $questions = word_appear_times('?',$SQL);
    $max_i=count($data_arr);
    if($questions!=$max_i)
    {
      echo "查詢條件無法匹配...:{$SQL} 
      <br>Questions:{$questions}
      <br>Arrays   :{$max_i}";
      exit();
    }
    $q = $pdo->prepare($SQL);
    for($i=0;$i<$max_i;$i++)
    {
      $q->bindParam(($i+1), $data_arr[$i]);
    }
    $q->execute() or die("查詢失敗:{$SQL}");       
    $ra = pdo_resulttoassoc($q);
    $output=ARRAY();
    for($i=0,$max_i=count($ra);$i<$max_i;$i++)
    {
      $output[$ra[$i][$field_name]] = $ra[$i];
    }
    return $output; 
  }       
  function selectSQL_KEY($SQL,$field_name)
  {
    global $pdo;   
    $res=$pdo->query($SQL) or die("查詢失敗:{$SQL}");
    $ra = pdo_resulttoassoc($res);
    $output=ARRAY();
    for($i=0,$max_i=count($ra);$i<$max_i;$i++)
    {
      $output[$ra[$i][$field_name]] = $ra[$i];
    }
    return $output; 
  }     
  function fb_date($datetime)
  {
    //類似 facebook的時間轉換方式
    //傳入日期　格式如 2011-01-19 04:12:12 
    //就會回傳 facebook 的幾秒、幾分鐘、幾小時的那種
    $week_array=array('星期一','星期二','星期三','星期四','星期五','星期六','星期日');
    $timestamp=strtotime($datetime);
    $distance=(time()-$timestamp);
    /*echo time();
    echo "<br>";
    echo $timestamp;
    echo "<br>";  
    echo $distance;
    echo "<br>";*/
    if($distance<=59)
    {
      return sprintf("%d %s",$distance,__("秒前")); 
    }
    else if($distance>=60 && $distance<59*60)
    {
      return sprintf("%d %s",floor($distance/60),__("分鐘前"));
    }
    else if($distance>=60*60 && $distance<60*60*24)
    {      
      return sprintf("%d %s",floor($distance/60/60),__("小時前"));
    }
    else if($distance>=60*60*24 && $distance<59*60*24*7)
    {      
      return sprintf("%s %s",__($week_array[date('N',$timestamp)]),date('H:i',$timestamp));
    }
    else
    {      
      return sprintf("%s",date("Y/m/d H:i",$timestamp));
    }
  }

  function jsAddSlashes($str) {
    $pattern = array(
        "/\\\\/"  , "/\n/"    , "/\r/"    , "/\"/"    ,
        "/\'/"    , "/&/"     , "/</"     , "/>/"
    );
    $replace = array(
        "\\\\\\\\", "\\n"     , "\\r"     , "\\\""    ,
        "\\'"     , "\\x26"   , "\\x3C"   , "\\x3E"
    );
    return preg_replace($pattern, $replace, $str);
  }

  function deslashes(&$s)
  {    
    if(is_array($s)){
        foreach($s as $k=>$v){
        deslashes($s[$k]);        
      }    
    }
    elseif(is_string($s)){
        $s=stripslashes($s);    
    }
  }
  function user_agent(){
    return trim($_SERVER['HTTP_USER_AGENT']);
  }
  function ip(){
      $a=array();    
    if(!empty($_SERVER['REMOTE_ADDR'])){
        $a[]=$_SERVER['REMOTE_ADDR'];    
    }
       if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){        
      $a[]=preg_replace('[^A-Z0-9.]','',$_SERVER['HTTP_X_FORWARDED_FOR']);    
    }
    return implode('-',$a);
  }
  function valid_email($s){
      return preg_match('/^[^@]+@([a-z0-9]+\\.)+[a-z0-9]+$/i',$s);
  }        
  
    //以後排序用這支
    function array_sort($array, $on, $order='SORT_DESC')
    {
      $new_array = array();
      $sortable_array = array();
 
      if (count($array) > 0) {
          foreach ($array as $k => $v) {
              if (is_array($v)) {
                  foreach ($v as $k2 => $v2) {
                      if ($k2 == $on) {
                          $sortable_array[$k] = $v2;
                      }
                  }
              } else {
                  $sortable_array[$k] = $v;
              }
          }
 
          switch($order)
          {
              case 'SORT_ASC':   
                  //echo "ASC";
                  asort($sortable_array);
              break;
              case 'SORT_DESC':
                  //echo "DESC";
                  arsort($sortable_array);
              break;
          }
 
          foreach($sortable_array as $k => $v) {
              $new_array[] = $array[$k];
          }
      }
      return $new_array;
    }   

  function delete_row_array($array,$rows)  //刪除一個陣列裡的列，  3,4,6  刪掉3、4、6列，回傳新的array
  {
    $del_field=explode(',',$rows); //先產生要刪的表
    for($i=0,$nums_del_field=count($del_field);$i<$nums_del_field;$i++)
    {
      unset($array[$del_field[$i]]);
    }
    $new_array=array_values($array);
    return $new_array;
  }

  ///////////////////////////////GD驗證////////////////////////////////
/**
 * 利用 GD 動態生成登入驗證的圖片
 *
 * 鑒於每個GD版本出來的效果有一定的差別，請使用附件中的GD.dll，或者選用GD 2.0以上的版本
 *
 * 目前該類庫主要用於登入時生成附帶驗證碼圖片的功能，存儲驗證碼有 Cookies 和 Session 兩種，
 * 生成的圖片支援 PNG / JPG 等，還有可以設定驗證碼的長度，英文字元和數字混合等。
 *
 * @作者         Hessian(solarischan@21cn.com)
 * @版本         1.0
 * @版權所有     Hessian / NETiS
 * @使用授權     GPL（請各位保留Comment）
 * @特別鳴謝     waff（提供了非常特別輸出方式）
 * @開始         2003-11-05
 * @瀏覽         公開
 *
 * 更新記錄
 *
 * ver 1.0 2003-11-05
 * 一個用於生成驗證碼圖片的類庫已經初步完成。
 *
 */


    /**
     * 判斷是否使用 Session。
     *
     * @變量類型  布爾值
     * @開始      1.0
     * @最後修改  1.0
     * @瀏覽      公開
     * @可選值    true / false
     */
     $UseSession = true;

    /**
     * 瀏覽 Session 的 Handle。
     *
     * @變量類型  字元串
     * @開始      1.0
     * @最後修改  1.0
     * @瀏覽      內部
     * @可選值    無
     */
     $_SessionNum = "";

    /**
     * 驗證碼的長度。
     *
     * @變量類型  數字
     * @開始      1.0
     * @最後修改  1.0
     * @瀏覽      公開
     * @可選值    10進制的純數字
     */
     $CodeLength = 0;

    /**
     * 生成的驗證碼是否帶有英文字元。
     *
     * @變量類型  布爾值
     * @開始      1.0
     * @最後修改  1.0
     * @瀏覽      公開
     * @可選值    true / false
     */
     $CodeWithChar = false;

    /**
     * 生成圖片的類型。
     *
     * @變量類型  字元串
     * @開始      1.0
     * @最後修改  1.0
     * @瀏覽      公開
     * @可選值    PNG / JPEG / WBMP / XBM
     */
     $ImageType = "JPEG";

    /**
     * 生成圖片的寬度。
     *
     * @變量類型  10進制數字
     * @開始      1.0
     * @最後修改  1.0
     * @瀏覽      公開
     * @可選值    10進制的純數字
     */
     $ImageWidth = 120;//50

    /**
     * 生成圖片的高度。
     *
     * @變量類型  10進制數字
     * @開始      1.0
     * @最後修改  1.0
     * @瀏覽      公開
     * @可選值    10進制的純數字
     */
     $ImageHeight = 30;//30

    /**
     * 生成後的驗證碼。
     *
     * @變量類型  字元串
     * @開始      1.0
     * @最後修改  1.0
     * @瀏覽      公開
     * @可選值    無
     */

     $AuthResult ="";

    /**
     * 圖片中驗證碼的顏色。
     *
     * @變量類型  數組
     * @開始      1.0
     * @最後修改  1.0
     * @瀏覽      公開
     * @可選值    順序為 R，G，B, 例如：HTML顏色為 '000033' / array(0,0,51)
     */
     $FontColor = array(0, 0, 0);

    /**
     * 圖片的背景色。
     *
     * @變量類型  數組
     * @開始      1.0
     * @最後修改  1.0
     * @瀏覽      公開
     * @可選值    順序為 R，G，B, 例如：HTML顏色為'000033' / array(0,0,51)
     */
     $BGColor = array(0, 0, 0);

    /**
     * 設定背景是否需要透明（注意：只有 PNG 格式支援，如果使用 JPG 格式的話，必須禁止該選項）。
     *
     * @變量類型  布爾值
     * @開始      1.0
     * @最後修改  1.0
     * @瀏覽      公開
     * @可選值    true / false
     */
     $Transparent = false;
    /**
     * 設定是否生成帶噪點的背景。
     *
     * @變量類型  布爾值
     * @開始      1.0
     * @最後修改  1.0
     * @瀏覽      公開
     * @可選值    true / false
     */
     $NoiseBG = false;

    /**
     * 設定生成噪點的字元。
     *
     * @變量類型  字元串
     * @開始      1.0
     * @最後修改  1.0
     * @瀏覽      公開
     * @可選值    任意
     */
     $NoiseChar = "*";

    /**
     * 設定生成多少個噪點字元。
     *
     * @變量類型  10進制的純數字
     * @開始      1.0
     * @最後修改  1.0
     * @瀏覽      公開
     * @可選值    0 - 無限
     */
     $TotalNoiseChar = 50;

    /**
     * 驗證碼在圖片中的左邊距。
     *
     * @變量類型  10進制數字
     * @開始      1.0
     * @最後修改  1.0
     * @瀏覽      公開
     * @可選值    10進制的純數字，範圍：0 - 100
     */
     $JpegQuality = 80;

    /**
     * GenAuth 的構造函數
     *
     * 詳細說明
     * @形參
     * @開始      1.0
     * @最後修改  1.0
     * @瀏覽      公開
     * @返回值    無
     * @throws
     */
    function GenAuth()
    {
    } // 結束 GenAuth 的構造函數


    /**
     * 直接顯示圖片
     *
     * 詳細說明
     * @形參      字元串      $ImageType   設定顯示圖片的格式
     *            10進制數字  $ImageWidth  設定顯示圖片的高度
     *            10進制數字  $ImageHeight 設定顯示圖片的寬度
     * @開始      1.0
     * @最後修改  1.0
     * @瀏覽      公開
     * @返回值    無
     * @throws
     */
    function Show( $ImageType = "", $ImageWidth = "", $ImageHeight = "" )
    {
        global $UseSession;
        global $_SessionNum;
        global $CodeLength;
        global $CodeWithChar;
        global $ImageType;
        global $ImageWidth;
        global $ImageHeight;
        global $AuthResult;
        global $FontColor;
        global $BGColor;
        global $Transparent;
        global $NoiseBG;
        global $NoiseChar;
        global $TotalNoiseChar;
        global $JpegQuality;
        // 生成驗證碼
        if( $CodeWithChar )
            for( $i = 0; $i < $CodeLength; $i++ )
                $AuthResult .= dechex( rand( 1, 15 ) );
        else
            for( $i = 0; $i < $CodeLength; $i++ )
                $AuthResult .= rand( 1, 9 );

        // 檢查有沒有設定圖片的輸出格式，如果沒有，則使用類庫的預設值作為最終結果。
        if ( $ImageType == "" )
            $ImageType = $ImageType;

        // 檢查有沒有設定圖片的輸出寬度，如果沒有，則使用類庫的預設值作為最終結果。
        if ( $ImageWidth == "" )
            $ImageWidth = $ImageWidth;

        // 檢查有沒有設定圖片的輸出高度，如果沒有，則使用類庫的預設值作為最終結果。
        if ( $ImageHeight == "" )
            $ImageHeight = $ImageHeight;

        // 建立圖片流
        $im = imagecreate( $ImageWidth, $ImageHeight );

        // 取得背景色
        list ($bgR, $bgG, $bgB) = $BGColor;

        // 設定背景色
        $background_color = imagecolorallocate( $im, $bgR, $bgG, $bgB );

        // 取得文字顏色
        list ($fgR, $fgG, $fgB) = $FontColor;

        // 設定字型顏色
        $font_color = imagecolorallocate( $im, $fgR, $fgG, $fgB );

        // 檢查是否需要將背景色透明
        if ( $Transparent ) {
            ImageColorTransparent( $im, $background_color );
        }

        if( $NoiseBG )
        {
//            ImageRectangle($im, 0, 0, $ImageHeight - 1, $ImageWidth - 1, $background_color);//先成一黑色的矩形把圖片包圍

            //下面該生成雪花背景了，其實就是在圖片上生成一些符號
            for ( $i = 1; $i <= $TotalNoiseChar; $i++ )
                imageString( $im, 1, mt_rand( 1, $ImageWidth ), mt_rand( 1, $ImageHeight ), $NoiseChar, imageColorAllocate( $im, mt_rand( 200, 255 ), mt_rand( 200,255 ), mt_rand( 200,255 ) ) );
        }

        // 為了區別於背景，這裡的顏色不超過200，上面的不小於200
        for ( $i = 0; $i < strlen( $AuthResult ); $i++ ){
          //mt_rand(3,5)
            //imageString( $im, mt_rand(3,5), $i*$ImageWidth/strlen( $AuthResult )+mt_rand(1,5), mt_rand(1, $ImageHeight/2), $AuthResult[$i], imageColorAllocate( $im, mt_rand(0, 100), mt_rand(0, 150), mt_rand(0, 200) ) );
            $tt=imageColorAllocate( $im, mt_rand(150, 255), mt_rand(150, 255), mt_rand(100, 255) ); //字型顏色設定
            ImageTTFText ($im, 18, mt_rand(-45,45), 12+$i*$ImageWidth/strlen( $AuthResult )+mt_rand(1,5),$ImageHeight*5/7, $tt, "photo/DFFN_Y7.TTC",$AuthResult[$i]);
        }

        // 檢查輸出格式
        if ( $ImageType == "PNG" ) {
            header( "Content-type: image/png" );
            imagepng( $im );
        }

        // 檢查輸出格式
        if ( $ImageType == "JPEG" ) {
            header( "Content-type: image/jpeg" );
            imagejpeg( $im, null, $JpegQuality );
        }

        // 釋放圖片流
        imagedestroy( $im );

    } // 結束 Show 函數
  //自動產生分頁排序說明
  //版本1.0
  //開發者:羽山秋人
  //時間:2007414
  //第二版修正於:2007416
  //使用方法
  /* array_page(
        $totals_rows  $資料庫算出的總筆數,
                $page         $目前的頁碼常用
                $p            $每頁顯示的筆數
                $px           $每頁要顯示多少個【第 xx 頁】
                $new_Link     $跳頁用的網頁帶入值  ---> ?以後原本傳的值

                P.S:需自行在 SQL 語法最後加上 limit ".($page*$p).",".$p;
                P.S:$p、$px、$page 請加注在 檔案開頭 以上
          #mysql      
            $SQL_ROWS="SELECT COUNT(*) AS `COUNTER` FROM ({$SQL}) a";
            $totals_rows=mysql_result(mysql_query($SQL_ROWS),0,0);
            $SQL.=sprintf(" LIMIT %d,%d",($page*$p),$p);  
          
          #pdo
            $SQL_ROWS="SELECT COUNT(*) AS `COUNTER` FROM ({$SQL}) a";
            $ra_counts=selectSQL($SQL_ROWS); 
            $totals_rows=$ra_counts[0]['COUNTER'];
            $SQL.=sprintf(" LIMIT %d,%d",($page*$p),$p);  

          $p=10;  //每頁顯示５筆
          $px=5;   //每頁顯示跳頁用的５筆
            if(isset($page))
             {
              $page=$page;
            }
            else if(isset($_GET['page']))
            {
              $page=$_GET['page'];
            }
            else
            {
              $page=0;
            }                
  */            
  function array_page($totals_rows,$page,$p,$px,$new_Link,$mode='normal',$spandiv='')
  {
        //傳說中的分頁
    //$p=5; // 每頁顯示5筆
    //$px=5; //每頁限制最多5頁，超過就用「下5頁」上5頁
      global $base_url;
       switch($mode)
       {
      case 'normal_ajax':
      case 'normal':
          ?>
<style>
          /*------------------------------
	分頁樣式 pagination
------------------------------*/

.pagination, 
.pagination span /*total*/ ,
.pagination .num ,
.pagination .num a , 
.pagination .num .current {
	display: inline-block;
	height: 28px;
	line-height: 28px;
	vertical-align: middle;
}
.pagination span ,
.num a {
	padding: 0 8px;
	border: 1px solid #333;
	font-size: 0.8125em;
}

.pagination .num {
	margin-top: -3px; /*fix*/
}

.pagination .num .first,
.pagination .num .last,
.pagination .num .prev,
.pagination .num .next {
	width: 26px;
	padding: 0;
	background: 50% 50% no-repeat;
}
.pagination .num .prev {
	background-image: url(<?=$base_url;?>/pic/page/w/arrow-left.png);	
}
.pagination .num .next {
	background-image: url(<?=$base_url;?>/pic/page/w/arrow-right.png);	
}
.pagination .num .first {
	background-image: url(<?=$base_url;?>/pic/page/w/arrow2-left.png);	
}
.pagination .num .last {
	background-image: url(<?=$base_url;?>/pic/page/w/arrow2-right.png);	
}

.pagination .num .current {
	background-color: #3890A5;
}

.pagination .num a:hover {
	background-color: #333;
}

.pagination .jump input ,
.pagination .jump button ,
.pagination .jump select {
	margin-top: -3px;
	padding: 0;
	height: 20px;
	line-height: 20px;
	border: 0;
	background: #222;
	color: #fff;
}

.pagination .jump input[type=text],
.pagination .jump button  {
	outline: none;
	width: 40px;
	text-align: center;
	background: #eee;
  color:#000;
}

.pagination .jump button  {
	display: none;
}
</style>
          <?php
          //自動算幾頁
          $p=(int)$p;
          $page=(int)$page;
          $pageurl='';
          $totals_rows=(int)$totals_rows;
          $totals_page=ceil($totals_rows/$p);
          $pre_page =$page-1;    //上一頁
          $next_page=$page+1;    //下一頁
          $show_prev = false;
          $show_next = false;
          $show_start = false;
          $show_end = false;
          //起始
          $spage = ($page-3<0)?0:$page-3;         
          
          //往後
          $epage = ($page+3>($totals_page-1))?$totals_page:$page+3;
          if($page-3<0)
          {
            $epage = (($epage+abs($page-3)) > ($totals_page-1))?$totals_page:($epage+abs($page-3));
          }
          
          
          
          
          if($page>=1)
          {
            $show_prev = true;
          }
          if($page<$totals_page-1)
          {
            $show_next = true;
          }
          if($spage>1){
            $show_start = true;
          }
          if($epage<$totals_page-1)
          {
            $show_end = true;
          }
          
?>
       <div class="pagination">
       <?php
        switch($_SESSION['LANG'])
        {
          case 'en':
            ?>
            <span class="total">Totals: <strong><?=$totals_rows;?></strong></span>
            <?php
            break;          
          case 'zh_TW':
          default:
            ?>
            <span class="total">共 <strong><?=$totals_rows;?></strong> 筆</span>
            <?php
            break;
        }
       ?>
           <span class="jump">
            <input type="text" name="myjumppage_text" id="myjumppage_text">
            <script language="javascript">
              $(document).ready(function(){
                $("#myjumppage_text").unbind("keyup");
                $("#myjumppage_text").keyup(function(event){
                  if(event.which==13)
                  {
                    $("#myjumppage_btn").trigger('click');
                  }
                });
                <?php
                switch($mode)
                {
                  case 'normal_ajax':
                    ?>
                    $(".pagination a").click(function(){
                      return false;
                    });
                    <?php
                    break;
                }
                ?>
              });
            </script>
            /
            <?=$totals_page;?>
            <button id="myjumppage_btn" onClick="myjumppage();return false;">Go</button>
            <script language="javascript">
            <?php
              if($page > $totals_page-1)
              {
                ?>
                <?php
                switch($mode)
                {
                  case 'normal':
                    ?>
                      location.href="?<?=$pageurl;?>&<?=$new_Link;?>&page="+<?=($totals_page-1);?>;
                    <?php
                    exit();
                    break;
                }                
              }
            ?>
            function myjumppage()
            {
              $("#myjumppage_text").val(trim($("#myjumppage_text").val()));
              if($("#myjumppage_text").val()=="")
              {
                alert("<?=__('請輸入頁碼');?>...");
                return;
              }
              var gopage = parseInt($("#myjumppage_text").val());
              if(gopage > <?=($totals_page);?>)
              {
                alert("<?=__('超過總頁數');?>...");
                return false;
              }
              else if(gopage<=0)
              {
                alert("<?=__('傳入值錯誤');?>...");
                return false;
              }
              else
              {
                <?php
                  switch($mode)
                  {
                    case 'normal_ajax':
                      ?>
                      normal_ajax_jump_page(gopage-1);
                      return false;
                      <?php
                      break;
                  }
                ?>
                location.href="?<?=$pageurl;?>&<?=$new_Link;?>&page="+(gopage-1);
              }
            }
            </script>
          </span>


        <div class="num">          
          <?php
          if($show_prev==true)
          {
          ?>
          <a href="?<?=$pageurl;?>&<?=$new_Link;?>&page=<?=$pre_page;?>" req="<?=$pre_page;?>" class="prev"></a>
          <?php
          }
          ?>
          <?php
          if($show_start==true)
          {
            ?>
            <a href="?<?=$pageurl;?>&<?=$new_Link;?>&page=0" req="0">1</a>
            ...
            <?php
          }
          ?>
          <?php
          //中間的
          //如果$epage - $spage 小於6筆，檢查 $spage 是否大於0，如果大於０，仍顯示6筆
          if($epage - $spage<6)
          {
            if($spage - (6-($epage - $spage))>=0)
            {
              $spage = $spage - (6-($epage - $spage));
            }
          }
          for($i=$spage;$i<$epage;$i++)
          {
            if($page==$i)
            {
              ?>
              <a href="?<?=$pageurl;?>&<?=$new_Link;?>&page=<?=$i;?>" class="current" req="<?=$i;?>"><?=($i+1);?></a>
              <?php
            }
            else
            {
              ?>
              <a href="?<?=$pageurl;?>&<?=$new_Link;?>&page=<?=$i;?>"  req="<?=$i;?>"><?=($i+1);?></a>
              <?php
            }
          }
          ?>
          <?php
          if($show_end==true)
          {
            ?>                    
            ...
            <a href="?<?=$pageurl;?>&<?=$new_Link;?>&page=<?=$totals_page-1;?>" req="<?=$totals_page-1;?>"><?=$totals_page;?></a>          
            <?php
          }
          ?>          
          <?php
          if($show_next==true)
          {
            ?>
            <a href="?<?=$pageurl;?>&<?=$new_Link;?>&page=<?=$next_page;?>" req="<?=$next_page;?>" class="next"></a>
            <?php
          }
          ?>                     
        </div>
      </div>
<?php
        break;  
 
      case 'ajax':
  
          $page_range_start=floor($page/$px)*$px;
          $page_range_end=$page_range_start+$px;
          //自動算幾頁
          $totals_page=ceil($totals_rows/$p);   
          if($page_range_end>$totals_page)
          {
            $page_range_end=$totals_page;
          }
          //echo $page_range_start;
          //echo "<br>";
          //echo $page_range_end;  
          //echo "<br>";   
                          
          if($page-($page%$px)>=$px)
          {
              echo "【<a href='javascript:;' onClick=\"makeRequest('?','".$new_Link."&page=".($page-$px)."','".$spandiv."');\">上".$px."頁</a>】　　　　　　　　　　　　　";
          }
          if(($page-$page%$px)<$totals_page-$px)
          {
            if(($page+$px)>=$totals_page) //修正加上page的頁碼超過最終頁碼 2007/4/16
            {
              $temp=$totals_page-1;
            }
            else
            {
              $temp=$page+$px;
            }
              echo "【<a href='javascript:;' onClick=\"makeRequest('?','".$new_Link."&page=".($temp)."','".$spandiv."');\">下".$px."頁</a>】";
          }
          echo "<br>";
          for($i=$page_range_start;$i<$page_range_end;$i++)        
          {
            if($page==$i)
              echo "【第 ".($i+1)." 頁】";
            else
              echo "【<a href='javascript:;' onClick=\"makeRequest('?','".$new_Link."&page=".$i."','".$spandiv."');\">第 ".($i+1)." 頁</a>】";
          }                
      echo "<br><div align=right>第【".($page+1)."】頁</div>";
          echo "合計共【".$totals_rows."】筆／共【".$totals_page."】頁";
          //分頁結束       
        break;
 
              
      } 
  }
  
  function getFields_From_Id($table,$id,$field)
  {
    $SQL="
          SELECT DISTINCT `{$field}` 
            FROM `{$table}` 
              WHERE 
                1=1
                AND `id`='{$id}' 
              LIMIT 0,1;";
    $ra=selectSQL($SQL);
    if(count($ra)!=0)
    {
      return $ra[0][$field];
    }
    else
    {
      return "";
    }
  }
 
  function fullToHalf($str, $encode='UTF-8') {
    //全型轉半型
      if ($encode != 'UTF8') {
          $str = mb_convert_encoding($str, 'UTF-8', $encode);
      }
      $ret='';
      for ($i=0; $i < strlen($str); $i++) {
          $s1 = $str[$i];
          // 判斷 $c 第八位是否為 1 (漢字）
          if( ($c=ord($s1)) & 0x80 ) { 
              $s2 = $str[++$i];
              $s3 = $str[++$i];
              $c = (($c & 0xF) << 12) | ((ord($s2) & 0x3F) << 6) | (ord($s3) & 0x3F);
              if ($c == 12288) {
                  $ret .= ' ';
              } elseif ($c > 65280 && $c < 65375) {
                  $c -= 65248;
                  $ret .= chr($c);
              } else {
                  $ret .= $s1 . $s2 . $s3;
              } 
          } else {
              $ret .= $str[$i];
          }
      }
      return ($encode== 'UTF-8' ? $ret : mb_convert_encoding($ret, $encode, 'UTF-8')); 
  }     
  function url_exists($url) {
    // Version 4.x supported
    $handle   = curl_init($url);
    if (false === $handle)
    {
        return false;
    }
    curl_setopt($handle, CURLOPT_HEADER, false);
    curl_setopt($handle, CURLOPT_FAILONERROR, true);  // this works
    curl_setopt($handle, CURLOPT_NOBODY, true);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, false);
    $connectable = curl_exec($handle);
    curl_close($handle);
    return $connectable;
  }
  function is_chinese($str){
    //檢查是否為中文
    //在gb2312编碼中,正規表為: '/['.chr(0xa1)."-".chr(0xff).']/'
    //在utf-8编碼中,正規表為: '/[\x{4e00}-\x{9fa5}]/u'
    //***********************************************
    //原创作者：易心 QQ 343931221
    //个人网站：www.ex123.net
    //作品由易心原创，转载请保留此版权信息。
    //http://exblog.ex123.net/html/blogview-81-4057_1.html
    //***********************************************    
    $pattern='/[\x{4e00}-\x{9fa5}]/u';
    return (preg_match($pattern,$str))? true:false;
  }
  /**
  * word-sensitive substring function with html tags awareness
  * @param text The text to cut
  * @param len The maximum length of the cut string
  * @returns string
  * 切字的，不知道好不好用  
  **/
  function mb_substrws( $text, $len=180 ) {  
      if( (mb_strlen($text) > $len) ) {  
          $whitespaceposition = mb_strpos($text," ",$len)-1;  
          if( $whitespaceposition > 0 ) {
              $chars = count_chars(mb_substr($text, 0, ($whitespaceposition+1)), 1);
              if ($chars[ord('<')] > $chars[ord('>')])
                  $whitespaceposition = mb_strpos($text,">",$whitespaceposition)-1;
              $text = mb_substr($text, 0, ($whitespaceposition+1));
          }
          // close unclosed html tags
          if( preg_match_all("|<([a-zA-Z]+)|",$text,$aBuffer) ) {
  
              if( !empty($aBuffer[1]) ) {
  
                  preg_match_all("|</([a-zA-Z]+)>|",$text,$aBuffer2);
  
                  if( count($aBuffer[1]) != count($aBuffer2[1]) ) {
  
                      foreach( $aBuffer[1] as $index => $tag ) {
  
                          if( empty($aBuffer2[1][$index]) || $aBuffer2[1][$index] != $tag)
                              $text .= '</'.$tag.'>';
                      }
                  }
              }
          }
      }
      return $text;
  }
 
  function get_field_from_id($table,$id,$fieldname)
  {
      $SQL="
          SELECT DISTINCT `{$fieldname}` 
            FROM `{$table}` 
              WHERE 
                1=1
                AND `id`='{$id}' 
              LIMIT 0,1;";
      $ra=selectSQL($SQL);
      if(count($ra)!=0)
      {
        return $ra[0][$fieldname];
      }
      else
      {
        return "";
      }              
  }
  function pre_print_r($values)
  {    
    echo "<pre>";
    print_r($values);
    echo "</pre>";
  }       
  function alert($values)
  {
    ?>
    <script language="javascript">
      alert("<?=$values;?>");
    </script>
    <?
  }
  function location_reload()
  {
    ?>
    <script language="javascript">
      location.reload();
    </script>
    <?  
  }
  function location_href($input)
  {
    ?>
    <script language="javascript">
      location.href="<?=$input;?>";
    </script>
    <?
  }
  function location_replace($input)
  {
    ?>
    <script language="javascript">
      location.replace("<?=$input;?>");
    </script>
    <?
  }  
  function history_go()
  {
    ?>
    <script language="javascript">
      history.go(-1);
    </script>
    <?
  }
  function history_back(){
    ?>
    <script language="javascript">
      history.back();
    </script>
    <?php
  }
 
  function notempty($inputs)
  {
    return (trim($inputs)=='')?false:true;
  } 
  function return_not_empty_array($array)
  {  
    return array_values(array_filter($array,"notempty"));
  }
  function str_replace_once($search, $replace, $subject) {
    $firstChar = strpos($subject, $search);
    if($firstChar !== false) {
        $beforeStr = substr($subject,0,$firstChar);
        $afterStr = substr($subject, $firstChar + strlen($search));
        return $beforeStr.$replace.$afterStr;
    } else {
        return $subject;
    }
  }
  function file_get_contents_post($url,$posts)
  {
    $opts = stream_context_create(array (
      'http'=>array(
         'method'=>"POST",
         'header'=>"Content-type: application/x-www-form-urlencoded\r\nReferer:{$url}",
         'content'=>(is_array($posts))?http_build_query($posts):$posts
      )
    ));
    return file_get_contents("{$url}",false,$opts);
  }


  function is_string_like($data,$find_string){
/*
  is_string_like($data,$fine_string)

  $mystring = "Hi, this is good!";
  $searchthis = "%thi% goo%";

  $resp = string_like($mystring,$searchthis);


  if ($resp){
     echo "milike = VERDADERO";
  } else{
     echo "milike = FALSO";
  }

  Will print:
  milike = VERDADERO

  and so on...

  this is the function:
*/
    $tieneini=0;
    if($find_string=="") return 1;
    $vi = explode("%",$find_string);
    $offset=0;
    for($n=0,$max_n=count($vi);$n<$max_n;$n++){
        if($vi[$n]== ""){
            if($vi[0]== ""){
                   $tieneini = 1;
            }
        } else {
            $newoff=strpos($data,$vi[$n],$offset);
            if($newoff!==false){
                if(!$tieneini){
                    if($offset!=$newoff){
                        return false;
                    }
                }
                if($n==$max_n-1){
                    if($vi[$n] != substr($data,strlen($data)-strlen($vi[$n]), strlen($vi[$n]))){
                        return false;
                    }

                } else {
                    $offset = $newoff + strlen($vi[$n]);
                 }
            } else {
                return false;
            }
        }
    }
    return true;
  }
  function mfound_words($data,$needles){
/*
  $data="你123\n是我個好人，我的名字，我是約我是翰ABC";
  echo "<pre>";
  echo $data."\n";
  print_r(found_words($data,"\n"));
  
  
  Array
  (
      [0] => Array
          (
              [start] => 4
              [end] => 5
          )

  )
*/
    if($needles=="")
    {
      return "";
    }
    $step=0;
    $output=array();
    for($i=0,$max=mb_strlen($data);$i<$max;$i++)
    {
      if(mb_substr($data,$i,mb_strlen($needles))==$needles)
      {
        $output[$step]['start']=$i;
        $output[$step]['end']=$i+mb_strlen($needles);
        $step++;
      }
    }
    return $output;
  }
/**
 * Recursive version of glob
 *
 * @return array containing all pattern-matched files.
 *
 * @param string $sDir      Directory to start with.
 * @param string $sPattern  Pattern to glob for.
 * @param int $nFlags       Flags sent to glob.
 */
  function globr($sDir, $sPattern, $nFlags = NULL)
  {
    $sp = DIRECTORY_SEPARATOR;
    if((strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN'))    
    {
      $sDir = escapeshellcmd($sDir);
    }
    // Get the list of all matching files currently in the
    // directory.
    $aFiles = glob("{$sDir}{$sp}{$sPattern}", $nFlags);
    // Then get a list of all directories in this directory, and
    // run ourselves on the resulting array.  This is the
    // recursion step, which will not execute if there are no
    // directories.
    foreach (glob("{$sDir}{$sp}*", GLOB_ONLYDIR) as $sSubDir)
    {
      $aSubFiles = globr($sSubDir, $sPattern, $nFlags);
      $aFiles = array_merge($aFiles, $aSubFiles);
    }
    // The array we return contains the files we found, and the
    // files all of our children found.
    return $aFiles;
  }
  function stripallslashes($string) {
    while(strchr($string,'\\')) {
        $string = stripslashes($string);
    }
    return $string;
  }
 
  function get_between_new($source, $beginning, $ending, $init_pos=0) {
      $beginning_pos = strpos($source, $beginning, $init_pos);
      $middle_pos = $beginning_pos + strlen($beginning);
      $ending_pos = strpos($source, $ending, $beginning_pos + 1);
      $middle = substr($source, $middle_pos, $ending_pos - $middle_pos);
      return $middle;
  }  
  //判斷字串是否為utf8
  function  is_utf8($str)  {
    $i=0;
    $len  =  strlen($str);

    for($i=0;$i<$len;$i++)  {
        $sbit  =  ord(substr($str,$i,1));
        if($sbit  <  128)  {
            //本字節為英文字符，不與理會
        }elseif($sbit  >  191  &&  $sbit  <  224)  {
            //第一字節為落於192~223的utf8的中文字(表示該中文為由2個字節所組成utf8中文字)，找下一個中文字
            $i++;
        }elseif($sbit  >  223  &&  $sbit  <  240)  {
            //第一字節為落於223~239的utf8的中文字(表示該中文為由3個字節所組成的utf8中文字)，找下一個中文字
            $i+=2;
        }elseif($sbit  >  239  &&  $sbit  <  248)  {
            //第一字節為落於240~247的utf8的中文字(表示該中文為由4個字節所組成的utf8中文字)，找下一個中文字
            $i+=3;
        }else{
            //第一字節為非的utf8的中文字
            return  0;
        }
    }
    //檢查完整個字串都沒問體，代表這個字串是utf8中文字
    return  1;
  }  
  function my_money_format($data,$n=0) {
    /*
    from : http://herolin.twbbs.org/entry/better-than-number-format-for-php
    傳入值為$data 就是你要轉換的數值，$n就是小數點後面的位數
    除了排除這個問題，在使用number_format時發現如果設定小數位數四位，
    如不足四數就會補零 。例如: 100000.12 會顯示  100,000.1200 ，
    所以小弟也順便調整，可以把後面的零給取消掉。
    在此提供給一樣遇到這問題的人一個方法(不一定是好方法，但一定是可行的方法)
    */
    $data1=number_format(substr($data,0,strrpos($data,".")==0?strlen($data):strrpos($data,".")));
    $data2=substr( strrchr( $data, "." ), 1 );
    if($data2==0) $data3="";
      else {
       if(strlen($data2)>$n) $data3=substr($data2,0,$n);
         else $data3=$data2;
      $data3=".".$data3;
      }
    return $data1;
  }
  function str_replace_deep($search, $replace, $subject)
  {
      if (is_array($subject))
      {
          foreach($subject as &$oneSubject)
              $oneSubject = str_replace_deep($search, $replace, $oneSubject);
          unset($oneSubject);
          return $subject;
      } else {
          return str_replace($search, $replace, $subject);
      }
  }
  
  
  
  function pdo_field_array($query_sql){
    global $pdo;
    $buff=array();
    $res=$pdo->query($query_sql);
    $cs=$res->columnCount();
    for($i=0;$i<$cs;$i++)
    {
      $meta=$res->getColumnMeta($i);
      array_push($buff,$meta['name']);
    }
    //print_r($buff);
    return $buff;
  }  
  function pdo_resulttoassoc($res){    
      
    return $res->fetchAll(PDO::FETCH_ASSOC);    
  }  
  function pdo_get_field_from_id($table,$id,$fieldname)
  {
    $SQL="
            SELECT DISTINCT `{$fieldname}` 
                FROM `{$table}` WHERE 
                    `id`='{$id}'
                LIMIT 0,1;";
    $ra=selectSQL($SQL);
    if(count($ra)!=0)
    {
      return $ra[0][$fieldname];
    }
    else
    {
      return "";
    }              
  } 
  function pdo_get_assoc_from_id($table,$id)
  {
    $SQL="SELECT 
              DISTINCT * 
            FROM `{$table}` 
            WHERE `id`='{$id}' 
            LIMIT 0,1;";    
    $ra=selectSQL($SQL);
    if(count($ra)!=0)
    {
      return $ra[0];
    }
    else
    {
      return "";
    }     
  }
 
  function replace_special_chars($s) {
    /*
      By Tsung
      抓回來的東西有太多的特殊符號(特別是拍賣. 購物等等的站)..
      將那些符號濾掉比較好瀏覽. 寫個小 function 來小濾一下~ :)
      //filter out symbols 濾掉一些星星等等的碼
    */
    $s = preg_replace("/([\x80-\xFF].|[\x02-\x7F])/", "\x01\$1", $s);
    $pattern = "/(\x01\xa1[\xb3-\xbf]|\x01\xa2[\xa1-\xae])/";
    $s = preg_replace($pattern, " ", $s);
    $s = preg_replace("/[\x01]/", "", $s);
    // 內碼表除了全形 0~9 和 ㄅㄆㄇ外全濾掉, 不過可能會造成有些中文字異常.
    // 下述的不建議使用.
    //$s = preg_replace("/(\xa1[\x4a-\xfe])|(\xa2[\x40-\xae])/", "", $s);
    return $s;
  }
  function is_url($url){
    if(filter_var($url, FILTER_VALIDATE_URL) === FALSE)
    {
      return false;
    }else{
      return true;
    }                       
  }
  /*function is_url($url){
    $url = substr($url,-1) == "/" ? substr($url,0,-1) : $url;
    if ( !$url || $url=="" ) return false;
    if ( !( $parts = @parse_url( $url ) ) ) return false;
    else {
        if ( $parts[scheme] != "http" && $parts['scheme'] != "https" && $parts['scheme'] != "ftp" && $parts['scheme'] != "gopher" ) return false;
        else if ( !eregi( "^[0-9a-z]([-.]?[0-9a-z])*.[a-z]{2,4}$", $parts['host'], $regs ) ) return false;
        else if ( !eregi( "^([0-9a-z-]|[_])*$", $parts['user'], $regs ) ) return false;
        else if ( !eregi( "^([0-9a-z-]|[_])*$", $parts['pass'], $regs ) ) return false;
        else if ( !eregi( "^[0-9a-z/_.@~-]*$", $parts['path'], $regs ) ) return false;
        else if ( !eregi( "^[0-9a-z?&=#,]*$", $parts['query'], $regs ) ) return false;
    }
    return true;
  }*/  
  function subname($fname){
    //$pathinfo=pathinfo($fname);
    //$pathinfo['extension'];
    
    $m=explode(".",$fname);
    return end($m);
  } 
  function mainname($fname){
    $pathinfo=pathinfo($fname);
    return $pathinfo['filename'];           
  }
  
  function size_hum_read_v2($size)
  {
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
  }  
   
  function removeBOM($str=""){
    if(substr($str, 0,3) == pack("CCC",0xef,0xbb,0xbf)) {
            $str=substr($str, 3);
    }
    return $str;
  } 
  function writeUTF8File($filename,$content) {
    $dhandle=fopen($filename,"w");
    # Now UTF-8 - Add byte order mark
    fwrite($dhandle, pack("CCC",0xef,0xbb,0xbf));
    fwrite($dhandle,$content);
    fclose($dhandle);
  } 
  /***************************************** 
  * 程式碼作者：umbrae 
  * 程式碼來源：http://tw.php.net/json_encode 
  * 程式碼說明：將JSON資料轉為可閱讀排版 
  ******************************************/  
  function json_format($json) {  
    $tab = "  ";  
    $new_json = "";  
    $indent_level = 0;  
    $in_string = false;  
    $json_obj = json_decode($json);  
    if(!$json_obj){  
        return false;  
    }  
    $json = json_encode($json_obj);  
    $len = strlen($json);  
    for($c = 0; $c < $len; $c++) {  
        $char = $json[$c];  
        switch($char) {  
            case '{':  
            case '[':  
                if(!$in_string) {  
                    $new_json .= $char . "\n" . str_repeat($tab, $indent_level+1);  
                    $indent_level++;  
                } else {  
                    $new_json .= $char;  
                }  
            break;  
            case '}':  
            case ']':  
                if(!$in_string){  
                    $indent_level--;  
                    $new_json .= "\n" . str_repeat($tab, $indent_level) . $char;  
                } else {  
                    $new_json .= $char;  
                }  
            break;  
            case ',':  
                if(!$in_string){  
                    $new_json .= ",\n" . str_repeat($tab, $indent_level);  
                } else {  
                    $new_json .= $char;  
                }  
            break;  
            case ':':  
                if(!$in_string) {  
                    $new_json .= ": ";  
                } else {  
                    $new_json .= $char;  
                }  
            break;  
            case '"':  
                $in_string = !$in_string;  
            default:  
                $new_json .= $char;  
            break;  
        }  
    }  
    return $new_json;  
  }

  // Originally written by xellisx
  function parse_query($var)
  {
    /**
     *  Use this function to parse out the query array element from
     *  the output of parse_url().
     */
    $var  = parse_url($var, PHP_URL_QUERY);
    $var  = html_entity_decode($var);
    $var  = explode('&', $var);
    $arr  = array();
  
    foreach($var as $val)
    {
      $x = explode('=', $val);
      $arr[$x[0]] = $x[1];
    }
    unset($val, $x, $var);
    return $arr;
  }
  function big5toutf8($str)
  {
    return mb_convert_encoding($str, 'UTF-8','BIG5');
  }
  function utf8tobig5($str)
  {
    return mb_convert_encoding($str, 'BIG5', 'UTF-8');
  }
  function send_mail($mTO,$mCCTO,$mBCCTO,$mATTACHFILE,$SUBJECT,$BODY)
  {
    global $base_dir;
    include_once "{$base_dir}/send_mail/send_mail.php";
  }
  # recursively remove a directory
  function deltree($dir) {    
    foreach(glob("{$dir}/*",GLOB_BRACE) as $file) {
      if(is_dir($file))
      {
        deltree($file);
      }
      else
      {
        unlink($file);        
      }
    }
    rmdir($dir);
  }    
  
  /**
   * 判斷檔案是不是 utf8
     return false=不是 utf8,true=是utf8
  */
  function checkIsUTF8File($suc_file) {       
    $str=file_get_contents($suc_file);
    for($i = 0; $i < strlen($str); $i++){       
      $value = ord($str[$i]);       
      if($value > 127) {       
        if($value >= 192 && $value <= 247) 
          return true;       
        else 
          return false;       
      }       
    }  
    return false;    
  } 
  function lazyimg($src,$j_data)
  {
    global $base_url;
    $tmp=sprintf("<img src='%s/inc/javascript/jquery/jquery-image-lazy-loading/images/grey.gif' data-original='%s'",$base_url,$src);
    $m=json_decode($j_data);
    if(isset($m['id']))
    {
      $tmp.=" id='{$m['id']}'";
    }
    if(isset($m['name']))
    {
      $tmp.=" name='{$m['name']}'";
    }
    if(isset($m['style']))
    {
      $tmp.=" style='{$m['style']}'";
    }
    return $tmp.">";
  }
  function returnMIMEType($filename)
  {
      preg_match("|\.([a-z0-9]{2,4})$|i", $filename, $fileSuffix);

      switch(strtolower($fileSuffix[1]))
      {
          case "js" :
              return "application/x-javascript";

          case "json" :
              return "application/json";

          case "jpg" :
          case "jpeg" :
          case "jpe" :
              return "image/jpg";

          case "png" :
          case "gif" :
          case "bmp" :
          case "tiff" :
              return "image/".strtolower($fileSuffix[1]);

          case "css" :
              return "text/css";

          case "xml" :
              return "application/xml";

          case "doc" :
          case "docx" :
              return "application/msword";

          case "xls" :
          case "xlt" :
          case "xlm" :
          case "xld" :
          case "xla" :
          case "xlc" :
          case "xlw" :
          case "xll" :
              return "application/vnd.ms-excel";

          case "ppt" :
          case "pps" :
              return "application/vnd.ms-powerpoint";

          case "rtf" :
              return "application/rtf";

          case "pdf" :
              return "application/pdf";

          case "html" :
          case "htm" :
          case "php" :
              return "text/html";

          case "txt" :
              return "text/plain";

          case "mpeg" :
          case "mpg" :
          case "mpe" :
              return "video/mpeg";

          case "mp3" :
              return "audio/mpeg3";

          case "wav" :
              return "audio/wav";

          case "aiff" :
          case "aif" :
              return "audio/aiff";

          case "avi" :
              return "video/msvideo";

          case "wmv" :
              return "video/x-ms-wmv";

          case "mov" :
              return "video/quicktime";

          case "zip" :
              return "application/zip";

          case "tar" :
              return "application/x-tar";

          case "swf" :
              return "application/x-shockwave-flash";

          default :
          if(function_exists("mime_content_type"))
          {
              $fileSuffix = mime_content_type($filename);
          }

          return "unknown/" . trim($fileSuffix[0], ".");
      }
  }  
  function csv_to_array($input, $delimiter=',')
  {
      $header = null;
      $data = array();
      $csvData = str_getcsv($input, "\n");
     
      foreach($csvData as $csvLine){
          if(is_null($header)) $header = explode($delimiter, $csvLine);
          else{
             
              $items = explode($delimiter, $csvLine);
             
              for($n = 0, $m = count($header); $n < $m; $n++){
                  $prepareData[$header[$n]] = $items[$n];
              }
             
              $data[] = $prepareData;
          }
      } 
      return $data;
  }
  function print_csv($ra,$fields='',$headers='',$is_need_header=true)
  {   
    if($fields==''||$fields=='*')
    { 
      $tmp="";
      $keys = array_keys($ra[0]);
      if($is_need_header)
      {
        $tmp.='"'.implode('","',$keys).'"'."\n";
      }
      for($i=0,$max_i=count($ra);$i<$max_i;$i++)
      {
        $d = ARRAY();
        foreach($ra[$i] as $k=>$v)
        {
          array_push($d,$v);
        }
        $tmp.='"'.implode('","',$d).'"';
        if($i!=$max_i-1)
        {
          $tmp.="\n";
        }
      }
      return $tmp;
    }
    else
    {
      $tmp="";
      $mheaders = explode(",",$headers);
      if($is_need_header)
      {
        $tmp.='"'.implode('","',$mheaders).'"'."\n";
      }
      $m_fields=explode(',',$fields);
      for($i=0,$max_i=count($ra);$i<$max_i;$i++)
      {
        $d = ARRAY();
        foreach($m_fields as $k)
        {
          array_push($d,$ra[$i][$k]);
        }
        $tmp.='"'.implode('","',$d).'"';
        if($i!=$max_i-1)
        {
          $tmp.="\n";
        }
      }
      return $tmp;      
    }
  }  
  function print_table($ra,$fields='',$headers='',$classname='')
  {    
    $classname=($classname=='')?'':" class='{$classname}' ";
    if($fields==''||$fields=='*')
    {      

        $tmp="<table {$classname} border='1' cellspacing='0' cellpadding='0'>";
        $tmp.="<thead><tr>";
        foreach($ra[0] as $k=>$v)
        {
          $tmp.="<th field=\"{$k}\">{$k}</th>";
        }
        $tmp.="</tr></thead>";
        $tmp.="<tbody>";
        for($i=0,$max_i=count($ra);$i<$max_i;$i++)
        {
          $tmp.="<tr>";
          foreach($ra[$i] as $k=>$v)
          {
            $tmp.="<td field=\"{$k}\">{$v}</td>";
          }
          $tmp.="</tr>";
        }
        $tmp.="</tbody>";
        $tmp.="</table>";
        return $tmp;
    }
    else
    {
      $tmp="<table {$classname} border='1' cellspacing='0' cellpadding='0'>";
      $tmp.="<thead><tr>";
      foreach(explode(',',$headers) as $k=>$v)
      {
        $tmp.="<th field=\"{$v}\">{$v}</th>";
      }
      $tmp.="</tr></thead>";
      $tmp.="<tbody>";
      $m_fields=explode(',',$fields);
      for($i=0,$max_i=count($ra);$i<$max_i;$i++)
      {
        $tmp.="<tr>";
        foreach($m_fields as $k)
        {          
          $tmp.="<td field=\"{$k}\">{$ra[$i][$k]}</td>";
        }
        $tmp.="</tr>";
      }
      $tmp.="</tbody>";
      $tmp.="</table>";
      return $tmp;
    }
  }
  function gd_show($key,$id='')
  {
    global $base_dir;
    @session_start();
    $font_size_and_spacing=24;
    @header('Content-type: image/png');

    $_SESSION["GD_CODE{$id}"]=$key;
    
    $new_image=imagecreatetruecolor($font_size_and_spacing*strlen($key)+10  , $font_size_and_spacing+12);
    imagesavealpha($new_image, true);
    //顏色宣告
    $font_color=ARRAY(
              imagecolorallocate($new_image,rand(234,255),rand(234,255),rand(175,255)), //顏色 白
              imagecolorallocate($new_image,233,255,179), //顏色 白
              imagecolorallocate($new_image,243,234,255), //顏色 白
              imagecolorallocate($new_image,169,223,179), //顏色 白
              imagecolorallocate($new_image,231,222,153), //顏色 白
              imagecolorallocate($new_image,153,255,242) //顏色 白
    );
    $color_black=imagecolorallocatealpha($new_image,0,0,0,255); //顏色 黑
    
    //整張弄成黑的
    imagefill($new_image,0,0,$color_black);
    
    //放入數值
    
    for($i=0,$max_i=strlen($key);$i<$max_i;$i++)
    {
      ImageTTFText ($new_image,$font_size_and_spacing, rand(-25,25), 8+$i*$font_size_and_spacing, 25, $font_color[rand(0,count($font_color)-1)], "{$base_dir}/photo/uming.ttf",$key[$i]);
    }
    
    //輸出成png
    imagepng($new_image);
    // release memory
    imagedestroy($new_image);
  }
  function myRealPath($path) {
    // check if path begins with "/" ie. is absolute
    // if it isnt concat with script path
    if (strpos($path,"/") !== 0) {
        $base=dirname($_SERVER['SCRIPT_FILENAME']);
        $path=$base."/".$path;
    }
 
    // canonicalize
    $path=explode('/', $path);
    $newpath=array();
    for ($i=0; $i<sizeof($path); $i++) {
        if ($path[$i]==='' || $path[$i]==='.') continue;
           if ($path[$i]==='..') {
              array_pop($newpath);
              continue;
        }
        array_push($newpath, $path[$i]);
    }
    $finalpath="/".implode('/', $newpath);

    // check then return valid path or filename
    if (file_exists($finalpath)) {
        return ($finalpath);
    }
    else return FALSE;
  }  
  function download_Header($filename,$filesize)
  {
    header('Content-Type: application/octet-stream');
    //如果是IE，要轉成 BIG5
    if(is_string_like(user_agent(),'%MSIE%'))
    {
      $filename=utf8tobig5($filename);
    }
    header("Content-Disposition: attachment; filename=\"{$filename}\"");
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header("Content-Length: {$filesize}");
  }
  function download_file($filename,$download_display=null)
  {
    apache_setenv('no-gzip', 1);
    header('Content-Type: application/octet-stream');
    //如果是IE，要轉成 BIG5
    $bigf = $filename;        
    if(is_string_like(user_agent(),'%MSIE%') || is_string_like(user_agent(),'%rv_11%') )
    {
      $bigf=utf8tobig5($bigf);
    }
    if($download_display==null)
    {
      $download_display=basename($bigf); 
    }
    else
    {
      if(is_string_like(user_agent(),'%MSIE%') || is_string_like(user_agent(),'%11%') )
      {
        $download_display=utf8tobig5($download_display);
      }    
    }
    header("Content-Disposition: attachment; filename=\"{$download_display}\"");
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: '.filesize($filename));

    if (in_array('mod_xsendfile', apache_get_modules())) {
      header("X-Sendfile: {$filename}");
      header("X-LIGHTTPD-send-file: {$filename}");
      header("X-Accel-Redirect : {$filename}");
    } else {
      readfile($filename);
    }
  }
 
  function is_win()
  {
    return (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');        
  }
 
  function getDom($html,$dom)
  {
    $dhtml = str_get_html($html);
    $OUTPUTS = ARRAY();
    foreach($dhtml->find($dom) as $k)
    {
      array_push($OUTPUTS,$k->innertext);
    }    
    return $OUTPUTS;
  }
  function getDomValue($html,$dom)
  {
    $dhtml = str_get_html($html);
    $OUTPUTS = ARRAY();
    foreach($dhtml->find($dom) as $k)
    {
      array_push($OUTPUTS,$k->value);
    }    
    return $OUTPUTS;
  }
  function allowAjax(){
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
  }
  
  