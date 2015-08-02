//複製URL地址
var userAgent = navigator.userAgent.toLowerCase();
var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
var is_ie = (userAgent.indexOf('msie') != -1 && !is_opera) && userAgent.substr(userAgent.indexOf('msie') + 5, 3);
is_ie=(is_ie==false)?false:true;
var is_safari = (userAgent.indexOf('webkit') != -1 || userAgent.indexOf('safari') != -1);
/*
  //iframe包含
  if (top.location != location) {
  	top.location.href = location.href;
  }

  function $(id) {
	 return document.getElementById(id);
  }
*/

function setCopy(_sTxt){
	if(is_ie) {
		clipboardData.setData('Text',_sTxt);
		alert ("網址「"+_sTxt+"」\n已經複製到您的剪貼板中\n您可以使用Ctrl+V快捷鍵粘貼到需要的地方");
	} else {
		prompt("請複製網站地址:",_sTxt); 
	}
}

function fullToHalf($value)
{
  $databases=' \
    { \
      "＋":"+", \
      "－":"-", \
      "＊":"*", \
      "／":"/", \
      "０":"0", \
      "１":"1", \
      "２":"2", \
      "３":"3", \
      "４":"4", \
      "５":"5", \
      "６":"6", \
      "７":"7", \
      "８":"8", \
      "９":"9" \
    } \
  ';
  $m=json_decode($databases);
  for(var k in $m)
  {
    $value=str_replace(k,$m[k],$value);
  }
  return $value;
}

String.prototype.trim=function(){return this.replace(/(^\s*)|(\s*$)/g,"")};

function handleEnter (field, event) {
   // 按 enter 不會 submit onkeypress="return handleEnter(this, event);"         
		var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
		if (keyCode == 13) {
			var i;
			for (i = 0; i < field.form.elements.length; i++)
				if (field == field.form.elements[i])
					break;
			i = (i + 1) % field.form.elements.length;
			field.form.elements[i].focus();
			return false;
		} 
		else
		return true;
	}  
	
