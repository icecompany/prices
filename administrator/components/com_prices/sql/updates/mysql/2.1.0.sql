create table `#__mkv_price_equipments` (
                                              id smallint unsigned not null auto_increment primary key,
                                              type enum('equipment', 'service', 'advert') not null default 'equipment',
                                              title text not null,
                                              index `#__mkv_price_equipments_type_index` (type)
) character set utf8mb4 collate utf8mb4_general_ci;

insert into `#__mkv_price_equipments` (
    select distinct null, if(appID = 3, 'equipment', if (appID = 4, 'service', 'advert')), substr(title from locate(' ', title) + 1)
    from `#__mkv_price_items`
    where appID in (3, 4, 5)
      and title regexp "^[0-4]{1,3}"
);

insert into `#__mkv_price_equipments` (
    select distinct null, if(appID = 3, 'equipment', if (appID = 4, 'service', 'advert')), title
    from `#__mkv_price_items`
    where appID in (3, 4, 5)
      and title not regexp "^[0-4]{1,3}"
);

alter table `#__mkv_price_items`
    add equipmentID smallint null default null after unit_2_ID;

update `#__mkv_price_items` pi
    left join `#__mkv_price_equipments` eq on substr(pi.title from locate(' ', pi.title) + 1) = eq.title
set pi.equipmentID = eq.id
where pi.appID in (3, 4, 5);
