<?php
namespace Org\Gucken\Events\Domain\Model;

/*                                                                        *
 * This script belongs to the FLOW3 package "Events".                     *
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

use Org\Gucken\Events\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * An Event
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @FLOW3\Scope("prototype")
 * @FLOW3\Entity
 */
class Image {

	/**
	 * The title
	 * @var string
	 */
	protected $title;

	/**
	 * The oringal image
	 * @var \TYPO3\FLOW3\Resource\Resource
	 * @ORM\OneToOne(cascade={"all"}, orphanRemoval=true)
	 */
	protected $original;

	/**
	 * a thumb
	 * @var \TYPO3\FLOW3\Resource\Resource
	 * @ORM\OneToOne(cascade={"all"}, orphanRemoval=true)
	 */
	protected $thumb;


	/**
	 * Get the image title
	 *
	 * @return string The image title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Sets this images title
	 *
	 * @param string $title The image title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 *
	 * @return \TYPO3\FLOW3\Resource\Resource
	 */
	public function getOriginal() {
		return $this->original;
	}

	/**
	 *
	 * @param \TYPO3\FLOW3\Resource\Resource $original
	 */
	public function setOriginal(\TYPO3\FLOW3\Resource\Resource $original) {
		$this->original = $original;
	}

	/**
	 *
	 * @return \TYPO3\FLOW3\Resource\Resource
	 */
	public function getThumb() {
		return $this->thumb;
	}

	/**
	 *
	 * @param \TYPO3\FLOW3\Resource\Resource $thumb
	 */
	public function setThumb(\TYPO3\FLOW3\Resource\Resource $thumb) {
		$this->thumb = $thumb;
	}

    /**
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->original->getFileName();
	}
}

?>