<?php

namespace ResoUpiTester;

use League\ISO3166\Exception\OutOfBoundsException;
use League\ISO3166\ISO3166;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger;

/**
 * Class Upi
 * @package ResoUpiTester
 *
 * @property string upi
 * @property string country_name
 * @property string country_code
 * @property string sub_country_name
 * @property string sub_country_code
 * @property string sub_county_name
 * @property string sub_county_code
 * @property string property_id
 * @property string property_code
 * @property string sub_property_code
 * @property boolean is_valid
 *
 */
class Upi implements UpiInterface
{
    const DEFAULT_PROPERTY = 'N'; // default property
    const REAL_PROPERTY = 'R'; // 'normal' property
    const STOCK_PROPERTY = 'S'; // stock, co-op
    const TEMPORARY_PROPERTY = 'T'; // temporary or proposed (eg. bare land)
    const BUILDING_PROPERTY = 'B'; // property with multiple building on it


    //{COUNTRY}-{SUB-COUNTRY}-{SUB-COUNTY}-{LOCAL-ID}-{PROPTYPE}-{SUB-PROPERTY-ID}

    public $log;
    public $upi;
    public $country_name;
    public $country_code;
    public $sub_country_name;
    public $sub_country_code;
    public $sub_county_name;
    public $sub_county_code = 'N'; // defaults to N if no authoritative sub county code exists
    public $property_id; // APN or parcel / tax ID
    public $sub_property_type_code;
    public $sub_property_id = 'N'; // defaults to N if no unit # exists
    public $is_valid = null;

    public $sub_property_type_codes = [
        Upi::DEFAULT_PROPERTY,
        Upi::REAL_PROPERTY,
        Upi::STOCK_PROPERTY,
        Upi::TEMPORARY_PROPERTY,
        Upi::BUILDING_PROPERTY,
    ];

    /**
     * Upi constructor.
     * @param HandlerInterface $log_handler
     * @param bool $upi
     */
    public function __construct($upi = false, HandlerInterface $log_handler = null)
    {
        $this->log = new Logger('upi_logger');

        $this->log->pushHandler(new ErrorLogHandler());
        if ($log_handler) {
            $this->log->pushHandler($log_handler);
        }

        if ($upi) {
            $this->upi = $upi;
            $this->parseUpi();
        }
    }

    /**
     *
     * {COUNTRY}-{SUB-COUNTRY}-{SUB-COUNTY}-{LOCAL-ID}-{PROPTYPE}-{SUB-PROPERTY-ID}
     */
    public function parseUpi(): void
    {
        $parts = explode('-', $this->upi);

        if (count($parts) < 6) {
            $this->setIsValid(false);
            return;
        }

        $this->setCountryCode($parts[0]); // eg US
        $this->setSubCountryCode($parts[1]); // eg FIPS code or Int'l equivalent
        $this->setSubCountyCode($parts[2]); // N if not set by county
        $this->setPropertyId($parts[3]); // parcel ID / APN
        $this->setSubPropertyTypeCode($parts[4]); // R(n) (n being integer), S, T, B
        $this->setSubPropertyId($parts[5]); // N, (int) unit number, lot number, or building ID number

        $this->checkValid();

        // if we still haven't set is_valid to false, set it to true
        if (is_null($this->is_valid)) {
            $this->setIsValid(true);
        }
    }

    /**
     * @return string
     */
    public function toUPI(): string
    {
        // gather data
        $data = [
            $this->getCountryCode(),
            $this->getSubCountryCode(),
            $this->getSubCountyCode(),
            $this->getPropertyId(),
            $this->getSubPropertyTypeCode(),
            $this->getSubPropertyId(),
        ];

        $this->setUpi(
            implode('-', $data)
        );

        // parse the new UPI so we can have a check for validity
        $this->parseUpi();

        return $this->upi;
    }

    /**
     * @param string $upi
     */
    public function setUpi(string $upi): void
    {
        $this->upi = $upi;
    }

    /**
     * @param $country_name
     */
    public function setCountryName(string $country_name): void
    {
        try {
            $country = (new ISO3166)->name($country_name);
            $this->country_code = $country['alpha2'];
            $this->country_name = $country['name'];
        } catch (OutOfBoundsException $e) {
            $this->log->error("Failed to find country for country name {$country_name}");
            $this->setIsValid(false);
        }
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->country_code;
    }

    /**
     * @param $country_code
     */
    public function setCountryCode(string $country_code): void
    {
        try {
            $country = (new ISO3166)->alpha2($country_code);
            $this->country_name = $country['name'];
            $this->country_code = $country['alpha2'];
        } catch (OutOfBoundsException $e) {
            $this->log->error("Failed to find country for country code {$country_code}");
            $this->setIsValid(false);
        }
    }

    /**
     * @param $sub_country_name
     */
    public function setSubCountryName(string $sub_country_name): void
    {
        $this->sub_country_name = $sub_country_name;
    }

    /**
     * @return string
     */
    public function getSubCountryCode(): string
    {
        return $this->sub_country_code;
    }

    /**
     * @param $sub_country_code
     */
    public function setSubCountryCode(string $sub_country_code): void
    {
        $this->sub_country_code = $sub_country_code;
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
    }

    /**
     * @return string
     */
    public function getSubCountyCode(): string
    {
        return $this->sub_county_code;
    }

    /**
     * @param string $sub_county_code
     */
    public function setSubCountyCode(string $sub_county_code): void
    {
        $this->sub_county_code = $sub_county_code;
    }

    /**
     * @return string
     */
    public function getPropertyId(): string
    {
        return $this->property_id;
    }

    /**
     * @param string $property_id
     */
    public function setPropertyId(string $property_id): void
    {
        $this->property_id = $property_id;
    }

    /**
     * @return string
     */
    public function getSubPropertyTypeCode(): string
    {
        return $this->property_code;
    }

    /**
     * @param $sub_property_type_code
     */
    public function setSubPropertyTypeCode(string $sub_property_type_code): void
    {
        $this->property_code = $sub_property_type_code;
    }

    /**
     * @return mixed
     */
    public function getSubPropertyId(): string
    {
        return $this->sub_property_code;
    }

    /**
     * @param $sub_property_id
     */
    public function setSubPropertyId($sub_property_id): void
    {
        $this->sub_property_code = $sub_property_id;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->is_valid;
    }

    /**
     * @param $is_valid
     */
    public function setIsValid(bool $is_valid): void
    {
        $this->is_valid = $is_valid;
    }

    private function checkValid()
    {
        // check the type
        if (!in_array($this->property_code, $this->sub_property_type_codes)) {
            $this->setIsValid(false);
        };
    }
}