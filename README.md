# University project - Record Management System

## Requirements

## Deployment

## Config

## App - PHP

## MySQL

### Init state verification

Enter to the MySQL container:
 - `docker exec -it <docker_container_id> mysql -uroot -p<root_password>`
 - `use record_management_system;`
 - `SELECT * FROM admin_users;`
![MySQL verification](imgs/mysql_init_verification.png)
