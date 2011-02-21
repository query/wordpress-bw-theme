<?php

header("Content-type: text/css");

$spotcolor = $_GET["spotcolor"];

if (!preg_match("/[0-9a-f]{6}/i", $spotcolor)) {
    die();
}

$spotcolor = "#$spotcolor";

/**
 * Gets the simple #rrggbb color triplet corresponding to the given triplet at
 * the given CSS opacity, against a white background.
 */
function bw_get_color_with_opacity($triplet, $opacity) {
    $rgb = hexdec(substr($triplet, 1));
    return '#' . dechex(0xff - (0xff - (($rgb & 0xff0000) >> 16)) * $opacity) .
                 dechex(0xff - (0xff - (($rgb & 0x00ff00) >>  8)) * $opacity) .
                 dechex(0xff - (0xff - (($rgb & 0x0000ff)      )) * $opacity);
}

?>
a:link {
    color: <?php echo $spotcolor; ?>;
}

a:visited {
    color: <?php echo bw_get_color_with_opacity($spotcolor, 0.75); ?>;
}

#wrapper {
    border: 3px solid <?php echo $spotcolor; ?>;
}

#wrapper > header hgroup {
    background-color: <?php echo $spotcolor; ?>;
}

#wrapper > header nav {
    background: <?php echo bw_get_color_with_opacity($spotcolor, 0.5); ?>;
}

#wrapper > header nav .current-menu-item > a {
    color: <?php echo $spotcolor; ?>;
}

#wrapper > header .menu {
    border-bottom-color: <?php echo bw_get_color_with_opacity($spotcolor, 0.75); ?>;
}

#wrapper > footer {
    background: <?php echo $spotcolor; ?>;
}

h1.entry-title, .entry-title h1 {
    color: <?php echo $spotcolor; ?>;
}

.staff h1 {
    color: <?php echo $spotcolor; ?>;
}

#home-thisissue article {
    background: <?php echo $spotcolor; ?>;
}

#home-thisissue .tray .selected {
    border-color: <?php echo $spotcolor; ?>;
    color: <?php echo $spotcolor; ?>;
    background: <?php echo bw_get_color_with_opacity($spotcolor, 0.25); ?>;
}