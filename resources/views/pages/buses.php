<?php 
// PUTHU MAATRAM: Namma Header-ah inga irunthu remove pannitom
?>

<?php // Namma content inga thaan start aaguthu nu solrom ?>
<?php $__env->startSection('content'); ?>

<div id="alert-container" class="alert-container-global"></div>

<div class="page-header drivers-page-header"> 
    <div class="header-left-content">
        <h1 class="page-title">Manage Buses</h1> 
        <p class="page-subtitle">Easily track and manage all the buses in your fleet.</p> 
        <p class="page-header-note">
            Below is a list of all registered buses. Use the "Add Bus" button to add a new one to the fleet.
        </p>
    </div>
    <div class="header-right-actions">
        <a href="javascript:void(0);" id="openAddBusModal" class="add-button"> 
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            <span>Add Bus</span>
        </a>
    </div>
</div>
<div class="page-container">
    <table id="datatable" class="table">
        <thead>
            <tr>
                <th style="width: 80px; text-align: center;">Image</th> 
                <th>Bus Name</th>
                <th>Plate Number</th>
                <th style="text-align: center;">Route</th> 
                <th>Capacity</th>
                <th>Assigned Driver</th> <?php // <-- PUTHU COLUMN ?>
                <?php // <th>Status</th> <-- MAATRAM: Status column-ah remove pannitom ?>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                // Intha $buses variable ippo namma puthu
                // BusesController la irunthu (DB facade vaziya) varuthu
                // Ensure $buses is an array/collection
                $buses = $buses ?? [];
                // Handle both arrays and collections
                $isEmpty = false;
                if (empty($buses)) {
                    $isEmpty = true;
                } elseif (is_object($buses) && method_exists($buses, 'isEmpty')) {
                    $isEmpty = $buses->isEmpty();
                } elseif (is_object($buses) && method_exists($buses, 'count')) {
                    $isEmpty = $buses->count() === 0;
                }
                
                if ($isEmpty):
            ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 3rem; color: hsl(var(--muted-foreground));">
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.5;">
                                <path d="M8 6v6"/><path d="M15 6v6"/><path d="M2 12h19.6"/><path d="M18 18h3s-1-1.5-1.5-2.5S19 14 19 14"/><path d="M6 18H3s1-1.5 1.5-2.5S5 14 5 14"/><rect width="20" height="10" x="2" y="8" rx="2"/>
                            </svg>
                            <div style="font-size: 1.125rem; font-weight: 600;">No buses found</div>
                            <div style="font-size: 0.875rem;">Click "Add Bus" to add your first bus to the fleet.</div>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($buses as $bus): ?>
                <tr>
                    <td>
                        <?php // PUTHU MAATRAM: Namma ippo object syntax (->) use panrom ?>
                        <?php if (!empty($bus->photo_path)): ?>
                            <img src="<?php echo htmlspecialchars($bus->photo_path); ?>" 
                                 alt="<?php echo htmlspecialchars($bus->name); ?>" 
                                 class="avatar" style="object-fit: cover;">
                        <?php else: ?>
                            <img src="https://api.dicebear.com/7.x/shapes/svg?seed=<?php echo htmlspecialchars($bus->plate); ?>&backgroundColor=282c34&shape1Color=86efac&shape2Color=e0e0e0" 
                                 alt="Default Bus" 
                                 class="avatar">
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($bus->name); ?></td>
                    <td>
                        <span class="bus-tag"><?php echo htmlspecialchars($bus->plate); ?></span>
                    </td>
                    
                    <td style="vertical-align: middle;">
                        <div class="route-display">
                            <?php // Note: $bus->start and $bus->end ippo JOIN vaziya varuthu ?>
                            <span class="route-point route-start" title="<?php echo htmlspecialchars($bus->start ?? 'N/A'); ?>">
                                <?php echo htmlspecialchars($bus->start ?? 'N/A'); ?>
                            </span>
                            
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="route-arrow">
                                <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                            </svg>
                            
                            <span class="route-point route-end" title="<?php echo htmlspecialchars($bus->end ?? 'N/A'); ?>">
                                <?php echo htmlspecialchars($bus->end ?? 'N/A'); ?>
                            </span>
                        </div>
                    </td>
                    
                    <td><?php echo htmlspecialchars($bus->capacity); ?> seats</td>
                    
                    <td>
                        <?php 
                            // Assigned driver name-ah kaatrom
                            $driver_name = $bus->driver_name ?? 'Unassigned';
                            $badge_class = $bus->driver_name ? 'badge-info' : 'badge-secondary';
                        ?>
                        <span class="badge <?php echo $badge_class; ?>">
                            <?php echo htmlspecialchars($driver_name); ?>
                        </span>
                    </td>

                    <td>
                        <div class="action-buttons-wrapper">
                            <a href="javascript:void(0);" 
                               class="btn-action-edit js-edit-bus"
                               data-id="<?php echo $bus->id; ?>"
                               data-name="<?php echo htmlspecialchars($bus->name); ?>"
                               data-plate="<?php echo htmlspecialchars($bus->plate); ?>"
                               data-capacity="<?php echo htmlspecialchars($bus->capacity); ?>"
                               data-driver-id="<?php echo htmlspecialchars($bus->driver_id ?? ''); ?>" <?php // <-- PUTHU ATTRIBUTE ?>
                               data-photo="<?php echo htmlspecialchars($bus->photo_path ?? ''); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                <span>Edit</span>
                            </a>
                            <a href="javascript:void(0);" 
                               class="btn-action-delete js-delete-bus" 
                               data-delete-id="<?php echo $bus->id; ?>"
                               data-delete-url="/buses/delete"> <?php // PUTHU MAATRAM: DELETE ID-AH SEPARATE PANNITOM ?>
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

