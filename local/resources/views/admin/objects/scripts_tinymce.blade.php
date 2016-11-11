<script src="{{asset('assets/admin/vendor/tinymce/tinymce.min.js')}}"></script>
<script>
    tinymce.init({
        selector:'textarea.tinyMCE',
        height  : 200,
        plugins: [
            'advlist autolink lists link image imagetools charmap print preview anchor',
            'searchreplace visualblocks code fullscreen directionality',
            'insertdatetime media table contextmenu paste code textcolor colorpicker'
        ],
        toolbar: 'insertfile undo redo | styleselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | ltr rtl | bullist numlist outdent indent | link image',
        directionality : 'rtl',
        language: 'fa_IR',
        relative_urls: false,
        convert_urls: false,
        paste_data_images : true,
        remove_script_host : false
    });
</script>