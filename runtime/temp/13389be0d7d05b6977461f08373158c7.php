<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"/www/wwwroot/my_test.nikm.cn/public/../app/admin/view/short_links/index.html";i:1572067903;}*/ ?>
<!--
 * @Author: 傍晚升起的太阳
 * @QQ: 1250201168
 * @Email: wuruiwm@qq.com
 * @Date: 2019-10-21 14:47:09
 * @LastEditors: 傍晚升起的太阳
 * @LastEditTime: 2019-10-25 11:13:17
 -->
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>短链管理</title>
  <link rel="stylesheet" href="https://cdn.nikm.cn/js/layui/css/layui.css">
  <link rel="stylesheet" href="https://cdn.nikm.cn/css/font-awesome/css/font-awesome.min.css">
  <style>
    .layui-input{
        display: inline;
        width: 75%;
    }
  </style>
</head>
<body class="layui-layout-body" style="overflow-y:visible;">

<!-- 顶部操作栏 -->
<div class="layui-form">
    <blockquote class="layui-elem-quote">
        <div class="layui-inline" style="margin-left: 2rem;">
            <button type="button" class="layui-btn" id="add">新增短链</button>
        </div>
        <div class="layui-inline" style="margin-left: 1rem;">
            <input type="text" placeholder="输入要搜索的值，支持短链,备注,跳转地址" class="layui-input" id="search_name" style="width:18rem;">
        </div>
        <div class="layui-inline" style="margin-left: 1rem;">
            <button type="button" class="layui-btn" id="search">搜索</button>
        </div>
    </blockquote>
</div>

<!-- 表格 -->                 
<table class="layui-hide" id="table" lay-filter="table">
</table>

<!-- 新增编辑弹窗   -->
<div id="post" class="layui-form layui-form-pane" style="display: none;margin:1rem 3rem;">
  <div class="layui-form-item">
    <label class="layui-form-label">短链</label>
    <div class="layui-input-block">
      <input type="text" placeholder="请输入短链" class="layui-input" id="tail">
      <button class="layui-btn" id="rand" style="margin-left: 5%;">随机生成</button>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">跳转地址</label>
    <div class="layui-input-block">
      <input type="text" placeholder="请输入跳转地址" class="layui-input" id="link">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">备注</label>
    <div class="layui-input-block">
      <input type="text" placeholder="请输入备注" class="layui-input" id="remark">
    </div>
  </div>
  <div class="layui-form-item">
    <div class="layui-input-block">
      <input type="hidden" name="" id="hiddenid" value="0">
      <button class="layui-btn" id="submit">立即提交</button>
    </div>
  </div>
</div>
<script type="text/html" id="qrcode">
  <div>
    <a href="#">
      <img class="tail_img" src="" style="width: 32px" data-tail="{{d.tail_url}}">
    </a>
  </div>
</script>
<script type="text/html" id="buttons">
  <a class="layui-btn layui-btn-xs layui-btn-normal copy" lay-event="copy" data-clipboard-text='{{d.tail_url}}' onclick="layer.msg('复制成功');">复制短链</a>
  <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<script src="https://cdn.nikm.cn/js/jquery.js"></script>
