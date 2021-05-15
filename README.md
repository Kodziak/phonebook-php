# HELLO

## Run the project

Requirements:
- database,
- backend,
- frontend.

To set up a database, simply run cmd:

```bash
docker run --name phonebook-php -e MYSQL_ROOT_PASSWORD=root -p 3306:3306 -d mysql
```

To run a backend, simply run cmd:

```bash
composer run:server
```

It will handle feed data to database.

To run a frontend, simply run cmd in folder app/:

```bash
yarn serve
```