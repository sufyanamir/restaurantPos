<div  id="dropzone" class="dropzone">
    
    <img id="profileImage" src="{{ 'assets/images/rectangle-image.svg' }}"  style="width: 100%; height: 237px; object-fit: fill;" alt="text">
    <div class="file-input-container">
        <input class="file-input" type="file" name="{{ $name }}" id="fileInput1">
        <div class="upload-icon" onclick="document.getElementById('fileInput1').click()">
            <img src="{{ asset('assets/images/upload-image.svg') }}" alt="Image">
        </div>
    </div>
</div>
<p class="error-image text-danger d-none" style="font-size: smaller;"></P>