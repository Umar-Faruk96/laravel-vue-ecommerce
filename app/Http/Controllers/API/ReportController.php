<?php

namespace App\Http\Controllers;

use App\Traits\ReportTrait;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    use ReportTrait;

    public function orders()
    {
        // Logic to retrieve order reports
    }

    public function customers()
    {
        // Logic to retrieve customer reports
    }
}
