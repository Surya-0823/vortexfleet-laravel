@extends('layouts.master')

@section('content')

<div id="alert-container" class="alert-container-global"></div>

<div class="page-header drivers-page-header">
    <div class="header-left-content">
        <h1 class="page-title">Manage Buses</h1>
        <p class="page-subtitle">Manage bus fleet, details, and driver assignments.</p>
        <p class="page-header-note">
            Below is a list of all registered buses. Use the "Add Bus" button to register a new one.
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
                <th>Photo</th>
                <th>Bus Name</th>
                <th>Plate Number</th>
                <th>Capacity</th>
                <th>Assigned Route</th>
                <th>Assigned Driver</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($buses as $bus)
                <tr>
                    <td>
                        @if (!empty($bus->photo_path))
                            <img src="{{ asset(htmlspecialchars($bus->photo_path)) }}" 
                                 alt="{{ htmlspecialchars($bus->name) }}" 
                                 class="avatar" style="object-fit: cover;">
                        @else
                            <img src="https://api.dicebear.com/7.x/initials/svg?seed={{ htmlspecialchars($bus->plate) }}&backgroundColor=282c34&fontColor=86efac" 
                                 alt="{{ htmlspecialchars($bus->name) }}" 
                                 class="avatar">
                        @endif
                    </td>
                    <td>{{ htmlspecialchars($bus->name) }}</td>
                    <td>
                        <span class="plate-number">{{ htmlspecialchars($bus->plate) }}</span>
                    </td>
                    <td>{{ htmlspecialchars($bus->capacity) }}</td>
                    <td>
                        @if (!empty($bus->start) && !empty($bus->end))
                            {{ htmlspecialchars($bus->start) }} &rarr; {{ htmlspecialchars($bus->end) }}
                        @else
                            <span class="text-muted">Not Assigned</span>
                        @endif
                    </td>
                    <td>
                        @if (!empty($bus->driver_name))
                            {{ htmlspecialchars($bus->driver_name) }}
                        @else
                            <span class="text-muted">Not Assigned</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons-wrapper">
                             <a href="javascript:void(0);" 
                               class="btn-action-edit js-edit-bus"
                               data-id="{{ $bus->id }}"
                               data-name="{{ htmlspecialchars($bus->name) }}"
                               data-plate="{{ htmlspecialchars($bus->plate) }}"
                               data-capacity="{{ $bus->capacity }}"
                               data-driver-id="{{ $bus->driver_id ?? '' }}"
                               data-photo="{{ htmlspecialchars($bus->photo_path ?? '') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                <span>Edit</span>
                            </a>
                            <a href="javascript:void(0);" 
                               class="btn-action-delete js-delete-bus" 
                               data-delete-id="{{ $bus->id }}"
                               data-delete-url="{{ url('/buses/delete') }}"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
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
                                <path d="M8 6v6"/><path d="M15 6v6"/><path d="M2 12h19.6"/><path d="M18 18h3s-1-1.5-1.5-2.5S19 14 19 14"/><path d="M6 18H3s1-1.5 1.5-2.5S5 14 5 14"/><rect width="20" height="10" x="2" y="8" rx="2"/>
                            </svg>
                            <div style="font-size: 1.125rem; font-weight: 600;">No buses found</div>
                            <div style="font-size: 0.875rem;">Click "Add Bus" to register your first bus.</div>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div id="addBusModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <form id="busForm" action="{{ url('/buses/create') }}" method="POST" enctype="multipart/form-data">
            @csrf <div class="modal-header">
                <h2 class="modal-title" id="busModalTitle">Add New Bus</h2>
                <button type="button" id="closeAddBusModal" class="modal-close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="bus_id">
                <div class="formbold-mb-3" id="photo-upload-wrapper">
                    <div id="photo-preview" title="Click to upload photo"></div>
                    <input type="file" name="photo" id="photo" accept="image/*" style="display: none;">
                    <button type="button" id="upload-button" class="btn btn-outline">Upload Bus Photo</button>
                </div>
                <small id="photoError" class="text-danger-inline" style="margin-top: -10px; margin-bottom: 15px;"></small>
                
                <div class="formbold-mb-3">
                    <label for="name" class="formbold-form-label">Bus Name</label>
                    <input type="text" name="name" id="name" class="formbold-form-input" required placeholder="e.g., School Bus A" />
                    <small id="nameError" class="text-danger-inline"></small>
                </div>

                <div class="formbold-input-flex">
                    <div>
                        <label for="plate" class="formbold-form-label">License Plate</label>
                        <input type="text" name="plate" id="plate" class="formbold-form-input" required placeholder="e.g., TN 01 AB 1234" />
                        <small id="plateError" class="text-danger-inline"></small>
                    </div>
                    <div>
                        <label for="capacity" class="formbold-form-label">Capacity</label>
                        <input type="number" name="capacity" id="capacity" class="formbold-form-input" required placeholder="e.g., 40" min="1" />
                        <small id="capacityError" class="text-danger-inline"></small>
                    </div>
                </div>

                <div class="formbold-mb-3">
                    <label for="driver_id" class="formbold-form-label">Assign Driver (Optional)</label>
                    <select name="driver_id" id="driver_id" class="formbold-form-input">
                        <option value="">Select an available driver</option>
                        @foreach ($available_drivers as $driver)
                            <option value="{{ $driver->id }}">{{ htmlspecialchars($driver->name) }}</option>
                        @endforeach
                    </select>
                    <small id="driver_idError" class="text-danger-inline"></small>
                    <small class="form-note">Only drivers not currently assigned to a bus are shown here.</small>
                </div>
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
            <p class="alert-text-primary">
                Are you sure you want to delete this bus?
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