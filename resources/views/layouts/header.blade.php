<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css') }}">
    <!-- bootstrap -->
    <script src="{{ asset('assets/fontawesome/fontawesome.js') }}"></script>
    <!-- style -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- style -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/restaurant-logo.png') }}">
    <!-- dataTables -->
    <link rel="stylesheet" href="{{ asset('assets/js/dataTables.min.css') }}">
    <!-- dataTables -->

    <title>Restaturant POS</title>

    <style>
        ::-webkit-scrollbar {
      width: 5px;
    }

    ::-webkit-scrollbar:horizontal {
      height: 5px;
    }

    ::-webkit-scrollbar-thumb {
      background-color: rgba(255, 143, 84, 1);
      border-radius: 10px;
    }

    ::-webkit-scrollbar-track {
      background-color: #F5F5F5;
    }

    ::-webkit-scrollbar-track:horizontal {
      display: none;
    }

    ::-webkit-scrollbar-horizontal {
      display: none;
    }

        body {
            font-family: "Lato", sans-serif;
            margin: 0;
            background: rgba(255, 178, 107, 1);
        }

        .sidebar {
            padding-top: 10px !important;
            height: 100%;
            width: 200px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background: rgb(0, 163, 255);
            background: linear-gradient(90deg, rgb(255, 123, 84) 20%, rgb(255, 178, 107) 60%);
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }

        .sidebar a {
            padding: 8px 8px 8px 18px;
            text-decoration: none;
            font-size: 14px;
            color: #FFFF;
            display: block;
            transition: 0.3s;
            margin: 20px;
            border-radius: 10px;
        }

        .sidebar a.link:hover {
            background-color: #FFFF;
            color: black;
        }
        .sidebar a.linkk {
            background-color: #FFFF;
            color: black;
        }

        .sidebar a:hover .white-img {

            display: none;
        }

        .sidebar a:hover .dark-img {


            visibility: visible;
            position: static;
        }
        .sidebar img.white-imgg {

            display: none;
        }

        .sidebar img.dark-imgg {


            visibility: visible;
            position: static;
        }


        .dark-img {

            visibility: hidden;
            position: absolute;
        }

        .sidebar .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        .openbtn {
            font-size: 20px;
            cursor: pointer;
            color: rgba(255, 178, 107, 1);
            background-color: transparent;
            padding: 10px 15px;
            border: none;
            outline:none; 
        }
