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
<?php if($syntax == "md" or $syntax == "all"){ // syntax md ?>
<!--microdatapro <?php echo $version; ?> product start [microdata] -->
<span itemscope itemtype="http://schema.org/Product">
<meta itemprop="name" content="<?php echo $name; ?>" />
<link itemprop="url" href="<?php echo $url; ?>" />
<?php if($popup){ ?>
<link itemprop="image" href="<?php echo $popup; ?>" />
<?php } ?><?php if($manufacturer){ ?>
<meta itemprop="brand" content="<?php echo $manufacturer; ?>" />
<meta itemprop="manufacturer" content="<?php echo $manufacturer; ?>" />
<?php } ?><?php if ($model){ ?>
<meta itemprop="model" content="<?php echo $model; ?>" />
<?php } ?><?php if($upc){ ?>
<meta itemprop="gtin12" content="<?php echo $upc; ?>" />
<?php } ?><?php if($ean){ ?>
<meta itemprop="gtin8" content="<?php echo $ean; ?>" />
<?php } ?><?php if($isbn){ ?>
<meta itemprop="productID" content="<?php echo $isbn; ?>" />
<?php } ?><?php if($mpn){ ?>
<meta itemprop="mpn" content="<?php echo $mpn; ?>" />
<?php } ?><?php if($sku){ ?>
<meta itemprop="sku" content="<?php echo $sku; ?>" />
<?php } ?><?php if($category){ ?>
<meta itemprop="category" content="<?php echo $category; ?>" />
<?php } ?>
<?php if($rating && $reviewCount){ ?>
<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
<meta itemprop="ratingValue" content="<?php echo $rating; ?>">
<meta itemprop="ratingCount" content="<?php echo $reviewCount; ?>">
<meta itemprop="reviewCount" content="<?php echo $reviewCount; ?>">
<meta itemprop="bestRating" content="5">
<meta itemprop="worstRating" content="1">
</span>
<?php } ?>
<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
<meta itemprop="priceCurrency" content="<?php echo $code; ?>" />
<meta itemprop="price" content="<?php echo $price; ?>" />
<meta itemprop="itemCondition" content="http://schema.org/NewCondition" />
<link itemprop="availability" href="http://schema.org/<?php  echo $stock; ?>" />
<meta itemprop="priceValidUntil" content="<?php echo $price_valid; ?>" />
<link itemprop="url" href="<?php echo $url; ?>" />
</span>
<meta itemprop="description" content="<?php echo $description; ?>" />
<?php if($reviews){ ?>
<?php foreach($reviews as $review_item){ ?>
<span itemprop="review" itemscope itemtype="http://schema.org/Review">
<meta itemprop="author" content="<?php echo $review_item['author']; ?>" />
<meta itemprop="datePublished" content="<?php echo $review_item['date_added']; ?>" />
<span itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
<meta itemprop="worstRating" content = "1" />
<meta itemprop="ratingValue" content="<?php echo $review_item['rating']?$review_item['rating']:5; ?>" />
<meta itemprop="bestRating" content="5" />
</span>
<meta itemprop="description" content="<?php echo $review_item['text']; ?>" />
</span>
<?php } ?>
<?php } ?>
<?php if ($attributes) { ?>
<?php foreach ($attributes as $attribute) { ?>
<span itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
<meta itemprop="value" content="<?php echo $attribute['text']; ?>" />
<meta itemprop="name" content="<?php echo $attribute['name']; ?>" />
</span>
<?php } ?>
<?php } ?>
<?php if($products){ ?>
<?php foreach($products as $key => $product){ ?>
<span id="related-product-<?php echo $key; ?>" itemprop="isRelatedTo" itemscope itemtype="http://schema.org/Product">
<meta itemprop="name" content="<?php echo $product['name']; ?>" />
<meta itemprop="description" content="<?php echo $product['name']; ?>" />
<link itemprop="url" href="<?php echo $product['href']; ?>" />
<link itemprop="image" href="<?php echo $product['thumb']; ?>" />
<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
<meta itemprop="priceCurrency" content="<?php echo $code; ?>" />
<meta itemprop="price" content="<?php echo $product['price']; ?>" />
</span>
</span>
<?php } ?>
<?php } ?>
</span>
<!--microdatapro <?php echo $version; ?> product end [microdata] -->
<!--microdatapro <?php echo $version; ?> image start[microdata] -->
<span itemscope itemtype="http://schema.org/ImageObject">
<meta itemprop="name" content="<?php echo $name; ?>" />
<meta itemprop="description" content="<?php echo $name; ?>" />
<link itemprop="thumbnailUrl" href="<?php echo $thumb; ?>" />
<link itemprop="contentUrl" href="<?php echo $popup; ?>" />
<meta itemprop="author" content="<?php echo $author; ?>" />
<meta itemprop="datePublished" content="<?php echo $date_added; ?>">
</span>
<!--microdatapro <?php echo $version; ?> image end [microdata] -->
<?php if($images){ ?>
<!--microdatapro <?php echo $version; ?> gallery start[microdata] -->
<span itemscope itemtype="http://schema.org/ImageGallery">
<?php foreach($images as $image){ ?>
<span itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
<meta itemprop="name" content="<?php echo $name; ?>" />
<meta itemprop="description" content="<?php echo $name; ?>" />
<link itemprop="thumbnailUrl" href="<?php echo $image['thumb']; ?>" />
<link itemprop="contentUrl" href="<?php echo $image['popup']; ?>" />
<meta itemprop="author" content="<?php echo $author; ?>" />
<meta itemprop="datePublished" content="<?php echo $date_added; ?>">
</span>
<?php } ?>
</span>
<!--microdatapro <?php echo $version; ?> gallery end [microdata] -->
<?php } ?>
<?php } ?>
<?php if($syntax == "ld" or $syntax == "all"){ // syntax json-ld ?>
<!--microdatapro <?php echo $version; ?> product start [json-ld] -->
<script type="application/ld+json">
{
"@context": "http://schema.org",
"@type": "Product",
"url": "<?php echo $url; ?>",
<?php if($category){ ?>
"category": "<?php echo $category; ?>",
<?php } ?>
<?php if($popup){ ?>
"image": "<?php echo $popup; ?>",
<?php } ?>
<?php if($manufacturer){ ?>
"brand": "<?php echo $manufacturer; ?>",
"manufacturer": "<?php echo $manufacturer; ?>",
<?php } ?><?php if($model){ ?>
"model": "<?php echo $model; ?>",
<?php } ?><?php if($upc){ ?>
"gtin12": "<?php echo $upc; ?>",
<?php } ?><?php if($ean){ ?>
"gtin8": "<?php echo $ean; ?>",
<?php } ?><?php if($isbn){ ?>
"productID": "<?php echo $isbn; ?>",
<?php } ?><?php if($mpn){ ?>
"mpn": "<?php echo $mpn; ?>",
<?php } ?><?php if($sku){ ?>
"sku": "<?php echo $sku; ?>",
<?php } ?><?php if($rating && $reviewCount){ ?>
"aggregateRating": {
"@type": "AggregateRating",
"ratingValue": "<?php echo $rating; ?>",
"ratingCount": "<?php echo $reviewCount; ?>",
"reviewCount": "<?php echo $reviewCount; ?>",
"bestRating": "5",
"worstRating": "1"
},
<?php } ?>
"description": "<?php echo $description; ?>",
"name": "<?php echo $name; ?>",
"offers": {
"@type": "Offer",
"availability": "http://schema.org/<?php echo $stock; ?>",
"price": "<?php  echo $price; ?>",
"priceValidUntil": "<?php echo $price_valid; ?>",
"url": "<?php echo $url; ?>",
"priceCurrency": "<?php echo $code; ?>",
"itemCondition": "http://schema.org/NewCondition"
}<?php if ($reviews){ ?>,
"review": [
<?php foreach ($reviews as $key => $review_item){ ?>
{
"@type": "Review",
"author": "<?php echo $review_item['author']; ?>",
"datePublished": "<?php echo $review_item['date_added']; ?>",
"description": "<?php echo $review_item['text']; ?>",
"reviewRating": {
"@type": "Rating",
"bestRating": "5",
"ratingValue": "<?php echo $review_item['rating']?$review_item['rating']:5; ?>",
"worstRating": "1"
}
}<?php if($key != count($reviews)){ ?>,<?php } ?><?php } ?>
]<?php } ?>
<?php if($products){ ?>
,"isRelatedTo": [
<?php foreach($products as $key => $product){ ?>{
"@type": "Product",
"image": "<?php echo $product['thumb']; ?>",
"url": "<?php echo $product['href']; ?>",
"name": "<?php echo $product['name']; ?>",
"offers": {
"@type": "Offer",
"price": "<?php echo $product['price']; ?>",
"priceCurrency": "<?php echo $code; ?>"
}
}<?php if($key != count($products)){ ?>,<?php } ?><?php } ?>
]
<?php } ?>
<?php if ($attributes) { ?>
,"additionalProperty":[
<?php foreach ($attributes as $key => $attribute) { ?>
{
"@type": "PropertyValue",
"name": "<?php echo $attribute['name']; ?>",
"value": "<?php echo $attribute['text']; ?>"
}<?php if($key != count($attributes)){ ?>,<?php } ?>
<?php } ?>
]
<?php } ?>
}
</script>
<!--microdatapro <?php echo $version; ?> product end [json-ld] -->
<!--microdatapro <?php echo $version; ?> image start [json-ld] -->
<script type="application/ld+json">
{
"@context": "http://schema.org",
"@type": "ImageObject",
"author": "<?php echo $author; ?>",
"thumbnailUrl": "<?php echo $thumb; ?>",
"contentUrl": "<?php echo $popup; ?>",
"datePublished": "<?php echo $date_added; ?>",
"description": "<?php echo $name; ?>",
"name": "<?php echo $name; ?>"
}
</script>
<!--microdatapro <?php echo $version; ?> image end [json-ld] -->
<?php if($images){ ?>
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
"datePublished": "<?php echo $date_added; ?>",
"description": "<?php echo $name; ?>",
"name": "<?php echo $name; ?>"
}<?php if($key_i != count($images)){ ?>,<?php } ?>
<?php $key_i++; } ?>
]
}
</script>
<!--microdatapro <?php echo $version; ?> gallery end [json-ld] -->
<?php } ?>
<?php } ?>
