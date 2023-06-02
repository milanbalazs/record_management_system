# University project - Record Management System

It's a Management System of car shop. The users can browse the available cars and their details. The Administrators can modify/add/delete the records (cars) in the service.

## Requirements

In this section you can read the requirements from different aspects.

### User requirements

- Users should be able to browse the available cars
- Users shouldn't be able to modify/add/delete the records (cars)
- Users don't have profile and log-in is not needed to browse the cars
- Users can registrate as Admin the request has to be approved by another Admin user

### Administrator requirements

- Admins should be able to browse the available cars
- Admins should be able to log-in to the application
- Admins should be able to approve/delete the registration requests
- Admins should be able to modify/add/delete the records (cars)

### Technical requirements

- Mandatory technologies
  - PHP, MySQL
- Store the log-in data in `Session` variable

## Prerequisites

The application is runnin in separated `Docker containers` so only the Docker/Docker-compose has to be installed to the PC.

The availability of `Docker` and `docker-compose` are tested in the deployment script (`deploy.sh`) and it one of them if not
available the script will thorw an error (with hint to install the package) and exit immediately.

**Official Docker installation documentation:**

- <https://docs.docker.com/engine/install/>

**Official Docker-compose installation documentation:**

- <https://docs.docker.com/compose/install/>

## Configuration

The configuration file is available in the root-folder of the application (`environment_config.sh`).

**Content of the configuration file:**

- MYSQL_ROOT_PASSWORD - Root password of the MySQL DB (You can use this password for the root user in the MySQL).
- MYSQL_DATABASE - The initial DataBase. It's not used currently in the application but in the future it can be used.
- MYSQL_USER - The user of the MySQL DB.
- MYSQL_PASSWORD - Password of the created user in MySQL DB.

**Note:**

- The configuration parameters are not visible on the `host` or on the user side. Only the admin/developer can see who perform the deployment of the application.

## Deployment

The deployment is contenerized (`Docker` and `docker-compose`) and it's platform independent.

The deployment is automatized and it can be done to run the `deploy.sh` scipt from the root-folder. The script sources the configuration (Previous section) and deploy the application based on the `docker-compose.yml` file (In the root folder).

**Reference of docker-compose:**

- <https://docs.docker.com/compose/compose-file/>

You can deploy the application smply to call the following command:

- `./deploy.sh`

## Tested Environment

**Operation System:**

- Linux Mint - 19.1 (Tessa)

**Docker version:**

- Client - 23.0.1 ; API version: 1.42
- Server - 23.0.1 ; API version: 1.42

**Docker-compose version:**

- 1.29.1

## Software structure

``` bash
├── app  --  (Application files. Eg.: PHP, CSS, etc..)
│   ├── add_modify.css
│   ├── ...
│   ├── ...
├── build  --  (The build related stuff)
│   ├── mysql  --  (MySQL build studd)
│   │   ├── Dockerfile  --  (Dockerfile of MySQL)
│   │   └── init.sql  --  (The script which runs during start-up phase)
│   └── php  --  (PHP build related stuff)
│       └── Dockerfile  --  (Dockerfile of PHP)
├── deploy.sh  --  (This script can be used to deploy the service)
├── docker-compose.yml  --  (The docker-compose file of Application)
├── environment_config.sh  --  (Configuration file of the application)
├── imgs  --  (The documentation images)
│   ├── admin_users_er_diagram.png
│   ├── ...
│   └── ...
└── README.md  --  (The documentation itself)
```

## App - `PHP`

**The used `PHP` base `Docker Image`:**

