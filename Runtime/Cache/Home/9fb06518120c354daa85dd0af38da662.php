<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PRODUCT</title>

    <link href="/www/Public/default/1.0.8/iconfont.css" rel="stylesheet" type="text/css" />
    <link href="/www/Public/default/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/www/Public/default/css/layout.css" rel="stylesheet">
    <link href="/www/Public/default/css/style.css" rel="stylesheet">
    <link href="/www/Public/default/select2/select2.css" rel="stylesheet">

    <link href="/www/Public/default/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/www/Public/default/bootstrap/js/bootstrap.js">

    <link href="/www/Public/admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="/www/Public/admin/css/bootstrap-theme.css" rel="stylesheet">
    <link href="/www/Public/admin/css/elegant-icons-style.css" rel="stylesheet" />
    <link href="/www/Public/admin/css/font-awesome.min.css" rel="stylesheet" />
    <link href="/www/Public/admin/css/style.css" rel="stylesheet">
    <link href="/www/Public/admin/css/style-responsive.css" rel="stylesheet" />

    <script src="/www/Public/default/jquery/jquery.min.js"></script>
    <script src="/www/Public/default/js/layer/layer.js" type="text/javascript"></script>
    <script src="/www/Public/default/global.js" type="text/javascript"></script>
    <script src="/www/Public/default/layui/layui.js" type="text/javascript"></script>
    <script src="/www/Public/default/select2/select2.min.js" type="text/javascript"></script>
    <script src="/www/Public/default/select2/zh-CN.js" type="text/javascript"></script>

    <style>
        .paging{overflow:hidden;margin:0;padding:0;}
        .paging a{float:left;display:block;overflow:hidden;margin:5px;padding:3px 8px; border:1px solid #CCC;}
        .paging a:hover{background-color:#333;color:#FFF;}
        .paging a.current{background-color:#333;color:#FFF;}
        .num{
            margin-right: 2rem;
            color: #8A95Aa !important;
            font-size: 16px;
        }
        .current{
            text-decoration:underline;
            color: #3E77D0;
            margin-right: 2rem;
            font-weight:600;
            font-size: 20px;
        }
    </style>
</head>
<body>
<script>

</script>

    <div class="view-product">
        <div class="info-center">
        <form role="form" method="POST">
            <div class="search_style table-margin">
                <div class="title_names text-blue">通知列表</div>
                <ul class="search_content clearfix">
                    <li class='w30'>
                        <!-- <input type="text" class="w100 height34 border" style="width:250px" placeholder="输入订单号" id="order_sn" name="order_sn" value="<?php echo ($order_sn); ?>"> -->
                        <!-- <input type="text" placeholder="订单号" value="<?php echo ($order_sn); ?>" class="w100 height34 border" id="order_sn"> -->
                    </li>
                    <li class=''><button type="submit" class="btn btn-success" id="" name="">搜索</button></li>
                    <!-- <li class=''><button class="btn_search button bg-blue" id="search"><i class="fa fa-search"></i> 搜索</button></li> -->
                    <li class=''><a href="<?php echo U();?>"><button class="btn_search button bg-orange">刷新</button></a></li>
                </ul>
            </div>
        </form>
            <div class="table-margin ">
                <?php if(!empty($list)): ?><table class="table table-hover">
                        <tr class="table-header border">
                            <th class="w5">ID</th>
                            <th class="w10">类型</th>
                            <th class="w10">标题</th>
                            <th class="w10">内容</th>
                            <th class="w10">发布人</th>
                            <th class="w20">创建时间</th>
                            <th class="w10">状态</th>
                            <th class="w10">操作</th>
                        </tr>

                        <tbody id="itemContainer">
                        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr class="list">
                                <td><?php echo ($item["notice_id"]); ?></td>
                                <td><?php echo ($item["type"]); ?></td>
                                <td><?php echo ($item["notice_title"]); ?></td>
                                <td><?php echo ($item["content"]); ?></td>
                                <td><?php echo ($item["admin_name"]); ?></td>
                                <td><?php echo (date('Y-m-d H:i', $item["add_time"])); ?></td>
                                <td><?php echo ($item["notice_status_name"]); ?></td>
                                <td>
                                    <a style="text-decoration:none" class="ml-5" onclick="order_detail('订单详情','<?php echo U('Home/Order/orderDetails',array('id'=>$item['order_id'],'order_type' => $item['order_type']));?>')" href="javascript:;" title="查看"><i class="Hui-iconfont">&#xe665;</i></a>
                                    <?php if($item['order_status'] != 3): ?><a title="删除" href="javascript:;" onclick="system_category_del(this,<?php echo ($item["order_id"]); ?>)" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a><?php endif; ?>
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
    <!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="/www/Public/default/js/layer/2.4/layer.js"></script>
<!--/_footer 作为公共模版分离出去-->
    <script src="//cdn.bootcss.com/jquery/1.12.1/jquery.min.js"></script>
    <script src="/www/Public/default/js/laypage/1.2/laypage.js"></script>

    <script type="text/javascript">
    function order_detail(title, url){
        var index = layer.open({
            type: 2,
            title: title,
            content: url
        });
        layer.full(index);
    }

    /*订单--删除*/
    function system_category_del(obj,id){
        layer.confirm('确认要删除吗？',function(index){
            $.ajax({
                type: 'POST',
                url: '<?php echo U("delOrder");?>',
                data: {'id':id},
                dataType: 'json',
                success: function(data){
                    if (data.status == 1){
                        $(obj).parents("tr").remove();
                        layer.msg(data.msg,{icon:1,time:1000});
                        window.location.reload();
                    }else {
                        layer.msg(data.msg,{icon:2,time:1000});
                    }

                },
                error:function(data) {
                    console.log(data.msg);
                },
            });
        });
    }

    </script>>


</body>
</html>