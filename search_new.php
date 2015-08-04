<?php require 'inc/config.php';
  $include_mode="jquery-lazy";
  $GETS_STRING="mode";
  $GETS=getGET_POST($GETS_STRING,'GET');
  switch($GETS['mode'])
  {
    case 'searchcode':   
      $POSTS_STRING="searchcode,kind,年度,計畫名稱,簡報人,author,是否延續型計畫";
      $POSTS=getGET_POST($POSTS_STRING,'REQUEST');
      $mSEARCHCODES = explode(",",$POSTS['searchcode']);

      $SQL_KIND="";
      $SQL_APPEND="";
      $SQL_SEARCH_CODE_APPEND="";
      $PA = ARRAY();
      switch(strtolower($POSTS['kind']))
      {
        case '':
          break;
        case 'ppt':
          $SQL_KIND=" AND `A`.`kind` like ? ";
          array_push($PA,"%ppt%");
          break;
        case 'doc':
          $SQL_KIND=" AND `A`.`kind` like ? ";
          array_push($PA,"%doc%");
          break;
        case 'pdf':
          $SQL_KIND=" AND `A`.`kind` = ? "; 
          array_push($PA,"%pdf%");           
          break;
      }      
      switch(strtolower($POSTS['年度']))
      {
        case '':
          break;
        default:
          $SQL_APPEND.=" AND LOWER(`A`.`年度`) like ? ";
          array_push($PA,"%{$POSTS['年度']}%");
          break;
      }
      switch(strtolower($POSTS['計畫名稱']))
      {
        case '':
          break;
        default:
          $SQL_APPEND.=" AND LOWER(`A`.`計畫名稱`) like ? ";
          array_push($PA,"%{$POSTS['計畫名稱']}%");
          break;
      }
      switch(strtolower($POSTS['簡報人']))
      {
        case '':
          break;
        default:
          $SQL_APPEND.=" AND LOWER(`A`.`簡報人`) like ? ";
          array_push($PA,"%{$POSTS['簡報人']}%");
          break;
      } 
      switch(strtolower($POSTS['author']))
      {
        case '':
          break;
        default:
          $SQL_APPEND.=" AND LOWER(`A`.`author`) like ? ";
          array_push($PA,"%{$POSTS['author']}%");
          break;
      }
      switch(strtolower($POSTS['是否延續型計畫']))
      {
        case '':
          break;
        default:
          $SQL_APPEND.=" AND LOWER(`A`.`是否延續型計畫`) = ? ";
          array_push($PA,"{$POSTS['是否延續型計畫']}");
          break;
      }    
      foreach($mSEARCHCODES as $v)
      {
        $v = strtoupper($v);
        if(substr($v,0,1)=="+")
        {
          $SQL_SEARCH_CODE_APPEND.=" AND UPPER(CONCAT(`B`.`contents`,`B`.`keyword`)) LIKE ? ";
          $v = substr($v,1);
        }
        else
        {
          $SQL_SEARCH_CODE_APPEND.=" OR UPPER(CONCAT(`B`.`contents`,`B`.`keyword`)) LIKE ? ";
        }
        array_push($PA,"%{$v}%");
      }
      $SQL="
          SELECT 
                `B`.*,
                (`A`.`download_counter`+`A`.`grades`) AS `like` 
            FROM `ppt` as `A`,
                `ppt_items` as `B`
            WHERE
              1=1
              AND `A`.`id`=`B`.`ppt_id`
              {$SQL_APPEND}
              {$SQL_KIND}
              AND 
              (
                0 = 1
                {$SQL_SEARCH_CODE_APPEND} 
              )
              
            ORDER BY 
              (`A`.`download_counter`+`A`.`grades`) DESC,
              `B`.`ppt_id` DESC,
              `B`.`id` ASC
      ";      
      #pdo
      $SQL_ROWS="SELECT COUNT(*) AS `COUNTER` FROM ({$SQL}) a";
      $ra_counts=selectSQL_SAFE($SQL_ROWS,$PA); 
      $totals_rows=$ra_counts[0]['COUNTER'];
      $SQL.=sprintf(" LIMIT %d,%d",($page*$p),$p); 
       
      $ra=selectSQL_SAFE($SQL,$PA);
      $code=$POSTS['searchcode'];
      $mcode=explode(",",$code);
      for($i=0,$max_i=count($ra);$i<$max_i;$i++)
      {
        $ra[$i]['fake_like']="<a targe='_blank' href='javascript:;' onClick=\"like('{$ra[$i]['id']}');\">讚</a>";
        $ra[$i]['f_id']=$page*$p+$i;
        $ra[$i]['f_png']=
        sprintf(" 
          <center> 
          <img class='thepng' width='80' src='{$base_url}/inc/javascript/jquery/jquery-image-lazy-loading/images/grey.gif' data-original='{$base_url}/OUTPUT/png/%d/%s'> 
          <br> 
          <a href='{$base_url}/api.php?mode=download&ppt_id=%d&id=%d'>下載</a> 
          <br> 
          第 %d 頁 
          </center>",          
          $ra[$i]['ppt_id'],$ra[$i]['filename'], //圖片用的
          $ra[$i]['ppt_id'],$ra[$i]['page'], //下載用的
          $ra[$i]['page'] //which page
        );
        
        foreach($mcode as $v)
        {
          $v=trim($v);
          if(substr($v,0,1)=='+')
          {
            $v=substr($v,1);
          }
          $ra[$i]['contents']=str_ireplace($v,sprintf("<span class='find_word'>%s</span>",$v),$ra[$i]['contents']);
          $ra[$i]['keyword']=str_ireplace($v,sprintf("<span class='find_word'>%s</span>",$v),$ra[$i]['keyword']);
        }
        
        
        //這時才加上讚的按鈕
        $ra[$i]['contents'].=
        "<br><br>(<span id='like_{$ra[$i]['id']}'>{$ra[$i]['grades']}</span>){$ra[$i]['fake_like']}";
        
        
      }
      
      
      //ob_start();
      //$POSTS['searchcode']=urlencode($POSTS['searchcode']);
      //array_page($totals_rows,$page,$p,$px,"mode=searchcode&searchcode={$POSTS['searchcode']}&kind={$POSTS['kind']}",$mode='normal',$spandiv='');
      //$thepage=ob_get_contents();
      //ob_end_clean();
      
      $doc = ARRAY();
      foreach($ra as $v)
      {
        if( !isset($doc[$v['ppt_id']]) )
        {
          $doc[$v['ppt_id']]=ARRAY();            
        }
        array_push($doc[$v['ppt_id']],$v);
      }
      
      $OUTPUT=ARRAY();
      $OUTPUT['data']=$ra;
      $OUTPUT['Gdata']=$doc;
      //$OUTPUT['page']=$thepage;
      $OUTPUT['debug']=$SQL;
      $OUTPUT['debug_ra']=print_r($PA,true);
      $OUTPUT['counters']=count($ra);
      //echo json_encode($OUTPUT,true);        
    break;
  }
