<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RaffleUrlCodeLog;
use App\Models\RaffleDrawList;
use App\Services\Expands\WxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RaffleUrlCodeController extends Controller
{
    protected $wxService;

    public function __construct(WxService $wxService)
    {
        $this->wxService = $wxService;
    }

    public function index()
    {
        $nowTime = date('Y-m-d H:i:s', time());
        $raffleUrlCodeLogs = RaffleUrlCodeLog::with('user:id,name')->latest('id')->paginate(10);
        foreach ($raffleUrlCodeLogs as $k => $v_log) {
            $v_log->time_frame = date("Y-m-d", strtotime($v_log->start_time)) . '~~' . date("Y-m-d", strtotime($v_log->end_time));;

            unset($v_log->updated_at);
            if ($v_log->status !== 0 && ($nowTime < $v_log->start_time || $nowTime > $v_log->end_time)) {
                $v_log->status = 2;
            }
            unset($v_log->start_time);
            unset($v_log->end_time);
        }

        return response($raffleUrlCodeLogs);
    }

    public function store(Request $request)
    {
        $result = Validator::make($request->all(), [
            'count' => 'required|integer|min:1'
        ]);

        if ($result->fails()) {
            return response(['message' => $result->errors()->first()], 422);
        }
        DB::transaction(function () use ($request) {
            // 批量生成二维码记录
            $qrcodeLogId = RaffleUrlCodeLog::insertGetId([
                'count' => $request->get('count'),
                'user_id' => $request->user()->id,
                'created_at' => date("Y-m-d H:i:s", time()),
            ]);

            $data = [];
            // 创建门店，生成二维码
            for ($i = 1; $i <= $request->get('count'); $i++) {
                $data[] = [
                    'url_code_log_id' => $qrcodeLogId
                ];
            }

            RaffleDrawList::insert($data);
        });

        return response()->noContent();
    }
}
