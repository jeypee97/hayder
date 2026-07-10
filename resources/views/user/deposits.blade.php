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

    <div class="main-panel deposit-page" data-theme="{{ $bg }}">
        <div class="content">
            <div class="page-inner">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="header-content">
                        <h1 class="page-title">Fund Your Account</h1>
                        <p class="page-subtitle">Add funds to start trading</p>
                    </div>
                    <div class="balance-display">
                        <span class="balance-label">Current Balance</span>
                        <span class="balance-amount">{{ $settings->currency }}{{ number_format(Auth::user()->account_bal, 2) }}</span>
                    </div>
                </div>

                <x-danger-alert/>
                <x-success-alert/>

                <!-- Minimum Deposit Notice -->
                <div class="notice-banner">
                    <div class="notice-icon">
                        <i class="fa fa-info-circle"></i>
                    </div>
                    <div class="notice-content">
                        <span class="notice-title">Minimum Deposit</span>
                        <span class="notice-text">The minimum deposit amount is <strong>{{ $settings->currency }}50</strong>. Deposits below this amount will not be processed.</span>
                    </div>
                </div>

                <div class="deposit-layout">
                    <!-- Main Form -->
                    <div class="deposit-form-card">
                        <form action="javascript:;" method="post" id="submitpaymentform">
                            @csrf
                            <input type="hidden" name="payment_method" id="paymethod">

                            <!-- Amount Input -->
                            <div class="amount-section">
                                <label class="section-label">
                                    <i class="fa fa-coins"></i>
                                    Enter Amount
                                </label>
                                <div class="amount-input-wrapper">
                                    <span class="currency-symbol">{{ $settings->currency }}</span>
                                    <input
                                        type="number"
                                        name="amount"
                                        id="amount"
                                        class="amount-input"
                                        placeholder="0.00"
                                        min="50"
                                        required
                                    >
                                </div>
                                <!-- Quick Amount Buttons -->
                                <div class="quick-amounts">
                                    <button type="button" class="quick-amount-btn" data-amount="50">$50</button>
                                    <button type="button" class="quick-amount-btn" data-amount="100">$100</button>
                                    <button type="button" class="quick-amount-btn" data-amount="250">$250</button>
                                    <button type="button" class="quick-amount-btn" data-amount="500">$500</button>
                                    <button type="button" class="quick-amount-btn" data-amount="1000">$1000</button>
                                </div>
                            </div>

                            <!-- Payment Methods -->
                            <div class="payment-section">
                                <label class="section-label">
                                    <i class="fa fa-wallet"></i>
                                    Select Payment Method
                                </label>

                                @if(count($dmethods) > 0)
                                    <div class="payment-methods-grid">
                                        @foreach ($dmethods as $index => $method)
                                            <div class="payment-method-card {{ $index === 0 ? 'selected' : '' }}"
                                                 data-method="{{ $method->name }}"
                                                 data-id="{{ $method->id }}"
                                                 onclick="selectPaymentMethod(this)">
                                                <div class="method-icon">
                                                    @if (!empty($method->img_url))
                                                        <img src="{{ $method->img_url }}" alt="{{ $method->name }}">
                                                    @else
                                                        @if(strtolower($method->name) == 'bitcoin' || strtolower($method->name) == 'btc')
                                                            <i class="fab fa-bitcoin"></i>
                                                        @elseif(strtolower($method->name) == 'ethereum' || strtolower($method->name) == 'eth')
                                                            <i class="fab fa-ethereum"></i>
                                                        @elseif(str_contains(strtolower($method->name), 'bank'))
                                                            <i class="fa fa-university"></i>
                                                        @elseif(str_contains(strtolower($method->name), 'paypal'))
                                                            <i class="fab fa-paypal"></i>
                                                        @else
                                                            <i class="fa fa-wallet"></i>
                                                        @endif
                                                    @endif
                                                </div>
                                                <span class="method-name">{{ $method->name }}</span>
                                                <div class="method-check">
                                                    <i class="fa fa-check"></i>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit" class="submit-btn" id="submitBtn">
                                        <i class="fa fa-arrow-right"></i>
                                        <span>Proceed to Payment</span>
                                    </button>
                                @else
                                    <div class="no-methods">
                                        <div class="no-methods-icon">
                                            <i class="fa fa-exclamation-circle"></i>
                                        </div>
                                        <h4>No Payment Methods Available</h4>
                                        <p>Payment methods are currently being configured. Please check back later.</p>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Sidebar Info -->
                    <div class="deposit-sidebar">
                        <!-- How It Works -->
                        <div class="info-card">
                            <h4>
                                <i class="fa fa-question-circle"></i>
                                How It Works
                            </h4>
                            <div class="steps-list">
                                <div class="step-item">
                                    <span class="step-number">1</span>
                                    <span class="step-text">Enter the amount you want to deposit</span>
                                </div>
                                <div class="step-item">
                                    <span class="step-number">2</span>
                                    <span class="step-text">Select your preferred payment method</span>
                                </div>
                                <div class="step-item">
                                    <span class="step-number">3</span>
                                    <span class="step-text">Complete the payment on the next page</span>
                                </div>
                                <div class="step-item">
                                    <span class="step-number">4</span>
                                    <span class="step-text">Funds will be credited once confirmed</span>
                                </div>
                            </div>
                        </div>

                        <!-- Security Info -->
                        <div class="info-card security">
                            <h4>
                                <i class="fa fa-shield-alt"></i>
                                Secure Payments
                            </h4>
                            <p>All transactions are encrypted and secured. Your payment information is never stored on our servers.</p>
                            <div class="security-badges">
                                <span class="badge"><i class="fa fa-lock"></i> SSL Encrypted</span>
                                <span class="badge"><i class="fa fa-check-circle"></i> Verified</span>
                            </div>
                        </div>

                        <!-- Need Help -->
                        <div class="info-card help">
                            <h4>
                                <i class="fa fa-headset"></i>
                                Need Help?
                            </h4>
                            <p>Having trouble with your deposit? Our support team is here to help.</p>
                            <a href="{{ route('support') }}" class="help-link">
                                Contact Support
                                <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .deposit-page {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* Theme Variables */
        .deposit-page[data-theme="dark"] {
            --bg-primary: #0a0a0f;
            --bg-card: rgba(18, 18, 26, 0.9);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #7d8ba3;
            --border-color: rgba(99, 102, 241, 0.15);
            --input-bg: #12121a;
            --hover-bg: rgba(99, 102, 241, 0.08);
        }

        .deposit-page[data-theme="light"] {
            --bg-primary: #f8fafc;
            --bg-card: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --input-bg: #f1f5f9;
            --hover-bg: rgba(99, 102, 241, 0.05);
        }

        .deposit-page .content {
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
            color: var(--text-primary);
        }

        /* Notice Banner */
        .notice-banner {
            display: flex;
            align-items: center;
            gap: 14px;
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.3);
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
        }

        .notice-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(245, 158, 11, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f59e0b;
            font-size: 18px;
            flex-shrink: 0;
        }

        .notice-content {
            display: flex;
            flex-direction: column;
        }

        .notice-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: #f59e0b;
        }

        .notice-text {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .notice-text strong {
            color: var(--text-primary);
        }

        /* Layout */
        .deposit-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 24px;
            align-items: start;
        }

        @media (max-width: 1024px) {
            .deposit-layout {
                grid-template-columns: 1fr;
            }
        }

        /* Form Card */
        .deposit-form-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 28px;
        }

        .section-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 16px;
        }

        .section-label i {
            color: #6366f1;
        }

        /* Amount Section */
        .amount-section {
            margin-bottom: 32px;
            padding-bottom: 28px;
            border-bottom: 1px solid var(--border-color);
        }

        .amount-input-wrapper {
            position: relative;
            margin-bottom: 16px;
        }

        .currency-symbol {
            position: absolute;
            left: 1px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        .amount-input {
            width: 100%;
            background: var(--input-bg);
            border: 2px solid var(--border-color);
            border-radius: 14px;
            padding: 20px 20px 20px 50px;
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            transition: all 0.2s ease;
        }

        .amount-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        }

        .amount-input::placeholder {
            color: var(--text-muted);
            font-weight: 400;
        }

        /* Quick Amounts */
        .quick-amounts {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .quick-amount-btn {
            padding: 10px 18px;
            background: var(--hover-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .quick-amount-btn:hover {
            border-color: #6366f1;
            color: #6366f1;
            background: rgba(99, 102, 241, 0.1);
        }

        .quick-amount-btn.active {
            background: #6366f1;
            border-color: #6366f1;
            color: white;
        }

        /* Payment Methods Grid */
        .payment-methods-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 12px;
            margin-bottom: 24px;
        }

        .payment-method-card {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            padding: 20px 16px;
            background: var(--input-bg);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .payment-method-card:hover {
            border-color: rgba(99, 102, 241, 0.5);
        }

        .payment-method-card.selected {
            border-color: #6366f1;
            background: rgba(99, 102, 241, 0.1);
        }

        .method-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: var(--bg-card);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--text-secondary);
        }

        .method-icon img {
            width: 32px;
            height: 32px;
            object-fit: contain;
        }

        .payment-method-card.selected .method-icon {
            color: #6366f1;
        }

        .method-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-primary);
            text-align: center;
        }

        .method-check {
            position: absolute;
            top: 8px;
            right: 8px;
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

        .payment-method-card.selected .method-check {
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
        }

        .submit-btn:hover {
            background: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -8px rgba(99, 102, 241, 0.5);
        }

        /* No Methods */
        .no-methods {
            text-align: center;
            padding: 40px 20px;
        }

        .no-methods-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: #ef4444;
            font-size: 24px;
        }

        .no-methods h4 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 8px;
        }

        .no-methods p {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin: 0;
        }

        /* Sidebar */
        .deposit-sidebar {
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
            margin: 0 0 14px;
        }

        .info-card h4 i {
            color: #6366f1;
        }

        .info-card p {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin: 0;
            line-height: 1.5;
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

        /* Security Badges */
        .security-badges {
            display: flex;
            gap: 10px;
            margin-top: 14px;
            flex-wrap: wrap;
        }

        .security-badges .badge {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: rgba(16, 185, 129, 0.1);
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #10b981;
        }

        /* Help Link */
        .help-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 12px;
            color: #6366f1;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: gap 0.2s ease;
        }

        .help-link:hover {
            gap: 10px;
            color: #4f46e5;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .deposit-form-card {
                padding: 20px;
            }

            .amount-input {
                font-size: 1.5rem;
                padding: 18px 18px 18px 45px;
            }

            .currency-symbol {
                font-size: 1.25rem;
                left: 1px;
            }

            .payment-methods-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .balance-display {
                width: 100%;
                align-items: flex-start;
            }
        }
    </style>

    <script>
        let paymethod = document.querySelector('#paymethod');
        const amountInput = document.getElementById('amount');
        const quickAmountBtns = document.querySelectorAll('.quick-amount-btn');

        // Quick amount buttons
        quickAmountBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const amount = this.dataset.amount;
                amountInput.value = amount;

                // Update active state
                quickAmountBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Remove active class when typing custom amount
        amountInput.addEventListener('input', function() {
            quickAmountBtns.forEach(b => b.classList.remove('active'));
        });

        // Select payment method
        function selectPaymentMethod(element) {
            const methodCards = document.querySelectorAll('.payment-method-card');
            methodCards.forEach(card => card.classList.remove('selected'));
            element.classList.add('selected');

            const methodId = element.dataset.id;
            const methodName = element.dataset.method;

            let url = "{{ url('/dashboard/get-method/') }}" + '/' + methodId;
            fetch(url)
                .then(res => res.json())
                .then(response => {
                    paymethod.value = response;
                    $.notify({
                        icon: 'fa fa-check-circle',
                        title: 'Payment Method Selected',
                        message: 'You have selected ' + response,
                    }, {
                        type: 'success',
                        placement: { from: "top", align: "right" },
                        delay: 3000,
                        animate: {
                            enter: 'animated fadeInDown',
                            exit: 'animated fadeOutUp'
                        },
                    });
                })
                .catch(err => console.log(err));
        }

        // Auto-select first method on load
        document.addEventListener('DOMContentLoaded', function() {
            const firstMethod = document.querySelector('.payment-method-card');
            if (firstMethod) {
                selectPaymentMethod(firstMethod);
            }
        });

        // Form submission
        $('#submitpaymentform').on('submit', function(e) {
            const amount = parseFloat(amountInput.value);

            if (paymethod.value === "") {
                e.preventDefault();
                $.notify({
                    icon: 'fa fa-exclamation-circle',
                    title: 'Select Payment Method',
                    message: 'Please select a payment method to continue',
                }, {
                    type: 'danger',
                    placement: { from: "top", align: "right" },
                    delay: 4000,
                    animate: {
                        enter: 'animated fadeInDown',
                        exit: 'animated fadeOutUp'
                    },
                });
            } else if (!amount || amount < 50) {
                e.preventDefault();
                $.notify({
                    icon: 'fa fa-exclamation-circle',
                    title: 'Invalid Amount',
                    message: 'Minimum deposit amount is $50',
                }, {
                    type: 'danger',
                    placement: { from: "top", align: "right" },
                    delay: 4000,
                    animate: {
                        enter: 'animated fadeInDown',
                        exit: 'animated fadeOutUp'
                    },
                });
            } else {
                document.getElementById("submitpaymentform").action = "{{ url('/dashboard/newdeposit') }}";
            }
        });
    </script>
@endsection
