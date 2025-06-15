<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Order;
use App\Traits\ReportTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class ReportController extends Controller
{
    use ReportTrait;

    public function orders(Request $request): JsonResponse
    {
        $searchDate = $this->searchQueryResults($request);

        $records = $this->getRecords(new Order(), $searchDate);

        $labels = $this->getLabels($searchDate);

        return response()->json(['labels' => $labels, 'dataset' => $this->getDataset($records, $labels)]);
    }

    public function customers(Request $request): JsonResponse
    {
        $searchDate = $this->searchQueryResults($request);

        $records = $this->getRecords(new Customer(), $searchDate);

        $labels = $this->getLabels($searchDate);

        return response()->json(['labels' => $labels, 'dataset' => $this->getDataset($records, $labels)]);
    }

    private function getRecords(Model $model, Carbon $searchDate): Collection
    {
        return $model->select([DB::raw('CAST(created_at as DATE) AS day'), DB::raw('COUNT(created_at) AS order_placed')])
            ->where('created_at', '>', $searchDate)
            ->groupBy('day')
            ->get();
    }

    private function getLabels(Carbon $searchDate): array
    {
        $labels = [];
        $now = Carbon::now();
        $searchDate = Carbon::parse($searchDate);

        while ($searchDate < $now) {
            $labels[] = $searchDate->format('Y-m-d');
            $searchDate->addDay();
        }

        return $labels;
    }

    private function getDataset(Collection $orders, array $labels): Collection
    {
        $data = collect($labels)->map(function ($label) use ($orders) {
            $orderCount = $orders->firstWhere('day', $label);
            return $orderCount ? $orderCount->order_placed : 0;
        });

        return $data;
    }
}
