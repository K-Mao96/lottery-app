<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TargetList;
use App\Models\Target;

class LotteryController extends Controller
{
    // 抽選画面表示
    public function index(Request $request)
    {
        $targetLists = TargetList::all();
        $selectedListId = $request->input('target_list_id');
        $targets = collect();
        $allSelected = false;
        if ($selectedListId) {
            $targets = Target::where('target_list_id', $selectedListId)->get();
            if ($targets->count() > 0 && $targets->every(fn($t) => $t->is_selected)) {
                $allSelected = true;
            }
        }
        return view('lottery.index', [
            'targetLists' => $targetLists,
            'selectedListId' => $selectedListId,
            'targets' => $targets,
            'allSelected' => $allSelected,
        ]);
    }

    // 抽選処理
    public function draw(Request $request)
    {
        $request->validate([
            'target_list_id' => 'required|exists:target_lists,id',
        ]);
        $exclude = $request->input('exclude', []);
        // is_selectedがtrueの人も除外
        $targets = Target::where('target_list_id', $request->target_list_id)
            ->whereNotIn('id', $exclude)
            ->where('is_selected', false)
            ->get();
        if ($targets->isEmpty()) {
            // 除外者以外が全員当選済みか判定
            $notExcluded = Target::where('target_list_id', $request->target_list_id)
                ->whereNotIn('id', $exclude)
                ->get();
            if ($notExcluded->count() > 0 && $notExcluded->every(fn($t) => $t->is_selected)) {
                // 除外者以外は当選済み
                return redirect()->back()->with('all_selected_except_excluded', true);
            }
            return redirect()->back()->withErrors(['対象者がいません']);
        }
        $winner = $targets->random();
        // 当選者のis_selectedをtrueに更新
        $winner->is_selected = true;
        $winner->selected_at = now();
        $winner->save();
        return redirect()->route('lottery.index', ['target_list_id' => $request->target_list_id])
            ->with('winner', $winner);
    }

    // 全員リセット処理
    public function reset(Request $request)
    {
        $request->validate([
            'target_list_id' => 'required|exists:target_lists,id',
        ]);
        Target::where('target_list_id', $request->target_list_id)
            ->update(['is_selected' => false, 'selected_at' => null]);
        return redirect()->route('lottery.index', ['target_list_id' => $request->target_list_id]);
    }
}
