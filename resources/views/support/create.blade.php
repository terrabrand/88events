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
        </p>
    </div>

    <div class="bg-card border rounded-xl p-6 shadow-sm">
        <form action="{{ route('support.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-control w-full">
                    <label class="label font-bold text-[#1E0A3C]">Priority</label>
                    <select name="priority" class="select select-bordered rounded-lg w-full">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>

                @if($type === 'attendee_to_organizer')
                    <div class="form-control w-full">
                        <label class="label font-bold text-[#1E0A3C]">Event</label>
                        <select name="event_id" class="select select-bordered rounded-lg w-full" required>
                            <option value="" disabled selected>Select an event</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}">{{ $event->title }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>

            <div class="form-control w-full">
                <label class="label font-bold text-[#1E0A3C]">Subject</label>
                <input type="text" name="subject" class="input input-bordered rounded-lg w-full" placeholder="What is this about?" required>
            </div>

            <div class="form-control w-full">
                <label class="label font-bold text-[#1E0A3C]">Message</label>
                <textarea name="message" class="textarea textarea-bordered rounded-lg w-full h-32" placeholder="Explain your request in detail..." required></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('support.index') }}" class="btn btn-ghost rounded-full px-8">Cancel</a>
                <button type="submit" class="btn btn-primary rounded-full px-8 shadow-lg shadow-primary/20">Create Ticket</button>
            </div>
        </form>
    </div>
</div>
@endsection
