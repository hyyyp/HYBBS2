<?php
namespace Action;
use HY\Action;
!defined('HY_PATH') && exit('HY_PATH not defined.');
use PDO;

class Inst extends Action {
    public $state;
    // public function index(){
    //     if(C('DOMAIN_NAME')){
    //       header("location: " . C('DOMAIN_NAME'));
    //       exit;
    //     }
    //     //$this->view = 'install';
    //     //$this->display('index');
    // }
    private function app_text($str){
      $this->state.='<p><i class="fa fa-check"></i> '.$str.'</p>';
    }
    
    public function install(){
        die('install');
    }
    public function rex(){
      $DOMAIN_NAME = C('DOMAIN_NAME');
      
        if(!empty($DOMAIN_NAME)){
          if(IS_AJAX)
          $this->json(array('error'=>false,'info'=>'你已经安装过,如果需要重装请将 /Conf/config.php删除'));
            else
          die('你已经安装过,如果需要重装请将 /Conf/config.php删除');
        }
        $bbs_user = X('post.bbs_user');
        $bbs_pass = X('post.bbs_pass');
        $email = X('post.email');
        $www = X('post.www');
        !empty($bbs_user) or $this->json(array('error'=>false,'info'=>'请输入管理员用户名'));
        !empty($bbs_pass) or $this->json(array('error'=>false,'info'=>'请输入管理员密码 (最少6位)'));
        !empty($email) or $this->json(array('error'=>false,'info'=>'请输入管理员邮箱'));
        !empty($www) or $this->json(array('error'=>false,'info'=>'请输入网站域名'));


        
        return $sql = new \HY\Lib\Medoo(array(
            // 必须配置项
            'database_type' => X("post.sqltype"),
            'database_name' => X("post.name"),
            'server' => X("post.ip"),
            'username' => X("post.username"),
            'password' => X("post.password"),
            'charset' => 'utf8',
            // 可选参数
            'port' => X("post.port"),
            // 可选，定义表的前缀
            'prefix' => 'hy_',
        ));
    }
    public function index(){
        // if(C('DOMAIN_NAME')){
        //   header("location: " . C('DOMAIN_NAME'));
        //   exit;
        // }
        
        $sql = $this->rex();
        $table_type = X("post.table_type");

        $gn = X('post.gn');

if($gn == 1){
    $salt = substr(md5(mt_rand(10000000, 99999999).NOW_TIME), 0, 8);
    $result = $sql->query("
DROP TABLE IF EXISTS hy_cache;
DROP TABLE IF EXISTS hy_chat;
DROP TABLE IF EXISTS hy_chat_count;
DROP TABLE IF EXISTS hy_count;
DROP TABLE IF EXISTS hy_file;
DROP TABLE IF EXISTS hy_filegold;
DROP TABLE IF EXISTS hy_fileinfo;
DROP TABLE IF EXISTS hy_forum;
DROP TABLE IF EXISTS hy_forum_group;
DROP TABLE IF EXISTS hy_friend;
DROP TABLE IF EXISTS hy_log;
DROP TABLE IF EXISTS hy_online;
DROP TABLE IF EXISTS hy_post;
DROP TABLE IF EXISTS hy_post_post;
DROP TABLE IF EXISTS hy_thread;
DROP TABLE IF EXISTS hy_threadgold;
DROP TABLE IF EXISTS hy_user;
DROP TABLE IF EXISTS hy_usergroup;
DROP TABLE IF EXISTS hy_vote_post;
DROP TABLE IF EXISTS hy_vote_thread;
DROP TABLE IF EXISTS hy_file_type;
DROP TABLE IF EXISTS hy_thread_star;






    --
    -- 表的结构 `hy_cache`
    --

    CREATE TABLE `hy_cache` (
    `cachekey` varchar(255) NOT NULL,
    `expire` int(11) NOT NULL,
    `data` blob,
    `datacrc` int(32) DEFAULT NULL,
    UNIQUE KEY `cachekey` (`cachekey`)
    ) ENGINE={$table_type} DEFAULT CHARSET=utf8;

    -- --------------------------------------------------------

    --
    -- 表的结构 `hy_chat`
    --

    CREATE TABLE `hy_chat` (
    `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `uid1` int(10) UNSIGNED NOT NULL,
    `uid2` int(10) UNSIGNED NOT NULL,
    `content` tinytext,
    `atime` int(10) UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    KEY `uid1` (`uid1`,`uid2`)
    ) ENGINE={$table_type} DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

    -- --------------------------------------------------------

    --
    -- 表的结构 `hy_chat_count`
    --

    CREATE TABLE `hy_chat_count` (
    `uid` int(10) UNSIGNED NOT NULL,
    `c` int(11) NOT NULL DEFAULT '0',
    `atime` int(10) UNSIGNED NOT NULL,
    UNIQUE KEY `uid` (`uid`)
    ) ENGINE={$table_type} DEFAULT CHARSET=utf8;

    -- --------------------------------------------------------

    --
    -- 表的结构 `hy_count`
    --

    CREATE TABLE `hy_count` (
        `name` varchar(12) NOT NULL,
        `v` int(11) NOT NULL DEFAULT '0'
    ) ENGINE={$table_type} DEFAULT CHARSET=utf8;
    INSERT INTO `hy_count` (`name`, `v`) VALUES
    ('A1.0', 1),
    ('A1.1', 1),
    ('A1.2', 1),
    ('1.5', 1),
    ('1.5.1', 1),
    ('1.5.27', 1),
    ('1.5.33', 1),
    ('2.0', 1),
    ('2.0.12', 1),
    ('2.0.17', 1),
    ('2.0.20', 1),
    ('2.1.0', 1),
    ('2.1.3', 1),
    ('2.2', 1),
    ('2.2.1', 1),
    ('thread', 0);


    -- --------------------------------------------------------

    --
    -- 表的结构 `hy_file`
    --

    CREATE TABLE `hy_file` (
    `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '附件ID',
    `uid` int(10) UNSIGNED NOT NULL COMMENT '附件主人UID',
    `tid` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `pid` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `filename` text COMMENT '附件名称',
    `md5name` text COMMENT '附件随机名',
    `md5` char(32) DEFAULT NULL,
    `filesize` int(10) UNSIGNED NOT NULL COMMENT '文件大小',
    `file_type` int(11) NOT NULL DEFAULT '0',
    `atime` int(10) UNSIGNED NOT NULL COMMENT '添加时间',
    PRIMARY KEY (`id`,`uid`) USING BTREE,
    KEY `tid` (`tid`),
    KEY `pid` (`pid`),
    UNIQUE KEY `md5` (`md5`) USING BTREE,
    UNIQUE KEY `uid_md5` (`uid`,`md5`)
    ) ENGINE={$table_type} DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

    -- --------------------------------------------------------

    --
    -- 表的结构 `hy_filegold`
    --

    CREATE TABLE `hy_filegold` (
    `uid` int(10) UNSIGNED NOT NULL,
    `fileid` int(10) UNSIGNED NOT NULL,
    PRIMARY KEY (`uid`,`fileid`) USING BTREE
    ) ENGINE={$table_type} DEFAULT CHARSET=utf8;

    -- --------------------------------------------------------

    --
    -- 表的结构 `hy_fileinfo`
    --

    CREATE TABLE `hy_fileinfo` (
    `fileid` int(10) UNSIGNED NOT NULL,
    `tid` int(10) UNSIGNED NOT NULL,
    `uid` int(10) UNSIGNED NOT NULL,
    `gold` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `hide` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
    `downs` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `mess` text,
    PRIMARY KEY (`fileid`) USING BTREE,
    KEY `tid` (`tid`) USING BTREE,
    KEY `uid` (`uid`) USING BTREE
    ) ENGINE={$table_type} DEFAULT CHARSET=utf8;

    -- --------------------------------------------------------

    --
    -- 表的结构 `hy_forum`
    --

    CREATE TABLE `hy_forum` (
    `id` int(10) UNSIGNED NOT NULL,
    `fid` int(10) NOT NULL DEFAULT '-1',
    `fgid` int(10) UNSIGNED NOT NULL DEFAULT '1',
    `name` varchar(12) NOT NULL,
    `name2` varchar(18) NOT NULL,
    `threads` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `posts` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `forumg` text,
    `json` text,
    `html` longtext,
    `color` varchar(30) NOT NULL DEFAULT '',
    `background` varchar(30) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`) USING BTREE,
    KEY `fid` (`fid`)
    ) ENGINE={$table_type} DEFAULT CHARSET=utf8;

    INSERT INTO `hy_forum` (`id`, `fid`, `name`,`name2`, `threads`) VALUES
    (1, -1, '默认分类','morenfenlei', 0);

    -- --------------------------------------------------------

    --
    -- 表的结构 `hy_forum_group`
    --

    CREATE TABLE `hy_forum_group` (
    `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` varchar(32) NOT NULL,
    PRIMARY KEY (`id`) USING BTREE
    ) ENGINE={$table_type} DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

    -- --------------------------------------------------------

    --
    -- 表的结构 `hy_friend`
    --

    CREATE TABLE `hy_friend` (
    `uid1` int(10) UNSIGNED NOT NULL,
    `uid2` int(10) UNSIGNED NOT NULL,
    `c` int(11) NOT NULL DEFAULT '0',
    `atime` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `state` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (`uid1`,`uid2`) USING BTREE,
    KEY `uid2` (`uid2`,`state`) USING BTREE
    ) ENGINE={$table_type} DEFAULT CHARSET=utf8;

    -- --------------------------------------------------------

    --
    -- 表的结构 `hy_log`
    --

    CREATE TABLE `hy_log` (
    `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `uid` int(10) UNSIGNED NOT NULL,
    `gold` int(11) NOT NULL DEFAULT '0',
    `credits` int(11) NOT NULL DEFAULT '0',
    `content` varchar(32) NOT NULL DEFAULT '',
    `atime` int(10) UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    KEY `uid` (`uid`)
    ) ENGINE={$table_type} DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

    -- --------------------------------------------------------

    --
    -- 表的结构 `hy_online`
    --

    CREATE TABLE `hy_online` (
    `uid` int(10) UNSIGNED NOT NULL,
    `user` char(18) NOT NULL,
    `gid` int(10) UNSIGNED NOT NULL,
    `atime` int(10) UNSIGNED NOT NULL,
    PRIMARY KEY (`uid`) USING BTREE
    ) ENGINE={$table_type} DEFAULT CHARSET=utf8;

    -- --------------------------------------------------------

    --
    -- 表的结构 `hy_post`
    --

    CREATE TABLE `hy_post` (
    `pid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `tid` int(10) UNSIGNED NOT NULL,
    `fid` int(10) UNSIGNED NOT NULL,
    `uid` int(10) UNSIGNED NOT NULL,
    `rpid` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `isthread` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
    `content` longtext,
    `atime` int(10) UNSIGNED NOT NULL,
    `etime` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `euid` int(10) UNSIGNED DEFAULT '0',
    `goods` int(10) UNSIGNED DEFAULT '0',
    `nos` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `posts` int(10) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (`pid`) USING BTREE,
    KEY `tid` (`tid`),
    KEY `uid` (`uid`)
    ) ENGINE={$table_type} DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

    CREATE TABLE `hy_post_post` (
      `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
      `pid` int(10) UNSIGNED NOT NULL,
      `tid` int(10) UNSIGNED NOT NULL,
      `uid` int(10) UNSIGNED NOT NULL,
      `content` longtext,
      `atime` int(10) UNSIGNED NOT NULL,
      `goods` int(10) UNSIGNED DEFAULT '0',
      `nos` int(10) UNSIGNED NOT NULL DEFAULT '0',
        UNIQUE KEY `id` (`id`),
        KEY `pid` (`pid`)
    ) ENGINE={$table_type} DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


    -- --------------------------------------------------------

    --
    -- 表的结构 `hy_thread`
    --

    CREATE TABLE `hy_thread` (
    `tid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `fid` int(10) UNSIGNED NOT NULL,
    `uid` int(10) UNSIGNED NOT NULL COMMENT 'user_id',
    `pid` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `title` char(128) NOT NULL,
    `summary` text,
    `atime` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `etime` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `euid` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `btime` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `buid` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `views` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'view_size',
    `posts` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'post_size',
    `goods` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `nos` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `img` text,
    `img_count` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
    `top` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
    `files` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '附件数量',
    `hide` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
    `digest` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
    `gold` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `state` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (`tid`) USING BTREE,
    KEY `uid` (`uid`),
    KEY `fid` (`fid`),
    KEY `top` (`top`),
    KEY `posts` (`posts`),
    KEY `btime` (`btime`),
    KEY `views` (`views`)
    ) ENGINE={$table_type} DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

    -- --------------------------------------------------------

    --
    -- 表的结构 `hy_threadgold`
    --

    CREATE TABLE `hy_threadgold` (
    `uid` int(10) UNSIGNED NOT NULL,
    `tid` int(10) UNSIGNED NOT NULL,
    PRIMARY KEY (`uid`,`tid`) USING BTREE
    ) ENGINE={$table_type} DEFAULT CHARSET=utf8;

    -- --------------------------------------------------------

    --
    -- 表的结构 `hy_user`
    --

    CREATE TABLE `hy_user` (
    `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user` varchar(18) NOT NULL,
    `pass` varchar(32) NOT NULL,
    `email` varchar(100) NOT NULL,
    `salt` varchar(8) NOT NULL,
    `threads` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `posts` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `post_ps` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `atime` int(10) UNSIGNED NOT NULL,
    `gid` smallint(2) UNSIGNED NOT NULL DEFAULT '0',
    `gold` int(11) NOT NULL DEFAULT '0' COMMENT '金钱',
    `credits` int(11) NOT NULL DEFAULT '0',
    `etime` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `ps` varchar(40) DEFAULT '',
    `fans` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `follow` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `ctime` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `file_size` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `chat_size` int(10) UNSIGNED NOT NULL DEFAULT '0',
    `ban_post` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
    `ban_login` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (`uid`) USING BTREE,
    UNIQUE KEY `user` (`user`) USING BTREE,
    UNIQUE KEY `email` (`email`) USING BTREE,
    KEY `gid` (`gid`)
    ) ENGINE={$table_type} DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

    INSERT INTO `hy_user` (`uid`, `user`, `pass`, `email`, `salt`, `threads`, `posts`, `atime`, `gid`) VALUES
    (1, '".X("post.bbs_user")."', '".L("User")->md5_md5(X("post.bbs_pass"),$salt)."', '".X("post.email")."', '".$salt."', 0, 0, ".NOW_TIME.", 1);

    -- --------------------------------------------------------

    --
    -- 表的结构 `hy_usergroup`
    --

    CREATE TABLE `hy_usergroup` (
    `gid` int(10) UNSIGNED NOT NULL,
    `credits` int(11) NOT NULL DEFAULT '-1',
    `credits_max` int(11) NOT NULL DEFAULT '-1',
    `space_size` int(10) UNSIGNED NOT NULL DEFAULT '4294967295',
    `chat_size` int(10) UNSIGNED NOT NULL DEFAULT '4294967295',
    `name` varchar(12) NOT NULL,
    `font_color` varchar(30) NOT NULL DEFAULT '',
    `font_css` longtext,
    `json` text,
    PRIMARY KEY (`gid`) USING BTREE
    ) ENGINE={$table_type} DEFAULT CHARSET=utf8;

    INSERT INTO `hy_usergroup` (`gid`, `space_size`, `chat_size`, `name`, `json`) VALUES
    (1, 4294967295, 4294967295, '管理员', '{\"uploadfile\":1,\"down\":1,\"del\":1,\"upload\":1,\"mess\":1,\"post\":1,\"thread\":1,\"tgold\":1,\"thide\":1,\"nogold\":0}'),
    (2, 4294967295, 4294967295, '新用户', '{\"down\":1,\"uploadfile\":1,\"del\":1,\"upload\":1,\"mess\":1,\"post\":1,\"thread\":1,\"nogold\":0,\"thide\":1,\"tgold\":1}'),
    (3, 4294967295, 4294967295, '游客', '{\"down\":1,\"uploadfile\":1,\"del\":1,\"upload\":1,\"mess\":1,\"post\":1,\"thread\":1,\"nogold\":0,\"thide\":1,\"tgold\":1}');

    -- --------------------------------------------------------

    --
    -- 表的结构 `hy_vote_post`
    --

    CREATE TABLE `hy_vote_post` (
    `uid` int(10) UNSIGNED NOT NULL,
    `pid` int(10) UNSIGNED NOT NULL,
    `atime` int(10) UNSIGNED NOT NULL,
    PRIMARY KEY (`uid`,`pid`) USING BTREE
    ) ENGINE={$table_type} DEFAULT CHARSET=utf8;

    -- --------------------------------------------------------

    --
    -- 表的结构 `hy_vote_thread`
    --

    CREATE TABLE `hy_vote_thread` (
    `uid` int(10) NOT NULL,
    `tid` int(10) NOT NULL,
    `atime` int(10) NOT NULL,
    PRIMARY KEY (`uid`,`tid`) USING BTREE
    ) ENGINE={$table_type} DEFAULT CHARSET=utf8;

    -- --------------------------------------------------------

    --
    -- 表的结构 `hy_file_type`
    --

    CREATE TABLE `hy_file_type` ( 
    `id` INT NOT NULL , 
    `name` VARCHAR(12) NOT NULL , 
    UNIQUE KEY `id` (`id`)
    ) ENGINE={$table_type} DEFAULT CHARSET=utf8;

    INSERT INTO `hy_file_type` (`id`, `name`) VALUES ('0', '未知'),('1', '图片'), ('2', '附件'), ('3', '视频'), ('4', '音频');

    CREATE TABLE `hy_thread_star` (
      `uid` int(10) UNSIGNED NOT NULL,
      `tid` int(10) UNSIGNED NOT NULL,
      `atime` int(10) UNSIGNED NOT NULL,
      UNIQUE KEY `uid_tid` (`uid`,`tid`),
      KEY `atime` (`atime`)
    ) ENGINE={$table_type} DEFAULT CHARSET=utf8;
    
    ");
    if($result->errorCode() ==0)
        $this->json(['error'=>true,'info'=>'创建数据表完成']);
    else
        $this->json(['error'=>false,'info'=>$result->errorInfo()[2]]);
}

if($gn == 2){

    //$sql->query("");
    $this->json(array('error'=>true,'info'=>'创建索引完成'));
}
if($gn == 3){
    
    $content = @file_get_contents(INDEX_PATH . 'Conf/config.back');
        if($content === false)
          $this->json(array('error'=>false,'info'=>'/Conf无读取权限'));
        $str = rand_str(16);
        $content = str_replace(

          array(
            'MYSQL_NAME',
            'MYSQL_IP',
            'MYSQL_USER',
            'MYSQL_PASS',
            'MYSQL_PORT',
            'http://127.0.0.1',
            'sql_typee',
            '1234567890',
            'SQL_STORAGE_ENGINE_VALUE',
            'TMP_PATH_KEY_VALUE',
            'TMP_PATH_PREFIX_VALUE',
          ),
          array(
            X("post.name"),
            X("post.ip"),
            X("post.username"),
            X("post.password"),
            X("post.port"),
            trim(X("post.www"),'/'),
            X("post.sqltype"),
            $str,
            $table_type,
            rand_str(16),
            rand_str(8),


          ),$content
        );

        

        if(@file_put_contents(INDEX_PATH . 'Conf/config.php',$content) === false)
          $this->json(array('error'=>false,'info'=>'/Conf无写入权限'));

    $this->json(array('error'=>true,'info'=>'创建AUTO_INCREMENT完成','url'=>trim(X("post.www"),'/') . '/?s='));
}
  //$this->json(array('error'=>false,'info'=>'创建SQL失败'));

//$this->app_text('Insert Data success');




      

      //if(is_file(ACTION_PATH . 'Install.php'))
          //rename(ACTION_PATH . 'Install.php' , ACTION_PATH . 'Install.php.back');
      
      //$this->json(array('error'=>true,'info'=>$this->state,'url'=>(X("post.https")=='on'?'https://':'http://').trim(X("post.www"),'/') ));
      


        //echo X("post.name");
    }

}
