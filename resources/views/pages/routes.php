<?php 
// PUTHU MAATRAM: 'header.php'-ah remove pannittom
?>

<?php // Namma content inga thaan start aaguthu nu solrom ?>
<?php $__env->startSection('content'); ?>

<div id="alert-container" class="alert-container-global"></div>

<div class="page-header drivers-page-header">
    <div class="header-left-content">
        <h1 class="page-title">Manage Routes</h1>
        <p class="page-subtitle">Get an overview of route details and manage them efficiently.</p>
        <p class="page-header-note">
            Below is a list of all registered routes. Use the "Add Route" button to register a new one.
        </p>
    </div>
    <div class="header-right-actions">
        <a href="javascript:void(0);" id="openAddRouteModal" class="add-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            <span>Add Route</span>
        </a>
    </div>
</div>

<div class="page-container">
    <table id="datatable" class="table">
        <thead>
            <tr>
                <th>Route Name</th>
                <th>Start Point</th>
                <th>End Point</th>
                <th>Assigned Bus</th>
                <?php // <th>Status</th> <-- MAATRAM: Status column-ah remove pannitom ?>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                // Intha $routes variable ippo namma puthu
                // RoutesController la irunthu (Model vaziya) varuthu
                // Ensure $routes is an array/collection
                $routes = $routes ?? [];
                // Handle both arrays and collections
                $isEmpty = false;
                if (empty($routes)) {
                    $isEmpty = true;
                } elseif (is_object($routes) && method_exists($routes, 'isEmpty')) {
                    $isEmpty = $routes->isEmpty();
                } elseif (is_object($routes) && method_exists($routes, 'count')) {
                    $isEmpty = $routes->count() === 0;
                }
                
                if ($isEmpty):
            ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 3rem; color: hsl(var(--muted-foreground));">
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.5;">
                                <path d="M3 12a9 9 0 1 0 18 0 9 9 0 0 0-18 0"/><path d="M12 8v8"/><path d="m8 12 4-4 4 4"/>
                            </svg>
                            <div style="font-size: 1.125rem; font-weight: 600;">No routes found</div>
                            <div style="font-size: 0.875rem;">Click "Add Route" to create your first route.</div>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($routes as $route): ?>
                <tr>
                    <td>
                        <?php // PUTHU MAATRAM: Namma ippo object syntax (->) use panrom ?>
                        <span style="font-weight: 600;"><?php echo htmlspecialchars($route->name); ?></span>
                    </td>
                    <td><?php echo htmlspecialchars($route->start); ?></td>
                    <td><?php echo htmlspecialchars($route->end); ?></td>
                    <td>
                        <span class="bus-tag"><?php echo htmlspecialchars($route->bus_plate ?? 'N/A'); ?></span>
                    </td>
                    <?php 
                    // Status badge logic ippo theva illai
                    /*
                    <td>
                        <?php
                            $status_class = ($route->status === 'active') ? 'badge-success' : 'badge-secondary';
                        ?>
                        <span class="badge <?php echo $status_class; ?>">
                            <?php echo htmlspecialchars($route->status); ?>
                        </span>
                    </td>
                    */ ?>
                    <td>
                        <div class="action-buttons-wrapper">
                             <a href="javascript:void(0);" 
                               class="btn-action-edit js-edit-route"
                               data-id="<?php echo $route->id; ?>"
                               data-name="<?php echo htmlspecialchars($route->name); ?>"
                               data-start="<?php echo htmlspecialchars($route->start); ?>"
                               data-end="<?php echo htmlspecialchars($route->end); ?>"
                               data-bus="<?php echo htmlspecialchars($route->bus_plate ?? ''); ?>"
                               data-status="<?php echo htmlspecialchars($route->status); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                <span>Edit</span>
                            </a>
                             <a href="javascript:void(0);" 
                               class="btn-action-delete js-delete-route" 
                               data-delete-id="<?php echo $route->id; ?>"
                               data-delete-url="/routes/delete"> <?php // PUTHU MAATRAM: DELETE ID-AH SEPARATE PANNITOM ?>
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

<div id="addRouteModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <form id="routeForm" action="/routes/create" method="POST">
            <div class="modal-header">
                <h2 class="modal-title" id="routeModalTitle">Add New Route</h2>
                <button type="button" id="closeAddRouteModal" class="modal-close-btn">&times;</button>
            </div>
            <div class="modal-body">
                
                <input type="hidden" name="id" id="route_id">

                <div class="formbold-input-flex">
                    <div>
                        <label for="start" class="formbold-form-label">Start Point</label>
                        <input type="text" name="start" id="start" class="formbold-form-input" required />
                        <small id="startError" class="text-danger-inline"></small>
                    </div>
                    <div>
                        <label for="end" class="formbold-form-label">End Point</label>
                        <input type="text" name="end" id="end" class="formbold-form-input" required />
                        <small id="endError" class="text-danger-inline"></small>
                    </div>
                </div>
                <div class="formbold-mb-3">
                    <label for="bus" class="formbold-form-label">Assigned Bus</label>
                    
                    <select name="bus" id="bus" class="formbold-form-input" required>
                        <option value="" disabled selected>Select a bus</option>
                        
                        <?php 
                            // Intha $buses variable ippo namma
                            // RoutesController la irunthu varuthu
                            foreach ($buses as $bus): 
                        ?>
                            <?php // PUTHU MAATRAM: Object syntax (->) use panrom ?>
                            <option value="<?php echo htmlspecialchars($bus->plate); ?>">
                                <?php echo htmlspecialchars($bus->plate); ?> (<?php echo htmlspecialchars($bus->name); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <small id="busError" class="text-danger-inline"></small>
                </div>
                 </div>
            <div class="modal-footer">
                <button type="button" id="cancelAddRouteModal" class="btn btn-outline">Cancel</button>
                <button type="submit" class="formbold-btn" id="routeModalSubmitBtn">Submit Route</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteRouteModal" class="modal-overlay" style="display: none;">
    <div class="modal-content modal-content-alert">
        <div class="modal-header-alert">
            <div class="alert-icon-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle"><path d="m21.73 18-8-14a2 2 0 0 0-3.46 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
            </div>
            <h2 class="modal-title">Confirm Deletion</h2>
        </div>
        <div class="modal-body">
            <p class="alert-text-primary">Are you sure you want to delete this route?</p>
            <p class="alert-text-secondary">This action cannot be undone.</p>
        </div>
        <div class="modal-footer modal-footer-alert">
            <button type="button" id="cancelDeleteModal" class="btn btn-outline">Cancel</button>
            <button type="button" id="confirmDeleteBtn" class="btn btn-destructive" data-id=""> <?php // PUTHU MAATRAM: data-url-ku pathila data-id use panrom ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                <span>Delete</span>
            </button>
        </div>
    </div>
</div>

<?php // Namma content inga mudiyuthu nu solrom ?>
<?php $__env->stopSection(); ?>

<?php 
// PUTHU MAATRAM: Namma 'master.php' layout-ah inga render panrom
// 'footer.php'-ah remove pannittom
echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); 
?>