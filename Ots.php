<?php
namespace reader;

require(__DIR__ . "/ots/vendor/autoload.php");
require(__DIR__ . "/config.php");

use Aliyun\OTS\OTSClient as OTSClient;

class Ots {

    protected static $es = null;

    public static function getInstance() {
        if (empty(self::$es)) {
            self::$es = new OTSClient(array(
                'EndPoint' => EXAMPLE_END_POINT,
                'AccessKeyID' => EXAMPLE_ACCESS_KEY_ID,
                'AccessKeySecret' => EXAMPLE_ACCESS_KEY_SECRET,
                'InstanceName' => EXAMPLE_INSTANCE_NAME,
                'DebugLogHandler' => null,
                'ErrorLogHandler' => null
            ));
        }
        return self::$es;
    }

    public static function getBooks() {
        $startPK = array(
            'book_name' => array('type' => 'INF_MIN'),         // array('type' => 'INF_MIN') 用来表示最小值
        );

        $endPK = array(
            'book_name' => array('type' => 'INF_MAX'),         // array('type' => 'INF_MAX') 用来表示最小值
        );

        $data = array();
        while (!empty($startPK)) {

            $request = array(
                'table_name' => 'Books',
                'direction' => 'FORWARD',                          // 方向可以为 FORWARD 或者 BACKWARD
                'inclusive_start_primary_key' => $startPK,         // 开始主键
                'exclusive_end_primary_key' => $endPK,             // 结束主键
            );

            $response = self::getInstance()->getRange($request);

            foreach ($response['rows'] as $rowData) {
                $data[] = $rowData['primary_key_columns']['book_name'];
            }

            // 如果 next_start_primary_key 不为空，则代表
            // 范围内还有数据，需要继续读取
            $startPK = $response['next_start_primary_key'];
        }
        return $data;
    }

    public static function getChapters($book_name) {
        $startPK = array(
            'book_name' => $book_name,
            'chapter_name' => array('type' => 'INF_MIN')         // array('type' => 'INF_MIN') 用来表示最小值
        );

        $endPK = array(
            'book_name' => $book_name,
            'chapter_name' => array('type' => 'INF_MAX')         // array('type' => 'INF_MAX') 用来表示最小值
        );

        $data = array();
        while (!empty($startPK)) {

            $request = array(
                'table_name' => 'Chapters',
                'direction' => 'FORWARD',                          // 方向可以为 FORWARD 或者 BACKWARD
                'inclusive_start_primary_key' => $startPK,         // 开始主键
                'exclusive_end_primary_key' => $endPK,             // 结束主键
            );

            $response = self::getInstance()->getRange($request);

            foreach ($response['rows'] as $rowData) {
                $data[] = array(
                    'chapter_name' => $rowData['primary_key_columns']['chapter_name'],
                    'content' => $rowData['attribute_columns']['content']
                );
            }

            // 如果 next_start_primary_key 不为空，则代表
            // 范围内还有数据，需要继续读取
            $startPK = $response['next_start_primary_key'];
        }
        return $data;
    }

    public static function getChapterContent($book_name, $chapter_name) {
        $request = array(
            'table_name' => 'Chapters',
            'primary_key' => array(          // 主键
                'book_name' => $book_name,
                'chapter_name' => $chapter_name
            )
        );
        $ret = self::getInstance()->getRow($request);
        return array_merge($ret['row']['primary_key_columns'], $ret['row']['attribute_columns']);
    }

    public static function putBookName($book_name) {
        $request = array(
            'table_name' => 'Books',
            'condition' => 'IGNORE',         // condition可以为IGNORE, EXPECT_EXIST, EXPECT_NOT_EXIST
            'primary_key' => array(          // 主键
                'book_name' => $book_name
            ),
            'attribute_columns' => array(    // 属性
                'ctime' => time()
            )
        );

        self::getInstance()->putRow($request);
        return true;
    }

    public static function putChapter($book_name, $chapter_name, $content = array(), $last_chapter = array(), $previous_chapter = array(), $time) {
        $attr = array(
            'content' => $content,
            'last_chapter' => $last_chapter,
            'previous_chapter' => $previous_chapter,
            'ctime' => $time,
            'mtime' => time()
        );
        $attr = array_filter($attr);
        $request = array(
            'table_name' => 'Chapters',
            'condition' => 'IGNORE',         // condition可以为IGNORE, EXPECT_EXIST, EXPECT_NOT_EXIST
            'primary_key' => array(          // 主键
                'book_name' => $book_name,
                'chapter_name' => $chapter_name
            ),
            'attribute_columns_to_put' => $attr
        );
        self::getInstance()->updateRow($request);
        return true;
    }
}
