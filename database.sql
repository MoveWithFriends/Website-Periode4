-- auto-generated definition
-- database MoveWithFriends
create table users
(
    id                        int(10) auto_increment
        primary key,
    firstname                 varchar(50)                          not null,
    lastname                  varchar(50)                          not null,
    email                     varchar(100)                         not null,
    password_hash             varchar(255)                         not null,
    phonenumber               varchar(20)                          not null,
    gender                    varchar(10)                          null,
    birthdate                 date                                 null,
    joindate                  timestamp  default CURRENT_TIMESTAMP not null,
    password_reset_hash       varchar(64)                          null,
    password_reset_expires_at datetime                             null,
    activation_hash           varchar(64)                          null,
    is_active                 tinyint(1) default 0                 not null,
    is_admin                  tinyint(1) default 0                 null,
    preferredgender           varchar(10)                          null,
    constraint activation_hash
        unique (activation_hash),
    constraint email
        unique (email),
    constraint password_reset_hash
        unique (password_reset_hash)
);

-- auto-generated definition
create table Timeslot
(
    id       int(10)     auto_increment not null
        primary key,
    Timeslot varchar(12) null
);

-- auto-generated definition
create table Interest
(
    id        int(10) auto_increment
        primary key,
    Interest  varchar(50)          not null,
    is_active tinyint(1) default 1 null
);

create index Interest
    on Interest (Interest);

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

-- auto-generated definition
create table Likes
(
    Interest    int(10) auto_increment
        primary key,
    id_user     int(10) not null,
    id_Interest int(10) not null,
    constraint likes_ibfk_1
        foreign key (id_Interest) references Interest (id)
            on update cascade on delete cascade,
    constraint likes_ibfk_2
        foreign key (id_user) references users (id)
            on update cascade on delete cascade
);

create index id_Interest
    on Likes (id_Interest);

create index id_user
    on Likes (id_user);
    
-- auto-generated definition
create table Day
(
    id  int(10)     not null
        primary key,
    Day varchar(12) null
);

-- auto-generated definition
create table search
(
    id          int auto_increment
        primary key,
    field       varchar(100) null,
    countresult int(10)      null,
    query       varchar(10)  null
);

-- auto-generated definition
create table Available
(
    id_User     int(10) not null,
    id_Day      int(10) not null,
    id_Timeslot int(10) not null,
    primary key (id_User, id_Day, id_Timeslot),
    constraint available_ibfk_1
        foreign key (id_User) references users (id)
            on update cascade on delete cascade,
    constraint available_ibfk_2
        foreign key (id_Day) references Day (id)
            on update cascade on delete cascade,
    constraint available_ibfk_3
        foreign key (id_Timeslot) references Timeslot (id)
            on update cascade on delete cascade
);

create index id_Day
    on Available (id_Day);

create index id_Timeslot
    on Available (id_Timeslot);



-- auto-generated definition
create view combined as
select sametime.NameA           AS NameA,
       sametime.NameB           AS NameB,
       sametime.id_Day          AS id_Day,
       sametime.id_Timeslot     AS id_Timeslot,
       sameinterest.id_Interest AS id_Interest
from ((movewithfriends.sametime join movewithfriends.sameinterest on ((
        (sametime.NameA = sameinterest.NameA) and (sametime.NameB = sameinterest.NameB))))
         join movewithfriends.gendermatch
              on (((sametime.NameA = gendermatch.NameA) and (sametime.NameB = gendermatch.NameB))));

-- auto-generated definition
create view gendermatch as
select A.id AS NameA, B.id AS NameB
from (movewithfriends.users A
         join movewithfriends.users B on (((A.id < B.id) and ((A.preferredgender = B.gender) or
                                                                            (A.preferredgender = 'geen')) and
                                                 ((B.preferredgender = A.gender) or
                                                  (B.preferredgender = 'geen')))));

-- auto-generated definition
create view sameinterest as
select A.id_user AS NameA, B.id_user AS NameB, A.id_Interest AS id_Interest
from (movewithfriends.Likes A
         join movewithfriends.Likes B
              on (((A.id_Interest = B.id_Interest) and (A.id_user <> B.id_user))));

-- auto-generated definition
create view sametime as
select A.id_User AS NameA, B.id_User AS NameB, A.id_Day AS id_Day, A.id_Timeslot AS id_Timeslot
from (movewithfriends.available A
         join movewithfriends.available B
              on (((A.id_Day = B.id_Day) and (A.id_Timeslot = B.id_Timeslot) and
                   (A.id_User <> B.id_User))));







