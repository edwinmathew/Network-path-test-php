# Network-path-test
### To check whether a signal can travel between two devices in a given amount of time or less.

Built with love, using:

[PHP](https://www.php.net/)

[MySQL](https://www.mysql.com/)


### Requirements

[PHP](https://www.php.net/)

[MySQL 8.0](https://www.mysql.com/)

### Set Up Local Development Environment (Windows)

 1. Download and install [XAMPP](https://www.apachefriends.org/).
 2. Start Appache and MySQL from XAMPP control panel.
 3. Download or clone this repository.
 4. Create databse 'network_test'
```
CREATE DATABASE network_test;

```
  5.Create table
```
CREATE TABLE IF NOT EXISTS paths (path_id int NOT NULL AUTO_INCREMENT,source char(50),destination char(50),signal_time int,PRIMARY KEY (path_id));

```
  6. Clone this repository or download
  7. Open dbconnection.php and enter your host and database details
  8. open command prompt inside the downloaded folder
  9. To run the project type
  ```
  php network.php
  ```
  
