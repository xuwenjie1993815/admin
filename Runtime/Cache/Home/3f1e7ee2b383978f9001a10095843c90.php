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
                <form id='list'>
                    <div class="tr-th clearfix text-right" style='margin-top: -20px;margin-bottom: 5px;'>
                        <div class="td padding-left-15 strong">
                            <input type='button' value='提交' class='button submit-order bg-mix padding-top-10'/>
                        </div>
                    </div>
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
                                    <td class="checks">
                                        <?php if(($item["opt"]) == "1"): ?><div style="margin-left: 20px;">
                                                <?php if(($item["checked"]) == "1"): ?>选择<input type="checkbox" onclick='choose(this, <?php echo ($item["groupid"]); ?>, <?php echo ($item["id"]); ?>)' class="child<?php echo ($item["groupid"]); ?>" name="node_id[]" checked="checked" value="<?php echo ($item["id"]); ?>" />
                                                    <?php else: ?>
                                                    选择<input type="checkbox" onclick='choose(this, <?php echo ($item["groupid"]); ?>, <?php echo ($item["id"]); ?>)' class="child<?php echo ($item["groupid"]); ?>" name="node_id[]" value="<?php echo ($item["id"]); ?>" /><?php endif; ?>
                                            </div>
                                            <?php else: ?>
                                            <span style="color:red;" class="permission-list">
                                                选择
                                                <?php if(($item["checked"]) == "1"): ?><input type="checkbox" id='parent<?php echo ($item["id"]); ?>' name="menu_id[]" checked="checked" value="<?php echo ($item["id"]); ?>">
                                                    <?php else: ?>
                                                        <input type="checkbox" id='parent<?php echo ($item["id"]); ?>' name="menu_id[]" value="<?php echo ($item["id"]); ?>"><?php endif; ?>
                                            </span><?php endif; ?>
                                    </td>

                                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>

                        </table>
                        <?php else: ?>
                        <div style="text-align:center"><span style="color:#CCCCCC;font-size:18px">没有符合条件的记录</span></div><?php endif; ?>
                    <input type="hidden" name="role_id" value="<?php echo ($id); ?>">
                </form>    
            </div>
            <!-- fpage -->
            <div class="page"><?php echo ($fpage); ?></div>
        </div>
    </div>

    <script>
        $(document).ready(function () { 
        })
        
        $(".checks span input:checkbox").click(function () {
            var thisValue = $(this).val()
            var childClass = '.child' + thisValue
            if ($(this).prop("checked")) {
                $(childClass).prop("checked", true);
            } else {
                $(childClass).prop("checked", false);
            }
        })
        
        function choose (self, value, currentValue) {
            var childClass = '.child' + value // 子级
            var parentId = '#parent' + value // 父级
            console.log()
            var flag = true
            for (var i = 0; i < $(childClass).length; i++) {
                if (!$($(childClass)[i]).prop('checked')) {
                    flag = false
                }
            }
            
            if (flag) {
                $(parentId).prop('checked', true)
            } else {
                $(parentId).prop('checked', false)
            }
        }
        $(".submit-order").click(function(){
            $.ajax({
                data: $("#list").serializeArray(),
                type: 'post',
                url:"<?php echo U('roleEdit');?>",
                dataType: 'json',
                success: function (data) {
                    layer.msg(data.msg);

                    if(data.status)
                    {
                        setTimeout(function() {window.location.reload();}, 700)
                    }
                },
            });
        })
    </script>



</body>
</html>