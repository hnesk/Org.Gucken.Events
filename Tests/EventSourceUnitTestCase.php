<?php
namespace Org\Gucken\Events\Tests;

require_once __DIR__.'/../Resources/Private/PHP/pickup/app/init.php';

class EventSourceUnitTestCase extends \TYPO3\FLOW3\Tests\UnitTestCase {	
	
	/**
	 * @var string
	 */
	protected $baseUrl;
	
	/**
	 * @var \Org\Gucken\Events\Domain\Model\EventSource\EventSourceInterface
	 */
	protected $source; 
	
	
	public function assertEventDataIsCorrect($file, $nr, $data) {
		$this->source->setUrl($this->baseUrl.'/'.$file);				
		$event = $this->source->getEvents()->item($nr);
				
		foreach ($data as $key => $value) {
			if (is_string($value) &&  strpos($value,'###') === 0) {
				self::assertContains(trim(substr($value, 3)), $event[$key]);
			} else {
				self::assertEquals($value, $event[$key]);
			}
			
		}		
	}
	
}
?>
