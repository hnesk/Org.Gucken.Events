<?php

namespace Org\Gucken\Events\Domain\Model;

/* *
 * This script belongs to the Flow package "Org.Gucken.Events".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License as published by the Free   *
 * Software Foundation, either version 3 of the License, or (at your      *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        *
 * You should have received a copy of the GNU General Public License      *
 * along with the script.                                                 *
 * If not, see http://www.gnu.org/licenses/gpl.html                       *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * An Event type
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @Flow\Scope("prototype")
 * @Flow\Entity
 */
class Type implements ScorableInterface
{

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $pluralTitle;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var ArrayCollection<\Org\Gucken\Events\Domain\Model\TypeKeyword>
     * @ORM\OneToMany(mappedBy="type", cascade={"all"}, orphanRemoval=true)
     */
    protected $keywords;

    /**
     *
     * @param string $title
     * @param string $pluralTitle
     * @param string $description
     */
    public function __construct($title = '', $pluralTitle = '', $description = '')
    {
        $this->title = $title;
        $this->pluralTitle = $pluralTitle;
        $this->description = $description;
        $this->keywords = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getPluralTitle()
    {
        return $this->pluralTitle;
    }

    /**
     *
     * @param string $pluralTitle
     */
    public function setPluralTitle($pluralTitle)
    {
        $this->pluralTitle = $pluralTitle;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function __toString()
    {
        return $this->title;
    }

    /**
     * Setter for keywords
     *
     * @param  Collection $keywords
     * @return void
     */
    public function setKeywords(Collection $keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * Adds a keyword
     *
     * @param  TypeKeyword $keyword
     * @return void
     */
    public function addKeyword(TypeKeyword $keyword)
    {
        $this->keywords->add($keyword);
    }

    /**
     * removes a keyword
     *
     * @param  TypeKeyword $keyword
     * @return void
     */
    public function removeKeyword(TypeKeyword $keyword)
    {
        $this->keywords->removeElement($keyword);
    }

    /**
     * Getter for type keywords
     *
     * @return Collection
     */
    public function getKeywords()
    {
        return clone $this->keywords;
    }

    /**
     * Getter for type keywords as an plain array
     *
     * @return array
     */
    public function getKeywordArray()
    {
        $keywords = array();
        foreach ($this->keywords as $keyword) {
            /** @var $keyword TypeKeyword */
            $keywords[] = mb_strtolower($keyword->getKeyword(), 'utf-8');
        }

        return $keywords;
    }

    /**
     *
     * @param  array $keywordLookup
     * @return float
     */
    public function score(array $keywordLookup)
    {
        $score = 0;
        foreach ($this->getKeywordArray() as $keyword) {
            if (isset($keywordLookup[$keyword])) {
                $score++;
            }
        }

        return $score;
    }

    /**
     * Helper function to remove empty keywords
     */
    public function removeEmptyKeywords()
    {
        foreach ($this->keywords as $key => $typeKeyword) {
            /** @var $typeKeyword TypeKeyword */
            if (trim($typeKeyword->getKeyword()) === '') {
                $this->keywords->remove($key);
            }
        }
    }

    public function removeEmptyRelations()
    {
        $this->removeEmptyKeywords();
    }

}
