<script>
    @if(@$model)
        var image_flage = true;
    @else
        var image_flag = false;
    @endif
    $("select.mselectize").each(function(){
       $(this).selectize({
          create:false
       });
    });
    function gcd (a, b) {
        return (b == 0) ? a : gcd (b, a%b);
    }
    var file_upload = true;

    $("#upload_btn").click(function(){
        $("#fileToUpload").click();
    });
    $("#fileToUpload").on('change',upload);
    $("#fileDemoToUpload").on('change',uploadDemo);


    $(".clickable").on('click',function(e){
        e.preventDefault();
        $("#"+$(this).attr('data-click')).click();
    });

    $(".uploadFile").on("change", function()
    {
        var select = $(this);
        var point = $(this).attr('data-point');
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

        if (/^image/.test( files[0].type)){ // only image file
            var reader = new FileReader(); // instance of the FileReader

            var _URL = window.URL;
            var  img;
            var ratio = "";
            reader.readAsDataURL(files[0]); // read the local file
            reader.onloadend = function(){ // set image data as background of div
                img = new Image();
                img.src = this.result;
                img.onload = function () {
                    var r = gcd(img.width,img.height);
                    ratio = img.width/r+":"+img.height/r;
                    if(ratio != "2:3"){
                        select.replaceWith(select.val('').clone(true));
                        $.alertable.alert('خطا ratio تصویر باید 2:3 باشد !');
                        Holder.run({
                            images: "img[data-img='"+point+"']"
                        });
                        image_flag = false;
                        return false;
                    }else{
                        image_flag = true;
                        $("img[data-img='"+point+"']").attr('src',img.src);
                    }
                };
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
        $("#progress_position .message").addClass('hidden');
        $("#progress_position .progress").removeClass('hidden');
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
                $("#progress_position .message").removeClass('hidden');
            },
            error: function(){
                $.alertable.alert('خطا در آپلود فایل دوباره تلاش کنید !');
                $("#progress_position .message").addClass('hidden');
                $("#progress_position").addClass('hidden');
            },
            complete: function(){
                file_upload=true;
                $("#progress_position .progress").addClass('hidden');
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


    function uploadDemo(){
        var fileIn = $("#fileDemoToUpload")[0];
        //Has any file been selected yet?
        if (fileIn.files === undefined || fileIn.files.length == 0) {
            alert("Please select a file");
            return;
        }

        //We will upload only one file in this demo
        var file = fileIn.files[0];
        var formData = new FormData();
        $("input#file_demo").val('');
        formData.append('file', file);
        //Show the progress bar
        $("#progress_position_demo .message").addClass('hidden');
        $("#progress_position_demo .progress").removeClass('hidden');
        $("#progress_position_demo").removeClass('hidden');
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
                $("input#file_demo").val(''+data+'');
                $("#progress_position_demo .message").removeClass('hidden');
            },
            error: function(){
                $.alertable.alert('خطا در آپلود فایل دوباره تلاش کنید !');
                $("#progress_position_demo .message").addClass('hidden');
                $("#progress_position_demo").addClass('hidden');
            },
            complete: function(){
                $("#progress_position_demo .progress").addClass('hidden');
            },
            //Work around #3
            xhr: function() {
                myXhr = $.ajaxSettings.xhr();
                if(myXhr.upload){
                    myXhr.upload.addEventListener('progress',showProgressDemo, false);
                } else {
                    $.alertable.alert('آپلودر ساپورت نمیشود لطفا از مرورگر دیگری استفاده کنید!');
                }
                return myXhr;
            }
        });
    }


    function showProgressDemo(evt) {
        if (evt.lengthComputable) {
            var percentComplete = (evt.loaded / evt.total) * 100;
            $('#progressbar_demo').attr("aria-valuenow", percentComplete );
            $('#progressbar_demo').css('width',percentComplete+"%");
            $("#progressbar_demo span").html(percentComplete+"% Complete");
        }
    }


    function dosubmit(){
        if(file_upload==false){
            $.alertable.alert('لطفا صبر کنید فایل در حال آپلود می باشد!');
        }
        if(image_flag==false){
            $.alertable.alert('تصویر شاخص را انتخاب کنید !');
            return false;
        }
        return file_upload;
    }

</script>