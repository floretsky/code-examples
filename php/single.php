<?php get_header(); ?>
<?php 
$page_id = get_the_ID();
$ids[] = $page_id;
function bd_nice_number($n) {
        // first strip any formatting;
        $n = (0+str_replace(",","",$n));
       
        // is this a number?
        if(!is_numeric($n)) return false;
       
        // now filter it;
        if($n>1000000000000) return round(($n/1000000000000),1).' трлн';
        else if($n>1000000000) return round(($n/1000000000),1).' млрд';
        else if($n>1000000) return round(($n/1000000),1).' млн';
        else if($n>1000) return round(($n/1000),1).' тыс';
       
        return number_format($n);
    }

$posts = get_posts( array(
	'numberposts' => -1,
	'orderby' => 'menu_order',
	'order' => 'ASC',
	'post_type'   => 'post',
	'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
) );

if ($posts) {
$categories = get_categories([
	'hide_empty' => true,
	'orderby'      => 'id',
	'order'        => 'ASC',
]); 
}

$post_cat = get_the_tags($post_id);
$radio_field = get_field('image_form');

global $wpdb;
$prefix = $wpdb->prefix;
$tags = $wpdb->get_results("SELECT DISTINCT pm.meta_value FROM `".$prefix."postmeta` pm JOIN ".$prefix."posts p on p.ID = pm.post_id WHERE `meta_key` LIKE 'article_tags_%' and length(pm.meta_value) > 0 and p.post_status = 'publish' and p.ID = $page_id order by pm.meta_value ASC;");

?>
<article class="unique-post" data-href="<?php the_permalink(); ?>" data-title="<?php the_title(); ?>" data-id="<?php echo $post_id; ?>">
<div class="main-area container regular-page_container">
	<?php if ($post_cat[0]->slug !== 'quizes'): ?>
	<div class="top-block-area container">
	<div class="tags-container">
			<div class="tags-container-inner-abs">
				<a href="/posts-feed" class="tag-name tag-name-header active" data-filter="*">
					<?php echo __('Все статьи'); ?>
				</a>
				<?php foreach( $categories as $category ) { 
				if ($category->cat_ID == 1) { continue; }?>
				<a href="/posts-feed/?tag=<?php echo $category->slug; ?>" class="tag-name tag-name-header" data-filter=".<?php echo $category->slug; ?>">
					<?php echo $category->name; ?>
				</a>
				<?php } ?>
		</div>
	</div>
		<div class="top-block-components-wrap row">
			<?php
			$alt_title = get_field('alt_title');
			?>
			<div class="top-block-info col-12 col-md-7 col-lg-7">
				<div class="top-block-cat-views">
					<div class="top-block-cat-views_cat">
					<?php
					if ($post_cat) {
						echo $post_cat[0]->name;
					}
					?>
					</div>
					<div class="top-block-cat-views-and-comm">
						<span class="top-block-cat-views_date">
							<?php echo get_the_date('j F Y'); ?>
						</span>
						<span class="top-block-cat-views_views">
						<svg width="14" height="9" viewBox="0 0 14 9" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M7.00273 9C11.1586 9 14 5.56638 14 4.5C14 3.42804 11.1476 -4.29506e-09 7.00273 0C2.9069 4.24419e-09 1.08507e-09 3.42804 0 4.5C-1.07942e-09 5.56638 2.9069 9 7.00273 9ZM7.00273 7.42556C5.41021 7.42556 4.14492 6.09119 4.13401 4.5C4.12855 2.86414 5.41021 1.58002 7.00273 1.58002C8.58979 1.58002 9.87145 2.86973 9.87145 4.5C9.87145 6.09119 8.58979 7.42556 7.00273 7.42556ZM7.00818 5.56638C7.58083 5.56638 8.05532 5.08065 8.05532 4.5C8.05532 3.91377 7.58083 3.42804 7.00818 3.42804C6.42462 3.42804 5.95014 3.91377 5.95014 4.5C5.95014 5.08065 6.42462 5.56638 7.00818 5.56638Z" fill="#5C0E9C"/>
