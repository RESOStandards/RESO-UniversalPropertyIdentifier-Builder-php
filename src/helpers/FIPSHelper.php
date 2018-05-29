<?php


namespace ResoUpiTester\helpers;


class FIPSHelper
{
    // format = [
    //   STATE ABBREV, STATE FIPS, COUNTY FIPS, COUNTY NAME, COUNTY SUB FIPS, COUNTY SUB NAME, FUNCSTAT
    // ]
    public $fips_codes = [];

    public function __construct()
    {
        $handle = fopen(__DIR__ . '/../data/fips_codes.csv', 'r');

        while (($row = fgetcsv($handle)) !== FALSE) {
            if (!array_key_exists($row[0], $this->fips_codes)) {
                $this->fips_codes[$row[0]] = [];
            }

            $this->fips_codes[$row[0]][] = [
                'state' => (string) $row[0],
                'state_code' => $row[1],
                'county_code' => $row[2],
                'fips_code' => $row[1] . $row[2],
                'name' => $row[3],
                'county_sub_fips' => $row[4],
                'county_sub_name' => $row[5],
            ];
        }

        fclose($handle);
    }

    public function getFipsCode(string $state_abbreviation, string $county) {
        $state_options = $this->fips_codes[$state_abbreviation];

        foreach ($state_options as $option) {
            if ($option['name'] == $county) {
                return $option['fips_code'];
            }
        }

        return null;
    }

    public function getStateCode($abbreviation)
    {
        $codes = array_keys($this->fips_codes);

        foreach ($codes as $code) {
            $data = $this->fips_codes[$code][0];

            if ($data['state'] == $abbreviation) {
                return $data['state_code'];
            }
        }

        return null;
    }

    public function getSubCountyCode(string $state_abbreviation, string $sub_county_name)
    {
        $state_options = $this->fips_codes[$state_abbreviation];

        foreach ($state_options as $option) {
            if ($option['county_sub_name'] == $sub_county_name) {
                return $option['county_sub_fips'];
            }
        }

        return null;
    }
}