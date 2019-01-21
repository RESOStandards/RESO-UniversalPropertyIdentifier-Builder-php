# ResoUpiTester_PHP
PHP Test tool for RESO UPI format

Create new UPI:

```
$upi = new \ResoUpiTester\UpiUS();
$upi->setStateAbbreviation('AL');
$upi->setCountyName('Autauga County');
$upi->setSubCountyName('Autaugaville CCD');
$upi->setPropertyId('123456');
$upi->setSubPropertyTypeCode('R');
$upi->setSubPropertyId('N');

$r = $upi->toUPI();

echo $r; // US-01001-90171-123456-R-N
```

Test UPI Format: 

```
$upi = new Upi("US-04015-N-11022331-R-N");
echo $upi->isValid(); // true
```