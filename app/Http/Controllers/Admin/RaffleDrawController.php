<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RaffleDrawList;
use App\Services\Expands\WxService;
use Illuminate\Http\Request;

class RaffleDrawController extends Controller
{
    protected $wxService;

    public function __construct(WxService $wxService)
    {
        $this->wxService = $wxService;
    }

    public function raffleDrawList(Request $request)
    {
        $type = $request->get('type');
        $page = $request->get('page');
        $take = $request->get('size') ? $request->get('size') : 20;

        $win_time = $request->get('win_time');//中奖时间
        $nickname = $request->get('nickname');//微信名称
        $phone = $request->get('phone');//手机号码
        $raffle_state_id = $request->get('raffle_state_id');
        $distributor_id = $request->get('distributor_id');
        $is_release = $request->get('is_release');
        $query = RaffleDrawList::select(['raffle_draw_lists.id', 'openid', 'win_time', 'nickname', 'avatar',
            'phone', 'amount', 'lat', 'lng', 'is_release', 'raffle_distributors.name as distributor_name'])
            ->leftJoin('raffle_distributors',
                'raffle_draw_lists.distributor_id', '=', 'raffle_distributors.id')
            ->where('is_win', RaffleDrawList::IS_WIN['yes'])
            ->when($win_time, function ($query) use ($win_time) {
                $query->whereDate('win_time', $win_time);
            })->when($nickname, function ($query) use ($nickname) {
                $query->where('nickname', 'like', '%' . $nickname . '%');
            })->when($phone, function ($query) use ($phone) {
                $query->wherePhone($phone);
            })->when($raffle_state_id, function ($query) use ($raffle_state_id) {
                $query->whereRaffleStateId($raffle_state_id);
            })->whereHas('distributor', function ($query) use ($distributor_id) {
                $query->select('id', 'name');
                if (isset($distributor_id)) {
                    $query->where('id', $distributor_id);
                }
            });
        if (isset($is_release)) {
            $query->where('is_release', $is_release);
        };
        $result = $query
            ->orderByDesc('win_time')
            ->paginate($take);

        foreach ($result as $k => $v) {
            $v->win_count = $this->win_count($v->openid);
        }
        return response($result);
    }

    private function win_count($openId)
    {
        $count = RaffleDrawList::where('openid', $openId)
            ->where('is_win', RaffleDrawList::IS_WIN['yes'])
            ->count();
        return $count;
    }

}
