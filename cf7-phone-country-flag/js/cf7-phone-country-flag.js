jQuery(document).ready(function($) {
    $('.cf7-country-flag-selector').on('change', function(){
        // Retrieve the selected country's phone prefix
        var prefix = $(this).find(':selected').data('prefix');
        // Update the telephone input field with the selected prefix
        $(this).siblings('.cf7-country-flag-input').val(prefix + ' ');
    });
});
