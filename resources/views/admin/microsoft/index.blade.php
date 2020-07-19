@extends('admin.comm.base')

@section('content')
    <div class="layui-form">
        <blockquote class="layui-elem-quote quoteBox">
            <div class="layui-inline" style="margin-left: 2rem;">
                <a class="layui-btn" id="create"><i class="layui-icon">&#xe608;</i> 添加邀请码</a>
            </div>
            <div class="layui-inline" style="margin-left: 2rem;">
                <input type="text" placeholder="请输入关键词进行搜索..." class="layui-input" id="search_keyword" style="width:15rem;">
            </div>
            <div class="layui-inline" style="margin-left: 1rem;">
                <select id="search_status">
                    <option value="">请选择是否使用</option>
                    <option value="1">已使用</option>
                    <option value="0">未使用</option>
                </select>
            </div>
            <div class="layui-inline" style="margin-left: 1rem;">
                <a class="layui-btn  layui-btn-normal" id="search"><i class="layui-icon ">&#xe615;</i> 搜索</a>
            </div>
            <div class="layui-inline" style="margin-left: 1rem;">
                <button type="button" class="layui-btn" id="export"><i class="layui-icon ">&#xe67d;</i> 导出</button>
            </div>
        </blockquote>
    </div>

    <table class="layui-hide" id="table" lay-filter="table"></table>

    <div id="content" class="layui-form layui-form-pane" style="display: none;margin:1rem 3rem;">
        <div class="layui-form-item">
            <label class="layui-form-label">生成数量</label>
            <div class="layui-input-block">
                <input type="text" placeholder="请输入生成数量" class="layui-input" value="" id="num">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-filter="formDemo" id="submit">立即提交</button>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/html" id="buttons">
        <button class="layui-btn layui-btn-normal layui-btn-xs" lay-event="active"><i class="layui-icon">&#xe605;</i>允许</button>
        <button class="layui-btn layui-btn-warm layui-btn-xs" lay-event="inactive"><i class="layui-icon">&#x1006;</i>禁止</button>
        <button class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delete"><i class="layui-icon">&#xe640;</i>删除</button>
    </script>

    <script>
        layui.use(['table','form'], function(){
            var id;
            var table = layui.table;
            var form = layui.form;
            var list_url = "{{ route('admin.microsoft.list') }}";
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
                    {field:'code', title:'邀请码', align:'center'},
                    {field:'status', title:'状态', align:'center',templet:function (d) {
                            if(d.status == 0){
                                return '<span style="color:green;">未使用</span>';
                            }else{
                                return '<span style="color:red;">已使用</span>';
                            }
                        }
                    },
                    {field:'email',title: '注册账号',align: 'center',templet:function(d){
                            if(d.email){
                                return d.email;
                            }else{
                                return '-';
                            }
                        }
                    },
                    {field:'account_status', title: '账户状态',align: 'center',templet:function(d){
                            if(d.account_status == -1){
                                return '<span style="color:red;">已禁用</span>';
                            }else if(d.account_status == 0){
                                return '<span style="color:green;">正常</span>';
                            }else{
                                return '-';
                            }
                        }
                    },
                    {field:'updated_at', title:'修改时间', align:'center'},
                    {field:'created_at', title:'创建时间', align:'center'},
                    {fixed:'right', title:'操作', align:'center', toolbar:'#buttons',width:230}
                ]],
                done: function (){
                    //表格刷新完后执行
                }
            });
            table.on('tool(table)', function(obj){
                //data就是一行的数据
                var data = obj.data;
                if(obj.event === 'delete'){
                    layer.confirm('删除邀请码会删除关联的账户，确定删除吗？', function(){
                        $.post("{{ route('admin.microsoft.delete') }}",{id:data.id},function(res){
                            if (res.code == 0){
                                obj.del();//删除表格这行数据
                            }
                            layer.msg(res.msg);
                        });
                    });
                }
                if(obj.event === 'inactive'){
                    layer.confirm('确认禁止登录吗', function(){
                        $.post("{{ route('admin.microsoft.inactive') }}",{id:data.id},function(res){
                            if (res.code == 0){
                                table.reload('table',{ //表格的id
                                    page: {
                                        curr: 1,
                                    }
                                });
                            }
                            layer.msg(res.msg);
                        });
                    });
                }
                if(obj.event === 'active'){
                    layer.confirm('确认允许登录吗', function(){
                        $.post("{{ route('admin.microsoft.active') }}",{id:data.id},function(res){
                            if (res.code == 0){
                                table.reload('table',{ //表格的id
                                    page: {
                                        curr: 1,
                                    }
                                });
                            }
                            layer.msg(res.msg);
                        });
                    });
                }
            });
            $('#create').click(function(){
                $('#num').val('');
                layer.open({
                    type: 1,
                    title:'添加邀请码',
                    skin: 'layui-layer-rim', //加上边框
                    area: ['50rem;', '12rem;'], //宽高
                    content: $('#content'),
                });
            });
            $('#submit').click(function(){
                var data = {
                    num:$('#num').val(),
                };
                $.post("{{ route('admin.microsoft.create') }}",data,function(res){
                    if (res.code == 0) {
                        layer.closeAll();
                        //提交数据成功后重载表格
                        table.reload('table',{ //表格的id
                            page: {
                                curr: 1,
                            }
                        });
                    }
                    layer.msg(res.msg);
                },'json');
            });
            $('#search').click(function(){
                //传递where条件实现搜索，并且重载表格数据
                table.reload('table',{ //表格的id
                    page: {
                        curr: 1,
                    },
                    where:{
                        'keyword':$('#search_keyword').val(),
                        'status':$('#search_status').val(),
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
                            res.data[k].status == 1 ? res.data[k].status = '已使用' : res.data[k].status = '未使用';
                            res.data[k].eamil ? res.data[k].eamil = res.data[k].eamil : '-';
                            res.data[k].account_status ? res.data[k].eamil = res.data[k].eamil : '-';
                            if(res.data[k].account_status == -1){
                                res.data[k].account_status = '已禁用';
                            }else if(res.data[k].account_status == 0){
                                res.data[k].account_status = '正常';
                            }else{
                                res.data[k].account_status = '-';
                            }
                        }
                        table.exportFile(['ID','邀请码','是否使用','注册账号','创建时间','修改时间','账户状态'],res.data,'csv');
                    }
                });
            });
        });
    </script>
@endsection
