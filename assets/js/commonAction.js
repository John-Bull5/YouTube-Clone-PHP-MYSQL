$(document).ready(function () {
    $('.navShowHide').on('click', function () {
        //grab the main section element

        var main = $('#mainSectionContainer');

        //grab the side nav

        var nav = $('#sideNavContainer');

        if (main.hasClass('leftPadding')) {
            nav.hide();
        }
        else {
            nav.show();
        }

        //toggle the class for main section

        main.toggleClass('leftPadding');
    })
})