</svg>
						<?php
						echo bd_nice_number(pvc_get_post_views( $post_id ));
						?>
						</span>
						<?php if (comments_open()) : ?>
						<span class="top-block-cat-views_comments">
						<svg width="12" height="10" viewBox="0 0 12 10" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M0 2C0 0.895431 0.895431 0 2 0H10C11.1046 0 12 0.895431 12 2V6C12 7.10457 11.1046 8 10 8H9V10L6 8H2C0.89543 8 0 7.10457 0 6V2Z" fill="#5C0E9C"/>
</svg>
						<?php
						echo get_comments_number( $post_id );
						?>
						</span>
						<?php endif; ?>
					</div>
				</div>
				<div class="top-block-title">
					<h1>
						<?php echo $alt_title ? $alt_title : get_the_title(); ?>
					</h1>
				</div>
				<div class="top-block-description">
					<?php the_excerpt(); ?>
				</div>
				<div class="top-block-cat-views-and-comm d-sm-none">
						<span class="top-block-cat-views_views">
						<svg width="14" height="9" viewBox="0 0 14 9" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M7.00273 9C11.1586 9 14 5.56638 14 4.5C14 3.42804 11.1476 -4.29506e-09 7.00273 0C2.9069 4.24419e-09 1.08507e-09 3.42804 0 4.5C-1.07942e-09 5.56638 2.9069 9 7.00273 9ZM7.00273 7.42556C5.41021 7.42556 4.14492 6.09119 4.13401 4.5C4.12855 2.86414 5.41021 1.58002 7.00273 1.58002C8.58979 1.58002 9.87145 2.86973 9.87145 4.5C9.87145 6.09119 8.58979 7.42556 7.00273 7.42556ZM7.00818 5.56638C7.58083 5.56638 8.05532 5.08065 8.05532 4.5C8.05532 3.91377 7.58083 3.42804 7.00818 3.42804C6.42462 3.42804 5.95014 3.91377 5.95014 4.5C5.95014 5.08065 6.42462 5.56638 7.00818 5.56638Z" fill="#5C0E9C"/>
</svg>
						<?php
						echo bd_nice_number(pvc_get_post_views( $post_id ));
						?>
						</span>
						<span class="top-block-cat-views_comments">
						<svg width="12" height="10" viewBox="0 0 12 10" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M0 2C0 0.895431 0.895431 0 2 0H10C11.1046 0 12 0.895431 12 2V6C12 7.10457 11.1046 8 10 8H9V10L6 8H2C0.89543 8 0 7.10457 0 6V2Z" fill="#5C0E9C"/>
</svg>
						<?php
						echo get_comments_number( $post_id );
						?>
						</span>
					</div>
				<div class="add-to-any-top">
					<?php echo do_shortcode('[addtoany]'); ?>
				</div>
				<div class="single-page-author-and-artist row">
					<?php if (get_field('post_author', $post_id)) : ?>
					<div class="single-page-author col-6 col-md-6">
						<b><?php echo __('Текст:'); ?></b>
						<?php $author_link = get_field('post_author_link', $post_id); 
						if ($author_link) : ?>
						<a href="<?php echo $author_link; ?>" rel="noopener" target="_blank">
							<span><?php echo get_field('post_author', $post_id); ?></span>
						</a>
						<?php else : ?>
						<span><?php echo get_field('post_author', $post_id); ?></span>
						<?php endif; ?>
					</div>
					<?php endif; ?>
					<?php if (get_field('post_picture_artist', $post_id)) : ?>
					<div class="single-page-artist col-6 col-md-6">
						<b><?php echo __('Иллюстрация:'); ?></b>
						<?php $illustrator_link = get_field('post_picture_artist_link'); 
						if ($illustrator_link) : ?>
						<a href="<?php echo $illustrator_link; ?>" rel="noopener" target="_blank">
							<span><?php echo get_field('post_picture_artist', $post_id); ?></span>
						</a>
						<?php else : ?>
						<span><?php echo get_field('post_picture_artist', $post_id); ?></span>
						<?php endif ?>
					</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="top-block-image_container <?php echo $radio_field; ?> col-12 col-md-5 col-lg-5">
				<div class="top-block-image <?php echo $radio_field; ?>">
					<div class="top-block-image-itself" style="background: url(<?php echo wp_get_attachment_image_url( get_field('image', $post_id), 'large' ); ?>) center/cover no-repeat">
					<img alt="<?php echo $alt_title ? $alt_title : get_the_title(); ?>" class="visibly-hidden" src="<?php echo wp_get_attachment_image_url( get_field('image', $post_id), 'large' ); ?>"/></div>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>
