<?php


namespace ResoUpiTester\helpers;


class FIPSHelper
{
    // format = [
    //   State Abbreviation, State FIPS Code, County FIPS Code, FIPS Entity Code, ANSI Code, GU Name, Entity Description
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
                'name' => $row[5],
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
}