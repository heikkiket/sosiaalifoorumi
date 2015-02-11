<?php

//Otsakekuvan korkeus on 180 pikseliä, mutta Twenty Tenissä asetus on eri. Tällä homma hoituu!
function sosiaalifoorumi_header_image_height($height) {
	$height="180";
	return $height;
}
add_filter('twentyten_header_image_height','sosiaalifoorumi_header_image_height');

//Poistetaan Twenty Tenin otsakekuvat, ja asennetaan tilalle oma:
function sosiaalifoorumi_remove_twenty_ten_headers(){
    unregister_default_headers( array(
        'berries',
        'cherryblossom',
        'concave',
        'fern',
        'forestfloor',
        'inkwell',
        'path' ,
        'sunset')
    );
    
    register_default_headers( array(
		'sosiaalifoorumi' => array(
			'url' => '%2$s/images/sosiaalifoorumi-header.png',
			'thumbnail_url' => '%2$s/images/sosiaalifoorumi-header-thumbnail.png',
			/* translators: header image description */
			'description' => __( 'Sosiaalifoorumi', 'sosiaalifoorumi-2013' )
		)
	) );
}
add_action( 'after_setup_theme', 'sosiaalifoorumi_remove_twenty_ten_headers', 11 );

function sosiaalifoorumi_theme_options() {
	remove_theme_support('custom-background');
}
add_action( 'after_setup_theme', 'sosiaalifoorumi_theme_options');

function sosiaalifoorumi_head() {
	echo '<meta name="viewport" content="width=device-width,initial-scale=" />';
	echo "\n";
	$menuScript = <<<EOD
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>

<script type="text/javascript">
jQuery(document).ready(function($){

	/* Valikon ikoni */
	$('#access').prepend('<div id="menu-icon"><a href="#">Näytä valikko</a></div>');
	
	/* Valikon kytkin */
	$("#menu-icon").on("click", function(){
		$("#menu-paavalikko").slideToggle();
		$(this).toggleClass("active");
	});

});
</script>
EOD;
	echo $menuScript . "\n";
}
add_action( 'wp_head', 'sosiaalifoorumi_head' );

?>
