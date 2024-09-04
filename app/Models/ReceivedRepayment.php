<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivedRepayment extends Model
{
    use HasFactory;

    protected $fillable = ['loan_id', 'scheduled_repayment_id', 'amount'];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function scheduledRepayment()
    {
        return $this->belongsTo(ScheduledRepayment::class);
    }
}
