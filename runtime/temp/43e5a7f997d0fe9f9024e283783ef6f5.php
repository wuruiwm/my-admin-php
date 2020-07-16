<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:68:"/www/wwwroot/my_test.nikm.cn/public/../app/admin/view/ssl/index.html";i:1576036488;}*/ ?>
<!--
 * @Author: 傍晚升起的太阳
 * @QQ: 1250201168
 * @Email: wuruiwm@qq.com
 * @Date: 2019-10-21 09:29:55
 * @LastEditors: 傍晚升起的太阳
 * @LastEditTime: 2019-10-26 13:31:38
 -->
 <!DOCTYPE html>
 <html>
 <head>
   <meta charset="utf-8">
   <title>域名管理</title>
   <link rel="stylesheet" href="https://cdn.nikm.cn/js/layui/css/layui.css">
   <link rel="stylesheet" href="https://cdn.nikm.cn/css/font-awesome/css/font-awesome.min.css">
 </head>
 <body class="layui-layout-body" style="overflow-y:visible;">
<!-- 顶部操作栏 -->
    <div class="layui-form">
        <blockquote class="layui-elem-quote">
            <div class="layui-inline" style="margin-left: 1rem;">
                <button type="button" class="layui-btn copy" id="key" data-clipboard-text="<?php echo $data['key']; ?>" onclick="layer.msg('复制成功')">复制key</button>
            </div>
            <div class="layui-inline" style="margin-left: 1rem;">
                <button type="button" class="layui-btn copy" id="pem" data-clipboard-text="<?php echo $data['pem']; ?>" onclick="layer.msg('复制成功')">复制pem</button>
            </div>
            <div class="layui-inline" style="margin-left: 1rem;color: #3d763e;font-size: 1rem;">
                证书到期时间:&nbsp;<?php echo $end_time; ?>
            </div>
        </blockquote>
    </div>
    <pre class="key" style="float: left;width: 49%;">
        <?php echo $data['key']; ?>
    </pre> 
    <pre class="pem" style="float: right;width: 49%;">
        <?php echo $data['pem']; ?>
    </pre>
 <script src="https://cdn.nikm.cn/js/jquery.js"></script>
 <script src="https://cdn.nikm.cn/js/clipboard.js"></script>
 <script src="https://cdn.nikm.cn/js/layui/layui.js"></script>
 <script>
    layui.use(['layer','code'], function(){
        var layer = layui.layer;
        layui.code({
            elem: '.key', //默认值为.layui-code
            title:'key',
            about:false
        });
        layui.code({
            elem: '.pem', //默认值为.layui-code
            title:'pem',
            about:false
        });
    });
    $(function(){
        new Clipboard('.copy');
    })
 </script>
 </body>
 </html>