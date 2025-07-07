# Biblioteca

A modern book search and discovery platform built with Laravel and Vue.js. Biblioteca leverages the Google Books API to provide users with comprehensive book search capabilities, detailed book information, and an intuitive browsing experience with personal library management.

## Key Features

- **Book Search**: Search books by title, author, or keywords using Google Books API
- **User Authentication**: Secure registration and login with Laravel Fortify
- **Personal Library**: Add books to your personal library with reading status tracking
- **Reading Status Management**: Organize books as "Want to Read", "Reading", or "Read"
- **Session-Based Security**: CSRF protection and secure session management
- **Author Discovery**: Clickable author links for exploring author-specific collections
- **Modern UI**: Responsive, card-based design with pagination and Material Symbols icons
- **Material Design Integration**: Google Material Symbols for consistent, scalable iconography
- **Component System**: Reusable Button and Icon components with comprehensive customization
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
   
   # Run database migrations (includes sessions table for authentication)
   ./vendor/bin/sail artisan migrate
   
   # Create session table for Fortify authentication
   ./vendor/bin/sail artisan session:table
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

## Authentication Setup

Biblioteca uses **Laravel Fortify** for session-based authentication with enhanced security features:

### Features
- **Registration & Login**: Secure user registration and authentication
- **Session Management**: Database-stored sessions for scalability
- **CSRF Protection**: Automatic CSRF token handling
- **Rate Limiting**: Protection against brute force attacks
- **Password Validation**: Strong password requirements
- **Inertia.js Integration**: Seamless SPA authentication flow

### Configuration
The authentication system is pre-configured with:
- Session driver set to `database`
- CSRF middleware enabled
- Rate limiting for login attempts
- Fortify views using Inertia.js components

### Default Test User
A test user is created during setup:
- **Email**: `test@example.com`
- **Password**: `password`

You can use this account to test the authentication system.

## Roles and Permissions System

Biblioteca implements a comprehensive roles and permissions system using **Spatie Laravel Permission** package, providing fine-grained access control for different user types.

### Setup

1. **Install and configure (already done):**
   ```bash
   # The package is already installed and configured
   # Run the role seeder to create roles and permissions
   ./vendor/bin/sail artisan db:seed --class=RolePermissionSeeder
   ```

2. **Assign admin role to a user:**
   ```bash
   # Using Tinker to assign admin role to first user
   ./vendor/bin/sail artisan tinker
   # In tinker console:
   $user = App\Models\User::first();
   $user->assignRole('admin');
   ```

### Available Roles

#### 1. **User** (Default Role)
- Basic library management permissions
- Can manage their own library
- Can add/remove books
- Can update book status
- Can sync books with external APIs

**Permissions:**
- `manage library` - Access to library management
- `add books` - Add books to personal library
- `remove books` - Remove books from personal library
- `update book status` - Change reading status
- `sync books` - Sync library with external sources

#### 2. **Librarian**
- Extended permissions for library management
- Can view all users' libraries
- Cannot manage users or system settings

**Permissions:**
- All User permissions, plus:
- `view all libraries` - View other users' libraries

#### 3. **Admin**
- Full system access
- Can manage users and assign roles
- Can access admin panel
- Can manage system settings

**Permissions:**
- All permissions (full access)
- `manage users` - User management access
- `assign roles` - Assign/remove roles from users
- `manage system` - System administration
- `view analytics` - View system analytics
- `manage settings` - System configuration

### Using Permissions in Code

#### Backend (Laravel)

**Controller Authorization:**
```php
// Check permission in controller
public function index(Request $request)
{
    // Using middleware
    if (!$request->user()->can('manage users')) {
        abort(403, 'Unauthorized');
    }
    
    // Or using gate
    Gate::authorize('manage users');
}
```

**Route Protection:**
```php
// Protect routes with middleware
Route::middleware(['can:manage users'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);
});
```

**Blade Templates:**
```php
@can('manage users')
    <a href="{{ route('admin.index') }}">Admin Panel</a>
@endcan
```

#### Frontend (Vue.js)

**Component Authorization:**
```vue
<template>
  <div>
    <!-- Show admin link only to users with permission -->
    <Link v-if="user && user.can_manage_users" :href="route('admin.index')">
      Admin Panel
    </Link>
  </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';

const { props } = usePage();
const user = props.auth.user;
</script>
```

### Admin Panel Features

The admin panel provides comprehensive user and role management:

#### 1. **User Management** (`/admin`)
- View all users with their assigned roles
- Assign/change user roles via dropdown
- Visual role indicators with color coding
- Paginated user list

#### 2. **Library Overview** (`/admin/libraries`)
- View all users' libraries
- See book collections with reading status
- Monitor user activity
- Books displayed with thumbnails and status indicators

#### 3. **Role & Permission Overview**
- Display all roles with their permissions
- Role descriptions and capability summaries
- Permission matrix view

### API Endpoints

