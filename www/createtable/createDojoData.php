<?php
    $tableName = 'djo_data';
    $adapter = $container->get('db');
    $quoteTableName = $adapter->platform->quoteIdentifier($tableName);

    $deleteStatementStr = "DROP TABLE IF EXISTS " .  $quoteTableName;
    $adapter->query($deleteStatementStr, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

    $createStr = 
        "CREATE TABLE IF NOT EXISTS "  .
        $quoteTableName .
        ' (' .    
            ' id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ' .
              ' floatNum DOUBLE,  ' .
              ' integerr INT, ' .
            ' text CHAR(40), ' .
            ' date2 DATE, ' .
             ' bool BOOL ' .          
        ' ) ' .    
        'ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;'
    ;    
    $adapter->query($createStr, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

    $insertStr = "INSERT INTO $quoteTableName (id, floatNum, integerr,text, date2, bool) VALUES (1, 1.1, 1, 'Monday', 2000-01-01 ,TRUE)";
    $adapter->query($insertStr, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    $insertStr = "INSERT INTO $quoteTableName (id, floatNum, integerr,text, date2, bool) VALUES (2, 2.2, 2, 'Thursday', 2000-02-02 ,FALSE)";
    $adapter->query($insertStr, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    $insertStr = "INSERT INTO $quoteTableName (id, floatNum, integerr,text, date2, bool) VALUES (3, 3.3, 3, 'Thursday', 2000-03-02 ,FALSE)";
    $adapter->query($insertStr, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
  