function replaceAll(strOrg,strFind,strReplace){
 var index = 0;
 while(strOrg.indexOf(strFind,index) != -1){
  strOrg = strOrg.replace(strFind,strReplace);
  index = strOrg.indexOf(strFind,index);
 }
 return strOrg
} 
/*document.getElementsByName  =  function(name,  tag)
{
        if(!tag){
                tag  =  '*';
        }
        var  elems  =  document.getElementsByTagName(tag);
        var  res  =  []
        for(var  i=0;i<elems.length;i++){
                att  =  elems[i].getAttribute('name');
                if(att  ==  name)  {
                        res.push(elems[i]);
                }
        }
        return  res;
}*/
/*var isOpera, isIE = false;
if(typeof(window.opera) != 'undefined')
{
  isOpera = true;
}
if(!isOpera && navigator.userAgent.indexOf('Internet Explorer'))
{
  isIE = true;
  if(isIE)
  {
    //var document.getElementsByName = document.getElementsByName;
    document.getElementsByName = function(name)
    {
      var temp = document.all[name];
      var matches = [];
      for(var i=0;i<temp.length;i++)
      {
        if(temp[i].name == name)
        {
          matches.push(temp[i]);
        }
      }
      return matches;  
    }
  }
}*/
function execInnerScript_googleapi(innerhtml)
{  
  var temp=innerhtml.replace(/\n|\r/g,"");
  var regex=/<script.+?<\/script>/gi;
  var arr=temp.match(regex);  
  if(arr)
  {   
    for(var iiiiiiiiii_iii=0;iiiiiiiiii_iii<arr.length;iiiiiiiiii_iii++)
    {      
      var temp1=arr[iiiiiiiiii_iii];      
      var reg=new RegExp("^<script(.+?)>(.+)<\/script>$","gi");
      reg.test(temp1);                  
      eval(RegExp.$2);          
    }
  }  
}
  // 修改自 AJAX: Getting Started - MDC
  function makeRequest_googleapi(url,input,out_span) {
    var http_request = false;

    if (window.XMLHttpRequest) { // Mozilla, Safari,...
      http_request = new XMLHttpRequest();
    } else if (window.ActiveXObject) { // IE
      try {
        http_request = new ActiveXObject("Msxml2.XMLHTTP");
      } catch (e) {
        try {
          http_request = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {}
      }
    }

    if (!http_request) {
      alert('Giving up :( Cannot create an XMLHTTP instance');
      return false;
    }
    // 定義事件處理函數為 alterContents()
    http_request.onreadystatechange = function() { 
                                      alertContents_googleapi(http_request,input,out_span); };
    //http_request.open('GET', url, true);

    
    
    http_request.open('POST', url, true);
    http_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    http_request.send(input);
    //http_request.send(null);
    
  }

  function alertContents_googleapi(http_request,input,out_span) {
    if (http_request.readyState == 4)
    {
      if (http_request.status == 200) {
        var mesg =http_request.responseText;

        //document.getElementById(out_span).innerHTML=mesg;
        out_span=replaceHtml(out_span,mesg);
        execInnerScript_googleapi(mesg);             
      } else {
        //alert('There was a problem with the request.');
      }

    }
    else
    {
      //document.getElementById(out_span).innerHTML="<center></center>";
    }
  }

function  execInnerScript(innerhtml)
{
var  temp=innerhtml.replace(/\n|\r/g,"");
var  regex=/<script.+?<\/script>/gi;
var  arr=temp.match(regex);
if(arr)
{
for(var  iiiiiiiiii_iii=0;iiiiiiiiii_iii<arr.length;iiiiiiiiii_iii++)
{
var  temp1=arr[iiiiiiiiii_iii];
var  reg=new  RegExp("^<script(.+?)>(.+)<\/script>$","gi");
reg.test(temp1);
eval(RegExp.$2);
}
}
}

  // 修改自 AJAX: Getting Started - MDC
  function makeRequest(url,input,out_span) {
    var http_request = false;

    if (window.XMLHttpRequest) { // Mozilla, Safari,...
      http_request = new XMLHttpRequest();
    } else if (window.ActiveXObject) { // IE
      try {
        http_request = new ActiveXObject("Msxml2.XMLHTTP");
      } catch (e) {
        try {
          http_request = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {}
      }
    }

    if (!http_request) {
      alert('Giving up :( Cannot create an XMLHTTP instance');
      return false;
    }
    // 定義事件處理函數為 alterContents()
    http_request.onreadystatechange = function() { 
                                      alertContents(http_request,input,out_span); };
    //http_request.open('GET', url, true);

    
    
    http_request.open('POST', url, true);
    http_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    http_request.send(input);
    //http_request.send(null);
    
  }
  function alertContents(http_request,input,out_span) {
    if (http_request.readyState == 4)
    {
      if (http_request.status == 200) {
        var mesg =http_request.responseText;
        //document.getElementById(out_span).innerHTML=mesg;
        replaceHtml(out_span,mesg);
        execInnerScript(mesg);            
      } else {
        //alert('There was a problem with the request.');
      }

    }
    else
    {
      //document.getElementById(out_span).innerHTML="<center></center>";
      //out_span=replaceHtml(out_span,document.getElementById(out_span).innerHTML);
    }
  }  

  
  function getWindowSize(){
    var myWidth = 0, myHeight = 0;
    if( typeof( window.innerWidth ) == 'number' ) {
      //Non-IE
      myWidth = window.innerWidth;
      myHeight = window.innerHeight;
    } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
      //IE 6+ in 'standards compliant mode'
      myWidth = document.documentElement.clientWidth;
      myHeight = document.documentElement.clientHeight;
    } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
      //IE 4 compatible
      myWidth = document.body.clientWidth;
      myHeight = document.body.clientHeight;      
    }
    var a=new Object();
    a['width']=myWidth;
    a['height']=myHeight;
    return a;
  }
  
/*function getScrollXY() {
  var scrOfX = 0, scrOfY = 0;
  if( typeof( window.pageYOffset ) == 'number' ) {
    //Netscape compliant
    scrOfY = window.pageYOffset;
    scrOfX = window.pageXOffset;
  } else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
    //DOM compliant
    scrOfY = document.body.scrollTop;
    scrOfX = document.body.scrollLeft;
  } else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
    //IE6 standards compliant mode
    scrOfY = document.documentElement.scrollTop;
    scrOfX = document.documentElement.scrollLeft;
  }
  return [ scrOfX, scrOfY ];
} */ 
function onKeyPressCode(e)
{
  var key =  window.event ? e.keyCode : e.which;
  var keychar = key;
  return keychar;
}


function replaceHtml(el, html) {
	var oldEl = typeof el === "string" ? document.getElementById(el) : el;
	/*@cc_on // Pure innerHTML is slightly faster in IE
		oldEl.innerHTML = html;
		return oldEl;
	@*/
	var newEl = oldEl.cloneNode(false);
	newEl.innerHTML = html;
	oldEl.parentNode.replaceChild(newEl, oldEl);
	/* Since we just removed the old element from the DOM, return a reference
	to the new element, which can be used to restore variable references. */
	return newEl;
}

