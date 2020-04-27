@extends('admin.comm.base')

@section('content')
    <div class="layui-form">
        <blockquote class="layui-elem-quote">
            <div class="layui-inline" style="margin-left: 2rem;">
                <button class="layui-btn" id="create"><i class="layui-icon">&#xe608;</i> 添加FRP跳转</button>
            </div>
            <div class="layui-inline" style="margin-left: 2rem;">
                <input type="text" placeholder="请输入关键词进行搜索..." class="layui-input" id="search_keyword" style="width:15rem;">
            </div>
            <div class="layui-inline">
                <select name="type" lay-verify="required" id="search_is_https">
                    <option value="">请选择是否HTTPS</option>
                    <option value="1">是</option>
                    <option value="0">否</option>
                </select>
            </div>
            <div class="layui-inline" style="margin-left: 1rem;">
                <button type="button" class="layui-btn" id="search"><i class="layui-icon ">&#xe615;</i> 搜索</button>
            </div>
            <div class="layui-inline" style="margin-left: 1rem;">
                <button type="button" class="layui-btn" id="export"><i class="layui-icon ">&#xe67d;</i> 导出</button>
            </div>
        </blockquote>
    </div>

    <table class="layui-hide" id="table" lay-filter="table"></table>

    <div id="content" class="layui-form layui-form-pane" style="display: none;margin:1rem 3rem;">
        <div class="layui-form-item">
            <label class="layui-form-label">域名</label>
            <div class="layui-input-block">
                <input type="text" placeholder="请输入域名" class="layui-input" id="domain_name">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">是否HTTPS</label>
            <div class="layui-input-block">
                <select name="type" lay-verify="required" id="is_https">
                    <option value="">请选择是否HTTPS</option>
                    <option value="1">是</option>
                    <option value="0">否</option>
                </select>
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
        <button class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit"><i class="layui-icon">&#xe642;</i>编辑</button>
        <button class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delete"><i class="layui-icon">&#xe640;</i>删除</button>
    </script>

    <!-- https开关templet -->
    <script type="text/html" id="https_switch">
        <input type="checkbox" data-id="@{{d.id}}" lay-skin="switch" @{{# if(d.is_https == 1){ }}checked@{{# } }} lay-filter="is_https" lay-text="是|否">
    </script>

    <script>
        layui.use(['table','form'], function(){
            var id;
            var table = layui.table;
            var form = layui.form;
            var list_url = "{{ route('admin.frp.list') }}";
            table.render({
                elem: '#table',
                url: list_url,
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
                    {field:'domain_name', title:'域名', align:'center'},
                    {field:'is_https', title:'HTTPS', align:'center',templet:'#https_switch'},
                    {field:'remark',title: '备注', align:'center', templet: function(d){
                            if(d.remark){
                                return d.remark;
                            }else{
                                return '无备注';
                            }
                        }},
                    {field:'updated_at', title:'修改时间', align:'center'},
                    {field:'created_at', title:'创建时间', align:'center'},
                    {fixed:'right', title:'操作', align:'center', toolbar:'#buttons',width:150}
                ]],
                done: function (){
                    //表格刷新完后执行
                }
            });
            form.on('switch(is_https)', function (obj){
                //找被点击的div同级的input上的data-id
                var id = obj.othis.siblings("input").data('id');
                var bool = obj.elem.checked;
                $.post("{{ route('admin.frp.https_switch') }}",{id:id,is_https:bool ? 1 : 0},function(res){
                    layer.msg(res.msg);
                });
            });
            table.on('tool(table)', function(obj){
                //data就是一行的数据
                var data = obj.data;
                if(obj.event === 'delete'){
                    layer.confirm('确定要删除吗', function(){
                        $.post("{{ route('admin.frp.delete') }}",{id:data.id},function(res){
                            if (res.code == 0){
                                obj.del();//删除表格这行数据
                            }
                            layer.msg(res.msg);
                        });
                    });
                }
                if(obj.event === 'edit'){
                    id = data.id;
                    $('#domain_name').val(data.domain_name);
                    $('#is_https').val(data.is_https);
                    $('#remark').val(data.remark);
                    form.render('select');
                    layer.open({
                        type: 1,
                        title:'编辑FRP跳转',
                        skin: 'layui-layer-rim',//边框
                        area: ['50rem;', '18rem;'],
                        content: $('#content'),
                    });
                }
            });
            $('#create').click(function(){
                id = 0;
                $('#domain_name').val('');
                $('#is_https').val('');
                $('#remark').val('');
                form.render('select');
                layer.open({
                    type: 1,
                    title:'添加FRP跳转',
                    skin: 'layui-layer-rim',//边框
                    area: ['50rem;', '18rem;'],
                    content: $('#content'),
                });
            });
            $('#submit').click(function(){
                var data = {
                    domain_name:$('#domain_name').val(),
                    is_https:$('#is_https').val(),
                    remark:$('#remark').val()
                };
                if(id == 0){
                    var url = "{{ route('admin.frp.create') }}";
                }else{
                    data.id = id;
                    var url = "{{ route('admin.frp.edit') }}";
                }
                $.post(url,data,function(res){
                    if(res.code == 0){
                        layer.closeAll();
                        //提交数据成功后重载表格
                        table.reload('table',{ //表格的id
                            url:list_url,
                        });
                    }
                    layer.msg(res.msg);
                });
            });
            $('#search').click(function(){
                //传递where条件实现搜索，并且重载表格数据
                table.reload('table',{ //表格的id
                    url:list_url,
                    where:{
                        'keyword':$('#search_keyword').val(),
                        'is_https':$('#search_is_https').val(),
                    }
                });
            });
            //监听按下回车键时并且焦点在搜索框时，触发搜索
            $(document).on('keydown', function(e){
                if(e.keyCode == 13 && $("#search_keyword").is(":focus")){
                    $('#search').click();
                }
            });
            $('#export').click(function(){
                var count = $('.layui-laypage-count').text().replace('共 ','').replace(' 条','');
                $.get(list_url+'?page=1&limit='+count,function(res){
                    if(res.code == 0){
                        for(let k in res.data){
                            res.data[k].is_https == 1 ? res.data[k].is_https = '是' : res.data[k].is_https = '否';
                        }
                        table.exportFile(['ID','域名','是否HTTPS','备注','创建时间','修改时间'],res.data,'csv');
                    }
                });
            });
        });
    </script>
@endsection
