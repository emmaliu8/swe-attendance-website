<?php /* Template Name: logEvent.php */ ?>

<?php
/**
 * The template for displaying pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ariel
 */
    get_header();

    $ariel_pages_sidebar = ariel_get_option( 'ariel_pages_sidebar' );
    $ariel_pages_featured_image_show = ariel_get_option( 'ariel_pages_featured_image_show' );

    if ( $ariel_pages_sidebar ) {
        $ariel_main_class = 'col-md-9';
    } else {
        $ariel_main_class = 'col-md-12';
    }
?>



<div class="contents">
	<div class="container">
		<div class="row two-columns">
            <div class="main-column col-md-9">
			    <div class="main-column <?php echo esc_attr( $ariel_main_class ); ?>">
				    <?php if ( have_posts() ) : while ( have_posts() ) : the_post();

					the_title( '<h1 class="page-title">', '</h1>' );

					if ( $ariel_pages_featured_image_show && has_post_thumbnail() ) : ?>
                
                        <div class="entry-thumb">
                            <?php the_post_thumbnail( 'full', array( 'alt' => get_the_title(), 'class' => 'img-responsive' ) ); ?>
                        </div><!-- entry-thumb -->
                        
					<?php endif;

					the_content();
					
					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ariel' ),
						'after'  => '</div>',
					) ); ?>
					
					
                <?php

                require_once 'dbconfig.php';
                                
                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                } 

                $getAllKerberosQuery = "SELECT Kerberos FROM Members ORDER BY Kerberos";
                $getAllEventsQuery = "SELECT Name FROM Events WHERE EventType = 'General' OR EventType = 'BoardMeeting' OR EventType = 'Recruiting' ORDER BY Name";

                $allKerberos = mysqli_query($conn, $getAllKerberosQuery);
                $allEvents = mysqli_query($conn, $getAllEventsQuery);
                ?>

                <form action="/swe/logEventAction.php">
                    <fieldset>
                    <p><label>Kerberos:</label><br />
                    <select name="kerberos" required><option hidden disabled selected value>&#8212;Select Kerberos&#8212;</option> <?php while($kerberosData = mysqli_fetch_array($allKerberos)){ ?>
                    
                    <option value="<?php echo $kerberosData['Kerberos'];?>"> <?php echo $kerberosData['Kerberos'];?>
                    </option>

                    <?php }?> </select></p> 

                    <p><label>Event:</label><br/>
                    <select name="eventName" required><option hidden disabled selected value>&#8212;Select Event&#8212;</option> <?php while($eventData = mysqli_fetch_array($allEvents)){ ?>
                    
                    <option value="<?php echo $eventData['Name'];?>"> <?php echo $eventData['Name'];?>
                    </option>

                    <?php }?> </select></p>

                    <?php $conn->close(); ?>
  
                    <label>Event Password:</label><br>
                    <input id="password" name="password" type="text" required/>
                    <br>
                    <br>
                    <input type="submit" value="Submit" />
                        
                    </fieldset>
                </form>
    

                <?php
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;

				endwhile; endif; ?>
			</div><!-- main-column col-md-9 -->

			<?php if ( $ariel_pages_sidebar ) : ?>
				<?php get_sidebar(); ?>
			<?php endif; ?>
			
		</div><!-- row two-columns -->
	</div><!-- container -->
</div><!-- contents -->

<?php get_footer();?>