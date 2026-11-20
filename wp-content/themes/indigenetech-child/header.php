<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package appzend
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="google-site-verification" content="dfYJXDlkLcmd_hEw8fJo20REUiMRYSm-Ukl5Thq97nc" />
	<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-FXNV5T8F24"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-FXNV5T8F24');
</script>
	<link rel="profile" href="https://gmpg.org/xfn/11">
	

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<div id="page" class="site">

	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'appzend' ); ?></a>

	<header id="masthead" class="site-header p-0 <?php if(get_theme_mod('appzend_menu_relative', 'enable') == 'enable') echo 'relative-menu'; ?> <?php if(get_theme_mod('appzend_menu_sticky', 'disable') == 'enable') echo 'sticky-menu'; ?>">
		<div class="nav-classic">
			<div class="container">
				<div class="header-middle-inner p-0 mob-px-20">
					<div class="site-branding">
						<?php the_custom_logo(); ?>
						<?php if ( ! has_custom_logo() ) : // the logo already links home; don't repeat the site name under it ?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
								<?php bloginfo( 'name' ); ?>
							</a>
						<?php endif; ?>
						<?php 
							$appzend_description = get_bloginfo( 'description', 'display' );
							if ( $appzend_description || is_customize_preview() ) : ?>
								<p class="site-description"><?php echo $appzend_description; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?></p>
						<?php endif; ?>
					</div>

					<?php
						$alignment = get_theme_mod('appzend_menu_alignment'); 
						$alignment_class = get_zppzend_alignment_class($alignment);
					?>
					<div class="nav-menu flex-align <?php echo esc_attr($alignment_class); ?>">
						<nav class="box-header-nav main-menu-wapper" aria-label="<?php esc_attr_e( 'Main Menu', 'appzend' ); ?>" role="navigation">
							<?php
								wp_nav_menu( array(
									'theme_location' => 'menu-1',
									'menu_class'     => 'main-menu',
									'walker'         => new Menu_Icon_Walker(),
								) );
							?>
						</nav>
						
						<?php if(get_theme_mod('appzend_button_enable', 'disable') == 'enable'): ?>
						<div class="extralmenu-item">
							<div class="right-btn <?php echo esc_attr(get_theme_mod('appzend-button-class')); ?>">
								<a class="btn btn-primary" <?php if(get_theme_mod('appzend-button-open-link-new-tab')): echo 'target="_blank"'; endif; ?> href="<?php echo esc_url(get_theme_mod('appzend-button-url', "#")); ?>"><?php echo esc_html(get_theme_mod('appzend-button-text', 'Shop Now')); ?></a>
							</div>
						</div>
						<?php endif; ?>
						<?php do_action('appzend_menu_toggle'); ?>
					</div>
				</div>
			</div><!-- .container end -->
		</div>
	</header><!-- #masthead -->

	
