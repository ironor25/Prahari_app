@extends('layouts.admin_master')
@section('page-content')
<div class="d-md-flex align-items-center justify-content-between my-5 page-header-breadcrumb">
    <h1 class="page-title fw-bold fs-2 mb-0">Dashboard</h1>
</div>

<div class="row">
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="d-block text-black fs-14 mb-2">Total Prahari</span>
                        <h4 class="fw-semibold mb-0">{{ number_format($totalPrahari) }}</h4>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="d-block text-black fs-14 mb-2">Total Cases</span>
                        <h4 class="fw-semibold mb-0">{{ number_format($totalCases) }}</h4>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="d-block text-black fs-14 mb-2">Total Challans</span>
                        <h4 class="fw-semibold mb-0">{{ number_format($totalChallans) }}</h4>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="d-block text-black fs-14 mb-2">Total Withdrawals</span>
                        <h4 class="fw-semibold mb-0">₹ {{ number_format($totalWithdrawals) }}</h4>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="d-block text-black fs-14 mb-2">Pending Withdrawals</span>
                        <h4 class="fw-semibold mb-0">₹ {{ number_format($totalPendingWithdrawals) }}</h4>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="d-block text-black fs-14 mb-2">Today's Cases</span>
                        <h4 class="fw-semibold mb-0">{{ number_format($todaysCases) }}</h4>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="d-block text-black fs-14 mb-2">Today's Challans</span>
                        <h4 class="fw-semibold mb-0">{{ number_format($todaysChallans) }}</h4>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="d-block text-black fs-14 mb-2">Active Prahari</span>
                        <h4 class="fw-semibold mb-0">{{ number_format($totalActivePrahari) }}</h4>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row mt-4">
    <div class="col-xl-6">
        <div class="card custom-card h-100">
            <div class="card-header">
                <h5 class="card-title">Cases Overview</h5>
            </div>
            <div class="card-body">
                <canvas id="casesOverviewChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card custom-card h-100">
            <div class="card-header">
                <h5 class="card-title">Challan Status</h5>
            </div>
            <div class="card-body d-flex justify-content-center">
                <canvas id="challanStatusChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@push('page-script')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Line Chart: Cases Overview
        var ctxCases = document.getElementById('casesOverviewChart').getContext('2d');
        var caseLabels = {!! json_encode($caseLabels) !!};
        var caseData = {!! json_encode($caseData) !!};

        new Chart(ctxCases, {
            type: 'line',
            data: {
                labels: caseLabels,
                datasets: [{
                    label: 'Number of Cases',
                    data: caseData,
                    borderColor: '#333333',
                    backgroundColor: 'rgba(0, 0, 0, 0.05)',
                    borderWidth: 2,
                    pointBackgroundColor: '#333333',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#333333',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Doughnut Chart: Challan Status
        var ctxChallans = document.getElementById('challanStatusChart').getContext('2d');
        var challanLabels = {!! json_encode($challanLabels) !!};
        var challanData = {!! json_encode($challanData) !!};

        // Logic for dynamic gray shades based on quantity
        var indices = challanData.map((v, i) => i);
        indices.sort((a, b) => challanData[b] - challanData[a]); // Sort indices by data value descending
        var grays = ['#212529', '#495057', '#6c757d', '#adb5bd', '#dee2e6'];
        var sortedColors = new Array(challanData.length);
        indices.forEach((idx, i) => {
            sortedColors[idx] = grays[Math.min(i, grays.length - 1)];
        });

        new Chart(ctxChallans, {
            type: 'doughnut',
            data: {
                labels: challanLabels,
                datasets: [{
                    data: challanData,
                    backgroundColor: sortedColors,
                    hoverBackgroundColor: sortedColors.map(c => c),
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
    });
</script>
@endpush
