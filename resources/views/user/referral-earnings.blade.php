<?php
if (Auth::user()->dashboard_style == "light") {
    $bg = "light";
    $text = "dark";
} else {
    $bg = "dark";
    $text = "light";
}
?>

@extends('layouts.app')

@section('content')
    @include('user.topmenu')
    @include('user.sidebar')

    <div class="main-panel earnings-page" data-theme="{{ $bg }}">
        <div class="content">
            <div class="page-inner">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="header-content">
                        <h1 class="page-title">Referral Earnings</h1>
                        <p class="page-subtitle">A breakdown of every bonus you've earned from your referrals</p>
                    </div>
                    <a href="{{ url('dashboard/referuser') }}" class="header-action">
                        <i class="fa fa-user-plus"></i>
                        Refer &amp; Earn
                    </a>
                </div>

                <x-danger-alert/>
                <x-success-alert/>

                <!-- Summary Cards -->
                <div class="summary-grid">
                    <div class="summary-card">
                        <div class="summary-icon referral">
                            <i class="fa fa-user-friends"></i>
                        </div>
                        <div class="summary-content">
                            <span class="summary-label">Referral Bonuses</span>
                            <span class="summary-value">{{ $settings->currency }}{{ number_format($referralTotal, 2) }}</span>
                            <span class="summary-meta">{{ $referralBonuses->count() }} {{ Str::plural('bonus', $referralBonuses->count()) }}</span>
                        </div>
                    </div>

                    <div class="summary-card">
                        <div class="summary-icon trade">
                            <i class="fa fa-chart-line"></i>
                        </div>
                        <div class="summary-content">
                            <span class="summary-label">Trade Bonuses</span>
                            <span class="summary-value">{{ $settings->currency }}{{ number_format($tradeTotal, 2) }}</span>
                            <span class="summary-meta">{{ $tradeBonuses->count() }} {{ Str::plural('bonus', $tradeBonuses->count()) }}</span>
                        </div>
                    </div>

                    <div class="summary-card total">
                        <div class="summary-icon earnings">
                            <i class="fa fa-coins"></i>
                        </div>
                        <div class="summary-content">
                            <span class="summary-label">Total Earned</span>
                            <span class="summary-value positive">{{ $settings->currency }}{{ number_format($referralTotal + $tradeTotal, 2) }}</span>
                            <span class="summary-meta">All-time referral income</span>
                        </div>
                    </div>
                </div>

                <!-- Referral Bonuses -->
                <div class="earn-card">
                    <div class="earn-card-header">
                        <h3>
                            <i class="fa fa-user-friends"></i>
                            Referral Bonuses
                        </h3>
                        <span class="badge-count">{{ $referralBonuses->count() }}</span>
                    </div>
                    <div class="earn-card-body no-padding">
                        @if($referralBonuses->isEmpty())
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fa fa-user-friends"></i></div>
                                <h4>No referral bonuses yet</h4>
                                <p>When someone you referred makes a deposit, your commission will appear here with their name.</p>
                            </div>
                        @else
                            <div class="earn-table-wrapper">
                                <table class="earn-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Referral</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($referralBonuses as $index => $bonus)
                                            <tr>
                                                <td class="row-num">{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="earn-user">
                                                        <div class="earn-avatar">
                                                            {{ strtoupper(substr($bonus->source_name ?? '?', 0, 1)) }}
                                                        </div>
                                                        <span class="earn-name">{{ $bonus->source_name ?? 'Referral' }}</span>
                                                    </div>
                                                </td>
                                                <td class="earn-amount positive">+{{ $settings->currency }}{{ number_format((float) $bonus->amount, 2) }}</td>
                                                <td class="earn-date">{{ $bonus->created_at->format('M d, Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Trade Bonuses -->
                <div class="earn-card">
                    <div class="earn-card-header">
                        <h3>
                            <i class="fa fa-chart-line"></i>
                            Trade Bonuses
                        </h3>
                        <span class="badge-count">{{ $tradeBonuses->count() }}</span>
                    </div>
                    <div class="earn-card-body no-padding">
                        @if($tradeBonuses->isEmpty())
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fa fa-chart-line"></i></div>
                                <h4>No trade bonuses yet</h4>
                                <p>When a referral makes a profitable trade, your commission will appear here with their name.</p>
                            </div>
                        @else
                            <div class="earn-table-wrapper">
                                <table class="earn-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Referral</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tradeBonuses as $index => $bonus)
                                            <tr>
                                                <td class="row-num">{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="earn-user">
                                                        <div class="earn-avatar trade">
                                                            {{ strtoupper(substr($bonus->source_name ?? '?', 0, 1)) }}
                                                        </div>
                                                        <span class="earn-name">{{ $bonus->source_name ?? 'Referral' }}</span>
                                                    </div>
                                                </td>
                                                <td class="earn-amount positive">+{{ $settings->currency }}{{ number_format((float) $bonus->amount, 2) }}</td>
                                                <td class="earn-date">{{ $bonus->created_at->format('M d, Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        /* ============================================
           REFERRAL EARNINGS PAGE
           ============================================ */
        .earnings-page[data-theme="dark"] {
            --bg-primary: #0a0a0f;
            --bg-card: rgba(18, 18, 26, 0.95);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #7d8ba3;
            --border-color: rgba(99, 102, 241, 0.15);
            --input-bg: #12121a;
            --hover-bg: rgba(99, 102, 241, 0.08);
        }

        .earnings-page[data-theme="light"] {
            --bg-primary: #f8fafc;
            --bg-card: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --input-bg: #f1f5f9;
            --hover-bg: rgba(99, 102, 241, 0.05);
        }

        .earnings-page .content {
            background: var(--bg-primary) !important;
        }

        .earnings-page .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .earnings-page .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 6px;
        }

        .earnings-page .page-subtitle {
            font-size: 0.95rem;
            color: var(--text-secondary);
            margin: 0;
        }

        .header-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: rgba(99, 102, 241, 0.12);
            color: #6366f1;
            border: 1px solid rgba(99, 102, 241, 0.25);
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .header-action:hover {
            background: #6366f1;
            color: #fff;
        }

        /* Summary cards */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        @media (max-width: 900px) {
            .summary-grid { grid-template-columns: 1fr; }
        }

        .summary-card {
            display: flex;
            align-items: center;
            gap: 16px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 22px 24px;
        }

        .summary-card.total {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.12), rgba(124, 58, 237, 0.10));
        }

        .summary-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .summary-icon.referral { background: rgba(99, 102, 241, 0.14); color: #6366f1; }
        .summary-icon.trade    { background: rgba(16, 185, 129, 0.14); color: #10b981; }
        .summary-icon.earnings { background: rgba(245, 158, 11, 0.14); color: #f59e0b; }

        .summary-content { display: flex; flex-direction: column; }

        .summary-label {
            font-size: 0.78rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .summary-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1.1;
        }

        .summary-value.positive { color: #10b981; }

        .summary-meta {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Cards */
        .earn-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .earn-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
        }

        .earn-card-header h3 {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .earn-card-header h3 i { color: #6366f1; font-size: 15px; }

        .badge-count {
            background: rgba(99, 102, 241, 0.12);
            color: #6366f1;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .earn-card-body.no-padding { padding: 0; }

        /* Table */
        .earn-table-wrapper { overflow-x: auto; }

        .earn-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        .earn-table th {
            padding: 14px 20px;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: var(--input-bg);
            border-bottom: 1px solid var(--border-color);
        }

        .earn-table td {
            padding: 16px 20px;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .earn-table tbody tr:last-child td { border-bottom: none; }
        .earn-table tbody tr:hover td { background: var(--hover-bg); }

        .row-num { color: var(--text-muted); font-size: 0.8rem; }

        .earn-user { display: flex; align-items: center; gap: 12px; }

        .earn-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff;
            font-size: 0.85rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .earn-avatar.trade { background: linear-gradient(135deg, #10b981, #059669); }

        .earn-name { font-weight: 500; color: var(--text-primary); }

        .earn-amount { font-weight: 700; white-space: nowrap; }
        .earn-amount.positive { color: #10b981; }

        .earn-date { font-size: 0.82rem; white-space: nowrap; }

        /* Empty state */
        .empty-state { text-align: center; padding: 56px 40px; }

        .empty-icon {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            background: rgba(99, 102, 241, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            color: #6366f1;
            font-size: 28px;
        }

        .empty-state h4 {
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 10px;
        }

        .empty-state p {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin: 0 auto;
            max-width: 360px;
            line-height: 1.6;
        }
    </style>

@endsection
