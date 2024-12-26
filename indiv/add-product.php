<?php
session_start();
include 'connect.php'; // Подключаем файл с подключением к базе данных

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Обработка формы при отправке
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получаем данные из формы с предварительной фильтрацией
    $name = trim($_POST['name']);
    $size = trim($_POST['size']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);

    // Проверка, что поля заполнены
    if (empty($name) || empty($size) || empty($description) || empty($price)) {
        echo "Все поля должны быть заполнены!";
        exit();
    }

    // Проверка, что цена — это число
    if (!is_numeric($price) || $price <= 0) {
        echo "Цена должна быть положительным числом!";
        exit();
    }

    // Обработка изображения
    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES['image']['tmp_name']);
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $uploadDir = 'images/'; // Папка для сохранения изображений
        $imagePath = $uploadDir . $imageName;

        // Проверка MIME-типа файла
        if (!in_array($fileType, $allowedTypes)) {
            echo "Можно загружать только изображения (JPEG, PNG, GIF)!";
            exit();
        }

        // Перемещение загруженного изображения
        if (!move_uploaded_file($imageTmpPath, $imagePath)) {
            echo "Ошибка загрузки изображения.";
            exit();
        }
    }

    // Подготовка запроса для добавления товара в базу данных
    $sql = "INSERT INTO clothes (name, image, size, description, price) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssss", $name, $imagePath, $size, $description, $price);

        if ($stmt->execute()) {
            echo "Товар успешно добавлен!";
            header("Location: stuffpage.php");
            exit();
        } else {
            echo "Ошибка при добавлении товара: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Ошибка при подготовке запроса: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить товар</title>
    <link href="https://fonts.googleapis.com/css?family=Inter&display=swap" rel="stylesheet">
    <!-- Подключение внешнего CSS -->
    <link href="./css/add-product.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Добавить товар</h1>
        <!-- Форма добавления товара -->
        <form action="add-product.php" method="POST" enctype="multipart/form-data">
            <!-- Поле Название -->
            <label for="name">Название:</label>
            <input type="text" id="name" name="name" placeholder="Введите название товара" required>

            <!-- Поле Размер -->
            <label for="size">Размер:</label>
            <input type="text" id="size" name="size" placeholder="Введите размер" required>

            <!-- Поле Описание -->
            <label for="description">Описание:</label>
            <textarea id="description" name="description" rows="4" placeholder="Введите описание товара" required></textarea>

            <!-- Поле Цена -->
            <label for="price">Цена:</label>
            <input type="text" id="price" name="price" placeholder="Введите цену" required>

            <!-- Поле для загрузки фото -->
            <label for="image">Фото:</label>
            <input type="file" id="image" name="image">

            <!-- Кнопка отправки -->
            <button type="submit">Добавить товар</button>
        </form>
    </div>
</body>

</html>
