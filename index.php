<?php
session_start();
include 'auth.php';

// Проверка авторизации
if (!getCurrentUser()) {
    header("Location: login.php");
    exit();
}

// Запрос дня и месяца рождения, если они не установлены
if (!isset($_SESSION['birth_day']) || !isset($_SESSION['birth_month'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['birth_day']) && !empty($_POST['birth_month'])) {
        $_SESSION['birth_day'] = (int) $_POST['birth_day'];
        $_SESSION['birth_month'] = (int) $_POST['birth_month'];
    }
}

// Вычисление дней до дня рождения
$days_until_birthday = null;
$birthday_discount = false;

if (isset($_SESSION['birth_day']) && isset($_SESSION['birth_month'])) {
    $current_date = new DateTime();
    $this_year_birthday = new DateTime(date('Y') . '-' . $_SESSION['birth_month'] . '-' . $_SESSION['birth_day']);

    // Если сегодня день рождения — даем скидку
    if ($this_year_birthday->format('m-d') === $current_date->format('m-d')) {
        $birthday_discount = true;
    } else {
        // Вычисляем разницу дней
        if ($this_year_birthday < $current_date) {
            $this_year_birthday->modify('+1 year'); // Если ДР уже прошел, берем дату следующего года
        }
        $days_until_birthday = $current_date->diff($this_year_birthday)->days;
    }
}

// Запись времени входа (если не установлено)
if (!isset($_SESSION['login_time'])) {
    $_SESSION['login_time'] = time();
}

// Вычисление оставшегося времени акции
$discount_duration = 24 * 60 * 60; // 24 часа в секундах
$time_left = $discount_duration - (time() - $_SESSION['login_time']);

if ($time_left > 0) {
    $hours = floor($time_left / 3600);
    $minutes = floor(($time_left % 3600) / 60);
    $seconds = $time_left % 60;
} else {
    unset($_SESSION['login_time']); // Акция истекла, сбрасываем время входа
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Спа Салон</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Спа Салон "Релакс"</h1>
        <p>Здравствуйте, <strong><?php echo getCurrentUser(); ?>!</strong></p>
        <form action="logout.php" method="POST" class="auth-form">
            <button type="submit">Выйти</button>
        </form>
    </header>
    
    <main>
        <h2>Наши услуги</h2>

        <?php if (!isset($_SESSION['birth_day']) || !isset($_SESSION['birth_month'])): ?>
            <form method="POST">
                <label>Введите ваш день рождения:</label>
                <input type="number" name="birth_day" min="1" max="31" required>
                <label>Введите месяц рождения:</label>
                <input type="number" name="birth_month" min="1" max="12" required>
                <button type="submit">Сохранить</button>
            </form>
        <?php else: ?>
            <?php if ($birthday_discount): ?>
                <h3>🎉 С Днем Рождения! Вам доступна скидка 5% на все услуги! 🎉</h3>
            <?php else: ?>
                <p>До вашего дня рождения осталось <strong><?php echo $days_until_birthday; ?></strong> дней.</p>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($time_left > 0): ?>
            <p>Ваша персональная скидка действует еще: <strong><?php echo "$hours ч $minutes м $seconds с"; ?></strong></p>
        <?php endif; ?>

        <div class="services">
            <?php
            include 'database.php';
            foreach ($services as $service) {
                $price = $service['price'];

                // Применяем скидку в 5% в день рождения
                if ($birthday_discount) {
                    $discounted_price = round($price * 0.95, 2);
                    echo "<div class='service'>";
                    echo "<img src='{$service['photo']}' alt='{$service['name']}'>";
                    echo "<h3>{$service['name']}</h3>";
                    echo "<p>Цена: <s>{$price}</s> <strong>{$discounted_price} руб.</strong> (Скидка 5%)</p>";
                    echo "</div>";
                } else {
                    echo "<div class='service'>";
                    echo "<img src='{$service['photo']}' alt='{$service['name']}'>";
                    echo "<h3>{$service['name']}</h3>";
                    echo "<p>Цена: {$price} руб.</p>";
                    echo "</div>";
                }
            }
            ?>
        </div>
    </main>
</body>
</html>
