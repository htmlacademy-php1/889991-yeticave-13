<?php
/**
 * Формирует SQL-запрос для получения списка новых лотов от определенной даты, с сортировкой
 * @param string $date Дата в виде строки, в формате 'YYYY-MM-DD'
 * @return string SQL-запрос
 */
function get_query_list_lots ($date) {
    return "SELECT lots.id, lots.title, lots.start_price, lots.img, lots.date_finish, categories.name_category FROM lots
    JOIN categories ON lots.category_id=categories.id
    WHERE date_creation > $date ORDER BY date_creation DESC;";
}

/**
 * Формирует SQL-запрос для показа лота на странице lot.php
 * @param integer $id_lot id лота
 * @return string SQL-запрос
 */
function get_query_lot ($id_lot) {
    return "SELECT lots.title, lots.start_price, lots.img, lots.date_finish, lots.lot_description, categories.name_category FROM lots
    JOIN categories ON lots.category_id=categories.id
    WHERE lots.id=$id_lot;";
}
/**
 * Формирует SQL-запрос для создания нового лота
 * @param integer $user_id id пользователя
 * @return string SQL-запрос
 */
function get_query_create_lot ($user_id) {
    return "INSERT INTO lots (title, category_id, lot_description, start_price, step, date_finish, img, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, $user_id);";
}
/**
 * Возвращает массив категорий
 * @param $con Подключение к MySQL
 * @return [Array | String] $categuries Ассоциативный массив с категориями лотов из базы данных
 * или описание последней ошибки подключения
 */
function get_categories ($con) {
    if (!$con) {
    $error = mysqli_connect_error();
    return $error;
    } else {
        $sql = "SELECT id, character_code, name_category FROM categories;";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $categories = get_arrow($result);
            return $categories;
        }
        $error = mysqli_error($con);
        return $error;
    }
}

/**
 * Возвращает массив данных пользователей: адресс электронной почты и имя
 * @param $con Подключение к MySQL
 * @return [Array | String] $users_data Двумерный массив с именами и емейлами пользователей
 * или описание последней ошибки подключения
 */
function get_users_data($con) {
    if (!$con) {
        $error = mysqli_connect_error();
        return $error;
    }
    $sql = "SELECT email, user_name FROM users;";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $users_data= get_arrow($result);
        return $users_data;
    }
    $error = mysqli_error($con);
    return $error;
}

/**
 * Формирует SQL-запрос для регистрации нового пользователя
 * @param integer $user_id id пользователя
 * @return string SQL-запрос
 */
function get_query_create_user() {
    return "INSERT INTO users (date_registration, email, user_password, user_name, contacts) VALUES (NOW(), ?, ?, ?, ?);";
}

/**
 * Записывает в БД данные пользователя из формы
 * @param $link mysqli Ресурс соединения
 * @param array $data Данные пользователя, полученные из формы
 * @return bool $res Возвращает true в случае успешного выполнения
 */
function add_user_database($link, $data = []) {
    $sql = "INSERT INTO users (date_registration, email, user_password, user_name, contacts) VALUES (NOW(), ?, ?, ?, ?);";
    $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);

    $stmt = db_get_prepare_stmt_version($link, $sql, $data);
    $res = mysqli_stmt_execute($stmt);
    return $res;
}
/**
 * Возвращает массив данных пользователя: id адресс электронной почты имя и хеш пароля
 * @param $con Подключение к MySQL
 * @param $email введенный адрес электронной почты
 * @return [Array | String] $users_data Массив с данными пользователя: id адресс электронной почты имя и хеш пароля
 * или описание последней ошибки подключения
 */
function get_login($con, $email) {
    if (!$con) {
    $error = mysqli_connect_error();
    return $error;
    }
    $sql = "SELECT id, email, user_name, user_password FROM users WHERE email = '$email'";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $users_data= get_arrow($result);
        return $users_data;
    }
    $error = mysqli_error($con);
    return $error;
}

/**
 * Возвращает массив лотов соответствующих поисковым словам
 * @param $link mysqli Ресурс соединения
 * @param string $words ключевые слова введенные ползователем в форму поиска
 * @return [Array | String] $goods Двумерный массив лотов, в названии или описании которых есть такие слова
 * или описание последней ошибки подключения
 */
function get_found_lots($link, $words, $limit, $offset) {
    $sql = "SELECT lots.id, lots.title, lots.start_price, lots.img, lots.date_finish, categories.name_category FROM lots
    JOIN categories ON lots.category_id=categories.id
    WHERE MATCH(title, lot_description) AGAINST(?) ORDER BY date_creation DESC LIMIT $limit OFFSET $offset;";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 's', $words);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        $goods = get_arrow($res);
        return $goods;
    }
    $error = mysqli_error($con);
    return $error;
}
/**
 * Возвращает количество лотов соответствующих поисковым словам
 * @param $link mysqli Ресурс соединения
 * @param string $words ключевые слова введенные ползователем в форму поиска
 * @return [int | String] $count Количество лотов, в названии или описании которых есть такие слова
 * или описание последней ошибки подключения
 */
function get_count_lots($link, $words) {
    $sql = "SELECT COUNT(*) as cnt FROM lots
    WHERE MATCH(title, lot_description) AGAINST(?);";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 's', $words);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        $count = mysqli_fetch_assoc($res)["cnt"];
        return $count;
    }
    $error = mysqli_error($con);
    return $error;
}



