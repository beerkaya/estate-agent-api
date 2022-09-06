# Estate Agent API


This project is developing for the the Estate Agent.


Clone project to your local machine:

```bash

git clone https://github.com/beerkaya/estate-agent-api.git

```

```bash

cd estate-agent-api

```
  

Install composer dependencies:

```bash

composer install

```


Copy the `.env.example` file to `.env`:

```bash

cp .env.example .env

```


Generate Jwt Secret:

```bash

php artisan jwt:secret

```
  

Migrate the database schemas:

```bash

php artisan migrate

```


Run the project:

```bash

php artisan serve

```
