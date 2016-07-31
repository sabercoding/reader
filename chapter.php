<?php
require_once "Ots.php";
$book_name = $_GET['book'];
$chapter_name = $_GET['chapter'];
$chapter = (new \reader\Ots())::getChapterContent($book_name, $chapter_name);
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
		<meta name="viewport" content="width=device-width initial-scale=1.0 maximum-scale=1.0 user-scalable=yes" />

		<title><?php echo $chapter['chapter_name']?></title>

        <link type="text/css" rel="stylesheet" href="css/style.css" />
        <link type="text/css" rel="stylesheet" href="css/jquery.mmenu.all.css" />
        <link type="text/css" rel="stylesheet" href="css/jquery.mobile-1.0rc2.min.css" >
        <link type="text/css" rel="stylesheet" href="css/photoswipe/photoswipe.css" />

	</head>
	<body style="background-color: #FBF6EC;" class="o-page">
		<div id="page">
			<div id="header">
				<a href="#menu"></a>
                <a style="position:initial; width: auto;background:no-repeat -21px -303px" href="/chapter_list.php?book=<?= $book_name;?>"><?= $book_name;?></a>
				<a class="backBtn" href="javascript:history.back();"></a>
			</div>
			<div id="content">
                <article>
                    <h2><a href="#"><?php echo $chapter['chapter_name']?></a></h2>
                    <p>
                        <?php echo $chapter['content']?>
                    </p>
                    <div>
                        <?php if ($chapter['previous_chapter']) {?>
                            <a href="/chapter.php?chapter=<?=$chapter['previous_chapter']?>&book=<?=$book_name?>">前一章</a>
                        <?php }
                        if ($chapter['last_chapter']) {?>
                            <a href="/chapter.php?chapter=<?=$chapter['last_chapter']?>&book=<?=$book_name?>">后一章</a>
                        <?php }?>
                    </div>
                </article>
            </div>
        </div>
    </body>
</html>