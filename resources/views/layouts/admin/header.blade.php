<!--  Header Start -->
<header class="app-header">
    <nav class="navbar navbar-expand-lg navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
                <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                    <i class="ti ti-menu-2"></i>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover position-relative" href="javascript:void(0)" id="drop1"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-bell-ringing"></i>
                    @php $unreadAdmin = Auth::user()->unreadNotifications()->count(); @endphp
                    @if ($unreadAdmin > 0)
                        <div class="notification bg-primary rounded-circle"></div>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop1"
                    style="width: 360px; max-height: 420px; overflow-y: auto;">
                    <div class="px-3 py-2 d-flex justify-content-between align-items-center border-bottom">
                        <p class="mb-0 fs-4 fw-bold">Notificações</p>
                        @if ($unreadAdmin > 0)
                            <span class="badge bg-primary rounded-pill">{{ $unreadAdmin }}</span>
                        @endif
                    </div>

                    @forelse(Auth::user()->notifications()->latest()->take(10)->get() as $notification)
                        <a href="{{ route('admin.orders.index') }}?order={{ $notification->data['order_id'] ?? '' }}"
                            class="d-flex align-items-start gap-2 px-3 py-2 dropdown-item {{ $notification->read_at ? '' : 'bg-light-primary' }}">
                            <i
                                class="ti ti-circle-filled fs-3 mt-1 {{ $notification->read_at ? 'text-muted' : 'text-primary' }}"></i>
                            <div>
                                <p class="mb-0 fs-3">{{ $notification->data['message'] ?? 'Notificação' }}</p>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                        </a>
                    @empty
                        <p class="px-3 py-4 mb-0 text-center text-muted fs-3">Sem notificações.</p>
                    @endforelse

                    @if (Auth::user()->notifications()->count() > 0)
                        <div class="px-3 py-2 text-center border-top">
                            <form action="{{ route('admin.notifications.read_all') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="p-0 btn btn-link btn-sm">Marcar todas como lidas</button>
                            </form>
                        </div>
                    @endif
                </div>
            </li>

        </ul>
        <div class="px-0 navbar-collapse justify-content-end" id="navbarNav">
            <ul class="flex-row navbar-nav ms-auto align-items-center justify-content-end">
                {{--               <a href="https://adminmart.com/product/Spike-free-bootstrap-admin-dashboard/" target="_blank" class="btn btn-primary">Download Free</a>
 --}} <li class="nav-item dropdown">
                    <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <div style="font-size:15px; margin-right:5px; "> <strong><b>Admin:</b></strong>
                            {{ Auth::user()->name }}</div>
                        <img src="{{ asset('../assets/images/profile/profile_photo.webp') }}" alt=""
                            width="35" height="35" class="rounded-circle">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                        <div class="message-body">
                            @if (request()->routeIs('profile.edit'))
                                <a href="{{ route('admin.dashboard') }}"
                                    class="gap-2 d-flex align-items-center dropdown-item">
                                    <i class="ti ti-dashboard fs-6"></i>
                                    <p class="mb-0 fs-3"> Dashboard</p>
                                </a>
                            @else
                                <a href="{{ route('profile.edit') }}"
                                    class="gap-2 d-flex align-items-center dropdown-item">
                                    <i class="ti ti-user fs-6"></i>
                                    <p class="mb-0 fs-3">Meu Perfil!</p>
                                </a>
                            @endif
                            <form action="{{ route('logout') }}" method="post">
                                @csrf

                                <button class="mx-3 mt-2 shadow-none btn btn-outline-primary d-block"
                                    type="submit">Logout</button>
                            </form>
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>
<!--  Header End -->

