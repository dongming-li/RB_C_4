CREATE TABLE PROJECTS (
	id int unique primary key,
	project_name varchar(100),
    manager_id int,
    team_id int,
	foreign key (manager_id) references USERS(id),
    foreign key (team_id) references TEAMS(id)
);