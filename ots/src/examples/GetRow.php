<?php

require(__DIR__ . "/../../vendor/autoload.php");
require(__DIR__ . "/ExampleConfig.php");

use Aliyun\OTS\OTSClient as OTSClient;


$otsClient = new OTSClient(array(
    'EndPoint' => EXAMPLE_END_POINT,
    'AccessKeyID' => EXAMPLE_ACCESS_KEY_ID,
    'AccessKeySecret' => EXAMPLE_ACCESS_KEY_SECRET,
    'InstanceName' => EXAMPLE_INSTANCE_NAME,
));

$request = array(
    'table_name' => 'Books',
    'condition' => 'IGNORE',         // condition可以为IGNORE, EXPECT_EXIST, EXPECT_NOT_EXIST
    'primary_key' => array(          // 主键
        'book_name' => $book_name
    )
);

$response = $otsClient->putRow($request);


// $request = array(
//     'table_name' => 'MyTable',
//     'primary_key' => array(          // 主键
//         'PK0' => 123,
//         'PK1' => 'abc',
//     )
// );
// $response = $otsClient->getRow($request);
// print json_encode($response);

/* 样例输出：
{
    "consumed": {
        "capacity_unit": {
            "read": 1,                 // 本次操作消耗了1个读CU
            "write": 0
        }
    },
    "row": {
        "primary_key_columns": {
            "PK0": 123,
            "PK1": "abc"
        },
        "attribute_columns": {
            "attr0": 456,
            "attr1": "Hangzhou",
            "attr2": 3.14,
            "attr3": true,
            "attr4": false,
            "attr5": {                  // 请注意BINARY类型的表示方法
                "type": "BINARY",
                "value": "a binary string"
            }
        }
    }
}

*/

