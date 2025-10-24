# Tracktik API Integration

### Some Details on the stack 

- PHP 8.4
- Symfony CLI version 5.15.1
- Docker + Docker Compose
- Frankenphp
- PHP 8.4
- Composer 2
- Symfony 7.2
    - API Platform
    - doctrine/migrations
    - doctrine/orm
- postgresql 16 (preferred for JSONB & reliability)
- DTO support

### Links

API Docs and Testing: https://localhost/docs  
Example: https://localhost/docs#/Employee/api_providerAemployees_post

Profiler: https://localhost/_profiler/  

Admin: https://localhost/admin  

### Curl Examples:

```
curl --insecure -X POST https://localhost/providerA/employees -H 'accept: application/json' -H "Content-Type: application/json" -d '{"given_name":"John","family_name":"Doez","email":"johndoez@example.com"}'

curl --insecure -X POST https://localhost/providerB/employees -H 'accept: application/json' -H "Content-Type: application/json" -d '{"first":"John","last":"Doe","email_address":"john@example.com"}'
```

## CLI Commands

**Load docker**
`docker compose up --build -d`

**Open bash shell on containers**
`docker exec -it api-platform-410-database-1 bash`
`docker exec -it api-platform-410-php-1 bash`

**View Logs**
`docker logs api-platform-410-php-1`
`docker logs api-platform-410-database-1`

### Database

generate a new database migration using Doctrine Migrations and apply it:

`docker compose exec php bin/console doctrine:migrations:diff`
`docker compose exec php bin/console doctrine:migrations:migrate`


