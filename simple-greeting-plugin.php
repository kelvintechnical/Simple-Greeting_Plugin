<?php /*this is the header*/
/*
Plugin Name: Simple Greeting Plugin
Description: A simple plugin to display a greeting message in the WordPress admin dashboard.
Version: 1.0
Author: Kelvin Tobias
*/

function display_greeting_message() {
    $hour = current_time("H");
    error_log("Current hour: " . $hour);

    if ($hour < 12) {
        $time_based_greeting = "Good Morning";
    } elseif ($hour < 18) {
        $time_based_greeting = "Good Afternoon";
    } else {
        $time_based_greeting = "Good Evening";
    }

    // Fetch the custom greeting message from the settings
    $custom_greeting = get_option('greeting_message', 'Kelvin');

    echo "<div class='notice notice-success is-dismissible'>
            <p>$time_based_greeting, $custom_greeting! Welcome to your WordPress site!</p>
          </div>";

}
/* echo outputs whatever follows it directly to the page */

/* 

<div>: This is an HTML container element that allows us to style or structure content.
class='notice notice-success is-dismissible': These classes are special CSS classes used by WordPress to create a “notice” (a type of styled message box) in the admin dashboard.
notice: Defines it as a notice box.
notice-success: Adds a green background, indicating a success message.
is-dismissible: Adds a close button to dismiss the message.
<p>Welcome to your WordPress site, Kelvin!</p>: This <p> tag contains the actual greeting message text. It will display on the dashboard.


*/
add_action('admin_notices', 'display_greeting_message');


/* 
add_action('admin_notices', 'display_greeting_message');:

This line “hooks” our function into WordPress’s admin_notices action.
add_action: This is a WordPress function that tells WordPress to run specific code at a certain point in the workflow.
'admin_notices': This is the hook name. The admin_notices hook runs whenever admin notices are displayed, which is at the top of the dashboard.
'display_greeting_message': This is the name of our function. It tells WordPress to execute display_greeting_message whenever admin_notices is called.

You can think of admin_notices as a “designated space” in the dashboard for showing important messages, which is why we see it right up top.

Our function is "hooked" to the admin_notices location.
Admin notices display in the same spot on the dashboard, making them ideal for important messages that the admin should see immediately.

*/

function greeting_plugin_menu() {
//     <!-- This menu item is necessary because it gives us a place in the WordPress settings where the admin can manage options for the plugin.
// Without this function, there would be no way for the admin to easily access and modify the greeting message within the WordPress interface. -->
    add_options_page(
        'Simple Greeting Settings',   //Page title
        'Greeting Settings',          //Menu title
        'manage_options',               //Capability
        'greeting-plugin-settings',     //Menu slug
        //The slug is used internally by WordPress to identify this specific settings page.
        'greeting_plugin_settings_page', //Callback function
        //When we click on our menu item in the Settings sidebar, WordPress will look for this function (greeting_plugin_settings_page) to display the content.
        // The content inside the callback function isn’t loaded until the settings page is actually accessed.

    );

}
add_action('admin_menu', 'greeting_plugin_menu');

// Function to display the settings page content


function greeting_plugin_settings_page() {
    /* switch from PHP to HTML */
    ?> 

    <div class="wrap">
        <h1>Simple Greeting Plugin Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('greeting_plugin_options_group');
            do_settings_sections('greeting-plugin-settings');

            ?>
            <table class="form-table">
            <tr valign="top">
            <th scope="row">Custom Greeting Message:</th>
            <td>
            <input type="text" name="greeting_message" value="<?php echo esc_attr(get_option('greeting_message', 'Kelvin')); ?>"/>
            </td>
            </tr>
            </table>

            <?php
            submit_button();
            ?>
            </form>
                </div>
                <?php
}



function greeting_plugin_register_settings() {
    register_setting('greeting_plugin_options_group', 'greeting_message');
}

add_action('admin_init', 'greeting_plugin_register_settings');


// 

// register_setting()

// This is a WordPress function, not a PHP keyword.
// It’s used in WordPress to define a new setting, which WordPress will save to its database.
// When you use register_setting(), WordPress will handle storing and retrieving the setting value, and it automatically sanitizes (cleans) the data to protect it from harmful inputs.
// 'greeting_plugin_options_group'

// This is a custom identifier we created for our plugin.
// It groups our settings together, so WordPress knows they belong to the same plugin or feature.
// It links our settings_fields() form (on the settings page) to the registered setting, allowing WordPress to correctly save and retrieve the settings.
// 'greeting_message'

// This is another custom identifier we chose as the option name to store our custom greeting message.
// When we call get_option('greeting_message'), WordPress retrieves the saved value associated with this name.

// settings_fields adds hidden fields for security checks, like a nonce (a unique token) that helps verify the form submission is legitimate.
// Option Group Link: It links the form to our option group (greeting_plugin_options_group), which we registered earlier. This tells WordPress, “Any settings saved from this form belong to this group.”
// In short, settings_fields is like a connector that tells WordPress which settings the form will save and ensures the form submission is secure.

// settings_fields, do_settings_sections, submit_button, add_action, and add_options_page are WordPress functions built on top of PHP. They only work within WordPress and are specific to its framework.

