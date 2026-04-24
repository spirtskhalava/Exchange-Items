<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// app/Models/ProductVerification.php
class ProductVerification extends Model
{
    protected $fillable = ['product_id', 'user_id', 'status'];

    public function product() { 
        return $this->belongsTo(Product::class); 
    }
    public function user() { 
        return $this->belongsTo(User::class); 
    }
}