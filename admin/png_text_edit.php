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
    $ra[$i]['f_png']="<img class='thepng' req='thepng' src='{$base_url}/OUTPUT/png/{$GETS['id']}/{$ra[$i]['filename']}'>";
  }
?>
<style>
  .thetable{
    
  }
  .thetable td[field='f_p']{
    width:30px;
    text-align:center;
  }
  .thetable td[field='f_png']{
    text-align:center;
    width:80px;
  }
  .thetable td[field='contents']{    
    width:280px;
  }
  .thetable td[field='kind']{
    text-align:center;    
    width:50px;
  }
  .thetable td[field='keyword']{    
    width:250px;
  }
  .thepng{
    cursor:pointer;
    border:1px solid #000;
    width:100px;
    background-color:white;
  }
  .ui-dialog { z-index: 99999999998 !important ;}
  .ui-front { z-index: 99999999999 !important; }
</style>
<script language="javascript">
  $(document).ready(function(){
    window['wh']=getWindowSize();
    $(".fly_close_btn").css({
      right: (window['wh']['width']-window['wh']['width']*85/100-50)+'px',
      top: (window['wh']['height']-window['wh']['height']*85/100-10)+'px'
    });
    $(".thepng").unbind("click");
    $(".thepng").click(function(){
      var tmp = "";
      tmp = sprintf(" \
            <div style='text-align:center;width:%dpx;height:%dpx;overflow:auto;'> \
              <img style='margin-left:auto;margin-right:auto;border:1px solid #000;background-color:white;width='%s' src='%s'> \
            </div> \
            ",
      (window['wh']['width']*85/100 ),
      (window['wh']['height']*85/100 ),
      (window['wh']['width']*80/100 ),
      $(this).attr('src'));
       
       $("#mycolorbox").html(tmp);
       $("#mycolorbox").dialog({
         'modal': false,
         'autoOpen': false,
         'width':window['wh']['width']*95/100,
         'height':window['wh']['height']*95/100,
         'zIndex':time()*2000
       });
       $("#mycolorbox").dialog('open'); 
               
    });
  });
</script>
<!--start-->
<center>
<h2>資料維護</h2>
<div class='fly_close_btn' style="text-align:right;right:50px;position:fixed;">
  <input type="button" value="關閉" onClick="dialogOff();">
</div>
<?php
  echo print_table($ra,"f_p,f_png,contents,kind,keyword",
                       "頁碼,圖片,內容,分類,關鍵字","thetable");
?>
</center>
<!--end-->