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
    }
    .card-header-tabs {
        border-bottom: 1px solid #e9ecef;
    }
    .tab-pane {
        display: none;
    }
    .tab-pane.active {
        display: block;
    }
</style>
@endpush

@section('page-content')

<div class="d-md-flex align-items-center justify-content-between my-3 page-header-breadcrumb">
    <h1 class="page-title fw-bold fs-2 mb-0">Application Settings</h1>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header-tabs px-4 pt-3">
                <ul class="nav nav-tabs nav-tabs-header mb-0 border-bottom-0" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="general-tab" data-bs-toggle="tab" href="#general-settings" role="tab">General Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="category-tab" data-bs-toggle="tab" href="#category-fines" role="tab">Modify  Fines</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- Tab 1: General Settings -->
                    <div class="tab-pane active" id="general-settings" role="tabpanel">
                        <div class="col-xl-6">
                            <form id="generalSettingsForm">
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">System Language</label>
                                    <select class="form-select border-1 border-secondary">
                                        <option selected>English (Global)</option>
                                        <option>Hindi (Standard)</option>
                                        <option>Regional (Local)</option>
                                    </select>
                                    <small class="text-muted">Primary language for the administrative interface.</small>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">System Currency</label>
                                    <select class="form-select border-1 border-secondary">
                                        <option selected>INR (₹) - Indian Rupee</option>
                                        <option>USD ($) - US Dollar</option>
                                    </select>
                                    <small class="text-muted">Currency used for fine amounts and transactions.</small>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Minimum Withdrawal Limit (₹)</label>
                                    <input type="number" class="form-control border-1 border-secondary" value="500">
                                    <small class="text-muted">The minimum balance a Prahari must have to request a withdrawal.</small>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Prahari Commission (%)</label>
                                    <input type="number" class="form-control border-1 border-secondary" value="25">
                                    <small class="text-muted">Percentage of the fine amount paid to the reporting Prahari.</small>
                                </div>
                                <button type="button" class="btn bg-body-tertiary text-white px-5">Save Configuration</button>
                            </form>
                        </div>
                    </div>

                    <!-- Tab 2: Category Fines -->
                    <div class="tab-pane" id="category-fines" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="fw-bold mb-0">Fine Management by Category</h6>
                            <!-- No "Add" button as per user request to just manage existing categories in table -->
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap w-100" id="categoryTable">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Category Name</th>
                                        <th>Fine Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Category Edit Modal (Referenced from Prahari Blade) -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModal" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-body-tertiary text-white">
                <h6 class="modal-title" id="modalTitle">Edit Category Fine</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="categoryForm">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="cat_id">
                    <div class="mb-3">
                        <label for="cat_name" class="form-label fw-semibold">Category Name</label>
                        <input type="text" class="form-control" id="cat_name" name="case_category_name" placeholder="Enter category name" required>
                    </div>
                    <div class="mb-3">
                        <label for="cat_fine" class="form-label fw-semibold">Fine Amount (₹)</label>
                        <input type="number" class="form-control" id="cat_fine" name="fine_amount" placeholder="Enter fine amount" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn bg-body-tertiary text-white" id="saveBtn">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('page-script')
<script>
    $(document).ready(function() {
        // Tab switching logic (Bootstrap 5 standard)
        $('#general-tab, #category-tab').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
            $('.tab-pane').removeClass('active');
            $($(this).attr('href')).addClass('active');
        });

        // Initialize Category DataTable
        let table = $('#categoryTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.case_categories.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'case_category_name', name: 'case_category_name' },
                { data: 'fine_amount', name: 'fine_amount', render: $.fn.dataTable.render.number(',', '.', 0, '₹ ') },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            dom: 'frtip', // Filter, rows, pagination
            pageLength: 10
        });

        // Edit Button Click (AJAX Fetch)
        $('body').on('click', '.editBtn', function () {
            let id = $(this).data('id');
            $.get("{{ route('admin.case_categories.index') }}/" + id + "/edit", function (data) {
                $('#categoryModal').modal('show');
                $('#cat_id').val(data.id);
                $('#cat_name').val(data.case_category_name);
                $('#cat_fine').val(data.fine_amount);
            });
        });

        // Update Category (AJAX Submit)
        $('#categoryForm').submit(function (e) {
            e.preventDefault();
            let id = $('#cat_id').val();
            let url = "{{ route('admin.case_categories.index') }}/" + id + "/update";

            $.ajax({
                data: $(this).serialize(),
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('#categoryModal').modal('hide');
                    table.draw();
                    alert(data.success);
                },
                error: function (data) {
                    console.log('Error:', data);
                    alert('Failed to update category.');
                }
            });
        });

        // Delete Category (AJAX Delete)
        $('body').on('click', '.deleteBtn', function () {
            let id = $(this).data('id');
            if (confirm("Are you sure you want to delete this category?")) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('admin.case_categories.index') }}/" + id,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        table.draw();
                        alert(data.success);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        alert('Failed to delete category.');
                    }
                });
            }
        });
    });
</script>
@endpush
