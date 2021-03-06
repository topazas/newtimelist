<?php

/**
 * Class EmployeeTest
 */
class EmployeeTest extends CDbTestCase
{
    public $fixtures = array(
        'employee' => 'Employee'
    );

    /**
     * test table name
     */
    public function testTableName()
    {
        $employee = new Employee;
        $this->assertEquals('employee', $employee->tableName());
    }

    /**
     *
     */
    public function testRules()
    {
        $employee = new Employee;
        $this->assertGreaterThan(0, count($employee->rules()));
    }

    /**
     *
     */
    public function testAttributeLabels()
    {
        $employee = new Employee;
        $this->assertGreaterThan(0, count($employee->attributeLabels()));
    }

    /**
     *
     */
    public function testSearch()
    {
        $employee = new Employee;
        $this->assertInstanceOf('CActiveDataProvider', $employee->search());
    }


}