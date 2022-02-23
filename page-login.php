<?php 

get_header();

?>


<section class="section-form flex wrap padding flex-top">
    
    <div class="row flex">
        <div class="column-2">
            <h1>Log in</h1>
        </div>
    </div>

    <div class="row flex list">
        <div class="column-2">
            <ul class='submission-list'>
                <?php wp_login_form(array(
                    'redirect' => 'healingskool.local/submissions/',
                    'label_username' => 'Username or Email',
                )); ?>
            </ul>
        </div>
    </div>

</section>

<?php 

get_footer();

