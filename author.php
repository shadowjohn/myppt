<?php require 'inc/config.php'; ?>
<?php require "{$base_dir}/template/html.php"; ?>
<?php require "{$base_dir}/template/head.php"; ?>
<style>
.thetable td{
  border-bottom:2px dashed orange;
}
</style>
</head>
<?php require "{$base_dir}/template/body.php"; ?>
<?php require "{$base_dir}/template/top.php"; ?>
<!--start-->
  <center>
    <h2>團隊成員</h2>
    <table border="0" class="thetable">
      <tr>
        <td align="center">
          <img src="<?=$base_url;?>/pic/author/pinky.jpg" width="80">         
        </td>
        <td>          
          地球處 黃碧慧 (Pinky)
        </td>    
        <td align="center">
          <img src="<?=$base_url;?>/pic/author/jerry.jpg" width="80">          
        </td>
        <td>
          空資處 吳政庭 (Jerry)
        </td>
      </tr>
      <tr>
        <td align="center">
          <img src="<?=$base_url;?>/pic/author/elly.jpg" width="80">         
        </td>
        <td>
          科管處 陳智偉 (Elly)
        </td>     
        <td align="center">
          <img src="<?=$base_url;?>/pic/author/john.jpg" width="80">
        </td>
        <td>
          地球處 何宗翰 (John)
        </td>
      </tr> 
      <tr>
        <td align="center">
          <img src="<?=$base_url;?>/pic/author/phoeni.jpg" width="80">         
        </td>
        <td>
          地球處 賴鈺婷 (Phoeni)
        </td>     
        <td align="center">
          <img src="<?=$base_url;?>/pic/author/vincent.jpg" width="80">
        </td>
        <td>
          事業處 郭保宏 (Vincent)
        </td>
      </tr>                
    </table>
  </center>
<!--end-->
<?php require "{$base_dir}/template/footer.php"; ?>
</body>
</html>