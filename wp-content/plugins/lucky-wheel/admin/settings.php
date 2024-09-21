<?php
function lucky_wheel_settings_page() {
    ?>
    <div class="wrap">
        <h1>Lucky Wheel Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('lucky_wheel_options');
            do_settings_sections('lucky_wheel');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function lucky_wheel_settings_init() {
    register_setting('lucky_wheel_options', 'lucky_wheel_segments');
    register_setting('lucky_wheel_options', 'lucky_wheel_form_fields', 'lucky_wheel_sanitize_form_fields');
    register_setting('lucky_wheel_options', 'lucky_wheel_facebook_page');

    add_settings_section(
        'lucky_wheel_section',
        'Wheel Segments',
        'lucky_wheel_section_callback',
        'lucky_wheel'
    );

    add_settings_field(
        'lucky_wheel_segments',
        'Segments',
        'lucky_wheel_segments_callback',
        'lucky_wheel',
        'lucky_wheel_section'
    );

    add_settings_field(
        'lucky_wheel_form_fields',
        'Form Fields',
        'lucky_wheel_form_fields_callback',
        'lucky_wheel',
        'lucky_wheel_section'
    );

    add_settings_field(
        'lucky_wheel_facebook_page',
        'Facebook Page URL',
        'lucky_wheel_facebook_page_callback',
        'lucky_wheel',
        'lucky_wheel_section'
    );
}

function lucky_wheel_section_callback() {
    echo 'Configure the segments for the Lucky Wheel:';
}

function lucky_wheel_segments_callback() {
    $segments = get_option('lucky_wheel_segments', [
        ['color' => "#FF6B6B", 'label' => "Prize 1"],
        ['color' => "#4ECDC4", 'label' => "Prize 2"],
        ['color' => "#45B7D1", 'label' => "Prize 3"],
        ['color' => "#F7DC6F", 'label' => "Prize 4"],
        ['color' => "#B8E994", 'label' => "Prize 5"],
        ['color' => "#FF9FF3", 'label' => "Prize 6"]
    ]);

    echo '<div id="lucky-wheel-segments">';
    foreach ($segments as $index => $segment) {
        echo '<div class="segment-row">';
        echo '<input type="text" name="lucky_wheel_segments[' . $index . '][color]" value="' . esc_attr($segment['color']) . '" class="color-picker" />';
        echo '<input type="text" name="lucky_wheel_segments[' . $index . '][label]" value="' . esc_attr($segment['label']) . '" />';
        echo '<button type="button" class="remove-segment">Remove</button>';
        echo '</div>';
    }
    echo '</div>';
    echo '<button type="button" id="add-segment">Add Segment</button>';

    // Add new segment form
    echo '<div id="new-segment-form" style="display:none; margin-top: 20px;">';
    echo '<h4>Add New Segment</h4>';
    echo '<input type="text" id="new-segment-color" class="color-picker" placeholder="Color (e.g., #FF0000)" />';
    echo '<input type="text" id="new-segment-label" placeholder="Prize Label" />';
    echo '<button type="button" id="save-new-segment">Save Segment</button>';
    echo '<button type="button" id="cancel-new-segment">Cancel</button>';
    echo '</div>';

    ?>
    <script>
    jQuery(document).ready(function($) {
        $('.color-picker').wpColorPicker();

        $('#add-segment').on('click', function() {
            $('#new-segment-form').show();
            $(this).hide();
            $('#new-segment-color').wpColorPicker();
        });

        $('#cancel-new-segment').on('click', function() {
            $('#new-segment-form').hide();
            $('#add-segment').show();
            $('#new-segment-color').val('');
            $('#new-segment-label').val('');
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

        $(document).on('click', '.remove-segment', function() {
            $(this).closest('.segment-row').remove();
        });
    });
    </script>
    <?php
}

function lucky_wheel_form_fields_callback() {
    $form_fields = get_option('lucky_wheel_form_fields', [
        ['type' => 'text', 'label' => 'Name', 'required' => true],
        ['type' => 'email', 'label' => 'Email', 'required' => true],
        ['type' => 'tel', 'label' => 'Phone', 'required' => true],
        ['type' => 'text', 'label' => 'Website', 'required' => false]
    ]);

    echo '<div id="lucky-wheel-form-fields">';
    foreach ($form_fields as $index => $field) {
        echo '<div class="form-field-row">';
        echo '<select name="lucky_wheel_form_fields[' . $index . '][type]">';
        echo '<option value="text"' . selected($field['type'], 'text', false) . '>Text</option>';
        echo '<option value="email"' . selected($field['type'], 'email', false) . '>Email</option>';
        echo '<option value="tel"' . selected($field['type'], 'tel', false) . '>Telephone</option>';
        echo '</select>';
        echo '<input type="text" name="lucky_wheel_form_fields[' . $index . '][label]" value="' . esc_attr($field['label']) . '" placeholder="Field Label" />';
        echo '<label><input type="checkbox" name="lucky_wheel_form_fields[' . $index . '][required]" ' . checked(isset($field['required']) ? $field['required'] : false, true, false) . ' /> Required</label>';
        echo '<button type="button" class="remove-field">Remove</button>';
        echo '</div>';
    }
    echo '</div>';
    echo '<button type="button" id="add-form-field">Add Form Field</button>';

    ?>
    <script>
    jQuery(document).ready(function($) {
        $('#add-form-field').on('click', function() {
            var index = $('.form-field-row').length;
            var newRow = '<div class="form-field-row">' +
                '<select name="lucky_wheel_form_fields[' + index + '][type]">' +
                '<option value="text">Text</option>' +
                '<option value="email">Email</option>' +
                '<option value="tel">Telephone</option>' +
                '</select>' +
                '<input type="text" name="lucky_wheel_form_fields[' + index + '][label]" placeholder="Field Label" />' +
                '<label><input type="checkbox" name="lucky_wheel_form_fields[' + index + '][required]" /> Required</label>' +
                '<button type="button" class="remove-field">Remove</button>' +
                '</div>';
            $('#lucky-wheel-form-fields').append(newRow);
        });

        $(document).on('click', '.remove-field', function() {
            $(this).closest('.form-field-row').remove();
        });
    });
    </script>
    <?php
}

function lucky_wheel_facebook_page_callback() {
    $facebook_page = get_option('lucky_wheel_facebook_page', 'https://www.facebook.com/your-fb-page');
    echo '<input type="url" name="lucky_wheel_facebook_page" value="' . esc_url($facebook_page) . '" class="regular-text">';
    echo '<p class="description">Enter the URL of your Facebook page for the "Contact to receive prize" link.</p>';
}

function lucky_wheel_sanitize_form_fields($input) {
    $sanitized_input = [];
    foreach ($input as $field) {
        $sanitized_field = [
            'type' => sanitize_text_field($field['type']),
            'label' => sanitize_text_field($field['label']),
            'required' => isset($field['required']) ? true : false
        ];
        $sanitized_input[] = $sanitized_field;
    }
    return $sanitized_input;
}

add_action('admin_init', 'lucky_wheel_settings_init');
