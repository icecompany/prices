alter table `#__mkv_price_items`
    add available decimal(11,2) null default null comment 'В наличии единиц' after disabled,
    add need_period boolean not null default 0 after available,
    add index `#__mkv_price_items_available_index` (available),
    add index `#__mkv_price_items_need_period_index` (need_period);



