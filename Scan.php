<?php
require_once "Ots.php";


$books = (new \reader\Ots())::putChapter('为啥你在这儿', '第三章', '', '第四章', '第二章', time());
