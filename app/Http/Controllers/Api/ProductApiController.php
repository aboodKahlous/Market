<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class ProductApiController extends Controller
{

    public function all()
    {
        $products = Product::latest()->get();
        return DataTables::of($products)
            ->addColumn('onSale', function ($row) {
                $val = $row->onSale ? 'onSale' : 'Toggle sale';
                $class = $row->onSale ? 'btn-danger' : 'btn-outline-danger';
                $item = '<a href="/api/product/onsale/invert/' . $row->id . '" class="btn btn-sm ' . $class . '">' . $val . '</a>
        ';
                return $item;
            })
            ->addColumn('live', function ($row) {
                $val = $row->live ? 'live now' : 'Toggle Live';
                $class = $row->live ? 'btn-success' : 'btn-outline-success';
                $item = '<a href="/api/product/live/invert/' . $row->id . '" class="btn btn-sm ' . $class . '">' . $val . '</a>';
                return $item;
            })
            ->addColumn('actions', function ($row) {
                $btns = '<a target="_blank" class="btn btn-info float-left btn-sm btn-block" href="/product/' . $row->id . '/image" ><i class="fas fa-eye"></i> Images</a>
                <a class="btn btn-primary btn-sm float-left btn-block " href="/product/' . $row->id . '/edit"><i class="fas fa-pen-square"></i> Edit</a>';
                return $btns;
            })
            ->rawColumns(['actions', 'live', 'onSale'])
            ->make(true);
    }

    public function onSaleInvert(Product $id)
    {
        //here id become the product
        $id->update([
            'onSale' => !$id->onSale
        ]);

        return redirect()->route('product.index');
    }

    public function liveInvert(Product $id)
    {
        //here id become the product
        $id->update([
            'live' => !$id->live
        ]);

        return redirect()->route('product.index');
    }

    use ResponseTrait;

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getById($id)
    {
        $product = Product::find($id);
        if ($product)
            return $this->returnWithSuccess($product, 'product retrieved successfully', 200);
        else
            return $this->returnWithFailed(null, 'No product with this id', 200);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getImagesById($id)
    {
        $images = Product::find($id)->getImages;
        if ($images)
            return $this->returnWithSuccess($images, 'Product images retrieved successfully', 200);
        else
            return $this->returnWithFailed(null, 'No images for this product', 200);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getFirstImageById($id)
    {
        $image = Product::find($id)->first_image;
        if ($image)
            return $this->returnWithSuccess($image, 'Product image retrieved successfully', 200);
        else
            return $this->returnWithFailed(null, 'No images for this product', 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request) {

        $request->validate(['title'=>'required']);

        $title = $request->get('title');
        if ($title) {
            $products = Product::where('title', 'like',"%{$title}%")->get();
            return $this->returnWithSuccess($products, 'Successful Get Products');
        }
    }
}
