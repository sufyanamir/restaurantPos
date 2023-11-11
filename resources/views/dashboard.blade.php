@include('layouts.header')
<div class="container-fluid">
    @if(session('user_details')['role'] == '1')
    <div class="row">
        <div class="col-12 col-sm-12 col-lg-6 col-xl-3 mb-2">
            
            <x-dashboard-card :value="$staffCount" :label="'lang.total_staff'" :svg="asset('assets/images/d-user-icon.svg')"></x-dashboard-card>
        </div>
        <div class="col-12 col-sm-12 col-lg-6 col-xl-3 mb-2">
            <x-dashboard-card :value="$customersCount" :label="'lang.total_customers'" :svg="asset('assets/images/d-user-icon.svg')"></x-dashboard-card>
        </div>
        <div class="col-12 col-sm-12 col-lg-6 col-xl-3 mb-2">
            <x-dashboard-card :value="$servicesCount" :label="'lang.total_services'" :svg="asset('assets/images/d-list.svg')"></x-dashboard-card>
        </div>
        <div class="col-12 col-sm-12 col-lg-6 col-xl-3 mb-2">
            <x-dashboard-card :value="'247'" :label="'lang.total_orders'" :svg="asset('assets/images/d-orders.svg')"></x-dashboard-card>
        </div>
    </div>
    <div class="pt-2" style="border-radius: 20px;">
        <div id="chartContainer" style="height: 100%; width: 100%;"></div>
    </div>
    @else
    <div class="row">
        <div class="col-12 col-sm-12 col-lg-6 col-xl-3 mb-2">
            <x-dashboard-card :value="$adminCount" :label="'lang.total_admins'" :svg="asset('assets/images/d-user-icon.svg')"></x-dashboard-card>
        </div>
        <div class="col-12 col-sm-12 col-lg-6 col-xl-3 mb-2">
            <x-dashboard-card :value="$adminCustomerCount" :label="'lang.total_customers'" :svg="asset('assets/images/d-user-icon.svg')"></x-dashboard-card>
        </div>
        <div class="col-12 col-sm-12 col-lg-6 col-xl-3 mb-2">
            <x-dashboard-card :value="'$87,561'" :label="'lang.total_revenue'" :svg="asset('assets/images/d-dollar.svg')"></x-dashboard-card>
        </div>
        <div class="col-12 col-sm-12 col-lg-6 col-xl-3 mb-2">
            <x-dashboard-card :value="'247'" :label="'lang.total_orders'" :svg="asset('assets/images/d-orders.svg')"></x-dashboard-card>
        </div>
    </div>
    @endif
    <div class="pt-2" style="border-radius: 20px;">
        <div id="chartContainer" style="height: 100%; width: 100%;"></div>
    </div>
</div>
<script>

</script>

@include('layouts.footer')