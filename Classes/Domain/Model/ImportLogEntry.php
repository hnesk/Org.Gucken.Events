<?php
namespace Org\Gucken\Events\Domain\Model;

/*                                                                        *
 * This script belongs to the FLOW3 package "Org.Gucken.Events".          *
 *                                                                        *
 *                                                                        */
use Doctrine\ORM\Mapping as ORM,
	TYPO3\FLOW3\Annotations as FLOW3;

/**
 * A Import history
 *
 * @FLOW3\Scope("prototype")
 * @FLOW3\Entity
 */
class ImportLogEntry {

	/**
	 * The source
	 * @var \Org\Gucken\Events\Domain\Model\EventSource
	 * @ORM\ManyToOne
	 */
	protected $source;

	/**
	 * The date
	 * @var \DateTime
	 */
	protected $date;

	/**
	 * The number of imported items
	 * @var integer
	 */
	protected $importCount;
	
	/**
	 * The number of duplicate items
	 * @var integer
	 */
	protected $duplicateCount;
	

	/**
	 * The messages
	 * @var string
	 */
	protected $messages;


	public function __construct(EventSource $source, \DateTime $date = null, $imported = 0, $duplicates = 0, $messages = '') {
		$this->setSource($source);
		$this->setDate($date ? $date : new \DateTime());
		$this->setImportCount($imported);
		$this->setDuplicateCount($duplicates);
		$this->setMessages($messages);
	}
	
	/**
	 * Get the Import history's source
	 *
	 * @return \Org\Gucken\Events\Domain\Model\EventSource The Import history's source
	 */
	public function getSource() {
		return $this->source;
	}

	/**
	 * Sets this Import history's source
	 *
	 * @param \Org\Gucken\Events\Domain\Model\EventSource $source The Import history's source
	 * @return void
	 */
	public function setSource($source) {
		$this->source = $source;
	}

	/**
	 * Get the Import history's date
	 *
	 * @return \DateTime The Import history's date
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * Sets this Import history's date
	 *
	 * @param \DateTime $date The Import history's date
	 * @return void
	 */
	public function setDate($date) {
		$this->date = $date;
	}

	/**
	 * Get the Import history's number of imported items
	 *
	 * @return integer The Import history's number of imported items
	 */
	public function getImportCount() {
		return $this->importCount;
	}

	/**
	 * Sets this Import history's number of imported items
	 *
	 * @param integer $number The Import history's number of imported items
	 * @return void
	 */
	public function setImportCount($importCount) {
		$this->importCount = $importCount;
	}
	
	/**
	 * Get the Import history's number of duplicated items
	 *
	 * @return integer The Import history's number of duplicated items
	 */
	public function getDuplicateCount() {
		return $this->duplicateCount;
	}

	/**
	 * Sets this Import history's number of duplicated items
	 *
	 * @param integer $number The Import history's number of duplicated items
	 * @return void
	 */
	public function setDuplicateCount($duplicateCount) {
		$this->duplicateCount = $duplicateCount;
	}
	

	/**
	 * Get the Import history's messages
	 *
	 * @return string The Import history's messages
	 */
	public function getMessages() {
		return $this->messages;
	}

	/**
	 * Sets this Import history's messages
	 *
	 * @param string $messages The Import history's messages
	 * @return void
	 */
	public function setMessages($messages) {
		$this->messages = $messages;
	}

}
?>