/*
	//給jQuery擴充的 cookie 功能
		http://www.stilbuero.de/2006/09/17/cookie-plugin-for-jquery/

	jQuery操作cookie的插件,大概的使用方法如下

	设置cookie的值
			$.cookie('the_cookie', ‘the_value');
	新建一个cookie 包括有效期 路径 域名等
			$.cookie('the_cookie', ‘the_value', {expires: 7, path: ‘/', domain: ‘jquery.com', secure: true});
	新建cookie
		  $.cookie('the_cookie', ‘the_value');
	删除一个cookie
		  $.cookie('the_cookie', null);

*/
jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        var path = options.path ? '; path=' + options.path : '';
        var domain = options.domain ? '; domain=' + options.domain : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};
function myAjaxGETOnly(url)
{
  var tmp = $.ajax({
      url: url,
      type: "GET",      
      //crossDomain :true,
      dataType: 'html',
      async: false
   }).responseText;
  return tmp;
}
//我的ajax
function myAjax(url,postdata)
{
  var tmp = $.ajax({
      url: url,
      type: "POST",
      data: postdata,
      timeout:999999,
      dataType: 'html',
      //crossDomain:true,
      async: false
   }).responseText;
  return tmp;
}
function ajax_post_encode(data)
{
  return encodeURIComponent(data);
}
function myAjax_async(url,postdata,func)
{
  $.ajax({
      url: url,
      type: "POST",
      data: postdata,
      timeout:999999,
      async: true,
      dataType: 'html',
      success: function(html){       
        func(html);        
      }
  });  
}
function my_loadScript(url)
{
  $.ajax({
      url:url,
      async: false,
      type: "POST",
      data: '',  
      timeout: 30*1000
  }).responseText;
}
//自然滑動機
function div_motion(domid)
{
	var pre_name="3WA_"+new Date().getTime();
	
    window[pre_name+'paperdown']=0;
    window[pre_name+'motion']=0;
    $("#"+domid).mousedown(function(event){
      if(window[pre_name+'paperdown']!=0)
      {		
		  $("#"+domid).stop();
	  }
      window[pre_name+'startmovetime']=new Date().getTime();
      window[pre_name+'paperdown']=1;
      window[pre_name+'moveX']=event.pageX;
      window[pre_name+'moveY']=event.pageY;
      window[pre_name+'motion']=0;                          
    });
    $("#"+domid).mouseup(function(){
	   window[pre_name+'endmovetime']=new Date().getTime();
	   window[pre_name+'lastX']=this.scrollLeft;
	   window[pre_name+'lastY']=this.scrollTop;
	   var orz=window[pre_name+'endmovetime']-window[pre_name+'startmovetime'];       
	   if(orz>=15){
		 orz=0;    
		 window[pre_name+'paperdown']=0; 
		 window[pre_name+'motion']=0;    
	   }
	   else
	   {
		 orz=15-orz;                                
		 window[pre_name+'motion']=1;
		 $("#"+domid).animate({ 
		   'scrollLeft':(window[pre_name+'lastX']+(window[pre_name+'lastX']-window[pre_name+'moveSX'])/0.1),
		   'scrollTop':(window[pre_name+'lastY']+(window[pre_name+'lastY']-window[pre_name+'moveSY'])/0.05)
		 },{
			duration:orz*100,
			query:false,
			complete:function(){
			window[pre_name+'paperdown']=0;
			window[pre_name+'motion']=0;			                 					 	
		   }
		 });
	   }   
    });
    $("#"+domid).mousemove(function(event){
      if(window[pre_name+'paperdown']==1&&window[pre_name+'motion']==0)
      {
        window[pre_name+'startmovetime']=new Date().getTime();
        window[pre_name+'moveSX']=this.scrollLeft;
        window[pre_name+'moveSY']=this.scrollTop;
                
        this.scrollLeft-=(event.pageX-window[pre_name+'moveX']);
        this.scrollTop-=(event.pageY-window[pre_name+'moveY']);
        window[pre_name+'moveX']=event.pageX;
        window[pre_name+'moveY']=event.pageY;

        /*if(this.scrollTop==0)
        {
			this.scrollTop=parseInt(str_replace('px','',this.scrollHeight))-parseInt(str_replace('px','',$("#TABLE_TR").css('height')));			
		}
		if(this.scrollTop+parseInt(str_replace('px','',$("#TABLE_TR").css('height')))-2==parseInt(str_replace('px','',this.scrollHeight)))
		{
			this.scrollTop=1;
		}*/
      }
    });
}

function disableEnterKey(e)
{
     var key;

     if(window.event)
          key = window.event.keyCode;     //IE
     else
          key = e.which;     //firefox

     if(key == 13)
          return false;
     else
          return true;
}

function comment(input,width,height)
{  
  $("#mycolorbox").html(input).dialog({
    'width':width,
    'height':height
  });
}

