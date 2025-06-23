# Installation

1. Clone the repository:
   ```sh
   git clone https://github.com/your-username/biblioteca.git
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
