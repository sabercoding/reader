<?php

namespace Aliyun\OTS\Tests;

use Aliyun\OTS;

require __DIR__ . "/../../../vendor/autoload.php";

SDKTestBase::cleanUp();
SDKTestBase::createInitialTable(
    array(
        "table_meta" => array(
            "table_name" => "myTable",
            "primary_key_schema" => array(
                "PK1" => "INTEGER",
                "PK2" => "STRING",
            )
        ),
        "reserved_throughput" => array(
            "capacity_unit" => array(
                "read" => 5000,
                "write" => 5000,
            )
        ),
    )
);

SDKTestBase::createInitialTable(
    array(
        "table_meta" => array(
            "table_name" => "myTablexx",
            "primary_key_schema" => array(
                "PK1" => "INTEGER"
            )
        ),
        "reserved_throughput" => array(
            "capacity_unit" => array(
                "read" => 5000,
                "write" => 5000,
            )
        ),
    )
);

SDKTestBase::createInitialTable(
    array(
        "table_meta" => array(
            "table_name" => "myTablexx2",
            "primary_key_schema" => array(
                "PK1" => "INTEGER"
            )
        ),
        "reserved_throughput" => array(
            "capacity_unit" => array(
                "read" => 5000,
                "write" => 5000,
            )
        ),
    )
);

SDKTestBase::createInitialTable(
    array(
        "table_meta" => array(
            "table_name" => "myTable1",
            "primary_key_schema" => array(
                "PK1" => "INTEGER",
                "PK2" => "STRING",
                "PK3" => "INTEGER",
                "PK4" => "STRING",
            )
        ),
        "reserved_throughput" => array(
            "capacity_unit" => array(
                "read" => 100,
                "write" => 100,
            )
        ),
    )
);

SDKTestBase::waitForTableReady();


class GetRangeTest extends SDKTestBase {


    /*     *
     * 
     * GetRangeForward
     * 先写入两行，PK分别为1和2，GetRange，方向为Forward，期望顺序得到1和2两行。
     */

    public function testGetRangeForward() {
        for ($i = 1; $i < 3; $i++) {
            $tablename = array(
                "table_name" => "myTable",
                "condition" => "IGNORE",
                "primary_key" => array("PK1" => $i, "PK2" => "a" . $i),
                "attribute_columns" => array("att1" => $i, "att2" => "att" . $i)
            );
            $this->otsClient->putRow($tablename);
        }

        $getRange = array(
            "table_name" => "myTable",
            "direction" => "FORWARD",
            "limit" => 10,
            "inclusive_start_primary_key" => array(
                "PK1" => 1,
                "PK2" => "a1"
            ),
            "exclusive_end_primary_key" => array(
                "PK1" => 3,
                "PK2" => "a3"
            ),
        );
        $rowone = array(
            "primary_key_columns" => array("PK1" => 1, "PK2" => "a1"),
            "attribute_columns" => array("att1" => 1, "att2" => "att1")
        );
        $rowtwo = array(
            "primary_key_columns" => array("PK1" => 2, "PK2" => "a2"),
            "attribute_columns" => array("att1" => 2, "att2" => "att2")
        );
        $tables = $this->otsClient->getRange($getRange);
        $this->assertEmpty($tables['next_start_primary_key']);
        $this->assertEquals($tables['rows'][0], $rowone);
        $this->assertEquals($tables['rows'][1], $rowtwo);
    }

    /*     *
     * 
     * GetRangeBackward
     * 先写入两行，PK分别为1和2，GetRange，方向为Backward，期望顺序得到2和1两行。
     */

