<?php

namespace zaboy\test\utils\Json;

use zaboy\utils\Json\Exception as JsonException;

abstract class SerializerTestAbstract extends \PHPUnit_Framework_TestCase
{

    /**
     * @var calable
     */
    protected $encoder;

    /**
     * @var calable
     */
    protected $decoder;

    //==========================================================================
    public function provider_ScalarType()
    {
        return array(
            array(false, 'false'),
            array(true, 'true'),
            //
            array(-30001, '-30001'),
            array(-1, '-1'),
            array(0, '0'),
            array(1, '1'),
            array(30001, '30001'),
            //
            array(-30001.00001, '-30001.00001'),
            array(0.0, '0', 0), //we get 0 - not 0.0
            array(30001.00001, '30001.00001')
        );
    }

    public function provider_StringType()
    {
        return array(
            //
            array('-30001', '"-30001"'),
            array('0', '"0"'),
            array('30001.0001', '"30001.0001"'),
            //
            array(
                'String строка !"№;%:?*(ХхЁ',
                //if use json_encode($data);
                '"String \u0441\u0442\u0440\u043e\u043a\u0430 !\"\u2116;%:?*(\u0425\u0445\u0401"'
            //if use json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | SON_HEX_APOS);
//              '"String \u0441\u0442\u0440\u043e\u043a\u0430 !\u0022\u2116;%:?*(\u0425\u0445\u0401\r\n"' - if Json\Coder
            )
        );
    }

    public function provider_ArrayType()
    {
        return array(
            //
            array(
                [],
                '[]'
            ),
            array(
                [1, 'a', ['array']],
                '[1,"a",["array"]]'
            ),
            array(
                ['one' => 1, 'a', 'next' => ['array']],
                '{"one":1,"0":"a","next":["array"]}'
            )
        );
    }

    public function provider_ObjectType()
    {
        return array(
            array(
                (object) []  // new \stdClass();
                , '{"#type":"stdClass"}'
            ),
            array(
                (object) ['prop' => 1]  //$stdClass = new \stdClass(); $stdClass->prop = 1
                , '{"prop":1,"#type":"stdClass"}'
            ),
            array(
                new \Exception('Exception', 1)
                , null
            ),
            array(
                new JsonException('JsonException', 1, new \Exception('Exception', 1))
                , null
            ),
        );
    }

    public function provider_ClosureType()
    {
        return array(
            array(
                function ($val) {
                    return $val;
                }
                , ''
            )
        );
    }

    public function provider_ResourceType()
    {
        return array(
            array(
                imagecreate(1, 1), ''
            )
        );
    }

    //==========================================================================
    public function serialize($value, $expectedJsonString, $expectedValue = null)
    {
        $expectedValue = !is_null($expectedValue) ? $expectedValue : $value; //usialy $expectedValue === $value
        $callableEncoder = $this->encoder;
        $callableDecoder = $this->decoder;
        $jsonString = $callableEncoder($value);
        $decodedValue = $callableDecoder($jsonString);
        if ($expectedJsonString !== null) {
            $jsonStringCopressed = str_replace(chr(13), '', str_replace(chr(10), '', str_replace(' ', '', $jsonString)));
            $expectedJsonStringCopressed = str_replace(chr(13), '', str_replace(chr(10), '', str_replace(' ', '', $expectedJsonString)));
            $this->assertSame(
                    $expectedJsonStringCopressed, $jsonStringCopressed
            );
        }
        $this->assertEquals(
                $expectedValue, $decodedValue
        );
    }

}
