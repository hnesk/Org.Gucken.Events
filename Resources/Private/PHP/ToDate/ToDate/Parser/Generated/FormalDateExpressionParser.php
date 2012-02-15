<?php
namespace ToDate\Parser\Generated;
/*
(Month = 3-10) AND DayOfWeekOfMonth = -1SAT  
DayOfWeekOfMonth = 1,3FRI
DateOffset = 2012-04-20 % 2 Weeks
*/
/**
 *  FormalDateExpressionParser
 * 
 *  21 : DayOfMonthExpression
 *  FRI : Day of week  
 *  1,3/FRI : DayOfWeekOfMonthExpression
 *  2012-04-02 : DateExpression
 *  2012-04-02%14: DateOffsetExpression
 *  12/31 : Day and Month
 *  Easter
 *  Easter+1
 * 
 *  AND 
 *  OR 
 *  NOT 
 */

require_once __DIR__.'/../../../lib/php-peg/Parser.php';
/**
 * Description of DateExpression
 *
 * @author jk
 */
class FormalDateExpressionParser extends \Parser {
/* Token: /[\d\w-]+/ */
protected $match_Token_typestack = array('Token');
function match_Token ($stack = array()) {
	$matchrule = "Token"; $result = $this->construct($matchrule, $matchrule, null);
	if (( $subres = $this->rx( '/[\d\w-]+/' ) ) !== FALSE) {
		$result["text"] .= $subres;
		return $this->finalise($result);
	}
	else { return FALSE; }
}


/* TokenList: Token (','|'-' Token)* */
protected $match_TokenList_typestack = array('TokenList');
function match_TokenList ($stack = array()) {
	$matchrule = "TokenList"; $result = $this->construct($matchrule, $matchrule, null);
	$_12 = NULL;
	do {
		$matcher = 'match_'.'Token'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_12 = FALSE; break; }
		while (true) {
			$res_11 = $result;
			$pos_11 = $this->pos;
			$_10 = NULL;
			do {
				$_8 = NULL;
				do {
					$res_2 = $result;
					$pos_2 = $this->pos;
					if (substr($this->string,$this->pos,1) == ',') {
						$this->pos += 1;
						$result["text"] .= ',';
						$_8 = TRUE; break;
					}
					$result = $res_2;
					$this->pos = $pos_2;
					$_6 = NULL;
					do {
						if (substr($this->string,$this->pos,1) == '-') {
							$this->pos += 1;
							$result["text"] .= '-';
						}
						else { $_6 = FALSE; break; }
						$matcher = 'match_'.'Token'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
						}
						else { $_6 = FALSE; break; }
						$_6 = TRUE; break;
					}
					while(0);
					if( $_6 === TRUE ) { $_8 = TRUE; break; }
					$result = $res_2;
					$this->pos = $pos_2;
					$_8 = FALSE; break;
				}
				while(0);
				if( $_8 === FALSE) { $_10 = FALSE; break; }
				$_10 = TRUE; break;
			}
			while(0);
			if( $_10 === FALSE) {
				$result = $res_11;
				$this->pos = $pos_11;
				unset( $res_11 );
				unset( $pos_11 );
				break;
			}
		}
		$_12 = TRUE; break;
	}
	while(0);
	if( $_12 === TRUE ) { return $this->finalise($result); }
	if( $_12 === FALSE) { return FALSE; }
}


/* Feature: /[dDjlNSwzWFmMntLoYyeIOPTZ]+/ */
protected $match_Feature_typestack = array('Feature');
function match_Feature ($stack = array()) {
	$matchrule = "Feature"; $result = $this->construct($matchrule, $matchrule, null);
	if (( $subres = $this->rx( '/[dDjlNSwzWFmMntLoYyeIOPTZ]+/' ) ) !== FALSE) {
		$result["text"] .= $subres;
		return $this->finalise($result);
	}
	else { return FALSE; }
}


/* Number: /[-+]?\d+/ */
protected $match_Number_typestack = array('Number');
function match_Number ($stack = array()) {
	$matchrule = "Number"; $result = $this->construct($matchrule, $matchrule, null);
	if (( $subres = $this->rx( '/[-+]?\d+/' ) ) !== FALSE) {
		$result["text"] .= $subres;
		return $this->finalise($result);
	}
	else { return FALSE; }
}


/* Unit: /days?|weeks?|months?|years?|d|w|m|y/i */
protected $match_Unit_typestack = array('Unit');
function match_Unit ($stack = array()) {
	$matchrule = "Unit"; $result = $this->construct($matchrule, $matchrule, null);
	if (( $subres = $this->rx( '/days?|weeks?|months?|years?|d|w|m|y/i' ) ) !== FALSE) {
		$result["text"] .= $subres;
		return $this->finalise($result);
	}
	else { return FALSE; }
}


