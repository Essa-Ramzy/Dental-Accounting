# Dental Clinic Accounting Web Application

## How to Start the Project

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

This project is a web application built using PHP and JavaScript. It uses the Laravel framework for the backend and npm for managing JavaScript packages. The application is structured following the MVC (Model-View-Controller) pattern.

## Models

There are three main models in this project: `Customer`, `Item`, and `Entries`.

### Customer Model

The `Customer` model represents a customer in the application. It has the following attributes:

- `name`: The name of the customer.
- `phone`: The phone number of the customer.
- `address`: The address of the customer.

The `Customer` model has a one-to-many relationship with the `Entries` model.

### Item Model

The `Item` model represents an item in the application. It has the following attributes:

- `name`: The name of the item.
- `price`: The price of the item.
- `cost`: The cost of the item.
- `description`: The description of the item.

The `Item` model has a one-to-many relationship with the `Entries` model.

### Entries Model

The `Entries` model represents an entry in the application. It has the following attributes:

- `customer_id`: The ID of the customer.
- `item_id`: The ID of the item.
- `date`: The date of the entry.
- `teeth`: The teeth attribute of the entry.
- `discount`: The discount of the entry.
- `price`: The price of the entry.
- `cost`: The cost of the entry.
- `description`: The description of the entry.

The `Entries` model has a many-to-one relationship with the `Item` model and the `Customer` model.

## Controllers

There are three main controllers in this project: `CustomerController`, `ItemController`, and `MainTableController`.

### CustomerController

The `CustomerController` handles the CRUD operations for the `Customer` model. It has the following methods:

- `index()`: Fetches all customers and returns a view with the customers.
- `create()`: Returns a view for creating a new customer.
- `store()`: Validates the request data and creates a new customer.
- `delete()`: Deletes a customer by its ID.
- `edit()`: Fetches a customer by its ID and returns a view for editing the customer.
- `update()`: Validates the request data and updates a customer by its ID.

### ItemController

The `ItemController` handles the CRUD operations for the `Item` model. It has the following methods:

- `index()`: Fetches all items and returns a view with the items.
- `create()`: Returns a view for creating a new item.
- `store()`: Validates the request data and creates a new item.
- `delete()`: Deletes an item by its ID.
- `edit()`: Fetches an item by its ID and returns a view for editing the item.
- `update()`: Validates the request data and updates an item by its ID.

### MainTableController

The `MainTableController` handles operations related to the main table in your application. It has the following methods:

- `index()`: Fetches all entries and returns a view with the entries.
- `create()`: Returns a view for creating a new entry.
- `store()`: Validates the request data and creates a new entry.
- `delete()`: Deletes an entry by its ID.
- `edit()`: Fetches an entry by its ID and returns a view for editing the entry.
- `update()`: Validates the request data and updates an entry by its ID.
- `pdf()`: This method generates a PDF of entries. It first gets the columns from the request, excluding certain keys. It then gets the entries by calling the `searchFunc()` method with all request data. The number of columns is calculated, and a PDF is generated using the `Pdf::view()` method with the 'pdf.entry_pdf' view and the entries, columns, and count as data. The PDF is formatted to 'A4' size and landscape orientation, and then downloaded with the name 'entries.pdf'.

### Controller

The `Controller` is the base controller of the application. It uses the `AuthorizesRequests` and `ValidatesRequests` traits from the Laravel framework.

## Views

The views of the application are not included in the provided code. However, based on the `ItemController`, we can infer that there are views for listing items (`entries.items`), adding an item (`forms.add_item`), and editing an item (`forms.edit_item`).

## Dependencies

The project uses Composer for managing PHP dependencies and npm for managing JavaScript dependencies. The specific dependencies are not included in the provided code.

## Conclusion

This is a basic overview of the project based on the provided code. For more detailed information, refer to the codebase and any additional documentation provided with the project.

## How to Use the Site

While the specific details of how to use the site are not provided, based on the structure of the application, a general guide can be inferred:

1. **Home Page**: When you first visit the site, you will likely land on the home page. This page may display a list of items, entries, or customers depending on the design of the application.

2. **Customer Page**: There might be a page dedicated to customers where you can view a list of all customers. There should be options to add a new customer, edit an existing customer's details, or delete a customer.

3. **Item Page**: Similarly, there should be a page for items where you can view a list of all items. Options to add a new item, edit an existing item's details, or delete an item should be available.

4. **Entries Page**: This page would display a list of all entries. You should be able to add a new entry, edit an existing entry's details, or delete an entry.

5. **Adding a New Entry, Item, or Customer**: When adding a new entry, item, or customer, you would need to fill out a form with the necessary details. After submitting the form, the new entry, item, or customer should be added to the database and visible in the respective list.

6. **Editing an Item, or Customer**: To edit an existing item, or customer, you would need to locate it in the list and select the edit option. This should bring up a form with the current details of the item, or customer. After making the necessary changes and submitting the form, the details should be updated in the database and the changes should be visible in the list.

7. **Deleting an Entry, Item, or Customer**: To delete an existing entry, item, or customer, you would need to locate it in the list and select the delete option. After confirming the deletion, the entry, item, or customer should be removed from the database and no longer visible in the list.

8. **Filtering and Searching**: You can filter and search the entries, items, or customers by using the filter dropdown and search input in the table view. The table will update dynamically based on your input.

Please note that the actual usage of the site may vary based on the specific implementation of the application. For more accurate instructions, refer to any user guides or manuals provided with the application, or consult the application's developer.
