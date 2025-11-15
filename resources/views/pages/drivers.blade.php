@extends('layouts.master')

@section('content')

<div id="alert-container" class="alert-container-global"></div>

<div class="page-header drivers-page-header">
    <div class="header-left-content">
        <h1 class="page-title">Manage Drivers</h1>
        <p class="page-subtitle">Manage driver profiles and OTP verification.</p>
        <p class="page-header-note">
            Below is a list of all registered drivers. Use the "Add Driver" button to register a new one.
        </p>
    </div>
    <div class="header-right-actions">
        <a href="javascript:void(0);" id="openAddDriverModal" class="add-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            <span>Add Driver</span>
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
                <th>Verification</th> 
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($drivers as $driver)
                <tr>
                    <td>
                        @if (!empty($driver->photo_path))
                            <img src="{{ asset(htmlspecialchars($driver->photo_path)) }}" 
                                 alt="{{ htmlspecialchars($driver->name) }}" 
                                 class="avatar" style="object-fit: cover;">
                        @else
                            <img src="https://api.dicebear.com/7.x/initials/svg?seed={{ htmlspecialchars($driver->name) }}&backgroundColor=282c34&fontColor=86efac" 
                                 alt="{{ htmlspecialchars($driver->name) }}" 
                                 class="avatar">
                        @endif
                    </td>
                    <td>{{ htmlspecialchars($driver->name) }}</td>
                    <td>
                        <div>{{ htmlspecialchars($driver->phone) }}</div>
                        <div class="text-muted">{{ htmlspecialchars($driver->email) }}</div>
                    </td>
                    <td>
                        <div class="text-muted">{{ htmlspecialchars($driver->app_username ?? 'N/A') }}</div>
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
                    <td style="width: 150px;"> 
                        @php
                            $isVerified = (bool) $driver->is_verified;
                            $statusLabel = $isVerified ? 'Verified' : 'Not Verified';
                            $statusBadgeClass = $isVerified ? 'status-badge status-badge-success' : 'status-badge status-badge-danger';
                            $avatarSource = !empty($driver->photo_path)
                                ? asset($driver->photo_path)
                                : "https://api.dicebear.com/7.x/initials/svg?seed=" . urlencode($driver->name) . "&backgroundColor=282c34&fontColor=86efac";
                        @endphp
                        <div class="status-cell-wrapper">
                            @if ($isVerified)
                                <span class="{{ $statusBadgeClass }}" aria-label="Driver verified">
                                    <span class="status-badge-avatar">
                                        <img src="{{ $avatarSource }}" alt="{{ e($driver->name) }} avatar">
                                    </span>
                                    <span class="status-badge-text">{{ $statusLabel }}</span>
                                    <span class="status-badge-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    </span>
                                </span>
                            @else
                                <button type="button"
                                        class="{{ $statusBadgeClass }} js-send-otp"
                                        data-user-id="{{ $driver->id }}"
                                        data-user-email="{{ e($driver->email) }}"
                                        data-user-name="{{ e($driver->name) }}">
                                    <span class="status-badge-avatar">
                                        <img src="{{ $avatarSource }}" alt="{{ e($driver->name) }} avatar">
                                    </span>
                                    <span class="status-badge-text">{{ $statusLabel }}</span>
                                    <span class="status-badge-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                                    </span>
                                </button>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="action-buttons-wrapper">
                             <a href="javascript:void(0);" 
                               class="btn-action-edit js-edit-driver"
                               data-id="{{ $driver->id }}"
                               data-name="{{ htmlspecialchars($driver->name) }}"
                               data-email="{{ htmlspecialchars($driver->email) }}"
                               data-phone="{{ htmlspecialchars($driver->phone) }}"
                               data-photo="{{ htmlspecialchars($driver->photo_path ?? '') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                <span>Edit</span>
                            </a>
                            
                            {{-- PUTHU MAATRAM: 'btn-disabled' class-a add panrom --}}
                            <a href="javascript:void(0);" 
                               class="btn-action-reset js-reset-password {{ $driver->is_verified ? 'btn-disabled' : '' }}"
                               data-id="{{ $driver->id }}"
                               data-name="{{ htmlspecialchars($driver->name) }}"
                               title="Reset Password">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-key-round"><path d="M2 18v3c0 .6.4 1 1 1h4v-3h3v-3h2l1.4-1.4a6.5 6.5 0 1 0-4-4Z"/><circle cx="16.5" cy="7.5" r="2.5"/></svg>
                                <span>Reset</span>
                            </a>
                            
                            {{-- PUTHU MAATRAM: 'btn-disabled' class-a add panrom --}}
                            <a href="javascript:void(0);" 
                               class="btn-action-delete js-delete-driver {{ $driver->is_verified ? 'btn-disabled' : '' }}" 
                               data-delete-id="{{ $driver->id }}"
                               data-delete-url="{{ url('/drivers/delete') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                <span>Delete</span>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 3rem; color: hsl(var(--muted-foreground));">
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.5;">
                                <path d="M19 17h2l.64-2.54A6 6 0 0 0 16.9 9h-1.8a6 6 0 0 0-4.74 5.46L9 17h10z"/><circle cx="7" cy="7" r="3"/><circle cx="17" cy="7" r="3"/><path d="M12 17v4"/><path d="M8 21h8"/>
                            </svg>
                            <div style="font-size: 1.125rem; font-weight: 600;">No drivers found</div>
                            <div style="font-size: 0.875rem;">Click "Add Driver" to register your first driver.</div>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div id="addDriverModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <form id="driverForm" action="{{ url('/drivers/create') }}" method="POST" enctype="multipart/form-data">
            @csrf <div class="modal-header">
                <h2 class="modal-title" id="driverModalTitle">Add New Driver</h2>
                <button type="button" id="closeAddDriverModal" class="modal-close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="driver_id">
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
                
                {{-- PUTHU MAATRAM: The app credentials fields are removed as they are now auto-generated in the backend. --}}
                
            </div>
            <div class="modal-footer">
                <button type="button" id="cancelAddDriverModal" class="btn btn-outline">Cancel</button>
                <button type="submit" class="formbold-btn" id="driverModalSubmitBtn">Submit Driver</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteDriverModal" class="modal-overlay" style="display: none;">
    <div class="modal-content modal-content-alert">
        <div class="modal-header-alert">
            <div class="alert-icon-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle"><path d="m21.73 18-8-14a2 2 0 0 0-3.46 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
            </div>
            <h2 class="modal-title">Confirm Deletion</h2>
        </div>
        <div class="modal-body">
            <p class="alert-text-primary">
                Are you sure you want to delete this driver?
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
    <div class="modal-content modal-content-alert">
        <div class="modal-header-alert">
            <div class="alert-icon-wrapper" id="statusModalIcon" style="background-color: hsla(var(--warning), 0.15); border-color: hsla(var(--warning), 0.2);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="hsl(var(--warning))" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle"><path d="m21.73 18-8-14a2 2 0 0 0-3.46 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
            </div>
            <h2 class="modal-title">Confirm Action</h2>
        </div>
        <div class="modal-body"><p class="alert-text-primary" id="statusConfirmText">Are you sure you want to change this status?</p></div>
        <div class="modal-footer modal-footer-alert">
            <button type="button" id="cancelStatusChange" class="btn btn-outline">Cancel</button>
            <button type="button" id="confirmStatusChange" class="btn" style="background-color: hsl(var(--warning)); color: #000; border-color: hsl(var(--warning));">
                <span>Confirm</span>
            </button>
        </div>
    </div>
