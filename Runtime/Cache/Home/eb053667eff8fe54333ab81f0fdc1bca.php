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

    <div class="view-product">
        <div class="info-center">
            <div class="tr-th clearfix padding-top text-center">
                <div class="td padding-left-15 strong">
                    <input type='button' value='添加角色' data-url="/www/index.php/Home/Rabc/roleAdd/id/<?php echo ($item['id']); ?>" onclick="open_layer(this, '添加角色', '50%', '200px')" class='button submit-order bg-mix padding-top-10' name='memo'/>
                </div>
        </div>
            <div class="table-margin ">
                <?php if(!empty($list)): ?><table class="table table-hover">
                        <tr class="table-header border">
                            <th class="w10">排序</th>
                            <th class="w10">角色名</th>
                            <th class="w15">操作</th>
                        </tr>

                        <tbody id="itemContainer">
                        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr class="list">
                                <td><?php echo ($i); ?></td>
                                <td><?php echo ($item["nick_name"]); ?></td>
                                <?php if($item["id"] == 1): ?><td>
                                    <a href="javascript:void(0)" data-url="/www/index.php/Home/Rabc/roleEditInfo/id/<?php echo ($item['id']); ?>" onclick="open_layer(this, '请输入菜单名称', '50%', '340px')"  class="btn btn-warning btn-small">修改</a>
                                    | <a href="javascript:void(0)" class="btn btn-info btn-small use" data-url="/www/index.php/Home/Rabc/adminList/id/<?php echo ($item['id']); ?>" onclick="open_layer(this, '登录用户', '50%', '340px')">登录用户</a>
                                    | <a href="javascript:void(0)" class="btn btn-info btn-small use" data-url="/www/index.php/Home/Rabc/adminAdd/id/<?php echo ($item['id']); ?>" onclick="open_layer(this, '添加用户', '50%', '340px')">添加用户</a>
                                </td>
                                <?php else: ?>
                                <td>
                                    <a href="javascript:void(0)" data-url="/www/index.php/Home/Rabc/roleEditInfo/id/<?php echo ($item['id']); ?>" onclick="open_layer(this, '请输入菜单名称', '50%', '340px')"  class="btn btn-warning btn-small">修改</a>
                                    | <a href="javascript:void(0)" class="btn btn-info btn-small use" data-url="/www/index.php/Home/Rabc/adminList/id/<?php echo ($item['id']); ?>" onclick="open_layer(this, '登录用户', '50%', '340px')">登录用户</a>
                                    | <a href="javascript:void(0)" class="btn btn-info btn-small use" data-url="/www/index.php/Home/Rabc/adminAdd/id/<?php echo ($item['id']); ?>" onclick="open_layer(this, '添加用户', '50%', '340px')">添加用户</a>
                                    | <a  href="/www/index.php/Home/Rabc/roleList/role_id/<?php echo ($item['id']); ?>" class="btn btn-success use" onClick="javascript:return del('<?php echo ($item["id"]); ?>')">设置权限</a>
                                </td><?php endif; ?>
                                
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

        function del($id){
            $.ajax({
                type:'POST',
                url:"<?php echo U('MenuDel');?>",
                data:{'id':$id},
                dataType:'json',
                success:function(obj) {
                    layer.msg(data.msg);

                    if(data.status)
                    {
                        setTimeout(function() {window.parent.close_layer(data.msg);}, 700)
                    }
                }
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