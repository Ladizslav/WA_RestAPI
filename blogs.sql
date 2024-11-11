use my_api_db;

create table blogs (
    ID int primary key auto_increment,
    uzivatel_id int,
    text varchar(100) not null,
    date date,
    foreign key (uzivatel_id) references uzivatel(ID)
);


create table uzivatel (
    ID int primary key auto_increment,
    username varchar(30) not null,
    password varchar(255) not null,
    admin bit default 0
);


create table access (
    ID int primary key auto_increment,
    blogs_id int,
    uzivatel_id int,
    foreign key (blogs_id) references blogs(ID),
    foreign key (uzivatel_id) references uzivatel(ID)
);

delimiter //
create procedure addaccess(_blogid int, _user varchar(30))
begin
    declare _uzivatel_id int;
    select ID into _uzivatel_id from uzivatel where username = _user;
    if (_uzivatel_id is not null) then
        insert into access(uzivatel_id, blogs_id)
        select _uzivatel_id, _blogid
        where not exists (select 1 from access where blogs_id = _blogid and uzivatel_id = _uzivatel_id);
    end if;
end //
delimiter ;

delimiter //
create procedure viewblogs(_user varchar(30))
begin
    declare _uzivatel_id int;
    select ID into _uzivatel_id from uzivatel where username = _user;
    select b.text, u.username, b.date, b.ID
    from blogs as b
    inner join uzivatel as u on b.uzivatel_id = u.ID
    where b.ID in (select a.blogs_id from access as a where a.uzivatel_id = _uzivatel_id)
       or b.ID not in (select a.blogs_id from access as a)
       or b.uzivatel_id = _uzivatel_id
    order by b.date desc;
end //
delimiter ;
