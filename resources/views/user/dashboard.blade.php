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

    <div class="main-panel dashboard-page" data-theme="{{ $bg }}">
        <div class="content">
            <div class="page-inner">

                <x-danger-alert/>
                <x-success-alert/>

                <!-- Welcome Header -->
                <div class="welcome-header">
                    <div class="welcome-text">
                        <h1>Welcome back, {{ Auth::user()->name }}</h1>
                        <p>Here's what's happening with your portfolio today.</p>
                    </div>
                    <div class="welcome-actions">
                        <a href="{{ route('deposits') }}" class="action-btn primary">
                            <i class="fa fa-plus"></i>
                            Deposit
                        </a>
                        <a href="{{ route('trading.pairs') }}" class="action-btn secondary">
                            <i class="fa fa-chart-line"></i>
                            Trade Now
                        </a>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="stats-grid">
                    <!-- Account Balance -->
                    <div class="stat-card balance">
                        <div class="stat-icon">
                            <i class="fa fa-wallet"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Account Balance</span>
                            <span class="stat-value">{{ $settings->currency }}{{ number_format(Auth::user()->account_bal, 2) }}</span>
                        </div>
                    </div>

                    <!-- Total Profit -->
                    <div class="stat-card profit">
                        <div class="stat-icon">
                            <i class="fa fa-coins"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Total Profit</span>
                            <span class="stat-value positive">{{ $settings->currency }}{{ number_format(Auth::user()->roi, 2) }}</span>
                        </div>
                    </div>

                    <!-- Total Deposit -->
                    <div class="stat-card deposit">
                        <div class="stat-icon">
                            <i class="fa fa-arrow-down"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Total Deposits</span>
                            @php $depositTotal = $deposited[0]->count ?? 0; @endphp
                            <span class="stat-value">{{ $settings->currency }}{{ number_format($depositTotal, 2) }}</span>
                        </div>
                    </div>

                    <!-- Total Withdrawals -->
                    <div class="stat-card withdrawal">
                        <div class="stat-icon">
                            <i class="fa fa-arrow-up"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Total Withdrawals</span>
                            @php $withdrawalTotal = $withdrawals[0]->count ?? 0; @endphp
                            <span class="stat-value">{{ $settings->currency }}{{ number_format($withdrawalTotal, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="content-grid">

                    <!-- Quick Actions Card -->
                    <div class="dashboard-card quick-actions-card">
                        <div class="card-header">
                            <h3>
                                <i class="fa fa-bolt"></i>
                                Quick Actions
                            </h3>
                        </div>
                        <div class="quick-actions-grid">
                            <a href="{{ route('deposits') }}" class="quick-action">
                                <div class="quick-action-icon deposit">
                                    <i class="fa fa-plus-circle"></i>
                                </div>
                                <span class="quick-action-label">Deposit Funds</span>
                            </a>
                            <a href="{{ route('withdrawalsdeposits') }}" class="quick-action">
                                <div class="quick-action-icon withdraw">
                                    <i class="fa fa-arrow-up"></i>
                                </div>
                                <span class="quick-action-label">Withdraw</span>
                            </a>
                            <a href="{{ route('trading.pairs') }}" class="quick-action">
                                <div class="quick-action-icon trade">
                                    <i class="fa fa-chart-line"></i>
                                </div>
                                <span class="quick-action-label">Start Trading</span>
                            </a>
                            <a href="{{ url('dashboard/recent-trades') }}" class="quick-action">
                                <div class="quick-action-icon history">
                                    <i class="fa fa-history"></i>
                                </div>
                                <span class="quick-action-label">Trade History</span>
                            </a>
                            <a href="{{ url('dashboard/referuser') }}" class="quick-action">
                                <div class="quick-action-icon referral">
                                    <i class="fa fa-user-plus"></i>
                                </div>
                                <span class="quick-action-label">Refer & Earn</span>
                            </a>
                            <a href="{{ route('profile') }}" class="quick-action">
                                <div class="quick-action-icon settings">
                                    <i class="fa fa-cog"></i>
                                </div>
                                <span class="quick-action-label">Settings</span>
                            </a>
                        </div>
                    </div>

                    <!-- Recent Activity Card -->
                    <div class="dashboard-card activity-card">
                        <div class="card-header">
                            <h3>
                                <i class="fa fa-clock"></i>
                                Recent Activity
                            </h3>
                            <a href="{{ url('dashboard/accounthistory') }}" class="view-all">View All</a>
                        </div>
                        <div class="activity-list">
                            @php
                                try {
                                    $recentTransactions = \App\Models\Deposit::where('user_id', Auth::id())
                                        ->orderBy('created_at', 'desc')
                                        ->take(5)
                                        ->get();
                                } catch (\Exception $e) {
                                    // Try with 'user' column instead
                                    try {
                                        $recentTransactions = \App\Models\Deposit::where('user', Auth::id())
                                            ->orderBy('created_at', 'desc')
                                            ->take(5)
                                            ->get();
                                    } catch (\Exception $e) {
                                        $recentTransactions = collect([]);
                                    }
                                }
                            @endphp

                            @forelse($recentTransactions as $transaction)
                                <div class="activity-item">
                                    <div class="activity-icon {{ $transaction->status == 'Processed' ? 'success' : ($transaction->status == 'Pending' ? 'pending' : 'failed') }}">
                                        <i class="fa fa-arrow-down"></i>
                                    </div>
                                    <div class="activity-details">
                                        <span class="activity-title">Deposit</span>
                                        <span class="activity-date">{{ $transaction->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="activity-amount positive">
                                        +{{ $settings->currency }}{{ number_format($transaction->amount, 2) }}
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <i class="fa fa-inbox"></i>
                                    <p>No recent activity</p>
                                    <a href="{{ route('deposits') }}" class="empty-action">Make your first deposit</a>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Active Trades Card -->
                    <div class="dashboard-card trades-card">
                        <div class="card-header">
                            <h3>
                                <i class="fa fa-chart-bar"></i>
                                Active Trades
                            </h3>
                            <a href="{{ url('dashboard/recent-trades') }}" class="view-all">View All</a>
                        </div>
                        <div class="trades-list">
                            @php
                                try {
                                    $activeTrades = \App\Models\Investment::with('tradingPair')
                                        ->where('user_id', Auth::id())
                                        ->where('status', 'active')
                                        ->orderBy('created_at', 'desc')
                                        ->take(4)
                                        ->get();
                                } catch (\Exception $e) {
                                    $activeTrades = collect([]);
                                }
                            @endphp

                            @forelse($activeTrades as $trade)
                                <div class="trade-item">
                                    <div class="trade-info">
                                        <img src="{{ $trade->tradingPair->base_icon_url ?? 'https://via.placeholder.com/32' }}"
                                             alt="{{ $trade->tradingPair->base_symbol ?? 'Coin' }}"
                                             class="trade-icon"
                                             onerror="this.src='https://via.placeholder.com/32'">
                                        <div class="trade-details">
                                            <span class="trade-pair">{{ $trade->tradingPair->base_symbol ?? 'N/A' }}/{{ $trade->tradingPair->quote_symbol ?? 'USDT' }}</span>
                                            <span class="trade-date">Started {{ $trade->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <div class="trade-stats">
                                        <span class="trade-amount">{{ $settings->currency }}{{ number_format($trade->amount, 2) }}</span>
                                        <span class="trade-status active">Active</span>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <i class="fa fa-chart-line"></i>
                                    <p>No active trades</p>
                                    <a href="{{ route('trading.pairs') }}" class="empty-action">Start trading now</a>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Account Status Card -->
                    <div class="dashboard-card status-card">
                        <div class="card-header">
                            <h3>
                                <i class="fa fa-shield-alt"></i>
                                Account Status
                            </h3>
                        </div>
                        <div class="status-list">
                            <!-- Email Verification -->
                            <div class="status-item">
                                <div class="status-info">
                                    <i class="fa fa-envelope"></i>
                                    <span>Email Verified</span>
                                </div>
                                @if(Auth::user()->email_verified_at)
                                    <span class="status-badge verified">
                                        <i class="fa fa-check"></i> Verified
                                    </span>
                                @else
                                    <span class="status-badge pending">
                                        <i class="fa fa-clock"></i> Pending
                                    </span>
                                @endif
                            </div>

                            <!-- KYC Status -->
                            @if($settings->enable_kyc == "yes")
                                <div class="status-item">
                                    <div class="status-info">
                                        <i class="fa fa-id-card"></i>
                                        <span>KYC Verification</span>
                                    </div>
                                    @if(Auth::user()->account_verify == 'Verified')
                                        <span class="status-badge verified">
                                            <i class="fa fa-check"></i> Verified
                                        </span>
                                    @else
                                        <a href="{{ route('account.verify') }}" class="status-badge action">
                                            <i class="fa fa-arrow-right"></i> Verify Now
                                        </a>
                                    @endif
                                </div>
                            @endif

                            <!-- Account Level -->
                            <div class="status-item">
                                <div class="status-info">
                                    <i class="fa fa-star"></i>
                                    <span>Account Level</span>
                                </div>
                                <span class="status-badge level">
                                    {{ Auth::user()->user_plan ?? 'Standard' }}
                                </span>
                            </div>

                            <!-- Member Since -->
                            <div class="status-item">
                                <div class="status-info">
                                    <i class="fa fa-calendar"></i>
                                    <span>Member Since</span>
                                </div>
                                <span class="status-badge date">
                                    {{ Auth::user()->created_at->format('M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Announcement Banner (if enabled) -->
                @if ($settings->enable_annoc == "on" && !empty($settings->newupdate))
                    <div class="announcement-banner">
                        <div class="announcement-icon">
                            <i class="fa fa-bullhorn"></i>
                        </div>
                        <div class="announcement-content">
                            <span class="announcement-label">Announcement</span>
                            <p class="announcement-text">{{ $settings->newupdate }}</p>
                        </div>
                        <button class="announcement-close" onclick="this.parentElement.style.display='none'">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                @endif

                <!-- Telegram Button -->
                @if (!empty($settings->telegram_channel))
                    <a href="{{ str_starts_with($settings->telegram_channel, '@') ? 'https://t.me/' . ltrim($settings->telegram_channel, '@') : $settings->telegram_channel }}"
                       class="telegram-fab"
                       target="_blank"
                       title="Join our Telegram">
                        <i class="fab fa-telegram-plane"></i>
                    </a>
                @endif

            </div>
        </div>
    </div>

    <style>
        .dashboard-page {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* Theme Variables */
        .dashboard-page[data-theme="dark"] {
            --bg-primary: #0a0a0f;
            --bg-card: rgba(18, 18, 26, 0.9);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --border-color: rgba(99, 102, 241, 0.15);
            --hover-bg: rgba(99, 102, 241, 0.08);
        }

        .dashboard-page[data-theme="light"] {
            --bg-primary: #f8fafc;
            --bg-card: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --hover-bg: rgba(99, 102, 241, 0.05);
        }

        .dashboard-page .content {
            background: var(--bg-primary) !important;
        }

        /* Welcome Header */
        .welcome-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 16px;
            margin-top: 5dvh;
        }

        .welcome-text h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 4px;
        }

        .welcome-text p {
            font-size: 0.95rem;
            color: var(--text-secondary);
            margin: 0;
        }

        .welcome-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .action-btn.primary {
            background: #6366f1;
            color: white;
        }

        .action-btn.primary:hover {
            background: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -8px rgba(99, 102, 241, 0.5);
        }

        .action-btn.secondary {
            background: var(--hover-bg);
            color: #6366f1;
            border: 1px solid var(--border-color);
        }

        .action-btn.secondary:hover {
            background: #6366f1;
            color: white;
            border-color: #6366f1;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
            align-items: stretch;
        }

        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 400px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.2s ease;
            min-height: 90px;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px -12px rgba(0, 0, 0, 0.2);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            min-width: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .stat-card.balance .stat-icon {
            background: rgba(99, 102, 241, 0.15);
            color: #6366f1;
        }

        .stat-card.profit .stat-icon {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
        }

        .stat-card.deposit .stat-icon {
            background: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
        }

        .stat-card.withdrawal .stat-icon {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
        }

        .stat-content {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .stat-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .stat-value.positive {
            color: #10b981;
        }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            align-items: start;
        }

        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Dashboard Card */
        .dashboard-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .dashboard-card .card-header {
            flex-shrink: 0;
        }

        .dashboard-card .quick-actions-grid,
        .dashboard-card .activity-list,
        .dashboard-card .trades-list,
        .dashboard-card .status-list {
            flex: 1;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .card-header h3 {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .card-header h3 i {
            color: #6366f1;
            font-size: 16px;
        }

        .view-all {
            font-size: 0.85rem;
            color: #6366f1;
            text-decoration: none;
            font-weight: 500;
        }

        .view-all:hover {
            text-decoration: underline;
        }

        /* Quick Actions */
        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            padding: 20px;
        }

        @media (max-width: 576px) {
            .quick-actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .quick-action {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            padding: 16px 12px;
            background: var(--hover-bg);
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .quick-action:hover {
            background: #6366f1;
            transform: translateY(-2px);
        }

        .quick-action:hover .quick-action-icon {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .quick-action:hover .quick-action-label {
            color: white;
        }

        .quick-action-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.2s ease;
        }

        .quick-action-icon.deposit { background: rgba(16, 185, 129, 0.15); color: #10b981; }
        .quick-action-icon.withdraw { background: rgba(239, 68, 68, 0.15); color: #ef4444; }
        .quick-action-icon.trade { background: rgba(99, 102, 241, 0.15); color: #6366f1; }
        .quick-action-icon.history { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
        .quick-action-icon.referral { background: rgba(34, 211, 238, 0.15); color: #22d3ee; }
        .quick-action-icon.settings { background: rgba(148, 163, 184, 0.15); color: #94a3b8; }

        .quick-action-label {
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-secondary);
            text-align: center;
            transition: color 0.2s ease;
        }

        /* Activity List */
        .activity-list {
            padding: 12px 20px 20px;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .activity-icon.success { background: rgba(16, 185, 129, 0.15); color: #10b981; }
        .activity-icon.pending { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
        .activity-icon.failed { background: rgba(239, 68, 68, 0.15); color: #ef4444; }

        .activity-details {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .activity-title {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-primary);
        }

        .activity-date {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .activity-amount {
            font-size: 0.95rem;
            font-weight: 600;
        }

        .activity-amount.positive { color: #10b981; }
        .activity-amount.negative { color: #ef4444; }

        /* Trades List */
        .trades-list {
            padding: 12px 20px 20px;
        }

        .trade-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .trade-item:last-child {
            border-bottom: none;
        }

        .trade-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .trade-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            object-fit: contain;
        }

        .trade-details {
            display: flex;
            flex-direction: column;
        }

        .trade-pair {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .trade-date {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .trade-stats {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .trade-amount {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .trade-status {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 4px;
        }

        .trade-status.active {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
        }

        /* Status List */
        .status-list {
            padding: 12px 20px 20px;
        }

        .status-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .status-item:last-child {
            border-bottom: none;
        }

        .status-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .status-info i {
            width: 16px;
            color: var(--text-muted);
        }

        .status-badge {
            font-size: 0.8rem;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 6px;
            text-decoration: none;
        }

        .status-badge.verified {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
        }

        .status-badge.pending {
            background: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
        }

        .status-badge.action {
            background: #6366f1;
            color: white;
            transition: all 0.2s ease;
        }

        .status-badge.action:hover {
            background: #4f46e5;
        }

        .status-badge.level {
            background: rgba(99, 102, 241, 0.15);
            color: #6366f1;
        }

        .status-badge.date {
            background: var(--hover-bg);
            color: var(--text-secondary);
        }

        /* Empty State */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 20px;
            text-align: center;
        }

        .empty-state i {
            font-size: 36px;
            color: var(--text-muted);
            margin-bottom: 12px;
        }

        .empty-state p {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin: 0 0 12px;
        }

        .empty-action {
            font-size: 0.85rem;
            color: #6366f1;
            text-decoration: none;
            font-weight: 500;
        }

        .empty-action:hover {
            text-decoration: underline;
        }

        /* Announcement Banner */
        .announcement-banner {
            display: flex;
            align-items: center;
            gap: 16px;
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 12px;
            padding: 16px 20px;
            margin-top: 24px;
        }

        .announcement-icon {
            width: 40px;
            height: 40px;
            background: #6366f1;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
            flex-shrink: 0;
        }

        .announcement-content {
            flex: 1;
        }

        .announcement-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6366f1;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .announcement-text {
            font-size: 0.9rem;
            color: var(--text-primary);
            margin: 4px 0 0;
        }

        .announcement-close {
            background: transparent;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 8px;
            transition: color 0.2s ease;
        }

        .announcement-close:hover {
            color: var(--text-primary);
        }

        /* Telegram FAB */
        .telegram-fab {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #0088cc, #00a8e8);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            text-decoration: none;
            box-shadow: 0 4px 20px rgba(0, 136, 204, 0.4);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .telegram-fab:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 28px rgba(0, 136, 204, 0.5);
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .welcome-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .welcome-text h1 {
                font-size: 1.4rem;
            }

            .welcome-actions {
                width: 100%;
            }

            .action-btn {
                flex: 1;
                justify-content: center;
            }
        }
    </style>
@endsection
