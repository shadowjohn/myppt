<?php 
  require 'inc/config.php';
  $GETS_STRING="mode";
  $GETS=getGET_POST($GETS_STRING,'GET');
  switch($GETS['mode'])
  {
    case 'before_search':
      $POSTS_STRING="s";
      $POSTS=getGET_POST($POSTS_STRING,'POST');
      
      $SQL="SELECT REPLACE(CONCAT(
                          `名詞`,'|',
                          `同義詞`,'|',
                          `技術相關`,'|',
                          `事件相關`,'|',
                          `物品相關`,'|',
                          `空間相關`,'|',
                          `人物相關`),'|',',')   as `output`
            FROM `keywords`
            WHERE 
              UPPER(CONCAT(
                          `名詞`,'|',
                          `同義詞`,'|',
                          `技術相關`,'|',
                          `事件相關`,'|',
                          `物品相關`,'|',
                          `空間相關`,'|',
                          `人物相關`)
              ) LIKE ? 
            LIMIT 1 ";
      $SCODE = "%{$POSTS['s']}%";
      $ra = selectSQL_SAFE($SQL,ARRAY($SCODE));
      if(count($ra)==0)
      {
        echo $POSTS['s'];
      }
      else
      {
        $ra[0]['output'] = str_replace("\n","",$ra[0]['output']);
        $ra[0]['output'] = str_replace_deep(",,",",",$ra[0]['output']);
        $m = explode(",",$ra[0]['output']);
        for($i=0,$max_i=count($m);$i<$max_i;$i++)
        {
          if(trim($m[$i])=="")
          {
            unset($m[$i]);
          }
        }
        $m=array_values($m);
        echo implode(",",$m);
      }
      exit();
      break;
    case 'doLike':
      $GETS_STRING="id";
      $GETS=getGET_POST($GETS_STRING,'GET');
      
      
      $ppt_items_info = pdo_get_assoc_from_id('ppt_items',$GETS['id']);
      $m=ARRAY();
      $m['grades']=($ppt_items_info['grades']+1);
      updateSQL('ppt_items',$m,"`id`='{$GETS['id']}'");
      echo $m['grades'];     
                
      $ppt_info = pdo_get_assoc_from_id('ppt',$ppt_items_info['ppt_id']);
      $m=ARRAY();
      $m['grades']=($ppt_info['grades']+1);
      updateSQL('ppt',$m,"`id`='{$ppt_info['id']}'");      
  
      exit();
      break;
    case 'for_elly_upload':
      $POSTS_STRING="ppt_id,page,b64_contents";
      $POSTS=getGET_POST($POSTS_STRING,'POST');
      $kind = pdo_get_field_from_id('ppt',$$POSTS['ppt_id'],'kind');
      $fn = sprintf("{$POSTS['ppt_id']}-{$POSTS['page']}.{$kind}");
      
      file_put_contents("{$base_dir}/OUTPUT/png/{$POSTS['ppt_id']}/{$fn}",
                  base64_decode($POSTS['b64_contents']));
      $m=ARRAY();
      $m['has_single_page']="1";
      updateSQL('ppt',$m,"`ppt_id`='{$POSTS['ppt_id']}'");  
      exit();
      break;
    case 'for_elly_webservice':
      $SQL="select * from `ppt` where `has_single_page`='0'";
      $ra = selectSQL($SQL);
      for($i=0,$max_i=count($ra);$i<$max_i;$i++)
      {
        $ra[$i]['ppt_download']="{$base_url}/upload/{$ra[$i]['filename']}";
      }
      echo json_format(json_encode($ra));
      exit();
      break;
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
      $GETS_STRING="ppt_id,id";
      $GETS=getGET_POST($GETS_STRING,'GET');
      $SQL="SELECT * FROM `ppt` WHERE `id`=? LIMIT 1";
      $ra = selectSQL_SAFE($SQL,ARRAY($GETS['ppt_id']));      
      download_file("{$base_dir}/upload/{$ra[0]['filename']}","{$ra[0]['orin_filename']}");
      
      $ppt_info = pdo_get_assoc_from_id('ppt',$GETS['ppt_id']);
      $m=ARRAY();
      $m['grades']=($ppt_info['grades']+1);
      updateSQL('ppt',$m,"`id`='{$GETS['ppt_id']}'");
      
      $ppt_items_info = pdo_get_assoc_from_id('ppt_items',$GETS['id']);
      $m=ARRAY();
      $m['grades']=($ppt_info['grades']+1);
      updateSQL('ppt_items',$m,"`id`='{$GETS['id']}'");
      
      exit();
      break;
    case 'searchcode':
        $POSTS_STRING="searchcode,kind";
        $POSTS=getGET_POST($POSTS_STRING,'POST');
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
            SELECT `B`.* FROM `ppt` as `A`,`ppt_items` as `B`
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
                `B`.`ppt_id` DESC,
                `B`.`id` ASC
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
        echo json_encode($OUTPUT,true);
        exit();
      break;
  }