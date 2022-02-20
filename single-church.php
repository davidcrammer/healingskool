<?php 

get_header();

while(have_posts()){

the_post();
?>

<section class="section-form flex wrap padding flex-top">
    
    <div class="row flex">
        <div class="column-1">
            <h1><?php the_title(); ?></h1>
            <p><?php echo get_field('description')?></p>

        </div>
    </div>

    <div class="row flex">
        <div class="column-1">
            <?php form_validation_messages(); ?>

            <form action="#" method="post">
                <?php wp_nonce_field( 'do-it', 'church-form-nonce' ); ?>

                <p><label for="message_full_name">Full Name <span>*</span> <br>
                <input type="text" name="message_full_name" ?></label></p>

                <p><label for="message_email">Email <span>*</span> <br>
                <input type="text" name="message_email" ?></label></p>

                <p><label for="message_doctor">Favorite Cat <span>*</span> <br>
                <p><input type="radio" id="doctor_yes" name="message_doctor" value="Lionel" checked>
                <label for="doctor_yes">Lionel</label></p>
                <p><input type="radio" id="doctor_no" name="message_doctor" value="Frank">
                <label for="doctor_no">Frank</label></p>

                <p><input type="submit" name="church-form"></p>
            </form>
        </div>
    </div>

</section>

<?php 
}


get_footer();

?>