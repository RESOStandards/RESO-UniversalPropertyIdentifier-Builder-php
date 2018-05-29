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
        $this->assertSame('01095', $this->helper->getFipsCode('AL', 'Marshall County'));
        $this->assertSame('42093', $this->helper->getFipsCode('PA', 'Montour County'));
    }

    public function testGetStateCode()
    {
        $this->assertSame('01', $this->helper->getStateCode('AL'));
        $this->assertSame('56', $this->helper->getStateCode('WY'));
    }

    public function testGetSubCountyCode()
    {
        $this->assertSame('90171', $this->helper->getSubCountyCode('AL', 'Autaugaville CCD'));
        $this->assertSame('91863', $this->helper->getSubCountyCode('ID', 'Kuna CCD'));
    }
}
