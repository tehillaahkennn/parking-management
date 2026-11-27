# Parking Management System

A web-based Parking Management System built with Laravel and MySQL to manage vehicle entry and exit, parking slot availability, parking fees, payments, user access, audit logs, and operational reports.

## Project Overview

The system helps parking administrators and staff manage day-to-day parking operations through a centralized application. It tracks parking sessions, calculates parking fees, records payments, and maintains an audit trail of important activities.

## Technology Stack

- **Backend:** PHP and Laravel
- **Database:** MySQL / MariaDB
- **Frontend:** Blade templates, HTML, CSS, and JavaScript
- **Authentication:** Laravel authentication
- **Authorization:** Role-based access control
- **Testing:** PHPUnit / Laravel testing tools
- **Development environment:** Laragon on Windows

## Main Features

### 1. Authentication and User Roles
- Login and logout
- Protected pages for authenticated users
- Role-based access for Admin and Parking Staff

### 2. Vehicle Management
- Register vehicle information
- Store plate number, owner name, and vehicle type
- Associate vehicles with parking records

### 3. Parking Slot Management
- Manage parking slot information
- Track slot availability
- Mark slots as occupied when a vehicle checks in
- Release slots after checkout

### 4. Parking Management
- Check in vehicles to available parking slots
- Record parking entry and exit times
- Calculate parking fees
- Prevent check-in to occupied slots
- Prevent a completed parking session from being checked out again

### 5. Payment Management
- Record payments for completed parking sessions
- Support Cash, GCash, and Card payment methods
- Use the calculated parking fee as the payment amount
- Reject payment before checkout
- Prevent duplicate payments for a parking record

### 6. Reports
- Parking activity report
- Payment report
- Date-based filtering
- Printable report views

### 7. Audit Trail
- Record important system activities
- Track actions such as login, logout, creation, updates, deletion, check-in, checkout, and payment
- Search and print audit records

### 8. Staff Management
- Provide administrative access to staff management
- Restrict unauthorized users from accessing protected administrative pages

## User Roles

| Feature | Admin | Parking Staff |
|---|---|---|
| Login and logout | Yes | Yes |
| Parking operations | Available according to assigned permissions | Available according to assigned permissions |
| Staff management | Admin-only | Restricted |
| Audit trail | Admin-only | Restricted |

Access to individual features is controlled by the application's route middleware and authorization rules.

## Parking Fee Rules

The current parking fee calculator uses the following rules:

- Minimum charge: ₱20
- Rate: ₱20 per hour or fraction of an hour
- Any positive parking duration up to one hour is charged ₱20.
- A duration greater than one hour and up to two hours is charged ₱40.
- The fee is calculated during checkout.

**Formula:**

`Parking Fee = max(1, ceiling(Parking Duration in Minutes / 60)) × ₱20`

## Requirements

- PHP 8.3 or a compatible supported PHP version
- Composer
- MySQL or MariaDB
- Node.js and npm, if frontend assets need to be built
- Git
- Laragon or another compatible local development environment

## Installation and Setup

### 1. Clone the repository

Replace the example URL with the actual GitHub repository URL.

```bash
git clone <YOUR_GITHUB_REPOSITORY_URL>
cd parking-management
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Configure the environment

Create your local environment file if it does not exist:

```bash
copy .env.example .env
```

Generate the application key if setting up a new local environment:

```bash
php artisan key:generate
```

### 4. Configure the database

Create a MySQL database named `parking_management`, or use another database name and update the configuration accordingly.

Update the database settings in `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=parking_management
DB_USERNAME=root
DB_PASSWORD=
```

Use the username and password configured for your own MySQL installation.

**Important:** Verify the database migrations and schema before running migrations on a new or existing database. Do not use destructive database reset commands on a database containing important records. The application's schema and migration files must be consistent for a reproducible fresh installation.

### 5. Prepare the database

Ensure that the required application tables and relationships are created using the project's verified migrations or approved database setup procedure.

The application uses these main tables:

- `users`
- `vehicles`
- `parking_slots`
- `parking_records`
- `payments`
- `audit_logs`

### 6. Start the development server

```bash
php artisan serve
```

Open the local address shown in the terminal, usually:

`http://127.0.0.1:8000`

Log in using an authorized account configured for the application.

## Running Automated Tests

Run the complete test suite:

```bash
php artisan test
```

Run the parking and payment workflow tests:

```bash
php artisan test --filter=ParkingPaymentWorkflowTest
```

Run the role authorization tests:

```bash
php artisan test --filter=RoleAuthorizationTest
```

Run the parking fee calculator unit test:

```bash
php artisan test --filter=ParkingFeeCalculatorTest
```

### Current Test Status

At the time of writing, the full test suite passes:

- **15 tests passed**
- **42 assertions**
- **0 failures**

The tests cover parking fee calculations, authentication access, role authorization, vehicle check-in, slot availability, checkout, payment validation, and duplicate-payment prevention.

## Basic User Guide

### Vehicle Check-in
1. Log in with an authorized account.
2. Open the parking management page.
3. Enter the vehicle's plate number, owner name, and vehicle type.
4. Select an available parking slot.
5. Submit the check-in form.

### Vehicle Checkout
1. Locate the active parking record.
2. Select the checkout action.
3. Review the calculated parking fee.
4. Confirm that the parking record is completed and the slot is available.

### Recording a Payment
1. Open the payments page.
2. Select an eligible completed parking record.
3. Choose Cash, GCash, or Card.
4. Submit the payment.
5. Verify that the payment appears in the payment records.

### Viewing Reports
1. Open the parking activity or payment report.
2. Select the desired date range.
3. Apply the filters.
4. Print the report when needed.

### Viewing the Audit Trail
1. Log in as an administrator.
2. Open the audit trail.
3. Search for relevant activity records.
4. Print the results if required.

## Project Structure

```text
parking-management/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   ├── Models/
│   └── Services/
├── database/
│   ├── factories/
│   └── migrations/
├── resources/
│   └── views/
├── routes/
│   └── web.php
├── tests/
│   ├── Feature/
│   └── Unit/
├── .env.example
├── composer.json
└── README.md
```

## Security Notes

- Keep `.env` out of version control.
- Do not publish real passwords, API keys, or database credentials.
- Use appropriate role restrictions for administrative pages.
- Validate user input on the server.
- Back up important database records before making schema changes.

## Contributors

Add the actual project members and their contributions here.

| Contributor | Responsibilities |
|---|---|
| Project member 1 | Application development and integration |
| Project member 2 | Documentation and user manual |
| Project member 3 | Diagrams, testing, and quality assurance |

Update this table to reflect the team's actual contributions.

## Repository

**GitHub URL:** `<YOUR_GITHUB_REPOSITORY_URL>`

## License

Specify the license required by your course or project team.
