$(document).ready(function() {

    console.log(122);

    $('#questplace-quiz_type').change(function() {
        const type = $(this).val();
        console.log(type);
        $('.quiz_type').addClass('hidden');
        $(`.quiz_type-${type}`).removeClass('hidden');
    });

})
