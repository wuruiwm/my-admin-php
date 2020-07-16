<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:70:"/www/wwwroot/my_test.nikm.cn/public/../app/admin/view/yiyan/index.html";i:1573284380;}*/ ?>
<!--
 * @Author: 傍晚升起的太阳
 * @QQ: 1250201168
 * @Email: wuruiwm@qq.com
 * @Date: 2019-10-19 10:28:40
 * @LastEditors: 傍晚升起的太阳
 * @LastEditTime: 2019-10-26 13:31:23
 -->
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>一言管理</title>
  <link rel="stylesheet" href="https://cdn.nikm.cn/js/layui/css/layui.css">
  <link rel="stylesheet" href="https://cdn.nikm.cn/css/font-awesome/css/font-awesome.min.css">
</head>
<body class="layui-layout-body" style="overflow-y:visible;">

<!-- 顶部操作栏 -->
<div class="layui-form">
    <blockquote class="layui-elem-quote">
        <div class="layui-inline" style="margin-left: 1rem;">
            <input type="text" required  lay-verify="required" placeholder="输入要搜索的值，支持内容，出处，提交者" class="layui-input" id="search_name" style="width:18rem;">
        </div>
        <div class="layui-inline">
            <select name="type" lay-verify="required" id="search_type">
                <option value="0">请选择类型</option>
                <option value="a">动画</option>
                <option value="b">漫画</option>
                <option value="c">游戏</option>
                <option value="d">小说</option>
                <option value="e">原创</option>
                <option value="f">来自网络</option>
                <option value="g">其他</option>
            </select>
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
    <label class="layui-form-label">内容</label>
    <div class="layui-input-block">
      <input type="text" required  lay-verify="required" placeholder="请输入内容" class="layui-input" id="content">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">类型</label>
    <div class="layui-input-block">
        <select name="type" lay-verify="required" id="type">
            <option value="0">请选择类型</option>
            <option value="a">动画</option>
            <option value="b">漫画</option>
            <option value="c">游戏</option>
            <option value="d">小说</option>
            <option value="e">原创</option>
            <option value="f">来自网络</option>
            <option value="g">其他</option>
        </select>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">一言出处</label>
    <div class="layui-input-block">
      <input type="text" required  lay-verify="required" placeholder="请输入一言出处" class="layui-input" id="from">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">提交者</label>
    <div class="layui-input-block">
      <input type="text" required placeholder="请输入提交者" class="layui-input" id="by">
    </div>
  </div>
  <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" id="submit">立即提交</button>
    </div>
  </div>
</div>

<script type="text/html" id="buttons">
  <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
</script>
<script src="https://cdn.nikm.cn/js/jquery.js"></script>
<script src="https://cdn.nikm.cn/js/layui/layui.js" charset="utf-8"></script>
<script>
var id;
layui.use(['layer','table','form'], function(){
  var table = layui.table;
  var layer = layui.layer;
  var form = layui.form;
  table.render({
    elem: '#table', //表格id
    url:"<?php echo url('admin/yiyan/list'); ?>",//list接口地址
    cellMinWidth: 80,//全局定义常规单元格的最小宽度
    cols: [[
    //align属性是文字在列表中的位置 可选参数left center right
    //sort属性是排序功能
    //title是这列的标题
    //field是取接口的字段值
    //width是宽度，不填则自动根据值的长度
      {field:'id', title: 'ID',align: 'center',sort: true,width:100},
      {field:'content',title: '内容', align:'center'},
      {field:'type_name', title: '类型', align:'center',width:120},
      {field:'from',title: '一言出处', align:'center',width:200},
      {field:'by',title: '提交者', align:'center',width:200},
      {field:'create_time',title: '创建时间', align:'center',width:180},
      {field:'update_time',title: '修改时间', align:'center',width:180},
      {fixed:'right', title: '操作', align:'center', toolbar: '#buttons',width:100}
    ]],
    page: true,//是否分页
    limit: 15,//每个多少行
    limits:[15, 25, 45, 60]//页面下的切换条数
  });
  //监听tool里面的按钮 根据按钮的lay-event属性的值 触发不同的事件
  table.on('tool(table)', function(obj){
      //data就是一行的数据
      var data = obj.data;
      //删除
      if(obj.event === 'edit'){
          //将该行的值赋值到弹窗表单上
          id = data.id;
          $('#content').val(data.content);
          $('#type').val(data.type);
          form.render('select');
          $('#from').val(data.from);
          $('#by').val(data.by);
          layer.open({
            type: 1,
            title:'编辑',
            skin: 'layui-layer-rim', //加上边框
            area: ['50rem;', '22rem;'], //宽高
            content: $('#post'),
          });
      }
    });
    $('#submit').click(function(){
        var data = {
              id:id,
              content:$('#content').val(),
              type:$('#type').val(),
              from:$('#from').val(),
              by:$('#by').val()
        }; 
        $.post("<?php echo url('admin/yiyan/post'); ?>",data,function(res){
            if(res.status == 1){
                layer.closeAll();
                //提交数据后重载表格
                table.reload('table', { //表格的id
                    url:"<?php echo url('admin/yiyan/list'); ?>",
                });
            }
            layer.msg(res.msg);
        },'json')
    })
    //搜索
    $('#search').on('click',function(){
          //传递where条件实现搜索，并且重载表格数据
        table.reload('table', { //表格的id
            url:"<?php echo url('admin/yiyan/list'); ?>",
            where:{
                'type':$('#search_type').val(),
                'name':$('#search_name').val(),
            }
        });
    });
});
//监听按下回车键时，触发搜索
$(document).on('keydown', function(e){
    if(e.keyCode == 13){
        $('#search').click();
    }
})
</script>
</body>
</html>