<?php
namespace Action;
use HY\Action;
!defined('HY_PATH') && exit('HY_PATH not defined.');
class Api extends HYBBS {
	public function index(){
		$size = X('get.size',10);
		$Thread = M('Thread');
		$data = $Thread->get_thread_list(1,$size);
		if(empty($data)){
			$this->jsonp(['error'=>false,'info'=>'无数据']);
		}
		else{
			$Thread->format($data);

			foreach ($data as $key => &$v) {
				$v['url']=HYBBS_URLA('Thread',$v['tid']);
				$v['forum_name'] = $this->_forum[$v['fid']]['name'];
			}
			$this->jsonp(['error'=>true,'info'=>$data]);
		}
	}
}