@extends('admin.comm.base')

@section('content')
    <div class="layui-form">
        <blockquote class="layui-elem-quote">
            <div class="layui-inline" style="margin-left: 1rem;">
                <button type="button" class="layui-btn copy" id="key" data-clipboard-text="{{$key}}" onclick="layer.msg('复制成功')"><i class="fa fa-files-o" aria-hidden="true"></i> 复制key</button>
            </div>
            <div class="layui-inline" style="margin-left: 1rem;">
                <button type="button" class="layui-btn copy" id="pem" data-clipboard-text="{{$pem}}" onclick="layer.msg('复制成功')"><i class="fa fa-files-o" aria-hidden="true"></i> 复制pem</button>
            </div>

            <div class="layui-inline" style="margin-left: 1rem;font-size: 1rem;">
                @if (!empty($end_time))
                    <span style="color: #3d763e;">证书到期时间:&nbsp;{{$end_time}}</span>
                @else
                    <span style="color: red;">exec函数被禁用或者证书文件不正确 无法查看证书到期时间</span>
                @endif
            </div>
        </blockquote>
    </div>
    <pre class="key" style="float: left;width: 49%;">
        {{$key}}
    </pre>
    <pre class="pem" style="float: right;width: 49%;">
        {{$pem}}
    </pre>
@endsection

@section('script')
    <script>
        layui.use(['layer','code'], function(){
            var layer = layui.layer;
            layui.code({
                elem: '.key', //默认值为.layui-code
                title:'key',
                about:false
            });
            layui.code({
                elem: '.pem', //默认值为.layui-code
                title:'pem',
                about:false
            });
        });
        $(function(){
            new Clipboard('.copy');
        });
    </script>
@endsection
