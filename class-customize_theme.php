<?php
/**
 * Tämän luokan avulla Sosiaalifoorumi-teemaa voi muunnella melko vapaasti.
 * Koodi on muokattu melko suoraan WP:n omista ohjeista: https://codex.wordpress.org/Theme_Customization_API
 * 
 * @link http://heikki.ketoharju.info
 * @since Sosiaalifoorumi 2013 1.1
 */
class sosiaalifoorumi_Customize {
   /**
    * This hooks into 'customize_register' (available as of WP 3.4) and allows
    * you to add new sections and controls to the Theme Customize screen.
    * 
    * Note: To enable instant preview, we have to actually write a bit of custom
    * javascript. See live_preview() for more.
    *  
    * @see add_action('customize_register',$func)
    * @param \WP_Customize_Manager $wp_customize
    * @link http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
    * @since MyTheme 1.0
    */
   public static function register ( $wp_customize ) {
      
      //Register new settings to the WP database.
      $wp_customize->add_setting( 'menu_highlight_color',
         array(
            'default' => '#FFD602', //Default setting/value to save
            'type' => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
            'transport' => 'refresh', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
         ) 
      );
      $wp_customize->add_setting( 'highlight_color',
         array(
            'default' => '#04f', //Default setting/value to save
            'type' => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
            'transport' => 'refresh', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
         ) 
      );   
      
      $wp_customize->add_setting( 'menu_bg_color',
         array(
            'default' => '#dedede', //Default setting/value to save
            'type' => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
            'transport' => 'refresh', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
         ) 
      );   
            
      //Finally, we define the control itself (which links a setting to a section and renders the HTML controls)...
      $wp_customize->add_control( new WP_Customize_Color_Control( //Instantiate the color control class
         $wp_customize, //Pass the $wp_customize object (required)
         'sosiaalifoorumi_highlight_color', //Set a unique ID for the control
         array(
            'label' => __( 'Linkkien väri', 'sosiaalifoorumi-2013' ), //Admin-visible name of the control
            'section' => 'colors', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
            'settings' => 'highlight_color', //Which setting to load and manipulate (serialized is okay)
            'priority' => 10, //Determines the order this control appears in for the specified section
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Color_Control(
         $wp_customize, 
         'sosiaalifoorumi_menu_bg_color',
         array(
            'label' => __( 'Valikon taustaväri', 'sosiaalifoorumi-2013' ),
            'section' => 'colors',
            'settings' => 'menu_bg_color',
            'priority' => 13,
         ) 
      ) );
      
      $wp_customize->add_control( new WP_Customize_Color_Control(
         $wp_customize, 
         'sosiaalifoorumi_menu_highlight_color',
         array(
            'label' => __( 'Valikon ja linkkien korostusväri', 'sosiaalifoorumi-2013' ),
            'section' => 'colors',
            'settings' => 'menu_highlight_color',
            'priority' => 15,
         ) 
      ) );

   }

   /**
    * This will output the custom WordPress settings to the live theme's WP head.
    * 
    * Used by hook: 'wp_head'
    * 
    * @see add_action('wp_head',$func)
    * @since MyTheme 1.0
    */
   public static function header_output() {
      ?>
      <!--Customizer CSS--> 
      <style type="text/css">
           <?php self::generate_css('#access li:hover > a', 'background', 'menu_highlight_color'); ?>
           <?php self::generate_css('#access ul ul li:hover > a', 'background', 'menu_highlight_color'); ?>
           <?php self::generate_css('a:link', 'color', 'highlight_color'); ?>
           <?php self::generate_css('a:hover', 'color', 'menu_highlight_color'); ?>
           <?php self::generate_css('#access', 'background', 'menu_bg_color'); ?>
      </style> 
      <!--/Customizer CSS-->
      <?php
   }
   
   /**
    * This outputs the javascript needed to automate the live settings preview.
    * Also keep in mind that this function isn't necessary unless your settings 
    * are using 'transport'=>'postMessage' instead of the default 'transport'
    * => 'refresh'
    * 
    * Used by hook: 'customize_preview_init'
    * 
    * @see add_action('customize_preview_init',$func)
    * @since Sosiaalifoorumi 2013 1.1
    */
//    public static function live_preview() {
//       wp_enqueue_script( 
//            'sosiaalifoorumi-themecustomizer', // Give the script a unique ID
//            get_template_directory_uri() . '/js/theme-customizer.js', // Define the path to the JS file
//            array(  'jquery', 'customize-preview' ), // Define dependencies
//            true // Specify whether to put in footer (leave this true)
//       );
//    }

    /**
     * This will generate a line of CSS for use in header output. If the setting
     * ($mod_name) has no defined value, the CSS will not be output.
     * 
     * @uses get_theme_mod()
     * @param string $selector CSS selector
     * @param string $style The name of the CSS *property* to modify
     * @param string $mod_name The name of the 'theme_mod' option to fetch
     * @param string $prefix Optional. Anything that needs to be output before the CSS property
     * @param string $postfix Optional. Anything that needs to be output after the CSS property
     * @param bool $echo Optional. Whether to print directly to the page (default: true).
     * @return string Returns a single line of CSS with selectors and a property.
     * @since Sosiaalifoorumi 2013 1.1
     */
    public static function generate_css( $selector, $style, $mod_name, $prefix='', $postfix='', $echo=true ) {
      $return = '';
      $mod = get_theme_mod($mod_name);
      if ( ! empty( $mod ) ) {
         $return = sprintf('%s { %s:%s; }',
            $selector,
            $style,
            $prefix.$mod.$postfix
         );
         if ( $echo ) {
            echo $return;
         }
      }
      return $return;
    }
}

// Setup the Theme Customizer settings and controls...
add_action( 'customize_register' , array( 'sosiaalifoorumi_Customize' , 'register' ) );

// Output custom CSS to live site
add_action( 'wp_head' , array( 'sosiaalifoorumi_Customize' , 'header_output' ) );

// // Enqueue live preview javascript in Theme Customizer admin screen
// add_action( 'customize_preview_init' , array( 'sosiaalifoorumi_Customize' , 'live_preview' ) );
