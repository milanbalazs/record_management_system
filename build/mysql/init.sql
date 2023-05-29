-- create the databases
CREATE DATABASE IF NOT EXISTS record_management_system;

USE record_management_system;

CREATE TABLE IF NOT EXISTS `admin_users` (

  `user_id` int(11) NOT NULL auto_increment,
  `user_name` varchar(250)  NOT NULL default '',        
  `first_name` varchar(250)  NOT NULL default '',
  `last_name` varchar(250)  NOT NULL default '',
  `password_hash` varchar(250)  NOT NULL default '',   
   PRIMARY KEY  (`user_id`)
);


INSERT INTO `admin_users` (`user_name`, `first_name`, `last_name`, `password_hash`)
    SELECT 'init_admin', 'init_admin_first_n', 'init_admin_last_n', '2fa72699dc4fc2d6138722dcc42d55cf' -- init_admin_password (MD5)
    FROM dual
    WHERE NOT EXISTS (SELECT * FROM `admin_users`);

CREATE TABLE IF NOT EXISTS `cars` (
  `car_id` int(11) NOT NULL auto_increment,
  `car_type` varchar(250)  NOT NULL default '',        
  `car fuel` varchar(250)  NOT NULL default '',
  `car_year` date  NOT NULL,
  `car_seats` varchar(250)  NOT NULL default '',
  `car_price` int(10)  NOT NULL default 0,
   PRIMARY KEY  (`car_id`)
);