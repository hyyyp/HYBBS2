window.debug = true;
function log(a){
	if(window.debug)
		console.log(a);
}
function open_thread(url){
window.location.href = url;
}
function open_post_box(obj){
	$('#editor').focus();
	//document.removeEventListener('touchmove', touchmove_handler, false);
	$(".post-box").addClass("post-box-a");
	$(obj).after('<div class="hy-back" onclick="hide_post_box(this)"></div>').addClass("hy-body-overflow");
	$("body").attr('hide_size',parseInt($("body").attr('hide_size'))+1 );
	setTimeout(function(){$(".hy-back").addClass('in');},1);

}
function star(tid,obj){
    star_thread(tid,function(e){
        var _obj = $(obj);
        if (e.error) {
            _obj.html('取消收藏');
        } else {
            _obj.html('收藏');
        }
    });
}
function hide_post_box(obj){
	//document.addEventListener('touchmove', touchmove_handler, false);
	if(obj == undefined)
		obj = '.hy-back';
	$(".post-box").removeClass("post-box-a");
	$.hy.overflow_show();
	$(".hy-back").removeClass('in');
	setTimeout(function(){
		$(obj).remove();
	},300);
	
}
function hide_lt(uid){
	 var i = parseInt($("body").attr('hide_size'))-1;
		$("body").attr('hide_size', (i<0)?0:i );
	$.hy.hide_iframe($('#lt-'+uid));
}
//点击好友 打开聊天窗口
function open_lt(username,uid,avatar){

	$("#friend-span-"+uid).removeClass("friend-show").addClass("friend-hide");

	var v = parseInt($(".xx").text()) - parseInt($("#friend-span-"+uid).text());

	$(".xx").text(v);
	if(v<1){
		$('.xx').hide();
		$(".xx").text('0')
	}


	var _this = $('#lt-'+uid);
	//console.log(_this.length);
	if(_this.length == 0){
		var obj = $.hy.create_iframe('right','lt-'+uid);
		$.hy.show_iframe(obj);
		var box = $('<div style="background: #f1f4f9;width:100%;height:100%"><header class="hy-header hy-bo-b"><a class="hy-header-nav hy-header-left icon icon-chevron-small-left" onclick="hide_lt('+uid+')"></a><h1 class="hy-header-title">'+username+'</h1><a class="hy-header-nav hy-header-right" onclick=""></a></header></div>');
		obj.append(box);
		box.append('<div class="mui-content" id="is-obj-'+uid+'"><div id="msg-list" class="is-'+uid+'"><div class="lt-id-'+uid+'" user="'+username+'" avatar="'+avatar+'"></div></div></div>');
		

		box.append('<footer class="footer-lt"><div class="footer-center"><textarea id="msg-text" type="text" class="input-text lt-text-'+uid+'"></textarea></duv><div class="footer-right"><button onclick="send_lt('+uid+',this)" class="hy-btn hy-btn-primary" type="button" style="height:36px">Send</button></div></footer>');


		get_old_chat(uid,username,avatar);
		//eval('window.is_'+uid+' = null');
		
	
		
		return;
	}
	$.hy.show_iframe(_this);
	
	
	//console.log(document.getElementById('is-obj-'+uid).iscroll);


}

function send_lt(uid,obj){
    if($(".lt-text-"+uid).val()=='')
        return $.hy.warning('内容不能为空!');

    $(".lt-text-"+uid).attr('disabled','disabled');
    $(obj).attr('disabled','disabled');
    $.ajax({
        url: www + 'friend' + exp + 'send_chat',
        data: {content : $(".lt-text-"+uid).val(), uid:uid},
        type:'post',
        dataType:'json',
        success:function(e){
            if(!e.error){
                $.hy.warning(e.info);
                $(obj).removeAttr('disabled');
                $(".lt-text-"+uid).removeAttr('disabled');
                return ;
            }
            add_lt(uid,'msg-item-self',window.hy_user,$(".lt-text-"+uid).val(),new Date().getHours() + ":"+ new Date().getMinutes() +":"+ new Date().getSeconds() ,window.hy_avatar);
            $(".lt-text-"+uid).val('');
            $(".lt-text-"+uid).removeAttr('disabled');
            $(obj).removeAttr('disabled');
            $(".lt-text-"+uid).focus();
            $(".is-"+uid).scrollTop(99999);

        },
        error:function(){
            $(".lt-text-"+uid).removeAttr('disabled');
            $(obj).removeAttr('disabled');
        }
    })
}

