<!DOCTYPE html>
<html lang="en">
<head>
   <?php wp_head(); ?>
</head>
<body>

<?php if (is_user_logged_in()) { ?>
   <section class="flex flex-wrap header-top-bar flex-right">
      <?php 
         $cu = wp_get_current_user(); 
         $userFirstName = $cu->first_name;
      ?>
      <p>Hey there, <span><?php echo $userFirstName; ?></span></p>
   </section>
<?php } ?>

<header class="flex flex-wrap">
   

   <div class="row flex flex-sb">
      <div class="column-2">
         <a href="<?php echo site_url(); ?>"><p><strong>HEALING</strong>SKOOL</p></a>
      </div>
      <div class="column-2 flex flex-right">
         
            <?php 
            if (!is_user_logged_in()) {
               echo "<p><a href='/login/'>Log in</a></p>";
            } else {
               ?> 
               <p><a href='/submissions/'>Submissions</a></p>
               <p>|</p>
               <p><a href='<?php echo wp_logout_url("/"); ?>'>Log Out</a></p>
               <?php
            }
            ?>
         
      </div>
   </div>
</header>   