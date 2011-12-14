<?php
namespace Org\Gucken\Events\Domain\Model\Day;
use Org\Gucken\Events\Domain\Model\Day;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * A Factory for Days, that ensures unique objects
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @FLOW3\Scope("singleton")
 */
class Factory {


	/**
	 * A lookup by date and timezone for all created Day Objects to ensure uniqueness of days by date
	 *
	 * @var array
	 */
	protected $daysBuild = array();

	/**
	 * builds a Day, unique by Date
	 * 
	 * @return \Org\Gucken\Events\Domain\Model\Day
	 */
	public function build(\DateTime $date = null) {
		$date = $date ?: new \DateTime();
		$key = $date->format('Y-m-d').'|'.$date->getTimezone()->getName();
		if (!isset($this->daysBuild[$key])) {
			$this->daysBuild[$key] = new Day($date);
		}
		return $this->daysBuild[$key];
	}
}
?>
