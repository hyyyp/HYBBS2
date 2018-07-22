<?php
define('INDEX_PATH' , str_replace('\\', '/', dirname(__FILE__)).'/');
if(is_file(INDEX_PATH . '../Conf/config.php')){
    $data = include INDEX_PATH . '../Conf/config.php';
    if(isset($data['DOMAIN_NAME']))
        die('你已经安装过,如果需要重装请将 /Conf/config.php删除');
}

!empty($_POST['name']) or die('请输入库名');
!empty($_POST['sqltype']) or die('请选择数据库类型');
!empty($_POST['ip']) or die('请输入 数据库IP');
!empty($_POST['username']) or die('请输入 数据库账号');


conn(array(
    'database_type'=>$_POST['sqltype'],
    'database_name'=>$_POST['name'],
    'server'=>$_POST['ip'],
    'username'=>$_POST['username'],
    'password'=>$_POST['password'],
    'charset'=>'utf8',
    'port'=>intval($_POST['port']),
));
function conn($arr)
{
    try {
        $commands = array();
        $dsn = '';
        
        if (isset($arr['port'])) {
            $port = $arr['port'];
        }
        $type = strtolower($arr['database_type']);
        $is_port = isset($port);
        $database_name = $arr['database_name'];
        $server = $arr['server'];
        $charset = $arr['charset'] = 'utf8';
        $username = $arr['username'];
        $password = $arr['password'];
        switch ($type) {
            case 'mariadb':
                $type = 'mysql';
            case 'mysql':
             
                    $dsn = $type . ':host=' . $server . ($is_port ? ';port=' . $port : '') . ';dbname=' . $database_name;
                
                // Make MySQL using standard quoted identifier
                $commands[] = 'SET SQL_MODE=ANSI_QUOTES';
                break;
            case 'pgsql':
                $dsn = $type . ':host=' . $server . ($is_port ? ';port=' . $port : '') . ';dbname=' . $database_name;
                break;
            case 'sybase':
                $dsn = 'dblib:host=' . $server . ($is_port ? ':' . $port : '') . ';dbname=' . $database_name;
                break;
            case 'oracle':
                $dbname = $server ? '//' . $server . ($is_port ? ':' . $port : ':1521') . '/' . $database_name : $database_name;
                $dsn = 'oci:dbname=' . $dbname . ($charset ? ';charset=' . $charset : '');
                break;
            case 'mssql':
                $dsn = strstr(PHP_OS, 'WIN') ? 'sqlsrv:server=' . $server . ($is_port ? ',' . $port : '') . ';database=' . $database_name : 'dblib:host=' . $server . ($is_port ? ':' . $port : '') . ';dbname=' . $database_name;
                // Keep MSSQL QUOTED_IDENTIFIER is ON for standard quoting
                $commands[] = 'SET QUOTED_IDENTIFIER ON';
                break;
            
        }

        if (in_array($type, explode(' ', 'mariadb mysql pgsql sybase mssql')) && $charset) {
            $commands[] = 'SET NAMES \'' . $charset . '\'';
        }
        $pdo = new PDO($dsn, $username, $password, array(PDO::ATTR_CASE => PDO::CASE_NATURAL));

        foreach ($commands as $value) {
            $pdo->exec($value);
        }
        echo 'sql success';
    } catch (PDOException $e) {
        //var_dump($e);
        echo $e->getMessage();
        //throw new Exception($e->getMessage());
    }
}