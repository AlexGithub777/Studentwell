<!-- Sidebar -->
<nav id="sidebarMenu" class="sidebar">
    <!-- Sidebar header with logo and close button -->
    <div class="d-flex justify-content-between align-items-center ps-3 pt-4 pb-4 pe-2">
        <img src="{{ asset('assets/images/studentwell-logo.png') }}" alt="StudentWell Logo" style="height: 30px;"
            class="img-fluid">
        <button class="btn btn-link text-secondary fs-5" onclick="toggleSidebar()">
            <i id="sidebar-close-btn" class="fas fa-times"></i>
        </button>
    </div>

    <ul class="nav flex-column p-0">
        @guest
            <li class="nav-item">
                <a class="nav-link {{ request()->is('signup') ? 'active' : '' }}" href="{{ url('/signup') }}">
                    <i class="fas fa-user-plus me-2"></i> Sign Up
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('signin') ? 'active' : '' }}" href="{{ url('/signin') }}">
                    <i class="fas fa-sign-in-alt me-2"></i> Sign In
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                    <i class="fas fa-house me-2"></i> Home
                </a>
            </li>
        @endguest

        @auth
            <li class="nav-item">
                <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                    <i class="fas fa-house me-2"></i> Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('mood') ? 'active' : '' }}" href="{{ url('/mood') }}">
                    <i class="fas fa-brain me-2"></i> Mood Tracking
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('exercise') ? 'active' : '' }}" href="{{ url('/exercise') }}">
                    <i class="fas fa-dumbbell me-2"></i> Exercise Planning & Tracking
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('sleep') ? 'active' : '' }}" href="{{ url('/sleep') }}">
                    <i class="fas fa-moon me-2"></i> Sleep Logging
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('goals') ? 'active' : '' }}" href="{{ url('/goals') }}">
                    <i class="fas fa-bullseye me-2"></i> Goal Setting
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('health-insights') ? 'active' : '' }}"
                    href="{{ url('/health-insights') }}">
                    <i class="fas fa-chart-line me-2"></i> Health Insights
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('support-resources') ? 'active' : '' }}"
                    href="{{ url('/support-resources') }}">
                    <i class="fas fa-address-card me-2"></i> Support Resources
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('forum') ? 'active' : '' }}" href="{{ url('/forum') }}">
                    <i class="fas fa-comments me-2"></i> Forum
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('account') ? 'active' : '' }}" href="{{ url('/account') }}">
                    <i class="fas fa-user me-2"></i> Account
                </a>
            </li>

            @if (Auth::user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin') ? 'active' : '' }}" href="{{ url('/admin') }}">
                        <i class="fas fa-user-tie me-2"></i> Admin
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link text-start ps-3 w-100">
                        <i class="fas fa-sign-out-alt me-2"></i> Log Out
                    </button>
                </form>
            </li>
        @endauth
    </ul>
</nav>
