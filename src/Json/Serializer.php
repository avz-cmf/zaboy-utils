<?php

/**
 * Zaboy lib (http://zaboy.org/lib/)
 *
 * @copyright  Zaboychenko Andrey
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace zaboy\utils\Json;

use mindplay\jsonfreeze\JsonSerializer;
use zaboy\utils\Json\Plugin\ExceptionSerializer;
use zaboy\utils\Json\Exception as JsonException;

/**
 *
 *
 * @category   utils
 * @package    zaboy
 * @todo set_error_handler in jsonEncode()
 */
class Serializer
{

    public static function jsonSerialize($value)
    {
        if (is_resource($value) || $value instanceof \Closure) {
            $class = is_object($value) ? ' with class ' . get_class($value) : '';
            throw new JsonException(
            'Data must be scalar or array or object,  ' .
            'but  type ' . gettype($value) . $class . ' given.'
            );
        }
        $serializer = new JsonSerializer();
        ExceptionSerializer::defineExceptionSerializer($value, $serializer);
        $serializedValue = $serializer->serialize($value);
        return $serializedValue;
    }

    public static function jsonUnserialize($serializedValue)
    {
        $serializer = new JsonSerializer();
        ExceptionSerializer::defineExceptionUnserializer($serializedValue, $serializer);
        $value = $serializer->unserialize($serializedValue);
        return $value;
    }

}
