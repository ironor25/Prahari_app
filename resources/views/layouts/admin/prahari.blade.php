@extends('layouts.admin_master')
@section('page-content')
<div class="d-md-flex align-items-center justify-content-between my-3 page-header-breadcrumb">
    <h1 class="page-title fw-bold fs-2 mb-0">Prahari</h1>
    <button class="btn btn-dark btn-wave addBtn" data-bs-toggle="modal" data-bs-target="#prahariModal" style="background-color: #2c3e50; border: none; padding: 10px 20px; font-weight: 500;">
        <i class="bi bi-plus-lg me-1"></i> Add Prahari
    </button>
</div>
        <div class="modal fade" id="prahariModal" tabindex="-1" aria-labelledby="prahariModal" data-bs-keyboard="false"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="modalTitle">Add Prahari</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="prahariForm">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="id" id="hidden_id">
                            <div class="mb-3">
                                <label for="custom_prahari_id" class="form-label">Prahari ID</label>
                                <input type="text" class="form-control" id="custom_prahari_id" name="prahari_id"
                                    placeholder="Enter custom ID (e.g. PRA-001)">
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter name" required>
                            </div>

                            <div class="mb-3">
                                <label for="mobile" class="form-label">Mobile</label>
                                <input type="text" class="form-control" id="mobile" name="mobile"
                                    placeholder="Enter mobile number" required>
                            </div>

                            <div class="mb-3">
                                <label for="bank_account" class="form-label">Bank Account</label>
                                <input type="text" class="form-control" id="bank_account" name="bank_account"
                                    placeholder="Enter bank account" required>
                            </div>

                            <div class="mb-3">
                                <label for="aadhaar_status" class="form-label">Aadhaar Status</label>
                                <select class="form-control" id="aadhaar_status" name="aadhaar_status" required>
                                    <option value="not_verified">Not Verified</option>
                                    <option value="verified">Verified</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="saveBtn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<div class="row">
    <div class="col-sm-12">
        
                <!-- Custom Controls -->
                <div class="custom-table-controls">
                    <div class="custom-search-container">
                        <i class="bi bi-search"></i>
                        <input type="text" class="custom-search-input" id="customSearchInput" placeholder="Search by Prahari ID, Name, Mobile, etc...">
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-light border text-muted d-flex align-items-center px-3 py-2 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-filter fs-5 me-2" style="line-height: 1;"></i> Filter
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item sort-item" href="javascript:void(0);" data-col="2" data-dir="asc">Name (A-Z)</a></li>
                            <li><a class="dropdown-item sort-item" href="javascript:void(0);" data-col="2" data-dir="desc">Name (Z-A)</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item sort-item" href="javascript:void(0);" data-col="1" data-dir="asc">Prahari ID (A-Z)</a></li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">
        <table class="table table-bordered" id="usersTable">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Prahari ID</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Aadhaar Status</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('page-script')
<script>
    $(document).ready(function() {
        let table = $('#usersTable').DataTable({
            processing: true,
            ajax: "{{ route('admin.praharis.index') }}",
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
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'prahari_id', name: 'prahari_id' },
                { data: 'name', name: 'name' },
                { data: 'mobile', name: 'mobile' },
                { data: 'aadhaar_status', name: 'aadhaar_status' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        // Custom Search functionality
        let searchTimer;
        $('#customSearchInput').on('keyup', function() {
            clearTimeout(searchTimer);
            const val = this.value;
            searchTimer = setTimeout(function() {
                table.search(val).draw();
            }, 300);
        });

        // Custom Sort functionality
        $(document).on('click', '.sort-item', function() {
            let col = $(this).data('col');
            let dir = $(this).data('dir');
            table.order([col, dir]).draw();
        });

        $('.addBtn').click(function() {
            $('#hidden_id').val('');
            $('#prahariForm')[0].reset();
            $('#modalTitle').html('Add Prahari');
            $('#prahariModal').modal('show');
        });

        $('body').on('click', '.editBtn', function() {
            let id = $(this).data('id');
            $.get("{{ route('admin.praharis.index') }}/" + id + "/edit", function(data) {
                $('#modalTitle').html('Edit Prahari');
                $('#prahariModal').modal('show');
                $('#hidden_id').val(data.id);
                $('#custom_prahari_id').val(data.prahari_id);
                $('#name').val(data.name);
                $('#mobile').val(data.mobile);
                $('#bank_account').val(data.bank_account);
                $('#aadhaar_status').val(data.aadhaar_status);
                $('#status').val(data.status);
            });
        });

        $('#prahariForm').submit(function(e) {
            e.preventDefault();
            let id = $('#hidden_id').val();
            let url = id ? "{{ route('admin.praharis.index') }}/" + id : "{{ route('admin.praharis.store') }}";
            let method = id ? 'PUT' : 'POST';

            $.ajax({
                data: $(this).serialize(),
                url: url,
                type: method,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $('#prahariForm')[0].reset();
                    $('#prahariModal').modal('hide');
                    table.ajax.reload(null, false);
                    showToast(data.success);
                },
                error: function(data) {
                    console.log('Error:', data);
                    showToast('Something went wrong!', 'error');
                }
            });
        });

        
        $('body').on('click', '.deleteBtn', function() {
            let id = $(this).data('id');
            showConfirm("Delete Prahari", "Are you sure you want to delete this Prahari?", function() {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('admin.praharis.index') }}/" + id,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        table.ajax.reload(null, false);
                        showToast(data.success);
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        showToast('Failed to delete Prahari', 'error');
                    }
                });
            });
        });
    });

</script>
@endpush
