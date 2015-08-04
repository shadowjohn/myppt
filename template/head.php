<head>  
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8">  
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="Resource-Type" content="document">
  <meta name="Distribution" content="Global">   
  <title>HUBBLE 哈博 智慧簡報＋報告搜尋網</title>
  <meta name="Author" content="Pinky,Elly,Jerry,Vincent,Phoeni,John">
  <meta name="Keywords" content="ppt,pptx,doc,docx,pdf,文件歸類,文件搜尋">
  <meta name="Generator" content="GIS-Company">
  <link href="<?php echo $base_url;?>/css/reset.css" rel="stylesheet" type="text/css">
  <link href="<?php echo $base_url;?>/css/style.css" rel="stylesheet" type="text/css">

  <script src="<?php echo $base_url;?>/inc/javascript/jquery/jquery-1.8.3.min.js" type="text/javascript"></script>
  <script src="<?php echo $base_url;?>/inc/javascript/php/php.js" type="text/javascript"></script>
  <script src="<?php echo $base_url;?>/inc/javascript/jquery/mybox/mybox-0.7.min.js" type="text/javascript"></script>
  <script src="<?php echo $base_url;?>/inc/javascript/include.js" type="text/javascript"></script>
  <script src="<?php echo $base_url;?>/inc/javascript/jquery/jquery-placeholder/jquery.placeholder.min.js" type="text/javascript"></script>  

  <link href="<?php echo $base_url;?>/css/layout.css" rel="stylesheet" type="text/css">


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
      case 'jquery-datetimepicker':
        ?>
        <script type="text/javascript" src="<?php echo $base_url;?>/inc/javascript/jquery/jquery-datetimepicker/jquery.datetimepicker.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>/inc/javascript/jquery/jquery-datetimepicker/jquery.datetimepicker.css"/ >
        <?php
        break;
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
      case 'jquery-lazy':
          ?>
            <script src="<?=$base_url;?>/inc/javascript/jquery/jquery-image-lazy-loading/js/jquery.lazyload.min.js"></script>
            <script language="javascript">
              $(document).ready(function(){
                if(navigator.platform != "iPad")
                {
                  //原本的圖片要改用
                  // <img src="<?=$base_url;?>/inc/javascript/jquery/jquery-image-lazy-loading/images/grey.gif" data-original="原圖路徑">
                  // 之後皆改用 php function lazyimg('src',"{'id','name','style'}");
                  $("img").lazyload({
                    effect:"fadeIn",
                    placeholder: "<?=$base_url;?>/inc/javascript/jquery/jquery-image-lazy-loading/images/grey.gif"
                  });
                }
              });
            </script>              
          <?php
        break;  
    }
  }
 
