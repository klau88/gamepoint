# Laravel Coding Test (Dockerized with Sail)

This is a fully self-contained Laravel application using [Laravel Sail](https://laravel.com/docs/sail) — a Docker-based development environment. No local PHP, Composer, or MySQL setup is required. Just Docker.

---

## 🧰 Requirements

- [Docker](https://www.docker.com/products/docker-desktop)
- macOS, Linux, or WSL (Windows Subsystem for Linux)

---

## 🚀 Quick Start (No Composer Required)

You can start the app even if `composer` and `vendor/` are not installed locally:

1. **Clone the repository:**

```bash
git clone https://github.com/yourusername/laravel-coding-test.git
cd laravel-coding-test

2. ** Install dependencies and start Sail:**

docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install

# Start Sail (first time setup)
vendor/bin/sail up -d

3. **Run migrations and seeders:**

vendor/bin/sail artisan migrate --seed

4. **Open your browser:**
http://localhost

**🧰 Common Sail Commands**
vendor/bin/sail up -d           # Start containers
vendor/bin/sail down            # Stop containers
vendor/bin/sail shell           # Enter container shell
vendor/bin/sail artisan         # Run artisan commands
vendor/bin/sail npm install     # Frontend setup (if applicable)

**♻️ Reset Database**
vendor/bin/sail artisan migrate:fresh --seed

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
