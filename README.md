# Running dev copy
```bash
ln -s inf/docker-compose.dev.yml docker-compose.yml
cp .env.example .env
docker compose build
docker compose up -d
```

# Running tests
TODO: Test system seem to be broken after switch to php8.3
```bash
docker exec -i faq-bot-app ./vendor/bin/phpunit
```
