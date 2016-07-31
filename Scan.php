<?php
require_once "Ots.php";


//namespace reader;
//
//class Scan {
//    public function scanChapter() {
//        $chapters_url = 'http://www.ishisetianxia.com/';
//        $chapter_data = file_get_contents($chapters_url."buxiufanren/");
$books = (new \reader\Ots())::putChapter('为啥你在这儿', '第三章', '', '第四章', '第二章', time());
echo 11;
//    }
//
//    public function scanContent($url) {
//
//    }
//
//}
//(new Scan())->scanChapter();