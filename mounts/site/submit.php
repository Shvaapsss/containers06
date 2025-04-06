<?php
/**
 * Обработчик теста
 */

$dataFile = "data.json";
$resultsFile = "results.json";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Неверный метод запроса.");
}

if (!file_exists($dataFile)) {
    die("Файл с вопросами не найден.");
}

$jsonData = file_get_contents($dataFile);
$questionsData = json_decode($jsonData, true);
$questions = $questionsData["questions"] ?? [];

$username = trim($_POST['username'] ?? '');
if (empty($username)) {
    die("Введите ваше имя.");
}

$answers = $_POST['answer'] ?? [];
$score = 0;
$totalQuestions = count($questions);

foreach ($questions as $index => $q) {
    $correctAnswers = $q['correct'];
    $userAnswer = $answers[$index] ?? [];

    if ($q['type'] === 'radio') {
        if ($userAnswer === $correctAnswers[0]) {
            $score++;
        }
    } else {
        sort($userAnswer);
        sort($correctAnswers);
        if ($userAnswer === $correctAnswers) {
            $score++;
        }
    }
}

$percentage = round(($score / $totalQuestions) * 100);

$results = file_exists($resultsFile) ? json_decode(file_get_contents($resultsFile), true) : [];
$results[] = ['username' => htmlspecialchars($username), 'score' => $percentage];
file_put_contents($resultsFile, json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Результаты</title>
</head>
<body>
    <h2>Ваш результат:</h2>
    <p>Правильных ответов: <?= $score ?> из <?= $totalQuestions ?></p>
    <p>Набранные баллы: <?= $percentage ?>%</p>
    <a href="dashboard.php"><button>Просмотреть лидеров</button></a>
    <a href="test.php"><button>Пройти тест заново</button></a>
</body>
</html>
