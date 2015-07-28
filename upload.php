<?php require 'inc/config.php'; ?>
<?php
  $include_mode="jquery-form";
  $GETS_STRING="mode,filename";
  $GETS=getGET_POST($GETS_STRING,'GET');
  switch($GETS['mode'])
  {
    case 'upload':   
      @mkdir("upload",0777);
      //pre_print_r($_FILES);
      
      $ok_file = "doc,docx,ppt,pptx,pdf";
      $m_ok_file = explode(',',$ok_file);      
      for($i=0,$max_i=count($_FILES['upfile']['name']);$i<$max_i;$i++)
      {
        $sn = strtolower(subname($_FILES['upfile']['name'][$i]));
        if(!in_array($sn,$m_ok_file))
        {
          continue;
        }
        if($_FILES['upfile']['size'][$i]==0)
        {
          continue;
        }
        $nfilename=sprintf("%s_%s.%s",time(),$i,subname($_FILES['upfile']['name'][$i]));
        copy($_FILES['upfile']['tmp_name'][$i],"{$base_dir}/upload/{$nfilename}");
        $m=ARRAY();
        $m['orin_filename']=$_FILES['upfile']['name'][$i];
        $m['filename']=$nfilename;
        $m['kind']=$sn;
        $m['create_datetime']=date('Y-m-d H:i:s',filectime($_FILES['upfile']['tmp_name'][$i]));
        $m['upload_datetime']=date('Y-m-d H:i:s');
        $m['title']="";
        $m['keyword']="";
        $m['author']="";
        switch($sn)
        {
          case 'pdf':
            //如果傳的是pdf，就不用傳了
            $m['pdf_status']="2";
            break;
          default:
            $m['pdf_status']="0";
            break;
        }
        
        $m['png_status']="0";
        $m['text_status']="0";
        $LAST_ID = insertSQL('ppt',$m);
        switch($sn)
        {
          case 'pdf':
            copy("{$base_dir}/upload/{$nfilename}","{$OUTPUT_PDF}/{$LAST_ID}.pdf");
            break;
        }                     
      }
      ?>
      <div style="background-color:pink;text-align:center;">
      上傳成功<br>      
      </div>
      <br>      
      <?php
      location_replace("admin/maintain.php");
      exit();
      break;    
  }
?>
<?php require "{$base_dir}/template/html.php"; ?>
<?php require "{$base_dir}/template/head.php"; ?>
<script language="javascript">
 function mySubmit(){
    $('#theform').ajaxSubmit({
        beforeSend: function () {                
        },
        uploadProgress: function (event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            if($("#percent").size()!=0)
            {                  
              $("#percent").html(percentVal);
            }                
        },
        complete: function(xhr) {
            alert("傳完了...");                
            dialogMyBoxOff();                
            location.reload();         
        }
    });
  }
  $(document).ready(function(){        
    $("#theform").submit(function(){
      dialogMyBoxOn("上傳中...<span id='percent'>0%</span>",false,function(){            
        mySubmit();
      });
      
      return false;
    });                         
        
       
  });
</script>
</head>
<?php require "{$base_dir}/template/body.php"; ?>
<?php require "{$base_dir}/template/top.php"; ?>
<!--start-->
  <h2>各種簡報上傳</h2>
  <fieldset style="width:50%;margin-left:auto;margin-right:auto;">
    <legend>上傳檔案：(doc,docx,ppt,pptx,pdf...)</legend>
    <form id='theform' action="?mode=upload" method="post" enctype="multipart/form-data">
      上傳檔案：<input type="file" id="upfile" name="upfile[]" multiple="true">
      <input id="submit_btn" type="submit" value="上傳">    
    </form>
  </fieldset>
  
<!--end-->  
</body>
</html>