<?php /* Template Name: Home page */ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="<?php bloginfo('charset');?>">
<title><?php wp_title('|', true, 'right');?></title>
<link rel="stylesheet" href="<?php echo esc_url(get_stylesheet_uri()); ?>" type="text/css" />
<?php wp_head();?>
</head>
<body>
<?php the_content(); ?>
<?php
$args = array(
    'post_type' => 'course',
    'posts_per_page' => 9,
);
$the_query = new WP_Query($args);
if ($the_query->have_posts()):  ?>
<div class="container">
    <div class="main-page-courses row">
    <div class="filters col-12 col-lg-3">
        <div class="accordion" id="accordionCampus">
            <div class="accordion-item accordion-item-styled">
                <span class="accordion-header" id="headingCampus">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Filter by campus
                </button>
                </span>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionCampus">
                <div class="accordion-body">
                    <?php 
                        $campus_terms = get_terms([
                            'taxonomy' => 'campuses',
                            'hide_empty' => false,
                        ]);

                        if ($campus_terms){ ?>
                            <ul class="campuses-list list-of-filters">
                            <?php foreach ($campus_terms as $campus) { ?>
                                <li class="campuses-list_item" id="<?php echo $campus->slug; ?>">
                                    <label for="checkbox-<?php echo $campus->slug; ?>" class="checkbox-container"><?php echo $campus->name; ?>
                                    <input type="checkbox" id="checkbox-<?php echo $campus->slug; ?>" name="<?php echo $campus->slug; ?>" value="<?php echo $campus->name; ?>">
                                    <span class="checkmark"></span>
                                    </label>
                                </li>
                            <?php } ?>
                            </ul>
                        <?php }
                    ?>
                </div>
                </div>
            </div>
        </div>

        <div class="accordion" id="accordionCourseType">
            <div class="accordion-item accordion-item-styled">
                <span class="accordion-header" id="headingCourseType">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    Filter by course type
                </button>
                </span>
                <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo" data-bs-parent="#accordionCourseType">
                <div class="accordion-body">
                    <?php 
                        $coursetype_terms = get_terms([
                            'taxonomy' => 'course_types',
                            'hide_empty' => false,
                        ]);

                        if ($coursetype_terms){ ?>
                            <ul class="coursetypes-list list-of-filters">
                            <li class="coursetypes-list_item" id="all-coursetypes">
                            <label for="checkbox-all" class="checkbox-container">All
                                <input type="checkbox" id="checkbox-all" name="checkbox-all" value="All">
                                <span class="checkmark"></span>
                            </label>
                            </li>
                            <?php foreach ($coursetype_terms as $coursetype) { ?>
                                <li class="coursetypes-list_item" id="<?php echo $coursetype->slug; ?>">
                                    <label for="checkbox-<?php echo $coursetype->slug; ?>" class="checkbox-container"><?php echo $coursetype->name; ?>
                                    <input type="checkbox" id="checkbox-<?php echo $coursetype->slug; ?>" name="<?php echo $coursetype->slug; ?>" value="<?php echo $coursetype->name; ?>">
                                    <span class="checkmark"></span>
                                    </label>
                                </li>
                            <?php } ?>
                            </ul>
                        <?php }
                    ?>
                </div>
                </div>
            </div>
        </div>

        <button class="button apply-filters-button">
            Apply Now
        </button>
    </div>
    <div class="course-grid row col-12 col-lg-9">
    <?php while ($the_query->have_posts()):
        $the_query->the_post();

        $id_of_course = get_the_ID();?>

		                <div class="course-card_container col-6 col-lg-4">
                            <div class="course-card">
		                    <div class="course-card_image">
		                        <?php the_post_thumbnail();?>
		                    </div>
                            <div class="course-card_insides">
		                    <span class="course-card_title"><?php the_title();?></span>
                                <div class="course-card_info">
                                <span class="course-card_course-code"><?php
                                    $terms = get_the_terms($id_of_course, 'course_types');
                                    if ($terms) {
                                    foreach ($terms as $term) {
                                        echo $term->name;
                                    }
                                }
                                    ?></span>
                                <span class="course-card_duration"><?php the_field('course_duration', $id_of_course);?></span>
                                </div>
                            </div>
                            <div class="course-card_campuses">
                            <?php
                                $terms = get_the_terms($id_of_course, 'campuses');
                                if ($terms) {
                                foreach ($terms as $term) { ?>
                                    <div class="course-card_campus-letters">
                                        <button data-toggle="tooltip" title="<?php echo $term->name; ?>" class="course-card_campus-letters_letter">
                                        <?php 
                                        $array_of_words = explode(' ', $term->name);
                                        for ($i = 0; $i < 2; $i++) {
                                            echo substr($array_of_words[$i], 0 ,1);
                                          } ?>
                                        </button>
                                    </div>
                                <?php } }
                                ?>
                            </div>
                            </div>
		                </div>
						<?php endwhile;?>

			<?php else: ?>
                <p>No courses found.</p>
            </div>
        </div>
    </div>
    <?php endif;?>
    </body>
</html>