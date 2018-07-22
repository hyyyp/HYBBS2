<?php
namespace Action;
use HY\Action;
!defined('HY_PATH') && exit('HY_PATH not defined.');
class Forum extends HYBBS {
    public function __construct(){
		parent::__construct();
        //{hook a_forum_init}
		$left_menu = array('index'=>'','forum'=>'active');
		$this->v("left_menu",$left_menu);
	}
    public function index(){
        //{hook a_forum_index_v}
        $this->v("title","板块分类首页");
        $data = S("Forum")->select("*");
        $this->v("data",$data);
        $this->display('forum_index');
    }
    public function _no(){

        //{hook a_forum_empty_1}
        $fid = 0;
        if(strcmp(intval(METHOD_NAME) , METHOD_NAME) == 0){ //ID方式
            $fid = intval(METHOD_NAME);
            
        }
        else{ //非ID
            
            foreach ($this->_forum as $v) {

                if(strcasecmp($v['name2'] , METHOD_NAME) == 0)
                {
                    $fid = $v['id'];
                    
                    break;
                }
            }
        }

        if(!isset($this->_forum[$fid]))
            return $this->message("没有此分类!");

        if(!L("Forum")->is_comp($fid,NOW_GID,'vforum',$this->_forum[$fid]['json']))
            return $this->message("你没有权限浏览此分类");

        //{hook a_forum_empty_2}
        //分页ID
        $pageid=intval(isset($_GET['HY_URL'][3]) ? $_GET['HY_URL'][3] : 1) or $pageid=1;
        //类型ID
        $type = (isset($_GET['HY_URL'][2]) ? $_GET['HY_URL'][2] : 'new') or $type='new';
        //echo $type;
        $type = strtolower($type);
        if($type != 'new' && $type != 'btime')
			$type='';

        //{hook a_forum_empty_3}
        $this->v("type",$type);

        $desc = ['tid'=>'DESC'];
		if($type == 'btime')
			$desc = ['btime'=>'DESC']; //最新回复


        //{hook a_forum_empty_33}
        //获取 主题列表
        $data           = $this->CacheObj->get("forum_data_{$fid}_{$pageid}_{$type}");
        $top_data       = $this->CacheObj->get("top_data_2");
        $forum_top_data = $this->CacheObj->get("forum_top_id_".$fid);
        if(empty($data) || empty($top_data) || empty($forum_top_data) || DEBUG){
            $Thread = M("Thread");
        }
        //{hook a_forum_empty_34}
        if(empty($data) || DEBUG){
            //{hook a_forum_empty_4}
            
            $data = $Thread->get_forum_thread_list($fid,$pageid,$this->conf['forumlist'],$desc);
            $Thread->format($data);
            foreach ($data as $key => $value) {
                if($value['top'] != 0)
                    unset($data[$key]);
            }
            
            $this->CacheObj->set("forum_data_{$fid}_{$pageid}_{$type}",$data);
        }
		
        //{hook a_forum_empty_5}
        //获取全站置顶缓存
        if(empty($top_data) || DEBUG){
            //{hook a_forum_empty_55}
            //全局置顶
            $top_data = $Thread->get_top_thread();
            //格式数据显示
            $Thread->format($top_data);
            //写入缓存
            $this->CacheObj->set("top_data_2",$top_data);
        }
        //End
        $this->v("top_list",$top_data);

        //{hook a_forum_empty_6}
        //获取板块置顶缓存
        
        if(empty($forum_top_data) || DEBUG){
            //{hook a_forum_empty_66}
            //全局置顶
            $forum_top_data = $Thread->get_forum_top_thread($fid);
            //格式数据显示
            $Thread->format($forum_top_data);
            //写入缓存
            $this->CacheObj->set("forum_top_id_".$fid,$forum_top_data);
        }

        $this->v("top_f_data",$forum_top_data);
        //{hook a_forum_empty_7}

		$count = $this->_forum[$fid]['threads'];
		$count = (!$count)?1:$count;
		$page_count = ($count % $this->conf['forumlist'] != 0)?(intval($count/$this->conf['forumlist'])+1) : intval($count/$this->conf['forumlist']);
        //{hook a_forum_empty_v}
        $this->v("title",$this->_forum[$fid]['name']);
		$this->v("pageid",$pageid);
		$this->v("page_count",$page_count);
		$this->v("data",$data);
        $this->v("fid",$fid);

		$this->display('forum_thread');
    }
    //{hook a_forum_fun}
    
}
