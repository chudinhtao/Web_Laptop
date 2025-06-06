<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Laptop;
use App\Models\Accessory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Chỉ lấy các sản phẩm có isActive = 1
        $products = Product::with(['type', 'branch', 'laptop', 'accessory'])
            ->where('isActive', 1)
            ->get();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'productName' => 'required|string|max:255',
            'id_type' => 'required|exists:product_types,id',
            'id_branch' => 'required|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'quality' => 'required|integer|min:0',
            'img' => 'nullable|string|max:255',
            'laptop' => 'nullable|array',
            'laptop.screenSpecs' => 'nullable|string|max:255',
            'laptop.CPU' => 'nullable|string|max:255',
            'laptop.RAM' => 'nullable|string|max:255',
            'laptop.SSD' => 'nullable|string|max:255',
            'laptop.GPU' => 'nullable|string|max:255',
            'laptop.des' => 'nullable|string',
            'accessory' => 'nullable|array',
            'accessory.des' => 'nullable|string'
        ]);

        $product = Product::create([
            'productName' => $data['productName'],
            'id_type' => $data['id_type'],
            'id_branch' => $data['id_branch'],
            'price' => $data['price'],
            'quality' => $data['quality'],
            'img' => $data['img'],
            'isActive' => 1 // Rõ ràng đặt isActive = 1 khi tạo mới
        ]);

        if ($data['id_type'] == 1 && $data['laptop']) {
            Laptop::create(array_merge(['productID' => $product->id], $data['laptop']));
        } elseif ($data['id_type'] == 2 && $data['accessory']) {
            Accessory::create(array_merge(['productID' => $product->id], $data['accessory']));
        }

        return response()->json($product->load(['type', 'branch', 'laptop', 'accessory']), 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->validate([
            'productName' => 'required|string|max:255',
            'id_type' => 'required|exists:product_types,id',
            'id_branch' => 'required|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'quality' => 'required|integer|min:0',
            'img' => 'nullable|string|max:255',
            'laptop' => 'nullable|array',
            'laptop.screenSpecs' => 'nullable|string|max:255',
            'laptop.CPU' => 'nullable|string|max:255',
            'laptop.RAM' => 'nullable|string|max:255',
            'laptop.SSD' => 'nullable|string|max:255',
            'laptop.GPU' => 'nullable|string|max:255',
            'laptop.des' => 'nullable|string',
            'accessory' => 'nullable|array',
            'accessory.des' => 'nullable|string',
            'isActive' => 'sometimes|boolean' // Cho phép cập nhật isActive nếu được gửi
        ]);

        $product->update([
            'productName' => $data['productName'],
            'id_type' => $data['id_type'],
            'id_branch' => $data['id_branch'],
            'price' => $data['price'],
            'quality' => $data['quality'],
            'img' => $data['img'],
            'isActive' => $data['isActive'] ?? $product->isActive // Giữ nguyên nếu không gửi
        ]);

        if ($data['id_type'] == 1 && $data['laptop']) {
            Laptop::updateOrCreate(
                ['productID' => $product->id],
                $data['laptop']
            );
            Accessory::where('productID', $product->id)->delete();
        } elseif ($data['id_type'] == 2 && $data['accessory']) {
            Accessory::updateOrCreate(
                ['productID' => $product->id],
                $data['accessory']
            );
            Laptop::where('productID', $product->id)->delete();
        }

        return response()->json($product->load(['type', 'branch', 'laptop', 'accessory']));
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        // Cập nhật isActive thành false thay vì xóa
        $product->update(['isActive' => 0]);
        return response()->json(['message' => 'Sản phẩm đã được ẩn thành công']);
    }
}