- [php:8.1-apache](https://hub.docker.com/layers/library/php/8.1-apache/images/sha256-1890a914d868d701e51b70f2a1b3b482162875d560b44290e2511f05f2cb2b13)

This image contains Debian's Apache httpd in conjunction with PHP (as `mod_php`) and uses `mpm_prefork` by default. It means, there is no need another Proxy service like `NgInx`.

**Additional extensions (In `Dockerfile`):**

- [docker-php-ext-install](https://github.com/mlocati/docker-php-extension-installer) - Script that can be used to easily install a PHP extension inside the official PHP Docker images.
- [mysqli](https://www.php.net/manual/en/book.mysqli.php) - The MySQLi functions allows you to access MySQL database servers.
- [pdo](https://www.php.net/manual/en/book.pdo.php) - PHP Data Objects.
- [pdo_mysql](https://www.php.net/manual/en/ref.pdo-mysql.php) - A driver that implements the PHP Data Objects (PDO) interface to enable access from PHP to MySQL databases.

## DataBase - `MySQL`

**The used `MySQL` base `Docker Image`:**

- [8.0.33](https://hub.docker.com/layers/library/mysql/8.0.33/images/sha256-13e429971e970ebcb7bc611de52d71a3c444247dc67cf7475a02718f6a5ef559?context=explore) (You can find here the parameters/configs)

The application uses custom `Docker Image` which can be found:

- [build/mysql/Dockerfile](build/mysql/Dockerfile)

The own `Docker Image` is useful because we won't reach the pull-rase limit in the official `Docker Registry`. Furthermore, we can change/extend/modify the original `Docker Image`.

### Create the init state of `MySQL` DB

**[init.sql](build/mysql/init.sql)**:

- Create `record_management_system` DataBase if it doesn't exist.
- Create `admin_users` table if it doesn't exist.
  - `user_id` int(11) NOT NULL auto_increment (Primary Key) - The ID of the user. It's auto-incremented
  - `user_name` varchar(250) NOT NULL default '' - The name of the user. It can be used to log-in to the application
  - `first_name` varchar(250) NOT NULL default '' - First name of the user.
  - `last_name` varchar(250) NOT NULL default '' - Last name of the user.
  - `password_hash` varchar(250) NOT NULL default '' - The password of the user. It's an MD5 hash.
  - `approved` int(1) NOT NULL default 0 - If it's 0 then the registration is not approved by another administrator (Login/Usage is not allowed).
- Create initial admin user
  - `user_name`: 'init_admin'
  - `first_name`: 'init_admin_first_n'
  - `last_name`: 'init_admin_last_n'
  - `password_hash`: '2fa72699dc4fc2d6138722dcc42d55cf' (init_admin_password) (MD5)
- Create `cars` table if it doesn't exist.
  - `car_id` int(11) NOT NULL auto_increment (Primary Key) - The ID of the user. It's auto-incremented
  - `car_type` varchar(250)  NOT NULL default '' - Type of the car
  - `car fuel` varchar(250)  NOT NULL default '' - Fuel of the car
  - `car_year` date  NOT NULL - The produce year of the car
  - `car_seats` varchar(250)  NOT NULL default '' - Number of the seats in the car
  - `car_price` int(10)  NOT NULL default 0 - The price of the car (The currency is not specified)
- Create initial car in `cars` table
  - `car_type`: 'Audi A8'
  - `car fuel`: 'Diesel'
  - `car_year`: '1990-12-12'
  - `car_seats`: 5
  - `car_price`: 15000

**`admin_users` table ER diagram:**

![admin_users ER Diagram](imgs/admin_users_er_diagram.png)

**`cars` table ER diagram:**

![cars ER Diagram](imgs/cars_er_diagram.png)

The script is able to handle if the databases or tables are available (persistent volume is set for `Docker Service`) so it creates them only in case if they are not available.

The above `SQL` script is copied to the `MySQL Docker image` to the `/docker-entrypoint-initdb.d` folder. In this folder the `*.sql` and `*.sh` script are run automatically by MySQL in the start-up phase.

It means in the first usage you can use the above admin user credentials (I highly recommend to changeing the password of it after first usage.).

### Init state verification

Enter to the MySQL container:

- `docker exec -it <docker_container_id> mysql -uroot -p<root_password>`
- `use record_management_system;`
- `SELECT * FROM admin_users;`

![MySQL verification](imgs/mysql_init_verification.png)

## Security stuff

- There is no open port for `MySQL` service. It's accissable only inside the `Docker network`.
- Only one (8000, but it's configurable in `docker-compose.yml`) port is open for `PHP` service.
- There is not execution of request (To avoid the attacks or SQL injection).
- The HTTPS protocol can be done with [letsencrypt](https://letsencrypt.org/). You can do it on your host with [certbot](https://certbot.eff.org/)
- The MySQLi (currently used) or PDO usage can protect the SQL injection ([Reference](https://stackoverflow.com/questions/60174/how-can-i-prevent-sql-injection-in-php))

## Automatic test

The `PHP Composer` can be added as `GitHub Workflow` and it can be run for each commit:

- <https://github.com/php-actions/composer>

## Improvement opportunities

- Use object orientated PHP
- Rewrite the file structure (different folders fow views)
- Use common CSS (Avoid the duplications)
- Add test cases (UnitTest, FunctionTest, E2E test)

## Pictures about the application

### Home page (before log-in [User view])

![User Home Page](imgs/user_home_page.png)

### Home page (after log-in [Admin view])

![UsAdminer Home Page](imgs/admin_home_page.png)

### Login page - Available all users

![Login Page](imgs/login_page.png)

### Registration page - Available all users

![Registration Page](imgs/registration_page.png)

### Add Page - Available only for authenticated Admin users

![Add Page](imgs/add_page.png)

### Modify Page - Available only for authenticated Admin users

![Modify Page](imgs/modify_page.png)

### Approve Page - Available only for authenticated Admin users

![Approve Page](imgs/approve_page.png)
