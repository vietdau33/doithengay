window.App = {
    init: function () {
        this.setPositionCopyright();
    },
    setPositionCopyright: function(){
        let wh = window.innerHeight;
        let ch = $('html').height();
        if(ch < wh) {
            $('body').addClass('full-height');
        }
    }
}

window.addEventListener('DOMContentLoaded', function () {
    window.App.init();
});