?>
<?php require "{$base_dir}/template/html.php"; ?>
<?php require "{$base_dir}/template/head.php"; ?>
<style>
.thepng{
  cursor:pointer;
  background-color:white;
  border:1px solid #000;
}
</style>
<script language="javascript">
  function like(id)
  {
    var new_id = myAjax("<?=$base_url;?>/api.php?mode=doLike&id="+id,"");
    
    $("#like_"+id).html(new_id);
  }
  $(document).ready(function(){
    /*$("#searchcode").unbind("keydown");
    $("#searchcode").keydown(function(event){      
      if(event.which==13)
      {
        $("#search_btn").trigger('click');        
      }
    });
    $("#search_btn").unbind("click");
    $("#search_btn").click(function(){
      doSearch(0);            
    });*/
    window['wh']=getWindowSize();
    $(".thepng").unbind("click");
    $(".thepng").click(function(){
      var tmp = "";
      tmp = sprintf("<div style='text-align:center;width:%dpx;height:%dpx;overflow:auto;'><img style='background-color:white;width='%s' src='%s'></div>",
      (window['wh']['width']*80/100 ),
      (window['wh']['height']*80/100 ),
      (window['wh']['width']*50/100 ),$(this).attr('src'));
      dialogOn(tmp,true,function(){        
      });
    });
    $("#searchcode").val("<?=jsAddSlashes(urldecode(htmlspecialchars_decode($POSTS['searchcode'])));?>");
    
    $("#search_btn").unbind("click");
    $("#search_btn").click(function(){
      $("#theform").submit();
    });
    
    $("#search_by_keywords_btn").unbind("click");
    $("#search_by_keywords_btn").click(function(){
      //語意搜尋
      var o = new Object();
      o['s']=$("#searchcode").val();
      var new_search_code = myAjax("<?=$base_url;?>/api.php?mode=before_search",o);
      //alert(new_search_code);
      $("#searchcode").val(new_search_code);
      $("#theform").submit();
    });
         
  });
