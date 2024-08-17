<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Текстовая обработка</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 50px;
        }
        .result {
            margin-top: 20px;
        }
        .form-control {
            resize: none;
        }
        .preformatted {
            white-space: pre-wrap; /* Обеспечивает перенос строк и отображение пробелов */
            word-wrap: break-word; /* Переносит слова на новую строку при необходимости */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Обработка текста</h1>
        <form method="post" onsubmit="capitalizeFirstLetter()">
            <div class="form-group">
                <label for="inputText">Введите текст:</label>
                <textarea class="form-control" id="inputText" name="inputText" rows="6" required><?php echo isset($_POST['inputText']) ? htmlspecialchars($_POST['inputText']) : ''; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Обработать</button>
        </form>

        <?php
        // Правила замены
        $rules = [
            // Если первое слово в тексте "объявление", меняем на "объявления"
            '/^объявление\b/u' => 'объявления',
            // Замена "во всех регионах" на "{geo_declination}"
            '/во всех регионах/u' => '{geo_declination}',
            // Замена "На Авито вы можете заказать" на "Заказать"
            '/На Авито вы можете заказать/u' => 'Заказать',
			'/На Авито вы можете записаться/u' => 'Записаться',
			'/Свои услуги предлагают/u' => 'Услуги предлагают',
			'/Выбирайте нужные услуги в прайс-листе, смотрите отзывы/u' => 'Смотрите отзывы',
			
			
			
            // Замена "а также посмотреть отзывы" на "посмотреть отзывы"
            '/а также посмотреть отзывы/u' => 'посмотреть отзывы',
            // Замена "вызвать нужных специалистов" на "вызвать специалистов"
            '/вызвать нужных специалистов/u' => 'вызвать специалистов',
			'/вызвать на дом нужных специалистов/u' => 'вызвать специалистов на дом',
			'/На Авито вы можете найти/u' => 'Найти',
			'/а затем найти/u' => 'найти',
			'/Объявлений/u' => 'Объявления',
			'/о недорогих услугах/u' => 'об услугах',
			'/, в том числе под ключ/u' => ' под ключ',
			
			
			
			
            // Если текст заканчивается на " на Авито.", меняем на "."
            '/ на Авито\.$/u' => '.',
        ];

        // Обработка формы
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $inputText = $_POST['inputText'];

            // Применение всех правил замены
            foreach ($rules as $pattern => $replacement) {
                $inputText = preg_replace($pattern, $replacement, $inputText);
            }

            echo '<div class="result">';
            echo '<h2>Результат:</h2>';
            echo '<pre id="resultText" class="preformatted">' . htmlspecialchars($inputText) . '</pre>';
            echo '<button class="btn btn-secondary mt-2" onclick="copyToClipboard()">Скопировать результат</button>';
            echo '</div>';
        }
        ?>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function capitalizeFirstLetter() {
            var inputText = document.getElementById("inputText").value;
            document.getElementById("inputText").value = inputText.charAt(0).toUpperCase() + inputText.slice(1);
        }

        function copyToClipboard() {
            // Создание временного элемента для копирования
            var copyText = document.getElementById("resultText").innerText;
            var tempElement = document.createElement("textarea");
            tempElement.value = copyText;
            document.body.appendChild(tempElement);
            tempElement.select();
            document.execCommand("copy");
            document.body.removeChild(tempElement);
            alert("Результат скопирован в буфер обмена!");
        }
    </script>
</body>
</html>
