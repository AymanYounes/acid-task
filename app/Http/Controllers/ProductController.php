<?php

namespace App\Http\Controllers;

use App\ApiClasses\Importer\ImportFile;
use App\Models\Product;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;

class ProductController extends BaseController
{

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function importProducts(Request $request): JsonResponse
    {

        $file = $request->file('products');
        if ($file) {
            $importer = new ImportFile();
            $importer->setValidExtensions(['csv']);
            $importer->setMaxFileSize(2097152);
            $importer->setCacheKey('allProducts');
            if ($importer->importFile($file)) {
                return response()->json([
                    'message' => "records successfully uploaded",
                    'file_path' => $importer->getFilePath()
                ]);
            } else {
                throw new Exception('Something went wrong while uploading file', Response::HTTP_BAD_REQUEST);
            }
        } else {
            throw new Exception('No file was uploaded', Response::HTTP_BAD_REQUEST);
        }
    }


    /**
     * @param $part_number
     * @return Product
     */
    public function getProduct($part_number): Product
    {

        if (!Cache::has('product_' . $part_number)) {
            Cache::remember('product_' . $part_number, '86400', function () use ($part_number) {
                return Product::where('part_number', $part_number)->first();
            });
        }

        return Cache::get('product_' . $part_number);

    }


    /**
     * @return Collection
     */
    public function getAllProducts(): Collection
    {
        if (!Cache::has('allProducts')) {
            Cache::remember('allProducts', '86400', function () {
                return Product::all();
            });
        }

        return Cache::get('allProducts');

    }

}
