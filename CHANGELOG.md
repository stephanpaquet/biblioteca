# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- **Generic Button Component**: Created a comprehensive, reusable Button component with:
  - Multiple variants (primary, secondary, success, danger, outline, ghost, link)
  - Size options (xs, sm, md, lg, xl)
  - Icon support (left, right, icon-only) with built-in icon library
  - Loading states with spinner animation
  - Disabled states with proper styling
  - Link functionality (href, router-link, external links)
  - Layout options (full-width, rounded variants, shadow control)
  - Accessibility features (focus states, screen reader support)
  - TypeScript-like prop validation
- **Button Documentation Page**: Added comprehensive documentation and examples at `/button-test` route
- **Button Integration**: Integrated Button component into existing pages:
  - Library page (sync buttons, filter controls)
  - Search form (search types, submit button, advanced options)
  - Consistent styling across all button implementations
- **Advanced Library Search and Filtering**: Added comprehensive search and filter system to library page with:
  - Text search across titles, authors, publishers, and ISBNs
  - Category filtering with dynamic category options
  - Status filtering (to read, reading, read, favorite)
  - Multi-field sorting (title, author, date added)
  - Sort order control (ascending/descending)
  - Clear filters functionality
  - Real-time results counter showing filtered vs total books
- **Enhanced Library UI**: Added dedicated search and filter interface with:
  - Prominent search bar with search icon
  - Four-column filter grid layout on desktop
  - Responsive design that stacks on mobile
  - Visual feedback for active filters
  - "No results" message when filters don't match any books
- **Book Categories System**: Added support for displaying and searching by book categories/subjects
- **SearchBySubject Component**: Created dedicated component for clickable category tags that trigger subject-specific searches
- **Category Display in BookGrid**: Categories are now displayed as styled pill buttons (green background) with click-to-search functionality
- **Page Count Display in BookGrid**: Added "Page X of Y" indicator in BookGrid component header for better navigation context
- **Page Numbers in Search Results**: Each book card now shows current page number when browsing paginated results
- **AddToLibraryButton on Book Details**: Added library management functionality to the book details page
- **Toast Notifications**: Replaced custom message displays with consistent toast notifications across the application
- **Library Management with BookGrid**: Library page now uses the BookGrid component for consistent interface
- **Remove from Library Functionality**: Added proper remove book functionality through the library store with API integration
- **Book Sync Functionality**: Added sync buttons to update book information from Google Books API
- **Individual Book Sync**: Each book in library can be synced individually with Google Books API data
- **Bulk Library Sync**: Added "Sync All Books" button to update all library books at once
- **Sync API Endpoint**: New `library/{book}/sync` PATCH endpoint for updating book information
- **Custom Color System**: Implemented branded color palette with primary, secondary, success, and danger color schemes
- **Professional UI Design**: Enhanced visual hierarchy with custom shadows, animations, and consistent spacing
- **Inter Font Integration**: Added Google Inter font for better typography and readability

### Changed
- **Button Component Visual Improvements**: Enhanced button visibility and styling:
  - Improved `ghost` variant with light gray background and border instead of transparent
  - Added new `neutral` variant with white background and gray border for subtle but visible buttons
  - Updated Library page buttons to use `outline` variant instead of invisible `ghost` variant
  - Updated SearchForm buttons to use `neutral` variant for better visibility
  - All buttons now have proper contrast and are clearly visible on white backgrounds
- **Google Fonts Implementation**: Moved Google Fonts import from CSS to HTML head section:
  - Removed CSS `@import` that was causing PostCSS build errors
  - Added proper `<link>` tags in HTML head with preconnect optimization
  - Improved font loading performance with `display=swap` parameter
- **Google Books API Result Limiting**: Limited totalItems to maximum of 300 to prevent excessive pagination and improve user experience
- **Library Store Route Correction**: Fixed library store to use correct `/library` route instead of `/api/library`
- **AddToLibraryButton Toast Integration**: Replaced custom message handling with toast notifications for better user feedback
- **Library.vue Component Architecture**: Refactored to use BookGrid component instead of custom book display, providing feature parity with search results
- **Book Data Transformation**: Added computed property to transform library book data to Google Books API format for BookGrid compatibility
- **Consistent Interactive Elements**: Library page now includes all interactive features (clickable authors, categories, ISBNs, publishers)
- **Color Scheme Modernization**: Updated entire application to use consistent primary/secondary color palette
- **Enhanced Button Styling**: Improved button designs with better hover states, shadows, and visual feedback
- **Typography Improvements**: Upgraded to Inter font for better readability and modern appearance
- **Card Design Enhancement**: Book cards now feature softer shadows and improved visual hierarchy

### Fixed
- **Library Store API Routes**: Corrected API endpoint from `/api/library` to `/library` to match Laravel route definitions
- **Remove Book Functionality**: Fixed removeFromLibrary function to use proper library store method with toast notifications
- **Code Duplication**: Eliminated duplicate library management functions by using centralized store methods

### Removed
- **Custom Library Book Display**: Removed custom book grid HTML in favor of reusable BookGrid component
- **Duplicate Library Functions**: Removed duplicate updateStatus and removeFromLibrary functions from Library.vue
- **Custom Message Handling**: Removed local message state management in favor of toast notifications

