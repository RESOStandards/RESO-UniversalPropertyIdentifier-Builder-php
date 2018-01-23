<?php


namespace ResoUpiTester;


interface UpiInterface
{
    public function parseUpi() : void;
    public function toUPI() : string;
    public function setUpi(string $upi) : void;
    public function setCountryName(string $country_name) : void;
    public function getCountryCode() : string;
    public function setCountryCode(string $country_code) : void;
    public function setSubCountryName(string $sub_country_name) : void;
    public function getSubCountryCode() : string;
    public function setSubCountryCode(string $sub_county_code) : void;
    public function getSubCountyCode() : string;
    public function setSubCountyCode(string $sub_county_code) : void;
    public function getPropertyId() : string;
    public function setPropertyId(string $property_id) : void;
    public function getPropertyCode() : string;
    public function setPropertyCode(string $property_code) : void;
    public function getSubPropertyCode() : mixed;
    public function setSubPropertyCode($sub_property_code) : void;
    public function isValid() : bool;
    public function setIsValid(bool $is_valid) : void;
}