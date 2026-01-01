@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h1 class="text-3xl font-black tracking-tight text-[#1E0A3C]">Create Support Ticket</h1>
        <p class="text-muted-foreground">
            @if($type === 'attendee_to_organizer')
                Send a message to the event organizer.
            @else
                Contact the system administrator for help.
            @endif
<div class="mx-auto max-w-2xl space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('support.index') }}" class="btn btn-circle btn-ghost">
            <span class="icon-[tabler--arrow-left] size-6"></span>
        </a>
        <div>
            <h2 class="text-3xl font-black tracking-tight text-foreground">Create Ticket</h2>
            <p class="text-muted-foreground">Describe your issue and we'll help you out.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-error shadow-sm border-error/20">
            <span class="icon-[tabler--alert-circle] size-5"></span>
            <div class="flex flex-col gap-1">
                @foreach($errors->all() as $error)
                    <span>{{ $error }}</span>
                @endforeach
            </div>
        </div>
    @endif

    <div class="card bg-card border border-border shadow-sm">
        <div class="card-body p-8">
            <form action="{{ route('support.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="type" value="{{ $type ?? 'organizer_to_admin' }}"> 
                
                @if(($type ?? '') === 'attendee_to_organizer')
                    <div class="space-y-2">
                        <label class="text-sm font-bold">Select Event</label>
                        <select name="event_id" class="select select-bordered w-full" required>
                            <option value="" disabled selected>Choose an event...</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}">{{ $event->title }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="space-y-2">
                    <label class="text-sm font-bold">Subject</label>
                    <input type="text" name="subject" value="{{ old('subject', $subject ?? '') }}" class="input input-bordered w-full" placeholder="Brief summary of the issue" required>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold">Priority</label>
                    <div class="flex gap-4">
                        <label class="label cursor-pointer justify-start gap-2">
                            <input type="radio" name="priority" value="low" class="radio radio-success" checked>
                            <span class="label-text font-medium">Low</span>
                        </label>
                        <label class="label cursor-pointer justify-start gap-2">
                            <input type="radio" name="priority" value="medium" class="radio radio-warning">
                            <span class="label-text font-medium">Medium</span>
                        </label>
                        <label class="label cursor-pointer justify-start gap-2">
                            <input type="radio" name="priority" value="high" class="radio radio-error">
                            <span class="label-text font-medium">High</span>
                        </label>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold">Message</label>
                    <textarea name="message" class="textarea textarea-bordered w-full h-40 text-base" placeholder="Detailed description..." required>{{ old('message', $message ?? '') }}</textarea>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <a href="{{ route('support.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary font-bold shadow-lg shadow-primary/20">
                        <span class="icon-[tabler--send] mr-2 size-5"></span>
                        Submit Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
