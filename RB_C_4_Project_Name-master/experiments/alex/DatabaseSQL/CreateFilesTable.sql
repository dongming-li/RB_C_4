CREATE TABLE FILES (
	id INT auto_increment primary key,
    filename varchar(50),
    project_id int,
    foreign key (project_id) references PROJECTS(id)
)