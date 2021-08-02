# Laravel 8 Soccer Management App
 CRUD REST END POINTS , using Laravel 8.0 with  laravel-permission | Spatie

-----
## Table of Contents

* [Features](#item1)
* [Quick Start](#item2)

-----
<a name="item1"></a>
## Features:
* Authentication
  * Registration
  * Login
  * Manage Permissions
  * Manage soccer Team
  * Manage Soccer Players
* Rest End Points
  * Soccer Team Listing
  * Soccer Team Detailed Listing
  * Soccer Team Player Listing
  * Soccer Team player Details

-----
<a name="item2"></a>
## Quick Start:

Clone this repository and install the dependencies.

    $ git clone https://github.com/sinujose007/soccer-update.git & cd soccer
	
	Update .env file to change database name or create a database with name 'soccer'

Run the command below to initialize. 

    $ php artisan migrate
	$ php artisan db:seed --class=PermissionTableSeeder
	$ php artisan db:seed --class=CreateAdminUserSeeder
	$ php artisan db:seed --class=CreateTeamSeeder
	$ php artisan db:seed --class=CreatePlayerSeeder

Finally, serve the application.

    $ php artisan serve

	Open [ http://127.0.0.1:8000 ] or [ http://localhost:8000 ]  from your browser. 
	
	It will redirect to soccer team listing page,
	
* Below end points  are available for anonymous users , [You can visit each page from the menu]
	
	* http://127.0.0.1:8000/teams
	* http://127.0.0.1:8000/teams/1
	* http://127.0.0.1:8000/players
	* http://127.0.0.1:8000/players/1
	
A controller  APIController available in this project  which will send internal API calls to this endpoints and demonstrated the result using UI.
APi and UI demos are available , You can check each one using available Menus.

For more details regarding API , check below document.

https://documenter.getpostman.com/view/16703545/TzsfkjpK

You can use below username and password for login as admin

	sinujose007@gmail.com
	12345678

After Login you can test create/update/delete activities.	

For permission management laravel/spatie package is using.

Admin is able to manage  user/role/permission /team /player etc.

-----
