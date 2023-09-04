<!-- Vendor js -->
<script src="{{ asset('/assets/js/vendor.min.js') }}"></script>
<!-- include summernote css/js -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<!-- optional plugins -->
<script src="{{ asset('/assets/libs/moment/moment.min.js') }}"></script>
{{--<script src="{{ asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>--}}
<script src="{{ asset('/assets/libs/flatpickr/flatpickr.min.js') }}"></script>
<!-- page js -->
{{--<script src="{{ asset('/assets/js/pages/dashboard.init.js') }}"></script>--}}
<!-- App js -->
<script src="{{ asset('/assets/js/app.min.js') }}"></script>
<!-- Datatables js -->
<script src="{{url('/assets/libs/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('/assets/libs/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{url('/assets/libs/datatables/dataTables.responsive.min.js')}}"></script>
<script src="{{url('/assets/libs/datatables/responsive.bootstrap4.min.js')}}"></script>
<script src="{{url('/assets/libs/datatables/dataTables.buttons.min.js')}}"></script>
<script src="{{url('/assets/libs/datatables/buttons.bootstrap4.min.js')}}"></script>
<script src="{{url('/assets/libs/datatables/buttons.html5.min.js')}}"></script>
<script src="{{url('/assets/libs/datatables/buttons.flash.min.js')}}"></script>
<script src="{{url('/assets/libs/datatables/buttons.print.min.js')}}"></script>
<script src="{{url('/assets/libs/datatables/dataTables.keyTable.min.js')}}"></script>
<script src="{{url('/assets/libs/datatables/dataTables.select.min.js')}}"></script>
<!-- Datatables init -->
<script src="{{url('/assets/js/pages/datatables.init.js')}}"></script>
<!-- Time Picker -->
<script src="{{ asset('/assets/js/timepicker.min.js') }}"></script>

<script type="text/javascript">
    (function ($) {
        $("#idsssss").DataTable();
    })(jQuery);

    $(function () {
        $('.bs-timepicker').timepicker();
    });

    $(document).ready(function() {
        $('.summernote').summernote();
    });
</script>
