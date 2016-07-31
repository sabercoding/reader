<?php
require_once "Ots.php";
$book_name = $_GET['book'];
$chapters = (new \reader\Ots())::getChapters($book_name);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=gbk" />
		<meta name="author" content="www.frebsite.nl" />
		<meta name="viewport" content="width=device-width initial-scale=1.0 maximum-scale=1.0 user-scalable=yes" />

		<title><?= $book_name;?></title>

        <link type="text/css" rel="stylesheet" href="css/style.css" />
        <link type="text/css" rel="stylesheet" href="css/jquery.mmenu.all.css" />
        <link type="text/css" rel="stylesheet" href="css/jquery.mobile-1.0rc2.min.css" >
        <link type="text/css" rel="stylesheet" href="css/photoswipe/photoswipe.css" />

	</head>
	<body class="o-page">
		<div id="page">
			<div id="header">
				<a href="#menu" rel="external"></a>
				<a style="position:initial; width: auto;background:no-repeat -21px -303px" href="reader.sabre91.com">书列表</a>
				<a class="backBtn" href="javascript:history.back();"></a>
			</div>
			<div class="subHeader"><i class="i-gallery i-small"></i>书列表</div>
			<div id="content">
				<h3 class="title"><?= $book_name;?></h3>
				<div id="Gallery">
					<div class="gallery-row">
<?php
foreach ($chapters as $chapter) {
    echo '<h3 class="title"><a href="/chapter.php?chapter='.$chapter['chapter_name']."&book=".$book_name.'" rel="external">'.$chapter['chapter_name'].'</a></h3>';
}
?>
                    </div>
				</div>
				
			</div>
			
			<div class="subFooter">Copyright 2013. All rights reserved.</div>
			
		</div>
	</body>
</html>