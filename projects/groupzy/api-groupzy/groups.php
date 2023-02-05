<?php
include('db.php');
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        (!empty($_GET['idGroup'])) ? getGroupById($_GET['idGroup']) : getAllGroups();
        break;
    case 'POST':
        (!empty($_GET['idGroup'])) ? updateGroupById($_GET['idGroup']) : createGroup();
        break;
    case 'DELETE':
        deleteGroupById($_GET['idGroup']);
        break;
    default:
        header('HTTP/1.0 405 Method Not Allowed');
        break;
}

function getAllGroups()
{
    global $conn;
    $sql    = "SELECT * FROM groups";
    $result = mysqli_query($conn, $sql);
    $res    = array();
    $x      = 0;
    while ($row = mysqli_fetch_array($result)) {
        $res[$x]['id']          = $row['id'];
        $res[$x]['title']       = $row['title'];
        $res[$x]['description'] = $row['description'];
        $res[$x]['image']       = $row['image'];
        $res[$x]['idUser']      = $row['idUser'];

        $x++;
    }
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($res);
}

function createGroup()
{
    global $conn;
    $title          = $_POST['title'];
    $description    = $_POST['description'];
    $idUser         = $_POST['idUser'];
    $sql = "INSERT INTO groups(title, description, idUser)
                VALUES('$title', '$description', '$idUser')";
    if (mysqli_query($conn, $sql)) {
        $res = array(
            'status'            => 1,
            'status_message'    => 'Grupo criado com sucesso.',
        );
    } else {
        $res = array(
            'status'            => 0,
            'status_message'    => 'Erro ao criar grupo!',
            'error'             => mysqli_error($conn)
        );
    }
    echo json_encode($res);
}

function getGroupById($id)
{
    global $conn;
    $sql    = "SELECT * FROM groups WHERE id = '$id' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $res    = array();
    $x      = 0;
    while ($row = mysqli_fetch_array($result)) {
        $res[$x]['id']          = $row['id'];
        $res[$x]['title']       = $row['title'];
        $res[$x]['description'] = $row['description'];
        $res[$x]['idUser']      = $row['idUser'];

        $x++;
    }
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($res);
}

function deleteGroupById($id)
{
    global $conn;
    $sql = "DELETE FROM groups WHERE id = '$id'";
    if (!mysqli_query($conn, $sql)) echo 'Erro ao deletar grupo!';
    header("Content-Type: application/json; charset=UTF-8");
}

function updateGroupById($id)
{
    global $conn;
    $title          = $_POST['title'];
    $description    = $_POST['description'];
    // FEAT: POST IMAGE
    $idUser         = $_POST['idUser'];

    $sql = "INSERT INTO groups(title, description, idUser)
                VALUES('$title', '$description', '$idUser')
                    WHERE id = '$id'";
    if (!mysqli_query($conn, $sql)) echo 'Erro ao atualizar grupo!';
}

mysqli_close($conn);
