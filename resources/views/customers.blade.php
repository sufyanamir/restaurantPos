@include('layouts.header')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-sm-12 col-lg-6 col-xl-3 mb-2">
            <x-dashboard-card :value="$customersCount" :label="'lang.total_customers'" :svg="asset('assets/images/d-user-icon.svg')"></x-dashboard-card>
        </div>
        <div class="col-12 col-sm-12 col-lg-6 col-xl-3 mb-2">
            <x-dashboard-card :value="'123'" :label="'lang.un_assigned'" :svg="asset('assets/images/d-dollar.svg')"></x-dashboard-card>
        </div>
        <div class="col-12 col-sm-12 col-lg-6 col-xl-3 mb-2">
            <x-dashboard-card :value="$activeCount" :label="'lang.active'" :svg="asset('assets/images/d-orders.svg')"></x-dashboard-card>
        </div>
        <div class="col-12 col-sm-12 col-lg-6 col-xl-3 mb-2">
            <x-dashboard-card :value="$pendingCount" :label="'lang.pending'" :svg="asset('assets/images/d-user-icon.svg')"></x-dashboard-card>
        </div>
    </div>
    <div class="px-0  box-shadow mt-4 ">
        <div class="row">
            <div class="col-12 col-xl-10">
                <div class="mx-3 my-3">
                    <h4>@lang('lang.customer_list')</h4>
                </div>
            </div>
            <div class="col-12 col-xl-2 my-3 text-right pr-5">
                <x-add-button :value="'lang.+add_customer'" :dataTarget="'#add-customer'"></x-add-button>
                <x-modal :modalId="'add-customer'" :formAction="'customers/store'" :editData="''"></x-modal>
            </div>
        </div>
        <div class="table-responsive">
            <table class="display" id="myTable">
                <thead class="thead-color ">
                    <tr>
                        <th>@lang('lang.photo')</th>
                        <th>@lang('lang.company_name')</th>
                        <th>@lang('lang.email')</th>
                        <th>@lang('lang.phone_number')</th>
                        <th>@lang('lang.address')</th>
                        <th>@lang('lang.social_links')</th>
                        <th>@lang('lang.status')</th>
                        <th>@lang('lang.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td>
                                <img class="img-size" src="{{ (isset($customer->customer_image)) ? asset($customer->customer_image) : 'assets/images/nav-user.svg'}}" alt="image">
                            </td>
                            <td>{{ $customer->customer_name }}</td>
                            <td>{{ $customer->customer_email }}</td>
                            <td>{{ $customer->customer_phone }}</td>
                            <td>{{ $customer->customer_address }}</td>
                            <td>
                                <?php
                                $customerLinks = $customer->customer_social_links; // Replace this with your actual string
                                
                                $linkArray = explode(',', $customerLinks);
                                
                                // Define an array of social media icons and their respective links
                                $icons = [
                                    $linkArray[0] => 'assets/images/fb-logo.svg',
                                    $linkArray[1] => 'assets/images/ig-logo.svg',
                                    $linkArray[2] => 'assets/images/tt-logo.svg',
                                ];
                                
                                // Loop through the links and display the icons and links
                                foreach ($linkArray as $index => $link) {
                                    $iconSrc = isset($icons[$link]) ? $icons[$link] : 'default-icon.png'; // Use a default icon if not found
                                    $iconAlt = ucfirst(str_replace('.com', '', $link)); // Generate alt text
                                
                                    echo "<a href='$link' target='_blank'><img style='width: 33%' src='$iconSrc' alt='$iconAlt' /></a>";
                                }
                                ?>
                            </td>
                            @if ($customer->customer_status == '0')
                                <td>
                                    <div
                                        style="width: 100%; height: 100%; padding-top: 5px; padding-bottom: 5px; padding-left: 19px; padding-right: 20px; background: rgba(48.62, 165.75, 19.34, 0.18); border-radius: 3px; justify-content: center; align-items: center; display: inline-flex">
                                        <div
                                            style="color: #31A613; font-size: 14px; font-weight: 500; word-wrap: break-word">
                                            @lang('lang.new')</div>
                                    </div>
                                </td>
                            @elseif($customer->customer_status == '1')
                                <td>
                                    <div
                                        style="width: 100%; height: 100%; padding-top: 5px; padding-bottom: 5px; padding-left: 19px; padding-right: 20px; background: rgba(48.62, 165.75, 19.34, 0.18); border-radius: 3px; justify-content: center; align-items: center; display: inline-flex">
                                        <div
                                            style="color: #31A613; font-size: 14px; font-weight: 500; word-wrap: break-word">
                                            @lang('lang.active')</div>
                                    </div>
                                </td>
                            @elseif($customer->customer_status == '2')
                                <td>
                                    <div
                                        style="width: 100%; height: 100%; padding-top: 6px; padding-bottom: 7px; padding-left: 14px; padding-right: 12px;    background: rgba(245, 34, 45, 0.19); border-radius: 3px; justify-content: center; align-items: center; display: inline-flex">
                                        <div
                                            style="text-align: center; color: #F5222D; font-size: 14px; font-weight: 500; word-wrap: break-word">
                                            @lang('lang.pending')</div>
                                    </div>
                                </td>
                            @else
                                <td>
                                    <div
                                        style="width: 100%; height: 100%; padding-top: 6px; padding-bottom: 4px; padding-left: 15px; padding-right: 13px; background: rgba(77, 77, 77, 0.12); border-radius: 3px; justify-content: center; align-items: center; display: inline-flex">
                                        <div
                                            style="text-align: center; color: #8F9090; font-size: 14px; font-weight: 500; word-wrap: break-word">
                                            @lang('lang.pending')</div>
                                    </div>
                                </td>
                            @endif
                            <td>
                                <button class="btn p-0" data-target="#editcustomer{{ $customer->customer_id }}"
                                    data-toggle="modal" style="background: none;">
                                    <svg width="36" height="36" viewBox="0 0 36 36" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <circle opacity="0.1" cx="18" cy="18" r="18"
                                            fill="#233A85" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M16.1637 23.6195L22.3141 15.6658C22.6484 15.2368 22.7673 14.741 22.6558 14.236C22.5593 13.777 22.277 13.3406 21.8536 13.0095L20.8211 12.1893C19.9223 11.4744 18.8081 11.5497 18.1693 12.3699L17.4784 13.2661C17.3893 13.3782 17.4116 13.5438 17.523 13.6341C17.523 13.6341 19.2686 15.0337 19.3058 15.0638C19.4246 15.1766 19.5137 15.3271 19.536 15.5077C19.5732 15.8614 19.328 16.1925 18.9641 16.2376C18.7932 16.2602 18.6298 16.2075 18.511 16.1097L16.6762 14.6499C16.5871 14.5829 16.4534 14.5972 16.3791 14.6875L12.0188 20.3311C11.7365 20.6848 11.64 21.1438 11.7365 21.5878L12.2936 24.0032C12.3233 24.1312 12.4348 24.2215 12.5685 24.2215L15.0197 24.1914C15.4654 24.1838 15.8814 23.9807 16.1637 23.6195ZM19.5957 22.8673H23.5928C23.9828 22.8673 24.2999 23.1886 24.2999 23.5837C24.2999 23.9795 23.9828 24.3 23.5928 24.3H19.5957C19.2058 24.3 18.8886 23.9795 18.8886 23.5837C18.8886 23.1886 19.2058 22.8673 19.5957 22.8673Z"
                                            fill="#233A85" />
                                    </svg>
                                </button>
                                <a href="{{ route('customer.delete', ['id' => $customer->customer_id]) }}"
                                    class="confirm-button">
                                    <button class="btn p-0" style="background: none;">
                                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <circle opacity="0.1" cx="18" cy="18" r="18"
                                                fill="#DF6F79" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M23.4905 13.743C23.7356 13.743 23.9396 13.9465 23.9396 14.2054V14.4448C23.9396 14.6975 23.7356 14.9072 23.4905 14.9072H13.0493C12.8036 14.9072 12.5996 14.6975 12.5996 14.4448V14.2054C12.5996 13.9465 12.8036 13.743 13.0493 13.743H14.8862C15.2594 13.743 15.5841 13.4778 15.6681 13.1036L15.7642 12.6739C15.9137 12.0887 16.4058 11.7 16.9688 11.7H19.5704C20.1273 11.7 20.6249 12.0887 20.7688 12.6431L20.8718 13.1029C20.9551 13.4778 21.2798 13.743 21.6536 13.743H23.4905ZM22.5573 22.4943C22.7491 20.707 23.0849 16.4609 23.0849 16.418C23.0971 16.2883 23.0548 16.1654 22.9709 16.0665C22.8808 15.9739 22.7669 15.9191 22.6412 15.9191H13.9028C13.7766 15.9191 13.6565 15.9739 13.5732 16.0665C13.4886 16.1654 13.447 16.2883 13.4531 16.418C13.4542 16.4259 13.4663 16.5755 13.4864 16.8255C13.5759 17.9364 13.8251 21.0303 13.9861 22.4943C14.1001 23.5729 14.8078 24.2507 15.8328 24.2753C16.6238 24.2936 17.4387 24.2999 18.272 24.2999C19.0569 24.2999 19.854 24.2936 20.6696 24.2753C21.7302 24.257 22.4372 23.5911 22.5573 22.4943Z"
                                                fill="#D11A2A" />
                                        </svg>
                                    </button>
                                </a>
                            </td>
                            <div class="modal fade" id="editcustomer{{ $customer->customer_id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content" style="border-radius: 15px;">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="">@lang('lang.edit_customer')</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close" id="closeModal{{ $customer->customer_id }}">
                                                <span aria-hidden="true">
                                                    <svg width="14" height="14" viewBox="0 0 14 14"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M13 1L1 13M1 1L13 13" stroke="#667085" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ url('customer/update/' . $customer->customer_id) }}"
                                                method="POST" id="" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="company_id"
                                                    value="{{ session('user_details')['company_id'] }}">
                                                <input type="hidden" name="added_user_id"
                                                    value="{{ session('user_details')['user_id'] }}">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <div class="row">
                                                            <div class="col-6 mb-3">
                                                                <x-input :name="'name'" :value="$customer->customer_name"
                                                                    :label="'lang.name'" :inputType="'text'"
                                                                    :id="''"></x-input>
                                                            </div>
                                                            <div class="col-6 mb-3">
                                                                <x-input :name="'email'" :value="$customer->customer_email"
                                                                    :label="'lang.email'" :inputType="'email'"
                                                                    :id="''"></x-input>
                                                            </div>
                                                            <div class="col-12 mb-3">
                                                                <x-input :name="'phone'" :value="$customer->customer_phone"
                                                                    :label="'lang.phone_number'" :inputType="'tel'"
                                                                    :id="''"></x-input>
                                                            </div>
                                                            <div class="col-12 mb-3">
                                                                <x-text-area :name="'address'" :value="$customer->customer_address"
                                                                    :label="'lang.address'"></x-text-area>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        {{-- @if ($customer->user_image) --}}
                                                        <div id="dropzone" class="dropzone">
                                                            <img id="profileImage{{ $customer->customer_id }}"
                                                                src="{{ $customer->customer_image ? asset($customer->customer_image) : 'assets/images/rectangle-image.svg' }}"
                                                                style="width: 100%; height: 237px; object-fit: fill;"
                                                                alt="text">
                                                            <div class="file-input-container">
                                                                <input value="" class="file-input"
                                                                    type="file" name="upload_image"
                                                                    id="fileInput{{ $customer->customer_id }}">
                                                                <div class="upload-icon"
                                                                    onclick="document.getElementById('fileInput{{ $customer->customer_id }}').click()">
                                                                    <img src="{{ asset('assets/images/upload-image.svg') }}"
                                                                        alt="Image">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p class="error-image text-danger d-none"
                                                            style="font-size: smaller;"></P>
                                                        {{-- <img src="{{asset($customer->customer_image)}}" alt="image">
                                                    @endif --}}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <h5 class="text-left" id="">@lang('lang.social_links')</h5>
                                                    </div>
                                                    <div class="col-8 mb-3">
                                                        @php
                                                            $socialLinks = $customer->customer_social_links; // Replace $user with your actual user or model variable
                                                            $socialLinksArray = explode(',', $socialLinks);
                                                            
                                                            // Now $socialLinksArray contains individual values
                                                            $fbAcc = $socialLinksArray[0];
                                                            $igAcc = $socialLinksArray[1];
                                                            $ttAcc = $socialLinksArray[2];
                                                        @endphp
                                                        <x-social-input :socialLogo="'assets/images/fb-logo.svg'" :value="$fbAcc"
                                                            :name="'fb_acc'" :qrId="''"
                                                            :qrName="''"></x-social-input>
                                                        <x-social-input :socialLogo="'assets/images/ig-logo.svg'" :value="$igAcc"
                                                            :name="'ig_acc'" :qrId="''"
                                                            :qrName="''"></x-social-input>
                                                        <x-social-input :socialLogo="'assets/images/tt-logo.svg'" :value="$ttAcc"
                                                            :name="'tt_acc'" :qrId="''"
                                                            :qrName="''"></x-social-input>
                                                    </div>
                                                    <div class="my-auto text-right">
                                                        <x-submit-button :name="'submit'"
                                                            :value="'lang.update'"></x-submit-button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </tr>
                        <script>
                            // Get references to the necessary elements
                            const fileInput {{$customer->customer_id}} = document.getElementById('fileInput{{ $customer->customer_id }}');
                            const profileImage {{$customer->customer_id}} = document.getElementById('profileImage{{ $customer->customer_id }}');
                            const form {{$customer->customer_id}} = document.getElementById('myForm{{ $customer->customer_id }}');


                            // Handle file input change
                            fileInput {{$customer->customer_id}}.addEventListener('change', function(event) {
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
                                            profileImage {{$customer->customer_id}}.src = e.target.result;
                                            form.action = "";
                                        };
                                    } else {
                                        $('.error-image').removeClass('d-none').text(
                                            'The user pic should be less than or equal to 1024KB');
                                        console.log("Image size exceeds the limit of 1 MB.");
                                        fileInput {{$customer->customer_id}}.value = "";
                                    }
                                } else {
                                    $('.error-image').removeClass('d-none').text('Please select an image file.');
                                    console.log("Please select an image file.");
                                    fileInput {{$customer->customer_id}}.value = "";
                                }


                            });
                        </script>
                    @endforeach
                </tbody>
            </table>
            <br />
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/jquery.js') }}"></script>
<script type="text/javascript">
    $('.confirm-button').click(function(event) {
        event.preventDefault();
        var deleteLink = $(this).attr('href');

        swal({
                title: `Are you sure you want to delete this row?`,
                text: "It will be gone forever",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    window.location.href = deleteLink; // Redirect to the delete action
                }
            });
    });
</script>

@include('layouts.footer')
