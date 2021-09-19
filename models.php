<?php
/**
 * Формирует SQL-запрос для получения списка новых лотов от определенной даты, с сортировкой
 * @param string $date Дата в виде строки, в формате 'YYYY-MM-DD'
 * @return string SQL-запрос
 */
function get_query_list_lots () {
    return "SELECT lots.id, lots.title, lots.start_price, lots.img, lots.date_finish, categories.name_category FROM lots
    JOIN categories ON lots.category_id=categories.id
    ORDER BY date_creation DESC;";
}

/**
 * Формирует SQL-запрос для показа лота на странице lot.php
 * @param integer $id_lot id лота
 * @return string SQL-запрос
 */
function get_query_lot ($id_lot) {
    return "SELECT lots.title, lots.start_price, lots.img, lots.date_finish, lots.lot_description, lots.step, lots.user_id, categories.name_category FROM lots
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
 * @return [Array | String] $categories Ассоциативный массив с категориями лотов из базы данных
 * или описание последней ошибки подключения
 */
function get_categories ($con) {
    if (!$con) {
    $error = mysqli_connect_error();
    return $error;
    }
    $sql = "SELECT id, character_code, name_category FROM categories;";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $categories = get_arrow($result);
        return $categories;
    }
    $error = mysqli_error($con);
    return $error;
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
    $result = mysqli_stmt_get_result($stmt);
    if ($result) {
        $goods = mysqli_fetch_all($result, MYSQLI_ASSOC);
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

/**
 * Записывает в БД сделанную ставку
 * @param $link mysqli Ресурс соединения
 * @param int $sum Сумма ставки
 * @param int $user_id ID пользователя
 * @param int $lot_id ID лота
 * @return bool $res Возвращает true в случае успешной записи
 */
function add_bet_database($link, $sum, $user_id, $lot_id) {
    $sql = "INSERT INTO bets (date_bet, price_bet, user_id, lot_id) VALUE (NOW(), ?, $user_id, $lot_id);";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $sum);
    $res = mysqli_stmt_execute($stmt);
    if ($res) {
        return $res;
    }
    $error = mysqli_error($con);
    return $error;
}

/**
 * Возвращает массив из десяти последних ставок на этот лот
 * @param $con Подключение к MySQL
 * @param int $id_lot Id лота
 * @return [Array | String] $list_bets Ассоциативный массив со списком ставок на этот лот из базы данных
 * или описание последней ошибки подключения
 */
function get_bets_history ($con, $id_lot) {
    if (!$con) {
    $error = mysqli_connect_error();
    return $error;
    }
    $sql = "SELECT users.user_name, bets.price_bet, DATE_FORMAT(date_bet, '%d.%m.%y %H:%i') AS date_bet
    FROM bets
    JOIN lots ON bets.lot_id=lots.id
    JOIN users ON bets.user_id=users.id
    WHERE lots.id=$id_lot
    ORDER BY bets.date_bet DESC LIMIT 10;";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $list_bets = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $list_bets;
    }
    $error = mysqli_error($con);
    return $error;

}
/**
 * Возвращает массив ставок пользователя
 * @param $con Подключение к MySQL
 * @param int $id Id пользователя
 * @return [Array | String] $list_bets Ассоциативный массив ставок
 *  пользователя из базы данных
 * или описание последней ошибки подключения
 */
function get_bets ($con, $id) {
    if (!$con) {
    $error = mysqli_connect_error();
    return $error;
    }
    $sql = "SELECT DATE_FORMAT(bets.date_bet, '%d.%m.%y %H:%i') AS date_bet, bets.price_bet, lots.title, lots.lot_description, lots.img, lots.date_finish, lots.id, lots.winner_id, categories.name_category, users.contacts
    FROM bets
    JOIN lots ON bets.lot_id=lots.id
    JOIN users ON bets.user_id=users.id
    JOIN categories ON lots.category_id=categories.id
    WHERE bets.user_id=$id
    ORDER BY bets.date_bet DESC;";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $list_bets = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $list_bets;
    }
    $error = mysqli_error($con);
    return $error;

}
/**
 * Возвращает массив лотов у которых истек срок окончания торгов и нет победетеля
 * @param $con Подключение к MySQL
 * @return [Array | String] $lots массив лотов
 * или описание последней ошибки подключения
 */
function get_lot_date_finish ($con) {
    if (!$con) {
        $error = mysqli_connect_error();
        return $error;
    }
    $sql = "SELECT * FROM lots
    where winner_id IS NULL && date_finish <= now();";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $lots;
    }
    $error = mysqli_error($con);
    return $error;
}

/**
 * Возвращает последнюю ставку на лот
 * @param $con Подключение к MySQL
 * @param $id ID лота
 * @return [Array | String] $bet массив с описанием ставки
 * или описание последней ошибки подключения
 */
