<style>
    .social-input {
        /* border-bottom: 2px solid gray; */
        border: none !important;
        border-bottom: 2px solid #DAD2D2 !important;
        width: 100%;
    }
</style>
<div class="row mb-2">
    <div class="col-1">
        <img src="{{ $socialLogo }}" alt="Image">
    </div>
    <div class="col-8 pr-0">
        <input type="text" value="{{$value}}" class="social-input" name="{{ $name }}">
    </div>
</div>