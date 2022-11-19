-- Use this to create the table
create table links
(
    id   int          not null auto_increment primary key,
    code varchar(32)  not null unique,
    link varchar(255) not null
);