.openbtn:focus{
    border: none !important;
    outline: none !important;
}
        .openbtn:hover {
            color: rgba(255, 178, 107, 1);
           background-color: transparent;
        }

        #main {
            transition: margin-left .5s;
            padding: 16px;
            /* margin-left: 250px; */
        }

        /* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
        @media screen and (max-height: 450px) {
            .sidebar {
                padding-top: 15px;

            }


            .sidebar a {
                font-size: 18px;
            }
        }

        @media screen and (max-width:550px) {
            .sidebar {
                width: 0px;
            }

            .main {
                margin-left: 0;

            }

            .main-panel {
                margin-left: 0 !important;
                border-radius: 0 !important;
            }

            #openbtn {
                display: block !important;
            }

            #closebtn {
                display: none !important;
            }

            .closeBtn {
                display: block !important;
                font-size: 20px;
                cursor: pointer;
                background-color: transparent;
                color: white;
                padding: 10px 15px;
                border: none;
                margin-left:150px 

            }
            .closeBtn:focus{
                outline: none
            }
        }

        .main-panel {
            margin-left: 200px;
            border-radius: 30px 0 0 30px;
            background-color: #f5F5F5;
            height: 100vh;
            transition: margin-left .5s;
        }

        .link>img {
            margin-right: 10px;
        }
    </style>
</head>

<body>

    <div id="mySidebar" class="sidebar">
        <div align="center">
            <img style=" width: 90%; height: 90%;" src="{{ asset('assets/images/restaurant-logo.png') }}" alt="Image">
        </div>
        <button class="closeBtn" id="closeBtn" style="display:none ;" onclick="closeNav()"><i class="fa fa-close"></i></button>
        @if (session()->has('user_details'))
            <a href="/dashboard" class="link {{Request::is('dashboard') ? 'linkk ':''}}">
                <img src="{{ asset('assets/images/d-white.svg') }}" class="white-img mb-1 {{Request::is('dashboard') ? 'white-imgg ':''}}" alt="Image">
                <img src="{{ asset('assets/images/d-dark.svg') }}" class="dark-img mb-1 {{Request::is('dashboard') ? 'dark-imgg ':''}}" alt="Image">
                @lang('lang.dashboard')
            </a>
            @if (session('user_details')['role'] == 'superadmin')
                <a href="/company" class="link {{Request::is('company') ? 'linkk ':''}}">
                    <img src="{{ asset('assets/images/c-white.svg') }}" class="white-img mb-1 {{Request::is('company') ? 'white-imgg ':''}}" alt="Image">
                    <img src="{{ asset('assets/images/c-dark.svg') }}" class="dark-img mb-1 {{Request::is('company') ? 'dark-imgg ':''}}" alt="Image">
                    @lang('lang.company')
                </a>
            @endif
            @if (session('user_details')['role'] == 'superadmin')
                <a href="/requests" class="link {{Request::is('requests') ? 'linkk ':''}}">
                    <img src="{{ asset('assets/images/c-white.svg') }}" class="white-img mb-1 {{Request::is('requests') ? 'white-imgg ':''}}" alt="Image">
                    <img src="{{ asset('assets/images/c-dark.svg') }}" class="dark-img mb-1 {{Request::is('requests') ? 'dark-imgg ':''}}" alt="Image">
                    @lang('lang.requests')
                </a>
            @endif
            @if (session('user_details')['role'] == '1')
                <a href="/staff" class="link {{Request::is('staff') ? 'linkk ':''}}">
                    <img src="{{ asset('assets/images/c-white.svg') }}" class="white-img mb-1 {{Request::is('staff') ? 'white-imgg ':''}}" alt="Image">
                    <img src="{{ asset('assets/images/c-dark.svg') }}" class="dark-img  mb-1 {{Request::is('staff') ? 'dark-imgg ':''}}" alt="Image">
                    @lang('lang.staff')
                </a>
            @endif
            @if (session('user_details')['role'] == '1')
                <a href="/services" class="link {{Request::is('services') ? 'linkk ':''}}">
                    <img src="{{ asset('assets/images/p-white.svg') }}" class="white-img mb-1 {{Request::is('services') ? 'white-imgg ':''}}" alt="Image">
                    <img src="{{ asset('assets/images/p-dark.svg') }}" class="dark-img mb-1 {{Request::is('services') ? 'dark-imgg ':''}}" alt="Image">
                    @lang('lang.services')
                </a>
            @endif
            @if (session('user_details')['role'] == '1')
                <a href="/customers" class="link {{Request::is('customers') ? 'linkk ':''}}">
                    <img src="{{ asset('assets/images/u-white.svg') }}" class="white-img mb-1 {{Request::is('customers') ? 'white-imgg ':''}}" alt="Image">
                    <img src="{{ asset('assets/images/u-dark.svg') }}" class="dark-img mb-1 {{Request::is('customers') ? 'dark-imgg ':''}}" alt="Image">
                    @lang('lang.customers')
                </a>
            @endif
            @if (session('user_details')['role'] == '1')
                <a href="/gallery" class="link {{Request::is('gallery') ? 'linkk ':''}}">
                    <img src="{{ asset('assets/images/p-white.svg') }}" class="white-img mb-1 {{Request::is('gallery') ? 'white-imgg ':''}}" alt="Image">
                    <img src="{{ asset('assets/images/p-dark.svg') }}" class="dark-img mb-1 {{Request::is('gallery') ? 'dark-imgg ':''}}" alt="Image">
                    @lang('lang.gallery')
                </a>
            @endif
            <a href="/settings" class="link {{Request::is('settings') ? 'linkk ':''}}">
               <span class=" mb-1 " style="margin-right:10px;"><i class="fa fa-cog" aria-hidden="true"></i>
               </span>
                    {{-- <img src="{{ asset('assets/images/p-white.svg') }}" class="white-img mb-1" alt="Image"> --}}
                    {{-- <img src="{{ asset('assets/images/p-dark.svg') }}" class="dark-img mb-1" alt="Image"> --}}
                    @lang('lang.settings')
                </a>
            <a href="/logout">
                <button class="btn p-0">
                    <img src="{{ asset('assets/images/logout-btn.svg') }}" style="width: 100%; height: 100%;"
                        alt="button">
                    <!-- <svg width="22" height="21" viewBox="0 0 22 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.7907 5.75V3.375C13.7907 2.74511 13.5457 2.14102 13.1096 1.69562C12.6734 1.25022 12.0819 1 11.4651 1H3.32558C2.7088 1 2.11728 1.25022 1.68115 1.69562C1.24502 2.14102 1 2.74511 1 3.375V17.625C1 18.2549 1.24502 18.859 1.68115 19.3044C2.11728 19.7498 2.7088 20 3.32558 20H11.4651C12.0819 20 12.6734 19.7498 13.1096 19.3044C13.5457 18.859 13.7907 18.2549 13.7907 17.625V15.25" stroke="#452C88" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M4.72095 10.5H21M21 10.5L17.5116 6.9375M21 10.5L17.5116 14.0625" stroke="#452C88" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <span style="color: #452C88;">Logout</span> -->
                </button>
            </a>
        @endif
    </div>
    <div class="main-panel" id="main-panel" style="overflow-x: auto;">
        <div id="main">
            <nav>
                <div class="row p-2">
                    <div class="col-lg-9 col-6 col-xl-9">
                        <button class="openbtn" id="closebtn" onclick="closeNav()">
                        <i class="fa fa-close"></i></button>
                        <button class="openbtn" id="openbtn" style="display: none;" onclick="openNav()">☰</button>
                    </div>
                    <div class="col-lg-3 col-6 col-xl-3 d-flex justify-content-evenly">
                        <form action="/lang_change" method="post">
                            @csrf
                            <select id="lang-select" class="form-control mx-2" style="width: 90%; height: 80%;"
                                name="lang" onchange="this.form.submit()">
                                <option value="en" {{ session()->get('locale') == 'en' ? 'selected' : '' }}>
                                    @lang('lang.english')</option>
                                <option value="th" {{ session()->get('locale') == 'th' ? 'selected' : '' }}>@lang('lang.thai')
                                </option>
                            </select>
                        </form>
                        <div class="mx-2 my-auto" style="position: relative;">
                            <div
                                style="z-index: 1; position: absolute; display: flex; justify-content: center; bottom: 70%; left: 40%;">
                                <span class="badge badge-danger"
                                    style="width: 20px; height: 20px; border-radius: 50px;">0</span>
                            </div>
                            <div class="dropdown" style="position: initial;">
                                <div id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <img src="{{ asset('assets/images/bell.svg') }}" alt="image">
                                </div>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <h5 class="text-left px-2">Notification</h5>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">
                                        <p>
                                            @lang('lang.no_notification_yet!')
                                        </p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="mx-2 pb-2">
                            <div class="dropdown">
                                <div id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <img src="{{ asset('assets/images/nav-user.svg') }}" alt="image">
                                </div>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <h5 class="text-left px-2">Profile</h5>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">
                                        <span class="mr-2"><i class="fa fa-user-circle " aria-hidden="true"></i>
                                        </span>
                                        {{ session('user_details')['name'] }}</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/settings">
                                        <span class="mr-2"><i class="fa fa-cog" aria-hidden="true"></i>
                                        </span>
                                        @lang('lang.settings')
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
