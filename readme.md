0. install WAMP server (http://www.wampserver.com/en/) and Composer (https://getcomposer.org/), make sure composer, PHP version >= 7.0 and MySQL are added to PATH environment variable. You can check with Window Command Prompt as image below, then run WAMP server application
```bash
#check composer
composer --version
# check php version 
php -version
# check mysql version
mysql -V
```
![alt text](https://github.com/Light0n/job-portal-app/blob/master/readme-imgs/php-mysql-composer-in-PATH-environment.PNG?raw=true)

1. create user, password and database in mysql to match with the database config in ".env" file lines 8 to 13
```bash
# enter as admin
mysql> -u root -p
# create database 'job_portal_app'
mysql> create database job_portal_app;
# create username and password as 'job_portal_app'
mysql> create user 'job_portal_app' identified by 'job_portal_app';
# allow user 'job_portal_app' access database 'job_portal_app'
mysql> grant all on job_portal_app.* to 'job_portal_app';
```
2. clone (https://github.com/Light0n/job-portal-app.git) and extra job_portal_app.zip file
3. open terminal in project folder directory, and run command to install dependencies, then create tables and mocking data in database
```bash
# 1. install dependencies
D:\CSIS3300\job_portal_app> composer install
# 2. create tables and mocking data 
D:\CSIS3300\job_portal_app> php artisan migrate:refresh --seed
```

![alt text](https://github.com/Light0n/job-portal-app/blob/master/readme-imgs/innitialize-database.PNG?raw=true)

4. start the serve
```bash
D:\CSIS3300\job_portal_app> php artisan serve
```
5. go to browser "http://localhost:8000/"
6. 10 mocking users all have the same password "jobportal". Some user emails: admin@gmail.com (admin), fae03@example.org (normal user). You can go to database to get more.
7. some routes
  - welcome: http://localhost:8000
  - login: http://localhost:8000/login
  - register: http://localhost:8000/register
  - list all open Jobs: http://localhost:8000/jobs
  - list detail of job which has id = 3 and all its job applications: http://localhost:8000/jobs/3
  - list user info and user's related jobs: http://localhost:8000/home

