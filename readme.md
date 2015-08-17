# City Happs Documentation v0.9

City Happs is an API and web application that combines a custom event listing with the listings gathered from other sites (currently Eventful, Eventbrite, and Meetup) to create a searchable, family-friendly listing of 'Happs' around a local city of your choosing (Atlanta currently available).

## Technology Stack

### LAMP

Basic stack to drive Apache as the web server. The DO droplet and development environment are both running Ubuntu.

### Laravel v5.1.*

PHP framework that we are using as an internal API to handle CRUD operations. We are not using any of the template or view functionality baked into Laravel. Because the app is mostly single page, if it can’t find a route, it defers to the front­end where they are defined. More on this later. I highly recommend laracasts.com; lots of high quality videos on everything Laravel does.

### Digital Ocean

Hosting provider.

### Angular ­v1.3.16 CDN

JS MVVM framework that syncs and consumes the PHP API. The official docs are not the best, however there a lots of resources and examples throughout the web. We are leveraging a number of community created Angular directives.

### jQuery ­v2.0.3 CDN 

JS Library­ Used for public/js/dom.js and sporadically throughout public/js/app.js. Used for category drop down fade toggles and light calendar manipulation.

### SASS

CSS preprocessor that extends vanilla CSS. We are using the .scssvariety so make sure to keep that in mind when reading the Sass docs. There are lots of nested rules used in CityHapps. CSS is valid Sass, but not always the other way around.
￼￼￼￼￼￼￼
### Gulp

JS build tool

### Vagrant

Another option and may be easier if you are familiar with running a VM and serving the site from there.

### Composer

Composer is the package manager for PHP. It is used to keep your projects dependencies located in composer.json up to date. I recommend creating a global alias so you can access it system from the command line with composer.

## Installation

The doumentation here is being reworked. A general outline is:

1. Pull the repo.
2. Make sure the .env file is correctly configured.
3. Run composer install to get vendor libraries.
4. Run gulp watch to compile the SASS and JS

### Adding Events to the Happs table

The process is set up as a series of CLI 'php artisan' commands. These commands are NOT brief and take a considerable amount of time to run (which is fine for now; it throttles the data coming from the source so we don't overwhelm it). The order in which they should be run to work from the ground up is:

1. php artisan api:pull-venue
2. php artisan api:load-venue
3. php artisan api:pull
4. php artisan api:load
5. php artisan api:clear-stale