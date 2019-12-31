<?php !defined('HY_PATH') && exit('HY_PATH not defined.'); ?>
<!doctype html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{#WWW}public/bootstrap4/css/bootstrap.min.css">
    <link href="{#WWW}public/css/alert.css" rel="stylesheet">
    <title>{$title}{$conf.title2}</title>
    <script src="{#WWW}public/js/jquery3.min.js"></script>
    <style type="text/css">
        html,body {
          height: 100%;
        }

        body {
          display: -ms-flexbox;
          display: flex;
          -ms-flex-align: center;
          align-items: center;
          padding-top: 40px;
          padding-bottom: 40px;
          background-color: #f5f5f5;
        }

        .form-signin {
          width: 100%;
          max-width: 420px;
          padding: 15px;
          margin: auto;
        }

        .form-label-group {
          position: relative;
          margin-bottom: 1rem;
        }

        .form-label-group > input,
        .form-label-group > label {
          height: 3.125rem;
          padding: .75rem;
        }

        .form-label-group > label {
          position: absolute;
          top: 0;
          left: 0;
          display: block;
          width: 100%;
          margin-bottom: 0; /* Override default `<label>` margin */
          line-height: 1.5;
          color: #495057;
          pointer-events: none;
          cursor: text; /* Match the input under the label */
          border: 1px solid transparent;
          border-radius: .25rem;
          transition: all .1s ease-in-out;
        }

        .form-label-group input::-webkit-input-placeholder {
          color: transparent;
        }

        .form-label-group input:-ms-input-placeholder {
          color: transparent;
        }

        .form-label-group input::-ms-input-placeholder {
          color: transparent;
        }

        .form-label-group input::-moz-placeholder {
          color: transparent;
        }

        .form-label-group input::placeholder {
          color: transparent;
        }

        .form-label-group input:not(:placeholder-shown) {
          padding-top: 1.25rem;
          padding-bottom: .25rem;
        }

        .form-label-group input:not(:placeholder-shown) ~ label {
          padding-top: .25rem;
          padding-bottom: .25rem;
          font-size: 12px;
          color: #777;
        }

        /* Fallback for Edge
        -------------------------------------------------- */
        @supports (-ms-ime-align: auto) {
          .form-label-group > label {
            display: none;
          }
          .form-label-group input::-ms-input-placeholder {
            color: #777;
          }
        }

        /* Fallback for IE
        -------------------------------------------------- */
        @media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
          .form-label-group > label {
            display: none;
          }
          .form-label-group input:-ms-input-placeholder {
            color: #777;
          }
        }

    </style>
