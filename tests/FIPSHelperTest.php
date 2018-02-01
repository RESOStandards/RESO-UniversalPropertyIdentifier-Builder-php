<?php


use ResoUpiTester\helpers\FIPSHelper;

class FIPSHelperTest extends PHPUnit_Framework_TestCase
{
    /** @var FIPSHelper $helper */
    public $helper;

    public function setUp()
    {
        $this->helper = new FIPSHelper();
    }

    public function testGetCode()
    {
        $this->assertSame('01095', $this->helper->getFipsCode('AL', 'Albertville'));
        $this->assertSame('42093', $this->helper->getFipsCode('PA', 'West Hemlock'));
    }

    public function testGetStateCode()
    {
        $this->assertSame('01', $this->helper->getStateCode('AL'));
        $this->assertSame('56', $this->helper->getStateCode('WY'));
    }
}
