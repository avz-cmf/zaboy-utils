<?php

/**
 * Zaboy lib (http://zaboy.org/lib/)
 *
 * @copyright  Zaboychenko Andrey
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace zaboy\utils\Json;

use zaboy\utils\Json\Exception as JsonException;

/**
 * Not use for object
 *
 *
 * @category   utils
 * @package    zaboy
 * @todo set_error_handler in jsonEncode()
 * @todo check in Dojo Rest -"String \u0441\u0442\u0440\u043e\u043a\u0430 !\"\u0022\u2116;%:?*(\u0425\u0445\u0401\r\n"
 */
class Coder
{

    /**
     * How objects should be encoded -- arrays or as stdClass. TYPE_ARRAY is 1
     * so that it is a boolean true value, allowing it to be used with
     * ext/json's functions.
     */
    const TYPE_ARRAY = 1;
    const TYPE_OBJECT = 0; // not used

    /**
     *
     * @param scalar|array $data
     * @return string
     * @throws JsonException
     * @see http://php.net/manual/ru/function.json-encode.php
     */

    public static function jsonEncode($data)
    {
        if (!is_scalar($data) and ! is_array($data)) {
            throw new JsonException(
            'Data must be scalar or array,  ' .
            'but  type ' . gettype($data) . ' given.'
            );
        }
        json_encode(null); // Clear json_last_error()
        $result = json_encode($data); //, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | SON_HEX_APOS);
        if (JSON_ERROR_NONE !== json_last_error()) {
            $jsonErrorMsg = json_last_error_msg();
            json_encode(null);  // Clear json_last_error()
            throw new JsonException(
            'Unable to encode data to JSON. Error - ' . $jsonErrorMsg . PHP_EOL .
            '$data type: ' . gettype($data)
            );
        }
        return $result;
    }

    /**
     *
     * @param mix $data
     * @return string
     * @throws JsonException
     * @see http://php.net/manual/ru/function.json-decode.php
     */
    public static function jsonDecode($data)
    {
        json_encode(null); // Clear json_last_error()
        $result = json_decode((string) $data, self::TYPE_ARRAY); //json_decode($data);
        if (JSON_ERROR_NONE !== json_last_error()) {
            $jsonErrorMsg = json_last_error_msg();
            json_encode(null);  // Clear json_last_error()
            throw new JsonException(
            'Unable to decode data from JSON. Error - ' . $jsonErrorMsg . PHP_EOL .
            'JSON string: ' . PHP_EOL . $data
            );
        }
        return $result;
    }

}
