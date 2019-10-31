//依赖JQuery
//
// 
function setCookie(name, value, hours) {
    var d = new Date();
    d.setTime(d.getTime() + hours * 3600 * 1000);
    document.cookie = name + '=' + value + '; expires=' + d.toGMTString();
}

function getCookie(name) {
    var arr = document.cookie.split('; ');
    for (var i = 0; i < arr.length; i++) {
        var temp = arr[i].split('=');
        if (temp[0] == name) {
            return temp[1];
        }
    }
    return '';
}

function removeCookie(name) {
    var d = new Date();
    d.setTime(d.getTime() - 10000);
    document.cookie = name + '=1; expires=' + d.toGMTString();
}

$(document).ready(function() {
    if (getCookie("WIDTH_4") == "1") {
        console.log('test');
        $(".container").css("width", "auto");
    }
    $("#setWidth").click(function() {
        if (getCookie("WIDTH_4") != "1") {
            setCookie("WIDTH_4", "1");
            $(this).attr("class", "icon-shrink2");
            $(".container").css("width", "auto");
        } else {

            removeCookie("WIDTH_4");
            $(this).attr("class", "icon-enlarge2");
            $(".container").css("width", "");
        }
    })


    $(".js-info").each(function() {
        var _this = this;
        var pos = 'south';
        var attr = $(this).attr('pos');

        if (attr == 'left')
            pos = 'east';
        else if (attr == 'right')
            pos = 'west';
        else if (attr == 'bottom')
            pos = 'north'
        $(_this).darkTooltip({
            size: 'lg',
            gravity: pos,
            content: '<img src="' + WWW + 'View/hybbs/loading.gif">',
            animation: 'flipIn',
            ajax: www + 'ajax' + exp + 'userjson',
            ajaxdata: {
                uid: $(_this).attr('uid')
            }
        });
    })

});

function friend(uid, obj) {
    friend_state(uid, function(b, m) {
        var _obj = $(obj);
        if (m) {
            _obj.removeClass("bg-primary");
            _obj.addClass("bg-red");
            _obj.text("取消关注");
        } else {
            _obj.removeClass("bg-red");
            _obj.addClass("bg-primary");
            _obj.text("关注");
        }
    })
}
function star(tid,obj){
    star_thread(tid,function(e){
        var _obj = $(obj);
        if (e.error) {
            _obj.html('<i class="iconfont icon-star" ></i> 取消收藏');
        } else {
            _obj.html('<i class="iconfont icon-star" ></i> 收藏');
        }
    });
}


function clear_mess() {
    swal({
        title: "清空未读数量",
        text: "将会清空你的未读消息数量.不会清空聊天记录",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "删除",
        cancelButtonText: '取消'
    }).then(
        function() {
            $.ajax({
                url: www + 'ajax' + exp + "clear_mess",
                type: "POST",
                cache: false,
                dataType: 'json'
            }).then(function(e) {
                setTimeout(function() {
                    swal(e.error ? "操作成功" : "操作失败", e.info, e.error ? "success" : "error");
                }, 100);
                $(".xx").text('').hide();

            }, function() {
                swal("失败", "请尝试重新提交", "error");
            });
        },
        function() {

        }
	);
}
//发表子评论
function post_post(pid,success,error){
    success = success ||null;
    error = error||null;

    var content = $('#post--'+pid).html();
    if('编写评论内容' == content || content == '')
        return swal('发表失败','请输入提交内容','error');
    
    $('#post--loading-'+pid).show();
    $('#post--btn-'+pid).attr('disabled','disabled').text('发表中...');
    

    ajax_api(www+'post'+exp+'post_post',{pid:pid,content:content},
    function(e){//success
        if(success != null)success(e);
        $('#post--loading-'+pid).hide();
        $('#post--btn-'+pid).removeAttr('disabled','disabled').text('发表');
        if(!e.error){
            return swal('发表失败',e.info,'error');
        }

        $('#post--'+pid).html('');

        var tag = '<li>'+
            '<div class="post--header">'+
                '<a href="javascript:;">'+
                    '<img class="circle avatar" src="'+WWW+e.data.avatar.b+'">'+
                    '<span class="author" >'+e.data.user+'</span>'+
                    '</a>'+
                '<span class="time">刚刚</span>'+
            '</div>'+
            '<div class="post--content">'+
                e.data.content+
            '</div>'+
        '</li>';

        $('#post--ul-'+pid).append(tag);
    },function(e){//error
        if(error != null)error(e);
        $('#post--loading-'+pid).hide();
        $('#post--btn-'+pid).removeAttr('disabled','disabled').text('发表');
        swal('发表失败','服务器出错或用户网络原因','error');
    });
}
function get_post_post(pid,pageid,sort){
    pageid = pageid || 1;
    ajax_api(www+'thread'+exp+'post'+'?cachetime='+new Date().getTime(),{pid:pid,pageid:pageid,sort:sort},
    function(e){//success
        $('#post--ul-'+pid).html('');
        for(var o in e.info){
            var tag = '<li>'+
                '<div class="post--header">'+
                    '<a href="javascript:;">'+
                        '<img class="circle avatar" src="'+WWW+e.info[o].avatar.b+'">'+
                        '<span class="author" >'+e.info[o].user+'<span onclick="post_post_ante('+pid+',\'@'+e.info[o].user+' \')" title="回复他" class="post--ante">@</span></span>'+
                        '</a>'+
                    '<span class="time">'+e.info[o].atime_str+'</span>'+
                '</div>'+
                '<div class="post--content">'+
                    e.info[o].content+
                '</div>'+
            '</li>';
            $('#post--ul-'+pid).append(tag);
        }
        
        
    },function(e){//error
        swal('发表失败','服务器出错或用户网络原因','error');
    });
}
function post_post_ante(pid,user){
    user = user+' &nbsp;';
    if($('#post--'+pid).text() == '编写评论内容')
        $('#post--'+pid).html(user).focus();
    else
        $('#post--'+pid).append(user).focus();


}