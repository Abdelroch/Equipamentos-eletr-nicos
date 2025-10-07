
     <!--  Header Start -->
     <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
            <li class="nav-item">
                {{-- aqui vai estar o link que vai redirecionar no perfil --}}
              <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                <i class="ti ti-bell-ringing"></i>
                <div class="notification bg-primary rounded-circle"></div>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
{{--               <a href="https://adminmart.com/product/Spike-free-bootstrap-admin-dashboard/" target="_blank" class="btn btn-primary">Download Free</a>
 --}}              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                   <div style="font-size:15px; margin-right:5px; "> <strong><b>Admin:</b></strong> {{ Auth::user()->name }}</div> 
                  <img src="{{asset('../assets/images/profile/profile_photo.webp')}}" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    @if (request()->routeIs('profile.edit'))
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 dropdown-item">
                        <i class="ti ti-dashboard fs-6"></i>
                        <p class="mb-0 fs-3"> Dashboard</p>
                    </a>
                @else
                    <a href="{{ route('profile.edit') }}" class="d-flex align-items-center gap-2 dropdown-item">
                        <i class="ti ti-user fs-6"></i>
                        <p class="mb-0 fs-3">Meu Perfil!</p>
                    </a>
                @endif
                       <form action="{{route('logout')}}" method="post">
                            @csrf
                            
                          <button class="btn btn-outline-primary mx-3 mt-2 d-block shadow-none" type="submit" >Logout</button>
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