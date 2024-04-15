# Dental Clinic Accounting Web Application

![Version](https://img.shields.io/badge/version-1.0.0-blue)
![Build](https://img.shields.io/badge/build-passing-brightgreen)
![License](https://img.shields.io/badge/license-MIT-green)

## Table of Contents

- [Getting Started](#getting-started)
    - [Prerequisites](#prerequisites)
    - [Setup](#setup)
    - [Install Dependencies](#install-dependencies)
    - [Configure the Application](#configure-the-application)
    - [Access the Application](#access-the-application)
- [Overview](#overview)
    - [Models](#models)
    - [Controllers](#controllers)
- [Using the Application](#using-the-application)

## Getting Started

Before you can use the application, you need to start the project. Here are the general steps you might follow:

### Prerequisites

1. **Install XAMPP**: If you haven't already, download and install XAMPP from the [official website](https://www.apachefriends.org/index.html).

2. **Install 7z or Git**: These are required for some dependencies during the `composer install` process. You can download 7z from the [official website](https://www.7-zip.org/download.html) and Git from its [official website](https://git-scm.com/downloads).

3. **Install Composer**: Composer is a tool for dependency management in PHP. You can download it from the [official website](https://getcomposer.org/download/).

4. **Install Node.js**: Node.js is a JavaScript runtime built on Chrome's V8 JavaScript engine. You can download it from the [official website](https://nodejs.org/en/download/).

### Setup

5. **Clone the Repository**: Clone the project repository to your local machine using Git.

6. **Place the Project in htdocs**: Move the project folder into the `htdocs` directory inside your XAMPP installation directory.

### Install Dependencies

7. **Install PHP Dependencies**: Navigate to the project directory in your terminal and run the following command:

    ```bash
    composer install
    ```

8. **Install JavaScript Dependencies (required for PDF export feature)**: Continue in the project directory and execute the following command:

    ```bash
    npm install
    ```

### Configure the Application

9. **Generate Key**: After installing all the dependencies, you need to generate a new application key. Run the following command in your terminal:
    ```bash
    php artisan key:generate
    ```

10. **Setup the Database**: First, if you don't have a `.env` file in your project root, you can create one by making a copy of the `.env.example` file and renaming it to `.env`. Then, update the `.env` file with your database connection details. The host will usually be `localhost` and the database will be the name of the database you want to create.

11. **Run Migrations**: Execute the following command in your terminal:

    ```bash
    php artisan migrate
    ```

    This command will migrate the database. If the database does not exist, Laravel will prompt you to create it. Confirm the creation and Laravel will generate the necessary tables in your database.

### Access the Application

12. **Start XAMPP**: Open the XAMPP control panel and start the Apache and MySQL services.

13. You should now be able to access the application in your web browser at `http://localhost/your_project_folder/public`.

Please note that these are general steps and the actual steps may vary based on the specific setup of your project. Always refer to any setup instructions provided in the project repository for the most accurate information.

### Step-by-Step Video Guide for Project Startup

For a visual walkthrough of the setup process, please watch our setup guide video:

[Setup Guide Video](https://github.com/Essa-Ramzy/Dental-Accounting/assets/100089248/f823d116-ac87-4207-981a-1012028f9347)

## Overview

This Laravel application is structured around the MVC (Model-View-Controller) pattern. Here's a detailed overview of each component:

### Models

Models represent the data structure of the application and are typically tied to database tables. Each model corresponds to a table in the database. They contain methods for retrieving, storing, and updating data in the database.

#### Item Model

The `Item` model represents an item in the application. 

**Fields:**

- `name`: The name of the item.
- `price`: The price of the item.
- `cost`: The cost of the item.
- `description`: The description of the item.

**Relationships:**

- `hasMany('Entry')`: An item can have many entries.

#### Customer Model

The `Customer` model represents a customer in the application. 

**Fields:**

- `name`: The name of the customer.

**Relationships:**

- `hasMany('Entry')`: A customer can have many entries.

#### Entry Model

The `Entry` model represents an entry in the application.

**Fields:**

- `customer_id`: The ID of the customer associated with the entry.
- `item_id`: The ID of the item associated with the entry.
- `date`: The date of the entry.
- `teeth`: Specifies the teeth of the customer on which the item is being applied.
- `amount`: The number of teeth involved in the entry.
- `unit_price`: The price for one item being applied to one tooth.
- `discount`: The discount for the entry.
- `price`: The total price of the entry.
- `cost`: The total cost of the entry.

**Relationships:**

- `belongsTo('Customer')`: Each entry belongs to a customer.
- `belongsTo('Item')`: Each entry belongs to an item.

### Controllers

Controllers handle the business logic of the application. They respond to HTTP requests, interact with models, and return views.

#### ItemController

The `ItemController` handles the CRUD operations for items. 

**Methods:**

- `search`: This function is used to handle AJAX search requests. It uses the `searchFunc` to get the filtered items and constructs an HTML string for the body and footer of the table. If the request is not AJAX, it redirects to the items page.
- `create`: Shows the form for creating a new item. It returns a view with the form.
- `store`: Stores a newly created item in the database. It validates the request data and creates a new item. After creating the item, it redirects to the items page.
- `delete`: Deletes items based on the search query, filter type, and date range. It uses the `searchFunc` to get the items to delete. After deleting the items, it redirects to the items page.
- `edit`: Shows the form for editing an existing item. It finds the item by id and returns a view with the item.
- `update`: Updates an existing item in the database. It validates the request data and updates the item. After updating the item, it redirects to the items page.

#### CustomerController

The `CustomerController` handles the CRUD operations for customers. 

**Methods:**

- `index`: Displays a list of customers. It retrieves all customers from the database and returns a view with the customers and the view name.
- `searchFunc`: This private function is used to filter customers based on the search query, filter type, and date range. It returns the filtered customers.
- `search`: This function is used to handle AJAX search requests. It uses the `searchFunc` to get the filtered customers and constructs an HTML string for the body and footer of the table. If the request is not AJAX, it redirects to the customers page.
- `create`: Shows the form for creating a new customer.
- `store`: Stores a newly created customer in the database. It validates the request data and creates a new customer. After creating the customer, it redirects to the customers page.
- `delete`: Deletes customers based on the search query, filter type, and date range. It uses the `searchFunc` to get the customers to delete. After deleting the customers, it redirects to the customers page.
- `edit`: Shows the form for editing an existing customer. It finds the customer by id and returns a view with the customer.
- `update`: Updates an existing customer in the database. It validates the request data and updates the customer. After updating the customer, it redirects to the customers page.

#### EntryController

The `EntryController` handles the CRUD operations for entries. 

**Methods:**

- `index`: Displays a list of entries. It retrieves all entries from the database and returns a view with the entries and the view name.
- `searchFunc`: This private function is used to filter entries based on the search query, filter type, and date range. It returns the filtered entries.
- `search`: This function is used to handle AJAX search requests. It uses the `searchFunc` to get the filtered entries and constructs an HTML string for the body and footer of the table. If the request is not AJAX, it redirects to the home page.
- `create`: Shows the form for creating a new entry. It retrieves all customers and items from the database and returns a view with them.
- `store`: Stores a newly created entry in the database. It validates the request data and creates a new entry. After creating the entry, it redirects to the home page.
- `delete`: Deletes entries based on the search query, filter type, and date range. It uses the `searchFunc` to get the entries to delete. After deleting the entries, it redirects to the home page.
- `customerRecords`: Redirects to the home page with the name of a specific customer. It finds the customer by id and returns the customer's name.
- `itemRecords`: Redirects to the home page with the name of a specific item. It finds the item by id and returns the item's name.
- `export`: Exports the filtered entries to a PDF file. It uses the `searchFunc` to get the entries to export and generates a PDF file with the entries. It returns the PDF file for download.

## Using the Application

### Main Table
The main table displays a list of all entries. You can add a new entry by clicking on the 'Add Entry' button. This will redirect you to a form where you can fill out the details of the new entry.

### Add Entry in Main Table
To add a new entry, click on the 'Add Entry' button in the home page. Fill out the form with the customer's name, item, teeth, date, and discount. Click on the 'Save' button to add the entry to the main table.

### Delete Entries from Main Table
To delete an entry, click on the 'Delete' icon next to the entry in the main table. A confirmation dialog will appear. Click on 'Yes, Delete' to delete the entry. To delete all entries displayed in the main table, click on the 'Delete All' icon in the footer of the table. Note that if you have applied a filter, only the filtered entries will be deleted.

### Customer Table
The customer table displays a list of all customers. You can add a new customer by clicking on the 'Add Customer' button. This will redirect you to a form where you can fill out the details of the new customer.

### Add Customer in Customer Table
To add a new customer, click on the 'Add Customer' button in the customer page. Fill out the form with the customer's name and click on the 'Save' button to add the customer to the customer table.

### Edit Customer
To edit a customer, click on the 'Edit' icon next to the customer in the customer table. Update the customer's name in the form and click on the 'Save' button to save the changes.

### Delete Customers
To delete a customer, click on the 'Delete' icon next to the customer in the customer table. A confirmation dialog will appear. Click on 'Yes, Delete' to delete the customer. Note that all entries related to this customer in the main table will also be deleted. To delete all customers displayed in the customer table, click on the 'Delete All' icon in the footer of the table. Note that if you have applied a filter, only the filtered customers will be deleted.

### Item Table
The item table displays a list of all items. You can add a new item by clicking on the 'Add Item' button. This will redirect you to a form where you can fill out the details of the new item.

### Add Item in Item Table
To add a new item, click on the 'Add Item' button in the item table. Fill out the form with the item's name, price, cost, and description. Click on the 'Save' button to add the item to the item table.

### Edit Item
To edit an item, click on the 'Edit' icon next to the item in the item table. Update the item's name, price, cost, and description in the form and click on the 'Save' button to save the changes.

### Delete Items
To delete an item, click on the 'Delete' icon next to the item in the item table. A confirmation dialog will appear. Click on 'Yes, Delete' to delete the item. Note that all entries related to this item in the main table will also be deleted. To delete all items displayed in the item table, click on the 'Delete All' icon in the footer of the table. Note that if you have applied a filter, only the filtered items will be deleted.

### Export to PDF
You can export the entries to a PDF by clicking on the 'Export to PDF' icon in the header of the main table on the home page. A dropdown will appear where you can select the columns you want to export. Note that only the filtered entries will be exported if you have applied a filter.