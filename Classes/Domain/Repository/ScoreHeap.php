<?php
namespace Org\Gucken\Events\Domain\Repository;

/**
 * A heap to compare scorable objects
 *
 * @package Org.Gucken.Events
 * @subpackage Domain
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * 
 */
class ScoreHeap extends \SplHeap {
	
	/**
	 * 
	 * @param array $keywords 
	 */
	public function __construct($keywords) {
		$this->keywordLookup = array_flip($keywords);
	}
	
	/**
	 * @param Location $value1
	 * @param Location $value2
	 * @return int
	 */
	protected function compare(\Org\Gucken\Events\Domain\Model\ScorableInterface $value1, \Org\Gucken\Events\Domain\Model\ScorableInterface $value2) {
		return $value1->score($this->keywordLookup) - $value2->score($this->keywordLookup);
	}
}
?>
