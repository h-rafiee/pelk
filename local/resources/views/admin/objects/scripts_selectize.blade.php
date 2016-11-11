<script src="{{asset('assets/admin/vendor/selectize/js/selectize.min.js')}}"></script>
<script>
    $('#select-tools').selectize({
        plugins: ['remove_button'],
        maxItems: null,
        valueField: 'id',
        labelField: 'value',
        searchField: 'value',
        options: {!! $tags !!},
        create: false
    });

    $('.input-selectize').selectize({
        create: function(input) {
            return {
                value: input,
                text: input
            }
        }
    });
</script>
