
<?php
/**
 * Тестовая страница
 */

// Загружаем вопросы
$dataFile = "data.json";
if (!file_exists($dataFile)) {
    die("Файл с вопросами не найден.");
}

$jsonData = file_get_contents($dataFile);
$questionsData = json_decode($jsonData, true);
$questions = $questionsData["questions"] ?? [];

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Тест</title>
</head>
<body>
    <h2>Пройдите тест</h2>
    <form action="submit.php" method="post">
        <label>Введите ваше имя: <input type="text" name="username" required></label><br><br>

        <?php foreach ($questions as $index => $q): ?>
            <p><?= htmlspecialchars(($index + 1) . ". " . $q["question"]) ?></p>
            <?php foreach ($q["answers"] as $answer): ?>
                <label>
                    <input type="<?= $q["type"] === "radio" ? "radio" : "checkbox" ?>" 
                           name="answer[<?= $index ?>]<?= $q["type"] === "checkbox" ? "[]" : "" ?>" 
                           value="<?= htmlspecialchars($answer) ?>"> <?= htmlspecialchars($answer) ?>
                </label><br>
            <?php endforeach; ?>
        <?php endforeach; ?>

        <br><button type="submit">Завершить тест</button>
    </form>
</body>
</html>
