<?php global $Restrict_Elementor;?>

<div class="wrap rfe_outside_wrap nosubsub">
  <div class="rfe-welcome-screen-wrap">

    <div class="rfe-settings-header">
      <div class="rfe-restrict-logo">
        <img src="<?php echo esc_url($Restrict_Elementor->plugin_url.'/assets/images/restrict-logo@2x.png'); ?>" width="70" />
        <div class="rfe-version">v <?php echo esc_html($Restrict_Elementor->version); ?></div>
      </div>

      <div class="rfe-header-title">
        <h1><?php echo esc_html($Restrict_Elementor->title);?></h1>
        <span><?php _e('Simple solution for restricting content of your Elementor-powered WordPress website.', 'restrict-for-elementor');?></span>
      </div>
      <?php $is_whitelabel = ( defined( 'RSC_EL_PLUGIN_TITLE' ) && RSC_EL_PLUGIN_TITLE !== 'Restrict for Elementor' ); ?>
      <div class="rfe-restrict-elementor-hint">
        <img src="<?php echo esc_url($Restrict_Elementor->plugin_url.'/assets/images/info-icon.svg');?>" /><p><?php echo sprintf(__('To start restricting open any page with Elementor editor, select a desired section, column or a widget, click on the Content tab, navigate to the "%s" section and choose desired restriction criteria.', 'restrict-for-elementor'), $Restrict_Elementor->title);?>
          <?php
          if(!$is_whitelabel){
            echo sprintf(__('%sLearn more %shere%s or watch the video &#x1F449;%s', 'restrict-for-elementor'), '<strong>', '<a href="https://restrict.io/documentation-category/restrict-for-elementor/">', '</a>', '</strong>');
            }?>
          </p>
          <?php
          if(!$is_whitelabel){?>
            <a class="rfe-play-icon rfe-popup-tutorial" data-video-id="nnhFlT8nCjg"><img src="<?php echo esc_url($Restrict_Elementor->plugin_url.'/assets/images/play-icon.svg');?>" /></a>
          <?php } ?>
        </div>


      </div><!-- .rc-settings-header -->

      <div class="wrap rfe_wrap">

        <div class="rfe-options-wrap rfe-current-welcome">

          <div class="rfe-welcome-screen">


            <div class="rfe-free-features">

              <div class="rfe-feature-header-wrap">
                <span><?php _e('Features', 'restrict-for-elementor'); ?></span>
                <?php if (!rfe_fs()->can_use_premium_code()) { ?>
                  <a href="<?php echo esc_url(admin_url('admin.php?page=restrict_for_elementor_settings-pricing'));?>" class="button-primary rfe-premium-button rfe-top-premium"><?php _e('GO PREMIUM', 'restrict-for-elementor'); ?></a>
                <?php } ?>
              </div>

              <div class="rfe-sections-wrap">

                <?php if($Restrict_Elementor->should_load_addon('WHITE_LABEL')){ ?>
                  <?php if(!$is_whitelabel){ ?>
                    <div class="rfe-half-section rfe-premium-section">

                      <div class="rfe-welcome-img">
                        <img src="<?php echo esc_url($Restrict_Elementor->plugin_url.'/assets/images/white-label.svg');?>" width="320" />
                      </div><!-- .rfe-welcome-img -->

                      <div class="rfe-welcome-text">
                        <?php if (rfe_fs()->can_use_premium_code()) { ?>
                          <div class="rfe-already-featured"></div>
                        <?php }else{
                          ?>
                          <div class="rfe-premium-section-button">
                            <a href="<?php echo esc_url(admin_url('admin.php?page=restrict_for_elementor_settings-pricing'));?>"><span><?php _e('Go Premium', 'restrict-for-elementor'); ?></span><div class="rfe-premium-icon"></div></a>
                          </div>
                          <?php
                        } ?>

                        <div class="rfe-rouned-box"></div>
                        <h3><?php _e('White Label', 'restrict-for-elementor'); ?></h3>
                        <p><?php _e('By adding just a single line to your wp-config.php file like this <strong>define(\'RSC_EL_PLUGIN_TITLE\', \'My Restriction Plugin\');</strong> the whole plugin will become white labeled and ready for your clients.', 'restrict-for-elementor');?></p>
                      </div>

                    </div><!-- .rfe-half-section -->
                  <?php } ?>
                <?php } ?>

                <?php if($Restrict_Elementor->should_load_addon('ALTERNATIVE_CONTENT')){ ?>
                  <div class="rfe-half-section rfe-premium-section">

                    <div class="rfe-welcome-img">
                      <img src="<?php echo esc_url($Restrict_Elementor->plugin_url.'/assets/images/alternative-content.svg');?>" width="320" />
                    </div><!-- .rfe-welcome-img -->

                    <div class="rfe-welcome-text">
                      <?php if (rfe_fs()->can_use_premium_code()) { ?>
                        <div class="rfe-already-featured"></div>
                      <?php }else{
                        ?>
                        <div class="rfe-premium-section-button">
                          <a href="<?php echo esc_url(admin_url('admin.php?page=restrict_for_elementor_settings-pricing'));?>"><span><?php _e('Go Premium', 'restrict-for-elementor'); ?></span><div class="rfe-premium-icon"></div></a>
                        </div>
                        <?php
                      } ?>
                      <div class="rfe-rouned-box"></div>
                      <h3><?php _e('Alternative Content', 'restrict-for-elementor'); ?></h3>
                      <p><?php echo sprintf(__('Instead of hiding content entirely, you can display a specific Elementor template or custom formatted text to users that do not fulfill required criteria for viewing a content.', 'restrict-for-elementor'), $Restrict_Elementor->title, __('Geolocation IP Detection', 'restrict-for-elementor'), '<a href="https://wordpress.org/plugins/geoip-detect/" target="_blank">', '</a>');?></p>
                      </div>

                    </div><!-- .rfe-half-section -->
                  <?php } ?>

                  <?php if($Restrict_Elementor->should_load_addon('WOOCOMMERCE')){ ?>
                    <div class="rfe-half-section rfe-premium-section">

                      <div class="rfe-welcome-img">
                        <img src="<?php echo esc_url($Restrict_Elementor->plugin_url.'/assets/images/woocommerce-integration.svg');?>" width="320"/>
                      </div><!-- .rfe-welcome-img -->

                      <div class="rfe-welcome-text">

                        <?php if (rfe_fs()->can_use_premium_code()) { ?>
                          <div class="rfe-already-featured"></div>
                        <?php }else{
                          ?>
                          <div class="rfe-premium-section-button">
                            <a href="<?php echo esc_url(admin_url('admin.php?page=restrict_for_elementor_settings-pricing'));?>"><span><?php _e('Go Premium', 'restrict-for-elementor'); ?></span><div class="rfe-premium-icon"></div></a>
                          </div>
                          <?php
                        } ?>

                        <div class="rfe-rouned-box"></div>
                        <h3><?php _e('WooCommerce Integration', 'restrict-for-elementor'); ?></h3>
                        <p><?php printf(__('Display different content based on whether a user purchased a specific %sWooCommerce%s product, product variation or has an active subscription via %sWooCommerce Subscriptions%s plugin.', 'restrict-for-elementor'), '<a href="https://woocommerce.com/?aff=1581" target="_blank">','</a>', '<a href="https://woocommerce.com/products/woocommerce-subscriptions/?aff=1581" target="_blank">', '</a>');?></p>
                        </div>

                      </div><!-- .rfe-half-section -->
                    <?php } ?>

                    <?php if($Restrict_Elementor->should_load_addon('EDD')){ ?>
                      <div class="rfe-half-section rfe-premium-section">

                        <div class="rfe-welcome-img">
                          <img src="<?php echo esc_url($Restrict_Elementor->plugin_url.'/assets/images/edd-integration.svg');?>" width="320" />
                        </div><!-- .rfe-welcome-img -->

                        <div class="rfe-welcome-text">
                          <?php if (rfe_fs()->can_use_premium_code()) { ?>
                            <div class="rfe-already-featured"></div>
                          <?php }else{
                            ?>
                            <div class="rfe-premium-section-button">
                              <a href="<?php echo esc_url(admin_url('admin.php?page=restrict_for_elementor_settings-pricing'));?>"><span><?php _e('Go Premium', 'restrict-for-elementor'); ?></span><div class="rfe-premium-icon"></div></a>
                            </div>
                            <?php
                          } ?>
                          <div class="rfe-rouned-box"></div>
                          <h3><?php _e('EDD Integration', 'restrict-for-elementor'); ?></h3>
                          <p><?php echo sprintf(__('Show or hide sections, columns or widgets of your page built with Elementor when user purchases a specific %3$s %2$s %4$s product.', 'restrict-for-elementor'), $Restrict_Elementor->title, __('Easy Digital Downloads', 'restrict-for-elementor'), '<a href="https://easydigitaldownloads.com/" target="_blank">', '</a>');?></p>
                          </div>

                        </div><!-- .rfe-half-section -->
                      <?php } ?>

                      <?php if($Restrict_Elementor->should_load_addon('TICKERA')){ ?>
                        <div class="rfe-half-section rfe-premium-section">

                          <div class="rfe-welcome-img">
                            <img src="<?php echo esc_url($Restrict_Elementor->plugin_url.'/assets/images/tickera-integration.svg');?>" width="320" />
                          </div><!-- .rfe-welcome-img -->

                          <div class="rfe-welcome-text">
                            <?php if (rfe_fs()->can_use_premium_code()) { ?>
                              <div class="rfe-already-featured"></div>
                            <?php }else{
                              ?>
                              <div class="rfe-premium-section-button">
                                <a href="<?php echo esc_url(admin_url('admin.php?page=restrict_for_elementor_settings-pricing'));?>"><span><?php _e('Go Premium', 'restrict-for-elementor'); ?></span><div class="rfe-premium-icon"></div></a>
                              </div>
                              <?php
                            } ?>
                            <div class="rfe-rouned-box"></div>
                            <h3><?php _e('Tickera Integration', 'restrict-for-elementor'); ?></h3>
                            <p><?php echo sprintf(__('With this %3$s %2$s %4$s for WordPress, you can choose criteria to restrict certain sections, columns and widgets for customers who purchased required ticket types or ticket(s) for selected events.', 'restrict-for-elementor'), $Restrict_Elementor->title, __('Event Ticketing System', 'restrict-for-elementor'), '<a href="https://tickera.com/" target="_blank">', '</a>');?></p>
                            </div>

                          </div><!-- .rfe-half-section -->
                        <?php } ?>

                        <?php if($Restrict_Elementor->should_load_addon('LOGGED_IN_USERS')){ ?>
                          <div class="rfe-half-section">

                            <div class="rfe-welcome-img">
                              <img src="<?php echo esc_url($Restrict_Elementor->plugin_url.'/assets/images/logged-in-users.svg');?>" width="320" />
                            </div><!-- .rfe-welcome-img -->

                            <div class="rfe-welcome-text">
                              <div class="rfe-already-featured"></div>
                              <div class="rfe-rouned-box"></div>
                              <h3><?php _e('Logged in users', 'restrict-for-elementor');?></h3>
                              <p><?php _e('Show or hide different widgets, columns or whole sections based on whether a visitor is logged in or not.', 'restrict-for-elementor');?></p>
                            </div>


                          </div><!-- .rfe-half-section -->
                        <?php } ?>

                        <?php if($Restrict_Elementor->should_load_addon('USER_CAPABILITY')){ ?>
                          <div class="rfe-half-section">

                            <div class="rfe-welcome-img">
                              <img src="<?php echo esc_url($Restrict_Elementor->plugin_url.'/assets/images/specific-capability.svg');?>" width="320" />
                            </div><!-- .rfe-welcome-img -->

                            <div class="rfe-welcome-text">
                              <div class="rfe-already-featured"></div>
                              <div class="rfe-rouned-box"></div>
                              <h3><?php _e('Users with specific capability', 'restrict-for-elementor'); ?></h3>
                              <p><?php _e('Show or hide Elementor elements to users with specific capabilities. Very useful if you are using custom capabilities with custom user roles.', 'restrict-for-elementor');?></p>
                            </div>

                          </div><!-- .rfe-half-section -->
                        <?php } ?>

                        <?php if($Restrict_Elementor->should_load_addon('USER_META')){ ?>
                          <div class="rfe-half-section">

                            <div class="rfe-welcome-img">
                              <img src="<?php echo esc_url($Restrict_Elementor->plugin_url.'/assets/images/specific-meta-value.svg');?>" width="320" />
                            </div><!-- .rfe-welcome-img -->


                            <div class="rfe-welcome-text">
                              <div class="rfe-already-featured"></div>
                              <div class="rfe-rouned-box"></div>
                              <h3><?php _e('Users with specific meta value', 'restrict-for-elementor'); ?></h3>
                              <p><?php _e('Use any user meta key as condition and use different comparing methods and data types to make the element visible or hidden.', 'restrict-for-elementor');?></p>
                            </div>

                          </div><!-- .rfe-half-section -->
                        <?php } ?>

                        <?php if($Restrict_Elementor->should_load_addon('USER_ROLE')){ ?>
                          <div class="rfe-half-section">

                            <div class="rfe-welcome-img">
                              <img src="<?php echo esc_url($Restrict_Elementor->plugin_url.'/assets/images/specific-role.svg');?>" width="320" />
                            </div><!-- .rfe-welcome-img -->

                            <div class="rfe-welcome-text">
                              <div class="rfe-already-featured"></div>
                              <div class="rfe-rouned-box"></div>
                              <h3><?php _e('Users with specific role', 'restrict-for-elementor'); ?></h3>
                              <p><?php _e('Want to control which user sees what? User roles can be used as criteria for showing or hiding sections, columns and widgets.', 'restrict-for-elementor');?></p>
                            </div>

                          </div><!-- .rfe-half-section -->
                        <?php } ?>

                        <?php if($Restrict_Elementor->should_load_addon('POST_AUTHOR')){ ?>
                          <div class="rfe-half-section">

                            <div class="rfe-welcome-img">
                              <img src="<?php echo esc_url($Restrict_Elementor->plugin_url.'/assets/images/post-author.svg');?>" width="320" />
                            </div><!-- .rfe-welcome-img -->

                            <div class="rfe-welcome-text">
                              <div class="rfe-already-featured"></div>
                              <div class="rfe-rouned-box"></div>
                              <h3><?php _e('Post Author', 'restrict-for-elementor'); ?></h3>
                              <p><?php _e('Want to make some element visible or hidden to post author only? This one is for you!', 'restrict-for-elementor');?></p>
                            </div>

                          </div><!-- .rfe-half-section -->
                        <?php } ?>

                        <?php if($Restrict_Elementor->should_load_addon('GEOLOCATION')){ ?>
                          <div class="rfe-half-section">

                            <div class="rfe-welcome-img">
                              <img src="<?php echo esc_url($Restrict_Elementor->plugin_url.'/assets/images/geolocation.svg');?>" width="320" />
                            </div><!-- .rfe-welcome-img -->

                            <div class="rfe-welcome-text">
                              <div class="rfe-already-featured"></div>
                              <div class="rfe-rouned-box"></div>
                              <h3><?php _e('Geolocation', 'restrict-for-elementor'); ?></h3>
                              <p><?php echo sprintf(__('With the %3$s %2$s %4$s plugin integration, you can easily restrict content based on visitor\'s country or continent of residence.', 'restrict-for-elementor'), $Restrict_Elementor->title, __('Geolocation IP Detection', 'restrict-for-elementor'), '<a href="https://wordpress.org/plugins/geoip-detect/" target="_blank">', '</a>');?></p>
                              </div>

                            </div><!-- .rfe-half-section -->
                          <?php } ?>

                        </div><!-- .rfe-free-features -->

                        <?php if (!rfe_fs()->can_use_premium_code()) { ?>
                          <div class="rfe-premium-text">
                            <span><?php _e('Upgrade to the premium version and get all features', 'restrict-for-elementor'); ?></span>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=restrict_for_elementor_settings-pricing'));?>" class="button-primary rfe-premium-button"><?php _e('GO PREMIUM', 'restrict-for-elementor'); ?></a>
                          </div>
                        <?php } ?>
                      </div>
                    </div><!-- .rfe-welcome-screen -->

                  </div><!-- .rfe-welcome-screen -->

                </div><!-- rfe-welvome-screen -->
              </div><!-- rfe_outside_wrap -->