<?php 
$featured_posts = get_field('material_link_post');
if ($featured_posts) : 
foreach( $featured_posts as $post ): 
setup_postdata($post); ?>
	<div class="read-article-container display-none" data-ce-tag="container" data-container-name="material-link-absolute">
					<a href="<?php the_permalink(); ?>" class="read-img-link">
							<img src="<?php echo wp_get_attachment_image_url( get_field('image', $post->ID), 'small' ); ?>" data-ce-tag="image-figure">
					</a>
					<p class="read-article-read">ЧИТАЙТЕ ТАКЖЕ</p>
					<a href="<?php the_permalink(); ?>" class="read-article-header"><?php echo the_title(); ?></a>
					<div class="read-article-sub"><?php echo get_the_excerpt(); ?></div>
	</div>
<?php
endforeach;
wp_reset_postdata();
endif; ?>
<div class="single-page-container <?= $radio_field ?>">
	<?php the_content(); ?>
</div>
<? if ($tags): ?>
    <div class="container-fluid container-tags">
        <div class="tags-overlay tags-overlay-single" id="tags-overlay" style="    margin-top: 30px;max-width: 730px;justify-content: initial;padding-left: 27px;">
            <? $count = 0; $c = 0; foreach ($tags as $tag): $c++; if ($c == 5){ $c = 1; } $count++; ?>
                <a class="tags-item" data-type="<?=$c?>" href="/tags/?tag=<?=$tag->meta_value?>">
                    <div class="tags-text-wrapper"><div class="tags-text"><?=$tag->meta_value?></div></div>
                    <svg class="tags-icon"></svg>
                </a>
            <? endforeach; ?>
            <? if ($count == 2): ?>
                <a class="tags-item mobile-hidden-tag" data-type="3" style="opacity: 0;"><div class="tags-text-wrapper"><div class="tags-text">12345</div></div><svg class="tags-icon"></svg></a>
            <? elseif ($count == 1): ?>
                <a class="tags-item mobile-hidden-tag" data-type="3" style="opacity: 0;"><div class="tags-text-wrapper"><div class="tags-text">12345</div></div><svg class="tags-icon"></svg></a>
                <a class="tags-item mobile-hidden-tag" data-type="3" style="opacity: 0;"><div class="tags-text-wrapper"><div class="tags-text">12345</div></div><svg class="tags-icon"></svg></a>
            <? endif; ?>
        </div>
    </div>
<? endif; ?>

<?php if( have_rows('sources',$post_id) ): ?>
<div class="container addtoany_container sources_container">
	<div class="accordion-container">
		<div class="ac">
		<h3 class="sources-header ac-header" id="sources-id">
			<button class="ac-trigger"><?php echo __('Источники'); ?></button>
		</h3>
			<div class="ac-panel">
				<ol>
			<?php while( have_rows('sources') ) : the_row(); ?>
					<li>
						<span>
						<?php echo get_sub_field('sources_text') ?>
						</span>
						<a href="<?php echo get_sub_field('sources_link') ?>" rel="noopener" target="_blank">
							<?php echo parse_url(get_sub_field('sources_link'),PHP_URL_HOST); ?>
						</a>
					</li>
			<?php endwhile; ?>
				</ol>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
