# Docker for Wordpress

## Configuration

host: http://localhost:8000  
PhpMyAdmin host: http://localhost:8080

## Database

Database access:  
host: wordpress_mysql  
user: dev  
password: dev  
port: 3306

## Admin panel

url: http://localhost:8000/wp-admin/  
login: admin  
password: TestApp776


## Installation:

1. Run docker container:

```docker-compose up```

2. Copy GetResponse plugin to proper directory:

```cp -r data/plugin/GetResponse web/wp-content/plugins```