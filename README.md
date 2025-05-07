# Springfield Panel & Paint Website

This is the codebase for the Springfield Panel & Paint website, a modern automotive panel beating and spray painting service provider.

## Local Development Setup

1. Clone the repository:
   ```
   git clone https://github.com/Nimeshin/springfield.git
   cd springfield
   ```

2. Set up configuration files:
   - Copy `config.template.php` to `config.php` and update with your database credentials
   - Copy `db_init.template.php` to `db_init.php` if necessary (no changes required)
   ```
   cp config.template.php config.php
   cp db_init.template.php db_init.php
   ```

3. Install dependencies:
   ```
   npm install
   ```

4. Build CSS:
   ```
   npm run build:css
   ```

5. Set up your local server (WAMP, XAMPP, etc.) to point to the project directory

6. Initialize the database by visiting `db_init.php` in your browser

## Important Notes

- Sensitive files like `config.php` and database SQL files are not tracked in Git to protect credentials
- Always use the template files as a starting point for configuration files on new installations
- The CSS is generated using Tailwind CSS - run `npm run build:css` after making changes

## Features

- Modern, responsive design
- Admin dashboard for content management
- Blog system
- Gallery management
- Contact form with database storage

## Technologies Used

- PHP
- MySQL
- Tailwind CSS
- JavaScript 