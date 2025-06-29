# Biblioteca

A modern book search and discovery platform built with Laravel and Vue.js. Biblioteca leverages the Google Books API to provide users with comprehensive book search capabilities, detailed book information, and an intuitive browsing experience with personal library management.

## Key Features

- **Book Search**: Search books by title, author, or keywords using Google Books API
- **Personal Library**: Add books to your personal library with reading status tracking
- **Reading Status Management**: Organize books as "Want to Read", "Reading", or "Read"
- **Author Discovery**: Clickable author links for exploring author-specific collections
- **Modern UI**: Responsive, card-based design with pagination
- **Book Details**: Comprehensive book information with preview links
- **Multilingual Support**: Available in English, French, Spanish, and German
- **State Management**: Centralized state management with Pinia
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

## New Features

### 📚 Personal Library Management
- **Add to Library**: One-click book addition from search results
- **Reading Status**: Track books as "Want to Read", "Reading", or "Read"
- **Library Dashboard**: View and manage your personal book collection
- **Status Updates**: Change reading status directly from the library view
- **Duplicate Prevention**: Automatic detection of books already in library

### 🌍 Multilingual Support
- **4 Languages**: English, French, Spanish, and German
- **Language Switcher**: Easy language switching in the navigation
- **Persistent Preferences**: Language choice remembered across sessions
- **Localized Content**: All UI text properly translated
- **Extensible**: Easy to add more languages

### ⚡ State Management with Pinia
- **Centralized State**: Consistent data across all components
- **Library Store**: Manage user's book collection and reading status
- **Search Store**: Handle search queries and results caching
- **Auth Store**: User authentication state management
- **Real-time Updates**: Reactive state updates across components

## Technology Stack

**Backend:**
- **Laravel 12.19.3** - PHP web framework for backend API and routing
- **Google Books API** - External API for book data retrieval
- **Meilisearch** - Fast search engine (optional)
- **SQLite/MariaDB** - Database for user data and library management

**Frontend:**
- **Vue 3** - Progressive JavaScript framework
- **Pinia** - State management for Vue.js
- **Inertia.js** - Laravel-Vue bridge for SPA experience
- **Tailwind CSS** - Utility-first CSS framework
- **Ziggy** - Laravel routes in JavaScript

**Development & Testing:**
- **Docker & Laravel Sail** - Containerized development environment
- **Pest** - PHP testing framework
- **Scribe** - API documentation generator
- **Vite** - Fast build tool and development server

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

## Testing

Run the comprehensive test suite covering all major functionality:

```bash
# Run all tests
./vendor/bin/sail artisan test

# Run specific test files
./vendor/bin/sail artisan test tests/Feature/LibraryControllerTest.php

# Run with coverage
./vendor/bin/sail artisan test --coverage
```

### Test Coverage
- **Library Management**: Add, remove, update book status
- **Authentication**: User registration, login, verification
- **API Validation**: Request validation and error handling
- **Database**: Migrations, relationships, and data integrity

## System Requirements

- **PHP**: 8.2+
- **Node.js**: 18+
- **Docker**: Latest version with Docker Compose

## Version Information

| Technology | Version |
|------------|---------|
| Laravel | 12.19.3 |
| Vue.js | 3.x |
| Pinia | 2.x |
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
- **State**: Pinia stores provide centralized state management

## API Endpoints

### Library Management
- `GET /library` - View user's library
- `POST /api/library` - Add book to library
- `DELETE /api/library/{book}` - Remove book from library
- `PATCH /api/library/{book}/status` - Update reading status

### Search
- `GET /?q={query}` - Search books and display results
- `GET /books/{id}` - View book details

### Multilingual
- `GET /?locale={locale}` - Switch language

## Best Practices & Recommendations

- **Validation**: Use Laravel request validation for all API endpoints
- **Error Handling**: Return structured JSON error responses
- **API Versioning**: Consider versioning for public APIs (e.g., `/api/v1/`)
- **Security**: Uses Laravel Fortify for session-based authentication and CSRF protection
- **Testing**: Write comprehensive tests using Pest
- **State Management**: Use Pinia stores for component communication
- **Translations**: Extract all user-facing text into language files

## Future Enhancements

### User Experience
- **Advanced Search Filters**: Genre, publication year, language, ratings
- **Book Reviews**: User ratings and review system
- **Reading Goals**: Set and track annual reading targets
- **Book Recommendations**: ML-based suggestions

### Social Features
- **Book Clubs**: Virtual book club functionality
- **Social Sharing**: Share books on social media
- **Friend Recommendations**: Book suggestions from friends
- **Reading Challenges**: Community reading challenges

### Platform Features
- **Dark Mode**: Theme toggle and accessibility improvements
- **Offline Support**: Cache results for offline access
- **Mobile App**: Companion mobile application
- **Export/Import**: Backup and restore library data

### Integrations
- **Goodreads API**: Sync with Goodreads accounts
- **Library Systems**: Integration with local library catalogs
- **E-book Platforms**: Connect with Kindle, Apple Books
- **Audio Books**: Audible integration

### Advanced Features
- **AI Summaries**: AI-generated book summaries
- **Reading Analytics**: Detailed reading statistics
- **Book Tracking**: Physical book location tracking
- **Price Alerts**: Notify when books go on sale

---

**Note**: If migrating from an existing MySQL setup, you may need to reset your database volume due to the MariaDB change. Check `docker-compose.yml` for configuration details.

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Ensure all tests pass
6. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).


