window.App = {
    init: function () {
        this.setSeePasswordInput();
        this.setErrorsUIView();
        this.setPositionCopyright();
        this.setClickCheckboxButton();
        this.setAutoSize();
        this.setEventClickMenu();
    },
    setPositionCopyright: function () {
        let cprh = $('#copyright').height();
        let hh = $('#header').height();
        $('#main-contents').css({
            'min-height': 'calc(100vh - ' + cprh + 'px)'
        });
        $('#auth-content').css({
            'padding-top': (hh + 10) + 'px'
        })
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
    setClickCheckboxButton: function () {
        let triggerChecked = function () {
            $('form input[type="checkbox"]:checked, form input[type="radio"]:checked').closest('label').addClass('checked');
            $('form input[type="checkbox"]:not(:checked), form input[type="radio"]:not(:checked)').closest('label').removeClass('checked');
        }
        setTimeout(triggerChecked, 0);
        $('input[type="checkbox"], input[type="radio"]').on('click', function () {
            setTimeout(triggerChecked, 50);
        });
    },
    setAutoSize: function () {
        if(typeof autosize == 'function') {
            autosize($('textarea.autosize'));
        }
    },
    setEventClickMenu : function(){
        $(document).on('click', function(event){
            if($(event.target).closest('.bar-user-menu').length <= 0) {
                $('.bar-user-icon').removeClass('show');
                $('.bar-user-icon .menu-user').hide(300);
            }
        });
        $('.bar-user-icon').on('click', function(event) {
            if(!$(event.target).is('.bar-user-icon')) {
                return;
            }
            if($(this).hasClass('show')){
                $('.bar-user-icon').removeClass('show');
                $('.bar-user-icon .menu-user').hide(300);
            }else{
                $('.bar-user-icon').addClass('show');
                $('.bar-user-icon .menu-user').show(300);
            }
        });
    }
}

window.addEventListener('DOMContentLoaded', function () {
    window.App.init();
});
