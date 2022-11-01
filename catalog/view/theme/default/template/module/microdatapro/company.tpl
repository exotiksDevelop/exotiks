<?php //microdatapro 7.3 ?>
<?php if($company_syntax == "md" or $company_syntax == "all"){ // syntax md ?>
<!--microdatapro <?php echo $version; ?> company start [microdata] -->
<span itemscope itemtype="http://schema.org/<?php echo $store_type; ?>">
<meta itemprop="name" content="<?php echo $organization_name; ?>" />
<link itemprop="url" href="<?php echo $organization_url; ?>" />
<link itemprop="image" href="<?php echo $organization_logo; ?>" />
<meta itemprop="email" content="<?php echo $organization_email; ?>" />
<meta itemprop="priceRange" content="<?php echo $code; ?>" />
<?php if ($organization_map){ ?>
<meta itemprop="hasMap" content="<?php echo $organization_map; ?>" />
<?php } ?>
<?php if ($organization_phones){ ?>
<?php foreach($organization_phones as $phone){ ?>
<meta itemprop="telephone" content="<?php echo $phone; ?>" />
<?php } ?>
<?php } ?>
<?php if ($organization_groups){ ?>
<?php foreach($organization_groups as $group){ ?>
<link itemprop="sameAs" href="<?php echo $group; ?>" />
<?php } ?>
<?php } ?>
<?php if ($organization_locations){ ?>
<?php foreach($organization_locations as $location){ ?>
<span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
<meta itemprop="addressLocality" content="<?php echo $location['addressLocality']; ?>" />
<meta itemprop="postalCode" content="<?php echo $location['postalCode']; ?>" />
<meta itemprop="streetAddress" content="<?php echo $location['streetAddress']; ?>" />
</span>
<span itemprop="location" itemscope itemtype="http://schema.org/Place">
<meta itemprop="name" content="<?php echo $organization_name; ?>" />
<?php if ($organization_phones){ ?>
<?php foreach($organization_phones as $phone){ ?>
<meta itemprop="telephone" content="<?php echo $phone; ?>" />
<?php } ?>
<?php } ?>
<span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
<meta itemprop="addressLocality" content="<?php echo $location['addressLocality']; ?>" />
<meta itemprop="postalCode" content="<?php echo $location['postalCode']; ?>" />
<meta itemprop="streetAddress" content="<?php echo $location['streetAddress']; ?>" />
</span>
<span itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">
<meta itemprop="latitude" content="<?php echo $location['latitude']; ?>" />
<meta itemprop="longitude" content="<?php echo $location['longitude']; ?>" />
<span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
<meta itemprop="streetAddress" content="<?php echo $location['streetAddress']; ?>" />
<meta itemprop="addressLocality" content="<?php echo $location['addressLocality']; ?>" />
<meta itemprop="postalCode" content="<?php echo $location['postalCode']; ?>" />
</span>
</span>
</span>
<?php } ?>
<?php } ?>
<span itemprop="potentialAction" itemscope itemtype="http://schema.org/SearchAction">
<meta itemprop="target" content="<?php echo $organization_url; ?>index.php?route=product/search&search={search_term_string}"/>
<input type="hidden" itemprop="query-input" name="search_term_string">
</span>
<?php if($organization_oh){ ?>
<?php foreach($organization_oh as $day => $oh){ ?>
<span itemprop="openingHoursSpecification" itemscope itemtype="http://schema.org/OpeningHoursSpecification">
<link itemprop="dayOfWeek" href="http://schema.org/<?php echo $day; ?>" />
<meta itemprop="opens" content="<?php echo $oh['open']; ?>" />
<meta itemprop="closes" content="<?php echo $oh['close']; ?>" />
</span>
<?php } ?>
<?php } ?>
</span>
<!--microdatapro <?php echo $version; ?> company end [microdata] -->
<?php } //end syntax md ?>
<?php if($company_syntax == "ld" or $company_syntax == "all"){ //syntax json-ld ?>
<!--microdatapro <?php echo $version; ?> company start [json-ld] -->
<script type="application/ld+json">
{
"@context": "http://schema.org",
"@type": "<?php echo $store_type; ?>",
"name": "<?php echo $organization_name; ?>",
"url": "<?php echo $organization_url; ?>",
"image": "<?php echo $organization_logo; ?>",
<?php if (isset($organization_phones[0])){ ?>
"telephone" : "<?php echo $organization_phones[0]; ?>",
<?php } ?>
"email": "<?php echo $organization_email; ?>",
"priceRange": "<?php echo $code; ?>",
<?php if ($organization_locations){ ?>
<?php foreach($organization_locations as $location){ ?>
"address": {
"@type": "PostalAddress",
"addressLocality": "<?php echo $location['addressLocality']; ?>",
"postalCode": "<?php echo $location['postalCode']; ?>",
"streetAddress": "<?php echo $location['streetAddress']; ?>"
},
"location": {
"@type": "Place",
"address": {
"@type": "PostalAddress",
"addressLocality": "<?php echo $location['addressLocality']; ?>",
"postalCode": "<?php echo $location['postalCode']; ?>",
"streetAddress": "<?php echo $location['streetAddress']; ?>"
},
"geo": {
"@type": "GeoCoordinates",
"latitude": "<?php echo $location['latitude']; ?>",
"longitude": "<?php echo $location['longitude']; ?>"
}
},
<?php break; } ?>
<?php } ?>
"potentialAction": {
"@type": "SearchAction",
"target": "<?php echo $organization_url; ?>index.php?route=product/search&search={search_term_string}",
"query-input": "required name=search_term_string"
}<?php if ($organization_phones){ ?>,
"contactPoint" : [
<?php $pi = 1; foreach($organization_phones as $phone){ ?>{
"@type" : "ContactPoint",
"telephone" : "<?php echo $phone; ?>",
"contactType" : "customer service"
}<?php if($pi != count($organization_phones)){ ?>,<?php } ?><?php $pi++; } ?>]<?php } ?><?php if ($organization_groups){ ?>,
"sameAs" : [
<?php $gi = 1; foreach($organization_groups as $group){ ?>
"<?php echo $group; ?>"<?php if($gi != count($organization_groups)){ ?>,<?php } ?>
<?php $gi++; } ?>
]<?php } ?><?php if($organization_oh){ ?>,
"openingHoursSpecification":[
<?php $oh_i = 1; foreach($organization_oh as $day => $oh){ ?>
{
"@type": "OpeningHoursSpecification",
"dayOfWeek": "<?php echo $day; ?>",
"opens": "<?php echo $oh['open']; ?>",
"closes": "<?php echo $oh['close']; ?>"
}<?php if($oh_i != count($organization_oh)){ ?>,<?php } ?>
<?php $oh_i++; } ?>
]
<?php } ?>
}
</script>
<!--microdatapro <?php echo $version; ?> company end [json-ld] -->
<?php } //end syntax json-ld ?>
<?php if($config_hcard){ ?>
<!--microdatapro <?php echo $version; ?> company start [hCard] -->
<span class="vcard">
<span class="org"><span class="value-title" title="<?php echo $organization_name; ?>"></span></span>
<span class="url"><span class="value-title" title="<?php echo $organization_url; ?>"></span></span>
<?php if ($organization_locations){ ?>
<?php foreach($organization_locations as $location){ ?>
<span class="adr">
<span class="locality"><span class="value-title" title="<?php echo $location['addressLocality']; ?>"></span></span>
<span class="street-address"><span class="value-title" title="<?php echo $location['streetAddress']; ?>"></span></span>
<span class="postal-code"><span class="value-title" title="<?php echo $location['postalCode']; ?>"></span></span>
</span>
<span class="geo">
<span class="latitude"><span class="value-title" title="<?php echo $location['latitude']; ?>"></span></span>
<span class="longitude"><span class="value-title" title="<?php echo $location['longitude']; ?>"></span></span>
</span>
<?php } ?>
<?php } ?>
<?php if ($organization_phones){ ?>
<?php foreach($organization_phones as $phone){ ?>
<span class="tel"><span class="value-title" title="<?php echo $phone; ?>"></span></span>
<?php } ?>
<?php } ?>
<span class="photo"><span class="value-title" title="<?php echo $organization_logo; ?>"></span></span>
</span>
<!--microdatapro <?php echo $version; ?> company end [hCard ] -->
<?php } ?>
