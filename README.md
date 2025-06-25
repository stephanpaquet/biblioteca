# Technology Stack

Biblioteca is built with a modern, full-stack approach using the following technologies:

- **Laravel**: PHP web framework for backend API, routing, service integration, and caching.
- **Vue 3**: Progressive JavaScript framework for building reactive, component-based UIs.
- **Inertia.js**: Bridges Laravel and Vue for seamless single-page app (SPA) experience without a separate API.
- **Tailwind CSS**: Utility-first CSS framework for rapid, responsive, and modern UI design.
- **Google Books API**: External API for searching and retrieving book data.
- **Pest**: Elegant PHP testing framework for unit and feature tests.
- **Scribe**: API documentation is automatically generated from Laravel routes and annotations.
- **Docker & Laravel Sail**: Containerized local development environment (with MariaDB, Redis, Meilisearch, etc.).
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

---

## Tech Info

- **Laravel**: 12.19.3
- **PHP**: 8.2+
- **Node.js**: 18+
- **Vue.js**: 3.x
- **Inertia.js**: 1.x
- **Tailwind CSS**: 3.x
- **Pest**: 2.x
- **Scribe**: 4.x
- **Docker/Sail**: Latest
- **Meilisearch**: 1.x (optional)
- **Ziggy**: 1.x

---

**Note:** The default database is now MariaDB (not MySQL). If you have existing MySQL data, you may need to migrate it or reset your database volume. See `docker-compose.yml` for details.

## Future Enhancements
- User Authentication and Profiles:
   - Allow users to create accounts, log in, and save their favorite books.
   - Add user profiles with reading history and personalized recommendations.
- Advanced Search Filters:
   - Add filters for genre, publication year, language, and ratings.
   - Implement sorting options (e.g., by relevance, popularity, or publication date).
- Book Reviews and Ratings:
   - Enable users to leave reviews and rate books.
   - Display average ratings and top reviews for each book.
- Wishlist and Reading List:
   - Allow users to create and manage wishlists or reading lists.
   - Add functionality to mark books as "read" or "currently reading."
- Social Sharing:
   - Add buttons to share book details on social media platforms.
   - Enable users to recommend books to friends via email or messaging.
- Offline Mode:
   - Cache search results and book details for offline access.
   - Allow users to download book information for later use.
- Integration with External APIs:
   - Integrate with Goodreads or other book-related APIs for additional data.
   - Add functionality to sync user data with external platforms.
- Dark Mode and Accessibility Features:
   - Implement a dark mode toggle for better usability.
   - Add accessibility features like text-to-speech for book descriptions.
- Book Recommendations:
   - Use machine learning or rule-based algorithms to recommend books based on user preferences.
   - Display "Similar Books" or "Books You May Like" sections.
- Admin Dashboard:
   - Create an admin panel to manage books, users, and reviews.
   - Add analytics to track user engagement and popular books.
- Multilingual Support:
   - Add support for multiple languages in the UI.
   - Allow users to search for books in different languages.
- Event and Community Features:
   - Add a section for book-related events like author signings or book clubs.
   - Enable users to join or create virtual book clubs.
- Mobile App Integration:
   - Develop a companion mobile app for the platform.
   - Sync data between the web app and mobile app.
- Gamification:
   - Add achievements or badges for reading milestones.
   - Create leaderboards for most active users or reviewers.
- Customizable Layouts:
   - Allow users to customize the layout of the book search results.
   - Add options for grid or list views.
- Performance Optimization:
   - Implement caching strategies for frequently accessed data.
   - Optimize search queries and indexing for faster results. 


   