<div class="single-page-author-and-artist d-sm-none row">
	<?php if (get_field('post_author', $post_id)) : ?>
	<div class="single-page-author col-12">
		<b><?php echo __('Текст:'); ?></b><?php 
		if ($author_link) : ?>
		<a href="<?php echo $author_link; ?>" rel="noopener" target="_blank">
			<span><?php echo get_field('post_author', $post_id); ?></span>
		</a>
		<?php else : ?>
		<span><?php echo get_field('post_author', $post_id); ?></span>
		<?php endif; ?>
	</div>
	<?php endif; ?>
	<?php if (get_field('post_picture_artist', $post_id)) : ?>
	<div class="single-page-artist col-12">
		<b><?php echo __('Иллюстрация:'); ?></b>
		<?php $illustrator_link = get_field('post_picture_artist_link');
		if ($illustrator_link) : ?>
		<a href="<?php echo $illustrator_link; ?>" rel="noopener" target="_blank">
			<span><?php echo get_field('post_picture_artist', $post_id); ?></span>
		</a>
		<?php else : ?>
		<span><?php echo get_field('post_picture_artist', $post_id); ?></span>
		<?php endif ?>
	</div>
	<?php endif; ?>
</div>
<div class="container addtoany_container">
	<?php echo do_shortcode('[addtoany]'); ?>
</div>
	<?php if (comments_open()) : ?>
