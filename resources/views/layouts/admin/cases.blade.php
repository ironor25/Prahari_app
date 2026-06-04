@extends('layouts.admin_master')


@section('page-content')
<div class="d-md-flex align-items-center justify-content-between my-3 page-header-breadcrumb">
    <h1 class="page-title fw-bold fs-2 mb-0">Cases</h1>
    <button class="btn btn-dark btn-wave addBtn" data-bs-toggle="modal" data-bs-target="#caseModal" style="background-color: #2c3e50; border: none; padding: 10px 20px; font-weight: 500;">
        <i class="bi bi-plus-lg me-1"></i> Add Case
    </button>
</div>

<div class="row">
    <div class="col-xl-12">
                <div class="custom-table-controls">
                    <div class="custom-search-container">
                        <i class="bi bi-search"></i>
                        <input type="text" class="custom-search-input" id="customSearchInput" placeholder="Search by Case ID, Prahari Name, etc...">
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-light border text-muted d-flex align-items-center px-4 py-2 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-filter fs-5 me-2" style="line-height: 1;"></i> Filter
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item sort-item" href="javascript:void(0);" data-col="7" data-dir="desc">Newest First</a></li>
                            <li><a class="dropdown-item sort-item" href="javascript:void(0);" data-col="7" data-dir="asc">Oldest First</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item sort-item" href="javascript:void(0);" data-col="1" data-dir="asc">Case ID (A-Z)</a></li>
                            <li><a class="dropdown-item sort-item" href="javascript:void(0);" data-col="5" data-dir="asc">Location (A-Z)</a></li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap w-100" id="usersTable">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Case ID</th>
                                <th>Prahari</th>
                                <th>Category</th>
                                <th>Vehicle Number</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Date Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
           
        
</div>

<div class="modal fade" id="caseModal" tabindex="-1" aria-labelledby="caseModal" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="modalTitle">Add Case</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="caseForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="hidden_id">
                    <div class="mb-3">
                        <label for="custom_case_id" class="form-label">Case ID</label>
                        <input type="text" class="form-control" id="custom_case_id" name="case_id" placeholder="Enter custom Case ID (e.g. CAS-001)">
                    </div>
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
                        <label for="case_category_id" class="form-label">Category</label>
                        <select class="form-control" id="case_category_id" name="case_category_id">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->case_category_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="vehicle_number" class="form-label">Vehicle Number</label>
                        <input type="text" class="form-control" id="vehicle_number" name="vehicle_number" placeholder="Enter vehicle number">
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" placeholder="Enter location">
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Open">Open</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
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

<!-- View Case Modal -->
<div class="modal fade" id="viewCaseModal" tabindex="-1" aria-labelledby="viewCaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-body-tertiary text-white">
                <h6 class="modal-title" id="viewCaseModalLabel">Case Details & Evidence</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Loader -->
                <div id="viewLoader" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Fetching case evidence...</p>
                </div>

                <!-- Content -->
                <div id="viewContent" style="display: none;">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small text-uppercase fw-bold">Case ID</p>
                            <h6 id="view_case_id" class="fw-bold text-dark"></h6>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small text-uppercase fw-bold">Prahari ID</p>
                            <h6 id="view_prahari_id" class="fw-bold text-dark"></h6>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small text-uppercase fw-bold">Category</p>
                            <h6 id="view_category" class="fw-bold text-dark"></h6>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small text-uppercase fw-bold">Vehicle Number</p>
                            <h6 id="view_vehicle" class="fw-bold text-dark"></h6>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <p class="mb-1 text-muted small text-uppercase fw-bold">Location</p>
                            <h6 id="view_location" class="fw-bold text-dark"></h6>
                        </div>
                    </div>
                    
                    <div class="mb-0">
                        <p class="mb-2 text-muted small text-uppercase fw-bold">Evidence Video</p>
                        <div class="rounded overflow-hidden bg-light border" style="max-height: 400px;">
                            <video id="view_video" controls class="w-100 h-100" style="background: #000;">
                                <source src="" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <div id="noVideoMsg" class="text-center py-5 d-none">
                                <i class="bi bi-camera-video-off fs-1 text-muted"></i>
                                <p class="mt-2 mb-0">No evidence video available for this case.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-body-tertiary px-4" data-bs-toggle="modal" data-bs-target="#viewCaseModal" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page-script')
