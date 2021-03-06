<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>好运兆</title>
    <link href="/www/Public/default/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <style>
        /* reset */
        html, body, div,  object, iframe,
        h1, h2, h3, h4, h5, h6, p, blockquote, pre,
        abbr, address, cite, code,
        del, dfn, em, img, ins, kbd, q, samp,
        small, strong, sub, sup, var,
        b, i,
        dl, dt, dd, ol, ul, li,
        fieldset, form, label, legend,
        table, caption, tbody, tfoot, thead, tr, th, td,
        article, aside, canvas, details, figcaption, figure,
        footer, header, hgroup, menu, nav, section, summary,
        time, mark, audio, video {
            margin:0;
            padding:0;
            border:0;
            outline:0;
            font-size:100%;
            vertical-align:baseline;
            background:transparent;
            font-family: 'MicroSoft Yahei';
            font-size: 14px;
        }

        body {
            line-height:1;
        }

        :focus {
            outline: 1;
        }

        article,aside,canvas,details,figcaption,figure,
        footer,header,hgroup,menu,nav,section,summary {
            display:block;
        }

        nav ul {
            list-style:none;
        }

        blockquote, q {
            quotes:none;
        }

        blockquote:before, blockquote:after,
        q:before, q:after {
            content:'';
            content:none;
        }

        a {
            margin:0;
            padding:0;
            border:0;
            font-size:100%;
            vertical-align:baseline;
            background:transparent;
            text-decoration: none;
            display: block;
        }

        ins {
            background-color:#ff9;
            color:#000;
            text-decoration:none;
        }

        mark {
            background-color:#ff9;
            color:#000;
            font-style:italic;
            font-weight:bold;
        }

        del {
            text-decoration: line-through;
        }

        abbr[title], dfn[title] {
            border-bottom:1px dotted #000;
            cursor:help;
        }

        table {
            border-collapse:collapse;
            border-spacing:0;
        }

        hr {
            display:block;
            height:1px;
            border:0;
            border-top:1px solid #cccccc;
            margin:1em 0;
            padding:0;
        }

        input, select {
            vertical-align:middle;
        }

        ul,li {list-style: none;}

        .cl { zoom: 1;}
        .cl:after {content: '';display: block;clear: both;}
        .fl {float: left;}
        .fr {float: right;}
        .fc {margin:0px auto;}

        .login{
            position:absolute; 
            width:400px; 
            height:50px; 
            left:258px; 
            top:10px;
            z-index: 100;
            line-height:100%;
            white-space:nowrap;
            color: #fff
        }
        
        /* index css */
        #topnav {
            height: 45px;
            background: #438eb9;
        }

        #topnav .logo {
            padding: 0 10px;
        }

        #topnav .logo a {
            color: #fff;
            font-size: 22px;
            margin: 10px 0;
            display: block;
        }
        #topnav .logo a:hover{
            text-decoration: underline;
        }

        #topnav ul li{
            float: left;
            line-height: 45px;
            width: 100px;
            text-align: center;
        }

        #topnav ul li a {
            color:#fff;
        }

        #topnav ul li a:hover{
            text-decoration: underline;
        }

        .topbar{
            width: 100%;
            background: #f2f2f2;
        }

        a:link,a:visited{
            text-decoration:none;
        }

        .menuDiv{
            width: 200px;
            position: fixed;
            height: 100%;
            left: 0;
            top: 45px;
            z-index: 99999;
            border-right: 1px solid #ccc;
            background: #f2f2f2;
        }
        .parentLi
        {
            padding-left: 15px;

            line-height: 40px;
            margin-top: 1px;
            color: #ccc;
            cursor: pointer;
        }
        .parentLi span
        {
            color:#333;
        }
        .parentLi:hover > span, .selectedParentMenu
        {
            color: #438eb9;
        }
        .childrenUl
        {
            display: none;
        }
        .childrenLi
        {
            width: 100%;
            line-height: 30px;
            font-size: .8em;
            margin-top: 1px;
            color: #000000;
            padding-left: 15px;
            cursor: pointer;
            border-left: 5px #f2f2f2 solid;
        }
        .childrenLi:hover, .selectedChildrenMenu
        {
            border-left: 5px #438eb9 solid;
        }

        childrenLi a{font-size: 14px !important}

        .menu{
            display: none
        }

        /* 右侧content */
        .r-content{
            position: absolute;
            left:210px;
            right: 0;
            top:60px;
            bottom:5px;
        }
        .r-content #mainframe{border: none;width:100%; min-height:100%}

        li.on{background:#186490; }
        .blue{color:#438eb9 }
    </style>
</head>
<body>
<!-- 头部导航 -->
<div id="topnav" class="cl">
    <div class="logo fl">
        <a href="/" style="text-decoration:none;">好运兆管理后台</a>
    </div>

    <ul class="cl fr" id="mainmenu"></ul>
</div>

<div id="login" class="login" style="width: 100px;">
    <?php echo ($dates); ?>，<font style="color: #FFE69F"><?php echo ($username); ?></font>
    <br>
    <p><a href="#" onclick="javascript:return logout()" style=" color: #ff9">注销登陆</a></p>
</div>

<!-- 左侧sidebar -->
<div class="menuDiv" id="sonmenu">

</div>
<!-- 右侧content -->
<div class="r-content">
    <iframe id="mainframe" src=""></iframe>
</div>


<script src="/www/Public/default/jquery/jquery.min.js"></script>
<script>
    function logout(){
        $.ajax({
            url: "<?php echo U('Logout',array(),'html',true);?>",
            type: 'POST',
            dataType: 'json',
            jsonp: 'menu',
            success: function(data) {
                window.location.href="<?php echo U('Login');?>";
            }
        })
    }
    
    $(function()
    {

        $("body").on('click', '#logout', function()
        {
            if(!confirm('确定注销吗？')) return false;

            var url = $(this).data('url');

            window.location.href = url;
            return false;
        });


        $.ajax({
            url: "<?php echo U('Home/Index/index',array('ac'=>'menu'),'html',true);?>",
            type: 'POST',
            dataType: 'json',
            jsonp: 'menu',
            success: function(data) {
                nav(data);
            }
        })

        var menuData;
        $("#mainmenu").on('click','li',function(){
            var index = $("#mainmenu li").index($(this))
            $("ul.sonmenu_"+index).find("li").find("a").removeClass("blue")
            $("ul.sonmenu_"+index).find("li").eq(0).find("a").addClass("blue")
            $("ul.sonmenu_"+index).find("li").eq(0).find("a").click()
            $("#sonmenu ul li").eq(0).find("a").html()
            $(this).addClass('on').siblings('li').removeClass('on');
            var sondom='.sonmenu_'+$(this).attr('data-index');
            console.log($('ul'+sondom))
            $('ul'+sondom).show().siblings('.menu').hide();
        })

        
        
        function nav(data){
            var html='';
            
            $.each(data,function(i,v){
                
                $("#mainmenu").append('<li data-index="'+i+'" class="parentLi link"><span><a herf="javascript:void(0)" data-href="'+v.url+'">'+v.text+'</a></span></li>');
                if(!!v.children){
                    html+='<ul class="menu sonmenu_'+i+'" >';
                    console.log(i)
                    $.each(v.children,function(m,n){
                        console.log(n)
                        console.log(!!v.url)
                        
                        if(!!n.url){
                            html+='<li class="parentLi link">';
                            html+='<span><a herf="javascript:void(0)" data-href="'+n.url+'" class="'+(m==0?"blue":"")+'">'+n.text+'</a></span>';
                        }else if(!!n.children){
                            html+='<li class="parentLi">';
                            html+='<span><a herf="javascript:void(0)" data-href="" class="'+(m==0?"blue":"")+'"><i class="fa fa-caret-right"></i>  '+n.text+'</a></span>';
                            html+='<ul class="childrenUl">';
                            $.each(n.children,function(x,y){
                                html+='<li class="childrenLi link"><span><a herf="javascript:void(0)" data-href="'+y.url+'">'+y.text+'</a></span></li>';
                            })
                            html+='</ul>';
                        }
                        html+='</li>';
                    })
                    
                    html+='</ul>';
                }
            })
            $("#sonmenu").html(html)
           
        }

        $("#sonmenu").on('click','li.parentLi',function(){
            $(this).find('ul.childrenUl').slideDown();
            $(this).siblings('li.parentLi').find('ul.childrenUl').slideUp();
        })

        $("#sonmenu").on('click','a',function(){
           
            if($(this).closest('li').hasClass('link')){

                $('.link').find('a').removeClass('blue')
                $(this).addClass('blue')
            }
            var url=$(this).attr('data-href');
            if(!!url){
                $("#mainframe").attr('src',url);
            }else{
                $(this).find('i').removeClass('fa-caret-right').addClass('fa-caret-down')
                $(this).closest('li.parentLi').siblings('li.parentLi').find('i').removeClass('fa-caret-down').addClass('fa-caret-right');
            }
        })


    })
</script>
</body>
</html>