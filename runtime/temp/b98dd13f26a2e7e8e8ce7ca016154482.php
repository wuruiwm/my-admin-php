<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:73:"/www/wwwroot/my_test.nikm.cn/public/../app/admin/view/password/index.html";i:1587352178;}*/ ?>
<!--
 * @Author: 傍晚升起的太阳
 * @QQ: 1250201168
 * @Email: wuruiwm@qq.com
 * @Date: 2019-10-19 10:28:40
 * @LastEditors: 傍晚升起的太阳
 * @LastEditTime: 2019-10-26 13:31:46
 -->
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>自用账号密码管理</title>
  <link rel="stylesheet" href="https://cdn.nikm.cn/js/layui/css/layui.css">
  <link rel="stylesheet" href="https://cdn.nikm.cn/css/font-awesome/css/font-awesome.min.css">
</head>
<body class="layui-layout-body" style="overflow-y:visible;">

<!-- 顶部操作栏 -->
<div class="layui-form">
    <blockquote class="layui-elem-quote">
        <div class="layui-inline" style="margin-left: 2rem;">
            <button type="button" class="layui-btn" id="add">新增账号密码</button> 
        </div>
        <div class="layui-inline" style="margin-left: 1rem;">
            <input type="text" placeholder="输入要搜索的值，支持名称，用户名，备注" class="layui-input" id="search_keyword" style="width:18rem;">
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
    <label class="layui-form-label">名称</label>
    <div class="layui-input-block">
      <input type="text" placeholder="请输入姓名" class="layui-input" id="name">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">用户名</label>
    <div class="layui-input-block">
      <input type="text" placeholder="请输入用户名" class="layui-input" id="user">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">密码</label>
    <div class="layui-input-block">
      <input type="text" placeholder="请输入密码" class="layui-input" id="password">
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
      <button class="layui-btn" id="submit">立即提交</button>
    </div>
  </div>
</div>

<!-- 用户名template -->
<script type="text/html" id="users">
    <div style="position:relative">
        {{# if(d.user){ }}
          {{d.user}}
          <button style="position: absolute;right: 10px;top:3px;" type="button" class="layui-btn layui-btn-xs copy" data-clipboard-text="{{d.user}}" onclick="layer.msg('复制成功');">复制</button>
        {{# }else{ }}
          无用户名
        {{# } }}
    </div>
</script>

<!-- 密码template -->
<script type="text/html" id="passwords">
    <div style="position:relative">
        <span id="pwd{{d.id}}">**********</span> 
        <i class="fa fa-eye" onclick="show_pwd('{{d.id}}','{{d.password}}')" id="eye{{d.id}}"></i>
        <button style="position: absolute;right: 10px;top:3px;" type="button" class="layui-btn layui-btn-xs copy" data-clipboard-text="{{d.password}}" onclick="layer.msg('复制成功');">复制</button>
    </div>
</script>
<script type="text/html" id="buttons">
  <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<script src="https://cdn.nikm.cn/js/jquery.js"></script>
<script src="https://cdn.nikm.cn/js/clipboard.js"></script>
<script src="https://cdn.nikm.cn/js/layui/layui.js"></script>
<script>
var id;
layui.use(['layer','table'], function(){
  var table = layui.table;
  var layer = layui.layer;
  table.render({
    elem: '#table', //表格id
    url:"<?php echo url('admin/password/list'); ?>",//list接口地址
    cellMinWidth: 80, //全局定义常规单元格的最小宽度
    cols: [[
    //align属性是文字在列表中的位置 可选参数left center right
    //sort属性是排序功能
    //title是这列的标题
    //field是取接口的字段值
    //width是宽度，不填则自动根据值的长度
      {field:'id', title: 'ID',align: 'center',sort: true,width:100},
      {field:'name',title: '名称', align:'center',width:200},
      {field:'user', title: '用户名', align:'center',templet: '#users'},
      {field:'password',title: '密码', align:'center',templet: '#passwords'},
      {field:'remark',title: '备注', align:'center',templet:function(d){
            if(d.remark){
              return d.remark;
            }else{
              return '无备注';
            }
      }},
      {field:'create_time',title: '创建时间', align:'center',width:170},
      {field:'update_time',title: '最后修改时间', align:'center',width:170},
      {fixed:'right', title: '操作', align:'center', toolbar: '#buttons',width:150}
    ]],   
    page: true,//是否分页
    limit: 15,//每个多少行
    limits:[15, 25, 45, 60],//页面下的切换条数
    done: function () {
      new Clipboard('.copy');
    }
  });
  //监听tool里面的按钮 根据按钮的lay-event属性的值 触发不同的事件
  table.on('tool(table)', function(obj){
      console.log(obj);
      //data就是一行的数据
      var data = obj.data;
      //删除
      if(obj.event === 'del'){
          layer.confirm('真的删除吗', function(index){
              var url = "<?php echo url('admin/password/del'); ?>" + '?id=' + data.id;
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
          $('#name').val(data.name);
          $('#user').val(data.user);
          $('#password').val(data.password);
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
            url:"<?php echo url('admin/password/list'); ?>",
            where:{
                'name':$('#search_name').val(),
            }
        });
    });
    //提交
    $('#submit').click(function(){
          var url = "<?php echo url('admin/password/post'); ?>";
          var data = {
            id:id,
            name:$('#name').val(),
            user:$('#user').val(),
            password:$('#password').val(),
            remark:$('#remark').val()
          };
          $.post(url,data,function(res){
            if(res.status == 1){
                layer.closeAll();
                //提交数据后重载表格
                table.reload('table', { //表格的id
                    url:"<?php echo url('admin/password/list'); ?>",
                });
            }
            layer.msg(res.msg);
          },'json');
    });
});
//新增
$('#add').click(function(){
      //初始化弹窗表单的值为空
      id = 0
      $('#name').val('');
      $('#user').val('');
      $('#remark').val('');
      $('#password').val('');
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
//点击眼睛 展示或隐藏密码
function show_pwd(id,pwd){
    if ($('#pwd'+id).text() == '**********') {
        $('#pwd'+id).text(pwd);
        $('#eye'+id).attr("class", "fa fa-eye-slash");
    }else{
        $('#pwd'+id).text('**********');
        $('#eye'+id).attr("class", "fa fa-eye");
    }
}
</script>
</body>
</html>