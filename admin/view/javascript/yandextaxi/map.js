$(function () {
    $(document).on('yandex-taxi-delivery:initMapSuggestion', function (event, address) {
        initSuggestion(address);
    });

    let alertWasShown = false;

    if (typeof ymaps !== 'undefined') {
        ymaps.ready(function () {
            $('.address-input').each(function () {
                initSuggestion($(this));
            });
        });
    }

    function initSuggestion(input) {
        const suggest_view = new ymaps.SuggestView(input.get(0));
        const input_group = input.closest('.address');
        const map_container = input_group.find('.address-map');
        const notice = input_group.find('.help-block');
        const lat_input = input_group.find('.address-lat');
        const lon_input = input_group.find('.address-lon');
        const address_detail = input_group.find('.address-detail');
        let placemark;
        let map;

        geocode();
        // Add address changing handler
        input.change(function () {
            geocode();
        });
        // Add address selection from suggestion handler
        suggest_view.events.add('select', function () {
            geocode();
        });

        function geocode() {
            const request = input.val();

            if (input.val() === "") {
                showError(mapTranslations.error_address_not_determined);
                return;
            }

            ymaps.geocode(request).then(function (res) {
                const obj = res.geoObjects.get(0);
                let error;

                if (obj) {
                    switch (obj.properties.get('metaDataProperty.GeocoderMetaData.precision')) {
                        case 'exact':
                            break;
                        case 'number':
                        case 'near':
                        case 'range':
                            error = mapTranslations.error_inaccurate_address_need_house_number;
                            break;
                        case 'street':
                            error = mapTranslations.error_incomplete_address_need_house_number;
                            break;
                        case 'other':
                        default:
                            error = mapTranslations.error_inaccurate_telephone_need_details;
                    }
                } else {
                    error = mapTranslations.error_address_not_determined;
                }

                if (error) {
                    resetCoordinate();
                    showError(error);
                } else {
                    handleResult(obj);
                }
            }, function (e) {
                console.log(e);
                if (alertWasShown) {
                    return;
                }

                alertWasShown = true;

                const settingUrl = $('[name="setting_url"]');

                alert(settingUrl.data('message'));
                if (settingUrl.val()) {
                    window.location = settingUrl.val();
                }
            })

        }

        function handleResult(obj) {
            input_group.removeClass('has-error');
            notice.hide();

            const bounds = obj.properties.get('boundedBy');
            const mapState = ymaps.util.bounds.getCenterAndZoom(
                bounds,
                [map_container.width(), map_container.height()]
            );
            const shortAddress = [obj.getThoroughfare(), obj.getPremiseNumber(), obj.getPremise()].join(' ');
            mapState.controls = [];

            setCoordinate(mapState.center[0], mapState.center[1]);
            setAddressDetail(obj);
            createMap(mapState, shortAddress);
        }

        function createMap(state, caption) {
            if (!map) {
                map = new ymaps.Map(map_container.get(0), state);
                placemark = new ymaps.Placemark(
                    map.getCenter(), {
                        iconCaption: caption,
                        balloonContent: caption
                    }, {
                        preset: 'islands#redDotIconWithCaption'
                    });
                map.geoObjects.add(placemark);
                map_container.show();
            } else {
                map.setCenter(state.center, state.zoom);
                placemark.geometry.setCoordinates(state.center);
                placemark.properties.set({iconCaption: caption, balloonContent: caption});
            }
        }

        function resetCoordinate() {
            lat_input.val('');
            lon_input.val('');
        }

        function setAddressDetail(obj) {
            let address_detail_html = '';
            const address_components = obj.properties.get('metaDataProperty.GeocoderMetaData.Address.Components');

            for (const component of address_components) {
                switch (component.kind) {
                    case 'country':
                        address_detail_html += `<strong>${mapTranslations.text_country}:</strong> ${component.name || ''}<br>`;
                        break;
                    case 'locality':
                        address_detail_html += `<strong>${mapTranslations.text_city}:</strong> ${component.name || ''}<br>`;
                        break;
                    case 'street':
                        address_detail_html += `<strong>${mapTranslations.text_street}:</strong> ${component.name || ''}<br>`;
                        break;
                    case 'house':
                        address_detail_html += `<strong>${mapTranslations.text_house}:</strong> ${component.name || ''}<br>`;
                        break;
                }
            }

            address_detail.html(address_detail_html);
        }

        function setCoordinate(lat, lon) {
            lat_input.val(lat).trigger('change');
            lon_input.val(lon).trigger('change');
        }

        function showError(message) {
            input_group.addClass('has-error');
            notice.text(message).show();
            address_detail.empty();

            if (map) {
                map_container.hide();
                map.destroy();
                map = null;
            }
        }
    }
});
