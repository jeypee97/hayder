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

    <div class="main-panel referral-page" data-theme="{{ $bg }}">
        <div class="content">
            <div class="page-inner">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="header-content">
                        <h1 class="page-title">Refer &amp; Earn</h1>
                        <p class="page-subtitle">Invite friends and earn commissions on their activity</p>
                    </div>

                </div>

                <x-danger-alert/>
                <x-success-alert/>

                <!-- Hero Banner -->
                

                <!-- Main Layout -->
                <div class="referral-layout">

                    <!-- Left Column -->
                    <div class="referral-main">

                        <!-- Referral Link Card -->
                        <div class="ref-card">
                            <div class="ref-card-header">
                                <h3>
                                    <i class="fa fa-link"></i>
                                    Your Referral Link
                                </h3>
                            </div>
                            <div class="ref-card-body">
                                <p class="ref-card-desc">Share this link with friends. When they sign up and deposit, you earn a commission automatically.</p>

                                <div class="link-copy-wrapper">
                                    <input
                                        type="text"
                                        id="referralLink"
                                        class="link-input"
                                        value="{{ $referralLink }}"
                                        readonly
                                    >
                                    <button class="copy-btn" onclick="copyLink()" id="copyBtn">
                                        <i class="fa fa-copy" id="copyIcon"></i>
                                        <span id="copyText">Copy</span>
                                    </button>
                                </div>

                                <div class="share-section">
                                    <span class="share-label">Share via</span>
                                    <div class="share-buttons">
                                        <a href="https://wa.me/?text=Join%20{{ urlencode($settings->site_name) }}%20and%20start%20trading%20today%21%20Use%20my%20link%3A%20{{ urlencode($referralLink) }}" target="_blank" class="share-btn whatsapp">
                                            <i class="fab fa-whatsapp"></i>
                                            WhatsApp
                                        </a>
                                        <a href="https://t.me/share/url?url={{ urlencode($referralLink) }}&text=Join%20{{ urlencode($settings->site_name) }}%20and%20start%20trading%21" target="_blank" class="share-btn telegram">
                                            <i class="fab fa-telegram-plane"></i>
                                            Telegram
                                        </a>
                                        <a href="https://twitter.com/intent/tweet?text=Join%20{{ urlencode($settings->site_name) }}%20and%20start%20trading%20crypto%21%20Use%20my%20referral%20link%3A%20{{ urlencode($referralLink) }}" target="_blank" class="share-btn twitter">
                                            <i class="fab fa-twitter"></i>
                                            Twitter
                                        </a>
                                        <a href="mailto:?subject=Join%20{{ urlencode($settings->site_name) }}&body=Hey%2C%20I%20think%20you%27d%20love%20this%20trading%20platform.%20Use%20my%20link%20to%20sign%20up%3A%20{{ $referralLink }}" class="share-btn email">
                                            <i class="fa fa-envelope"></i>
                                            Email
                                        </a>
                                    </div>
                                </div>

                                <div class="ref-code-row">
                                    <span class="ref-code-label">Your referral code:</span>
                                    <span class="ref-code-badge">{{ Auth::user()->username }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- How It Works -->
                        <div class="ref-card">
                            <div class="ref-card-header">
                                <h3>
                                    <i class="fa fa-info-circle"></i>
                                    How It Works
                                </h3>
                            </div>
                            <div class="ref-card-body">
                                <div class="steps-grid">
                                    <div class="step">
                                        <div class="step-number">1</div>
                                        <div class="step-content">
                                            <h4>Share Your Link</h4>
                                            <p>Copy your unique referral link and share it with friends, family, or on social media.</p>
                                        </div>
                                    </div>
                                    <div class="step">
                                        <div class="step-number">2</div>
                                        <div class="step-content">
                                            <h4>Friends Sign Up</h4>
                                            <p>Your friends register using your link. They're automatically linked to your account.</p>
                                        </div>
                                    </div>
                                    <div class="step">
                                        <div class="step-number">3</div>
                                        <div class="step-content">
                                            <h4>They Deposit &amp; Trade</h4>
                                            <p>When your referred users make a deposit, the commission is triggered immediately.</p>
                                        </div>
                                    </div>
                                    <div class="step">
                                        <div class="step-number">4</div>
                                        <div class="step-content">
                                            <h4>You Earn</h4>
                                            <p>Your referral bonus is credited to your account balance automatically — no waiting.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Referred Users Table -->
                        <div class="ref-card">
                            <div class="ref-card-header">
                                <h3>
                                    <i class="fa fa-users"></i>
                                    Your Referrals
                                </h3>
                                <span class="badge-count">{{ $refs->count() }}</span>
                            </div>
                            <div class="ref-card-body no-padding">
                                @if($refs->isEmpty())
                                    <div class="empty-referrals">
                                        <div class="empty-icon">
                                            <i class="fa fa-user-friends"></i>
                                        </div>
                                        <h4>No referrals yet</h4>
                                        <p>Share your referral link above to start earning commissions when friends join and deposit.</p>
                                        <button class="copy-link-cta" onclick="copyLink()">
                                            <i class="fa fa-copy"></i>
                                            Copy Referral Link
                                        </button>
                                    </div>
                                @else
                                    <div class="referrals-table-wrapper">
                                        <table class="referrals-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Joined</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($refs as $index => $ref)
                                                    <tr>
                                                        <td class="row-num">{{ $index + 1 }}</td>
                                                        <td>
                                                            <div class="ref-user">
                                                                <div class="ref-avatar">{{ strtoupper(substr($ref->name, 0, 1)) }}</div>
                                                                <span class="ref-name">{{ $ref->name }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="ref-email">{{ $ref->email }}</td>
                                                        <td class="ref-date">{{ $ref->created_at->format('M d, Y') }}</td>
                                                        <td>
                                                            @if($ref->account_bal > 0)
                                                                <span class="status-badge active">Active</span>
                                                            @else
                                                                <span class="status-badge pending">Pending</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>

                    <!-- Right Column (Sidebar) -->
                    <div class="referral-sidebar">

                        <!-- Commission Structure -->
                        <div class="ref-card">
                            <div class="ref-card-header">
                                <h3>
                                    <i class="fa fa-percentage"></i>
                                    Commission Rates
                                </h3>
                            </div>
                            <div class="ref-card-body">
                                <p class="commission-desc">Earn commissions on your referrals' deposits across multiple levels.</p>

                                @php
                                    $levels = [
                                        ['label' => 'Level 1', 'rate' => $settings->referral_commission1 ?? 0, 'desc' => 'Direct referrals'],
                                        ['label' => 'Level 2', 'rate' => $settings->referral_commission2 ?? 0, 'desc' => "Your referrals' referrals"],
                                        ['label' => 'Level 3', 'rate' => $settings->referral_commission3 ?? 0, 'desc' => '3rd-tier referrals'],
                                        ['label' => 'Level 4', 'rate' => $settings->referral_commission4 ?? 0, 'desc' => '4th-tier referrals'],
                                        ['label' => 'Level 5', 'rate' => $settings->referral_commission5 ?? 0, 'desc' => '5th-tier referrals'],
                                    ];
                                @endphp

                                <div class="commission-levels">
                                    @foreach($levels as $level)
                                        @if($level['rate'] > 0)
                                            <div class="commission-level">
                                                <div class="level-info">
                                                    <span class="level-label">{{ $level['label'] }}</span>
                                                    <span class="level-desc">{{ $level['desc'] }}</span>
                                                </div>
                                                <span class="level-rate">{{ $level['rate'] }}%</span>
                                            </div>
                                        @endif
                                    @endforeach
                                    @php
                                        $hasRates = collect($levels)->where('rate', '>', 0)->isNotEmpty();
                                    @endphp
                                    @if(!$hasRates)
                                        <div class="no-rates">
                                            <i class="fa fa-info-circle"></i>
                                            Referral commission rates will appear here once configured.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Stats Card -->
                        <div class="ref-card">
                            <div class="ref-card-header">
                                <h3>
                                    <i class="fa fa-chart-pie"></i>
                                    Your Stats
                                </h3>
                            </div>
                            <div class="ref-card-body">
                                <div class="stats-list">
                                    <div class="stats-row">
                                        <div class="stats-row-icon referrals">
                                            <i class="fa fa-users"></i>
                                        </div>
                                        <div class="stats-row-content">
                                            <span class="stats-row-label">Total Referrals</span>
                                            <span class="stats-row-value">{{ $refs->count() }}</span>
                                        </div>
                                    </div>
                                    <div class="stats-row">
                                        <div class="stats-row-icon active-users">
                                            <i class="fa fa-user-check"></i>
                                        </div>
                                        <div class="stats-row-content">
                                            <span class="stats-row-label">Active Users</span>
                                            <span class="stats-row-value">{{ $refs->where('account_bal', '>', 0)->count() }}</span>
                                        </div>
                                    </div>
                                    <div class="stats-row">
                                        <div class="stats-row-icon earnings">
                                            <i class="fa fa-coins"></i>
                                        </div>
                                        <div class="stats-row-content">
                                            <span class="stats-row-label">Total Bonus Earned</span>
                                            <span class="stats-row-value positive">{{ $settings->currency }}{{ number_format(Auth::user()->ref_bonus ?? 0, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tips Card -->
                        <div class="ref-card tips-card">
                            <div class="ref-card-header">
                                <h3>
                                    <i class="fa fa-lightbulb"></i>
                                    Tips to Maximise Earnings
                                </h3>
                            </div>
                            <div class="ref-card-body">
                                <ul class="tips-list">
                                    <li>
                                        <i class="fa fa-check-circle"></i>
                                        Share on social media platforms for wider reach
                                    </li>
                                    <li>
                                        <i class="fa fa-check-circle"></i>
                                        Send your link directly to interested contacts
                                    </li>
                                    <li>
                                        <i class="fa fa-check-circle"></i>
                                        Encourage referrals to deposit sooner to activate commission
                                    </li>
                                    <li>
                                        <i class="fa fa-check-circle"></i>
                                        Multi-level commissions mean deeper networks earn more
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        /* ============================================
           REFERRAL PAGE STYLES
           ============================================ */

        .referral-page[data-theme="dark"] {
            --bg-primary: #0a0a0f;
            --bg-card: rgba(18, 18, 26, 0.95);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --border-color: rgba(99, 102, 241, 0.15);
            --input-bg: #12121a;
            --hover-bg: rgba(99, 102, 241, 0.08);
        }

        .referral-page[data-theme="light"] {
            --bg-primary: #f8fafc;
            --bg-card: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --input-bg: #f1f5f9;
            --hover-bg: rgba(99, 102, 241, 0.05);
        }

        .referral-page .content {
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

        .ref-bonus-display {
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

        /* Hero Banner */
        .referral-hero {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 50%, #7c3aed 100%);
            border-radius: 16px;
            padding: 32px 36px;
            margin-bottom: 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 24px;
            flex-wrap: wrap;
            position: relative;
            overflow: hidden;
        }

        .referral-hero::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
        }

        .referral-hero::after {
            content: '';
            position: absolute;
            bottom: -60px;
            left: 30%;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.04);
        }

        .hero-content {
            display: flex;
            align-items: center;
            gap: 20px;
            z-index: 1;
        }

        .hero-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: white;
            flex-shrink: 0;
        }

        .hero-text h2 {
            font-size: 1.35rem;
            font-weight: 700;
            color: white;
            margin: 0 0 6px;
        }

        .hero-text p {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
            max-width: 380px;
            line-height: 1.5;
        }

        .hero-stats {
            display: flex;
            align-items: center;
            gap: 0;
            z-index: 1;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            overflow: hidden;
        }

        .hero-stat {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 18px 28px;
        }

        .hero-stat-value {
            font-size: 1.4rem;
            font-weight: 800;
            color: white;
            line-height: 1;
            margin-bottom: 4px;
        }

        .hero-stat-label {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .hero-stat-divider {
            width: 1px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
        }

        /* Layout */
        .referral-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 24px;
            align-items: start;
        }

        @media (max-width: 1100px) {
            .referral-layout {
                grid-template-columns: 1fr;
            }
            .referral-sidebar {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 24px;
            }
            .referral-sidebar .ref-card {
                margin-bottom: 0;
            }
        }

        @media (max-width: 700px) {
            .referral-sidebar {
                grid-template-columns: 1fr;
            }
            .hero-stats {
                width: 100%;
            }
            .hero-content {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        /* Cards */
        .ref-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .ref-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
        }

        .ref-card-header h3 {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .ref-card-header h3 i {
            color: #6366f1;
            font-size: 15px;
        }

        .badge-count {
            background: rgba(99, 102, 241, 0.12);
            color: #6366f1;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .ref-card-body {
            padding: 24px;
        }

        .ref-card-body.no-padding {
            padding: 0;
        }

        .ref-card-desc {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin: 0 0 20px;
            line-height: 1.6;
        }

        /* Referral Link Input */
        .link-copy-wrapper {
            display: flex;
            gap: 0;
            margin-bottom: 20px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            transition: border-color 0.2s;
        }

        .link-copy-wrapper:focus-within {
            border-color: #6366f1;
        }

        .link-input {
            flex: 1;
            background: var(--input-bg);
            border: none;
            padding: 14px 18px;
            font-size: 0.875rem;
            color: var(--text-primary);
            outline: none;
            font-family: 'Inter', monospace;
            min-width: 0;
        }

        .copy-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 14px 22px;
            background: #6366f1;
            color: white;
            border: none;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease;
            white-space: nowrap;
            font-family: 'Inter', sans-serif;
        }

        .copy-btn:hover {
            background: #4f46e5;
        }

        .copy-btn.copied {
            background: #10b981;
        }

        /* Share Buttons */
        .share-section {
            margin-bottom: 20px;
        }

        .share-label {
            display: block;
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .share-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .share-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            border-radius: 9px;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .share-btn.whatsapp {
            background: rgba(37, 211, 102, 0.1);
            color: #25d366;
            border: 1px solid rgba(37, 211, 102, 0.25);
        }

        .share-btn.whatsapp:hover {
            background: #25d366;
            color: white;
        }

        .share-btn.telegram {
            background: rgba(0, 136, 204, 0.1);
            color: #0088cc;
            border: 1px solid rgba(0, 136, 204, 0.25);
        }

        .share-btn.telegram:hover {
            background: #0088cc;
            color: white;
        }

        .share-btn.twitter {
            background: rgba(29, 161, 242, 0.1);
            color: #1da1f2;
            border: 1px solid rgba(29, 161, 242, 0.25);
        }

        .share-btn.twitter:hover {
            background: #1da1f2;
            color: white;
        }

        .share-btn.email {
            background: rgba(99, 102, 241, 0.1);
            color: #6366f1;
            border: 1px solid rgba(99, 102, 241, 0.25);
        }

        .share-btn.email:hover {
            background: #6366f1;
            color: white;
        }

        /* Referral Code Badge */
        .ref-code-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            background: var(--input-bg);
            border-radius: 10px;
        }

        .ref-code-label {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .ref-code-badge {
            background: rgba(99, 102, 241, 0.12);
            color: #6366f1;
            font-size: 0.875rem;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 6px;
            letter-spacing: 0.5px;
            font-family: monospace;
        }

        /* How It Works Steps */
        .steps-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 600px) {
            .steps-grid {
                grid-template-columns: 1fr;
            }
        }

        .step {
            display: flex;
            gap: 14px;
            align-items: flex-start;
        }

        .step-number {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
            font-size: 0.9rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .step-content h4 {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 6px;
        }

        .step-content p {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin: 0;
            line-height: 1.5;
        }

        /* Referrals Table */
        .referrals-table-wrapper {
            overflow-x: auto;
        }

        .referrals-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        .referrals-table th {
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

        .referrals-table td {
            padding: 16px 20px;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .referrals-table tbody tr:last-child td {
            border-bottom: none;
        }

        .referrals-table tbody tr:hover td {
            background: var(--hover-bg);
        }

        .row-num {
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .ref-user {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .ref-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
            font-size: 0.85rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .ref-name {
            font-weight: 500;
            color: var(--text-primary);
        }

        .ref-email {
            color: var(--text-muted);
            font-size: 0.82rem;
        }

        .ref-date {
            font-size: 0.82rem;
            white-space: nowrap;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
        }

        .status-badge.active {
            background: rgba(16, 185, 129, 0.12);
            color: #10b981;
        }

        .status-badge.pending {
            background: rgba(245, 158, 11, 0.12);
            color: #f59e0b;
        }

        /* Empty State */
        .empty-referrals {
            text-align: center;
            padding: 60px 40px;
        }

        .empty-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(99, 102, 241, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: #6366f1;
            font-size: 30px;
        }

        .empty-referrals h4 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 10px;
        }

        .empty-referrals p {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin: 0 0 24px;
            line-height: 1.6;
            max-width: 360px;
            margin-left: auto;
            margin-right: auto;
        }

        .copy-link-cta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: #6366f1;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease;
            font-family: 'Inter', sans-serif;
        }

        .copy-link-cta:hover {
            background: #4f46e5;
        }

        /* Sidebar: Commission Levels */
        .commission-desc {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin: 0 0 18px;
            line-height: 1.5;
        }

        .commission-levels {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .commission-level {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background: var(--input-bg);
            border-radius: 10px;
            border: 1px solid var(--border-color);
        }

        .level-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-primary);
            display: block;
            margin-bottom: 2px;
        }

        .level-desc {
            font-size: 0.775rem;
            color: var(--text-muted);
        }

        .level-rate {
            font-size: 1.1rem;
            font-weight: 700;
            color: #10b981;
        }

        .no-rates {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 16px;
            background: var(--input-bg);
            border-radius: 10px;
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        /* Stats List */
        .stats-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .stats-row {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .stats-row-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .stats-row-icon.referrals {
            background: rgba(99, 102, 241, 0.12);
            color: #6366f1;
        }

        .stats-row-icon.active-users {
            background: rgba(16, 185, 129, 0.12);
            color: #10b981;
        }

        .stats-row-icon.earnings {
            background: rgba(245, 158, 11, 0.12);
            color: #f59e0b;
        }

        .stats-row-content {
            display: flex;
            flex-direction: column;
        }

        .stats-row-label {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .stats-row-value {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .stats-row-value.positive {
            color: #10b981;
        }

        /* Tips Card */
        .tips-card .ref-card-header h3 i {
            color: #f59e0b;
        }

        .tips-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .tips-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 0.875rem;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        .tips-list li i {
            color: #10b981;
            font-size: 14px;
            margin-top: 2px;
            flex-shrink: 0;
        }
    </style>

    <script>
        function copyLink() {
            var input = document.getElementById('referralLink');
            var btn = document.getElementById('copyBtn');
            var icon = document.getElementById('copyIcon');
            var text = document.getElementById('copyText');

            if (navigator.clipboard) {
                navigator.clipboard.writeText(input.value).then(function() {
                    showCopied(btn, icon, text);
                });
            } else {
                input.select();
                document.execCommand('copy');
                showCopied(btn, icon, text);
            }
        }

        function showCopied(btn, icon, text) {
            btn.classList.add('copied');
            icon.className = 'fa fa-check';
            text.textContent = 'Copied!';
            setTimeout(function() {
                btn.classList.remove('copied');
                icon.className = 'fa fa-copy';
                text.textContent = 'Copy';
            }, 2500);
        }
    </script>

@endsection