    public function testGetRangeBackward() {
        for ($i = 1; $i < 3; $i++) {
            $tablename = array(
                "table_name" => "myTable",
                "condition" => "IGNORE",
                "primary_key" => array("PK1" => $i, "PK2" => "a" . $i),
                "attribute_columns" => array("att1" => $i, "att2" => "att" . $i)
            );
            $this->otsClient->putRow($tablename);
        }
        $getRange = array(
            "table_name" => "myTable",
            "direction" => "BACKWARD",
            "limit" => 10,
            "inclusive_start_primary_key" => array(
                "PK1" => 2,
                "PK2" => "a2"
            ),
            "exclusive_end_primary_key" => array(
                "PK1" => 0,
                "PK2" => "a0"
            ),
        );
        $rowone = array(
            "primary_key_columns" => array("PK1" => 1, "PK2" => "a1"),
            "attribute_columns" => array("att1" => 1, "att2" => "att1")
        );
        $rowtwo = array(
            "primary_key_columns" => array("PK1" => 2, "PK2" => "a2"),
            "attribute_columns" => array("att1" => 2, "att2" => "att2")
        );
        $tables = $this->otsClient->getRange($getRange);
        //print_r($tables);die;
        $this->assertEmpty($tables['next_start_primary_key']);
        $this->assertEquals($tables['rows'][0], $rowtwo);
        $this->assertEquals($tables['rows'][1], $rowone);
    }

    /*     *
     * 
     * InfMinInRange
     * 先写入2行，PK分别为1和2，GetRange，方向为Forward，范围为 [INF_MIN, 2) 或者 [1, INF_MIN)，期望顺序得到0和1两行，或者返回服务端错误
     */

    public function testInfMinInRange() {
        for ($i = 1; $i < 3; $i++) {
            $tablename = array(
                "table_name" => "myTable",
                "condition" => "IGNORE",
                "primary_key" => array("PK1" => $i, "PK2" => "a" . $i),
                "attribute_columns" => array("att1" => $i, "att2" => "att" . $i)
            );
            $this->otsClient->putRow($tablename);
        }
        $getRange = array(
            "table_name" => "myTable",
            "direction" => "FORWARD",
            "columns_to_get" => array(),
            "limit" => 10,
            "inclusive_start_primary_key" => array(
                "PK1" => array('type' => 'INF_MIN'),
                "PK2" => array('type' => 'INF_MIN'),
            ),
            "exclusive_end_primary_key" => array(
                "PK1" => 10,
                "PK2" => "a10"
            ),
        );
        $rowone = array(
            "primary_key_columns" => array("PK1" => 1, "PK2" => "a1"),
            "attribute_columns" => array("att1" => 1, "att2" => "att1")
        );
        $rowtwo = array(
            "primary_key_columns" => array("PK1" => 2, "PK2" => "a2"),
            "attribute_columns" => array("att1" => 2, "att2" => "att2")
        );
        $tables = $this->otsClient->getRange($getRange);
        //print_r($tables);die;
        $this->assertEmpty($tables['next_start_primary_key']);
        $this->assertEquals($tables['rows'][0], $rowone);
        $this->assertEquals($tables['rows'][1], $rowtwo);
    }

    /*     *
     * 
     * InfMinInRange
     * 先写入2行，PK分别为0, 1和2，GetRange，方向为Backward，范围为 [INF_MAX, 2) 或者 [1, INF_MAX)，顺序得到2和1两行
     */

    public function testInfMaxInRange() {
        for ($i = 1; $i < 3; $i++) {
            $tablename = array(
                "table_name" => "myTable",
                "condition" => "IGNORE",
                "primary_key" => array("PK1" => $i, "PK2" => "a" . $i),
                "attribute_columns" => array("att1" => $i, "att2" => "att" . $i)
            );
            $this->otsClient->putRow($tablename);
        }
        $getRange = array(
            "table_name" => "myTable",
            "direction" => "BACKWARD",
            "limit" => 10,
            "inclusive_start_primary_key" => array(
                "PK1" => array('type' => 'INF_MAX'),
                "PK2" => array('type' => 'INF_MAX')
            ),
            "exclusive_end_primary_key" => array(
                "PK1" => 0,
                "PK2" => "a0"
            ),
        );
        $rowone = array(
            "primary_key_columns" => array("PK1" => 1, "PK2" => "a1"),
            "attribute_columns" => array("att1" => 1, "att2" => "att1")
        );
        $rowtwo = array(
            "primary_key_columns" => array("PK1" => 2, "PK2" => "a2"),
            "attribute_columns" => array("att1" => 2, "att2" => "att2")
        );
        $tables = $this->otsClient->getRange($getRange);
        //print_r($tables);die;
        $this->assertEmpty($tables['next_start_primary_key']);
        $this->assertEquals($tables['rows'][0], $rowtwo);
        $this->assertEquals($tables['rows'][1], $rowone);
    }

