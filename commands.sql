create database sample_db;

use sample_db;

create table members (
id int auto_increment primary key,
name varchar(255),
email varchar(255),
created_at datetime,
updated_at datetime
);

