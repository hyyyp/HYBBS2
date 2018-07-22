<?php
namespace HY;
use PDO;
/**
 * Model.php Model
*/

// class HY_MODEL{
//     static public $pdo =false;
//     static public $database;
// }

class Model {
    public $pdo = false; 
    public $table;
    function __construct($name,$more_sql_name = '') {
        static $pdo_obj =array();
        $sql_more_bool = false;
        if($more_sql_name){
            $sql_more = C('SQL_MORE.'.$more_sql_name);

            if(!empty($sql_more))
                $sql_more_bool =true;
        }
        $database_type = $sql_more_bool ? $sql_more['SQL_TYPE'] : C("SQL_TYPE");
        $database_name = $sql_more_bool ? $sql_more['SQL_NAME'] : C("SQL_NAME");
        $server = $sql_more_bool ? $sql_more['SQL_IP'] : C("SQL_IP");
        $key = $more_sql_name;//substr(md5($database_type . $database_name . $server),0,6);
        $this->table = $name;
        if(!isset($pdo_obj[$key])){
            $username = $sql_more_bool ? $sql_more['SQL_USER'] : C("SQL_USER");
            $password = $sql_more_bool ? $sql_more['SQL_PASS'] : C("SQL_PASS");
            $charset = $sql_more_bool ? $sql_more['SQL_CHARSET'] : C("SQL_CHARSET");
            $port = $sql_more_bool ? $sql_more['SQL_PORT'] : C("SQL_PORT");
            $prefix = $sql_more_bool ? strtolower($sql_more['SQL_PREFIX']) : strtolower(C("SQL_PREFIX"));
            $option = $sql_more_bool ? $sql_more['SQL_OPTION'] : C("SQL_OPTION");
            $a = microtime(TRUE);
            $pdo_obj[$key] = new \HY\Lib\Medoo(array(
                // 必须配置项
                'database_type' => $database_type,
                'database_name' => $database_name,
                'server'        => $server,
                'username'      => $username,
                'password'      => $password,
                'charset'       => $charset,
                // 可选参数
                'port'          => $port,
                // 可选，定义表的前缀
                'prefix'        => $prefix,
                // 连接参数扩展, 更多参考 http://www.php.net/manual/en/pdo.setattribute.php
                'option'        => $option,

            ));
            $GLOBALS['SQL_LOG'][] = '连接'.$database_type.'::'.$database_name.'数据库 [耗时] ' .round(microtime(TRUE) - $a, 4).'ms';
        }

        $this->pdo = $pdo_obj[$key] ;
    }
    // 插入数据 array('user'=>$user)
    public function insertAll($columns ,$datas){
        return $this->pdo->insertAll($this->table,$columns, $datas);
    }
    public function insert($data){
        return $this->pdo->insert($this->table, $data);
    }
    //查询数据 要查询的字段名.    查询的条件.
    public function select($join, $columns = null, $where = null){
        return $this->pdo->select($this->table,$join,$columns,$where);
        
    }
    public function update($data, $where=null){
        return $this->pdo->update($this->table,$data,$where);
    }
    public function delete($where){
        return $this->pdo->delete($this->table,$where);
    }
    public function get($join = null, $column = null, $where = null){
        return $this->pdo->get($this->table,$join,$column,$where);
        
    }
    public function find($join = null, $column = null, $where = null){
        return $this->pdo->get($this->table,$join,$column,$where);
        
    }
    public function replace($columns, $search = null, $replace = null, $where = null){
        return $this->pdo->replace($this->table,$columns, $search, $replace, $where);
    }
    public function has($join, $where=null){
        return $this->pdo->has($this->table,$join, $where);
    }
    public function count($join=null, $column=null, $where=null){
        return $this->pdo->count($this->table,$join, $column, $where);
    }
    public function max($join, $column = null, $where = null){
        return $this->pdo->max($this->table,$join, $column, $where);
    }
    public function min($join, $column = null, $where = null){
        return $this->pdo->min($this->table, $join, $column, $where);
    }
    public function avg($join, $column = null, $where = null){
        return $this->pdo->avg($this->table, $join, $column, $where);
    }
    public function sum($join, $column = null, $where = null){
        return $this->pdo->sum($this->table, $join, $column, $where);
    }
    public function action($actions){
        return $this->pdo->action($actions);
    }
    public function query($query){
        return $this->pdo->query($query);
    }
    public function quote($string){
        return $this->pdo->quote($string);
    }
    public function debug(){
        $this->pdo->debug();
        return $this;
    }
    public function id(){
        return $this->pdo->id();
    }



}
