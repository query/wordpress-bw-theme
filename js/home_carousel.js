jQuery(document).ready(function() {
    var tray = jQuery('<ul class="tray" />');
    
    jQuery('#home-thisissue .carousel').jcarousel({
        scroll: 1, auto: 7, wrap: 'circular',
        buttonPrevHTML: null, buttonNextHTML: null,
        initCallback: function (carousel, state) {
            // Create number buttons for navigation within the carousel.
            var i, button;
            
            for (i = 1; i <= carousel.size(); i++) {
                button = jQuery('<li>' + i + '</li>');
                button.bind('click', function () {
                    carousel.scroll(jQuery.jcarousel.intval(jQuery(this).text()));
                });
                tray.append(button);
            }
            
            jQuery('#home-thisissue').append(tray);
        },
        itemFirstInCallback: function (carousel, li, index, state) {
            tray.children().removeClass('selected');
            jQuery(tray.children()[(index - 1) % carousel.size()]).addClass('selected');
        }
    });
});