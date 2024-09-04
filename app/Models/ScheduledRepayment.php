<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledRepayment extends Model
{
    use HasFactory;

    protected $fillable = ['loan_id', 'amount', 'due_date', 'status'];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function receivedRepayments()
    {
        return $this->hasMany(ReceivedRepayment::class);
    }
}
