<?php
  //每分鐘把ppt、doc轉pdf的工具
  $PWD = dirname(__FILE__);
  require "{$PWD}/../inc/config.php";
  
  $SQL="SELECT 
            * 
            FROM `ppt` WHERE 
              1=1
              AND `pdf_status`='2' 
              AND `png_status`='0'  
            LIMIT 1";
  $ra = selectSQL($SQL);  
  for($i=0,$max_i=count($ra);$i<$max_i;$i++)
  {
    //狀態改成轉檔中
    $m=ARRAY();
    $m['png_status']='1';
    updateSQL('ppt',$m,"`id`='{$ra[$i]['id']}'");
    `rm -fr {$base_dir}/tmp/png/{$ra[$i]['id']}`; 
    `mkdir {$base_dir}/tmp/png/{$ra[$i]['id']} -p`;
    `cp {$base_dir}/OUTPUT/pdf/{$ra[$i]['id']}.pdf {$base_dir}/tmp/png/{$ra[$i]['id']}/{$ra[$i]['id']}.pdf`;
    `cd {$base_dir}/tmp/png/{$ra[$i]['id']} && convert {$ra[$i]['id']}.pdf {$ra[$i]['id']}.png`;
    
     $files = glob("{$base_dir}/tmp/png/{$ra[$i]['id']}/*.png");
     natcasesort($files);
     $files=array_values($files);     
     deleteSQL("ppt_items","`ppt_id`='{$ra[$i]['id']}'");
     
     for($j=0;$j<count($files);$j++)
     {
       $p = ($j+1);
       `cd {$base_dir}/tmp/png/{$ra[$i]['id']} && /usr/bin/pdftotext -f {$p} -l {$p} {$ra[$i]['id']}.pdf {$j}.txt`;
       $bn = basename($files[$j]);
       $m=ARRAY();
       $m['ppt_id']=$ra[$i]['id'];
       $m['filename']=$bn;
       $m['page']=($j+1);
       $m['contents']=file_get_contents("{$base_dir}/tmp/png/{$ra[$i]['id']}/{$j}.txt");
       $m['keyword']="";
       $m['kind']="";
       $m['grades']="0";
       insertSQL("ppt_items",$m);
     }
    
    `mkdir {$base_dir}/OUTPUT/png/{$ra[$i]['id']} -p`;
    `cp {$base_dir}/tmp/png/{$ra[$i]['id']}/*.png {$base_dir}/OUTPUT/png/{$ra[$i]['id']}/`;
    `rm -fr {$base_dir}/tmp/png/{$ra[$i]['id']}`;
    $m=ARRAY();
    $m['png_status']='2';
    updateSQL('ppt',$m,"`id`='{$ra[$i]['id']}'");     
  }