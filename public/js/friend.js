var zindex = 10000;
var docMouseMoveEvent = document.onmousemove; 
    var docMouseUpEvent = document.onmouseup; 


function resize(obj,jd){
    var evt = getEvent(); 
      var resizeX = evt.clientX;
      var resizeY = evt.clientY;
      var mWidth = obj.width();
      var mHeight = obj.height();
      var mLeft = obj.position().left;
      var mTop = obj.position().top;
      document.onmousemove = function() { 
        var evt = getEvent(); 
        var tmp  = resizeY - evt.clientY;
        var tmp1 = resizeX - evt.clientX;
        var tmp2 = resizeY - evt.clientY;
        if(jd=="n"){
            obj.height(mHeight + tmp);
            obj.css("top",(mTop-tmp)+"px");
        }else if(jd=="s"){
            obj.height(mHeight - tmp);
        }else if(jd=="e"){
            obj.width(mWidth - tmp1);
        }else if(jd=="w"){
            obj.width(mWidth + tmp1);
            obj.css("left",(mLeft-tmp1)+"px");
        }else if(jd=="nw"){
            obj.width(mWidth + tmp1);
            obj.height(mHeight + tmp2);
            obj.css("left",(mLeft-tmp1)+"px");
            obj.css("top",(mTop-tmp2)+"px");
        }else if(jd=="ne"){
            obj.width(mWidth - tmp1);
            obj.height(mHeight + tmp2);
            obj.css("top",(mTop-tmp2)+"px");
        }else if(jd=="sw"){
            obj.width(mWidth + tmp1);
            obj.height(mHeight - tmp2);
            obj.css("left",(mLeft-tmp1)+"px");
        }else if(jd=="se"){
            obj.width(mWidth - tmp1);
            obj.height(mHeight - tmp2);;
        }
      };
      document.onmouseup = function () {  
        document.onmousemove = docMouseMoveEvent; 
        document.onmouseup = docMouseUpEvent; 
      };
}

