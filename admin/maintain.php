<?php require '../inc/config.php'; 
  $include_mode="jquery-ui|jquery-datetimepicker";
  $GETS_STRING="searchcode";
  $GETS=getGET_POST($GETS_STRING,"GET");
  $SQL_APPEND="";
  $PA=ARRAY();

  $SQL_APPEND.=" OR CONCAT(`orin_filename`,`title`,`keyword`) LIKE ? ";
  array_push($PA,"%{$GETS['searchcode']}%");

  $SQL="
          SELECT * FROM 
            `ppt`
          WHERE
            1=1
            AND (0=1 {$SQL_APPEND} )  
          ORDER BY 
            `id` DESC
       ";       
  $SQL_ROWS="SELECT COUNT(*) AS `COUNTER` FROM ({$SQL}) a";
            $ra_counts=selectSQL_SAFE($SQL_ROWS,$PA); 
            $totals_rows=$ra_counts[0]['COUNTER'];
            $SQL.=sprintf(" LIMIT %d,%d",($page*$p),$p);  
  $ra=selectSQL_SAFE($SQL,$PA);
  foreach($ra as $k=>$v)
  {
    //建檔時間
    $ra[$k]['f_create_datetime']=date('Y-m-d',strtotime($ra[$k]['create_datetime']));
    //檔名顏色
    $ra[$k]['f_orin_filename']=str_ireplace("{$GETS['searchcode']}","<span class='find_word'>{$GETS['searchcode']}</span>",$ra[$k]['orin_filename']);
    $ra[$k]['f_title']=str_ireplace("{$GETS['searchcode']}","<span class='find_word'>{$GETS['searchcode']}</span>",$ra[$k]['title']);
    $ra[$k]['f_keyword']=str_ireplace("{$GETS['searchcode']}","<span class='find_word'>{$GETS['searchcode']}</span>",$ra[$k]['keyword']);
    
    $ra[$k]['f_id']="<center>{$ra[$k]['id']}</center>";
    $ra[$k]['ppt']="<a target='_blank' href='{$base_url}/api.php?mode=download&ppt_id={$ra[$k]['id']}'>下載</a>";
    switch($ra[$k]['pdf_status'])
    {
      case '0':
        $ra[$k]['f_pdf_status']="待命";
        break;
      case '1':
        $ra[$k]['f_pdf_status']="轉檔中";
        break;
      case '2':
        $ra[$k]['f_pdf_status']="<a target='_blank' href='{$base_url}/OUTPUT/pdf/{$ra[$k]['id']}.pdf'>PDF</a>";
        break;
    }
    switch($ra[$k]['png_status'])
    {
      case '0':
        $ra[$k]['f_png_status']="待命";
        break;
      case '1':
        $ra[$k]['f_png_status']="轉檔中";
        break;
      case '2':
        $ra[$k]['f_png_status']="<center><a target='_blank' req='png_text_{$ra[$k]['id']}' href='#' action='{$base_url}/admin/png_text_edit.php?id={$ra[$k]['id']}'>維護</a></center>";
        break;
    }
    switch($ra[$k]['text_status'])
    {
      case '0':
        $ra[$k]['f_text_status']="待命";
        break;
      case '1':
        $ra[$k]['f_text_status']="轉檔中";
        break;
      case '2':
        $ra[$k]['f_text_status']="";
        break;
    }
    $ra[$k]['OTHER']="
    <input req='edit_{$ra[$k]['id']}' type='button' value='編輯'> 
    &nbsp;
    <input req='del_{$ra[$k]['id']}' type='button' value='刪除'>
    ";          
  }
