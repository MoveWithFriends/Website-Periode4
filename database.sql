create schema MWFregistration collate utf8_general_ci;

create table users
(
	id int auto_increment
		primary key,
	firstname varchar(50) not null,
	lastname varchar(50) not null,
	email varchar(255) not null,
	password_hash varchar(255) not null,
	phonenumber int(10) not null,
	gender varchar(10) null,
	birthdate date not null,
	joindate timestamp default CURRENT_TIMESTAMP not null,
	password_reset_hash varchar(64) null,
	password_reset_expires_at datetime null,
	activation_hash varchar(64) null,
	is_active tinyint(1) default 0 not null,
	constraint activation_hash
		unique (activation_hash),
	constraint email
		unique (email),
	constraint password_reset_hash
		unique (password_reset_hash)
);

create table remembered_logins
(
	token_hash varchar(64) not null
		primary key,
	user_id int not null,
	expires_at datetime not null,
	constraint remembered_logins_ibfk_1
		foreign key (user_id) references users (id)
			on update cascade on delete cascade
);

create index user_id
	on remembered_logins (user_id);

