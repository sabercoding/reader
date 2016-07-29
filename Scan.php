<?php
namespace reader;

class Scan {
    public function scanChapter() {
        $chapters_url = 'http://www.ishisetianxia.com/';
        $chapter_data = file_get_contents($chapters_url."buxiufanren/");

    }

    public function scanContent($url) {

    }

}