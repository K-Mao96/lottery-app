<?php

namespace App\Http\Controllers;

use App\Models\TargetList;
use Illuminate\Http\Request;

class TargetListController extends Controller
{
    // リスト一覧
    public function index()
    {
        $lists = TargetList::all();
        return view('target_lists.index', compact('lists'));
    }

    // 作成画面
    public function create()
    {
        return view('target_lists.create');
    }

    // 保存処理
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $list = TargetList::create($validated);
        // リスト作成後、抽選画面に遷移
        return redirect()->route('lottery.index', ['target_list_id' => $list->id]);
    }

    // 編集画面
    public function edit($id)
    {
        $list = TargetList::with('targets')->findOrFail($id);
        return view('target_lists.edit', compact('list'));
    }

    // 更新処理
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $list = TargetList::with('targets')->findOrFail($id);
        $list->update($validated);

        // --- メンバー名の変更・削除 ---
        if ($request->has('members')) {
            foreach ($request->input('members') as $targetId => $memberData) {
                $target = $list->targets->where('id', $targetId)->first();
                if (!$target) continue;
                if (isset($memberData['delete'])) {
                    $target->delete();
                } else {
                    $target->update(['name' => $memberData['name']]);
                }
            }
        }
        // --- 新規メンバー追加 ---
        if ($request->filled('new_members')) {
            $names = preg_split('/\r\n|\r|\n/', $request->input('new_members'));
            $names = array_filter(array_map('trim', $names));
            foreach ($names as $name) {
                $list->targets()->create(['name' => $name]);
            }
        }
        return redirect()->route('target_lists.edit', $list->id)->with('success', 'リストを更新しました');
    }

    // リスト削除
    public function destroy($id)
    {
        $list = TargetList::findOrFail($id);
        $list->targets()->delete(); // 関連するメンバーも削除
        $list->delete();
        return redirect()->route('target-lists.index')->with('success', 'リストを削除しました');
    }
}
