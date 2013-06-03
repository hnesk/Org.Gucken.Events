<?php
namespace Org\Gucken\Events\Domain\Model;

/*                                                                        *
 * This script belongs to the Flow package "Org.Gucken.Events".           *
 *                                                                        *
 *                                                                        */
use Doctrine\ORM\Mapping as ORM,
	TYPO3\Flow\Annotations as Flow;

/**
 * A Import history
 *
 * @Flow\Scope("prototype")
 * @Flow\Entity
 */
class ImportLogEntry {

	/**
	 * The source
	 * @var \Org\Gucken\Events\Domain\Model\EventSource
	 * @ORM\ManyToOne
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $source;

	/**
	 * The start date
	 * @var \DateTime
	 */
	protected $startTime;
	
	/**
	 * The end date
	 * @var \DateTime
	 */
	protected $endTime;
	

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
	 * @var array
	 */
	protected $messages;
	
	/**
	 * The errors
	 * @var array
	 */
	protected $errors;
	

	public function __construct(EventSource $source, \DateTime $startTime = null, \DateTime $endTime = null, $imported = 0, $duplicates = 0, $messages = array(), $errors = array()) {
		$this->setSource($source);
		$this->setStartTime($startTime ? $startTime : new \DateTime());
		$this->setEndTime($endTime);
		$this->setImportCount($imported);
		$this->setDuplicateCount($duplicates);
		$this->setMessages($messages);
		$this->setErrors($errors);
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
	public function getStartTime() {
		return $this->startTime;
	}

	/**
	 * Sets this Import history's date
	 *
	 * @param \DateTime $date The Import history's date
	 * @return void
	 */
	public function setStartTime($startTime) {
		$this->startTime = $startTime;
	}
	
	/**
	 * Get the Import history's end date
	 *
	 * @return \DateTime The Import history's end date
	 */
	public function getEndTime() {
		return $this->endTime;
	}

	/**
	 * Sets this Import history's end date
	 *
	 * @param \DateTime $date The Import history's end date
	 * @return void
	 */
	public function setEndTime($endTime) {
		$this->endTime = $endTime;
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
	 * Increment this Import history's number of imported items
	 *
	 * @param integer $by increment
	 * @return void
	 */
	public function incrementImportCount($by = 1) {
		$this->importCount += $by;
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
	 * Increment this Import history's number of duplicate items
	 *
	 * @param integer $by increment
	 * @return void
	 */
	public function incrementDuplicateCount($by = 1) {
		$this->duplicateCount += $by;
	}	
	

	/**
	 * Get the Import history's messages
	 *
	 * @return array The Import history's messages
	 */
	public function getMessages() {
		return $this->messages;
	}

	/**
	 * Sets this Import history's messages
	 *
	 * @param array $messages The Import history's messages
	 * @return void
	 */
	public function setMessages(array $messages) {
		$this->messages = $messages;
	}

	/**
	 *
	 * @param type $message 
	 */
	public function addMessage($message) {
		$this->messages[] = $message;
	}
	
	/**
	 * Get the Import history's errors
	 *
	 * @return array The Import history's errors
	 */
	public function getErrors() {
		return $this->errors;
	}

	/**
	 * Sets this Import history's errors
	 *
	 * @param array $messages The Import history's errors
	 * @return void
	 */
	public function setErrors(array $errors) {
		$this->errors = $errors;
	}

	/**
	 *
	 * @param string $error
	 */
	public function addError($error) {
		$this->errors[] = $error;
	}
	
}
?>