</div>

<div id="otpConfirmModal" class="modal-overlay" style="display: none;">
    <div class="modal-content modal-content-alert">
        <div class="modal-header-alert">
            <div class="alert-icon-wrapper" style="background-color: hsla(var(--warning), 0.15); border-color: hsla(var(--warning), 0.2);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="hsl(var(--warning))" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail-question">
                    <path d="M22 10.5V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h12.5"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                    <path d="M18 15.28c.2.4.5.7.9.9a2.1 2.1 0 0 1-2.1 3.5c-.6 0-1.2-.2-1.7-.5s-.8-1.3-1-2.3c-.2-1.1.4-2.2 1.3-2.8.5-.3.9-.4 1.3-.4.3 0 .6 0 .9.1Z"/>
                    <path d="M21.99 18.28a.5.5 0 1 0-.7-.7.5.5 0 1 0 .7.7Z"/>
                </svg>
            </div>
            <h2 class="modal-title">Confirm Verification</h2>
        </div>
        <div class="modal-body">
            <p class="alert-text-primary" id="otpConfirmPrimaryText">Send verification OTP to [email]?</p>
            <p class="alert-text-secondary">The driver will receive a 6-digit code.</p>
        </div>
        <div class="modal-footer modal-footer-alert">
            <button type="button" id="cancelOtpConfirm" class="btn btn-outline">Cancel</button>
            <button type="button" id="confirmOtpSend" class="btn" style="background-color: hsl(var(--warning)); color: #000; border-color: hsl(var(--warning));">
                <span>Send OTP</span>
            </button>
        </div>
    </div>
</div>

