<?php
namespace Action;
use HY\Action;
!defined('HY_PATH') && exit('HY_PATH not defined.');
class Search extends HYBBS {
    public function __construct() {
		parent::__construct();
        //{hook a_search_init}
        $this->view = IS_MOBILE ? $this->conf['wap_search'] : $this->conf['pc_search'];
        //{hook a_search_init_1}
    }
    public function _no(){
        //{hook a_search_empty}
        $this->index();
    }
    public function index(){
        //{hook a_search_index_0}
        $key = X("get.key");
        if(empty($key)) 
            $key='';
            //return $this->message("请输入关键字");
        $key = htmlspecialchars($key);
        $this->v("key",$key);
        $this->v("search_key",$key);

        


        //{hook a_search_index_1}
        $this->v('title',$key.' 搜索');

        //分页ID
        $pageid=intval(X('get.pageid')) or $pageid=1;

        $user_list =array();
        $forum_list = array();
        if($pageid == 1){
            if(mb_strlen($key) >= 2){
                $User = M('User');
                $user_list = (array)$User->select(array('user','uid'),array('user[~]'=>$key,'ORDER'=>['uid'=>'DESC']));
                foreach ($user_list as &$v) {
                    $v['avatar'] = $this->avatar($v['user']);
                }
            }

            $forum_list = (array)S('Forum')->select(array('name','id'),array('name[~]'=>$key,'LIMIT'=>10));

        }
        $this->v('user_list',$user_list);
        $this->v('forum_list',$forum_list);

        $type = intval(X('get.type'));
        $this->v("type",$type);

        if(mb_strlen($key) < $this->conf['search_key_size']){
            $this->v("pageid",$pageid);
            $this->v("page_count",0);
            $this->v("data",array());
            $this->v('message','搜索的关键字长度不能少于'.$this->conf['search_key_size'].'位');
            return $this->display('search_index');
        }
        
        //{hook a_search_index_2}
        $Thread = M("Thread");
        
        $data=array();
        $page_count = 0;
        if($type == 2){
            $data = $Thread->select(
                array(
                    "[>]post" => array( "pid" => "pid" ), //thread.pid == post.pid
                ),
                array(
                    'thread.tid',
                    'thread.title',
                    'thread.uid',
                    'thread.summary',
                    'thread.hide',
                    'thread.gold',
                    'post.content',
                    'thread.goods',
                    'thread.nos',
                    'thread.views',
                    'thread.img_count',
                    'thread.files',
                    'thread.atime',
                ),
                array(
                    'AND'=>array(
                        'isthread'=>1,
                        'OR'=>array(
                            'thread.title[~]'=>$key,
                            'post.content[~]'=>$key
                        )
                    ),
                    'ORDER'=> ['tid'=>'DESC'],
                    "LIMIT" => array(($pageid-1) * $this->conf['searchlist'], $this->conf['searchlist'])
                )
            );

            $page_count = $Thread->count(
                array(
                    "[>]post" => array( "pid" => "pid"), //thread.pid == post.pid
                ),
                '*',
                array('AND'=>array(
                    'isthread'=>1,'OR'=>array(
                        'thread.title[~]'=>$key,
                        'post.content[~]'=>$key
                    )))
            );
            
        }else{
            $data = $Thread->select('*',array(
                'title[~]'=>$key,
                'ORDER'=> ['tid'=>'DESC'],
                'LIMIT' => array(($pageid-1) * $this->conf['searchlist'], $this->conf['searchlist'])
            ));
            $page_count = $Thread->count(array(
                'title[~]'=>$key
            ));
            foreach ($data as &$v) {
                $v['content'] = $v['summary'];
            }
        }
        
        if(empty($data)){
            $this->v('message','没有搜索到相关内容');
            $data=array();
        }





        //$Thread->format($data);
        //{hook a_search_index_3}
        $t=$key;

        $tmp_avatar = 'public/images/user.gif';
        foreach ($data as $i => &$v) {
            $v['title'] = preg_replace("/({$key})/is",'<font color="red">$1</font>',$v['title']);
            $tmp = strip_tags($v['content']);

            if((empty($tmp) || stripos($tmp, $key) === false) && stripos($v['title'], $key) === false){
                unset($data[$i]);
                continue;
            }
            //临时解决
            $v['avatar']=array('a'=>$tmp_avatar,'b'=>$tmp_avatar,'c'=>$tmp_avatar);
            $v['user']='';
            $v['buser']='';
            //
            
            if(empty($tmp))
                $tmp = '...';


            if(mb_strlen($tmp) > 120){
                $length = mb_strpos($tmp,$key);
                $v['content'] = mb_substr($tmp, $length, 120) . '...';
            }else{
                $v['content'] = $tmp;
            }
            
            
            $v['content'] = preg_replace("/({$key})/is",'<font color="red">$1</font>',$v['content']);
            
            
        }

        //{hook a_search_index_v}
        $this->v("pageid",$pageid);
        $this->v("page_count",$page_count);
        $this->v("data",$data);
        $this->display('search_index');
    }
    //{hook a_search_fun}
}
