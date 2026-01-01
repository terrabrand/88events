<div x-show="sidebarOpen" style="display: none;" class="fixed inset-0 z-40 bg-black/50 lg:hidden" x-transition.opacity @click="sidebarOpen = false"></div>
<aside id="layout-sidebar"
    class="fixed inset-y-0 start-0 z-50 h-full w-72 border-r border-border bg-card text-card-foreground transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-auto lg:shrink-0"
    :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }"
    aria-label="Sidebar" tabindex="-1">
    <div class="h-full border-r border-border bg-card text-card-foreground">
        <div class="flex h-full flex-col">
            <button type="button" class="btn btn-text btn-circle btn-sm absolute end-3 top-3 lg:hidden"
                aria-label="Close" @click="sidebarOpen = false">
                <span class="icon-[tabler--x] size-5"></span>
            </button>
            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-4 border-b border-border px-4 pt-10 pb-6 hover:bg-accent/50 transition-colors group">
                 <div class="relative flex h-16 w-16 shrink-0 overflow-hidden rounded-full ring-2 ring-border group-hover:ring-primary transition-all">
                    @if(auth()->user()->avatar)
                        <img class="aspect-square h-full w-full object-cover" src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" />
                    @else
                        <img class="aspect-square h-full w-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random" alt="avatar" />
                    @endif
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-semibold tracking-tight group-hover:text-primary transition-colors">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-muted-foreground">{{ auth()->user()->email }}</p>
                </div>
            </a>
            <div class="flex-1 overflow-y-auto p-4">
                <nav class="space-y-1">
                    <!-- Dashboard Menu Item -->
                    <a href="{{ route('dashboard') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-accent-foreground', request()->routeIs('dashboard') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-accent'])>
                        <span class="icon-[lucide--layout-dashboard] size-5"></span>
                        Dashboard
                    </a>

                    <a href="{{ route('support.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-accent-foreground', request()->routeIs('support.*') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-accent'])>
                        <span class="icon-[lucide--life-buoy] size-5"></span>
                        Support
                    </a>
                    
                    @role('organizer|admin')
                        <a href="{{ route('events.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-accent-foreground', request()->routeIs('events.*') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-accent'])>
                            <span class="icon-[lucide--calendar] size-5"></span>
                            My Events
                        </a>
                        <a href="{{ route('organizer.coupons.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-accent-foreground', request()->routeIs('organizer.coupons.*') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-accent'])>
                            <span class="icon-[lucide--ticket] size-5"></span>
                            My Coupons
                        </a>
                        <a href="{{ route('organizer.sms.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-accent-foreground', request()->routeIs('organizer.sms.*') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-accent'])>
                            <span class="icon-[lucide--message-circle] size-5"></span>
                            SMS Marketing
                        </a>
                        <a href="{{ route('organizer.email.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-accent-foreground', request()->routeIs('organizer.email.*') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-accent'])>
                            <span class="icon-[lucide--send] size-5"></span>
                            Email Marketing
                        </a>
                        <a href="{{ route('organizer.guests.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-accent-foreground', request()->routeIs('organizer.guests.index') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-accent'])>
                            <span class="icon-[lucide--users] size-5"></span>
                            Guest Pool
                        </a>
                        <a href="{{ route('organizer.venues.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-accent-foreground', request()->routeIs('organizer.venues.*') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-accent'])>
                            <span class="icon-[lucide--landmark] size-5"></span>
                            My Venues
                        </a>
                        
                        <a href="{{ route('organizer.promotions.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-accent-foreground', request()->routeIs('organizer.promotions.*') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-accent'])>
                            <span class="icon-[lucide--megaphone] size-5"></span>
                            Ad Promotions
                        </a>

                        <a href="{{ route('organizer.credits.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-accent-foreground', request()->routeIs('organizer.credits.*') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-accent'])>
                            <span class="icon-[lucide--wallet] size-5"></span>
                            My Wallet
                        </a>

                        @role('admin')
                            <a href="{{ route('admin.venues.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-accent-foreground', request()->routeIs('admin.venues.*') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-accent'])>
                                <span class="icon-[lucide--landmark] size-5"></span>
                                Global Venues (Admin)
                            </a>
                        @endrole
                    @endrole
                    
                    @role('attendee')
                        <a href="{{ route('tickets.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-accent-foreground', request()->routeIs('tickets.index') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-accent'])>
                            <span class="icon-[lucide--ticket] size-5"></span>
                            My Tickets
                        </a>
                    @endrole

                    @role('promoter')
                        <a href="{{ route('dashboard') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-accent-foreground', request()->routeIs('dashboard') && auth()->user()->hasRole('promoter') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-accent'])>
                            <span class="icon-[lucide--coins] size-5"></span>
                            Promoter Stats
                        </a>
                    @endrole

                    @role('organizer|admin')
                        <!-- Section Divider -->
                        <div class="my-4 border-t border-border"></div>
                        <div class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                            User Management
                        </div>

                        <!-- User Management Menu -->
                        <a href="{{ route('users.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-accent-foreground', request()->routeIs('users.index') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-accent'])>
                            <span class="icon-[lucide--users] size-5"></span>
                            Users
                        </a>
                    @endrole

                    @role('admin')
                        <!-- Admin Menu -->
                        <div class="my-4 border-t border-border"></div>
                        <div class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                            Administration
                        </div>

                        <a href="{{ route('admin.ad-packages.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-accent-foreground', request()->routeIs('admin.ad-packages.*') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-accent'])>
                            <span class="icon-[lucide--package] size-5"></span>
                            Ad Packages
                        </a>

                        <a href="{{ route('admin.credits.create') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-accent-foreground', request()->routeIs('admin.credits.create') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-accent'])>
                            <span class="icon-[lucide--wallet] size-5"></span>
                            Manual Credits
                        </a>

                        <a href="{{ route('admin.orders.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-accent-foreground', request()->routeIs('admin.orders.*') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-accent'])>
                            <span class="icon-[lucide--circle-pound-sterling] size-5"></span>
                            Orders
                        </a>

                        <a href="{{ route('admin.categories.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-accent-foreground', request()->routeIs('admin.categories.*') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-accent'])>
                            <span class="icon-[lucide--layers] size-5"></span>
                            Categories
                        </a>

                        <a href="{{ route('admin.featured.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-accent-foreground', request()->routeIs('admin.featured.*') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-accent'])>
                            <span class="icon-[lucide--monitor-play] size-5"></span>
                            Featured Content
                        </a>

                        <a href="{{ route('admin.promotions.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-accent-foreground', request()->routeIs('admin.promotions.*') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-accent'])>
                            <span class="icon-[lucide--megaphone] size-5"></span>
                            Ad Promotions
                        </a>

                        <a href="{{ route('admin.reports.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-accent-foreground', request()->routeIs('admin.reports.*') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-accent'])>
                            <span class="icon-[lucide--flag] size-5"></span>
                            Reports
                        </a>

                        <a href="{{ route('admin.reviews.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-accent-foreground', request()->routeIs('admin.reviews.*') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-accent'])>
                            <span class="icon-[lucide--star] size-5"></span>
                            Reviews
                        </a>

                        <a href="{{ route('admin.support.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-accent-foreground', request()->routeIs('admin.support.index') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-accent'])>
                            <span class="icon-[lucide--life-buoy] size-5"></span>
                            Support Tickets
                        </a>

                        <!-- Settings Submenu -->
                        <div x-data="{ open: {{ request()->routeIs('admin.settings.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open" class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-medium text-muted-foreground transition-colors hover:bg-accent hover:text-accent-foreground">
                                <div class="flex items-center gap-3">
                                    <span class="icon-[lucide--settings] size-5"></span>
                                    Settings
                                </div>
                                <span class="icon-[lucide--chevron-down] size-4 transition-transform duration-200" :class="{ 'rotate-180': open }"></span>
                            </button>
                            <div x-show="open" class="mt-1 space-y-1 px-3" style="display: {{ request()->routeIs('admin.settings.*') ? 'block' : 'none' }}">
                                <a href="{{ route('admin.settings.payment') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-primary', request()->routeIs('admin.settings.payment') ? 'text-primary' : 'text-muted-foreground'])>
                                    <span class="icon-[lucide--credit-card] size-4"></span>
                                    Payment Gateway
                                </a>
                                <a href="{{ route('admin.settings.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:text-primary', request()->routeIs('admin.settings.index') ? 'text-primary' : 'text-muted-foreground'])>
                                    <span class="icon-[lucide--sliders-horizontal] size-4"></span>
                                    App Settings
                                </a>
                            </div>
                        </div>
                    @endrole
                </nav>
            </div>
        </div>
    </div>
</aside>
