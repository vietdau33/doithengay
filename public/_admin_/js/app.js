window.App = {
    init: function () {
        this.setSeePasswordInput();
        this.setErrorsUIView();
        this.setAutoSize();
        this.preventEnterSubmit();
    },
    setSeePasswordInput: function () {
        let clsEyeShow = 'fa-eye';
        let clsEyeHide = 'fa-eye-slash';
        let elEye = $('<i />').addClass('fas show-hide-pwd');

        elEye.on('click', function () {
            let parent = $(this).parent();
            let elPwd = parent.find('input');

            if ($(this).hasClass(clsEyeShow)) {
                $(this).removeClass(clsEyeShow).addClass(clsEyeHide);
                elPwd.attr('type', 'password');
            } else {
                $(this).removeClass(clsEyeHide).addClass(clsEyeShow);
                elPwd.attr('type', 'text');
            }
        });

        $('input[type="password"]:not(.setted-see-password)').each(function () {
            let elEyeCopy = elEye.clone(true, true);
            elEyeCopy.addClass('fa-eye-slash');
            $(this).addClass('setted-see-password');
            $(this).parent().addClass('position-relative').append(elEyeCopy);
        });
    },
    setErrorsUIView: function () {
        if (typeof window.errors != 'object') {
            return;
        }
        if (Object.keys(window.errors).length <= 0) {
            return;
        }
        let errEl = $('<p />').addClass('error');
        for (let key in window.errors) {
            let error = window.errors[key];
            let el = $('[name="' + key + '"]');
            if (el.length <= 0) {
                continue;
            }
            if (el.attr('type') == 'radio' || el.attr('type') == 'checkbox') {
                continue;
            }
            el.off('input.remove_error');
            el.on('input.remove_error', function () {
                el.parent().find('.error').fadeOut(300, function () {
                    $(this).remove();
                })
            })
            el.parent().find('.error').remove();
            for (let i in error) {
                el.before(errEl.clone().text(error[i]));
            }
            delete window.errors[key];
        }

        $('form').find('#area-error-ui-show').remove();

        if (Object.keys(window.errors).length <= 0) {
            return;
        }

        let errArea = $('<div />').addClass('alert alert-warning').attr('id', 'area-error-ui-show');
        let pText = $('<p />').addClass('m-0');
        for (let key in window.errors) {
            let error = window.errors[key];
            for (let i in error) {
                errArea.append(pText.clone().text(error));
            }
            delete window.errors[key];
        }
        $('form').prepend(errArea);
    },
    setAutoSize: function () {
        if (typeof autosize == 'function') {
            autosize($('textarea.autosize'));
        }
    },
    preventEnterSubmit: function () {
        $(document).on("keydown", ":input:not(textarea)", function (event) {
            if (event.key == "Enter") {
                event.preventDefault();
            }
        });
    }
}

window.addEventListener('DOMContentLoaded', function () {
    window.App.init();
});
