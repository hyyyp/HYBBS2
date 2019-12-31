<?php !defined('HY_PATH') && exit('HY_PATH not defined.'); ?>
<!doctype html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{#WWW}public/bootstrap4/css/bootstrap.min.css">
    <title>{$title}</title>
    <script src="{#WWW}public/js/jquery3.min.js"></script>
    <style type="text/css">
        html,body{
            height: 100%
        }
    </style>
</head>
<body class="d-flex align-items-center">
    <div class="container" style="max-width: 500px">
        <div class="alert alert-{if $bool}success{else}danger{/if}" role="alert">
          <h4 class="alert-heading">提示</h4>
          <p>{$msg}</p>
          <hr>
          <div class="mb-0 clearfix">
              <a href="javascript:history.back(-1);" class="btn btn-dark">&larr; 返回上一页</a>
              <a href="{#WWW}" class="btn btn-warning float-right">&Sigma; 返回主页</a>
          </div>
        </div>
    </div>
</body>
</html>