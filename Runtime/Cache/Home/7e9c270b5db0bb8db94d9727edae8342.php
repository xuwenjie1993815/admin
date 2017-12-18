<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PRODUCT</title>

    <link href="/Public/default/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/Public/default/css/layout.css" rel="stylesheet">
    <link href="/Public/default/css/style.css" rel="stylesheet">
    <link href="/Public/default/select2/select2.css" rel="stylesheet">

    <link href="/Public/default/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Public/default/bootstrap/js/bootstrap.js">

    <script src="/Public/default/jquery/jquery.min.js"></script>
    <script src="/Public/default/layer/layer.js" type="text/javascript"></script>
    <script src="/Public/default/global.js" type="text/javascript"></script>
    <script src="/Public/default/layui/layui.js" type="text/javascript"></script>
    <script src="/Public/default/select2/select2.min.js" type="text/javascript"></script>
    <script src="/Public/default/select2/zh-CN.js" type="text/javascript"></script>

    <style>
        .paging{overflow:hidden;margin:0;padding:0;}
        .paging a{float:left;display:block;overflow:hidden;margin:5px;padding:3px 8px; border:1px solid #CCC;}
        .paging a:hover{background-color:#333;color:#FFF;}
        .paging a.current{background-color:#333;color:#FFF;}
    </style>
</head>
<body>
<script>

</script>

    <form id="post_form">
        <div class="offcial-table input-table table-margin clearfix ">
            <div class="tr-th clearfix ">
                <div class="td w15 padding-left-15 strong text-right">角色名称：</div>
                <div class="td w80 padding-left-15 strong"><input type='text' class='w75 input remark' name='nick_name' id='nick_name' value="<?php echo ($info["nick_name"]); ?>" /></div>
            </div>
        </div>

        <div class="tr-th clearfix padding-top text-center">
            <div class="td padding-left-15 strong">
                <input type='button' value='提交' class='button submit-order bg-mix padding-top-10'/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type='button' value='取消' onclick="javascript:window.parent.close_layer();" class='button bg-grey'/>
            </div>
        </div>
    </form>



    <script>
        var body = $('body');
        var _this,data,load,confirm;

        $(".submit-order").click(function () {

            _this = $(this);
            
            var nick_name = $(":input[name='nick_name']").val();
            
            if(nick_name == '') {
                layer.msg('请填写角色名称');
                return false;
            }
            $.ajax({
                data: {'nick_name':nick_name},
                type: 'post',
                url:"<?php echo U('roleAdd');?>",
                dataType: 'json',
                success: function (data) {
                    layer.msg(data.msg);

                    if(data.status)
                    {
                        setTimeout(function() {window.parent.close_layer(data.msg);}, 700)
                    }
                },
            });
        });
    </script>

</body>
</html>