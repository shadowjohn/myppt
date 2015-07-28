<?php require '../inc/config.php'; ?>
<?php
  $SQL="SELECT * FROM `ppt` ORDER BY `id` DESC";
  $ra=selectSQL($SQL);
  foreach($ra as $k=>$v)
  {
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
        $ra[$k]['f_png_status']="<center><a target='_blank' req='png_text_{$ra[$k]['id']}' href='{$base_url}/admin/png_text_edit.php?id={$ra[$k]['id']}'>維護</a></center>";
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
<script language="javascript">
  $(document).ready(function(){

  });
</script>
</head>
<?php require "{$base_dir}/template/body.php"; ?>
<?php require "{$base_dir}/template/top.php"; ?>
<!--start-->
  <h2>簡報維護</h2>
  <center>
  <?php
  echo print_table($ra,"id,orin_filename,title,keyword,page,create_datetime,f_pdf_status,f_png_status,OTHER",
  "序號,檔名,標題,建檔時間,頁數,關鍵字,PDF,圖檔/文字,其他");
  ?>
  </center>
<!--end-->
</body>
</html>