<?php !defined('HY_PATH') && exit('HY_PATH not defined.'); ?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style type="text/css">
*{
  margin: 0;
  padding: 0;
  white-space: normal;
    word-break: break-all;
}
body {
  font-family:"微软雅黑";
  background: #F5F5F5;
  font-size: 16px;
  line-height: 1.7;
}
table{
  border-spacing: 0;
border-collapse: collapse;
}
body, td{
  font-size: 16px;
  padding:1px;
      border: 1px solid #ddd;
}


dl,pre{
  margin: 0 10px;
}
#backtrace td {
  font-size: 16px;
}
.box{
  padding: 10px;
    background-color: #FFF;
    border: 1px solid #E3E3E3;
    box-shadow: 1px 2px 17px #D8D8D8;
    margin:10px;
}

</style>
</head>
<body>


  <div class="box" style="margin-top:10px">
    <h3>HYPHP详细调试页</h3>
    <div>
      <b style="width:100px">错误信息:</b> 
      <font color="red"> 
        <?php 
        $translate = include LIB_PATH . 'translate.php';
        foreach ($translate as $key => $value) {
            $message = str_replace($key,$value,$message);
        }
        echo $message;
        ?>
      </font>
    </div>
    

    <div><b>发生错误文件:</b> <?php echo $file; ?> </div>
    <div><b>出错行数:</b> <?php echo $line; ?> </div>
   
  </div>

  <?php 
  $file_data = file($file);
  $plugin_info = [];
  $plugin_type = '';
  for($i=$line;$i>=0;$i--){
    if(!isset($file_data[$i])) break;
    if(preg_match('/\/\/Hook ##(.+)##(.+)##/',$file_data[$i],$matches)){
      if(isset($matches[1]) && isset($matches[2])){
        //if($matches[1] == 'END')//插件结束
        //  break;

        $plugin_type = $matches[1];
        $plugin_info = unserialize($matches[2]);
        break;
      }
      
    }
  }
  ?>

  <?php if(!empty($plugin_type)): ?>
  <div class="box" style="margin-top:10px">
    <h3><?php if($plugin_type == 'END'): ?>插件报错诊断（最后运行的插件，非精准报告）<?php else: ?>插件报错诊断<?php endif; ?></h3>
    <div>
      <b style="width:100px">插件名:</b>
      <font color="red"><?php echo $plugin_info['plugin_name']; ?></font>
    </div>

    <div>
      <b style="width:100px">插件目录名:</b>
      <font color="red"><?php echo $plugin_info['dir_name']; ?></font>
    </div>

    <div>
      <b style="width:100px">报错文件路径:</b>
      <font color="red"><?php echo $plugin_info['path']; ?></font>
    </div>
  </div>
  <?php endif; ?>


  <?php if(substr_count(str_replace(array('/','\\'),array('/','/'),$file),str_replace(array('/','\\'),array('/','/'),TMP_PATH)) == 1): ?>
  <div class="box" style="margin-top:10px">
  出现错误的文件为编译缓存文件.你修正它是无效的.你需要找到他的源文件进行修复！
  <!-- <p>例如: 上面出现了 XXXXX\HYBBS\Tmp\Admin_f96ff8be27366a346ce77875ceace8c8.php</p>
  <p>如果你去修正Admin_f96ff8be27366a346ce77875ceace8c8.php 这个文件, 是没用的.</p>
  <p>而它的源文件是 \XXXXX\HYBBS\Action\Admin.php .它可能是Action的Admin.php文件</p> -->

  </div>
  <?php endif?>
  <div class="box">
    <h4>出现错误的地方</h4>
    <table cellspacing="0" width="100%">
    <tr>
        <td width="40" style="font-weight: bold;    background: #F0F0F0;">行数</td>
        <td style="font-weight: bold;padding-left:10px;    background: #F0F0F0;">代码内容</td>
      </tr>
    <?php foreach($codelist as $_line=>$code) {?>
      <tr <?php if($_line + 1 == $line) echo 'title="" style="background: #f2dede;color: #a94442;"'; ?>>
        <td width="40" valign=""><?php echo $_line + 1;?>:</td>
        <td><?php echo $code;?></td>
      </tr>
    <?php } ?>
    </table>
  </div>



  
  <div class="box">
    <h3>返回跟踪</h3>
    <table cellspacing="3" width="100%" id="backtrace">
      <tr style="text-align: left;">
        <th>文件</th>
        <th style="width:38px">行</th>
        <th>函数</th>
        <th>信息</th>
      </tr>
    <?php foreach($backtracelist as $k=>$backtrace) {?>
      <tr valign="top">

        <td><?php echo $backtrace['file'];?></td>
        <td><?php echo $backtrace['line'];?></td>
        <td><?php if(!empty($backtrace['class'])) {?><?php echo $backtrace['class'];?><?php echo $backtrace['type'];?><?php echo $backtrace['function'];?>(<?php echo nl2br(htmlspecialchars($backtrace['args']));?>)<?php }?></td>
        <td><?php echo $backtrace['args'];?></td>
      </tr>

    <?php } ?>
    </table>

  </div>


<div class="box">
  <h3>运行信息</h3>
  <dd><b>URL:</b> <?php echo $_SERVER['REQUEST_URI'];?></dd>
  <dd><b>控制器Action:</b> <?php echo ACTION_NAME;?></dd>
  <dd><b>操作方法Method:</b> <?php echo METHOD_NAME;?></dd>
  <dd><b>访问者IP:</b> <?php echo CLIENT_IP?></dd>
  <dd><b>现在服务器时间:</b> <?php echo date('Y/n/j H:i',NOW_TIME);?></dd>

</div>
<?php if(isset($_SERVER['new_class'])): ?>
<div class="box">
  <h3>类库加载</h3>
<?php foreach($_SERVER['new_class'] as $key => $file) {?>
  <dd><?php echo $key;?></dd>
<?php } ?>
</div>
<?php endif; ?>

<?php if(isset($_SERVER['sqls'])): ?>
<div class="box">
  <h3>数据库操作SQL</h3>
<?php foreach($_SERVER['sqls'] as $sql) {?>
  <dd><?php echo $sql;?></dd>
<?php } ?>
</div>
<?php endif; ?>

<div class="box">
  <h3>文件加载</h3>
<?php foreach(get_included_files() as $file) {?>
  <dd><?php echo $file;?></dd>
<?php } ?>
</div>




<?php
  if(DEBUG) {
    if(isset($_GET))
      echo '<div class="box" style="    margin: 10px 10px 0 10px;white-space: pre;word-break: break-word;white-space: pre-wrap;word-wrap: break-word;">$_GET = '.print_r($_GET, 1).'</div>';
    if(isset($_POST))
      echo '<div class="box" style="    margin: 10px 10px 0 10px;white-space: pre;word-break: break-word;white-space: pre-wrap;word-wrap: break-word;">$_POST = '.print_r($_POST, 1).'</div>';
    if(isset($_COOKIE))
      echo '<div class="box" style="    margin: 10px 10px 0 10px;white-space: pre;word-break: break-word;white-space: pre-wrap;word-wrap: break-word;">$_COOKIE = '.print_r($_COOKIE, 1).'</div>';

    echo '<div class="box" style="    margin: 10px 10px 0 10px;">内存使用 = '.(memory_get_usage() / 1000).' kb</div>';
    if(isset($GLOBALS['START_TIME']))
      echo '<div class="box" style="    margin: 10px 10px 0 10px;">运行时间 = '.number_format(microtime(1) - $GLOBALS['START_TIME'], 4).' s</div>';
  }
?>

</body>
</html>