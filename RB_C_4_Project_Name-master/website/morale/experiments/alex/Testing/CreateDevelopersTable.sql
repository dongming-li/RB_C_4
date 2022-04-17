CREATE TABLE DEVELOPERS (
	id int auto_increment primary key,
    first_name varchar(60),
    last_name varchar(60),
    email varchar(60),
    username varchar(60),
    password varchar(60),
    manager_id int,
    foreign key (manager_id) references MANAGERS(id)
)