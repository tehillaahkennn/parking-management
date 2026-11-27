# Parking Management System

A web-based Parking Management System built with Laravel, PHP, and MySQL. The application manages vehicle entry and exit, parking slot availability, parking fees, payments, user access, audit logs, and operational reports.

## Project Overview

The Parking Management System helps administrators and parking staff manage daily parking operations through a centralized web application. It records parking sessions, calculates fees, processes payments, manages vehicle information, and maintains an audit trail of important activities.

## Technology Stack

- **Backend:** PHP 8.3 and Laravel
- **Database:** MySQL / MariaDB
- **Frontend:** Blade templates, HTML, CSS, and JavaScript
- **Authentication:** Laravel authentication
- **Authorization:** Role-based access control
- **Testing:** PHPUnit and Laravel testing tools
- **Development Environment:** Laragon on Windows
- **Version Control:** Git and GitHub

## Main Features

### 1. Authentication and User Management
- Login and logout
- Protected pages for authenticated users
- Role-based access for Admin and Parking Staff
- Staff account creation, editing, activation, and deactivation
- Restrictions on unauthorized administrative access

### 2. Vehicle Management
- Create, view, update, and delete vehicle records
- Store plate number, owner name, and vehicle type
- Search and paginate vehicle records
- Protect vehicles with existing parking history from deletion

### 3. Parking Slot Management
- Manage parking slot information
- Track slot availability
- Mark slots as occupied during check-in
- Release slots after checkout
- Validate slot availability and vehicle-type compatibility

### 4. Parking Management
- Register vehicle check-in
- Record entry and exit times
- Calculate parking fees during checkout
- Prevent check-in to occupied slots
- Prevent completed parking sessions from being checked out again
- Update parking records and slot status within database transactions

### 5. Payment Management
- Record payments for completed parking sessions
- Support Cash, GCash, and Card payment methods
- Use the calculated parking fee as the payment amount
- Reject payment before checkout
- Prevent duplicate payments for a parking record

### 6. Reports
- Parking activity report
- Payment report
- Date-range filtering
- Revenue calculation for payment reports
- Report printing, subject to verification of the final interface

### 7. Audit Trail
- Record important system activities
- Track login, logout, record changes, check-in, checkout, and payment activities
- Store the associated user, action, description, and IP address when available
- Search and review audit records
- Restrict audit log access to administrators

## User Roles

| Feature | Admin | Parking Staff |
|---|---|---|
| Login and logout | Allowed | Allowed |
| Parking operations | Allowed | Allowed |
| Vehicle management | Allowed | Allowed |
| Payment management | Allowed | Allowed |
| Parking slot management | Full management access | View access |
| Staff management | Allowed | Restricted |
| Reports | Allowed | Restricted |
| Audit trail | Allowed | Restricted |

Access is controlled by the application's route middleware and authorization rules. Verify the final permissions against the implemented routes before submission.

## Parking Fee Rules

The parking fee calculator uses the following rules:

- Minimum charge: PHP 20
- Rate: PHP 20 per hour or fraction of an hour
- A positive parking duration of up to one hour is charged PHP 20.
- A duration greater than one hour and up to two hours is charged PHP 40.
- The fee is calculated during checkout.

**Formula:**

`Parking Fee = max(1, ceiling(Parking Duration in Minutes / 60)) * PHP 20`

The standard parking fee calculator uses a rate of PHP 20 per hour. A separate discounted calculator demonstrates the fee strategy design and is not the default checkout calculator.

## Requirements

- PHP 8.3 or another version supported by the installed Laravel dependencies
- Composer
- MySQL or MariaDB
- Node.js and npm, if frontend assets need to be built
- Git
- Laragon or another compatible local development environment

## Installation and Setup

### 1. Clone the repository

Replace the placeholder with the actual GitHub repository URL.

```bash
git clone https://github.com/tehillaahkennn/parking-management.git
cd parking-management
```

### 2. Install dependencies

```bash
composer install
```

If frontend assets are required, install and build them using the project's package scripts:

```bash
npm install
npm run build
```

### 3. Configure the environment

Create a local environment file only if one does not already exist:

```powershell
Copy-Item .env.example .env
```

Do not overwrite an existing `.env` file containing local configuration.

Generate the application key only when setting up a new local environment without an existing key:

```bash
php artisan key:generate
```

### 4. Configure the database

