<?php //microdatapro 7.3 ?>
<?php if($breadcrumbs){ ?>
<?php if($syntax == "md" or $syntax == "all"){ ?>
<!--microdatapro <?php echo $version; ?> breadcrumb start [microdata] -->
<span itemscope itemtype="http://schema.org/BreadcrumbList">
<?php foreach ($breadcrumbs as $key => $breadcrumb) { ?>
<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
<link itemprop="item" href="<?php echo $breadcrumb['href']; ?>">
<meta itemprop="name" content="<?php echo $breadcrumb['text']; ?>" />
<meta itemprop="position" content="<?php echo $key; ?>" />
</span>
<?php } ?>
</span>
<!--microdatapro <?php echo $version; ?> breadcrumb end [microdata] -->
<?php } ?>
<?php if($syntax == "ld" or $syntax == "all"){ ?>
<!--microdatapro <?php echo $version; ?> breadcrumb start [json-ld] -->
<script type="application/ld+json">
{
"@context": "http://schema.org",
"@type": "BreadcrumbList",
"itemListElement": [<?php foreach ($breadcrumbs as $key => $breadcrumb) { ?>{
"@type": "ListItem",
"position": <?php echo $key; ?>,
"item": {
"@id": "<?php echo $breadcrumb['href']; ?>",
"name": "<?php echo $breadcrumb['text']; ?>"
}
}<?php if($key != count($breadcrumbs)){ ?>,<?php } ?><?php } ?>]
}
</script>
<!--microdatapro <?php echo $version; ?> breadcrumb end [json-ld] -->
<?php } ?>
<?php } ?>
<?php if($range or $review){ ?>
<?php if($syntax == "md" or $syntax == "all"){ ?>
<!--microdatapro <?php echo $version; ?> product start [microdata] -->
<span itemscope itemtype="http://schema.org/Product">
<meta itemprop="name" content="<?php echo $name; ?>">
<link itemprop="image" href="<?php echo $image; ?>" />
<meta itemprop="brand" content="<?php echo $name; ?>">
<meta itemprop="description" content="<?php echo $description; ?>">
<meta itemprop="sku" content="<?php echo $sku; ?>">
<meta itemprop="mpn" content="<?php echo $sku; ?>">
<?php if($review && $rating_count){ ?>
<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
<meta itemprop="ratingCount" content="<?php echo (int)$rating_count; ?>">
<meta itemprop="ratingValue" content="<?php echo (int)$rating_value; ?>">
<meta itemprop="bestRating" content="5">
</span>
<?php } ?>
<?php if($range){ ?>
<span itemprop="offers" itemscope itemtype="http://schema.org/AggregateOffer">
<meta itemprop="highPrice" content="<?php echo $max; ?>">
<meta itemprop="lowPrice" content="<?php echo $min; ?>">
<meta itemprop="offerCount" content="<?php echo $total; ?>">
<meta itemprop="priceCurrency" content="<?php echo $code; ?>">
</span>
<?php } ?>
</span>
<!--microdatapro <?php echo $version; ?> product end [microdata] -->
<?php } ?>
<?php if($syntax == "ld" or $syntax == "all"){ ?>
<!--microdatapro <?php echo $version; ?> breadcrumb start [json-ld] -->
<script type="application/ld+json">
{
"@context": "http://schema.org/",
"@type": "Product",
"name": "<?php echo $name; ?>",
"image": "<?php echo $image; ?>",
"brand": "<?php echo $name; ?>",
"description": "<?php echo $description; ?>",
"sku": "<?php echo $sku; ?>",
"mpn": "<?php echo $sku; ?>"
<?php if($review && $rating_count){ ?>,"aggregateRating": {
"@type": "AggregateRating",
"bestRating": "5",
"ratingValue": "<?php echo (int)$rating_value; ?>",
"ratingCount": "<?php echo (int)$rating_count; ?>"
}<?php } ?>
<?php if($range){ ?>,"offers": {
"@type": "AggregateOffer",
"lowPrice": "<?php echo $min; ?>",
"highPrice": "<?php echo $max; ?>",
"offerCount": "<?php echo $total; ?>",
"priceCurrency": "<?php echo $code; ?>"
}<?php } ?>
}
</script>
<!--microdatapro <?php echo $version; ?> breadcrumb end [json-ld] -->
<?php } ?>
<?php } ?>
<?php if($images){ ?>
<?php if($syntax == "md" or $syntax == "all"){ ?>
<!--microdatapro <?php echo $version; ?> gallery start[microdata] -->
<span itemscope itemtype="http://schema.org/ImageGallery">
<?php foreach($images as $image){ ?>
<span itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
<meta itemprop="name" content="<?php echo $image['name']; ?>" />
<meta itemprop="description" content="<?php echo $image['name']; ?>" />
<link itemprop="thumbnailUrl" href="<?php echo $image['thumb']; ?>" />
<link itemprop="contentUrl" href="<?php echo $image['popup']; ?>" />
<meta itemprop="author" content="<?php echo $author; ?>" />
<meta itemprop="datePublished" content="<?php echo $image['date_added']; ?>">
</span>
<?php } ?>
</span>
<!--microdatapro <?php echo $version; ?> gallery end [microdata] -->
<?php } ?>
<?php if($syntax == "ld" or $syntax == "all"){ ?>
<!--microdatapro <?php echo $version; ?> gallery start [json-ld] -->
<script type="application/ld+json">
{
"@context": "http://schema.org",
"@type": "ImageGallery",
"associatedMedia":[
<?php $key_i = 1; foreach($images as $image){ ?>
{
"@type": "ImageObject",
"author": "<?php echo $author; ?>",
"thumbnailUrl": "<?php echo $image['thumb']; ?>",
"contentUrl": "<?php echo $image['popup']; ?>",
"datePublished": "<?php echo $image['date_added']; ?>",
"description": "<?php echo $image['name']; ?>",
"name": "<?php echo $image['name']; ?>"
}<?php if($key_i != count($images)){ ?>,<?php } ?>
<?php $key_i++; } ?>
]
}
</script>
<!--microdatapro <?php echo $version; ?> gallery end [json-ld] -->
<?php } ?>
<?php } ?>
