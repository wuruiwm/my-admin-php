@extends('admin.comm.base')

@section('content')
    <style>
        .copy-btn{
            position: absolute;
            right: 10px;
            top:3px;
        }
    </style>
    <div class="layui-form">
        <blockquote class="layui-elem-quote">
            <div class="layui-inline" style="margin-left: 2rem;">
                <button class="layui-btn" id="create">添加账号密码</button>
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
    <!-- 用户名template -->
    <script type="text/html" id="users">
        <div style="position:relative">
            @{{# if(d.user){ }}
                @{{d.user}}
                <button type="button" class="layui-btn layui-btn-xs copy copy-btn" data-clipboard-text="@{{d.user}}">复制</button>
            @{{# }else{ }}
                无用户名
            @{{# } }}
        </div>
    </script>

    <!-- 密码template -->
    <script type="text/html" id="passwords">
        <div style="position:relative">
            <span>**********</span>
            <i class="fa fa-eye eye" data-is-show-password="0"></i>
            <button type="button" class="layui-btn layui-btn-xs copy copy-btn" data-clipboard-text="@{{d.password}}">复制</button>
        </div>
    </script>

    <script type="text/html" id="buttons">
        <button class="layui-btn layui-btn-xs" lay-event="edit">编辑</button>
        <button class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delete">删除</button>
    </script>
    <script>
        layui.use(['table'], function(){
            var id;
            var table = layui.table;
            table.render({
                elem: '#table',
                url: "{{ route('admin.password.list') }}",
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
                    {field:'user', title:'用户名', align:'center',templet: '#users'},
                    {field:'password', title:'密码', align:'center',templet: '#passwords'},
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
                    //表格刷新完后执行
                    //点击按钮复制
                    new Clipboard('.copy');
                    //复制后提示
                    $('.copy').click(function(){
                        layer.msg('复制成功');
                    });
                    //点击眼睛隐藏展示密码
                    $('.eye').click(function(){
                        if($(this).data("is-show-password") == 1){
                            $(this).prev().html("**********");
                            $(this).removeClass("fa-eye-slash").addClass("fa-eye");
                            $(this).data("is-show-password","0");
                        }else{
                            $(this).prev().html($(this).next().data("clipboard-text"));
                            $(this).removeClass("fa-eye").addClass("fa-eye-slash");
                            $(this).data("is-show-password","1");
                        }
                    });
                }
            });
            table.on('tool(table)', function(obj){
                //data就是一行的数据
                var data = obj.data;
                if(obj.event === 'delete'){
                    layer.confirm('确定要删除吗', function(){
                        $.post("{{ route('admin.password.delete') }}",{id:data.id},function(res){
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
                    $('#user').val(data.user);
                    $('#password').val(data.password);
                    $('#remark').val(data.remark);
                    layer.open({
                        type: 1,
                        title:'编辑账号密码',
                        skin: 'layui-layer-rim',//边框
                        area: ['50rem;', '22rem;'],
                        content: $('#content'),
                    });
                }
            });
            $('#create').click(function(){
                id = 0;
                $('#title').val('');
                $('#user').val('');
                $('#remark').val('');
                $('#password').val('');
                layer.open({
                    type: 1,
                    title:'添加账号密码',
                    skin: 'layui-layer-rim',
                    area: ['50rem;', '22rem;'],//边框
                    content: $('#content'),
                });
            });
            $('#submit').click(function(){
                var data = {
                    title:$('#title').val(),
                    user:$('#user').val(),
                    password:$('#password').val(),
                    remark:$('#remark').val()
                };
                if(id == 0){
                    var url = "{{ route('admin.password.create') }}";
                }else{
                    data.id = id;
                    var url = "{{ route('admin.password.edit') }}";
                }
                $.post(url,data,function(res){
                    if(res.code == 0){
                        layer.closeAll();
                        //提交数据成功后重载表格
                        table.reload('table',{ //表格的id
                            url:"{{ route('admin.password.list') }}",
                        });
                    }
                    layer.msg(res.msg);
                });
            });
            $('#search').click(function(){
                //传递where条件实现搜索，并且重载表格数据
                table.reload('table',{ //表格的id
                    url:"{{ route('admin.password.list') }}",
                    where:{
                        'keyword':$('#search_keyword').val(),
                    }
                });
            });
            //监听按下回车键时，触发搜索
            $(document).on('keydown', function(e){
                if(e.keyCode == 13 && $("#search_keyword").is(":focus")){
                    $('#search').click();
                }
            })
        });
    </script>
@endsection
