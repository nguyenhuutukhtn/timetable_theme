jQuery(document).ready(function($) {
    $('.color-picker').wpColorPicker();

    $('#add-segment').on('click', function() {
        $('#new-segment-form').show();
        $(this).hide();
        $('#new-segment-color').wpColorPicker();
    });

    $('#save-new-segment').on('click', function() {
        var color = $('#new-segment-color').val();
        var label = $('#new-segment-label').val();
        if (color && label) {
            var index = $('.segment-row').length;
            var newRow = '<div class="segment-row">' +
                '<input type="text" name="lucky_wheel_segments[' + index + '][color]" value="' + color + '" class="color-picker" />' +
                '<input type="text" name="lucky_wheel_segments[' + index + '][label]" value="' + label + '" />' +
                '<button type="button" class="remove-segment">Remove</button>' +
                '</div>';
            $('#lucky-wheel-segments').append(newRow);
            $('.color-picker').wpColorPicker();
            $('#new-segment-form').hide();
            $('#add-segment').show();
            $('#new-segment-color').val('');
            $('#new-segment-label').val('');
        } else {
            alert('Please enter both color and label for the new segment.');
        }
    });

    // Other event handlers...
});