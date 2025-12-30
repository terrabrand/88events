@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-foreground">Subscription Plans</h2>
            <p class="text-muted-foreground mt-1">Manage tiers and usage limits for organizers.</p>
        </div>
        <button class="btn btn-primary" onclick="window.add_package_modal.showModal()">
            <span class="icon-[tabler--plus] mr-2 size-4"></span>
            Add New Plan
        </button>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-success/50 bg-success/10 p-4 text-success flex items-center gap-2">
            <span class="icon-[tabler--check] size-5"></span>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($packages as $package)
            <div class="rounded-xl border border-border bg-card shadow-sm flex flex-col">
                <div class="p-6 border-b border-border">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-xl font-bold">{{ $package->name }}</h3>
                        <span @class(['badge badge-sm', $package->is_active ? 'badge-success' : 'badge-soft text-muted-foreground'])>
                            {{ $package->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <p class="text-2xl font-bold text-primary">${{ number_format($package->price, 2) }}<span class="text-sm text-muted-foreground font-normal">/mo</span></p>
                </div>
                <div class="p-6 flex-1 space-y-4">
                    <p class="text-sm text-muted-foreground line-clamp-2">{{ $package->description }}</p>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2 text-sm">
                            <span class="icon-[tabler--message-share] size-4 text-primary"></span>
                            <span class="font-medium">{{ number_format($package->sms_limit) }}</span> SMS Credits
                        </li>
                        <li class="flex items-center gap-2 text-sm">
                            <span class="icon-[tabler--mail] size-4 text-primary"></span>
                            <span class="font-medium">{{ number_format($package->email_limit) }}</span> Email Credits
                        </li>
                    </ul>
                </div>
                <div class="p-6 border-t border-border bg-muted/5 flex gap-2">
                    <button class="btn btn-outline btn-sm flex-1" onclick="editPackage({{ $package }})">
                        Edit Plan
                    </button>
                    <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" onsubmit="return confirm('Are you sure? This cannot be undone if no active subs exist.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline btn-sm btn-square text-destructive hover:bg-destructive/10">
                            <span class="icon-[tabler--trash] size-4"></span>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Add Package Modal -->
    <dialog id="add_package_modal" class="modal">
        <div class="modal-box max-w-md">
            <h3 class="font-bold text-lg mb-4">Add Subscription Plan</h3>
            <form action="{{ route('admin.packages.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-2">
                    <label class="text-sm font-medium">Plan Name</label>
                    <input type="text" name="name" class="input input-bordered w-full" required>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">Description</label>
                    <textarea name="description" class="textarea textarea-bordered w-full" rows="2"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Price ($)</label>
                        <input type="number" name="price" step="0.01" class="input input-bordered w-full" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Status</label>
                        <select name="is_active" class="select select-bordered w-full">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-medium">SMS Limit</label>
                        <input type="number" name="sms_limit" class="input input-bordered w-full" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Email Limit</label>
                        <input type="number" name="email_limit" class="input input-bordered w-full" required>
                    </div>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="window.add_package_modal.close()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Plan</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Edit Package Modal -->
    <dialog id="edit_package_modal" class="modal">
        <div class="modal-box max-w-md">
            <h3 class="font-bold text-lg mb-4">Edit Subscription Plan</h3>
            <form id="edit_package_form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="space-y-2">
                    <label class="text-sm font-medium">Plan Name</label>
                    <input type="text" name="name" id="edit_name" class="input input-bordered w-full" required>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">Description</label>
                    <textarea name="description" id="edit_description" class="textarea textarea-bordered w-full" rows="2"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Price ($)</label>
                        <input type="number" name="price" id="edit_price" step="0.01" class="input input-bordered w-full" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Status</label>
                        <select name="is_active" id="edit_active" class="select select-bordered w-full">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-medium">SMS Limit</label>
                        <input type="number" name="sms_limit" id="edit_sms" class="input input-bordered w-full" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Email Limit</label>
                        <input type="number" name="email_limit" id="edit_email" class="input input-bordered w-full" required>
                    </div>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="window.edit_package_modal.close()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Plan</button>
                </div>
            </form>
        </div>
    </dialog>
</div>

<script>
    function editPackage(package) {
        const form = document.getElementById('edit_package_form');
        form.action = `/admin/packages/${package.id}`;
        
        document.getElementById('edit_name').value = package.name;
        document.getElementById('edit_description').value = package.description || '';
        document.getElementById('edit_price').value = package.price;
        document.getElementById('edit_active').value = package.is_active ? "1" : "0";
        document.getElementById('edit_sms').value = package.sms_limit;
        document.getElementById('edit_email').value = package.email_limit;
        
        window.edit_package_modal.showModal();
    }
</script>
@endsection
