# Art Feedback Button

Плагин обратного звонка. Выводит шорткодом кнопку обратного звонка. 

Работает на REST API. Кнопка выводиться `[afb]`. 

Дополнительные атрибуты шорткода
* `label` - надпись на кнопке
* `class` - произвольный ccs класс
* `emails` - список адресов через запятую, который будет обправляться письмо, по умолчанию берется адрес из настроек сайта

Пример шорткода

```[afb label="Обратный звонок" class="custom-button" emails="art@art.ru, der@tru.ru, art2l@gmail.com, der2@tru.ru"]```

## Changelog

= 1.2.0 =
* Обновлено - отправки формы по событию submit
* Исправлено - форматирования емайлов при отправке реста
* Исправлено - работа валидации полей
* Исправлено - вывод полей в шаблоне письма и в модальном окне

= 1.1.1 =
* Исправлено - правки стилей

= 1.1.0 =
* Добавлено - возможность изменения шаблонов через файлы темы
* Добавлено - триггеры для отслеживания событий
* Добавлено - динамическая генерация полей
* Добавлено - фильтр `afb_form_fields` - для возможности изменения полей
* Добавлено - фильтр `afb_locate_template` - для возможности изменения пути к файлам шаблонов
* Исправлено - мелкие правки

### = 1.0.2 =
* Добавлено - атрибуты шорткода
* Добавлено - атрибуты шорткода, управление адресом почты

### = 1.0.1 =
* Рефакторинг - вынос шорткода в отдельный класс

### = 1.0.0 =
* Релиз
