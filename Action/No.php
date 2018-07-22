<?php
namespace Action;
use HY\Action;
!defined('HY_PATH') && exit('HY_PATH not defined.');
class No extends HYBBS {
    public function __construct(){
        //parent::__construct();
        //{hook a_empty_init}
    }
    public function index(){
        //{hook a_empty_index_v}
        $_GET['type'] = ACTION_NAME;
        if($_GET['type'] != 'New' && $_GET['type'] != 'Btime' && preg_match("/^[0-9a-zA-Z]+$/",ACTION_NAME))
            E('不存在该页面',false);

        $_GET['pageid'] = intval(isset($_GET['HY_URL'][1]) ? $_GET['HY_URL'][1] : 1) or $pageid=1;
        A("Index")->Index();
    }
    public function _no(){
        //{hook a_empty_empty_v}
        $_GET['type'] = ACTION_NAME;
        if($_GET['type'] != 'New' && $_GET['type'] != 'Btime' && preg_match("/^[0-9a-zA-Z]+$/",ACTION_NAME))
            E('不存在该页面',false);
        
        $_GET['pageid'] = intval(isset($_GET['HY_URL'][1]) ? $_GET['HY_URL'][1] : 1) or $pageid=1;
        A("Index")->Index();
    }
    //{hook a_empty_fun}
}