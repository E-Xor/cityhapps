# City Happs Documentation v0.9

City Happs is an API and web application that combines a custom event listing with the listings gathered from other sites (currently Eventful, Eventbrite, and Meetup) to create a searchable, family-friendly listing of 'Happs' around a local city of your choosing (Atlanta currently available).

## Technology Stack

### LAMP

Basic stack to drive Apache as the web server. The DO droplet and development environment are both running Ubuntu.

### Laravel v5.1.*

PHP framework that we are using as an internal API to handle CRUD operations. We are not using any of the template or view functionality baked into Laravel. Because the app is mostly single page, if it can’t find a route, it defers to the front­end where they are defined. More on this later.

### Digital Ocean

Hosting provider.

### Angular ­v1.3.16 CDN

JS MVVM framework that syncs and consumes the PHP API. The official docs are not the best, however there a lots of resources and examples throughout the web. We are leveraging a number of community created Angular directives.

### jQuery ­v2.0.3 CDN

JS Library­ Used for public/js/dom.js and sporadically throughout public/js/app.js. Used for category drop down fade toggles and light calendar manipulation.

### Sass

CSS preprocessor that extends vanilla CSS. There are lots of nested rules used in CityHapps.

### Gulp

JS build tool that ships with Laravel Elixir.  To watch assets, run `npm run elixir`, or to build for production, run `npm run build`

### Vagrant

Another option and may be easier if you are familiar with running a VM and serving the site from there.

### Composer

Composer is the package manager for PHP. It is used to keep your projects dependencies located in composer.json up to date. I recommend creating a global alias so you can access it system from the command line with composer.

## Installation

The documentation here should be more in-depth, but the overall goal is to pull the repo, setup your environment, install the necessary components, and load the database before starting up the VM for City Happs.

### Setup Environment

After cloning the repository, you'll need an `.env` file in the root directory for your local instance.

1. Clone the City Happs repo to your local project directory
2. Copy the `.env.example` file to a new file, `.env`
3. Add all the details to the `.env` file for your local setup (app key, database information and email)

### Install Necessary Components

You'll need Composer, Sass, and Node/NPM installed for the local instance to run.

1. Use `curl` to download and install Composer from the command line by running `curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer`, where the `--install-dir` parameter is set to wherever you'd like to install the executable
2. From your local project directory, run `composer install` to setup the project dependencies
3. You'll be asked to create a GitHub OAuth token during this process.  Feel free to create one, or re-run with `composer install --prefer-source`
4. Ensure you have Node 4.2.x or higher installed
5. Then run `npm install` to bundle up Elixir for gulp usage
6. Compile the Sass for development (watched and updated) with `npm run elixir`, or compile for production (single compilation) with `npm run build`

### Create Database

Setup a local database to store all the City Happs data before seeding and pulling from the API. In the case of using MySQL in a LAMP configuration:

1. Make sure MySQL is running
2. Use a GUI tool or the command line to create a new, empty database, ie. `cityhapps`
3. Make sure your database settings are correct in the `.env` file in your project directory
4. If using MySQL instance in MAMP on a Mac, add the following line to the `database.php` file under the `connections/mysql` section: `'unix_socket' => '/Applications/MAMP/tmp/mysql/mysql.sock',`.  Alternatively, if you don't want to use the faster socket, you can instead set the host to `127.0.0.1`.

### Setup and Seed the Database

Before adding data to the database, we'll need to create the table structure and fire off the seeders for categories and age levels. Run these commands from your local project directory:

1. Create the database structure with `php artisan migrate`
2. Seed the database with `php artisan db:seed`

### Adding Events to the `happs` table

The process is set up as a series of CLI `php artisan` commands. These commands *take a considerable amount of time to run*, plan on at least an hour (which is fine for now; it throttles the data coming from the source so we don't overwhelm it). Run them one at a time in the following order from your local project directory:

1. `php artisan api:pull-venue`
2. `php artisan api:load-venue`
3. `php artisan api:pull`
4. `php artisan api:load`
5. `php artisan api:clear-stale`

### Start the Server

Last but not least, start the server and make sure the site is running locally.

1. Run `php artisan serve` from your local project directory
2. Point your web browser to `http://localhost:8000`

### Debug

Logging. Helps a lot.


```
use Log; // storage/log/laravel.log

Log::Debug('Some message');

Log::Debug('Some message ' . var_export($some_var, true)); // Dumps $some_var in log file
```

Many request don't go in controllers directly. Look in Http/routes.php, especially for api routes. These requests go to Http/Contollers/APIController.php which makes some filtering and uses Handler/*.php for data.
