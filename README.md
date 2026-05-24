# Team Expense Tracker

A Laravel 10 internal expense tracker where team members submit claims, managers approve or reject them, and admins manage users, categories, and monthly category budgets.

## Stack

- Laravel 10, PHP 8.1+
- MySQL
- Blade, vanilla JavaScript-ready layout, Vite CSS
- Laravel Sanctum is kept from the base install for the authenticated API route

## Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Create a MySQL database, then update `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=expense_tracker
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations and seeders:

```bash
php artisan migrate --seed
npm run dev
php artisan serve
```

If you use XAMPP, make sure MySQL is running and the database name in `.env` exists.

## Frontend Assets

This project uses Laravel's default Vite setup for loading `resources/css/app.css` and `resources/js/app.js`.

For local development, start the Vite dev server in a separate terminal:

```bash
npm run dev
```

For a production-style compiled asset build, run:

```bash
npm run build
```

If `npm install` has not been run, the CSS/JS assets will not compile or load correctly.

## Seeded Accounts

All seeded accounts use password `password`.

| Role | Email |
| --- | --- |
| Admin | admin@example.com |
| Manager | manager@example.com |
| Team Member | team1@example.com |
| Team Member | team2@example.com |

## Main Features

- Manual register/login/logout. New registrations are always `team_member`.
- Role middleware returns `403` for unauthorized role access.
- Team members can create, edit, delete, and filter their own claims.
- Claims can only be edited or deleted by the submitter while pending.
- Managers can approve/reject pending claims with comments.
- Managers cannot approve their own claims; this is enforced in the controller.
- Admins can manage users, categories, monthly budget limits, and override claim statuses.
- Budget utilization is calculated per calendar month and resets automatically by month.
- Bonus API endpoint: `GET /api/claims` returns the authenticated user's claims.
- Bonus admin CSV export is available on the global claims screen with current filters applied.
- Claim approval/rejection sends an email notification; `MAIL_MAILER=log` is enough for local review.

## Notes

- The optional feature tests for login, claim submission validation, and manager approve/reject are not included in the final submission.

## Design Notes

Budget logic lives in `App\Services\BudgetService` instead of views or controllers. This keeps the monthly approved-spend query reusable for team budget views, manager approval warnings, and future reports. The service uses SQL aggregation with `sum()` and `groupBy()` so claims are not loaded into PHP and filtered in memory.

Claim amounts and category budget limits use `DECIMAL(12,2)`. Currency should not be stored as float because binary floating-point math can introduce rounding errors. Validation rejects amounts with more than two decimal places.

The budget limit is soft. `BudgetService::approvalWarning()` returns an `exceeds` boolean and message; the manager controller decides how to display it while still allowing approval.

Admin category deletion is blocked by database constraints if claims exist. In that case the UI reports the issue and the category can be marked inactive instead.
