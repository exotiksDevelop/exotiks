<?php echo $header; ?><?php echo $column_left; ?>
    <div id="content">
        <div class="page-header">
            <div class="container-fluid">
                <div class="pull-right">
                    <button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary submit-forms">
                        <i class="fa fa-save"></i></button>
                    <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default">
                        <i class="fa fa-reply"></i></a>
                </div>
                <h1><?php echo $heading_title; ?></h1>
                <ul class="breadcrumb">
                    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                        <li>
                            <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div class="container-fluid">
            <div id="warning" class="alert alert-danger hidden"><i class="fa fa-exclamation-circle"></i>
                <span></span>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <div id="success" class="alert alert-success hidden"><i class="fa fa-exclamation-circle"></i>
                <span></span>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php if (!$check_license) { ?>
                <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_license; ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php } ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
                </div>
                <div class="panel-body">
                    <ul id="tabs" class="nav nav-tabs">
                        <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                        <li><a href="#tab-popups" data-toggle="tab"><?php echo $tab_popup; ?></a></li>
                        <li><a href="#tab-messages" data-toggle="tab"><?php echo $tab_messages; ?></a></li>
                        <li><a href="#tab-redirects" data-toggle="tab"><?php echo $tab_redirects; ?></a></li>
                        <li><a href="#tab-currencies" data-toggle="tab"><?php echo $tab_currencies; ?></a></li>
                        <li><a id="tab-regions-btn" href="#tab-regions" data-toggle="tab"><?php echo $tab_regions; ?></a></li>
                    </ul>
                    <div class="tab-content form-horizontal">
                        <div class="tab-pane active" id="tab-general">
                            <?php include('geoip/general.tpl'); ?>
                        </div>
                        <div class="tab-pane" id="tab-popups">
                            <?php include('geoip/popup.tpl'); ?>
                        </div>
                        <div class="tab-pane" id="tab-messages">
                            <?php include('geoip/messages.tpl'); ?>
                        </div>
                        <div class="tab-pane" id="tab-redirects">
                            <?php include('geoip/redirects.tpl'); ?>
                        </div>
                        <div class="tab-pane" id="tab-currencies">
                            <?php include('geoip/currencies.tpl'); ?>
                        </div>
                        <div class="tab-pane" id="tab-regions">
                            <?php include('geoip/zone_fias.tpl'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php echo $footer; ?>
<script type="text/javascript"><!--
    function savePopups() {
        var form = $('#tab-popups').find('form');
        form.find('.text-danger').remove();
        $.post(form.attr('action'), form.serialize(),
            function(json) {
                if (json.errors) {
                    for (i in json.errors.cities) {
                        $('#city-row' + i).find('.row .col-md-4:first').append('<p class="text-danger">' + json.errors.cities[i] + '</p>');
                    }
                    $('#tabs').find('a[href="#tab-popups"]').tab('show');
                    return;
                }

                saveMessages();
            }, 'json');
    }

    function saveMessages() {
        var form = $('#tab-messages').find('form');
        form.find('.text-danger').remove();
        $.post(form.attr('action'), form.serialize(),
            function(json) {
                if (json.errors) {
                    for (i in json.errors.key) {
                        $('#rule-row' + i).find('.row .col-md-3:first').append('<p class="text-danger">' + json.errors.key[i] + '</p>');
                    }
                    for (i in json.errors.fias) {
                        $('#rule-row' + i).find('.row .col-md-3:eq(1)').append('<p class="text-danger">' + json.errors.fias[i] + '</p>');
                    }
                    $('#tabs').find('a[href="#tab-messages"]').tab('show');
                    return;
                }

                saveRedirects();
            }, 'json');
    }

    function saveRedirects() {
        var form = $('#tab-redirects').find('form');
        form.find('.text-danger').remove();
        $.post(form.attr('action'), form.serialize(),
            function(json) {
                if (json.errors) {
                    for (i in json.errors.fias) {
                        $('#redirect-row' + i).find('.row .col-md-4:eq(0)').append('<p class="text-danger">' + json.errors.fias[i] + '</p>');
                    }
                    for (i in json.errors.subdomain) {
                        $('#redirect-row' + i).find('.row .col-md-4:eq(1)').append('<p class="text-danger">' + json.errors.subdomain[i] + '</p>');
                    }
                    $('#tabs').find('a[href="#tab-redirects"]').tab('show');
                    return;
                }

                saveCurrencies();
            }, 'json');
    }

    function saveCurrencies() {
        var form = $('#tab-currencies').find('form');
        form.find('.text-danger').remove();
        $.post(form.attr('action'), form.serialize(),
            function(json) {
                if (json.errors) {
                    for (i in json.errors.country) {
                        $('#currency-row' + i).find('.row .col-md-4:eq(0)').append('<p class="text-danger">' + json.errors.country[i] + '</p>');
                    }
                    for (i in json.errors.code) {
                        $('#currency-row' + i).find('.row .col-md-4:eq(1)').append('<p class="text-danger">' + json.errors.code[i] + '</p>');
                    }
                    $('#tabs').find('a[href="#tab-currencies"]').tab('show');
                    return;
                }

                saveRegions();
            }, 'json');
    }

    function saveRegions() {
        var form = $('#tab-regions').find('form');
        form.find('.text-danger').remove();
        $.post(form.attr('action'), form.serialize(),
            function(json) {
                if (json.errors) {
                    for (i in json.errors.country) {
                        $('#zone-fias-row' + i).find('.row .col-md-4:eq(0)').append('<p class="text-danger">' + json.errors.country[i] + '</p>');
                    }
                    $('#tabs').find('a[href="#tab-regions"]').tab('show');
                    return;
                }

                $('#success').removeClass('hidden').find('span').text(json.success);
            }, 'json');
    }

    $(function() {
        $('.submit-forms').click(function() {
            $('#warning, #success').addClass('hidden').find('span').text('');
            var form = $('#tab-general').find('form');
            $.post(form.attr('action'), form.serialize() + '&' + $('.for-general-form :input').serialize(),
                function(json) {
                    if (json.warning) {
                        $('#warning').removeClass('hidden').find('span').text(json.warning);
                        $('#tabs').find('a[href="#tab-general"]').tab('show');
                        return;
                    }

                    savePopups();
                }, 'json');
        });

        $('form').submit(function(e) {
            e.preventDefault();
        });
    });

    var xhr;

    $('#rules, #redirects, #cities').on('focus', '.row-fias-name', function() {
        if (!$(this).data('autocomplete')) {
            addAutocomplete($(this));
        }
    });

    $('.row-fias-name').each(function() {
        addAutocomplete($(this));
    });

    function addAutocomplete(el) {
        el.autocomplete({
            'source': function(request, response) {
                if (xhr) {
                    xhr.abort();
                }

                request = $.trim(request);
                if (request && request.length > 2) {
                    xhr = $.get('index.php?route=module/geoip/search&token=<?php echo $token; ?>&term=' + encodeURIComponent(request),
                            function(json) {
                                response(json);
                            }, 'json');
                }
                else {
                    response([]);
                }
            },
            'select': function(item) {
                el.val(item.value);
                el.siblings('.row-fias-id').val(item.fias_id);
            }
        });
        el.data('autocomplete', true);
    }
//--></script>
