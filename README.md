# Dental Clinic Accounting Web Application

![Version](https://img.shields.io/badge/version-1.0.0-blue)
![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php)
![License](https://img.shields.io/badge/license-MIT-green)

A comprehensive web application designed for dental clinics to manage accounting records, track patient treatments, and generate detailed reports with visual tooth selection interface.

## Key Features

-   **Interactive Tooth Selection**: Visual SVG-based tooth chart for precise treatment recording
-   **Patient Management**: Complete CRUD operations for customer records
-   **Treatment Tracking**: Detailed entry system with pricing, discounts, and cost tracking
-   **Inventory Management**: Item catalog with pricing and cost management
-   **Advanced Filtering**: Search and filter entries by date, customer, item, and more
-   **PDF Export**: Generate detailed reports with customizable column selection
-   **Responsive Design**: Modern Bootstrap-based UI with dark/light theme support
-   **Real-time Calculations**: Automatic price calculations with discount support
-   **Soft Delete Protection**: Safe deletion with recovery options for all entities

## Table of Contents

-   [Demo](#demo)
-   [Technology Stack](#technology-stack)
-   [Getting Started](#getting-started)
    -   [Prerequisites](#prerequisites)
    -   [Installation](#installation)
    -   [Configuration](#configuration)
    -   [Database Setup](#database-setup)
    -   [Running the Application](#running-the-application)
    -   [Setup Guide Video](#setup-guide-video)
-   [Project Structure](#project-structure)
    -   [Models](#models)
    -   [Controllers](#controllers)
    -   [Views](#views)
    -   [Components](#components)
-   [Features & Usage](#features--usage)
    -   [Entry Management](#entry-management)
    -   [Patient Management](#patient-management)
    -   [Item Management](#item-management)
    -   [Visual Teeth Selection](#visual-teeth-selection)
    -   [Search & Filtering](#search--filtering)
    -   [PDF Export](#pdf-export)
-   [API Routes](#api-routes)
-   [Development](#development)

## Demo

<div align="center">
  <video src="https://github.com/user-attachments/assets/577c3432-b4a3-4f0f-8f1b-124e50308783" alt="Demo Video" style="border-radius: 15px; max-width: 80%;" controls>
    <em>If you can't see the video, <a href="https://github.com/user-attachments/assets/577c3432-b4a3-4f0f-8f1b-124e50308783">click here to watch it directly</a>.</em>
  </video>
</div>

## Technology Stack

-   **Backend**: Laravel 12.x
-   **Frontend**: Bootstrap 5, jQuery, HTML5, CSS3
-   **Database**: MySQL with soft delete support
-   **PDF Generation**: Spatie Laravel PDF with Puppeteer
-   **Interactive Components**: SVG-based tooth visualization
-   **PHP Version**: 8.2+
-   **Data Protection**: Eloquent ORM with soft delete functionality

## Getting Started

### Prerequisites

Before setting up the application, ensure you have the following installed:

1. **Web Server** (Choose one):

    - **XAMPP** (Recommended for beginners): [apachefriends.org](https://www.apachefriends.org/)
    - **WAMP**: [wampserver.com](http://www.wampserver.com/)
    - **MAMP**: [mamp.info](https://www.mamp.info/)
    - Or any other Apache/Nginx server

2. **Composer**

    - Download from [getcomposer.org](https://getcomposer.org/download/)

3. **Node.js & NPM**

    - Download from [nodejs.org](https://nodejs.org/)
    - Required for PDF generation (Puppeteer dependency)

4. **MySQL Database Server and PHP** (Usually included with the web server packages above)

### Installation

1. **Clone or Download the Repository**

    ```bash
    git clone https://github.com/Essa-Ramzy/Dental-Accounting.git
    # or download and extract the ZIP file
    ```

2. **Move to Web Server Directory**

    - For XAMPP: Move the project folder to `xampp/htdocs/`
    - For other servers: Move to the appropriate document root

3. **Install PHP Dependencies**

    ```bash
    cd Dental-Accounting
    composer install
    ```

4. **Install Node.js Dependencies**
    ```bash
    npm install
    ```

### Configuration

1. **Environment Setup**

    ```bash
    # Copy the example environment file
    cp .env.example .env

    # Generate application key
    php artisan key:generate
    ```

2. **Database Configuration**

    Open the `.env` file and update the database settings:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=dental_accounting
    DB_USERNAME=root
    DB_PASSWORD=your_password
    ```

3. **Create Database**
    - Open phpMyAdmin (usually at `http://localhost/phpmyadmin`)
    - Create a new database named `dental_accounting` (or your chosen name)

### Database Setup

1. **Run Migrations**

    ```bash
    php artisan migrate
    ```

2. **Seed Sample Data** (Optional)
    ```bash
    php artisan db:seed
    ```
    This will create sample customers, items, and entries for testing.

### Running the Application

1. **Start Web Server Services**

    - **XAMPP**: Open XAMPP Control Panel and start Apache and MySQL
    - **Other servers**: Start according to your server's documentation

2. **Access the Application**

    - Open your browser and navigate to:
        ```
        http://localhost/Dental-Accounting/public
        ```
    - Or if using a virtual host: `http://your-domain.local`

3. **Development Server** (Alternative - PDF Generation Won't Work)
    ```bash
    php artisan serve
    ```
    Then access at `http://localhost:8000`

### Setup Guide Video

<div align="center">
  <video src="https://github.com/Essa-Ramzy/Dental-Accounting/assets/100089248/f823d116-ac87-4207-981a-1012028f9347" alt="Setup Guide Video" style="border-radius: 15px; max-width: 80%;" controls>
    <em>If you can't see the video, <a href="https://github.com/Essa-Ramzy/Dental-Accounting/assets/100089248/f823d116-ac87-4207-981a-1012028f9347">click here to watch it directly</a>.</em>
  </video>
</div>

## Project Structure

This Laravel application follows the MVC (Model-View-Controller) architecture with additional components for enhanced functionality.

### Models

The application uses three main Eloquent models representing the core entities:

#### Customer Model (`app/Models/Customer.php`)

Represents clinic patients/customers.

**Database Fields:**

-   `id`: Primary key (auto-increment)
-   `name`: Customer's full name (unique)
-   `created_at`: Record creation timestamp
-   `updated_at`: Last modification timestamp
-   `deleted_at`: Soft delete timestamp (nullable)

**Relationships:**

-   `hasMany('Entry')`: A customer can have multiple treatment entries

**Key Features:**

-   Unique name constraint to prevent duplicates
-   Soft delete functionality for data recovery
-   Factory and seeder support for testing

#### Item Model (`app/Models/Item.php`)

Represents dental procedures, treatments, or products.

**Database Fields:**

-   `id`: Primary key (auto-increment)
-   `name`: Item/procedure name (unique)
-   `price`: Selling price (decimal, stored with 2 decimal places)
-   `cost`: Item cost (decimal, stored with 2 decimal places)
-   `description`: Optional detailed description or notes
-   `created_at`: Record creation timestamp
-   `updated_at`: Last modification timestamp
-   `deleted_at`: Soft delete timestamp (nullable)

**Relationships:**

-   `hasMany('Entry')`: An item can be used in multiple entries

**Key Features:**

-   Price and cost stored as decimals with 2 decimal places for precision
-   Optional description field for detailed procedure notes
-   Unique name constraint
-   Soft delete functionality for data recovery

#### Entry Model (`app/Models/Entry.php`)

Represents individual treatment records linking customers to items.

**Database Fields:**

-   `id`: Primary key (auto-increment)
-   `customer_id`: Foreign key to customers table
-   `item_id`: Foreign key to items table
-   `date`: Treatment date
-   `teeth`: Comma-separated list of affected teeth (e.g., "UR-125, UL-2, LL-45")
-   `amount`: Number of teeth/units treated
-   `unit_price`: Price per unit/tooth (decimal, 2 decimal places)
-   `discount`: Discount in pounds (decimal, 2 decimal places)
-   `price`: Total calculated price after discount (decimal, 2 decimal places)
-   `cost`: Total cost for the treatment (decimal, 2 decimal places)
-   `created_at`: Record creation timestamp
-   `updated_at`: Last modification timestamp
-   `deleted_at`: Soft delete timestamp (nullable)

**Relationships:**

-   `belongsTo('Customer')`: Each entry belongs to one customer
-   `belongsTo('Item')`: Each entry belongs to one item/procedure

**Computed Properties:**

-   `teeth_list`: Converts teeth string to collection for easier manipulation
-   `unit_cost`: Calculates cost per unit/tooth

**Key Features:**

-   Automatic price calculation based on amount, unit_price, and discount
-   Flexible teeth notation system supporting dental quadrants (UR, UL, LR, LL)
-   Financial tracking with separate cost and price fields
-   Soft delete functionality for data recovery
-   Cascade relationships for data integrity

### Controllers

Controllers handle HTTP requests and business logic, following RESTful conventions.

#### EntryController (`app/Http/Controllers/EntryController.php`)

Manages dental treatment entries with comprehensive CRUD operations.

**Key Methods:**

-   `index()`: Display table of entries with relationships (customers, items)
-   `create()`: Show entry creation form with customer/item dropdowns
-   `store()`: Validate and save new entries with automatic calculations
-   `edit($id)`: Display edit form pre-populated with entry data
-   `update($id)`: Update existing entries with validation
-   `delete()`: Soft delete entries (preserves data for potential recovery)
-   `search()`: AJAX endpoint for real-time filtering and search (excludes soft deleted)
-   `customerRecords($id)`: Filter entries by specific customer
-   `itemRecords($id)`: Filter entries by specific item
-   `export()`: Generate PDF reports with custom column selection

**Validation Rules:**

-   Discount must be a positive decimal number
-   Date must be a valid date
-   Customer and item must exist in the database

**Special Features:**

-   Advanced search functionality with multiple filter types
-   Real-time AJAX table updates
-   Bulk operations support
-   PDF export with customizable layouts

#### CustomerController (`app/Http/Controllers/CustomerController.php`)

Handles patient/customer management.

**Key Methods:**

-   `index()`: Display customer table
-   `create()`: Show customer creation form
-   `store()`: Validate and save new customers
-   `edit($id)`: Display customer edit form
-   `update($id)`: Update customer information
-   `delete()`: Soft delete customers (preserves historical data)
-   `search()`: AJAX search functionality (excludes soft deleted)

**Validation Rules:**

-   Name required and must be unique
-   Soft deleted records maintain uniqueness constraints

#### ItemController (`app/Http/Controllers/ItemController.php`)

Manages dental procedures and inventory items.

**Key Methods:**

-   `index()`: Display items with pricing information
-   `create()`: Show item creation form
-   `store()`: Validate and save new items
-   `edit($id)`: Display item edit form
-   `update($id)`: Update item details
-   `delete()`: Soft delete items (maintains data integrity)
-   `search()`: AJAX search with price/cost filtering (excludes soft deleted)

**Validation Rules:**

-   Name required and unique
-   Price and cost must be positive decimal numbers
-   Description is optional
-   Soft deleted records maintain uniqueness constraints

### Views

The application uses Blade templating with a hierarchical layout structure.

#### Layout Structure

-   `layouts/app.blade.php`: Base HTML structure
-   `layouts/app-with-header.blade.php`: Adds navigation header
-   `layouts/table.blade.php`: Table view layout with search/filter
-   `layouts/form.blade.php`: Form layout with success notifications

#### Key View Files

-   `pages/home.blade.php`: Dashboard with hero section and quick links
-   `pages/entries.blade.php`: Main entries table
-   `pages/customers.blade.php`: Customer management table
-   `pages/items.blade.php`: Item catalog table
-   `forms/add-entry.blade.php`: New entry form with visual teeth selection
-   `forms/add-customer.blade.php`: New customer form
-   `forms/add-item.blade.php`: New item form
-   `forms/edit-entry.blade.php`: Entry editing form
-   `forms/edit-customer.blade.php`: Customer editing form
-   `forms/edit-item.blade.php`: Item editing form
-   `pdf/entry-pdf.blade.php`: PDF export template

### Components

#### TeethVisual Component (`app/View/Components/TeethVisual.php`)

A custom Blade component providing interactive tooth selection.

**Features:**

-   SVG-based visual teeth chart
-   Click-to-select functionality
-   Integration with list-based selection
-   Quadrant-based organization (UR, UL, LR, LL)
-   Real-time synchronization between visual and list selection

**Template:** `resources/views/components/teeth-visual.blade.php`

-   Interactive SVG with 32 tooth elements
-   Hover effects and selection states
-   Responsive design for different screen sizes

## Features & Usage

### Entry Management

The core functionality of the application revolves around managing dental treatment entries.

#### Creating New Entries

1. **Access**: Click "Add Entry" from the home page or entries table page`
2. **Customer Selection**: Choose existing customer or create new one inline
3. **Item Selection**: Select procedure/item or create new item inline
4. **Teeth Selection**: Use either:
    - **Visual Mode**: Interactive SVG teeth chart with click-to-select
    - **List Mode**: Dropdown with organized quadrants (UR, UL, LR, LL)
5. **Treatment Details**:
    - Date of treatment
    - Discount amount/percentage (optional)
    - Automatic price calculation

#### Editing Entries

-   Click edit icon next to any entry
-   Modify customer, item, teeth, date, or discount
-   Automatic recalculation of totals
-   Option to update the entry using the procedure/item’s latest price (if changed) or keep the original price

#### Deleting Entries

-   **Single**: Click delete icon with confirmation dialog (soft delete - recoverable)
-   **Bulk**: Use "Delete All" for filtered results (soft delete - recoverable)
-   **Data Safety**: All deletions are soft deletes, preserving data integrity

### Patient Management

#### Customer Operations

-   **Add New**: Simple form with name and automatic timestamp
-   **Edit**: Update customer information with validation
-   **View Records**: Click "Records" to see all treatments for a customer
-   **Delete**: Soft delete customer (can be recovered if needed)
-   **Data Safety**: Deleted customers are hidden but preserved for recovery

#### Customer Features

-   Unique name validation
-   Creation date tracking
-   Quick access to treatment history
-   Soft delete protection with recovery options

### Item Management

#### Item Operations

-   **Add New**: Form with name, price, cost, and description
-   **Edit**: Update all item details
-   **Delete**: Soft delete items (preserves historical data)
-   **Pricing**: Separate cost and selling price tracking with decimal precision
-   **Data Safety**: Deleted items are hidden but preserved for recovery

#### Item Features

-   Unique name validation
-   Decimal precision for financial accuracy
-   Creation date tracking
-   Quick access to usage history
-   Soft delete protection maintaining data integrity

### Visual Teeth Selection

The application's standout feature is the interactive teeth selection system.

#### Visual Interface

-   **SVG-based Chart**: Scalable, responsive teeth diagram
-   **Quadrant Organization**:
    -   **UR**: Upper Right (teeth 1-8)
    -   **UL**: Upper Left (teeth 9-16)
    -   **LR**: Lower Right (teeth 17-24)
    -   **LL**: Lower Left (teeth 25-32)
-   **Click Selection**: Click teeth to select/deselect
-   **Visual Feedback**: Selected teeth highlighted in red
-   **Hover Effects**: Interactive feedback on mouse over

#### List Interface

-   **Organized Dropdowns**: Grouped by quadrant
-   **Multiple Selection**: Select multiple teeth simultaneously
-   **Search Functionality**: Filter teeth by typing
-   **Synchronization**: Real-time sync with visual selection

#### Notation System

-   **Format**: `Quadrant-Number` (e.g., UR-1, LL-3)
-   **Storage**: Comma-separated list in database
-   **Display**: Human-readable format in tables and PDFs

### Search & Filtering

#### Advanced Search Features

-   **Real-time Search**: AJAX-powered instant results
-   **Multiple Filters**:
    -   Customer name
    -   Item/procedure
    -   Date ranges
    -   etc.
-   **Column-specific Search**: Filter by specific fields
-   **Date Range Picker**: Intuitive date selection

#### Filter Types

-   **Text Search**: Customer names, item names
-   **Numeric Filters**: Prices, costs, discounts
-   **Date Filters**: Creation dates, treatment dates
-   **Relationship Filters**: Customer-specific or item-specific entries

### PDF Export

#### Export Features

-   **Custom Column Selection**: Choose which data to include
-   **Filtered Results**: Export only filtered/searched data
-   **Professional Formatting**: Clean, printable layout

#### Export Options

-   **Column Selection**:
    -   ID, Date, Customer Name
    -   Item/Procedure, Teeth
    -   Amount, Unit Price, Discount
    -   Total Price, Cost
-   **Data Filtering**: Export respects current search/filter state

## API Routes

The application provides a comprehensive RESTful API structure:

### Main Routes

```php
GET  /                           # Home dashboard
GET  /entries                    # Entries index
GET  /customers                  # Customers index
GET  /items                      # Items index
```

### Search & Filter Routes

```php
GET  /entries/search             # Entry search (AJAX)
GET  /customers/search           # Customer search (AJAX)
GET  /items/search               # Item search (AJAX)
```

### CRUD Operations

#### Create Routes

```php
GET  /entries/create             # New entry form
GET  /customers/create           # New customer form
GET  /items/create               # New item form

POST /entries/store              # Store new entry
POST /customers/store            # Store new customer
POST /items/store                # Store new item
```

#### Edit & Update Routes

```php
GET   /entries/{id}/edit         # Edit entry form
GET   /customers/{id}/edit       # Edit customer form
GET   /items/{id}/edit           # Edit item form

PATCH /entries/{id}/update       # Update entry
PATCH /customers/{id}/update     # Update customer
PATCH /items/{id}/update         # Update item
```

#### Delete Routes

```php
DELETE /entries/delete           # Delete entries
DELETE /customers/delete         # Delete customers
DELETE /items/delete             # Delete items
```

### Specialized Routes

```php
GET  /customers/{id}/records     # Customer treatment history
GET  /items/{id}/records         # Item usage history
POST /entries/export             # PDF export
```

## Development

### Database Schema

#### Migrations

The application includes four main migrations:

1. **Items Table** (`2024_03_03_160656_item.php`)

    - Core inventory/procedure structure with pricing
    - Unique name constraints and soft delete support

2. **Customers Table** (`2024_03_03_160702_customer.php`)

    - Patient information storage
    - Unique name constraints and soft delete support

3. **Entries Table** (`2024_03_03_163108_entries.php`)

    - Treatment records with financial details
    - Foreign key relationships to customers and items
    - Soft delete support

4. **Sessions Table** (`2024_03_31_143438_sessions.php`)
    - User session management
    - Web authentication support

### Seeders & Factories

#### Database Seeders

-   **CustomerItemEntrySeeder**: Creates sample data for testing
-   **Realistic Data**: Uses Faker for authentic-looking records
-   **Relationships**: Properly linked customers, items, and entries

#### Model Factories

-   **CustomerFactory**: Generates test customers
-   **ItemFactory**: Creates dental procedures/items
-   **EntryFactory**: Builds treatment records with proper relationships

### Frontend Assets

#### JavaScript Architecture

-   **Modular Structure**: Separate JS files for each page/component
-   **jQuery-based**: Familiar, reliable DOM manipulation
-   **AJAX Integration**: Real-time search and filtering
-   **Component Communication**: Synchronized teeth selection

#### CSS Organization

-   **Component-based**: Separate styles for components
-   **Bootstrap Foundation**: Responsive grid and utilities
-   **Custom Themes**: Dark/light mode support
-   **Visual Feedback**: Hover states and transitions
