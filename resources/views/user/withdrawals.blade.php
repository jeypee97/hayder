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

    <div class="main-panel withdrawal-page" data-theme="{{ $bg }}">
        <div class="content">
            <div class="page-inner">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="header-content">
                        <h1 class="page-title">Withdraw Funds</h1>
                        <p class="page-subtitle">Request a withdrawal to your wallet</p>
                    </div>
                    <div class="balance-display">
                        <span class="balance-label">Available Balance</span>
                        <span class="balance-amount">{{ $settings->currency }}{{ number_format(Auth::user()->account_bal, 2) }}</span>
                    </div>
                </div>

                <x-danger-alert/>
                <x-success-alert/>

                @if ($settings->enable_with == "false")
                    <!-- Withdrawals Disabled -->
                    <div class="disabled-card">
                        <div class="disabled-icon">
                            <i class="fa fa-pause-circle"></i>
                        </div>
                        <h3>Withdrawals Temporarily Disabled</h3>
                        <p>Withdrawal services are currently unavailable. Please check back later or contact support for more information.</p>
                        <a href="{{ route('support') }}" class="contact-btn">
                            <i class="fa fa-headset"></i>
                            Contact Support
                        </a>
                    </div>
                @else
                    <div class="withdrawal-layout">
                        <!-- Main Form -->
                        <div class="withdrawal-form-card">
                            <form action="{{ route('withdrawamount') }}" method="POST" id="withdrawalForm">
                                @csrf

                                <!-- Hidden fields for the payout amount and full balance deduction -->
                                <input type="hidden" name="amount" id="netAmount" value="0">
                                <input type="hidden" name="gross_amount" id="grossAmountSubmitted" value="0">

                                <!-- Amount Input -->
                                <div class="form-section">
                                    <label class="section-label">
                                        <i class="fa fa-coins"></i>
                                        Withdrawal Amount
                                    </label>
                                    <div class="amount-input-wrapper">
                                        <span class="currency-symbol">{{ $settings->currency }}</span>
                                        <input
                                            type="number"
                                            id="grossAmount"
                                            class="amount-input"
                                            placeholder="0.00"
                                            min="1"
                                            step="any"
                                            required
                                        >
                                    </div>
                                    <!-- Quick Amount Buttons -->
                                    <div class="quick-amounts">
                                        <button type="button" class="quick-amount-btn" data-amount="50">$50</button>
                                        <button type="button" class="quick-amount-btn" data-amount="100">$100</button>
                                        <button type="button" class="quick-amount-btn" data-amount="250">$250</button>
                                        <button type="button" class="quick-amount-btn" data-amount="500">$500</button>
                                        <button type="button" class="quick-amount-btn" data-amount="all">All</button>
                                    </div>

                                    <!-- Fee Calculation -->
                                    <div class="fee-breakdown">
                                        <div class="fee-row">
                                            <span class="fee-label">Requested Amount</span>
                                            <span class="fee-value" id="requestedAmount">{{ $settings->currency }}0.00</span>
                                        </div>
                                        <div class="fee-row">
                                            <span class="fee-label">Service Fee ({{ $settings->withdrawal_percentage }}%)</span>
                                            <span class="fee-value negative" id="feeAmount">-{{ $settings->currency }}0.00</span>
                                        </div>
                                        <div class="fee-row total">
                                            <span class="fee-label">You'll Receive (Amount Sent)</span>
                                            <span class="fee-value" id="receiveAmount">{{ $settings->currency }}0.00</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Wallet Address -->
                                <div class="form-section">
                                    <label class="section-label">
                                        <i class="fa fa-wallet"></i>
                                        Wallet Address
                                    </label>
                                    <input
                                        type="text"
                                        name="wallet_address"
                                        class="form-input"
                                        placeholder="Enter your wallet address (e.g., 0x...)"
                                        required
                                    >
                                </div>

                                <!-- Network Selection -->
                                <div class="form-section">
                                    <label class="section-label">
                                        <i class="fa fa-network-wired"></i>
                                        Select Network
                                    </label>
                                    <div class="network-options">
                                        <label class="network-option selected">
                                            <input type="radio" name="network" value="BSC" checked>
                                            <div class="network-content">
                                                <span class="network-name">BNB Smart Chain</span>
                                                <span class="network-tag">BEP20</span>
                                            </div>
                                            <div class="network-check">
                                                <i class="fa fa-check"></i>
                                            </div>
                                        </label>
                                        <label class="network-option">
                                            <input type="radio" name="network" value="ERC20">
                                            <div class="network-content">
                                                <span class="network-name">Ethereum</span>
                                                <span class="network-tag">ERC20</span>
                                            </div>
                                            <div class="network-check">
                                                <i class="fa fa-check"></i>
                                            </div>
                                        </label>

                                    </div>
                                </div>

                                <!-- Notes -->
                                <div class="form-section">
                                    <label class="section-label">
                                        <i class="fa fa-sticky-note"></i>
                                        Notes <span class="optional">(Optional)</span>
                                    </label>
                                    <textarea
                                        name="notes"
                                        class="form-textarea"
                                        rows="3"
                                        placeholder="Add any notes for this withdrawal request..."
                                    ></textarea>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="submit-btn">
                                    <i class="fa fa-paper-plane"></i>
                                    <span>Submit Withdrawal Request</span>
                                </button>
                            </form>
                        </div>

                        <!-- Sidebar Info -->
                        <div class="withdrawal-sidebar">
                            <!-- Important Notice -->
                            <div class="info-card notice">
                                <div class="notice-header">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    <h4>Important Notice</h4>
                                </div>
                                <ul class="notice-list">
                                    <li>{{ $settings->network_note }}</li>
                                    <li>Processing time: <strong>10-15 minutes</strong></li>
                                    <li>Service charge: <strong>{{ $settings->withdrawal_percentage }}%</strong></li>
                                    <li>You'll receive <strong>{{ 100 - $settings->withdrawal_percentage }}%</strong> of requested amount</li>
                                </ul>
                            </div>

                            <!-- Processing Time -->
                            <div class="info-card">
                                <div class="info-icon">
                                    <i class="fa fa-clock"></i>
                                </div>
                                <h4>Processing Time</h4>
                                <p>Withdrawals are typically processed within 10-15 minutes after verification.</p>
                                <div class="status-indicator">
                                    <span class="status-dot"></span>
                                    <span>System operational</span>
                                </div>
                            </div>

                            <!-- Telegram Support -->
                            @if (!empty($settings->telegram_channel))
                                <div class="info-card telegram">
                                    <div class="info-icon tg">
                                        <i class="fab fa-telegram-plane"></i>
                                    </div>
                                    <h4>Need Assistance?</h4>
                                    <p>Join our Telegram channel for real-time support and updates.</p>
                                    <a href="{{ str_starts_with($settings->telegram_channel, '@') ? 'https://t.me/' . ltrim($settings->telegram_channel, '@') : $settings->telegram_channel }}"
                                       target="_blank"
                                       class="telegram-btn">
                                        <i class="fab fa-telegram-plane"></i>
                                        Join Telegram
                                    </a>
                                </div>
                            @endif

                            <!-- Security -->
                            <div class="info-card security">
                                <div class="info-icon secure">
                                    <i class="fa fa-shield-alt"></i>
                                </div>
                                <h4>Secure Withdrawals</h4>
                                <p>All withdrawals are verified and processed through secure channels.</p>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <style>
        .withdrawal-page {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* Theme Variables */
        .withdrawal-page[data-theme="dark"] {
            --bg-primary: #0a0a0f;
            --bg-card: rgba(18, 18, 26, 0.9);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --border-color: rgba(99, 102, 241, 0.15);
            --input-bg: #12121a;
            --hover-bg: rgba(99, 102, 241, 0.08);
        }

        .withdrawal-page[data-theme="light"] {
            --bg-primary: #f8fafc;
            --bg-card: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --input-bg: #f1f5f9;
            --hover-bg: rgba(99, 102, 241, 0.05);
        }

        .withdrawal-page .content {
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

        .balance-display {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 12px 20px;
        }

        .balance-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .balance-amount {
            font-size: 1.25rem;
            font-weight: 700;
            color: #10b981;
        }

        /* Disabled Card */
        .disabled-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 60px 40px;
            text-align: center;
            max-width: 500px;
            margin: 0 auto;
        }

        .disabled-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            color: #ef4444;
            font-size: 36px;
        }

        .disabled-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 12px;
        }

        .disabled-card p {
            font-size: 0.95rem;
            color: var(--text-secondary);
            margin: 0 0 24px;
            line-height: 1.6;
        }

        .contact-btn {
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

        .contact-btn:hover {
            background: #4f46e5;
            color: white;
        }

        /* Layout */
        .withdrawal-layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 24px;
            align-items: start;
        }

        @media (max-width: 1024px) {
            .withdrawal-layout {
                grid-template-columns: 1fr;
            }
        }

        /* Form Card */
        .withdrawal-form-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 28px;
        }

        .form-section {
            margin-bottom: 24px;
        }

        .section-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 12px;
        }

        .section-label i {
            color: #6366f1;
            font-size: 14px;
        }

        .section-label .optional {
            color: var(--text-muted);
            font-weight: 400;
            font-size: 0.85rem;
        }

        /* Amount Input */
        .amount-input-wrapper {
            position: relative;
            margin-bottom: 14px;
        }

        .currency-symbol {
            position: absolute;
            left: 18px;
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
                        margin-left: 20px;

        }

        .amount-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        }

        /* Quick Amounts */
        .quick-amounts {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .quick-amount-btn {
            padding: 8px 16px;
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

        /* Fee Breakdown */
        .fee-breakdown {
            background: var(--hover-bg);
            border-radius: 10px;
            padding: 14px 16px;
        }

        .fee-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
        }

        .fee-row.total {
            border-top: 1px solid var(--border-color);
            margin-top: 8px;
            padding-top: 12px;
        }

        .fee-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .fee-value {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .fee-value.negative {
            color: #ef4444;
        }

        .fee-row.total .fee-value {
            color: #10b981;
            font-size: 1rem;
        }

        /* Form Input */
        .form-input,
        .form-textarea {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 14px 16px;
            font-size: 0.95rem;
            color: var(--text-primary);
            font-family: inherit;
            transition: all 0.2s ease;
        }

        .form-input:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        .form-textarea {
            resize: vertical;
            min-height: 80px;
        }

        /* Network Options */
        .network-options {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        @media (max-width: 576px) {
            .network-options {
                grid-template-columns: 1fr;
            }
        }

        .network-option {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px;
            background: var(--input-bg);
            border: 2px solid var(--border-color);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .network-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .network-option:hover {
            border-color: rgba(99, 102, 241, 0.5);
        }

        .network-option.selected {
            border-color: #6366f1;
            background: rgba(99, 102, 241, 0.1);
        }

        .network-content {
            display: flex;
            flex-direction: column;
        }

        .network-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .network-tag {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .network-check {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #6366f1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 10px;
            opacity: 0;
            transform: scale(0.5);
            transition: all 0.2s ease;
        }

        .network-option.selected .network-check {
            opacity: 1;
            transform: scale(1);
        }

        /* Submit Button */
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
            margin-top: 8px;
        }

        .submit-btn:hover {
            background: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -8px rgba(99, 102, 241, 0.5);
        }

        /* Sidebar */
        .withdrawal-sidebar {
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

        .info-card.notice {
            border-color: rgba(245, 158, 11, 0.3);
            background: rgba(245, 158, 11, 0.05);
        }

        .notice-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
            color: #f59e0b;
        }

        .notice-header h4 {
            font-size: 0.95rem;
            font-weight: 600;
            margin: 0;
        }

        .notice-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .notice-list li {
            position: relative;
            padding-left: 16px;
            margin-bottom: 10px;
            font-size: 0.85rem;
            color: var(--text-secondary);
            line-height: 1.4;
        }

        .notice-list li:last-child {
            margin-bottom: 0;
        }

        .notice-list li::before {
            content: '';
            position: absolute;
            left: 0;
            top: 8px;
            width: 5px;
            height: 5px;
            background: #f59e0b;
            border-radius: 50%;
        }

        .info-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: rgba(99, 102, 241, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6366f1;
            font-size: 18px;
            margin-bottom: 14px;
        }

        .info-icon.tg {
            background: rgba(0, 136, 204, 0.15);
            color: #0088cc;
        }

        .info-icon.secure {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
        }

        .info-card h4 {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 8px;
        }

        .info-card p {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin: 0;
            line-height: 1.5;
        }

        .status-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 12px;
            font-size: 0.8rem;
            color: #10b981;
            font-weight: 500;
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

        .telegram-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 12px;
            margin-top: 14px;
            background: #0088cc;
            border-radius: 10px;
            color: white;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .telegram-btn:hover {
            background: #006699;
            color: white;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .withdrawal-form-card {
                padding: 20px;
            }

            .amount-input {
                font-size: 1.25rem;
            }

            .balance-display {
                width: 100%;
                align-items: flex-start;
            }
        }
    </style>

    <script>
        const grossAmountInput = document.getElementById('grossAmount');
        const netAmountInput = document.getElementById('netAmount');
        const grossAmountSubmittedInput = document.getElementById('grossAmountSubmitted');
        const quickAmountBtns = document.querySelectorAll('.quick-amount-btn');
        const requestedAmount = document.getElementById('requestedAmount');
        const feeAmount = document.getElementById('feeAmount');
        const receiveAmount = document.getElementById('receiveAmount');
        const networkOptions = document.querySelectorAll('.network-option');
        const feePercentage = {{ $settings->withdrawal_percentage }};
        const userBalance = {{ Auth::user()->account_bal }};
        const currency = '{{ $settings->currency }}';

        // Calculate fees and update hidden inputs with payout and total debit amounts.
        function calculateFees() {
            const gross = parseFloat(grossAmountInput.value) || 0;
            const fee = (gross * feePercentage) / 100;
            const net = gross - fee;

            // Update display
            requestedAmount.textContent = `${currency}${gross.toFixed(2)}`;
            feeAmount.textContent = `-${currency}${fee.toFixed(2)}`;
            receiveAmount.textContent = `${currency}${net.toFixed(2)}`;

            // Keep the submitted values aligned with the UI.
            netAmountInput.value = net.toFixed(2);
            grossAmountSubmittedInput.value = gross.toFixed(2);
        }

        // Quick amount buttons
        quickAmountBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const amount = this.dataset.amount;

                if (amount === 'all') {
                    grossAmountInput.value = userBalance;
                } else {
                    grossAmountInput.value = amount;
                }

                quickAmountBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                calculateFees();
            });
        });

        // Amount input change
        grossAmountInput.addEventListener('input', function() {
            quickAmountBtns.forEach(b => b.classList.remove('active'));
            calculateFees();
        });

        // Network selection
        networkOptions.forEach(option => {
            option.addEventListener('click', function() {
                networkOptions.forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');
                this.querySelector('input').checked = true;
            });
        });

        // Form validation before submit
        document.getElementById('withdrawalForm').addEventListener('submit', function(e) {
            const gross = parseFloat(grossAmountInput.value) || 0;
            const net = parseFloat(netAmountInput.value) || 0;

            if (gross <= 0) {
                e.preventDefault();
                alert('Please enter a valid withdrawal amount.');
                return;
            }

            if (gross > userBalance) {
                e.preventDefault();
                alert('Insufficient balance. Your available balance is ' + currency + userBalance.toFixed(2));
                return;
            }

            if (net <= 0) {
                e.preventDefault();
                alert('The amount after fees must be greater than zero.');
                return;
            }
        });

        // Initial calculation
        calculateFees();
    </script>
@endsection
