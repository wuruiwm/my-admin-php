<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>WebSsh</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link href="https://cdn.nikm.cn/js/layui/css/layui.css" rel="stylesheet">
    <link href="https://cdn.nikm.cn/css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <style>
        #LAY_app,body,html {
            height: 100%
        }

        .layui-layout-body {
            overflow: auto
        }

        #LAY-user-login {
            display: block!important
        }

        .layadmin-user-login {
            position: relative;
            left: 0;
            top: 0;
            padding: 110px 0;
            min-height: 100%;
            box-sizing: border-box
        }

        .layadmin-user-login-main {
            width: 375px;
            margin: 0 auto;
            box-sizing: border-box
        }

        .layadmin-user-login-box {
            padding: 20px
        }

        .layadmin-user-login-header {
            text-align: center
        }

        .layadmin-user-login-header h2 {
            margin-bottom: 10px;
            font-weight: 300;
            font-size: 30px;
            color: #000
        }

        .layadmin-user-login-header p {
            font-weight: 300;
            color: #999
        }

        .layadmin-user-login-body .layui-form-item {
            position: relative
        }

        .layadmin-user-login-icon {
            position: absolute;
            left: 1px;
            top: 1px;
            width: 38px;
            line-height: 36px;
            text-align: center;
            color: #d2d2d2
        }

        .layadmin-user-login-body .layui-form-item .layui-input {
            padding-left: 38px
        }

        .layadmin-user-login-other {
            position: relative;
            font-size: 0;
            line-height: 38px;
            padding-top: 20px
        }

        .layadmin-user-login-other>* {
            display: inline-block;
            vertical-align: middle;
            margin-right: 10px;
            font-size: 14px
        }

        .layadmin-user-login-other .layui-icon {
            position: relative;
            top: 2px;
            font-size: 26px
        }

        .layadmin-user-login-other a:hover {
            opacity: .8
        }

        .layadmin-user-login-footer {
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            line-height: 30px;
            padding: 20px;
            text-align: center;
            box-sizing: border-box;
            color: rgba(0,0,0,.5)
        }

        .layadmin-user-login-footer span {
            padding: 0 5px
        }

        .layadmin-user-login-footer a {
            padding: 0 5px;
            color: rgba(0,0,0,.5)
        }

        .layadmin-user-login-footer a:hover {
            color: rgba(0,0,0,1)
        }

        .layadmin-user-login-main[bgimg] {
            background-color: #fff;
            box-shadow: 0 0 5px rgba(0,0,0,.05)
        }

        .ladmin-user-login-theme {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center
        }

        .ladmin-user-login-theme ul {
            display: inline-block;
            padding: 5px;
            background-color: #fff
        }

        .ladmin-user-login-theme ul li {
            display: inline-block;
            vertical-align: top;
            width: 64px;
            height: 43px;
            cursor: pointer;
            transition: all .3s;
            -webkit-transition: all .3s;
            background-color: #f2f2f2
        }

        .ladmin-user-login-theme ul li:hover {
            opacity: .9
        }

        @media screen and (max-width:768px) {
            .layadmin-user-login {
                padding-top: 80px
            }

            .layadmin-user-login-main {
                width: 300px
            }
        }
        .hide{
            display: none;
        }
        .show{
            display: block;
        }
    </style>
</head>
<body class="layui-layout-body">
<div class="layadmin-user-login">
    <div class="layadmin-user-login-main">
        <div class="layadmin-user-login-box layadmin-user-login-header">
            <h2>WebSsh</h2>
            <p>基于billchurch的webssh2</p>
        </div>
        <div class="layadmin-user-login-box layadmin-user-login-body">
            <form class="layui-form" method="post">
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon">
                        <i class="fa fa-internet-explorer"></i>
                    </label>
                    <input type="text" placeholder="请输入域名或ip" class="layui-input" name="host">
                </div>
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon">
                        <i class="fa fa-unlock-alt"></i>
                    </label>
                    <input type="password" placeholder="请输入密码" class="layui-input" name="password">
                </div>
                <div class="layui-form-item hide">
                    <label class="layadmin-user-login-icon">
                        <i class="fa fa-user"></i>
                    </label>
                    <input type="text" placeholder="请输入用户名" class="layui-input" value="root" name="user">
                </div>
                <div class="layui-form-item hide">
                    <label class="layadmin-user-login-icon">
                        <i class="fa fa-dot-circle-o"></i>
                    </label>
                    <input type="text" placeholder="请输入ssh端口" class="layui-input" value="22" name="port">
                </div>
                <div class="layui-form-item">
                    <input type="checkbox" name="switch" lay-skin="switch" lay-text="显示|隐藏" lay-filter="is_show">
                </div>
                <div class="layui-form-item">
                    <button class="layui-btn layui-btn-fluid" lay-submit lay-filter="submit">走&nbsp;你</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.nikm.cn/js/layui/layui.js"></script>
<script src="https://cdn.nikm.cn/js/jquery.js"></script>
<script>
    var layer;
    var form;
    layui.use(['form','layer'], function(){
        form = layui.form;
        layer = layui.layer;
        form.on('switch(is_show)', function (obj) {
            if(obj.elem.checked){
                $('.hide').attr('class','layui-form-item show');
            }else{
                $('.show').attr('class','layui-form-item hide');
            }
        });
        form.on('submit(submit)', function(data){
            $.post('',data.field,function(res){
                if(res.code == 0){
                    window.location.href = res.url;
                }
                layer.msg(res.msg);
            },'json')
            return false;
        });
    });
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        dataType:'json',
        timeout:30000,
        beforeSend:function(){
            layer.load();
        },
        error:function(xhr){
            if(xhr.status == 419){
                layer.msg('CSRF验证过期,请刷新本页面后重试');
            }else if(xhr.status == 403){
                layer.msg('请检查您是否有权限');
            }else{
                layer.msg('访问出错');
            }
        },
        complete:function(){
            layer.closeAll('loading');
        }
    });
</script>
</body>
</html>
