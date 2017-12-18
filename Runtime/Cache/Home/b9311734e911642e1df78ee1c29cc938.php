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
            <div class="search_style table-margin">
                <div class="title_names text-blue">用户列表</div>
                <ul class="search_content clearfix">
                    <li class='w30'>
                        <input type="text" placeholder=" 请填写用户电话" value="<?php echo ($tel); ?>" class="w100 height34 border" id="tel">
                    </li>

                    <li class=''><button class="btn_search button bg-blue" id="search"><i class="fa fa-search"></i> 搜索</button></li>
                    <li class=''><a href="<?php echo U();?>"><button class="btn_search button bg-orange">刷新</button></a></li>
                </ul>
            </div>

            <div class="table-margin ">
                <?php if(!empty($list)): ?><table class="table table-hover">
                        <tr class="table-header border">
                            <th class="w5">编号</th>
                            <th class="w15">会员卡号</th>
                            <th class="w10">用户名</th>
                            <th class="w10">真实姓名</th>
                            <th class="w20">手机</th>
                            <!--<th class="w20">QQ号</th>-->
                            <th class="w20">职位</th>
                            <!--<th class="w20">省</th>-->
                            <!--<th class="w20">市</th>-->
                            <!--<th class="w20">区</th>-->
                            <th class="w15">注册时间</th>
                            <th class="w10">启用状态</th>
                        </tr>

                        <tbody id="itemContainer">
                        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr class="list">
                                <td><?php echo ($item["id"]); ?></td>
                                <td><?php echo ($item["user_card"]); ?></td>
                                <td><?php echo ($item["user_name"]); ?></td>
                                <td><?php echo ($item["real_name"]); ?></td>
                                <td><?php echo ($item["tel"]); ?></td>
                                <!--<td><?php echo ($item["qq"]); ?></td>-->
                                <td><?php echo ($item["job"]); ?></td>
                                <!--<td><?php echo ($item["province"]); ?></td>-->
                                <!--<td><?php echo ($item["city"]); ?></td>-->
                                <!--<td><?php echo ($item["county"]); ?></td>-->
                                <td><?php echo (date('Y-m-d H:i', $item["ctime"])); ?></td>
                                <?php if(($item["status"]) == "1"): ?><td><span class="text-green">已启用</span></td>
                                    <?php else: ?>
                                    <td><span class="text-red">已禁用</span></td><?php endif; ?>

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

    <script src="//cdn.bootcss.com/jquery/1.12.1/jquery.min.js"></script>

    <script type="text/javascript">
        function delcar(id){
            layer.confirm('确定删除吗？',function(index){
                if(index){
                    $.ajax({
                        url:"<?php echo U('delcar');?>",
                        dataType:'json',
                        type:'POST',
                        data:{'id':id},
                        success:function(data){
                            layer.msg(data.msg);
                            if(data.status == 1){
                                setTimeout(function(){
                                    window.location.reload();
                                },500);
                            }
                        }
                    })
                }
            })
        }
    </script>>


</body>
</html>