</head>
<body>
    <div class="form-signin">
        <div class="text-center mb-4">
            <img class="mb-4" src="{#WWW}View/default/Untitled.svg" alt="" width="72" height="72">
            <h1 class="h3 mb-3 font-weight-normal">找回密码</h1>
            <p>简介</p>
        </div>
        <div id="tab1">
            <form action="" method="post" id="form1">
                <input type="hidden" name="gn" value="has">
                <div class="form-label-group">
                    <input type="text" id="user" name="user" class="form-control" placeholder="账号" required autofocus>
                    <label for="has-user">输入需要找回的账号</label>
                </div>
                <div class="form-label-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">下一步</button>
                </div>
            </form>
        </div>
        <div id="tab2" style="display: none">
            <form action="" method="post" id="form2">
                <input type="hidden" name="gn" value="send_code">
                <input type="hidden" name="user" id="hide-user">
                <div class="form-label-group">
                    <input type="text" id="now-user" class="form-control" required disabled>
                    <label >正在操作的账号</label>
                </div>
                <div class="form-group">
                    <select id="type" class="custom-select" name="type" onchange="select_type(this)">
                        <option value="-1" selected>选择找回密码方式</option>
                        <option value="-1">-------</option>
                        <option value="email">邮箱地址接收验证码</option>
                    </select>
                </div>
                <div class="form-label-group" style="display: none">
                    <input type="text" id="safe-info" class="form-control" required disabled>
                    <label for="safe-info">接收验证码账号</label>
                </div>
                <div id="send-code-group" class="form-label-group" style="display: none">
                    <button id="send-code-btn" type="submit" class="btn btn-primary btn-lg btn-block">发送验证码</button>
                </div>
                
            </form>
        </div>
        <div id="tab3" style="display: none">
            <form action="" method="post" id="form3">
                <div id="code-input-group" class="form-label-group">
                    <input type="text" id="code" name="code" class="form-control" placeholder="验证码" onblur="verify_code(this)" required>
                    <div class="invalid-feedback">
                        验证码不正确
                    </div>
                    <label for="code">请输入收到的验证码</label>
                </div>
                <div class="form-label-group clearfix">
                    <button id="back-send-link" type="button" class="btn btn-link float-right" onclick="back_send_code()">没有收到验证码？重新获取</button>
                </div>
                <div class="form-label-group">
                    <input type="password" id="pass1" name="pass1" class="form-control" placeholder="验证码" required>
                    <label for="pass1">设置新密码</label>
                </div>
                <div class="form-label-group">
                    <input type="password" id="pass2" name="pass2" class="form-control" placeholder="验证码" required>
                    <label for="pass2">确认新密码</label>
                </div>
                <div class="form-label-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">提交修改</button>
                </div>
            </form>
        </div>
        
        
        <div class="form-label-group clearfix">
            <a href="{#WWW}" class="btn btn-dark float-left">&larr; 返回主页</a>

            <a href="{php HYBBS_URL('user','add');}" class="btn btn-warning float-right">前往登录 &rarr;</a>
        </div>
        <p class="mt-5 mb-3 text-muted text-center">HYBBS &copy; 2016. All Rights Reserved. <a href="http://bbs.hyphp.cn/">HYBBS</a></p>
    </div>
    
    <script type="text/javascript">
        function select_type(obj){
            if(obj.value == -1){
                $('#send-code-group').hide();
                $('#safe-info').parent().hide();
            }else{
                $.ajax({
                    url:"{php HYBBS_URL('user','repass');}",
                    type:'POST',
                    data:{
                        gn:'get_safe_info',
                        type:obj.value,
                        user:$('#user').val()
                    },
                    dataType:'json',
                    success:function(e){
                        if(e.error){
                            $('#send-code-group').show();
                            $('#safe-info').val(e.info).parent().show();

                        }else{
                            swal('提示', e.info, "error");
                        }
                    },
                    error:function(e,type){
                        swal(type, e.status + ' ' + e.statusText, "error");
                    }
                });
                

            }
        }
        function verify_code(obj){
            $.ajax({
                url:"{php HYBBS_URL('user','repass');}",
                type:'POST',
                data:{
                    gn:'verify_code',
                    type:$('#type').val(),
                    user:$('#user').val(),
                    code:obj.value
                },
                dataType:'json',
                success:function(e){
                    if(e.error){
                        $(obj).addClass('is-valid');
                        $(obj).removeClass('is-invalid');
                    }else{
                        $(obj).addClass('is-invalid');
                        $(obj).removeClass('is-valid');
                        $(obj).next().text(e.info);
                    }
                },
                error:function(e,type){
                    swal(type, e.status + ' ' + e.statusText, "error");
                }
            });
        }
        function back_send_code(){
            $('#tab3').hide();
            $('#tab2').show();
        }
        $(function(){
            //提交账号账号
            $('#form1').submit(function() {
                var data = $(this).serialize();
                $.ajax({
                    url:"{php HYBBS_URL('user','repass');}",
                    type:'POST',
                    data:data,
                    dataType:'json',
                    success:function(e){
                        if(e.error){
                            $('#tab1').hide();
                            $('#tab2').show();
                            $('#hide-user,#now-user').val($('#user').val());
                        }else{
                            swal('提示', e.info, "error");
                        }
                    },
                    error:function(e,type){
                        swal(type, e.status + ' ' + e.statusText, "error");
                    }
                });
                return false;
            });
            //发送验证码
            var timeobj=null;
            $('#form2').submit(function() {
                var data = $(this).serialize();
                loading('发送中');
                $.ajax({
                    url:"{php HYBBS_URL('user','repass');}",
                    type:'POST',
                    data:data,
                    dataType:'json',
                    success:function(e){
                        loading_close();
                        if(e.error){
                            $('#tab2').hide();
                            $('#tab3').show();
                            $btn = $('#send-code-btn');
                            $link = $('#back-send-link');
                            $btn.attr('disabled','disabled').text(e.next_s + ' 秒后重新获取');
                            $link.attr('disabled','disabled').text(e.next_s + ' 秒后重新获取');
                            if(timeobj!=null){
                                clearInterval(timeobj);
                                timeobj=null;
                            }
                            time=e.next_s;
                            timeobj = setInterval(function(){
                                if(--time <= 0){
                                    clearInterval(timeobj);
                                    timeobj=null;
                                    $btn.text('重新发送验证码').removeAttr('disabled');
                                    $link.text('没有收到验证码？重新获取').removeAttr('disabled');
                                    return;
                                }
                                $btn.text((time) + ' 秒后重新获取');
                                $link.text((time) + ' 秒后重新获取');
                            }, 1000)
                            swal('提示', e.info, "success");
                        }else{
                            swal('提示', e.info, "error");
                        }
                    },
                    error:function(e,type){
                        loading_close();
                        swal(type, e.status + ' ' + e.statusText, "error");
                    }
                });
                return false;
            });
            //提交修改
            $('#form3').submit(function() {
                $.ajax({
                    url:"{php HYBBS_URL('user','repass');}",
                    type:'POST',
                    data:{
                        gn:'change',
                        type:$('#type').val(),
                        user:$('#user').val(),
                        code:$('#code').val(),
                        pass1:$('#pass1').val(),
                        pass2:$('#pass2').val(),
                    },
                    dataType:'json',
                    success:function(e){
                        if(e.error){
                            swal('提示', e.info, "success");
                            setTimeout(function(){
                                window.location.href="{php HYBBS_URL('user','login')}";
                            },1000);
                        }else{
                            swal('提示', e.info, "error");
                        }
                    },
                    error:function(e,type){
                        swal(type, e.status + ' ' + e.statusText, "error");
                    }
                });
                return false;
            });
        });
    </script>
    <script src="{#WWW}public/js/popper.min.js"></script>
    <script src="{#WWW}public/bootstrap4/js/bootstrap.min.js"></script>
    <script src="{#WWW}public/js/app.js"></script>
</body>
</html>