<?php !defined('HY_PATH') && exit('HY_PATH not defined.'); ?>
    {if ACTION_NAME != 'Post'}
    <div class="right" style="float: right;width: 300px;">
        <!--{hook right_box_top}-->
        {if view_form('hybbs','forum_right')}
        {php isset($fid) || $fid = -1;}
        {if ACTION_NAME == 'Thread'}{php $fid = $thread_data['fid']}{/if}
        {if (ACTION_NAME == 'Thread' || ACTION_NAME == 'Forum') && isset($forum[$fid]['z'])}
        <div class="right-widget only-logo">
            
            <ul id="right-forum-list">
                {if $forum[$fid]['fid'] != -1}
                <li>
                    <a href="{php HYBBS_URL('forum',$forum[$forum[$fid]['fid']]['id']);}">
                        <img src="{#WWW}upload/forum{$forum[$forum[$fid]['fid']]['id']}.png" onerror="this.src='{#WWW}upload/de.png'">
                        返回：{$forum[$forum[$fid]['fid']]['name']} <i class="iconfont icon-right2 pull-right"></i>
                    </a>
                </li>
                {else}
                <li>
                    <a href="{#WWW}">
                        <img src="{#WWW}View/hybbs/forum_home.png">
                        返回首页 <i class="iconfont icon-right2 pull-right"></i>
                    </a>
                </li>
                {/if}
                <li>
                    <a class="active" href="{php HYBBS_URL('forum',$fid);}">
                        <img src="{#WWW}upload/forum{$fid}.png" onerror="this.src='{#WWW}upload/de.png'">
                        {$forum[$fid]['name']} <i class="iconfont icon-right2 pull-right"></i>
                    </a>
                </li>
                {foreach $forum as $key => $v}
                {if $v['fid'] == $fid}
                <li>
                    <a href="{php HYBBS_URL('forum',$v['id']);}">
                        <img src="{#WWW}upload/forum{$key}.png" onerror="this.src='{#WWW}upload/de.png'">
                        {$v.name} <i class="iconfont icon-right2 pull-right"></i>
                    </a>
                </li>
                {/if}
                {/foreach}
            
            </ul>
        </div>
        {else}
        <div class="right-widget only-logo">
            <div class="head">
                分类列表 <a href="{php HYBBS_URL('forum')}" class="pull-right js-tooltip">更多</a>
            </div>
            <ul id="right-forum-list">
                {foreach $forum as $key => $v}
                {if $v['fid'] == -1}
                <li>
                    <a href="{php HYBBS_URL('forum',$v['id']);}" >
                        <img src="{#WWW}upload/forum{$key}.png" onerror="this.src='{#WWW}upload/de.png'" >
                        {$v.name} <i class="iconfont icon-right2 pull-right"></i>
                    </a>
                </li>
                {/if}
                {/foreach}
            
            </ul>
        </div>
        {/if}
        {/if}
        <!--{hook right_box_bottom}-->
    </div>
    {/if}
    <div class="clearfix"></div>
</div>
<footer>
    <div class="container">
        <div class="version">
            <p>HYBBS © 2016. All Rights Reserved. <a href="{#WWW}">{$conf.logo}</a> </p>
            <p>Powered by <a href="http://bbs.hyphp.cn/">HYBBS</a> Version {#HYBBS_V}</p>
            {if view_form('hybbs','show_sleep')}
            <p>Runtime:<?php echo number_format(microtime(1) - $GLOBALS['START_TIME'], 4); ?>s Mem:<?php echo round((memory_get_usage() - $GLOBALS['START_MEMORY'])/1024); ?>Kb</p>
            {/if}
        </div>
    </div>
</footer>
<!--{hook t_bottom_box}-->
{include hy_friend::index}
</body>
</html>