<?php

namespace App\Http\Controllers;

use App\Models\Carousel;
use App\Models\ProductImage;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SubCategory;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class ShopController extends Controller
{
    public function index()
    {
        if (auth()->user()) {
            if (auth()->user()->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            }
            if (auth()->user()->hasRole('shipper')) {
                return view('shipper.index',['admins' => User::all()]);
            }
        }
        $carousels = Carousel::latest()->take(3)->get();
        $newProducts = Product::inRandomOrder()->get();
       //......//
       //get imges products
        foreach($newProducts as $pro){
            $image = ProductImage::where('product_id',$pro->id)->first();
            $img = '';
            if($image)
            $img = $image->original;
            $pro->productImage = $img;
        }

        //......//

        return view('shop.index')->with([
            'carousels' => $carousels,
            'newProducts' => $newProducts,
        ]);
    }
    public function show($id)
    {
        if (auth()->user()) {
            if (auth()->user()->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            }
            if (auth()->user()->hasRole('shipper')) {
                return view('shipper.index',['admins' => User::all()]);
            }
        }   
        $product = Product::where('id', $id)->with('productImage','category','attributes')->first();
        if($product->status == 0 || !$product)
            return redirect('404');
        $product->image = $product->productImage->first();

        $questions = $product->getQuestions()->with('user')->paginate(6);
        $mightAlsoLike = Product::where('id', '!=', $product->id)->inRandomOrder()->with('productImage')->take(6)->get();

        return view('shop.show')->with([
            'product' => $product,
            'questions' => $questions,
            'mightAlsoLike' => $mightAlsoLike
        ]);
    }

    public function catalog(Request $request)
    {

        if (auth()->user()) {
            if (auth()->user()->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            }
            if (auth()->user()->hasRole('shipper')) {
                return view('shipper.index',['admins' => User::all()]);
            }
        }
        //get random sub categories

        $products = QueryBuilder::for(Product::class)
            ->allowedFilters([
                'title',
                'subCategory',
                AllowedFilter::scope('min_price'),
                AllowedFilter::scope('max_price'),
            ])->where('status','<>','0')
            ->with('productImage')
            ->paginate(20);

        return view('shop.catalog')->with([
            'productCategories' => [],
            'products' => $products
        ]);
    }
}