<div class="container addtoany_container">
<?php
echo do_shortcode("[wpdiscuz_comments]");
?>
</div>
<?php endif; ?>
</article>
<?php if(get_field('unpopular_pages',get_the_ID())) { 
	$unpopular_ids = get_field('unpopular_pages',get_the_ID());
	$ids[] = implode(',',$unpopular_ids);
} else {
	$popularpost = new WP_Query( array( 'posts_per_page' => 2, 'orderby' => 'post_views', 'post_status'     => 'publish' , 'order' => 'ASC', 'post__not_in' => array( get_the_ID() ) ) );
	$unpopular_ids = wp_list_pluck( $popularpost->posts, 'ID' );
} ?>
<div id="after-scroll">
</div>
<div class="container">
	<div class="subscribe-wrap">
		<div class="subscribe row">
			<div class="subscribe-icon-and-text col-12 col-md-5 col-lg-7"><svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M1.37844 13.7838C1.2824 13.7838 1.18876 13.7537 1.1107 13.6977C1.03264 13.6418 0.974085 13.5628 0.943256 13.4718C0.912428 13.3808 0.910881 13.2825 0.938833 13.1906C0.966785 13.0987 1.02283 13.0179 1.09909 12.9595L6.6126 8.74122C6.7096 8.66983 6.83074 8.63938 6.94996 8.65642C7.06918 8.67346 7.17694 8.73662 7.25006 8.83232C7.32317 8.92802 7.35579 9.0486 7.34089 9.1681C7.32599 9.28761 7.26476 9.39649 7.17039 9.4713L1.65687 13.6891C1.57712 13.7507 1.47918 13.784 1.37844 13.7838Z" fill="black"/>
			<path d="M32.6216 33.0812H1.3784C1.25655 33.0812 1.13968 33.0328 1.05352 32.9466C0.967352 32.8604 0.918945 32.7436 0.918945 32.6217V13.3244C0.918945 13.2026 0.967352 13.0857 1.05352 12.9995C1.13968 12.9134 1.25655 12.865 1.3784 12.865C1.50026 12.865 1.61713 12.9134 1.70329 12.9995C1.78946 13.0857 1.83786 13.2026 1.83786 13.3244V32.1623H32.1622V13.3244C32.1622 13.2026 32.2106 13.0857 32.2968 12.9995C32.3829 12.9134 32.4998 12.865 32.6216 12.865C32.7435 12.865 32.8604 12.9134 32.9465 12.9995C33.0327 13.0857 33.0811 13.2026 33.0811 13.3244V32.6217C33.0811 32.7436 33.0327 32.8604 32.9465 32.9466C32.8604 33.0328 32.7435 33.0812 32.6216 33.0812Z" fill="black"/>
			<path d="M17 24.7236C15.3857 24.7257 13.821 24.1657 12.5745 23.1398L1.08801 13.6791C0.993936 13.6016 0.934503 13.4899 0.922785 13.3686C0.911066 13.2472 0.948022 13.1262 1.02552 13.0322C1.10302 12.9381 1.21472 12.8787 1.33604 12.8669C1.45736 12.8552 1.57837 12.8922 1.67244 12.9697L13.1589 22.4304C14.2415 23.3195 15.5991 23.8056 17 23.8056C18.4009 23.8056 19.7585 23.3195 20.8411 22.4304L32.3276 12.9697C32.4216 12.8922 32.5427 12.8552 32.664 12.8669C32.7853 12.8787 32.897 12.9381 32.9745 13.0322C33.052 13.1262 33.089 13.2472 33.0772 13.3686C33.0655 13.4899 33.0061 13.6016 32.912 13.6791L21.4255 23.1398C20.1791 24.1657 18.6143 24.7257 17 24.7236Z" fill="black"/>
			<path d="M32.6216 33.081C32.5152 33.081 32.412 33.044 32.3299 32.9763L20.6136 23.3276C20.5671 23.2893 20.5285 23.2421 20.5001 23.1888C20.4718 23.1355 20.4542 23.0772 20.4484 23.0171C20.4367 22.8958 20.4737 22.7748 20.5512 22.6807C20.6287 22.5866 20.7404 22.5272 20.8617 22.5155C20.983 22.5038 21.104 22.5407 21.1981 22.6182L32.9143 32.2669C32.9874 32.3271 33.0401 32.4084 33.0653 32.4997C33.0904 32.591 33.0867 32.6879 33.0546 32.777C33.0226 32.8661 32.9638 32.9432 32.8863 32.9976C32.8088 33.052 32.7163 33.0812 32.6216 33.081Z" fill="black"/>
			<path d="M1.37855 33.081C1.28384 33.0812 1.1914 33.052 1.11389 32.9976C1.03638 32.9432 0.977584 32.8661 0.945545 32.777C0.913506 32.6879 0.909793 32.591 0.934913 32.4997C0.960033 32.4084 1.01276 32.3271 1.08587 32.2669L12.8021 22.6182C12.8962 22.5407 13.0172 22.5038 13.1385 22.5155C13.2598 22.5272 13.3715 22.5866 13.449 22.6807C13.5265 22.7748 13.5635 22.8958 13.5517 23.0171C13.54 23.1384 13.4806 23.2501 13.3865 23.3276L1.67031 32.9763C1.58813 33.044 1.485 33.081 1.37855 33.081Z" fill="black"/>
			<path d="M22.2099 5.8213C22.1091 5.82121 22.0111 5.78795 21.931 5.72665L17.0001 1.95679L12.0692 5.72665C11.9724 5.80068 11.8501 5.83321 11.7293 5.8171C11.6085 5.80099 11.4991 5.73755 11.4251 5.64073C11.351 5.54392 11.3185 5.42166 11.3346 5.30086C11.3507 5.18005 11.4142 5.0706 11.511 4.99657L16.7212 1.01352C16.8013 0.952334 16.8993 0.919189 17.0001 0.919189C17.1009 0.919189 17.1989 0.952334 17.279 1.01352L22.4893 4.99657C22.5667 5.05462 22.6238 5.13561 22.6525 5.22802C22.6811 5.32043 22.6799 5.41952 22.6489 5.51119C22.618 5.60285 22.5589 5.6824 22.48 5.73849C22.4012 5.79459 22.3067 5.82437 22.2099 5.8236V5.8213Z" fill="black"/>
			<path d="M32.6215 13.7838C32.5208 13.784 32.4228 13.7507 32.3431 13.6891L26.8295 9.4713C26.7352 9.39649 26.6739 9.28761 26.659 9.1681C26.6441 9.0486 26.6768 8.92802 26.7499 8.83232C26.823 8.73662 26.9308 8.67346 27.05 8.65642C27.1692 8.63938 27.2903 8.66983 27.3873 8.74122L32.9008 12.9595C32.9771 13.0179 33.0331 13.0987 33.0611 13.1906C33.0891 13.2825 33.0875 13.3808 33.0567 13.4718C33.0258 13.5628 32.9673 13.6418 32.8892 13.6977C32.8112 13.7537 32.7175 13.7838 32.6215 13.7838Z" fill="black"/>
			<path d="M27.1081 18.3233C26.9862 18.3233 26.8693 18.2749 26.7832 18.1888C26.697 18.1026 26.6486 17.9857 26.6486 17.8639V5.82145H7.35129V17.8639C7.35129 17.9857 7.30288 18.1026 7.21672 18.1888C7.13055 18.2749 7.01369 18.3233 6.89183 18.3233C6.76998 18.3233 6.65311 18.2749 6.56695 18.1888C6.48078 18.1026 6.43237 17.9857 6.43237 17.8639V5.36199C6.43237 5.24013 6.48078 5.12326 6.56695 5.0371C6.65311 4.95093 6.76998 4.90253 6.89183 4.90253H27.1081C27.2299 4.90253 27.3468 4.95093 27.4329 5.0371C27.5191 5.12326 27.5675 5.24013 27.5675 5.36199V17.8639C27.5675 17.9857 27.5191 18.1026 27.4329 18.1888C27.3468 18.2749 27.2299 18.3233 27.1081 18.3233Z" fill="black"/>
			<path d="M23.3177 10.1081H10.6826C10.5607 10.1081 10.4439 10.0597 10.3577 9.97352C10.2716 9.88736 10.2231 9.77049 10.2231 9.64864C10.2231 9.52678 10.2716 9.40992 10.3577 9.32375C10.4439 9.23759 10.5607 9.18918 10.6826 9.18918H23.3177C23.4396 9.18918 23.5565 9.23759 23.6426 9.32375C23.7288 9.40992 23.7772 9.52678 23.7772 9.64864C23.7772 9.77049 23.7288 9.88736 23.6426 9.97352C23.5565 10.0597 23.4396 10.1081 23.3177 10.1081Z" fill="black"/>
			<path d="M23.3177 13.4391H10.6826C10.5607 13.4391 10.4439 13.3907 10.3577 13.3046C10.2716 13.2184 10.2231 13.1015 10.2231 12.9797C10.2231 12.8578 10.2716 12.741 10.3577 12.6548C10.4439 12.5686 10.5607 12.5202 10.6826 12.5202H23.3177C23.4396 12.5202 23.5565 12.5686 23.6426 12.6548C23.7288 12.741 23.7772 12.8578 23.7772 12.9797C23.7772 13.1015 23.7288 13.2184 23.6426 13.3046C23.5565 13.3907 23.4396 13.4391 23.3177 13.4391Z" fill="black"/>
			<path d="M23.3177 16.7703H10.6826C10.5607 16.7703 10.4439 16.7219 10.3577 16.6357C10.2716 16.5496 10.2231 16.4327 10.2231 16.3108C10.2231 16.189 10.2716 16.0721 10.3577 15.986C10.4439 15.8998 10.5607 15.8514 10.6826 15.8514H23.3177C23.4396 15.8514 23.5565 15.8998 23.6426 15.986C23.7288 16.0721 23.7772 16.189 23.7772 16.3108C23.7772 16.4327 23.7288 16.5496 23.6426 16.6357C23.5565 16.7219 23.4396 16.7703 23.3177 16.7703Z" fill="black"/>
			</svg>
			<span class="subscribe-text">Следите за нашими обновлениями!</span>
			</div>
			<div class="subscribe-form-iteself col-12 col-md-7 col-lg-5">
			<?php echo do_shortcode('[contact-form-7 id="6" title="Подписка на рассылку"]'); ?>
			</div>
		</div>
	</div>
