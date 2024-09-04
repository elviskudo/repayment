<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'duration', 'start_date'];

    public function scheduledRepayments()
    {
        return $this->hasMany(ScheduledRepayment::class);
    }

    public function receivedRepayments()
    {
        return $this->hasMany(ReceivedRepayment::class);
    }
}
