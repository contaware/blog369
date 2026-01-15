# Blog 369

This project demonstrates one method of programming a PHP blog connected to a database of your choice using the [PHP Data Objects (PDO)](https://www.php.net/manual/book.pdo.php) extension.

It has not been tested in a production environment, before using it online, make sure you understand the code and carry out your own safety tests.


## Installation

Clone this repository to your local computer:

```bash
git clone https://github.com/contaware/blog369.git
```

When you have your web server running (see below section if you want to use our LAMP stack), just navigate to the home page with your browser. On first use, it will emit an error and give you the link to create the database tables.

All of the blog's source files can be found in the *./html/* directory.

## Docker LAMP stack

You need to install Docker on your machine to use this LAMP (Linux Apache MySQL PHP) stack. Usually you want to install Docker Desktop, [read the instructions](https://docs.docker.com/desktop/) for your system.

### Start servers

1. Enter the project directory: `cd blog369/`
2. Run: `docker compose up -d` 

### Stop servers

1. Enter the project directory: `cd blog369/`
2. Run: `docker compose down`

### Web Server and PHP

The apache server is listening on <http://localhost:8888>. Change the port in *./compose.yaml* file and the PHP version in *./Dockerfile*.

### Database Server

By default the database server is configured to listen on port **3306**. From PHP access the database server with the **db** hostname. The databases are stored under *./db_data/*.

Other than the **root** user, there is also a **blog** user with a **blogdb** database. Both **root** and **blog** have the **1234** password. Server version, port and passwords can be changed in *./compose.yaml*. When changing database server or version, it may be necessary to start with no databases, for that delete the *./db_data/* directory.

### phpMyAdmin

phpMyAdmin is accessed through <http://localhost:8080>. Use the credentials reported in the previous section.

Change version and port in *./compose.yaml*.
