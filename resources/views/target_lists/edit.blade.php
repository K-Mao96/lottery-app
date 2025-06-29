<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>リスト編集</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; }
        .lottery-card { max-width: 600px; margin: 40px auto; box-shadow: 0 2px 16px rgba(0,0,0,0.08); background: #fff; padding: 2rem; }
        .delete-checkbox-label { 
            min-width: 90px; 
            display: inline-flex; 
            align-items: center; 
            background: #f1f1f1; /* ここを薄いグレーに変更 */
            border-radius: 8px; 
            padding: 0.3em 0.9em;
            font-weight: 500;
            cursor: pointer;
            margin-left: 0.5rem;
            transition: background 0.2s;
        }
        .delete-checkbox-label input[type="checkbox"] {
            accent-color: #dc3545;
            margin-right: 0.5em;
            width: 1.1em;
            height: 1.1em;
        }
        .delete-checkbox-label:hover {
            background: #e2e2e2; /* ホバー時も少し濃いグレーに */
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card lottery-card p-4 mt-5">
        <h2 class="mb-4 text-center">リスト編集</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('target_lists.update', $list->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label"><h4 class="mb-3">リスト名</h4></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $list->name) }}" required>
            </div>
            <hr>
            <h4 class="mb-3">メンバー編集</h4>
            @if(isset($list->targets) && count($list->targets) > 0)
                <div class="mb-3">
                    <ul class="list-group" style="list-style-type: none; padding-left: 0;">
                        @foreach($list->targets as $target)
                            <li class="list-group-item d-flex align-items-center">
                                <input type="text" name="members[{{ $target->id }}][name]" value="{{ old('members.'.$target->id.'.name', $target->name) }}" class="form-control me-2" required>
                                <label class="delete-checkbox-label">
                                    <input type="checkbox" name="members[{{ $target->id }}][delete]"> 削除
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="mb-3">メンバーがいません</div>
            @endif
            <div class="mb-3 mt-4 mb-5">
                <h4 class="mb-3">メンバー追加（複数人は改行区切りで入力）</h4>
                <div class="d-flex justify-content-center">
                    <div class="w-100">
                        <textarea name="new_members" id="new_members" class="form-control" rows="3">{{ old('new_members') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center mb-2 mt-4">
                <button type="submit" class="btn btn-primary w-50">更新</button>
            </div>
            <div class="d-flex justify-content-center mb-2">
                <a href="{{ route('lottery.index', ['target_list_id' => $list->id]) }}" class="btn btn-outline-secondary w-50">戻る</a>
            </div>
        </form>
        <form action="{{ route('target_lists.destroy', $list->id) }}" method="POST" onsubmit="return confirm('本当にこのリストを削除しますか？');" class="mt-3">
            @csrf
            @method('DELETE')
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-danger w-50">リストを削除</button>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
