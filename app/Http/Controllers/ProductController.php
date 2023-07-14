<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use Exception;
use http\Env\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

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
     *     path="/api",
     *     summary="Listing products",
     *     tags={"Product"},
     *     @OA\Parameter(
     *      name="title",
     *      in="query",
     *      required=false,
     *    ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema (
     *          type="array",
     *               @OA\Items(
     *                 @OA\Property(property="id", type="number", example="1"),
     *                 @OA\Property(property="title", type="string", minLength=2, maxLength=50, example="Самса"),
     *                 @OA\Property(property="description", type="string", minLength=10, maxLength=200, example="Очень вкусная, с курицей"),
     *                 @OA\Property(property="price", type="float", example=169.99),
     *                 @OA\Property(property="created_at", type="time", example="2023-07-06T08:27:30.000000Z"),
     *                 @OA\Property(property="updated_at", type="time", example="2023-07-06T09:45:07.000000Z"),
     *            ),
     *          )
     *         )
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *     )
     * )
     */
    public function getProducts(Request $request): JsonResponse
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: X-Requested-With");

        if ($request->title != null) {
            header('Access-Control-Allow-Origin: *');
            $products = Product::where('title', 'like', '%' . $request->title . '%')->get();
        } else {
            $products = Product::latest()->get();
        }
        return response()->json($products);
    }

    /**
     * @OA\Get(
     *     path="/api/id/{id}",
     *     summary="Listing a product by id",
     *     tags={"Product"},
     *     @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=false,
     *     @OA\Schema (
     *          type="int",
     *     )
     *    ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema (
     *          type="array",
     *               @OA\Items(
     *                 @OA\Property(property="id", type="number", example="1"),
     *                 @OA\Property(property="title", type="string", minLength=2, maxLength=50, example="Самса"),
     *                 @OA\Property(property="description", type="string", minLength=10, maxLength=200, example="Очень вкусная, с курицей"),
     *                 @OA\Property(property="price", type="float", example=169.99),
     *                 @OA\Property(property="created_at", type="time", example="2023 - 07 - 06T08:27:30.000000Z"),
     *                 @OA\Property(property="updated_at", type="time", example="2023 - 07 - 06T09:45:07.000000Z"),
     *            ),
     *          )
     *         )
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Product not found",
     *     )
     * )
     */
    public function getProductId($id): JsonResponse|bool|int
    {
//        header('Access-Control-Allow-Origin: *');
//        header('Access-Control-Allow-Methods: GET, POST');
//        header("Access-Control-Allow-Headers: X-Requested-With");

        try {
            $product = Product::find($id);
            if ($product == null) {
                return response()->json([
                    'message' => 'Product not found.'
                ], 404);
            } else {
                return response()->json($product);
            }
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
     *                      ),
     *                 ),
     *                 example={"title": "Самса", "description": "Очень вкусная, с курицей", "price": 169.99}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="",
     *         @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema (
     *          type="array",
     *               @OA\Items(
     *                 @OA\Property(property="id", type="number", example="1"),
     *                 @OA\Property(property="title", type="string", minLength=2, maxLength=50, example="Самса"),
     *                 @OA\Property(property="description", type="string", minLength=10, maxLength=200, example="Очень вкусная, с курицей"),
     *                 @OA\Property(property="price", type="float", example="169.99"),
     *                 @OA\Property(property="created_at", type="time", example="2023 - 07 - 06T08:27:30.000000Z"),
     *                 @OA\Property(property="updated_at", type="time", example="2023 - 07 - 06T09:45:07.000000Z"),
     *            ),
     *          )
     *         )
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *     )
     * )
     */
    public function createProduct(ProductRequest $request): JsonResponse
    {
        $product = new Product();
        $product->title = $request->title;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->save();

        return response()->json($product);
    }

    /**
     * @OA\Patch(
     *     path="/api/edit/{id}",
     *     summary="Product update by parameters 'title', 'description', 'price'",
     *     tags={"Product"},
     *     @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=false,
     *     @OA\Schema (
     *          type="int",
     *     )
     *    ),
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
     *                      ),
     *                 ),
     *                 example={"title": "Самса", "description": "Очень вкусная, с курицей", "price": 169.99}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema (
     *          type="array",
     *               @OA\Items(
     *                 @OA\Property(property="id", type="number", example="1"),
     *                 @OA\Property(property="title", type="string", minLength=2, maxLength=50, example="Самса"),
     *                 @OA\Property(property="description", type="string", minLength=10, maxLength=200, example="Очень вкусная, с курицей"),
     *                 @OA\Property(property="price", type="float", example="169.99"),
     *                 @OA\Property(property="created_at", type="time", example="2023 - 07 - 06T08:27:30.000000Z"),
     *                 @OA\Property(property="updated_at", type="time", example="2023 - 07 - 06T09:45:07.000000Z"),
     *            ),
     *          )
     *         )
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *     )
     * )
     */
    public function updateProduct(ProductUpdateRequest $request, int $id = 1): JsonResponse
    {
        try {
            $product = Product::find($id);

            if ($product == null) {
                return response()->json([
                    'message' => 'Product not found.'
                ], 404);
            } else {
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

                $product->title = $productName;
                $product->description = $productDescription;
                $product->price = $productPrice;
                $product->save();

                return response()->json($product);
            }
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/delete/{id}",
     *     summary="Delete product by id",
     *     tags={"Product"},
     *     @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=false,
     *     @OA\Schema (
     *          type="int",
     *     )
     *    ),
     *     @OA\Response(
     *         response=204,
     *         description="",
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Not Found",
     *     )
     * )
     */
    public function deleteProduct(int $id): JsonResponse
    {
        $product = Product::find($id);
        if ($product == null) {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        } else {
            $product->delete();
            return response()->json('success!');
        }
    }
}
