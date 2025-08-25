create database if not exists sys_register;

USE sys_register;

create table users (
    id int auto_increment primary key,
    name_ varchar(100) not null,
    email varchar(150) not null unique,
    password_ varchar(250) not null,
    phone varchar(200) not null,
    adress varchar(100) not null,
    state char (2),
    birthDate date not null,
    created timestamp default current_timestamp
);