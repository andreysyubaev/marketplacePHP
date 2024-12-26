<?php
include "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $surname = $_POST['lastname'];
    $name = $_POST['firstname'];
    $date = $_POST['birthdate'];
    $mail = $_POST['email'];
    $login = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO userdata (surname, name, date, mail, login, password) VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssss", $surname, $name, $date, $mail, $login, $password);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Ошибка: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Ошибка: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Неверный метод запроса.";
}
?>
