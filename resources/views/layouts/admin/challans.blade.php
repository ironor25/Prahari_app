@extends('layouts.admin_master')
@section('page-content')
<div class="d-md-flex align-items-center justify-content-between my- page-header-breadcrumb">
    <h1 class="page-title fw-bold fs-2 mb-0">Challans</h1>
</div>

<div class="modal fade" id="challanModal" tabindex="-1" aria-labelledby="challanModal" data-bs-keyboard="false"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="modalTitle">Edit Challan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="challanForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="challan_id" id="challan_id">
                    <div class="mb-3">
                        <label for="prahari_id" class="form-label">Prahari</label>
                        <select class="form-control" id="prahari_id" name="prahari_id">
                            <option value="">Select Prahari</option>
                            @foreach($praharis as $prahari)
                                <option value="{{ $prahari->id }}">{{ $prahari->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-control" id="category_id" name="category_id">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->case_category_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="vehicle_number" class="form-label">Vehicle Number</label>
                        <input type="text" class="form-control" id="vehicle_number" name="vehicle_number"
                            placeholder="Enter vehicle number">
                    </div>

                    <div class="mb-3">
                        <label for="fine_amount" class="form-label">Fine Amount</label>
                        <input type="number" step="0.01" class="form-control" id="fine_amount" name="fine_amount"
                            placeholder="Enter fine amount">
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save Changes</button>
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
                        <input type="text" class="custom-search-input" id="customSearchInput" placeholder="Search by Case ID, Prahari ID, Vehicle, etc...">
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-light border text-muted d-flex align-items-center px-3 py-2 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-filter fs-5 me-2" style="line-height: 1;"></i> Filter
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item sort-item" href="javascript:void(0);" data-col="7" data-dir="desc">Newest First</a></li>
                            <li><a class="dropdown-item sort-item" href="javascript:void(0);" data-col="7" data-dir="asc">Oldest First</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item sort-item" href="javascript:void(0);" data-col="2" data-dir="asc">Case ID (A-Z)</a></li>
                            <li><a class="dropdown-item sort-item" href="javascript:void(0);" data-col="3" data-dir="asc">Prahari (A-Z)</a></li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">
        <table class="table table-bordered" id="usersTable">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Prahari ID</th>
                    <th>Case ID</th>
                    <th>Prahari</th>
                    <th>Vehicle Number</th>
                    <th>Fine Amount</th>
                    <th>Status</th>
                    <th>Date</th>
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
            ajax: "{{ route('admin.challans.index') }}",
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
                { data: 'prahari_custom_id', name: 'prahari_custom_id' },
                { data: 'case_custom_id', name: 'case_custom_id' },
                { data: 'prahari_name', name: 'prahari.name' },
                { data: 'vehicle_number', name: 'vehicle_number' },
                { data: 'fine_amount', name: 'fine_amount' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
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

        $('body').on('click', '.editBtn', function() {
            let id = $(this).data('id');
            $.get("{{ route('admin.challans.index') }}/" + id + "/edit", function(data) {
                $('#modalTitle').html('Edit Challan');
                $('#challanModal').modal('show');
                $('#challan_id').val(data.id);
                $('#prahari_id').val(data.prahari_id);
                $('#category_id').val(data.category_id);
                $('#vehicle_number').val(data.vehicle_number);
                $('#fine_amount').val(data.fine_amount);
                $('#status').val(data.status);
            });
        });

        $('#challanForm').submit(function(e) {
            e.preventDefault();
            let id = $('#challan_id').val();
            let url = "{{ route('admin.challans.index') }}/" + id;

            $.ajax({
                data: $(this).serialize(),
                url: url,
                type: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $('#challanModal').modal('hide');
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
            showConfirm("Delete Challan", "Are you sure you want to delete this challan?", function() {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('admin.challans.index') }}/" + id,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        table.ajax.reload(null, false);
                        showToast(data.success);
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        showToast('Failed to delete challan', 'error');
                    }
                });
            });
        });
    });
</script>
@endpush