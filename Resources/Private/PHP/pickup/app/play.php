<?php
require_once 'init.php';
require_once '../../SG-iCalendar/SG_iCal.php';

$cal = url('https://www.google.com/calendar/ical/webmistress%40fraze.de/public/basic.ics')->load()->getContent();
/*
$icsFile = BASE_PATH.'/../Tests/fixtures/Calendar/fraze.ics';
$icsContent = file_get_contents($icsFile);

$cal = \Type\Calendar\Factory::fromString($icsContent);
*/
foreach ($cal->getEvents() as $event) {
	echo $event.PHP_EOL;
}

die();




#print_r(App::instance()->getConfig());

#$page = new Facebook\Type\Page('stereo.bielefeld');

#print_r($page->getEvents('-1 days','+28 days')->getNativeValue());


#die();


echo 1;

echo url('http://www.ringlokschuppen-bielefeld.com/index.php?view=details&id=391%3Abe-invited-party&option=com_eventlist&Itemid=54')
    ->load('badHtml')->getContent()->xpath('html')->asXml();


die();
$string =  '11.11.11 20:00, planks,  || trainwräck || hysterse || high society @ ajz bielefeld';


$keywords = string($string)->toLower()->pregReplace('#[^[:alpha:]]+#ui', ' ')->normalizeSpace()->explode(' ')->unique()->getNativeValue();

print_r($keywords);
die();



$string = 'Udo Baumann Ulrich Meier Wolfgang Neumann Buchladen Eulenspiegel Donnerstag, 13.10.2011 20.00 Uhr Eintritt: 8,–/6,– €';
#$regExp = '#(?<day>(?:3[01]|[12][0-9]|0?[1-9]))\.\s*((?<f_month>[A-Za-zäöüÄÖÜ]+)|(?<month>(?:1[0-2]|0?[0-9]))\.)\s*(?<year>\d{4})\s+(?<hour24>(?:2[0-3]|1[0-9]|0?[0-9])).(?<minute>[0-5][0-9])#';

$string = new Type\String('Udo Baumann Ulrich Meier Wolfgang Neumann Buchladen Eulenspiegel Donnerstag, 13.10.2011 20.00 Uhr Eintritt: 8,–/6,– €');
$date = $string->asDate('%d\.\s*(%B|%m\.)\s*%Y\s+%H.%M');

var_dump($date);


die();
$content = url('http://www.buchladen-eulenspiegel.de/lib/dispatch.php?thema=lesung&page=null')
            ->load(o('headers.X-Requested-With=XMLHttpRequest')->merge('metadata.override.content-type=application/json'))
            ->getContent()->jpath('content')->asHtml()->asXmlString();

$source = new \Domain\Extractor\Event\Eulenspiegel();
print_r($source->getEvents()->getNativeValue());


#$venues = Lastfm\Type\Venue\Collection\Factory::fromString('AJZ Bielefeld');
#print_r($venues->first()->getNativeValue());

#$source = new Domain\Extractor\Event\Bunker('ProgrammView,con', 'Konzert');

#$source = new \Domain\Extractor\Event\Eulenspiegel();
#$source = new \Domain\Extractor\Event\LastFm();


#$sources = new Domain\Events();
#print_r($source->getEvents()->getNativeValue());

#$zaz = new Domain\Extractor\Event\PopSecret();
#print_r($zaz->getEvents()->getNativeValue());

#print_r($bielefeldAtLastFm->getEvents());

#$bielefeldAtFacebook = new Domain\Extractor\Event\Facebook();
#print_r($bielefeldAtFacebook->getEvents());

#$user->authenticate();

#$ticker = url('https://mtgox.com/code/data/ticker.php')->load()->getContent()->jpath('ticker');
#$luna = new \Domain\Extractor\Event\LunaKino();
#print_r($luna->getEvents()->getNativeValue());

?>
