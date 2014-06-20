<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php meetup_get_template_part( 'meetup', 'header' ); ?>

    <div class="meetup-content-wrap">
        <div class="meetup-col-left">

            <div class="entry-content">
                <?php the_content(); ?>
            </div>

            <div class="meetup-join-event">
                <h3>Join the Event</h3>

                <section class="meetup-fb-register meetup-join-form">

                    <span class="meetup-select-box">
                        <select name="meetup-fb-join-seat" id="meetup-fb-join-set">
                            <?php for ($i = 1; $i < 10; $i++) { ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php } ?>
                        </select>
                    </span>

                    <button class="meetup-fb-button" href="#">
                        <i class="fa fa-facebook-square"></i>
                        <span><?php _e( 'Connect to Register', 'meetup' ); ?></span>
                    </button>

                    <div class="meetup-reg-link">
                        <a href="#">or, register with email</a>
                    </div>
                </section><!-- .meetup-fb-register -->

                <section class="meetup-site-join meetup-join-form">

                    <div class="meetup-reg-link">
                        <a href="#">Register with Facebook</a>
                    </div>

                    <form action="" method="post" id="meetup-site-join-form">
                        <div class="meetup-form-row meetup-col-wrap">
                            <div class="meetup-form-half">
                                <label for="meetup_fname">First Name</label>
                                <input type="text" name="meetup_fname" id="meetup_fname" value="" placeholder="First Name">
                            </div>

                            <div class="meetup-form-half">
                                <label for="meetup_lname">Last Name</label>
                                <input type="text" name="meetup_lname" id="meetup_lname" value="" placeholder="Last Name">
                            </div>

                        </div>

                        <div class="meetup-form-row meetup-email-wrap">
                            <label for="meetup_email">Email Address</label>
                            <input type="email" name="meetup_email" id="meetup_email" value="" placeholder="you@example.com">
                        </div>

                        <div class="meetup-form-row">

                            <select name="meetup-fb-join-seat" id="meetup-fb-join-set">
                                <?php for ($i = 1; $i < 10; $i++) { ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php } ?>
                            </select>

                            <input type="submit" name="meetup_submit" value="<?php _e( 'Book My Seat', 'meetup' ); ?>">
                        </div>

                    </form>
                </section><!-- .meetup-site-join -->

                <div class="meetup-reg-ends">
                    <strong>Registration Ends:</strong> July 28, 2014 2:30pm
                </div>
            </div><!-- .meetup-join-event -->
        </div><!-- .meetup-col-left -->

        <div class="meetup-col-right">
            <ul>
                <li>
                    <div class="meetup-icon">
                        <i class="fa fa-clock-o"></i>
                    </div>

                    <div class="meetup-details">
                        <time>Saturday, June 28 at 3:30pm - 7:30pm</time><br>
                        <a href="#">Add to my calendar</a>
                    </div>
                </li>

                <li class="clearfix">
                    <div class="meetup-icon">
                        <i class="fa fa-map-marker"></i>
                    </div>

                    <div class="meetup-details">
                        <address>
                            Hub Dhaka<br>
                            Islam Plaza, <br>
                            9th Floor <br>
                            Plot No.7, Main Road-3, <br>
                            Section-7 Pallavi <br>
                            Dhaka, 1216
                        </address>

                        <div class="meetup-map"></div>
                    </div>
                </li>
            </ul>
        </div><!-- .meetup-col-right -->
    </div>

</article><!-- #post-<?php the_ID(); ?> -->