function new_chat(title, msg, w, h,uid,user,avatar,ps){  
   
    if($('#lt-box-'+uid).length)
        return;

    $(".xx").html(parseInt($(".xx").html()) - parseInt($("#friend-span-"+uid).html()));
    if(parseInt($(".xx").html()) <= 0)
        $(".xx").hide().html('0');
    $("#friend-span-"+uid).removeClass("friend-show").addClass("friend-hide").html('0');
    


    var msgObj=$('<div uid="'+uid+'" avatar="'+WWW+avatar+'" user="'+user+'" class="lt-dlg-box" id="lt-box-'+uid+'" style="z-index:'+(zindex++)+';top:'+($(window).height()-h)/2+'px;left:'+($(window).width()-w)/2+'px;width:'+w+'px;height:'+h+'px;"></div>');
    $('body').append(msgObj);

    var Title = $('<div class="lt-title"></div>');
     
    msgObj.append(Title);


    var resize_n    = $('<div class="resize resize-n" ></div>');
    var resize_e    = $('<div class="resize resize-e" ></div>');
    var resize_s    = $('<div class="resize resize-s" ></div>');
    var resize_w    = $('<div class="resize resize-w" ></div>');
    var resize_se   = $('<div class="resize resize-se" ></div>');
    var resize_sw   = $('<div class="resize resize-sw" ></div>');
    var resize_ne   = $('<div class="resize resize-ne" ></div>');
    var resize_nw   = $('<div class="resize resize-nw" ></div>');

    msgObj.append(resize_n);
    msgObj.append(resize_e);
    msgObj.append(resize_s);
    msgObj.append(resize_w);
    msgObj.append(resize_se);
    msgObj.append(resize_sw);
    msgObj.append(resize_ne);
    msgObj.append(resize_nw);



    var moveX = 0; 
    var moveY = 0; 
    var moveTop = 0; 
    var moveLeft = 0; 
    var moveable = false; 
    

    //顶部拉伸
    resize_n.mousedown(function(){
      resize(msgObj,"n");
    });
    //底部拉伸
    resize_s.mousedown(function(){
      resize(msgObj,"s");
    });
    //右边拉伸
    resize_e.mousedown(function(){
      resize(msgObj,"e");
    });
    //左边拉伸
    resize_w.mousedown(function(){
      resize(msgObj,"w");
    });
    //左上角拉伸
    resize_nw.mousedown(function(){
      resize(msgObj,"nw");
    }); 
    //右上角拉伸
    resize_ne.mousedown(function(){
      resize(msgObj,"ne");
    }); 
    //左下角拉伸
    resize_sw.mousedown(function(){
      resize(msgObj,"sw");
    }); 
    //右下角拉伸
    resize_se.mousedown(function(){
      resize(msgObj,"se");
    }); 

    msgObj.mousedown(function(){
      msgObj.css("z-index",zindex++);
    })
    Title.dblclick(function(){
        if(msgObj.attr("data")=="1"){
            


            msgObj.css("left",msgObj.attr("iLeft"));
            msgObj.css("top",msgObj.attr("iTop"));
            msgObj.width(msgObj.attr("iWidth"));
            msgObj.height(msgObj.attr("iHeight"));
            msgObj.attr("data",0);
        }else{
            msgObj.attr("iLeft",msgObj.css("left"));
            msgObj.attr("iTop",msgObj.css("top"));
            msgObj.attr("iWidth",msgObj.width());
            msgObj.attr("iHeight",msgObj.height());


            msgObj.css("left","10px");
            msgObj.css("top","10px");
            msgObj.width($(window).width()-20);
            msgObj.height($(window).height()-20);
            msgObj.attr("data",1);
        }
    })
    Title.mousedown(function() { 
        var evt = getEvent(); 
        moveable = true;  
        moveX = evt.clientX; 
        moveY = evt.clientY; 
        moveTop = parseInt(msgObj.position().top); 
        moveLeft = parseInt(msgObj.position().left); 
         
        document.onmousemove = function() { 
            if (moveable) { 
                var evt = getEvent(); 
                var x = moveLeft + evt.clientX - moveX; 
                var y = moveTop + evt.clientY - moveY; 
                //if ( x > 0 &&( x + msgObj.width() < $(window).width()) && y > 0 && (y + msgObj.height() < $(window).height()) ) { 
                    msgObj.css("left",x + "px") ; 
                    msgObj.css("top" ,y + "px");
                //} 
            }     
        }; 
        document.onmouseup = function () {  
            if (moveable) {  
                document.onmousemove = docMouseMoveEvent; 
                document.onmouseup = docMouseUpEvent; 
                moveable = false;  
                moveX = 0; 
                moveY = 0; 
                moveTop = 0; 
                moveLeft = 0; 
            }  
        }; 
    } );

  
  var h4 = $('<h4 style="margin-bottom: 0;"><img src="'+WWW+avatar+'"><span class="lt-name">'+user+'</span><span class="lt-ps">'+ps+'</span></h4>');


  var closeBtn = $('<span class="lt-close r">×</span>')

    Title.append(h4);
    h4.append(closeBtn);
    closeBtn.click(function(){  
        msgObj.remove();
    });



    var msgBox = $('<div class="lt-content lt-id-'+uid+'"><ul>'+
    '</ul></div><div class="lt-footer"><div class="lt-input-group"><input id="lt-text-'+uid+'" type="text" class="lt-text"><span class="lt-input-group-btn"><button class="lt-send-btn" onclick="send_lt('+uid+',this)">Send</button></span></div></div></div></div>'); 
    msgObj.append(msgBox);
    msgBox.find(".lt-text").keypress(function(e){
        e = e || window.event; 
        var key = e.whick || e.keyCode; 
        if(key == 13){
            send_lt(uid,msgBox.find(".lt-send-btn"));
        }
    })

    
    get_old_chat(uid,user,WWW+avatar);
     
    
}  
// 获得事件Event对象，用于兼容IE和FireFox 
function getEvent() { 
    return window.event || arguments.callee.caller.arguments[0]; 
}
function get_old_chat(uid,user,avatar,bool){
    $.ajax({
        url:www+'Friend'+exp+'get_old_chat',
        data:{uid:uid},
        type:'post',
        dataType:'json',
        success:function(e){

            for(o in e.reverse()){
                //uid1 = 接收者
                //uid2 = 发送者
                //console.log(e[o]);
                if(e[o].uid1 == uid){
                    add_lt(uid,'right',window.hy_user,e[o].content,e[o].time,window.hy_avatar);
                }
                else{
                    add_lt(uid,'left',user,e[o].content,e[o].time,avatar);
                }
                
            }
            //add_lt()
        }
        ,error: function(){

        }
    });
}
function send_lt(uid,obj){
    if($("#lt-text-"+uid).val()=='')
        return swal('内容不能为空!');

    $("#lt-text-"+uid).attr('disabled','disabled');
    $(obj).attr('disabled','disabled');
    $.ajax({
        url: www + 'friend' + exp + 'send_chat',
        data: {content : $("#lt-text-"+uid).val(), uid:uid},
        type:'post',
        dataType:'json',
        success:function(e){
            if(!e.error){
                swal(e.info);
                $(obj).removeAttr('disabled');
                $("#lt-text-"+uid).removeAttr('disabled');
                return ;
            }
            add_lt(uid,'right',window.hy_user,$("#lt-text-"+uid).val(),new Date().getHours() + ":"+ new Date().getMinutes() +":"+ new Date().getSeconds() ,window.hy_avatar);
            $("#lt-text-"+uid).val('');
            $("#lt-text-"+uid).removeAttr('disabled');
            $(obj).removeAttr('disabled');
            $("#lt-text-"+uid).focus();

        },
        error:function(){
            $("#lt-text-"+uid).removeAttr('disabled');
            $(obj).removeAttr('disabled');
        }
    })
}
function add_lt(id,pos,user,content,time,avatar){
    var c_obj = $(".lt-id-"+id);
    //console.log(c_obj);

    var html = '<li class="'+pos+'">'+'<img class="avatar" src="'+avatar+'" alt="User Avatar">'+
    '<div class="chat-body">'+
        
        '<p>'+content+
            
        '</p>'+
        '<span>'+time+'</span>'+
    '</div>'+
    '</li>';
    
    c_obj.find('ul').append(html);
    c_obj.scrollTop(9999);

}
function play_msg(){
    var audio = document.getElementById("play-msg");
    audio.play();
}
function play_system(){
    var audio = document.getElementById("play-system");
    audio.play();
}
//加载好友列表
function load_friend(){
    $.ajax({
        url : www+'Friend'+exp+'friend_list',
        type:'post',
        dataType:'json',
        success:function(e){
            var html2 ='';
            var html3 ='';
            var html0 = '';
            for(o in e){
                if(e[o].state==0){
                    html0 += '<li><a onclick="new_chat(\'title\',\'ssss\',444,465,'+e[o].uid+',\''+e[o].user+'\',\''+e[o].avatar.b+'\',\''+((e[o].ps==null)?'':e[o].ps)+'\')" >'+
                    '<img src="'+WWW+e[o].avatar.b+'" class="img-circle" alt="user avatar">'+
                    '<div class="chat-detail m-left-sm"><div class="chat-name">'+e[o].user+
                    '</div><div class="chat-message">'+((e[o].ps==null)?'':e[o].ps)+'</div>'+
                    '</div><div class="chat-status"><span class="friend-'+(e[o].ol?'zx':'lx')+'"></span></div>'+
                    '<div class="chat-alert"><span id="friend-span-'+e[o].uid+'" class="badge badge-purple bounceIn animation-delay2 friend-'+(e[o].c=='0' ? 'hide' : 'show')+'">'+e[o].c+'</span></div></a></li>';
                }else if(e[o].state==1 || e[o].state==2){
                    html2 += '<li><a onclick="new_chat(\'title\',\'ssss\',444,465,'+e[o].uid+',\''+e[o].user+'\',\''+e[o].avatar.b+'\',\''+((e[o].ps==null)?'':e[o].ps)+'\')" >'+
                    '<img src="'+WWW+e[o].avatar.b+'" class="img-circle" alt="user avatar">'+
                    '<div class="chat-detail m-left-sm"><div class="chat-name">'+e[o].user+
                    '</div><div class="chat-message">'+((e[o].ps==null)?'':e[o].ps)+'</div>'+
                    '</div><div class="chat-status"><span class="friend-'+(e[o].ol?'zx':'lx')+'"></span></div>'+
                    '<div class="chat-alert"><span id="friend-span-'+e[o].uid+'" class="badge badge-purple bounceIn animation-delay2 friend-'+(e[o].c=='0' ? 'hide' : 'show')+'">'+e[o].c+'</span></div></a></li>';
                }else if(e[o].state==3){
                    html3 += '<li><a onclick="new_chat(\'title\',\'ssss\',444,465,'+e[o].uid+',\''+e[o].user+'\',\''+e[o].avatar.b+'\',\''+((e[o].ps==null)?'':e[o].ps)+'\')" >'+
                    '<img src="'+WWW+e[o].avatar.b+'" class="img-circle" alt="user avatar">'+
                    '<div class="chat-detail m-left-sm"><div class="chat-name">'+e[o].user+
                    '</div><div class="chat-message">'+((e[o].ps==null)?'':e[o].ps)+'</div>'+
                    '</div><div class="chat-status"><span class="friend-'+(e[o].ol?'zx':'lx')+'"></span></div>'+
                    '<div class="chat-alert"><span id="friend-span-'+e[o].uid+'" class="badge badge-purple bounceIn animation-delay2 friend-'+(e[o].c=='0' ? 'hide' : 'show')+'">'+e[o].c+'</span></div></a></li>';
                }  
            }
            $("#friend-1").append(html2);
            $("#friend-3").append(html3);
            $("#friend-0").append(html0);
            window.friend_pm = 0;
            function friend_pm_call(){
                $.ajax({
                    url:www+'Friend'+exp+'pm',
                    type:'post',
                    dataType:'json',
                    data:{
                        time:window.friend_pm
                    },
                    success:function(e){
                        window.friend_pm = e.atime;
                        var system_msg = false;
                        if(e.error){
                            var size =0;
                            for(o in e.info.reverse()){
                                if(e.info[o].uid2 == 0){
                                    
                                    system_msg=true;
                                }
                                if(!$('#lt-box-'+e.info[o].uid2).length){//未打开聊天框
                                    if(!$('#friend-span-'+e.info[o].uid2).length){ //没有该好友在列表
                                        add_friend_li(e.info[o].uid2);
                                    }
                                    else{
                                        $('#friend-span-'+e.info[o].uid2).removeClass('friend-hide').addClass('friend-show').text(e.info[o].c);
                                        var obj = $('#friend-span-'+e.info[o].uid2).parent().parent().parent();
                                        var html = obj.html();
                                        
                                        obj.parent().prepend("<li>"+html+"</li>");
                                        obj.remove();

                                        if(document.hidden){
                                            window_message($("#friend-span-"+e.info[o].uid2).parent().parent().find('img').attr('src'),$("#friend-span-"+e.info[o].uid2).parent().parent().find('.chat-name').text(),'新消息');
                                            
                                            
                                        }

                                    }
                                }
                                else{ //已打开聊天框
                                    get_old_chat(e.info[o].uid2,$('#lt-box-'+e.info[o].uid2).attr('user'),$('#lt-box-'+e.info[o].uid2).attr('avatar'));
                                    
                                }
                                size+=parseInt(e.info[o].c);
                                //console.log(e.info[o].c);
                                
                            }
                            if(size != 0 ){
                                if(!system_msg)
                                    play_msg();
                                else
                                    play_system();
                                $(".xx").show().text(size);
                            }
                        }
                        
                    },error:function(){

                    }
                })
            }
            friend_pm_call();
            setInterval(friend_pm_call,5000);
            
        },
        error:function(){

        }
    })
}
function add_friend_li(uid){
    $.ajax({
        url:www+'Friend'+exp+'user_info',
        type:'post',
        data:{uid:uid},
        dataType:'json',
        success:function(e){
            if(e.error){
                var html = '<li><a onclick="new_chat(\'title\',\'ssss\',444,365,'+uid+',\''+e.info.user+'\',\''+e.info.avatar.b+'\')">'+
                    '<img src="'+WWW+e.info.avatar.b+'" class="img-circle" alt="user avatar">'+
                    '<div class="chat-detail m-left-sm"><div class="chat-name">'+e.info.user+
                    '</div><div class="chat-message">个性签名</div>'+
                    '</div><div class="chat-status"><span class="friend-zx"></span></div>'+
                    '<div class="chat-alert"><span id="friend-span-'+uid+'" class="badge badge-purple bounceIn animation-delay2 friend-show">..</span></div></a></li>';
                $("#friend-0").prepend(html);
            }
        }
    })
}