function ValidEmail(emailtoCheck)
{
  //  email
  //  規則:  1.只有一個  "@"
  //              2.網址中,  至少要有一個".",  且不能連續出現
  //              3.不能有空白
  var  regExp = /^[^@^\s]+@[^\.@^\s]+(\.[^\.@^\s]+)+$/;
  if(emailtoCheck.match(regExp))
    return true;
  else
    return false;
}
function getCheckBox_req(dom_name)
{
  //return array
    var arr = new Array();
    var doms = $("input[name='"+dom_name+"']:checked");
    for (i = 0, max_i = doms.size(); i < max_i; i++) {
        array_push(arr, doms.eq(i).attr('req'));
    }    
    return arr;
}
function getCheckBox_val(dom_name) {
    //return array
    /*var arr = new Array();
    for (var i = 0, max_i = $($("*[name='" + dom_name + "']")).size(); i < max_i; i++) {
        if ($($("*[name='" + dom_name + "']")[i]).prop('checked')) {
            array_push(arr, $($("*[name='" + dom_name + "']")[i]).val());
        }
    }*/

    var arr = new Array();
    var doms = $("input[name='"+dom_name+"']:checked");
    for (i = 0, max_i = doms.size(); i < max_i; i++) {
        array_push(arr, doms.eq(i).val());
    }    
    return arr;
}
function ValidPhone(phonenum)
{
  //格式為 09xx-xxxxxx
  var tel=/^(09)\d{2}-\d{6}$/;
  if(!tel.test(phonenum))
  {    
    return false;
  } 
  else
  {
    return true;
  }
}
function ValidChineseEnglish(input)
{
  //格式為 中文、英文
  var check=/^[\u4E00-\u9FA5A-Za-z]+$/;
  if(!check.test(input))
  {    
    return false;
  } 
  else
  {
    return true;
  }
}
function ValidEnglishDigital(input)
{
  //格式為 英數字
  var check=/^[a-zA-Z0-9]+$/;
  if(!check.test(input))
  {    
    return false;
  } 
  else
  {
    return true;
  }
}
function ValidEnglishDigitalWordNotSpaceAnd(input)
{
  //格式為 任何英數字符號，但不能為　空白、&
  var check=/^[^ ^&\fa-zA-Z0-9]+$/;
  if(!check.test(input))
  {    
    return false;
  } 
  else
  {
    return true;
  }
}
jQuery.fn.outerHTML = function(s) {
return (s) ? $(this).replaceWith(s) : $(this).clone().wrap('<p>').parent().html();
} 

//scroll page to id , 如 #id
function Animate2id(dom){
    var animSpeed=800; //animation speed
    var easeType="easeInOutExpo"; //easing type
    if($.browser.webkit){ //webkit browsers do not support animate-html
        $("body").stop().animate({scrollTop: $(dom).offset().top}, animSpeed, easeType,function(){
          $(dom).focus();
        }
        );
    } else {
        $("html").stop().animate({scrollTop: $(dom).offset().top}, animSpeed, easeType,function(){
          $(dom).focus();
        }
        );
    }
}
function windows_status()
{
  $m=new Array();
  $m['scrollTop']=$(window).scrollTop();
  $m['scrollLeft']=$(window).scrollLeft();
  $m['clientWidth']=$(document.body)[0].clientWidth;
  $m['clientHeight']=$(document.body)[0].clientHeight;
  return $m;
}
function html_multiselect_set_value(domid,value_string)
{
  var selected = explode(",",value_string);  
  var obj=$('#'+domid);
  for (var i in selected) {
    var val=selected[i];    
    obj.find('option:[value='+val+']').attr('selected',1);
  }           
}


  //修正手機滑動的效果
	function touchHandler(event)
	{
	 var touches = event.changedTouches,
	    first = touches[0],
	    type = "";

	     switch(event.type)
	{
	    case "touchstart": type = "mousedown"; break;
	    case "touchmove":  type="mousemove"; break;        
	    case "touchend":   type="mouseup"; break;
	    default: return;
	}
	var simulatedEvent = document.createEvent("MouseEvent");
	simulatedEvent.initMouseEvent(type, true, true, window, 1,
	                          first.screenX, first.screenY,
	                          first.clientX, first.clientY, false,
	                          false, false, false, 0/*left*/, null);

	first.target.dispatchEvent(simulatedEvent);
	event.preventDefault();
	}

	function mouse_init()
	{
	   document.addEventListener("touchstart", touchHandler, true);
	   document.addEventListener("touchmove", touchHandler, true);
	   document.addEventListener("touchend", touchHandler, true);
	   document.addEventListener("touchcancel", touchHandler, true);    
	}
  function size_hum_read($size){
      /* Returns a human readable size */
  	$size = parseInt($size);
    var $i=0;
    var $iec = new Array();
    var $iec_kind="B,KB,MB,GB,TB,PB,EB,ZB,YB";
    $iec=explode(',',$iec_kind);
    while (($size/1024)>1) {
      $size=$size/1024;
      $i++;
    }
    return sprintf("%s%s",substr($size,0,strpos($size,'.')+4),$iec[$i]);
  }
	$(document).ready(function() {
		//mouse_init();
		
	});
  function plus_or_minus_one_month($year_month,$kind)
  {
    //$year_month 傳入值格式為 2011-01
    //$kind 看是 '+' or '-'
    //回傳格式為 2011-01  
    switch($kind)
    {
      case '+':
          return date('Y-m',strtotime("+1 month",strtotime($year_month)));
        break;
      case '-':
          return date('Y-m',strtotime("-1 month",strtotime($year_month)));
        break;
    }  
  }
  function my_ids_mix(ids)
  {
    var m=new Array();
    m=explode(",",ids);
    var data=new Array();    
    for(i=0,max_i=m.length;i<max_i;i++)
    {
      array_push(data,m[i]+"="+encodeURIComponent($("#"+m[i]).val()));
    }
    return implode('&',data);
  }  
  function my_names_mix(indom)
  {
    var m=new Array();
    var names=$(indom).find('*[req="group[]"]');    
    for(i=0,max=names.length;i<max;i++)
    {
      array_push(m,$(names[i]).attr('name')+"="+encodeURIComponent($(names[i]).val()));
    }
    return implode('&',m);
  }