#### Admin Management
- `GET /admin` - Admin dashboard (requires `manage users` permission)
- `GET /admin/libraries` - View all libraries (requires `view all libraries` permission)
- `POST /admin/users/{user}/assign-role` - Assign role (requires `assign roles` permission)
- `DELETE /admin/users/{user}/remove-role` - Remove role (requires `assign roles` permission)

### Creating Custom Roles and Permissions

#### 1. **Add New Permissions**
```php
// In RolePermissionSeeder.php
$permissions = [
    'manage library',
    'add books',
    // ... existing permissions
    'export data',        // New permission
    'import data',        // New permission
    'manage categories',  // New permission
];
```

#### 2. **Create Custom Role**
```php
// In RolePermissionSeeder.php
$customRole = Role::create(['name' => 'editor']);
$customRole->givePermissionTo([
    'manage library',
    'add books',
    'remove books',
    'update book status',
    'manage categories',  // Custom permission
]);
```

#### 3. **Assign Custom Role**
```php
// Programmatically
$user = User::find(1);
$user->assignRole('editor');

// Or via admin panel dropdown
```

### Permission Checking Examples

#### Check Single Permission
```php
// Laravel
if ($user->can('manage users')) {
    // User can manage users
}

// Vue.js
if (user.can_manage_users) {
    // Show admin features
}
```

#### Check Multiple Permissions
```php
// Laravel - Check if user has ANY of these permissions
if ($user->hasAnyPermission(['manage users', 'view all libraries'])) {
    // User has at least one permission
}

// Laravel - Check if user has ALL permissions
if ($user->hasAllPermissions(['manage users', 'assign roles'])) {
    // User has all permissions
}
```

#### Check Role
```php
// Laravel
if ($user->hasRole('admin')) {
    // User is admin
}

// Check multiple roles
if ($user->hasAnyRole(['admin', 'librarian'])) {
    // User has admin or librarian role
}
```

### Security Considerations

1. **Route Protection**: All admin routes are protected with permission middleware
2. **Frontend Checks**: UI elements are conditionally shown based on permissions
3. **API Validation**: All API endpoints validate permissions before executing
4. **Role Hierarchy**: Roles are hierarchical (admin > librarian > user)
5. **Permission Caching**: Spatie package caches permissions for performance

### Extending the System

#### Adding New Admin Features
1. Create controller methods with permission checks
2. Add corresponding routes with middleware
3. Create frontend components with permission-based rendering
4. Update the admin navigation

#### Custom Permission Middleware
```php
// Create custom middleware
php artisan make:middleware CheckLibrarianPermission

// In middleware
if (!auth()->user()->hasRole(['admin', 'librarian'])) {
    abort(403);
}
```

This roles and permissions system provides a solid foundation for user management and access control, making it easy to extend and customize based on your specific needs.

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

### 🎨 Modern UI Components
- **Material Symbols Integration**: Complete Google Material Symbols icon library
- **Reusable Button Component**: Comprehensive button system with 8 variants and Material icons
- **Icon Component**: Standalone icon component with customizable variants, weights, and fills
- **Consistent Design System**: Unified color palette and typography with Inter font
- **Responsive Design**: Mobile-first approach with Tailwind CSS utilities
- **Library Interface**: Modernized "My Library" section with Material Symbols throughout
- **Interactive Elements**: All buttons, links, and controls use the unified component system

### 🎨 Material Design System
- **Material Symbols**: 2,500+ icons in 3 variants (Outlined, Rounded, Sharp)
- **Icon Customization**: Adjustable weight, fill, grade, and optical size
- **Backward Compatibility**: Legacy icon names automatically mapped to Material Symbols
- **Performance Optimized**: Variable fonts with efficient loading
- **Accessibility**: Proper ARIA labels and screen reader support
- **Unified Library UI**: All library actions (add, remove, sync, status) use Material Symbols

### ⚡ State Management with Pinia
- **Centralized State**: Consistent data across all components
- **Library Store**: Manage user's book collection and reading status
- **Search Store**: Handle search queries and results caching
- **Auth Store**: User authentication state management
- **Real-time Updates**: Reactive state updates across components

## Technology Stack

**Backend:**
- **Laravel 12.19.3** - PHP web framework for backend API and routing
- **Laravel Fortify** - Authentication scaffolding with session-based security
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
- **Authentication**: User registration, login, session validation
- **API Validation**: Request validation and error handling  
- **Database**: Migrations, relationships, and data integrity
- **CSRF Protection**: Token validation and security
- **Rate Limiting**: Authentication attempt limits

## System Requirements

- **PHP**: 8.2+
- **Node.js**: 18+
- **Docker**: Latest version with Docker Compose

## Version Information

| Technology | Version |
|------------|---------|
| Laravel | 12.19.3 |
| Laravel Fortify | 1.x |
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

### Authentication (Laravel Fortify)
- `GET /login` - Login form
- `POST /login` - Authenticate user
- `GET /register` - Registration form  
- `POST /register` - Register new user
- `POST /logout` - Log out user
- `GET /dashboard` - Protected dashboard page

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


