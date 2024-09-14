<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Restaurant POS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/restaurant-logo.png') }}">
    <!-- <script src="https://kit.fontawesome.com/c35c4a5799.js" crossorigin="anonymous"></script> -->
    <!-- <link rel="stylesheet" href="assets/font-web/css/all.css" /> -->
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
    <style>
        /*
        *
        * ==========================================
        * CUSTOM UTIL CLASSES
        * ==========================================
        *
        */
        .navbar-brand {
            font-family: "Poppins", sans-serif;
            font-style: normal !important;
            font-weight: 700 !important;
            font-size: 45px !important;
            line-height: 68px !important;
            color: #3a0ca3 !important;
        }

        .border-md {
            border-width: 2px;
        }

        .sign_up {
            box-shadow: 0px 4px 26px rgba(0, 0, 0, 0.25) !important;
            border-radius: 32px !important;
        }

        /*
        *
        * ==========================================
        * FOR DEMO PURPOSES
        * ==========================================
        *
        */
        input,
        .input-group-text {
            /*border-top: 0px !important;*/
            /*border-left: 0px !important;*/
            /*border-right: 0px !important;*/
            outline: 0px !important;
        }

        .form-control:not(select) {
            padding: 1.2rem 0.5rem;
        }

        select.form-control {
            height: 42px;
            padding-left: 0.5rem;
        }

        option,
        .form-control::placeholder {
            color: #000842;
            font-weight: 400;
            font-size: 1rem;
        }

        option {
            color: #000000;
            font-weight: bold;
            font-size: 1rem;
        }

        .form-control:focus {
            box-shadow: none;
        }

        .lbl-text {
            color: #999999;
            font-family: Poppins;
            font-size: 13px;
            font-weight: 500;
            line-height: 20px;
            letter-spacing: 0em;
            text-align: left;
        }

        @media (max-width: 767px) {
           
               
            form {
                display: contents;
                text-align: center;

            }

            .row {
                margin: 0px 5px 0px 5px;
            }

        }
    </style>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">  -->
</head>

