<nav class="sticky top-0 z-50 glass-nav border-b border-border/40 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl h-20 flex items-center justify-between gap-4">
        <div class="flex items-center gap-6 shrink-0">
            <a href="{{ route('home') }}" class="flex items-center gap-1">
                <span class="icon-[lucide--ticket] size-8 text-[#D1410C]"></span>
                <span class="text-2xl font-black tracking-tighter text-[#D1410C]">{{ config('app.name') }}</span>
            </a>
        </div>

        {{-- Two-part Search --}}
        <form action="{{ route('search') }}" method="GET" class="hidden lg:flex flex-1 max-w-2xl bg-muted/20 border border-border rounded-lg overflow-hidden h-11 items-center shadow-sm">
            <div class="flex items-center gap-2 px-3 border-r border-border h-full flex-1 min-w-0">
                <span class="icon-[lucide--search] size-4 text-muted-foreground"></span>
                <input type="text" name="q" placeholder="Search events" class="bg-transparent border-none focus:ring-0 text-sm w-full outline-none">
            </div>
            <div class="flex items-center gap-2 px-3 h-full flex-1 min-w-0 group">
                <span class="icon-[lucide--map-pin] size-4 text-muted-foreground"></span>
                <input type="text" name="l" placeholder="Your Location" class="bg-transparent border-none focus:ring-0 text-sm w-full outline-none">
                <button type="submit" class="bg-[#D1410C] p-2 rounded-md text-white ml-auto">
                    <span class="icon-[lucide--search] size-4"></span>
                </button>
            </div>
        </form>

        <div class="flex items-center gap-4">
            <div class="hidden xl:flex items-center gap-5 mr-2">
                <a href="{{ route('blog.index') }}" class="text-xs font-bold hover:text-primary transition-colors">Blog</a>
                <a href="{{ route('search') }}" class="text-xs font-bold hover:text-primary transition-colors">Find Events</a>
                <a href="{{ route('events.create') }}" class="text-xs font-bold hover:text-primary transition-colors">Create Events</a>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false" class="text-xs font-bold flex items-center gap-1 hover:text-primary transition-colors">
                        Help Center <span class="icon-[lucide--chevron-down] size-3 transition-transform duration-200" :class="{ 'rotate-180': open }"></span>
                    </button>
                    <div x-show="open" x-transition.opacity.duration.200ms class="absolute right-0 top-full mt-2 w-52 p-2 shadow-lg bg-card border border-border rounded-xl z-50">
                        <ul class="menu p-0">
                            @auth
                                @if(Auth::user()->hasRole('attendee'))
                                    <li><a href="{{ route('support.index') }}" class="block px-4 py-2 hover:bg-muted rounded-lg text-sm">Contact Organizer</a></li>
                                @elseif(Auth::user()->hasRole('organizer'))
                                    <li><a href="{{ route('support.index') }}" class="block px-4 py-2 hover:bg-muted rounded-lg text-sm">Contact Admin</a></li>
                                @elseif(Auth::user()->hasRole('admin'))
                                    <li><a href="{{ route('admin.support.index') }}" class="block px-4 py-2 hover:bg-muted rounded-lg text-sm">System Support</a></li>
                                @endif
                                <li><a href="{{ route('support.index') }}" class="block px-4 py-2 hover:bg-muted rounded-lg text-sm">My Support Tickets</a></li>
                            @else
                                <li><a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-muted rounded-lg text-sm">Log in to get help</a></li>
                            @endauth
                            <li><hr class="my-1 border-border"></li>
                            <li><a href="#" class="block px-4 py-2 hover:bg-muted rounded-lg text-sm">FAQs</a></li>
                        </ul>
                    </div>
                </div>
                <a href="{{ route('tickets.index') }}" class="text-xs font-bold hover:text-primary transition-colors">Find my tickets</a>
            </div>

            {{-- Theme Toggle --}}
            <button x-data="{ 
                dark: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
                toggle() {
                    this.dark = !this.dark;
                    if (this.dark) {
                        document.documentElement.classList.add('dark');
                        document.documentElement.setAttribute('data-theme', 'dark');
                        localStorage.setItem('theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        document.documentElement.setAttribute('data-theme', 'light');
                        localStorage.setItem('theme', 'light');
                    }
                }
            }" 
            @click="toggle()" 
            class="btn btn-circle btn-sm btn-ghost" 
            aria-label="Toggle Theme">
                <span class="icon-[lucide--sun] size-5 block dark:hidden"></span>
                <span class="icon-[lucide--moon] size-5 hidden dark:block"></span>
            </button>

            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary rounded-full btn-sm px-6">Dashboard</a>
            @else
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="text-xs font-bold hover:text-primary transition-colors">Log In</a>
                    <a href="{{ route('register') }}" class="text-xs font-bold hover:text-primary transition-colors">Sign Up</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
