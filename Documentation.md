#vue #php #laravel #node
# Development Environment
Run these commands to start the Laravel and Vue.js development server

### Launch Laravel Server

```bash
cd laravel-backend
composer install
php artisan serve
```

### Launch Vue.js Server

```bash
cd vue-frontend
npm install
npm run dev
```

---
# Laravel
Some important notes for Laravel that could be useful

Reference(s):
- https://blog.treblle.com/how-to-create-rest-api-using-laravel/

### Prerequisites
Before doing anything with Laravel, a database must be created in mysql, and there should be another user, besides root, that has access to this database.

```bash
sudo mysql
```

```sql
create database equiz;
```

to create a user with sufficient privileges:

```sql
create user 'name'@'localhost' identified by 'password';
grant all on equiz.* to 'name'@'localhost';
```

to check if another user exists besides root:

```sql
select current_user();
```

### Model
use `-m` option to generate database migration

```bash
php artisan make:model <name of table> -m 
```

in app/models/\<name of db>.php file, columns can be specified:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    use HasFactory;
    protected $table = 'students';
    protected $fillable = ['name', 'email'];
}
```

do the same thing in database/migrations/xxxx_create\_\<name of table>_table.php:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
```

after that, create the table into the database with this command:

```bash
php artisan migrate
```

### Database Seeding (Optional)
database seeding is essentially filling table with data, useful for testing

first, make a database seeder.

```bash
php artisan make:seeder SomeSeeder
```

the seeder file will be in database/seeders/:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Students;

class StudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 10; $i++) {
            Students::create([
                'name' => $faker->name,
                'email' => $faker->email
            ]);
        }
    }
}
```

Notice we're using the [Faker](https://github.com/fzaninotto/Faker) PHP library  

We can then run the database seeder:

```bash
php artisna db:seed --class=SomeSeeder
```


### Controllers
This will be responsible for handling logic on the application level

to make a controller:
```bash
php artisan make:controller SomeControler
```

The file will be in app/Http/Controllers/:
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Students;

class StudentsController extends Controller
{
    //indexing
    public function index()
    {
        $students = Students::all();
        return response() -> json($students);
    }

	//Create
	public function store(Request $request) 
	{
		$students = new Students;
		$students -> name = $request -> name
		...
	}
    
}
```


### Routing
this is how the frontend will get data from laravel. laravel will be made as an API for vue to retrieve data from.

in routes/api.php:
```php
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentsController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/students', [StudentsController::class, 'index']);
Route::post('/students', [StudentsController::class, 'store']);
...
```

the data can be accessed from localhost:\<where the php server is ported>/api/students
