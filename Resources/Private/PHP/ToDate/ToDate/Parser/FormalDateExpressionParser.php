<?php

namespace ToDate\Parser;

use ToDate\Condition;

class FormalDateExpressionParser extends Generated\FormalDateExpressionParser {
	protected $lastResult = array();
	protected static $unitLookup = array(
		'D' => 1,
		'W' => 7,
		'M' => 30,
		'Y' => 365
	);
	
	/*
	 * Functions for DayOfWeekOfMonthExpression
	 */
	protected function Expression_DayOfWeekOfMonthExpression(&$self, $sub) {
		$self['Expression'] = new Condition\DayOfWeekOfMonthCondition($sub['WeekList'], $sub['DayOfWeek']);
	}

	protected function DayOfWeekOfMonthExpression_WeekList(&$self, $sub) {
		$self['WeekList'] = self::createList($sub);
	}
	
	protected function DayOfWeekOfMonthExpression_DayOfWeek(&$self, $sub) {
		$self['DayOfWeek'] = $sub['text'];
	}
	

	/*
	 * Functions for DayOfWeekExpression
	 */
	protected function Expression_DayOfWeekExpression(&$self, $sub) {
		$self['Expression'] = new Condition\DayOfWeekCondition($sub['DayOfWeekList']['text']);
	}


	/*
	 * Functions for DateExpression
	 */
	protected function Expression_DateExpression(&$self, $sub) {
		if (isset($sub['Date'])) {
			$self['Expression'] = new Condition\DateCondition($sub['Date']);
		} else if (isset($sub['EasterOffset'])) {
			$self['Expression'] = new Condition\EasterBasedCondition($sub['EasterOffset']);
		}
	}
	
	protected function DateExpression_Date(&$self, $sub) {
		$self['Date'] = self::createDate($sub);
	}

	protected function DateExpression_Easter(&$self, $sub) {
		$self['EasterOffset'] = $sub['Offset']['Number'];
	}

	
	/*
	 * Functions for DateOffsetExpression
	 */
	protected function Expression_DateModuloExpression(&$self, $sub) {
		$self['Expression'] =  new Condition\DateModuloOffsetCondition($sub['Date'], $sub['Offset']);
	}
	
	protected function DateModuloExpression_Date(&$self, $sub) {
		$self['Date'] = self::createDate($sub);
	}
	
	protected function DateModuloExpression_Offset(&$self, $sub) {
		$unit = isset($sub['Unit']) ? $sub['Unit'] : 'D';
		$self['Offset'] = $sub['Number'] * self::$unitLookup[$unit];

	}
	
	protected function Offset_Unit(&$self, $sub) {
		$self['Unit'] = strtoupper(substr($sub['text'],0,1));
	}
	
	protected function Offset_Number(&$self, $sub) {
		$self['Number'] = intval($sub['text']);
	}

	
	/*
	 * Functions for DayOfMonthExpression
	 */
	protected function Expression_DayAndMonthExpression(&$self, $sub) {
		$self['Expression'] = new Condition\DayAndMonthCondition($sub['Day']['text'], $sub['Month']['text']);
	}
	
	/*
	 * Functions for DayOfMonthExpression
	 */
	protected function Expression_DayOfMonthExpression(&$self, $sub) {
		$self['Expression'] = new Condition\DayOfMonthCondition($sub['DayList']['text']);
	}

	
	/*
	 * Functions for FeatureExpression
	 */
	protected function Expression_FeatureExpression(&$self, $sub) {
		$self['Expression'] = new Condition\FeatureInSetCondition(trim($sub['Feature']['text']), $sub['TokenList']['text']);
	}

	
	/*
	 * Functions for MonthExpression
	 */
	protected function Expression_MonthExpression(&$self, $sub) {
		$self['Expression'] = new Condition\MonthCondition($sub['MonthList']['text']);
	}	

	/*
	 * Functions for YearExpression
	 */
	protected function Expression_YearExpression(&$self, $sub) {
		$self['Expression'] = new Condition\YearCondition($sub['YearList']['text']);
	}	
	
	protected function Expression_Result(&$self, $sub) {
		$self['Expression'] = $sub['Expression'];
	}

	protected function Expression_NotExpression(&$self, $sub) {
		$self['Expression'] = new Condition\NotCondition($sub['operand']['Expression']);
	}

	
	protected function Result_Expression( &$result, $sub ) {
		$result['Expression'] = $sub['Expression'] ;
	}
	

	protected function Result_OrExpression( &$result, $sub ) {
		$result['Expression'] = new Condition\UnionCondition($result['Expression'], $sub['operand']['Expression']);
	}
	
	protected function Result_AndExpression( &$result, $sub ) {
		$result['Expression'] = new Condition\IntersectionCondition($result['Expression'], $sub['operand']['Expression']);
	}

	
	/* 
	 * Helper functions
	 */
	protected static function createDate($sub) {
		$date = new \DateTime();
		$date->setDate($sub['Year']['text'],$sub['Month']['text'],$sub['Day']['text']);
		$date->setTime(0,0,0);
		return $date;
	}
	
	protected static function createList($sub) {
		return array_map('trim',explode(',',$sub['text']));
	}

	/**
	 *
	 * @return Condition\DateConditionInterface
	 */
	public function parse() {
		$result = $this->match_Result();
		$this->lastResult = $result;
		return $result['Expression'];
	}
	
	public function getResult() {
		return $this->lastResult;
	}
	
}

?>
