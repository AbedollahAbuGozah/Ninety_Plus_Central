<?php

namespace App\Traits;

use App\Models\Comment;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder;

trait Invoiceable
{

    public function invoices()
    {
        return $this->morphMany(Invoice::class, 'invoiceable');
    }
}