</script>
</head>
<?php require "{$base_dir}/template/body.php"; ?>
<?php require "{$base_dir}/template/top.php"; ?>
<!--start-->
  <h2>簡報快查</h2>
  <center>
      <fieldset style="min-width:600px;display:inline;text-align:left;">
      <legend>別人的簡報好好用</legend>
        <form id="theform" action="?mode=searchcode" method="post">
          類　型：<select id="kind" name="kind">
            <option value="">ALL</option>
            <option value="ppt">ppt</option>
            <option value="pdf">pdf</option>
            <option value="doc">doc</option>
          </select>
          <br>
          計畫年度：<input type="text" id="年度" name="年度" placeholder="請輸入年度，如：2015">
          <br>
          計畫名稱：<input type="text" id="計畫名稱" name="計畫名稱" placeholder="請輸入計畫名稱">
          <br>
          簡報人：<input type="text" id="簡報人" name="簡報人" placeholder="請輸入簡報人">
          <br>
          製作人：<input type="text" id="author" name="author" placeholder="請輸入製作人">
          <br>
          是否延續型計畫：
          <select id="是否延續型計畫" name="是否延續型計畫">
            <option value="">不居</option>
            <option value="0">否</option>
            <option value="1">是</option>            
            <option value="2">其他</option>
          </select>
          <br>
          關鍵字：<input type="text" id="searchcode" name="searchcode" placeholder="請輸入查詢關鍵字啊...(可用半型逗號分格)" style="width:400px;">    
          <input type="button" value="查詢" id="search_btn">
          <input type="button" value="語意搜尋" id="search_by_keywords_btn">
        </form>
      </fieldset>
      <br>
      <br>
      <div id="output" style="text-align:left;">
        
      <?php
        if(isset($OUTPUT))
        {
          ?>
          <div align="right">
            找到的筆數：<?=$OUTPUT['counters'];?> 筆
          </div>
          <ul class="title_ul">
          <?php
          foreach($OUTPUT['Gdata'] as $k=>$v)
          {
            $ppt_info = pdo_get_assoc_from_id('ppt',$k);
            ${'是否有拿到計畫'}="";
            ${'是否延續型計畫'}="";
            switch($ppt_info['是否有拿到計畫'])
            {
              case '0':
                  ${'是否有拿到計畫'}="無";
                break;
              case '1':
                  ${'是否有拿到計畫'}="有";
                break;
              case '2':
                  ${'是否有拿到計畫'}="其他";
                break;
            }
            switch($ppt_info['是否延續型計畫'])
            {
              case '0':
                  ${'是否延續型計畫'}="否";
                break;
              case '1':
                  ${'是否延續型計畫'}="是";
                break;
              case '2':
                  ${'是否延續型計畫'}="其他";
                break;
            }
            $likes = ($ppt_info['download_counter']+$ppt_info['grades']);
            $mn = $ppt_info['orin_filename'];
            ?>
            <li> 
              <a href='#ppt_id_<?=$k;?>'><?=$mn;?>
                (<?=count($v);?>頁)
                (讚次數：<?=$likes;?>)
                <input type="button" value="詳細資料" onClick="$('#ppt_info_fieldset_<?=$ppt_info['id'];?>').toggle(); return false;">
              </a>
              <fieldset id="ppt_info_fieldset_<?=$ppt_info['id'];?>" style="display:none;min-width:500px;">
                <legend><?=$mn;?> 詳細資料</legend>                             
                檔案類型：<?=$ppt_info["kind"];?><br>
                標題<?=$ppt_info["title"];?><br>
                計畫名稱：<?=$ppt_info["計畫名稱"];?><br>
                年度：<?=$ppt_info["年度"];?><br>
                簡報人：<?=$ppt_info["簡報人"];?><br>
                簡報類別：<?=$ppt_info["簡報類別"];?><br>
                報告書類型：<?=$ppt_info["報告書類型"];?><br>
                是否有拿到計畫：<?=${'是否有拿到計畫'};?><br>
                競爭對手：<?=$ppt_info["競爭對手"];?><br>
                委員名單：<?=$ppt_info["委員名單"];?><br>
                評選心得：<?=$ppt_info["評選心得"];?><br>
                是否延續型計畫：<?=${'是否延續型計畫'};?><br>
                關鍵字：<?=$ppt_info["keyword"];?><br>
                原建立時間：<?=$ppt_info["create_datetime"];?><br>
                上傳時間：<?=$ppt_info["upload_datetime"];?><br>
                作者：<?=$ppt_info["author"];?><br>                           
              </fieldset>
            </li>
            <?php
          }
          ?>
          </ul>
          <?php
          
          foreach($OUTPUT['Gdata'] as $k=>$v)
          {
            $ppt_info = pdo_get_assoc_from_id('ppt',$k);
            $mn = $ppt_info['orin_filename'];
            $ppt_id = $ppt_info['ppt_id']; 
            echo "<div id='ppt_id_{$k}'><h2>{$mn}</h2>";
            echo print_table($v,
                                "f_id,f_png,contents",
                                "序號,圖片,內容",
                                "search_table"
                             );
            echo "</div>";
          }
        
          echo "<br>";
          
          //echo $OUTPUT['page'];
        }
      ?>
      </div>

  </center>
  
<!--end-->
<?php require "{$base_dir}/template/footer.php"; ?>
</body>
</html>