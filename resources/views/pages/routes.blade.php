@extends('layouts.master')

@section('content')

<div id="alert-container" class="alert-container-global"></div>

<div class="page-header drivers-page-header">
    <div class="header-left-content">
        <h1 class="page-title">Manage Routes</h1>
        <p class="page-subtitle">Manage bus routes and assign them to buses.</p>
        <p class="page-header-note">
            Below is a list of all registered routes. Use the "Add Route" button to register a new one.
        </p>
    </div>
    <div class="header-right-actions">
        <a href="javascript:void(0);" id="openAddRouteModal" class="add-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            <span>Add Route</span>
        </a>
    </div>
</div>

<div class="page-container">
    <div class="table-container fade-in">
        <table id="datatable" class="table">
            <thead>
                <tr>
                    <th>Route Details</th>
                    <th>Path (Start <span style="color: var(--text-muted);">→</span> End)</th>
                    <th>Assigned Bus</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($routes as $route)
                    <tr>
                        <td>
                            <div class="table-profile">
                                <div class="table-avatar" style="background: rgba(108, 99, 255, 0.1); color: var(--accent); border: 1px solid rgba(108, 99, 255, 0.2);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20a1 1 0 0 1-1-1v-1a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1h-1Z"/><path d="M12 4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1h-1Z"/><path d="M19 12a1 1 0 0 1-1-1h-1a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v4Z"/><path d="M5 12a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H5Z"/><path d="M17.8 7.8a1 1 0 0 1-1.4 0l-.7-.7a1 1 0 0 1 0-1.4l.7-.7a1 1 0 0 1 1.4 0l.7.7a1 1 0 0 1 0 1.4l-.7.7Z"/><path d="M6.2 19.8a1 1 0 0 1-1.4 0l-.7-.7a1 1 0 0 1 0-1.4l.7-.7a1 1 0 0 1 1.4 0l.7.7a1 1 0 0 1 0 1.4l-.7.7Z"/><path d="M6.2 7.8a1 1 0 0 1 0 1.4l-.7.7a1 1 0 0 1-1.4 0l-.7-.7a1 1 0 0 1 0-1.4l.7-.7a1 1 0 0 1 1.4 0l.7.7Z"/><path d="M17.8 19.8a1 1 0 0 1 0 1.4l-.7.7a1 1 0 0 1-1.4 0l-.7-.7a1 1 0 0 1 0-1.4l.7-.7a1 1 0 0 1 1.4 0l.7.7Z"/></svg>
                                </div>
                                <div class="table-info">
                                    <h4>{{ htmlspecialchars($route->name) }}</h4>
                                    <p>Route ID: #{{ $route->id }}</p>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="info-cell-group">
                                <div class="info-primary" style="display: flex; align-items: center; gap: 8px;">
                                    <span style="color: var(--success);">●</span> {{ htmlspecialchars($route->start) }}
                                </div>
                                <div style="padding-left: 5px; border-left: 1px dashed var(--border); margin-left: 3.5px; height: 12px;"></div>
                                <div class="info-primary" style="display: flex; align-items: center; gap: 8px;">
                                    <span style="color: var(--danger);">📍</span> {{ htmlspecialchars($route->end) }}
                                </div>
                            </div>
                        </td>

                        <td>
                            @if ($route->bus)
                                <div class="table-profile" style="gap: 0.75rem;">
                                    <div class="table-avatar" style="width: 32px; height: 32px; font-size: 0.75rem; background: var(--bg-hover); border-color: var(--border);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6v6"/><path d="M15 6v6"/><path d="M2 12h19.6"/><path d="M18 18h3s-1-1.5-1.5-2.5S19 14 19 14"/><path d="M6 18H3s1-1.5 1.5-2.5S5 14 5 14"/><rect width="20" height="10" x="2" y="8" rx="2"/></svg>
                                    </div>
                                    <div class="table-info">
                                        <h4 style="font-size: 0.9rem;">{{ htmlspecialchars($route->bus->name) }}</h4>
                                        <p class="status-badge status-active" style="padding: 2px 8px; font-size: 0.7rem; border-radius: 4px; display: inline-block;">{{ htmlspecialchars($route->bus_plate) }}</p>
                                    </div>
                                </div>
                            @else
                                <span class="status-badge status-inactive" style="background: rgba(255, 255, 255, 0.05); color: var(--text-secondary); border: 1px solid var(--border);">
                                    Not Assigned
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="action-buttons">
                                <button type="button" 
                                   class="action-btn js-edit-route"
                                   data-id="{{ $route->id }}"
                                   data-name="{{ htmlspecialchars($route->name) }}"
                                   data-start="{{ htmlspecialchars($route->start) }}"
                                   data-end="{{ htmlspecialchars($route->end) }}"
                                   data-bus-plate="{{ htmlspecialchars($route->bus_plate ?? '') }}"
                                   title="Edit Route">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                </button>
                                
                                <button type="button" 
                                   class="action-btn delete-btn js-delete-route" 
                                   data-delete-id="{{ $route->id }}"
                                   data-delete-url="{{ url('/routes/delete') }}"
                                   title="Delete Route">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 4rem 2rem; color: var(--text-secondary);">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3;"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1Z"/><line x1="4" x2="4" y1="15" y2="21"/><line x1="12" x2="12" y1="15" y2="21"/><line x1="20" x2="20" y1="15" y2="21"/></svg>
                                <div style="font-size: 1.1rem; font-weight: 500;">No routes found</div>
                                <p style="font-size: 0.9rem; opacity: 0.7;">Click "Add Route" to create your first route.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="addRouteModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <form id="routeForm" action="{{ url('/routes/create') }}" method="POST">
            @csrf <div class="modal-header">
                <h2 class="modal-title" id="routeModalTitle">Add New Route</h2>
                <button type="button" id="closeAddRouteModal" class="modal-close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="route_id">
                
                <div class="formbold-mb-3">
                    <label for="name" class="formbold-form-label">Route Name</label>
                    <input type="text" name="name" id="name" class="formbold-form-input" required placeholder="e.g., Main Campus Route" />
                    <small id="nameError" class="text-danger-inline"></small>
                </div>

                <div class="formbold-input-flex">
                    <div>
                        <label for="start" class="formbold-form-label">Start Point</label>
                        <input type="text" name="start" id="start" class="formbold-form-input" required placeholder="e.g., Central Station" />
                        <small id="startError" class="text-danger-inline"></small>
                    </div>
                    <div>
                        <label for="end" class="formbold-form-label">End Point</label>
                        <input type="text" name="end" id="end" class="formbold-form-input" required placeholder="e.g., School Campus" />
                        <small id="endError" class="text-danger-inline"></small>
                    </div>
                </div>

                <div class="formbold-mb-3">
                    <label for="bus_plate" class="formbold-form-label">Assign Bus</label>
                    <select name="bus_plate" id="bus_plate" class="formbold-form-input" required>
                        <option value="">Select a bus</option>
                        @foreach ($buses as $bus)
                            <option value="{{ htmlspecialchars($bus->plate) }}">{{ htmlspecialchars($bus->name) }} ({{ htmlspecialchars($bus->plate) }})</option>
                        @endforeach
                    </select>
                    <small id="bus_plateError" class="text-danger-inline"></small>
                    <small class="form-note">A route must be assigned to a bus.</small>
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
            <button type="button" id="confirmDeleteBtn" class="btn btn-destructive" data-id="">
                <span>Delete</span>
            </button>
        </div>
    </div> 
</div>

@endsection