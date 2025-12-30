<div class="bg-background border-b border-border sticky top-0 z-50 flex">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-2">
                <button type="button" class="btn btn-ghost btn-square btn-sm lg:hidden hover:bg-accent hover:text-accent-foreground" 
                    @click="sidebarOpen = !sidebarOpen" aria-label="Toggle Navigation">
                    <span class="icon-[lucide--menu] size-5"></span>
                </button>
            </div>

            <div class="flex items-center gap-4">
                <x-theme-toggle />
                
                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-ghost btn-circle" aria-label="Logout" title="Logout">
                        <span class="icon-[lucide--log-out] size-5 text-destructive"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
