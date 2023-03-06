## Minimum Requirements

- PHP 8.0+
- Composer 2.0+
- MySQL 5.7+
- Laravel 9.0

## How to install
- Step 1: Clone the repository
https://github.com/spysabbirproject/news-project-one.git

- Step 2: Install Laravel
 i) run command "composer install"
 ii) Copy .env.example file and remame .env file
 iii) run command "php artisan key:generate"

- Step 3: Edit database details 
    i) Create news_project_one tables in your database
    ii) Database details contact .env file in database feld
    iii) Import news_project_one tables

- Step 4: Mail details contact .env file in mail feld
MAIL_MAILER=Your mail mailer
MAIL_HOST=Your mail host
MAIL_PORT=Your mail port
MAIL_USERNAME= Your mail address
MAIL_PASSWORD=Your mail password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS= Your mail address
MAIL_FROM_NAME="${APP_NAME}"

## About This Project

This is a web-based news project. From here user can see news. Any valid user can comment any news. We have an dashboard panel to control this site.
Our system has a search box by using this you can search news headline. Another important feature in our system is using a valid and unique email address. This means you can not be able to register in our system if you are already registered with existing mail address.

## Feature Admin Panel: 
Admin panel have three roles which is:
- Admin
- Super Admin
- Reporter

-   Super admin can change user role at any time. Manager and Admin have an limit that they can not be able to change Super Admins role.
-   Admin or reporter who posted a news they can be able to update and delete their posts at any time.

Reporter, Admin & Super Admin panel login details:- 
Super Admin Email: superadmin@gmail.com
Admin Email: admin@gmail.com
Reporter Email: reporterdhaka@gmail.com
Password : 123456789

## Feature Frontend Panel

-   They can see any news from this site and also can comment any news.
-   User can see location wise news and old date wise news.

User login details:- 
User Email: user@gmail.com
Password : 123456789

## Software Requirement
I have used those languages to create this project.

Front-End
- HTML
- CSS
- Java Scripts

Back-End: 
- PHP
- Laravel-Framework : 9
- Mysql
- Ajax
