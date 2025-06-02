<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product_Types; // Đổi thành ProductType
use Illuminate\Http\Request;

class ProductTypesController extends Controller
{
    /**
     * Hiển thị danh sách loại sản phẩm.
     */
    public function index(Request $request)
    {

        $query = Product_types::query();

        if ($request->has('keyword')) {
            $keyword = $request->input('keyword');
            $query->where('typeName', 'like', '%' . $keyword . '%');
        }

         return response()->json($query->paginate(5));

    }

    /**
     * Thêm loại sản phẩm mới.
     */
    public function store(Request $request)
    {
        $request->validate([
            'typeName' => 'required|unique:product_types,typeName',
        ]);

        $loai = Product_Types::create([
            'typeName' => $request->typeName,
        ]);

        return response()->json($loai, 201);
    }

    /**
     * Hiển thị chi tiết loại sản phẩm.
     */
    public function show($id)
    {
        $loai = Product_Types::find($id);
        if (!$loai) {
            return response()->json(['message' => 'Không tìm thấy loại sản phẩm'], 404);
        }
        return $loai;
    }

    /**
     * Cập nhật loại sản phẩm.
     */
    public function update(Request $request, $id)
    {
        $loai = Product_Types::find($id);
        if (!$loai) {
            return response()->json(['message' => 'Không tìm thấy loại sản phẩm'], 404);
        }

        $request->validate([
            'typeName' => 'required|string|max:255',
        ]);

        $loai->typeName = $request->typeName;
        $loai->save();

        return response()->json($loai);
    }

    /**
     * Xóa loại sản phẩm.
     */
    public function destroy($id)
    {
        $loai = Product_Types::find($id);
        if (!$loai) {
            return response()->json(['message' => 'Không tìm thấy loại sản phẩm'], 404);
        }

        if ($loai->products()->exists()) {
            return response()->json(['message' => 'Không thể xóa vì loại sản phẩm đang có sản phẩm liên quan'], 400);
        }

        $loai->delete();
        return response()->json(['message' => 'Xóa thành công']);
    }
}