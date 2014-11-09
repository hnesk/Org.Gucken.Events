<?php
namespace Org\Gucken\Events\Domain\Model;

/**
 * Scorable items can calculate a score based on matching keywords, so they can be used in ScoreHeap
 */
interface ScorableInterface
{
    /**
     * Returns a score how good the value fits the search keywords
     *
     * @param  array $keywordLookup
     * @return float
     */
    public function score(array $keywordLookup);
}