/* Offset: Number > Unit? */
protected $match_Offset_typestack = array('Offset');
function match_Offset ($stack = array()) {
	$matchrule = "Offset"; $result = $this->construct($matchrule, $matchrule, null);
	$_20 = NULL;
	do {
		$matcher = 'match_'.'Number'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_20 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$res_19 = $result;
		$pos_19 = $this->pos;
		$matcher = 'match_'.'Unit'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else {
			$result = $res_19;
			$this->pos = $pos_19;
			unset( $res_19 );
			unset( $pos_19 );
		}
		$_20 = TRUE; break;
	}
	while(0);
	if( $_20 === TRUE ) { return $this->finalise($result); }
	if( $_20 === FALSE) { return FALSE; }
}


/* WeekNumber: /-?[1-5]/ */
protected $match_WeekNumber_typestack = array('WeekNumber');
function match_WeekNumber ($stack = array()) {
	$matchrule = "WeekNumber"; $result = $this->construct($matchrule, $matchrule, null);
	if (( $subres = $this->rx( '/-?[1-5]/' ) ) !== FALSE) {
		$result["text"] .= $subres;
		return $this->finalise($result);
	}
	else { return FALSE; }
}


/* WeekList: WeekNumber (',' WeekNumber)* */
protected $match_WeekList_typestack = array('WeekList');
function match_WeekList ($stack = array()) {
	$matchrule = "WeekList"; $result = $this->construct($matchrule, $matchrule, null);
	$_28 = NULL;
	do {
		$matcher = 'match_'.'WeekNumber'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_28 = FALSE; break; }
		while (true) {
			$res_27 = $result;
			$pos_27 = $this->pos;
			$_26 = NULL;
			do {
				if (substr($this->string,$this->pos,1) == ',') {
					$this->pos += 1;
					$result["text"] .= ',';
				}
				else { $_26 = FALSE; break; }
				$matcher = 'match_'.'WeekNumber'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_26 = FALSE; break; }
				$_26 = TRUE; break;
			}
			while(0);
			if( $_26 === FALSE) {
				$result = $res_27;
				$this->pos = $pos_27;
				unset( $res_27 );
				unset( $pos_27 );
				break;
			}
		}
		$_28 = TRUE; break;
	}
	while(0);
	if( $_28 === TRUE ) { return $this->finalise($result); }
	if( $_28 === FALSE) { return FALSE; }
}


/* DayOfWeek: 'MON'|'TUE'|'WED'|'THU'|'FRI'|'SAT'|'SUN' */
protected $match_DayOfWeek_typestack = array('DayOfWeek');
function match_DayOfWeek ($stack = array()) {
	$matchrule = "DayOfWeek"; $result = $this->construct($matchrule, $matchrule, null);
	$_53 = NULL;
	do {
		$res_30 = $result;
		$pos_30 = $this->pos;
		if (( $subres = $this->literal( 'MON' ) ) !== FALSE) {
			$result["text"] .= $subres;
			$_53 = TRUE; break;
		}
		$result = $res_30;
		$this->pos = $pos_30;
		$_51 = NULL;
		do {
			$res_32 = $result;
			$pos_32 = $this->pos;
			if (( $subres = $this->literal( 'TUE' ) ) !== FALSE) {
				$result["text"] .= $subres;
				$_51 = TRUE; break;
			}
			$result = $res_32;
			$this->pos = $pos_32;
			$_49 = NULL;
			do {
				$res_34 = $result;
				$pos_34 = $this->pos;
				if (( $subres = $this->literal( 'WED' ) ) !== FALSE) {
					$result["text"] .= $subres;
					$_49 = TRUE; break;
				}
				$result = $res_34;
				$this->pos = $pos_34;
				$_47 = NULL;
				do {
					$res_36 = $result;
					$pos_36 = $this->pos;
					if (( $subres = $this->literal( 'THU' ) ) !== FALSE) {
						$result["text"] .= $subres;
						$_47 = TRUE; break;
					}
					$result = $res_36;
					$this->pos = $pos_36;
					$_45 = NULL;
					do {
						$res_38 = $result;
						$pos_38 = $this->pos;
						if (( $subres = $this->literal( 'FRI' ) ) !== FALSE) {
							$result["text"] .= $subres;
							$_45 = TRUE; break;
						}
						$result = $res_38;
						$this->pos = $pos_38;
						$_43 = NULL;
						do {
							$res_40 = $result;
							$pos_40 = $this->pos;
							if (( $subres = $this->literal( 'SAT' ) ) !== FALSE) {
								$result["text"] .= $subres;
								$_43 = TRUE; break;
							}
							$result = $res_40;
							$this->pos = $pos_40;
							if (( $subres = $this->literal( 'SUN' ) ) !== FALSE) {
								$result["text"] .= $subres;
								$_43 = TRUE; break;
							}
							$result = $res_40;
							$this->pos = $pos_40;
							$_43 = FALSE; break;
						}
						while(0);
						if( $_43 === TRUE ) { $_45 = TRUE; break; }
						$result = $res_38;
						$this->pos = $pos_38;
						$_45 = FALSE; break;
					}
					while(0);
					if( $_45 === TRUE ) { $_47 = TRUE; break; }
					$result = $res_36;
					$this->pos = $pos_36;
					$_47 = FALSE; break;
				}
				while(0);
				if( $_47 === TRUE ) { $_49 = TRUE; break; }
				$result = $res_34;
				$this->pos = $pos_34;
				$_49 = FALSE; break;
			}
			while(0);
			if( $_49 === TRUE ) { $_51 = TRUE; break; }
			$result = $res_32;
			$this->pos = $pos_32;
			$_51 = FALSE; break;
		}
		while(0);
		if( $_51 === TRUE ) { $_53 = TRUE; break; }
		$result = $res_30;
		$this->pos = $pos_30;
		$_53 = FALSE; break;
	}
	while(0);
	if( $_53 === TRUE ) { return $this->finalise($result); }
	if( $_53 === FALSE) { return FALSE; }
}


