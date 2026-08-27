<?php

namespace App\Jobs;

use App\Models\Investment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CloseExpiredInvestmentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        try {
            $investments = Investment::with(['tradingPair', 'user'])
                ->where('status', 'active')
                ->whereNotNull('start_date')
                ->whereNotNull('end_date')
                ->where('end_date', '<=', now())
                ->get();

            if ($investments->isEmpty()) {
                Log::info('No expired investments found.');
                return;
            }

            foreach ($investments as $investment) {
                DB::transaction(function () use ($investment) {

                    if (!$investment->tradingPair || !$investment->user) {
                        Log::warning("Investment {$investment->id} missing relations. Skipped.");
                        return;
                    }

                    $startDate = $investment->start_date;
                    $endDate   = $investment->end_date;

                    // Derive duration dynamically
                    $duration = $startDate->diffInDays($endDate);

                    if ($duration <= 0) {
                        Log::warning("Investment {$investment->id} has invalid duration ({$duration}).");
                        return;
                    }

                    $minReturn = $investment->tradingPair->min_return_percentage / 100;
                    $maxReturn = $investment->tradingPair->max_return_percentage / 100;

                    $returnRate = mt_rand(
                        (int) ($minReturn * 10000),
                        (int) ($maxReturn * 10000)
                    ) / 10000;

                    $dailyProfit = $investment->amount * $returnRate;
                    $profit      = $dailyProfit * $duration;

                    // Pay the investor's direct upline a commission on the profit.
                    // The commission is deducted from the profit the investor sees
                    // and from their credited return, so a $100 profit at 10%
                    // shows as $90 for the investor.
                    //
                    // A broken referral chain (e.g. the upline account was
                    // deleted) must not block the investment from closing, so
                    // failures here are logged and treated as no commission.
                    try {
                        $commission = app(\App\Services\ReferralService::class)
                            ->handleTradeProfitCommission($investment->user, $profit);
                    } catch (\Throwable $e) {
                        Log::warning("Investment {$investment->id}: trade profit commission failed, skipping commission.", [
                            'trader_id' => $investment->user->id,
                            'ref_by'    => $investment->user->ref_by,
                            'error'     => $e->getMessage(),
                        ]);
                        $commission = 0.0;
                    }

                    $profit      -= $commission;
                    $totalReturn = $investment->amount + $profit;

                    $investment->user->increment('account_bal', $totalReturn);
                    $investment->user->increment('roi', $profit);

                    $investment->update([
                        'status'   => 'completed',
                        'profit'   => $profit,
                        'end_date' => $endDate,
                    ]);

                    Log::info(
                        "Investment {$investment->id} closed. " .
                        "Duration: {$duration} days, " .
                        "Daily rate: " . ($returnRate * 100) . "%, " .
                        "Profit: {$profit}, " .
                        "Total return: {$totalReturn}"
                    );
                });
            }

            Log::info("Processed {$investments->count()} expired investments.");

        } catch (\Throwable $e) {
            Log::error('CloseExpiredInvestmentsJob failed: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
        }
    }
}
