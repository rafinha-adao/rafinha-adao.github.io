<?php
include('db.php');
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        login($_GET['email'], $_GET['pass']);
        break;
    default:
        header('HTTP/1.0 405 Method Not Allowed');
        break;
}

function login($email, $pass)
{
    global $conn;
    $sql    = "SELECT * FROM users WHERE email = '$email' AND pass = '$pass'";
    $result = mysqli_query($conn, $sql);
    $user   = array();
    while ($row = mysqli_fetch_array($result)) {
        $user['id']         = $row['id'];
        $user['name']       = $row['name'];
        $user['tagName']    = $row['tagName'];
        $user['birthday']   = $row['birthday'];
        $user['bio']        = $row['bio'];
        $user['email']      = $row['email'];
        $user['idGroup']    = $row['idGroup'];
        $user['hasGroup']   = $row['hasGroup'];
        $user['pass']       = $row['pass'];
    }
    $row = mysqli_num_rows($result);
    if ($row == 1) {
        $res = array(
            'status'            => 1,
            'status_message'    => 'Login efetuado com sucesso!',
            'user'              => json_encode($user)
        );
    } else {
        $res = array(
            'status'            => 0,
            'status_message'    => 'Erro ao efetuar login!'
        );
    }
    echo json_encode($res);
}

mysqli_close($conn);