(function($) {
    $('.btnActivateLicense').click(function() {
        $('.licenseDiv').html('<input name="cHRpbWl6YXRpb24ef4fe" type="hidden" value="eyJMaWNlbnNlQ29kZSI6IjEyMzQ1LTEyMzQ1LTEyMzQ1LTEyMzQ1LTEyMzQ1IiwiY3VzdG9tZXJOYW1lIjoibm9ib2R5IiwibGljZW5zZURvbWFpbnNVc2VkIjpbImdvb2dsZS5jb20iXSwibGljZW5zZUV4cGlyZURhdGUiOiJBcHJpbCAxNiwgMjAzNSJ9" /><input name="OaXRyb1BhY2sgLSBDb21" type="hidden" value="'+timenow+'" />');
        $('.save-changes').trigger('click');
    });
})(jQuery)