<?php
namespace Org\Gucken\Events\Tests\Unit\Domain\Model\EventSource;

use Org\Gucken\Events\Domain\Model\EventSource\AbstractEventSource;
use Org\Gucken\Events\Tests\EventSourceUnitTestCase;

require_once FLOW_PATH_PACKAGES.'/Application/Org.Gucken.Events/Tests/EventSourceUnitTestCase.php';

class AbstractEventSourceTest extends EventSourceUnitTestCase {
	
	/**
	 * @test 
	 */
	public function getUrlReturnsUrlObject() {
        $eventSource = $this->getMockForAbstractClass(AbstractEventSource::class);
		/* @var $eventSource \Org\Gucken\Events\Domain\Model\EventSource\AbstractEventSource */
		$eventSource->setUrl('http://www.phpunit.de/manual/3.6/en/test-doubles.html');
		self::assertInstanceOf('\Type\Url', $eventSource->getUrl());
	}
	
	/**
	 * @test 
	 */
	public function getUrlReturnsUrlObjectWithSetUrl() {
        $eventSource = $this->getMockForAbstractClass('\Org\Gucken\Events\Domain\Model\EventSource\AbstractEventSource');
		/* @var $eventSource \Org\Gucken\Events\Domain\Model\EventSource\AbstractEventSource */
		$url = 'http://www.phpunit.de/manual/3.6/en/test-doubles.html';
		$eventSource->setUrl($url);
		self::assertEquals($url, $eventSource->getUrl()->getNativeValue());
	}
	
}
?>
