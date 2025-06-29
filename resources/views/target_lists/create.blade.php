{{-- @extends('layouts.app')
@section('content') --}}
<div class="container">
    <h1>新規抽選対象者リスト作成</h1>
    <form method="POST" action="{{ route('target-lists.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">リスト名</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary">作成</button>
    </form>
</div>
{{-- @endsection --}}