/* DayOfWeekList: DayOfWeek (',' DayOfWeek)* */
protected $match_DayOfWeekList_typestack = array('DayOfWeekList');
function match_DayOfWeekList ($stack = array()) {
	$matchrule = "DayOfWeekList"; $result = $this->construct($matchrule, $matchrule, null);
	$_60 = NULL;
	do {
		$matcher = 'match_'.'DayOfWeek'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_60 = FALSE; break; }
		while (true) {
			$res_59 = $result;
			$pos_59 = $this->pos;
			$_58 = NULL;
			do {
				if (substr($this->string,$this->pos,1) == ',') {
					$this->pos += 1;
					$result["text"] .= ',';
				}
				else { $_58 = FALSE; break; }
				$matcher = 'match_'.'DayOfWeek'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_58 = FALSE; break; }
				$_58 = TRUE; break;
			}
			while(0);
			if( $_58 === FALSE) {
				$result = $res_59;
				$this->pos = $pos_59;
				unset( $res_59 );
				unset( $pos_59 );
				break;
			}
		}
		$_60 = TRUE; break;
	}
	while(0);
	if( $_60 === TRUE ) { return $this->finalise($result); }
	if( $_60 === FALSE) { return FALSE; }
}


/* Day: /3[01]|[12][0-9]|0?[1-9]/ */
protected $match_Day_typestack = array('Day');
function match_Day ($stack = array()) {
	$matchrule = "Day"; $result = $this->construct($matchrule, $matchrule, null);
	if (( $subres = $this->rx( '/3[01]|[12][0-9]|0?[1-9]/' ) ) !== FALSE) {
		$result["text"] .= $subres;
		return $this->finalise($result);
	}
	else { return FALSE; }
}


/* DayList: Day ((','|'-') Day)* */
protected $match_DayList_typestack = array('DayList');
function match_DayList ($stack = array()) {
	$matchrule = "DayList"; $result = $this->construct($matchrule, $matchrule, null);
	$_74 = NULL;
	do {
		$matcher = 'match_'.'Day'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_74 = FALSE; break; }
		while (true) {
			$res_73 = $result;
			$pos_73 = $this->pos;
			$_72 = NULL;
			do {
				$_69 = NULL;
				do {
					$_67 = NULL;
					do {
						$res_64 = $result;
						$pos_64 = $this->pos;
						if (substr($this->string,$this->pos,1) == ',') {
							$this->pos += 1;
							$result["text"] .= ',';
							$_67 = TRUE; break;
						}
						$result = $res_64;
						$this->pos = $pos_64;
						if (substr($this->string,$this->pos,1) == '-') {
							$this->pos += 1;
							$result["text"] .= '-';
							$_67 = TRUE; break;
						}
						$result = $res_64;
						$this->pos = $pos_64;
						$_67 = FALSE; break;
					}
					while(0);
					if( $_67 === FALSE) { $_69 = FALSE; break; }
					$_69 = TRUE; break;
				}
				while(0);
				if( $_69 === FALSE) { $_72 = FALSE; break; }
				$matcher = 'match_'.'Day'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_72 = FALSE; break; }
				$_72 = TRUE; break;
			}
			while(0);
			if( $_72 === FALSE) {
				$result = $res_73;
				$this->pos = $pos_73;
				unset( $res_73 );
				unset( $pos_73 );
				break;
			}
		}
		$_74 = TRUE; break;
	}
	while(0);
	if( $_74 === TRUE ) { return $this->finalise($result); }
	if( $_74 === FALSE) { return FALSE; }
}