<div id="otpModal" class="modal-overlay" style="display: none;">
    <div class="modal-content modal-content-alert" style="max-width: 480px;">
        <form id="otpForm">
            @csrf
            <div class="modal-header-alert">
                <div class="alert-icon-wrapper" style="background-color: hsla(var(--primary), 0.15); border-color: hsla(var(--primary), 0.2);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="hsl(var(--primary))" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-key-round">
                        <path d="M2 18v3c0 .6.4 1 1 1h4v-3h3v-3h2l1.4-1.4a6.5 6.5 0 1 0-4-4Z"/><circle cx="16.5" cy="7.5" r="2.5"/>
                    </svg>
                </div>
                <h2 class="modal-title">Enter Verification Code</h2>
            </div>
            <div class="modal-body">
                <input type="hidden" id="otp_user_id" name="user_id">
                <p class="alert-text-secondary" id="otpMessage" style="color: hsl(var(--foreground));">An OTP has been sent to the driver's email.</p>
                
                <div class="form-group" style="margin-top: 1.5rem; text-align: left;">
                    <label for="otp_code" class="formbold-form-label" style="text-align: left;">Enter 6-Digit OTP</label>
                    <input type="text" id="otp_code" name="otp_code" class="formbold-form-input" 
                           style="font-size: 1.5rem; text-align: center; letter-spacing: 0.5rem;" 
                           maxlength="6" required>
                    <small id="otpError" class="text-danger-inline" style="text-align: center; margin-top: 10px;"></small>
                </div>
                
                <div style="margin-top: 1rem; font-size: 0.875rem; color: hsl(var(--muted-foreground));">
                    Didn't receive the code? 
                    <button type="button" id="otpResendBtn" class="btn-link" disabled style="background: none; border: none; color: hsl(var(--primary)); cursor: pointer; text-decoration: underline; padding: 0;">Resend</button>
                    <span id="otpTimer" style="margin-left: 0.5rem;"></span>
                </div>
            </div>
            <div class="modal-footer modal-footer-alert">
                <button type="button" id="closeOtpModal" class="btn btn-outline">Cancel</button>
                <button type="submit" class="formbold-btn">Verify & Activate</button>
            </div>
        </form>
    </div>
</div>

{{-- PUTHU MAATRAM: Reset Password Modal text updated --}}
<div id="resetPasswordModal" class="modal-overlay" style="display: none;">
    <div class="modal-content modal-content-alert">
        <div class="modal-header-alert">
            <div class="alert-icon-wrapper" style="background-color: hsla(var(--warning), 0.15); border-color: hsla(var(--warning), 0.2);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="hsl(var(--warning))" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-key-round">
                    <path d="M2 18v3c0 .6.4 1 1 1h4v-3h3v-3h2l1.4-1.4a6.5 6.5 0 1 0-4-4Z"/><circle cx="16.5" cy="7.5" r="2.5"/>
                </svg>
            </div>
            <h2 class="modal-title">Confirm Password Reset</h2>
        </div>
        <div class="modal-body">
            <p class="alert-text-primary" id="resetPasswordText">Are you sure you want to reset the password for [Driver Name]?</p>
            <p class="alert-text-secondary">
                A new password (Format: Name@1234) will be generated. The driver will be marked as 'Not Verified' and must be activated again using OTP.
            </p>
        </div>
        <div class="modal-footer modal-footer-alert">
            <button type="button" id="cancelResetPassword" class="btn btn-outline">Cancel</button>
            <button type="button" id="confirmResetPassword" class="btn" style="background-color: hsl(var(--warning)); color: #000; border-color: hsl(var(--warning));">
                <span>Reset Password</span>
            </button>
        </div>
    </div>
</div>

{{-- This modal is no longer needed as we email the password --}}
{{-- <div id="showNewPasswordModal" ... > ... </div> --}}
<div id="showNewPasswordModal" class="modal-overlay" style="display: none;">
    <div class="modal-content modal-content-alert">
        <div class="modal-header-alert">
            <div class="alert-icon-wrapper" style="background-color: hsla(var(--success), 0.15); border-color: hsla(var(--success), 0.2);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="hsl(var(--success))" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-check"><path d="M18 6 7 17l-5-5"/><path d="m22 10-7.5 7.5L13 16"/></svg>
            </div>
            <h2 class="modal-title">Password Reset Successful</h2>
        </div>
        <div class="modal-body">
            <p class="alert-text-secondary" style="margin-top: 0;">Please copy the new password and share it with the driver.</p>
            <div class="form-group" style="margin-top: 1.5rem; text-align: left;">
                <label for="newPasswordText" class="formbold-form-label" style="text-align: left;">New Generated Password:</label>
                <input type="text" id="newPasswordText" class="formbold-form-input" 
                       style="font-size: 1.5rem; text-align: center; letter-spacing: 0.5rem; background-color: hsl(var(--background));" 
                       readonly>
            </div>
        </div>
        <div class="modal-footer modal-footer-alert">
            <button type="button" id="closeNewPasswordModal" class="btn btn-outline">Close</button>
        </div>
    </div>
</div>

@endsection