<div id="addBusModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <form id="busForm" action="/buses/create" method="POST" enctype="multipart/form-data">
            <div class="modal-header">
                <h2 class="modal-title" id="busModalTitle">Add New Bus</h2>
                <button type="button" id="closeAddBusModal" class="modal-close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="bus_id">
                <div class="formbold-mb-3" id="photo-upload-wrapper">
                    <div id="photo-preview" title="Click to upload photo"></div>
                    <input type="file" name="photo" id="photo" accept="image/*" style="display: none;">
                    <button type="button" id="upload-button" class="btn btn-outline">Upload Bus Image</button>
                </div>
                <small id="photoError" class="text-danger-inline" style="margin-top: -10px; margin-bottom: 15px;"></small>
                <div class="formbold-mb-3 name-field-wrapper">
                    <label for="name" class="formbold-form-label">Bus Name / Model</label>
                    <input type="text" name="name" id="name" class="formbold-form-input" required />
                    <small id="nameError" class="text-danger-inline"></small>
                </div>
                <div class="formbold-input-flex">
                    <div>
                        <label for="plate" class="formbold-form-label">Number Plate</label>
                        <input type="text" name="plate" id="plate" class="formbold-form-input" placeholder="e.g., TN-01-AB-1234" required />
                        <small id="plateError" class="text-danger-inline"></small>
                    </div>
                    <div>
                        <label for="capacity" class="formbold-form-label">Capacity (Seats)</label>
                        <input type="number" name="capacity" id="capacity" class="formbold-form-input" required />
                        <small id="capacityError" class="text-danger-inline"></small>
                    </div>
                </div>
                
                <?php // --- PUTHU FIELD: DRIVER ASSIGNMENT --- ?>
                <div class="formbold-mb-3 name-field-wrapper">
                    <label for="driver" class="formbold-form-label">Assigned Driver (Edit Only)</label>
                    <select name="driver" id="driver" class="formbold-form-input">
                        <option value="" selected>--- Select a Driver ---</option>
                        <?php 
                            // $available_drivers ippo Controller la irundhu varuthu
                            foreach ($available_drivers as $driver): 
                        ?>
                            <option value="<?php echo htmlspecialchars($driver->id); ?>">
                                <?php echo htmlspecialchars($driver->name); ?> (<?php echo htmlspecialchars($driver->phone); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small id="driverError" class="text-danger-inline"></small>
                </div>
                <?php // --- PUTHU FIELD MUDINJATHU --- ?>
                
            </div>
            <div class="modal-footer">
                <button type="button" id="cancelAddBusModal" class="btn btn-outline">Cancel</button>
                <button type="submit" class="formbold-btn" id="busModalSubmitBtn">Submit Bus</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteBusModal" class="modal-overlay" style="display: none;">
    <div class="modal-content modal-content-alert">
        <div class="modal-header-alert">
            <div class="alert-icon-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle"><path d="m21.73 18-8-14a2 2 0 0 0-3.46 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
            </div>
            <h2 class="modal-title">Confirm Deletion</h2>
        </div>
        <div class="modal-body">
            <p class="alert-text-primary">Are you sure you want to delete this bus?</p>
            <p class="alert-text-secondary">This action cannot be undone.</p>
        </div>
        <div class="modal-footer modal-footer-alert">
            <button type="button" id="cancelDeleteBusModal" class="btn btn-outline">Cancel</button>
            <button type="button" id="confirmDeleteBusBtn" class="btn btn-destructive" data-id=""> <?php // PUTHU MAATRAM: data-url-ku pathila data-id use panrom ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                <span>Delete</span>
            </button>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php 
// PUTHU MAATRAM: Namma 'master.php' layout-ah inga render panrom
// 'footer.php'-ah remove pannittom
echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); 
?>