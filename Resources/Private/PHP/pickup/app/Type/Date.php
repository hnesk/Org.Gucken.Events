<?php

namespace Type;

/**
 * A Date Class
 *
 * @author jk
 */
class Date extends \Type\Base {
    const MONTH_SHORT = '%b';
    const MONTH_LONG = '%B';

    const WEEKDAY_SHORT = '%a';
    const WEEKDAY_LONG = '%A';


    /**
     *
     * @var \DateTime
     */
    protected $date;

    public function __construct($date= null, $modifier = null) {
        $this->set($date);
        if (!is_null($modifier)) {
            if (is_numeric($modifier)) {
                $tz = $this->date->getTimezone();
                $this->date->setTimestamp($this->date->getTimestamp() + $modifier);
                $this->date->setTimezone($tz);
            } else {
                $this->date->modify($modifier);
            }
        }
    }

    protected function set($date) {
        if (is_null($date)) {
            $this->date = clone \App::instance()->date();
        } else if ($date instanceof Date) {
            $this->date = $date->getNativeValue();
        } else if ($date instanceof \DateTime) {
            $this->date = $date;
        } else if ($date instanceof \Zend_Date) {
            $dateParts = $date->toArray();
            $this->date = new \DateTime();
            $this->date->setDate($dateParts['year'], $dateParts['month'], $dateParts['day']);
            $this->date->setTime($dateParts['hour'], $dateParts['minute'], $dateParts['second']);
            $this->date->setTimezone(new \DateTimeZone($dateParts['timezone']));
        } else if (\is_numeric($date)) {
            $this->date = new \DateTime('@' . $date);
			$this->date->setTimezone(new \DateTimeZone('Europe/Berlin'));
        } else if (\is_array($date)) {
            $this->date = new \DateTime();
            $this->date->setDate($date[0] , $date[1], $date[2]);
            $this->date->setTime(isset($date[3]) ? $date[3] : 0 , isset($date[4]) ? $date[4] : 0, isset($date[5]) ? $date[5] : 0);
        } else if (\is_string($date)) {
            $this->date = new \DateTime($date);
        }
    }

    public function __toString() {
        return $this->date->format(\DateTime::ISO8601);
    }

    public function getNativeValue() {
        return $this->date;
    }

    /**
     *
     * @param string $string
     * @return Date
     */
    public function modified($string) {
        $date = clone $this->date;
        $date->modify($string);
        return new self($date);
    }

    /**
     *
     * @param string $string
     * @return Date
     */
    public function timed($time, $format='%H:%M:%S', $defaults=array()) {
        $timedDate = null;

        if (\is_string($time)) {
            $parser = new Date\Parser($format, $defaults);
            if ($parser->match($time)) {
                $timedDate = $parser->getDate();
            }
        } else if (\is_object($time)) {
            if ($time instanceof Date) {
                $timedDate = $time->getNativeValue();
            } else if ($time instanceof \DateTime) {
                $timedDate = $time;
            }
        }

        if ($timedDate) {
            $date = clone $this->date;
            /* @var $date \DateTime */
            $date->setTime($timedDate->format('H'), $timedDate->format('i'), $timedDate->format('s'));
            return new self($date);
        } else {
            return $this;
        }
    }


    /**
     *
     * @param Date $date
     * @return Number
     */
    public function compare(Date $date) {
        return new Number($this->date->getTimestamp() - $date->date->getTimestamp());
    }


    /**
     *
     * @param Date $date
     * @return boolean
     */
    public function isBefore(Date $date) {
        return $this->compare($date)->isNegative();
    }

    /**
     *
     * @param Date $date
     * @return boolean
     */
    public function isAfter(Date $date) {
        return $this->compare($date)->isPositive();
    }

    public function guaranteeAfter($date) {
        if ($this->isAfter($date)) {
            return $this;
        }
        $current = clone $this;
        while ($current->isBefore($date)) {
            $current = $current->modified('+1 day');
        }
        return $current;
    }


