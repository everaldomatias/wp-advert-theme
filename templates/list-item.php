    <div class="<?php echo adverts_css_classes( 'advert-item advert-item-col-'.(int)$columns, get_the_ID() ) ?>">

        <?php $image = adverts_get_main_image( get_the_ID() ) ?>
        <div class="advert-img">
            <?php if($image): ?>
                <img src="<?php echo esc_attr($image) ?>" alt="" class="advert-item-grow" />
            <?php endif; ?>
        </div>
     
        <div class="advert-post-title">
            <span title="<?php echo esc_attr( get_the_title() ) ?>" class="advert-link"><?php the_title() ?></span>
            <a href="<?php the_permalink() ?>" title="<?php echo esc_attr( get_the_title() ) ?>" class="advert-link-wrap"></a>
        </div>
        
        <div class="advert-published ">
            
            <span class="advert-date"><?php echo date_i18n( get_option( 'date_format' ), get_post_time( 'U', false, get_the_ID() ) ) ?></span>
            
            <?php $location = get_post_meta( get_the_ID(), "adverts_location", true ); ?>
            <?php if( ! empty( $location ) ): ?>
            <span class=" advert-location adverts-icon-location"><?php echo esc_html( $location ) ?></span>
            <?php endif; ?>

            <?php $advert_category = get_the_terms( $post_id, 'advert_category' ) ?>
            <?php if(!empty($advert_category)): ?> 
            <div class="adverts-grid-row ">
                <div class="adverts-grid-col adverts-col-100">
                    <?php foreach($advert_category as $c): ?> 
                        <a href="<?php esc_attr_e( get_term_link( $c ) ) ?>"><?php echo join( " / ", advert_category_path( $c ) ) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>        
            
            <?php endif; ?>
            
            <?php $price = get_post_meta( get_the_ID(), "adverts_price", true ) ?>
            <?php if( $price && is_single() ): ?>
            <div class="advert-price"><?php echo esc_html( adverts_get_the_price( get_the_ID(), $price ) ) ?></div>
            <?php endif; ?>

        </div><!-- advert-published -->
        
    </div>
