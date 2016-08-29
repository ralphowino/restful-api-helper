#API STARTER PACKAGE
[![Build Status](https://travis-ci.org/ralphowino/restful-api-helper.svg?branch=master)](https://travis-ci.org/ralphowino/restful-api-helper)


This is a laravel package that helps you start of building a laravel API. It contains useful command generators to generate various files for your API and is completely configurable. The generators include:
* `starter:init`
* `starter:model <name> --fillable[=FILLABLE] --migration --relationships[=RELATIONSHIPS] --repository  --schema[=SCHEMA] --soft-deletes --table[=TABLE] --transformer`
* `starter:migration <name> --schema[=SCHEMA] --soft-deletes --model[=MODEL]`
* `starter:controller <name> --automate --except[=EXCEPT] --only[=ONLY] --plain --repository[=REPOSITORY] --resource --transformer[=TRANSFORMER]` 
* `starter:repository <name> --model`
* `starter:transformer <name> --fields[=FIELDS] --includes[=INCLUDES] --model[=MODEL]`
 

##Installation

###Step 1: Install the composer packages

Add
```json
{
   "minimum-stability" : "dev",
   "prefer-stable" : true
}
```
to your composer.json file.

Run in terminal
 
    composer require ralphowino/restful-api-helper 1.0.x-dev.

###Step 2: Add the Service Provider

Add the service provider in `config/app.php`:  

    Ralphowino\ApiStarter\ApiStarterServiceProvider::class

###Step 3: Publish the packages configuration files

Publish the package's assets by running 

    php artisan vendor:publish --provider="Ralphowino\ApiStarter\ApiStarterServiceProvider"

###Step 4: Initialize the package

Initialize the project by running 

    php artisan starter:init

Select directories to save the generated files in.

*NB: Just press enter for every question to retain the default.*

###Step 5: Generate a new jwt token

Generate a new jwt token for the application by running 

    php artisan jwt:generate

AND YOU ARE READY TO GO!

For more documentation on the package checkout https://ralphowino.github.io/restful-api-helper/