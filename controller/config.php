<?php

spl_autoload_register(function ($name) {
    echo "Want to load $name.\n";
    throw new Exception("Unable to load $name.");
});

try {
    $mapper = \asc\MySQL_DataMapper::getInstance();
    $stuff = $mapper->fetchUserByFname('Alexandre');

    var_dump($stuff);
} catch (Exception $e) {
    echo $e->getMessage(), "\n";
}
