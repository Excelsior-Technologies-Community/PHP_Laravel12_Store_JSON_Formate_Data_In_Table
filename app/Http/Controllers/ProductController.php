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
