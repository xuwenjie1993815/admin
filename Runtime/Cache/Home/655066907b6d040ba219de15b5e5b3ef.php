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
            <div class="search_style table-margin">
                <div class="title_names text-blue">左侧子菜单管理(请不要轻易改动)</div>
<!--                
                <ul class="search_content clearfix">
                    <li style="float: right">
                        <a href="javascript:void(0)" data-url="/index.php/Home/Rabc/NodeUpdate" onclick="open_layer(this, '请输入菜单名称', '50%', '340px')"  class="btn btn-warning btn-small">添加菜单</a>
                    </li>
                </ul>-->
            </div>

            <div class="table-margin ">
                <?php if(!empty($list)): ?><table class="table table-hover">
                        <tr class="table-header border">
                            <!--<th class="">编号</th>-->
                            <th class="w20">菜单名称</th>
                            <th class="w20">参数配置</th>
                            <th class="w10">归属菜单</th>
                            <th class="w10">是否为菜单</th>
                            <th class="w10">显示顺序</th>
                            <th class="w15">操作</th>
                        </tr>

                        <tbody id="itemContainer">
                        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr class="list">
                                <!--<td><?php echo ($item["id"]); ?></td>-->
                                <td><?php echo ($item["title"]); ?></td>
                                <td><?php echo ($item["name"]); ?></td>
                                <td><?php echo ($item["groupname"]); ?></td>
                                <td style="padding-left: 30px;"><?php echo ($item["ismenu"]); ?></td>
                                <td style="padding-left: 30px;"><?php echo ($item["sort"]); ?></td>
                                <td>
                                    <?php if(($item["opt"]) == "1"): ?><a href="javascript:void(0)" data-url="/index.php/Home/Rabc/NodeEdit/id/<?php echo ($item['id']); ?>" onclick="open_layer(this, '请输入菜单名称', '50%', '340px')"  class="btn btn-warning btn-small">修改</a>
                                    | <a href="javascript:void(0)" class="btn btn-info btn-small use" onClick="javascript:return del('<?php echo ($item["id"]); ?>')">删除</a><?php endif; ?>
                                    <!--
                                    <?php if(($item["status"]) == "1"): ?>| <a href="javascript:void(0)" data-id="<?php echo ($item['id']); ?>" data-url="/index.php/Home/Rabc/start" class="btn btn-info btn-small use">停用</a>
                                        <?php else: ?>
                                        | <a href="javascript:void(0)" data-id="<?php echo ($item['id']); ?>" data-url="/index.php/Home/Rabc/start" class="btn btn-warning btn-small use">启用</a><?php endif; ?>
                                    -->
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

        function del($id){
            $.ajax({
                type:'POST',
                url:"<?php echo U('NodeDel');?>",
                data:{'id':$id},
                dataType:'json',
                success:function(obj) {
                    msg(obj.msg);
                    if(obj.status){redirect(window.location.href)}
                    return false;
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