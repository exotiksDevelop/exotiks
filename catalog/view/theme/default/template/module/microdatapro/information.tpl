<?php //microdatapro 7.3 ?>
<?php if($breadcrumbs){ ?>
<!--microdatapro <?php echo $version; ?> breadcrumb start [microdata & json-ld] -->
<?php if($syntax == "ld" or $syntax == "all"){ ?>
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
<?php } ?>
<?php if($syntax == "md" or $syntax == "all"){ ?>
<span itemscope itemtype="http://schema.org/BreadcrumbList">
<?php foreach ($breadcrumbs as $key => $breadcrumb) { ?>
<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
<link itemprop="item" href="<?php echo $breadcrumb['href']; ?>">
<meta itemprop="name" content="<?php echo $breadcrumb['text']; ?>" />
<meta itemprop="position" content="<?php echo $key; ?>" />
</span>
<?php } ?>
</span>
<?php } ?>
<!--microdatapro <?php echo $version; ?> breadcrumb end [microdata & json-ld] -->
<?php } ?>
<?php if($syntax == "ld" or $syntax == "all"){ // syntax md ?>
<!--microdatapro <?php echo $version; ?> information start [microdata] -->
<script type="application/ld+json">
{
"@context": "http://schema.org",
"@type": "NewsArticle",
"mainEntityOfPage":{
"@type":"WebPage",
"@id": "<?php echo $url; ?>"
},
"headline": "<?php echo $name; ?>",
"image": {
"@type": "ImageObject",
"url": "<?php echo $logo; ?>",
"width": "<?php echo $image_width; ?>",
"height": "<?php echo $image_height; ?>"
},
"datePublished": "<?php echo $date; ?>",
"dateModified": "<?php echo $date; ?>",
"author": "<?php echo $author; ?>",
"publisher": {
"@type": "Organization",
"name": "<?php echo $author; ?>",
"logo": {
"@type": "ImageObject",
"url": "<?php echo $logo; ?>"
}
},
"description":"<?php echo $description; ?>"
}
</script>
<!--microdatapro <?php echo $version; ?> information end [microdata] -->
<?php } ?>
<?php if($syntax == "md" or $syntax == "all"){ ?>
<!--microdatapro <?php echo $version; ?> information start [json-ld] -->
<span itemscope itemtype="http://schema.org/NewsArticle">
<meta itemscope itemprop="mainEntityOfPage"  itemType="https://schema.org/WebPage" itemid="<?php echo $url; ?>"/>
<meta itemprop="headline" content="<?php echo $name; ?>" />
<span itemprop="author" itemscope itemtype="https://schema.org/Person"><meta itemprop="name" content="<?php echo $author; ?>" /></span>
<meta itemprop="description" content="<?php echo $description; ?>">
<span itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
<link itemprop="contentUrl" href="<?php echo $logo; ?>" />
<link itemprop="url" href="<?php echo $logo; ?>">
<meta itemprop="width" content="<?php echo $image_width; ?>">
<meta itemprop="height" content="<?php echo $image_height; ?>">
</span>
<?php if($organization){ ?>
<span itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
<?php foreach($organization as $address){ ?>
<span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
<meta itemprop="addressLocality" content="<?php echo $address['addressLocality']; ?>" />
<meta itemprop="postalCode" content="<?php echo $address['postalCode']; ?>" />
<meta itemprop="streetAddress" content="<?php echo $address['streetAddress']; ?>" />
</span>
<?php } ?>
<?php if ($phones){ ?>
<?php foreach($phones as $phone){ ?>
<meta itemprop="telephone" content="<?php echo $phone; ?>" />
<?php } ?>
<?php } ?>
<span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
<link itemprop="url" href="<?php echo $logo; ?>">
<link itemprop="contentUrl" href="<?php echo $logo; ?>" />
</span>
<meta itemprop="name" content="<?php echo $author; ?>" />
</span>
<?php } ?>
<meta itemprop="datePublished" content="<?php echo $date; ?>" />
<meta itemprop="dateModified" content="<?php echo $date; ?>" />
</span>
<!--microdatapro <?php echo $version; ?> information end [json-ld] -->
<?php } ?>
