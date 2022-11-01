if (typeof(dvbusiness) === 'undefined') {
    var dvbusiness = {};
}

/**
 * Объект "Frontend Http Client"
 * @type {dvbusiness.frontendHttpClient}
 *
 * Интерфейс колбеков ответа:
 * responseCallback(success, error, parameterErrors, rawResponse);
 *
 */
dvbusiness.frontendHttpClient = new (function() {
    /** @type {dvbusiness.frontendHttpClient} */
    var _self = this;

    /** @type {string|null} */
    var _userToken = null;

    function getJsonFromLocation() {
        url = location.search;

        var query = url.substr(1);
        var result = {};
        query.split("&").forEach(function (part) {
            var item = part.split("=");
            result[item[0]] = decodeURIComponent(item[1]);
        });

        return result;
    }

    var queryParamsJson = getJsonFromLocation();
    if (typeof(queryParamsJson['user_token']) !== 'undefined') {
        _userToken = queryParamsJson['user_token'];
    }

    /**
     * Функция для определения префикса в урле. Некоторые магазины используют урл типа https://host.com/prefix
     */
    function getUrlPrefix() {
        var fullUrl =  window.location.href;
        var prefix = fullUrl.replace(/(http(s)?\:\/\/)/g, '')
                .replace(/\/admin\/.*/g, '')
                .replace(window.location.hostname, '')
        ;
        return prefix;
    }

    function sendRequest(method, url, postData, responseCallback) {
        var handler = function(response, textStatus, jqXHR) {
            var parameterErrors = null;
            var error = null;
            var isSuccessful = true;
            var responseJson = typeof(response.responseJSON) !== 'undefined' ? response.responseJSON : response;

            if (typeof(responseJson.error) !== 'undefined' || jqXHR.status != 200) {
                error = response.error;
                isSuccessful = false;
            }

            if (typeof(responseJson.parameter_errors) !== 'undefined') {
                parameterErrors = responseJson.parameter_errors;
            }

            if (responseCallback && typeof(responseCallback) !== 'undefined') {
                responseCallback(isSuccessful, error, parameterErrors, responseJson);
            }
        };

        var headers = {};
        if (_userToken) {
            url = url + '&user_token=' + encodeURIComponent(_userToken);
        }

        var urlPrefix = getUrlPrefix();
        if (urlPrefix) {
            url = urlPrefix + url;
        }

        if (method === 'POST') {
            $.ajax(
                {
                    type: 'POST',
                    url: url,
                    data: JSON.stringify(postData),
                    dataType: 'json',
                    headers: headers
                }
            ).always(handler);
        } else {
            $.ajax({type: 'GET', url: url, dataType: 'json'}).always(handler);
        }
    }

    this.calculateOrder = function(data, responseCallback) {
        sendRequest('POST', '/admin/index.php?route=extension/shipping/dvbusiness/orderFormCalculate', data, responseCallback);
    };

    this.createOrder = function(data, responseCallback) {
        sendRequest('POST', '/admin/index.php?route=extension/shipping/dvbusiness/orderFormCreate', data, responseCallback);
    };

    this.saveWarehouse = function(data, responseCallback) {
        sendRequest('POST', '/admin/index.php?route=extension/shipping/dvbusiness/warehouseFormSave', data, responseCallback);
    };

    this.deleteWarehouse = function(data, responseCallback) {
        sendRequest('POST', '/admin/index.php?route=extension/shipping/dvbusiness/warehouseDelete', data, responseCallback);
    };

    this.createAuthToken = function(data, responseCallback) {
        sendRequest('POST', '/admin/index.php?route=extension/shipping/dvbusiness/createAuthToken', data, responseCallback);
    };

    this.storeSettings = function(data, responseCallback) {
        sendRequest('POST', '/admin/index.php?route=extension/shipping/dvbusiness/storeSettings', data, responseCallback);
    };

    this.getPaymentMethods = function(data, responseCallback) {
        sendRequest('POST', '/admin/index.php?route=extension/shipping/dvbusiness/getPaymentMethods', data, responseCallback);
    };

    this.setLastFinishedWizardStep = function(data, responseCallback) {
        sendRequest('POST', '/admin/index.php?route=extension/shipping/dvbusiness/setWizardLastFinishedStep', data, responseCallback);
    };

    this.dostavistaClientLogout = function(responseCallback) {
        sendRequest('POST', '/admin/index.php?route=extension/shipping/dvbusiness/dostavistaLogout', {}, responseCallback);
    };
});

