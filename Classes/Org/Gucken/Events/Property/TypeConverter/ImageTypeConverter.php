<?php
namespace Org\Gucken\Events\Property\TypeConverter;


/*                                                                        *
 * This script orginally belongs to the Flow package  "TYPO3.Neos"        *
 * and has be adapted for Org.Gucken.Events.                              *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Property\Exception\TypeConverterException;
use TYPO3\Flow\Property\PropertyMappingConfigurationInterface;
use TYPO3\Flow\Property\TypeConverter\AbstractTypeConverter;
use TYPO3\Flow\Resource\ResourceManager;
use TYPO3\Media\Domain\Model\Image;

/**
 * An type converter for Image objects; which are uploaded using plupload
 *
 * @Flow\Scope("singleton")
 */
class ImageTypeConverter extends AbstractTypeConverter {

	/**
	 * @var array<string>
	 */
	protected $sourceTypes = array('array');

	/**
	 * @var string
	 */
	protected $targetType = Image::class;

	/**
	 * @var integer
	 */
	protected $priority = 2;

	/**
	 * @Flow\Inject
	 * @var ResourceManager
	 */
	protected $resourceManager;

	/**
	 * We only convert stuff being uploaded using plupload.
	 *
	 * @param mixed $source the source data
	 * @param string $targetType the type to convert to.
	 * @return boolean TRUE if this TypeConverter can convert from $source to $targetType, FALSE otherwise.
	 * @api
	 */
	public function canConvertFrom($source, $targetType) {
		return (isset($source['type']) && $source['type'] === 'plupload');
	}

    /**
     * Converts the given string or array to a ResourcePointer object.
     *
     * If the input format is an array, this method assumes the resource to be a
     * fresh file upload and imports the temporary upload file through the
     * resource manager.
     *
     * @param array $source The upload info (expected keys: error, name, tmp_name)
     * @param string $targetType
     * @param array $convertedChildProperties
     * @param PropertyMappingConfigurationInterface $configuration
     * @throws TypeConverterException
     * @return Image An object or an instance of TYPO3\Flow\Error\Error if the input format is not supported or could not be converted for other reasons
     */
	public function convertFrom($source, $targetType, array $convertedChildProperties = array(), PropertyMappingConfigurationInterface $configuration = NULL) {
		$resource = $this->resourceManager->importUploadedResource($_FILES['file']);
		if ($resource === FALSE) {
			throw new TypeConverterException('Resource could not be converted.', 1316428994);
		}
		$image = new Image($resource);
		$image->setTitle(''); // TODO: this should maybe be settable
		return $image;
	}
}

?>