/* Month:/1[0-2]|0?[0-9]/ */
protected $match_Month_typestack = array('Month');
function match_Month ($stack = array()) {
	$matchrule = "Month"; $result = $this->construct($matchrule, $matchrule, null);
	if (( $subres = $this->rx( '/1[0-2]|0?[0-9]/' ) ) !== FALSE) {
		$result["text"] .= $subres;
		return $this->finalise($result);
	}
	else { return FALSE; }
}


/* MonthList: Month ((','|'-') Month)* */
protected $match_MonthList_typestack = array('MonthList');
function match_MonthList ($stack = array()) {
	$matchrule = "MonthList"; $result = $this->construct($matchrule, $matchrule, null);
	$_88 = NULL;
	do {
		$matcher = 'match_'.'Month'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_88 = FALSE; break; }
		while (true) {
			$res_87 = $result;
			$pos_87 = $this->pos;
			$_86 = NULL;
			do {
				$_83 = NULL;
				do {
					$_81 = NULL;
					do {
						$res_78 = $result;
						$pos_78 = $this->pos;
						if (substr($this->string,$this->pos,1) == ',') {
							$this->pos += 1;
							$result["text"] .= ',';
							$_81 = TRUE; break;
						}
						$result = $res_78;
						$this->pos = $pos_78;
						if (substr($this->string,$this->pos,1) == '-') {
							$this->pos += 1;
							$result["text"] .= '-';
							$_81 = TRUE; break;
						}
						$result = $res_78;
						$this->pos = $pos_78;
						$_81 = FALSE; break;
					}
					while(0);
					if( $_81 === FALSE) { $_83 = FALSE; break; }
					$_83 = TRUE; break;
				}
				while(0);
				if( $_83 === FALSE) { $_86 = FALSE; break; }
				$matcher = 'match_'.'Month'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_86 = FALSE; break; }
				$_86 = TRUE; break;
			}
			while(0);
			if( $_86 === FALSE) {
				$result = $res_87;
				$this->pos = $pos_87;
				unset( $res_87 );
				unset( $pos_87 );
				break;
			}
		}
		$_88 = TRUE; break;
	}
	while(0);
	if( $_88 === TRUE ) { return $this->finalise($result); }
	if( $_88 === FALSE) { return FALSE; }
}


/* Year:/\d{4}/ */
protected $match_Year_typestack = array('Year');
function match_Year ($stack = array()) {
	$matchrule = "Year"; $result = $this->construct($matchrule, $matchrule, null);
	if (( $subres = $this->rx( '/\d{4}/' ) ) !== FALSE) {
		$result["text"] .= $subres;
		return $this->finalise($result);
	}
	else { return FALSE; }
}


/* YearList: Year ((','|'-') Year)* */
protected $match_YearList_typestack = array('YearList');
function match_YearList ($stack = array()) {
	$matchrule = "YearList"; $result = $this->construct($matchrule, $matchrule, null);
	$_102 = NULL;
	do {
		$matcher = 'match_'.'Year'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_102 = FALSE; break; }
		while (true) {
			$res_101 = $result;
			$pos_101 = $this->pos;
			$_100 = NULL;
			do {
				$_97 = NULL;
				do {
					$_95 = NULL;
					do {
						$res_92 = $result;
						$pos_92 = $this->pos;
						if (substr($this->string,$this->pos,1) == ',') {
							$this->pos += 1;
							$result["text"] .= ',';
							$_95 = TRUE; break;
						}
						$result = $res_92;
						$this->pos = $pos_92;
						if (substr($this->string,$this->pos,1) == '-') {
							$this->pos += 1;
							$result["text"] .= '-';
							$_95 = TRUE; break;
						}
						$result = $res_92;
						$this->pos = $pos_92;
						$_95 = FALSE; break;
					}
					while(0);
					if( $_95 === FALSE) { $_97 = FALSE; break; }
					$_97 = TRUE; break;
				}
				while(0);
				if( $_97 === FALSE) { $_100 = FALSE; break; }
				$matcher = 'match_'.'Year'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_100 = FALSE; break; }
				$_100 = TRUE; break;
			}
			while(0);
			if( $_100 === FALSE) {
				$result = $res_101;
				$this->pos = $pos_101;
				unset( $res_101 );
				unset( $pos_101 );
				break;
			}
		}
		$_102 = TRUE; break;
	}
	while(0);
	if( $_102 === TRUE ) { return $this->finalise($result); }
	if( $_102 === FALSE) { return FALSE; }
}


