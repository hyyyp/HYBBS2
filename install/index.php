<?php 
header("Content-Type: text/html; charset=UTF-8");
define('INDEX_PATH' , str_replace('\\', '/', dirname(__FILE__)).'/');
if(is_file(INDEX_PATH . '../Conf/config.php')){
	$data = include INDEX_PATH . '../Conf/config.php';
	if(isset($data['DOMAIN_NAME']))
	    die('你已经安装过,如果需要重装请将 /Conf/config.php删除');
}

function ok($content){
	echo '<span class="label label-success"><i class="fa fa-check" aria-hidden="true"></i> '.$content.'</span>';
}
function no($content){
	echo '<span class="label label-danger"><i class="fa fa-close" aria-hidden="true"></i> '.$content.'</span>';
}
function userOS(){
    return PHP_OS;   
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>
		    HYBBS安装程序 - HYBBS轻论坛程序
		</title>
		<script type="text/javascript" src="../public/js/jquery1.11.3.min.js"></script>
		<script type="text/javascript" src="stickUp.min.js"></script>
		
		<link href="../public/admin/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="../public/admin/css/docs.min.css" rel="stylesheet">
		<link href="../public/css/font-awesome.min.css" rel="stylesheet">

		<style>
		body{
			    background-color: #f1fcff;
		}
		.box {
		    padding: 10px;
		    background-color: #FFF;
		    border: 1px solid #E3E3E3;
		    box-shadow: 1px 2px 17px #D8D8D8;
		    margin-bottom: 50px;
		}
		.bs-docs-header, .bs-docs-masthead{
			    background-color: #186d6e;
		}
		.bs-docs-header p{
			color: #FFF
		}
		pre{
			    max-height: 604px;
			overflow: auto;
			    overflow-x: hidden;
    white-space: normal;
    
			    
		}
		pre *{
			    color: #407423;
			    font-weight: 600;
			    font-size: 16px;

		}
		.isStuck{
			margin-top:30px;
		}
		.panel-body *,.btn{
			font-family: "Helvetica Neue", Helvetica, Microsoft Yahei, Hiragino Sans GB, WenQuanYi Micro Hei, sans-serif;
		}
		
		</style>
		<script type="text/javascript">
		jQuery(function($) {
            $(document).ready( function() {
              //enabling stickUp on the '.navbar-wrapper' class
              $('pre').stickUp({ marginTop: '30px'});
            });

            

          });

		</script>
	</head>
	<body>
		<div class="bs-docs-header" id="content" tabindex="-1">
	        <div class="container">
	            <h1>HY BBS 安装程序</h1>
	            <p>
	                HYBBS 轻论坛程序安装页面. 
	            </p>
	            <p>
	          <!--   <ul>
  <li>确认网站目录有读取写入权限.</li>
  <li>数据库信息正确.</li>
</ul> -->
	            </p>
	            <p>
	            	<a style="color: #FFF;" href="http://bbs.hyphp.cn/" target="_blank">HYBBS 论坛官方链接</a>
	            </p>
	            
	        </div>
	    </div>
	    <div class="container " id="install-1">
	    <div class="panel panel-default">
  <div class="panel-body">
			<p  align="center" ><span  style="font-size:16.0pt;line-height:150%;
font-family:Arial;mso-fareast-font-family:黑体">HYBBS</span><span style="font-size:16.0pt;line-height:150%;font-family:黑体;mso-ascii-font-family:
Arial;mso-hansi-font-family:Arial;mso-bidi-font-family:Arial">开源授权许可协议</span></p>

<p ><span style=>版权所有</span><span > (c) 2015-2016</span><span >，浙江逆天网络科技有限责任公司保留所有权利。</span></p>

<p ><span style=>感谢您选择</span><span >HYBBS</span><span ></span><span ></span><span >，</span><span >HYBBS</span><span >致力于为用户提供全面的</span><span >BBS</span><span >解决方案。</span></p>

<p ><span style="">浙江逆天网络科技有限责任公司为</span><span >HYBBS</span><span style="">产品的开发商，依法独立拥有</span><span >HYBBS</span><span style="">）。浙江逆天网络科技有限责任公司网址为</span> <span ><a href="http://bbs.hyphp.cn/">http://bbs.hyphp.cn</a></span><span style="">，</span><span >HYBBS</span><span style="">官方网站网址为</span> <span ><a href="http://bbs.hyphp.cn/">http://bbs.hyphp.cn</a></span><span style="">。</span> </p>

<p ><span >HYBBS</span><span style="">著作权已在中华人民共和国国家版权局注册，著作权受到法律和国际公约保护。使用者：无论个人或组织、盈利与否、用途如何（包括以学习和研究为目的），均需仔细阅读本协议，在理解、同意、并遵守本协议的全部条款后，方可开始使用</span>
<span >HYBBS</span><span style="">软件。</span></p>

<p ><span style="">浙江逆天网络科技有限责任公司拥有对本授权协议的最终解释权。</span></p>

<p  align="left" style="margin-left:41.95pt;text-align:left;
text-indent:-24.1pt;line-height:150%;"><span  style=""><span >1.0<span >&nbsp;&nbsp;
</span></span></span><span >协议许可的权利</span><span style=""> <span ><o:p></o:p></span></span></p>

<p  align="left" style="margin-left:66.05pt;text-align:left;
text-indent:-24.1pt;line-height:150%;"><span  style=""><span >1)<span >&nbsp;&nbsp;&nbsp;
</span></span></span><span style="">您可以在完全遵守本最终用户授权协议的基础上，将本软件应用于非商业用途，而不必支付软件版权授权费用；<span ><o:p></o:p></span></span></p>

<p  align="left" style="margin-left:66.05pt;text-align:left;
text-indent:-24.1pt;line-height:150%;"><span  style=""><span >2)<span >&nbsp;&nbsp;&nbsp;
</span></span></span><span style="">您可以在协议规定的约束和限制范围内修改
<span >HYBBS </span>源代码或界面风格以适应您的网站要求；<span ><o:p></o:p></span></span></p>

<p  align="left" style="margin-left:66.05pt;text-align:left;
text-indent:-24.1pt;line-height:150%;"><span  style=""><span >3)<span >&nbsp;&nbsp;&nbsp;
</span></span></span><span style="">您拥有使用本软件构建的社区中全部会员资料、文章及相关信息的所有权，并独立承担与文章内容的相关法律义务；<span ><o:p></o:p></span></span></p>

<p  align="left" style="margin-left:66.05pt;text-align:left;
text-indent:-24.1pt;line-height:150%;"><span  style=""><span >4)<span >&nbsp;&nbsp;&nbsp;
</span></span></span><span style="">获得商业授权之后，您可以将本软件应用于商业用途，同时依据所购买的授权类型中确定的技术支持期限、技术支持方式和技术支持内容，自购买时刻起，
在技术支持期限内拥有通过指定的方式获得指定范围内的技术支持服务。商业授权用户享有反映和提出意见的权力，相关意见将被作为首要考虑，但没有一定被采纳的承诺或保证。<span ><o:p></o:p></span></span></p>

<p  align="left" style="margin-left:41.95pt;text-align:left;
text-indent:-24.1pt;line-height:150%;"><span  style=""><span >2.0<span >&nbsp;&nbsp; </span></span></span><span style="">协议规定的约束和限制 <span ><o:p></o:p></span></span></p>

<p  align="left" style="margin-left:66.05pt;text-align:left;
text-indent:-24.1pt;line-height:150%;"><span  style=""><span >1)<span >&nbsp;&nbsp;&nbsp;
</span></span></span><span style="">未获商业授权之前，不得将本软件用于商业用途（包括但不限于企业网站、经营性网站、以营利为目或实现盈利的网站）。购买商业授权请登陆</span><span ><a href="http://bbs.hyphp.cn/"><span style="">http://bbs.hyphp.cn</span></a></span><span  style=""> </span><span style="">参考相关说明，也可以致电了解详情；<span ><o:p></o:p></span></span></p>

<p  align="left" style="margin-left:66.05pt;text-align:left;
text-indent:-24.1pt;line-height:150%;"><span  style=""><span >2)<span >&nbsp;&nbsp;&nbsp;
</span></span></span><span style="">不得对本软件或与之关联的商业授权进行出租、出售、抵押或发放子许可证；<span ><o:p></o:p></span></span></p>

<p  align="left" style="margin-left:66.05pt;text-align:left;
text-indent:-24.1pt;line-height:150%;"><span  style=""><span >3)<span >&nbsp;&nbsp;&nbsp;
</span></span></span><span style="">无论如何，即无论用途如何、是否经过修改或美化、修改程度如何，只要使用<span >HYBBS</span>的整体或任何部分，未经书面许可，页面页脚处的 <span >Powered by
HYBBS</span>名称和官网网站的链接（</span><span ><a href="http://bbs.hyphp.cn/"><span style="">http://bbs.hyphp.cn</span></a></span><span  style="mso-bidi-font-size:10.5pt;line-height:150%;font-family:宋体;
mso-ascii-theme-font:minor-fareast;mso-fareast-theme-font:minor-fareast;
mso-hansi-theme-font:minor-fareast;mso-bidi-font-family:Arial"> </span><span style="">）都必须保留，而不能清除或修改；<span ><o:p></o:p></span></span></p>

<p  align="left" style="margin-left:66.05pt;text-align:left;
text-indent:-24.1pt;line-height:150%;"><span  style=""><span >4)<span >&nbsp;&nbsp;&nbsp;
</span></span></span><span style="">禁止<span >HYBBS</span>的整体或任何部分基础上以发展任何派生版本、修改版本或第三方版本用于重新分发；<span ><o:p></o:p></span></span></p>

<p  align="left" style="margin-left:66.05pt;text-align:left;
text-indent:-24.1pt;line-height:150%;"><span  style=""><span >5)<span >&nbsp;&nbsp;&nbsp;
</span></span></span><span style="">如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回，并承担相应法律责任。<span ><o:p></o:p></span></span></p>

<p  align="left" style="margin-top:8.15pt;margin-right:0cm;
margin-bottom:8.15pt;margin-left:42.0pt;mso-para-margin-top:.5gd;mso-para-margin-right:
0cm;mso-para-margin-bottom:.5gd;mso-para-margin-left:42.0pt;text-align:left;
text-indent:-24.0pt;line-height:150%;"><span ><span >3.0<span >&nbsp;&nbsp;&nbsp;&nbsp;
</span></span></span><span>有限担保和免责声明</span><span > <span ><o:p></o:p></span></span></p>

<p  align="left" style="margin-top:2.5pt;margin-right:0cm;
margin-bottom:2.5pt;margin-left:66.0pt;text-align:left;text-indent:-24.0pt;
line-height:150%;"><span  style=""><span >1)<span >&nbsp;&nbsp;&nbsp;
</span></span></span><span style="">本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的；<span ><o:p></o:p></span></span></p>

<p  align="left" style="margin-top:2.5pt;margin-right:0cm;
margin-bottom:2.5pt;margin-left:66.05pt;text-align:left;text-indent:-24.1pt;
line-height:150%;"><span  style=""><span >2)<span >&nbsp;&nbsp;&nbsp;
</span></span></span><span style="">用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未购买产品技术服务之前，我们不承诺提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任；<span ><o:p></o:p></span></span></p>

<p  align="left" style="margin-top:2.5pt;margin-right:0cm;
margin-bottom:2.5pt;margin-left:66.05pt;text-align:left;text-indent:-24.1pt;
line-height:150%;"><span  style=""><span >3)<span >&nbsp;&nbsp;&nbsp;
</span></span></span><span style="">浙江逆天网络科技有限责任公司不对使用本软件构建的社区中的文章或信息承担责任。<span ><o:p></o:p></span></span></p>

<p ><span style="">有关</span><span >HYBBS</span><span style="">最终用户授权协议、商业授权与技术服务的详细内容，均由</span><span >HYBBS</span><span style="">官方网站独家提供。浙江逆天网络科技有限责任公司拥有在不事先通知的情况下，修改授权协议和服务价目表的权力，修改后的协议或价目表对自改变之日起的新授权用户生效。</span></p>

<p ><span style="">电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和等同的法律效力。您一旦开始安装</span>
<span >HYBBS</span><span style="">，即被视为完全理解并接受本协议的各项条款，在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。</span></p>

<p  align="left" style="margin-top:8.15pt;margin-right:0cm;
margin-bottom:8.15pt;margin-left:0cm;mso-para-margin-top:.5gd;mso-para-margin-right:
0cm;mso-para-margin-bottom:.5gd;mso-para-margin-left:0cm;text-align:left;
line-height:150%"></p>
	    </div>
	    </div>
	    <button type="button" class="btn btn-primary btn-lg btn-block" onclick="$(this).hide();$('#install-1').hide();$('#install-2').show();$('pre').stickUp({ marginTop: '30px'});window.scrollTo('0','0');">同意协议, 继续安装HYBBS</button>
	    </div>
	    <div class="container" id="install-2" style="display:none">
			
			<table class="table table-bordered" style="background:#FFF">
			 <thead>

			 	<tr>
			 		<th>环境</th>
			 		<th>本地结果</th>
			 		<th>推荐使用</th>
			 	</tr>
			 </thead>
			 <tbody>
			 <tr>
			 	<td>WEB服务器</td>
			 	<td><i class="fa fa-check text-success" aria-hidden="true"></i> <?php echo $_SERVER['SERVER_SOFTWARE'];?></td>
			 	<td>Nginx - Apache - IIS</td>
			 </tr>
			 <tr>
			 	<td>PHP版本</td>
			 	<td><?php 
			 		 if(!version_compare(PHP_VERSION,'5.4.0','<'))
			 		 	echo '<i class="fa fa-check text-success" aria-hidden="true"></i>';
			 		 else
			 		 	echo '<i class="fa fa-close text-danger" aria-hidden="true"></i>';
			 		?>
			 		 <?php echo PHP_VERSION;?></td>
			 	<td>PHP 7</td>
			 </tr>
			 <tr>
			 	<td>系统类型</td>
				<td><i class="fa fa-check text-success" aria-hidden="true"></i> <?php echo userOS();?></td>
				<td>Unix</td>
			 </tr>
			 </tbody>
			</table>
			<table class="table table-bordered" style="background:#FFF">
			 <thead>

			 	<tr>
			 		<th>检测</th>
			 		<th>要求</th>
			 		<th>结果</th>
			 		<th>解决方案</th>
			 	</tr>
			 </thead>
			 <tbody>
			 	<tr>
			 		<td>PHP环境</td>
			 		<td>PHP版本 5.4以上 (包括5.4)</td>
			 		<td><?php 
			 		 if(version_compare(PHP_VERSION,'5.3.0','<')){
			 		 	no('不支持 (PHP '.PHP_VERSION.')');
			 		 	echo '</td><td>提升PHP版本';
			 		 }
			 		 else
			 		 	ok('支持');
			 		?></td>
			 		
			 	</tr>
			 	<tr>
			 		<td>数据库环境</td>
			 		<td>PDO</td>
			 		<td><?php 
			 		 if(!class_exists('PDO')){
			 		 	no('不支持 PDO');
			 		 	echo '</td><td>php.ini 找到 php_pdo 模块去除注释.';
			 		 }
			 		 else
			 		 	ok('支持');
			 		?></td>
			 		
			 	</tr>
			 	
			 	<!-- <tr>
			 		<td>伪静态规则</td>
			 		<td>必须安装</td>
			 		<td>
			 			<span id="txt1">
			 				<span class="fa fa-bug"></span> 检测中
			 			</span>
			 			<script type="text/javascript">
			 			var href=window.location.href;
			 			href = href.replace('index.php','');
			 			if(href.substr(href.length-1,1) !='/')
			 				href+='/';
			 			href+='../Inst/install';
			 			
			 			$.ajax({
			 				url:href,
			 				dataType:'html',
			 				success:function(e){
			 					
			 					if(e=='install')
			 						$("#txt1").html('<?php ok('正常'); ?>')
			 					else
			 						$("#txt1").html('<?php no('不正常'); ?>')
			 				},error:function(){
			 					$("#txt1").html('<?php no('不正常'); ?>')
			 				}
			 			});
			 	
			 			</script>
			 		</td>
			 		<td id="txt2">
			 			<a href="http://bbs.hyphp.cn/t/489.html" target="_blank">查看解决方案</a>
			 		</td>
			 		
			 	</tr> -->
			 	<tr>
			 		<td>CURL模块</td>
			 		<td>建议开启</td>
			 		<td>
			 			<?php 
				 		 if(!function_exists('curl_init')){
				 		 	no('未开启');
				 		 	echo '</td><td>php.ini开启curl';
				 		 }
				 		 else
				 		 	ok('已开启');
				 		?>
			 			
			 		</td>
			 		
			 		
			 	</tr>
			 	<tr>
			 		<td>ZIP关键函数</td>
			 		<td>gzinflate函数 可能被空间禁用</td>
			 		<td>
			 			<?php 
				 		 if(!function_exists('gzinflate')){
				 		 	no('被禁用');
				 		 	echo '</td><td>php.ini开启curl';
				 		 }
				 		 else
				 		 	ok('已开启');
				 		?>
			 			
			 		</td>
			 		
			 		
			 	</tr>
			 	<tr>
			 		<td>PHP时区</td>
			 		<td>建议到php.ini 找到data.timezone 值改为 "Asia/Shanghai"</td>
			 		<td>
			 			<?php 
				 	
				 		 	ok(date_default_timezone_get());
				 		?>
			 			
			 		</td>
			 		<td>
			 		此项可忽略
			 		</td>
			 		
			 		
			 	</tr>
			 	

			 	<tr>
			 		<td>OPENSSL</td>
			 		<td>PHP访问HTTPS远程链接 (建议开启) (可忽略此项)</td>
			 		<td>
			 			<?php 
				 		 if(!extension_loaded('openssl')){
				 		 	no('被禁用 (可忽略)');
				 		 	echo '</td><td>php.ini 找到 php_openssl 去掉注释开启';
				 		 }
				 		 else
				 		 	ok('已开启');
				 		?>
			 			
			 		</td>
			 		
			 		
			 	</tr>

			 	<tr>
			 		<td>
			 			/Tmp 目录权限
			 		</td>
			 		<td>
			 			必须可读可写
			 		</td>
			 		<td>
			 			<?php 
			 				if(@file_put_contents('../Tmp/install', '1') === false){
			 					no('无写入权限');
			 					echo '</td><td>建议将/Tmp目录权限设为 777';
			 				}
			 				else{

			 					if(@file_get_contents('../Tmp/install') !== '1'){
			 						no('无读取权限');
			 						echo '</td><td>建议将/Tmp目录权限设为 777';
			 					}
			 					else{
			 						if(is_file('../Tmp/install'))
			 							unlink('../Tmp/install');
			 						ok('通过');
			 					}
			 				}
			 			?>
			 		</td>
			 		
			 	</tr>
			 	<tr>
			 		<td>
			 			/Conf 目录权限
			 		</td>
			 		<td>
			 			必须可读可写
			 		</td>
			 		<td>
			 			<?php 
			 				if(@file_put_contents('../Conf/install', '1') === false){
			 					no('无写入权限');
			 					echo '</td><td>建议将/Conf 目录权限设为 777';
			 				}
			 				else{

			 					if(@file_get_contents('../Conf/install') !== '1'){
			 						no('无读取权限');
			 						echo '</td><td>建议将/Conf 目录权限设为 777';
			 					}
			 					else{
			 						if(is_file('../Conf/install'))
			 							unlink('../Conf/install');
			 						ok('通过');
			 					}
			 				}
			 			?>
			 		</td>
			 		
			 	</tr>
			 	<tr>
			 		<td>
			 			/Plugin 目录权限
			 		</td>
			 		<td>
			 			必须可读可写
			 		</td>
			 		<td>
			 			<?php 
			 				if(@file_put_contents('../Plugin/install', '1') === false){
			 					no('无写入权限');
			 					echo '</td><td>建议将/Plugin 目录权限设为 777';
			 				}
			 				else{

			 					if(@file_get_contents('../Plugin/install') !== '1'){
			 						no('无读取权限');
			 						echo '</td><td>建议将/Plugin 目录权限设为 777';
			 					}
			 					else{
			 						if(is_file('../Plugin/install'))
			 							unlink('../Plugin/install');
			 						ok('通过');
			 					}
			 				}
			 			?>
			 		</td>
			 		
			 	</tr>
			 	<tr>
			 		<td>
			 			/View 目录权限
			 		</td>
			 		<td>
			 			必须可读可写
			 		</td>
			 		<td>
			 			<?php 

			 				if(@file_put_contents('../View/install.txt', '1') === false){
			 					no('无写入权限');
			 					echo '</td><td>建议将/View 目录权限设为 777';
			 				}
			 				else{

			 					if(@file_get_contents('../View/install.txt') !== '1'){
			 						no('无读取权限');
			 						echo '</td><td>建议将/View 目录权限设为 777';
			 					}
			 					else{
			 						if(is_file('../View/install.txt'))
			 							unlink('../View/install.txt');
			 						ok('通过');
			 					}
			 				}
			 			?>
			 		</td>
			 		
			 	</tr>
			 </tbody>
			</table>
			
			
			<p class="alert alert-warning">请确认以上项目全部通过 再通过下面表单进行安装, 强制安装不是最好的结果</p>
			<div class="row" id="div1" style="">
				<div class="col-md-6">
				<h2>数据库配置</h2>
				<table class="table table-bordered" style="background:#FFF">
				 <thead>
				 <tr>
				 	<th>PDO (本地配置支持情况)

				 	</th>
				 	<th>可用状态 </th>
				 </tr>
				 </thead>
				 <tbody>
				 	<tr>
				 		<td>My SQL (pdo_mysql)</td>
				 		<td><?php if(extension_loaded('pdo_mysql')) ok('支持'); else no('无法使用 (必须开启)'); ?></td>
				 	</tr>
				 	<tr><td>
				 		目前HYBBS仅支持 MYSQL
				 	</td></tr>
				 	
				 	
				 </tbody>
				 </table>
			<form id="form">
               <div class="form-group">
                    <label for=>数据库 类型</label>
                    <select name="sqltype" class="form-control">
                      <option value="mysql">MySQL</option>
                      
                    </select>
                </div>
                <div class="form-group">
                <label for="" style="display:block">数据表 类型 (无需求,无需修改)</label>
                <label for="inlineRadio1" class="radio-inline">
				  <input type="radio" name="table_type" id="inlineRadio1" value="MyISAM" checked> MyISAM
				</label>
				<label for="inlineRadio2" class="radio-inline">
				  <input type="radio" name="table_type" id="inlineRadio2" value="InnoDB"> InnoDB
				</label>
                </div>
              <div class="form-group">
                <label for="">数据库 IP</label>
                <input type="text" name="ip" class="form-control" value="localhost">
              </div>
              <div class="form-group">
                <label for="">数据库名</label>
                <input type="text" name="name" class="form-control" value="">
              </div>
              <div class="form-group">
                <label for="">数据库 账号</label>
                <input type="text" name="username" class="form-control" value="root">
              </div>
              <div class="form-group">
                <label for="">数据库 密码</label>
                <input type="password" name="password" class="form-control" >
              </div>
              <div class="form-group">
                <label for="">数据库 端口</label>
                <input type="text" name="port" class="form-control" value="3306">
              </div>
             
              <h2>网站管理配置</h2>
              <div class="form-group">
                <label for="">安装域名(默认<strong>不需要修改</strong>. 除非特殊要求)</label>
                <input type="text" name="www" id="www" class="form-control" value="">
              </div>
              <script>
              document.getElementById("www").value = window.location.href.replace('/install/','').replace('/install','');
              </script>
              <!-- <div class="form-group">
                <label for="check">使用 https (ssl) &nbsp;&nbsp;&nbsp;&nbsp;</label>
                <input id="check" type="checkbox" name="https" value="on"/>
              </div> -->
              <div class="form-group">
                <label for="exampleInputPassword1">管理员邮箱 (请认真填写)</label>
                <input type="text" name="email" class="form-control" value="">
              </div>
              <div class="form-group">
                <label for="exampleInputPassword1">管理员账号 </label>
                <input type="text" name="bbs_user" class="form-control" value="admin">
              </div>
              <div class="form-group">
                <label for="exampleInputPassword1">管理员密码 (最少5位)</label>
                <input type="password" name="bbs_pass" class="form-control" value="">
              </div>
              </form>
              <button type="submit" class="btn btn-default btn-block" onclick="install();">安装</button>
			
			</div>
			<div class="col-md-6">
			<h2>安装过程记录</h2>
			<pre></pre>
			</div>
			</div>
	    </div>
		<div id="footer" style="text-align: center;margin: 90px 0 30px 0;">
			<span class="beian">HYBBS © 2016. All Rights Reserved.</span>
		</div>
		<script type="text/javascript">
			function install(){
				var href=window.location.href;
				href = href.replace('index.php','');
	 			if(href.substr(href.length-1,1) !='/')
	 				href+='/';
	 			href+='./mysql.php';
	 			
	 			
					$.ajax({
						url:href,
						type:'post',
						data:$("#form").serialize(),

						dataType:'html',

						success:function(e){
							$("pre").html('');
							if(e!='sql success'){
								app_text('<i class="fa fa-close" style="color:#e13e3e"></i> 数据库测试连接出错: '+e,'#e13e3e');
							}
							else{//测试连接成功
								href=window.location.href;
								href = href.replace('index.php','');
					 			if(href.substr(href.length-1,1) !='/')
					 				href+='/';
					 			href+='../index.php?s=Inst';
					 			app_text('<i class="fa fa-check"></i> 测试连接数据库成功;')
					 			app_text('<i class="fa fa-check"></i> 正在安装,请勿离开...(一般只需要5秒钟左右);')
								$.ajax({
									url:href,
									type:'post',
									data:$("#form").serialize()+"&gn=1",
									dataType:'json',
									success:function(e){
										if(!e.error)
											app_text('<i class="fa fa-close" style="color:#e13e3e"></i> 数据库安装出错: '+e.info,'#e13e3e');
										else{
											app_text('<i class="fa fa-check"></i> '+e.info);
											app_text('<i class="fa fa-check"></i> 正在创建索引');
											$.ajax({
												url:href,
												type:'post',
												data:$("#form").serialize()+"&gn=2",
												dataType:'json',
												success:function(e){
													if(!e.error)
														app_text('<i class="fa fa-close" style="color:#e13e3e"></i>'+e.info,'#e13e3e');
													else{
														app_text('<i class="fa fa-check"></i> '+e.info);
														app_text('<i class="fa fa-check"></i> 正在创建自动增值');
														$.ajax({
															url:href,
															type:'post',
															data:$("#form").serialize()+"&gn=3",
															dataType:'json',
															success:function(e){
																if(!e.error)
																	app_text('<i class="fa fa-close" style="color:#e13e3e"></i>'+e.info,'#e13e3e');
																else{
																	app_text('<i class="fa fa-check"></i> '+e.info);
																	app_text('<i class="fa fa-check"></i> 程序安装成功');
																	app_text('<i class="fa fa-check"></i> 建议删除 /install 目录');
																	app_text('<i class="fa fa-check"></i> <a style="color:#217DCD" href="'+e.url+'">访问网站</a> , <a style="color:#217DCD" href="'+e.url+'admin">访问后台</a>');
																}
																
																$('pre').scrollTop( $('pre')[0].scrollHeight );
															}

														})
													}
													
													$('pre').scrollTop( $('pre')[0].scrollHeight );
												}

											});
											
											
										}
										
										$('pre').scrollTop( $('pre')[0].scrollHeight );
									}

								});
							}
						}
					});
				
			}
			function app_text(str,color){
				$("pre").append('<p style="color:'+color+'">'+str+'</p>');
			}

			</script>

	</body>
</html>
