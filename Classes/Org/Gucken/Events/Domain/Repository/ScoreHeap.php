<?php
namespace Org\Gucken\Events\Domain\Repository;
use Org\Gucken\Events\Domain\Model\ScorableInterface;

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
     * @param ScorableInterface $value1
     * @param ScorableInterface $value2
     * @return int
     */
	protected function compare($value1, $value2) {
		return $value1->score($this->keywordLookup) - $value2->score($this->keywordLookup);
	}


	/**
	 *
	 * @param int $minScore
	 * @return array
	 */
	public function getBest($minScore = 1) {
		$result = array();
		$finished = false;
		while ( $this->valid() && !$finished ) {
			$node = $this->extract();
			$score = $node->score($this->keywordLookup);
			
			if ($score >= $minScore) {
				$result[] = $node;
			} else {
				$finished = true;
			}
		}
		return $result;

	}
}
?>
