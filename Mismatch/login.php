<?php

require_once 'config.php';

session_start();

try {
    login();
    echo 'Welcoome';
} catch (Exception $e) {
    echo 'System error'
} catch (InvalidCredentialsException $e) {
    $message = '401 Not authorized';
    http_response_code(401);
    echo $message;
} catch (Exception $e){
    echo 'System error'
}

function login() {

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

$statement = $db->prepare('
    SELECT username FROM user
    WHERE username = ?
    AND password = SHA(?)');       

$statement->bind_param('ss', $_POST['uname'], $_POST['psw']);

$statement->execute();

$statement->bind_result($username);
$statement->fetch();

if (empty($username)) {
    throw new InvalidCredentialsException('Invalid credentials');
}
}