var friend_box = false;
var friend_obj = null;
function hide_friend_box(){
	$.hy.overflow_show();

	if(friend_obj != null){
		$.hy.hide_iframe(friend_obj);
	}
}
function tog_friend_box(){
	var scrollTop = $("body").scrollTop();
	

	//$.hy.overflow_hide();
	$("#hy-mess").text("");

	
	if(friend_obj == null){
		friend_obj = $.hy.create_iframe('right');
		$.hy.show_iframe(friend_obj);
		var box = $('<div style="background: #f1f4f9;width:100%;height:100%"><header class="hy-header hy-bo-b"><a class="hy-header-nav hy-header-left icon icon-chevron-small-left" onclick="hide_friend_box()"></a><h1 class="hy-header-title" id="fr-head">联系人</h1><a class="hy-header-nav hy-header-right" onclick=""><img style="width: 36px;border-radius: 50%;margin-right: 9px;" src="'+WWW+window.hy_avatar+'"><span class="hy-lable hy-lable-danger xx" style="display:none;position: absolute;top: 3px;right: 5px;">0</span></a></header></div>');
		friend_obj.append(box);
		//box.append('<div class="hy-input-box"><input type="text" placeholder="搜索联系人"></div>');
		box.append('<div id="friend-tab" class="hy-cent-list"><ul><li class="a"><a data="1" href="javascript:void(0);">关注</a></li><li><a data="3" href="javascript:void(0);">粉丝</a></li><li><a data="0" href="javascript:void(0);">陌生人</a></li></ul></div>');
		box.append('<div id="friend-1" class="friend-list hy-list hy-bo-t hy-bo-b"><div></div></div>');
		box.append('<div id="friend-3" style="display:none" class="friend-list hy-list hy-bo-t hy-bo-b"><div></div></div>');
		box.append('<div id="friend-0" style="display:none" class="friend-list hy-list hy-bo-t hy-bo-b"><div></div></div>');


		setTimeout(function(){
			$("#friend-tab a").click(function(){
				$("#friend-tab li").removeClass('a');
				$(this).parent().addClass('a');
				$(".friend-list").hide();
				$('#friend-'+$(this).attr('data')).show();

			});
		},500);

		$.ajax({
	        url : www+'Friend'+exp+'friend_list',
	        type:'post',
	        dataType:'json',
	        success:function(e){
	            var html2 ='';
	            var html3 ='';
	            var html0 = '';
	            for(o in e){
	            	//console.log(e[o].c);
	            	$(".xx").text(parseInt($(".xx").text()) + parseInt(e[o].c));
	            	if(e[o].c != 0)
	            		$(".xx").show();

	            	var time1 = new Date(parseInt(e[o].atime)*1000);
	            	var time = time1.getTime();
	            	var date=new Date();
					date.setHours(0);
					date.setMinutes(0);
					date.setSeconds(0);
					date.setMilliseconds(0);

					var time2=date.getTime();
					//console.log(time1.getMonth());

					if(time < time2){ //非今天
						time = '16/'+time1.getMonth()+"/"+time1.getDate();
					}else{
						time = time1.getHours()+":"+time1.getMinutes();
					}
					if(e[o].atime =='0')
						time='';

	            	
	                if(e[o].state==0){
	                    html0 += '<a  href="javascript:void(0)" onclick="open_lt(\''+e[o].user+'\','+e[o].uid+',\''+e[o].avatar.c+'\')"><img class="hy-ty right-10 " width="40" height="40" src="'+WWW+e[o].avatar.b+'"></span><span class="title friend-name">'+e[o].user+'</span><span id="friend-ps-'+e[o].uid+'" class="friend-ms">'+((e[o].ps==null)?'':e[o].ps)+'</span><span class="friend-xx hy-lable hy-lable-danger friend-'+(e[o].c=='0' ? 'hide' : 'show')+'" id="friend-span-'+e[o].uid+'">'+e[o].c+'</span><span class="friend-time">'+time+'</span></a>';
	                }else if(e[o].state==1 || e[o].state==2){
	                    html2 += '<a  href="javascript:void(0)" onclick="open_lt(\''+e[o].user+'\','+e[o].uid+',\''+e[o].avatar.c+'\')"><img class="hy-ty right-10 " width="40" height="40" src="'+WWW+e[o].avatar.b+'"></span><span class="title friend-name">'+e[o].user+'</span><span id="friend-ps-'+e[o].uid+'" class="friend-ms">'+((e[o].ps==null)?'':e[o].ps)+'</span><span class="friend-xx hy-lable hy-lable-danger friend-'+(e[o].c=='0' ? 'hide' : 'show')+'" id="friend-span-'+e[o].uid+'">'+e[o].c+'</span><span class="friend-time">'+time+'</span></a>';
	                }else if(e[o].state==3){
	                    html3 += '<a  href="javascript:void(0)" onclick="open_lt(\''+e[o].user+'\','+e[o].uid+',\''+e[o].avatar.c+'\')"><img class="hy-ty right-10 " width="40" height="40" src="'+WWW+e[o].avatar.b+'"></span><span class="title friend-name">'+e[o].user+'</span><span id="friend-ps-'+e[o].uid+'" class="friend-ms">'+((e[o].ps==null)?'':e[o].ps)+'</span><span class="friend-xx hy-lable hy-lable-danger friend-'+(e[o].c=='0' ? 'hide' : 'show')+'" id="friend-span-'+e[o].uid+'">'+e[o].c+'</span><span class="friend-time">'+time+'</span></a>';
	                }
	                
	                        
	            }
	            
	            $("#friend-1 div").append(html2);
	         
	           

	            $("#friend-3 div").append(html3);
	            $("#friend-0 div").append(html0);

	            

	            $("#friend-1 div").prepend('<a  href="javascript:void(0)" onclick="open_lt(\'系统消息\',0,\'View/hy_friend/bell.png\')"><img class="hy-ty right-10" width="40" height="40" src="'+WWW+'View/hy_friend/bell.png"></span><span class="title friend-name">系统消息</span><span id="friend-ps-0" class="friend-ms">没有新消息</span><span class="friend-xx hy-lable hy-lable-danger friend-hide" id="friend-span-0">0</span></a>');
	            window.friend_pm = 0;
	            setInterval(function(){
	                $.ajax({
	                    url:www+'Friend'+exp+'pm',
	                    type:'post',
	                    dataType:'json',
	                    data:{
	                        time:window.friend_pm
	                    },
	                    success:function(e){
	                        window.friend_pm = e.atime;
	                        if(e.error){
	                            var size =0;
	                            for(o in e.info.reverse()){
	                            	//判断聊天框是否打开 
	                                if(!$('.lt-id-'+e.info[o].uid2).length){ //未打开
	                                    if(!$('#friend-span-'+e.info[o].uid2).length){ //朋友列表不存在该用户
	                                        add_friend_li(e.info[o].uid2);//添加好友信息到好友列表
	                                    }
	                                    else{
	                                        $('#friend-span-'+e.info[o].uid2).removeClass('friend-hide').addClass('friend-show').text(e.info[o].c);
	                                        var obj = $('#friend-span-'+e.info[o].uid2).parent();
	                                        var html = obj.prop("outerHTML");
	                                        obj.parent().prepend(html);
	                                        obj.remove();
	                                    }
	                                }
	                                else{ //打开聊天框
	                                    var obj = $('.lt-id-'+e.info[o].uid2);
	                                    //判断是否已经创建
	                                    if(!obj.parent().parent().parent().hasClass('hy-iframe-a')){ 
		                                    
		                                    $('#friend-span-'+e.info[o].uid2).removeClass('friend-hide').addClass('friend-show').text(e.info[o].c);

		                                    var obj1 = $('#friend-span-'+e.info[o].uid2).parent();
	                                        var html = obj1.prop("outerHTML");
	                                        obj1.parent().prepend(html);
	                                        obj1.remove();


	                                    }

	                                    get_old_chat(e.info[o].uid2,obj.attr('user'),obj.attr('avatar'));

	                                    
	                                }
	                                size+=parseInt(e.info[o].c);
	                                //console.log(e.info[o].c);
	                                
	                            }
	                            if(size != 0 ){
	                                $(".xx").show().text(size);
	                                $("#hy-mess").html('(<em class="hy-font-warning">'+size+'</em>)');
	                            }
	                        }
	                        
	                    },error:function(){

	                    }
	                })
	            },2000);

	           

	            
	        },
	        error:function(){

	        }
	    })
	    return;
	}
	$("#fr-head").text("联系人");

	$.hy.show_iframe(friend_obj);
	

}
function add_lt(id,pos,user,content,time,avatar){
    var c_obj = $(".lt-id-"+id);
    //console.log(c_obj);
	
    var html = '<div class="msg-item '+pos+'">'+'<img class="msg-user-img msg-user" src="'+WWW+avatar+'" alt=""><div class="msg-content"><div class="chat-body clearfix"><div class="msg-content-inner">'+ content+'</div><div class="msg-content-arrow"></div></div><div class="mui-item-clear"></div></div>';
    
    c_obj.append(html);
    c_obj.scrollTop(9999);
	
	
    
	

}

