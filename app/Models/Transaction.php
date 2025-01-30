<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'borrow_date',
        'return_date',
        'user_id',
        'books_id',
        'status',
    ];

    public function user():BelongsTo{
        return $this->belongsTo(User::class, 'user_id');
    }

    public function books():BelongsTo{
        return $this->belongsTo(Book::class, 'books_id');
    }
}
