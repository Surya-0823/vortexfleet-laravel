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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($routes as $route)
                <tr>
                    <td>
                        <span class="plate-number">{{ htmlspecialchars($route->name) }}</span>
                    </td>
                    <td>{{ htmlspecialchars($route->start) }}</td>
                    <td>{{ htmlspecialchars($route->end) }}</td>
                    <td>
                        @if ($route->bus)
                            {{ htmlspecialchars($route->bus->name) }} ({{ htmlspecialchars($route->bus_plate) }})
                        @else
                            <span class="text-muted">Not Assigned</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons-wrapper">
                             <a href="javascript:void(0);" 
                               class="btn-action-edit js-edit-route"
                               data-id="{{ $route->id }}"
                               data-name="{{ htmlspecialchars($route->name) }}"
                               data-start="{{ htmlspecialchars($route->start) }}"
                               data-end="{{ htmlspecialchars($route->end) }}"
                               data-bus-plate="{{ htmlspecialchars($route->bus_plate ?? '') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                <span>Edit</span>
                            </a>
                            <a href="javascript:void(0);" 
                               class="btn-action-delete js-delete-route" 
                               data-delete-id="{{ $route->id }}"
                               data-delete-url="{{ url('/routes/delete') }}"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                <span>Delete</span>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 3rem; color: hsl(var(--muted-foreground));">
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.5;">
                                <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1Z"/><line x1="4" x2="4" y1="15" y2="21"/><line x1="12" x2="12" y1="15" y2="21"/><line x1="20" x2="20" y1="15" y2="21"/>
                            </svg>
                            <div style="font-size: 1.125rem; font-weight: 600;">No routes found</div>
                            <div style="font-size: 0.875rem;">Click "Add Route" to create your first route.</div>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
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
            <p class="alert-text-primary">
                Are you sure you want to delete this route?
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

@endsection