/* Date:Year:Year '-' Month:Month '-' Day:Day */
protected $match_Date_typestack = array('Date');
function match_Date ($stack = array()) {
	$matchrule = "Date"; $result = $this->construct($matchrule, $matchrule, null);
	$_109 = NULL;
	do {
		$matcher = 'match_'.'Year'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "Year" );
		}
		else { $_109 = FALSE; break; }
		if (substr($this->string,$this->pos,1) == '-') {
			$this->pos += 1;
			$result["text"] .= '-';
		}
		else { $_109 = FALSE; break; }
		$matcher = 'match_'.'Month'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "Month" );
		}
		else { $_109 = FALSE; break; }
		if (substr($this->string,$this->pos,1) == '-') {
			$this->pos += 1;
			$result["text"] .= '-';
		}
		else { $_109 = FALSE; break; }
		$matcher = 'match_'.'Day'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "Day" );
		}
		else { $_109 = FALSE; break; }
		$_109 = TRUE; break;
	}
	while(0);
	if( $_109 === TRUE ) { return $this->finalise($result); }
	if( $_109 === FALSE) { return FALSE; }
}


/* Easter: 'Easter' > Offset:Offset */
protected $match_Easter_typestack = array('Easter');
function match_Easter ($stack = array()) {
	$matchrule = "Easter"; $result = $this->construct($matchrule, $matchrule, null);
	$_114 = NULL;
	do {
		if (( $subres = $this->literal( 'Easter' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_114 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Offset'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "Offset" );
		}
		else { $_114 = FALSE; break; }
		$_114 = TRUE; break;
	}
	while(0);
	if( $_114 === TRUE ) { return $this->finalise($result); }
	if( $_114 === FALSE) { return FALSE; }
}


/* Equal: '=' | 'IN' */
protected $match_Equal_typestack = array('Equal');
function match_Equal ($stack = array()) {
	$matchrule = "Equal"; $result = $this->construct($matchrule, $matchrule, null);
	$_119 = NULL;
	do {
		$res_116 = $result;
		$pos_116 = $this->pos;
		if (substr($this->string,$this->pos,1) == '=') {
			$this->pos += 1;
			$result["text"] .= '=';
			$_119 = TRUE; break;
		}
		$result = $res_116;
		$this->pos = $pos_116;
		if (( $subres = $this->literal( 'IN' ) ) !== FALSE) {
			$result["text"] .= $subres;
			$_119 = TRUE; break;
		}
		$result = $res_116;
		$this->pos = $pos_116;
		$_119 = FALSE; break;
	}
	while(0);
	if( $_119 === TRUE ) { return $this->finalise($result); }
	if( $_119 === FALSE) { return FALSE; }
}


/* DateExpression: 'Date' > Equal > (Date:Date|Easter:Easter) */
protected $match_DateExpression_typestack = array('DateExpression');
function match_DateExpression ($stack = array()) {
	$matchrule = "DateExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_132 = NULL;
	do {
		if (( $subres = $this->literal( 'Date' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_132 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Equal'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_132 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_130 = NULL;
		do {
			$_128 = NULL;
			do {
				$res_125 = $result;
				$pos_125 = $this->pos;
				$matcher = 'match_'.'Date'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres, "Date" );
					$_128 = TRUE; break;
				}
				$result = $res_125;
				$this->pos = $pos_125;
				$matcher = 'match_'.'Easter'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres, "Easter" );
					$_128 = TRUE; break;
				}
				$result = $res_125;
				$this->pos = $pos_125;
				$_128 = FALSE; break;
			}
			while(0);
			if( $_128 === FALSE) { $_130 = FALSE; break; }
			$_130 = TRUE; break;
		}
		while(0);
		if( $_130 === FALSE) { $_132 = FALSE; break; }
		$_132 = TRUE; break;
	}
	while(0);
	if( $_132 === TRUE ) { return $this->finalise($result); }
	if( $_132 === FALSE) { return FALSE; }
}


/* DateModuloExpression: 'DateModulo' > Equal > Date > '%' > Offset */
protected $match_DateModuloExpression_typestack = array('DateModuloExpression');
function match_DateModuloExpression ($stack = array()) {
	$matchrule = "DateModuloExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_143 = NULL;
	do {
		if (( $subres = $this->literal( 'DateModulo' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_143 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Equal'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_143 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Date'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_143 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == '%') {
			$this->pos += 1;
			$result["text"] .= '%';
		}
		else { $_143 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Offset'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_143 = FALSE; break; }
		$_143 = TRUE; break;
	}
	while(0);
	if( $_143 === TRUE ) { return $this->finalise($result); }
	if( $_143 === FALSE) { return FALSE; }
}


/* DayOfWeekOfMonthExpression:'DayOfWeekOfMonth' > Equal > WeekList DayOfWeek */
protected $match_DayOfWeekOfMonthExpression_typestack = array('DayOfWeekOfMonthExpression');
function match_DayOfWeekOfMonthExpression ($stack = array()) {
	$matchrule = "DayOfWeekOfMonthExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_151 = NULL;
	do {
		if (( $subres = $this->literal( 'DayOfWeekOfMonth' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_151 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Equal'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_151 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'WeekList'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_151 = FALSE; break; }
		$matcher = 'match_'.'DayOfWeek'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_151 = FALSE; break; }
		$_151 = TRUE; break;
	}
	while(0);
	if( $_151 === TRUE ) { return $this->finalise($result); }
	if( $_151 === FALSE) { return FALSE; }
}


/* DayOfMonthExpression: 'DayOfMonth' > Equal > DayList:DayList */
protected $match_DayOfMonthExpression_typestack = array('DayOfMonthExpression');
function match_DayOfMonthExpression ($stack = array()) {
	$matchrule = "DayOfMonthExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_158 = NULL;
	do {
		if (( $subres = $this->literal( 'DayOfMonth' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_158 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Equal'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_158 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'DayList'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "DayList" );
		}
		else { $_158 = FALSE; break; }
		$_158 = TRUE; break;
	}
	while(0);
	if( $_158 === TRUE ) { return $this->finalise($result); }
	if( $_158 === FALSE) { return FALSE; }
}


/* DayAndMonthExpression: 'DayAndMonth' > Equal > Day:Day '/' Month:Month */
protected $match_DayAndMonthExpression_typestack = array('DayAndMonthExpression');
function match_DayAndMonthExpression ($stack = array()) {
	$matchrule = "DayAndMonthExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_167 = NULL;
	do {
		if (( $subres = $this->literal( 'DayAndMonth' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_167 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Equal'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_167 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Day'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "Day" );
		}
		else { $_167 = FALSE; break; }
		if (substr($this->string,$this->pos,1) == '/') {
			$this->pos += 1;
			$result["text"] .= '/';
		}
		else { $_167 = FALSE; break; }
		$matcher = 'match_'.'Month'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "Month" );
		}
		else { $_167 = FALSE; break; }
		$_167 = TRUE; break;
	}
	while(0);
	if( $_167 === TRUE ) { return $this->finalise($result); }
	if( $_167 === FALSE) { return FALSE; }
}


/* DayOfWeekExpression: 'DayOfWeek' > Equal > DayOfWeekList:DayOfWeekList */
protected $match_DayOfWeekExpression_typestack = array('DayOfWeekExpression');
function match_DayOfWeekExpression ($stack = array()) {
	$matchrule = "DayOfWeekExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_174 = NULL;
	do {
		if (( $subres = $this->literal( 'DayOfWeek' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_174 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Equal'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_174 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'DayOfWeekList'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "DayOfWeekList" );
		}
		else { $_174 = FALSE; break; }
		$_174 = TRUE; break;
	}
	while(0);
	if( $_174 === TRUE ) { return $this->finalise($result); }
	if( $_174 === FALSE) { return FALSE; }
}


/* MonthExpression: 'Month' > Equal > MonthList:MonthList */
protected $match_MonthExpression_typestack = array('MonthExpression');
function match_MonthExpression ($stack = array()) {
	$matchrule = "MonthExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_181 = NULL;
	do {
		if (( $subres = $this->literal( 'Month' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_181 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Equal'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_181 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'MonthList'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "MonthList" );
		}
		else { $_181 = FALSE; break; }
		$_181 = TRUE; break;
	}
	while(0);
	if( $_181 === TRUE ) { return $this->finalise($result); }
	if( $_181 === FALSE) { return FALSE; }
}


/* YearExpression: 'Year' > Equal > YearList:YearList */
protected $match_YearExpression_typestack = array('YearExpression');
function match_YearExpression ($stack = array()) {
	$matchrule = "YearExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_188 = NULL;
	do {
		if (( $subres = $this->literal( 'Year' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_188 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Equal'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_188 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'YearList'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "YearList" );
		}
		else { $_188 = FALSE; break; }
		$_188 = TRUE; break;
	}
	while(0);
	if( $_188 === TRUE ) { return $this->finalise($result); }
	if( $_188 === FALSE) { return FALSE; }
}


/* FeatureExpression: '"' > Feature:Feature > '"' > Equal > TokenList:TokenList */
protected $match_FeatureExpression_typestack = array('FeatureExpression');
function match_FeatureExpression ($stack = array()) {
	$matchrule = "FeatureExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_199 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '"') {
			$this->pos += 1;
			$result["text"] .= '"';
		}
		else { $_199 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Feature'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "Feature" );
		}
		else { $_199 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == '"') {
			$this->pos += 1;
			$result["text"] .= '"';
		}
		else { $_199 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Equal'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_199 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'TokenList'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "TokenList" );
		}
		else { $_199 = FALSE; break; }
		$_199 = TRUE; break;
	}
	while(0);
	if( $_199 === TRUE ) { return $this->finalise($result); }
	if( $_199 === FALSE) { return FALSE; }
}


/* NotExpression: 'NOT(' > operand:Result > ')' */
protected $match_NotExpression_typestack = array('NotExpression');
function match_NotExpression ($stack = array()) {
	$matchrule = "NotExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_206 = NULL;
	do {
		if (( $subres = $this->literal( 'NOT(' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_206 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Result'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "operand" );
		}
		else { $_206 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == ')') {
			$this->pos += 1;
			$result["text"] .= ')';
		}
		else { $_206 = FALSE; break; }
		$_206 = TRUE; break;
	}
	while(0);
	if( $_206 === TRUE ) { return $this->finalise($result); }
	if( $_206 === FALSE) { return FALSE; }
}


/* Expression: NotExpression | DateExpression | DateModuloExpression | DayOfWeekOfMonthExpression | DayOfMonthExpression | DayAndMonthExpression | DayOfWeekExpression | MonthExpression | YearExpression | FeatureExpression |  '(' > Result > ')'  */
protected $match_Expression_typestack = array('Expression');
function match_Expression ($stack = array()) {
	$matchrule = "Expression"; $result = $this->construct($matchrule, $matchrule, null);
	$_253 = NULL;
	do {
		$res_208 = $result;
		$pos_208 = $this->pos;
		$matcher = 'match_'.'NotExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_253 = TRUE; break;
		}
		$result = $res_208;
		$this->pos = $pos_208;
		$_251 = NULL;
		do {
			$res_210 = $result;
			$pos_210 = $this->pos;
			$matcher = 'match_'.'DateExpression'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres );
				$_251 = TRUE; break;
			}
			$result = $res_210;
			$this->pos = $pos_210;
			$_249 = NULL;
			do {
				$res_212 = $result;
				$pos_212 = $this->pos;
				$matcher = 'match_'.'DateModuloExpression'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_249 = TRUE; break;
				}
				$result = $res_212;
				$this->pos = $pos_212;
				$_247 = NULL;
				do {
					$res_214 = $result;
					$pos_214 = $this->pos;
					$matcher = 'match_'.'DayOfWeekOfMonthExpression'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_247 = TRUE; break;
					}
					$result = $res_214;
					$this->pos = $pos_214;
					$_245 = NULL;
					do {
						$res_216 = $result;
						$pos_216 = $this->pos;
						$matcher = 'match_'.'DayOfMonthExpression'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_245 = TRUE; break;
						}
						$result = $res_216;
						$this->pos = $pos_216;
						$_243 = NULL;
						do {
							$res_218 = $result;
							$pos_218 = $this->pos;
							$matcher = 'match_'.'DayAndMonthExpression'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_243 = TRUE; break;
							}
							$result = $res_218;
							$this->pos = $pos_218;
							$_241 = NULL;
							do {
								$res_220 = $result;
								$pos_220 = $this->pos;
								$matcher = 'match_'.'DayOfWeekExpression'; $key = $matcher; $pos = $this->pos;
								$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
								if ($subres !== FALSE) {
									$this->store( $result, $subres );
									$_241 = TRUE; break;
								}
								$result = $res_220;
								$this->pos = $pos_220;
								$_239 = NULL;
								do {
									$res_222 = $result;
									$pos_222 = $this->pos;
									$matcher = 'match_'.'MonthExpression'; $key = $matcher; $pos = $this->pos;
									$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
									if ($subres !== FALSE) {
										$this->store( $result, $subres );
										$_239 = TRUE; break;
									}
									$result = $res_222;
									$this->pos = $pos_222;
									$_237 = NULL;
									do {
										$res_224 = $result;
										$pos_224 = $this->pos;
										$matcher = 'match_'.'YearExpression'; $key = $matcher; $pos = $this->pos;
										$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
										if ($subres !== FALSE) {
											$this->store( $result, $subres );
											$_237 = TRUE; break;
										}
										$result = $res_224;
										$this->pos = $pos_224;
										$_235 = NULL;
										do {
											$res_226 = $result;
											$pos_226 = $this->pos;
											$matcher = 'match_'.'FeatureExpression'; $key = $matcher; $pos = $this->pos;
											$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
											if ($subres !== FALSE) {
												$this->store( $result, $subres );
												$_235 = TRUE; break;
											}
											$result = $res_226;
											$this->pos = $pos_226;
											$_233 = NULL;
											do {
												if (substr($this->string,$this->pos,1) == '(') {
													$this->pos += 1;
													$result["text"] .= '(';
												}
												else { $_233 = FALSE; break; }
												if (( $subres = $this->whitespace(  ) ) !== FALSE) {
													$result["text"] .= $subres;
												}
												$matcher = 'match_'.'Result'; $key = $matcher; $pos = $this->pos;
												$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
												if ($subres !== FALSE) {
													$this->store( $result, $subres );
												}
												else { $_233 = FALSE; break; }
												if (( $subres = $this->whitespace(  ) ) !== FALSE) {
													$result["text"] .= $subres;
												}
												if (substr($this->string,$this->pos,1) == ')') {
													$this->pos += 1;
													$result["text"] .= ')';
												}
												else { $_233 = FALSE; break; }
												$_233 = TRUE; break;
											}
											while(0);
											if( $_233 === TRUE ) { $_235 = TRUE; break; }
											$result = $res_226;
											$this->pos = $pos_226;
											$_235 = FALSE; break;
										}
										while(0);
										if( $_235 === TRUE ) { $_237 = TRUE; break; }
										$result = $res_224;
										$this->pos = $pos_224;
										$_237 = FALSE; break;
									}
									while(0);
									if( $_237 === TRUE ) { $_239 = TRUE; break; }
									$result = $res_222;
									$this->pos = $pos_222;
									$_239 = FALSE; break;
								}
								while(0);
								if( $_239 === TRUE ) { $_241 = TRUE; break; }
								$result = $res_220;
								$this->pos = $pos_220;
								$_241 = FALSE; break;
							}
							while(0);
							if( $_241 === TRUE ) { $_243 = TRUE; break; }
							$result = $res_218;
							$this->pos = $pos_218;
							$_243 = FALSE; break;
						}
						while(0);
						if( $_243 === TRUE ) { $_245 = TRUE; break; }
						$result = $res_216;
						$this->pos = $pos_216;
						$_245 = FALSE; break;
					}
					while(0);
					if( $_245 === TRUE ) { $_247 = TRUE; break; }
					$result = $res_214;
					$this->pos = $pos_214;
					$_247 = FALSE; break;
				}
				while(0);
				if( $_247 === TRUE ) { $_249 = TRUE; break; }
				$result = $res_212;
				$this->pos = $pos_212;
				$_249 = FALSE; break;
			}
			while(0);
			if( $_249 === TRUE ) { $_251 = TRUE; break; }
			$result = $res_210;
			$this->pos = $pos_210;
			$_251 = FALSE; break;
		}
		while(0);
		if( $_251 === TRUE ) { $_253 = TRUE; break; }
		$result = $res_208;
		$this->pos = $pos_208;
		$_253 = FALSE; break;
	}
	while(0);
	if( $_253 === TRUE ) { return $this->finalise($result); }
	if( $_253 === FALSE) { return FALSE; }
}


/* AndExpression: 'AND' > operand:Expression > */
protected $match_AndExpression_typestack = array('AndExpression');
function match_AndExpression ($stack = array()) {
	$matchrule = "AndExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_259 = NULL;
	do {
		if (( $subres = $this->literal( 'AND' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_259 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "operand" );
		}
		else { $_259 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_259 = TRUE; break;
	}
	while(0);
	if( $_259 === TRUE ) { return $this->finalise($result); }
	if( $_259 === FALSE) { return FALSE; }
}


/* OrExpression: 'OR' > operand:Expression > */
protected $match_OrExpression_typestack = array('OrExpression');
function match_OrExpression ($stack = array()) {
	$matchrule = "OrExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_265 = NULL;
	do {
		if (( $subres = $this->literal( 'OR' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_265 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "operand" );
		}
		else { $_265 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_265 = TRUE; break;
	}
	while(0);
	if( $_265 === TRUE ) { return $this->finalise($result); }
	if( $_265 === FALSE) { return FALSE; }
}


/* Result: Expression > (AndExpression | OrExpression) * */
protected $match_Result_typestack = array('Result');
function match_Result ($stack = array()) {
	$matchrule = "Result"; $result = $this->construct($matchrule, $matchrule, null);
	$_276 = NULL;
	do {
		$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_276 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_275 = $result;
			$pos_275 = $this->pos;
			$_274 = NULL;
			do {
				$_272 = NULL;
				do {
					$res_269 = $result;
					$pos_269 = $this->pos;
					$matcher = 'match_'.'AndExpression'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_272 = TRUE; break;
					}
					$result = $res_269;
					$this->pos = $pos_269;
					$matcher = 'match_'.'OrExpression'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_272 = TRUE; break;
					}
					$result = $res_269;
					$this->pos = $pos_269;
					$_272 = FALSE; break;
				}
				while(0);
				if( $_272 === FALSE) { $_274 = FALSE; break; }
				$_274 = TRUE; break;
			}
			while(0);
			if( $_274 === FALSE) {
				$result = $res_275;
				$this->pos = $pos_275;
				unset( $res_275 );
				unset( $pos_275 );
				break;
			}
		}
		$_276 = TRUE; break;
	}
	while(0);
	if( $_276 === TRUE ) { return $this->finalise($result); }
	if( $_276 === FALSE) { return FALSE; }
}



}


?>