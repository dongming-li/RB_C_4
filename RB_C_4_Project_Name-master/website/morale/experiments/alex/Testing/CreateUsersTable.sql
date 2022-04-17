CREATE table USERS (
	id INT unique auto_increment primary key,
    first_name varchar(60),
    last_name varchar(60),
    email varchar(60),
    username varchar(60),
    password varchar(60),
    user_type ENUM('MANAGER', 'DEVELOPER', 'CLIENT')
)