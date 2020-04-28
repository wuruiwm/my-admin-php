@extends('admin.comm.base')

@section('content')
    <div class="layui-form">
        <blockquote class="layui-elem-quote">
            <div class="layui-inline" style="margin-left: 1rem;">
                <button type="button" class="layui-btn" id="translate"><i class="layui-icon ">&#xe663;</i> 翻译</button>
            </div>
            <div class="layui-inline" style="margin-left: 1rem;">
                <select lay-verify="required" id="language">
                    @foreach ($language as $k => $v)
                        <option value="{{$k}}">{{$v}}</option>
                    @endforeach
                </select>
            </div>
            <div class="layui-inline" style="margin-left: 1rem;">
                <button type="button" class="layui-btn" id="translate_dst_copy" data-clipboard-text=""><i class="fa fa-files-o" aria-hidden="true"></i> 复制翻译后文本</button>
            </div>
        </blockquote>
    </div>
    <div class="layui-form" style="margin-right: 5rem;">
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">原文本</label>
            <div class="layui-input-block">
                <textarea name="desc" placeholder="请输入需要翻译的文本" class="layui-textarea" id="translate_src_text" style="height: 20rem;"></textarea>
            </div>
        </div>
    </div>
    <div class="layui-form" style="margin-right: 5rem;">
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">翻译后文本</label>
            <div class="layui-input-block">
                <textarea name="desc" placeholder="翻译后的文本会出现在这里喔" class="layui-textarea" id="translate_dst_text" style="height: 20rem;"></textarea>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        layui.use(['layer','form'], function(){
            var layer = layui.layer;
            new Clipboard('#translate_dst_copy');
            $('#translate').click(function(){
                $.post("{{ route('admin.translate.translate') }}",{text:$('#translate_src_text').val(),language:$('#language').val()},function(res){
                    if(res.code == 0){
                        $('#translate_dst_text').val(res.text);
                        $('#translate_dst_copy').attr('data-clipboard-text',res.text).click(function(){
                            layer.msg("复制成功");
                        });
                    }
                    layer.msg(res.msg);
                });
            });
        });
    </script>
@endsection
