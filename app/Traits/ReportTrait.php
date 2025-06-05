<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;

trait ReportTrait
{
    private function searchQueryResults(Request $request)
    {
        $queryValue = $request->get('dateQuery') ?? 'all';

        $queryOptions = [
            '1d' => Carbon::now()->subDays(),
            '1w' => Carbon::now()->subWeek(),
            '2w' => Carbon::now()->subWeeks(2),
            '1m' => Carbon::now()->subMonth(),
            '3m' => Carbon::now()->subMonths(3),
            '6m' => Carbon::now()->subMonths(6),
            '1y' => Carbon::now()->subYear(),
            'all' => '0000-00-00'
        ];

        return $queryOptions[$queryValue];
    }
}
