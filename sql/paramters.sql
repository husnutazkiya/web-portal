
create table bug_parameters (
	id int primary key auto_increment not null,
	param_type varchar(50),
	param_code varchar(50),
	param_mapping varchar(50),
	param_value varchar(50),
	param_desc varchar(50),
	is_active char(1)
)


insert into bug_parameters(param_type ,param_code , param_mapping , param_value , param_desc , is_active) values
	('APPLICATION' , 'SEVERITY_BUG' , NULL , '1' , 'Low' , '1')
	,('APPLICATION' , 'SEVERITY_BUG' , NULL , '2' , 'Medium' , '1')
	,('APPLICATION' , 'SEVERITY_BUG' , NULL , '3' , 'High' , '1')
	,('APPLICATION' , 'STATUS_BUG' , NULL , '1' , 'Open' , '1')
	,('APPLICATION' , 'STATUS_BUG' , NULL , '2' , 'Ready To Test' , '1')
	,('APPLICATION' , 'STATUS_BUG' , NULL , '3' , 'Close' , '1')
	

create table filter_search(
	id int primary key auto_increment not null,
	filter_name varchar(50),
	filter_value varchar(50),
	filter_type varchar(50)
);

INSERT INTO filter_search (filter_name,filter_value,filter_type) VALUES
	('-- Select -- ','pic','fixed_text'), 
	('PIC','pic','fixed_text'),
	('Status','status','fixed_text'),
	('Severity','severity','fixed_text');


create table mapping_filter(
	mapping_id int primary key auto_increment not null,
	filter_id int,
	menu_id int
);

insert into mapping_filter(filter_id , menu_id) values
	(1 , 14)
	, (2 , 14)
	, (3 , 14)
	, (4 , 14)
	