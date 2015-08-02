<?
  $DB_HOST="localhost";
  $DB_LOGIN="ppt";
  $DB_PASSWORD="*gis5200";
  $DB_NAME="myppt";
  $DB_KIND="mysql"; 
  
  
  @ini_set("memory_limit","1024M");
  @ini_set('post_max_size', '800M');  
  @ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
  //@ini_set('session.save_path', '/tmp');
  @header("p3p: CP=\"CAO PSA OUR\"");
  @session_start();  
  @session_cache_limiter('private_no_expire, must-revalidate');
  @header("Content-Type: text/html; charset=utf-8");       
  date_default_timezone_set("Asia/Taipei");
  mb_http_output("UTF-8");
  mb_internal_encoding('UTF-8'); 
  mb_regex_encoding("UTF-8");


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
  $pdo->query("SET GLOBAL group_concat_max_len=102400");
  $pdo->query("SET GLOBAL max_connections=1024");
  //function start
  function __($input)
  {
    return $input;
  }
  function getGET_POST($inputs,$mode)
  {
    $GET_POSTS=array();
    switch($mode)
    {
      case 'GET':
          foreach(explode(',',$inputs) as $index)
          {
            if(!is_array($_GET[$index]))
            {
              $GET_POSTS[$index]=trim(htmlspecialchars($_GET[$index]));
            }
            else
            {        
              $GET_POSTS[$index]=$_GET[$index];
            }
          }
        break;
      case 'POST':
          foreach(explode(',',$inputs) as $index)
          {
            if(!is_array($_POST[$index]))
            {
              $GET_POSTS[$index]=trim(htmlspecialchars($_POST[$index]));
            }
            else
            {
              $GET_POSTS[$index]=$_POST[$index];
            }
          }
        break;
    }
    return $GET_POSTS;
  }
  function pdo_resulttoarray($res){
    $g=null;
    $g=array();
    
    /*while($row=$res->fetchAll(PDO::FETCH_NUM)){
      array_push($g,$row);
    }*/
    $g=$res->fetchAll(PDO::FETCH_NUM);
    return $g;
  }
  function pdo_resulttoobject($res){
    $g = null;
    $g = array();
    
    /*while($row=$res->fetchAll(PDO::FETCH_OBJ)){    
      array_push($g,$row);
    }*/
    $g=$res->fetchAll(PDO::FETCH_OBJ);    
    return $g;
  }    
  
    
  $GETS_STRING="mode,database";
  $GETS=getGET_POST($GETS_STRING,'GET');
  switch($GETS['mode'])
  {
    case 'getTables':
          $POSTS_STRING="database";
          $POSTS=getGET_POST($POSTS_STRING,'POST');
          $SQL=sprintf("show table status from `%s`",$POSTS['database']);
          $res=$pdo->query($SQL);
          $res_object=pdo_resulttoobject($res);
          ?>       
          <form action="?mode=dump_schema&database=<?=urlencode($POSTS['database']);?>" method="post">
          <table border="1" cellpadding="0" cellspacing="0">
            <tr>
              <th>選擇<input type="checkbox" id="selectAll" onClick="selectAlls();"></th>
              <th>資料表名稱</th>
              <th>說明</th>
            </tr>
            <?
            for($i=0;$i<count($res_object);$i++)
            {              
            ?>
            <tr>
              <td align="center">
                <input type="checkbox" id="selectAll_<?=$i;?>" name="selectAll[]" value="<?=$res_object[$i]->{'Name'};?>">
              </td>
              <td>
                <?=$res_object[$i]->{'Name'};?>
              </td>
              <td>
                <?=$res_object[$i]->{'Comment'};?>
              </td>
            </tr>
            <?
            }
            ?>
          </table>          
          <br>
          <input type="submit" onClick="dumpDB();" value="<?=('匯出資料結構');?>">
          </form>
          <?
          //print_r($res_object);
        exit();
      break;
    case 'dump_schema':
         $POSTS_STRING="selectAll";
         $POSTS=getGET_POST($POSTS_STRING,'POST');
         $data_base_tmp_data="";
         $try_value=@implode("",$POSTS['selectAll']);
         if($try_value=="")
         {
           ?>
           <script language="javascript">
            alert("<?=__('沒有資料表被選到');?>...");
            history.go(-1);
           </script>
           <?
           exit();
         }         
         for($i=0;$i<count($POSTS['selectAll']);$i++)
         {
           $SQL=sprintf("show table status from `%s` where name='%s' ",$GETS['database'],$POSTS['selectAll'][$i]);
           $res=$pdo->query($SQL) or die ("資料庫錯誤:{$SQL}");
           $res_object=pdo_resulttoobject($res);  
           $SQL=sprintf("SHOW FULL FIELDS FROM `%s`.`%s`",$GETS['database'],$POSTS['selectAll'][$i]);
           $res_explain=$pdo->query($SQL) or die ("資料庫錯誤:{$SQL}");
           $res_explain_object=pdo_resulttoobject($res_explain);           
           if(count($res_object)!=0)
           {
                          
              //echo "<form id=newForm1 action=?mode=startcreate method=POST>";
              ob_start();
              echo $res_object[0]->{'Name'}."　　".$res_object[0]->{'Comment'};              
              ?>
              <br>
              <table border="1" cellpadding="5" cellspacing="0" width="90%">
              <tr>
                <th width="25%">欄位名稱(英)</th>
                <th width="25%">欄位名稱(中)</th>
                <th width="25%">型態</th>
                <th width="25%">相關參數</th>
              </tr>
              <?
              for($j=0;$j<count($res_explain_object);$j++)
              {
                ?>
                <tr>
                <td><?=$res_explain_object[$j]->{'Field'};?></td>
                <td><?=$res_explain_object[$j]->{'Comment'};?></td>
                <td><?=$res_explain_object[$j]->{'Type'};?></td>                
                <td><?=(trim($res_explain_object[$j]->{'Key'})=="")?"":"Key：{$res_explain_object[$j]->{'Key'}}";?>
                    <?=(trim($res_explain_object[$j]->{'Default'})=="")?"":"<br>Default：{$res_explain_object[$j]->{'Default'}}";?>                    
                    <?=(trim($res_explain_object[$j]->{'Extra'})=="")?"":"<br>Extra：{$res_explain_object[$j]->{'Extra'}}";?>
                    &nbsp;
                </td>
                </tr>
                <?
              }     
              ?>
              </table>
              <br>
              
              
              
              <?         
              $data_base_tmp_data.=ob_get_contents();
              
            }
            ob_end_clean();
            
            //echo code             
            ob_start();                  
echo "
<?php\n
  //查找資料庫的內容
  \$SQL=sprintf(\"SELECT * FROM `{$res_object[0]->{'Name'}}`\");  
  //分頁
  \$sql_rows=\"select count(*) as `counter` from (\".\$SQL.\") a\";
  \$rows=\$pdo->query(\$sql_rows);
  \$rows_assoc=pdo_resulttoassoc(\$rows);
  \$totals_rows=\$rows_assoc[0]['counter'];
  \$SQL.=\" limit \".(\$page*\$p).\",\".\$p;    
    
  \$ra=selectSQL(\$SQL);  
?>          
";
?>  
<!--表單畫面Start-->          
<table border="1" cellpadding="0" cellspacing="0">
  <thead>
    <tr>
<?php
        for($j=0;$j<count($res_explain_object);$j++)
        {
          ?>
       <th><?=$res_explain_object[$j]->{'Comment'};?></th>
<?
        }
?>
    </tr>
  </thead>
  <tbody>
<?php 
echo '
    <?php
      for($i=0;$i<count($ra);$i++)
      {
    ?>
        <tr>
';

  for($j=0;$j<count($res_explain_object);$j++)
  {
    ?>
          <td><?php echo '<?=$ra[$i][\'';
                    echo $res_explain_object[$j]->{'Field'};
                    echo '\'];?>';?>&nbsp;</td>
<?php
  }

echo '        </tr>
    <?php
      }
    ?>
';
?>
  </tbody>
</table>
<!--表單畫面End-->
<!--分頁Start-->
<?php
echo '<?php
  array_page($totals_rows,$page,$p,$px,$new_Link,$mode=\'normal\',$spandiv=\'\');
?>
';
?>
<!--分頁Start--> 
            <?
            $data_base_tmp_data.="<div class='codes' style='align:left;width:700px;background-color:#222;padding:5px;text-align:left;'><pre>".htmlspecialchars(ob_get_contents())."</pre></div>";
            ob_end_clean();
            
                     
            
            
          }
         
            
      break;
  }
  $SQL=sprintf("show databases");
  $res=$pdo->query($SQL) or die("查詢資料庫失敗:{$SQL}");
  $res_array=pdo_resulttoarray($res);  

?>
<!DOCTYPE HTML>
<html>  
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<title><?=__('資料庫資料匯出機');?></title>
<style>
html,body{
  background-color:black;
  color:white;
}
.codes{
  display:none;
}
</style>
<script language="javascript" src="jquery-1.8.3.min.js"></script>
<script language="javascript" src="php.js"></script>
<style>
</style>
<script language="javascript">
  function myAjax(url,postdata)
  {
    var tmp = $.ajax({
        url: url,
        type: "POST",
        data: postdata,
        async: false
     }).responseText;
    return tmp;
  }
  $(document).ready(function(){
  });
</script>
</head>
<body>
<center>
  <font color="#FFFFFF" size="6" face="標楷體"><?=__('資料庫資料匯出機');?></font>
  <br>
  <br>  

           <!--內文 Start -->
<script language="javascript">
  $(document).ready(function(){
      <?
        if($GETS['database']!="")
        {
          ?>
          $("#database").val("<?=$GETS['database'];?>");
          $("#database_btn").click();
          <?
        }
        if($GETS['mode']=='dump_schema')
        {
          
          for($i=0;$i<count($POSTS['selectAll']);$i++)
          {
            ?>
            var list_array=new Array('<?=implode("','",$POSTS['selectAll']);?>');
            for(i=0;i<$("*[name='selectAll[]']").length;i++)
            {
              if(in_array($($("*[name='selectAll[]']")[i]).val(),list_array))
              {
                $($("*[name='selectAll[]']")[i]).prop('checked',true);
              }
            }
            <?
          }
        }
      ?>
      $("#codes_btn").unbind("click");
      $("#codes_btn").click(function(){        
        $(".codes").toggle();
        if($(".codes").is(":visible"))
        {
          $("#codes_btn").attr("程式碼-關");          
        }
        else
        {
          $("#codes_btn").attr("程式碼-開");
        }
      });
  });        
  function selectDB(input)
  {
    var $tmp="";
    $tmp=myAjax("?mode=getTables","database="+input);
    $("#show_tables_list").html($tmp);
  }
</script>
<script language="javascript">
  function selectAlls()
  {
    //alert($("#selectAll").prop('checked'));
    for(i=0;i<$("*[name='selectAll[]']").length;i++)
    {
       $($("*[name='selectAll[]']")[i]).prop('checked',$("#selectAll").prop('checked'));
    }              
  }
</script>
<center><h2>管理人員架站資料→系統資料結構表</h2></center>
<div align="left">說明：這支程式是用來匯出資料庫的結構。</div>

<?=('請選擇要匯出的資料庫');?>：
<select id="database" name="database">
  <option value="">--<?=('請選擇');?>--</option>
<?
  for($i=0;$i<count($res_array);$i++)
  {
    if($res_array[$i][0]=='information_schema')
    {
      continue;
    }
  ?>
  <option value="<?=$res_array[$i][0];?>"><?=$res_array[$i][0];?></option>
  <?
  }
?>
</select>
&nbsp;&nbsp;&nbsp;
<input id="database_btn" type="button" onClick="if($('#database').val()!=''){selectDB($('#database').val());}" value="<?=('選擇資料庫');?>">
<br>
<br>
<center>
<div id="show_tables_list" align="center"></div>
<hr>
<?php
  if($GETS['mode']=='dump_schema')
  {
?>
<div style="margin-right:50px;text-align:right;">
  <input id='codes_btn' type='button' value='程式碼-開'>
</div>
<br>
<?
  }
  echo $data_base_tmp_data;
  //製作 odt 文件
 /* $temp_dir="output_".time();
  $temp_file="output.odt";
  @mkdir("temp",0777);
  @mkdir("temp/{$temp_dir}",0777);
  exec("cp output.odt temp/{$temp_dir}/");
  exec("cd temp/{$temp_dir}/;unzip output.odt;rm -fr output.odt;cd -");
  $odt_contents=file_get_contents("temp/{$temp_dir}/content.xml");
  $odt_contents=str_replace("{3WA_DBNAME_COMMENT}",$GETS['database'],$odt_contents); //英文的 database name
  $odt_contents=str_replace("{3WA_CONTENTS}",$data_base_tmp_data,$odt_contents); //下面表格的部分
  file_put_contents("temp/{$temp_dir}/content.xml",$odt_contents);
  exec("cd temp/{$temp_dir}/;zip -r output.odt .;rm -fr Configurations2 content.xml META-INF meta.xml mimetype settings.xml styles.xml Thumbnails;cd -");*/  
?>
</center>
<!--a target="_blank" href="<? //="temp/{$temp_dir}/output.odt";?>">~Download odt~</a-->
<!--內文 End -->

</body>
</html>

