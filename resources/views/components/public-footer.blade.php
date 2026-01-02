<footer class="bg-muted/30 border-t border-border pt-16 pb-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">
            <div>
                <h4 class="font-bold mb-6 text-foreground">Use {{ config('app.name') }}</h4>
                <ul class="space-y-4 text-muted-foreground">
                    <li><a href="{{ route('events.create') }}" class="hover:text-primary transition-colors">Create Events</a></li>
                    <li><a href="{{ route('pages.pricing') }}" class="hover:text-primary transition-colors">Pricing</a></li>
                    <li><a href="{{ route('blog.index') }}" class="hover:text-primary transition-colors">Blog</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Content Guidelines</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">FAQs</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Sitemap</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold mb-6 text-foreground">Plan Events</h4>
                <ul class="space-y-4 text-muted-foreground">
                    <li><a href="#" class="hover:text-primary transition-colors">Sell Tickets Online</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Event Management</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">QR Code Check-in</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Post your event online</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold mb-6 text-foreground">Find Events</h4>
                <ul class="space-y-4 text-muted-foreground">
                    <li><a href="{{ route('search', ['q' => 'New York']) }}" class="hover:text-primary transition-colors">New York Events</a></li>
                    <li><a href="{{ route('search', ['q' => 'Chicago']) }}" class="hover:text-primary transition-colors">Chicago Events</a></li>
                    <li><a href="{{ route('search', ['q' => 'Los Angeles']) }}" class="hover:text-primary transition-colors">Los Angeles Events</a></li>
                    <li><a href="{{ route('search', ['q' => 'Online']) }}" class="hover:text-primary transition-colors">Online Events</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold mb-6 text-foreground">Connect With Us</h4>
                <ul class="space-y-4 text-muted-foreground">
                    <li><a href="#" class="hover:text-primary transition-colors">Contact Support</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Contact Sales</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">X (Twitter)</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Facebook</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">LinkedIn</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Instagram</a></li>
                </ul>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center gap-6 pt-10 border-t border-border text-xs text-muted-foreground">
            <div class="flex flex-col md:flex-row items-center gap-6">
                <div>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</div>
                <div class="flex gap-4 flex-wrap justify-center">
                    <a href="{{ route('pages.about') }}" class="hover:text-primary">About</a>
                    <a href="{{ route('pages.careers') }}" class="hover:text-primary">Careers</a>
                    <a href="{{ route('pages.press') }}" class="hover:text-primary">Press</a>
                    <a href="{{ route('pages.security') }}" class="hover:text-primary">Security</a>
                    <a href="{{ route('pages.developers') }}" class="hover:text-primary">Developers</a>
                    <a href="{{ route('pages.terms') }}" class="hover:text-primary">Terms</a>
                    <a href="{{ route('pages.privacy') }}" class="hover:text-primary">Privacy</a>
                    <a href="{{ route('pages.cookies') }}" class="hover:text-primary">Cookies</a>
                </div>
            </div>
            <div class="dropdown dropdown-top dropdown-end">
                <button tabindex="0" class="hover:text-primary flex items-center gap-1 uppercase font-medium">
                    English (US) <span class="icon-[lucide--chevron-up] size-3"></span>
                </button>
                <ul tabindex="0" class="dropdown-content menu p-2 shadow-lg bg-card border border-border rounded-box w-52 mt-2 z-50">
                    <li><a href="#">Español</a></li>
                    <li><a href="#">Français</a></li>
                    <li><a href="#">Deutsch</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
