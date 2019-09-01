/* заполняем категории */
INSERT INTO categories (name , symbol_code) VALUES ('Доски и лыжи' , 'boards'); 
INSERT INTO categories (name , symbol_code) VALUES ('Крепления ' , 'attachment');
INSERT INTO categories (name , symbol_code) VALUES ('Ботинки' , 'boots');
INSERT INTO categories (name , symbol_code) VALUES ('Одежда' , 'clothing');
INSERT INTO categories (name , symbol_code) VALUES ('Инструменты' , 'tools');
INSERT INTO categories (name , symbol_code) VALUES ('Разное' , 'other');

/* добавляем юзеров */
INSERT INTO users (registration_date , email, username, pasword, contacts  ) 
VALUES (NOW() , 'user1@mail.ru', 'user1', 'user1pass' , 'Brooklyn , street'); 
INSERT INTO users (registration_date , email, username, pasword, contacts  ) 
VALUES (NOW() , 'user2@mail.ru', 'user2', 'user2pass' , 'Saratov , home'); 
INSERT INTO users (registration_date , email, username, pasword, contacts  ) 
VALUES (NOW() , 'user3@mail.ru', 'user3', 'user3pass' , 'Torino , home'); 
INSERT INTO users (registration_date , email, username, pasword, contacts  ) 
VALUES (NOW() , 'user4@mail.ru', 'user4', 'user4pass' , 'Manchester , street'); 

/* Добавляем лоты */
INSERT INTO lots ( user_id_author, user_id_winner, category_id, date_create, name, description, img_ref, start_price, date_finish, bet_step ) 
VALUES (1, 2, 1, NOW() , '2014 Rossignol District Snowboard', 'lotdescript#1', 'img/lot-1.jpg' , 10999 , '2019-08-31' , 10); 


INSERT INTO lots ( user_id_author, user_id_winner, category_id, date_create, name, description, img_ref, start_price, date_finish, bet_step ) 
VALUES (2, 3, 1, NOW() , 'DC Ply Mens 2016/2017 Snowboard', 'lotdescript#2', 'img/lot-2.jpg' , 159999 , '2019-09-01' , 15); 

INSERT INTO lots ( user_id_author, user_id_winner, category_id, date_create, name, description, img_ref, start_price, date_finish, bet_step ) 
VALUES (4, 2, 2, NOW() , 'Крепления Union Contact Pro 2015 года размер L/XL', 'lotdescript#3', 'img/lot-3.jpg' , 8000 , '2019-08-30' , 5); 

INSERT INTO lots ( user_id_author, user_id_winner, category_id, date_create, name, description, img_ref, start_price, date_finish, bet_step ) 
VALUES (2, 1, 3, NOW() , 'Ботинки для сноуборда DC Mutiny Charocal', 'lotdescript#4', 'img/lot-4.jpg' , 10999 , '2019-09-05' , 4);

INSERT INTO lots ( user_id_author, user_id_winner, category_id, date_create, name, description, img_ref, start_price, date_finish, bet_step ) 
VALUES (1, 3, 4, NOW() , 'Куртка для сноуборда DC Mutiny Charocal', 'lotdescript#5', 'img/lot-5.jpg' , 7500 , '2019-10-11', 15);

INSERT INTO lots ( user_id_author, user_id_winner, category_id, date_create, name, description, img_ref, start_price, date_finish, bet_step ) 
VALUES (3, 2, 6, NOW() , 'Маска Oakley Canopy', 'lotdescript#6', 'img/lot-6.jpg' , 5400 , '2019-09-09', 20);
 
/* добавляем ставки */
INSERT INTO bets ( user_id, lot_id, date_create, price ) 
VALUES (3, 5, NOW(), 1500); 

INSERT INTO bets ( user_id, lot_id, date_create, price ) 
VALUES (1, 1, NOW(), 2500); 

INSERT INTO bets ( user_id, lot_id, date_create, price ) 
VALUES (4, 2, NOW(), 3500); 

/*Выборка категорий*/
SELECT name FROM categories ORDER BY id;

/*получить самые новые, открытые лоты*/
SELECT l.name, l.start_price, l.img_ref, c.name, b.price FROM lots l 
JOIN categories c ON c.id=l.category_id
LEFT JOIN bets b ON b.lot_id=l.id 
ORDER BY l.date_create

/* получить название категории лота */
SELECT l.id, c.name FROM lots l 
JOIN categories c ON l.category_id=c.id ORDER BY l.id

/*обновить название лота по его идентификатору */
UPDATE lots SET name="NEW NAME" WHERE id=1;

/*получить список ставок для лота по его идентификатору с сортировкой по дате */
SELECT * FROM bets  WHERE lot_id=5 ORDER BY date_create
