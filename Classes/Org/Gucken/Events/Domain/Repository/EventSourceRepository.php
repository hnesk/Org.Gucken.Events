<?php
namespace Org\Gucken\Events\Domain\Repository;

use TYPO3\Flow\Persistence\QueryInterface;
use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for Events
 *
 * @package Org.Gucken.Events
 * @subpackage Domain
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * 
 * @Flow\Scope("singleton")
 */
class EventSourceRepository extends \TYPO3\Flow\Persistence\Repository {
	protected $defaultOrderings = array(
		'name' => QueryInterface::ORDER_ASCENDING
	);
	
	/**
	 *
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface 
	 */
	public function findAllActive() {
		$query = $this->createQuery();
		return $query->matching($query->equals('active', 1))->execute();
	}

}
?>