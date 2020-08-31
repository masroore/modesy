<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="audio_upload_result">
    <?php $this->load->view('product/_audio_upload_response'); ?>
</div>

<!-- File item template -->
<script type="text/html" id="files-template-audio">
    <li class="media">
        <div class="media-body">
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </li>
</script>

<script>
    $('#drag-and-drop-zone-audio').dmUploader({
        url: '<?php echo base_url(); ?>file_controller/upload_audio',
        maxFileSize: <?php echo $this->general_settings->max_file_size_audio; ?>,
        queue: true,
        extFilter: ["mp3", "wav"],
        multiple: false,
        extraData: {
            "product_id":<?php echo $product->id; ?>
        },
        onDragEnter: function () {
            this.addClass('active');
        },
        onDragLeave: function () {
            this.removeClass('active');
        },
        onInit: function () {
        },
        onComplete: function (id) {
        },
        onNewFile: function (id, file) {
            ui_multi_add_file(id, file, "audio");
        },
        onBeforeUpload: function (id) {
            ui_multi_update_file_progress(id, 0, '', true);
            ui_multi_update_file_status(id, 'uploading', 'Uploading...');
        },
        onUploadProgress: function (id, percent) {
            ui_multi_update_file_progress(id, percent);
        },
        onUploadSuccess: function (id, product_id) {
            load_audio_preview(product_id);
        },
        onUploadError: function (id, xhr, status, message) {
        },
        onFallbackMode: function () {
        },
        onFileSizeError: function (file) {
            $(".error-message-img-upload").show();
            $(".error-message-img-upload p").html("<?php echo trans('file_too_large') . ' ' . formatSizeUnits($this->general_settings->max_file_size_audio); ?>");
            setTimeout(function () {
                $(".error-message-img-upload").fadeOut("slow");
            }, 4000)
        },
        onFileTypeError: function (file) {
        },
        onFileExtError: function (file) {
        },
    });
    $(document).ajaxStop(function () {
        $('#drag-and-drop-zone-audio').dmUploader({
            url: '<?php echo base_url(); ?>file_controller/upload_audio',
            maxFileSize: <?php echo $this->general_settings->max_file_size_audio; ?>,
            queue: true,
            extFilter: ["mp3", "wav"],
            multiple: false,
            extraData: {
                "product_id":<?php echo $product->id; ?>
            },
            onDragEnter: function () {
                this.addClass('active');
            },
            onDragLeave: function () {
                this.removeClass('active');
            },
            onInit: function () {
            },
            onComplete: function (id) {
            },
            onNewFile: function (id, file) {
                ui_multi_add_file(id, file, "audio");
            },
            onBeforeUpload: function (id) {
                ui_multi_update_file_progress(id, 0, '', true);
                ui_multi_update_file_status(id, 'uploading', 'Uploading...');
            },
            onUploadProgress: function (id, percent) {
                ui_multi_update_file_progress(id, percent);
            },
            onUploadSuccess: function (id, product_id) {
                load_audio_preview(product_id);
            },
            onUploadError: function (id, xhr, status, message) {
            },
            onFallbackMode: function () {
            },
            onFileSizeError: function (file) {
                $(".error-message-img-upload").show();
                $(".error-message-img-upload p").html("<?php echo trans('file_too_large') . ' ' . formatSizeUnits($this->general_settings->max_file_size_audio); ?>");
                setTimeout(function () {
                    $(".error-message-img-upload").fadeOut("slow");
                }, 4000)
            },
            onFileTypeError: function (file) {
            },
            onFileExtError: function (file) {
            },
        });
    });

    function load_audio_preview(product_id) {
        var data = {
            "product_id": product_id
        };
        data[csfr_token_name] = $.cookie(csfr_cookie_name);
        $.ajax({
            type: "POST",
            url: base_url + "file_controller/load_audio_preview",
            data: data,
            success: function (response) {
                setTimeout(function () {
                    document.getElementById("audio_upload_result").innerHTML = response;
                    const audio_player = new Plyr('#audio_player');
                }, 10000);
            }
        });
    }
</script>

