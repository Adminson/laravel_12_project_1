<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="customizer-hide"
    dir="ltr"
    data-bs-theme="light"
    data-skin="default"
    data-assets-path="../../assets/"
    data-template="vertical-menu-template-no-customizer"
>

<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet"
    />
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@400;700&family=Inter:wght@400;700&display=swap"
        rel="stylesheet"
    >
    <!-- ✅ Vuexy CSS (adjust paths to match your Vuexy HTML package) -->
    <link
        rel="stylesheet"
        href="{{ asset('vuexy/assets/vendor/css/core.css') }}"
        class="template-customizer-core-css"
    />
    <link
        rel="stylesheet"
        href="{{ asset('vuexy/assets/css/demo.css') }}"
    >
    <link
        rel="stylesheet"
        href="{{ asset('vuexy/assets/vendor/fonts/iconify-icons.css') }}"
    >
    <link
        rel="stylesheet"
        href="{{ asset('vuexy/assets/vendor/libs/node-waves/node-waves.css') }}"
    >

    <!-- Vuexy vendor libs (only if your template uses them) -->
    <link
        rel="stylesheet"
        href="{{ asset('vuexy/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}"
    >

    <!-- Helpers (Vuexy requires this early) -->
    <script src="{{ asset('vuexy/assets/vendor/js/helpers.js') }}"></script>
    <!-- Optional: if your Vuexy has config.js, include it -->
    <script src="{{ asset('vuexy/assets/js/config.js') }}"></script>
    <script src="{{ asset('vuexy/assets/vendor/libs/@algolia/autocomplete-js.js') }}"></script>

    {{-- DataTables Bootstrap 5 CSS --}}
    <link
        rel="stylesheet"
        href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css"
    >

    {{--
    <link rel="stylesheet" href="{{ asset('vuexy/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}"> --}}

    {{-- datetime picker CSS --}}
    {{--
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.css"> --}}
    <link
        rel="stylesheet"
        href="{{ asset('vuexy/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}"
    >

    {{-- sweet alert2 --}}
    <link
        rel="stylesheet"
        href="{{ asset('vuexy/assets/vendor/libs/sweetalert2/sweetalert2.css') }}"
    >

    {{-- select2 --}}
    <link
        rel="stylesheet"
        href="{{ asset('vuexy/assets/vendor/libs/select2/select2.css') }}"
    >
    {{-- highlight --}}
    <link
        rel="stylesheet"
        href="{{ asset('vuexy/assets/vendor/libs/highlight/highlight.css') }}"
    >
    {{-- Quill --}}
    <link
        rel="stylesheet"
        href="{{ asset('vuexy/assets/vendor/libs/quill/typography.css') }}"
    >
    <link
        rel="stylesheet"
        href="{{ asset('vuexy/assets/vendor/libs/quill/katex.css') }}"
    >
    <link
        rel="stylesheet"
        href="{{ asset('vuexy/assets/vendor/libs/quill/editor.css') }}"
    >
    @stack('styles')
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">

            <!-- Navbar -->
            <nav
                class="layout-navbar navbar navbar-expand-xl align-items-center"
                id="layout-navbar"
            >
                <div class="container-fluid">
                    <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4 ms-0">
                        <a
                            href="index.html"
                            class="app-brand-link"
                        >
                            <span class="app-brand-logo demo">
                                <span class="text-primary">
                                    <svg
                                        width="32"
                                        height="22"
                                        viewBox="0 0 32 22"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            clip-rule="evenodd"
                                            d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                                            fill="currentColor"
                                        />
                                        <path
                                            opacity="0.06"
                                            fill-rule="evenodd"
                                            clip-rule="evenodd"
                                            d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z"
                                            fill="#161616"
                                        />
                                        <path
                                            opacity="0.06"
                                            fill-rule="evenodd"
                                            clip-rule="evenodd"
                                            d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z"
                                            fill="#161616"
                                        />
                                        <path
                                            fill-rule="evenodd"
                                            clip-rule="evenodd"
                                            d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                                            fill="currentColor"
                                        />
                                    </svg>
                                </span>
                            </span>
                            <span class="app-brand-text demo menu-text fw-bold text-heading">Vuexy</span>
                        </a>

                        <a
                            href="javascript:void(0);"
                            class="layout-menu-toggle menu-link text-large ms-auto d-xl-none"
                        >
                            <i
                                class="icon-base ti tabler-x icon-sm d-flex align-items-center justify-content-center"></i>
                        </a>
                    </div>

                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0  d-xl-none  ">
                        <a
                            class="nav-item nav-link px-0 me-xl-6"
                            href="javascript:void(0)"
                        >
                            <i class="icon-base ti tabler-menu-2 icon-md"></i>
                        </a>
                    </div>

                    <div
                        class="navbar-nav-right d-flex align-items-center justify-content-end"
                        id="navbar-collapse"
                    >
                        <ul class="navbar-nav flex-row align-items-center ms-md-auto">
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a
                                    class="nav-link dropdown-toggle hide-arrow p-0"
                                    href="javascript:void(0);"
                                    data-bs-toggle="dropdown"
                                >
                                    <div class="avatar avatar-online">
                                        <img
                                            src="../../assets/img/avatars/1.png"
                                            alt
                                            class="rounded-circle"
                                        />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="#"
                                        >
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img
                                                            src="../../assets/img/avatars/1.png"
                                                            alt
                                                            class="w-px-40 h-auto rounded-circle"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">John Doe</h6>
                                                    <small class="text-body-secondary">Admin</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1 mx-n2"></div>
                                    </li>
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="#"
                                        >
                                            <i class="icon-base ti tabler-user icon-md me-3"></i><span>My Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="#"
                                        >
                                            <i
                                                class="icon-base ti tabler-settings icon-md me-3"></i><span>Settings</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="#"
                                        >
                                            <span class="d-flex align-items-center align-middle">
                                                <i
                                                    class="flex-shrink-0 icon-base ti tabler-credit-card icon-md me-3"></i><span
                                                    class="flex-grow-1 align-middle"
                                                >Billing Plan</span>
                                                <span class="flex-shrink-0 badge rounded-pill bg-danger">4</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1 mx-n2"></div>
                                    </li>
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="javascript:void(0);"
                                        >
                                            <i class="icon-base ti tabler-power icon-md me-3"></i><span>Log Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- / Navbar -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Menu -->
                    @include('layouts.top_menu')

                    <!-- / Menu -->

                    {{-- loading screen --}}
                    <div
                        id="global-loader"
                        style="display:none; position:fixed; inset:0; background:rgba(255, 255, 255, 0.774); z-index: 9999;"
                    >
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <div
                                class="spinner-border spinner-border-lg text-primary"
                                role="status"
                            >
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->

                    <div class="container-fluid flex-grow-1 container-p-y">
                        {{-- {{ $slot }} --}}
                        @yield('content')
                    </div>
                    <!--/ Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-fluid">
                            <div
                                class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                                <div class="text-body">
                                    &#169;
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script>
                                    , made with ❤️ by <a
                                        href="https://pixinvent.com"
                                        target="_blank"
                                        class="footer-link"
                                    >Pixinvent</a>
                                </div>
                                <div class="d-none d-lg-inline-block">
                                    <a
                                        href="https://demos.pixinvent.com/vuexy-html-admin-template/documentation/"
                                        target="_blank"
                                        class="footer-link me-4"
                                    >Documentation</a>
                                </div>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!--/ Content wrapper -->
            </div>

            <!--/ Layout container -->
        </div>
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>

    <!-- ✅ jQuery 3.7 (if you need it) -->
    <!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> -->

    <!-- ✅ Vuexy JS (order matters; adjust based on your Vuexy bundle) -->
    <script src="{{ asset('vuexy/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('vuexy/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('vuexy/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('vuexy/assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('vuexy/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('vuexy/assets/vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('vuexy/assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('vuexy/assets/js/main.js') }}"></script>
    {{-- DataTables JS --}}
    {{-- <script src="{{ asset('vuexy/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script> --}}
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>

    {{-- jQuery Validation --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/additional-methods.min.js"></script>

    {{-- datetime picker JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js">
    </script> --}}
    <script src="{{ asset('vuexy/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>

    {{-- sweet alert2 --}}
    <script src="{{ asset('vuexy/assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    {{-- select2 --}}
    <script src="{{ asset('vuexy/assets/vendor/libs/select2/select2.js') }}"></script>
    {{-- highlight --}}
    <script src="{{ asset('vuexy/assets/vendor/libs/highlight/highlight.js') }}"></script>

    {{-- Quill --}}
    <script src="{{ asset('vuexy/assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('vuexy/assets/vendor/libs/quill/quill.js') }}"></script>
    @vite(['resources/js/app.js'])
    <!-- CSRF for jQuery AJAX -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
