<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>API Groupzy</title>
</head>
<body>
    <header>
        <h1>API Groupzy</h1>
    </header>
    <hr>
    <main>
        <ul>
            <li><a href="./users.php">Usuários</a></li>
            <li><a href="./groups.php">Grupos</a></li>
            <li><button style="cursor:pointer;" onclick="$.ajax({type:'DELETE',url:'https:/\/api-groupzy.herokuapp.com/messages.php/',success:function(res){window.location='./index.php'}});">Apagar mensagens (Grupo teste)</button></li>
        </ul>
    </main>
</body>
</html>