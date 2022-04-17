CREATE TABLE CLIENTS (
	id INT auto_increment primary key,
    first_name varchar(60),
    last_name varchar(60),
    email varchar (60),
    company_name varchar(60),
    username varchar(60),
    password varchar(60),
    project_id int,
    foreign key (project_id) references PROJECTS(id)
)