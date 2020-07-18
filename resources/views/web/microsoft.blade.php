<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="https://blobs.officehome.msocdn.com/images/content/images/favicon-8f211ea639.ico" />
    <title>{{admin_config('office365_title')}}</title>
    <link rel="stylesheet" href="https://cdn.nikm.cn/css/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdui/0.4.3/css/mdui.min.css">
    <style>
        @media screen and (max-width:600px){.mdui-toolbar>img{margin:auto}.hero>.mdui-typo-display-3{font-size:27px}.hero>.mdui-typo-title-opacity{font-size:15px}.enroll{padding:40px 15px!important}.email{display:block!important}}@media screen and (min-width:600px){.enroll{padding:50px 40px!important}}.enroll-mdui-dialog{max-width:inherit;overflow-x:hidden;overflow-y:scroll}.hero-bg{background:url(https://i.loli.net/2020/01/25/KEScJXCBfAzaIjW.png) no-repeat;background-size:cover;height:100vh;overflow:scroll}.hero-btn{width:120px;height:45px}.enroll{background-color:#fff;position:absolute;color:#000;padding:60px 60px 100px 60px;display:none}.code,.email{display:flex;align-items:flex-end;justify-content:space-between}
    </style>
</head>

<body>

<div class="mdui-dialog" id="msg">
    <div class="mdui-dialog-title">创建失败...</div>
    <div class="mdui-dialog-content">激活码无效！</div>
    <div class="mdui-dialog-actions">
        <button class="mdui-btn mdui-ripple" mdui-dialog-confirm>我知道了</button>
    </div>
</div>

<div class="mdui-appbar">
    <div class="mdui-toolbar" style="width: 85%; margin: auto">
        <img class="mdui-img-fluid" src="https://i.loli.net/2020/04/21/ST9ru5mwVqUXnKO.png" alt="">
        <span class="mdui-typo-display-1 mdui-hidden-xs">|</span>
        <span class="mdui-typo-title mdui-hidden-xs">Office</span>
        <div class="mdui-toolbar-spacer mdui-hidden-xs"></div>
        <a class="mdui-typo-title mdui-hidden-xs" href="https://github.com/wuruiwm/msautocreate"><i class="fa fa-github fa-6" aria-hidden="true"></i></a>
    </div>
</div>

<div class="hero-bg mdui-typo mdui-text-color-white-text mdui-valign">

    <div class="hero mdui-col-xs-10 mdui-col-offset-xs-1 mdui-text-center">
        <div class="mdui-center mdui-typo-display-3">{{admin_config('office365_title')}}</div>
        <div class="mdui-typo-title-opacity">{{admin_config('office365_subtitle')}}</div>
        <br>
        <button class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-red mdui-m-a-1 hero-btn" id="getOffice">获取账号</button>
        <button class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-white mdui-m-a-1 hero-btn" onclick="window.location.href='https://office.com/login'">登录</button>
    </div>

    <div class="enroll-mdui-dialog mdui-dialog enroll mdui-col-xs-10 mdui-col-offset-xs-1 mdui-col-md-8 mdui-col-offset-md-2 mdui-shadow-8" id="enroll">
        <div class="mdui-typo-title">获取账号</div>
        <hr><br>

        <form>

            <div class="mdui-col-md-6 mdui-col-offset-md-3 mdui-col-xs-12" style="display: none" id="createdAccount">
                <div class="mdui-textfield">
                    <i class="mdui-icon material-icons mdui-text-color-pink">email</i>
                    <p>邮箱：</p>
                    <input class="mdui-textfield-input" type="email" value="" id="email">
                </div>
                <div class="mdui-textfield">
                    <i class="mdui-icon material-icons mdui-text-color-pink">lock</i>
                    <p>初始密码：</p>
                    <input class="mdui-textfield-input" type="text" value="" id="password">
                </div>
                <br>
                <br>
                <a class="mdui-btn mdui-btn-raised mdui-ripple mdui-text-color-yellow mdui-color-pink" href="https://office.com/login" target="_blank" style="float: right">前往登录</a>
                <div style="clear: both"></div>
            </div>

            <div class="mdui-progress" style="display: none">
                <div class="mdui-progress-indeterminate"></div>
            </div>

            <div class="mdui-col-xs-12 mdui-col-md-6" id="accountInfo">
                <div class="mdui-typo-subheading">账号信息：</div>
                <br>
                <div class="mdui-col-xs-12">
                    <span>订阅：</span>
                    <select class="mdui-select" name="sku_id" mdui-select id="sku_id">
                        @foreach (json_decode(admin_config('office_sku_id'),true) as $k => $v)
                            <option value="{{$v['sku_id']}}">{{$v['title']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mdui-textfield mdui-textfield-floating-label mdui-col-xs-12">
                    <label class="mdui-textfield-label">姓名</label>
                    <input class="mdui-textfield-input" type="text" maxlength="20" required id="display_name" name="display_name">
                    <div class="mdui-textfield-error">用户名仅限中文/英文/数字</div>
                    <div class="mdui-textfield-helper">账号展示的姓名</div>
                </div>
                <div class="email mdui-col-xs-12">
                    <div class="mdui-textfield mdui-textfield-floating-label" style="width: 100%">
                        <label class="mdui-textfield-label">用户名</label>
                        <input class="mdui-textfield-input" type="text" maxlength="20" required id="user_name" name="user_name" pattern="^[A-Za-z0-9]+$">
                        <div class="mdui-textfield-error">邮箱前缀仅限英文/数字</div>
                        <div class="mdui-textfield-helper">邮箱 @ 前面的字符</div>
                    </div>
                    <span class="mdui-hidden-xs">&nbsp;&nbsp;&nbsp;</span>
                    <select class="mdui-select" mdui-select style="margin-bottom: 28px; color: black" name="domain" id="domain">
                        @foreach (json_decode(admin_config('office_domain'),true) as $k => $v)
                            <option value="{{$v}}">@ {{$v}}</option>
                        @endforeach
                    </select>
                </div>
                <div style="clear: both"></div>
                <br>
                <br>
            </div>

            <div class="mdui-col-xs-12 mdui-col-md-5 mdui-col-offset-md-1" id="activation">
                <div class="mdui-typo-subheading">激活信息：</div>

                <div class="mdui-col-xs-12 code">
                    <div class="mdui-textfield mdui-textfield-floating-label" style="width: 100%">
                        <label class="mdui-textfield-label">激活码</label>
                        <input class="mdui-textfield-input" type="text" required id="invitation_code" name="code">
                        <div class="mdui-textfield-error">必填</div>
                    </div>
                    <span>&nbsp;&nbsp;&nbsp;</span>
                    <button class="mdui-btn mdui-color-pink mdui-ripple" style="margin-bottom: 28px; padding: 0" onclick="window.open('{{admin_config('office365_code_buy_link')}}')">获取激活码</button>
                </div>

                <div class="mdui-col-xs-12">
                    <br>
                    <br>
                    <input type="submit" class="mdui-col-xs-12 mdui-btn mdui-ripple mdui-color-pink hero-button" style="float: right" id="submit">
                </div>
            </div>

        </form>

    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/mdui/0.4.3/js/mdui.min.js"></script>
<script src="https://cdn.nikm.cn/js/jquery.js"></script>
<script src="https://cdn.nikm.cn/js/layui/layui.js"></script>
<script>
    var layer;
    var form;
    layui.use(['form','layer'], function(){
        form = layui.form;
        layer = layui.layer;
    });
    var enroll = new mdui.Dialog("#enroll", {
        modal: !0
    });
    $("#getOffice").on("click", function(e) {
        enroll.open()
    });
    $("form").on("submit", function(e){
        e.preventDefault();
        var data = {
            sku_id: $("#sku_id").val(),
            display_name: $("#display_name").val().trim(),
            user_name: $("#user_name").val().trim(),
            domain: $("#domain").val(),
            invitation_code: $("#invitation_code").val().trim(),
        };
        $("#accountInfo, #activation").hide();
        $(".mdui-progress").show();
        $(".enroll").height("auto");
        $("hr").hide();
        mdui.mutation();
        $.post('{{route('microsoft.submit')}}',data,function(res){
            $(".mdui-progress").hide();
            $("hr").show();
            if(res.code == 0){
                $("#createdAccount").show();
                $("#email").val(res.data.email);
                $("#password").val(res.data.password);
            }else if(res.code == 1){
                $("#accountInfo, #activation").show();
                enroll.close();
                $('#msg>.mdui-dialog-content').html(res.msg);
                new mdui.Dialog("#msg").open();
            }
            $("#display_name").val("");
            $("#user_name").val("");
            $("#invitation_code").val("");
            $(".enroll").height("auto");
        },'json');
    });
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        dataType:'json',
        timeout:30000,
        error:function(xhr){
            var msg;
            if(xhr.status == 419){
                msg = 'CSRF验证过期,请刷新本页面后重试';
            }else if(xhr.status == 403){
                msg = '请检查您是否有权限';
            }else{
                msg = '访问出错';
            }
            $(".mdui-progress").hide();
            $("hr").show();
            $("#accountInfo, #activation").show();
            enroll.close();
            $('#msg>.mdui-dialog-content').html(msg);
            new mdui.Dialog("#msg").open();
            $("#display_name").val("");
            $("#user_name").val("");
            $("#invitation_code").val("");
            $(".enroll").height("auto");
        },
    });
</script>
</body>

</html>
