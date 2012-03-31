<?php

/**
 *
 * @param \Type\Url|string $url
 * @return \Type\Url
 */
function url($url, $relative = null) {
	return new \Type\Url($url, $relative);
}


/**
 *
 * @param \Type\Url|string $url
 * @return \Type\Directory
 */
function directory($url) {
	return new \Type\Directory($url);
}


/**
 *
 * @param int $start
 * @param int $end
 * @param int $step
 * @return \Type\Number\Range
 */
function typerange($start, $end, $step = 1) {
	return new \Type\Number\Range($start, $end, $step);
}


/**
 *
 * @return \Type\String
 */
function string($text = '') {
	return new \Type\String($text);
}

/**
 *
 * @return \Type\String
 */
function newdate($date = null) {
	return new \Type\Date($date);
}

/**
 * Picks the first argument that evaluates to something
 * @param
 * @return mixed
 */
function pick() {
    foreach (func_get_args() as $argument) {
        if ($argument) {
            if (is_object($argument) && $argument instanceof \Type\Base) {
                if ($argument->is()) {
                    return $argument;
                }
            } else {
                return $argument;
            }
        }
    }
}

function is($type) {
    return $type && is_object($type) && $type instanceof Type\Base && $type->is();
}

/**
 *
 * @param string|array|object
 * @return \Util\Options
 */
function options($options) {
	return \Util\Options::factory($options);
}


/**
 *
 * @param string|array|object
 * @return \Util\Options
 */
function o($options=array()) {
	return \Util\Options::factory($options);
}



define('SECOND',1);
define('MINUTE',60*SECOND);
define('HOUR',60*MINUTE);
define('DAY',24*HOUR);
define('WEEK',7*DAY);
define('NOW',time());


define('PARAGRAPH_SEPARATOR',PHP_EOL.PHP_EOL);