<?php

if ( defined( 'JETPACK__VERSION' ) ) :
    $jetpack_active_modules = get_option( 'jetpack_active_modules' );

	/**
	 * Improvement with Jetpack: auto-detect the XML sitemaps for the preload option
	 *
	 * @since 2.8
	 * @author Remy Perona
	 */
    if ( is_array( $jetpack_active_modules ) && in_array( 'sitemaps', $jetpack_active_modules ) ) {
        add_filter( 'rocket_first_install_options', '__rocket_add_jetpack_sitemap_option' );
        function __rocket_add_jetpack_sitemap_option( $options ) {
            $options['jetpack_xml_sitemap'] = 0;

            return $options;
        }

        add_filter( 'rocket_inputs_sanitize', '__rocket_jetpack_sitemap_option_sanitize' );
        function __rocket_jetpack_sitemap_option_sanitize( $inputs ) {
            $inputs['jetpack_xml_sitemap'] = ! empty( $inputs['jetpack_xml_sitemap'] ) ? 1 : 0;

            return $inputs;
        }

        add_filter( 'rocket_sitemap_preload_list', '__rocket_add_jetpack_sitemap' );
        function __rocket_add_jetpack_sitemap( $sitemaps ) {
            if ( get_rocket_option( 'jetpack_xml_sitemap', false ) ) {
                $sitemaps['jetpack'] = jetpack_sitemap_uri();
            }

            return $sitemaps;
        }

        add_filter( 'rocket_sitemap_preload_options', '__rocket_sitemap_preload_jetpack_option' );
        function __rocket_sitemap_preload_jetpack_option( $options ) {
            $options[] = array(
                'parent'       => 'sitemap_preload',
                'type'         => 'checkbox',
	            'label'        => __('Jetpack XML Sitemaps', 'rocket' ),
	            'label_for'    => 'jetpack_xml_sitemap',
	            'label_screen' => sprintf( __( 'Preload the sitemap from the %s plugin', 'rocket' ), 'Jetpack' ),
	            'default'      => 0,
            );
            $options[] = array(
                'parent'        => 'sitemap_preload',
                'type'			=> 'helper_description',
                'name'			=> 'jetpack_xml_sitemap_desc',
                'description'   => sprintf( __( 'We automatically detected the sitemap generated by the %s plugin. You can check the option to preload it.', 'rocket' ), 'Jetpack' )
            );

            return $options;
        }
    }
endif;