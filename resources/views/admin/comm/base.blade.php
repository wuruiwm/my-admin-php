<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/static/admin/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/static/admin/font-awesome/css/font-awesome.min.css" media="all">
    <style>font{vertical-align:baseline!important;}</style>
</head>
<body class="layui-layout-body" style="overflow-y:visible;background: #fff;">
    @yield('content')
</body>
<script src="/static/admin/jquery/jquery.min.js"></script>
<script src="/static/admin/layui/layui.js"></script>
<script src="/static/admin/clipboard/clipboard.js"></script>
<script>
    var layer;
    var form;
    layui.use(['form','layer'], function(){
        form = layui.form;
        layer = layui.layer;
    });
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        dataType:'json',
        timeout:5000,
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
@yield('script')
</html>
