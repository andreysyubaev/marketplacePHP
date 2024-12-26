<?php
session_start(); // Включение сессии
include 'connect.php'; // Подключаем файл с подключением к базе данных

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Получаем данные пользователя из базы данных
$user_id = $_SESSION['user_id'];
$sql = "SELECT surname, name, date, mail, login FROM userdata WHERE id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($surname, $name, $date, $mail, $login);
            $stmt->fetch();
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
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль</title>
    <link href="https://fonts.googleapis.com/css?family=Inter&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="./css/profile.css">
</head>

<body>
    <!-- Основной контейнер -->
    <div class="v6_58">
        <!-- Шапка страницы -->
        <header class="header">
            <a href="stuffpage.php" class="header-logo">clothes</a>
                <div class="header-profile">
                    <!-- Иконка профиля с кнопкой -->
                    <a href="profile.php" class="header-profile-icon"></a>
                </div>
        </header>

        <!-- Основная информация профиля -->
        <section class="profile-container">
            <span class="profile-field"><?php echo htmlspecialchars($surname); ?></span>
            <span class="profile-field"><?php echo htmlspecialchars($name); ?></span>
            <span class="profile-field"><?php echo htmlspecialchars($date); ?></span>
            <span class="profile-field"><?php echo htmlspecialchars($mail); ?></span>
            <span class="profile-field"><?php echo htmlspecialchars($login); ?></span>
        </section>
    </div>
</body>

</html>
