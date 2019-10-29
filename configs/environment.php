<?php

	//--- Режим работы системы
	define('TIMEZONE', 'Europe/Moscow');

	//--- Режим работы системы
	define('CP_ENVIRONMENT', 'development');

	//--- Использовать IP для автоматического входа на сайте
	define('LOGIN_USER_IP', false);

	//--- Пытаться очистить память если выходит за пределы (-1 выключенно) в мегабайтах (увеличивается нагрузка на MySQL)
	define('MEMORY_LIMIT_PANIC', -1);

	//--- Директория для временных файлов
	define('TEMP_DIR', '/tmp');

	//--- Директория для хранения вложений
	define('ATTACH_DIR', '/tmp/attachments');

	//--- Директория для хранения файлов
	define('UPLOAD_DIR', '/uploads');

	//--- Папка для хранения сессий
	define('SESSION_DIR', '/tmp/sessions');

	//--- Хранить сессии в БД
	define('SESSION_SAVE_HANDLER', 'db');

	//--- Время жизни сессии (60*60*24 - 24 часа)
	define('SESSION_LIFETIME', 1209600);

	//--- Домен для cookie. По умолчанию пусто
	define('COOKIE_DOMAIN', '');

	//--- Время жизни cookie (60*60*24*14 - 2 недели)
	define('COOKIE_LIFETIME', 1209600);

	//--- Контролировать изменения tpl файлов После настройки сайта установить - false
	define('SMARTY_COMPILE_CHECK', true);

	//--- Создание папок для кэширования Установите это в false если ваше окружение PHP не разрешает создание директорий от имени Smarty. Поддиректории более эффективны, так что используйте их, если можете
	define('SMARTY_USE_SUB_DIRS', true);

	//--- Кэширование скомпилированных шаблонов документов
	define('CACHE_DOC_TPL', false);

	//--- Время жизни кеша
	define('CACHE_LIFETIME', 3600);

	//--- Время жизни кеша системных запросов
	define('SYSTEM_CACHE_LIFETIME', 300);

	//--- Включить стандартную обработку ошибок PHP
	define('PHP_DEBUGGING', true);

	//--- Включить обработку ошибок PHP через обработчик cms
	define('SELF_ERROR', true);

	//--- Консоль отладки Smarty
	define('SMARTY_DEBUGGING', false);

	//--- Включить вывод статистики запросов
	define('SQL_DEBUGGING', false);

	//--- Останавливать систему, если произошла ошибка в MySQL запросе
	define('SQL_ERRORS_STOP', false);

	//--- Отправка писем с ошибками MySQL
	define('SEND_SQL_ERROR', false);

	//--- Вывод статистики выполненых запросов
	define('SQL_PROFILING', false);

	//--- Вывод статистики
	define('PROFILING', false);

	//--- Включить html компрессию
	define('HTML_COMPRESSION', true);

	//--- Включить gzip компрессию
	define('GZIP_COMPRESSION', true);

	//--- Отдавать заголовок на кеширование страницы
	define('OUTPUT_EXPIRE', false);

	//--- Время жизни кеширования страницы (60*60 - 1 час)
	define('OUTPUT_EXPIRE_OFFSET', 3600);

	//--- Адрес Memcached сервера
	define('USE_MEMCACHED', false);

	//--- Адрес Memcached сервера
	define('MEMCACHED_SERVER', 'localhost');

	//--- Порт Memcached сервера
	define('MEMCACHED_PORT', '11211');

	//--- Проверка наличия новых версий системы
	define('CHECK_VERSION', false);
