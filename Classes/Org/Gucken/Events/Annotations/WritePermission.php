<?php
namespace Org\Gucken\Events\Annotations;


use Doctrine\Common\Annotations\Annotation as DoctrineAnnotation;

/**
 * @Annotation
 * @DoctrineAnnotation\Target("METHOD")
 */
final class WritePermission{}

?>