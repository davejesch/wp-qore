<?php

// Customize howdy message
add_filter('gettext', 'change_howdy', 10, 3);

function change_howdy($translated, $text, $domain) {

    if (!is_admin() || 'default' != $domain)
        return $translated;

    if (false !== strpos($translated, 'Howdy'))
        $howdy_text = get_option("wpqorefunc_howdy_text");
        return str_replace('Howdy', __( $howdy_text, 'wp-qore' ), $translated);

    return $translated;
}

?>
