{{-- @extends('layouts.app')
@section('content') --}}
<div class="container">
    <h1>抽選対象者リスト一覧</h1>
    <a href="{{ route('target-lists.create') }}" class="btn btn-primary mb-3">新規リスト作成</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <ul>
        @foreach($lists as $list)
            <li>
                {{ $list->name }}
                <a href="{{ route('targets.create', $list) }}" class="btn btn-sm btn-secondary">対象者追加</a>
                <ul>
                    @foreach($list->targets as $target)
                        <li>{{ $target->name }}</li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</div>
{{-- @endsection --}}
