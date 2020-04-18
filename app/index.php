<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="widht=device-width, initial-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <title>Получение данных из БД через API</title>
</head>
<body>
<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
    <h5 class="my-0 mr-md-auto font-weight-normal">Тестовое задание часть 2 (REST API)</h5>
</div>
<div class="container ml-5" >
    <form id="getData" method="get" style="width: 600px;">
        <h5>Получить данные о курсе валют за последние 30 дней</h5><br>
        <div class="form-group" style="width: 70%">
            <label>Выбирете ид валюты</label>
            <select id="selectValiteID" class="form-control"></select>
        </div>
        <div class="form-group" style="width: 70%">
            <label>Выбирете период</label>
            <input id="dateStart" type="date" class="form-control" required>
        </div>
        <div class="form-group" style="width: 70%">
            <label>По</label>
            <input id="dateEnd" type="date" class="form-control" required>
        </div>
        <div class="form-group">
            <button id="getDataButton" type="submit" name="getData" class="btn btn-success" >Получить данные о курсе валют</button>
        </div>
    </form>
    <div class="table-responsive"  id="dataRate">

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js" integrity="sha256-sPB0F50YUDK0otDnsfNHawYmA5M0pjjUf4TvRJkGFrI=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        //вызываем функцию наполнения селектора
        loadValuteID();

        //получаем список ИД валют через API для селектора
        function loadValuteID(){
            //получаем данные ИД валют в формате Имя_валюты => ИД_валюты
            $.getJSON('http://127.0.0.1:8081/bank-rest-api/api/rate/getValuteID.php', function (data) {
                $.each(data.records, function (key,val) {
                    $('#selectValiteID').append('<option value="' + val.valuteID + '">' + val.name + '</option>');
                })
            })
        }

        //Получаем данные за выбранный период
        $('#getData').on('submit',function (event) {
            event.preventDefault();

            //выключаем кнопку и изменяем ее текст
            $('#getDataButton').attr("disabled", true);
            $('#getDataButton').text("Подождите идет получение данных...");

            //получаем значения элементов для формирования запроса
            let valute = $('#selectValiteID').val();
            let dataStart = $('#dateStart').val();
            let dateEnd = $('#dateEnd').val();

            //запрашиваем данные
            $.getJSON('http://127.0.0.1:8081/bank-rest-api/api/rate/getData.php?valuteID='+valute+'&dateStart='+dataStart+'&dateEnd='+dateEnd, function (data) {

                //формируем заголовки таблицы
                let dataTableRate = `
                <table id="tableDataRate" class="table table-sm table-dark" border="1">
                    <thead >
                        <tr>
                            <th>ID валюты</th>
                            <th>Числовой код</th>
                            <th>Буквенный код</th>
                            <th>Название валюты</th>
                            <th>Курс</th>
                            <th>Дата публикации</th>
                        </tr>
                    </thead>
                <tbody>`;

                //перебираем данные в цикле и формируем строки
                $.each(data.records, function (key,val) {
                    dataTableRate+=`
                <tr>
                    <td>` + val.valuteID + `</td>
                    <td>` + val.numCode + `</td>
                    <td>` + val.charCode + `</td>
                    <td>` + val.name + `</td>
                    <td>` + val.value + `</td>
                    <td>` + val.date + `</td>
                </tr>`;
                })
                //вставляем содержимое в div
                $('#dataRate').html(dataTableRate);
                $('#getDataButton').attr("disabled", false);
                $('#getDataButton').text("Получить данные о курсе валют");
            })
                .fail(function(jqXHR, textStatus, errorThrown) {

                    $('#dataRate').html('Данные не найдены');
                    $('#getDataButton').attr("disabled", false);
                    $('#getDataButton').text("Получить данные о курсе валют");
                    alert('Ошибка запроса данных! ' + errorThrown);
                })


        });

    });
</script>
</body>
</html>