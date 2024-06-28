<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory, Filterable, Sortable;

    public function invoiceable()
    {
        return $this->morphTo();

    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    protected $guarded = ['id'];
}
