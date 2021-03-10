<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

?>
<style>
.container {
	width: 100vw;
	height: 100vh;
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	background-color: #fff;
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; 
	font-size: 24px; 
	font-style: normal; 
	font-variant: normal;
}
.heading {
	text-align: center;
}
.button {
  background-color: black;
  border: 2px solid black;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
	margin-bottom: 20px;
}
.button:hover {
	background-color: white;
	color: black;
}
.site-header,
.widget-area,
.site-footer {
	display : none !important;
}
.site-main {
	padding: 0 !important;
}
</style>
<?php get_header(); ?>
<section class="container">
	<?php if ( have_rows( 'redirection', 'option' ) ) : ?>
		<?php while ( have_rows( 'redirection', 'option' ) ) : the_row(); ?>
			<?php
			$page_heading = get_sub_field( 'page_heading' );
			if ($page_heading) {
			?>		
				<h1 class="heading"><?php echo $page_heading; ?></h1>
			<?php } ?>
			<?php $visit_site_button = get_sub_field( 'visit_site_button' ); ?>
			<?php if ( $visit_site_button ) { ?>
				<a href="<?php echo $visit_site_button['url']; ?>" target="<?php echo $visit_site_button['target']; ?>" class="button"><?php echo $visit_site_button['title']; ?></a>
			<?php } ?>
		<?php endwhile; ?>
	<?php endif; ?>
	<a href="/admin" class="button">Log In</a>
</section>
<?php get_footer(); ?>