function anti_right_click()
{
  //鎖右鍵防盜
  document.onselectstart = function(){return false;}
  document.ondragstart = function(){return false;}
  document.oncontextmenu = function(){return false;}
  if (document.all)
      document.body.onselectstart = function() { return false; };
  else {
      $('body').css('-moz-user-select', 'none');
      $('body').css('-webkit-user-select', 'none');
  }
  document.onmousedown = clkARR_;
  document.onkeydown = clkARR_;
  document.onkeyup = clkARRx_;
  window.onmousedown = clkARR_;
  window.onkeydown = clkARR_;
  window.onkeyup=clkARRx_;
  
  var clkARRCtrl = false;
  
  function clkARRx_(e) {
      var k = (e) ? e.which : event.keyCode;
      if (k==17) clkARRCtrl = false;
  }
  
  function clkARR_(e) {
      var k = (e) ? e.which : event.keyCode;
      var m = (e) ? (e.which==3) : (event.button==2);
      if (k==17) clkARRCtrl = true;
      if (m || clkARRCtrl && (k==67 || k==83))
          alert((typeof(clkARRMsg)=='string') ? clkARRMsg : '-版權所有-請勿複製-');
  }   
}
$.fn.center = function () {
    this.css("position","absolute");
    this.css("top", ( $(window).height() - this.height() ) / 2+$(window).scrollTop() + "px");
    this.css("left", ( $(window).width() - this.width() ) / 2+$(window).scrollLeft() + "px");
    return this;
}

