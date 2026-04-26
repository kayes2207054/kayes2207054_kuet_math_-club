# KUET Math Club - Dynamic PHP Portfolio

Professional no-framework PHP portfolio for KUET Math Club.

## Project Structure

- includes/
	- header.php
	- footer.php
- config/
	- config.php
	- schema.sql
- classes/
	- Utilities.php
	- Database.php
	- Person.php
	- Member.php
	- Admin.php
	- Event.php
- pages/
	- home.php
	- about.php
	- events.php
	- members.php
	- contact.php
	- admin.php
- index.php
- style.css

## Covered PHP Concepts

- Variables and constants
- Indexed and associative arrays
- Foreach loops and if-else conditionals
- Include/require modular layout
- Form handling with POST
- Server-side validation and error/success messaging
- OOP: constructor, properties, methods, inheritance
- Interface, abstract class, trait
- Static methods for utility

## Bonus Features

- Simple admin panel (session-based)
- Optional MySQL support through PDO

## Run Locally

1. Make sure PHP 8+ is installed.
2. From project root:

	 php -S localhost:8000

3. Open:

	 http://localhost:8000/index.php?page=home

## MySQL Setup (Optional)

1. Import config/schema.sql into MySQL.
2. Open config/config.php.
3. Set:

	 - USE_DB to true
	 - DB_HOST, DB_NAME, DB_USER, DB_PASS

Then forms/admin writes data into MySQL tables.

## Admin Panel

- URL: index.php?page=admin
- Default password: kuet_admin_2026
- Change ADMIN_PASSWORD in config/config.php before deployment.