    public function guaranteeBefore($date) {
        if ($this->isBefore($date)) {
            return $this;
        }
        $current = clone $this;
        while ($current->isAfter($date)) {
            $current = $current->modified('-1 day');
        }
        return $current;
    }


    /**
     *
     * @param string $format
     * @return String
     */
    public function format($format = 'Y-m-d H:i:s') {
        return new String($this->date->format($format));
    }

    /**
     * Return
     *
     * @param int $length
     * @param int $monthStep
     * @return Date\Collection
     */
    public function getMonthRange($length = 3, $monthStep = 1) {
        $result = new Date\Collection();
        $current = new Date(array(
            (string)$this->getYear(),
            (string)$this->getMonth(),
            1
        ));

        /* @var $current Date */
        for ($t = 0; $t < $length; $t += $monthStep) {
            $result->addOne($current);
            $current = new Date(array(
                (string)$current->getYear(),
                (string)$current->getMonth()+$monthStep,
                1
            ));
        }

        return $result;
    }

    /**
     *
     * @param string $format
     * @param null|string|array $locale
     * @return \Type\String
     */
    public function strftime($format = '%Y-%m-%d %H:%M:%S', $locale = null) {
        $oldLocale = null;
        if (!empty($locale)) {
            $oldLocale = \setlocale(\LC_TIME, $locale);
        }
        $string = new String(\strftime($format, $this->date->getTimestamp()));
        if (!empty($oldLocale)) {
            \setlocale(\LC_TIME, $oldLocale);
        }
        return $string;
    }

    /**
     *
     * @return \Type\Number
     */
    public function getYear() {
        return new Number($this->date->format('Y'));
    }

    /**
     *
     * @return \Type\Number
     */
    public function getMonth() {
        return new Number($this->date->format('m'));
    }

    /**
     *
     * @param null|string|array $locale
     * @return \Type\String
     */
    public function getMonthStringLong($locale = 'de_DE.UTF-8') {
        return $this->strftime(self::MONTH_LONG, $locale);
    }

    /**
     *
     * @param null|string|array $locale
     * @return \Type\String
     */
    public function getMonthStringShort($locale = null) {
        return $this->strftime(self::MONTH_SHORT, $locale);
    }

    /**
     *
     * @return \Type\Number
     */
    public function getDay() {
        return new Number($this->date->format('d'));
    }

    /**
     *
     * @return Number
     */
    public function getDayOfYear() {
        return new Number($this->date->format('z'));
    }

    /**
     *
     * @return Number
     */
    public function getIsoDayOfWeek() {
        return new Number($this->date->format('N'));
    }

    /**
     *
     * @return Number
     */
    public function getDaysInMonth() {
        return new Number($this->date->format('t'));
    }

    /**
     * Week of the year according to ISO-8601
     *
     * @return Number
     */
    public function getIsoWeek() {
        return new Number($this->date->format('W'));
    }

    /**
     * Year according to ISO-8601 (to be used with @see getIsoWeek())
     *
     * @return Number
     */
    public function getIsoYear() {
        return new Number($this->date->format('o'));
    }

    /**
     * @return boolean
     */
    public function isDaylightSavingTime() {
        return (boolean) $this->date->format('I');
    }

    /**
     *
     * @return Number
     */
    public function getHour() {
        return new Number($this->date->format('H'));
    }

    /**
     *
     * @return Number
     */
    public function getMinute() {
        return new Number($this->date->format('i'));
    }

    /**
     *
     * @return Number
     */
    public function getSecond() {
        return new Number($this->date->format('s'));
    }

    /**
     *
     * @param int $seconds
     * @return \Type\Date
     */
    public static function ago($seconds) {
        return new Date(null, -$seconds);
    }

    /**
     *
     * @param int $seconds
     * @return \Type\Date
     */
    public static function in($seconds) {
        return new Date(null, $seconds);
    }

    /**
     *
     * @param string|\DateTime $date
     * @return \Type\Date
     */
    public static function cast($date) {
        try {
            return new Date($date);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Could not convert ' . (\is_object($date) ? get_class($date) : \gettype($date)) . ' to a Date', NULL, $e);
        }
    }

}

?>
