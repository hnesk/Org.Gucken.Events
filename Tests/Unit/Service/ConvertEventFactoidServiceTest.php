<?php
namespace Org\Gucken\Events\Tests\Unit\Service;

use Org\Gucken\Events\Domain\Model\EventFactoidIdentity;
use Org\Gucken\Events\Domain\Model\EventFactoid;
use Org\Gucken\Events\Service\ConvertEventFactoidService;


class ConvertEventFactoidServiceTest extends \TYPO3\FLOW3\Tests\UnitTestCase {
	
	/**
	 *
	 * @var Org\Gucken\Events\Service\ConvertEventFactoidService
	 */
	protected $service;
	
	public function setUp() {
		$eventRepository = $this->getMock('Org\Gucken\Events\Domain\Repository\EventRepository');
		$eventRepository->expects($this->once())->method('add');
		
		$identityRepository = $this->getMock('Org\Gucken\Events\Domain\Repository\EventFactoidIdentityRepository');
		$identityRepository->expects($this->once())->method('update');		
		
		$this->service = new ConvertEventFactoidService();
		$this->service->injectEventRepository($eventRepository);
		$this->service->injectFactoidIdentityRepository($identityRepository);		
	}
	
	public function tearDown() {
		$this->service = null;
	}
		
	/**
	 * @test 
	 */
	public function convertTransfersIdentityData() {
		
		$identity = $this->buildIdentity();
		$event = $this->service->convert($identity);
						
		$this->assertSame($identity->getStartDateTime(),$event->getStartDateTime(), 'startDateTime is not the same');
		$this->assertSame($identity->getLocation(),$event->getLocation(), 'Location is not the same');		
	}
	
	/**
	 * @test 
	 */
	public function convertTransfersFactoidData() {
		
		$identity = $this->buildIdentity();
		
		$event = $this->service->convert($identity);
		
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
