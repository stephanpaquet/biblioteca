# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]
- Initial CHANGELOG created.

## [2025-06-24]
- Migrated database from MySQL to MariaDB (Docker/Sail).
- Implemented API authentication with Laravel Sanctum.
- Added user registration, login, logout, and password reset via API and frontend.
- Integrated Inertia.js + Vue 3 authentication UI (Login/Register/Logout links in layout).
- Added password reset request page and route.
- Cleaned up duplicate migrations for personal access tokens.
- Updated `.env.example` and documentation for MariaDB.
- Improved README with tech stack, roadmap, and MariaDB notes.
- Added web routes for login, register, and password reset pages.
- General UI/UX improvements and bug fixes.
- Built book search and detail pages using Google Books API.
- Implemented author search with clickable author links.
- Added paginated, card-based book results with modern UI.
- Book cards include preview links and details navigation.
- Created reusable paginator component for book results.
- Book detail page displays all relevant book info from API.
- Improved error handling and loading states on book pages.

---

> This file follows [Keep a Changelog](https://keepachangelog.com/en/1.0.0/). Dates are in YYYY-MM-DD format.
