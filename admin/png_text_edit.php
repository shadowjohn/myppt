<?php require '../inc/config.php'; ?>
<?php
  $GETS_STRING="id";
  $GETS=getGET_POST($GETS_STRING,'GET');
  $SQL="SELECT * FROM `ppt_items` WHERE `ppt_id`=? ORDER BY `id` ASC";
  $ra=selectSQL_SAFE($SQL,ARRAY($GETS['id']));
  for($i=0,$max_i=count($ra);$i<$max_i;$i++)
  {
    $ra[$i]['f_p']=($i+1);
    $ra[$i]['contents']=strip_tags($ra[$i]['contents']);
    $ra[$i]['contents']=nl2br($ra[$i]['contents']);
    $ra[$i]['f_png']="<img class='thepng' req='thepng' width='80' src='{$base_url}/OUTPUT/png/{$GETS['id']}/{$ra[$i]['filename']}'>";
  }
?>
<?php require "{$base_dir}/template/html.php"; ?>
<?php require "{$base_dir}/template/head.php"; ?>
<style>
  .thetable{
    
  }
  .thetable td:nth-child(1){
    width:30px;
    text-align:center;
  }
  .thetable td:nth-child(2){
    text-align:center;
    width:80px;
  }
  .thetable td:nth-child(3){    
    width:280px;
  }
  .thetable td:nth-child(4){
    text-align:center;    
    width:50px;
  }
  .thetable td:nth-child(5){    
    width:250px;
  }
  .thepng{
    cursor:pointer;
  }
</style>
<script language="javascript">
  $(document).ready(function(){
    window['wh']=getWindowSize();
    $(".thepng").unbind("click");
    $(".thepng").click(function(){
      var tmp = "";
      tmp = sprintf("<img width='%s' src='%s'>",(window['wh']['width']*50/100 ),$(this).attr('src'));
      dialogOn(tmp,true,function(){        
      });
    });
  });
</script>
</head>
<?php require "{$base_dir}/template/body.php"; ?>
<?php require "{$base_dir}/template/top.php"; ?>
<!--start-->
<center>
<?php
  echo print_table($ra,"f_p,f_png,contents,kind,keyword",
                       "頁碼,圖片,內容,分類,關鍵字","thetable");
?>
</center>
<!--end-->
</body>
</html>