<?php


namespace App\Services;


use App\Models\RaffleDrawList;
use App\Models\RaffleJackpot;
use App\Models\RaffleState;
use Illuminate\Support\Facades\DB;

class RaffleStateService
{
    /**
     * 奖金档次中奖个数
     */
    public function getWinCount($stateID)
    {
        $winCount = RaffleDrawList::where([
            'raffle_state_id' => $stateID,
            ['openid', '!=', ''],
            'is_win' => RaffleDrawList::IS_WIN['yes'],
        ])->count();

        return $winCount;
    }

    /**
     * 奖金档次剩余个数
     */
    public function getSurplusCount($stateID)
    {
        $surplusCount = RaffleJackpot::where([
            'raffle_state_id' => $stateID,
        ])->count();

        return $surplusCount;
    }

    /**
     * 按是否在大奖区域抽奖
     */
    public function draw($isGrand)
    {
        if ($isGrand == 0) {
            do {
                $draw = DB::select('SELECT t1.*
                    FROM raffle_jackpots AS t1 
                    JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM raffle_jackpots)-(SELECT MIN(id) FROM raffle_jackpots))+(SELECT MIN(id) FROM raffle_jackpots)) AS id) AS t2
                    WHERE  t1.id >= t2.id
                    ORDER BY t1.id LIMIT 1'
                );
                foreach ($draw as $k=>$v) {
                    $raffleState = RaffleState::whereId($v->raffle_state_id)->first();
                    if ($raffleState->is_grand == 1) {
                        //echo intval($v->id) . '---' . $isGrand;
                    } else {
                        return $draw;
                    }
                }
            } while ($raffleState->is_grand = 1);
        } else {
            $draw = DB::select('SELECT t1.*
                FROM raffle_jackpots AS t1 
                JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM raffle_jackpots)-(SELECT MIN(id) FROM raffle_jackpots))+(SELECT MIN(id) FROM raffle_jackpots)) AS id) AS t2
                WHERE  t1.id >= t2.id
                ORDER BY t1.id LIMIT 1'
            );
            return $draw;
        }
    }

}