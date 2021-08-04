INSERT INTO categories (character_code, name_category)
VALUES
	('boards', 'Доски и лыжи'),
  ('attachment', 'Крепления'),
	('boots', 'Ботинки'),
  ('clothing', 'Одежда'),
  ('tools', 'Инструменты'),
  ('other', 'Разное');

INSERT INTO users
	(email, user_name, user_password, contacts)
VALUES
	('hero34@mail.ru', 'Ярослав', 'secretpassw1', '89191202527'),
  ('asis174@mail.ru', 'Слава', 'secretpassw2', '83512254836');

INSERT INTO lots
	(title, lot_description, img, start_price, date_finish, step, user_id, category_id)
VALUES
	('2014 Rossignol District Snowboard', 'Легкий маневренный сноуборд, готовый дать жару в любом парке', 'img/lot-1.jpg', 10999, '2021-08-10', 500, 1, 1),
  ('DC Ply Mens 2016/2017 Snowboard', 'Легкий маневренный сноуборд, готовый дать жару в любом парке', 'img/lot-2.jpg', 159999, '2021-08-11', 1000, 2, 1),
  ('Крепления Union Contact Pro 2015 года размер L/XL', 'Хорошие крепления, надежные и легкие', 'img/lot-3.jpg', 8000, '2021-08-12', 500, 2, 2),
  ('Ботинки для сноуборда DC Mutiny Charocal', 'Теплые и красивые ботинки', 'img/lot-4.jpg', 10999, '2021-08-13', 600, 1, 3),
  ('Куртка для сноуборда DC Mutiny Charocal', 'Легкая, теплая и прочная куртка', 'img/lot-5.jpg', 7500, '2021-08-14', 500, 1, 4),
  ('Маска Oakley Canopy', 'Желтые очки, все будет веселенькое', 'img/lot-6.jpg', 5400, '2021-08-15', 100, 1, 6);

INSERT INTO bets
	(price_bet, user_id, lot_id)
VALUES
	(8500, 1, 4);
INSERT INTO bets
	(price_bet, user_id, lot_id)
VALUES
	(9000, 1, 4);


--Получаем все категории
SELECT name_category AS 'Категории' FROM categories;


--Получаем открытые лоты, в каждом получаем название, стартовую цену, ссылку на изображение, название категории
SELECT lots.title, lots.start_price, lots.img, categories.name_category
FROM lots JOIN categories ON lots.category_id=categories.id;

--Показываем лот по его ID и получаем название категории, к которой принадлежит лот
SELECT lots.id, lots.date_creation, lots.title, lots.lot_description, lots.img, lots.start_price, lots.date_finish, lots.step, categories.name_category
FROM lots JOIN categories ON lots.category_id=categories.id
WHERE lots.id=4;

--Обновляем название лота по его идентификатору
UPDATE lots
SET title='Ботинки для сноуборда обычные'
WHERE id=4;

--Получаем список ставок для лота по его идентификатору с сортировкой по дате, начиная с самой последней
SELECT bets.date_bet, bets.price_bet, lots.title, users.user_name
FROM bets
JOIN lots ON bets.lot_id=lots.id
JOIN users ON bets.user_id=users.id
WHERE lots.id=4
ORDER BY bets.date_bet DESC;
