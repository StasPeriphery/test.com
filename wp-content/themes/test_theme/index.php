<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package test_theme
 */

$count_show = 5;

$posts = get_posts([
    'numberposts' => -1,
    'orderby'=> 'content',
    'order'=>'ASC',

]);


$sort_length = get_option('sort_post');

$posts = sort_post_for_index_page($posts);


get_header();

?>

    <button class="sort-down">a-z</button>
    <button class="sort-up">z-a</button>

<?php  if(isset($sort_length)) {
    ?>
    <button class="sort-length-down">1-∞</button>
    <button class="sort-length-up">∞-1</button>
    <?php
}
?>
    <div class="wrapper">

        <?php

        foreach ($posts as $post) {

            if ($count_show-- == 0)
                break;

            $post_content = $post->post_content;
            $post_title = $post->post_title;
            $id = $post->ID;
            $guid = $post->guid;
            $thumb = get_the_post_thumbnail_url($id, 'thumbnail');
            $author_id = $post->post_author;
            $author_name = get_the_author_meta('user_nicename', $author_id);
            $date = get_the_date('j F Y H:i', $id);

            ?>

            <div class="wrapper-post">
                <div class="wrapper-img">
                    <img src="<?= $thumb; ?>?size=small" class="post__img" alt="">
                </div>
                <div class="wrapper-context">
                    <a class="title-link" href="<?= $guid ?>" >
                        <?= $post_title; ?>
                    </a>
                    <div class="wrapper-context-date">Date
                        <?php echo $date; ?>
                    </div>
                    <div class="wrapper-context-author">
                        Author <?= $author_name ?>
                    </div>
                </div>
            </div>

            <?php
        }
        ?>
    </div>

    <button class="load_more">LOAD MORE</button>
    <main id="primary" class="site-main">

        <?php
        //		if ( have_posts() ) :
        //			if ( is_home() && ! is_front_page() ) :
        //				?>
        <!--    				<header>-->
        <!--    					<h1 class="page-title screen-reader-text">-->
        <?php //single_post_title(); ?><!--</h1>-->
        <!--    				</header>-->
        <!--    				--><?php
        //			endif;
        //			while ( have_posts() ) :
        //				the_post();
        //				get_template_part( 'template-parts/content', get_post_type() );
        //			endwhile;
        //
        //			the_posts_navigation();
        //		else :
        //			get_template_part( 'template-parts/content', 'none' );
        //
        //		endif;
        ?>

    </main><!-- #main -->

<?php
get_footer();
