<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package gsnbasic
 */

get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="post-inner-content">

				<section class="error-404 not-found">
					<header class="page-header">
						<h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'gsnbasic' ); ?></h1>
					</header><!-- .page-header -->

					<div class="page-content">
						<p><?php _e( 'It looks like nothing was found at this location.  Maybe try one of the suggestions below?', 'gsnbasic' ); ?></p>
						<ul>
							<li>Click your brower's Refresh button or try reconnecting.</li>
							<li>Check the spelling of the URL to make sure the address is correct (capitalization and punctuations are important) and then click your browser's Refresh button.</li>
							<li>Go to <a href="/">Home Page</a> or <a href="javascript:void(0)" onclick="window.history.back();">Back</a> to previous page.</li>
						</ul>
				</section><!-- .error-404 -->
			</div>
		</main><!-- #main -->
</div>

<?php get_footer(); ?>