</div>
<?php 
if (get_field('posts_select', 'option') == 'last') {
$posts = get_posts( array(
	'numberposts' => 4,
	'orderby' => 'menu_order',
	'order' => 'ASC',
	'post_type'   => 'post',
	'post_status' => 'publish',
	'suppress_filters' => true,
	'exclude' => implode(",", $ids),
) );
} else {
	$posts = get_field('posts_more','option');
}

if ($posts) : ?>
<div class="container-fluid read-more-posts">
	<div class="container">
		<div class="read-more-posts_text">
			<span>
				<?php echo __('Читайте также'); ?>
			</span>
		</div>
		<div id="posts-grid" class="posts-grid">
			<?php foreach( $posts as $post ){
				setup_postdata($post); 
				$post_id = get_the_ID(); 
				$post_cat = get_the_category($post_id);
			$posttags = get_the_tags($post_id); ?>
			<div class="grid-item col-6 col-sm-6 col-md-4 col-lg-3 <?php 
						if ($post_cat) {
						  foreach($post_cat as $cat) {
							if ($category->cat_ID == 1) { continue; }
							  echo $cat->slug . ' '; 
						  }
						} ?>">
				<a href="<?php echo get_permalink($post_id, false); ?>">
					<div class="grid-item_post-image <?php echo get_field('image_form', $post_id); ?>" style="background: url(<?php echo wp_get_attachment_image_url( get_field('image', $post_id), 'medium' ); ?>) center/100% no-repeat;">
						<img src="<?php echo wp_get_attachment_image_url( get_field('image', $post_id), 'medium' ); ?>" alt="" class="visibly-hidden">
					</div>
				</a>
					<div class="grid-item_text">
						<div class="grid-item_post-tag">
							<?php echo $posttags[0]->name; ?>
						</div>
						<div class="grid-item_post-title">
							<a href="<?php echo get_permalink($post_id, false); ?>">
								<span><?php the_title();?></span>
							</a>
						</div>
						<div class="grid-item_post-excerpt">
							<?php echo get_the_excerpt( $post_id ); ?>
						</div>
					</div>
				</div>
			<?php } 
			wp_reset_postdata(); ?>
		</div>
	</div>
