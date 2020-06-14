# Work Algo for this Project

## Steps as Follows

1. **Install / Setup Lumen**

	1. composer create-project --prefer-dist laravel/lumen QeK

	1. Rename the .env.example file to .env exist in root directory

	1. **Generate App Key**

		1. You can put any 32 characters long string and set it to APP_KEY into .env file **or**

		1. I generated using **via terminal** ```php php -r "echo bin2hex(random_bytes(32));"```

	1. **Git Repository Initialization**

		1. Create a new Git Repo. at server (github.com)

		1. Initialize a empty Git Repo. at local machine by terminal ```php git init```

		1. **Be updated the latest code**, will be always push the latest app code to the server repo.(https://github.com/irfan8108/QeK)

1. **Activate the Auth and Eloquent features** - By default, the Facades and Eloquent features has disabled in Lumen to work faster.

	1. Uncomments in the **app.php** as follows to activate that features, we have to use it.

		```php
		$app->withFacades();
		$app->withEloquent();
		$app->routeMiddleware([
		    'auth' => App\Http\Middleware\Authenticate::class,
		]);
		$app->register(App\Providers\AuthServiceProvider::class);
		```

1. **Database Migration**
	
	1. Create database and schemas for the project which is required. 

	1. Created schema table for the user ```php php artisan make:migration create_users_table --create=users```

	1. After update to schemas for the users, according to requirement. command to migrate with all of the schema / tables ```php php artisan migrate```

	1. You can read [API Documentation](https://deen-e-muhammad.com/QeK/developer/documentation) to know more about **QeK Migrations**

1. **Creating a Controller** - Unfortunately, lumen laravel doesn’t provide php artisan feature to make the controller file automatically

	1. Created the Controller(s) Manually

1. **Further Process**

	1. Routes, Middlewares modification and all, you can view this complete Git Repo. for the the updated code and process.