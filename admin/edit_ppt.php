<?php require '../inc/config.php';   
  $GETS_STRING="id";
  $GETS=getGET_POST($GETS_STRING,"GET");
  
  
  $FIELDS="id,kind,title,計畫名稱,年度,簡報人,簡報類別,報告書類型,是否有拿到計畫,競爭對手,委員名單,評選心得,是否延續型計畫,orin_filename,filename,keyword,create_datetime,upload_datetime,author,pdf_status,png_status,text_status,has_single_page,download_counter,grades";
  $mFIELDS=explode(",",$FIELDS);
  $SQL="SELECT * FROM `ppt` WHERE `id` = ? LIMIT 1";
  $ra = selectSQL_SAFE($SQL,ARRAY($GETS['id']));  
?>
<script language="javascript">
  $(document).ready(function(){    
     <?php     
     foreach($mFIELDS as $v)     
     {
      ?>
      $("#<?=$v;?>").val("<?=jsAddSlashes($ra[0][$v]);?>");
      <?php
     }
     ?>
  });
</script>
<div style="width:80%;height:500px;overflow:auto;">
<center>
<h2>編輯【<?=$ra[0]['orin_filename'];?>】</h2>
<form id="theform" name="theform">
<table border="1" cellpadding="0" cellspacing="0">
  <tr>  
    <th>項目</th>
    <th>內容</th>
  </tr>

<tr>
                  <th align="center">ptt.doc.pdf...</th>
                  <td align="left">
                    <input id="kind" name="kind" type="text">
                  </td>
                </tr>
                <tr>
                  <th align="center">標題</th>
                  <td align="left">
                    <input id="title" name="title" type="text">
                  </td>
                </tr>
                <tr>
                  <th align="center">計畫名稱</th>
                  <td align="left">
                    <input id="計畫名稱" name="計畫名稱" type="text">
                  </td>
                </tr>
                <tr>
                  <th align="center">年度</th>
                  <td align="left">
                    <input id="年度" name="年度" type="text">
                  </td>
                </tr>
                <tr>
                  <th align="center">簡報人</th>
                  <td align="left">
                    <input id="簡報人" name="簡報人" type="text">
                  </td>
                </tr>
                <tr>
                  <th align="center">簡報類別:期初,期中,期末,評選,其他</th>
                  <td align="left">
                    <input id="簡報類別" name="簡報類別" type="text">
                  </td>
                </tr>
                <tr>
                  <th align="center">報告書類型：期初,期中,期末,成果,服務建議書,其他</th>
                  <td align="left">
                    <input id="報告書類型" name="報告書類型" type="text">
                  </td>
                </tr>
                <tr>
                  <th align="center">是否有拿到計畫<br>0:未,1:有,2:其他</th>
                  <td align="left">
                    <input id="是否有拿到計畫" name="是否有拿到計畫" type="text">
                  </td>
                </tr>
                <tr>
                  <th align="center">競爭對手</th>
                  <td align="left">
                    <input id="競爭對手" name="競爭對手" type="text">
                  </td>
                </tr>
                <tr>
                  <th align="center">委員名單</th>
                  <td align="left">
                    <input id="委員名單" name="委員名單" type="text">
                  </td>
                </tr>
                <tr>
                  <th align="center">評選心得</th>
                  <td align="left">
                    <input id="評選心得" name="評選心得" type="text">
                  </td>
                </tr>
                <tr>
                  <th align="center">是否延續型計畫<br>0:否,1:是,2:其他</th>
                  <td align="left">
                    <input id="是否延續型計畫" name="是否延續型計畫" type="text">
                  </td>
                </tr>               
                <tr>
                  <th align="center">關鍵字</th>
                  <td align="left">
                    <input id="keyword" name="keyword" type="text">
                  </td>
                </tr>
                <tr>
                  <th align="center">原建立時間</th>
                  <td align="left">
                    <input id="create_datetime" name="create_datetime" type="text">
                  </td>
                </tr>
                <tr>
                  <th align="center">上傳時間</th>
                  <td align="left">
                    <input id="upload_datetime" name="upload_datetime" type="text">
                  </td>
                </tr>
                <tr>
                  <th align="center">作者</th>
                  <td align="left">
                    <input id="author" name="author" type="text">
                  </td>
                </tr>              
                <tr>
                  <th align="center">下載次數</th>
                  <td align="left">
                    <input id="download_counter" name="download_counter" type="text">
                  </td>
                </tr>
                <tr>
                  <th align="center">被讚的次數</th>
                  <td align="left">
                    <input id="grades" name="grades" type="text">
                  </td>
                </tr>




  
</table>
</form>
<br>
<input type="button" value="儲存" id="ppt_save_btn">
<input type="button" value="取消" onClick="dialogOff();">
</center>
</div>