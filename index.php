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
		<script type="text/javascript" src="js/photoswipe/klass.min.js"></script>
		<script type="text/javascript" src="js/jquery.mmenu.min.all.js"></script>
		<script type="text/javascript" src="js/jquery.mobile-1.0rc2.min.js"></script>
		<script type="text/javascript" src="js/photoswipe/code.photoswipe.jquery-3.0.4.min.js"></script>
		<script type="text/javascript" src="js/o-script.js"></script>

	</head>
	<body class="o-page">
		<div id="page">
			<div id="header">
				<a href="#menu" rel="external"></a>
				
				<a class="backBtn" href="javascript:history.back();"></a>
			</div>
			<div class="subHeader"><i class="i-gallery i-small"></i>书列表</div>
			<div id="content">

<?php
foreach ($books as $book) {
    echo '<h3 class="title"><a href="/chapter_list.php?book='.$book.'" rel="external">'.$book.'</a></h3>';
}
?>
                    </div>
				</div>
				
			</div>
			
			<div class="subFooter">Copyright 2013. All rights reserved.</div>
			
		</div>
	</body>
</html>