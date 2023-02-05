<?php
include('db.php');
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        (!empty($_GET['idMessage'])) ? deleteMessageById($_GET['idMessage']) : getAllMessagesByGroup($_GET['idGroup']);
        break;
    case 'POST':
        createMessage();
        break;
    case 'DELETE':
        deleteAllMessages();
        break;
    default:
        header('HTTP/1.0 405 Method Not Allowed');
        break;
}

function getAllMessagesByGroup($idGroup)
{
    global $conn;
    $sql    = "SELECT
                messages.id,
                messages.content,
                messages.date,
                messages.idUser,
                messages.idGroup,
                users.tagName
                FROM messages
                INNER JOIN users
                ON messages.idUser = users.id
                WHERE messages.idGroup = '$idGroup'
                ORDER BY messages.id
    ";
    $result = mysqli_query($conn, $sql);
    $res    = array();
    $x      = 0;
    while ($row = mysqli_fetch_array($result)) {
        $res[$x]['id']          = $row['id'];
        $res[$x]['content']     = $row['content'];
        $res[$x]['date']        = $row['date'];
        $res[$x]['idUser']      = $row['idUser'];
        $res[$x]['idGroup']     = $row['idGroup'];
        $res[$x]['tagName']     = $row['tagName'];

        $x++;
    }
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($res);
}

function createMessage()
{
    global $conn;
    $content    = $_POST['content'];
    $date       = date('Y-m-d h:i:s');
    $idUser     = $_POST['idUser'];
    $idGroup    = $_POST['idGroup'];

    $sql        = "INSERT INTO
                    messages(
                        content,
                        date,
                        idUser,
                        idGroup
                    )
                    VALUES(
                        '$content',
                        '$date',
                        '$idUser',
                        '$idGroup'
                    )
    ";
    if (!mysqli_query($conn, $sql)) echo 'Erro ao enviar mensagem!';
}

function deleteAllMessages()
{
    global $conn;
    $sql = "DELETE FROM messages WHERE idGroup = '1'";
    if (!mysqli_query($conn, $sql)) echo 'Erro ao apagar mensagens!';
}

function deleteMessageById($idMessage)
{
    global $conn;
    $sql = "DELETE FROM messages WHERE id = '$idMessage'";
    if (!mysqli_query($conn, $sql)) echo 'Erro ao apagar mensagens!';
}

mysqli_close($conn);