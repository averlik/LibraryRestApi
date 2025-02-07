# Library Rest API
Это проект для управления библиотекой, который реализует REST API с использованием фреймворка Laravel.

## Требования
Перед тем как запустить проект, убедитесь, что у вас установлены:

- PHP >= 8.0
- Composer
- MySQL (или MariaDB)
- Laravel >= 9.x
- XAMPP

## Установка

### 1. Установите XAMPP
Для работы с базой данных и локальным сервером необходимо установить XAMPP. После установки запустите Apache и MySQL через панель управления XAMPP.

### 2. Клонируйте репозиторий
```bash
git clone https://github.com/yourusername/LibraryRestApi.git
cd LibraryRestApi
```

### 3. Установите зависимости с помощью Composer
```bash
composer install
```

### 4. Настройте файл .env
Скопируйте файл `.env.example` в `.env`:
```bash
cp .env.example .env
```

### Настройте параметры подключения к базе данных в файле `.env`. Убедитесь, что MySQL настроен и доступен. Пример настроек:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=library
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Создайте ключ приложения
```bash
php artisan key:generate
```

### 6. Запустите миграции
```bash
php artisan migrate
```

### 7. Запустите сидеры для заполнения базы данных тестовыми данными
```bash
php artisan db:seed --class=BookSeeder
```

### 8. Запустите локальный сервер:
```bash
php artisan serve
```
Теперь Rest API доступно по адресу `http://localhost:8000`.

## Валидация
- **Почта:**  Регистрация разрешена только с определенными доменами. Если почта не соответствует указанным доменам, регистрация не будет выполнена. ( **домены:** gmail.com, mail.ru, yandex.ru, yahoo.com )
- **Имя:**  Имя не должно содержать цифры.
- **Пароль:**  Пароль должен быть не менее 6 символов.

## Использование
API имеет следующие конечные точки:

### Для пользователей:
- **Регистрация пользователя**  
  `POST /api/user/register`

```bash
  {
      "name": "User",
      "email": "user1@gmail.com",
      "password": "Pass123"
  }
```

- **Авторизация пользователя**  
  `POST /api/user/login`
    
```bash
  {
      "email": "user1@gmail.com",
      "password": "Pass123"
  }
```

- **Выход пользователя**  
  `POST /api/user/logout` (Требует авторизации)

### Для библиотекарей:

- **Регистрация библиотекаря через консоль**  
Для регистрации нового библиотекаря используется команда:
```bash
php artisan librarian:register "Иван Иванов" "librarian@gmail.com" "password123"
```
- **Авторизация библиотекаря**  
  `POST /api/librarian/login`

  ```bash
  {
      "email": "librarian@gmail.com",
      "password": "password123"
  }
    ```

- **Выход библиотекаря**  
  `POST /api/librarian/logout` (Требует авторизации)

### Маршруты для пользователей
Маршруты пользователей требуют для выполнения:
- Headers:
    - Authorization: Bearer <ТВОЙ_JWT_ТОКЕН>
    
- **Просмотр доступных книг**  
  `GET /api/books`
   Возвращает список всех доступных книг.

- **Взять книгу**  
  `POST /api/books/{book}/borrow`  
  Где `{book}` — это ID книги. Запрос требует авторизации.

- **Сдать книгу**  
  `POST /api/books/{book}/return`  
  Где `{book}` — это ID книги. Запрос требует авторизации.

- **Просмотр всех своих одолженных книг**  
  `GET /api/borrowed-books/user`  
  Возвращает список всех книг, которые были одолжены текущим пользователем.

### Маршруты для библиотекарей
Маршруты библиотекарей требуют для выполнения:
- Headers:
    - Authorization: Bearer <ТВОЙ_JWT_ТОКЕН>
      
#### Управление книгами:
- **Создание книги**  
  `POST /api/librarian/books`  
  Требует авторизации библиотекаря.
```bash
  {
  "title": "Книга Книжная",
  "author": "Автор"
  }
```

- **Просмотр всех книг**  
  `GET /api/librarian/books`  
  Возвращает список всех книг.

- **Обновление книги**  
  `PUT /api/librarian/books/{book}`  
  Где `{book}` — это ID книги.

- **Удаление книги**  
  `DELETE /api/librarian/books/{book}`  
  Где `{book}` — это ID книги.

#### Управление одолженными книгами:
- **Просмотр всех записей об одолженных книгах**  
  `GET /api/librarian/borrowed-books`  
  Возвращает список всех одолженных книг.


### Тестирование
#### Запуск тестов
Для того чтобы запустить автотесты, используйте команду:
```bash
php artisan test
```

