# My First PHP Project / PHP CRUD Application with User Authentication

This is a basic PHP application built as a learning project that demonstrates Create, Read, Update, and Delete operations along with user registration, login, session management, and role-based access control.

## Objective
- Set up a local development environment using XAMPP.
- Configure version control using Git and GitHub.
- Develop a cohesive CRUD web application with authentication and security enhancements (prepared statements, form validation, RBAC).

## Tools Used
- XAMPP (Apache & MySQL)
- PHP, HTML, CSS (Bootstrap 5)
- Visual Studio Code
- Git & GitHub

## Setup Instructions

1. Start Apache and MySQL in XAMPP.
2. Clone or place the project files in your web server's document root (e.g., `htdocs/MYPROJECT` for XAMPP).
3. Execute the `setup.sql` script in your MySQL database to create the `blog` database and required tables (`users` and `posts`). Wait until it populates the default `admin` and `editor` roles if you want to test them.
4. Configure the database connection settings in `config.php` if necessary. (Default is `localhost`, `root`, no password).
5. Open your browser and go to: `http://localhost/MYPROJECT/register.php` (or wherever your path lies) to get started!

## Author
Muskan Gupta