function get_last_bet ($con, $id) {
    if (!$con) {
        $error = mysqli_connect_error();
        return $error;
    }
    $sql = "SELECT * FROM bets
    where lot_id = $id
    ORDER BY date_bet DESC LIMIT 1;";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $bet = get_arrow($result);
        return $bet;
    }
    $error = mysqli_error($con);
    return $error;

}

/**
 * Записывает в таблицу лотов в базе данных ID победителя торгов по конкретному лоту
 * @param $con Подключение к MySQL
 * @param $winer_id ID победителя торгов
 * @param $lot_id ID лота
 * @return [Bool | String] $res Возвращает true в случае успешной записи
 * или описание последней ошибки подключения
 */
function add_winner ($con, $winer_id, $lot_id) {
    if (!$con) {
        $error = mysqli_connect_error();
        return $error;
    }
    $sql = "UPDATE lots SET winner_id=$winer_id WHERE id = $lot_id";
    $result = mysqli_query($con, $sql);
    if ($result) {
        return $result;
    }
        $error = mysqli_error($con);
        return $error;

}

/**
 * Возвращает email, телефон и имя пользователя по id
 * @param $con Подключение к MySQL
 * @param $id ID пользователя
 * @return [Array | String] $user_date массив
 * или описание последней ошибки подключения
 */
function get_user_contacts ($con, $id) {
    if (!$con) {
    $error = mysqli_connect_error();
    return $error;
    }
    $sql = "SELECT users.user_name, users.email, users.contacts FROM users
    WHERE id=$id;";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $user_date = get_arrow($result);
        return $user_date;
    }
    $error = mysqli_error($con);
    return $error;
}

/**
 * Возвращает имя пользователя и название лота для письма
 * @param $con Подключение к MySQL
 * @param $id ID лота
 * @return [Array | String] $data массив
 * или описание последней ошибки подключения
 */
function get_user_win ($con, $id) {
    if (!$con) {
    $error = mysqli_connect_error();
    return $error;
    }
    $sql = "SELECT lots.id, lots.title, users.user_name, users.contacts
    FROM bets
    JOIN lots ON bets.lot_id=lots.id
    JOIN users ON bets.user_id=users.id
    WHERE lots.id = $id;";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $data = get_arrow($result);
        return $data;
    }
    $error = mysqli_error($con);
    return $error;
}

/**
 * Возвращает контакты владельца лота
 * @param $con Подключение к MySQL
 * @param $id ID лота
 * @return [Array | String] $contacts массив
 * или описание последней ошибки подключения
 */
function get_user_tell ($con, $id) {
    if (!$con) {
    $error = mysqli_connect_error();
    return $error;
    }
    $sql = "SELECT  users.contacts AS tell FROM lots
    JOIN users ON users.id=lots.user_id
    WHERE lots.id = $id;";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $contacts = get_arrow($result);
        return $contacts;
    }
    $error = mysqli_error($con);
    return $error;

}

/**
 * Возвращает количество лотов данной категории
 * @param $con mysqli Ресурс соединения
 * @param int $id - id категории
 * @return [int | String] $count Количество лотов
 * или описание последней ошибки подключения
 */
function get_count_lot_cat ($con, $id) {
    if (!$con) {
    $error = mysqli_connect_error();
    return $error;
    }
    $sql = "SELECT COUNT(*) as cnt FROM lots
    WHERE category_id=$id";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $count = mysqli_fetch_assoc($result)["cnt"];
        return $count;
    }
    $error = mysqli_error($con);
    return $error;
}

/**
 * Возвращает массив лотов соответствующей категории
 * @param $link mysqli Ресурс соединения
 * @param int $id - id категории
 * @return [Array | String] $goods Двумерный массив лотов, в названии или описании которых есть такие слова
 * или описание последней ошибки подключения
 */
function get_lots_cat ($con, $id, $limit, $offset) {
    if (!$con) {
        $error = mysqli_connect_error();
        return $error;
        }
        $sql = "SELECT * FROM lots
        WHERE category_id=$id ORDER BY date_creation DESC LIMIT $limit OFFSET $offset;";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $goods = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $goods;
        }
        $error = mysqli_error($con);
        return $error;
}

/**
 * Возвращает название категории
 * @param $con mysqli Ресурс соединения
 * @param int $id - id категории
 * @return [String | String] $count Количество лотов
 * или описание последней ошибки подключения
 */
function get_category_name ($con, $id) {
    if (!$con) {
    $error = mysqli_connect_error();
    return $error;
    }
    $id = intval($id);
    $sql = "SELECT name_category FROM categories
    WHERE id = $id";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $count = mysqli_fetch_assoc($result)["name_category"];
        return $count;
    }
    $error = mysqli_error($con);
    return $error;

}
