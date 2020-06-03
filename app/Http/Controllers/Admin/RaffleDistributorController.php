<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RaffleDistributor;
use App\Services\RaffleDistributorService;
use Illuminate\Http\Request;

class RaffleDistributorController extends Controller
{
    protected $raffleDistributorService;

    public function __construct(RaffleDistributorService $raffleDistributorService)
    {
        $this->raffleDistributorService = $raffleDistributorService;
    }

    public function index()
    {
        $distributors = RaffleDistributor::get(['id', 'name', 'reward']);
        $rewardSum = RaffleDistributor::sum('reward');

        foreach ($distributors as &$distributor) {
            $distributor->reward = round($distributor->reward, 2);
            $distributor->rate = round($distributor->reward / $rewardSum, 2);
            $distributor->balance = round($distributor->reward - $this->raffleDistributorService->distributorSpend($distributor->id), 2);
        }
        return response($distributors);
    }
}
