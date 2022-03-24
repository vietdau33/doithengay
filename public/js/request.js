const Request = {
    ajax: function (url, param, succFunc, doneFunc) {
        if (typeof param == 'undefined') {
            param = {};
        }
        if (typeof succFunc == 'undefined' && typeof param == 'function') {
            succFunc = param;
            param = {};
        }
        const self = this;
        let config = {
            url: url,
            type: 'POST',
            dataType: 'json',
            data: param,
            beforeSend: function () {
                self.showPendingRequest();
            },
            success: function (result) {
                if (typeof succFunc == 'function') {
                    succFunc(result);
                }
            },
            error: function (error) {
                console.log(error.responseText);
            }
        };
        if (param instanceof FormData) {
            config = Object.assign(config, {
                cache: false,
                processData: false,
                contentType: false,
            });
        }
        const ajaxResult = $.ajax(config);
        ajaxResult.always(function (xhr) {
            if (typeof doneFunc == 'function') {
                doneFunc(xhr);
            }
            self.hidePendingRequest();
        });

        return ajaxResult;
    },
    showPendingRequest: function () {
        $(".pending-request").addClass("show");
    },
    hidePendingRequest: function () {
        $(".pending-request").removeClass("show");
    }
}
