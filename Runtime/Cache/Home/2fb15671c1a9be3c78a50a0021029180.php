<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PRODUCT</title>

    <link href="/www/Public/default/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/www/Public/default/css/layout.css" rel="stylesheet">
    <link href="/www/Public/default/css/style.css" rel="stylesheet">
    <link href="/www/Public/default/select2/select2.css" rel="stylesheet">

    <link href="/www/Public/default/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/www/Public/default/bootstrap/js/bootstrap.js">

    <script src="/www/Public/default/jquery/jquery.min.js"></script>
    <script src="/www/Public/default/layer/layer.js" type="text/javascript"></script>
    <script src="/www/Public/default/global.js" type="text/javascript"></script>
    <script src="/www/Public/default/layui/layui.js" type="text/javascript"></script>
    <script src="/www/Public/default/select2/select2.min.js" type="text/javascript"></script>
    <script src="/www/Public/default/select2/zh-CN.js" type="text/javascript"></script>

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
                <div class="td w15 padding-left-15 strong text-right">菜单名：</div>
                <div class="td w80 padding-left-15 strong"><input type='text' class='w50 input remark' name='name' id='name' value="<?php echo ($info["name"]); ?>"></input></div>
            </div>
            <div class="tr-th clearfix ">
                <div class="td w15 padding-left-15 strong text-right">显示顺序：</div>
                <div class="td w80 padding-left-15 strong"><input type='number' class='w25 input remark' name='sort' id='sort' value="<?php echo ($info["sort"]); ?>"></input></div>
            </div>
            <div class="tr-th clearfix ">
                <div class="td w15 padding-left-15 strong text-right">跳转链接：</div>
                <div class="td w80 padding-left-15 strong"><input type='text' class='w50 input remark' name='url' id='sort' value="<?php echo ($info["url"]); ?>"></input></div>
            </div>
        </div>

        <div class="tr-th clearfix padding-top text-center">
            <div class="td padding-left-15 strong">
                <input type='hidden' name='id' value="<?php echo ($info["id"]); ?>"  />
                <input type='button' value='提交' class='button submit-order bg-mix padding-top-10' name='memo'/>
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

            if($(":input[name='name']").val() == '') {
                layer.msg('请填写菜单名称');
                return false;
            }

            $.ajax({
                data: $("#post_form").serializeArray(),
                type: 'post',
                url:"<?php echo U('MenuUpdate');?>",
                dataType: 'json',
                beforeSend:function() {
                    _this.prop('disabled', 'true');
                    load = layer.load(2);
                },
                success: function (data) {
                    layer.msg(data.msg);

                    if(data.status)
                    {
                        setTimeout(function() {window.parent.close_layer(data.msg);}, 700)
                    }
                },
                complete:function() {
                    _this.prop('disabled', '');
                    layer.close(load);
                },
                error:function() {
                    layer.msg('系统繁忙');
                }
            });
        });
    </script>

</body>
</html>