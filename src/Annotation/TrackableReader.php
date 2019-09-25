<?php
/**
 * Created by PhpStorm.
 * User: nambinina2
 * Date: 25/09/2019
 * Time: 10:24
 */

namespace App\Annotation;


use Doctrine\Common\Annotations\AnnotationReader;

class TrackableReader
{
    private $reader;
    public function __construct(AnnotationReader $reader)
    {
        $this->reader = $reader;
    }

    public function isTrackable($entity): bool
    {
        $reflection = new \ReflectionClass(get_class($entity));
        return $this->reader->getClassAnnotation($reflection, TrackableClass::class) !== null;
    }
}