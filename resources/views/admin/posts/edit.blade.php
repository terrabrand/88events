@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl space-y-8">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight text-foreground">Edit Post</h2>
            <a href="{{ route('admin.posts.index') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                <span class="icon-[tabler--arrow-left] mr-2 size-4"></span>
                Back to Posts
            </a>
        </div>

        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
            <div class="p-6">
                <!-- AI Assistant Section -->
                <div class="mb-8 rounded-lg border border-purple-200 bg-purple-50/50 p-6 dark:border-purple-900/50 dark:bg-purple-900/20">
                    <h3 class="flex items-center text-lg font-semibold text-purple-900 dark:text-purple-100 mb-4">
                        <span class="icon-[tabler--sparkles] mr-2 size-5"></span>
                        AI Content Assistant
                    </h3>
                    
                    <div class="grid gap-4 md:grid-cols-3">
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none" for="ai-model">Model</label>
                            <select id="ai-model" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                <option value="gemini-2.5-pro" selected>Gemini 2.5 Pro (Default)</option>
                            </select>
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label class="text-sm font-medium leading-none" for="ai-prompt">Prompt</label>
                            <div class="flex gap-2">
                                <input id="ai-prompt" type="text" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2" placeholder="e.g. Write a blog post about Laravel 11 features...">
                                <button type="button" onclick="generateContent()" id="ai-generate-btn" class="inline-flex shrink-0 items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-purple-600 text-white hover:bg-purple-700 h-10 px-4 py-2">
                                    Generate
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="ai-error" class="hidden text-sm text-destructive mt-2"></div>
                </div>

                <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none" for="title">Title</label>
                        <input type="text" name="title" id="title" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" value="{{ old('title', $post->title) }}" required>
                        @error('title') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none" for="slug">Slug</label>
                         <input type="text" name="slug" id="slug" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" value="{{ old('slug', $post->slug) }}" disabled>
                         <p class="text-[10px] text-muted-foreground">Slug is auto-generated and cannot be changed directly.</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none" for="content">Content</label>
                        <textarea name="content" id="content" rows="15" class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" required>{{ old('content', $post->content) }}</textarea>
                        @error('content') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none" for="excerpt">Excerpt</label>
                        <textarea name="excerpt" id="excerpt" rows="3" class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">{{ old('excerpt', $post->excerpt) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none" for="featured_image">Featured Image</label>
                            @if($post->featured_image)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($post->featured_image) }}" alt="Current Image" class="h-24 w-auto rounded-md object-cover">
                                </div>
                            @endif
                            
                            <div class="flex items-center gap-2 mb-2">
                                <label class="text-sm font-medium leading-none" for="featured_image">Update Image</label>
                                <div class="flex-1"></div>
                                <select id="ai-image-model" class="h-6 rounded-md border border-input bg-background px-2 text-xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                    <option value="gemini-3-pro-image-preview">Gemini 3 Pro (Preview)</option>
                                    <option value="gemini-2.5-flash-image">Gemini 2.5 Flash</option>
                                </select>
                                <button type="button" onclick="generateImage()" id="ai-image-btn" class="inline-flex items-center justify-center rounded-md text-xs font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-purple-100 text-purple-900 override:hover:bg-purple-200 h-6 px-2 dark:bg-purple-900/40 dark:text-purple-100">
                                    <span class="icon-[tabler--photo-ai] mr-1 size-3"></span>
                                    Generate
                                </button>
                            </div>
                            <input type="file" name="featured_image" id="featured_image" accept="image/*" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <input type="hidden" name="featured_image_base64" id="featured_image_base64">
                            <p class="text-[10px] text-muted-foreground mt-1">Upload a new image or generate one. Replaces old image on save.</p>
                            
                            <div id="ai-image-preview" class="hidden mt-2 relative group">
                                <img id="ai-image-img" src="" alt="AI Generated Preview" class="w-full h-auto rounded-md shadow-sm border border-border">
                                <div class="absolute top-2 right-2 bg-black/70 text-white text-[10px] px-2 py-1 rounded">AI Generated Preview</div>
                                <button type="button" onclick="clearAiImage()" class="absolute top-2 left-2 bg-red-500 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity" title="Remove">
                                    <span class="icon-[tabler--x] size-3"></span>
                                </button>
                            </div>
                            <div id="ai-image-error" class="hidden text-xs text-destructive mt-1"></div>
                        </div>

                        <div class="flex items-center space-x-2 pt-8">
                            <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $post->is_published) ? 'checked' : '' }} class="h-4 w-4 rounded border-primary text-primary focus:ring-primary">
                            <label class="text-sm font-medium leading-none" for="is_published">Published</label>
                        </div>
                    </div>

                    <div class="space-y-4 pt-4 border-t border-border">
                        <h3 class="font-semibold">SEO Settings</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="space-y-2">
                                <label class="text-sm font-medium leading-none" for="meta_title">Meta Title</label>
                                <input type="text" name="meta_title" id="meta_title" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" value="{{ old('meta_title', $post->meta_title) }}">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium leading-none" for="meta_description">Meta Description</label>
                                <textarea name="meta_description" id="meta_description" rows="2" maxlength="160" class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">{{ old('meta_description', $post->meta_description) }}</textarea>
                                @error('meta_description') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">Update Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const modelSelect = document.getElementById('ai-model');
            try {
                const response = await fetch('{{ route("admin.posts.models") }}', {
                    headers: { 'Accept': 'application/json' }
                });
                if (response.ok) {
                    const data = await response.json();
                    if (data.models && data.models.length > 0) {
                        modelSelect.innerHTML = ''; // Clear default
                        data.models.forEach(model => {
                            const option = document.createElement('option');
                            const modelId = model.name.replace('models/', '');
                            option.value = modelId;
                            option.text = `${model.displayName} (${modelId})`;
                            if (modelId === 'gemini-2.5-pro') option.selected = true;
                            // Re-prioritize gemini-2.5-pro over flash
                            // modelSelect.value defaults to last set selected
                            modelSelect.appendChild(option);
                        });
                        // Explicitly set 2.5 pro if available, otherwise first
                        const preferred = Array.from(modelSelect.options).find(o => o.value === 'gemini-2.5-pro');
                        if (preferred) preferred.selected = true;
                        else if (modelSelect.options.length > 0 && !modelSelect.value) modelSelect.selectedIndex = 0;
                    }
                }
            } catch (e) {
                console.error('Failed to fetch models', e);
            }
        });

        async function generateContent() {
            const prompt = document.getElementById('ai-prompt').value;
            const model = document.getElementById('ai-model').value;
            const btn = document.getElementById('ai-generate-btn');
            const errorDiv = document.getElementById('ai-error');
            const originalBtnText = btn.innerHTML; // Save text
            
            if (!prompt) return;

            // Reset state
            errorDiv.classList.add('hidden');
            errorDiv.textContent = '';
            btn.disabled = true;
            btn.innerHTML = '<span class="loading loading-spinner loading-sm mr-2"></span> Generating...';

            try {
                const response = await fetch('{{ route("admin.posts.generate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ prompt: prompt, model: model })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.error || 'Failed to generate content');
                }

                // Populate fields from structured JSON
                if (data.title) document.getElementById('title').value = data.title;
                if (data.content) document.getElementById('content').value = data.content;
                if (data.excerpt) document.getElementById('excerpt').value = data.excerpt;
                if (data.meta_title) document.getElementById('meta_title').value = data.meta_title;
                if (data.meta_description) document.getElementById('meta_description').value = data.meta_description.substring(0, 160);

                // Fallback heuristic if title is still empty
                if (!document.getElementById('title').value && data.content) {
                    const lines = data.content.split('\n');
                    if (lines.length > 0 && lines[0].length < 100 && (lines[0].startsWith('#') || !lines[0].includes('.'))) {
                        document.getElementById('title').value = lines[0].replace(/^#+\s*/, '').trim();
                    } else {
                         document.getElementById('title').value = "Blog Post about " + prompt.substring(0, 30).replace(/\n/g, ' ') + "...";
                    }
                }

            } catch (err) {
                console.error(err);
                errorDiv.textContent = err.message;
                errorDiv.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalBtnText; // Restore original text
            }
        }

        async function generateImage() {
            const title = document.getElementById('title').value;
            const btn = document.getElementById('ai-image-btn');
            const model = document.getElementById('ai-image-model').value; // Get selected image model
            const errorDiv = document.getElementById('ai-image-error');
            const previewDiv = document.getElementById('ai-image-preview');
            const imgParams = document.getElementById('ai-image-img');
            const hiddenInput = document.getElementById('featured_image_base64');
            const originalBtnText = btn.innerHTML;

            if (!title) {
                alert('Please enter a post title first.');
                return;
            }

            // Reset
            errorDiv.classList.add('hidden');
            errorDiv.textContent = '';
            btn.disabled = true;
            btn.innerHTML = '<span class="loading loading-spinner loading-xs mr-1"></span> Generating...';

            try {
                const response = await fetch('{{ route("admin.posts.generate-image") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ 
                        title: title,
                        model: model 
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.error || 'Failed to generate image');
                }

                if (data.image_base64) {
                    const base64Str = "data:image/jpeg;base64," + data.image_base64;
                    imgParams.src = base64Str;
                    hiddenInput.value = base64Str;
                    previewDiv.classList.remove('hidden');
                    
                    // Clear file input
                    document.getElementById('featured_image').value = '';
                }

            } catch (err) {
                console.error(err);
                errorDiv.textContent = err.message;
                errorDiv.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalBtnText;
            }
        }

        function clearAiImage() {
            document.getElementById('ai-image-img').src = '';
            document.getElementById('ai-image-preview').classList.add('hidden');
            document.getElementById('featured_image_base64').value = '';
        }
    </script>
    @endpush
@endsection
