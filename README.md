# PHP_Laravel12_Store_JSON_Formate_Data_In_Table
# Step 1 : Install Laravel 12 and Create Project using Command
```php
composer create-project laravel/laravel PHP_Laravel12_Store_JSON_Formate_Data_In_Table
```
# Step 2 : Setup database method for .env file
```php
 DB_CONNECTION=mysql
 DB_HOST=127.0.0.1
 DB_PORT=3306
 DB_DATABASE=your database name
 DB_USERNAME=root
 DB_PASSWORD=
```
# Step 3 : Create migration file for database table 
```php
php artisan make:migration create_products_table
```
database/migrations/2024_04_11_141714_create_products_table.php
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```
# Then run the migration command to create the items table.
```php
Php artisan migrate
```
# Step 4: Create Model
```php
Php artisan make:model Product
```
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   

    protected $fillable = [
        'name', 'details'
    ]; 

    protected $casts = [
        'details' => 'json'    
    ];
}
```

# Step 5 : Create controller 
```php
Php artisan make:controller ProductController
```
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
class ProductController extends Controller
{
 public function create()
    {
        $input = [
            'name' => 'Washing mashine',
            'details' => [
                'brand' => 'Bosch', 
                'tags' => ['7kg', '8kg', '10kg']
            ]
        ];

       return Product::create($input);
    }
     public function search()
    {
        $product = Product::whereJsonContains('details->tags', '7kg')->get();
        return $product;
    }
}
```
# Step 6 : Create web route
Routes/web.php file
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
     
Route::get('products/create', [ProductController::class, 'create']);

Route::get('products/search', [ProductController::class, 'search']);

Route::get('/', function () {
    return view('welcome');
});
```
# Step 7 : Now Run Server and paste this url from browser
```php
php artisan serve

http://127.0.0.1:8000/products/create
```
 
 <img width="1530" height="381" alt="image" src="https://github.com/user-attachments/assets/c159f02e-b49c-47de-b556-2f9b1841ecb9" />
<img width="1155" height="297" alt="image" src="https://github.com/user-attachments/assets/cd73cd6a-3550-486d-8679-30d3cfd06103" />