<script>
    let $table;

    $(document).ready(function() {
        $table = $('#usersTable').DataTable({
            processing: true,
            ajax: "{{ route('admin.cases.index') }}",
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
                { data: 'case_id', name: 'case_id' },
                { data: 'prahari_name', name: 'prahari.name' },
                { data: 'category_name', name: 'caseCategory.case_category_name' },
                { data: 'vehicle_number', name: 'vehicle_number' },
                { data: 'location', name: 'location' },
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
                $table.search(val).draw();
            }, 300);
        });

        // Custom Sort functionality
        $(document).on('click', '.sort-item', function() {
            let col = $(this).data('col');
            let dir = $(this).data('dir');
            $table.order([col, dir]).draw();
        });

        $('.addBtn').click(function() {
            $('#hidden_id').val('');
            $('#caseForm')[0].reset();
            $('#modalTitle').html('Add Case');
        });

        // View Case Logic
       $('body').on('click', '.viewBtn', function() {
    let id = $(this).data('id');
    
    $('#viewLoader').show();
    $('#viewContent').hide();
    $('#view_video').attr('src', '');
    $('#viewCaseModal').modal('show');

    $.get("{{ route('admin.cases.index') }}/" + id, function(data) {

        $('#view_case_id').text(data.case_id || 'N/A');
        $('#view_prahari_id').text(data.prahari ? data.prahari.prahari_id || 'N/A' : 'N/A');
        $('#view_category').text(data.category_name);
        $('#view_vehicle').text(data.vehicle_number || 'N/A');
        $('#view_location').text(data.location || 'N/A');

    
        if (data.evidence) {

            let videoUrl = data.evidence;

            if (!videoUrl.startsWith('http')) {
                videoUrl = "{{ asset('') }}" + videoUrl;
            }

            $('#view_video').attr('src', videoUrl).show();
            $('#view_video')[0].load();
            $('#noVideoMsg').addClass('d-none');

        } else { 
            $('#view_video').hide();
            $('#noVideoMsg').removeClass('d-none');
        }

        $('#viewLoader').hide();
        $('#viewContent').fadeIn();

        }).fail(function() {
            alert('Failed to load case details.');
            $('#viewCaseModal').modal('hide');
        });
    });

    $('body').on('click', '.approveBtn', function() {
            updateStatus($(this).data('id'), 'Approved');
        });

        $('body').on('click', '.rejectBtn', function() {
            updateStatus($(this).data('id'), 'Rejected');
        });

        function updateStatus(id, status) {
            showConfirm("Update Status", "Are you sure you want to " + status + " this case?", function() {
                $.ajax({
                    url: "{{ route('admin.cases.index') }}/" + id + "/status",
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

        $.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
        }, "Letters and numbers only please");

        $('#caseForm').validate({
            rules: {
                vehicle_number: {
                    alphanumeric: true,
                    maxlength: 10,
                    minlength: 7
                }
            },
            messages: {
                vehicle_number: {
                    alphanumeric: "Please enter only alphanumeric characters",
                    maxlength: "Please enter a valid vehicle number",
                    minlength: "Please enter a valid vehicle number"
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.mb-3').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function(form, e) {
                e.preventDefault();
                let id = $('#hidden_id').val();
                let url = id ? "{{ route('admin.cases.index') }}/" + id : "{{ route('admin.cases.store') }}";
                let method = id ? 'PUT' : 'POST';

                $.ajax({
                    data: $(form).serialize(),
                    url: url,
                    type: method,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $('#caseForm')[0].reset();
                        $('#caseModal').modal('hide');
                        $table.ajax.reload(null, false);
                        showToast(data.success);
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        showToast('Something went wrong!', 'error');
                    }
                });
            }
        });
        $('body').on('click', '.deleteBtn', function() {
            let id = $(this).data('id');
            showConfirm("Delete Case", "Are you sure you want to delete this case?", function() {
                $.ajax({
                    url: "{{ route('admin.cases.index') }}/" + id,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $table.ajax.reload(null, false);
                        showToast(data.success);
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        showToast('Failed to delete case', 'error');
                    }
                });
            });
        });
    });
</script>
@endpush