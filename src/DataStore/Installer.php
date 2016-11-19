<?php

/**
 * Zaboy lib (http://zaboy.org/lib/)
 *
 * @copyright  Zaboychenko Andrey
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace zaboy\utils\DataStore;

use Zend\Db\Adapter\AdapterInterface;
use zaboy\rest\TableGateway\TableManagerMysql as TableManager;
use zaboy\res\Di\InsideConstruct;
use zaboy\utils\DataStore\Email;

/**
 * Installer class
 *
 * @category   Zaboy
 * @package    zaboy
 */
class Installer
{

    /**
     *
     * @var AdapterInterface
     */
    private $emailDbAdapter;

    public function __construct(AdapterInterface $emailDbAdapter = null)
    {
        //set $this->emailDbAdapter as $cotainer->get('emailDbAdapter');
        InsideConstruct::initServices();
    }

    public function install()
    {
        $tableManager = new TableManager($this->emailDbAdapter);
        $tableConfig = $this->getTableConfig();
        $tableName = Email::TABLE_NAME;
        $tableManager->createTable($tableName, $tableConfig);
    }

    protected function getTableConfig()
    {
        return [
            Email::MESSAGE_ID => [
                'field_type' => 'Varchar',
                'field_params' => [
                    'length' => 32,
                    'nullable' => false
                ]
            ],
            Email::SUBJECT => [
                'field_type' => 'Varchar',
                'field_params' => [
                    'length' => 4094,
                    'nullable' => true
                ]
            ],
            Email::SENDING_TIME => [
                'field_type' => 'Integer',
                'field_params' => [
                    'nullable' => true
                ]
            ],
            Email::BODY_HTML => [
                'field_type' => 'Varchar',
                'field_params' => [
                    'length' => 65000,
                    'nullable' => true
                ]
            ],
            Email::BODY_TXT => [
                'field_type' => 'Varchar',
                'field_params' => [
                    'length' => 65000,
                    'nullable' => true
                ]
            ],
            Email::HEADERS => [
                'field_type' => 'Varchar',
                'field_params' => [
                    'length' => 65000,
                    'nullable' => true
                ]
            ],
            Email::STATUS => [
                'field_type' => 'Varchar',
                'field_params' => [
                    'length' => 16,
                    'nullable' => false
                ]
            ],
        ];
    }

}
