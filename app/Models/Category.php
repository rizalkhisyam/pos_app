<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['category_name', 'user_id'];

    public function users(): BelongsTo{
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products(): HasMany{
        return $this->hasMany(Product::class);
    }

}
