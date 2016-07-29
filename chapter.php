<?php
require_once "Ots.php";
$book_name = $_GET['book'];
$chapter_name = $_GET['chapter'];
$chapter = (new \reader\Ots())::getChapterContent($book_name, $chapter_name);

?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=gbk" />
		<meta name="author" content="www.frebsite.nl" />
		<meta name="viewport" content="width=device-width initial-scale=1.0 maximum-scale=1.0 user-scalable=yes" />

		<title><?php echo $chapter['chapter_name']?></title>

        <link type="text/css" rel="stylesheet" href="css/style.css" />
        <link type="text/css" rel="stylesheet" href="css/jquery.mmenu.all.css" />
        <link type="text/css" rel="stylesheet" href="css/jquery.mobile-1.0rc2.min.css" >
        <link type="text/css" rel="stylesheet" href="css/photoswipe/photoswipe.css" />

        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/photoswipe/klass.min.js"></script>
        <script type="text/javascript" src="js/jquery.mmenu.min.all.js"></script>
        <script type="text/javascript" src="js/jquery.mobile-1.0rc2.min.js"></script>
        <script type="text/javascript" src="js/photoswipe/code.photoswipe.jquery-3.0.4.min.js"></script>
        <script type="text/javascript" src="js/o-script.js"></script>
	</head>
	<body class="o-page">
		<div id="page">
			<div id="header">
				<a href="#menu"></a>
            
				<a class="backBtn" href="javascript:history.back();"></a>
			</div>
			<div id="content">
                <article>
                    <h2><a href="#"><?php echo $chapter['chapter_name']?></a></h2>
                    <p>
                        <?php echo $chapter['content']?>
                    </p>
                </article>

            </div>
            <div class="subFooter">Copyright 2013. All rights reserved.</div>

        </div>
    </body>
</html>