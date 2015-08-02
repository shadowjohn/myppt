<?php require 'inc/config.php';
  $GETS_STRING="mode";
  $GETS=getGET_POST($GETS_STRING,'GET');
  switch($GETS['mode'])
  {
    case 'searchcode':   
      $POSTS_STRING="searchcode,kind";
      $POSTS=getGET_POST($POSTS_STRING,'REQUEST');
      $mSEARCHCODES = explode(",",$POSTS['searchcode']);
      $SQL_APPEND="";
      $SQL_KIND="";
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
       
      foreach($mSEARCHCODES as $v)
      {
        $v = strtoupper($v);
        if(substr($v,0,1)=="+")
        {
          $SQL_APPEND.=" AND UPPER(CONCAT(`B`.`contents`,`B`.`keyword`)) LIKE ? ";
          $v = substr($v,1);
        }
        else
        {
          $SQL_APPEND.=" OR UPPER(CONCAT(`B`.`contents`,`B`.`keyword`)) LIKE ? ";
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
              {$SQL_KIND}
              AND 
              (
                0 = 1
                {$SQL_APPEND} 
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
          <img class='thepng' width='80' src='{$base_url}/OUTPUT/png/%d/%s'> 
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
      
      
      ob_start();
      $POSTS['searchcode']=urlencode($POSTS['searchcode']);
      array_page($totals_rows,$page,$p,$px,"mode=searchcode&searchcode={$POSTS['searchcode']}&kind={$POSTS['kind']}",$mode='normal',$spandiv='');
      $thepage=ob_get_contents();
      ob_end_clean();
      
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
      $OUTPUT['page']=$thepage;
      $OUTPUT['debug']=$SQL;
      $OUTPUT['debug_ra']=print_r($PA,true);
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
    <form id="theform" action="?mode=searchcode" method="post">
        類　型：
        <select id="kind" name="kind">
          <option value="">ALL</option>
          <option value="ppt">ppt</option>
          <option value="pdf">pdf</option>
          <option value="doc">doc</option>
        </select><br>
        關鍵字：<input type="text" id="searchcode" name="searchcode" placeholder="請輸入查詢關鍵字啊...(可用半型逗號分格)" style="width:400px;">    
        <input type="button" value="查詢" id="search_btn">
        <input type="button" value="語意搜尋" id="search_by_keywords_btn">
      </form>
      <br>
      <br>
      <div id="output" style="text-align:left;">
        
      <?php
        if(isset($OUTPUT))
        {
          ?>
          <ul class="title_ul">
          <?php
          foreach($OUTPUT['Gdata'] as $k=>$v)
          {
            $ppt_info = pdo_get_assoc_from_id('ppt',$k);
            $likes = ($ppt_info['download_counter']+$ppt_info['grades']);
            $mn = $ppt_info['orin_filename'];
            ?>
            <li> 
              <a href='#ppt_id_<?=$k;?>'><?=$mn;?>
                (<?=count($v);?>頁)
                (讚次數：<?=$likes;?>)
              </a>
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
</body>
</html>