For a fresh installation, create a dedicated MySQL database named `parking_management`. Configure `.env` to match your local MySQL setup:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=parking_management
DB_USERNAME=root
DB_PASSWORD=
```

Use the username and password configured for your database server. Never publish real credentials.

### 5. Prepare the database

For a fresh installation, verify that `.env` points to the intended database, inspect the migration files, and then run:

```bash
php artisan migrate
```

**Important database safety rules:**

- Back up important records before making schema changes.
- Do not run `php artisan migrate:fresh`, `php artisan db:wipe`, or other destructive reset commands on a database containing important records.
- Do not run seeders on an existing database without inspecting the data they create.
- For an existing installation, verify the database schema and migration status before applying changes.
- Do not use a production database for automated tests.

The main application tables are:

- `users`
- `vehicles`
- `parking_slots`
- `parking_records`
- `payments`
- `audit_logs`

Laravel also uses infrastructure tables for sessions, caching, queues, and migrations.

### 6. Start the development server

```bash
php artisan serve
```

Open the local address shown in the terminal, usually:

`http://127.0.0.1:8000`

Log in using an authorized account configured for the application. Do not assume a default administrator account exists unless one has been securely created.

## Running Automated Tests

Run the full test suite:

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

Run the audit trail tests:

```bash
php artisan test --filter=AuditTrailTest
```

### Current Test Status

The latest verified full test run passed:

- **41 tests passed**
- **115 assertions**
- **0 failures**

The tests cover parking fee calculations, audit logging, role authorization, vehicle management, parking slot management, parking checkout, payment validation, and report filtering.

Run the test suite again before submission and update these figures if the results change.

## Basic User Guide

### Vehicle Check-in

1. Log in with an authorized account.
2. Open the parking management page.
3. Enter the vehicle's plate number, owner name, and vehicle type.
4. Select a compatible available parking slot.
5. Submit the check-in form.

### Vehicle Checkout

1. Locate the active parking record.
2. Select the checkout action.
3. Allow the system to calculate the parking fee.
4. Verify that the parking record is completed and the slot is available.

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
4. Review the results and use the print function if available in the final interface.

### Viewing the Audit Trail

1. Log in as an administrator.
2. Open the audit trail.
3. Search for relevant activity records.
4. Review the available audit details.

## Object-Oriented Programming Implementation

The application demonstrates OOP concepts through the parking fee calculation strategy.

| OOP Principle | Implementation | Purpose |
|---|---|---|
| Abstraction | `app/Contracts/ParkingFeeStrategy.php` - `ParkingFeeStrategy` interface | Defines the required fee calculation contract |
| Inheritance | `app/Services/Fees/AbstractParkingFeeStrategy.php` - `AbstractParkingFeeStrategy` | Provides shared fee calculation behavior |
| Encapsulation | `AbstractParkingFeeStrategy` - private readonly `$hourlyRate` property and constructor validation | Protects the configured rate from direct external modification |
| Polymorphism | `app/Services/ParkingFeeCalculator.php`, `app/Services/Fees/DiscountedParkingFeeCalculator.php`, and the `ParkingFeeStrategy` binding in `app/Providers/AppServiceProvider.php` | Allows different calculator implementations to follow the same interface |

The checkout logic in `app/Http/Controllers/ParkingRecordController.php` depends on the `ParkingFeeStrategy` interface rather than a specific calculator class. The standard calculator is registered through the service container.

The discounted calculator is a separate implementation used to demonstrate the strategy design; it is not the default checkout calculator.

## Project Structure

```text
parking-management/
|-- app/
|   |-- Contracts/
|   |-- Http/
|   |   |-- Controllers/
|   |   |-- Middleware/
|   |-- Models/
|   |-- Providers/
|   |-- Services/
|       |-- Fees/
|-- database/
|   |-- factories/
|   |-- migrations/
|   |-- seeders/
|-- resources/
|   |-- views/
|-- routes/
|   |-- web.php
|-- tests/
|   |-- Feature/
|   |-- Unit/
|-- .env.example
|-- composer.json
|-- README.md
```

## Security Notes

- Keep `.env` out of version control.
- Do not publish real passwords, API keys, or database credentials.
- Use role restrictions for administrative pages.
- Validate user input on the server.
- Use database transactions for operations that update related records.
- Back up important records before schema changes.
- Use a separate test database for automated tests.

## Contributors

| Contributor | Responsibilities |
|---|---|
| Rivas, Tehillah Ken J. | Application development and integration |
| Copleting, John Noel | Documentation and user manual |
| Cordero, Claudene James | Diagrams, testing, and quality assurance |

Update the responsibilities to reflect each member's actual contribution.


## Repository

**GitHub URL:** https://github.com/tehillaahkennn/parking-management.git

## License

Specify the license required by your course or project team. If no license has been selected, confirm the requirement before adding a license.
