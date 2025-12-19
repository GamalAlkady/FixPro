<div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">تأكيد </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-primary btnYes">نعم</button>
                <button class="btn btn-secondary" type="button" data-dismiss="modal">لا</button>
<!--                <a class="btn btn-primary" href="login.html">نعم</a>-->
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        "use strict";
        const myModal = new bootstrap.Modal(document.getElementById('confirm'), {keyboard: false});
        let formId = null;
        $(".btnDelete , .btnCancel").on('click', function (event) {
            $(".modal-body").text($(this).data('title'))
            formId=$(this).data('form');
            console.log($(this).data)
            // $('#formDelete').preventDefault()
            myModal.show()
        })

        $(".btnYes").on('click', function (event) {

            // console.log(`#formDelete${formId}`)
            $(`#${formId}`).submit();

        })


    })();

</script>