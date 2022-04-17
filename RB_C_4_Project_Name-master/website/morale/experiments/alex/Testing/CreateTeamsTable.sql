CREATE TABLE TEAMS (
	id INT unique auto_increment primary key,
    manager_id int,
    foreign key (manager_id) references MANAGERS(id)
);