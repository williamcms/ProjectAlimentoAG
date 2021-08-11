<?php
require './users_class.php';

$account = new _account();

try{
    //(username, name, email, password, permissions)
    $newId = $account->addUser('myNewName2', 'MyNewRealname', 'MyNewEmail', 'myPassword', '1');
}
catch (Exception $e){
    echo $e->getMessage();
    die();
}

echo 'The new account has been created';
?>