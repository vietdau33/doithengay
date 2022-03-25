window.App = {
    init: function () {
        this.setPositionCopyright();
        this.setSeePasswordInput();
    },
    setPositionCopyright: function () {
        let wh = window.innerHeight;
        let ch = $('html').height();
        if (ch < wh) {
            $('body').addClass('full-height');
        }
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
    }
}

window.addEventListener('DOMContentLoaded', function () {
    window.App.init();
});
