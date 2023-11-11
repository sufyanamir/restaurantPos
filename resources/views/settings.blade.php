@include('layouts.header')
<style>
    .btn-bg {
        background-image: linear-gradient(#00A3FF, #023D5F);
        border: none;
        border-radius: 6px;
    }

    .btn-bg:active {
        background-image: linear-gradient(#023D5F, #00A3FF) !important;
    }

    img {
        position: relative;
    }

    .edit-button {
        position: absolute;
        bottom: 50px;
        left: 560px;
        right: 450px;
    }

    .dropzone {
        position: relative;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        display: inline-block;
    }

    .file-input-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        cursor: pointer;
        transition: opacity 0.3s ease;
    }

    .file-input-container:hover {
        opacity: 1;
    }

    .upload-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .upload-icon svg {
        width: 30px;
        height: 30px;
        fill: #233A85;
    }
</style>
<!-- partial -->
<div class="content-wrapper py-0 my-2">
    <div style="border: none;">
        <div class="bg-white" style="border-radius: 20px;">
            <div class="p-3">
                <h3 class="page-title">

                    <span>@lang('lang.admin_details')</span>
                </h3>
                <form id="formData" action="{{ url('settings/update/'.$userDetails->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="company_id" value="{{ session('user_details')['company_id'] }}">
                    <div class="row mb-3">
                        <div class="col-lg-12 text-center">
                            <div id="dropzone" class="dropzone">
                                <img id="profileImage" src="{{ (isset($company->company_image)) ? asset($company->company_image) : 'assets/images/nav-user.svg'}}"
                                    style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;"
                                    alt="text">
                                <div class="file-input-container">
                                    <input class="file-input" type="file" name="upload_image" id="fileInput1">
                                    <div class="upload-icon" onclick="document.getElementById('fileInput1').click()">
                                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="18" cy="18" r="18" fill="#233A85" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M16.1634 23.6195L22.3139 15.6658C22.6482 15.2368 22.767 14.741 22.6556 14.236C22.559 13.777 22.2768 13.3406 21.8534 13.0095L20.8208 12.1893C19.922 11.4744 18.8078 11.5497 18.169 12.3699L17.4782 13.2661C17.3891 13.3782 17.4114 13.5438 17.5228 13.6341C17.5228 13.6341 19.2684 15.0337 19.3055 15.0638C19.4244 15.1766 19.5135 15.3271 19.5358 15.5077C19.5729 15.8614 19.3278 16.1925 18.9638 16.2376C18.793 16.2602 18.6296 16.2075 18.5107 16.1097L16.676 14.6499C16.5868 14.5829 16.4531 14.5972 16.3788 14.6875L12.0185 20.3311C11.7363 20.6848 11.6397 21.1438 11.7363 21.5878L12.2934 24.0032C12.3231 24.1312 12.4345 24.2215 12.5682 24.2215L15.0195 24.1914C15.4652 24.1838 15.8812 23.9807 16.1634 23.6195ZM19.5955 22.8673H23.5925C23.9825 22.8673 24.2997 23.1886 24.2997 23.5837C24.2997 23.9795 23.9825 24.3 23.5925 24.3H19.5955C19.2055 24.3 18.8883 23.9795 18.8883 23.5837C18.8883 23.1886 19.2055 22.8673 19.5955 22.8673Z"
                                                fill="white" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <p class="error-image text-danger d-none">* The user pic should be less than or equal to
                                1024KB.</P>

                            <p></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 mt-3" style="width: 100%;">
                            <x-input :name="'user_name'" :value="$userDetails->name" :label="'lang.enter_your_name'" :inputType="'text'"
                                :id="''"></x-input>
                        </div>
                        <div class="col-lg-6 col-sm-12 mt-3">
                            <x-input :name="'user_phone'" :value="$userDetails->phone" :label="'lang.phone_number'" :inputType="'tel'"
                                :id="''"></x-input>
                        </div>
                        <div class="col-lg-4 col-sm-6 mt-3">
                            <x-input :name="'user_address'" :value="$userDetails->address" :label="'lang.address'" :inputType="'text'"
                                :id="''"></x-input>
                        </div>
                        <div class="col-lg-4 col-sm-6 mt-3">
                            <div class="input-group">
                                <input id="password"  type="password" name="user_password" value="" autocomplete="off" class="input">
                                <label class="user-label">@lang('lang.new_password')</label>
                            </div>
                            {{-- <x-input :name="'user_password'" :value="''" :label="'New Password'" :inputType="'password'"
                                :id="'password'"></x-input> --}}
                        </div>
                        <div class="col-lg-4 col-sm-6 mt-3">
                            <div class="input-group">
                                <input id="confirm_password" type="password" name="confirm_user_password" value="" autocomplete="off" class="input">
                                <label class="user-label">@lang('lang.confirm_password')</label>
                            </div>
                            {{-- <x-input :name="'confrim_user_password'" :value="''" :label="'Confirm Password'" :inputType="'password'"
                                :id="'confirm_password'"></x-input> --}}
                            <small id="passwordError" style="color: red;"></small> <!-- Error message element -->
                            <small id="passwordSuccess" style="color: green;"></small> <!-- Success message element -->
                        </div>


                    </div>
                    <div class="row">
                        @if (session('user_details')['role'] == '1')
                            <div class="col-12 mb-2 mt-4">
                                <h5 class="text-left">@lang('lang.company_details')</h5>
                            </div>
                            <div class="col-lg-4 col-sm-12 mt-3" style="width: 100%;">
                                <x-input :name="'company_name'" :value="$company->company_name" :label="'lang.company_name'" :inputType="'text'"
                                    :id="''"></x-input>
                            </div>
                            <div class="col-lg-4 col-sm-12 mt-3">
                                <x-input :name="'company_phone'" :value="$company->company_phone" :label="'lang.phone_number'" :inputType="'tel'"
                                    :id="''"></x-input>
                            </div>
                            <div class="col-lg-4 col-sm-6 mt-3">
                                <x-input :name="'company_address'" :value="$company->company_address" :label="'lang.address'" :inputType="'text'"
                                    :id="''"></x-input>
                            </div>
                        @endif
                        <div class=" col-lg-12 mt-3 my-4 text-right">
                            <button class="float-end btn btn-md btn-bg px-5 text-white" type="submit" name="update"
                                onclick="validateForm(event)">
                                <div class="spinner-border spinner-border-sm text-white d-none" id="spinner"></div>
                                <span id="add_btn">@lang('lang.update')</span>
                            </button>
                        </div>
                    </div>

                </form>
                {{-- form --}}
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
    <script>
        // Get references to the necessary elements
        const fileInput = document.getElementById('fileInput1');
        const profileImage = document.getElementById('profileImage');
        const form = document.getElementById('myForm');

        // Handle file input change
        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            const reader = new FileReader();
            if (!$('.error-image').hasClass('d-none')) {
                $('.error-image').addClass('d-none');
            }

            // Check the file size and type of file
            if (file.type.startsWith('image/')) {
                if (file.size <= 1048576) {
                    reader.readAsDataURL(file);

                    reader.onload = function(e) {
                        profileImage.src = e.target.result;
                        form.action = "";
                    };
                } else {
                    $('.error-image').removeClass('d-none').text(
                        'The user pic should be less than or equal to 1024KB');
                    console.log("Image size exceeds the limit of 1 MB.");
                    fileInput.value = "";
                }
            } else {
                $('.error-image').removeClass('d-none').text('Please select an image file.');
                console.log("Please select an image file.");
                fileInput.value = "";
            }


        });


        // Function to validate password and confirm_password fields
        function validatePassword() {
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('confirm_password');
            const passwordError = document.getElementById('passwordError');
            const passwordSuccess = document.getElementById('passwordSuccess');

            const passwordValue = passwordInput.value;
            const confirmValue = confirmInput.value;

            if (passwordValue === confirmValue) {
                // Passwords match, clear error message and display success message
                passwordError.textContent = '';
                passwordSuccess.textContent = 'Passwords match';
            } else {
                // Passwords do not match, display error message and clear success message
                passwordError.textContent = 'Passwords do not match';
                passwordSuccess.textContent = '';
            }
        }

        // Function to validate the form before submitting
        function validateForm(event) {
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('confirm_password');

            const passwordValue = passwordInput.value;
            const confirmValue = confirmInput.value;

            if (passwordValue !== confirmValue) {
                // Passwords do not match, prevent form submission
                event.preventDefault();
                swal({
                    title: "Password",
                    text: "do not match!",
                    icon: "warning"
                });
            }
        }

        // Add event listeners for input change
        document.getElementById('password').addEventListener('input', validatePassword);
        document.getElementById('confirm_password').addEventListener('input', validatePassword);
    </script>


    @include('layouts.footer')
