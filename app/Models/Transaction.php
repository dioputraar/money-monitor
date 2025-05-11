<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'transaction';

    protected $guarded = ['id']; 

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
