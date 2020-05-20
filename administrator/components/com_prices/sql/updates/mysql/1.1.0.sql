alter table `#__mkv_price_items`
    add square_type tinyint null default null after type,
    add index `#__mkv_price_items_square_type_index` (square_type);
