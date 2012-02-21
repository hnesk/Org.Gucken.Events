<?php
namespace Util\Reflection;
/**
 * Description of Reflector
 *
 * @author jk
 */
class Object extends \ReflectionClass {
    /**
     *
     * @var mixed
     */
    protected $object;

    public function __construct($object) {
        if (!\is_object($object)) {
            throw new \InvalidArgumentException('Passed object is not an object');
        }
        $this->object = $object;
        parent::__construct($object);
    }
    
    public function getAutocompleteMethods() {
        $phpMethods = parent::getMethods();
        $methods = array();
        foreach ($phpMethods as $method) {
            if (!$method->isAbstract() && !$method->isStatic() && !$method->isConstructor() && !$method->isDeprecated() && !$method->isDestructor()) {
                if (strpos($method->getDocComment(),'@autocomplete') !== false) {
                    $methods[] = new Method($this->object,$method->getName());
                }
            }
        }
        return $methods;
    }
}
?>
