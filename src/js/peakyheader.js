/*
|--------------------------------------------------------------------------
| PeakyHeader
|--------------------------------------------------------------------------
|
| Create a scroll-sensitive header. If the page is scrolled and the header
| scrolled off-screen, then a scrollDown or scrollUp class (specified below)
| is added to the body. CSS like this can then be used to pin the header
| to the top of the screen
|
|    .scroll-down .active-header {
|        transform: translateY(-100%);
|        transition: all .3s ease-in-out;
|        opacity: 0;
|    }
|
|    .scroll-up .active-header {
|        position: fixed;
|        z-index: 21;
|        top: 0;
|        right: 0;
|        left: 0;
|        transform: translateY(0);
|        transition: all .3s ease-in-out;
|        opacity:1;
|    }
|
| @version 1.0
| @author luke@meccamedialight.com.au
|
*/
(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
        typeof define === 'function' && define.amd ? define(factory) :
            (global = global || self, global.ScrollHeader = factory());
}(this, (function () {
    'use strict';
    // ID of the header that will be tracked
    const headerID = 'mainheader';
    // tolerance
    const tolerance = 10;
    // classes
    const scrollUp = "scroll-up";
    const scrollDown = "scroll-down";


    function initScrollHeader() {

        let lastScroll = 0;
        //check for Header
        let header = document.getElementById(headerID);
        let headHeight = 0;
        if (header) {
            headHeight = document.getElementById(headerID).offsetHeight + tolerance;
        }
        // scrolly events
        window.addEventListener("scroll", () => {
            const body = document.querySelector('body')
            const currentScroll = window.pageYOffset;

            if (currentScroll <= headHeight) {
                body.classList.remove(scrollUp);
                return;
            }
            let y = Math.abs(currentScroll - lastScroll);
            if (y < tolerance) return
            if (currentScroll > lastScroll && !body.classList.contains(scrollDown)) {
                // down
                body.classList.remove(scrollUp);
                body.classList.add(scrollDown);
            } else if (currentScroll < lastScroll && body.classList.contains(scrollDown)) {
                // up
                body.classList.remove(scrollDown);
                body.classList.add(scrollUp);
            }
            // and all around... :)
            lastScroll = currentScroll;
        });
    }

    document.addEventListener('DOMContentLoaded', initScrollHeader);

})));