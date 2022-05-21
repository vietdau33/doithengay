const Request = {
    ajax : function(url, param, succFunc, doneFunc){
        if(typeof param == 'undefined'){
            param = {};
        }
        if(typeof succFunc == 'undefined' && typeof param == 'function'){
            succFunc = param;
            param = {};
        }
        const self = this;
        let config = {
            url: url,
            type: 'POST',
            dataType: 'json',
            data: param,
            success: function (result) {
                if (typeof succFunc == 'function') {
                    succFunc(result);
                }
            }
        };
        if(param instanceof FormData){
            config = Object.assign(config, {
                cache       : false,
                processData : false,
                contentType : false,
            });
        }
        const ajaxResult = $.ajax(config);
        ajaxResult.always(function(xhr){
            if(typeof doneFunc == 'function'){
                doneFunc(xhr);
            }
        });
        return ajaxResult;
    }
}
