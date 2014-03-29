<?php
namespace Org\Gucken\Events\Tests\Unit\Service;

use Org\Gucken\Events\Domain\Model\EventFactoidIdentity;
use Org\Gucken\Events\Domain\Model\EventFactoid;
use Org\Gucken\Events\Domain\Model\EventSource;
use Org\Gucken\Events\Domain\Model\Location;
use Org\Gucken\Events\Domain\Model\Type;
use Org\Gucken\Events\Domain\Repository\EventRepository;
use Org\Gucken\Events\Domain\Repository\EventFactoidIdentityRepository;
use Org\Gucken\Events\Service\ConvertEventFactoidService;
use TYPO3\Flow\Tests\UnitTestCase;


class ConvertEventFactoidServiceTest extends UnitTestCase {
	
	/**
	 *
	 * @var Org\Gucken\Events\Service\ConvertEventFactoidService
	 */
	protected $service;
	
	public function setUp() {
		$eventRepository = $this->getMock(EventRepository::class, array(),array(),'', false);
		$eventRepository->expects($this->once())->method('add');
		
		$identityRepository = $this->getMock(EventFactoidIdentityRepository::class, array(),array(),'', false);
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
		$factoid->setType(new Type());
		$factoid->setUrl('http://www.example.com/');
		
		$identity = new EventFactoidIdentity();
		$identity->setLocation(new Location());
		$identity->setSource(new EventSource());
		$identity->setStartDateTime(new \DateTime());
		$identity->addFactoid($factoid);
		
		return $identity;
	}
}
?>
