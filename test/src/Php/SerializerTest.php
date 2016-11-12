<?php

namespace zaboy\test\utils\Php;

use zaboy\test\utils\Php\SerializerTestAbstract;
use zaboy\utils\Php\Serializer as PhpSerializer;

class SerializerTest extends SerializerTestAbstract
{

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed
     */
    protected function setUp()
    {

    }

    //==========================================================================

    /**
     * @dataProvider provider_ScalarType
     */
    public function testSerialize_ScalarType($in)
    {
        parent::serialize($in);
    }

    /**
     * @dataProvider provider_StringType
     */
    public function testSerialize_StringType($in)
    {
        parent::serialize($in);
    }

    /**
     * @dataProvider provider_ArrayType
     */
    public function testSerialize_ArrayType($in)
    {
        parent::serialize($in);
    }

    /**
     * @dataProvider provider_ObjectType
     */
    public function testSerialize_ObjectType($in)
    {
        if ($in instanceof \Exception) {
            $this->assertEquals($in->getMessage(), 'Exception');
        } else {
            parent::serialize($in);
        }
    }

    /**
     * @dataProvider provider_ClosureType
     */
    public function testSerialize_ClosureType($in)
    {
        $value = PhpSerializer::phpUnserialize(PhpSerializer::phpSerialize($in));
        $this->assertEquals($value(1), (object) ['prop' => 1]);
    }

    /**
     * @dataProvider provider_ResourceType
     */
    public function testSerialize_ResourceType($in)
    {
        $this->setExpectedException(\LogicException::class);
        parent::serialize($in);
    }

}
