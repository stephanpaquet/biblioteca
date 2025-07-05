# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- **Laravel Pint Integration**: Added Laravel Pint for automated PHP code formatting and style consistency
- **Code Formatting Configuration**: Created `pint.json` with Laravel preset and custom rules for ordered class elements

### Changed
- **Code Style Standardization**: Applied Laravel Pint formatting to entire codebase (71 files, 36 style issues fixed)
- **Import Organization**: Standardized and ordered import statements across all PHP files
- **Class Structure**: Applied ordered class elements for better code organization
- **Code Consistency**: Implemented consistent spacing, braces positioning, and quote usage

### Technical Improvements
- **Automated Formatting**: Integrated Laravel Pint for consistent code formatting
- **Development Workflow**: Added code style checks and formatting tools

## [2.1.0] - 2024-12-23

### Changed

#### 🔐 Authentication System Migration
- **Laravel Fortify Integration**: Migrated from Laravel Sanctum to Laravel Fortify for improved session-based authentication
- **Session-Based Authentication**: Replaced token-based authentication with secure session management
- **Database Sessions**: Configured session storage using database driver for improved scalability
- **Inertia.js Authentication**: Refactored frontend authentication to use Inertia.js form helpers instead of raw fetch calls
- **CSRF Protection**: Enhanced CSRF token handling with automatic meta tag injection and Axios configuration
- **Authentication Flow**: Streamlined login and registration processes with proper error handling

#### 🛡️ Security Enhancements
- **Rate Limiting**: Implemented comprehensive rate limiting for login and two-factor authentication attempts
- **RateLimitServiceProvider**: Created dedicated service provider for managing authentication rate limits
- **Session Security**: Enhanced session configuration for production-ready security
- **CSRF Middleware**: Proper CSRF token validation across all authentication endpoints
- **Protected Routes**: Updated route protection to use session-based authentication middleware

#### 🎨 Frontend Authentication Refactor
- **Login Component**: Refactored `Login.vue` to use Inertia.js POST requests with automatic CSRF handling
- **Registration Component**: Updated `Register.vue` to use Inertia.js form helpers with proper validation feedback
- **Error Handling**: Improved error display and user feedback for authentication failures
- **Form Validation**: Client-side and server-side validation with real-time error messages
- **Redirect Handling**: Proper post-authentication redirects to dashboard

### Removed
- **Laravel Sanctum**: Completely removed Sanctum package and all related configurations
- **Token Authentication**: Eliminated API token-based authentication system
- **Custom Auth API**: Removed custom API authentication endpoints in favor of Fortify routes
- **Manual CSRF Handling**: Removed manual CSRF token management in favor of automatic handling

### Fixed
- **Session Store Issues**: Resolved "Session store not set on request" errors with proper middleware configuration
- **CSRF Token Errors**: Fixed CSRF token mismatch issues with proper meta tag and Axios setup
- **Rate Limiter Exceptions**: Resolved MissingRateLimiterException by implementing proper rate limiting configuration
- **Authentication State**: Fixed authentication state persistence across page reloads
- **Middleware Conflicts**: Resolved middleware ordering and configuration conflicts
- **CRITICAL: Inertia Response Handling**: Fixed 500 errors on `/register` and `/login` routes caused by improper Fortify-Inertia response integration - updated response bindings to properly convert Inertia responses to HTTP responses

### Technical Improvements

#### 🏗️ Configuration Updates
- **Fortify Configuration**: Comprehensive Fortify setup with custom Inertia.js views
- **Environment Variables**: Updated `.env` configuration for session-based authentication
- **Middleware Registration**: Proper registration of authentication and rate limiting middleware
- **Service Providers**: Added and configured RateLimitServiceProvider for authentication security
- **API Documentation**: Updated Scribe configuration to reflect session-based authentication and CSRF handling

#### 🧪 Testing & Validation
- **Authentication Testing**: Verified login, registration, and protected route access
- **Session Validation**: Confirmed proper session creation and management
- **CSRF Testing**: Validated CSRF token generation and verification
- **Rate Limit Testing**: Confirmed rate limiting functionality for authentication endpoints

### Migration Notes

#### From 2.0.x to 2.1.x
- **Authentication Method**: Migration from token-based to session-based authentication
- **Frontend Changes**: Updated Vue components to use Inertia.js form helpers
- **Configuration Updates**: New Fortify configuration and rate limiting setup
- **Database Changes**: Session storage moved to database (requires session table migration)

#### Breaking Changes
- **API Authentication**: Removed custom API authentication endpoints
- **Token Storage**: Frontend no longer stores or manages authentication tokens
- **Authentication Headers**: Changed from Authorization Bearer tokens to session cookies

#### Upgrade Steps
1. Install Laravel Fortify: `composer require laravel/fortify`
2. Publish Fortify configuration: `php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"`
3. Run session table migration: `php artisan session:table && php artisan migrate`
4. Update environment configuration for session driver
5. Clear all caches: `php artisan config:clear && php artisan cache:clear && php artisan route:clear`
6. Update frontend components to use new authentication methods

## [2.0.0] - 2024-01-15

### Added

#### 📚 Personal Library Management
- **Library Controller**: Complete CRUD operations for user's personal library
- **Book Model**: Database model for storing book information from Google Books API
- **User-Book Relationship**: Many-to-many relationship with pivot table for reading status
- **Add to Library Button**: One-click book addition from search results
- **Reading Status Tracking**: Three status levels (Want to Read, Reading, Read)
- **Library Dashboard**: Dedicated page to view and manage personal book collection
- **Status Updates**: Real-time status changes directly from library view
- **Duplicate Prevention**: Automatic detection and prevention of duplicate books
- **Book Removal**: Remove books from personal library
- **Database Migrations**: Books and user_books tables with proper relationships

