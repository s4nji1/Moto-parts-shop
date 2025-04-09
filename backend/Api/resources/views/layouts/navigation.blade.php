<nav x-data="{ open: false }" class="nav-container">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left Side Links -->
            <div class="flex space-x-4 items-center">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Table de Bord</a>
                <a href="{{ route('models.index') }}" class="nav-link {{ request()->routeIs('models.*') ? 'active' : '' }}">Modeles</a>
                <a href="{{ route('motos.index') }}" class="nav-link {{ request()->routeIs('motos.*') ? 'active' : '' }}">Motos</a>
                <a href="{{ route('schemas.index') }}" class="nav-link {{ request()->routeIs('schemas.*') ? 'active' : '' }}">Pièces Détachées</a>
                <a href="{{ route('commandes.index') }}" class="nav-link {{ request()->routeIs('commandes.*') ? 'active' : '' }}">Commandes</a>
                <a href="{{ route('clients.index') }}" class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">Clients</a>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md text-polished-chrome bg-transparent border border-carbon-fiber hover:text-white focus:outline-none transition ease-in-out duration-150 rider-profile-button">
                            <div>{{ Auth::user()->firstname }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content" class="dropdown-menu">
                        <x-dropdown-link :href="route('profile.edit')" class="dropdown-item">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();" class="dropdown-item">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-polished-chrome hover:text-white hover:bg-carbon-fiber focus:outline-none focus:bg-carbon-fiber focus:text-white transition duration-150 ease-in-out moto-nav-toggler">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden mobile-nav">
        <div class="pt-2 pb-3 space-y-1">
    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="mobile-nav-link">
        {{ __('Table de Bord') }}
    </x-responsive-nav-link>
    <x-responsive-nav-link :href="route('models.index')" :active="request()->routeIs('models.*')" class="mobile-nav-link">
        {{ __('Models') }}
    </x-responsive-nav-link>
    <x-responsive-nav-link :href="route('motos.index')" :active="request()->routeIs('motos.*')" class="mobile-nav-link">
        {{ __('Motos') }}
    </x-responsive-nav-link>
    <x-responsive-nav-link :href="route('schemas.index')" :active="request()->routeIs('schemas.*')" class="mobile-nav-link">
        {{ __('Pièces Détachées') }}
    </x-responsive-nav-link>
    <x-responsive-nav-link :href="route('commandes.index')" :active="request()->routeIs('commandes.*')" class="mobile-nav-link">
        {{ __('Commandes') }}
    </x-responsive-nav-link>
    <x-responsive-nav-link :href="route('clients.index')" :active="request()->routeIs('clients.*')" class="mobile-nav-link">
        {{ __('Clients') }}
    </x-responsive-nav-link>
</div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-carbon-fiber">
            <div class="px-4">
                <div class="font-medium text-base text-white rider-name">{{ Auth::user()->firstname }}</div>
                <div class="font-medium text-sm text-polished-chrome rider-email">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="mobile-nav-link">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();" class="mobile-nav-link">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>


<style>
    /* Navigation styling */
    .nav-container {
        background-color: var(--deep-metal);
        border-bottom: 3px solid var(--engine-red);
        box-shadow: 0 4px 12px rgba(0,0,0,0.5);
        position: relative;
        z-index: 50;
    }
    
    .nav-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, 
            transparent 0%,
            var(--engine-red) 20%,
            var(--engine-red) 80%,
            transparent 100%);
        opacity: 0.7;
    }
    
    .nav-logo {
        position: relative;
        display: inline-block;
        transition: all 0.3s ease;
    }
    
    .nav-logo::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 125%;
        height: 125%;
        background: radial-gradient(circle, rgba(209,32,38,0.15) 0%, rgba(209,32,38,0) 70%);
        border-radius: 50%;
        z-index: -1;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .nav-logo:hover {
        transform: scale(1.05);
    }
    
    .nav-logo:hover::after {
        opacity: 1;
    }
    
    .nav-link {
        font-family: 'Barlow', sans-serif;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--polished-chrome);
        padding: 0.5rem 0.75rem;
        position: relative;
        transition: all 0.3s ease;
    }
    
    .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0.5rem;
        width: 0;
        height: 3px;
        background-color: var(--engine-red);
        transition: all 0.3s ease;
        box-shadow: 0 0 8px rgba(209,32,38,0.6);
    }
    
    .nav-link:hover {
        color: white;
    }
    
    .nav-link:hover::after,
    .nav-link[aria-current="page"]::after {
        width: calc(100% - 1rem);
    }
    
    .nav-link[aria-current="page"] {
        color: white;
        font-weight: 700;
    }
    
    /* Dropdown styling */
    .rider-profile-button {
        background-color: var(--carbon-fiber);
        background-image: 
            linear-gradient(45deg, rgba(255,255,255,0.03) 25%, transparent 25%),
            linear-gradient(-45deg, rgba(255,255,255,0.03) 25%, transparent 25%),
            linear-gradient(45deg, transparent 75%, rgba(255,255,255,0.03) 75%),
            linear-gradient(-45deg, transparent 75%, rgba(255,255,255,0.03) 75%);
        background-size: 6px 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.3), inset 0 1px 1px rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.1);
    }
    
    .rider-profile-button:hover {
        background-color: var(--matte-gray);
        box-shadow: 0 1px 3px rgba(209,32,38,0.3), inset 0 1px 1px rgba(255,255,255,0.1);
        border-color: var(--engine-red);
    }
    
    .dropdown-menu {
        background-color: var(--carbon-fiber);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 6px;
        box-shadow: 0 10px 15px rgba(0,0,0,0.4);
        overflow: hidden;
    }
    
    .dropdown-item {
        padding: 0.75rem 1rem;
        color: var(--polished-chrome);
        transition: all 0.2s ease;
        font-family: 'Barlow', sans-serif;
    }
    
    .dropdown-item:hover {
        background-color: rgba(209,32,38,0.15);
        color: white;
    }
    
    /* Mobile navigation */
    .moto-nav-toggler {
        border: 1px solid rgba(255,255,255,0.1);
        background-color: var(--carbon-fiber);
        background-image: 
            linear-gradient(45deg, rgba(255,255,255,0.03) 25%, transparent 25%),
            linear-gradient(-45deg, rgba(255,255,255,0.03) 25%, transparent 25%),
            linear-gradient(45deg, transparent 75%, rgba(255,255,255,0.03) 75%),
            linear-gradient(-45deg, transparent 75%, rgba(255,255,255,0.03) 75%);
        background-size: 6px 6px;
    }
    
    .mobile-nav {
        background-color: var(--primary-black);
        border-bottom: 2px solid var(--engine-red);
        box-shadow: 0 5px 15px rgba(0,0,0,0.4);
    }
    
    .mobile-nav-link {
        display: block;
        padding: 0.75rem 1rem;
        color: var(--polished-chrome);
        font-family: 'Barlow', sans-serif;
        font-weight: 600;
        border-left: 3px solid transparent;
        transition: all 0.2s ease;
    }
    
    .mobile-nav-link:hover,
    .mobile-nav-link[aria-current="page"] {
        color: white;
        background-color: rgba(255,255,255,0.03);
        border-left-color: var(--engine-red);
    }
    
    .rider-name {
        font-family: 'Barlow', sans-serif;
        font-weight: 700;
    }
    
    .rider-email {
        opacity: 0.7;
    }
</style>