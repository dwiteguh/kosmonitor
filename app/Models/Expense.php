<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model {
    protected $fillable = [
        'category', 'description', 'amount', 'expense_date', 'receipt_path'
    ];
}