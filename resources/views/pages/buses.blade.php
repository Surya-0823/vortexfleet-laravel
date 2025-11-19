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
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            <span>Add Bus</span>
        </a>
    </div>
</div>

<div class="page-container">
    <div class="table-container fade-in">
        <table id="datatable" class="table">
            <thead>
                <tr>
                    <th>Bus Details</th>
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
                            <div class="table-profile">
                                <div class="table-avatar">
                                    @if (!empty($bus->photo_path))
                                        <img src="{{ asset(htmlspecialchars($bus->photo_path)) }}" alt="{{ htmlspecialchars($bus->name) }}">
                                    @else
                                        <img src="https://api.dicebear.com/7.x/initials/svg?seed={{ htmlspecialchars($bus->plate) }}&backgroundColor=282c34&fontColor=86efac" alt="{{ htmlspecialchars($bus->name) }}">
                                    @endif
                                </div>
                                <div class="table-info">
                                    <h4>{{ htmlspecialchars($bus->name) }}</h4>
                                    <p>{{ htmlspecialchars($bus->plate) }}</p>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="info-primary" style="font-weight: 600;">{{ htmlspecialchars($bus->capacity) }} Seats</div>
                        </td>

                        <td>
                            @if (!empty($bus->start) && !empty($bus->end))
                                <div class="info-cell-group">
                                    <div class="info-primary" style="font-size: 0.85rem;">
                                        {{ htmlspecialchars($bus->start) }} <span style="color: var(--accent);">→</span> {{ htmlspecialchars($bus->end) }}
                                    </div>
                                </div>
                            @else
                                <span class="status-badge status-inactive" style="background: rgba(255, 255, 255, 0.05); color: var(--text-secondary); border: 1px solid var(--border);">Not Assigned</span>
                            @endif
                        </td>

                        <td>
                            @if (!empty($bus->driver_name))
                                <div class="table-profile" style="gap: 0.5rem;">
                                    <div class="table-avatar" style="width: 28px; height: 28px; font-size: 0.7rem;">
                                        {{ strtoupper(substr($bus->driver_name, 0, 2)) }}
                                    </div>
                                    <span class="info-primary">{{ htmlspecialchars($bus->driver_name) }}</span>
                                </div>
                            @else
                                <span class="status-badge status-inactive" style="background: rgba(255, 255, 255, 0.05); color: var(--text-secondary); border: 1px solid var(--border);">No Driver</span>
                            @endif
                        </td>

                        <td>
                            <div class="action-buttons">
                                <button type="button" 
                                   class="action-btn js-edit-bus"
                                   data-id="{{ $bus->id }}"
                                   data-name="{{ htmlspecialchars($bus->name) }}"
                                   data-plate="{{ htmlspecialchars($bus->plate) }}"
                                   data-capacity="{{ $bus->capacity }}"
                                   data-driver-id="{{ $bus->driver_id ?? '' }}"
                                   data-photo="{{ htmlspecialchars($bus->photo_path ?? '') }}"
                                   title="Edit Bus">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                </button>
                                
                                <button type="button" 
                                   class="action-btn delete-btn js-delete-bus" 
                                   data-delete-id="{{ $bus->id }}"
                                   data-delete-url="{{ url('/buses/delete') }}"
                                   title="Delete Bus">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 4rem 2rem; color: var(--text-secondary);">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3;"><path d="M8 6v6"/><path d="M15 6v6"/><path d="M2 12h19.6"/><path d="M18 18h3s-1-1.5-1.5-2.5S19 14 19 14"/><path d="M6 18H3s1-1.5 1.5-2.5S5 14 5 14"/><rect width="20" height="10" x="2" y="8" rx="2"/></svg>
                                <div style="font-size: 1.1rem; font-weight: 500;">No buses found</div>
                                <p style="font-size: 0.9rem; opacity: 0.7;">Click "Add Bus" to get started.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
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
            <p class="alert-text-primary">Are you sure you want to delete this bus?</p>
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