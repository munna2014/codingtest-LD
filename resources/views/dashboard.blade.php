@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Dashboard</h1>
    <p>Welcome Home</p>
    <p>User List</p>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Dynamic Alert for AJAX responses -->
    <div id="alertContainer"></div>

    <!-- Users Table -->
    <div class="table-responsive">
        <table id="usersTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr id="user-row-{{ $user->id }}">
                        <td>{{ $user->id }}</td>
                        <td class="user-name">{{ $user->name }}</td>
                        <td class="user-email">{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('d M Y h:i A') }}</td>
                        <td>
                            <button
                                class="btn btn-primary btn-sm editBtn"
                                data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}"
                                data-email="{{ $user->email }}">
                                Edit
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap Edit Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editUserForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="userId" name="id">
                    
                    <div class="mb-3">
                        <label for="userName" class="form-label">Name</label>
                        <input type="text" name="name" id="userName" class="form-control" required>
                        <div class="invalid-feedback" id="nameError"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="userEmail" class="form-label">Email</label>
                        <input type="email" name="email" id="userEmail" class="form-control" required>
                        <div class="invalid-feedback" id="emailError"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="updateBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    // CSRF Token setup for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Open modal with user data
    $('.editBtn').on('click', function () {
        let id = $(this).data('id');
        let name = $(this).data('name');
        let email = $(this).data('email');

        // Clear previous validation errors
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        // Populate modal fields
        $('#userId').val(id);
        $('#userName').val(name);
        $('#userEmail').val(email);

        // Show modal
        $('#editUserModal').modal('show');
    });

    // Handle form submission
    $('#editUserForm').on('submit', function (e) {
        e.preventDefault();

        let userId = $('#userId').val();
        let formData = new FormData(this);
        
        // Show loading state
        let updateBtn = $('#updateBtn');
        let spinner = updateBtn.find('.spinner-border');
        updateBtn.prop('disabled', true);
        spinner.removeClass('d-none');

        // Clear previous validation errors
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        // AJAX request
        $.ajax({
            url: '/users/' + userId,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    // Close modal
                    $('#editUserModal').modal('hide');

                    // Update table row with new data
                    let row = $('#user-row-' + userId);
                    row.find('.user-name').text(response.user.name);
                    row.find('.user-email').text(response.user.email);
                    
                    // Update edit button data attributes
                    let editBtn = row.find('.editBtn');
                    editBtn.data('name', response.user.name);
                    editBtn.data('email', response.user.email);
                    editBtn.attr('data-name', response.user.name);
                    editBtn.attr('data-email', response.user.email);

                    // Show success message
                    showAlert('success', response.message);
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    // Validation errors
                    let errors = xhr.responseJSON.errors;
                    
                    if (errors.name) {
                        $('#userName').addClass('is-invalid');
                        $('#nameError').text(errors.name[0]);
                    }
                    
                    if (errors.email) {
                        $('#userEmail').addClass('is-invalid');
                        $('#emailError').text(errors.email[0]);
                    }
                } else {
                    // Other errors
                    let message = xhr.responseJSON?.message || 'An error occurred while updating the user.';
                    showAlert('danger', message);
                }
            },
            complete: function () {
                // Hide loading state
                updateBtn.prop('disabled', false);
                spinner.addClass('d-none');
            }
        });
    });

    // Function to show alerts
    function showAlert(type, message) {
        let alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        $('#alertContainer').html(alertHtml);
        
        // Auto-dismiss after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    }

    // Clear alerts when modal is shown
    $('#editUserModal').on('show.bs.modal', function () {
        $('#alertContainer').empty();
    });
});
</script>
@endpush