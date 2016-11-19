<?php

namespace zaboy\utils\DataStore\RockyMountain;

use zaboy\rest\DataStore\DbTable;
use Zend\Db\TableGateway\TableGateway;
use zaboy\utils\Api\Gmail as ApiGmail;
use zaboy\utils\Json\Coder as JsonCoder;
use Zend\Db\Adapter\AdapterInterface;
use zaboy\res\Di\InsideConstruct;

/**
 *
 * time GMT
 */
class Orders extends DbTable
{

    const TABLE_NAME = 'orders';
    //
    const ORDER_ID = 'id';
    const DATA_TIME = 'data_time'; //GMT in sec
    const TOTAL_VALUE = 'total_value';
    const RM_CASH_VALUE = 'rm_cash_value';
    const PAYPAL_VALUE = 'paypal_value';
    const GIFT_VALUE = 'gift_value';
    const GIFT_USING = 'gift_using';
    const SHIP_DATE = 'ship_date';
    const SHIP_ADDRESS = 'ship_address';
    const PARTS = 'parts';
    const MESSAGE_ID = 'message_id';

    /**
     *
     * @var AdapterInterface
     */
    protected $rockyMountainDbAdapter;

    public function __construct($rockyMountainDbAdapter = null)
    {
        //set $this->rockyMountainDbAdapter as $cotainer->get('rockyMountainDbAdapter');
        InsideConstruct::initServices();

        $dbTable = new TableGateway(static::TABLE_NAME, $this->rockyMountainDbAdapter);
        parent::__construct($dbTable);
    }

    public static function getTableConfig()
    {
        return [
            static::ORDER_ID => [
                'field_type' => 'BigInteger',
                'field_params' => [
                    'nullable' => false
                ]
            ],
            static::DATA_TIME => [
                'field_type' => 'Integer',
                'field_params' => [
                    'nullable' => false
                ]
            ],
            static::TOTAL_VALUE => [
                'field_type' => 'Decimal',
                'field_params' => [
                    'nullable' => false,
                    'digits' => 6,
                    'decimal' => 2
                ]
            ],
            static::RM_CASH_VALUE => [
                'field_type' => 'Decimal',
                'field_params' => [
                    'nullable' => true,
                    'digits' => 5,
                    'decimal' => 2
                ]
            ],
            static::PAYPAL_VALUE => [
                'field_type' => 'Decimal',
                'field_params' => [
                    'nullable' => true,
                    'digits' => 5,
                    'decimal' => 2
                ]
            ],
            static::GIFT_VALUE => [
                'field_type' => 'Decimal',
                'field_params' => [
                    'nullable' => true,
                    'digits' => 5,
                    'decimal' => 2
                ]
            ],
            static::GIFT_USING => [
                'field_type' => 'Varchar',
                'field_params' => [
                    'length' => 1024,
                    'nullable' => true
                ]
            ],
            static::SHIP_DATE => [
                'field_type' => 'Integer',
                'field_params' => [
                    'nullable' => true
                ]
            ],
            static::SHIP_ADDRESS => [
                'field_type' => 'Varchar',
                'field_params' => [
                    'length' => 512,
                    'nullable' => true
                ]
            ],
            static::PARTS => [
                'field_type' => 'Varchar',
                'field_params' => [
                    'length' => 4096,
                    'nullable' => true
                ]
            ],
            static::MESSAGE_ID => [
                'field_type' => 'Varchar',
                'field_params' => [
                    'length' => 32,
                    'nullable' => true
                ]
            ],
        ];
    }

}
