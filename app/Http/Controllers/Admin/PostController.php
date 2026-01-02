<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }



    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }



    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully.');
    }

    public function models(GeminiService $gemini)
    {
        try {
            $models = $gemini->listModels();
            $models = array_filter($models, function($model) {
                return in_array('generateContent', $model['supportedGenerationMethods'] ?? []);
            });
            return response()->json(['models' => array_values($models)]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private const SYSTEM_PROMPT = <<<EOT
You must output STRICTLY VALID JSON in the following format:
{
    "title": "The Blog Post Title",
    "content": "The full blog post content in Markdown format...",
    "excerpt": "A short summary of the post (max 200 chars)",
    "meta_title": "SEO Optimized Title (max 60 chars)",
    "meta_description": "SEO Meta Description (max 160 chars)"
}

Do not include any markdown formatting around the JSON (like ```json). Just the raw JSON.

Blog Guidelines:
- Understand the topic fully before writing.
- Write with clarity, depth, and purpose. Avoid filler.
- Use a professional but conversational tone.
- Structure with clear headings (H2/H3).
- Include a strong introduction and a concise conclusion.
- Naturally integrate relevant keywords.

When provided a topic, generate the complete post, excerpt, and SEO metadata in the specified JSON format.
EOT;

    public function generate(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
            'model' => 'nullable|string'
        ]);

        try {
            $gemini = new GeminiService();
            // Combine System Prompt with User Prompt
            $fullPrompt = self::SYSTEM_PROMPT . "\n\nTOPIC: " . $request->prompt;
            
            $rawContent = $gemini->generateContent($fullPrompt, $request->model ?? 'gemini-2.5-pro');
            
            // Clean up potential Markdown fencing if the model ignores the "raw JSON" instruction
            $cleanContent = str_replace(['```json', '```'], '', $rawContent);
            $cleanContent = trim($cleanContent);

            $jsonContent = json_decode($cleanContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                // Fallback if valid JSON wasn't returned, though prompt strongly suggests it
                return response()->json([
                    'content' => $rawContent, // Raw text fallback
                    'title' => '',
                    'excerpt' => '',
                    'meta_title' => '',
                    'meta_description' => ''
                ]);
            }
            
            // Ensure all keys exist
            return response()->json([
                'content' => $jsonContent['content'] ?? '',
                'title' => $jsonContent['title'] ?? '',
                'excerpt' => $jsonContent['excerpt'] ?? '',
                'meta_title' => $jsonContent['meta_title'] ?? '',
                'meta_description' => $jsonContent['meta_description'] ?? ''
            ]);

        } catch (\Exception $e) {
            \Log::error('Gemini Generation Error: ' . $e->getMessage(), [
                'exception' => $e,
                'prompt' => $request->prompt,
                'model' => $request->model
            ]);
            
            return response()->json([
                'error' => 'An error occurred while generating content. Please check the logs.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        \Log::info('Store Post Request Data:', $request->all());

        if (empty($request->all())) {
             \Log::error('Request is empty. Likely exceeds post_max_size.');
             return back()->with('error', 'Post too large. Try a smaller image.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|image|max:5120', // Max 5MB
            'featured_image_base64' => 'nullable|string', // Base64 fallback from AI
            'is_published' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:160',
        ]);

        $post = new Post();
        $post->title = $validated['title'];
        $post->content = $validated['content'];
        $post->excerpt = $validated['excerpt'] ?? null;
        $post->author_id = auth()->id();
        $post->is_published = $request->has('is_published');
        $post->published_at = $request->has('is_published') ? now() : null;
        $post->meta_title = $validated['meta_title'] ?? null;
        $post->meta_description = $validated['meta_description'] ?? null;

        // Handle Image Upload (Priority: File > Base64)
        if ($request->hasFile('featured_image')) {
            $post->featured_image = $this->compressAndUploadImage($request->file('featured_image'));
        } elseif ($request->filled('featured_image_base64')) {
            $post->featured_image = $this->compressAndUploadImage($request->input('featured_image_base64'));
        }

        $post->save();

        return redirect()->route('admin.posts.index')->with('success', 'Post created successfully.');
    }

    public function update(Request $request, Post $post)
    {
        \Log::info('Update Post ID: ' . $post->id, ['request' => $request->all()]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|image|max:5120',
            'featured_image_base64' => 'nullable|string',
            'is_published' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:160',
        ]);

        $post->title = $validated['title'];
        $post->content = $validated['content'];
        $post->excerpt = $validated['excerpt'] ?? null;
        
        $post->is_published = $request->has('is_published');
        if ($post->is_published && !$post->published_at) {
            $post->published_at = now();
        }
        $post->meta_title = $validated['meta_title'] ?? null;
        $post->meta_description = $validated['meta_description'] ?? null;

        // Handle Image
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($post->featured_image) {
                \Storage::disk('public')->delete($post->featured_image);
            }
            $post->featured_image = $this->compressAndUploadImage($request->file('featured_image'));
        } elseif ($request->filled('featured_image_base64')) {
            // Handle AI generated image save
            if ($post->featured_image) {
                \Storage::disk('public')->delete($post->featured_image);
            }
            $post->featured_image = $this->compressAndUploadImage($request->input('featured_image_base64'));
        }

        \Log::info('Saving Post ID: ' . $post->id, ['dirty' => $post->getDirty()]);
        $post->save();

        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully.');
    }

    private const IMAGEN_SYSTEM_PROMPT = <<<EOT
Generate one high-quality 16:9 landscape header image based only on the blog post title.
Visually represent the core idea using clean, modern, non-cartoonish style with strong composition and subtle symbolism.
Do not include text, logos, watermarks, UI elements, or recognizable public figures; ensure the image is publication-ready with clear focus and balanced colors.
EOT;

    public function generateImage(Request $request)
    {
        set_time_limit(120); // Allow up to 2 minutes for image generation

        $request->validate([
            'title' => 'required|string',
            'model' => 'nullable|string'
        ]);

        try {
            $gemini = new GeminiService();
            // Combine System Prompt with User Title
            $fullPrompt = self::IMAGEN_SYSTEM_PROMPT . " Topic: " . $request->title;
            
            $base64Image = $gemini->generateImage($fullPrompt, $request->model ?? 'nano-banana-pro');
            
            if (!$base64Image) {
                return response()->json(['error' => 'No image generated.'], 500);
            }

            return response()->json([
                'image_base64' => $base64Image,
                'message' => 'Image generated successfully. Not saved to storage yet.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Gemini Image Generation Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while generating image.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Compress and upload image using native PHP GD.
     * Handles UploadedFile or Base64 string.
     */
    private function compressAndUploadImage($file)
    {
        // Check if GD extension is loaded
        if (!extension_loaded('gd')) {
            if ($file instanceof \Illuminate\Http\UploadedFile) {
                return $file->store('posts', 'public');
            }
            return null; // Cannot handle base64 without GD easily here implies simple storage
        }

        $image = null;
        if ($file instanceof \Illuminate\Http\UploadedFile) {
            $info = getimagesize($file);
            $mime = $info['mime'];
            switch ($mime) {
                case 'image/jpeg': $image = imagecreatefromjpeg($file); break;
                case 'image/png': $image = imagecreatefrompng($file); break;
                case 'image/gif': $image = imagecreatefromgif($file); break;
            }
        } elseif (is_string($file)) {
            // Assume Base64
            $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $file));
            $image = imagecreatefromstring($data);
        }

        if (!$image) return null;

        // Handle transparency for PNG -> JPG if relevant
        if (imagesx($image) && imagesy($image)) { // Basic check
             $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
             imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
             imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
             $image = $bg;
        }

        $filename = Str::uuid() . '.jpg';
        $path = 'posts/' . $filename;
        $absolutePath = storage_path('app/public/' . $path);

        if (!file_exists(dirname($absolutePath))) {
            mkdir(dirname($absolutePath), 0755, true);
        }

        imagejpeg($image, $absolutePath, 75);
        imagedestroy($image);

        return $path;
    }
}
