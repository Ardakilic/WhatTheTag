```
 _       ____          __ ________       ______           
| |     / / /_  ____ _/ //_  __/ /_  ___/_  __/___ _____ _
| | /| / / __ \/ __ `/ __// / / __ \/ _ \/ / / __ `/ __ `/
| |/ |/ / / / / /_/ / /_ / / / / / /  __/ / / /_/ / /_/ / 
|__/|__/_/ /_/\__,_/\__//_/ /_/ /_/\___/_/  \__,_/\__, /  
                                                 /____/   
```

Hey all,

Here's my latest project that came out using [Laravel 5](https://www.laravel.com). This is called **WhatTheTag**.

##Why?

There are some reasons behind this project.

* Firstly, I needed this. I didn't like Picasa's web interface, Lost tags of ~600 photos thanks to Picasa, and I wanted to make a simple website that I could search my photos (actually my "*internet meme folder*") efficiently.
* I wanted to play with Laravel 5.x and to make a sample application that uses various features of Laravel 5.x.
* I also wanted to play with some asset management tools such as [Gulp](http://gulpjs.com/).

After deciding to make this app, it took maybe a week or so in my spare times to pull this application together.

##Features

* This application's main feature is to upload, tag, list and search photos, so you can access to your photos easier.
* There is multi account support, also there are two roles of users called **user** and **admin**.
* All assets are handled using `gulp`. You just need to add or edit the assets in `resources` folder. Afterwards, all of the assets are compiled, merged, minified and copied into their own folders in `public` directory. I specifically didn't use Laravel Elixir, because I think using this way it's more flexible.
* The app shows **Random Photos** in the main page
* You can list **Recent Photos** from the navigation menu called Recent Photos.
* You can make a **search**. This feature searches in Photo's title, Photo's uploader's name and in the tags that are attached to the photo. You may assume this is sort of a global search.
* There are also pages such as "**user's uploaded photos**", "**all photos tagged with xx**".
* You can **preview** the photos in a Bootstrap modal, and **download directly**.
* There is also a **photo details page**. In this page there are **Disqus comments**, and **social buttons**.
* In **administration panel**, while uploading / editing a photo, **you may also fetch the EXIF data of a photo directly and add them into the tags**. So let's say you've already tagged a photo before. If it's tagged normally, the system gets the tags and adds them into the tags list of the photo.
* You can also preview the photo before uploading.

##Requirements

* [Laravel 5.1's server requirements](http://laravel.com/docs/5.1/installation#installation)
* [Composer](https://getcomposer.org) to install 3rd party dependencies
* [npm](https://www.npmjs.com/) to install frontend dependencies
* [gulp](http://gulpjs.com/) to compile assets
* ~~[Ruby](https://www.ruby-lang.org/) Required for [gulp-ruby-sass](https://www.npmjs.com/package/gulp-ruby-sass),~~
* ~~[Sass >= 3.4](http://sass-lang.com/install) Required for [gulp-ruby-sass](https://www.npmjs.com/package/gulp-ruby-sass). You can check it by running `sass --version` from your terminal.~~

##Showcase

**Main page**
![](https://i.imgur.com/3wFQriO.png)

**Listing pages, also the hover effect**
![](https://i.imgur.com/cm3QweK.png)

**Clicking to "zoom" on a list page**
![](https://i.imgur.com/nj7BBVL.png)

**Photo detail page**
![](https://i.imgur.com/i8Z0ut5.png)

**Admin panel, user management**
![](https://i.imgur.com/2rpT5J3.png)

**Admin panel, photo management**
![](https://i.imgur.com/PVRW54c.png)

**Admin panel, adding / Editing a photo**
![](https://i.imgur.com/jH6CfoP.png)


You can check all the images at [this Imgur album](https://imgur.com/a/pK047).

##Installation

* First, clone the repository into your server

```shell
git clone https://github.com/Ardakilic/WhatTheTag.git
```

* Secondly, install dependencies

```shell
composer install
npm install
```

Alternatively, you can also update dependencies with

```shell
composer update
npm update
```

*  (optional) Thirdly, compile and install assets (Repository holds pre-compiled assets inside)

```shell
npm install -g sey
sey build
```

* Now, (if not created already,) create an `.env` file and fill your database credentials. You can copy it from `.env.example` file as a template.

* Now, install the database schema from migration, and seed the users table for the administrator user:

```
php artisan migrate
php artisan db:seed
```

* Now, edit the app-specific configuration files found in `config/whatthetag.php`:

	* `site_name` defines the site's name (will be shown in the title etc.).
	* `twitter_name` will fill the "shared via" section on Twitter share button.
	* `comments_enabled` will enable or disable the comments section in the site. The value is boolean, so set it `false` to disable comments.
	* `disqus_identifier` is your unique identifier in your Disqus panel. You can create a new identifier for your website by [following this link](https://disqus.com/admin/create/)

* Make sure the `/public/uploads/` folder is writable.

* Now, simply run the application. You can create a new virtualhost either from Apache, or Nginx etc.
	
	**Note:** PHP's own web server (e.g. if you're running it with `php artisan serve`) will give 404 erros in administration panel, because Datatables's AJAX links are quite long, and it gives 404 errors on very long links unless you edit this setting.
	
* Now you can login into your application.

	Default username is `admin@whatthetag.com`
	The default password is `whatthetag`
	
	*Don't forget to change these after logging in!*

* Now, simply navigate through your app and enjoy! :smile:

##Digging the Code?
Well, you should do this!

Why you may ask. Because one of the reasons of this app is to make a sample application using Laravel 5.

This application covers:

* Simple CRUD (Create, Read, Update, Delete) operations in Laravel 5.
* Eloquent Relationships. You can see live examples of BelongsTo, HasMany, BelongsToMany relationships in this app.
* All the contents are accessed using slugs. Managing slugs are handled with a 3rd party package.
* Listing content dynamically using Datatables in Administration Panel with a 3rd party package.
* Example usage of Masterpages for views.
* Downloading popular assets (jQuery, Bootstrap, FontAwesome etc.) from npm repositories, compile and merge them with application's own assets.
* Handling file uploads and processing them.
* Reading EXIF data from an uploaded image using PHP.
* Has an authentication system, and role-based access middlewares.

##TODOs

* Dynamic searching (Search as you type)
* Caching
* Google Vision API integration
* ~~Better file uploads, such as [Dropify](http://jeremyfagis.github.io/dropify/)~~

##Changelog

####0.4.0

* All dependencies are updated
* Rollback to gulp
* (WIP) Algolia implementation

####0.3.1

* Assets now have relative path so that the script runs smoothly both with http and https protocols
* Croppa configuration has been updated for upscales for the cases when the image is too small
* Admin panel photo search is fixed.

####0.3.0

* Got Gulp Switched with [sey](https://github.com/eserozvataf/sey), made by [Eser Özvataf](http://eser.ozvataf.com/). This way, assets building will be easier.
* All dependencies' versions updated.
* Minor code optimisations.
* All configuration values are now fetched from environment vars. Check `.env.example` file and find all variables prefixed with `WTT_`.
* A new trait added for random methods, so it will run out of the box also with Postgres etc.

####0.2.2

* [Dropify](http://jeremyfagis.github.io/dropify/) integrated to upload forms. **You can now drag & drop the images!**
* Custom 404 page added for the situations when debug is closed.
* Tabs are switched with four spaces in (hopefully every part of the) project.

####0.2.1

* Got Sass switched with Less, because there is almost no need for Sass, and it brings additional installations of Ruby and Sass to the server. This way it's lighter. Thanks to @eserozvataf

####0.2.0

* Application updated to Laravel 5.1
* Assets and Requirements updated

####0.1.0

* First Release

##Thanks

* [Koding](https://koding.com/R/arda), all of the project is written on the cloud using Koding's service on their VMs and my machine directly from my browser. You must definitely try the service, also it's free!
* [Burak Can](https://twitter.com/neoberg) and [Eser Özvataf](http://eser.ozvataf.com/) for their ideas especially while handling assets.