function get_old_chat(uid,user,avatar){
    $.ajax({
        url:www+'Friend'+exp+'get_old_chat',
        data:{uid:uid},
        type:'post',
        dataType:'json',
        success:function(e){
			
            for(o in e.reverse()){
				//alert('1.4');
                //uid1 = 接收者
                //uid2 = 发送者
                //console.log(e[o]);
                if(e[o].uid1 == uid){
                    add_lt(uid,'msg-item-self',window.hy_user,e[o].content,e[o].time,window.hy_avatar);
                }
                else{
                    add_lt(uid,'',user,e[o].content,e[o].time,avatar);
                    if(uid == 0){
                    	e[o].content = e[o].content.replace(/<[^>]+>/g,"");
                    }
                    $("#friend-ps-"+uid).text(e[o].content);
                }


                
            }
            
            $(".is-"+uid).scrollTop(99999);
        }
        ,error: function(){

        }
    });
}

function add_friend_li(uid){
    $.ajax({
        url:www+'Friend'+exp+'user_info',
        type:'post',
        data:{uid:uid},
        dataType:'json',
        success:function(e){
            if(e.error){
                var html = '<a href="javascript:void(0)" onclick="open_lt(\''+e.info.user+'\','+uid+',\''+e.info.avatar.c+'\')"><img class="hy-ty right-10" width="40" height="40" src="'+WWW+e.info.avatar.b+'"></span><span class="title">'+e.info.user+'</span><span class="hy-lable hy-lable-danger friend-show" id="friend-span-'+uid+'"  id="friend-span-'+uid+'">..</span></a>';



                $("#friend-0").prepend(html);
            }
        }
    })
}
function friend(uid,obj){
    friend_state(uid,function(b,m){
        var _obj = $(obj);
        if(m){
            _obj.removeClass("hy-btn-primary");
            _obj.addClass("hy-btn-danger");
            _obj.text("取消关注");
        }
        else{
            _obj.removeClass("hy-btn-danger");
            _obj.addClass("hy-btn-primary");
            _obj.text("关注");
        }
    })
}

