<div class="user-sidebar">
    <div class="sidebar-header p-3 text-center">
        <h5 class="text-white">{{ Auth::user()->username }}</h5>
    </div>
    
    <ul class="sidebar-menu list-unstyled">
        <li>
            <a href="{{ route('user.home') }}" class="d-block p-2 text-white">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('user.deposit') }}" class="d-block p-2 text-white">
                <i class="fas fa-wallet me-2"></i> Deposit
            </a>
        </li>
        <li>
            <a href="{{ route('user.withdraw') }}" class="d-block p-2 text-white">
                <i class="fas fa-money-bill me-2"></i> Withdraw
            </a>
        </li>
        <li>
            <a href="{{ route('user.transactions') }}" class="d-block p-2 text-white">
                <i class="fas fa-history me-2"></i> Transactions
            </a>
        </li>
        <li>
            <a href="{{ route('user.profile') }}" class="d-block p-2 text-white">
                <i class="fas fa-user me-2"></i> Profile
            </a>
        </li>
        <li>
            <a href="{{ route('user.logout') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="d-block p-2 text-white">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('user.logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</div>
