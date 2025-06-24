# Technology Stack

Biblioteca is built with a modern, full-stack approach using the following technologies:

- **Laravel**: PHP web framework for backend API, routing, and service integration.
- **Vue 3**: Progressive JavaScript framework for building reactive, component-based UIs.
- **Inertia.js**: Bridges Laravel and Vue for seamless single-page app (SPA) experience without a separate API.
- **Tailwind CSS**: Utility-first CSS framework for rapid, responsive, and modern UI design.
- **Google Books API**: External API for searching and retrieving book data.
- **Pest**: Elegant PHP testing framework for unit and feature tests.
- **Scribe**: Generates API documentation from Laravel routes and annotations.
- **Docker & Laravel Sail**: Containerized local development environment.
- **Meilisearch**: Fast, open-source search engine (optional, for advanced search features).
- **Ziggy**: Exposes Laravel named routes to JavaScript for robust client-side navigation.

## Key Features
- Book search and detail via Google Books API
- Author search with clickable author links
- Paginated, card-based results with modern UI
- Book detail and preview links
- API documentation (Scribe)
- Docker/Sail support for easy setup
- All navigation and state handled via Inertia.js (no vue-router)

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
   ./vendor/bin/sail npm run dev
   ```
10. Access the website:

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

   http://localhost/docs

**Tip:** Add PHPDoc comments to your controllers and routes to improve the generated docs.

# Recommendations

- **Validation:** Use Laravel's request validation in your controllers to ensure required parameters are present and valid.
- **Error Handling:** Return structured error responses for failed API calls (not just `null`).
- **Remove Unused Services:** Redis has been removed from Docker; ensure `.env` and config files do not reference it.
- **API Versioning:** For public APIs, consider versioning your API routes (e.g., `/api/v1/books/search`).
- **Consistent Environment:** Ensure `.env.example` matches your Docker/Sail setup (MySQL, Meilisearch, etc.).
- **Security:** Add authentication (e.g., Laravel Sanctum) for protected endpoints if needed.
