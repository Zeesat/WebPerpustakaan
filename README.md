[![LibManage System](https://img.shields.io/badge/Project-LibManage-blue.svg)](#)
# LibManage

We built this **LibManage** repository to provide a streamlined library loan management system for tracking book inventories and managing borrowing workflows. If you are operating a community library, school book repository, or private collection, you can deploy this system to handle your core operations with minimal overhead. 

`app/` directory contains a hierarchically structured MVC framework built entirely in native PHP. The architecture is deliberately kept lightweight, ensuring that developers can easily understand, modify, and extend the business logic without relying on external dependencies or complex framework ecosystems.

**Please notice that this project relies on file-based configuration rather than environment variables**. The primary configuration files are located in the `app/config/` directory. If you are deploying this to a production environment, please ensure your web server restricts access to these internal directories and serves only the `public/` directory.

## Which environments can run LibManage?

Your deployment of LibManage will be successful if all of the conditions below are met:

1. The server runs a standard web server (Apache, Nginx, etc.) capable of serving PHP applications, and the document root is explicitly set to the `public/` directory.

2. The server provides a compatible relational database (such as MySQL or MariaDB) and you have correctly specified the connection parameters inside the `app/config/database.php` file.

**NOTE:** If your deployment environment does not support URL rewriting or you are running the application from a subdirectory, it may require adjustments to the core routing behavior. We designed the routing to be intentionally simple, but we are glad to offer you a flexible foundation that you can customize to fit your specific server constraints.

If you represent a larger institution and you are certain of the necessity of complex third-party integrations, please consider extending the service layer located within `app/services/`.

## How to deploy the system

Deploying LibManage (e.g., `on your local machine`) automatically structures the MVC components. Do not expose the entire repository to the web — only the main `public/` directory is needed.

### Steps to Deploy: ###
1. Clone this repository. Please place it in your local development environment.
2. Configure your server. Set the `public/` directory as the document root. Example: if using XAMPP, you can access it via your configured virtual host or localhost. 
3. Set up the database. Import the initial database schema from `database/migrations/001_initial_schema.sql` into your database system. Make sure the database name matches your configuration in `app/config/database.php`.
4. (Optional) Seed the initial admin. Import `database/seeds/001_seed_admin.sql`. This creates an admin account with the email `admin@library.test` and password `Admin123!`.

We review the architecture to keep it lightweight, and usually it takes only a few minutes to start.
   
#### How to configure the application quicker
> We manage configurations manually and check the variables which you have provided us with before starting the application.
> Thus, if you wish to make the setup process easier and therefore much quicker, please verify the following in your `app/config/app.php` file:
> * the `name` variable, which sets the application title for layouts
> * the `base_url` variable, if your project does not run at the root domain
> * ensure your database credentials in `database.php` exactly match the database you created

## How to run the test suite
If a developer modifies the core services or authentication flow, they can run the native PHP test suite to ensure system integrity. Execute `php tests/run.php` from the root directory.

## Additional references
Please refer to the `app/services/` directory in this repository to read more about how business rules for loan approvals and returns are structured.

## FAQ
#### There are many frameworks available, why does LibManage use native PHP?
If the project relies heavily on complex frameworks, it often introduces unnecessary overhead for simple management tasks. For example, if there is a library looking for a straightforward tool to track book loans and manage users, a native implementation guarantees high performance and zero external dependency maintenance. 

However, if the system requires extensive third-party API integrations or complex microservices, we encourage you to adapt the provided architecture or migrate the business logic to a larger framework, if it is possible.

#### The reset password page is currently a placeholder. Shall I add a database column for the reset token to have it function properly?
No, it's not needed for the core authentication to run right now. However, nothing bad happens if you do it, don't worry. It does not affect the core functionality of logging in and borrowing books. The current implementation deliberately keeps the schema minimal and compatible.
