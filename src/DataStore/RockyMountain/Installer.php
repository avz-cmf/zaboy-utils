<?php

/**
 * Zaboy lib (http://zaboy.org/lib/)
 *
 * @copyright  Zaboychenko Andrey
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace zaboy\utils\DataStore\RockyMountain;

use Zend\Db\Adapter\AdapterInterface;
use zaboy\rest\TableGateway\TableManagerMysql as TableManager;
use zaboy\res\Di\InsideConstruct;
use zaboy\utils\DataStore\RockyMountain\GiftCards;
use zaboy\utils\DataStore\RockyMountain\Orders;

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
    private $rockyMountainDbAdapter;

    public function __construct($rockyMountainDbAdapter = null)
    {
        //set $this->emailDbAdapter as $cotainer->get('emailDbAdapter');
        InsideConstruct::initServices();
    }

    public function install($dataStores)
    {
        $tableManager = new TableManager($this->rockyMountainDbAdapter);
        if (in_array(GiftCards::TABLE_NAME, $dataStores)) {
            $tableConfig = GiftCards::getTableConfig();
            $tableName = GiftCards::TABLE_NAME;
            $tableManager->createTable($tableName, $tableConfig);
        }
        if (in_array(Orders::TABLE_NAME, $dataStores)) {
            $tableConfig = Orders::getTableConfig();
            $tableName = Orders::TABLE_NAME;
            $tableManager->createTable($tableName, $tableConfig);
        }
    }

}
