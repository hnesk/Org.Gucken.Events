<?php
namespace Util\Reflection;
/**
 * Description of Reflector
 *
 * @author jk
 */
class Method extends \ReflectionMethod {
    /**
     *
     * @var mixed
     */
    protected $object;

    public function __construct($object, $name) {
        if (!\is_object($object)) {
            throw new \InvalidArgumentException('Passed object is not an object');
        }
        $this->object = $object;
        parent::__construct($object, $name);
    }


}
?>
