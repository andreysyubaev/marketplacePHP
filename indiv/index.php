<?php
session_start(); // Включение сессии

include "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM userdata WHERE login = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);

        if ($stmt->execute()) {
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $surname, $name, $date, $mail, $login, $hashed_password);
                $stmt->fetch();

                if (password_verify($password, $hashed_password)) {
                    // Успешная авторизация
                    $_SESSION['user_id'] = $id;
                    $_SESSION['username'] = $username;
                    header("Location: profile.php");
                    exit();
                } else {
                    echo "Неверный пароль.";
                }
            } else {
                echo "Пользователь не найден.";
            }
        } else {
            echo "Ошибка: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Ошибка: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
    <?php
    // Проверяем, есть ли уведомление о регистрации
    if (isset($_SESSION['success_message'])) {
        echo "<div class='alert success'>" . $_SESSION['success_message'] . "</div>";
        unset($_SESSION['success_message']); // Удаляем сообщение после вывода
    }
    ?>
    <div class="auth">
        <h2>Авторизация</h2>
        <form action="index.php" method="POST">
            <input type="text" name="username" placeholder="Логин" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Войти</button>
        </form>
        <a href="register.html">Зарегистрироваться</a>
    </div>
</body>
</html>
