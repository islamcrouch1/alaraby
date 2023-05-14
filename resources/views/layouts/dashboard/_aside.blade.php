<nav class="navbar navbar-light navbar-vertical navbar-expand-xl">
    <script>
        var navbarStyle = localStorage.getItem("navbarStyle");
        if (navbarStyle && navbarStyle !== 'transparent') {
            document.querySelector('.navbar-vertical').classList.add(`navbar-${navbarStyle}`);
        }
    </script>
    <div class="d-flex align-items-center">
        <div class="toggle-icon-wrapper">

            <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-bs-toggle="tooltip"
                data-bs-placement="left" title="Toggle Navigation"><span class="navbar-toggle-icon"><span
                        class="toggle-line"></span></span></button>

        </div><a class="navbar-brand" href="{{ route('home') }}">
            <div class="d-flex align-items-center py-3"><img class="me-2"
                    src="{{ asset('assets/img/logo-blue.png') }}" alt="" width="150" />
            </div>
        </a>
    </div>
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <div class="navbar-vertical-content scrollbar">
            <ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">

                @if (Auth::user()->hasRole('administrator|superadministrator'))
                    <li class="nav-item">
                        <!-- label-->
                        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                            <!-- users - roles - countries - settings -->
                            <div class="col-auto navbar-vertical-label">{{ __('Users & Roles') }}
                            </div>
                            <div class="col ps-0">
                                <hr class="mb-0 navbar-vertical-divider" />
                            </div>
                        </div>
                        @if (auth()->user()->hasPermission('users-read'))
                            <!-- parent pages--><a class="nav-link {{ Route::is('users*') ? 'active' : '' }}"
                                href="{{ route('users.index') }}" role="button" data-bs-toggle=""
                                aria-expanded="false">
                                <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                            class="fas fa-user"></span></span><span
                                        class="nav-link-text ps-1">{{ __('Users') }}</span>
                                    <span
                                        class="badge badge-soft-primary">{{ \app\models\User::all()->count() - 1 }}</span>
                                </div>
                            </a>
                        @endif

                        @if (auth()->user()->hasPermission('roles-read'))
                            <!-- parent pages--><a class="nav-link {{ Route::is('roles*') ? 'active' : '' }}"
                                href="{{ route('roles.index') }}" role="button" data-bs-toggle=""
                                aria-expanded="false">
                                <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                            class="fas fa-user-tag"></span></span><span
                                        class="nav-link-text ps-1">{{ __('Roles') }}</span>
                                </div>
                            </a>
                        @endif

                        @if (auth()->user()->hasPermission('centrals-read'))
                            <!-- parent pages-->
                            <a class="nav-link {{ Route::is('centrals*') ? 'active' : '' }}"
                                href="{{ route('centrals.index') }}" role="button" data-bs-toggle=""
                                aria-expanded="false">

                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon">
                                        <span class="fas fa-user-tag">
                                        </span>
                                    </span>
                                    <span class="nav-link-text ps-1">
                                        {{ __('Centrals') }}
                                    </span>
                                    <span class="badge badge-soft-primary">
                                        {{ getCentralsCount() }}
                                    </span>
                                </div>
                            </a>
                        @endif


                    </li>
                @endif

            </ul>

        </div>
    </div>
</nav>
