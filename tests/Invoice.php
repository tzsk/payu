<?php

namespace Tzsk\Payu\Tests;

use Illuminate\Database\Eloquent\Model;
use Tzsk\Payu\Models\HasTransactions;

class Invoice extends Model
{
    use HasTransactions;

    protected $fillable = ['id'];

    public static function fake()
    {
        return new self(['id' => 1]);
    }
}
