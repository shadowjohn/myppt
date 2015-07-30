<?php
  //每分鐘把ppt、doc轉pdf的工具
  $PWD = dirname(__FILE__);
  require "{$PWD}/../inc/config.php";
  
  $SQL="SELECT * FROM `ppt` WHERE `pdf_status`='0' LIMIT 1";
  $ra = selectSQL($SQL);  
  for($i=0,$max_i=count($ra);$i<$max_i;$i++)
  {
    //狀態改成轉檔中
    $m=ARRAY();
    $m['pdf_status']='1';
    updateSQL('ppt',$m,"`id`='{$ra[$i]['id']}'");
        
    `rm -fr {$base_dir}/tmp/pdf/{$ra[$i]['id']}`;
    `mkdir {$base_dir}/tmp/pdf/{$ra[$i]['id']} -p`;
    $sn = subname($ra[$i]['filename']);
    `cp {$base_dir}/upload/{$ra[$i]['filename']} {$base_dir}/tmp/pdf/{$ra[$i]['id']}/{$ra[$i]['id']}.{$sn}`;
    `cd {$base_dir}/tmp/pdf/{$ra[$i]['id']} && xvfb-run -e log -f log.txt oowriter -convert-to pdf:writer_pdf_Export {$ra[$i]['id']}.{$sn}`;
    `cp {$base_dir}/tmp/pdf/{$ra[$i]['id']}/{$ra[$i]['id']}.pdf {$base_dir}/OUTPUT/pdf/{$ra[$i]['id']}.pdf`;
    `chmod 777 {$base_dir}/tmp -R`;
    `chmod 777 {$base_dir}/OUTPUT -R`;
    `rm -fr {$base_dir}/tmp/pdf/{$ra[$i]['id']}`;
    
    $m=ARRAY();
    $m['pdf_status']='2';
    updateSQL('ppt',$m,"`id`='{$ra[$i]['id']}'");   
  }
  