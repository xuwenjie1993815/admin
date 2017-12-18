<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container-fluid">
            <p></p>
            <div class="row">
            <div class="col-md-12">
                <a href="<?php echo U('version','','html',true);?>" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 新增版本信息</a>
            </div>
        </div>
            <p></p>
       <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$dt): $mod = ($i % 2 );++$i;?><div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6">版本号：Version <?php echo ($dt["version"]); ?></div>
                    <div class="col-md-6 text-right"><?php echo ($dt["updatetime"]); ?></div>
                </div>
                
         
            </div>
            <div class="panel-body">
                <h5>更新内容：</h5>
                <p><?php echo ($dt["remark"]); ?></p>
            </div>

            <!-- List group -->
            <ul class="list-group">
              <li class="list-group-item">下载地址：http://<?php echo ($_SERVER['HTTP_HOST']); echo substr($dt['downloadlink'],1);?></li>
            </ul>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        
    </body>
</html>