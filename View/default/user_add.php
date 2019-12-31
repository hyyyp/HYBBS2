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
    <form action="" method="post" id="form" class="form-signin">
        <div class="text-center mb-4">
            <img class="mb-4" src="{#WWW}View/default/Untitled.svg" alt="" width="72" height="72">
            <h1 class="h3 mb-3 font-weight-normal">注册账号</h1>
            <p>简介</p>
        </div>
        <div class="form-label-group">
            <input type="text" id="user" name="user" class="form-control" placeholder="账号" required autofocus>
            <label for="user">账号</label>
        </div>
        <div class="form-label-group">
            <input type="text" id="email" name="email" class="form-control" placeholder="邮箱" required autofocus>
            <label for="email">邮箱</label>
        </div>
        <div class="form-label-group">
            <input type="password" id="pass1" name="pass1" class="form-control" placeholder="密码" required>
            <label for="pass1">密码</label>
        </div>
        <div class="form-label-group">
            <input type="password" id="pass2" name="pass2" class="form-control" placeholder="确认密码" required>
            <label for="pass2">确认密码</label>
        </div>
        <div class="form-label-group">
            <button type="submit" class="btn btn-primary btn-lg btn-block">提交注册</button>
        </div>
        <div class="form-label-group clearfix">
            <a href="{#WWW}" class="btn btn-dark float-left">&larr; 返回主页</a>

            <a href="{php HYBBS_URL('user','login');}" class="btn btn-warning float-right">已有账号登录 &rarr;</a>
        </div>
        

        <p class="mt-5 mb-3 text-muted text-center">HYBBS &copy; 2016. All Rights Reserved. <a href="http://bbs.hyphp.cn/">HYBBS</a></p>
    </form>
    <script type="text/javascript">
        $(function(){
            $('#form').submit(function() {
                var data = $(this).serialize();
                $.ajax({
                    url:"{php HYBBS_URL('user','add');}",
                    type:'POST',
                    data:data,
                    dataType:'json',
                    success:function(e){
                        if(e.error){
                            if(e.url !='' && e.url != 'NULL' && e.url != 'null')
                                window.location.href=e.url;
                            else
                                window.location.href="{#WWW}";
                        }else{
                            swal('提示', e.info, e.error ? "success" : "error");
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