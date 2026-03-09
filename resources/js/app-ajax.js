// resources/js/app-ajax.js

// Global AJAX helper
export function appAjax(options) {
    const defaults = {
        method: 'GET',
        data: {},
        showLoader: true,
        onSuccess: function () {},
        onError: function () {},
        onComplete: function () {}
    };

    const settings = $.extend({}, defaults, options);

    let ajaxOptions = {
        url: settings.url,
        method: settings.method,
        data: settings.data,
        beforeSend: function () {
            if (settings.showLoader) {
                $('#global-loader').fadeIn(100);
            }
        },
        success: function (response) {
            settings.onSuccess(response);
        },
        error: function (xhr) {
            console.error('AJAX Error:', xhr);
            settings.onError(xhr);
        },
        complete: function () {
            if (settings.showLoader) {
                $('#global-loader').fadeOut(100);
            }
            settings.onComplete();
        }
    };

    // If data is FormData, adjust options for file uploads
    if (settings.data instanceof FormData) {
        ajaxOptions.processData = false;
        ajaxOptions.contentType = false;
    }

    $.ajax(ajaxOptions);
}

// Optionally attach to window to use in inline scripts
window.appAjax = appAjax;