</div>
<?php endif; ?>
<?php if (comments_open()) : ?>
<svg id="comments-icon" width="29" height="24" viewBox="0 0 29 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M21.75 18.7H21.25V19.2V23.0693L14.776 18.7831L14.6505 18.7H14.5H2C1.17157 18.7 0.5 18.0284 0.5 17.2V2C0.5 1.17157 1.17157 0.5 2 0.5H27C27.8284 0.5 28.5 1.17157 28.5 2V17.2C28.5 18.0284 27.8284 18.7 27 18.7H21.75Z" stroke="#5C0E9C"/>
</svg>
<?php endif; ?>
<?php if ($post_cat[0]->slug !== 'quizes' && !is_preview()): ?>
<script type="text/javascript">
 jQuery(document).ready(function($) {
	 if ($('.single-page-container').length) {
	 let doneTheStuff = false;
	 var element_position = $('.single-page-container').offset().top;
	putMaterialsIntoPlace();
	$(window).on('scroll', function() {
		if (!doneTheStuff) {
			var y_scroll_pos = window.pageYOffset;
			var scroll_pos_test = element_position;

			if(y_scroll_pos > scroll_pos_test) {
				loadArticle();
				doneTheStuff = true;
			}
		}
	});
	 
   function loadArticle(){
     //$('a#inifiniteLoader').show('fast');
	 const arrIds = [<?php echo implode(',',$unpopular_ids); ?>];
     $.ajax({
       url: "<?php echo admin_url(); ?>admin-ajax.php",
       type:'POST',
       data: "action=infinite_scroll&ids="+ arrIds.join(',') + '&loop_file=loop',
       success: function (html) {
         //$('li#inifiniteLoader').hide('1000');
         $("#after-scroll").append(html);
         putMaterialsIntoPlace();
       }
     });
     return false;
   }
	 
	 function putMaterialsIntoPlace(){
		 if (document.querySelector('.read-article-container') && document.querySelector('.read-also-container')) {
			$($(".read-article-container").get().reverse()).each(function(){
				const filling = $(this);
				$(".read-also-container").each(function(){
					if ($(this).is(':empty')) {
						filling.removeClass('display-none');
						filling.appendTo($(this));
					}
				})
			});
		}
		$('.pic-left-with-text_image-figure-container').each(function(){
			if ($(this).find('figcaption').length == 0) {
				$(this).css('background','url(' + $(this).find('img').attr('src') + ') center/cover no-repeat');
				$(this).find('img').css('display','none');
			} else {
				$(this).parent().css('margin-bottom','20px');
				$(this).css({'border-left':'0','border-right':'0'});
				$(this).children().css('display','block');
				$(this).find('img').css('border-radius','40px');
			}
		})
		 $('.read-more-posts .posts-grid').masonry({
		  columnWidth: '.grid-item',
		  itemSelector: '.grid-item'
		});
	 }
	 }
 });
</script>
<?php endif; ?>
<?php get_footer();?>