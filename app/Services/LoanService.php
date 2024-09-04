<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\ScheduledRepayment;
use App\Models\ReceivedRepayment;
use Carbon\Carbon;

class LoanService
{
    public function createLoan($amount, $duration, $startDate)
    {
        $loan = Loan::create([
            'amount' => $amount,
            'duration' => $duration,
            'start_date' => $startDate,
        ]);

        $monthlyAmount = $amount / $duration;
        $dueDate = Carbon::parse($startDate);

        for ($i = 1; $i <= $duration; $i++) {
            ScheduledRepayment::create([
                'loan_id' => $loan->id,
                'amount' => $monthlyAmount,
                'due_date' => $dueDate->addMonth(),
            ]);
        }

        return $loan;
    }

    public function repayLoan($loanId, $scheduledRepaymentId, $amount)
    {
        $loan = Loan::findOrFail($loanId);
        $scheduledRepayment = ScheduledRepayment::findOrFail($scheduledRepaymentId);

        ReceivedRepayment::create([
            'loan_id' => $loan->id,
            'scheduled_repayment_id' => $scheduledRepayment->id,
            'amount' => $amount,
        ]);

        $totalReceived = $scheduledRepayment->receivedRepayments->sum('amount');
        if ($totalReceived >= $scheduledRepayment->amount) {
            $scheduledRepayment->update(['status' => 'fully_paid']);
        }
    }

    public function getOutstandingRepayments($loanId)
    {
        $loan = Loan::findOrFail($loanId);

        return $loan->scheduledRepayments->filter(function ($repayment) {
            $totalReceived = $repayment->receivedRepayments->sum('amount');
            return $totalReceived < $repayment->amount;
        });
    }
}
