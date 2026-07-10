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

    <div class="main-panel trading-pairs-page" data-theme="{{ $bg }}">
        <div class="content">
            <div class="page-inner">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="header-content">
                        <h1 class="page-title">Trading Pairs</h1>
                        <p class="page-subtitle">
                            <span class="live-indicator">
                                <span class="live-dot"></span>
                              Pair movement
                            </span>
                            synchronized according to our trades.
                        </p>
                    </div>
                    <div class="header-stats">
                        <div class="market-stat">
                            <span class="stat-label">Available Pairs</span>
                            <span class="stat-value">{{ $tradingPairs->count() }}</span>
                        </div>
                    </div>
                </div>

                <x-danger-alert/>
                <x-success-alert/>

                @if ($tradingPairs->isEmpty())
                    <div class="empty-state-card">
                        <div class="empty-icon">
                            <i class="fa fa-chart-line"></i>
                        </div>
                        <h4>No Trading Pairs Available</h4>
                        <p>Trading pairs are currently being updated. Please check back later.</p>
                    </div>
                @else
                    <!-- Search & Filter Bar -->
                    <div class="filter-bar">
                        <div class="search-box">
                            <i class="fa fa-search"></i>
                            <input type="text" id="searchInput" placeholder="Search coins..." autocomplete="off">
                        </div>
                        <div class="filter-tabs">
                            <button class="filter-tab active" data-filter="all">All</button>
                            <button class="filter-tab" data-filter="gainers">Gainers</button>
                            <button class="filter-tab" data-filter="losers">Losers</button>
                        </div>
                        <div class="chart-mode-switch" id="chartModeSwitch">
                            <button type="button" class="chart-mode-btn active" data-mode="line">Line</button>
                            <button type="button" class="chart-mode-btn" data-mode="candles">Candlestick</button>
                        </div>
                    </div>

                    <!-- Trading Pairs Grid -->
                    <div class="pairs-grid" id="pairsGrid">
                        @foreach ($tradingPairs as $pair)
                            <div class="pair-card"
                                 data-name="{{ strtolower($pair->base_name) }}"
                                 data-symbol="{{ strtolower($pair->base_symbol) }}"
                                   data-pair-id="{{ $pair->id }}"
                                 data-change="{{ $pair->price_change_24h }}">

                                <div class="pair-header">
                                    <div class="pair-info">
                                        <div class="coin-icon">
                                            <img src="{{ $pair->base_icon_url ?? asset('images/default-coin.png') }}"
                                                 alt="{{ $pair->base_symbol }}"
                                                 onerror="this.src='https://via.placeholder.com/40'">
                                        </div>
                                        <div class="coin-details">
                                            <span class="coin-symbol">{{ $pair->base_symbol }}/{{ $pair->quote_symbol }}</span>
                                            <span class="coin-name">{{ $pair->base_name }}</span>
                                        </div>
                                    </div>
                                    <div class="pair-change" id="change-wrapper-{{ $pair->id }}">
                                        <span class="change-badge {{ $pair->price_change_24h >= 0 ? 'positive' : 'negative' }}"
                                              id="change-{{ $pair->id }}">
                                            <i class="fa fa-{{ $pair->price_change_24h >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                            {{ $pair->price_change_24h >= 0 ? 'Rising' : 'Falling' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="pair-price">
                                    <span class="price-label">Market Direction</span>
                                    <span class="price-value" id="direction-{{ $pair->id }}">
                                        {{ $pair->price_change_24h >= 0 ? 'Uptrend' : 'Downtrend' }}
                                    </span>
                                </div>

                                <div class="card-chart-switch" data-pair-switch>
                                    <button type="button" class="card-chart-btn active" data-card-mode="line">Line</button>
                                    <button type="button" class="card-chart-btn" data-card-mode="candles">Candle</button>
                                </div>

                                <div class="pair-chart" id="pair-chart-{{ $pair->id }}">
                                    <svg id="pair-chart-svg-{{ $pair->id }}" viewBox="0 0 100 38" preserveAspectRatio="none" aria-label="{{ $pair->base_symbol }} chart"></svg>
                                </div>

                                <div class="pair-stats">
                                    <div class="stat-item">
                                        <span class="stat-label">Market Cap</span>
                                        <span class="stat-value">{{ $settings->currency }}{{ formatNumber($pair->market_cap) }}</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-label">24h Volume</span>
                                        <span class="stat-value">{{ formatNumber($pair->volume_24h) }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('user.trading-pairs.invest', $pair->id) }}" class="trade-btn">
                                    <i class="fa fa-chart-line"></i>
                                    Trade Now
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <!-- Desktop Table View (Alternative) -->
                    <div class="table-card d-none">
                        <div class="table-wrapper">
                            <table class="pairs-table">
                                <thead>
                                <tr>
                                    <th>Coin</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">24h Change</th>
                                    <th class="text-end">Market Cap</th>
                                    <th class="text-end">24h Volume</th>
                                    <th class="text-end">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($tradingPairs as $pair)
                                    <tr>
                                        <td>
                                            <div class="coin-cell">
                                                <img src="{{ $pair->base_icon_url ?? asset('images/default-coin.png') }}"
                                                     alt="{{ $pair->base_symbol }}">
                                                <div class="coin-info">
                                                    <span class="symbol">{{ $pair->base_symbol }}/{{ $pair->quote_symbol }}</span>
                                                    <span class="name">{{ $pair->base_name }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <span class="table-price">{{ $settings->currency }}{{ number_format($pair->current_price, 2) }}</span>
                                        </td>
                                        <td class="text-end">
                                                <span class="table-change {{ $pair->price_change_24h >= 0 ? 'positive' : 'negative' }}">
                                                    {{ number_format($pair->price_change_24h, 2) }}%
                                                </span>
                                        </td>
                                        <td class="text-end">{{ $settings->currency }}{{ formatNumber($pair->market_cap) }}</td>
                                        <td class="text-end">{{ formatNumber($pair->volume_24h) }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('user.trading-pairs.invest', $pair->id) }}" class="table-trade-btn">
                                                Trade
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <style>
        .trading-pairs-page {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* Theme Variables */
        .trading-pairs-page[data-theme="dark"] {
            --bg-primary: #0a0a0f;
            --bg-card: rgba(18, 18, 26, 0.9);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #7d8ba3;
            --border-color: rgba(99, 102, 241, 0.15);
            --hover-bg: rgba(99, 102, 241, 0.08);
            --input-bg: #12121a;
        }

        .trading-pairs-page[data-theme="light"] {
            --bg-primary: #f8fafc;
            --bg-card: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --hover-bg: rgba(99, 102, 241, 0.05);
            --input-bg: #f1f5f9;
        }

        .trading-pairs-page .content {
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
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .live-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #10b981;
            font-weight: 500;
        }

        .live-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.2); }
        }

        .header-stats {
            display: flex;
            gap: 16px;
        }

        .market-stat {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 12px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .market-stat .stat-label {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .market-stat .stat-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        /* Filter Bar */
        .filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            flex: 1;
            max-width: 320px;
        }

        .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 14px;
        }

        .search-box input {
            width: 100%;
            height: 42px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 0 14px 0 42px;
            font-size: 0.9rem;
            color: var(--text-primary);
            transition: all 0.2s ease;
        }

        .search-box input::placeholder {
            color: var(--text-muted);
        }

        .search-box input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .filter-tabs {
            display: flex;
            gap: 8px;
        }

        .chart-mode-switch {
            display: inline-flex;
            height: 42px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            overflow: hidden;
            background: var(--bg-card);
        }

        .chart-mode-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            color: var(--text-secondary);
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0 14px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .chart-mode-btn.active {
            background: rgba(99, 102, 241, 0.16);
            color: #6366f1;
        }

        .card-chart-switch {
            display: flex;
            width: max-content;
            margin-left: auto;
            height: 28px;
            margin-bottom: 8px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
            background: var(--bg-card);
        }

        .card-chart-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            color: var(--text-secondary);
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0 10px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .card-chart-btn.active {
            background: rgba(99, 102, 241, 0.16);
            color: #6366f1;
        }

        .filter-tab {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 42px;
            padding: 0 18px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-secondary);
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .filter-tab:hover {
            border-color: #6366f1;
            color: #6366f1;
        }

        .filter-tab.active {
            background: #6366f1;
            border-color: #6366f1;
            color: white;
        }

        /* Pairs Grid */
        .pairs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 16px;
        }

        .pair-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 20px;
            transition: all 0.2s ease;
        }

        .pair-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px -10px rgba(0, 0, 0, 0.3);
            border-color: rgba(99, 102, 241, 0.3);
        }

        .pair-card.hidden {
            display: none;
        }

        .pair-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .pair-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .coin-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--hover-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .coin-icon img {
            width: 32px;
            height: 32px;
            object-fit: contain;
        }

        .coin-details {
            display: flex;
            flex-direction: column;
        }

        .coin-symbol {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .coin-name {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .change-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .change-badge.positive {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
        }

        .change-badge.negative {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
        }

        .pair-price {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 0;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 14px;
        }

        .price-label {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .price-value {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .pair-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }

        .pair-chart {
            width: 100%;
            height: 110px;
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 10px;
            background: linear-gradient(180deg, rgba(99, 102, 241, 0.08) 0%, rgba(99, 102, 241, 0.01) 100%);
            margin-bottom: 14px;
            overflow: hidden;
        }

        .pair-chart svg {
            width: 100%;
            height: 100%;
            display: block;
            touch-action: none;
            cursor: grab;
        }

        .pair-chart svg.is-dragging {
            cursor: grabbing;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
        }

        .stat-item .stat-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 2px;
        }

        .stat-item .stat-value {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-secondary);
        }

        .trade-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 12px;
            background: #6366f1;
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .trade-btn:hover {
            background: #4f46e5;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px -4px rgba(99, 102, 241, 0.5);
        }

        /* Empty State */
        .empty-state-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 60px 20px;
            text-align: center;
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

        .empty-state-card h4 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 8px;
        }

        .empty-state-card p {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin: 0;
        }

        /* Table Card (Alternative View) */
        .table-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .pairs-table {
            width: 100%;
            border-collapse: collapse;
        }

        .pairs-table th {
            padding: 14px 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            background: var(--hover-bg);
            text-align: left;
        }

        .pairs-table td {
            padding: 16px 20px;
            font-size: 0.9rem;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border-color);
        }

        .pairs-table tbody tr:hover {
            background: var(--hover-bg);
        }

        .coin-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .coin-cell img {
            width: 32px;
            height: 32px;
            border-radius: 8px;
        }

        .coin-cell .symbol {
            font-weight: 600;
            display: block;
        }

        .coin-cell .name {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .table-price {
            font-weight: 600;
        }

        .table-change.positive {
            color: #10b981;
        }

        .table-change.negative {
            color: #ef4444;
        }

        .table-trade-btn {
            display: inline-block;
            padding: 8px 16px;
            background: #6366f1;
            color: white;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .table-trade-btn:hover {
            background: #4f46e5;
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                max-width: none;
            }

            .filter-tabs {
                justify-content: center;
            }

            .pairs-grid {
                grid-template-columns: 1fr;
            }

            .header-stats {
                display: none;
            }
        }
    </style>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const cards = document.querySelectorAll('.pair-card');

            cards.forEach(card => {
                const name = card.dataset.name;
                const symbol = card.dataset.symbol;

                if (name.includes(searchTerm) || symbol.includes(searchTerm)) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        });

        // Filter tabs
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Update active tab
                document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                const filter = this.dataset.filter;
                const cards = document.querySelectorAll('.pair-card');

                cards.forEach(card => {
                    const change = parseFloat(card.dataset.change);

                    if (filter === 'all') {
                        card.classList.remove('hidden');
                    } else if (filter === 'gainers' && change >= 0) {
                        card.classList.remove('hidden');
                    } else if (filter === 'losers' && change < 0) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                });
            });
        });

        const pairsBaseUrl = '{{ url('/trading-pairs') }}';
        const pairCards = document.querySelectorAll('.pair-card[data-pair-id]');
        const chartModeButtons = document.querySelectorAll('.chart-mode-btn');

        let chartMode = 'line';
        let chartRefreshTimer = null;

        function normalizeSeries(values) {
            const min = Math.min(...values);
            const max = Math.max(...values);
            const spread = Math.max(max - min, 0.001);
            return values.map(value => (value - min) / spread);
        }

        function toY(value) {
            return 3 + (1 - value) * 32;
        }

        function renderLine(svg, series, trend) {
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
                const y = toY(value).toFixed(3);
                path += `${index === 0 ? 'M' : 'L'} ${x} ${y} `;
            });

            svg.innerHTML = `
                <defs>
                    <linearGradient id="pairLineFill-${svg.id}" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="${color}" stop-opacity="0.35"></stop>
                        <stop offset="100%" stop-color="${color}" stop-opacity="0"></stop>
                    </linearGradient>
                </defs>
                <path d="${path} L 100 36 L 0 36 Z" fill="url(#pairLineFill-${svg.id})"></path>
                <path d="${path}" fill="none" stroke="${color}" stroke-width="1.1" stroke-linecap="round"></path>
            `;
        }

        function renderCandles(svg, candles) {
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
                const x = index * step + step / 2;
                const openY = toY(scale(candle.o));
                const closeY = toY(scale(candle.c));
                const highY = toY(scale(candle.h));
                const lowY = toY(scale(candle.l));
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

        function parseViewBox(svg) {
            const raw = svg.dataset.baseViewBox || svg.getAttribute('viewBox') || '0 0 100 38';
            const parts = raw.split(/\s+/).map(Number);
            return {
                x: parts[0] || 0,
                y: parts[1] || 0,
                width: parts[2] || 100,
                height: parts[3] || 38,
            };
        }

        function clamp(value, min, max) {
            return Math.min(Math.max(value, min), max);
        }

        function ensureChartZoomState(svg) {
            if (!svg.dataset.baseViewBox) {
                svg.dataset.baseViewBox = svg.getAttribute('viewBox') || '0 0 100 38';
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

            setChartViewBox(svg, { x: nextX, y: nextY, width: nextWidth, height: nextHeight });
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

        function applyDirection(card, trend) {
            const pairId = card.dataset.pairId;
            const directionLabel = document.getElementById(`direction-${pairId}`);
            const changeBadge = document.getElementById(`change-${pairId}`);
            const isUp = trend === 'up';

            if (directionLabel) {
                directionLabel.textContent = isUp ? 'Uptrend' : 'Downtrend';
            }

            if (changeBadge) {
                changeBadge.className = `change-badge ${isUp ? 'positive' : 'negative'}`;
                changeBadge.innerHTML = `<i class="fa fa-${isUp ? 'arrow-up' : 'arrow-down'}"></i> ${isUp ? 'Rising' : 'Falling'}`;
            }
        }

        async function refreshCardChart(card) {
            const pairId = card.dataset.pairId;
            const svg = document.getElementById(`pair-chart-svg-${pairId}`);

            if (!pairId || !svg) {
                return;
            }

            try {
                const response = await fetch(`${pairsBaseUrl}/${pairId}/chart-feed`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    return;
                }

                const payload = await response.json();
                if (!payload.success) {
                    return;
                }

                const trend = payload.trend || 'up';
                const line = (payload.line || []).map(point => Number(point.v));
                const candles = payload.candles || [];

                applyDirection(card, trend);

                if (getCardMode(card) === 'candles') {
                    renderCandles(svg, candles);
                    attachChartInteractions(svg);
                    return;
                }

                renderLine(svg, line, trend);
                attachChartInteractions(svg);
            } catch (error) {
                console.error('Error refreshing pair chart:', error);
            }
        }

        function getCardMode(card) {
            return card.dataset.chartMode === 'candles' ? 'candles' : 'line';
        }

        function syncCardSwitch(card, mode) {
            const switchEl = card.querySelector('[data-pair-switch]');
            if (!switchEl) {
                return;
            }
            switchEl.querySelectorAll('.card-chart-btn').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.cardMode === mode);
            });
        }

        pairCards.forEach(card => {
            card.dataset.chartMode = chartMode;
            const switchEl = card.querySelector('[data-pair-switch]');
            if (!switchEl) {
                return;
            }
            switchEl.querySelectorAll('.card-chart-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const mode = btn.dataset.cardMode === 'candles' ? 'candles' : 'line';
                    card.dataset.chartMode = mode;
                    syncCardSwitch(card, mode);
                    refreshCardChart(card);
                });
            });
        });

        function refreshAllCharts() {
            pairCards.forEach(card => {
                refreshCardChart(card);
            });
        }

        chartModeButtons.forEach(button => {
            button.addEventListener('click', () => {
                chartModeButtons.forEach(item => item.classList.remove('active'));
                button.classList.add('active');
                chartMode = button.dataset.mode === 'candles' ? 'candles' : 'line';
                pairCards.forEach(card => {
                    card.dataset.chartMode = chartMode;
                    syncCardSwitch(card, chartMode);
                });
                refreshAllCharts();
            });
        });

        function startChartRefresh() {
            refreshAllCharts();

            if (chartRefreshTimer) {
                clearInterval(chartRefreshTimer);
            }

            chartRefreshTimer = setInterval(refreshAllCharts, 400);
        }

        document.addEventListener('visibilitychange', () => {
            if (document.hidden && chartRefreshTimer) {
                clearInterval(chartRefreshTimer);
                chartRefreshTimer = null;
                return;
            }

            if (!document.hidden) {
                startChartRefresh();
            }
        });

        window.addEventListener('beforeunload', () => {
            if (chartRefreshTimer) {
                clearInterval(chartRefreshTimer);
            }
        });

        startChartRefresh();
    </script>

@endsection

@php
    function formatNumber($num) {
        if ($num >= 1000000000) {
            return number_format($num / 1000000000, 2) . 'B';
        } elseif ($num >= 1000000) {
            return number_format($num / 1000000, 2) . 'M';
        } elseif ($num >= 1000) {
            return number_format($num / 1000, 2) . 'K';
        }
        return number_format($num, 0);
    }
@endphp
