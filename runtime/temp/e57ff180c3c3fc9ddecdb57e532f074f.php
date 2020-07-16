<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:69:"/www/wwwroot/my_test.nikm.cn/public/../app/admin/view/jump/index.html";i:1572067917;}*/ ?>
<!--
 * @Author: 傍晚升起的太阳
 * @QQ: 1250201168
 * @Email: wuruiwm@qq.com
 * @Date: 2019-10-21 09:29:55
 * @LastEditors: 傍晚升起的太阳
 * @LastEditTime: 2019-10-26 13:31:54
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
            <button type="button" class="layui-btn" id="add">新增域名</button>
        </div>
        <div class="layui-inline" style="margin-left: 1rem;">
            <input type="text" name="title" required  lay-verify="required" placeholder="请输入域名或者备注" class="layui-input" id="search_name">
        </div> 
        <div class="layui-inline">
            <select name="type" lay-verify="required" id="search_https">
                <option value="">请选择是否是HTTPS</option>
                <option value="0">HTTP</option>
                <option value="1">HTTPS</option>
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
    <label class="layui-form-label">域名</label>
    <div class="layui-input-block">
      <input type="text" required  lay-verify="required" placeholder="请输入域名" class="layui-input" id="domain_name">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">是否HTTPS</label>
    <div class="layui-input-block">
        <select name="type" lay-verify="required" id="https">
            <option value="0">否</option>
            <option value="1">是</option>
        </select>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">备注</label>
    <div class="layui-input-block">
      <input type="text" required  lay-verify="required" placeholder="请输入备注" class="layui-input" id="remark">
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
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<script type="text/html" id="https_btn">
  <input type="checkbox" data-id="{{d.id}}" lay-skin="switch" {{# if(d.is_https == 1){ }}checked{{# } }} lay-filter="is_https" lay-text="是|否">
</script>
<script src="https://cdn.nikm.cn/js/jquery.js"></script>
<script src="https://cdn.nikm.cn/js/layui/layui.js"></script>
<script>
var id;
layui.use(['layer','table','form'], function(){
  var table = layui.table;
  var layer = layui.layer;
  var form = layui.form;
  table.render({
    elem: '#table' //表格id
    ,url:"<?php echo url('admin/jump/list'); ?>"//list接口地址
    ,cellMinWidth: 80 //全局定义常规单元格的最小宽度
    ,cols: [[
    //align属性是文字在列表中的位置 可选参数left center right
    //sort属性是排序功能
    //title是这列的标题
    //field是取接口的字段值
    //width是宽度，不填则自动根据值的长度
      {field:'id', title: 'ID',align: 'center',sort: true,width:100},
      {field:'domain_name',title: '域名', align:'center'},
      {field:'is_https', title: '是否HTTPS', align:'center',width:120,templet:'#https_btn'},
      {field:'remark',title: '备注', align:'center',width:180,templet:function(d){
          if(d.remark){
              return d.remark;
          }else{
              return '无备注';
          }
      }},
      {field:'create_time',title: '创建时间', align:'center',width:180},
      {field:'update_time',title: '修改时间', align:'center',width:180},
      {fixed:'right', title: '操作', align:'center', toolbar: '#buttons',width:180}
    ]]
    ,page: true//是否分页
    ,limit: 15//每个多少行
    ,limits:[15, 25, 45, 60]//页面下的切换条数
  });
  form.on('switch(is_https)', function (obj) {
        //找被点击的div同级的input上的data-id
        var id = obj.othis.siblings("input").data('id');
        var bool = obj.elem.checked;
        $.post("<?php echo url('admin/jump/https'); ?>",{id:id,https:bool},function(res){
            console.log(res);
        },'json')
  });
  //监听tool里面的按钮 根据按钮的lay-event属性的值 触发不同的事件
  table.on('tool(table)', function(obj){
      //data就是一行的数据
      var data = obj.data;
      //删除
      if(obj.event === 'edit'){
          //将该行的值赋值到弹窗表单上
          id = data.id;
          $('#domain_name').val(data.domain_name);
          $('#https').val(data.is_https);
          form.render('select');
          $('#remark').val(data.remark);
          layer.open({
              type: 1,
              title:'编辑',
              skin: 'layui-layer-rim', //加上边框
              area: ['50rem;', '22rem;'], //宽高
              content: $('#post'),
          });
      }else if(obj.event === 'del'){
        layer.confirm('真的删除吗', function(index){
              $.post("<?php echo url('admin/jump/del'); ?>",{id:data.id},function(res){
                if (res.status == 1) {
                    obj.del();//删除表格这行数据
                }
                layer.msg(res.msg);
              },'json')
          });
      }
    });
    $('#add').click(function(){
        id = 0;
        $('#domain_name').val('');
        $('#https').val(0);
        form.render('select');
        $('#remark').val('');
        layer.open({
            type: 1,
            title:'新增',
            skin: 'layui-layer-rim', //加上边框
            area: ['50rem;', '22rem;'], //宽高
            content: $('#post'),
        });
    })
    $('#submit').click(function(){
        var data = {
              id:id,
              domain_name:$('#domain_name').val(),
              is_https:$('#https').val(),
              remark:$('#remark').val()
        }; 
        $.post("<?php echo url('admin/jump/post'); ?>",data,function(res){
            if(res.status == 1){
                layer.closeAll();
                //提交数据后重载表格
                table.reload('table', { //表格的id
                    url:"<?php echo url('admin/jump/list'); ?>",
                });
            }
            layer.msg(res.msg);
        },'json')
    })
    //搜索
    $('#search').on('click',function(){
          //传递where条件实现搜索，并且重载表格数据
        table.reload('table', { //表格的id
            url:"<?php echo url('admin/jump/list'); ?>",
            where:{
                'name':$('#search_name').val(),
                'is_https':$('#search_https').val(),
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