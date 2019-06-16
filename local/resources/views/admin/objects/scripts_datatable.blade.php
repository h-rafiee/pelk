<script src="{{asset('assets/admin/vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/admin/vendor/datatables-plugins/dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('assets/admin/vendor/datatables-responsive/dataTables.responsive.js')}}"></script>

<script>
    $(document).ready(function() {
        $('.dataTABLE').DataTable({
            responsive: true
        });
    });
</script>
