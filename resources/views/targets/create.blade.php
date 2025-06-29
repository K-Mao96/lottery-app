{{-- @extends('layouts.app')
@section('content') --}}
<div class="container">
    <h1>対象者追加（{{ $targetList->name }}）</h1>
    <form method="POST" action="{{ route('targets.store', $targetList) }}">
        @csrf
        <div class="mb-3">
            <label for="names" class="form-label">対象者名（複数人は改行区切りで入力）</label>
            <textarea name="names" id="names" class="form-control" rows="5" required>{{ old('names') }}</textarea>
            @error('names')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary">追加</button>
    </form>
</div>
{{-- @endsection --}}