### Technical Improvements
- **Component Reusability**: Created generic Button component to eliminate code duplication and ensure consistent styling
- **Icon System**: Built-in icon library with commonly used SVG icons (search, plus, minus, check, x, arrows, sync, trash, edit, external link)
- **Props Validation**: Implemented comprehensive prop validation similar to TypeScript for better development experience
- **Dynamic Component Rendering**: Button component can render as button, anchor, or router-link based on props
- **Accessibility Enhancement**: Added proper ARIA attributes, focus management, and keyboard navigation support
- **Performance Optimization**: Smart class computation and efficient re-rendering with Vue 3 reactivity
- **Developer Experience**: Comprehensive documentation with live examples and code snippets
- **Consistent UI/UX**: Library page now provides the same user experience as search results with all interactive features
- **Code Maintainability**: Centralized book display logic in BookGrid component reduces code duplication
- **Toast System Integration**: Unified notification system across all library operations
- **API Integration**: Proper integration with Laravel backend for all library operations
- **Component Reusability**: Enhanced component architecture for better code organization

### Enhanced Search System
- **Enhanced Search System**: Comprehensive search functionality with multiple search types (ISBN, Title, Author, Publisher, Subject, Description)
- **Advanced Search Filters**: Language restriction, publication date range, print type selection, and result ordering
- **Smart Search Interface**: Dynamic search form with collapsible advanced options and contextual placeholders
- **Search Type Selector**: Visual buttons for easy switching between search types
- **Clickable Author Links**: Authors in book results are now clickable links that trigger author-specific searches
- **Clickable ISBN Links**: Industry identifiers (ISBN-10, ISBN-13) are displayed as clickable links that trigger ISBN-specific searches
- **Clickable Publisher Links**: Publisher information is displayed with clickable links that trigger publisher-specific searches
- **Publication Date Display**: Added publication date information alongside publisher details for better book context
- **Search Results Pagination**: Comprehensive pagination system for Google Books API search results with page navigation controls
- **Results Summary Display**: Shows current page range and total results count for better search context
- **Reusable Paginator Component**: Created dedicated pagination component for consistent navigation across the application
- **Top and Bottom Pagination**: Pagination controls displayed at both top and bottom of search results for better user experience
- **Laravel Pint Integration**: Added Laravel Pint for automated PHP code formatting and style consistency
- **Code Formatting Configuration**: Created `pint.json` with Laravel preset and custom rules for ordered class elements

### Previous Changes
- **BookGrid Component Enhancement**: Authors, ISBNs, and publishers are now displayed as clickable links instead of plain text, enabling quick searches
- **Industry Identifiers Display**: Added professional display of ISBN-10, ISBN-13, and other book identifiers with proper formatting
- **Publisher Information**: Added publisher display with publication date and clickable publisher search functionality
- **SearchController Pagination**: Enhanced search controller to handle page parameters and calculate pagination metadata
- **Google Books API Integration**: Proper implementation of startIndex parameter for paginated search results
- **Component Architecture**: Refactored pagination into reusable component for better code organization and maintainability
- **SearchController Enhancement**: Updated to handle advanced search parameters with proper validation and error handling
- **GoogleBooksService Expansion**: Added `advancedSearch()` method with support for all Google Books API search parameters
- **Search Results Display**: Enhanced Search/Index.vue with better result presentation, error handling, and filter display
- **Search Routing**: Improved search routes to handle query parameters and maintain backward compatibility
- **Code Style Standardization**: Applied Laravel Pint formatting to entire codebase (71 files, 36 style issues fixed)
- **Import Organization**: Standardized and ordered import statements across all PHP files
- **Class Structure**: Applied ordered class elements for better code organization
- **Code Consistency**: Implemented consistent spacing, braces positioning, and quote usage

### Previous Fixes
- **UserBooks Authentication**: Fixed `Method Illuminate\Auth\SessionGuard::books does not exist` error by properly accessing user books through the authenticated user model
- **Auth Helper Issues**: Resolved authentication method calls in UserBooks action and HomeController using proper Auth facade
- **Search Form Routing**: Fixed search form to properly navigate to `/search` route with parameters
- **Component Import Paths**: Corrected Vue component import paths for proper module resolution
- **Advanced Search Parameters**: Proper handling of date filters, language restrictions, and ordering in Google Books API calls

### Previous Technical Improvements
- **Interactive Book Display**: Enhanced user experience with clickable author names, ISBN identifiers, and publisher information in book displays
- **Publisher Search Integration**: Added dedicated publisher search functionality with proper formatting and validation
- **Pagination System**: Complete pagination implementation with Google Books API startIndex support, page navigation, and smart page number display
- **Results Navigation**: Responsive pagination controls with mobile-friendly design and accessibility features
- **Dual Pagination Display**: Strategic placement of pagination controls at top and bottom of results for optimal user experience
- **ISBN Search Integration**: Added dedicated ISBN search functionality with proper formatting and validation
- **Google Books API Integration**: Enhanced API query building with support for specialized search operators (intitle, inauthor, isbn, etc.)
- **Search Parameter Validation**: Comprehensive validation for all search inputs and filters
- **Error Handling**: Robust error handling for failed API requests with user-friendly error messages
- **Caching Strategy**: Extended caching support for advanced search queries
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
