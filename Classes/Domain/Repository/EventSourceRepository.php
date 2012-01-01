<?php
namespace Org\Gucken\Events\Domain\Repository;

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * A repository for Events
 *
 * @package Org.Gucken.Events
 * @subpackage Domain
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * 
 * @FLOW3\Scope("singleton")
 */
class EventSourceRepository extends \TYPO3\FLOW3\Persistence\Repository {
	/**
	 *
	 * @return \TYPO3\FLOW3\Persistence\QueryResultInterface 
	 */
	public function findAllActive() {
		$query = $this->createQuery();
		return $query->matching($query->equals('active', 1))->execute();
	}

}
?>