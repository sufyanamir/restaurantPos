</div>
    </div>
    <!-- dataTables -->
    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <!-- dataTables -->
    <!-- bootstrap -->
    <!-- sweetalert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="{{ asset('assets/bootstrap/jquery.slim.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/bootstrap.min.js') }}"></script>
    <!-- bootstrap -->
    <script src="{{ asset('assets/canvas/canvasjs.min.js') }}"></script>
    @if (session('status'))
    <script>
        swal({
            title: "@lang('lang.success!')",
            text: "{{ session('status') }}",
            icon: "success",
            buttons: "OK",
        })
        // .then((willClose) => {
        //     if (willClose) {
        //         // Add any additional actions you want to perform on close
        //         // For example, redirect to another page
        //         window.location.href = "/another-paege";
        //     }
        // });
    </script>
    @elseif($errors->has('email') || $errors->has('admin_email')){
    <script>
        swal({
            title: "Email",
            text: "@lang('lang.already_registered') @lang('lang.enter_new_email')",
            icon: "error",
            buttons: "OK",
        })
        // .then((willClose) => {
        //     if (willClose) {
        //         // Add any additional actions you want to perform on close
        //         // For example, redirect to another page
        //         window.location.href = "/another-paege";
        //     }
        // });
    </script>
    }
    @elseif($errors->any()){
    <script>
        swal({
            title: "Opps!",
            text: "@lang('lang.something_went_wrong')",
            icon: "error",
            buttons: "OK",
        })
        // .then((willClose) => {
        //     if (willClose) {
        //         // Add any additional actions you want to perform on close
        //         // For example, redirect to another page
        //         window.location.href = "/another-paege";
        //     }
        // });
    </script>
    }
@endif
</body>

</html>