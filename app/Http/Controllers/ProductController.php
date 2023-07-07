<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="RIA laravel API"
 * )
 */
class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/",
     *     summary="Listing products",
     *     tags={"Product"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      property="products",
     *                      type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="title", type="string", minLength=2, maxLength=50),
     *                          @OA\Property(property="description", type="string", minLength=10, maxLength=200),
     *                          @OA\Property(property="price", type="float"),
     *                          @OA\Property(property="created_at", type="time"),
     *                          @OA\Property(property="updated_at", type="time"),
     *                      ),
     *                 ),
     *                 example={"id": 1, "title": "Самса", "description": "Очень вкусная, с курицей", "price": "219.99", "created_at": "2023-07-06T08:27:30.000000Z", "updated_at": "2023-07-06T09:45:07.000000Z"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function getProducts(): JsonResponse
    {
        $products = Product::all();
        return response()->json($products);
    }

    /**
     * @OA\Get(
     *     path="/api/title={title}",
     *     summary="Listing a product by title",
     *     tags={"Product"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      property="products",
     *                      type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="title", type="string", minLength=2, maxLength=50),
     *                          @OA\Property(property="description", type="string", minLength=10, maxLength=200),
     *                          @OA\Property(property="price", type="float"),
     *                          @OA\Property(property="created_at", type="time"),
     *                          @OA\Property(property="updated_at", type="time"),
     *                      ),
     *                 ),
     *                 example={"id": 1, "title": "Самса", "description": "Очень вкусная, с курицей", "price": "219.99", "created_at": "2023-07-06T08:27:30.000000Z", "updated_at": "2023-07-06T09:45:07.000000Z"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function getProductTitle(string $title = null): JsonResponse
    {
        try {
            $product = Product::where('title', $title)->first();
            return response()->json($product);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/{id}",
     *     summary="Listing a product by id",
     *     tags={"Product"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      property="products",
     *                      type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="title", type="string", minLength=2, maxLength=50),
     *                          @OA\Property(property="description", type="string", minLength=10, maxLength=200),
     *                          @OA\Property(property="price", type="float"),
     *                          @OA\Property(property="created_at", type="time"),
     *                          @OA\Property(property="updated_at", type="time"),
     *                      ),
     *                 ),
     *                 example={"id": 1, "title": "Самса", "description": "Очень вкусная, с курицей", "price": "219.99", "created_at": "2023-07-06T08:27:30.000000Z", "updated_at": "2023-07-06T09:45:07.000000Z"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function getProductId(int $id = 1): JsonResponse
    {
        try {
            $product = Product::find($id);
            return response()->json($product);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/create",
     *     summary="Сreating a product according to the required parameters 'title', 'description', 'price'",
     *     tags={"Product"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      property="products",
     *                      type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="title", type="string", minLength=2, maxLength=50),
     *                          @OA\Property(property="description", type="string", minLength=10, maxLength=200),
     *                          @OA\Property(property="price", type="float"),
     *                          @OA\Property(property="created_at", type="time"),
     *                          @OA\Property(property="updated_at", type="time"),
     *                      ),
     *                 ),
     *                 example={"id": 1, "title": "Самса", "description": "Очень вкусная, с курицей", "price": "219.99", "created_at": "2023-07-06T08:27:30.000000Z", "updated_at": "2023-07-06T09:45:07.000000Z"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="OK",
     *     )
     * )
     */
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

    /**
     * @OA\Patch(
     *     path="/api/{id}/edit",
     *     summary="Product update by parameters 'title', 'description', 'price'",
     *     tags={"Product"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                @OA\Property(
     *                      property="products",
     *                      type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="title", type="string", minLength=2, maxLength=50),
     *                          @OA\Property(property="description", type="string", minLength=10, maxLength=200),
     *                          @OA\Property(property="price", type="float"),
     *                          @OA\Property(property="created_at", type="time"),
     *                          @OA\Property(property="updated_at", type="time"),
     *                      ),
     *                 ),
     *                 example={"id": 1, "title": "Самса", "description": "Очень вкусная, с курицей", "price": "219.99", "created_at": "2023-07-06T08:27:30.000000Z", "updated_at": "2023-07-06T09:45:07.000000Z"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/{id}/delete",
     *     summary="Delete product by id",
     *     tags={"Product"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                @OA\Property(
     *                      property="products",
     *                      type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="id", type="int"),
     *                      ),
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="OK",
     *     )
     * )
     */
    public function deleteProduct(int $id)
    {
        Product::destroy($id);
    }
}
