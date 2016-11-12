<?php

/**
 * Zaboy lib (http://zaboy.org/lib/)
 *
 * @copyright  Zaboychenko Andrey
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace zaboy\utils\Json\Plugin;

use mindplay\jsonfreeze\JsonSerializer;
use zaboy\utils\Json\Exception as JsonException;

/**
 *
 *
 * @category   utils
 * @package    zaboy
 */
class ExceptionSerializer
{

    public static function exceptionSerialize(\Exception $exception)
    {
        $data = array(
            JsonSerializer::TYPE => get_class($exception),
            "message" => $exception->getMessage(),
            "code" => $exception->getCode(),
            "line" => $exception->getLine(),
            "file" => $exception->getFile(),
            "prev" => $exception->getPrevious(),
        );
        return $data;
    }

    public static function exceptionUnserialize($data)
    {

        $prev = isset($data["prev"]) ? static::exceptionUnserialize($data["prev"]) : null;

        try {
            $exc = new $data[JsonSerializer::TYPE]($data["message"], $data["code"], $prev);
        } catch (\Exception $exc) {
            $exc = new \RuntimeException('Can not Unserialize Exception '
                    . $data[JsonSerializer::TYPE], 0, $exc
            );
        }

        $data['trace'] = [];
        $propArray = ['line', 'file', 'trace'];
        $refClassExc = new \ReflectionClass(\Exception::class);
        foreach ($propArray as $propName) {
            $refProperty = $refClassExc->getProperty($propName);
            $refProperty->setAccessible(true);
            $refProperty->setValue($exc, $data[$refProperty->getName()]);
            $refProperty->setAccessible(false);
        }
        return $exc;
    }

    public static function defineExceptionSerializer($value, $serializer)
    {
        $objectClasses = static::getClassesFromObject($value)['class'];
        self::define($objectClasses, $serializer);
    }

    public static function defineExceptionUnserializer($serializedValue, $serializer)
    {
        $objectClasses = static::getClassesFromString($serializedValue);
        self::define($objectClasses, $serializer);
    }

    protected static function define($objectClasses, $serializer)
    {
        foreach ($objectClasses as $className) {

            if (is_a($className, 'Exception', true)) {
                $serializer->defineSerialization(
                        $className
                        , [get_class(), 'exceptionSerialize']
                        , [get_class(), 'exceptionUnserialize']
                );
            }
        }
    }

    /**
     * Extract types of serialized objects
     *
     * in:
     * <code>
     * '[1,{ "#type": "Exception", "message": "Exception",  "string": "",  "code": 404,  "previous":
     * {"#type": "zaboy\\utils\\Json\\Exception", "message": "JsonException"}},"a",{"#type": "stdClass"}]'
     * </code>
     *
     * out:
     * <code>
     * out:['Exception', 'zaboy\utils\Json\Exception', 'stdClass']
     * </code>
     *
     *
     * @param string  $subject
     * @return array
     */
    protected static function getClassesFromString($subject)
    {
        $pattern = '/"#type": "([\w\x5c]+)"/';
        $match = array();
        $types = array();

        if (preg_match_all($pattern, $subject, $match)) {
            if (count($match) > 1) {
                foreach ($match[1] as $type) {
                    $types[] = preg_replace('|([\x5c]+)|s', '\\', $type);
                }
            }
        }

        return $types;
    }

    protected static function getClassesFromObject($subject, $typesAndObjects = ['class' => [], 'objects' => []])
    {
        if (is_scalar($subject) || is_resource($subject) || empty($subject) || $subject instanceof \Closure) {
            return $typesAndObjects;
        }

        if (is_array($subject)) {
            foreach ($subject as $value) {
                $typesAndObjects = static::getClassesFromObject($value, $typesAndObjects); //Recursion
            }
            return $typesAndObjects;
        }

        if (is_object($subject)) {
            //We are looking circular references
            foreach ($typesAndObjects['objects'] as $value) {
                if ($value === $subject) {
                    return $typesAndObjects;
                }
            }
            $typesAndObjects['objects'][] = $subject;
            //We collect unique class names
            if (!in_array(get_class($subject), $typesAndObjects['class'])) {
                $typesAndObjects['class'][] = get_class($subject);
            }
            $propsArray = static::getClassProperties($subject);
            //Recursion
            return static::getClassesFromObject($propsArray, $typesAndObjects);
        }

        throw new JsonException('Unknown type');
    }

    /**
     * Recursive function to get an associative array of class properties by property name => value
     * including inherited from extended classes
     *
     * @param object $object
     * @param string $className
     * @return array [$propName1 =>$propVal1, $propName2=> ...]
     */
    protected static function getClassProperties($object, $className = null)
    {
        $className = $className ? $className : get_class($object);
        $ref = new \ReflectionClass($className);
        $props = $ref->getProperties();
        $props_arr = array();
        foreach ($props as $prop) {
            $propName = $prop->getName();

            if ($prop->isPublic()) {
                $props_arr[$propName] = $prop->getValue($object);
            }
            if ($prop->isPrivate() || $prop->isProtected()) {
                $prop->setAccessible(true);
                $props_arr[$propName] = $prop->getValue($object);
                $prop->setAccessible(false);
            }
            continue;
        }
        $parentClass = $ref->getParentClass();
        if ($parentClass) {
            $parent_props_arr = self::getClassProperties($object, $parentClass->getName()); //RECURSION
            if (count($parent_props_arr) > 0) {
                $props_arr = array_merge($parent_props_arr, $props_arr);
            }
        }
        return $props_arr;
    }

}
