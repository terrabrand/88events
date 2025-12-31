@extends('layouts.app')

@section('title', 'Manage Ad Packages')

@section('content')
<div class="mx-auto max-w-6xl space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black tracking-tight text-foreground">Ad Packages</h2>
            <p class="text-muted-foreground mt-1 text-lg">Define and manage promotional tiers for event organizers.</p>
        </div>
        <button class="btn btn-primary shadow-lg shadow-primary/25 rounded-full px-6 transition-transform active:scale-95" onclick="window.add_package_modal.showModal()">
            <span class="icon-[tabler--plus] mr-2 size-5"></span>
            Create Package
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm border-success/20">
            <span class="icon-[tabler--check] size-5"></span>
            {{ session('success') }}
        </div>
    @endif

    @if($packages->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($packages as $package)
                <div class="card group border border-border bg-card shadow-sm hover:shadow-xl hover:border-primary/30 transition-all duration-300">
                    <div class="card-body p-6 relative overflow-hidden">
                        <!-- Decorative bg -->
                        <div class="absolute -right-6 -top-6 bg-primary/5 rounded-full size-32 group-hover:bg-primary/10 transition-colors"></div>
                        
                        <div class="flex justify-between items-start mb-6 relative z-10">
                            <div>
                                <h3 class="card-title text-xl font-bold tracking-tight">{{ $package->name }}</h3>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="icon-[tabler--clock] size-4 text-muted-foreground"></span>
                                    <span class="text-sm font-medium text-muted-foreground">{{ $package->duration_days }} Days Duration</span>
                                </div>
                            </div>
                            <span @class(['badge badge-sm font-bold shadow-sm', $package->is_active ? 'badge-success text-success-foreground' : 'badge-ghost text-muted-foreground'])>
                                {{ $package->is_active ? 'Active' : 'Hidden' }}
                            </span>
                        </div>
                        
                        <div class="mb-8 relative z-10">
                            <span class="text-4xl font-black text-foreground tracking-tighter">${{ number_format($package->price, 2) }}</span>
                            <span class="text-muted-foreground font-medium ml-1">/ package</span>
                        </div>
                        
                        <div class="card-actions grid grid-cols-2 gap-3 mt-auto relative z-10">
                            <button class="btn btn-outline hover:bg-muted hover:text-foreground border-border hover:border-foreground/20 transition-all" onclick='editPackage(@json($package))'>
                                <span class="icon-[tabler--edit] size-4 mr-1"></span>
                                Edit
                            </button>
                            <form action="{{ route('admin.ad-packages.destroy', $package) }}" method="POST" onsubmit="return confirm('Delete this package? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-ghost hover:bg-destructive/10 text-muted-foreground hover:text-destructive w-full transition-colors">
                                    <span class="icon-[tabler--trash] size-4 mr-1"></span>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-20 border-2 border-dashed border-border rounded-3xl bg-muted/5 text-center">
            <div class="bg-card p-4 rounded-full shadow-sm mb-4">
                <span class="icon-[tabler--packages] size-10 text-muted-foreground/50"></span>
            </div>
            <h3 class="text-xl font-bold text-foreground">No Packages Defined</h3>
            <p class="text-muted-foreground max-w-sm mt-2 mb-6">Create your first ad package to start generating revenue from event promotions.</p>
            <button class="btn btn-outline" onclick="window.add_package_modal.showModal()">
                Create First Package
            </button>
        </div>
    @endif

    <!-- Create Modal -->
    <dialog id="add_package_modal" class="fixed inset-0 z-[999] w-screen h-screen max-w-none max-h-none m-0 p-0 bg-transparent backdrop:bg-black/50 backdrop:backdrop-blur-[2px] open:flex items-center justify-center">
        <div class="modal-box w-full max-w-lg bg-card text-card-foreground border border-border/50 shadow-2xl relative z-10 p-8 rounded-3xl scale-95 open:scale-100 transition-all duration-200" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-black text-2xl tracking-tight">New Ad Package</h3>
                    <p class="text-sm text-muted-foreground">Configure a new promotion tier.</p>
                </div>
                <button class="btn btn-sm btn-circle btn-ghost" onclick="window.add_package_modal.close()">
                    <span class="icon-[tabler--x] size-5"></span>
                </button>
            </div>
            
            <form action="{{ route('admin.ad-packages.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="space-y-2">
                    <label class="text-sm font-bold ml-1">Package Name</label>
                    <input type="text" name="name" class="input input-bordered w-full h-11 focus:ring-2 focus:ring-primary/20 transition-all bg-muted/30 focus:bg-card" placeholder="e.g. 7-Day Blast" required>
                </div>
                <div class="grid grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label class="text-sm font-bold ml-1">Duration (Days)</label>
                        <div class="relative">
                            <span class="icon-[tabler--calendar] absolute left-3 top-3.5 size-4 text-muted-foreground"></span>
                            <input type="number" name="duration_days" class="input input-bordered w-full h-11 pl-10 focus:ring-2 focus:ring-primary/20 transition-all bg-muted/30 focus:bg-card" min="1" required>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold ml-1">Price ($)</label>
                        <div class="relative">
                            <span class="icon-[tabler--currency-dollar] absolute left-3 top-3.5 size-4 text-muted-foreground"></span>
                            <input type="number" name="price" class="input input-bordered w-full h-11 pl-10 focus:ring-2 focus:ring-primary/20 transition-all bg-muted/30 focus:bg-card" step="0.01" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-bold ml-1">Visibility Status</label>
                    <select name="is_active" class="select select-bordered w-full h-11 focus:ring-2 focus:ring-primary/20 transition-all bg-muted/30 focus:bg-card">
                        <option value="1">Active (Organizer can see)</option>
                        <option value="0">Hidden (Draft)</option>
                    </select>
                </div>
                <div class="modal-action pt-4">
                    <button type="submit" class="btn btn-primary w-full shadow-lg shadow-primary/20 font-bold h-11">Create Package</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="fixed inset-0 w-full h-full cursor-pointer" onclick="window.add_package_modal.close()"></form>
    </dialog>

    <!-- Edit Modal -->
    <dialog id="edit_package_modal" class="fixed inset-0 z-[999] w-screen h-screen max-w-none max-h-none m-0 p-0 bg-transparent backdrop:bg-black/50 backdrop:backdrop-blur-[2px] open:flex items-center justify-center">
        <div class="modal-box w-full max-w-lg bg-card text-card-foreground border border-border/50 shadow-2xl relative z-10 p-8 rounded-3xl" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-black text-2xl tracking-tight">Edit Package</h3>
                    <p class="text-sm text-muted-foreground">Update package details.</p>
                </div>
                <button class="btn btn-sm btn-circle btn-ghost" onclick="window.edit_package_modal.close()">
                    <span class="icon-[tabler--x] size-5"></span>
                </button>
            </div>

            <form id="edit_form" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                <div class="space-y-2">
                    <label class="text-sm font-bold ml-1">Package Name</label>
                    <input type="text" name="name" id="edit_name" class="input input-bordered w-full h-11 focus:ring-2 focus:ring-primary/20 bg-muted/30 focus:bg-card" required>
                </div>
                <div class="grid grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label class="text-sm font-bold ml-1">Duration (Days)</label>
                        <div class="relative">
                            <span class="icon-[tabler--calendar] absolute left-3 top-3.5 size-4 text-muted-foreground"></span>
                            <input type="number" name="duration_days" id="edit_duration" class="input input-bordered w-full h-11 pl-10 focus:ring-2 focus:ring-primary/20 bg-muted/30 focus:bg-card" min="1" required>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold ml-1">Price ($)</label>
                        <div class="relative">
                            <span class="icon-[tabler--currency-dollar] absolute left-3 top-3.5 size-4 text-muted-foreground"></span>
                            <input type="number" name="price" id="edit_price" class="input input-bordered w-full h-11 pl-10 focus:ring-2 focus:ring-primary/20 bg-muted/30 focus:bg-card" step="0.01" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-bold ml-1">Visibility</label>
                    <select name="is_active" id="edit_active" class="select select-bordered w-full h-11 focus:ring-2 focus:ring-primary/20 bg-muted/30 focus:bg-card">
                        <option value="1">Active</option>
                        <option value="0">Hidden</option>
                    </select>
                </div>
                <div class="modal-action pt-4">
                    <button type="submit" class="btn btn-primary w-full shadow-lg shadow-primary/20 font-bold h-11">Save Changes</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="fixed inset-0 w-full h-full cursor-pointer" onclick="window.edit_package_modal.close()"></form>
    </dialog>
</div>

@push('scripts')
<script>
    function editPackage(pkg) {
        const form = document.getElementById('edit_form');
        form.action = `/admin/ad-packages/${pkg.id}`;
        
        document.getElementById('edit_name').value = pkg.name;
        document.getElementById('edit_duration').value = pkg.duration_days;
        document.getElementById('edit_price').value = pkg.price;
        document.getElementById('edit_active').value = pkg.is_active ? "1" : "0";
        
        window.edit_package_modal.showModal();
    }
</script>
@endpush
@endsection
