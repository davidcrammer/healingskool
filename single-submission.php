<?php 

get_header();

if(logged_in()){ 
    
    ?> <p>Small Change. this is the new feature. </p> <?php
    

while(have_posts()){
    the_post(); 
    $cu = wp_get_current_user();
    $userChurches = $cu->groups;
    $postChurch = get_field('church')->ID;

    if( ! in_array($postChurch, $userChurches, false)) { ?>
        
        <section class="section-form flex wrap padding flex-top">
        
            <div class="row flex">
                <div class="column-1">
                    <p> Sorry, you're not allowed to see this submission.</p>
                    <p><a href="/submissions"><strong>< Back to submissions.</strong></a></p>
                </div>
            </div>  

        </section> <?php

    } else {

    ?>

    <section class="section-form flex wrap padding flex-top">
        
        <div class="row flex">
            <div class="column-1">
                <h1><?php the_title(); ?></h1>
                <p>Submitted a prayer request on <b><?php the_time('M m, Y'); ?></b> at <b><?php the_time('g:ia'); ?></b> to <b><?php echo get_field('church')->post_title; ?></b></p>
                <br>
                <p>Email: <?php echo get_field('email'); ?></p>
                <p>Favorite Cat: <?php echo get_field('doctor'); ?></p>
                

                <hr>
            </div>
        </div>

        <div class="row flex list">
            <div class="column-1">
                <h2>Updates</h2>
                <?php
                    $comments = new WP_Query(array(
                        'post_type' => 'sub_comment',
                        'order' => "ASC",
                        'posts_per_page' => -1,
                        'meta_query' => array(
                            array(
                                'key' => 'submission',
                                'value' => $post->ID,
								'compare' => '=',
                                'type' => "NUMERIC"
                            )
                        )
                    ));
                
                while($comments->have_posts()){
                    $comments->the_post(); ?> 
                    <div class="comment">
                        <p class="comment-meta"><i><?php the_author(); ?> on <?php the_time('M m, Y'); ?> at <?php the_time('g:ia'); ?> said:</i></p>
                        <p><?php the_content(); ?></p>
                    </div>
                    
                    <br>
                    <?php 
                }
                ?>

                <form action="#" method="post">
                    <?php wp_nonce_field( 'do-it-2', 'comment-form-nonce' ); ?>

                    <textarea name="comment_field" class="comment-field" placeholder="Add an Update"></textarea>

                    <p><input type="submit" name="comment-form" value="Add Update"></p>
                </form>
            </div>
        </div>

    </section>

    <?php

    } 
}

}

get_footer();

