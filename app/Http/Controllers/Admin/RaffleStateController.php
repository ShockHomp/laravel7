<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RaffleDistributor;
use App\Models\RaffleJackpot;
use App\Models\RaffleState;
use App\Services\RaffleDistributorService;
use App\Services\RaffleStateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RaffleStateController extends Controller
{
    protected $raffleStateService;
    protected $raffleDistributorService;

    public function __construct(RaffleStateService $raffleStateService, RaffleDistributorService $raffleDistributorService)
    {
        $this->raffleStateService = $raffleStateService;
        $this->raffleDistributorService = $raffleDistributorService;
    }

    public function index()
    {
        $raffleStates = RaffleState::get()->toArray();
        foreach ($raffleStates as &$raffleState) {
            $raffleState['winCount'] = $this->raffleStateService->getWinCount($raffleState['id']);
            $raffleState['winBalanceCount'] = $this->raffleStateService->getSurplusCount($raffleState['id']);
        }
        $rewardSum = RaffleDistributor::sum('reward');
        $spend = $this->raffleDistributorService->distributorSpend();

        return response([
            'rewardSum' => round($rewardSum, 2),
            'spend' => round($spend, 2), // 已发放
            'balance' => round($rewardSum - $spend), // 余额
            'raffleStates' => $raffleStates
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'grade' => 'required',
            'winChance' => 'required',
            'prizeNumber' => 'required',
            'isGrand' => '',
            'isNotWin' => ''
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        DB::transaction(function () use ($request) {
            $raffleStateID = RaffleState::insertGetId([
                'grade' => $request->grade,
                'win_chance' => $request->winChance,
                'prize_number' => $request->prizeNumber,
                'is_grand' => is_null($request->isGrand) ? 0 : $request->isGrand,
                'is_not_win' => is_null($request->isNotWin) ? 0 : $request->isNotWin
            ]);

            $data = [];
            for ($i = 1; $i <= $request->prizeNumber; $i++) {
                $data[] = [
                    'raffle_state_id' => $raffleStateID
                ];
            }

            RaffleJackpot::insert($data);
        });


        return response()->noContent();
    }

    public function changeIsGrand(Request $request)
    {
        $id = $request->id;
        $isGrand = $request->isGrand;

        RaffleState::whereId($id)->update([
            'is_grand' => $isGrand
        ]);

        return response()->noContent();
    }
}
