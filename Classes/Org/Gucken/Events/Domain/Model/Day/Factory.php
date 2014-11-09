<?php
namespace Org\Gucken\Events\Domain\Model\Day;
use Org\Gucken\Events\Domain\Model\Day;

use TYPO3\Flow\Annotations as Flow;

/**
 * A Factory for Days, that ensures unique objects
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @Flow\Scope("singleton")
 */
class Factory
{

    /**
     * A lookup by date and timezone for all created Day Objects to ensure uniqueness of days by date
     *
     * @var array
     */
    protected $daysBuild = array();

    /**
     *
     * @var array
     */
    protected $settings = array();

    /**
     *
     * @var int
     */
    protected $midnightHour = 0;

    public function injectSettings($settings)
    {
        $this->settings = $settings;
        $this->midnightHour = (int) $this->settings['midnightHour'];
    }

    /**
     * builds a Day, unique by Date
     *
     * @return \Org\Gucken\Events\Domain\Model\Day
     */
    public function build(\DateTime $date = null)
    {
        $date = $date ?: new \DateTime();
        $date = clone $date;
        $date->modify('-' . $this->midnightHour . ' hours');
        $key = $date->format('Y-m-d') . '|' . $date->getTimezone()->getName();
        if (!isset($this->daysBuild[$key])) {
            $this->daysBuild[$key] = new Day($date);
        }

        return $this->daysBuild[$key];
    }
}