<body style="overflow-x: hidden;">
    <div class="container-fluid mx-2" style="margin-top: 20px  !important;">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-6 ml-auto " style="height: 90vh !important; width: 100%;">
                <div class="text-center text-md-left">
                    <!-- Navbar Brand -->
                    <a href="#" class="navbar-brand px-auto">
                        <img src="{{ asset('assets/images/restaurant-logo.png') }}" alt="Image">
                    </a>
                </div>
                <div class="row mt-4 ">
                    <form action="/" id="login-form" method="post" style="width: 500px !important;">
                        @csrf
                        <div class="row">
                            <div class="w-100" style="padding-left: 15px;">
                                <p
                                    style="font-style: normal; font-weight: 500; font-size: 30px; line-height: 45px; color: #000000 !important;">
                                    @lang('lang.login')
                                </p>
                            </div>
                            <!-- Email Address -->
                            <!-- <div class="w-100" style="padding-left: 15px;">
                                <p
                                    style="font-style: normal; font-weight: 400;font-size: 16px;line-height: 24px;color: #000000;margin-bottom: 0px !important;">
                                    @lang('lang.If_you_dont_have_an_account')
                                </p>
                                <p>
                                    @lang('lang.you_can')
                                    <a href="/register" style="color: #f37e17">@lang('lang.register_here')</a>
                                </p>
                            </div> -->
                            <label class="ml-3 mb-0 lbl-text" for="">@lang('lang.email')</label>
                            <div class="input-group col-lg-12 mb-4">

                                <div class="input-group-prepend">

                                    <span
                                        class="input-group-text bg-white px-4 border-right-0 border-left-0 border-top-0 border-dark"
                                        style="border-radius: 0px !important; border-bottom: 2px solid #f37e17 !important">
                                        <svg width="17" height="12" viewBox="0 0 17 12" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M1.49414 0H15.5059C16.3297 0 17 0.670272 17 1.49414V10.1529C17 10.9768 16.3297 11.6471 15.5059 11.6471H1.49414C0.670271 11.6471 0 10.9768 0 10.1529V1.49414C0 0.670272 0.670271 0 1.49414 0ZM1.68914 0.996094L1.88856 1.16214L7.90719 6.17386C8.25071 6.45987 8.74936 6.45987 9.09281 6.17386L15.1114 1.16214L15.3109 0.996094H1.68914ZM16.0039 1.71521L11.1001 5.79863L16.0039 9.06226V1.71521ZM1.49414 10.651H15.5059C15.7465 10.651 15.9478 10.4794 15.9939 10.2522L10.3014 6.46365L9.73018 6.93932C9.37377 7.23609 8.93685 7.38447 8.49997 7.38447C8.06308 7.38447 7.62619 7.23609 7.26976 6.93932L6.69853 6.46365L1.00605 10.2521C1.05221 10.4794 1.25348 10.651 1.49414 10.651ZM0.996094 9.06226L5.89993 5.79866L0.996094 1.71521V9.06226Z"
                                                fill="#000842" />
                                        </svg>
                                    </span>
                                </div>

                                <input id="email" type="email" name="email" placeholder="@lang('lang.enter_your_email_address')"
                                    class="border-top-0 border-right-0 border-dark form-control bg-white border-left-0"
                                    style="border-radius: 0px !important; border-bottom: 2px solid #f37e17 !important" />
                                <div class="col-lg-12">
                                    <div class="validation-error-email"></div>
                                </div>
                            </div>
                            <!-- password -->
                            <label class="ml-3 mb-0 lbl-text" for="">@lang('lang.password')</label>
                            <div class="input-group col-lg-12 mb-2">
                                <div class="input-group-prepend">
                                    <span
                                        class="input-group-text bg-white px-4 border-right-0 border-left-0 border-top-0 border-dark"
                                        style="border-radius: 0px !important; border-bottom: 2px solid #f37e17 !important">
                                        <svg width="13" height="17" viewBox="0 0 13 17" fill="none"
                                            xmlns="http://www.w3.org/2000/sv">
                                            <path
                                                d="M10.6318 7.2296V4.53742C10.639 3.31927 10.1524 2.14798 9.28387 1.29383C8.44414 0.457706 7.34492 0 6.18084 0C6.16282 0 6.14119 0 6.12317 0C3.64003 0.00360399 1.62179 2.03625 1.62179 4.53742V7.2296C0.684757 7.34132 0 8.12699 0 9.07844V15.1259C0 16.1531 0.821709 17 1.84884 17H10.4083C11.4354 17 12.2572 16.1531 12.2572 15.1259V9.07844C12.2535 8.13059 11.5688 7.34132 10.6318 7.2296ZM2.33899 4.53742H2.34259C2.34259 2.43269 4.04007 0.709985 6.12678 0.709985H6.13038C7.12148 0.706381 8.07293 1.09922 8.7757 1.79839C9.50731 2.52279 9.91457 3.51028 9.90736 4.53742V7.2332H9.11448V4.53742C9.12169 3.71931 8.79733 2.93364 8.21709 2.35701C7.66928 1.8092 6.92686 1.49926 6.152 1.49926H6.13038C4.47255 1.49926 3.13186 2.86156 3.13186 4.53381V7.2332H2.33899V4.53742ZM8.39729 4.53742V7.2332H3.85626V4.53742C3.85626 3.26161 4.87259 2.22366 6.13398 2.22366H6.15561C6.73945 2.22366 7.30167 2.45792 7.71613 2.87238C8.15582 3.31206 8.40449 3.91393 8.39729 4.53742ZM11.5688 15.1367C11.5688 15.7674 11.057 16.2792 10.4263 16.2792H1.86326C1.23256 16.2792 0.720797 15.7674 0.720797 15.1367V9.09646C0.720797 8.46576 1.23256 7.954 1.86326 7.954H10.4263C11.057 7.954 11.5688 8.46576 11.5688 9.09646V15.1367Z"
                                                fill="#000842" />
                                        </svg>
                                    </span>
                                </div>
                                <input id="password" type="password" name="password" placeholder="@lang('lang.enter_your_password')"
                                    class="border-top-0 border-right-0 form-control bg-white border-left-0 border-dark"
                                    style="border-radius: 0px !important; border-bottom: 2px solid #f37e17 !important" />
                                <div class="input-group-append">
                                    <span
                                        class="input-group-text bg-white border-top-0 border-right-0 border-left-0 border-dark"
                                        style="border-radius: 0px !important; border-bottom: 2px solid #f37e17 !important">
                                        <button style="border: none; background: none; cursor: pointer;" type="button"
                                            id="eye">
                                            <i class="fa fa-eye text-muted"></i>
                                        </button>
                                    </span>
                                </div>
                                <div class="col-lg-12">
                                    <div class="validation-error-password"></div>
                                </div>
                            </div>
                            <!-- Submit Button -->
                            <div class="form-group col-lg-12 mx-auto mt-5 ">
                                <button type="submit" id="btn_user_login"
                                    class="font-weight-bold sign_up btn btn-block py-2 text-white"
                                    style=" border:none; background-color: #f37e17; "
                                    name="submit">
                                    <div class="spinner-border spinner-border-sm text-white d-none" id="spinner">
                                    </div>
                                    <span id="text">@lang('lang.signin')</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- For Demo Purpose -->
            <div class="col-md-5 col-lg-5 pr-lg-5 d-lg-block d-md-block d-none mb-5 mb-md-0">
                <div class="p-2 d-flex align-items-center justify-content-center"
                    style=" height: 90vh !important; border-radius: 15px;">
                    <img src="{{ asset('assets/images/restaurant.png') }}" style="width: 100%; height: 100%; border-radius: 25px 0px 25px 0px;" alt="Image">
                </div>
            </div>
            <!-- Registeration Form -->
        </div>
    </div>
    <script src="{{ asset('assets/bootstrap/jquery.slim.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $("#login-form").submit(function(event) {
                // Prevent the default form submission
                event.preventDefault();

                // Serialize the form data into a JSON object
                var formData = $(this).serialize();

                // Send the AJAX request
                $.ajax({
                    type: "POST", // Use POST method
                    url: "/", // Replace with the actual URL to your login endpoint
                    data: formData, // Send the form data
                    dataType: "json", // Expect JSON response
                    beforeSend: function() {
                        $('#spinner').removeClass('d-none');
                        $('#text').addClass('d-none');
                    },
                    success: function(response) {
                        // Handle the success response here
                        if (response.success) {
                            // Redirect to the dashboard or do something else
                            $('#text').removeClass('d-none');
                            $('#spinner').addClass('d-none');
                            window.location.href = "/dashboard";
                        } else {
                            // Handle login failure, show an error message, etc.
                            alert(response.error);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // Handle the error here
                        Swal.fire(
                            'Warning!',
                            'Invalid Credentials!',
                            'warning'
                        )
                        $('#text').removeClass('d-none');
                        $('#spinner').addClass('d-none');
                        console.error("AJAX Error: " + textStatus, errorThrown);
                    }
                });
            });
        });
    </script>
    <script>
        // For Demo Purpose [Changing input group text on focus]
        $(document).ready(function() {
            $("#eye").on("click", function() {
                var passwordField = $("#password");
                var passwordFieldType = passwordField.attr("type");
                if (passwordFieldType == "password") {
                    passwordField.attr("type", "text");
                    $(this).find("i").removeClass("fa-eye").addClass("fa-eye-slash");
                } else {
                    passwordField.attr("type", "password");
                    $(this).find("i").removeClass("fa-eye-slash").addClass("fa-eye");
                }
            });




        });
    </script>

</html>
