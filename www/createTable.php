<?php
    $adapter = $container->get('db');
    $quoteTableName = $adapter->platform->quoteIdentifier($tableName);

    $deleteStatementStr = "DROP TABLE IF EXISTS " .  $quoteTableName;
    $adapter->query($deleteStatementStr, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

    $createStr = 
        "CREATE TABLE IF NOT EXISTS "  .
        $quoteTableName .
        '(' .    
            ' id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ' .
            ' fWeekday CHAR(20), ' .
            ' fNumberOfHours INT ' .
        ' ) ' .    
        'ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;'
    ;    
    $adapter->query($createStr, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

    $insertStr = "INSERT INTO $quoteTableName (id, fWeekday, fNumberOfHours) VALUES (1, 'Monday', 8)";
    $adapter->query($insertStr, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    $insertStr = "INSERT INTO $quoteTableName (id, fWeekday, fNumberOfHours) VALUES (2, 'Tuesday', 8)";
    $adapter->query($insertStr, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    $insertStr = "INSERT INTO $quoteTableName (id, fWeekday, fNumberOfHours) VALUES (3, 'Wednesday', 8)";
    $adapter->query($insertStr, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    $insertStr = "INSERT INTO $quoteTableName (id, fWeekday, fNumberOfHours) VALUES (4, 'Monday', 6)";
    $adapter->query($insertStr, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);           
    $insertStr = "INSERT INTO $quoteTableName (id, fWeekday, fNumberOfHours) VALUES (5, 'Thursday', 6)";
    $adapter->query($insertStr, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    $insertStr = "INSERT INTO $quoteTableName (id, fWeekday, fNumberOfHours) VALUES (6, 'Friday', 6)";
    $adapter->query($insertStr, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    $insertStr = "INSERT INTO $quoteTableName (id, fWeekday, fNumberOfHours) VALUES (7, 'Monday', 8)";
    $adapter->query($insertStr, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    $insertStr = "INSERT INTO $quoteTableName (id, fWeekday, fNumberOfHours) VALUES (8, 'Tuesday', 4)";
    $adapter->query($insertStr, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
