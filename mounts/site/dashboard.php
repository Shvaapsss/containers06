<?php
/**
 * Таблица лидеров
 */

$resultsFile = "results.json";
$results = file_exists($resultsFile) ? json_decode(file_get_contents($resultsFile), true) : [];

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лидеры</title>
</head>
<body>
    <h2>Результаты теста</h2>
    <table border="1">
        <tr>
            <th>Имя</th>
            <th>Процент</th>
        </tr>
        <?php foreach ($results as $result): ?>
            <tr>
                <td><?= htmlspecialchars($result['username']) ?></td>
                <td><?= htmlspecialchars($result['score']) ?>%</td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <a href="test.php"><button>Пройти тест заново</button></a>
</body>
</html>
