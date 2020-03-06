drop table if exists `#__mkv_price_watchers`, `#__mkv_price_items`, `#__mkv_price_units`, `#__mkv_price_apps`, `#__mkv_price_sections`, `#__mkv_prices`;

create table `#__mkv_prices` (
                                    id smallint unsigned not null auto_increment primary key,
                                    title varchar(255) not null comment 'Название прайс-листа'
) character set utf8 collate utf8_general_ci;

insert into `#__mkv_prices` select id, title from `#__prc_prices`;

create table `#__mkv_price_sections` (
                                            id smallint unsigned not null auto_increment primary key,
                                            priceID smallint unsigned not null,
                                            title varchar(255) not null comment 'Название раздела прайс-листа',
                                            constraint `#__mkv_price_sections_#__mkv_prices_priceID_id_fk` foreign key (priceID) references `#__mkv_prices` (id) on update cascade on delete cascade
) character set utf8 collate  utf8_general_ci;

insert into `#__mkv_price_sections` select id, priceID, title from `#__prc_sections`;

create table `#__mkv_price_units` (
                                         id tinyint unsigned not null auto_increment primary key,
                                         title varchar(255) not null comment 'Название единицы измерения',
                                         weight tinyint not null default 0 comment 'Вес единицы',
                                         index `#__mkv_price_units_weight_index` (weight)
) character set utf8 collate  utf8_general_ci;

insert into `#__mkv_price_units` values
(1, 'шт.', 0),
(2, 'кв. м.', 2),
(3, 'наб.', 4),
(4, 'симв.', 6),
(5, 'пар.', 8),
(6, 'п.м.', 10),
(7, 'день', 12),
(8, 'час', 14),
(9, 'ночь', 16),
(10, '4 ч.', 18),
(11, '1 д. / 1 кв. м.', 20),
(12, '1 кв. м. / 1 пер.', 22),
(13, '1 сут. / 1 кв. м.', 24),
(14, 'вид', 26),
(15, '1 чел. / день', 28),
(16, 'чел.', 30),
(17, 'за 1 кв. м.', 32);

create table `#__mkv_price_apps` (
                                        id tinyint unsigned not null auto_increment primary key,
                                        title varchar(255) not null comment 'Название приложения в договоре',
                                        weight tinyint not null default 0 comment 'Вес единицы',
                                        index `#__mkv_price_apps_weight_index` (weight)
) character set utf8 collate  utf8_general_ci;

insert into `#__mkv_price_apps` values
(1, 'Договор', 0),
(2, 'Приложение 1', 5),
(3, 'Приложение 2', 10),
(4, 'Приложение 3', 15),
(5, 'Приложение 4', 20),
(6, 'Дополнительно', 25);

create table `#__mkv_price_items` (
                                         id smallint unsigned not null auto_increment primary key,
                                         sectionID smallint unsigned not null,
                                         appID tinyint unsigned not null,
                                         unit_1_ID tinyint unsigned not null,
                                         unit_2_ID tinyint unsigned null default null,
                                         type set('square', 'electric', 'internet', 'multimedia', 'water', 'cleaning', 'badge') null default null,
                                         price_rub decimal(11,2) not null default 0,
                                         price_usd decimal(9,2) not null default 0,
                                         price_eur decimal(9,2) not null default 0,
                                         column_1 decimal(3,2) not null default 1,
                                         column_2 decimal(3,2) not null default 1.50,
                                         column_3 decimal(3,2) not null default 2,
                                         title text not null,
                                         title_en text null default null,
                                         disabled boolean not null default 0,
                                         weight tinyint not null default 0,
                                         constraint `#__mkv_price_items_#__mkv_price_sections_sectionID_id_fk` foreign key (sectionID) references `#__mkv_price_sections` (id),
                                         constraint `#__mkv_price_items_#__mkv_price_apps_appID_id_fk` foreign key (appID) references `#__mkv_price_apps` (id),
                                         constraint `#__mkv_price_items_#__mkv_price_units_unit_1_ID_id_fk` foreign key (unit_1_ID) references `#__mkv_price_units` (id),
                                         constraint `#__mkv_price_items_#__mkv_price_units_unit_2_ID_id_fk` foreign key (unit_2_ID) references `#__mkv_price_units` (id),
                                         index `#__mkv_price_items_type_index` (type),
                                         index `#__mkv_price_items_disabled_index` (disabled),
                                         index `#__mkv_price_items_weight_index` (weight)
) character set utf8 collate utf8_general_ci;

insert into `#__mkv_price_items`
select id, sectionID,
       if(application = 'contract', 1, if(application = 'app1', 2, if (application = 'app2', 3, if (application = 'app3', 4, if (application = 'app4', 5, 6))))),
       if(unit='piece',1,if(unit='sqm',2,if(unit='kit',3,if(unit='letter',4,if(unit='pair',5,if(unit='sym',4,if(unit='pm',6,if(unit='days',7,if(unit='hours',8,if(unit='nights',9,if(unit='4h',10,if(unit='1d1sqm',11,if(unit='1sqm1p',12,if(unit='1s1sqm',13,if(unit='view',14,if(unit='1pd',15,if(unit='ppl',16,17))))))))))))))))),
       if(unit_2='piece',1,if(unit_2='sqm',2,if(unit_2='kit',3,if(unit_2='letter',4,if(unit_2='pair',5,if(unit_2='sym',4,if(unit_2='pm',6,if(unit_2='days',7,if(unit_2='hours',8,if(unit_2='nights',9,if(unit_2='4h',10,if(unit_2='1d1sqm',11,if(unit_2='1sqm1p',12,if(unit_2='1s1sqm',13,if(unit_2='view',14,if(unit_2='1pd',15,if(unit_2='ppl',16,17))))))))))))))))),
       if(is_sq=1,'square',if(is_electric=1,'electric',if(is_internet=1,'internet',if(is_multimedia=1,'multimedia',if(is_water=1,'water',if(is_cleaning=1,'cleaning',null)))))),
       price_rub, price_usd, price_eur, column_1, column_2, column_3, title_ru, title_en, stop, 0
from `#__prc_items`;

create table `#__mkv_price_watchers` (
                                            id smallint unsigned not null auto_increment primary key,
                                            itemID smallint unsigned not null,
                                            userID int not null,
                                            constraint `#__mkv_price_watchers_#__mkv_price_items_itemID_id_fk` foreign key (itemID) references `#__mkv_price_items` (id),
                                            constraint `#__mkv_price_watchers_#__users_userID_id_fk` foreign key (userID) references `#__users` (id)
) character set utf8 collate utf8_general_ci;

insert into `#__mkv_price_watchers` select id, itemID, managerID from `#__prc_item_notifies`;

