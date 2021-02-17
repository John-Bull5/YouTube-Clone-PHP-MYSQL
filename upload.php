<?php

include_once('./core/VideoDetailsForm.php');
include_once('./includes/header.php');

?>
<div class="column">
    <?php

    $form = new VideoDetailsForm($con);
    echo $form->createUploadForm();

    ?>
</div>
<script>
    $('form').submit(function() {
        $('#loadingModal').modal('show');
    })
</script>
<!-- Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModal" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="container-fluid">
                    Please wait video is uploading
                    <div><img src="assets/images/icons/6.gif"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('./includes/footer.php'); ?>