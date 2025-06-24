# Installation

1. Clone the repository:
   ```sh
   git clone https://github.com/stephanpaquet/biblioteca.git
   cd biblioteca
   ```
2. Copy the example environment file and set your environment variables:
   ```sh
   cp .env.example .env
   ```
3. Start Laravel Sail (Docker):
   ```sh
   ./vendor/bin/sail up -d
   ```
4. Install PHP dependencies (inside Sail):
   ```sh
   ./vendor/bin/sail composer install
   ```
5. Install JavaScript dependencies (inside Sail):
   ```sh
   ./vendor/bin/sail npm install
   ```
6. Generate the application key (inside Sail):
   ```sh
   ./vendor/bin/sail artisan key:generate
   ```
7. (Optional) Set your Google Books API key in the `.env` file:
   ```sh
   GOOGLE_BOOKS_API_KEY=your_api_key_here
   ```
8. Run database migrations (inside Sail):
   ```sh
   ./vendor/bin/sail artisan migrate
   ```
9. Start the development servers (inside Sail):
   ```sh
   ./vendor/bin/sail artisan serve
   ./vendor/bin/sail npm run dev
   ```
10. Access web site

http://localhost

# API Documentation

This project uses [Scribe](https://scribe.knuckles.wtf/) to automatically generate API documentation.

## To generate or update the API docs:

1. Install Scribe (if not already installed):
   ```sh
   ./vendor/bin/sail composer require --dev knuckleswtf/scribe
   ```
2. Publish Scribe's config and views (only needed once):
   ```sh
   ./vendor/bin/sail artisan vendor:publish --provider="Knuckles\\Scribe\\ScribeServiceProvider"
   ```
3. Generate the documentation:
   ```sh
   ./vendor/bin/sail artisan scribe:generate
   ```
4. Visit your documentation at:
   ```
   http://localhost/docs
   ```

Add PHPDoc comments to your controllers and routes to improve the generated docs.
