<?php

namespace Aliyun\OTS\Tests;
use Aliyun\OTS;

require __DIR__ . "/../../../vendor/autoload.php";


class listTableTest extends SDKTestBase
{
    public function setup()
    {
        $this->cleanUp();
    }

    /* *
     * ListTableWith0Table
     * 在没有表的情况下 ListTable，期望返回0个Table Name
     */
    public function testListTableWith0Table()
    {
        
        $this->assertEmpty($this->otsClient->listTable(array()));
    }
    /* *
     * ListTableWith1Table
     * 在有1个表的情况下 ListTable，期望返回1个Table Name。
     */
    public function testListTableWith1Table()
    {
        
        $tablebody = array(
            "table_meta" => array(
                "table_name" => "myTable",
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
        $this->otsClient->CreateTable($tablebody);
        $table_name = array("myTable");
        $this->assertEquals($this->otsClient->listTable(array()),$table_name);
    }
    
    /* *
     * ListTableWith2Tables
     * 在有2个表的情况下 ListTable，期望返回2个Table Name。
     */
    public function testListTableWith2Tables()
    {
        $tablebody = array(
            "table_meta" => array(
                "table_name" => "myTable",
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
        $tablebody1 = array(
            "table_meta" => array(
                "table_name" => "myTable1",
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
        $this->otsClient->CreateTable($tablebody);
        $this->otsClient->CreateTable($tablebody1);
        $table_name = array("myTable","myTable1");
        $this->assertEquals($this->otsClient->listTable(array()),$table_name);
    }

    public function testListTable40Times()
    {
        for ($i = 0; $i < 40; $i ++) {
            $this->otsClient->listTable(array());
        }
    }
    
}