    /*     *
     * 
     * GetRangeWithDefaultColumnsToGet
     * 先PutRow包含4个主键列，4个属性列，然后GetRange请求ColumnsToGet参数属性列，期望读出所有4个属性列。
     */

    public function testGetRangeWithDefaultColumnsToGet() {
        for ($i = 1; $i < 3; $i++) {
            $tablename = array(
                "table_name" => "myTable1",
                "condition" => "IGNORE",
                "primary_key" => array(
                    "PK1" => $i,
                    "PK2" => "a" . $i,
                    "PK3" => $i,
                    "PK4" => "b" . $i
                ),
                "attribute_columns" => array("att1" => $i, "att2" => "att" . $i, "att3" => $i, "att4" => "att" . $i)
            );
            $this->otsClient->putRow($tablename);
        }
        $getRange = array(
            "table_name" => "myTable1",
            "direction" => "BACKWARD",
            "columns_to_get" => array("att1", "att2", "att3", "att4"),
            "limit" => 10,
            "inclusive_start_primary_key" => array(
                "PK1" => array('type' => 'INF_MAX'),
                "PK2" => array('type' => 'INF_MAX'),
                "PK3" => array('type' => 'INF_MAX'),
                "PK4" => array('type' => 'INF_MAX')
            ),
            "exclusive_end_primary_key" => array(
                "PK1" => 0,
                "PK2" => "a0",
                "PK3" => 1,
                "PK4" => "a0",
            ),
        );
        $rowone = //            "primary_key_columns" => array("PK1" => 1, "PK2" => "a1"),
                array("att1" => 2, "att2" => "att2", "att3" => 2, "att4" => "att2");
        $rowtwo = //            "primary_key_columns" => array("PK1" => 2, "PK2" => "a2"),
                array("att1" => 1, "att2" => "att1", "att3" => 1, "att4" => "att1");
        $tables = $this->otsClient->getRange($getRange);
        // print_r($tables);die;
        $this->assertEmpty($tables['next_start_primary_key']);
        $this->assertEmpty($tables['rows'][0]['primary_key_columns']);
        $this->assertEmpty($tables['rows'][1]['primary_key_columns']);
        $this->assertEquals($tables['rows'][0]['attribute_columns'], $rowone);
        $this->assertEquals($tables['rows'][1]['attribute_columns'], $rowtwo);
    }

    /*     *
     * 
     * GetRangeWith0ColumsToGet
     * 先PutRow包含4个主键列，4个属性列，然后GetRange请求ColumnsToGet为空数组，期望读出所有数据。
     */

    public function testGetRangeWith0ColumsToGet() {
        for ($i = 1; $i < 3; $i++) {
            $tablename = array(
                "table_name" => "myTable1",
                "condition" => "IGNORE",
                "primary_key" => array(
                    "PK1" => $i,
                    "PK2" => "a" . $i,
                    "PK3" => $i,
                    "PK4" => "b" . $i
                ),
                "attribute_columns" => array("att1" => $i, "att2" => "att" . $i, "att3" => $i, "att4" => "att" . $i)
            );
            $this->otsClient->putRow($tablename);
        }
        $getRange = array(
            "table_name" => "myTable1",
            "direction" => "BACKWARD",
            "columns_to_get" => array(),
            "limit" => 10,
            "inclusive_start_primary_key" => array(
                "PK1" => array('type' => 'INF_MAX'),
                "PK2" => array('type' => 'INF_MAX'),
                "PK3" => array('type' => 'INF_MAX'),
                "PK4" => array('type' => 'INF_MAX')
            ),
            "exclusive_end_primary_key" => array(
                "PK1" => 0,
                "PK2" => "a0",
                "PK3" => 1,
                "PK4" => "a0",
            ),
        );
        $rowone = array(
            "primary_key_columns" => array("PK1" => 1, "PK2" => "a1", "PK3" => 1, "PK4" => "b1"),
            "attribute_columns" => array("att1" => 1, "att2" => "att1", "att3" => 1, "att4" => "att1")
        );
        $rowtwo = array(
            "primary_key_columns" => array("PK1" => 2, "PK2" => "a2", "PK3" => 2, "PK4" => "b2"),
            "attribute_columns" => array("att1" => 2, "att2" => "att2", "att3" => 2, "att4" => "att2")
        );
        $tables = $this->otsClient->getRange($getRange);
        $this->assertEmpty($tables['next_start_primary_key']);
        $this->assertEquals($tables['rows'][0], $rowtwo);
        $this->assertEquals($tables['rows'][1], $rowone);
    }

