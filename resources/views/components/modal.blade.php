<!-- Modal -->
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="border-radius: 15px;">
      <div class="modal-header">
        @if($modalId == 'add-staff')
        <h5 class="modal-title" id="">@lang('lang.add_company')</h5>
        @elseif($modalId == 'add-service')
        <h5 class="modal-title" id="">@lang('lang.add_service')</h5>
        @elseif($modalId == 'add-customer')
        <h5 class="modal-title" id="">@lang('lang.add_customer')</h5>
        @else
        <h5 class="modal-title" id="">@lang('lang.add_company')</h5>
        @endif
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M13 1L1 13M1 1L13 13" stroke="#667085" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ $formAction }}" method="post" id="{{ $modalId }}" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="company_id" value="{{ session('user_details')['company_id'] }}">
          <input type="hidden" name="added_user_id" value="{{ session('user_details')['user_id'] }}">
          <div class="row">
            <div class="col-8">
              <div class="row">
                <div class="col-6 mb-3">
                  <x-input :name="'name'" :value="''" :label="'lang.name'" :inputType="'text'" :id="''"></x-input>
                </div>
                <div class="col-6 mb-3">
                  @if($modalId == 'add-service')
                  <x-input :name="'subtitle'" :value="''" :label="'lang.subtitle'" :inputType="'text'" :id="''"></x-input>
                  @else
                  <x-input :name="'email'" :value="''" :label="'lang.email'" :inputType="'email'" :id="''"></x-input>
                  @endif
                </div>
                @if($modalId == 'add-staff')
                <div class="col-6 mb-3">
                  <x-input :name="'phone'" :value="''" :label="'lang.phone_number'" :inputType="'tel'" :id="''"></x-input>
                </div>
                <div class="col-6 mb-3">
                  <select name="category" id="" class="form-control" style="height: 51px; border-radius: 0.5rem;">
                    <option value="not-select">@lang('lang.select_user_role')</option>
                    <option value="Manager">@lang('lang.manager')</option>
                    <option value="Worker">@lang('lang.worker')</option>
                    <option value="Trainer">@lang('lang.trainer')</option>
                  </select>
                </div>
                @elseif($modalId == 'add-service')
                <div class="col-6 mb-3">
                  <x-input :name="'charges'" :value="''" :label="'lang.charges'" :inputType="'number'" :id="''"></x-input>
                </div>
                <div class="col-6 mb-3">
                  <x-input :name="'service_duration'" :value="''" :label="'lang.duration'" :inputType="'number'" :id="''"></x-input>
                </div>
                <div class="col-12 mb-3">
                  <x-text-area :name="'description'" :value="''" :label="'lang.description'"></x-text-area>
                </div>
                @else
                <div class="col-12 mb-3">
                  <x-input :name="'phone'" :value="''" :label="'lang.phone_number'" :inputType="'tel'" :id="''"></x-input>
                </div>
                @endif
                @if($modalId != 'add-service')
                <div class="col-12 mb-3">
                  <x-text-area :name="'address'" :value="''" :label="'lang.address'"></x-text-area>
                </div>
                @endif
              </div>
            </div>
            <div class="col-4">
              <x-drop-zone :value="''" :name="'upload_image'"></x-drop-zone>
            </div>
          </div>
          <div class="row">
            <div class="col-12 mb-3">
              @if($modalId == 'add-staff' || $modalId == 'add-customer')
              <h5 class="text-left" id="">@lang('lang.social_links')</h5>
              @elseif($modalId == 'add-service')
              <h5 class="text-left" id="">@lang('lang.overhead_and_costs')</h5>
              @else
              <h5 class="text-left" id="">@lang('lang.add_admin_details')</h5>
              @endif
            </div>
            @if($modalId == 'add-staff' || $modalId == 'add-customer')
            <div class="col-8 mb-3">
              <x-social-input :socialLogo="'assets/images/fb-logo.svg'" :value="''" :name="'fb_acc'" :qrId="''" :qrName="''"></x-social-input>
              <x-social-input :socialLogo="'assets/images/ig-logo.svg'" :value="''" :name="'ig_acc'" :qrId="''" :qrName="''"></x-social-input>
              <x-social-input :socialLogo="'assets/images/tt-logo.svg'" :value="''" :name="'tt_acc'" :qrId="''" :qrName="''"></x-social-input>
            </div>
            @elseif($modalId == 'add-service')
            <div class="row ml-1 col-12" style="position: relative;" id="readroot">
              <div class="col-5 mb-3">
                <x-input :name="'cost_name[]'" :value="''" :label="'lang.name'" :inputType="'text'" :id="'costName'"></x-input>
              </div>
              <div class="col-5 mb-3">
                <x-input :name="'cost[]'" :value="''" :label="'lang.cost'" :inputType="'number'" :id="'cost'"></x-input>
              </div>
              <div class="col-2 my-2 text-left">
                <x-plus-button :name="'add_row'" :addRow="'moreFields'" :class="'moreFields'" :label="'+'" :onclick="''"></x-plus-button>
              </div>
              <button type="button" style="position: absolute; margin-left: 700px; margin-top: 8px; background: none !important;" class="btn p-0" onclick="this.parentNode.parentNode.removeChild(this.parentNode);">
                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <circle opacity="0.1" cx="18" cy="18" r="18" fill="#DF6F79" />
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M23.4905 13.743C23.7356 13.743 23.9396 13.9465 23.9396 14.2054V14.4448C23.9396 14.6975 23.7356 14.9072 23.4905 14.9072H13.0493C12.8036 14.9072 12.5996 14.6975 12.5996 14.4448V14.2054C12.5996 13.9465 12.8036 13.743 13.0493 13.743H14.8862C15.2594 13.743 15.5841 13.4778 15.6681 13.1036L15.7642 12.6739C15.9137 12.0887 16.4058 11.7 16.9688 11.7H19.5704C20.1273 11.7 20.6249 12.0887 20.7688 12.6431L20.8718 13.1029C20.9551 13.4778 21.2798 13.743 21.6536 13.743H23.4905ZM22.5573 22.4943C22.7491 20.707 23.0849 16.4609 23.0849 16.418C23.0971 16.2883 23.0548 16.1654 22.9709 16.0665C22.8808 15.9739 22.7669 15.9191 22.6412 15.9191H13.9028C13.7766 15.9191 13.6565 15.9739 13.5732 16.0665C13.4886 16.1654 13.447 16.2883 13.4531 16.418C13.4542 16.4259 13.4663 16.5755 13.4864 16.8255C13.5759 17.9364 13.8251 21.0303 13.9861 22.4943C14.1001 23.5729 14.8078 24.2507 15.8328 24.2753C16.6238 24.2936 17.4387 24.2999 18.272 24.2999C19.0569 24.2999 19.854 24.2936 20.6696 24.2753C21.7302 24.257 22.4372 23.5911 22.5573 22.4943Z" fill="#D11A2A" />
                </svg>
              </button>
            </div>
            <div class="row" id="writeroot"></div>
            @else
            <div class="col-6 mb-3">
              <x-input :name="'admin_name'" :value="''" :label="'lang.admin_name'" :inputType="'text'" :id="''"></x-input>
            </div>
            <div class="col-6 mb-3">
              <x-input :name="'admin_email'" :value="''" :label="'lang.email'" :inputType="'email'" :id="''"></x-input>
            </div>
            <div class="col-6 mb-3">
              <x-input :name="'admin_phone'" :value="''" :label="'lang.phone_number'" :inputType="'tel'" :id="''"></x-input>
            </div>
            <div class="col-6 mb-3">
              <x-input :name="'password'" :value="''" :label="'lang.assign_password'" :inputType="'text'" :id="''"></x-input>
            </div>
            <div class="col-8 mb-3">
              <x-text-area :name="'admin_address'" :value="''" :label="'lang.address'"></x-text-area>
            </div>
            @endif
            <div class="{{ $modalId === 'add-service' ? 'col-12' : 'col-4' }} my-auto text-right">
              <x-submit-button :name="'submit'" :value="'lang.save'"></x-submit-button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script>
  var counter = 0;

  window.onload = loadEventListener();

  function loadEventListener() {
    let addRowAnchorTags = document.getElementsByClassName('moreFields');

    for (i = 0; i < addRowAnchorTags.length; i++) {
      addRowAnchorTags[i].onclick = moreFields;
    }
  }

  function moreFields() {
    counter++;
    var newFields = document.getElementById('readroot').cloneNode(true);
    newFields.id = '';
    newFields.style.display = 'row';

    // Reset input values to blank in the copied row
    var inputFields = newFields.querySelectorAll('input[type="text"], input[type="number"]');
    for (var i = 0; i < inputFields.length; i++) {
      inputFields[i].value = '';
    }

    var newField = newFields.querySelectorAll('[name]');
    for (var i = 0; i < newField.length; i++) {
      var theName = newField[i].getAttribute('name');
      if (theName)
        newField[i].setAttribute('name', theName + counter);
    }

    var insertHere = document.getElementById('writeroot');
    insertHere.parentNode.insertBefore(newFields, insertHere);
    setTimeout(function() {
      loadEventListener();
    });
  }
</script>

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
        $('.error-image').removeClass('d-none').text('The user pic should be less than or equal to 1024KB');
        console.log("Image size exceeds the limit of 1 MB.");
        fileInput.value = "";
      }
    } else {
      $('.error-image').removeClass('d-none').text('Please select an image file.');
      console.log("Please select an image file.");
      fileInput.value = "";
    }


  });
</script>