<?php

namespace App\Http\Controllers;

use App\Models\TargetList;
use App\Models\Target;
use Illuminate\Http\Request;

class TargetController extends Controller
{
    // 対象者追加画面
    public function create(TargetList $targetList)
    {
        return view('targets.create', compact('targetList'));
    }

    // 対象者保存処理
    public function store(Request $request, TargetList $targetList)
    {
        $validated = $request->validate([
            'names' => 'required|string',
        ], [
            'names.required' => '対象者名を入力してください。',
        ]);

        // 改行で分割し、空行を除去
        $names = preg_split('/\r\n|\r|\n/', $validated['names']);
        $names = array_filter(array_map('trim', $names));

        foreach ($names as $name) {
            $targetList->targets()->create(['name' => $name]);
        }

        return redirect()->route('target-lists.index')->with('success', '対象者を追加しました');
    }
}
