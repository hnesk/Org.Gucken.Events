<?php
namespace Org\Gucken\Events\Tests\Unit\Domain\Model;


class EventSourceTest extends \TYPO3\FLOW3\Tests\UnitTestCase {
	
	/**
	 * @test 
	 */
	public function getParameterPropertiesReturnsEmptyArrayWithoutImplementationClass() {
		$source = new \Org\Gucken\Events\Domain\Model\EventSource();
		$properties = $source->getParameterProperties();
		$this->assertEquals(0,count($properties));		
	}
}
?>
