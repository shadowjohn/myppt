<head>  
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8">  
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="Resource-Type" content="document">
  <meta name="Distribution" content="Global">   
  <title>簡報贏家方程式</title>
  <meta name="Author" content="Pinky,Elly,Jerry,保宏,Phoeni,John">
  <meta name="Keywords" content="ppt,pptx,doc,docx,pdf,文件歸類,文件搜尋">
  <meta name="Generator" content="3WA-Company">
  <link href="<?php echo $base_url;?>/css/reset.css" rel="stylesheet" type="text/css">
  <link href="<?php echo $base_url;?>/css/style.css" rel="stylesheet" type="text/css">

  <script src="<?php echo $base_url;?>/inc/javascript/jquery/jquery-1.8.3.min.js" type="text/javascript"></script>
  <script src="<?php echo $base_url;?>/inc/javascript/php/php.js" type="text/javascript"></script>
  <script src="<?php echo $base_url;?>/inc/javascript/jquery/mybox/mybox-0.6.min.js" type="text/javascript"></script>
  <script src="<?php echo $base_url;?>/inc/javascript/include.js" type="text/javascript"></script>
  <script src="<?php echo $base_url;?>/inc/javascript/jquery/jquery-placeholder/jquery.placeholder.min.js" type="text/javascript"></script>  



  <script language="javascript">
    // 修正 placeholder
    $(document).ready(function() {
      $('input, textarea').placeholder();

    });
  </script>                      
<?php
  $minclude_mode = explode("|",$include_mode);
  for($i=0,$max_i=count($minclude_mode);$i<$max_i;$i++)
  {
    $minclude_mode[$i]=strtolower(trim($minclude_mode[$i]));
    switch($minclude_mode[$i])
    {

      case 'jquery-form':
        ?>
        <script type="text/javascript" src="<?php echo $base_url;?>/inc/javascript/jquery/jquery.form.js"></script>
        <?php
        break;
      case 'jquery-center':
        ?>
        <script type="text/javascript" src="<?php echo $base_url;?>/inc/javascript/jquery/jquery.center.js"></script>
        <?php
        break;
      case 'jquery-ui':
          ?>
            <script src="<?php echo $base_url;?>/inc/javascript/jquery/jquery_UI/jquery-ui-1.8.24/ui/minified/jquery-ui.min.js" type="text/javascript"></script>
            <link href="<?php echo $base_url;?>/inc/javascript/jquery/jquery_UI/jquery-ui-1.8.24/themes/base/minified/jquery-ui.min.css" rel="stylesheet" type="text/css">
          <?php
        break;
      case 'jquery-corner':
          ?>
            <script src="<?php echo $base_url;?>/inc/javascript/jquery/jquery.corner.js" type="text/javascript"></script>
          <?php
        break;  
    }
  }
 