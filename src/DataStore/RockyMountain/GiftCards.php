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
class GiftCards extends DbTable
{

    const TABLE_NAME = 'gift_cards';
    //
    const CARD_ID = 'id';
    const VALUE = 'value';
    const RECEIVED_TIME = 'received_time'; //GMT in sec
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
            static ::CARD_ID => [
                'field_type' => 'Varchar',
                'field_params' => [
                    'length' => 18,
                    'nullable' => false
                ]
            ],
            static::VALUE => [
                'field_type' => 'Integer',
                'field_params' => [
                    'nullable' => true
                ]
            ],
            static::RECEIVED_TIME => [
                'field_type' => 'Integer',
                'field_params' => [
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
