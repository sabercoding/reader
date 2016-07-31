<?php
require_once "Ots.php";

$books = (new \reader\Ots())::getBooks();
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=gbk" />
    <meta name="author" content="www.frebsite.nl" />
    <meta name="viewport" content="width=device-width initial-scale=1.0 maximum-scale=1.0 user-scalable=yes" />

    <title>小说</title>

    <link type="text/css" rel="stylesheet" href="css/style.css" />
    <link type="text/css" rel="stylesheet" href="css/jquery.mmenu.all.css" />
    <link type="text/css" rel="stylesheet" href="css/jquery.mobile-1.0rc2.min.css" >
    <link type="text/css" rel="stylesheet" href="css/photoswipe/photoswipe.css" />
    <script type="text/javascript" src="js/jquery.min.js"></script>

    <script type="text/javascript">
        $(function() {
            $("#all").click(function() {
                $('input[name="items"]').attr("checked",true);
            });

            $("#update").click(function(){
                var arr = [];
                $("input[name='items']:checked").each(function () {
                    arr.push(this.value);
                });
                $.post("/scan.php",
                    {
                        book:arr
                    },
                    function(data,status){
                        alert("数据：" + data + "\n状态：" + status);
                    });
            });
        });
    </script>
</head>
<body class="o-page">
<div id="page">
    <div id="header">
        <a href="#menu" rel="external"></a>

        <a class="backBtn" href="javascript:history.back();"></a>
    </div>
    <div class="subHeader"><i class="i-gallery i-small"></i>书列表</div>
    <div id="content">
        <div>
            <?php
            foreach ($books as $book) {
                echo '<input type="checkbox" name="items" value="'.$book.'">'.$book.'<br />';
            }
            ?>
<!--            <input type="checkbox" id="all">-->

            <br />
            <button id="all">全选</button>
            <button id="update">更新</button>
            <br /><br />
        </div>

    </div>



    <div class="subFooter">Copyright 2013. All rights reserved.</div>
</div>
</body>
</html>