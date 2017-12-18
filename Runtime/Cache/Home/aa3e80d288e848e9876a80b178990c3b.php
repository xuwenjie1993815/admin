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

    <div class="view-product">
        <div class="info-center">

            <div class="table-margin ">
                <?php if(!empty($list)): ?><table class="table table-hover">
                        <tr class="table-header border">
                            <th class="w10">排序</th>
                            <th class="w10">账户</th>
                            <th class="w10">昵称</th>
                            <th class="w15">操作</th>
                        </tr>

                        <tbody id="itemContainer">
                        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr class="list">
                                <td><?php echo ($i); ?></td>
                                <td><?php echo ($item["account"]); ?></td>
                                <td><?php echo ($item["nick_name"]); ?></td>
                                <td>
                                    <a href="javascript:void(0)" onclick="del(<?php echo ($item["id"]); ?>)"  class="btn btn-warning btn-small">删除</a>
									| <a href="javascript:void(0)" onclick="repassword(<?php echo ($item["id"]); ?>)"  class="btn btn-success btn-small">修改密码</a>
                                </td>
                                
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>

                    </table>
                    <?php else: ?>
                    <div style="text-align:center"><span style="color:#CCCCCC;font-size:18px">没有符合条件的记录</span></div><?php endif; ?>
            </div>
            <!-- fpage -->
            <div class="page"><?php echo ($fpage); ?></div>
        </div>
    </div>

    <script>

        function del(id){
            
            $.ajax({
                type:'POST',
                url:"<?php echo U('adminDel');?>",
                data:{'id':id},
                dataType:'json',
                success:function(data) {
                    layer.msg(data.msg);

                    if(data.status)
                    {
                        setTimeout(function() {window.parent.close_layer(data.msg);}, 700)
                    }
                }
            });
        }
        function repassword(id){
		alert(id);
            layer.open({
                type: 2,
                title: '修改密码',
                shadeClose: true,
                shade: 0.8,
                skin: 'layui-layer-title-jxc',
                area: ['500px', '200px'],
                content:'/Home/Rabc/repassword/id/' + id,
            });
		}
        function open_layer(obj, title, width, height) {
            var url = $(obj).data('url');
            layer.open({
                type: 2,
                title: title,
                shadeClose: true,
                shade: 0.8,
                skin: 'layui-layer-title-jxc',
                area: [width, height],
                content:url,
            });
        }
        
        function close_layer(msg) {
            if (msg) {
                setTimeout(function () {
                    layer.closeAll('iframe');
                }, 500);
                layer.msg(msg);
                window.location.reload();
            } else {
                layer.closeAll('iframe');
            }
        }

    </script>



</body>
</html>