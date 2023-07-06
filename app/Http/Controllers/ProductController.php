<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function getProducts(): JsonResponse
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function getProductTitle(string $title = null): JsonResponse
    {
        try {
            $product = Product::where('title', $title)->first();
            return response()->json($product);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function getProductId(int $id = 1): JsonResponse
    {
        try {
            $product = Product::find($id);
            return response()->json($product);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function createProduct(ProductRequest $request): ?JsonResponse
    {
        if ((mb_strlen($request->title) >= 2) && (mb_strlen($request->description) >= 10)) {
            $product = new Product();
            $product->title = $request->title;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->save();

            return response()->json($product);
        }
        return null;
    }

    public function updateProduct(Request $request, int $id = 1): JsonResponse
    {
        try {
            $product = Product::find($id);

            $productName = $product->title;
            $productDescription = $product->description;
            $productPrice = $product->price;

            if ($request->title != null) {
                $productName = $request->title;
            }

            if ($request->description != null) {
                $productDescription = $request->description;
            }

            if ($request->price != null) {
                $productPrice = $request->price;
            }

            $productArray = array(
                $productName, $productDescription, $productPrice
            );

            $product->title = $productArray[0];
            $product->description = $productArray[1];
            $product->price = $productArray[2];
            $product->save();

            return response()->json($product);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
