<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

    {{-- Sidebar brand logo --}}
    @if(config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    {{-- Sidebar menu --}}
    <div class="sidebar">
        <nav class="pt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if(config('adminlte.sidebar_nav_animation_speed') != 300)
                    data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}"
                @endif
                @if(!config('adminlte.sidebar_nav_accordion'))
                    data-accordion="false"
                @endif>
                {{-- Configured sidebar links --}}
                {{-- @each('adminlte::partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item') --}}
                 @if(Auth::user()->role === 'admin')
                    <!-- Admin-Specific Menu Items -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('libraries.index') }}">
                         <i class="fas fa-book-reader"></i>  <p>Manage Libray</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('books.index') }}">
                          <i class="fa fa-solid fa-book"></i> <p>Manage Books</p>
                        </a>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link" href="{{ route('books-libray.index') }}">
                          <i class="fa fa-solid fa-book"></i> <p>Manage Books Libray</p>
                        </a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="{{ url('reservation') }}">
                        <i class="fa fa-shopping-cart"></i> <span>Reserved Books</span>
                    </a>
                </li>
            @elseif(Auth::user()->role === 'users')
                <!-- User-Specific Menu Items -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('nearby-libraries') }}">
                        <i class="fas fa-map-marker-alt"></i><p>Nearest Libraries</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('reservation') }}">
                        <i class="fa fa-shopping-cart"></i> <span>Reserve Books</span>
                    </a>
                </li>
            @endif
            </ul>
        </nav>
    </div>

</aside>
