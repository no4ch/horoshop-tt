# Test task for horoshop

## Project bootstrap

- clone this project to your `<project dir>`

- cd `<project dir>/docker`

- run
```shell
docker compose up -d
```

- copy `.env.example` to `.env` in `app`/`docker` directories of `<project dir>`

- run
```shell
docker compose exec app composer install
```

- run
```shell
docker compose exec database mysql -u root --password=root
```

- run inside `SOURCE /database/dumps/horoshop_db.sql` and `exit`

## Project urls

http://localhost:8080/ - main page
http://localhost:8081/ - php my admin (root/root by default)

## Project usage

#### Get token for admin

```shell
curl -X POST http://localhost:8080/api/login \
-H "Content-Type: application/json" \
-H "Accept: application/json" \
-d '{
    "login": "admin",
    "password": "12345"
}'
```

#### Get token for user

```shell
curl -X POST http://localhost:8080/api/login \
-H "Content-Type: application/json" \
-H "Accept: application/json" \
-d '{
    "login": "vasya",
    "password": "12345"
}'
```

#### Get user info

Admin id: 1
User id: 2

```shell
curl -X GET http://localhost:8080/v1/api/users/1 \
-H "Content-Type: application/json" \
-H "Accept: application/json" \
-H "Authorization: Bearer <testAdmin or testUser token>"
```

#### Create user (admin only, Idempotent)

```shell
curl -X POST http://localhost:8080/v1/api/users \
-H "Content-Type: application/json" \
-H "Accept: application/json" \
-H "Authorization: Bearer <testAdmin token>" \
-d '{
    "login": "testUser",
    "phone": "3652345",
    "password": "1234"
}'
```

#### Update user info (Idempotent)

```shell
curl -X PUT http://localhost:8080/v1/api/users/2 \
-H "Content-Type: application/json" \
-H "Accept: application/json" \
-H "Authorization: Bearer <testAdmin or testUser token>" \
-d '{
    "login": "vasya",
    "phone": "77777",
    "password": "12345"
}'
```

#### Delete user (admin only, Idempotent)

```shell
curl -X DELETE http://localhost:8080/v1/api/users/3 \
-H "Content-Type: application/json" \
-H "Accept: application/json" \
-H "Authorization: Bearer <testAdmin token>"
```
