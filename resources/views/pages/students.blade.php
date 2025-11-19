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
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            <span>Add Student</span>
        </a>
    </div>
</div>

<div class="page-container">
    <div class="table-container fade-in">
        <table id="datatable" class="table">
            <thead>
                <tr>
                    <th>Student Details</th>
                    <th>Contact Info</th>
                    <th>App Login</th>
                    <th>Assigned Route</th>
                    <th>Status</th> 
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $student)
                    <tr>
                        <td style="width: 250px;">
                            <div class="table-profile">
                                <div class="table-avatar">
                                    @if (!empty($student->photo_path))
                                        <img src="{{ asset(htmlspecialchars($student->photo_path)) }}" alt="{{ htmlspecialchars($student->name) }}">
                                    @else
                                        <img src="https://api.dicebear.com/7.x/initials/svg?seed={{ htmlspecialchars($student->name) }}&backgroundColor=282c34&fontColor=bbf7d0" alt="{{ htmlspecialchars($student->name) }}">
                                    @endif
                                </div>
                                <div class="table-info">
                                    <h4>{{ htmlspecialchars($student->name) }}</h4>
                                    <p>ID: STD{{ str_pad($student->id, 4, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="info-cell-group">
                                <div class="info-primary">{{ htmlspecialchars($student->phone) }}</div>
                                <div class="info-secondary">{{ htmlspecialchars($student->email) }}</div>
                            </div>
                        </td>

                        <td>
                            <div class="info-cell-group">
                                <div class="info-primary">{{ htmlspecialchars($student->app_username ?? 'N/A') }}</div>
                                <div class="info-secondary" style="letter-spacing: 2px;">•••••••</div>
                            </div>
                        </td>

                        <td>
                            @if ($student->route)
                                <div class="table-profile" style="gap: 0.5rem;">
                                    <div class="table-avatar" style="width: 28px; height: 28px; font-size: 0.7rem; background: var(--bg-hover); border-color: var(--border);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                                    </div>
                                    <div class="table-info">
                                        <h4 style="font-size: 0.9rem;">{{ htmlspecialchars($student->route->name) }}</h4>
                                        <p style="font-size: 0.75rem; opacity: 0.7;">Bus: {{ htmlspecialchars($student->route->bus->name ?? 'N/A') }}</p>
                                    </div>
                                </div>
                            @else
                                <span class="status-badge status-inactive" style="background: rgba(255, 255, 255, 0.05); color: var(--text-secondary); border: 1px solid var(--border);">Not Assigned</span>
                            @endif
                        </td>

                        <td> 
                            @php
                                $isStudentVerified = (bool) $student->is_verified;
                            @endphp
                            
                            @if ($isStudentVerified)
                                <span class="status-badge status-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    Verified
                                </span>
                            @else
                                <button type="button"
                                        class="status-badge status-danger js-send-otp"
                                        data-user-id="{{ $student->id }}"
                                        data-user-email="{{ e($student->email) }}"
                                        data-user-name="{{ e($student->name) }}"
                                        title="Click to Verify">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4"/><path d="M12 17h.01"/><path d="m21.73 18-8-14a2 2 0 0 0-3.46 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/></svg>
                                    Verify Now
                                </button>
                            @endif
                        </td>

                        <td>
                            <div class="action-buttons">
                                <button type="button" 
                                   class="action-btn js-edit-student"
                                   data-id="{{ $student->id }}"
                                   data-name="{{ htmlspecialchars($student->name) }}"
                                   data-email="{{ htmlspecialchars($student->email) }}"
                                   data-phone="{{ htmlspecialchars($student->phone) }}"
                                   data-route-name="{{ htmlspecialchars($student->route->name ?? '') }}"
                                   data-photo="{{ htmlspecialchars($student->photo_path ?? '') }}"
                                   title="Edit Student">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                </button>
                                
                                <button type="button" 
                                   class="action-btn js-reset-password"
                                   {{ $student->is_verified ? 'disabled' : '' }}
                                   data-id="{{ $student->id }}"
                                   data-name="{{ htmlspecialchars($student->name) }}"
                                   title="Reset Password">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 18v3c0 .6.4 1 1 1h4v-3h3v-3h2l1.4-1.4a6.5 6.5 0 1 0-4-4Z"/><circle cx="16.5" cy="7.5" r="2.5"/></svg>
                                </button>
                                
                                <button type="button" 
                                   class="action-btn delete-btn js-delete-student" 
                                   {{ $student->is_verified ? 'disabled' : '' }}
                                   data-delete-id="{{ $student->id }}"
                                   data-delete-url="{{ url('/students/delete') }}"
                                   title="Delete Student">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 4rem 2rem; color: var(--text-secondary);">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3;"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20v2H6.5a2.5 2.5 0 0 1 0-5H20v2H6.5a2.5 2.5 0 0 1 0-5H20v2H6.5A2.5 2.5 0 0 1 4 9.5V8a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1.5"/><path d="M12 13V2l-2 3 2 3 2-3-2-3Z"/></svg>
                                <div style="font-size: 1.1rem; font-weight: 500;">No students found</div>
                                <p style="font-size: 0.9rem; opacity: 0.7;">Click "Add Student" to get started.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
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
                
                <div class="formbold-mb-3">
                    <div class="form-note">
                        App login credentials will be generated automatically based on the student's email and phone number.
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
            <p class="alert-text-primary">Are you sure you want to delete this student?</p>
            <p class="alert-text-secondary">This action cannot be undone.</p>
        </div>
        <div class="modal-footer modal-footer-alert">
            <button type="button" id="cancelDeleteModal" class="btn btn-outline">Cancel</button>
            <button type="button" id="confirmDeleteBtn" class="btn btn-destructive" data-id="">
                <span>Delete</span>
            </button>
        </div>
    </div> 
</div>

<div id="statusConfirmModal" class="modal-overlay" style="display: none;">
    <div class="modal-content modal-content-alert">
        <div class="modal-header-alert">
            <div class="alert-icon-wrapper" id="statusModalIcon" style="background-color: hsla(var(--warning), 0.15); border-color: hsla(var(--warning), 0.2);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="hsl(var(--warning))" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle"><path d="m21.73 18-8-14a2 2 0 0 0-3.46 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
            </div>
            <h2 class="modal-title">Confirm Action</h2>
        </div>
        <div class="modal-body"><p class="alert-text-primary" id="statusConfirmText">Are you sure you want to update this student's status?</p></div>
        <div class="modal-footer modal-footer-alert">
            <button type="button" id="cancelStatusChange" class="btn btn-outline">Cancel</button>
            <button type="button" id="confirmStatusChange" class="btn" style="background-color: hsl(var(--warning)); color: #000; border-color: hsl(var(--warning));">Confirm</button>
        </div>
    </div>
</div>

<div id="otpModal" class="modal-overlay" style="display: none;">
    <div class="modal-content modal-content-alert" style="max-width: 480px;">
        <form id="otpForm">
            @csrf
            <div class="modal-header-alert">
                <div class="alert-icon-wrapper" style="background-color: hsla(var(--primary), 0.15); border-color: hsla(var(--primary), 0.2);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="hsl(var(--primary))" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-key-round"><path d="M2 18v3c0 .6.4 1 1 1h4v-3h3v-3h2l1.4-1.4a6.5 6.5 0 1 0-4-4Z"/><circle cx="16.5" cy="7.5" r="2.5"/></svg>
                </div>
                <h2 class="modal-title">Enter Verification Code</h2>
            </div>
            <div class="modal-body">
                <input type="hidden" id="otp_user_id" name="user_id">
                <p class="alert-text-secondary" id="otpMessage">An OTP has been sent to the student's email.</p>
                <div class="form-group" style="margin-top: 1.5rem; text-align: left;">
                    <label for="otp_code" class="formbold-form-label">Enter 6-Digit OTP</label>
                    <input type="text" id="otp_code" name="otp_code" class="formbold-form-input" style="font-size: 1.5rem; text-align: center; letter-spacing: 0.5rem;" maxlength="6" required>
                    <small id="otpError" class="text-danger-inline" style="text-align: center; margin-top: 10px;"></small>
                </div>
            </div>
            <div class="modal-footer modal-footer-alert">
                <button type="button" id="closeOtpModal" class="btn btn-outline">Cancel</button>
                <button type="submit" class="formbold-btn">Verify & Activate</button>
            </div>
        </form>
    </div>
</div>

<div id="resetPasswordModal" class="modal-overlay" style="display: none;">
    <div class="modal-content modal-content-alert">
        <div class="modal-header-alert">
            <div class="alert-icon-wrapper" style="background-color: hsla(var(--warning), 0.15); border-color: hsla(var(--warning), 0.2);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="hsl(var(--warning))" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-key-round"><path d="M2 18v3c0 .6.4 1 1 1h4v-3h3v-3h2l1.4-1.4a6.5 6.5 0 1 0-4-4Z"/><circle cx="16.5" cy="7.5" r="2.5"/></svg>
            </div>
            <h2 class="modal-title">Confirm Password Reset</h2>
        </div>
        <div class="modal-body">
            <p class="alert-text-primary" id="resetPasswordText">Are you sure you want to reset this student's password?</p>
            <p class="alert-text-secondary">A new password will be generated and shown to you.</p>
        </div>
        <div class="modal-footer modal-footer-alert">
            <button type="button" id="cancelResetPassword" class="btn btn-outline">Cancel</button>
            <button type="button" id="confirmResetPassword" class="btn" style="background-color: hsl(var(--warning)); color: #000; border-color: hsl(var(--warning));">Reset Password</button>
        </div>
    </div>
</div>

<div id="showNewPasswordModal" class="modal-overlay" style="display: none;">
    <div class="modal-content modal-content-alert">
        <div class="modal-header-alert">
            <div class="alert-icon-wrapper" style="background-color: hsla(var(--success), 0.15); border-color: hsla(var(--success), 0.2);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="hsl(var(--success))" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 7 17l-5-5"/><path d="m22 10-7.5 7.5L13 16"/></svg>
            </div>
            <h2 class="modal-title">Password Reset Successful</h2>
        </div>
        <div class="modal-body">
            <p class="alert-text-secondary">Please copy the new password below.</p>
            <div class="form-group" style="margin-top: 1.5rem;">
                <input type="text" id="newPasswordText" class="formbold-form-input" style="font-size: 1.5rem; text-align: center; letter-spacing: 0.5rem;" readonly>
            </div>
        </div>
        <div class="modal-footer modal-footer-alert">
            <button type="button" id="closeNewPasswordModal" class="btn btn-outline">Close</button>
        </div>
    </div>
</div>

@endsection