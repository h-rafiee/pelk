<script src="{{asset('assets/admin/vendor/pwtdatepicker/js/persian-date.js')}}"></script>
<script src="{{asset('assets/admin/vendor/pwtdatepicker/js/persian-datepicker-0.4.5.min.js')}}"></script>
<script>
    $(".pwtdatepicker").pDatepicker({
        observer: true,
        format: 'YYYY MMMM D',
    });
</script>