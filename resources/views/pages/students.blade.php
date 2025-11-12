@extends('layouts.master')

@section('content')

<div id="alert-container" class="alert-container-global"></div>

<div class="page-header drivers-page-header">
    <div class="header-left-content">
        <h1 class="page-title">Manage Students</h1>
        <p class="page-subtitle">Manage student profiles and OTP verification.</p>
        <p class="page-header-note">
            Below is a list of all registered students. Use the "Add Student" button to register a new one.
        </p>
    </div>
    <div class="header-right-actions">
        <a href="javascript:void(0);" id="openAddStudentModal" class="add-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            <span>Add Student</span>
        </a>
    </div>
</div>
<div class="page-container">
    <table id="datatable" class="table">
        <thead>
            <tr>
                <th>Profile</th>
                <th>Name</th>
                <th>Contact</th>
                <th>App Login</th>
                <th>Assigned Route</th>
                <th>Verification</th> 
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($students as $student)
                <tr>
                    <td>
                        @if (!empty($student->photo_path))
                            <img src="{{ asset(htmlspecialchars($student->photo_path)) }}" 
                                 alt="{{ htmlspecialchars($student->name) }}" 
                                 class="avatar" style="object-fit: cover;">
                        @else
                            <img src="https://api.dicebear.com/7.x/initials/svg?seed={{ htmlspecialchars($student->name) }}&backgroundColor=282c34&fontColor=bbf7d0" 
                                 alt="{{ htmlspecialchars($student->name) }}" 
                                 class="avatar">
                        @endif
                    </td>
                    <td>{{ htmlspecialchars($student->name) }}</td>
                    <td>
                        <div>{{ htmlspecialchars($student->phone) }}</div>
                        <div class="text-muted">{{ htmlspecialchars($student->email) }}</div>
                    </td>
                    <td>
                        <div class="text-muted">{{ htmlspecialchars($student->app_username ?? 'N/A') }}</div>
                        <div class="password-cell">
                            <span class="password-text">
                                ••••••••
                            </span>
                             <button type="button" class="btn-action-icon" disabled title="Password is Hashed">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-eye-off">
                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                    <td>
                        @if ($student->route)
                            {{ htmlspecialchars($student->route->name) }}
                        @else
                            <span class="text-muted">Not Assigned</span>
                        @endif
                    </td>
                    <td style="width: 150px;"> 
                        <div class="status-cell-wrapper">
                            @php
                                // PUTHU MAATRAM: Blade @php block
                                if ($student->is_verified == 1) { 
                                    $btn_class = 'btn-verify-verified';
                                    $btn_text = 'Verified';
                                    $js_class = 'js-status-toggle';
                                    $email_attr = '';
                                } else { 
                                    $btn_class = 'btn-verify-pending';
                                    $btn_text = 'Verification'; 
                                    $js_class = 'js-send-otp';
                                    $email_attr = 'data-user-email="' . htmlspecialchars($student->email) . '"';
                                }
                            @endphp
                            <button class="btn-verify {{ $btn_class }} {{ $js_class }}"
                                    data-user-id="{{ $student->id }}"
                                    data-user-name="{{ htmlspecialchars($student->name) }}"
                                    {!! $email_attr !!}>
                                
                                @if ($student->is_verified == 1)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                @endif
                                
                                <span>{{ $btn_text }}</span>

                                @if ($student->is_verified != 1)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                                @endif
                            </button>
                        </div>
                    </td>
                    <td>
                        <div class="action-buttons-wrapper">
                             <a href="javascript:void(0);" 
                               class="btn-action-edit js-edit-student"
                               data-id="{{ $student->id }}"
                               data-name="{{ htmlspecialchars($student->name) }}"
                               data-email="{{ htmlspecialchars($student->email) }}"
                               data-phone="{{ htmlspecialchars($student->phone) }}"
                               data-route-name="{{ htmlspecialchars($student->route_name ?? '') }}"
                               data-photo="{{ htmlspecialchars($student->photo_path ?? '') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                <span>Edit</span>
                            </a>
                            
                            <a href="javascript:void(0);" 
                               class="btn-action-reset js-reset-password"
                               data-id="{{ $student->id }}"
                               data-name="{{ htmlspecialchars($student->name) }}"
                               title="Reset Password">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-key-round"><path d="M2 18v3c0 .6.4 1 1 1h4v-3h3v-3h2l1.4-1.4a6.5 6.5 0 1 0-4-4Z"/><circle cx="16.5" cy="7.5" r="2.5"/></svg>
                                <span>Reset</span>
                            </a>
                            <a href="javascript:void(0);" 
                               class="btn-action-delete js-delete-student" 
                               data-delete-id="{{ $student->id }}"
                               data-delete-url="{{ url('/students/delete') }}"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                <span>Delete</span>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 3rem; color: hsl(var(--muted-foreground));">
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.5;">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20v2H6.5a2.5 2.5 0 0 1 0-5H20v2H6.5a2.5 2.5 0 0 1 0-5H20v2H6.5A2.5 2.5 0 0 1 4 9.5V8a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1.5"/><path d="M12 13V2l-2 3 2 3 2-3-2-3Z"/>
                            </svg>
                            <div style="font-size: 1.125rem; font-weight: 600;">No students found</div>
                            <div style="font-size: 0.875rem;">Click "Add Student" to register your first student.</div>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div id="addStudentModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <form id="studentForm" action="{{ url('/students/create') }}" method="POST" enctype="multipart/form-data">
            @csrf <div class="modal-header">
                <h2 class="modal-title" id="studentModalTitle">Add New Student</h2>
                <button type="button" id="closeAddStudentModal" class="modal-close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="student_id">
                <div class="formbold-mb-3" id="photo-upload-wrapper">
                    <div id="photo-preview" title="Click to upload photo"></div>
                    <input type="file" name="photo" id="photo" accept="image/*" style="display: none;">
                    <button type="button" id="upload-button" class="btn btn-outline">Upload Photo</button>
                </div>
                <small id="photoError" class="text-danger-inline" style="margin-top: -10px; margin-bottom: 15px;"></small>
                
                <div class="formbold-mb-3 name-field-wrapper">
                    <label for="name" class="formbold-form-label">Full Name</label>
                    <input type="text" name="name" id="name" class="formbold-form-input" required />
                    <small id="nameError" class="text-danger-inline"></small>
                </div>

                <div class="formbold-input-flex">
                    <div>
                        <label for="email" class="formbold-form-label">Email</label>
                        <input type="email" name="email" id="email" class="formbold-form-input" required />
                        <small id="emailError" class="text-danger-inline"></small>
                    </div>
                    <div>
                        <label for="phone" class="formbold-form-label">Phone Number</label>
                        <input type="tel" name="phone" id="phone" class="formbold-form-input" required />
                        <small id="phoneError" class="text-danger-inline"></small>
                    </div>
                </div>

                <div class="formbold-mb-3">
                    <label for="route_name" class="formbold-form-label">Assign Route</label>
                    <select name="route_name" id="route_name" class="formbold-form-input" required>
                        <option value="">Select a route</option>
                        @foreach ($routes as $route)
                            <option value="{{ htmlspecialchars($route->name) }}">
                                {{ htmlspecialchars($route->name) }} (Bus: {{ htmlspecialchars($route->bus_name) }})
                            </option>
                        @endforeach
                    </select>
                    <small id="route_nameError" class="text-danger-inline"></small>
                </div>
                
                <div id="app-credentials-fields">
                    <div class="formbold-input-flex">
                        <div>
                            <label for="app_username" class="formbold-form-label">App Username</label>
                            <input type="text" name="app_username" id="app_username" class="formbold-form-input" required />
                            <small id="app_usernameError" class="text-danger-inline"></small>
                        </div>
                        <div>
                            <label for="app_password" class="formbold-form-label">App Password</label>
                            <input type="password" name="app_password" id="app_password" class="formbold-form-input" required minlength="6" />
                            <small id="app_passwordError" class="text-danger-inline"></small>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" id="cancelAddStudentModal" class="btn btn-outline">Cancel</button>
                <button type="submit" class="formbold-btn" id="studentModalSubmitBtn">Submit Student</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteStudentModal" class="modal-overlay" style="display: none;">
    <div class="modal-content modal-content-alert">
        <div class="modal-header-alert">
            <div class="alert-icon-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle"><path d="m21.73 18-8-14a2 2 0 0 0-3.46 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
            </div>
            <h2 class="modal-title">Confirm Deletion</h2>
        </div>
        <div class="modal-body">
            <p class="alert-text-primary">
                Are you sure you want to delete this student?
            </p>
            <p class="alert-text-secondary">
                This action cannot be undone.
            </p>
        </div>
        <div class="modal-footer modal-footer-alert">
            <button type="button" id="cancelDeleteModal" class="btn btn-outline">Cancel</button>
            <button type="button" id="confirmDeleteBtn" class="btn btn-destructive" data-id="">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                <span>Delete</span>
            </button>
        </div>
    </div> 
</div>

<div id="statusConfirmModal" class="modal-overlay" style="display: none;">
    </div>

<div id="otpConfirmModal" class="modal-overlay" style="display: none;">
    </div>

<div id="otpModal" class="modal-overlay" style="display: none;">
    </div>

<div id="resetPasswordModal" class="modal-overlay" style="display: none;">
    </div>

<div id="showNewPasswordModal" class="modal-overlay" style="display: none;">
    </div>

@endsection