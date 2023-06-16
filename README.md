# Wallet App

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --pull --no-cache` to build fresh images
3. Run `docker compose up` (the logs will be displayed in the current shell)
4. Create `.env.local` and after that create there a variable named `CURRENCY_EXCHANGE_RATES_API`, whose value will be the endpoint that returns the exchange rates 
5. Execute `docker compose up` inside `wallet-app-php` container
