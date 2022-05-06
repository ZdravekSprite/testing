<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
  <!-- Primary Navigation Menu -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
      <div class="flex">
        <!-- Logo -->
        <div class="flex-shrink-0 flex items-center">
          <a href="{{ route('home') }}">
            <x-application-logo class="block h-10 w-auto fill-current text-gray-600" />
          </a>
        </div>

        <!-- Navigation Links -->
        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
          <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
          </x-nav-link>
        </div>
        @impersonate
        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
          <x-nav-link :href="route('admin.impersonate.stop')" :active="true">
            {{ __('Stop Impersonating') }}
          </x-nav-link>
        </div>
        @endimpersonate
        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
          <x-nav-link :href="route('chat')" :active="request()->routeIs('chat')">
            {{ __('Chat') }}
          </x-nav-link>
        </div>
        @hasrole('superadmin')
        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
          <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index')">
            {{ __('Menage Users') }}
          </x-nav-link>
        </div>
        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
          <x-nav-link :href="route('admin.roles.index')" :active="request()->routeIs('admin.roles.index')">
            {{ __('Menage Roles') }}
          </x-nav-link>
        </div>
        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
          <x-nav-link :href="route('routes.index')" :active="request()->routeIs('routes.index')">
            {{ __('Rute') }}
          </x-nav-link>
        </div>
        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
          <x-nav-link :href="route('signs.index')" :active="request()->routeIs('routes.index')">
            {{ __('Znakovi') }}
          </x-nav-link>
        </div>
        @endhasrole
        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
          <x-nav-link :href="route('holidays.index')" :active="request()->routeIs('holidays.index')">
            {{ __('Praznici') }}
          </x-nav-link>
        </div>
        @hasrole('user')
        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
          <x-nav-link :href="route('month')" :active="request()->routeIs('month', 'days.index', 'days.create', 'days.show', 'days.edit')">
            {{ __('ERS') }}
          </x-nav-link>
        </div>
        <div class="hidden sm:flex sm:items-center sm:ml-6">
          <x-dropdown align="left">
            <x-slot name="trigger">
              <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                <div class="ml-1">
                  <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </div>
              </button>
            </x-slot>
            <x-slot name="content">
              <div class="pt-2 pb-1 border-t border-gray-200">
                <x-nav-link :href="route('days.index')" :active="request()->routeIs('days.index')">
                  {{ __('Radni dani') }}
                </x-nav-link>
              </div>
              <div class="pt-2 pb-1 border-t border-gray-200">
                <x-nav-link :href="route('day.create')" :active="request()->routeIs('day.create')">
                  {{ __('Novi dan') }}
                </x-nav-link>
              </div>
              <div class="pt-2 pb-1 border-t border-gray-200">
                <x-nav-link :href="route('months.index')" :active="request()->routeIs('months.index')">
                  {{ __('Mjeseci') }}
                </x-nav-link>
              </div>
              <div class="pt-2 pb-1 border-gray-200">
                <x-nav-link :href="route('months.create')" :active="request()->routeIs('months.create')">
                  {{ __('Novi mjesec') }}
                </x-nav-link>
              </div>
            </x-slot>
          </x-dropdown>
        </div>
        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
          <x-nav-link :href="route('lista')" :active="request()->routeIs('lista')">
            {{ __('Platna lista') }}
          </x-nav-link>
        </div>
        @endhasrole
        @hasrole('binance')
        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
          <x-nav-link :href="route('bHome')" :active="request()->routeIs('bHome')">
            {{ __('Binance') }}
          </x-nav-link>
        </div>
        <div class="hidden sm:flex sm:items-center sm:ml-6">
          <x-dropdown align="left">
            <x-slot name="trigger">
              <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                <div class="ml-1">
                  <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </div>
              </button>
            </x-slot>
            <x-slot name="content">
              <div class="pt-2 pb-1 border-t border-gray-200">
                <x-nav-link :href="route('bPortfolio')" :active="request()->routeIs('bPortfolio')">
                  {{ __('Portfolio') }}
                </x-nav-link>
              </div>
              <div class="pt-2 pb-1 border-t border-gray-200">
                <x-nav-link :href="route('allMyTrades')" :active="request()->routeIs('allMyTrades')">
                  {{ __('allMyTrades') }}
                </x-nav-link>
              </div>
              <div class="pt-2 pb-1 border-t border-gray-200">
                <x-nav-link :href="route('bExchange')" :active="request()->routeIs('bExchange')">
                  {{ __('bExchange') }}
                </x-nav-link>
              </div>
            </x-slot>
          </x-dropdown>
        </div>
        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
          <x-nav-link :href="route('eurojackpot')" :active="request()->routeIs('eurojackpot')">
            {{ __('EuroJackPot') }}
          </x-nav-link>
        </div>
        @endhasrole
      </div>

      @if (Route::has('login'))
      @auth
      <!-- Settings Dropdown -->
      <div class="hidden sm:flex sm:items-center sm:ml-6">
        <x-dropdown align="right" width="48">
          <x-slot name="trigger">
            <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
              <div>{{ Auth::user()->name }}</div>
              <div class="ml-1">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </div>
            </button>
          </x-slot>

          <x-slot name="content">
            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
              @csrf

              <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                {{ __('Log out') }}
              </x-dropdown-link>
            </form>
          </x-slot>
        </x-dropdown>
      </div>
      @else
      <div class="hidden space-x-8 sm:flex sm:items-center sm:ml-6">
        <x-nav-link :href="route('login')">
          {{ __('Login') }}
        </x-nav-link>
        @if (Route::has('register'))
        <x-nav-link :href="route('register')">
          {{ __('Register') }}
        </x-nav-link>
        @endif
      </div>
      @endauth
      @endif

      <!-- Hamburger -->
      <div class="-mr-2 flex items-center sm:hidden">
        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
          <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Responsive Navigation Menu -->
  <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
    <div class="pt-2 pb-3 space-y-1">
      <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
      </x-responsive-nav-link>
    </div>
    @impersonate
    <div class="pt-4 pb-1 border-t border-gray-200">
      <x-responsive-nav-link :href="route('admin.impersonate.stop')" :active="true">
        {{ __('Stop Impersonating') }}
      </x-responsive-nav-link>
    </div>
    @endimpersonate
    <div class="pt-4 pb-1 border-t border-gray-200">
      <x-responsive-nav-link :href="route('chat')" :active="request()->routeIs('chat')">
        {{ __('Chat') }}
      </x-responsive-nav-link>
    </div>
    @hasrole('superadmin')
    <div class="pt-4 pb-1 border-t border-gray-200">
      <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index')">
        {{ __('Menage Users') }}
      </x-responsive-nav-link>
    </div>
    <div class="pt-4 pb-1 border-t border-gray-200">
      <x-responsive-nav-link :href="route('admin.roles.index')" :active="request()->routeIs('admin.roles.index')">
        {{ __('Menage Roles') }}
      </x-responsive-nav-link>
    </div>
    @endhasrole
    <div class="pt-4 pb-1 border-t border-gray-200">
      <x-responsive-nav-link :href="route('holidays.index')" :active="request()->routeIs('holidays.index')">
        {{ __('Praznici') }}
      </x-responsive-nav-link>
    </div>
    @hasrole('user')
    <div class="pt-4 pb-1 border-t border-gray-200">
      <x-responsive-nav-link :href="route('month')" :active="request()->routeIs('month')">
        {{ __('ERS') }}
      </x-responsive-nav-link>
    </div>
    <div class="pb-1 border-gray-200">
      <x-responsive-nav-link :href="route('day.create')" :active="request()->routeIs('day.create')">
        {{ __('Novi dan') }}
      </x-responsive-nav-link>
    </div>
    <div class="pt-4 pb-1 border-t border-gray-200">
      <x-responsive-nav-link :href="route('months.index')" :active="request()->routeIs('months.index')">
        {{ __('Mjeseci') }}
      </x-responsive-nav-link>
    </div>
    <div class="pb-1 border-gray-200">
      <x-responsive-nav-link :href="route('months.create')" :active="request()->routeIs('months.create')">
        {{ __('Novi mjesec') }}
      </x-responsive-nav-link>
    </div>
    <div class="pt-4 pb-1 border-t border-gray-200">
      <x-responsive-nav-link :href="route('lista')" :active="request()->routeIs('lista')">
        {{ __('Platna lista') }}
      </x-responsive-nav-link>
    </div>
    @endhasrole

    <!-- Responsive Settings Options -->
    @if (Route::has('login'))
    @auth
    <div class="pt-4 pb-1 border-t border-gray-200">
      <div class="flex items-center px-4">
        <div class="flex-shrink-0">
          <svg class="h-10 w-10 fill-current text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
        </div>

        <div class="ml-3">
          <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
          <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
        </div>
      </div>

      <div class="mt-3 space-y-1">
        <!-- Authentication -->
        <form method="POST" action="{{ route('logout') }}">
          @csrf

          <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();">
            {{ __('Log out') }}
          </x-responsive-nav-link>
        </form>
      </div>
    </div>
    @else

    <div class="pt-4 pb-1 border-t border-gray-200">
      <div class="mt-3 space-y-1">
        <x-responsive-nav-link :href="route('login')">
          {{ __('Login') }}
        </x-responsive-nav-link>
      </div>

      @if (Route::has('register'))
      <div class="mt-3 space-y-1">
        <x-responsive-nav-link :href="route('register')">
          {{ __('Register') }}
        </x-responsive-nav-link>
      </div>
      @endif
    </div>
    @endauth
    @endif
  </div>
</nav>
