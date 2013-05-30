<?php
namespace Org\Gucken\Events\ViewHelpers\Format;

use TYPO3\FLOW3\Annotations as FLOW3;


/**
 * Formats a \DateTime object.
 *
 * = Examples =
 *
 * <code title="Defaults">
 * <e:format.strftime>{dateObject}</e:format.strftime>
 * </code>
 * <output>
 * 1980-12-13
 * (depending on the current date)
 * </output>
 *
 * <code title="Custom date format">
 * <e:format.strftime format="H:i">{dateObject}</e:format.strftime>
 * </code>
 * <output>
 * 01:23
 * (depending on the current time)
 * </output>
 *
 * <code title="strtotime string">
 * <e:format.strftime format="d.m.Y - H:i:s">+1 week 2 days 4 hours 2 seconds</e:format.strftime>
 * </code>
 * <output>
 * 13.12.1980 - 21:03:42
 * (depending on the current time, see http://www.php.net/manual/en/function.strtotime.php)
 * </output>
 *
 * <code title="output date from unix timestamp">
 * <e:format.strftime format="d.m.Y - H:i:s">@{someTimestamp}</e:format.strftime>
 * </code>
 * <output>
 * 13.12.1980 - 21:03:42
 * (depending on the current time. Don't forget the "@" in front of the timestamp see http://www.php.net/manual/en/function.strtotime.php)
 * </output>
 *
 * <code title="Inline notation">
 * {e:format.strftime(date: dateObject)}
 * </code>
 * <output>
 * 1980-12-13
 * (depending on the value of {dateObject})
 * </output>
 *
 * <code title="Inline notation (2nd variant)">
 * {dateObject -> e:format.strftime()}
 * </code>
 * <output>
 * 1980-12-13
 * (depending on the value of {dateObject})
 * </output>
 *
 * @api
 */
class StrftimeViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @var boolean
	 */
	protected $escapingInterceptorEnabled = FALSE;

	/**
	 * Render the supplied DateTime object as a formatted date.
	 *
	 * @param mixed $date either a \DateTime object or a string that is accepted by \DateTime constructor
	 * @param string $format Format String which is taken to format the Date/Time
	 * @param string $locale Locale to use
	 * @return string Formatted date
	 * @api
	 */
	public function render($date = NULL, $format = 'Y-m-d',$locale = 'de_DE.utf8') {
		if ($date === NULL) {
			$date = $this->renderChildren();
			if ($date === NULL) {
				return '';
			}
		}
		if (!$date instanceof \DateTime) {
			try {
				$date = new \DateTime($date);
			} catch (\Exception $exception) {
				throw new \TYPO3\Fluid\Core\ViewHelper\Exception('"' . $date . '" could not be parsed by \DateTime constructor.', 1241722579);
			}
		}
		$settedLocale = null;
		$currentLocale =  setlocale(LC_TIME, 0);
		if ($locale !==  $currentLocale) {
			$settedLocale = setlocale(LC_TIME, $locale);
		}
		$timeString = strftime($format, $date->getTimestamp());
		if ($settedLocale) {
			setlocale(LC_TIME, $currentLocale);
		}
		return $timeString;
	}
}
?>