<?php
session_start(); // Включение сессии
include 'connect.php'; // Подключаем файл с подключением к базе данных

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Получаем данные товаров из базы данных
$sql = "SELECT name, image, size, description, price FROM clothes";
$result = $conn->query($sql);

// Проверка на наличие ошибок в запросе
if (!$result) {
    die("Ошибка выполнения запроса: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <link href="https://fonts.googleapis.com/css?family=Inter&display=swap" rel="stylesheet" />
    <link href="./css/stuffpage.css" rel="stylesheet" />
    <title>Каталог</title>
</head>

<body>
    <div class="v1_2">
        <!-- Шапка страницы -->
        <div class="v6_2">
            <a href="stuffpage.php" class="v6_21">clothes</a>
            <div class="v6_22">
                <a href="profile.php" class="v6_23"></a>
            </div>
        </div>

        <!-- Контейнер с товарами -->
        <div class="products-container">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Путь к изображению или заглушка, если изображения нет
                    $imagePath = !empty($row['image']) ? htmlspecialchars($row['image']) : 'images/placeholder.jpg';

                    echo '<div class="product">';
                    echo '<div class="product-image" style="background-image: url(\'' . $imagePath . '\');"></div>';
                    echo '<div class="product-info">';
                    echo '<span class="product-name">' . htmlspecialchars($row['name']) . '</span>';
                    echo '<span class="product-size">Размер: ' . htmlspecialchars($row['size']) . '</span>';
                    echo '<div class="product-description"><span>' . htmlspecialchars($row['description']) . '</span></div>';
                    echo '<span class="product-price">' . htmlspecialchars($row['price']) . ' руб.</span>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>Нет товаров в каталоге.</p>';
            }
            ?>
        </div>

        <!-- Кнопка с зелёным плюсиком -->
        <a href="add-product.php" class="add-product-icon" title="Добавить товар">
            <img src="images/plus-icon.svg" alt="Добавить товар">
        </a>
    </div>
</body>

</html>
