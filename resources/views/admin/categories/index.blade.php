@extends('layouts.app')



@section('content')
    <div class="mx-auto max-w-4xl space-y-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-foreground">Event Categories</h2>
                <p class="text-sm text-muted-foreground mt-1">Organize your events with custom categories.</p>
            </div>
            <button onclick="window.add_category_modal.showModal()" class="btn btn-primary gap-2">
                <span class="icon-[tabler--plus] size-4"></span>
                Add Category
            </button>
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-success/50 bg-success/10 p-4 text-success flex items-center gap-2">
                <span class="icon-[tabler--check] size-5"></span>
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-xl border border-border bg-card shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border bg-muted/30">
                            <th class="h-12 px-6 text-left align-middle font-semibold text-muted-foreground uppercase tracking-wider w-16">Icon</th>
                            <th class="h-12 px-6 text-left align-middle font-semibold text-muted-foreground uppercase tracking-wider">Name</th>
                            <th class="h-12 px-6 text-left align-middle font-semibold text-muted-foreground uppercase tracking-wider">Events</th>
                            <th class="h-12 px-6 text-right align-middle font-semibold text-muted-foreground uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($categories as $category)
                            <tr class="hover:bg-muted/50 transition-colors">
                                <td class="px-6 py-4 align-middle">
                                    <div class="size-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                                        <span class="icon-[lucide--{{ $category->icon ?: 'layers' }}] size-5"></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="font-bold text-foreground">{{ $category->name }}</div>
                                    @if($category->description)
                                        <div class="text-xs text-muted-foreground line-clamp-1">{{ $category->description }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <span class="badge badge-soft badge-primary">{{ $category->events_count }} Events</span>
                                </td>
                                <td class="px-6 py-4 align-middle text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="editCategory({{ json_encode($category) }})" class="btn btn-sm btn-ghost btn-square text-muted-foreground hover:bg-muted">
                                            <span class="icon-[tabler--edit] size-4"></span>
                                        </button>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-ghost btn-square text-destructive hover:bg-destructive/10">
                                                <span class="icon-[tabler--trash] size-4"></span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-muted-foreground italic">
                                    No categories found. Start by creating one.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create Modal -->
        <dialog id="add_category_modal" class="fixed inset-0 z-[999] w-screen h-screen max-w-none max-h-none m-0 p-0 bg-transparent backdrop:bg-black/50 backdrop:backdrop-blur-[2px] open:flex items-center justify-center">
            <div class="modal-box max-w-md border border-border shadow-2xl relative z-10 bg-card text-card-foreground p-6 rounded-2xl" onclick="event.stopPropagation()">
                <div class="mb-6">
                    <h3 class="font-bold text-2xl text-foreground">New Category</h3>
                    <p class="text-muted-foreground text-sm mt-1">Define a classification for your events.</p>
                </div>
                
                <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-sm font-semibold px-1">Category Name</label>
                        <input type="text" name="name" class="input input-bordered w-full h-11 focus:ring-2 focus:ring-primary/20" placeholder="e.g. Music, Tech, Business" required />
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold px-1">Lucide Icon Name</label>
                        <div class="relative">
                            <span class="icon-[lucide--layers] absolute left-3 top-1/2 -translate-y-1/2 size-4 text-muted-foreground"></span>
                            <input type="text" name="icon" class="input input-bordered w-full h-11 pl-10 focus:ring-2 focus:ring-primary/20" placeholder="e.g. music, sparkles, heart" />
                        </div>
                        <p class="text-[10px] text-muted-foreground px-1">Refer to <a href="https://lucide.dev/icons" target="_blank" class="text-primary underline">Lucide Icons</a></p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold px-1">Description (Optional)</label>
                        <textarea name="description" class="textarea textarea-bordered w-full min-h-[100px] focus:ring-2 focus:ring-primary/20" placeholder="Describe what kind of events belong here..."></textarea>
                    </div>
                    
                    <div class="modal-action gap-3 flex">
                        <button type="button" onclick="window.add_category_modal.close()" class="btn btn-ghost flex-1">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary flex-1 shadow-lg shadow-primary/20">
                            Create Category
                        </button>
                    </div>
                </form>
            </div>
            <form method="dialog" class="fixed inset-0 w-full h-full cursor-pointer" onclick="window.add_category_modal.close()"></form>
        </dialog>

        <!-- Edit Modal -->
        <dialog id="edit_category_modal" class="fixed inset-0 z-[999] w-screen h-screen max-w-none max-h-none m-0 p-0 bg-transparent backdrop:bg-black/50 backdrop:backdrop-blur-[2px] open:flex items-center justify-center">
            <div class="modal-box max-w-md border border-border shadow-2xl relative z-10 bg-card text-card-foreground p-6 rounded-2xl" onclick="event.stopPropagation()">
                <div class="mb-6">
                    <h3 class="font-bold text-2xl text-foreground">Edit Category</h3>
                    <p class="text-muted-foreground text-sm mt-1">Update classification details.</p>
                </div>
                
                <form id="edit_category_form" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')
                    <div class="space-y-2">
                        <label class="text-sm font-semibold px-1">Category Name</label>
                        <input type="text" name="name" id="edit_name" class="input input-bordered w-full h-11 focus:ring-2 focus:ring-primary/20" required />
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold px-1">Lucide Icon Name</label>
                        <div class="relative">
                            <span class="icon-[lucide--layers] absolute left-3 top-1/2 -translate-y-1/2 size-4 text-muted-foreground"></span>
                            <input type="text" name="icon" id="edit_icon" class="input input-bordered w-full h-11 pl-10 focus:ring-2 focus:ring-primary/20" />
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold px-1">Description (Optional)</label>
                        <textarea name="description" id="edit_description" class="textarea textarea-bordered w-full min-h-[100px] focus:ring-2 focus:ring-primary/20"></textarea>
                    </div>
                    
                    <div class="modal-action gap-3">
                        <button type="button" onclick="window.edit_category_modal.close()" class="btn btn-ghost flex-1">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary flex-1 shadow-lg shadow-primary/20">
                            Update Category
                        </button>
                    </div>
                </form>
            </div>
            <form method="dialog" class="fixed inset-0 w-full h-full cursor-pointer" onclick="window.edit_category_modal.close()"></form>
        </dialog>

        @push('scripts')
        <script>
            function editCategory(category) {
                document.getElementById('edit_category_form').action = `/admin/categories/${category.id}`;
                document.getElementById('edit_name').value = category.name;
                document.getElementById('edit_icon').value = category.icon || '';
                document.getElementById('edit_description').value = category.description || '';
                window.edit_category_modal.showModal();
            }
        </script>
        @endpush
    </div>
@endsection