function url_back(test){
	//if(document.referrer =='')
		document.referrer=window.href_top;
	if(window.debug)
		console.log('来源:'+document.referrer);
	
	//return;
	if(
		document.referrer.search(WWW) == -1   || //别站跳转.
		document.referrer == '' //|| //无来路
		//document.referrer.search("/post") != -1 
		//|| document.referrer.search("/user") != -1
	){
		if(window.debug)
			console.log('后退1');
		window.location.href=WWW;
	}
	else{
		if(window.debug)
			console.log('后退2');
		if(test==true)
			$("body").attr('hide_size',parseInt($("body").attr('hide_size'))-1 );
		
		history.back(-1);
	}
}


window.iframe_size = 0;
window.now_href1 = window.location.href;
$(document).ready(function(){  
	window.addEventListener("popstate",function(event){
		if(window.debug){
			console.log('触发后退事件 : '+iframe_size);
			console.log("NOW_href: "+now_href1);
			console.log("location: "+window.location.href);
		}
		if(now_href1 == window.location.href && iframe_size == 0)
			return;
		if(window.iframe_size >0){
			if(window.debug)
				console.log('iframe退');
			var i = window.iframe_size--;
			//document.addEventListener('touchmove', touchmove_handler, false);
			if(window.debug)
				console.log(i);
			if(i==1){
				$.hy.overflow_show();
			}
			$("#hy-iframe-box-"+(i)).removeClass("hy-iframe-a");
			setTimeout(function(){
				$("#hy-iframe-box-"+(i)).remove();
			},500);
			return;
		}
		if(window.debug)
			console.log('结束后退事件');
		window.location.reload()

    });
});
var touchmove_handler = function (e) {
        e.preventDefault();
    };
    window.href_top = document.referrer;