?>
<?php require "{$base_dir}/template/html.php"; ?>
<?php require "{$base_dir}/template/head.php"; ?>
<style>
.thetable th[field='其他']{
  width:140px;
  text-align:center;  
}
.thetable th[field='檔案']{
  width:60px;
  text-align:center;  
}
.thetable th[field='PDF']{
  width:60px;
  text-align:center;  
}
.thetable th[field='圖檔/文字']{
  width:60px;
  text-align:center;  
}
.thetable td[field='ppt']{  
  text-align:center;  
}
.thetable td[field='f_pdf_status']{  
  text-align:center;  
}
.thetable td[field='f_png_status']{  
  text-align:center;  
}
.thetable td[field='OTHER']{
  padding:0px;  
  text-align:center;  
}
</style>
<script language="javascript">
  $(document).ready(function(){
    //維護
    $("a[req^='png_text_']").unbind("click");
    $("a[req^='png_text_']").click(function(){
      window['wh']=getWindowSize();
      var tmp = "";
      tmp+=sprintf("<div style='width:%dpx;height:%dpx;overflow:auto;'>",
        (window['wh']['width']*80/100),
        (window['wh']['height']*80/100));
      tmp+=myAjax($(this).attr('action'),"");
      tmp+="</div>";
      dialogOn(tmp,false,function(){
      });
      return false;
    });
    //編輯
    $("input[req^='edit_']").unbind("click");
    $("input[req^='edit_']").click(function(){            
      var id = end(explode("_",$(this).attr('req')));
      var dom = $(this);
      myAjax_async("<?=$base_url;?>/admin/edit_ppt.php?id="+id,"",function(tmp){
        dialogOn(tmp,false,function(){                            
          //save
          $("#ppt_save_btn").unbind("click");
          $("#ppt_save_btn").click(function(){
            var o = $("#theform").serialize();
            var tmp = myAjax("<?=$base_url;?>/api.php?mode=save_ppt_edit&id="+id,o);
            //alert(tmp);
            alert("儲存成功了...");
            //alert(dom.parent().parent().html());
            var jtmp = json_decode(tmp,true);
            //alert(dom.parent().parent().find("td[field='"+k+"']").size());
            for(var k in jtmp)
            {  
              //alert(k+","+jtmp[k]);
              $(dom.parent().parent()).find("td[field='"+k+"']").html(jtmp[k]);
              $(dom.parent().parent()).find("td[field='f_"+k+"']").html(jtmp[k]);
              $(dom.parent().parent()).find("td[field='fake_"+k+"']").html(jtmp[k]);              
            }
            
            $(dom.parent().parent()).addClass("trShow",1000);        
            (function (x) {
              setTimeout(function(){
                x.removeClass("trShow",1000);
              },3500);
            })($(dom.parent().parent()));
            dialogOff();
          });
        });
      });       
    });
    //刪除
    $("input[req^='del_']").unbind("click");
    $("input[req^='del_']").click(function(){
      var id=end(explode("_",$(this).attr('req')));
      if(confirm("要刪除嗎！？")==true)
      {
        var o=new Object();
        o['id']=id;
        myAjax("<?=$base_url;?>/api.php?mode=del_ppt",o);
        alert("已刪除...");
        location.reload();
      } 
    });
  });
</script>
</head>
<?php require "{$base_dir}/template/body.php"; ?>
<?php require "{$base_dir}/template/top.php"; ?>
<!--start-->
  <h2>簡報維護</h2>  
  <center>
    <form method="GET" action="?">
    搜尋：<input type="text" id="searchcode" name="searchcode" placeholder="請輸入搜尋關鍵字">
    <input type="submit" value="搜尋">
    </form>
  </center>
  <br>
  <center>
  <?php
  echo print_table($ra,"f_id,f_orin_filename,f_title,f_create_datetime,ppt,f_pdf_status,f_png_status,OTHER",
  "序號,檔名,標題,建檔時間,檔案,PDF,圖檔/文字,其他","thetable");
  ?>
  <br>
  <?php
  $SCODE = urlencode($GETS['searchcode']);
  array_page($totals_rows,$page,$p,$px,"searchcode={$SCODE}",$mode='normal',$spandiv='');
  ?>
  </center>
<!--end-->
<?php require "{$base_dir}/template/footer.php"; ?>
</body>
</html>