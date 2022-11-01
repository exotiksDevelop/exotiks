<?php //microdatapro 7.3 ?>
<?php if($twitter){ ?>
<!--microdatapro <?php echo $version; ?> twitter cards start -->
<meta property="twitter:card" content="summary_large_image" />
<meta property="twitter:creator" content="<?php echo $twitter_account; ?>" />
<meta property="twitter:site" content="<?php echo $title; ?>" />
<meta property="twitter:title" content="<?php echo $title; ?>" />
<meta property="twitter:description" content="<?php echo $description; ?>" />
<meta property="twitter:image" content="<?php echo $image; ?>" />
<meta property="twitter:image:alt" content="<?php echo $title; ?>" />
<!--microdatapro <?php echo $version; ?> twitter cards end -->
<?php } ?>
<?php if($opengraph){ ?>
<!--microdatapro <?php echo $version; ?> open graph start -->
<meta property="og:locale" content="<?php echo $locale; ?>">
<meta property="og:rich_attachment" content="true">
<meta property="og:site_name" content="<?php echo $site_name; ?>">
<meta property="og:type" content="<?php echo $og_type; ?>" />
<meta property="og:title" content="<?php echo $title; ?>" />
<meta property="og:description" content="<?php echo $description; ?>" />
<meta property="og:image" content="<?php echo $image; ?>" />
<meta property="og:image:secure_url" content="<?php echo $image; ?>" />
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<?php if($images){ ?>
<?php foreach($images as $image){ ?>
<meta property="og:image" content="<?php echo $image; ?>" />
<meta property="og:image:secure_url" content="<?php echo $image; ?>" />
<?php } ?>
<?php } ?>
<meta property="og:url" content="<?php echo $url; ?>">
<?php if($contacts){ ?>
<meta property="business:contact_data:street_address" content="<?php echo $street_address; ?>" />
<meta property="business:contact_data:locality" content="<?php echo $locality; ?>" />
<meta property="business:contact_data:postal_code" content="<?php echo $postal_code; ?>" />
<meta property="business:contact_data:country_name" content="<?php echo $country_name; ?>" />
<meta property="place:location:latitude" content="<?php echo $latitude; ?>" />
<meta property="place:location:longitude" content="<?php echo $longitude; ?>" />
<meta property="business:contact_data:email" content="<?php echo $email; ?>"/>
<?php if($telephone){ ?>
<meta property="business:contact_data:phone_number" content="<?php echo $telephone; ?>"/>
<?php } ?>
<?php } ?>
<?php if($microdatapro_profile_id){ ?>
<meta property="fb:profile_id" content="<?php echo $microdatapro_profile_id; ?>">
<?php } ?>
<?php if($product_page){ ?>
<meta property="product:product_link" content="<?php echo $url; ?>">
<meta property="product:brand" content="<?php echo $product_manufacturer; ?>">
<meta property="product:category" content="<?php echo $product_category; ?>">
<meta property="product:availability" content="<?php echo $product_stock; ?>">
<?php if($age_group){ ?>
<meta property="product:age_group" content="<?php echo $age_group; ?>">
<?php if($age_group == "adult"){ ?>
<meta property="og:restrictions:age" content="18+">
<?php } ?>
<?php } ?>
<meta property="product:condition" content="new">
<?php if($ean){ ?>
<meta property="product:ean" content="<?php echo $ean; ?>">
<?php } ?>
<?php if($isbn){ ?>
<meta property="product:isbn" content="<?php echo $isbn; ?>">
<?php } ?>
<?php if($upc){ ?>
<meta property="product:upc" content="<?php echo $upc; ?>">
<?php } ?>
<?php if($color){ ?>
<meta property="product:color" content="<?php echo $color; ?>">
<?php } ?>
<?php if($material){ ?>
<meta property="product:material" content="<?php echo $material; ?>">
<?php } ?>
<?php if($size){ ?>
<meta property="product:size" content="<?php echo $size; ?>">
<?php } ?>
<meta property="product:target_gender" content="<?php echo $target_gender; ?>">
<?php if($price){ ?>
<meta property="product:price:amount" content="<?php echo $price; ?>">
<meta property="product:price:currency" content="<?php echo $currency; ?>">
<?php } ?>
<?php if($special){ ?>
<meta property="product:sale_price:amount" content="<?php echo $special; ?>">
<meta property="product:sale_price:currency" content="<?php echo $currency; ?>">
<?php } ?>
<?php if($relateds){ ?>
<?php foreach($relateds as $related){ ?>
<meta property="og:see_also" content="<?php echo $related; ?>" />
<?php } ?>
<?php } ?>
<?php } ?>
<!--microdatapro <?php echo $version; ?> open graph end -->
<?php } ?>
