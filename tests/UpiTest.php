<?php


use ResoUpiTester\Upi;

class UpiTest extends PHPUnit_Framework_TestCase
{
    public function testBasicUPI()
    {
        $good_upis = [
            "US-04015-N -11022331-R-N",
            "US-42049-49888-1213666-R-N",
            "US-36061-N- 010237502R1-R-N",
            "US-36061-N-010237502R1-S-113",
            "US-06075-N-40010333-T-10",
            "US-13051-N-1122444-R-N",
            "US-36061-N-0122213-S-118",
            "US-04019-N-12401001H-B-65A",
            "US-123331-N-N-99798987-99",
        ];

        $bad_upis = [
            "US-123331-N-87-99",
            "XX-123331-N-N-99798987-99",
            "OIOASPODASDO APOSAPSCAS" ,
        ];

        foreach ($good_upis as $gu) {
            $upi = new Upi($gu);
            $this->assertTrue($upi->isValid());
        }

        foreach ($bad_upis as $bu) {
            $upi = new Upi($bu);
            $this->assertFalse($upi->isValid());
        }
    }
}
