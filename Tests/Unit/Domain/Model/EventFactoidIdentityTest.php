<?php
namespace Org\Gucken\Events\Tests\Unit\Domain\Model;

use Org\Gucken\Events\Domain\Model\EventFactoidIdentity;
use Org\Gucken\Events\Domain\Model\EventFactoid;
use TYPO3\Flow\Tests\UnitTestCase;


class EventFactoidIdentityTest extends UnitTestCase {
	
	/**
	 * @test 
	 */
	public function getFactoidReturnsNewestFactoid() {
		$identity = new EventFactoidIdentity();
		
		$factoid1 = new EventFactoid();
		$factoid1->setImportDateTime(new \DateTime('2011-11-01'));
		$identity->addFactoid($factoid1);
		
		$factoid2 = new EventFactoid();
		$factoid2->setImportDateTime(new \DateTime('2011-12-01'));
		$identity->addFactoid($factoid2);
		
		$factoid3 = new EventFactoid();
		$factoid3->setImportDateTime(new \DateTime('2011-11-15'));
		$identity->addFactoid($factoid3);
		
		
		self::assertEquals($factoid2, $identity->getFactoid());
		
	}
}
?>
