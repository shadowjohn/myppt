<?php require '../inc/config.php';   
  $GETS_STRING="id";
  $GETS=getGET_POST($GETS_STRING,"GET");
  
  
  $FIELDS="id,kind,title,計畫名稱,年度,簡報人,簡報類別,報告書類型,是否有拿到計畫,競爭對手,委員名單,評選心得,是否延續型計畫,orin_filename,filename,keyword,create_datetime,upload_datetime,author,pdf_status,png_status,text_status,has_single_page,download_counter,grades";
  $mFIELDS=explode(",",$FIELDS);
  $SQL="SELECT * FROM `ppt` WHERE `id` = ? LIMIT 1";
  $ra = selectSQL_SAFE($SQL,ARRAY($GETS['id'])); 
  $edit_title = "";
  if(mb_strlen($ra[0]['orin_filename'])>=15)
  {
    $edit_title=mb_substr($ra[0]['orin_filename'],0,10)."...";
  } 
  else
  {
    $edit_title = $ra[0]['orin_filename'];
  }
?>
<script language="javascript">
  $(document).ready(function(){    
     <?php     
     foreach($mFIELDS as $v)     
     {
      ?>
      $("#<?=$v;?>").val("<?=jsAddSlashes($ra[0][$v]);?>");
      $("#<?=$v;?>_span").html("<?=jsAddSlashes($ra[0][$v]);?>");
      <?php
     }
     ?>
     
     //datetime
     $("#create_datetime").datetimepicker({
       timepicker:true,
       format:'Y-m-d H:i:s'
     });
     $("#upload_datetime").datetimepicker({
       timepicker:true,
       format:'Y-m-d H:i:s'
     });
  });
</script>
<style>
.ppt_edit_table td{
  padding:5px;
}
.ppt_edit_table input[type='text']{
  width:95%;
}
.ppt_edit_table textarea{
  width:95%;
}
</style>
<div style="padding-left:25px;padding-right:25px;width:80%;width:600px;height:500px;overflow:auto;margin-left:auto;margin-right:auto;">
<center>
<h3>編輯【<?=$edit_title;?>】</h3>
<form id="theform" name="theform">
<div align="right">
  <input type="button" value="關閉" onClick="dialogOff();">
</div>
<table class="ppt_edit_table" border="1" cellpadding="0" cellspacing="0" width="500">
  <tr>  
    <th width="35%">項目</th>
    <th>內容</th>
  </tr>

<tr>
                  <th align="center">檔案類型</th>
                  <td align="left">
                    <input type="hidden" id="kind" name="kind">
                    <span id="kind_span"></span>
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
                  <th align="center">簡報類別</th>
                  <td align="left">
                    <select id="簡報類別" name="簡報類別">
                       <option value="其他">其他</option>
                       <option value="期初">期初</option>
                       <option value="期中">期中</option>
                       <option value="期末">期末</option>
                       <option value="評選">評選</option>                       
                    </select>
                  </td>
                </tr>
                <tr>
                  <th align="center">報告書類型</th>
                  <td align="left">                    
                    <select id="報告書類型" name="報告書類型">
                       <option value="其他">其他</option>
                       <option value="期初">期初</option>
                       <option value="期中">期中</option>
                       <option value="期末">期末</option>
                       <option value="成果">成果</option>
                       <option value="服務建議書">服務建議書</option>                       
                    </select>
                  </td>
                </tr>
                <tr>
                  <th align="center">是否有拿到計畫</th>
                  <td align="left">                    
                    <select id="是否有拿到計畫" name="是否有拿到計畫">
                      <option value='0'>還沒有</option>
                      <option value='1'>有</option>
                      <option value='2'>其他</option>
                    </select>
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
                    <br>(可逗號分格)
                  </td>
                </tr>
                <tr>
                  <th align="center">評選心得</th>
                  <td align="left">
                    <textarea id="評選心得" name="評選心得"></textarea>
                  </td>
                </tr>
                <tr>
                  <th align="center">是否延續型計畫</th>
                  <td align="left">
                    <!--input id="是否延續型計畫" name="是否延續型計畫" type="text"-->
                    <select id="是否延續型計畫" name="是否延續型計畫">
                      <option value='0'>否</option>
                      <option value='1'>是</option>
                      <option value='2'>其他</option>
                    </select>
                  </td>
                </tr>               
                <tr>
                  <th align="center">關鍵字</th>
                  <td align="left">
                    <textarea id="keyword" name="keyword"></textarea>
                  </td>
                </tr>
                <tr>
                  <th align="center">原建立時間</th>
                  <td align="left">
                    <input id="create_datetime" name="create_datetime" type="text" readonly>
                  </td>
                </tr>
                <tr>
                  <th align="center">上傳時間</th>
                  <td align="left">
                    <input id="upload_datetime" name="upload_datetime" type="text" readonly>
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
                    <input id="download_counter" name="download_counter" type="hidden">
                    <span id="download_counter_span"></span>                     
                  </td>
                </tr>
                <tr>
                  <th align="center">被讚的次數</th>
                  <td align="left">
                    <input id="grades" name="grades" type="hidden">
                    <span id="grades_span"></span>  
                  </td>
                </tr>  
          </table>
</form>
<br>
<input type="button" value="儲存" id="ppt_save_btn">
<input type="button" value="取消" onClick="dialogOff();">
</center>
</div>