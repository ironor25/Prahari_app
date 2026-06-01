@extends('layouts.admin_master')
@section('page-content')
<div class="d-md-flex align-items-center justify-content-between my-3 page-header-breadcrumb">
    <h1 class="page-title fw-bold fs-2 mb-0">Admins</h1>
    <button class="btn btn-dark btn-wave addBtn" data-bs-toggle="modal" data-bs-target="#adminModal" style="background-color: #2c3e50; border: none; padding: 10px 20px; font-weight: 500;">
        <i class="bi bi-plus-lg me-1"></i> Add Admin
    </button>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-body">
                <!-- Custom Controls -->
                <div class="custom-table-controls">
                    <div class="custom-search-container">
                        <i class="bi bi-search"></i>
                        <input type="text" class="custom-search-input" id="customSearchInput" placeholder="Search by Name, Email, etc...">
                    </div>
                    <button class="filter-btn">
                        <i class="bi bi-filter"></i>
                        Filter
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap w-100" id="adminsTable">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined Date</th>
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

<!-- Modal -->
<div class="modal fade" id="adminModal" tabindex="-1" aria-labelledby="adminModal" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Add New Admin</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="adminForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter admin name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter admin email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Set password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Create Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('page-script')
<script>
    $(document).ready(function() {
        let $table = $('#adminsTable').DataTable({
            processing: true,
            ajax: "{{ route('admin.admins') }}",
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'csv',
                    text: 'Export CSV',
                    className: 'csv-export-btn',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ],
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { 
                    data: 'created_at', 
                    name: 'created_at',
                    render: function(data) {
                        return new Date(data).toLocaleDateString();
                    }
                }
            ]
        });

        // Custom Search functionality
        let searchTimer;
        $('#customSearchInput').on('keyup', function() {
            clearTimeout(searchTimer);
            const val = this.value;
            searchTimer = setTimeout(function() {
                $table.search(val).draw();
            }, 300);
        });

        $('#adminForm').submit(function(e) {
            e.preventDefault();
            $('#saveBtn').prop('disabled', true).text('Processing...');

            $.ajax({
                url: "{{ route('admin.admins.store') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $('#adminModal').modal('hide');
                    $('#adminForm')[0].reset();
                    $table.ajax.reload();
                    showToast(response.success);
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMsg = '';
                    if (errors) {
                        $.each(errors, function(key, value) {
                            errorMsg += value[0] + ' ';
                        });
                    }
                    showToast(errorMsg || 'Something went wrong!', 'error');
                },
                complete: function() {
                    $('#saveBtn').prop('disabled', false).text('Create Admin');
                }
            });
        });
    });
</script>
@endpush
