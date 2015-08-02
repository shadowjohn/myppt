<?php 
  require 'inc/config.php';
  $GETS_STRING="mode";
  $GETS=getGET_POST($GETS_STRING,'GET');
  switch($GETS['mode'])
  {
    case 'del_ppt':
      //刪除簡報
      $POSTS_STRING="id";
      $POSTS=getGET_POST($POSTS_STRING,'POST');
      $SQL="SELECT * FROM `ppt` WHERE `id`=? LIMIT 1";
      $ra = selectSQL_SAFE($SQL,ARRAY($POSTS['id']));
      if(count($ra)!=0)
      {      
        @unlink("{$base_dir}/upload/{$ra[0]['filename']}");
        @unlink("{$base_dir}/OUTPUT/pdf/{$POSTS['id']}");
        `rm -fr {$base_dir}/OUTPUT/png/{$POSTS['id']}`;
      }
      deleteSQL("ppt_items","`ppt_id`='{$POSTS['id']}'");
      deleteSQL("ppt","`id`='{$POSTS['id']}'");
      exit();
      break;
    case 'download':
      $GETS_STRING="ppt_id,page";
      $GETS=getGET_POST($GETS_STRING,'GET');
      $SQL="SELECT * FROM `ppt` WHERE `id`=? LIMIT 1";
      $ra = selectSQL_SAFE($SQL,ARRAY($GETS['ppt_id']));      
      download_file("{$base_dir}/upload/{$ra[0]['filename']}","{$ra[0]['orin_filename']}");
      exit();
      break;
    case 'searchcode':
        $POSTS_STRING="searchcode,kind";
        $POSTS=getGET_POST($POSTS_STRING,'POST');
        $mSEARCHCODES = explode(",",$POSTS['searchcode']);
        $SQL_APPEND="";
        $PA = ARRAY();
        foreach($mSEARCHCODES as $v)
        {
          $v = strtoupper($v);
          if(substr($v,0,1)=="+")
          {
            $SQL_APPEND.=" AND UPPER(CONCAT(`contents`,`keyword`)) LIKE ? ";
            $v = substr($v,1);
          }
          else
          {
            $SQL_APPEND.=" OR UPPER(CONCAT(`contents`,`keyword`)) LIKE ? ";
          }
          array_push($PA,"%{$v}%");
        }
        $SQL="
            SELECT * FROM `ppt_items`
              WHERE
                1=1
                AND 
                (
                  0 = 1
                  {$SQL_APPEND} 
                )
                
              ORDER BY 
                `ppt_id` DESC,
                `id` ASC
        ";      
        #pdo
        $SQL_ROWS="SELECT COUNT(*) AS `COUNTER` FROM ({$SQL}) a";
        $ra_counts=selectSQL_SAFE($SQL_ROWS,$PA); 
        $totals_rows=$ra_counts[0]['COUNTER'];
        $SQL.=sprintf(" LIMIT %d,%d",($page*$p),$p); 
         
        $ra=selectSQL_SAFE($SQL,$PA);
        
        ob_start();
        array_page($totals_rows,$page,$p,$px,$new_Link,$mode='normal_ajax',$spandiv='');
        $thepage=ob_get_contents();
        ob_end_clean();
        
        $OUTPUT=ARRAY();
        $OUTPUT['data']=$ra;
        $OUTPUT['page']=$thepage;
        echo json_encode($OUTPUT,true);
        exit();
      break;
  }