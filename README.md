# Biblioteca

A modern book search and discovery platform built with Laravel and Vue.js. Biblioteca leverages the Google Books API to provide users with comprehensive book search capabilities, detailed book information, and an intuitive browsing experience.

## Key Features

- **Book Search**: Search books by title, author, or keywords using Google Books API
- **Author Discovery**: Clickable author links for exploring author-specific collections
- **Modern UI**: Responsive, card-based design with pagination
- **Book Details**: Comprehensive book information with preview links
- **API Documentation**: Auto-generated documentation with Scribe
- **SPA Experience**: Seamless navigation powered by Inertia.js
- **Docker Ready**: Easy development setup with Laravel Sail

## Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/stephanpaquet/biblioteca.git
   cd biblioteca
   ```

2. **Set up environment:**
   ```bash
   cp .env.example .env
   ```

3. **Start Laravel Sail (Docker):**
   ```bash
   ./vendor/bin/sail up -d
   ```

4. **Install dependencies:**
   ```bash
   # PHP dependencies
   ./vendor/bin/sail composer install
   
   # JavaScript dependencies
   ./vendor/bin/sail npm install
   ```

5. **Configure application:**
   ```bash
   # Generate application key
   ./vendor/bin/sail artisan key:generate
   
   # Run database migrations
   ./vendor/bin/sail artisan migrate
   ```

6. **Set up Google Books API (Optional):**
   
   Get your API key from [Google Cloud Console](https://console.cloud.google.com/) and add it to your `.env` file:
   ```bash
   GOOGLE_BOOKS_API_KEY=your_api_key_here
   ```

7. **Start development server:**
   ```bash
   ./vendor/bin/sail npm run dev
   ```

8. **Access the application:**
   
   Open your browser to: http://localhost

## Technology Stack

**Backend:**
- **Laravel** - PHP web framework for backend API and routing
- **Google Books API** - External API for book data retrieval
- **Meilisearch** - Fast search engine (optional)

**Frontend:**
- **Vue 3** - Progressive JavaScript framework
- **Inertia.js** - Laravel-Vue bridge for SPA experience
- **Tailwind CSS** - Utility-first CSS framework
- **Ziggy** - Laravel routes in JavaScript

**Development & Testing:**
- **Docker & Laravel Sail** - Containerized development environment
- **Pest** - PHP testing framework
- **Scribe** - API documentation generator

## API Documentation

This project uses [Scribe](https://scribe.knuckles.wtf/) for automatic API documentation generation.

### Generate Documentation

1. **Install Scribe (if needed):**
   ```bash
   ./vendor/bin/sail composer require --dev knuckleswtf/scribe
   ```

2. **Publish configuration (one-time setup):**
   ```bash
   ./vendor/bin/sail artisan vendor:publish --provider="Knuckles\\Scribe\\ScribeServiceProvider"
   ```

3. **Generate documentation:**
   ```bash
   ./vendor/bin/sail artisan scribe:generate
   ```

4. **View documentation:**
   
   Visit: http://localhost/docs

> **Tip:** Add PHPDoc comments to your controllers for better documentation quality.

## System Requirements

- **PHP**: 8.2+
- **Node.js**: 18+
- **Docker**: Latest version with Docker Compose

## Version Information

| Technology | Version |
|------------|---------|
| Laravel | 12.19.3 |
| Vue.js | 3.x |
| Inertia.js | 1.x |
| Tailwind CSS | 3.x |
| Pest | 2.x |
| Scribe | 4.x |
| Meilisearch | 1.x |
| Ziggy | 1.x |

## Development Notes

- **Database**: Uses MariaDB by default (not MySQL)
- **Redis**: Removed from Docker setup
- **Navigation**: Handled entirely by Inertia.js (no vue-router)
- **Environment**: Ensure `.env.example` matches Docker/Sail configuration

## Best Practices & Recommendations

- **Validation**: Use Laravel request validation for all API endpoints
- **Error Handling**: Return structured JSON error responses
- **API Versioning**: Consider versioning for public APIs (e.g., `/api/v1/`)
- **Security**: Implement Laravel Sanctum for protected endpoints
- **Testing**: Write comprehensive tests using Pest

## Future Enhancements

### User Experience
- **User Authentication**: Account creation, login, and user profiles
- **Personal Lists**: Wishlists, reading lists, and reading history
- **Book Reviews**: User ratings and review system
- **Social Features**: Book sharing and friend recommendations

### Search & Discovery
- **Advanced Filters**: Genre, publication year, language, ratings
- **Smart Recommendations**: ML-based book suggestions
- **Enhanced Search**: Sort by relevance, popularity, date

### Platform Features
- **Dark Mode**: Theme toggle and accessibility improvements
- **Offline Support**: Cache results for offline access
- **Mobile App**: Companion mobile application
- **Admin Dashboard**: Content and user management panel

### Integrations
- **External APIs**: Goodreads and other book platforms
- **Social Media**: Sharing capabilities
- **Multi-language**: Internationalization support

### Community
- **Book Clubs**: Virtual book club features
- **Events**: Author signings and literary events
- **Gamification**: Reading achievements and leaderboards

---

**Note**: If migrating from an existing MySQL setup, you may need to reset your database volume due to the MariaDB change. Check `docker-compose.yml` for configuration details.


