# Installation

1. Clone the repository:
   ```sh
   git clone git@github.com:stephanpaquet/biblioteca.git
   cd biblioteca
   ```
2. Install PHP dependencies:
   ```sh
   composer install
   ```
3. Install JavaScript dependencies:
   ```sh
   npm install
   ```
4. Copy the example environment file and set your environment variables:
   ```sh
   cp .env.example .env
   ```
5. Generate the application key:
   ```sh
   php artisan key:generate
   ```
6. (Optional) Set your Google Books API key in the `.env` file:
   ```sh
   GOOGLE_BOOKS_API_KEY=your_api_key_here
   ```
7. Run database migrations:
   ```sh
   php artisan migrate
   ```
8. Start the development servers:
   ```sh
   php artisan serve
   npm run dev
   ```

# API Documentation

This project uses [Scribe](https://scribe.knuckles.wtf/) to automatically generate API documentation.

## To generate or update the API docs:

1. Install Scribe (if not already installed):
   ```sh
   composer require --dev knuckleswtf/scribe
   ```
2. Publish Scribe's config and views (only needed once):
   ```sh
   php artisan vendor:publish --provider="Knuckles\\Scribe\\ScribeServiceProvider"
   ```
3. Generate the documentation:
   ```sh
   php artisan scribe:generate
   ```
4. Visit your documentation at:
   ```
   http://localhost:8000/docs
   ```

Add PHPDoc comments to your controllers and routes to improve the generated docs.