function dialogOn(message,isTouchOutSideClose,functionAction)
{  
	$.mybox({
    is_background_touch_close:isTouchOutSideClose,
		message : message,
		css : {
      'border' : '2px solid #fff',
			'background-color' : '#fff',
			'color' : '#000',
			'padding' : '15px'
		},		
		onBlock : function() {      
			functionAction();      
		}						
	});
}
function dialogOff()
{
  $.unmybox();
}
function basename(filepath)
{
  m=explode("/",filepath);
  mdata = explode("?",end(m));  
  return mdata[0];
}
function mainname(filepath)
{
  filepath = basename(filepath);
  mdata=explode(".",filepath);
  return mdata[0];
}
function subname(filepath)
{
  filepath = basename(filepath);
  m=explode(".",filepath);
  return end(m);
} 
function getext($s){	return strtolower(subname($s));}
function isvideo($file){	if(in_array(getext($file),new Array('mpg','mpeg','avi','rm','rmvb','mov','wmv','mod','asf','m1v','mp2','mpe','mpa','flv','3pg','vob'))){		return true;	}	return false;} 
function isdocument($file){	if(in_array(getext($file),new Array('docx','odt','odp','ods','odc','csv','doc','txt','pdf','ppt','pps','xls'))){		return true;	}	return false;} 
function isimage($file){	if(in_array(getext($file),new Array('jpg','bmp','gif','png','jpeg','tiff','tif','psd'))){		return true;	}	return false;} 
function isspecimage($file){	if(in_array(getext($file),new Array('tiff','tif','psd'))){		return true;	}	return false;}
function isweb($file){	if(in_array(getext($file),new Array('htm','html'))){		return true;	}	return false;} 
function iscode($file){	if(in_array(getext($file),new Array('c','cpp','h','pl','py','php','phps','asp','aspx','css','jsp','sh','shar'))){		return true;	}	return false;}
function dialogColorBoxOn(message,isTouchOutSideClose,func){
  //$(this).colorbox.resize();
  isTouchOutSideClose=(isTouchOutSideClose==='boolean')?false:isTouchOutSideClose;
  if($("#mycolorbox").size()==0)
  {
    $("body").append("<div id='mycolorbox' style='display:none;'></div>");
  }
  $("#mycolorbox").colorbox({
    html:message,
    open:true,
    overlayClose:isTouchOutSideClose,
    onComplete: func 
  });
}
function dialogColorBoxOff(){
  if($("#mycolorbox").size()!=0){
    $("#mycolorbox").colorbox.close();
  }
}
function colorToRgb(data) {
    data=strtolower(data);
    data=str_replace("#","",data);
    data=str_replace("rgb(","",data);
    data=str_replace(")","",data);
    var test=explode(',',data);
    var m = new Array();
    if(test.length==3)
    {
      
      m['r']=test[0];
      m['g']=test[1];
      m['b']=test[2];       
    }
    else
    {
      var bigint = parseInt(data, 16);
      var r = (bigint >> 16) & 255;
      var g = (bigint >> 8) & 255;
      var b = bigint & 255;      
      m['r']=r;
      m['g']=g;
      m['b']=b;
    }
    return m;
}
function hexToRgb(hex) {
    var bigint = parseInt(hex, 16);
    var r = (bigint >> 16) & 255;
    var g = (bigint >> 8) & 255;
    var b = bigint & 255;
    var m = new Array();
    m['r']=r;
    m['g']=g;
    m['b']=b;
    return m;
}
function rgbToHex(color) {
    if (color.substr(0, 1) === "#") {
        return color;
    }
    var nums = /(.*?)rgb\((\d+),\s*(\d+),\s*(\d+)\)/i.exec(color),
        r = parseInt(nums[2], 10).toString(16),
        g = parseInt(nums[3], 10).toString(16),
        b = parseInt(nums[4], 10).toString(16);
    return "#"+ (
        (r.length == 1 ? r +"0" : r) +
        (g.length == 1 ? g +"0" : g) +
        (b.length == 1 ? b +"0" : b)
    );
}
  function is_string_like($data,$find_string){
/*
  is_string_like($data,$fine_string)

  $mystring = "Hi, this is good!";
  $searchthis = "%thi% goo%";

  $resp = string_like($mystring,$searchthis);


  if ($resp){
     echo "milike = VERDADERO";
  } else{
     echo "milike = FALSO";
  }

  Will print:
  milike = VERDADERO

  and so on...

  this is the function:
*/
    $tieneini=0;
    if($find_string=="") return 1;
    $vi = explode("%",$find_string);
    $offset=0;
    for($n=0,$max_n=count($vi);$n<$max_n;$n++){
        if($vi[$n]== ""){
            if($vi[0]== ""){
                   $tieneini = 1;
            }
        } else {
            $newoff=strpos($data,$vi[$n],$offset);
            if($newoff!==false){
                if(!$tieneini){
                    if($offset!=$newoff){
                        return false;
                    }
                }
                if($n==$max_n-1){
                    if($vi[$n] != substr($data,strlen($data)-strlen($vi[$n]), strlen($vi[$n]))){
                        return false;
                    }

                } else {
                    $offset = $newoff + strlen($vi[$n]);
                 }
            } else {
                return false;
            }
        }
    }
    return true;
  }
