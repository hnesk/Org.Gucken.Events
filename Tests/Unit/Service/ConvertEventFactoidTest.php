<?php
namespace Org\Gucken\Events\Tests\Unit\Service;

use Org\Gucken\Events\Domain\Model\EventFactoidIdentity;
use Org\Gucken\Events\Domain\Model\EventFactoid;
use Org\Gucken\Events\Service\ConvertEventFactoidService;


class ConvertEventFactoidServiceTest extends \TYPO3\FLOW3\Tests\UnitTestCase {
	
	/**
	 * @test 
	 */
	public function convertTransfersIdentityData() {
		$eventRepository = $this->getMock('Org\Gucken\Events\Domain\Repository\EventRepository');
		$eventRepository->expects($this->once())->method('add');
		$service = new ConvertEventFactoidService();
		$service->injectEventRepository($eventRepository);
		
		$identity = $this->buildIdentity();
		$event = $service->convert($identity);
		
		$this->assertSame($identity->getStartDateTime(),$event->getStartDateTime(), 'startDateTime is not the same');
		$this->assertSame($identity->getLocation(),$event->getLocation(), 'Location is not the same');		
		$this->assertSame($identity,$event->getFactoidIdentitys()->first(), 'factoidIdentity is not the same');		
	}
	
	/**
	 * @test 
	 */
	public function convertTransfersFactoidData() {
		$eventRepository = $this->getMock('Org\Gucken\Events\Domain\Repository\EventRepository');
		$eventRepository->expects($this->once())->method('add');
		$service = new ConvertEventFactoidService();
		$service->injectEventRepository($eventRepository);
		
		$identity = $this->buildIdentity();
		
		$event = $service->convert($identity);
		
		$factoid = $identity->getFactoid();
		
		$this->assertSame($factoid->getTitle(),$event->getTitle());
		$this->assertSame($factoid->getShortDescription(),$event->getShortDescription());
		$this->assertSame($factoid->getDescription(),$event->getDescription());
		$this->assertSame($factoid->getEndDateTime(),$event->getEndDateTime());
		$this->assertSame($factoid->getType(),$event->getTypes()->first());
		
	}
	
	
	/**
	 *
	 * @return EventFactoidIdentity 
	 */
	protected function buildIdentity() {
		
		$factoid = new EventFactoid();
		$factoid->setTitle('Test');
		$factoid->setStartDateTime(new \DateTime());
		$factoid->setEndDateTime(new \DateTime());
		$factoid->setImportDateTime(new \DateTime());
		$factoid->setShortDescription('short description');
		$factoid->setDescription('Long description');
		$factoid->setType(new \Org\Gucken\Events\Domain\Model\Type());
		$factoid->setUrl('http://www.example.com/');
		
		$identity = new EventFactoidIdentity();
		$identity->setLocation(new \Org\Gucken\Events\Domain\Model\Location());
		$identity->setSource(new \Org\Gucken\Events\Domain\Model\EventSource());
		$identity->setStartDateTime(new \DateTime());
		$identity->addFactoid($factoid);
		
		return $identity;
	}
}
?>
