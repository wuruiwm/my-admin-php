@extends('admin.comm.base')

@section('content')
    <div class="layui-form">
        <blockquote class="layui-elem-quote">
            <div class="layui-inline" style="margin-left: 2rem;">
                <button class="layui-btn" id="create"><i class="layui-icon">&#xe608;</i> 添加服务商对接表</button>
            </div>
            <div class="layui-inline" style="margin-left: 2rem;">
                <input type="text" placeholder="请输入关键词进行搜索..." class="layui-input" id="search_keyword" style="width:15rem;">
            </div>
            <div class="layui-inline" style="margin-left: 1rem;">
                <input type="text" placeholder="请选择时间进行搜索" class="layui-input" id="search_date" style="width:15rem;">
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
            <label class="layui-form-label">添加时间</label>
            <div class="layui-input-block">
                <input type="text" placeholder="请选择添加时间" class="layui-input" id="add_time">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">服务商名称</label>
            <div class="layui-input-block">
                <input type="text" placeholder="请输入服务商名称" class="layui-input" id="service_name">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">姓名</label>
            <div class="layui-input-block">
                <input type="text" placeholder="请输入姓名" class="layui-input" id="name">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">手机号</label>
            <div class="layui-input-block">
                <input type="text" placeholder="请输入手机号" class="layui-input" id="mobile">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">会员类型</label>
            <div class="layui-input-block">
                <input type="text" placeholder="请输入会员类型" class="layui-input" id="member_type">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">省</label>
            <div class="layui-input-block">
                <input type="text" placeholder="请输入省" class="layui-input" id="province">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">市</label>
            <div class="layui-input-block">
                <input type="text" placeholder="请输入市" class="layui-input" id="city">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">服务区域</label>
            <div class="layui-input-block">
                <input type="text" placeholder="请输入服务区域" class="layui-input" id="service_region">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">服务项目</label>
            <div class="layui-input-block">
                <input type="text" placeholder="请输入服务项目" class="layui-input" id="service_project">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">饱和量</label>
            <div class="layui-input-block">
                <input type="text" placeholder="饱和量" class="layui-input" id="saturated">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">推送时间</label>
            <div class="layui-input-block">
                <input type="text" placeholder="请选择推送时间" class="layui-input" id="push_time">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">销售名称</label>
            <div class="layui-input-block">
                <input type="text" placeholder="请输入销售名称" class="layui-input" id="sale_name">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">后台跟进记录填写情况</label>
            <div class="layui-input-block">
                <input type="text" placeholder="请输入后台跟进记录填写情况" class="layui-input" id="record">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">服务商对接能力与态度</label>
            <div class="layui-input-block">
                <input type="text" placeholder="请输入服务商对接能力与态度" class="layui-input" id="ability">
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

    <script>
        layui.use(['table','form','laydate'], function(){
            var id;
            var table = layui.table;
            var form = layui.form;
            var laydate = layui.laydate;
            var list_url = "{{ route('admin.docking.list') }}";
            //实例化时间控件
            laydate.render({
                elem: '#add_time' //指定元素
            });
            laydate.render({
                elem: '#push_time' //指定元素
            });
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
                    {field:'add_time', title:'添加时间', align:'center'},
                    {field:'service_name', title:'服务商名称', align:'center'},
                    {field:'name', title:'姓名', align:'center'},
                    {field:'mobile', title:'手机号', align:'center'},
                    {field:'member_type', title:'会员类型', align:'center'},
                    {field:'province', title:'省', align:'center'},
                    {field:'city', title:'市', align:'center'},
                    {field:'service_region', title:'服务区域', align:'center'},
                    {field:'service_project', title:'服务项目', align:'center'},
                    {field:'saturated', title:'饱和量', align:'center'},
                    {field:'push_time', title:'推送时间', align:'center'},
                    {field:'sale_name', title:'销售名称', align:'center'},
                    {field:'record', title:'后台跟进记录填写情况', align:'center'},
                    {field:'ability', title:'服务商对接能力与态度', align:'center'},
                    {fixed:'right', title:'操作', align:'center', toolbar:'#buttons',width:150}
                ]],
                done: function (){
                    //表格刷新完后执行
                }
            });
            table.on('tool(table)', function(obj){
                //data就是一行的数据
                var data = obj.data;
                if(obj.event === 'delete'){
                    layer.confirm('确定要删除吗', function(){
                        $.post("{{ route('admin.docking.delete') }}",{id:data.id},function(res){
                            if (res.code == 0){
                                obj.del();//删除表格这行数据
                            }
                            layer.msg(res.msg);
                        });
                    });
                }
                if(obj.event === 'edit'){
                    id = data.id;
                    $('#add_time').val(data.add_time);
                    $('#service_name').val(data.service_name);
                    $('#name').val(data.name);
                    $('#mobile').val(data.mobile);
                    $('#member_type').val(data.member_type);
                    $('#province').val(data.province);
                    $('#city').val(data.city);
                    $('#service_region').val(data.service_region);
                    $('#service_project').val(data.service_project);
                    $('#saturated').val(data.saturated);
                    $('#push_time').val(data.push_time);
                    $('#sale_name').val(data.sale_name);
                    $('#record').val(data.record);
                    $('#ability').val(data.ability);
                    layer.open({
                        type: 1,
                        title:'编辑服务商对接表',
                        skin: 'layui-layer-rim',//边框
                        area: ['80rem;', '45rem;'],
                        content: $('#content'),
                    });
                }
            });
            $('#create').click(function(){
                id = 0;
                $('#add_time').val('');
                $('#service_name').val('');
                $('#name').val('');
                $('#mobile').val('');
                $('#member_type').val('');
                $('#province').val('');
                $('#city').val('');
                $('#service_region').val('');
                $('#service_project').val('');
                $('#saturated').val('');
                $('#push_time').val('');
                $('#sale_name').val('');
                $('#record').val('');
                $('#ability').val('');
                layer.open({
                    type: 1,
                    title:'添加服务商对接表',
                    skin: 'layui-layer-rim',//边框
                    area: ['80rem;', '45rem;'],
                    content: $('#content'),
                });
            });
            $('#submit').click(function(){
                var data = {
                    add_time:$('#add_time').val(),
                    service_name:$('#service_name').val(),
                    name:$('#name').val(),
                    mobile:$('#mobile').val(),
                    member_type:$('#member_type').val(),
                    province:$('#province').val(),
                    city:$('#city').val(),
                    service_region:$('#service_region').val(),
                    service_project:$('#service_project').val(),
                    saturated:$('#saturated').val(),
                    push_time:$('#push_time').val(),
                    sale_name:$('#sale_name').val(),
                    record:$('#record').val(),
                    ability:$('#ability').val(),
                };
                if(id == 0){
                    var url = "{{ route('admin.docking.create') }}";
                }else{
                    data.id = id;
                    var url = "{{ route('admin.docking.edit') }}";
                }
                $.post(url,data,function(res){
                    if(res.code == 0){
                        layer.closeAll();
                        //提交数据成功后重载表格
                        table.reload('table',{ //表格的id
                            page: {
                                curr: 1,
                            }
                        });
                    }
                    layer.msg(res.msg);
                });
            });
            $('#search').click(function(){
                //传递where条件实现搜索，并且重载表格数据
                table.reload('table',{ //表格的id
                    page: {
                        curr: 1,
                    },
                    where:{
                        'keyword':$('#search_keyword').val(),
                        'date':$('#search_date').val(),
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
