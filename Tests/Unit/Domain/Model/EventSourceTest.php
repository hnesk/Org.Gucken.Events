<?php
namespace Org\Gucken\Events\Tests\Unit\Domain\Model;


use Org\Gucken\Events\Domain\Model\EventSource;

class EventSourceTest extends \TYPO3\Flow\Tests\UnitTestCase {
	
	/**
	 * @test 
	 */
	public function getParameterPropertiesReturnsEmptyArrayWithoutImplementationClass() {
		$source = new EventSource();
		$properties = $source->getParameterProperties();
		$this->assertEquals(0,count($properties));		
	}
}
?>
