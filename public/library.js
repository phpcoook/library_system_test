$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    loadLibraryData(1);

    $(document).on('click', '.page-link', function () {
        let pageId = $(this).data('id');
        loadLibraryData(pageId);
    });

    function loadLibraryData(currentPage) {
        $.ajax({
            type: "POST",
            url: "/fetch-books",
            data: {
                page: currentPage,
            },
            dataType: false,
            success: function (response) {
                $("#table-container").html(response.view);
            },
        });
    }
});
