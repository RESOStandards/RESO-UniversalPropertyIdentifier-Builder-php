<?php


namespace ResoUpiTester;


use Monolog\Handler\HandlerInterface;
use ResoUpiTester\helpers\FIPSHelper;

class UpiUS extends Upi
{
    public $FIPSHelper;
    public $state_abbreviation;
    public $state_code;
    public $county_name;

    public function __construct(bool $upi = false, HandlerInterface $log_handler = null)
    {
        $this->FIPSHelper = new FIPSHelper();
        $this->setCountryCode('US');

        parent::__construct($upi, $log_handler);
    }

    /**
     * @return string
     */
    public function getSubCountryCode(): string
    {
        if ($this->state_abbreviation and $this->county_name) {
            $this->sub_country_code = $this->FIPSHelper->getFipsCode($this->state_abbreviation, $this->county_name);
        }

        return $this->sub_country_code;
    }

    /**
     * @param string
     */
    public function setStateAbbreviation(string $state_abbreviation): void
    {
        $this->state_abbreviation = $state_abbreviation;
        $this->state_code = $this->FIPSHelper->getStateCode($this->state_abbreviation);
    }

    /**
     * @return string
     */
    public function getStateAbbreviation(): string
    {
        return $this->state_abbreviation;
    }

    /**
     * @return string
     */
    public function getStateCode(): string
    {
        return $this->state_code;
    }

    /**
     * @return mixed
     */
    public function getCountyName()
    {
        return $this->county_name;
    }

    /**
     * @param mixed $county_name
     */
    public function setCountyName($county_name): void
    {
        $this->county_name = $county_name;
        $this->getSubCountryCode();
    }

    /**
     * @return string
     */
    public function getSubCountyName(): string
    {
        return $this->sub_county_name;
    }

    /**
     * @param string $sub_county_name
     */
    public function setSubCountyName(string $sub_county_name): void
    {
        $this->sub_county_name = $sub_county_name;
        $sub_county_code = $this->FIPSHelper->getSubCountyCode($this->getStateAbbreviation(), $sub_county_name);

        if ($sub_county_code) {
            $this->setSubCountyCode($sub_county_code);
        } else {
            $this->setSubCountyCode('N');
        }
    }
}