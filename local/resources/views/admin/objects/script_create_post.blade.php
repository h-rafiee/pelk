<script>
    var file_upload = true;

    $("#upload_btn").click(function(){
        $("#fileToUpload").click();
    });
    $("#fileToUpload").on('change',upload);

    $(".clickable").on('click',function(e){
        e.preventDefault();
        $("#"+$(this).attr('data-click')).click();
    });

    $(".uploadFile").on("change", function()
    {
        var point = $(this).attr('data-point');
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

        if (/^image/.test( files[0].type)){ // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file

            reader.onloadend = function(){ // set image data as background of div
                $("img[data-img='"+point+"']").attr('src',this.result);
            }
        }
    });


    function upload(){
        file_upload = false;
        var fileIn = $("#fileToUpload")[0];
        //Has any file been selected yet?
        if (fileIn.files === undefined || fileIn.files.length == 0) {
            alert("Please select a file");
            return;
        }

        //We will upload only one file in this demo
        var file = fileIn.files[0];
        var formData = new FormData();
        $("input#file").val('');
        formData.append('file', file);
        //Show the progress bar
        $("#progress_position").removeClass('hidden');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{url('admin/ajax/upload')}}",
            type: "POST",
            data: formData,
            processData: false, //Work around #1
            contentType: false, //Work around #2
            success: function(data){
                $("input#file").val(''+data+'');
            },
            error: function(){
                $.alertable.alert('خطا در آپلود فایل دوباره تلاش کنید !');
            },
            complete: function(){
                file_upload=true;
                $("#progress_position").addClass('hidden');
            },
            //Work around #3
            xhr: function() {
                myXhr = $.ajaxSettings.xhr();
                if(myXhr.upload){
                    myXhr.upload.addEventListener('progress',showProgress, false);
                } else {
                    $.alertable.alert('آپلودر ساپورت نمیشود لطفا از مرورگر دیگری استفاده کنید!');
                }
                return myXhr;
            }
        });
    }


    function showProgress(evt) {
        if (evt.lengthComputable) {
            var percentComplete = (evt.loaded / evt.total) * 100;
            $('#progressbar').attr("aria-valuenow", percentComplete );
            $('#progressbar').css('width',percentComplete+"%");
            $("#progressbar span").html(percentComplete+"% Complete");
        }
    }


    function dosubmit(){
        if(file_upload==false){
            $.alertable.alert('لطفا صبر کنید فایل در حال آپلود می باشد!');
        }
        return file_upload;
    }

</script>