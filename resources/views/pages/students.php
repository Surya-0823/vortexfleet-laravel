<?php 
// PUTHU MAATRAM: 'header.php'-ah remove pannittom
?>

<?php // Namma content inga thaan start aaguthu nu solrom ?>
<?php $__env->startSection('content'); ?>

<div id="alert-container" class="alert-container-global"></div>

<div class="page-header drivers-page-header"> 
    <div class="header-left-content">
        <h1 class="page-title">Manage Students</h1>
        <p class="page-subtitle">View student details and manage their profiles.</p>
        <p class="page-header-note">
            The list below shows all registered students. Use the "Add Student" button to enroll a new one.
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
                <th>App Login</th> <?php // <-- PUTHU COLUMN HEADER ?>
                <th>Grade</th>
                <th>Route</th>
                <th>Verification</th> <?php // <-- MAATRAM: Status column name-ah maathitom ?>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                // Intha $students variable ippo namma puthu
                // StudentsController la irunthu (Model vaziya) varuthu
                $students = $students ?? [];
                $isEmpty = false;
                if (empty($students)) {
                    $isEmpty = true;
                } elseif (is_object($students) && method_exists($students, 'isEmpty')) {
                    $isEmpty = $students->isEmpty();
                } elseif (is_object($students) && method_exists($students, 'count')) {
                    $isEmpty = $students->count() === 0;
                }
                
                if ($isEmpty):
            ?>
                <tr>
                    <td colspan="8" style="text-align: center; padding: 3rem; color: hsl(var(--muted-foreground));">
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.5;">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                            <div style="font-size: 1.125rem; font-weight: 600;">No students found</div>
                            <div style="font-size: 0.875rem;">Click "Add Student" to enroll your first student.</div>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($students as $student): ?>
                <tr>
                    <td>
                        <?php if (!empty($student->photo_path)): ?>
                            <img src="<?php echo htmlspecialchars($student->photo_path); ?>" 
                                 alt="<?php echo htmlspecialchars($student->name); ?>" 
                                 class="avatar" style="object-fit: cover;">
                        <?php else: ?>
                            <img src="https://api.dicebear.com/7.x/initials/svg?seed=<?php echo htmlspecialchars($student->name); ?>&backgroundColor=282c34&fontColor=86efac" 
                                 alt="<?php echo htmlspecialchars($student->name); ?>" 
                                 class="avatar">
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($student->name); ?></td>
                    <td>
                        <div><?php echo htmlspecialchars($student->phone); ?></div>
                        <div class="text-muted"><?php echo htmlspecialchars($student->email); ?></div>
                    </td>

                    <td>
                        <div class="text-muted"><?php echo htmlspecialchars($student->app_username ?? 'N/A'); ?></div>
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
                        <div class="text-muted"><?php echo htmlspecialchars($student->grade); ?> Grade</div>
                    </td>
                    
                    
                    
                    <td>
                        <?php 
                            // Namma ippo $student->route object-ah access panrom
                            // Ithu namma eager loading-la irunthu varuthu
                            if ($student->route): 
                        ?>
                            <div style="font-weight: 500;"><?php echo htmlspecialchars($student->route->name ?? 'N/A'); ?></div>
                            <div class="text-muted" style="font-size: 0.8rem;">
                                <?php echo htmlspecialchars($student->route->start ?? '...'); ?> to 
                                <?php echo htmlspecialchars($student->route->end ?? '...'); ?>
                            </div>
                        <?php else: ?>
                            <div style="font-weight: 500;"><?php echo htmlspecialchars($student->route_name ?? 'N/A'); ?></div>
                            <div class="text-muted" style="font-size: 0.8rem;">(Route not found)</div>
                        <?php endif; ?>
                    </td>
                    <td style="width: 150px;"> 
                        <div class="status-cell-wrapper">
                            <?php
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
                            ?>
                            <button class="btn-verify <?php echo $btn_class; ?> <?php echo $js_class; ?>"
                                    data-user-id="<?php echo $student->id; ?>"
                                    data-user-name="<?php echo htmlspecialchars($student->name); ?>"
                                    <?php echo $email_attr; ?>>
                                
                                <?php if ($student->is_verified == 1): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                <?php endif; ?>
                                
                                <span><?php echo $btn_text; ?></span>

                                <?php if ($student->is_verified != 1): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                                <?php endif; ?>
                            </button>
                        </div>
                    </td>

                    <td>
                        <div class="action-buttons-wrapper">
                             <a href="javascript:void(0);" 
                               class="btn-action-edit js-edit-student"
                               data-id="<?php echo $student->id; ?>"
                               data-name="<?php echo htmlspecialchars($student->name); ?>"
                               data-email="<?php echo htmlspecialchars($student->email); ?>"
                               data-phone="<?php echo htmlspecialchars($student->phone); ?>"
                               data-grade="<?php echo htmlspecialchars($student->grade); ?>"
                               data-route="<?php echo htmlspecialchars($student->route_name ?? ''); ?>"
                               data-photo="<?php echo htmlspecialchars($student->photo_path ?? ''); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                <span>Edit</span>
                            </a>
                            
                            <a href="javascript:void(0);" 
                               class="btn-action-reset js-reset-password"
                               data-id="<?php echo $student->id; ?>"
                               data-name="<?php echo htmlspecialchars($student->name); ?>"
                               title="Reset Password">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-key-round"><path d="M2 18v3c0 .6.4 1 1 1h4v-3h3v-3h2l1.4-1.4a6.5 6.5 0 1 0-4-4Z"/><circle cx="16.5" cy="7.5" r="2.5"/></svg>
                                <span>Reset</span>
                            </a>
                            <a href="javascript:void(0);" 
                               class="btn-action-delete js-delete-student" 
                               data-delete-id="<?php echo $student->id; ?>"
                               data-delete-url="/students/delete"> 
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                <span>Delete</span>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div id="addStudentModal" class="modal-overlay" style="display: none;">
    
    <div class="modal-content">
        
        <form id="studentForm" action="/students/create" method="POST" enctype="multipart/form-data">
            
            <div class="modal-header">
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
                        <label for="email" class="formbold-form-label">Email (This will be App Username)</label>
                        <input type="email" name="email" id="email" class="formbold-form-input" required />
                        <small id="emailError" class="text-danger-inline"></small>
                    </div>
                    <div>
                        <label for="phone" class="formbold-form-label">Phone Number</label>
                        <input type="tel" name="phone" id="phone" class="formbold-form-input" required />
                        <small id="phoneError" class="text-danger-inline"></small>
                    </div>
                </div>

                <div class="formbold-input-flex">
                     <div>
                        <label for="grade" class="formbold-form-label">Grade</label>
                        <select name="grade" id="grade" class="formbold-form-input" required>
                            <option value="" disabled selected>Select a grade</option>
                            <option value="1st">1st</option>
                            <option value="2nd">2nd</option>
                            <option value="3rd">3rd</option>
                            <option value="4th">4th</option>
                            <option value="5th">5th</option>
                            <option value="6th">6th</option>
                            <option value="7th">7th</option>
                            <option value="8th">8th</option>
                            <option value="9th">9th</option>
                            <option value="10th">10th</option>
                            <option value="11th">11th</option>
                            <option value="12th">12th</option>
                        </select>
                        <small id="gradeError" class="text-danger-inline"></small>
                    </div>
                </div>
                 
                 <div class="formbold-mb-3 name-field-wrapper">
                    <label for="route" class="formbold-form-label">Bus Route</label>
                    <select name="route" id="route" class="formbold-form-input" required>
                        <option value="" disabled selected>Select a route</option>
                        <?php 
                            foreach ($routes as $route): 
                        ?>
                            <option value="<?php echo htmlspecialchars($route->name); ?>">
                                <?php echo htmlspecialchars($route->name); ?> (<?php echo htmlspecialchars($route->bus_name); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small id="routeError" class="text-danger-inline"></small>
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
        <div class="modal-body"><p class="alert-text-primary">Are you sure you want to delete this student?</p><p class="alert-text-secondary">This action cannot be undone.</p></div>
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
            <div class="alert-icon-wrapper" style="background-color: hsla(var(--warning), 0.15); border-color: hsla(var(--warning), 0.2);">
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
            <p class="alert-text-secondary">The student will receive a 6-digit code.</p>
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
                <p class="alert-text-secondary" id="otpMessage" style="color: hsl(var(--foreground));">An OTP has been sent to the student's email.</p>
                
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
            <p class="alert-text-primary" id="resetPasswordText">Are you sure you want to reset the password for [Student Name]?</p>
            <p class="alert-text-secondary">A new 6-character password will be generated and their account will be unlocked.</p>
        </div>
        <div class="modal-footer modal-footer-alert">
            <button type="button" id="cancelResetPassword" class="btn btn-outline">Cancel</button>
            <button type="button" id="confirmResetPassword" class="btn" style="background-color: hsl(var(--warning)); color: #000; border-color: hsl(var(--warning));">
                <span>Reset Password</span>
            </button>
        </div>
    </div>
</div>


<div id="showNewPasswordModal" class="modal-overlay" style="display: none;">
    <div class="modal-content modal-content-alert">
        <div class="modal-header-alert">
            <div class="alert-icon-wrapper" style="background-color: hsla(var(--success), 0.15); border-color: hsla(var(--success), 0.2);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="hsl(var(--success))" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-check"><path d="M18 6 7 17l-5-5"/><path d="m22 10-7.5 7.5L13 16"/></svg>
            </div>
            <h2 class="modal-title">Password Reset Successful</h2>
        </div>
        <div class="modal-body">
            <p class="alert-text-secondary" style="margin-top: 0;">Please copy the new password and share it with the student.</p>
            <div class="form-group" style="margin-top: 1.5rem; text-align: left;">
                <label for="otp_code" class="formbold-form-label" style="text-align: left;">New Generated Password:</label>
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
<?php $__env->stopSection(); ?>

<?php 
// PUTHU MAATRAM: Namma 'master.php' layout-ah inga render panrom
// 'footer.php'-ah remove pannittom
echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); 
?>