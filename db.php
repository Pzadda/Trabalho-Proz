<?

//Conexão com o banco
$host = "localhost";
$user = "root";
$pass = "aluno";
$db = "sys_register";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "conection failed:("], JSON_UNESCAPED_UNICODE);
    exit();

}


?>