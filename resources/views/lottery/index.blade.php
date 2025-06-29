{{-- resources/views/lottery/index.blade.php --}}
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>抽選</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; }
        .lottery-card { max-width: 600px; margin: 40px auto; box-shadow: 0 2px 16px rgba(0,0,0,0.08); }
    </style>
</head>
<body>
<div class="container">
    <div class="card lottery-card p-4 mt-5">
        <h1 class="mb-4 text-center">抽選</h1>
        <form method="GET" action="{{ route('lottery.index') }}">
            <div class="mb-3">
                <div class="d-flex align-items-center mb-2">
                    <label for="target_list_id" class="form-label mb-0 me-2">リスト選択</label>
                    <a href="{{ route('target-lists.create') }}" class="btn btn-outline-secondary btn-sm ms-2">リストを追加する</a>
                </div>
                <select name="target_list_id" id="target_list_id" class="form-select" required onchange="this.form.submit()">
                    <option value="">選択してください</option>
                    @foreach($targetLists as $list)
                        <option value="{{ $list->id }}" {{ request('target_list_id', $selectedListId ?? '') == $list->id ? 'selected' : '' }}>{{ $list->name }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        @if(request('target_list_id', $selectedListId ?? false))
            <div class="mb-3 text-end">
                <a href="{{ route('target_lists.edit', request('target_list_id', $selectedListId ?? '')) }}" class="btn btn-outline-primary btn-sm">リスト編集</a>
            </div>
        @endif
        @if(isset($targets) && count($targets) > 0)
        <form method="POST" action="{{ route('lottery.draw') }}">
            @csrf
            <input type="hidden" name="target_list_id" value="{{ request('target_list_id', $selectedListId ?? '') }}">
            <div class="mb-3">
                <label class="form-label">除外する人（複数選択可）</label>
                <div class="row g-2">
                    @foreach($targets as $target)
                        <div class="col-12 col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="exclude[]" value="{{ $target->id }}" id="exclude_{{ $target->id }}" {{ is_array(old('exclude')) && in_array($target->id, old('exclude', [])) ? 'checked' : '' }} @if($target->is_selected) disabled @endif>
                                <label class="form-check-label" for="exclude_{{ $target->id }}">
                                    {{ $target->name }}@if($target->is_selected)<span class="badge bg-success ms-2">当選済</span>@endif
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">抽選</button>
        </form>
        @endif

        @if(session('winner'))
            <div class="alert alert-success mt-4 text-center">
                <strong>当選者: </strong><span class="fs-4">{{ session('winner')->name }}</span>
            </div>
        @endif

        @if(isset($allSelected) && $allSelected)
            <div class="alert alert-info mt-4 text-center">
                <form method="POST" action="{{ route('lottery.reset') }}">
                    @csrf
                    <input type="hidden" name="target_list_id" value="{{ request('target_list_id', $selectedListId ?? '') }}">
                    <p>全員当選しました。初期化しますか？</p>
                    <button type="submit" class="btn btn-warning">初期化する</button>
                </form>
            </div>
        @endif

        @if(session('all_selected_except_excluded'))
            <div class="alert alert-info mt-4 text-center">
                <form method="POST" action="{{ route('lottery.reset') }}">
                    @csrf
                    <input type="hidden" name="target_list_id" value="{{ request('target_list_id', $selectedListId ?? '') }}">
                    <p>除外者以外は当選済みです。初期化しますか？</p>
                    <button type="submit" class="btn btn-warning">初期化する</button>
                </form>
            </div>
        @endif
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const listId = document.getElementById('target_list_id')?.value || '{{ request('target_list_id', $selectedListId ?? '') }}';
        const excludeName = `lottery_exclude_${listId}`;
        const checkboxes = document.querySelectorAll('input[name="exclude[]"]');

        // クッキーから復元
        const getCookie = (name) => {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
        };
        const saved = getCookie(excludeName);
        if (saved) {
            try {
                const arr = JSON.parse(saved);
                checkboxes.forEach(cb => {
                    if (arr.includes(cb.value)) cb.checked = true;
                });
            } catch(e) {}
        }

        // チェック時にクッキーへ保存
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const checked = Array.from(checkboxes).filter(c => c.checked).map(c => c.value);
                document.cookie = `${excludeName}=${encodeURIComponent(JSON.stringify(checked))}; max-age=86400; path=/`;
            });
        });
    });
</script>
<!-- Bootstrap JS（必要なら） -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
