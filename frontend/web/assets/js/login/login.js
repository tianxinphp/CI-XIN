layui.use(['jquery','layer','form','carousel'],function(){
    var form = layui.form,
        carousel=layui.carousel,
        layer = parent.layer === undefined ? layui.layer : top.layer,
        $ = layui.jquery,
        flow=layui.flow;

    $(".loginBody .seraph").click(function(){
        layer.msg("这只是做个样式，至于功能，以后再做",{
            time:3000
        });
    });

    //登录按钮
    form.on("submit(login)",function(data){
        var button=$(this);
        $.ajax({
            url:'login',
            type: 'post',
            dataType:'json',
            data:$("#loginForm").serialize(),
            beforeSend:function () {
                $(button).text("登录中...").attr("disabled","disabled").addClass("layui-disabled");
            },
            complete:function () {
                $(button).text("登录").attr("disabled",false).removeClass("layui-disabled");
            },
            success:function (data) {
                console.log(data);
                if(data.result){
                    layer.msg(data.msg, {icon: 1});
                    setTimeout(function(){
                        window.location.href = "/welcome";
                    },1000);
                }else{
                    layer.msg(data.msg, {icon: 5,time:1000});
                    $("#imgCode #verification_code").click();
                    refreshCsrf();
                }
            },
            error:function (data) {
                layer.msg('请重试', {icon: 5,time:1000});
            }
        });
        return false;
    });

    //表单验证
    form.verify({
        username: function(value, item){ //value：表单的值、item：表单的DOM对象
            if(!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)){
                return '用户名不能有特殊字符';
            }
            if(/(^\_)|(\__)|(\_+$)/.test(value)){
                return '用户名首尾不能出现下划线\'_\'';
            }
            if(/^\d+\d+\d$/.test(value)){
                return '用户名不能全为数字';
            }
        }

        //我们既支持上述函数式的方式，也支持下述数组的形式
        //数组的两个值分别代表：[正则匹配、匹配不符时的提示文字]
        ,password: [
            /^[\S]{6,12}$/
            ,'密码必须6到12位，且不能出现空格'
        ]
    });

    //图片轮播
    carousel.render({
        elem: '#login_bk'
        ,width: '100%'
        ,height: '100%'
        ,anim: 'fade'
        ,arrow: 'none'//不显示箭头
        ,interval: 5000//5秒轮播
        ,indicator:'none'//不显示指示器
    });


    //表单输入效果
    $(".loginBody .input-item").click(function(e){
        e.stopPropagation();
        $(this).addClass("layui-input-focus").find(".layui-input").focus();
    });
    $(".loginBody .layui-form-item .layui-input").focus(function(){
        $(this).parent().addClass("layui-input-focus");
    });
    $(".loginBody .layui-form-item .layui-input").blur(function(){
        $(this).parent().removeClass("layui-input-focus");
        if($(this).val() != ''){
            $(this).parent().addClass("layui-input-active");
        }else{
            $(this).parent().removeClass("layui-input-active");
        }
    });

    /**
     * 动态绑定刷新验证码
     */
   $("#imgCode").on('click','#verification_code',function (e) {
       e.stopPropagation();
       $.ajax({
           url:'createVerification',
           type: 'get',
           dataType:'text',
           success:function (data) {
               console.log(data);
               $("#verification_code").remove();
               $("#imgCode").append(data);
           },
           error:function (data) {
               layer.msg('刷新验证码失败', {icon: 5});
           }
       });
   });

    /**
     * 刷新csrf
     */
    function refreshCsrf() {
        $.ajax({
            url:'refreshCsrf',
            type: 'get',
            dataType:'text',
            success:function (data) {
                console.log(data);
                $("#loginForm").find("input[type='hidden']").remove();
                $("#loginForm").append(data);
            },
            error:function (data) {
                layer.msg('刷新验证码失败', {icon: 5});
            }
        });
    }




});
