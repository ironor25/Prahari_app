@extends('layouts.admin_master')
@push('page-style')
<style>
    .nav-tabs-header .nav-link {
        border: none !important;
        background: transparent !important;
        color: #8c90a7 !important;
        font-weight: 500;
        padding: 12px 20px !important;
        position: relative;
        transition: all 0.3s ease;
    }
    .nav-tabs-header .nav-link.active {
        color: #2c3e50 !important;
    }
    .nav-tabs-header .nav-link.active::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: #2c3e50;
        border-radius: 2px 2px 0 0;
        transition: all 0.3s ease;
    }
    .card-header-tabs {
        border-bottom: 1px solid #e9ecef;
    }
</style>
@endpush
@section('page-content')
<div class="d-md-flex align-items-center justify-content-between my-3 page-header-breadcrumb">
    <h1 class="page-title fw-bold fs-2 mb-0">Payments</h1>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between pb-0 border-bottom-0">
                <div class="card-title">
                    Payments / Withdrawals
                </div>
            </div>
            <div class="card-header-tabs px-4">
                <ul class="nav nav-tabs nav-tabs-header mb-0 border-bottom-0" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="javascript:void(0);" role="tab" onclick="filterTransactions('history')">All Transactions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="javascript:void(0);" role="tab" onclick="filterTransactions('requests')">Withdrawal Requests</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <!-- Custom Controls -->
                <div class="custom-table-controls">
                    <div class="custom-search-container">
                        <i class="bi bi-search"></i>
                        <input type="text" class="custom-search-input" id="customSearchInput" placeholder="Search by Request ID, Prahari, Bank Account, etc...">
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-light border text-muted d-flex align-items-center px-3 py-2 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-filter fs-5 me-2" style="line-height: 1;"></i> Filter
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item sort-item" href="javascript:void(0);" data-col="1" data-dir="asc">Request ID (A-Z)</a></li>
                            <li><a class="dropdown-item sort-item" href="javascript:void(0);" data-col="2" data-dir="asc">Prahari (A-Z)</a></li>
                            <li><a class="dropdown-item sort-item" href="javascript:void(0);" data-col="3" data-dir="desc">Amount (High-Low)</a></li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap w-100" id="transactionsTable">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Request ID</th>
                                <th>Prahari</th>
                                <th>Amount</th>
                                <th>Bank Account</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page-script')
<script>
    let currentFilter = 'history';
    let $table;

    $(document).ready(function() {
        $table = $('#transactionsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.transactions.index') }}",
                data: function(d) {
                    d.type = currentFilter;
                }
            },
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'csv',
                    text: 'Export CSV',
                    className: 'csv-export-btn',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }
            ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'withdrawal_id',
                        name: 'withdrawal_id'
                    },
                    {
                        data: 'prahari_name',
                        name: 'prahari.name'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'bank_account',
                        name: 'bank_account'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $table.column(6).visible(false);

            // Custom Search functionality
            let searchTimer;
            $('#customSearchInput').on('keyup', function() {
                clearTimeout(searchTimer);
                const val = this.value;
                searchTimer = setTimeout(function() {
                    $table.search(val).draw();
                }, 300);
            });

            // Custom Sort functionality
            $(document).on('click', '.sort-item', function() {
                let col = $(this).data('col');
                let dir = $(this).data('dir');
                $table.order([col, dir]).draw();
            });

            $('body').on('click', '.approveBtn', function() {
                updateStatus($(this).data('id'), 'Approved');
            });

            $('body').on('click', '.rejectBtn', function() {
                updateStatus($(this).data('id'), 'Rejected');
            });

            function updateStatus(id, status) {
                showConfirm("Confirm Action", "Are you sure you want to " + status + " this request?", function() {
                    $.ajax({
                        url: "{{ route('admin.transactions.index') }}/" + id + "/status",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            status: status
                        },
                        success: function(data) {
                            $table.ajax.reload(null, false);
                            showToast(data.success);
                        },
                        error: function(data) {
                            console.log('Error:', data);
                            showToast('Failed to update status', 'error');
                        }
                    });
                });
            }
        });

        function filterTransactions(type) {
            currentFilter = type;

            // Clear existing data immediately to avoid "ghost" data while loading new content
            $('#transactionsTable tbody').empty();

            if (type === 'requests') {
                $table.column(6).visible(true);
            } else {
                $table.column(6).visible(false);
            }
            $table.ajax.reload();
        }
    </script>
@endpush