function window_message(avatar,title,content) {  
        if (window.webkitNotifications) {  
            //chrome老版本  
            if (window.webkitNotifications.checkPermission() == 0) {  
                var notif = window.webkitNotifications.createNotification(avatar, title, content);  
                notif.display = function() {}  
                notif.onerror = function() {}  
                notif.onclose = function() {}  
                notif.onclick = function() {this.cancel();}  
                notif.replaceId = 'Meteoric';  
                notif.show();  
            } else {  
                window.webkitNotifications.requestPermission($jy.notify);  
            }  
        }  
        else if("Notification" in window){  
            // 判断是否有权限  
            if (Notification.permission === "granted") {  
                var notification = new Notification(title, {  
                    "icon": avatar,  
                    "body": content,  
                });  
            }  
            //如果没权限，则请求权限  
            else if (Notification.permission !== 'denied') {  
                Notification.requestPermission(function(permission) {  
                    // Whatever the user answers, we make sure we store the  
                    // information  
                    if (!('permission' in Notification)) {  
                        Notification.permission = permission;  
                    }  
                    //如果接受请求  
                    if (permission === "granted") {  
                        var notification = new Notification(title, {  
                            "icon": avatar,  
                            "body": content,  
                        });  
                    }  
                });  
            }  
        }  
    } 
$(function(){
    $('.friend-text').keyup(function(){
        var v = $(this).val();

        $(".friend-div-box .chat-name").each(function(){
            var _this = $(this);
            var __this = _this.parent().parent();
            console.log(_this.text().indexOf(v));
            if(_this.text().indexOf(v) == -1 ){ //搜索不存在
                if(!__this.is(":hidden")){
                    __this.hide();
                }
            }
            else
            {
               if(__this.is(":hidden")){
                    __this.show();
                }
            }

            
        })
            

    })
    
});