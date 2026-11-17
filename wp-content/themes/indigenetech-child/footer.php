<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *

 */

?>  
    <footer id="colophon" class="site-footer clear">
		<?php if ( is_active_sidebar( 'footer-1' ) ):
			$footerarea =  count( wp_get_sidebars_widgets()['footer-1'] ); ?>
				<div class="footer-area">
					<div class="container">
						<div class="footer-inner footer-<?php echo intval( $footerarea ); ?>">
							<?php dynamic_sidebar( 'footer-1' ); ?>
						</div> 
					</div>
				</div>
		<?php endif; ?>

    </footer><!-- #colophon -->

    <div class="sub-footer">
        <div class="container">
			<div class="copyright-wrap">
				<div class="copyright"></div>
				Copyright &#169; 2024 <a href="<?php echo home_url(); ?>">IndigeneTech</a>  All Rights Reserved
			</div>
        </div>
    </div>

</div><!-- #page -->


<a href="#" id="back-to-top" class="progress" data-tooltip="Back To Top">
    <div class="arrow-top"></div>
    <div class="arrow-top-line"></div>
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="0 0 100 100" preserveAspectRatio="xMinYMin meet"> <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"/></svg> 
</a>

<?php //wp_footer(); ?>

</body>
</html>
