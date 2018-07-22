<?php
namespace Action;
use HY\Action;
!defined('HY_PATH') && exit('HY_PATH not defined.');
class Index extends HYBBS {
	public function __construct(){
		parent::__construct();
		//{hook a_index_init}
	}
	public function Index(){
		//{hook a_index_index_1}
		$this->v('title',$this->conf['title']);

		$pageid	= intval(X('get.pageid')) 	or $pageid=1;
		$type 	= X('get.type') 			or $type='New';
		
		if($type != 'New' && $type != 'Btime')
			$type='';
		$this->v("type",strtolower($type));
		$Thread = M("Thread");
		$desc = ['tid' => 'DESC'];
		if($type == 'Btime')
			$desc = ['btime'=>'DESC']; //最新回复

		//{hook a_index_index_11}
		$thread_list = $this->CacheObj->get("index_index_".$type.'_'.$pageid);

		//获取主题列表
		if(empty($thread_list) || DEBUG){
			//{hook a_index_index_22}
			$thread_list = $Thread->get_thread_list($pageid,$this->conf['homelist'],$desc);
			$Thread->format($thread_list);
			foreach ($thread_list as $key => $value) {
				if($value['top'] == 2)
					unset($thread_list[$key]);
			}
			$this->CacheObj->set("index_index_".$type.'_'.$pageid,$thread_list);
		}
		//{hook a_index_index_2}

		//获取置顶缓存
		$top_data=$this->CacheObj->get("top_data_2");
		if(empty($top_data) || DEBUG){
			//{hook a_index_index_33}
			//全局置顶
	        $top_data = $Thread->get_top_thread();
	        //格式数据显示
	        $Thread->format($top_data);
	        //写入缓存
			$this->CacheObj->set("top_data_2",$top_data);
		}
		//End
		//{hook a_index_index_3}


		$count = $this->_count['thread'];
		$count = (!$count)?1:$count;
		$page_count = ($count % $this->conf['homelist'] != 0)?(intval($count/$this->conf['homelist'])+1) : intval($count/$this->conf['homelist']);
		
		//{hook a_index_index_v}

		$this->v("pageid",$pageid);
		$this->v("page_count",$page_count);
		$this->v("thread_list",$thread_list);
		$this->v("top_list",$top_data);

		$this->display('index_index');
	}
	
	
	//{hook a_index_fun}
}
