window.App = {
    init: function () {
        this.setSeePasswordInput();
        this.setErrorsUIView();
        this.setPositionCopyright();
    },
    setPositionCopyright: function () {
        let cprh = $('#copyright').height();
        let hh = $('#header').height();
        $('#main-contents').css({
            'min-height': 'calc(100vh - ' + (cprh + hh) + 'px)'
        });
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
        if (window.errors.length <= 0) {
            return;
        }
        let errEl = $('<p />').addClass('error');
        for (let key in window.errors) {
            let error = window.errors[key];
            let el = $('input[name="' + key + '"]');
            if (el.length <= 0) {
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
        }
    }
}

window.addEventListener('DOMContentLoaded', function () {
    window.App.init();
});
