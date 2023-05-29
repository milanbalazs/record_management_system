# University project - Record Management System

## Requirements

## Deployment

## Config

## App - `PHP`

## `MySQL`

**The used `MySQL` base `Docker Image`:**

- [8.0.33](https://hub.docker.com/layers/library/mysql/8.0.33/images/sha256-13e429971e970ebcb7bc611de52d71a3c444247dc67cf7475a02718f6a5ef559?context=explore) (You can find here the parameters/configs)

The application uses custom `Docker Image` which can be found:

- [build/mysql/Dockerfile](build/mysql/Dockerfile)

The own `Docker Image` is useful because we won't reach the pull-rase limit in the official `Docker Registry`. Furthermore we can change/extend/modify the original `Docker Image`.

### Create the init state of `MySQL` DB

**[init.sql](build/mysql/init.sql)**:

- Create `record_management_system` DataBase if it doesn't exist.
- Create `admin_users` table if it doesn't exist.
  - `user_id` int(11) NOT NULL auto_increment (Primary Key)
  - `user_name` varchar(250) NOT NULL default ''
  - `first_name` varchar(250) NOT NULL default ''
  - `last_name` varchar(250) NOT NULL default ''
  - `password_hash` varchar(250) NOT NULL default ''
- Create initial admin user
  - `user_name`: 'init_admin'
  - `first_name`: 'init_admin_first_n'
  - `last_name`: 'init_admin_last_n'
  - `password_hash`: '2fa72699dc4fc2d6138722dcc42d55cf' (init_admin_password) (MD5)
- Create `cars` table if it doesn't exist.
  - `car_id` int(11) NOT NULL auto_increment (Primary Key)
  - `car_type` varchar(250)  NOT NULL default ''
  - `car fuel` varchar(250)  NOT NULL default ''
  - `car_year` date  NOT NULL
  - `car_seats` varchar(250)  NOT NULL default ''
  - `car_price` int(10)  NOT NULL default 0

The script is able to handle if the databases or tables are available (persistent volume is set for `Docker Service`) so it creates them only in case if they are not available.

The above `SQL` script is copied to the `MySQL Docker image` to the `/docker-entrypoint-initdb.d` folder. In this folder the `*.sql` and `*.sh` script are ran automatically by MySQL in the start-up phase.

It means in the first usage you can use the above admin user credentials (I highly recommend to change the password of it after first usage.).

### Init state verification

Enter to the MySQL container:

- `docker exec -it <docker_container_id> mysql -uroot -p<root_password>`
- `use record_management_system;`
- `SELECT * FROM admin_users;`

![MySQL verification](imgs/mysql_init_verification.png)

## Security stuff

- There is no open port for `MySQL` service. It's accissable only inside the `Docker network`.
- Only 80 port is open for `PHP` service.
