<?php

require_once '../bootstrap.php';

use ToDate\Iterator;
use ToDate\Parser\FormalDateExpressionParser;

$germanHolidays = 'DayOfWeek = SAT,SUN OR DayAndMonth = 1/1 OR Date = Easter-2 OR Date = Easter+1 OR DayAndMonth = 1/5 OR Date = Easter+39 OR Date = Easter+50 OR Date = Easter+60 OR DayAndMonth = 3/10 OR DayAndMonth = 1/11 OR DayAndMonth = 25/12 OR DayAndMonth = 26/12';

//show all german holidays in 2012
$it = new Iterator\ConditionIterator(new Iterator\DayIterator('2012-01-01','2012-12-31'), $germanHolidays);
foreach ($it as $date) {
	echo $date->format('d.m.Y, l').PHP_EOL;
}





?>