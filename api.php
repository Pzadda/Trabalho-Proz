<?php
include("db.php");

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

$method = $_SERVER["REQUEST_METHOD"];

if ($method == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (
        isset(
            $data["name_"],
            $data["email"],
            $data["password_"],
            $data["phone"],
            $data["adress"],
            $data["state"],
            $data["birthDate"]
        )
    ) {
        $name_ = trim($data["name_"]);
        $email = trim($data["email"]);
        $password_ = $data["password_"];
        $phone = trim($data["phone"]);
        $adress = trim($data["adress"]);
        $state = trim($data["state"]);
        $birthDate = $data["birthDate"];

        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$/', $password_)) {
            http_response_code(400);
            echo json_encode([
                'error' => 'The password must have at least 8 characters, including at least one uppercase letter, one lowercase letter, one number and one special character'
            ], JSON_UNESCAPED_UNICODE);
            exit();
        }

        $hashed_password = password_hash($password_, PASSWORD_DEFAULT);
        $phone = $data["phone"];
        $adress = $data["adress"];
        $state = $data["state"];
        $birthDate = $data["birthDate"];

        $stmt = $conn->prepare("INSERT INTO users (name_, email, password_, phone, adress, state, birthdate) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $name_, $email, $hashed_password, $phone, $adress, $state, $birthDate);

        if ($stmt->execute()) {
            $id = $stmt->insert_id;
            $result = $conn->query("SELECT id, name_, email, phone, adress, state, birthdate, created FROM users WHERE id=$id");
            $client = $result->fetch_assoc();
            echo json_encode(["message" => "Client registered successfully", "client" => $client], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(400);
            echo json_encode(["error" => $stmt->error], JSON_UNESCAPED_UNICODE);
        }

        $stmt->close();

    } else {
        http_response_code(400);
        echo json_encode(["error" => "all fields are mandatory"], JSON_UNESCAPED_UNICODE);
        exit();
    }

} elseif ($method == "GET") {
    $sql = "SELECT id, name_, email, phone, adress, state, birthdate FROM users";
    $result = $conn->query($sql);

    if ($result) {
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        if (!empty($users)) {
            http_response_code(200);
            echo json_encode(["users" => $users], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "No users found"], JSON_UNESCAPED_UNICODE);
        }
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error querying users: " . $conn->error], JSON_UNESCAPED_UNICODE);
    }

} elseif ($method == "DELETE") {
  
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data["id"])) {
        $id = intval($data["id"]);

        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(["message" => "User with ID $id deleted successfully"], JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "User with ID $id not found"], JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to delete user: " . $stmt->error], JSON_UNESCAPED_UNICODE);
        }

        $stmt->close();
    } else {
        http_response_code(400);
        echo json_encode(["error" => "User ID is required"], JSON_UNESCAPED_UNICODE);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed. Use POST, GET or DELETE."], JSON_UNESCAPED_UNICODE);
}

$conn->close();

?>
