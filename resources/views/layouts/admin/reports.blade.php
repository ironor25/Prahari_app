@extends('layouts.admin_master')
@section('page-content')



<div class="d-md-flex align-items-center justify-content-between my-3 page-header-breadcrumb">
    <h1 class="page-title fw-bold fs-2 mb-0">Reports</h1>
    <div class="ms-md-1 ms-0">
        <select class="form-select border-1 border-secondary rounded-2">
            <option>01 May 2024 - 31 May 2024</option>
            <option>01 Apr 2024 - 30 Apr 2024</option>
            <option>This Year</option>
        </select>
    </div>
</div>

<!-- Metrics Row -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card custom-card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <p class="fs-14 fw-semibold text-muted mb-2">Total Cases</p>
                <h2 class="fw-bold text-dark mb-0">{{ number_format($totalCases) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card custom-card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <p class="fs-14 fw-semibold text-muted mb-2">Total Challans</p>
                <h2 class="fw-bold text-dark mb-0">{{ number_format($totalChallans) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card custom-card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <p class="fs-14 fw-semibold text-muted mb-2">Total Revenue</p>
                <h2 class="fw-bold text-dark mb-0">₹ {{ number_format($totalRevenue, 0) }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Graphs Row -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card custom-card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold text-dark mb-4">Challan Trend (Monthly)</h6>
                <div style="height: 300px;">
                    <canvas id="challanTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card custom-card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold text-dark mb-4">Revenue Trend</h6>
                <div style="height: 300px;">
                    <canvas id="revenueTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@push('page-script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Challan Trend Chart
        const challanTrendCtx = document.getElementById('challanTrendChart').getContext('2d');
        new Chart(challanTrendCtx, {
            type: 'line',
            data: {
                labels: @json($challanTrendLabels),
                datasets: [{
                    label: 'Challans',
                    data: @json($challanTrendData),
                    borderColor: '#2c3e50',
                    backgroundColor: 'rgba(44, 62, 80, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#2c3e50',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });

        // Revenue Trend Chart
        const revenueTrendCtx = document.getElementById('revenueTrendChart').getContext('2d');
        new Chart(revenueTrendCtx, {
            type: 'bar',
            data: {
                labels: @json($revenueTrendLabels),
                datasets: [{
                    label: 'Revenue (₹)',
                    data: @json($revenueTrendData),
                    backgroundColor: '#2c3e50',
                    borderColor: '#2c3e50',
                    borderWidth: 1,
                    borderRadius: 5,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₹' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