function ajax_click(){
		if(window.debug)
	 		console.log('链接点击');
		var _this = $(this);
		var href = _this.attr('href');
		var now_href=window.location.href;
		window.href_top = now_href;
		var pos = _this.attr('pos');
		var hide_menu = _this.attr('hide_menu');
		if(pos != ''){
			var iframe = _this.attr('iframe');
			
			if(iframe=='undefined')
				iframe='';
			
			if(hide_menu =='true'){
				$.hy.canvas_hide('left');
			}
			if(iframe == 'true'){
				$(".body").html('<img src="'+WWW+'View/hy_moblie/loading.gif" style="width: 100%;">');
			}else{
				var obj = $.hy.create_iframe(pos,"hy-iframe-box-"+(++window.iframe_size));
				obj.html('<header class="hy-header hy-fix-t"><a href="javascript:history.back(-1)" class="hy-header-nav hy-header-left icon icon-chevron-small-left" ></a><h1 class="hy-header-title">加载中...</h1></header>');
				obj.append('<div class="body"><img src="'+WWW+'View/hy_moblie/loading.gif" style="width: 100%;"></div>');
				var rgb = _this.attr('rgb');
				
				if(rgb != '')
					obj.css('background',rgb);
				$.hy.show_iframe(obj);
			}
			
			
			$.ajax({
				url:href,
				type:'get',
				dataType:'html',
				success:function(data){
					$.ajaxSetup({ cache: true });
					if(iframe == 'true'){
						
						$(".body").html(data.match(/<section class="body".*?>([\s\S]*?)<\/section>/)[1]);
						$(".body a[ajax=true]").click(ajax_click);
						$(".hy-header-title").html(data.match(/<h1 class="hy-header-title".*?>([\s\S]*?)<\/h1>/)[1]);
					}else{
						setTimeout(function(){
							obj.html(data.match(/<body.*?>([\s\S]*?)<\/body>/)[1]);
							obj.find('a[ajax=true]').click(ajax_click);
						},400);
					}
            		$("title").text(data.match(/<title>([\s\S]*?)<\/title>/)[1]);
            		
				},
				error:function(){}
			});
			
			
			window.history.pushState("","",href);
		}
		return false;
	 }
$(function(){



	function iframe_forum_size(){
		$(".iframe_forum").height($(window).height() - $("#iframe-forum-top").height() -40);
	}
	iframe_forum_size();
	
	$(window).resize(iframe_forum_size);


	 $(".iframe_forum a").click(function(){
	 	$(".iframe_forum a").removeClass('active');
	 	$(this).addClass('active');
	 });
	 
	$("a[ajax=true]").click(ajax_click);
	

})

