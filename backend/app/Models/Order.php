<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
      protected $fillable = [
        'id',
        'userID',
        'totalAmount',
        'fullName',
          'phone',
        'address',
        'orderstatus', // Thêm dòng này để cho phép cập nhật trạng thái
    ];
    public function details() {
    return $this->hasMany(Order_detail::class, 'orderID');
}
}
