<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="customizer-hide"
    dir="ltr"
    data-bs-theme="light"
    data-skin="default"
    data-assets-path="{{ asset('vuexy/assets/') }}"
    data-template="horizontal-menu-template"
>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | {{ config('app.name', 'App') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('vuexy/assets/vendor/fonts/iconify-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('vuexy/assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('vuexy/assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('vuexy/assets/css/demo.css') }}" />

    <style>
        html, body { height: 100%; }
        body { font-family: "Public Sans", sans-serif; }

        .authentication-wrapper {
            display: flex;
            align-items: stretch;
            min-height: 100vh;
            position: relative;
        }
        .authentication-cover .auth-cover-brand {
            position: absolute;
            top: 2rem;
            left: 2rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            z-index: 10;
        }
        .authentication-inner { width: 100%; }

        .auth-cover-bg {
            width: 100%;
            height: 100%;
            min-height: 100vh;
            background-color: #f4f5fb;
            position: relative;
            overflow: hidden;
        }
        .auth-illustration {
            max-height: 75vh;
            max-width: 80%;
            position: relative;
            z-index: 2;
        }
        .platform-bg {
            position: absolute;
            bottom: 0;
            width: 100%;
            z-index: 1;
        }
        .authentication-bg {
            background-color: #fff;
        }
        .w-px-400 { width: 400px; max-width: 100%; }
    </style>

    <script src="{{ asset('vuexy/assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('vuexy/assets/js/config.js') }}"></script>
</head>

<body>

    <div class="authentication-wrapper authentication-cover">

        {{-- Logo --}}
        <a href="{{ url('/') }}" class="app-brand auth-cover-brand gap-2">
            <span class="app-brand-logo demo">
                <span class="text-primary">
                    <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                            fill="currentColor" />
                        <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                            d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616" />
                        <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                            d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                            fill="currentColor" />
                    </svg>
                </span>
            </span>
            <span class="app-brand-text demo text-heading fw-bold">{{ config('app.name', 'App') }}</span>
        </a>

        <div class="authentication-inner row m-0">

            {{-- Left illustration --}}
            <div class="d-none d-xl-flex col-xl-8 p-0">
                <div class="auth-cover-bg d-flex justify-content-center align-items-center">
                    <img
                        src="{{ asset('img/illustrations/auth-login-illustration-light.png') }}"
                        alt="login cover"
                        class="my-5 auth-illustration"
                    />
                    <img
                        src="{{ asset('img/illustrations/bg-shape-image-light.png') }}"
                        alt="bg shape"
                        class="platform-bg"
                    />
                </div>
            </div>

            {{-- Login form --}}
            <div class="d-flex col-12 col-xl-4 align-items-center authentication-bg p-sm-12 p-6">
                <div class="w-px-400 mx-auto mt-12 pt-5">

                    <h4 class="mb-1">Welcome! 👋</h4>
                    <p class="mb-6">Please sign in to your account</p>

                    @if (session('status'))
                        <div class="alert alert-success mb-4" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form id="formAuthentication" class="mb-6" method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email --}}
                        <div class="mb-6">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="Enter your email"
                                autofocus
                                required
                                autocomplete="username"
                            />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-6 form-password-toggle">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group input-group-merge">
                                <input
                                    type="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    name="password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    required
                                    autocomplete="current-password"
                                />
                                <span class="input-group-text cursor-pointer">
                                    <i class="icon-base ti tabler-eye-off"></i>
                                </span>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Remember me --}}
                        <div class="my-8">
                            <div class="form-check mb-0 ms-2">
                                <input class="form-check-input" type="checkbox" id="remember_me" name="remember" />
                                <label class="form-check-label" for="remember_me">Remember Me</label>
                            </div>
                        </div>

                        {{-- Login button --}}
                        <button type="submit" class="btn btn-primary d-grid w-100">Login</button>
                    </form>

                </div>
            </div>

        </div>
    </div>

    <script src="{{ asset('vuexy/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('vuexy/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('vuexy/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('vuexy/assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('vuexy/assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('vuexy/assets/js/main.js') }}"></script>
</body>
</html>
