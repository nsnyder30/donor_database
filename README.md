# Donor Database
This is a simple donor management system for tracking contacts, organizations, and transactional donation history. Features include:
 - Password protected access
 - Manual insertion, update, and deletion of table records
 - CSV upload from Paypal and Facebook downloaded donation data

## Setup Instructions
This app was designed to run on a XAMPP or LAMP stack. If you don't already have one built, you will need to install an Apache server and MySQL db.
 - Once installed, copy setup/connections.ini to a folder outside the web contents directory and update the username and password
 - Update the $GLOBALS['cfg_file'] variable in includes/page_init.php to the address of the connections.ini file
 - Create a new database in MySQL with the same name as the "db" parameter in your connections.ini file
 - Import setup/donor_db.sql into your new database
 - Create a user in your MySQL application with the same name as the "user" parameter and the same password as the "password" parameter in your connections.ini file
 - Grant the user privileges to access your uploaded donor database

Your donor database application should now be accessible at http://127.0.0.1/donor_database. The default username and password are:
user: test_user
password: test_password

Which can be updated in the db_users table of your MySQL database. When creating new users, use the MD5() mysql function to hash their passwords.
