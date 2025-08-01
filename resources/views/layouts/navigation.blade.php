<header x-data="{
    mobileMenuOpen: false,
    cartItemsCount: {{ \App\Helpers\Cart::getCartItemsCount() }},
}" @cart-change.window="cartItemsCount = $event.detail.count"
    class="flex justify-between bg-slate-800 text-white shadow-md">
    <div>
        <a href="{{ route('home') }}" class="py-navbar-item block pl-5"> Logo </a>
    </div>
    <!-- Mobile Menu -->
    <div class="height fixed bottom-0 top-0 z-10 block h-full w-[220px] bg-slate-900 transition-all md:hidden"
        :class="mobileMenuOpen ? 'left-0' : '-left-[220px]'">
        <ul>
            <li>
                <a href="{{ route('cart.index') }}"
                    class="relative flex items-center justify-between px-3 py-2 transition-colors hover:bg-slate-800">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-mt-1 mr-2 h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Cart
                    </div>
                    <!-- Cart Items Counter -->
                    <small x-show="cartItemsCount" x-transition x-text="cartItemsCount"
                        class="rounded-full bg-red-500 px-[8px] py-[2px]"></small>
                    <!--/ Cart Items Counter -->
                </a>
            </li>

            @auth
                <li x-data="{ open: false }" class="relative">
                    <a @click="open = !open"
                        class="flex cursor-pointer items-center justify-between px-3 py-2 hover:bg-slate-800">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ auth()->user()->name }}
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                    <ul x-show="open" x-transition class="right-0 z-10 bg-slate-800 py-2">
                        <li>
                            <a href="{{ route('profile.show') }}" class="flex px-3 py-2 hover:bg-slate-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                My Profile
                            </a>
                        </li>
                        <li class="hover:bg-slate-900">
                            <a href="{{ route('order.index') }}" class="flex items-center px-3 py-2 hover:bg-slate-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                My Orders
                            </a>
                        </li>
                        <li class="hover:bg-slate-900">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="flex items-center px-3 py-2 hover:bg-slate-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            @else
                <li>
                    <a href="{{ route('login') }}" class="flex items-center px-3 py-2 transition-colors hover:bg-slate-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Login
                    </a>
                </li>

                <li class="px-3 py-3">
                    <a href="{{ route('register') }}"
                        class="block w-full rounded bg-emerald-600 px-3 py-2 text-center text-white shadow-md transition-colors hover:bg-emerald-700 active:bg-emerald-800">
                        Register now
                    </a>
                </li>
            @endauth
        </ul>
    </div>
    <!--/ Mobile Menu -->
    <nav class="hidden md:block">
        <ul class="grid grid-flow-col items-center">
            <li>
                <a href="{{ route('cart.index') }}"
                    class="py-navbar-item px-navbar-item relative inline-flex items-center hover:bg-slate-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Cart
                    <small x-show="cartItemsCount" x-transition x-cloak x-text="cartItemsCount"
                        class="absolute -right-3 top-4 z-[100] rounded-full bg-red-500 px-[8px] py-[2px]"></small>
                </a>
            </li>

            @auth
                <li x-data="{ open: false }" class="relative">
                    <a @click="open = !open"
                        class="py-navbar-item px-navbar-item flex cursor-pointer items-center pr-5 hover:bg-slate-900">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ auth()->user()->name }}
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-5 w-5" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                    <ul @click.outside="open = false" x-show="open" x-transition x-cloak
                        class="absolute right-0 z-10 w-48 bg-slate-800 py-2">
                        <li>
                            <a href="{{ route('profile.show') }}" class="flex px-3 py-2 hover:bg-slate-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                My Profile
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('order.index') }}" class="flex px-3 py-2 hover:bg-slate-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                My Orders
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="flex w-full px-3 py-2 hover:bg-slate-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            @else
                <li>
                    <a href="{{ route('login') }}"
                        class="py-navbar-item px-navbar-item flex items-center hover:bg-slate-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Login
                    </a>
                </li>

                <li>
                    <a href="{{ route('register') }}"
                        class="mx-5 inline-flex items-center rounded bg-emerald-600 px-3 py-2 text-white shadow-md transition-colors hover:bg-emerald-700 active:bg-emerald-800">
                        Register now
                    </a>
                </li>
            @endauth
        </ul>
    </nav>
    <button @click="mobileMenuOpen = !mobileMenuOpen" class="block p-4 md:hidden">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
</header>
