<button x-data="{
        theme: localStorage.getItem('theme') || 'light',
        toggle() {
            this.theme = this.theme === 'light' ? 'dark' : 'light';
            localStorage.setItem('theme', this.theme);
            document.documentElement.setAttribute('data-theme', this.theme);
            if (this.theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        },
        init() {
            if (this.theme === 'dark') {
               document.documentElement.classList.add('dark');
            }
        }
    }" 
    @click="toggle()"
    type="button" 
    class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 w-9"
    aria-label="Toggle theme">
    
    <span x-show="theme === 'light'" class="icon-[lucide--sun] size-4.5"></span>
    <span x-show="theme === 'dark'" class="icon-[lucide--moon] size-4.5" style="display: none;"></span>
</button>
