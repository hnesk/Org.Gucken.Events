<?php

namespace Org\Gucken\Events\Domain\Model\EventSource;

use Org\Gucken\Events\Domain\Model\EventFactoidIdentity;
use Org\Gucken\Events\Domain\Model\EventLink;
use Org\Gucken\Events\Domain\Model\WebEventLink;
use Type\Record\Collection;
use Org\Gucken\Events\Domain\Model;
use Org\Gucken\Events\Annotations as Events;
use TYPO3\Flow\Annotations as Flow;

/**
 * Null Implementation that does nothing
 * @Flow\Scope("prototype")
 */
class ManualEntry implements EventSourceInterface
{
    /**
     *
     * @param  EventFactoidIdentity $factoidIdentity
     * @param  EventLink            $link            if set link will be updated else created
     * @return WebEventLink
     */
    public function convertLink(EventFactoidIdentity $factoidIdentity,EventLink $link = null)
    {
        $link = $link ?: new WebEventLink();
        $link->setUrl($factoidIdentity->getFactoid()->getUrl());

        return $link;
    }

    /**
     * @return Collection
     */
    public function getEvents()
    {
        return new Collection();
    }

}