    /*     *
     * 
     * GetRangeWith4ColumnsToGet
     * 先PutRow包含4个主键列，4个属性列，然后GetRange请求ColumnsToGet包含其中2个主键列，2个属性列，期望返回数据包含参数中指定的列。
     */

    public function testGetRangeWith4ColumnsToGet() {
        for ($i = 1; $i < 3; $i++) {
            $tablename = array(
                "table_name" => "myTable1",
                "condition" => "IGNORE",
                "primary_key" => array(
                    "PK1" => $i,
                    "PK2" => "a" . $i,
                    "PK3" => $i,
                    "PK4" => "b" . $i
                ),
                "attribute_columns" => array("att1" => $i, "att2" => "att" . $i, "att3" => $i, "att4" => "att" . $i)
            );
            $this->otsClient->putRow($tablename);
        }
        $getRange = array(
            "table_name" => "myTable1",
            "direction" => "BACKWARD",
            "columns_to_get" => array("PK1", "PK2", "att1", "att2"),
            "limit" => 10,
            "inclusive_start_primary_key" => array(
                "PK1" => array('type' => 'INF_MAX'),
                "PK2" => array('type' => 'INF_MAX'),
                "PK3" => array('type' => 'INF_MAX'),
                "PK4" => array('type' => 'INF_MAX')
            ),
            "exclusive_end_primary_key" => array(
                "PK1" => 0,
                "PK2" => "a0",
                "PK3" => 1,
                "PK4" => "a0",
            ),
        );
        $rowone = array(
            "primary_key_columns" => array("PK1" => 1, "PK2" => "a1"),
            "attribute_columns" => array("att1" => 1, "att2" => "att1")
        );
        $rowtwo = array(
            "primary_key_columns" => array("PK1" => 2, "PK2" => "a2"),
            "attribute_columns" => array("att1" => 2, "att2" => "att2")
        );
        $tables = $this->otsClient->getRange($getRange);
        $this->assertEmpty($tables['next_start_primary_key']);
        $this->assertEquals($tables['rows'][0], $rowtwo);
        $this->assertEquals($tables['rows'][1], $rowone);
    }

    /*     *
     * 
     * GetRangeWith1000ColumnsToGet
     * GetRange请求ColumnsToGet包含1000个不重复的列名，期望返回服务端错误？
     */

    public function testGetRangeWith1000ColumnsToGet() {
        for ($i = 1; $i < 3; $i++) {
            $tablename = array(
                "table_name" => "myTable",
                "condition" => "IGNORE",
                "primary_key" => array("PK1" => $i, "PK2" => "a" . $i),
                "attribute_columns" => array("att1" => $i, "att2" => "att" . $i)
            );
            $this->otsClient->putRow($tablename);
        }
        for ($i = 0; $i < 1001; $i++) {
            $a[] = 'a' . $i;
        }
        $getRange = array(
            "table_name" => "myTable",
            "direction" => "FORWARD",
            "columns_to_get" => $a,
            "limit" => 10,
            "inclusive_start_primary_key" => array(
                "PK1" => 1,
                "PK2" => "a1"
            ),
            "exclusive_end_primary_key" => array(
                "PK1" => 3,
                "PK2" => "a3"
            ),
        );
        
        $this->otsClient->getRange($getRange);
    }

