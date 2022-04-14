<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package test_theme
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php
        if (is_singular()) :
            the_title('<h1 class="entry-title">', '</h1>');
        else :
            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
        endif;

        if ('post' === get_post_type()) :
            ?>
            <div class="entry-meta">
                <?php
                test_theme_posted_on();
                test_theme_posted_by();
                ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php test_theme_post_thumbnail(); ?>

    <div class="entry-content">
        <?php

        $paragraph_insert = [1, 3, 8];


        $iterator = 0;

        $pos = 0;
        $content = get_the_content();
        $str_insert = '<img  src="http://test.com/wp-content/uploads/2022/04/104368428_gettyimages-543560762.jpg" >';
        $needle = '</p>';
        foreach ($paragraph_insert as $el) {

            while ($iterator !== $el) {
                $pos = strpos(get_the_content(), $needle, $pos + 1);
                $iterator++;
            }

            $content = substr_replace($content, $str_insert, $pos + strlen($needle), 0);
        }

        echo $content;


        wp_link_pages(
            array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'test_theme'),
                'after' => '</div>',
            )
        );
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <?php test_theme_entry_footer(); ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