function lockRight(){
  document.onselectstart = function(){return false;}
  document.ondragstart = function(){return false;}
  document.oncontextmenu  =  function(){
    var userAgent = navigator.userAgent.toLowerCase();
    var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
    var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
    var is_ie = (userAgent.indexOf('msie') != -1 && !is_opera) && userAgent.substr(userAgent.indexOf('msie') + 5, 3);
    var is_safari = (userAgent.indexOf('webkit') != -1 || userAgent.indexOf('safari') != -1);
    if (is_ie!=false){
      // IE
      window.event.returnValue=false;
    }
    return  false;
  }
}
/*function is_url(s) {
  var regexp = /http:\/\/[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}/;
  return regexp.test(s);
}*/
/*function is_url(value) {
    var urlregex = new RegExp("^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)");
    if (urlregex.test(value)) {
        return (true);
    }
    return (false);
}*/
function is_url(s) {
  var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
  return regexp.test(s);
}
function blockIE678(){
  var userAgent = navigator.userAgent.toLowerCase();
  if(
    userAgent.indexOf('msie 8.0')!=-1 ||
    userAgent.indexOf('msie 7.0')!=-1 ||
    userAgent.indexOf('msie 6.0')!=-1
  )
  {
    //ie6 7 8
    dialogOn("您的瀏覽器太古老了，請升級到 IE9 或 Firefox、Google Chrome...<br><br><center><input type='button' value='離開' onClick=\"location.href='http://3wa.tw';\"></center>",function(){
      
    });
    
  }
}
function distance(lat1,lon1,lat2,lon2) {
	//二個經緯度換算距離
  var R = 6371; // km (change this constant to get miles)
  var dLat = (lat2-lat1) * Math.PI / 180;
  var dLon = (lon2-lon1) * Math.PI / 180;
  var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
  Math.cos(lat1 * Math.PI / 180 ) * Math.cos(lat2 * Math.PI / 180 ) *
  Math.sin(dLon/2) * Math.sin(dLon/2);
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
  var d = R * c;
  if (d>1) return d.toFixed(2)+"km";
  else if (d<=1) return (d*1000).toFixed(2)+"m";
  return d;
}
function canvas_scale(base64_data,nw,nh)
{
  //讀入 base64_data 會作出 canvas tag
  //接受格式 ... 如 dom : $("#a_img")[0] 或 document.getElementById('a_img') 、base64
  //alert(typeof(base64_data));    
  var _t = new Date().getTime();
  //讀入 base64_data 會作出 canvas tag
  if(typeof(base64_data)=="object")
  {
    //轉入 canvas
    var tmp_canvas = "canvas_scale_tmp_canvas_"+_t;
    var w = $(base64_data).width();
    var h = $(base64_data).height();  
    $("body").append("<canvas id='"+tmp_canvas+"' width='"+w+"' height='"+w+"'></canvas>");
    var canvas = document.getElementById(tmp_canvas);
    var ctx = canvas.getContext('2d');            
    ctx.drawImage(base64_data, 0, 0, w, h);    
    base64_data = $("#"+tmp_canvas)[0].toDataURL();      
    $("#"+tmp_canvas).remove();
  }
  if(base64_data.substr(0,10).toLowerCase() != "data:image")
  {
     base64_data = "data:image/png;base64,"+base64_data;     
  }    
  //先取目前大小
  var _tmpid = "canvas_scale_img_"+_t; 
  $("body").append("<img id='"+_tmpid+"' src='"+base64_data+"'>");
  var w = $("#"+_tmpid).width();
  var h = $("#"+_tmpid).height();
 
  var r = parseFloat(w)/parseFloat(h);
  
  //這二行絕不可以刪 :)
  var new_w=nw;
  var new_h=new_w;
  if(typeof(nh)=="undefined" || isNaN(nh))
  {
	  new_h=new_w/r;
  }
  else
  {
	  new_h=nh;
  }
  //alert(new_w);    
  //alert(new_h);
  //$("#"+_tmpid).remove();
  //建立一個canvas來用
  var _canvas_tmpid = "canvas_scale_canvas"+_t;
  $("body").append("<canvas id='"+_canvas_tmpid+"' width='"+new_w+"' height='"+new_h+"'></canvas>");
  
  var canvas = document.getElementById(_canvas_tmpid);
  var ctx = canvas.getContext('2d');            
  ctx.drawImage(document.getElementById(_tmpid), 0, 0, new_w, new_h);    
  var output = document.getElementById(_canvas_tmpid).toDataURL();
  //alert(new_w+","+new_h);
  //alert(w+","+h);
  $("#"+_tmpid).remove();
  $("#"+_canvas_tmpid).remove();    
  delete canvas;
  delete ctx;
  delete w;
  delete h;
  delete r;
  delete new_h;
  delete new_w;
  return output;
}
$(document).ready(function(){
  //$("body").append(userAgent);

});
//加密與解密
function enPWD_string( $str, $key ) {
  $str = base64_encode($str);
  $key = base64_encode($key); 
  $xored = "";
  for ($i=0,$max_i=strlen($str);$i<$max_i;$i++) {
    $a = ord(substr($str,$i,1));      
    for ($j=0,$max_j=strlen($key);$j<$max_j;$j++) {      
      $k = ord(substr($key,$j,1));
      $a = $a ^ $k;
    }
    $xored = sprintf("%s%s",$xored,chr($a));
  }       
  return base64_encode($xored);
}
function dePWD_string( $str, $key ) {
  $str = base64_decode($str);    
  $key = base64_encode($key);
  $xored = "";
  for ($i=0,$max_i=strlen($str);$i<$max_i;$i++) {
    $a = ord(substr($str,$i,1));
    for ($j=strlen($key)-1;$j>=0;$j--) {    
      $k = ord(substr($key,$j,1));
      $a = $a ^ $k;
    }
    $xored = sprintf("%s%s",$xored,chr($a));
  }   
  $xored = base64_decode($xored);
  return $xored;
}
// javascript
function get_between($data,$s_begin,$s_end)
{
  /*
    $a = "abcdefg";
    echo get_between($a, "cde", "g");
    // get "f"
  */
  $s = $data;
  $start = strpos($s,$s_begin);
  $new_s = substr($s,$start + strlen($s_begin));
  $end = strpos($new_s,$s_end);
  return substr($s,$start + strlen($s_begin), $end);
}
function smallComment(message,is_need_motion)
{
	//畫面的1/15	
	if($("#mysmallComment").size()==0)
	{
		$("body").append("<div id='mysmallComment'><div class='' id='mysmallCommentContent'></div></div>");
		$("#mysmallComment").css({
			'display':'none',
			'position':'fixed',
			'left':'0px',
			'right':'0px',
			'bottom':'4em',
			'z-index':new Date().getTime(),
			'text-align':'center',
			'opacity':0.8
		});
		$("#mysmallCommentContent").css({			
			'display': 'inline-block',	
			'color':'#fff',
			'background-color':'#000'			
		});

		/*
		$("#mysmallComment").css({
			'left': (wh['width']-$("#mysmallComment").width())/2+'px' 
		});
		*/

		//$("#mysmallComment").corner();
	}		
	$("#mysmallCommentContent").html(message);
	if(is_need_motion==true)
	{
		$("#mysmallComment").stop();
		$("#mysmallComment").fadeIn("slow");
		setTimeout(function(){
			$("#mysmallComment").fadeOut('fast');
		},2500);
	}
	else
	{
		$("#mysmallComment").show();
		setTimeout(function(){
			$("#mysmallComment").hide();
		},2500);
	}
}
function timedownbutton(button_dom,title,seconds,done_func)
{
  // By 3WA John (linainverseshadow@gmail.com)
  // Version : 1.0
  // Method : 倒數計時按鈕 
  // button_dom = which buttom dom you wanna control
  // title = button title
  // second = wait second
  // done_func = after count down. what will be going on.
  
  if($(button_dom).size()==0)
  {
    return;
  }
  if(window['timedown_step']==null)
  {
    window['timedown_step']=new Array();
  }
  window['timedown_step'].push("REGISTED");
  $(button_dom).attr('timedown',seconds);
  var t = new Date().getTime()+"_"+window['timedown_step'].length;
  window['time_down_func_'+t] = function(){
    $(button_dom).val(
          title+
          "("+
          parseInt($(button_dom).attr('timedown'))+
          ")"
    );                
    setTimeout(function(){
      if(parseInt($(button_dom).attr('timedown'))>0)
      {
        $(button_dom).attr('timedown',parseInt($(button_dom).attr('timedown')) -1 );
        window['time_down_func_'+t]();
      }
      else
      {
        done_func();
      }
    },1000);
  }
  
  window['time_down_func_'+t]();
}
function print_table($ra,$fields,$headers,$classname)
{    
  $classname=(typeof($classname)=="undefined"||$classname=='')?'':" class='"+$classname+"' ";
  if(typeof($fields)=="undefined"||$fields==''||$fields=='*')
  {      

      $tmp=sprintf("<table %s border='1' cellspacing='0' cellpadding='0'>",$classname);
      $tmp+="<thead><tr>";
      for(var k in $ra[0])
      {
        $tmp+=sprintf("<th>%s</th>",k);
      }
      $tmp+="</tr></thead>";
      $tmp+="<tbody>";
      for($i=0,$max_i=count($ra);$i<$max_i;$i++)
      {
        $tmp+="<tr>";
        for(var k in $ra[$i])
        {
          $tmp+=sprintf("<td field=\"%s\">%s</td>",k,$ra[$i][k]);
        }
        $tmp+="</tr>";
      }
      $tmp+="</tbody>";
      $tmp+="</table>";
      return $tmp;
  }
  else
  {
    $tmp=sprintf("<table %s border='1' cellspacing='0' cellpadding='0'>",$classname);
    $tmp+="<thead><tr>";
    $mheaders=explode(',',$headers);
    for(var k in $mheaders)
    {
      $tmp+=sprintf("<th>%s</th>",$mheaders[k]);
    }
    $tmp+="</tr></thead>";
    $tmp+="<tbody>";
    $m_fields=explode(',',$fields);
    for($i=0,$max_i=count($ra);$i<$max_i;$i++)
    {
      $tmp+="<tr>";
      for(var k in $m_fields)
      {
        $tmp+=sprintf("<td field=\"%s\">%s</td>",k,$ra[$i][$m_fields[k]]);
      }
      $tmp+="</tr>";
    }
    $tmp+="</tbody>";
    $tmp+="</table>";
    return $tmp;
  }
}