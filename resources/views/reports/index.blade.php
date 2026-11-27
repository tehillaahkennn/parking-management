
@extends('layouts.parking')

@section('title', 'Reports Dashboard | Parking Management System')

@section('content')

<style>
    .report-heading {
        margin-bottom: 25px;
    }

    .report-heading h2 {
        margin: 0 0 7px;
        color: #172b4d;
        font-size: 24px;
    }

    .report-heading p {
        color: #7b8495;
        margin: 0;
        font-size: 14px;
    }

    .report-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .report-stat {
        background: white;
        padding: 23px;
        border-radius: 12px;
        box-shadow: 0 3px 15px rgba(0,0,0,.04);
        border: 1px solid #edf0f5;
    }

    .report-stat .stat-label {
        color: #7b8495;
        font-size: 13px;
        margin-bottom: 12px;
    }

    .report-stat h3 {
        color: #172b4d;
        font-size: 27px;
        margin: 0;
    }

    .report-stat .stat-icon {
        font-size: 25px;
        margin-bottom: 12px;
    }

    .report-links {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
    }

    .report-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        border: 1px solid #edf0f5;
        box-shadow: 0 3px 15px rgba(0,0,0,.04);
    }

    .report-card h3 {
        color: #172b4d;
        margin: 0 0 10px;
        font-size: 18px;
    }

    .report-card p {
        color: #7b8495;
        font-size: 13px;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .report-btn {
        display: inline-block;
        padding: 10px 17px;
        background: #0866d9;
        color: white;
        border-radius: 7px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
    }

    .report-btn:hover {
        background: #0754b3;
        color: white;
    }

    @media(max-width: 600px) {
        .report-stats,
        .report-links {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="report-heading">
    <h2>Reports Dashboard</h2>
    <p>Overview of parking activities and payment transactions.</p>
</div>

<div class="report-stats">

    <div class="report-stat">
        <div class="stat-icon">🚗</div>
        <div class="stat-label">Total Parking Records</div>
        <h3>{{ number_format($totalRecords) }}</h3>
    </div>

    <div class="report-stat">
        <div class="stat-icon">✅</div>
        <div class="stat-label">Completed Parking</div>
        <h3>{{ number_format($completedRecords) }}</h3>
    </div>

    <div class="report-stat">
        <div class="stat-icon">🅿️</div>
        <div class="stat-label">Currently Parked</div>
        <h3>{{ number_format($currentlyParked) }}</h3>
    </div>

    <div class="report-stat">
        <div class="stat-icon">💰</div>
        <div class="stat-label">Total Revenue</div>
        <h3>₱{{ number_format($totalRevenue, 2) }}</h3>
    </div>

</div>

<div class="report-heading">
    <h2>Available Reports</h2>
    <p>Select a report to view detailed records.</p>
</div>

<div class="report-links">

    <div class="report-card">
        <h3>🚘 Parking Report</h3>
        <p>
            View parking records, vehicle information, parking slots,
            entry times, exit times, and parking statuses.
        </p>

        <a href="{{ route('reports.parking') }}" class="report-btn">
            View Parking Report →
        </a>
    </div>

    <div class="report-card">
        <h3>💳 Payment Report</h3>
        <p>
            Review payment transactions, payment methods, dates,
            amounts, and total revenue collected.
        </p>

        <a href="{{ route('reports.payments') }}" class="report-btn">
            View Payment Report →
        </a>
    </div>

</div>

@endsection
