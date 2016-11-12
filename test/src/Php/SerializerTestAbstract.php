<?php

namespace zaboy\test\utils\Php;

use zaboy\utils\Json\Exception as JsonException;
use zaboy\utils\Php\Serializer as PhpSerializer;

abstract class SerializerTestAbstract extends \PHPUnit_Framework_TestCase
{

    public function provider_ScalarType()
    {
        return array(
            array(false),
            array(true),
            //
            array(-30001),
            array(-1),
            array(0),
            array(1),
            array(30001),
            //
            array(-30001.00001),
            array(0.0),
            array(30001.00001)
        );
    }

    public function provider_StringType()
    {
        return array(
            //
            array('-30001'),
            array('0'),
            array('30001.0001'),
            //
            array(
                'String строка !"№;%:?*(ХхЁ'
            )
        );
    }

    public function provider_ArrayType()
    {
        return array(
            //
            array(
                []
            ),
            array(
                [1, 'a', ['array']],
            ),
            array(
                ['one' => 1, 'a', 'next' => ['array']],
            )
        );
    }

    public function provider_ObjectType()
    {
        return array(
            array(
                (object) []  // new \stdClass();
            ),
            array(
                (object) ['prop' => 1]  //$stdClass = new \stdClass(); $stdClass->prop = 1
            ),
            array(
                new \Exception('Exception', 1, null)
            ),
            array(
                new JsonException('Exception', 1, new \Exception('subException', 1))
            ),
        );
    }

    public function provider_ClosureType()
    {
        $obj = new \stdClass();
        return array(
            array(
                function ($val) use($obj) {
                    $obj->prop = $val;
                    return $obj;
                }
                , ''
            )
        );
    }

    public function provider_ResourceType()
    {
        return array(
            array(
                imagecreate(1, 1)
            )
        );
    }

    //==========================================================================
    public function serialize($value)
    {

        $this->assertEquals(
                $value, PhpSerializer::phpUnserialize(PhpSerializer::phpSerialize($value))
        );
    }

}