    /*     *
     * 
     * GetRangeWithDuplicateColumnsToGet
     * GetRange请求ColumnsToGet包含2个重复的列名，成功读取指定信息
     */

    public function testGetRangeWithDuplicateColumnsToGet() {
        for ($i = 1; $i < 3; $i++) {
            $tablename = array(
                "table_name" => "myTable",
                "condition" => "IGNORE",
                "primary_key" => array("PK1" => $i, "PK2" => "a" . $i),
                "attribute_columns" => array("att1" => $i, "att2" => "att" . $i)
            );
            $this->otsClient->putRow($tablename);
        }
        $getRange = array(
            "table_name" => "myTable",
            "direction" => "FORWARD",
            "columns_to_get" => array("att1", "att1"),
            "limit" => 10,
            "inclusive_start_primary_key" => array(
                "PK1" => 1,
                "PK2" => "a1"
            ),
            "exclusive_end_primary_key" => array(
                "PK1" => 3,
                "PK2" => "a3"
            ),
        );
        $tables = $this->otsClient->getRange($getRange);
        $this->assertEmpty($tables['next_start_primary_key']);
        $this->assertEmpty($tables['rows'][0]['primary_key_columns']);
        $this->assertEmpty($tables['rows'][1]['primary_key_columns']);
        $this->assertEquals($tables['rows'][0]['attribute_columns']['att1'], 1);
        $this->assertEquals($tables['rows'][1]['attribute_columns']['att1'], 2);
    }

    /*     *
     * 
     * GetRangeWithLimit10
     * 先写入20行，GetRange Limit=10，期望返回10行，并校验 NextPK。
     */

    public function testGetRangeWithLimit10() {
        for ($i = 1; $i < 21; $i++) {
            $tablename = array(
                "table_name" => "myTable",
                "condition" => "IGNORE",
                "primary_key" => array("PK1" => $i, "PK2" => "a" . $i),
                "attribute_columns" => array("att1" => $i)
            );
            $this->otsClient->putRow($tablename);
        }
        $getRange = array(
            "table_name" => "myTable",
            "direction" => "FORWARD",
            "columns_to_get" => array(),
            "limit" => 10,
            "inclusive_start_primary_key" => array(
                "PK1" => 1,
                "PK2" => "a1"
            ),
            "exclusive_end_primary_key" => array(
                "PK1" => array('type' => 'INF_MAX'),
                "PK2" => array('type' => 'INF_MAX')
            ),
        );
        $tables = $this->otsClient->getRange($getRange);
        for ($i = 1; $i < 11; $i++) {
            $row[] = array(
                "primary_key_columns" => array("PK1" => $i, "PK2" => "a" . $i),
                "attribute_columns" => array("att1" => $i)
            );
        }
        for ($i = 0; $i < count($tables['rows']); $i++) {
            $this->assertEquals($tables['rows'][$i], $row[$i]);
        }
        $primary = array("PK1" => 11, "PK2" => "a11");
        $this->assertEquals($tables['next_start_primary_key'], $primary);
        $this->assertEquals(count($tables['rows']), 10);
    }

    /*     *
     * 
     * GetRangeIteratorWith1Row
     * GetRangeIterator 返回1行的情况。
     */

    public function testGetRangeIteratorWith1Row() {
        for ($i = 1; $i < 2; $i++) {
            $tablename = array(
                "table_name" => "myTable",
                "condition" => "IGNORE",
                "primary_key" => array("PK1" => $i, "PK2" => "a" . $i),
                "attribute_columns" => array("att1" => $i)
            );
            $this->otsClient->putRow($tablename);
        }
        $getRange = array(
            "table_name" => "myTable",
            "direction" => "FORWARD",
            "columns_to_get" => array(),
            "limit" => 1,
            "inclusive_start_primary_key" => array(
                "PK1" => 1,
                "PK2" => "a1"
            ),
            "exclusive_end_primary_key" => array(
                "PK1" => array('type' => 'INF_MAX'),
                "PK2" => array('type' => 'INF_MAX')
            ),
        );
        $tables = $this->otsClient->getRange($getRange);
        $row = array(
            "primary_key_columns" => array("PK1" => 1, "PK2" => "a1"),
            "attribute_columns" => array("att1" => 1)
        );
        $this->assertEquals($tables['rows'][0], $row);
    }
   
