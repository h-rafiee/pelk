<script src="{{asset('assets/admin/vendor/selectize/js/selectize.min.js')}}"></script>
<script>
    @if(@$tags)
    $('.tag-selectize').selectize({
        plugins: ['remove_button'],
        maxItems: null,
        valueField: 'id',
        labelField: 'value',
        searchField: 'value',
        options: {!! $tags !!},
        create: true
    });
    @endif

    @if(@$publications)
    $('.publication-selectize').selectize({
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'title',
        searchField: 'title',
        options: {!! $publications !!},
        create: true
    });
    @endif


    @if(@$writers)
    $('.writer-selectize').selectize({
                plugins: ['remove_button'],
                maxItems: null,
                valueField: 'id',
                labelField: 'name',
                searchField: 'name',
                options: {!! $writers !!},
                create: true
            });
    @endif

    @if(@$translators)
    $('.translator-selectize').selectize({
                plugins: ['remove_button'],
                maxItems: null,
                valueField: 'id',
                labelField: 'name',
                searchField: 'name',
                options: {!! $translators !!},
                create: true
            });
    @endif
</script>
