<?php
if (Auth('admin')->User()->dashboard_style == "light") {
    $text = "dark";
    $bg = 'light';
} else {
    $text = "light";
    $bg = 'dark';
}
?>
@extends('layouts.app')
@section('content')
    @include('admin.topmenu')
    @include('admin.sidebar')
    <div class="main-panel">
        <div class="content bg-{{ $bg }}">
            <div class="page-inner">
                <div class="mt-2 mb-4">
                    <h1 class="title1 text-{{ $text }}">All Users' Trade History</h1>
                    <small class="text-{{ $text }}">Every trade placed by every user, all statuses, newest first.</small>
                </div>
                <x-danger-alert />
                <x-success-alert />

                {{-- Filters --}}
                <div class="mb-3 row">
                    <div class="col-12 card shadow p-3 bg-{{ Auth('admin')->User()->dashboard_style }}">
                        <form method="GET" action="{{ route('admin.trades-history') }}" class="form-row align-items-end">
                            <div class="form-group col-md-5 mb-2">
                                <label class="text-{{ $text }}">Search user</label>
                                <input type="text" name="search" value="{{ $search }}"
                                       class="form-control bg-{{ $bg }} text-{{ $text }}"
                                       placeholder="Name or email">
                            </div>
                            <div class="form-group col-md-4 mb-2">
                                <label class="text-{{ $text }}">Status</label>
                                <select name="status" class="form-control bg-{{ $bg }} text-{{ $text }}">
                                    <option value="">All statuses</option>
                                    @foreach ($statuses as $s)
                                        <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3 mb-2">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fa fa-filter"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 card shadow p-4 bg-{{ Auth('admin')->User()->dashboard_style }}">
                        @if ($investments->isEmpty())
                            <div class="alert alert-info text-{{ $text }} mb-0">
                                No trades found.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover text-{{ $text }}">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Email</th>
                                            <th>Trading Pair</th>
                                            <th>Amount</th>
                                            <th>Profit</th>
                                            <th>Status</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($investments as $investment)
                                            <tr>
                                                <td>{{ optional($investment->user)->name ?? 'Deleted user' }}</td>
                                                <td>{{ optional($investment->user)->email ?? '—' }}</td>
                                                <td>{{ optional($investment->tradingPair)->pair_name ?? '—' }}</td>
                                                <td>{{ $settings->currency }}{{ number_format($investment->amount, 2) }}</td>
                                                <td>
                                                    @if (!is_null($investment->profit))
                                                        {{ $settings->currency }}{{ number_format($investment->profit, 2) }}
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($investment->status == 'active')
                                                        <span class="badge badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-secondary">{{ ucfirst($investment->status) }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $investment->start_date ? \Carbon\Carbon::parse($investment->start_date)->toDayDateTimeString() : '—' }}</td>
                                                <td>{{ $investment->end_date ? \Carbon\Carbon::parse($investment->end_date)->toDayDateTimeString() : '—' }}</td>
                                                <td>
                                                    @if ($investment->user)
                                                        <a href="{{ route('admin.user-trades', $investment->user->id) }}" class="btn btn-primary btn-sm">
                                                            View user
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $investments->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
