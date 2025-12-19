// Check if passwords match
$("#confirmPassword").on("keyup", function () {
    if (
        $("#newPassword").val() != "" &&
        $("#confirmPassword").val() != "" &&
        $("#confirmPassword").val() == $("#newPassword").val()
    ) {
        this.setCustomValidity('');
    }
    else this.setCustomValidity(true);
});

// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
    "use strict";

    window.addEventListener(
        "load",
        function () {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName("needs-validation");
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener(
                    "submit",
                    function (event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add("was-validated");
                    },
                    false
                );
            });
        },
        false
    );




    // $('#dataTable2').DataTable( {
    //     "scrollX": false,
    //     order:[],
    //     searching: true,
    //     select:true,
    //     paging: false,
    //     info:false,
    //     language: {url:'https://cdn.datatables.net/plug-ins/1.13.1/i18n/ar.json'},
    //     dom: 'Bfrtip',
    //     buttons: [
    //         'copy', 'csv', 'excel', 'pdf'
    //     ],
    //
    //     initComplete: function () {
    //         this.api()
    //             .columns()
    //             .every(function () {
    //                 let column = this;
    //
    //                 // Create select element
    //                 let select = document.createElement('select');
    //                 select.add(new Option(''));
    //                 column.header().replaceChildren(select);
    //
    //                 // Apply listener for user change in value
    //                 select.addEventListener('change', function () {
    //                     column
    //                         .search(select.value, {exact: true})
    //                         .draw();
    //                 });
    //
    //                 // Add list of options
    //                 column
    //                     .data()
    //                     .unique()
    //                     .sort()
    //                     .each(function (d, j) {
    //                         select.add(new Option(d));
    //                     });
    //             });
    //     }
    // } );
    // $('.buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
})();