    /*     *
     * TableNameOfZeroLength
     * 表名长度为0的情况，期望返回错误消息：Invalid table name: ''. 中包含的TableName与输入一致
     */

    public function testTableNameOfZeroLength() {

        $tablebody = array(
            "table_meta" => array(
                "table_name" => "",
                "primary_key_schema" => array(
                    "PK1" => "STRING",
                    "PK2" => "INTEGER",
                    "PK3" => "STRING",
                    "PK4" => "INTEGER"
                )
            ),
            "reserved_throughput" => array(
                "capacity_unit" => array(
                    "read" => 100,
                    "write" => 100,
                )
            ),
        );
        try {
            $this->otsClient->createTable($tablebody);
            $this->fail('An expected exception has not been raised.');
        } catch (\Aliyun\OTS\OTSServerException $exc) {
            $c = "Invalid table name: ''.";
            $this->assertEquals($c, $exc->getOTSErrorMessage());
        }
        
    }

    /*     *
     * TableNameWithUnicode
     * 表名包含Unicode，期望返回错误信息：Invalid table name: '{TableName}'. 中包含的TableName与输入一致
     */

    public function testTableNameWithUnicode() {
        $tablebody = array(
            "table_meta" => array(
                "table_name" => "testU+0053",
                "primary_key_schema" => array(
                    "PK1" => "STRING",
                    "PK2" => "INTEGER",
                    "PK3" => "STRING",
                    "PK4" => "INTEGER"
                )
            ),
            "reserved_throughput" => array(
                "capacity_unit" => array(
                    "read" => 100,
                    "write" => 100,
                )
            ),
        );
        try {
            $this->otsClient->createTable($tablebody);
            $this->fail('An expected exception has not been raised.');
        } catch (\Aliyun\OTS\OTSServerException $exc) {
            $c = "Invalid table name: '{$tablebody['table_meta']['table_name']}'.";
            $this->assertEquals($c, $exc->getOTSErrorMessage());
        }
        
    }
    
    /*     *
     * 
     * GetRangeIteratorWith5000Rows
     * GetRangeIterator 返回5000行的情况，这时正好不发生第二次GetRange。
     */

    public function testGetRangeIteratorWith5000Rows() {
        for ($i = 1; $i < 5001; $i++) {
            $tablename = array(
                "table_name" => "myTablexx",
                "condition" => "IGNORE",
                "primary_key" => array("PK1" => $i),
                "attribute_columns" => array()
            );
            $this->otsClient->putRow($tablename);
        }
        $getRange = array(
            "table_name" => "myTablexx",
            "direction" => "FORWARD",
            "columns_to_get" => array(),
            "limit" => 5000,
            "inclusive_start_primary_key" => array(
                "PK1" => 1,
            ),
            "exclusive_end_primary_key" => array(
                "PK1" => array('type' => 'INF_MAX'),
            ),
        );
        $tables = $this->otsClient->getRange($getRange);
        $this->assertEmpty($tables['next_start_primary_key']);
    }

    /*     *
     * 
     * GetRangeIteratorWith5001Rows
     * GetRangeIterator 返回5001行的情况，这时正好发生第二次GetRange。
     */

    public function testGetRangeIteratorWith5001Rows() {
        for ($i = 1; $i < 5002; $i++) {
            $tablename = array(
                "table_name" => "myTablexx",
                "condition" => "IGNORE",
                "primary_key" => array("PK1" => $i),
                "attribute_columns" => array()
            );
            $this->otsClient->putRow($tablename);
        }
        $getRange = array(
            "table_name" => "myTablexx",
            "direction" => "FORWARD",
            "columns_to_get" => array(),
            "limit" => 5000,
            "inclusive_start_primary_key" => array(
                "PK1" => 1
            ),
            "exclusive_end_primary_key" => array(
                "PK1" => array('type' => 'INF_MAX')
            ),
        );
        $tables = $this->otsClient->getRange($getRange);
        $this->assertNotEmpty($tables['next_start_primary_key']);
    }
    
