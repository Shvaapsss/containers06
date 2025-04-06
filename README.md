# Лабораторная работа №6: Docker. Многоконтейнерное приложение

## Цель работы

Выполнив данную работу, студент сможет управлять взаимодействием нескольких контейнеров.

## Задание

Создать PHP-приложение на базе двух контейнеров: `nginx` и `php-fpm`.

## Подготовка

Для выполнения работы необходимо:
- установленный Docker;
- опыт выполнения лабораторной работы №3.

## Выполнение

1. **Создание репозитория**

   Клонируем репозиторий `containers06` на компьютер.

2. **Структура проекта**

   В корне создаем структуру директорий:

   mounts/
   └── site/        # PHP сайт, созданный ранее
   nginx/
   └── default.conf # Конфигурация nginx
   .gitignore
   README.md

3. **.gitignore**

   В корне проекта создаем файл `.gitignore` и добавляем:
```
   # Ignore files and directories
   mounts/site/*
```
4. **Конфигурация Nginx**

   В `nginx/default.conf` добавляем:
```
   server {
       listen 80;
       server_name _;
       root /var/www/html;
       index index.php;
       location / {
           try_files $uri $uri/ /index.php?$args;
       }
       location ~ \.php$ {
           fastcgi_pass backend:9000;
           fastcgi_index index.php;
           fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
           include fastcgi_params;
       }
   }
```
5. **Создание сети**

   Создаем изолированную сеть:
```
   docker network create internal
```
6. **Контейнер `backend`**

   Запускаем контейнер на базе `php:7.4-fpm`:
   
```
   docker run -d --name backend \
     --network internal \
     -v $(pwd)/mounts/site:/var/www/html \
     php:7.4-fpm
```

7. **Контейнер `frontend`**

   Запускаем контейнер `nginx`:

```
   docker run -d --name frontend \
     --network internal \
     -p 80:80 \
     -v $(pwd)/mounts/site:/var/www/html \
     -v $(pwd)/nginx/default.conf:/etc/nginx/conf.d/default.conf \
     nginx:1.23-alpine
```

8. **Проверка**

   Открываем браузер и переходим по адресу http://localhost. Если видим страницу PHP-сайта — всё работает.

   Если отображается базовая страница Nginx — перегружаем страницу (`Ctrl + F5`).

## Ответы на вопросы

1. **Каким образом в данном примере контейнеры могут взаимодействовать друг с другом?**

   Контейнеры взаимодействуют по внутренней Docker-сети `internal`. Nginx передает PHP-запросы на контейнер `backend`, используя его имя как хост (DNS работает внутри сети Docker).

2. **Как видят контейнеры друг друга в рамках сети `internal`?**

   Через имена контейнеров. Контейнер `frontend` обращается к `backend:9000`, и Docker сам разрешает имя `backend` внутри сети `internal`.

3. **Почему необходимо было переопределять конфигурацию nginx?**

   Стандартная конфигурация Nginx не знает, как обрабатывать `.php`-файлы. Мы добавили блок `location ~ \.php$`, чтобы перенаправлять PHP-запросы на FPM-сервер (`backend:9000`), а также указали корневую директорию.

## Выводы

В ходе лабораторной работы было создано многоконтейнерное PHP-приложение с использованием Nginx и PHP-FPM. Изучены принципы взаимодействия контейнеров в изолированной сети и настройка конфигурации Nginx для проксирования PHP-запросов. Получены практические навыки в создании и тестировании многоконтейнерных приложений с Docker.
