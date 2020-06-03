<?php


namespace App\Services;


use App\Models\RaffleDrawList;

class RaffleDistributorService
{
    /**
     * 经销商已经中奖金额
     */
    public function distributorSpend($distributorID = '')
    {
        $distributorSpend = RaffleDrawList::when($distributorID, function ($query) use ($distributorID) {
            $query->where('distributor_id', $distributorID);
        })->where([
            ['openid', '!=', ''],
            'is_win' => RaffleDrawList::IS_WIN['yes'],
        ])->sum('amount');

        return $distributorSpend;
    }
}