<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>music</title>
</head>
<body>
<h3>music</h3>
<audio>
    <source src="" type="audio/mp3">
</audio>

<script>
    // 1. Создаём новый объект XMLHttpRequest
    var xhr = new XMLHttpRequest();

    // 2. Конфигурируем его: GET-запрос на URL 'phones.json'
    xhr.open('GET', 'http://blog.my/api/play', false);

    // 3. Отсылаем запрос
    xhr.send();

    // 4. Если код ответа сервера не 200, то это ошибка
    if (xhr.status != 200) {
        // обработать ошибку
        alert( xhr.status + ': ' + xhr.statusText ); // пример вывода: 404: Not Found
    } else {
        // вывести результат
        zz = document.getElementsByTagName('source')[0].setAttribute('src',xhr.responseText )
        document.getElementsByTagName('audio')[0].play();
        // alert( xhr.responseText ); // responseText -- текст ответа.
    }
</script>
</body>
</html>