    /*     *
     * 
     * GetRangeIteratorWith15001Rows
     * GetRangeIterator 返回15001行的情况，这时共发生4次GetRange。
     */

    public function testGetRangeIteratorWith15001Rows() {
        for ($i = 1; $i < 15001; $i++) {
            $tablename = array(
                "table_name" => "myTablexx",
                "condition" => "IGNORE",
                "primary_key" => array("PK1" => $i),
                "attribute_columns" => array()
            );
            $this->otsClient->putRow($tablename);
        }
        $getRange = array(
            "table_name" => "myTablexx",
            "direction" => "FORWARD",
            "columns_to_get" => array(),
            "limit" => 5000,
            "inclusive_start_primary_key" => array(
                "PK1" => 1,
            ),
            "exclusive_end_primary_key" => array(
                "PK1" => array('type' => 'INF_MAX')
            ),
        );
        $this->otsClient->getRange($getRange);
        $getRange1 = array(
            "table_name" => "myTablexx",
            "direction" => "FORWARD",
            "columns_to_get" => array(),
            "limit" => 5000,
            "inclusive_start_primary_key" => array(
                "PK1" => 5001
            ),
            "exclusive_end_primary_key" => array(
                "PK1" => array('type' => 'INF_MAX')
            ),
        );
        $this->otsClient->getRange($getRange1);
        $getRange2 = array(
            "table_name" => "myTablexx",
            "direction" => "FORWARD",
            "columns_to_get" => array(),
            "limit" => 5000,
            "inclusive_start_primary_key" => array(
                "PK1" => 10001
            ),
            "exclusive_end_primary_key" => array(
                "PK1" => array('type' => 'INF_MAX')
            ),
        );
        $this->otsClient->getRange($getRange2);
        $getRange3 = array(
            "table_name" => "myTablexx",
            "direction" => "FORWARD",
            "columns_to_get" => array(),
            "limit" => 5000,
            "inclusive_start_primary_key" => array(
                "PK1" => 15001
            ),
            "exclusive_end_primary_key" => array(
                "PK1" => array('type' => 'INF_MAX')
            ),
        );
        $tables = $this->otsClient->getRange($getRange3);
        $this->assertEmpty($tables['next_start_primary_key']);
    }

    /*     *
     * 
     * GetRangeWithDefaultLimit
     * 先写入10000行，GetRange Limit为默认，期望2次返回全部行。
     */
    public function testGetRangeWithDefaultLimit() {
        for ($i = 1; $i < 10001; $i++) {
            $tablename = array(
                "table_name" => "myTablexx2",
                "condition" => "IGNORE",
                "primary_key" => array("PK1" => $i),
                "attribute_columns" => array()
            );
            $this->otsClient->putRow($tablename);
        }
        $getRange = array(
            "table_name" => "myTablexx2",
            "direction" => "FORWARD",
            "columns_to_get" => array(),
            "limit" => 5000,
            "inclusive_start_primary_key" => array(
                "PK1" => 1,
            ),
            "exclusive_end_primary_key" => array(
                "PK1" => array('type' => 'INF_MAX')
            ),
        );
        $this->otsClient->getRange($getRange);
       // $primary = array("PK1" => 5001);
        //$this->assertEquals($tables['next_start_primary_key'], $primary);
        $getRange1 = array(
            "table_name" => "myTablexx2",
            "direction" => "FORWARD",
            "columns_to_get" => array(),
            "limit" => 5000,
            "inclusive_start_primary_key" => array(
                "PK1" => 5001
            ),
            "exclusive_end_primary_key" => array(
                "PK1" => array('type' => 'INF_MAX')
            ),
        );
        $tables = $this->otsClient->getRange($getRange1);
        $this->assertEmpty($tables['next_start_primary_key']);
    }
}

