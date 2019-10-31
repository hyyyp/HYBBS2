
<div id="HY-BOX" style="z-index: 1000;display:none;position: fixed; left: 0px; right: 0px; bottom: 0px; height: 200px; overflow-y: auto; background-color: rgb(245, 245, 245); border-top: 1px solid rgb(224, 224, 224);">
    <div id="HY-CLOSE" style="z-index: 999;font-size: 15pt; color: rgb(0, 0, 0); cursor: pointer; position: absolute; right: 9px; font-weight: bold; padding: 0px 10px;">−</div>
    <div style="z-index: 998;border-bottom: solid 1px #D2D2D2;height: 33px;position: absolute;width: 100%;background-color: #FFF;">
        <ul id="HY-LIST" class="HY1">
            <li class="action">运行</li>
            <li>数据库操作</li>
            <li>文件加载</li>
            <li>类库加载</li>
            <li>COOKIE</li>
            <li>_GET</li>
        </ul>
    </div>
    <div id="HY-ID0" style="height: 198px;overflow-y: auto;">
        <ul class="HY">
            <li style="border-top: solid 1px #D2D2D2;">请求参数：<?php echo $url; ?></li>
            <li>控制器：<?php echo ACTION_NAME; ?>::<?php echo METHOD_NAME; ?></li>
            <li>访问成员：<?php echo METHOD_NAME; ?></li>
            <li>DEBUG：<?php echo DEBUG?'开启':'未开启'; ?> </li>
            <li>总运行时间：<?php echo round($GLOBALS['END_TIME'] - $GLOBALS['START_TIME'],4); ?> s</li>
            <li>服务器时间戳：<?php echo NOW_TIME; ?> ，格式化时间：<?php echo date('Y-m-d H:i:s',NOW_TIME); ?></li>
            <li>内存使用：<?php echo round((memory_get_usage() - $GLOBALS['START_MEMORY'])/1024); ?> Kb</li>
            
            <li id="HY-COOKIE">COOKIE：</li>
        </ul>
    </div>
    <div id="HY-ID1" style="height: 198px;overflow-y: auto;display:none;">
        <ul class="HY">
            <li style="border-top: solid 1px #D2D2D2;">SQL查询 (<?php echo count($DEBUG_SQL)-1; ?>)</li>
            <?php foreach ($DEBUG_SQL as $v): ?>
                <li><?php echo $v; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div id="HY-ID2" style="height: 198px;overflow-y: auto;display:none;">
        <ul class="HY">
            <li style="border-top: solid 1px #D2D2D2;">文件加载统计 (<?php echo count(get_included_files()); ?>)</li>
            <?php foreach (get_included_files() as $v): ?>
                <li><?php echo $v; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div id="HY-ID3" style="height: 198px;overflow-y: auto;display:none;">
        <ul class="HY">
        <li style="border-top: solid 1px #D2D2D2;">New 类 (<?php echo count($DEBUG_CLASS); ?>)</li>
            <li>new \HY</li>
            <?php foreach ($DEBUG_CLASS as $k => $v): ?>
                <li>new \<?php echo $k; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div id="HY-ID4" style="height: 198px;overflow-y: auto;display:none;">
        <ul class="HY">
        <?php if(!isset($_COOKIE))
                $_COOKIE = array();
            else{
                if(empty($_COOKIE))  
                    $_COOKIE = array();
                }
            ?>
        <li style="border-top: solid 1px #D2D2D2;">COOKIE个数 (<?php echo count($_COOKIE); ?>)</li>
            
            <?php foreach ($_COOKIE as $k => $v): ?>
                <li><?php echo $k; ?> : <?php echo $v; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div id="HY-ID5" style="height: 198px;overflow-y: auto;display:none;">
        <ul class="HY">
        <?php if(!isset($_GET))
                $_GET = array();
            else{
                if(empty($_GET))  
                    $_GET = array();
                }
            ?>
        <li style="border-top: solid 1px #D2D2D2;">GET个数 (<?php echo count($_GET); ?>)</li>
            
            <?php foreach ($_GET as $k_GET => $v_GET): ?>
                <li><?php echo $k_GET; ?>：<?php echo (is_array($v_GET)?'Json格式化：'.json_encode($v_GET):$v_GET); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
   

</div>

<style>
#HY-BOX{
    font-family: "微软雅黑";
}
.HY{

    padding: 0;
margin: 0;
margin-top: 45px;
word-wrap: break-word;word-break:break-all;
}
.HY1{
    padding: 0;
margin: 0;
}
.HY1>li.action{
    background-color: #F3F3F3;
    color: #078600;
}
.HY1>li{
    list-style: none;
    float: left;
    padding: 5px 10px;
background-color: #FFF;
cursor: pointer;
}
.HY>li{
    font-size: 16px;
    list-style: none;
    background-color: #FFF;
    border-bottom: solid 1px #D2D2D2;
    padding: 5px 10px;

}
</style>
<div id="HY-SHOW" onclick style="
   position: fixed;
    right: 0px;
    bottom: 0px;
    display: block;
    color: rgb(85, 119, 182);
    padding: 0 8px;
    font-weight: bold;
    vertical-align: middle;
    cursor: pointer;
    height: 35px;
    line-height: 35px;
    border: dashed;
    ">
    <img style="    float: left;
    background-color: #ffffff;
        margin: 1px 4px 0 -5px;" width="32" height="32" src="data:image/png;base64,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">
    <span style="    float: left;">
    程序耗时 <?php echo round($GLOBALS['END_TIME'] - $GLOBALS['START_TIME'],4);?>秒
    <?php if(function_exists('memory_get_usage')): ?>
    内存:<?php echo round((memory_get_usage() - $GLOBALS['START_MEMORY'])/1024);?>KB
    <?php endif ?>
    </span>

</div>

<script>
(function(){

var cookie   = document.cookie.match(/HY_DEBUG=(\d)/);
var HY_BOX = document.getElementById('HY-BOX');
var HY_open =  document.getElementById('HY-SHOW');
var HY_close =  document.getElementById('HY-CLOSE');

var HY_LI  = document.getElementById('HY-LIST').getElementsByTagName('li');



document.getElementById('HY-COOKIE').innerHTML    = 'COOKIE：'+document.cookie;

if(cookie && typeof cookie[1] != 'undefined')
    cookie = cookie[1];
else
    cookie = 0;


//HY-ID1

if(cookie == 0){
    HY_BOX.style.display = 'none';
    HY_open.style.display = 'block';
}else{
    HY_BOX.style.display = 'block';
	HY_open.style.display = 'none';
}

HY_open.onclick = function(){
	HY_BOX.style.display = 'block';
	HY_open.style.display = 'none';
	document.cookie = 'HY_DEBUG=1';
    //console.log(document.cookie);
}
HY_close.onclick = function(){
    HY_BOX.style.display = 'none';
    HY_open.style.display = 'block';
    document.cookie = 'HY_DEBUG=0'
}


for(var i = 0; i < HY_LI.length; i++){
    //console.log(i);
	HY_LI[i].onclick = (function(i){

		return function(){

			for(var j = 0; j < HY_LI.length; j++){
                //console.log(i);
				HY_LI[j].className  = '';
                document.getElementById('HY-ID'+j).style.display = 'none';
				//HY_LI[j].style.color = '#999';
			}
            HY_LI[i].className = 'action';
            document.getElementById('HY-ID'+i).style.display = 'block';
		}
	})(i)
}

})();
</script>