<script src="https://cdn.nikm.cn/js/clipboard.js"></script>
<script src="https://cdn.nikm.cn/js/layui/layui.js"></script>
<script src="https://cdn.nikm.cn/js/jr-qrcode.js"></script>
<script>
var id;
layui.use(['layer','table'], function(){
  var table = layui.table;
  var layer = layui.layer;
  table.render({
    elem: '#table', //表格id
    url:"<?php echo url('admin/short_links/list'); ?>",//list接口地址
    cellMinWidth: 80, //全局定义常规单元格的最小宽度
    cols: [[
    //align属性是文字在列表中的位置 可选参数left center right
    //sort属性是排序功能
    //title是这列的标题
    //field是取接口的字段值
    //width是宽度，不填则自动根据值的长度
      {field:'id', title: 'ID',align: 'center',sort: true},
      {field:'tail',title: '短链', align:'center'},
      {field:'link', title: '跳转地址', align:'center'},
      {field:'qrcode',title: '二维码',align: 'center',templet:'#qrcode'},
      {field:'remark',title: '备注', align:'center',templet:function(d){
            if(d.remark){
              return d.remark;
            }else{
              return '无备注';
            }
      }},
      {field:'create_time',title: '创建时间', align:'center'},
      {field:'update_time',title: '最后修改时间', align:'center'},
      {fixed:'right', title: '操作', align:'center', toolbar: '#buttons'}
    ]],
    page: true,//是否分页
    limit: 15,//每个多少行
    limits:[15, 25, 45, 60],//页面下的切换条数
    done: function () {
        new Clipboard('.copy');
        tail_img();
        hoverOpenImg();
    }
  });
  //监听tool里面的按钮 根据按钮的lay-event属性的值 触发不同的事件
  table.on('tool(table)', function(obj){
      //data就是一行的数据
      var data = obj.data;
      //删除
      if(obj.event === 'del'){
          layer.confirm('真的删除吗', function(index){
              var url = "<?php echo url('admin/short_links/del'); ?>" + '?id=' + data.id;
              $.get(url,function(res){
                if(res.status == 1){
                  obj.del();//删除表格这行数据
                }
                layer.msg(res.msg);
              },'json')
          });
          //编辑
      }else if(obj.event === 'edit'){
          //将该行的值赋值到弹窗表单上
          id = data.id;
          $('#tail').val(data.tail);
          $('#link').val(data.link);
          $('#remark').val(data.remark);
          layer.open({
            type: 1,
            title:'编辑',
            skin: 'layui-layer-rim', //加上边框
            area: ['50rem;', '22rem;'], //宽高
            content: $('#post'),
          });
      }
    });
    //搜索
    $('#search').on('click',function(){
        //传递where条件实现搜索，并且重载表格数据
        table.reload('table', { //表格的id
            url:"<?php echo url('admin/short_links/list'); ?>",
            where:{
                'name':$('#search_name').val(),
            }
        });
    });
    //提交
    $('#submit').click(function(){
          var url = "<?php echo url('admin/short_links/post'); ?>";
          var data = {
            tail:$('#tail').val(),
            link:$('#link').val(),
            id:id,
            remark:$('#remark').val()
          };
          $.post(url,data,function(res){
            if(res.status == 1){
                layer.closeAll();
                //提交数据后重载表格
                table.reload('table', { //表格的id
                    url:"<?php echo url('admin/short_links/list'); ?>",
                });
            }
            layer.msg(res.msg);
          },'json');
    });
});
//新增
$('#add').click(function(){
      //初始化弹窗表单的值为空
      id = 0;
      $('#tail').val('');
      $('#link').val('');
      $('#remark').val('');
      layer.open({
          type: 1,
          title:'新增',
          skin: 'layui-layer-rim', //加上边框
          area: ['50rem;', '22rem;'], //宽高
          content: $('#post'),
      });
})
//监听按下回车键时，触发搜索
$(document).on('keydown', function(e){
    if(e.keyCode == 13){
        $('#search').click();
    }
})
function hoverOpenImg() {
    var img_show = null;
    $('td img').hover(
        function () {
            var kd = $(this).width();
            var kd1 = kd * 5;
            var kd2 = kd * 5 + 30;
            var img = "<img class='img_msg' src='" + $(this).attr('src') + "' style='width:" + kd1 + "px;' />";
            img_show = layer.tips(img, this, {
                tips: [2, 'rgba(41,41,41,.1)'],
                area: [kd2 + 'px']
            });
        }, 
        function () {
            layer.close(img_show);
        }
    );
}
function tail_img(){
    $('.tail_img').each(function(){
        $(this).attr('src',jrQrcode.getQrBase64($(this).data('tail')));
    });
}
$('#rand').click(function(){
    $.get("<?php echo url('admin/short_links/rand'); ?>",function(res){
        if(res.status == 1){
          $('#tail').val(res.rand);
        }
    },'json')
})
</script>
</body>
</html>