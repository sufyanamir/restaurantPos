<style>
    .d-card {
        background-image: linear-gradient(90deg, rgb(255, 123, 84) 20%, rgb(255, 178, 107) 60%);
        width: 100%;
        height: 110px;
        top: 122px;
        left: 287px;
        border-radius: 20px;
    }

    .card-text {
        padding: 20px;
        color: white;
    }
</style>
<div class="d-card">
    <div class="row">
        <div class="col-7">
        <div class="card-text">
            <h5>{{ $value }}</h5>
            <h6>@lang($label)</h6>
        </div>
        </div>
        <div class="col-5 my-auto">
        <div class="text-right pr-2">
            <img src="{{ $svg }}" alt="SVG Image">
        </div>
        </div>
    </div>
</div>