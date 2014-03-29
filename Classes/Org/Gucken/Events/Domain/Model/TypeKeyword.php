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

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * An tag for a type 
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @Flow\Scope("prototype")
 * @Flow\ValueObject
 */
class TypeKeyword {

    /**
     * The keyword 
     * 
     * @var string
     * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=255 })     * 
     */
    protected $keyword;
    
    /**
     *
     * @var Type
     * @ORM\ManyToOne(inversedBy="keywords")
     */
    protected $type;
	
	/**
	 * This is only to allow non unqiue values, because Flows valueobject hashgeneration doesn't work yet
	 * TYPO3\Flow\Persistence\Aspect\PersistenceMagicAspect::generateValueHash
	 * @var string
	 */
	protected $typeId;

    /**
     *
     * @param string $keyword
     * @param Type $type
	 * @param string $typeId
     */
    public function __construct($keyword, Type $type, $typeId) {
        $this->keyword = $keyword;
        $this->type = $type;
		$this->typeId = $typeId;
    }


    /**
     * Get the keyword
     *
     * @return string 
     */
    public function getKeyword() {
        return $this->keyword;
    }
                
    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->keyword;
    }   
}

?>