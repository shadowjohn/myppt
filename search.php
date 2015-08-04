<?php require 'inc/config.php'; 
  //$SQL = "SELECT DISTINCT `kind` FROM `ppt`";
  //$ra = selectSQL($SQL);
  //pre_print_r($ra);
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
  function doSearch(page){
    var code = trim($("#searchcode").val());
    var kind = trim($("#kind").val());
    if(code=="")
    {
      //alert("你這樣資料會太多啦...")
      //return;
    }
    page=(typeof(page)=="undefined")?"0":page;
    dialogOn("請稍候...",false,function(){
      var o =new Object();
      o['kind']=kind;
      o['searchcode']=code;
      myAjax_async("<?=$base_url;?>/api.php?mode=searchcode&page="+page,o,function(data){
        var jdata = json_decode(data,true);
        var mcode=explode(",",code);
        for(var i=0,max_i=count(jdata['data']);i<max_i;i++)
        {
          //找到的內容改成紅字
          jdata['data'][i]['f_id']=(page*<?=$p;?>)+(i+1);
          
          for(var v in mcode)
          {
            mcode[v]=trim(mcode[v]);
            if(substr(mcode[v],0,1)=='+')
            {
              mcode[v]=substr(mcode[v],1);
            }
            jdata['data'][i]['contents']=str_ireplace(mcode[v],sprintf("<span class='find_word'>%s</span>",mcode[v]),jdata['data'][i]['contents']);
            jdata['data'][i]['keyword']=str_ireplace(mcode[v],sprintf("<span class='find_word'>%s</span>",mcode[v]),jdata['data'][i]['keyword']);
          }
          //jdata['data'][i]['contents']=nl2br(jdata['data'][i]['contents']);
          
          jdata['data'][i]['f_png']=sprintf(" \
          <center> \
          <img class='thepng' width='80' src='<?=$base_url;?>/OUTPUT/png/%d/%s'> \
          <br> \
          <a href='<?=$base_url;?>/api.php?mode=download&ppt_id=%d&page=%d'>下載</a> \
          <br> \
          第 %d 頁 \
          </center>",          
          jdata['data'][i]['ppt_id'],jdata['data'][i]['filename'], //圖片用的
          jdata['data'][i]['ppt_id'],jdata['data'][i]['page'], //下載用的
          jdata['data'][i]['page'] //頁碼
          );
          jdata['data'][i]['f_ppt_download']=sprintf("<a href='<?=$base_url;?>/api.php?mode=download&ppt_id=%d&page=%d'>下載</a>",jdata['data'][i]['ppt_id'],jdata['data'][i]['page']);
        }
        var tmp = "";
        tmp = print_table(jdata['data'],
                            "f_id,f_png,contents",
                            "序號,圖片,內容",
                            "search_table"
                         );
        tmp += "<br>";
        
        //分頁的畫面
        tmp += jdata['page'];
        
                           
        $("#output").html(tmp);
        //alert(print_r(jdata,true));
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
        //分頁點到的控制 
        $(".pagination .num a").unbind("click");
        $(".pagination .num a").click(function(){        
          doSearch($(this).attr('req'));
          return false;
        });
        dialogOff();
      });
    });
  }
  function normal_ajax_jump_page(page)
  {
    doSearch(page);   
  }
  $(document).ready(function(){
    $("#searchcode").unbind("keydown");
    $("#searchcode").keydown(function(event){      
      if(event.which==13)
      {
        $("#search_btn").trigger('click');        
      }
    });
    $("#search_btn").unbind("click");
    $("#search_btn").click(function(){
      doSearch(0);            
    });
  });
</script>
</head>
<?php require "{$base_dir}/template/body.php"; ?>
<?php require "{$base_dir}/template/top.php"; ?>
<!--start-->
  <h2>簡報快查</h2>
  <center>
    類　型：
    <select id="kind" name="kind">
      <option value="">ALL</option>
      <option value="ppt">ppt</option>
      <option value="pdf">pdf</option>
      <option value="doc">doc</option>
    </select><br>
    關鍵字：<input type="text" id="searchcode" name="searchcode" placeholder="請輸入查詢關鍵字啊...(可用半型逗號分格)" style="width:400px;">    
    <input type="button" value="查詢" id="search_btn">
  <br>
  <br>
  <div id="output"></div>
  </center>
  
<!--end-->
<?php require "{$base_dir}/template/footer.php"; ?>
</body>
</html>