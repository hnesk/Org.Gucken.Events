<?php

namespace ToDate\Condition;

require_once TODATE_BASEDIR . '/ToDate/Condition/DateCondition.php';

/**
 * Test class for DateCondition.
 * Generated by PHPUnit on 2012-02-07 at 22:33:24.
 */
class DateConditionTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var DateCondition
	 */
	protected $condition;

	protected function setUp() {
		$this->condition = new DateCondition(new \DateTime('2012-01-01 12:34'));
	}


	/**
	 * @covers ToDate\Condition\DateCondition::__construct
	 * @covers ToDate\Condition\DateCondition::contains
	 * @covers ToDate\Condition\AbstractDateCondition::normalizeDate
	 */
	public function testContainsReturnsTrueForSameDayAndDifferentTime() {
		self::assertTrue($this->condition->contains(new \DateTime('2012-01-01 23:45')));
	}

	/**
	 * @covers ToDate\Condition\DateCondition::__construct
	 * @covers ToDate\Condition\DateCondition::contains
	 * @covers ToDate\Condition\AbstractDateCondition::normalizeDate
	 */
	public function testContainsReturnsTrueForSameDayAndTime() {
		self::assertTrue($this->condition->contains(new \DateTime('2012-01-01 12:34')));
	}

	/**
	 * @covers ToDate\Condition\AbstractDateCondition::__invoke
	 */
	public function testContainsCanBeCalledViaInvoke() {
		$c = $this->condition;
		self::assertTrue($c(new \DateTime('2012-01-01 12:34')));
	}

		

	/**
	 * @covers ToDate\Condition\DateCondition::__construct
	 * @covers ToDate\Condition\DateCondition::contains
	 */
	public function testContainsReturnsFalseForOtherDayAndTime() {
		self::assertFalse($this->condition->contains(new \DateTime('2012-01-02 12:34')));
	}
	
	
	/**
	 * @covers ToDate\Condition\DateCondition::__toString
	 */
	public function testToString() {
		self::assertEquals('Date = 2012-01-01', (string)$this->condition);
	}

}

?>