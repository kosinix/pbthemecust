/**
 * Vimeo page video bg
 * 
 * https://github.com/vimeo/player.js
 * https://github.com/vimeo/player.js#embed-options
 */

jQuery(document).ready(function($){

    var element = $('#pbtheme_page_bg_vimeo');

    var options = {
        id: pbtheme_vimeo_player_data.id,
        height: false,
        width: false,
        loop: pbtheme_vimeo_player_data.loop === 'loop' ? true : false,
        autoplay: true
    };

    var player = new Vimeo.Player(element, options);

    if ( pbtheme_vimeo_player_data.mute === 'mute' ) {
        player.setVolume(0);
    }
    
    player.on('pause', function() {
        player.play();
    });
    
});