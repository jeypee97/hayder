<?php
if (Auth::user()->dashboard_style == "light") {
    $bgmenu = "light";
    $bg = "light";
    $text = "dark";
} else {
    $bgmenu = "dark";
    $bg = "dark";
    $text = "light";
}
?>

@extends('layouts.app')

@section('content')
    @include('user.topmenu')
    @include('user.sidebar')

    <div class="main-panel trades-page" data-theme="{{ $bg }}">
        <div class="content">
            <div class="page-inner">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="header-content">
                        <h1 class="page-title">Recent Trades</h1>
                        <p class="page-subtitle">Track your active and completed trades</p>
                    </div>
                    <div class="balance-card">
                        <div class="balance-icon">
                            <i class="fa fa-wallet"></i>
                        </div>
                        <div class="balance-info">
                            <span class="balance-label">Available Balance</span>
                            <span class="balance-value">{{ $settings->currency }}{{ number_format(auth()->user()->account_bal, 2) }}</span>
                        </div>
                    </div>
                </div>

                <x-danger-alert/>
                <x-success-alert/>

                <!-- Stats Overview -->
                @if (!$investments->isEmpty())
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon active-icon">
                                <i class="fa fa-spinner fa-pulse"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value">{{ $investments->where('status', 'active')->count() }}</span>
                                <span class="stat-label">Active Trades</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon completed-icon">
                                <i class="fa fa-check-circle"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value">{{ $investments->where('status', 'completed')->count() }}</span>
                                <span class="stat-label">Completed</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon total-icon">
                                <i class="fa fa-coins"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value">{{ $settings->currency }}{{ number_format($investments->sum('amount'), 2) }}</span>
                                <span class="stat-label">Total Invested</span>
                            </div>
                        </div>
                    </div>

                    <div class="history-chart-toolbar">
                        <span class="history-chart-note">Pair movement charts are shared per trading pair.</span>
                        <div class="history-chart-mode-switch" id="historyChartModeSwitch">
                            <button type="button" class="history-chart-mode-btn active" data-mode="line">Line</button>
                            <button type="button" class="history-chart-mode-btn" data-mode="candles">Candlestick</button>
                        </div>
                    </div>
                @endif

                <!-- Trades Content -->
                <div class="trades-card">
                    @if ($investments->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fa fa-chart-line"></i>
                            </div>
                            <h4>No Trades Yet</h4>
                            <p>You haven't made any trades. Start trading to see your history here.</p>
                            <a href="{{ route('trading.pairs') }}" class="empty-action-btn">
                                <i class="fa fa-plus"></i>
                                Start Trading
                            </a>
                        </div>
                    @else
                        <!-- Trades List -->
                        <div class="trades-list">
                            @foreach ($investments as $investment)
                                <div class="trade-item {{ $investment->status === 'active' ? 'active' : '' }}">
                                    <div class="trade-main">
                                        <div class="trade-pair">
                                            <div class="pair-icon">
                                                @if ($investment->tradingPair && $investment->tradingPair->base_icon_url)
                                                    <img src="{{ $investment->tradingPair->base_icon_url ?? asset('images/default-coin.png') }}"
                                                         alt="{{ $investment->tradingPair->base_symbol }}"
                                                         onerror="this.src='https://via.placeholder.com/40'">
                                                @else
                                                    <i class="fa fa-coins"></i>
                                                @endif
                                            </div>
                                            <div class="pair-info">
                                                <span class="pair-symbol">
                                                    {{ $investment->tradingPair ? $investment->tradingPair->base_symbol . '/' . $investment->tradingPair->quote_symbol : 'N/A' }}
                                                </span>
                                                <span class="pair-dates">
                                                    {{ $investment->start_date->format('M d, Y') }}
                                                    <i class="fa fa-arrow-right"></i>
                                                    {{ $investment->end_date ? $investment->end_date->format('M d, Y') : 'Ongoing' }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="trade-status-wrapper">
                                            @if($investment->status === 'active')
                                                <span class="status-badge active">
                                                    <span class="status-dot"></span>
                                                    Active
                                                </span>
                                            @else
                                                <span class="status-badge completed">
                                                    <i class="fa fa-check"></i>
                                                    Completed
                                                </span>
                                            @endif

                                            @if($investment->tradingPair)
                                                <span class="status-badge trend" id="trend-badge-{{ $investment->id }}">
                                                    <i class="fa fa-arrow-up"></i>
                                                    Uptrend
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($investment->tradingPair)
                                        <div class="trade-chart-wrap">
                                            <svg class="trade-chart-svg"
                                                 id="trade-chart-svg-{{ $investment->id }}"
                                                 data-investment-id="{{ $investment->id }}"
                                                 data-pair-id="{{ $investment->tradingPair->id }}"
                                                 viewBox="0 0 100 36"
                                                 preserveAspectRatio="none"
                                                 aria-label="{{ $investment->tradingPair->base_symbol }} trend chart"></svg>
                                        </div>
                                    @endif

                                    <div class="trade-details">
                                        <div class="detail-item">
                                            <span class="detail-label">Amount</span>
                                            <span class="detail-value">{{ $settings->currency }}{{ number_format($investment->amount, 2) }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Profit</span>
                                            <span class="detail-value profit-display"
                                                  data-investment-id="{{ $investment->id }}"
                                                  data-pair-id="{{ $investment->tradingPair ? $investment->tradingPair->id : '' }}"
                                                  data-amount="{{ $investment->amount }}"
                                                  data-min-return="{{ $investment->tradingPair ? $investment->tradingPair->min_return_percentage : 0 }}"
                                                  data-max-return="{{ $investment->tradingPair ? $investment->tradingPair->max_return_percentage : 0 }}"
                                                  data-status="{{ $investment->status }}"
                                                  data-profit="{{ $investment->profit ?? 0 }}">
                                                {{ $investment->profit !== null ? $settings->currency . number_format($investment->profit, 2) : 'Calculating...' }}
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Time Left</span>
                                            <span class="detail-value countdown-timer"
                                                  data-endtime="{{ $investment->end_date ? $investment->end_date->toISOString() : '' }}">
                                                {{ $investment->end_date ? '' : 'N/A' }}
                                            </span>
                                        </div>
                                    </div>

                                    @if($investment->status === 'active')
                                        <div class="trade-progress">
                                            <div class="progress-bar">
                                                <div class="progress-fill"
                                                     data-start="{{ $investment->start_date->timestamp }}"
                                                     data-end="{{ $investment->end_date ? $investment->end_date->timestamp : '' }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($investments->hasPages())
                            <div class="pagination-wrapper">
                                {{ $investments->links() }}
                            </div>
                        @endif
                    @endif
                </div>

            </div>
        </div>
    </div>

    <style>
        .trades-page {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* Theme Variables */
        .trades-page[data-theme="dark"] {
            --bg-primary: #0a0a0f;
            --bg-card: rgba(18, 18, 26, 0.9);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --border-color: rgba(99, 102, 241, 0.15);
            --hover-bg: rgba(99, 102, 241, 0.08);
        }

        .trades-page[data-theme="light"] {
            --bg-primary: #f8fafc;
            --bg-card: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --hover-bg: rgba(99, 102, 241, 0.05);
        }

        .trades-page .content {
            background: var(--bg-primary) !important;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 6px;
        }

        .page-subtitle {
            font-size: 0.95rem;
            color: var(--text-secondary);
            margin: 0;
        }

        .balance-card {
            display: flex;
            align-items: center;
            gap: 14px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 14px 20px;
        }

        .balance-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: rgba(99, 102, 241, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6366f1;
            font-size: 18px;
        }

        .balance-info {
            display: flex;
            flex-direction: column;
        }

        .balance-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .balance-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .balance-card {
                width: 100%;
            }
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .stat-icon.active-icon {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
        }

        .stat-icon.completed-icon {
            background: rgba(99, 102, 241, 0.15);
            color: #6366f1;
        }

        .stat-icon.total-icon {
            background: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
        }

        .stat-content {
            display: flex;
            flex-direction: column;
        }

        .stat-value {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .history-chart-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .history-chart-note {
            font-size: 0.82rem;
            color: var(--text-secondary);
        }

        .history-chart-mode-switch {
            display: inline-flex;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            overflow: hidden;
            background: var(--bg-card);
        }

        .history-chart-mode-btn {
            border: none;
            background: transparent;
            color: var(--text-secondary);
            font-size: 0.8rem;
            font-weight: 600;
            padding: 8px 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .history-chart-mode-btn.active {
            background: rgba(99, 102, 241, 0.16);
            color: #6366f1;
        }

        /* Trades Card */
        .trades-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
        }

        /* Trades List */
        .trades-list {
            padding: 8px;
        }

        .trade-item {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 12px;
            transition: all 0.2s ease;
        }

        .trade-item:last-child {
            margin-bottom: 0;
        }

        .trade-item:hover {
            border-color: rgba(99, 102, 241, 0.3);
        }

        .trade-item.active {
            border-left: 3px solid #10b981;
        }

        .trade-main {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .trade-pair {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .pair-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: var(--hover-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .pair-icon img {
            width: 32px;
            height: 32px;
            object-fit: contain;
        }

        .pair-icon i {
            font-size: 20px;
            color: var(--text-muted);
        }

        .pair-info {
            display: flex;
            flex-direction: column;
        }

        .pair-symbol {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .pair-dates {
            font-size: 0.8rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .pair-dates i {
            font-size: 10px;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-badge.active {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
        }

        .status-badge.completed {
            background: rgba(99, 102, 241, 0.15);
            color: #6366f1;
        }

        .status-badge.trend {
            margin-left: 8px;
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
        }

        .status-badge.trend.down {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Trade Details */
        .trade-details {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            padding: 16px 0;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }

        .trade-chart-wrap {
            width: 100%;
            height: 95px;
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 10px;
            background: linear-gradient(180deg, rgba(99, 102, 241, 0.08) 0%, rgba(99, 102, 241, 0.01) 100%);
            overflow: hidden;
            margin: 0 0 16px;
        }

        .trade-chart-svg {
            width: 100%;
            height: 100%;
            display: block;
            touch-action: none;
            cursor: grab;
        }

        .trade-chart-svg.is-dragging {
            cursor: grabbing;
        }

        @media (max-width: 576px) {
            .trade-details {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .detail-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .detail-value {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .detail-value.profit-positive {
            color: #10b981;
        }

        .detail-value.profit-negative {
            color: #ef4444;
        }

        .detail-value.countdown-active {
            color: #10b981;
        }

        .detail-value.countdown-expired {
            color: #ef4444;
        }

        /* Progress Bar */
        .trade-progress {
            margin-top: 16px;
        }

        .progress-bar {
            height: 6px;
            background: var(--hover-bg);
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #6366f1, #818cf8);
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(99, 102, 241, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: #6366f1;
            font-size: 28px;
        }

        .empty-state h4 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 8px;
        }

        .empty-state p {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin: 0 0 20px;
        }

        .empty-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: #6366f1;
            color: white;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .empty-action-btn:hover {
            background: #4f46e5;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px -4px rgba(99, 102, 241, 0.5);
        }

        /* Pagination */
        .pagination-wrapper {
            padding: 20px;
            display: flex;
            justify-content: center;
            border-top: 1px solid var(--border-color);
        }

        .pagination-wrapper .pagination {
            display: flex;
            gap: 6px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .pagination-wrapper .page-item .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 12px;
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .pagination-wrapper .page-item .page-link:hover {
            border-color: #6366f1;
            color: #6366f1;
        }

        .pagination-wrapper .page-item.active .page-link {
            background: #6366f1;
            border-color: #6366f1;
            color: white;
        }

        .pagination-wrapper .page-item.disabled .page-link {
            opacity: 0.5;
            pointer-events: none;
        }
    </style>

    <script>
        const currency = '{{ $settings->currency }}';
        const pairsBaseUrl = '{{ url('/trading-pairs') }}';
        const historyChartModeButtons = document.querySelectorAll('.history-chart-mode-btn');
        const tradeChartNodes = document.querySelectorAll('.trade-chart-svg[data-pair-id]');
        const profitElements = document.querySelectorAll('.profit-display');

        let historyChartMode = 'line';
        let historyChartTimer = null;
        const liveProfitState = new Map();

        function normalizeSeries(values) {
            const min = Math.min(...values);
            const max = Math.max(...values);
            const spread = Math.max(max - min, 0.001);
            return values.map(value => (value - min) / spread);
        }

        function toChartY(value) {
            return 3 + (1 - value) * 30;
        }

        function renderLineChart(svg, series, trend) {
            if (!series.length) {
                svg.innerHTML = '';
                return;
            }

            const normalized = normalizeSeries(series);
            const step = series.length > 1 ? 100 / (series.length - 1) : 100;
            const color = trend === 'up' ? '#10b981' : '#ef4444';

            let path = '';
            normalized.forEach((value, index) => {
                const x = (step * index).toFixed(3);
                const y = toChartY(value).toFixed(3);
                path += `${index === 0 ? 'M' : 'L'} ${x} ${y} `;
            });

            svg.innerHTML = `
                <defs>
                    <linearGradient id="historyLineFill-${svg.id}" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="${color}" stop-opacity="0.35"></stop>
                        <stop offset="100%" stop-color="${color}" stop-opacity="0"></stop>
                    </linearGradient>
                </defs>
                <path d="${path} L 100 33 L 0 33 Z" fill="url(#historyLineFill-${svg.id})"></path>
                <path d="${path}" fill="none" stroke="${color}" stroke-width="1.1" stroke-linecap="round"></path>
            `;
        }

        function renderCandleChart(svg, candles) {
            if (!candles.length) {
                svg.innerHTML = '';
                return;
            }

            const values = [];
            candles.forEach(item => values.push(item.o, item.h, item.l, item.c));
            const min = Math.min(...values);
            const max = Math.max(...values);
            const spread = Math.max(max - min, 0.001);
            const scale = value => (value - min) / spread;

            const step = 100 / candles.length;
            let html = '';

            candles.forEach((candle, index) => {
                const x = index * step + (step / 2);
                const openY = toChartY(scale(candle.o));
                const closeY = toChartY(scale(candle.c));
                const highY = toChartY(scale(candle.h));
                const lowY = toChartY(scale(candle.l));
                const top = Math.min(openY, closeY);
                const bodyHeight = Math.max(Math.abs(openY - closeY), 0.6);
                const wickHeight = Math.max(Math.abs(highY - lowY), bodyHeight + 0.6);
                const bodyWidth = clamp(step * (0.24 + ((bodyHeight / 34) * 0.96)), 0.75, step * 0.9);
                const wickWidth = clamp(0.22 + (wickHeight / 42), 0.2, 1.0);
                const rising = candle.c >= candle.o;
                const color = rising ? '#10b981' : '#ef4444';

                html += `
                    <line x1="${x.toFixed(3)}" y1="${highY.toFixed(3)}" x2="${x.toFixed(3)}" y2="${lowY.toFixed(3)}" stroke="${color}" stroke-width="${wickWidth.toFixed(3)}" stroke-linecap="round"></line>
                    <rect x="${(x - bodyWidth / 2).toFixed(3)}" y="${top.toFixed(3)}" width="${bodyWidth.toFixed(3)}" height="${bodyHeight.toFixed(3)}" fill="${color}" opacity="0.88" rx="0.22"></rect>
                `;
            });

            svg.innerHTML = html;
        }

        function applyTrendBadge(investmentId, trend) {
            const badge = document.getElementById(`trend-badge-${investmentId}`);
            if (!badge) {
                return;
            }

            const up = trend === 'up';
            badge.classList.toggle('down', !up);
            badge.innerHTML = `<i class="fa fa-${up ? 'arrow-up' : 'arrow-down'}"></i> ${up ? 'Uptrend' : 'Downtrend'}`;
        }

        function clamp(value, min, max) {
            return Math.min(Math.max(value, min), max);
        }

        function renderCompletedProfits() {
            profitElements.forEach(element => {
                const status = element.dataset.status;
                if (status === 'active') {
                    return;
                }

                const profit = parseFloat(element.dataset.profit || '0');
                element.textContent = `${currency}${profit.toFixed(2)}`;
                element.classList.remove('profit-positive', 'profit-negative');
                element.classList.add(profit >= 0 ? 'profit-positive' : 'profit-negative');
            });
        }

        function parseViewBox(svg) {
            const raw = svg.dataset.baseViewBox || svg.getAttribute('viewBox') || '0 0 100 36';
            const parts = raw.split(/\s+/).map(Number);
            return {
                x: parts[0] || 0,
                y: parts[1] || 0,
                width: parts[2] || 100,
                height: parts[3] || 36,
            };
        }

        function clamp(value, min, max) {
            return Math.min(Math.max(value, min), max);
        }

        function ensureChartZoomState(svg) {
            if (!svg.dataset.baseViewBox) {
                svg.dataset.baseViewBox = svg.getAttribute('viewBox') || '0 0 100 36';
            }

            if (!svg._zoomState) {
                const base = parseViewBox(svg);
                svg._zoomState = {
                    base,
                    current: { ...base },
                    dragging: false,
                    startClientX: 0,
                    startClientY: 0,
                    startViewBox: { ...base },
                };
            }

            return svg._zoomState;
        }

        function setChartViewBox(svg, box) {
            svg.setAttribute('viewBox', `${box.x} ${box.y} ${box.width} ${box.height}`);
            if (svg._zoomState) {
                svg._zoomState.current = { ...box };
            }
        }

        function resetChartZoom(svg) {
            const state = ensureChartZoomState(svg);
            state.current = { ...state.base };
            setChartViewBox(svg, state.base);
        }

        function zoomChart(svg, delta, clientX, clientY) {
            const state = ensureChartZoomState(svg);
            const rect = svg.getBoundingClientRect();
            const pointerX = clamp((clientX - rect.left) / rect.width, 0, 1);
            const pointerY = clamp((clientY - rect.top) / rect.height, 0, 1);
            const scale = delta < 0 ? 0.88 : 1.14;

            const base = state.current;
            const nextWidth = clamp(base.width * scale, state.base.width / 8, state.base.width);
            const nextHeight = clamp(base.height * scale, state.base.height / 8, state.base.height);

            const worldX = base.x + (base.width * pointerX);
            const worldY = base.y + (base.height * pointerY);

            const nextX = clamp(worldX - (nextWidth * pointerX), state.base.x, state.base.x + state.base.width - nextWidth);
            const nextY = clamp(worldY - (nextHeight * pointerY), state.base.y, state.base.y + state.base.height - nextHeight);

            setChartViewBox(svg, {
                x: nextX,
                y: nextY,
                width: nextWidth,
                height: nextHeight,
            });
        }

        function panChart(svg, clientX, clientY) {
            const state = ensureChartZoomState(svg);
            if (!state.dragging) {
                return;
            }

            const rect = svg.getBoundingClientRect();
            const deltaX = (clientX - state.startClientX) / rect.width * state.startViewBox.width;
            const deltaY = (clientY - state.startClientY) / rect.height * state.startViewBox.height;

            const nextX = clamp(state.startViewBox.x - deltaX, state.base.x, state.base.x + state.base.width - state.startViewBox.width);
            const nextY = clamp(state.startViewBox.y - deltaY, state.base.y, state.base.y + state.base.height - state.startViewBox.height);

            setChartViewBox(svg, {
                x: nextX,
                y: nextY,
                width: state.startViewBox.width,
                height: state.startViewBox.height,
            });
        }

        function attachChartInteractions(svg) {
            if (!svg || svg.dataset.zoomBound === '1') {
                return;
            }

            ensureChartZoomState(svg);
            svg.dataset.zoomBound = '1';

            svg.addEventListener('wheel', event => {
                event.preventDefault();
                zoomChart(svg, event.deltaY, event.clientX, event.clientY);
            }, { passive: false });

            svg.addEventListener('pointerdown', event => {
                const state = ensureChartZoomState(svg);
                state.dragging = true;
                state.startClientX = event.clientX;
                state.startClientY = event.clientY;
                state.startViewBox = { ...state.current };
                svg.classList.add('is-dragging');
                svg.setPointerCapture(event.pointerId);
            });

            svg.addEventListener('pointermove', event => {
                panChart(svg, event.clientX, event.clientY);
            });

            const endDrag = event => {
                const state = ensureChartZoomState(svg);
                state.dragging = false;
                svg.classList.remove('is-dragging');
                if (event && svg.hasPointerCapture(event.pointerId)) {
                    svg.releasePointerCapture(event.pointerId);
                }
            };

            svg.addEventListener('pointerup', endDrag);
            svg.addEventListener('pointercancel', endDrag);
            svg.addEventListener('mouseleave', () => {
                const state = ensureChartZoomState(svg);
                state.dragging = false;
                svg.classList.remove('is-dragging');
            });

            svg.addEventListener('dblclick', () => {
                resetChartZoom(svg);
            });
        }

        function updateActiveProfitFromFeed(element, payload) {
            const investmentId = element.dataset.investmentId;
            const amount = parseFloat(element.dataset.amount || '0');
            const minReturn = parseFloat(element.dataset.minReturn || '0') / 100;
            const maxReturn = parseFloat(element.dataset.maxReturn || '0') / 100;

            const lineSeries = (payload.line || []).map(point => Number(point.v));
            if (lineSeries.length < 2 || amount <= 0) {
                return;
            }

            const last = lineSeries[lineSeries.length - 1];
            const prev = lineSeries[lineSeries.length - 2];
            const movingUp = last >= prev;

            const minProfit = -minReturn * amount;
            const maxProfit = maxReturn * amount;

            const defaultProfit = parseFloat(element.dataset.profit || '0');
            const currentProfit = liveProfitState.has(investmentId)
                ? liveProfitState.get(investmentId)
                : defaultProfit;

            const movementRatio = Math.abs(last - prev) / Math.max(Math.abs(prev), 1);
            const averageRange = (minReturn + maxReturn) / 2;
            const baseStep = amount * averageRange * 0.08;
            const movementStep = amount * movementRatio * 6;
            const step = Math.max(baseStep + movementStep, amount * 0.001);

            const nextProfit = clamp(
                currentProfit + (movingUp ? step : -step),
                minProfit,
                maxProfit
            );

            liveProfitState.set(investmentId, nextProfit);

            element.textContent = `${currency}${nextProfit.toFixed(2)}`;
            element.classList.remove('profit-positive', 'profit-negative');
            element.classList.add(nextProfit >= 0 ? 'profit-positive' : 'profit-negative');
        }

        async function fetchPairFeeds(pairIds) {
            const uniquePairIds = Array.from(new Set(pairIds.filter(Boolean)));

            const payloads = await Promise.all(uniquePairIds.map(async pairId => {
                try {
                    const response = await fetch(`${pairsBaseUrl}/${pairId}/chart-feed`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        return [pairId, null];
                    }

                    const payload = await response.json();
                    if (!payload.success) {
                        return [pairId, null];
                    }

                    return [pairId, payload];
                } catch (error) {
                    console.error('Failed to refresh recent trade chart feed:', error);
                    return [pairId, null];
                }
            }));

            return new Map(payloads);
        }

        async function refreshAllTradeCharts() {
            const pairIds = [
                ...Array.from(tradeChartNodes).map(svg => svg.dataset.pairId),
                ...Array.from(profitElements).map(node => node.dataset.pairId)
            ];

            const feedByPair = await fetchPairFeeds(pairIds);

            tradeChartNodes.forEach(svg => {
                const pairId = svg.dataset.pairId;
                const investmentId = svg.dataset.investmentId;
                const payload = feedByPair.get(pairId);

                if (!payload) {
                    return;
                }

                const trend = payload.trend || 'up';
                const lineSeries = (payload.line || []).map(point => Number(point.v));
                const candles = payload.candles || [];

                applyTrendBadge(investmentId, trend);

                if (historyChartMode === 'candles') {
                    renderCandleChart(svg, candles);
                    attachChartInteractions(svg);
                    return;
                }

                renderLineChart(svg, lineSeries, trend);
                attachChartInteractions(svg);
            });

            profitElements.forEach(element => {
                if (element.dataset.status !== 'active') {
                    return;
                }

                const pairId = element.dataset.pairId;
                const payload = feedByPair.get(pairId);
                if (!payload) {
                    return;
                }

                updateActiveProfitFromFeed(element, payload);
            });
        }

        historyChartModeButtons.forEach(button => {
            button.addEventListener('click', () => {
                historyChartModeButtons.forEach(item => item.classList.remove('active'));
                button.classList.add('active');
                historyChartMode = button.dataset.mode === 'candles' ? 'candles' : 'line';
                refreshAllTradeCharts();
            });
        });

        function updateCountdowns() {
            const countdownElements = document.querySelectorAll('.countdown-timer');

            countdownElements.forEach(el => {
                const endTimeStr = el.dataset.endtime;
                if (!endTimeStr) {
                    el.textContent = 'N/A';
                    return;
                }

                const endTime = new Date(endTimeStr);
                const now = new Date();
                const diff = endTime - now;

                if (diff <= 0) {
                    el.textContent = '0.0.0';
                    el.classList.add('countdown-expired');
                    el.classList.remove('countdown-active');
                    return;
                }

                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
                const minutes = Math.floor((diff / (1000 * 60)) % 60);
                const seconds = Math.floor((diff / 1000) % 60);

                let timeString = '';
                if (days > 0) {
                    timeString = `${days}d ${String(hours).padStart(2, '0')}h ${String(minutes).padStart(2, '0')}m`;
                } else if (hours > 0) {
                    timeString = `${String(hours).padStart(2, '0')}h ${String(minutes).padStart(2, '0')}m ${String(seconds).padStart(2, '0')}s`;
                } else {
                    timeString = `${String(minutes).padStart(2, '0')}m ${String(seconds).padStart(2, '0')}s`;
                }

                el.textContent = timeString;
                el.classList.add('countdown-active');
                el.classList.remove('countdown-expired');
            });
        }

        function updateProgressBars() {
            const progressBars = document.querySelectorAll('.progress-fill');

            progressBars.forEach(bar => {
                const startTime = parseInt(bar.dataset.start) * 1000;
                const endTime = parseInt(bar.dataset.end) * 1000;

                if (!endTime) return;

                const now = Date.now();
                const total = endTime - startTime;
                const elapsed = now - startTime;
                const progress = Math.min(Math.max((elapsed / total) * 100, 0), 100);

                bar.style.width = `${progress}%`;
            });
        }

        // Initialize
        renderCompletedProfits();
        updateCountdowns();
        updateProgressBars();
        refreshAllTradeCharts();

        // Update intervals
        setInterval(updateCountdowns, 1000);
        setInterval(updateProgressBars, 1000);

        historyChartTimer = setInterval(refreshAllTradeCharts, 400);

        document.addEventListener('visibilitychange', () => {
            if (document.hidden && historyChartTimer) {
                clearInterval(historyChartTimer);
                historyChartTimer = null;
                return;
            }

            if (!document.hidden) {
                refreshAllTradeCharts();
                historyChartTimer = setInterval(refreshAllTradeCharts, 400);
            }
        });

        window.addEventListener('beforeunload', () => {
            if (historyChartTimer) {
                clearInterval(historyChartTimer);
            }
        });
    </script>
@endsection
