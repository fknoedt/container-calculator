<?php

require '../src/ContainerCalculator.php';

?>
<!DOCTYPE html>
<html lang="en">
<body>
/**
<br/>
&nbsp;* <strong>Optimum Container Calculator</strong>
<br/>
&nbsp;* Calculate the optimum number of large and small containers to hold packages to be shipped | input: numbers of packages to ship and containers available | output: calculation results
<br/>
&nbsp;* @author <strong>Filipe Knoedt</strong> &lt;fknoedt@gmail.com&gt;
<br/>
*/
<br/>
<br/>
<?php
// random nubers to test
$randomNumberOfPackages = [];

for($i=0; $i <= rand(1,5); $i++) {

	$randomNumberOfPackages[] = rand(10, 200);

}

$randomSmallContainersAvailable = rand(10,100);
$randomLargeContainersAvailable = rand(10,100);

$calculator = new ContainerCalculator($randomSmallContainersAvailable, $randomLargeContainersAvailable);

echo count($randomNumberOfPackages) . ' test(s) to be made, starting with ' . $randomSmallContainersAvailable . ' small and ' . $randomLargeContainersAvailable . ' large containers available:<br/><br/>';

foreach($randomNumberOfPackages as $numberOfPackages) {

	echo "----<br/>Optimizing shipping for <strong>{$numberOfPackages} package(s)</strong> with {$calculator->largeContainersAvailable} large and {$calculator->smallContainersAvailable} small containers available...<br/>";

	$calculator->calculate($numberOfPackages);

	echo 'Results:<pre>';

	echo $calculator->getResults(true);

	echo '</pre>';

}

echo '</body></html>';