@extends('admin.comm.base')

@section('content')
    <div class="layui-form">
        <blockquote class="layui-elem-quote">
            <div class="layui-inline" style="margin-left: 2rem;">
                <button class="layui-btn" id="create">添加SSH</button>
            </div>
            <div class="layui-inline" style="margin-left: 2rem;">
                <input type="text" placeholder="请输入关键词进行搜索..." class="layui-input" id="search_keyword" style="width:15rem;">
            </div>
            <div class="layui-inline" style="margin-left: 1rem;">
                <button type="button" class="layui-btn" id="search">搜索</button>
            </div>
        </blockquote>
    </div>

    <table class="layui-hide" id="table" lay-filter="table"></table>

    <div id="content" class="layui-form layui-form-pane" style="display: none;margin:1rem 3rem;">
        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-block">
                <input type="text" placeholder="请输入名称" class="layui-input" id="title">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">HOST</label>
            <div class="layui-input-block">
                <input type="text" placeholder="请输入HOST" class="layui-input" id="host">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">端口</label>
            <div class="layui-input-block">
                <input type="text" placeholder="请输入SSH端口号" class="layui-input" id="port">
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
@endsection

@section('script')
    <script type="text/html" id="buttons">
        <button class="layui-btn layui-btn-xs" lay-event="connect">连接</button>
        <button class="layui-btn layui-btn-xs" lay-event="edit">编辑</button>
        <button class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delete">删除</button>
    </script>
    <script>
        layui.use(['table'], function(){
            var id;
            var table = layui.table;
            table.render({
                elem: '#table',
                url: "{{ route('admin.ssh.list') }}",
                cellMinWidth: 80, //全局定义常规单元格的最小宽度
                height: 'full-180',
                page: true,
                limits: [15, 30, 45, 60],
                limit: 15,
                cols: [[
                    /*
                    align属性是文字在列表中的位置 可选参数left center right
                    sort属性是排序功能
                    title是这列的标题
                    field是取接口的字段值
                    width是宽度，不填则自动根据值的长度
                    */
                    {field:'id', title:'ID', align:'center'},
                    {field:'title', title:'名称', align:'center'},
                    {field:'host', title:'HOST', align:'center'},
                    {field:'port', title:'端口', align:'center'},
                    {field:'user', title:'用户名', align:'center'},
                    {field:'password', title:'密码', align:'center'},
                    {field:'remark',title: '备注', align:'center', templet: function(d){
                            if(d.remark){
                                return d.remark;
                            }else{
                                return '无备注';
                            }
                        }},
                    {field:'updated_at', title:'修改时间', align:'center'},
                    {field:'created_at', title:'创建时间', align:'center'},
                    {fixed:'right', title:'操作', align:'center', toolbar:'#buttons'}
                ]],
                done: function (){

                }
            });
            table.on('tool(table)', function(obj){
                //data就是一行的数据
                var data = obj.data;
                if(obj.event === 'connect'){
                    var url = 'http://192.168.18.173:8000/?hostname='+ data.host +'&username='+ data.user +'&password='+ window.btoa(data.password)
                    console.log(url);
                    layer.open({
                        type: 2,
                        title: data.title,
                        shadeClose: true,
                        shade: false,
                        maxmin: true, //开启最大化最小化按钮
                        skin: 'layui-layer-rim', //加上边框
                        area: ['893px', '600px'],
                        content: url
                    });
                }
                if(obj.event === 'delete'){
                    layer.confirm('确定要删除吗', function(){
                        $.post("{{ route('admin.ssh.delete') }}",{id:data.id},function(res){
                            if (res.code == 0){
                                obj.del();//删除表格这行数据
                            }
                            layer.msg(res.msg);
                        });
                    });
                }
                if(obj.event === 'edit'){
                    id = data.id;
                    $('#title').val(data.title);
                    $('#host').val(data.host);
                    $('#port').val(data.port);
                    $('#user').val(data.user);
                    $('#password').val(data.password);
                    $('#remark').val(data.remark);
                    layer.open({
                        type: 1,
                        title:'编辑SSH',
                        skin: 'layui-layer-rim',//边框
                        area: ['50rem;', '28rem;'],
                        content: $('#content'),
                    });
                }
            });
            $('#create').click(function(){
                id = 0;
                $('#title').val('');
                $('#host').val('');
                $('#port').val('22');
                $('#user').val('root');
                $('#remark').val('');
                $('#password').val('');
                layer.open({
                    type: 1,
                    title:'添加SSH',
                    skin: 'layui-layer-rim',
                    area: ['50rem;', '28rem;'],//边框
                    content: $('#content'),
                });
            });
            $('#submit').click(function(){
                var data = {
                    title:$('#title').val(),
                    host:$('#host').val(),
                    port:$('#port').val(),
                    user:$('#user').val(),
                    password:$('#password').val(),
                    remark:$('#remark').val()
                };
                if(id == 0){
                    var url = "{{ route('admin.ssh.create') }}";
                }else{
                    data.id = id;
                    var url = "{{ route('admin.ssh.edit') }}";
                }
                $.post(url,data,function(res){
                    if(res.code == 0){
                        layer.closeAll();
                        //提交数据成功后重载表格
                        table.reload('table',{ //表格的id
                            url:"{{ route('admin.ssh.list') }}",
                        });
                    }
                    layer.msg(res.msg);
                });
            });
            $('#search').click(function(){
                //传递where条件实现搜索，并且重载表格数据
                table.reload('table',{ //表格的id
                    url:"{{ route('admin.ssh.list') }}",
                    where:{
                        'keyword':$('#search_keyword').val(),
                    }
                });
            });
            //监听按下回车键时并且焦点在搜索框时，触发搜索
            $(document).on('keydown', function(e){
                if(e.keyCode == 13 && $("#search_keyword").is(":focus")){
                    $('#search').click();
                }
            });
        });
    </script>
@endsection
