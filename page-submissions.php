<?php 

get_header();

if(logged_in()){ ?>


<section class="section-form flex wrap padding flex-top">
    
    <div class="row flex">
        <div class="column-1">
            <h1>Submissions</h1>
        </div>
    </div>

    <?php 

    $cu = wp_get_current_user();
    $churchList = $cu->groups;

    $query = new WP_Query(array(
        'post_type' => 'submission',
        'posts_per_page' => 10,
        'meta_query' => array(
            array(
                'key' => 'church',
                'value' => $churchList,
                'compare' => 'IN',
            )
        )
    ));
    ?>

    <div class="row flex list">
        <div class="column-1">
            <ul class='submission-list'>
            <?php while($query->have_posts()){
                $query->the_post(); ?>
                <a href="<?php the_permalink(); ?>">
                <li><?php  
                    $church = get_field('church');
                    echo get_the_title() . " | " . $church->post_title . ' | ' . get_the_date();
                ?></li>
                </a>
                
            <?php
            }
            ?>
            </ul>
        </div>
    </div>

</section>


<?php }

get_footer();

