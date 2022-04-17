CREATE TABLE CHUNKS (
    id varchar(64) unique primary key,
    tag varchar(25),
    create_date Date,
    last_modified Date,
    developer_id int,
    layout_file_id int
)