<?php
namespace Org\Gucken\Events\Tests\Unit\Domain\Model\EventSource;

require_once FLOW3_PATH_PACKAGES.'/Application/Org.Gucken.Events/Tests/EventSourceUnitTestCase.php';


class AbstractEventSourceTest extends \Org\Gucken\Events\Tests\EventSourceUnitTestCase {
	
	/**
	 * @test 
	 */
	public function getUrlReturnsUrlObject() {
        $eventSource = $this->getMockForAbstractClass('\Org\Gucken\Events\Domain\Model\EventSource\AbstractEventSource');
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
