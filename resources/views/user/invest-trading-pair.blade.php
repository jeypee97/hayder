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

    <div class="main-panel invest-page" data-theme="{{ $bg }}">
        <div class="content">
            <div class="page-inner">

                <!-- Back Button -->
                <a href="{{ route('trading.pairs') }}" class="back-link">
                    <i class="fa fa-arrow-left"></i>
                    <span>Back to Trading Pairs</span>
                </a>

                <x-danger-alert/>
                <x-success-alert/>

                <div class="invest-layout">
                    <!-- Main Form Card -->
                    <div class="invest-form-card">
                        <!-- Coin Header -->
                        <div class="coin-header">
                            <div class="coin-info">
                                <div class="coin-icon">
                                    <img src="{{ $tradingPair->base_icon_url ?? 'https://via.placeholder.com/48' }}"
                                         alt="{{ $tradingPair->base_symbol }}"
                                         onerror="this.src='https://via.placeholder.com/48'">
                                </div>
                                <div class="coin-details">
                                    <h1 class="coin-symbol">{{ $tradingPair->base_symbol }}/{{ $tradingPair->quote_symbol }}</h1>
                                    <span class="coin-name">{{ $tradingPair->base_name }}</span>
                                </div>
                            </div>
                            <div class="coin-price">
                                <span class="price-label">Market Direction</span>
                                <span class="price-value" id="market-direction-label">{{ $tradingPair->price_change_24h >= 0 ? 'Uptrend' : 'Downtrend' }}</span>
                                <span id="market-direction-badge" class="price-change {{ $tradingPair->price_change_24h >= 0 ? 'positive' : 'negative' }}">
                                    <i class="fa fa-{{ $tradingPair->price_change_24h >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                    {{ $tradingPair->price_change_24h >= 0 ? 'Momentum rising' : 'Momentum falling' }}
                                </span>
                            </div>
                        </div>

                        <div class="pair-chart-shell">
                            <div class="pair-chart-toolbar">
                                <div class="chart-type-switch">
                                    <button type="button" class="chart-type-btn active" data-chart-mode="line">Line</button>
                                       <button type="button" class="chart-type-btn" data-chart-mode="candles">Candlestick</button>
                                </div>
                                <div class="chart-trend-badge" id="chart-trend-badge">Trend: Up</div>
                            </div>
                            <div class="pair-chart-canvas" id="pair-chart-canvas">
                                <svg id="pair-chart-svg" viewBox="0 0 100 48" preserveAspectRatio="none" aria-label="Pair trend chart"></svg>
                            </div>
                               <p class="chart-footnote">Illustrative movement only. Chart does not represent live market prices.</p>
                        </div>

                        <!-- Investment Form -->
                        <form action="{{ route('user.trading-pairs.store-investment', $tradingPair->id) }}" method="POST" id="investForm">
                            @csrf

                            <!-- Amount Section -->
                            <div class="form-section">
                                <label class="section-label">
                                    <i class="fa fa-coins"></i>
                                    Trade Amount
                                </label>
                                <div class="amount-input-wrapper">
                                    <span class="currency-symbol ">{{ $settings->currency }}</span>
                                    <input
                                        type="number"
                                        name="amount"
                                        id="amount"
                                        class="amount-input"
                                        min="{{ $tradingPair->min_investment }}"
                                        max="{{ $tradingPair->max_investment }}"
                                        step="0.01"
                                        value="{{ old('amount', $tradingPair->min_investment) }}"
                                        placeholder="0.00"
                                        required
                                    >
                                </div>
                                <div class="amount-range">
                                    <span>Min: {{ $settings->currency }}{{ number_format($tradingPair->min_investment, 2) }}</span>
                                    <span>Max: {{ $settings->currency }}{{ number_format($tradingPair->max_investment, 2) }}</span>
                                </div>
                                <!-- Quick Amount Buttons -->
                                <div class="quick-amounts">
                                    <button type="button" class="quick-amount-btn" data-amount="{{ $tradingPair->min_investment }}">Min</button>
                                    <button type="button" class="quick-amount-btn" data-amount="{{ ($tradingPair->min_investment + $tradingPair->max_investment) / 4 }}">25%</button>
                                    <button type="button" class="quick-amount-btn" data-amount="{{ ($tradingPair->min_investment + $tradingPair->max_investment) / 2 }}">50%</button>
                                    <button type="button" class="quick-amount-btn" data-amount="{{ $tradingPair->max_investment }}">Max</button>
                                </div>
                                @error('amount')
                                <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Duration Section -->
                            <div class="form-section">
                                <label class="section-label">
                                    <i class="fa fa-clock"></i>
                                    Trade Duration
                                </label>
                                <div class="duration-options">
                                    @for ($i = $tradingPair->investment_duration; $i <= $tradingPair->max_investment_duration; $i++)
                                        <label class="duration-option {{ $i == $tradingPair->investment_duration ? 'selected' : '' }}">
                                            <input type="radio" name="duration" value="{{ $i }}" {{ old('duration', $tradingPair->investment_duration) == $i ? 'checked' : '' }}>
                                            <div class="duration-content">
                                                <span class="duration-value">{{ $i }}</span>
                                                <span class="duration-label">{{ $i > 1 ? 'Days' : 'Day' }}</span>
                                            </div>
                                        </label>
                                    @endfor
                                </div>
                                <p class="helper-text">
                                    <i class="fa fa-info-circle"></i>
                                    Longer durations may qualify for higher returns
                                </p>
                                @error('duration')
                                <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Estimated Return Preview -->
                            <div class="return-preview">
                                <div class="preview-header">
                                    <i class="fa fa-chart-line"></i>
                                    <span>Estimated Return</span>
                                </div>
                                <div class="preview-content">
                                    <div class="preview-row">
                                        <span class="preview-label">Trade Amount</span>
                                        <span class="preview-value" id="previewAmount">{{ $settings->currency }}{{ number_format($tradingPair->min_investment, 2) }}</span>
                                    </div>
                                    <div class="preview-row">
                                        <span class="preview-label">Duration</span>
                                        <span class="preview-value" id="previewDuration">{{ $tradingPair->investment_duration }} day(s)</span>
                                    </div>
                                    <div class="preview-row">
                                        <span class="preview-label">Return Range</span>
                                        <span class="preview-value return-range">{{ number_format($tradingPair->min_return_percentage, 1) }}% — {{ number_format($tradingPair->max_return_percentage, 1) }}%</span>
                                    </div>
                                    <div class="preview-divider"></div>
                                    <div class="preview-row highlight">
                                        <span class="preview-label">Potential Profit</span>
                                        <span class="preview-value profit" id="previewProfit">
                                            {{ $settings->currency }}{{ number_format($tradingPair->min_investment * $tradingPair->min_return_percentage / 100 * $tradingPair->investment_duration, 2) }} — {{ $settings->currency }}{{ number_format($tradingPair->min_investment * $tradingPair->max_return_percentage / 100 * $tradingPair->investment_duration, 2) }}
                                        </span>
                                    </div>
                                </div>
                                <p class="preview-disclaimer">
                                    * Actual return calculated at maturity based on market conditions
                                </p>
                            </div>

                            <!-- Error Messages -->
                            @if ($errors->any())
                                <div class="error-box">
                                    <i class="fa fa-exclamation-circle"></i>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Submit Buttons -->
                            <div class="form-actions">
                                <button type="submit" class="submit-btn">
                                    <i class="fa fa-check-circle"></i>
                                    Confirm Trade
                                </button>
                                <a href="{{ route('trading.pairs') }}" class="cancel-btn">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Sidebar Info -->
                    <div class="invest-sidebar">
                        <!-- Investment Parameters -->
                        <div class="info-card params">
                            <h4>
                                <i class="fa fa-sliders-h"></i>
                                Trade Parameters
                            </h4>
                            <div class="param-list">
                                <div class="param-item">
                                    <span class="param-label">Min Amount</span>
                                    <span class="param-value">{{ $settings->currency }}{{ number_format($tradingPair->min_investment, 2) }}</span>
                                </div>
                                <div class="param-item">
                                    <span class="param-label">Max Amount</span>
                                    <span class="param-value">{{ $settings->currency }}{{ number_format($tradingPair->max_investment, 2) }}</span>
                                </div>
                                <div class="param-item">
                                    <span class="param-label">Duration Range</span>
                                    <span class="param-value">{{ $tradingPair->investment_duration }} — {{ $tradingPair->max_investment_duration }} days</span>
                                </div>
                                <div class="param-item highlight">
                                    <span class="param-label">Expected Return</span>
                                    <span class="param-value positive">{{ number_format($tradingPair->min_return_percentage, 1) }}% — {{ number_format($tradingPair->max_return_percentage, 1) }}%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Your Balance -->
                        <div class="info-card balance">
                            <h4>
                                <i class="fa fa-wallet"></i>
                                Your Balance
                            </h4>
                            <div class="balance-display">
                                <span class="balance-amount">{{ $settings->currency }}{{ number_format(Auth::user()->account_bal, 2) }}</span>
                                <span class="balance-label">Available</span>
                            </div>
                            @if(Auth::user()->account_bal < $tradingPair->min_investment)
                                <div class="balance-warning">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    <span>Insufficient balance for minimum trade</span>
                                </div>
                                <a href="{{ route('deposits') }}" class="deposit-btn">
                                    <i class="fa fa-plus"></i>
                                    Deposit Funds
                                </a>
                            @endif
                        </div>

                        <!-- How It Works -->
                        <div class="info-card">
                            <h4>
                                <i class="fa fa-question-circle"></i>
                                How It Works
                            </h4>
                            <div class="steps-list">
                                <div class="step-item">
                                    <span class="step-number">1</span>
                                    <span class="step-text">Enter your trade amount</span>
                                </div>
                                <div class="step-item">
                                    <span class="step-number">2</span>
                                    <span class="step-text">Select trade duration</span>
                                </div>
                                <div class="step-item">
                                    <span class="step-number">3</span>
                                    <span class="step-text">Confirm and start earning</span>
                                </div>
                                <div class="step-item">
                                    <span class="step-number">4</span>
                                    <span class="step-text">Receive returns at maturity</span>
                                </div>
                            </div>
                        </div>

                        <!-- Risk Notice -->
                        {{-- <div class="info-card risk">
                            <h4>
                                <i class="fa fa-shield-alt"></i>
                                Risk Notice
                            </h4>
                            <p>Cryptocurrency trading involves risk. Past performance does not guarantee future results. Only invest what you can afford to lose.</p>
                        </div> --}}
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .invest-page {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* Theme Variables */
        .invest-page[data-theme="dark"] {
            --bg-primary: #0a0a0f;
            --bg-card: rgba(18, 18, 26, 0.9);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --border-color: rgba(99, 102, 241, 0.15);
            --input-bg: #12121a;
            --hover-bg: rgba(99, 102, 241, 0.08);
        }

        .invest-page[data-theme="light"] {
            --bg-primary: #f8fafc;
            --bg-card: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --input-bg: #f1f5f9;
            --hover-bg: rgba(99, 102, 241, 0.05);
        }

        .invest-page .content {
            background: var(--bg-primary) !important;
        }

        /* Back Link */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            margin-bottom: 24px;
            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: #6366f1;
        }

        /* Layout */
        .invest-layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 24px;
            align-items: start;
        }

        @media (max-width: 1024px) {
            .invest-layout {
                grid-template-columns: 1fr;
            }
        }

        /* Form Card */
        .invest-form-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 28px;
        }

        /* Coin Header */
        .coin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 24px;
            margin-bottom: 24px;
            border-bottom: 1px solid var(--border-color);
            flex-wrap: wrap;
            gap: 16px;
        }

        .coin-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .coin-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: var(--hover-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .coin-icon img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .coin-symbol {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .coin-name {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .coin-price {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .price-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .price-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .price-change {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .price-change.positive {
            color: #10b981;
        }

        .price-change.negative {
            color: #ef4444;
        }

        .pair-chart-shell {
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .pair-chart-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            gap: 12px;
            flex-wrap: wrap;
        }

        .chart-type-switch {
            display: inline-flex;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            overflow: hidden;
        }

        .chart-type-btn {
            border: none;
            background: transparent;
            color: var(--text-secondary);
            font-size: 0.8rem;
            font-weight: 600;
            padding: 8px 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .chart-type-btn.active {
            background: rgba(99, 102, 241, 0.16);
            color: #6366f1;
        }

        .chart-trend-badge {
            font-size: 0.8rem;
            font-weight: 600;
            color: #10b981;
            background: rgba(16, 185, 129, 0.14);
            border: 1px solid rgba(16, 185, 129, 0.35);
            border-radius: 999px;
            padding: 6px 10px;
        }

        .chart-trend-badge.down {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.14);
            border-color: rgba(239, 68, 68, 0.35);
        }

        .pair-chart-canvas {
            width: 100%;
            height: 260px;
            border-radius: 10px;
            background: linear-gradient(180deg, rgba(99, 102, 241, 0.06) 0%, rgba(99, 102, 241, 0.01) 100%);
            overflow: hidden;
            border: 1px solid rgba(99, 102, 241, 0.12);
        }

        .pair-chart-canvas svg {
            width: 100%;
            height: 100%;
            display: block;
            touch-action: none;
            cursor: grab;
        }

        .pair-chart-canvas svg.is-dragging {
            cursor: grabbing;
        }

        .chart-footnote {
            margin: 10px 0 0;
            font-size: 0.78rem;
            color: var(--text-muted);
        }

        /* Form Sections */
        .form-section {
            margin-bottom: 28px;
        }

        .section-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 14px;
        }

        .section-label i {
            color: #6366f1;
            font-size: 14px;
        }

        /* Amount Input */
        .amount-input-wrapper {
            position: relative;
            margin-bottom: 10px;
        }

        .currency-symbol {
            position: absolute;
            left: 2px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.35rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        .amount-input {
            width: 100%;
            background: var(--input-bg);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 18px 18px 18px 48px;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            transition: all 0.2s ease;
        }

        .amount-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        }

        .amount-range {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 12px;
        }

        /* Quick Amounts */
        .quick-amounts {
            display: flex;
            gap: 8px;
        }

        .quick-amount-btn {
            flex: 1;
            padding: 10px;
            background: var(--hover-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-secondary);
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .quick-amount-btn:hover {
            border-color: #6366f1;
            color: #6366f1;
        }

        .quick-amount-btn.active {
            background: #6366f1;
            border-color: #6366f1;
            color: white;
        }

        /* Duration Options */
        .duration-options {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .duration-option {
            flex: 1;
            min-width: 70px;
            position: relative;
            cursor: pointer;
        }

        .duration-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .duration-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 16px 12px;
            background: var(--input-bg);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .duration-option:hover .duration-content {
            border-color: rgba(99, 102, 241, 0.5);
        }

        .duration-option.selected .duration-content,
        .duration-option input:checked + .duration-content {
            border-color: #6366f1;
            background: rgba(99, 102, 241, 0.1);
        }

        .duration-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .duration-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .helper-text {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 12px;
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        /* Return Preview */
        .return-preview {
            background: rgba(99, 102, 241, 0.08);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .preview-header {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            color: #6366f1;
            margin-bottom: 16px;
        }

        .preview-content {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .preview-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .preview-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .preview-value {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .preview-value.return-range {
            color: #6366f1;
        }

        .preview-divider {
            height: 1px;
            background: rgba(99, 102, 241, 0.2);
            margin: 8px 0;
        }

        .preview-row.highlight .preview-label {
            font-weight: 600;
            color: var(--text-primary);
        }

        .preview-value.profit {
            color: #10b981;
            font-size: 1rem;
        }

        .preview-disclaimer {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin: 12px 0 0;
            font-style: italic;
        }

        /* Error */
        .error-text {
            display: block;
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 8px;
        }

        .error-box {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 20px;
        }

        .error-box i {
            color: #ef4444;
            margin-top: 2px;
        }

        .error-box ul {
            margin: 0;
            padding: 0 0 0 16px;
            color: #ef4444;
            font-size: 0.875rem;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .submit-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 18px 24px;
            background: #6366f1;
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .submit-btn:hover {
            background: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -8px rgba(99, 102, 241, 0.5);
        }

        .cancel-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 14px 24px;
            background: transparent;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            color: var(--text-secondary);
            font-size: 0.95rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .cancel-btn:hover {
            border-color: var(--text-muted);
            color: var(--text-primary);
        }

        /* Sidebar */
        .invest-sidebar {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .info-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 20px;
        }

        .info-card h4 {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 16px;
        }

        .info-card h4 i {
            color: #6366f1;
        }

        .info-card p {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin: 0;
            line-height: 1.5;
        }

        /* Parameters List */
        .param-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .param-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .param-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .param-label {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .param-value {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .param-value.positive {
            color: #10b981;
        }

        /* Balance Card */
        .balance-display {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 16px;
            background: var(--hover-bg);
            border-radius: 10px;
            margin-bottom: 12px;
        }

        .balance-amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .balance-label {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .balance-warning {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            background: rgba(245, 158, 11, 0.1);
            border-radius: 8px;
            margin-bottom: 12px;
            color: #f59e0b;
            font-size: 0.8rem;
        }

        .deposit-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 12px;
            background: #6366f1;
            border-radius: 10px;
            color: white;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .deposit-btn:hover {
            background: #4f46e5;
            color: white;
        }

        /* Steps List */
        .steps-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .step-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .step-number {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: rgba(99, 102, 241, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            color: #6366f1;
            flex-shrink: 0;
        }

        .step-text {
            font-size: 0.85rem;
            color: var(--text-secondary);
            line-height: 1.4;
        }

        /* Risk Card */
        .info-card.risk {
            border-color: rgba(245, 158, 11, 0.3);
            background: rgba(245, 158, 11, 0.05);
        }

        .info-card.risk h4 i {
            color: #f59e0b;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .invest-form-card {
                padding: 20px;
            }

            .coin-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .coin-price {
                align-items: flex-start;
            }

            .amount-input {
                font-size: 1.25rem;
            }

            .duration-options {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
            }
        }
    </style>

    <script>
        const amountInput = document.getElementById('amount');
        const quickAmountBtns = document.querySelectorAll('.quick-amount-btn');
        const durationOptions = document.querySelectorAll('.duration-option');
        const previewAmount = document.getElementById('previewAmount');
        const previewDuration = document.getElementById('previewDuration');
        const previewProfit = document.getElementById('previewProfit');

        const chartSvg = document.getElementById('pair-chart-svg');
        const chartModeButtons = document.querySelectorAll('.chart-type-btn');
        const chartTrendBadge = document.getElementById('chart-trend-badge');
        const marketDirectionLabel = document.getElementById('market-direction-label');
        const marketDirectionBadge = document.getElementById('market-direction-badge');

        const currency = '{{ $settings->currency }}';
        const minReturn = {{ $tradingPair->min_return_percentage }};
        const maxReturn = {{ $tradingPair->max_return_percentage }};
        const chartFeedUrl = '{{ route('user.trading-pairs.chart-feed', $tradingPair->id) }}';

        let chartMode = 'line';
        let chartTimer = null;

        function updatePreview() {
            const amount = parseFloat(amountInput.value) || 0;
            const selectedDuration = document.querySelector('input[name="duration"]:checked');
            const duration = selectedDuration ? selectedDuration.value : 1;

            previewAmount.textContent = `${currency}${amount.toFixed(2)}`;
            previewDuration.textContent = `${duration} day${duration > 1 ? 's' : ''}`;

            const minProfit = (amount * minReturn / 100 * duration).toFixed(2);
            const maxProfit = (amount * maxReturn / 100 * duration).toFixed(2);
            previewProfit.textContent = `${currency}${minProfit} — ${currency}${maxProfit}`;
        }

        function normalizeValues(values) {
            const min = Math.min(...values);
            const max = Math.max(...values);
            const spread = Math.max(max - min, 0.001);
            return values.map(value => ((value - min) / spread));
        }

        function toChartY(normalizedValue) {
            return 4 + (1 - normalizedValue) * 40;
        }

        function renderLineChart(series, trend) {
            if (!series.length) {
                chartSvg.innerHTML = '';
                return;
            }

            const normalized = normalizeValues(series);
            const stepX = series.length > 1 ? 100 / (series.length - 1) : 100;

            let linePath = '';
            normalized.forEach((value, index) => {
                const x = (index * stepX).toFixed(3);
                const y = toChartY(value).toFixed(3);
                linePath += `${index === 0 ? 'M' : 'L'} ${x} ${y} `;
            });

            const color = trend === 'up' ? '#10b981' : '#ef4444';

            chartSvg.innerHTML = `
                <defs>
                    <linearGradient id="lineFill" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="${color}" stop-opacity="0.35"></stop>
                        <stop offset="100%" stop-color="${color}" stop-opacity="0"></stop>
                    </linearGradient>
                </defs>
                <path d="${linePath} L 100 46 L 0 46 Z" fill="url(#lineFill)"></path>
                <path d="${linePath}" fill="none" stroke="${color}" stroke-width="1.2" stroke-linecap="round"></path>
            `;
        }

        function renderCandles(candles) {
            if (!candles.length) {
                chartSvg.innerHTML = '';
                return;
            }

            const values = [];
            candles.forEach(candle => {
                values.push(candle.o, candle.h, candle.l, candle.c);
            });

            const min = Math.min(...values);
            const max = Math.max(...values);
            const spread = Math.max(max - min, 0.001);

            const normalize = value => (value - min) / spread;
            const stepX = candles.length > 0 ? 100 / candles.length : 100;
               const bodyWidth = Math.max(stepX * 0.58, 0.8);

            let svg = '';

            candles.forEach((candle, index) => {
                const xCenter = (index * stepX) + (stepX / 2);
                const openY = toChartY(normalize(candle.o));
                const closeY = toChartY(normalize(candle.c));
                const highY = toChartY(normalize(candle.h));
                const lowY = toChartY(normalize(candle.l));
                const bodyTop = Math.min(openY, closeY);
                   const bodyHeight = Math.max(Math.abs(openY - closeY), 0.6);
                   const wickHeight = Math.max(Math.abs(highY - lowY), bodyHeight + 0.6);
                   const bodyWidth = clamp(stepX * (0.24 + ((bodyHeight / 40) * 1.02)), 0.75, stepX * 0.9);
                   const wickWidth = clamp(0.22 + (wickHeight / 44), 0.2, 1.0);
                const rising = candle.c >= candle.o;
                const bodyColor = rising ? '#10b981' : '#ef4444';

                svg += `
                       <line x1="${xCenter.toFixed(3)}" y1="${highY.toFixed(3)}" x2="${xCenter.toFixed(3)}" y2="${lowY.toFixed(3)}" stroke="${bodyColor}" stroke-width="${wickWidth.toFixed(3)}" stroke-linecap="round"></line>
                    <rect x="${(xCenter - (bodyWidth / 2)).toFixed(3)}" y="${bodyTop.toFixed(3)}" width="${bodyWidth.toFixed(3)}" height="${bodyHeight.toFixed(3)}" fill="${bodyColor}" opacity="0.8" rx="0.2"></rect>
                `;
            });

            chartSvg.innerHTML = svg;
        }

        function parseViewBox(svg) {
            const raw = svg.dataset.baseViewBox || svg.getAttribute('viewBox') || '0 0 100 48';
            const parts = raw.split(/\s+/).map(Number);
            return {
                x: parts[0] || 0,
                y: parts[1] || 0,
                width: parts[2] || 100,
                height: parts[3] || 48,
            };
        }

        function clamp(value, min, max) {
            return Math.min(Math.max(value, min), max);
        }

        function ensureChartZoomState(svg) {
            if (!svg.dataset.baseViewBox) {
                svg.dataset.baseViewBox = svg.getAttribute('viewBox') || '0 0 100 48';
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

        function applyTrend(trend) {
            const isUp = trend === 'up';

            chartTrendBadge.textContent = `Trend: ${isUp ? 'Up' : 'Down'}`;
            chartTrendBadge.classList.toggle('down', !isUp);

            marketDirectionLabel.textContent = isUp ? 'Uptrend' : 'Downtrend';
            marketDirectionBadge.classList.toggle('positive', isUp);
            marketDirectionBadge.classList.toggle('negative', !isUp);
            marketDirectionBadge.innerHTML = `<i class="fa fa-${isUp ? 'arrow-up' : 'arrow-down'}"></i> ${isUp ? 'Momentum rising' : 'Momentum falling'}`;
        }

        async function refreshPairChart() {
            try {
                const response = await fetch(chartFeedUrl, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`Chart feed failed with status ${response.status}`);
                }

                const payload = await response.json();
                if (!payload.success) {
                    return;
                }

                const lineValues = (payload.line || []).map(point => Number(point.v));
                const candles = payload.candles || [];
                const trend = payload.trend || 'up';

                applyTrend(trend);

                if (chartMode === 'candles') {
                    renderCandles(candles);
                    attachChartInteractions(chartSvg);
                } else {
                    renderLineChart(lineValues, trend);
                    attachChartInteractions(chartSvg);
                }
            } catch (error) {
                console.error('Failed to refresh chart:', error);
            }
        }

        function setupChartModeToggle() {
            chartModeButtons.forEach(button => {
                button.addEventListener('click', () => {
                    chartModeButtons.forEach(item => item.classList.remove('active'));
                    button.classList.add('active');
                    chartMode = button.dataset.chartMode === 'candles' ? 'candles' : 'line';
                    refreshPairChart();
                });
            });
        }

        function startChartAutoRefresh() {
            refreshPairChart();

            if (chartTimer) {
                clearInterval(chartTimer);
            }

            chartTimer = setInterval(refreshPairChart, 400);
        }

        quickAmountBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                amountInput.value = parseFloat(this.dataset.amount).toFixed(2);
                quickAmountBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                updatePreview();
            });
        });

        amountInput.addEventListener('input', function() {
            quickAmountBtns.forEach(b => b.classList.remove('active'));
            updatePreview();
        });

        durationOptions.forEach(option => {
            option.addEventListener('click', function() {
                durationOptions.forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');
                updatePreview();
            });
        });

        document.addEventListener('visibilitychange', () => {
            if (document.hidden && chartTimer) {
                clearInterval(chartTimer);
                chartTimer = null;
                return;
            }

            if (!document.hidden) {
                startChartAutoRefresh();
            }
        });

        window.addEventListener('beforeunload', () => {
            if (chartTimer) {
                clearInterval(chartTimer);
                chartTimer = null;
            }
        });

        updatePreview();
        setupChartModeToggle();
        startChartAutoRefresh();
    </script>
@endsection
