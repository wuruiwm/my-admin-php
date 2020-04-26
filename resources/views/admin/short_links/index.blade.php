@extends('admin.comm.base')

@section('content')
    <style>
        .layui-input{
            display: inline;
            width: 75%;
        }
    </style>
    <div class="layui-form">
        <blockquote class="layui-elem-quote">
            <div class="layui-inline" style="margin-left: 2rem;">
                <button class="layui-btn" id="create"><i class="layui-icon">&#xe608;</i> 添加短链</button>
            </div>
            <div class="layui-inline" style="margin-left: 2rem;">
                <input type="text" placeholder="请输入关键词进行搜索..." class="layui-input" id="search_keyword" style="width:15rem;">
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

    <!-- 添加编辑弹窗  -->
    <div id="content" class="layui-form layui-form-pane" style="display: none;margin:1rem 3rem;">
        <div class="layui-form-item">
            <label class="layui-form-label">短链</label>
            <div class="layui-input-block">
                <input type="text" placeholder="请输入短链" class="layui-input" id="tail">
                <button class="layui-btn" id="rand_tail" style="margin-left: 5%;">随机生成</button>
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
@endsection

@section('script')
    <script type="text/html" id="buttons">
        <button class="layui-btn layui-btn-xs copy" data-clipboard-text="{{ $short_links_domain_name }}/@{{d.tail}}"><i class="layui-icon">&#xe64c;</i>复制短链</button>
        <button class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit"><i class="layui-icon">&#xe642;</i>编辑</button>
        <button class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delete"><i class="layui-icon">&#xe640;</i>删除</button>
    </script>

    <!-- 二维码templet -->
    <script type="text/html" id="qrcode">
        <img class="qrcode" style="width: 32px" data-tail="@{{d.tail}}">
    </script>

    <script>
        layui.use(['table'], function(){
            var id;
            var table = layui.table;
            var list_url = "{{ route('admin.short_links.list') }}";
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
                    {field:'tail',title: '短链', align:'center'},
                    {field:'link', title: '跳转地址', align:'center'},
                    {field:'qrcode',title: '二维码',align: 'center',templet:'#qrcode'},
                    {field:'remark',title: '备注', align:'center', templet: function(d){
                        if(d.remark){
                            return d.remark;
                        }else{
                            return '无备注';
                        }
                    }},
                    {field:'updated_at', title:'修改时间', align:'center'},
                    {field:'created_at', title:'创建时间', align:'center'},
                    {fixed:'right', title:'操作', align:'center', toolbar:'#buttons',width:250}
                ]],
                done: function (){
                    //表格刷新完后执行
                    //点击按钮复制
                    new Clipboard('.copy');
                    //复制后提示
                    $('.copy').click(function(){
                        layer.msg('复制成功');
                    });
                    //链接转二维码
                    $('.qrcode').each(function(){
                        var url = '{{ $short_links_domain_name }}/' + $(this).data('tail');
                        $(this).attr('src',jrQrcode.getQrBase64(url));
                    });
                    //鼠标悬停，图片放大
                    hover_open_img();
                }
            });
            table.on('tool(table)', function(obj){
                //data就是一行的数据
                var data = obj.data;
                if(obj.event === 'delete'){
                    layer.confirm('确定要删除吗', function(){
                        $.post("{{ route('admin.short_links.delete') }}",{id:data.id},function(res){
                            if (res.code == 0){
                                obj.del();//删除表格这行数据
                            }
                            layer.msg(res.msg);
                        });
                    });
                }
                if(obj.event === 'edit'){
                    id = data.id;
                    $('#tail').val(data.tail);
                    $('#link').val(data.link);
                    $('#remark').val(data.remark);
                    layer.open({
                        type: 1,
                        title:'编辑短链',
                        skin: 'layui-layer-rim',
                        area: ['45rem;', '18rem;'],//边框
                        content: $('#content'),
                    });
                }
            });
            $('#create').click(function(){
                id = 0;
                $('#tail').val('');
                $('#link').val('');
                $('#remark').val('');
                layer.open({
                    type: 1,
                    title:'添加短链',
                    skin: 'layui-layer-rim',
                    area: ['45rem;', '18rem;'],//边框
                    content: $('#content'),
                });
            });
            $('#submit').click(function(){
                var data = {
                    tail:$('#tail').val(),
                    link:$('#link').val(),
                    remark:$('#remark').val()
                };
                if(id == 0){
                    var url = "{{ route('admin.short_links.create') }}";
                }else{
                    data.id = id;
                    var url = "{{ route('admin.short_links.edit') }}";
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
                    }
                });
            });
            //监听按下回车键时并且焦点在搜索框时，触发搜索
            $(document).on('keydown', function(e){
                if(e.keyCode == 13 && $("#search_keyword").is(":focus")){
                    $('#search').click();
                }
            });
            $('#rand_tail').click(function(){
                $.get("{{ route('admin.short_links.rand_tail') }}",function(res){
                    if(res.code == 0){
                        $('#tail').val(res.tail);
                    }
                });
            });
            //鼠标悬停，图片放大
            function hover_open_img(){
                var img_show;
                $('td img').hover(
                    function (){
                        var kd = $(this).width();
                        var kd1 = kd * 5;
                        var kd2 = kd * 5 + 30;
                        var img = "<img class='img_msg' src='" + $(this).attr('src') + "' style='width:" + kd1 + "px;' />";
                        img_show = layer.tips(img, this, {
                            tips: [2, 'rgba(41,41,41,.1)'],
                            area: [kd2 + 'px']
                        });
                    },
                    function (){
                        layer.close(img_show);
                    }
                );
            }
            $('#export').click(function(){
                var count = $('.layui-laypage-count').text().replace('共 ','').replace(' 条','');
                $.get(list_url+'?page=1&limit='+count,function(res){
                    if(res.code == 0){
                        table.exportFile(['ID','短链','跳转地址','备注','创建时间','修改时间'],res.data,'csv');
                    }
                });
            });
        });
    </script>
@endsection