#### 🌍 Multilingual Support
- **Multi-language System**: Complete internationalization (i18n) implementation
- **Language Files**: Extracted all UI text into translatable language files
- **4 Language Support**: English, French, Spanish, and German translations
- **Language Switcher Component**: Dropdown selector in navigation bar
- **Persistent Language Preferences**: Language choice remembered across browser sessions
- **Laravel Localization**: Proper Laravel localization system integration
- **Inertia Shared Data**: Locale information shared across all Vue components
- **SetLocale Middleware**: Automatic locale detection and setting
- **Fallback System**: Graceful fallback to default language for missing translations

#### ⚡ State Management with Pinia
- **Pinia Store Setup**: Complete state management system implementation
- **Library Store**: Centralized management of user's book collection
- **Search Store**: Search queries, results caching, and search history
- **Auth Store**: User authentication state management
- **Reactive State**: Real-time updates across all components
- **Store Actions**: Async actions for API calls and state mutations
- **Computed Properties**: Derived state for book counts and filtered collections
- **Error Handling**: Centralized error state management

### Enhanced

#### 🔍 Search & Navigation
- **Home Page Integration**: Search functionality directly on homepage
- **Search Form Component**: Reusable search component with loading states
- **Book Grid Component**: Reusable component for displaying book collections
- **Search Results Enhancement**: Improved layout and user experience
- **Featured Books**: Homepage displays featured/bestseller books when no search
- **Quick Action Cards**: Informational cards explaining platform features

#### 🧪 Testing Infrastructure
- **LibraryController Tests**: Comprehensive test suite for all library operations
- **Database Testing**: SQLite in-memory database for fast test execution
- **Test Factories**: Book factory for generating test data
- **DatabaseMigrations**: Proper test database handling
- **Validation Testing**: API endpoint validation and error handling tests
- **Authentication Testing**: User authentication flow testing

#### 🏗️ Code Architecture
- **Component Organization**: Better separation of concerns with reusable components
- **Controller Improvements**: Request object usage instead of auth() helper
- **API Error Handling**: Structured JSON error responses
- **Code Documentation**: Improved inline documentation and comments

### Technical Improvements

#### 🐳 Development Environment
- **Docker Configuration**: Optimized Laravel Sail setup
- **Database**: MariaDB as default database (replacing MySQL)
- **Vite Integration**: Modern build tool for faster development
- **Hot Module Replacement**: Instant updates during development

#### 🔧 Configuration
- **Environment Variables**: Proper configuration for all features
- **Google Books API**: Integrated API key configuration
- **Locale Configuration**: Multi-language support configuration
- **Database Configuration**: Optimized for both development and testing

### Fixed
- **CSRF Token Handling**: Proper CSRF token integration for API calls
- **Inertia.js Imports**: Fixed import issues with @inertiajs/inertia
- **Vue Template Errors**: Resolved malformed template structures
- **Database Migration Issues**: Fixed SQLite VACUUM errors in testing
- **Route Configuration**: Proper middleware and route organization

### Security
- **Request Validation**: Comprehensive validation for all API endpoints
- **Authentication Middleware**: Proper authentication checks for protected routes
- **CSRF Protection**: Cross-site request forgery protection
- **Input Sanitization**: Proper data sanitization and validation

## [1.0.0] - 2024-01-01

### Added
- **Initial Release**: Basic book search functionality using Google Books API
- **Laravel Backend**: RESTful API with Laravel framework
- **Vue.js Frontend**: Modern reactive frontend with Vue 3
- **Inertia.js Integration**: Seamless SPA experience without separate API
- **Tailwind CSS**: Modern utility-first CSS framework
- **Docker Support**: Laravel Sail for containerized development
- **Basic Search**: Book search by title, author, or keywords
- **Book Details**: Individual book detail pages
- **Responsive Design**: Mobile-friendly responsive layout
- **API Documentation**: Scribe integration for API documentation

### Technical Stack
- Laravel 12.19.3
- Vue.js 3.x
- Inertia.js 1.x
- Tailwind CSS 3.x
- Docker & Laravel Sail
- SQLite/MariaDB
- Pest Testing Framework

---

## Migration Notes

### From 1.x to 2.x
- **Database Changes**: New tables for books and user_books relationships
- **New Dependencies**: Pinia for state management
- **Configuration Updates**: New locale and Google Books API settings
- **Component Structure**: New reusable components for library management

### Breaking Changes
- **Database**: Migration from MySQL to MariaDB as default
- **Component Props**: Some component interfaces have changed for multilingual support
- **State Management**: Introduction of Pinia stores may affect existing state handling

### Upgrade Steps
1. Run new database migrations: `php artisan migrate`
2. Install new npm dependencies: `npm install`
3. Update environment configuration for new features
4. Clear caches: `php artisan config:clear && php artisan cache:clear`

---

## Development Team
- **Lead Developer**: Stephan Paquet
- **Framework**: Laravel + Vue.js + Inertia.js
- **AI Assistant**: GitHub Copilot for development assistance

## Special Thanks
- Google Books API for providing comprehensive book data
- Laravel community for excellent documentation and support
- Vue.js community for reactive frontend capabilities
- Inertia.js for seamless full-stack development experience
