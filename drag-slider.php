<?php
/**
 * Plugin Name: Draggable Image Slider 
 * Description: Draggable image slider with admin upload option. Use shortcode [drag_slider] to display.
 * Version: 1.2
 * Author: WE DESIGN LAB
 */

if (!defined('ABSPATH')) exit;

define('DRAG_SLIDER_PATH', plugin_dir_path(__FILE__));
define('DRAG_SLIDER_URL', plugin_dir_url(__FILE__));

// Register settings
function drag_slider_register_settings() {
    register_setting('drag_slider_group', 'drag_slider_images');
}
add_action('admin_init', 'drag_slider_register_settings');

// Add settings page
function drag_slider_settings_page() {
    add_options_page('Drag Slider Settings', 'Drag Slider', 'manage_options', 'drag-slider-settings', 'drag_slider_render_admin_page');
}
add_action('admin_menu', 'drag_slider_settings_page');

// Render settings page
function drag_slider_render_admin_page() {
    $images = get_option('drag_slider_images', []);
    ?>
    <div class="wrap">
        <h1>Drag Image Slider Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('drag_slider_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Upload Images</th>
                    <td>
                        <div id="drag-slider-images" class="drag-slider-sortable">
                            <?php if (!empty($images)) {
                                foreach ($images as $index => $url) {
                                    echo '<div class="drag-slider-item">
                                            <img src="' . esc_url($url) . '" style="width: 100px; height: auto; margin-right: 10px;" />
                                            <input type="hidden" name="drag_slider_images[]" value="' . esc_attr($url) . '" />
                                            <button class="button button-secondary replace-image">Replace</button>
                                            <button class="button remove-image">Remove</button>
                                          </div>';
                                }
                            } ?>
                        </div>
                        <button type="button" class="button button-primary" id="add-slider-images">Add Images</button>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <style>
        .drag-slider-sortable { display: flex; flex-wrap: wrap; gap: 10px; }
        .drag-slider-item { border: 1px solid #ccc; padding: 10px; background: #fff; cursor: move; display: flex; align-items: center; gap: 10px; }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            jQuery('#add-slider-images').on('click', function(e) {
                e.preventDefault();
                let frame = wp.media({ multiple: true });
                frame.on('select', function() {
                    let selection = frame.state().get('selection');
                    selection.each(function(attachment) {
                        let url = attachment.attributes.url;
                        let container = document.createElement('div');
                        container.classList.add('drag-slider-item');
                        container.innerHTML = '<img src="' + url + '" style="width:100px; height:auto; margin-right:10px;" />' +
                                             '<input type="hidden" name="drag_slider_images[]" value="' + url + '" />' +
                                             '<button class="button button-secondary replace-image">Replace</button>' +
                                             '<button class="button remove-image">Remove</button>';
                        document.getElementById('drag-slider-images').appendChild(container);
                    });
                });
                frame.open();
            });

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-image')) {
                    e.preventDefault();
                    e.target.parentElement.remove();
                }

                if (e.target.classList.contains('replace-image')) {
                    e.preventDefault();
                    let parent = e.target.parentElement;
                    let frame = wp.media({ multiple: false });
                    frame.on('select', function() {
                        let attachment = frame.state().get('selection').first().toJSON();
                        parent.querySelector('img').src = attachment.url;
                        parent.querySelector('input[type="hidden"]').value = attachment.url;
                    });
                    frame.open();
                }
            });

            jQuery('#drag-slider-images').sortable();
        });
    </script>
    <?php
}

// Enqueue admin scripts
function drag_slider_admin_enqueue($hook) {
    if ($hook === 'settings_page_drag-slider-settings') {
        wp_enqueue_media();
        wp_enqueue_script('jquery-ui-sortable');
    }
}
add_action('admin_enqueue_scripts', 'drag_slider_admin_enqueue');

// Shortcode function
function drag_slider_shortcode_output() {
    if (is_admin()) {
        return ''; // or return '<p>Preview disabled in admin.</p>';
    }

    ob_start();
    include DRAG_SLIDER_PATH . 'slider-template.php';
    return ob_get_clean();
}
add_shortcode('drag_slider', 'drag_slider_shortcode_output');
