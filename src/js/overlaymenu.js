/*
|--------------------------------------------------------------------------
| Overlaymenu
|--------------------------------------------------------------------------
|
| Create an overlay menu.
|   * open the menu by clicking on elements with class '.opens-menu'
|   * close the menu by clicking on elements with class '.menu-close' or
|     pressing the esc key
|
| @version 1.0
| @author luke@meccamedialight.com.au
|
*/
(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
        typeof define === 'function' && define.amd ? define(factory) :
            (global = global || self, global.Overlaymenu = factory());
}(this, (function () {
    'use strict';

    let menuOpen = false;
    // open the menu
    let openmenu = document.querySelectorAll('.opens-menu')
    for (let i = 0; i < openmenu.length; i++) {
        openmenu[i].addEventListener('click', function (event) {
            event.preventDefault()
            toggleMenu()
        })
    }
    // close the menu
    let closemenu = document.querySelectorAll('.menu-close')
    for (let i = 0; i < closemenu.length; i++) {
        closemenu[i].addEventListener('click', toggleMenu)
    }


    // setup transitions
    function getTransitionEndEventName() {
        var transitions = {
            "transition": "transitionend",
            "OTransition": "oTransitionEnd",
            "MozTransition": "transitionend",
            "WebkitTransition": "webkitTransitionEnd"
        }
        let bodyStyle = document.body.style;
        for (let transition in transitions) {
            if (bodyStyle[transition] != undefined) {
                return transitions[transition];
            }
        }
    }

    let transitionEndEventName = getTransitionEndEventName();

    function toggleMenu() {
        console.log('toggleMenu');
        const body = document.querySelector('body')
        body.classList.toggle('menu-open')
        const modal = document.querySelector('.overlaymenu')
        //modal.classList.toggle('pointer-events-none')
        menuOpen = (body.classList.contains('menu-open'))
        toggleBodyScroll(menuOpen)

        // setup asides
        let transEls = document.querySelectorAll('.aside')

        for (let i = 0; i < transEls.length; i++) {
            let el = transEls[i];
            el.addEventListener(transitionEndEventName, function (e) {
                if (e.propertyName == 'visibility') {
                    el.classList.toggle('aside-open', menuOpen)
                }
            })
        }
    }

    let _scrollbarwidth = null;

    function scrollbarwidth() {
        if (!_scrollbarwidth) {
            // Add temporary box to wrapper
            let scrollbox = document.createElement('div');

            // Make box scrollable
            scrollbox.style.overflow = 'scroll';

            // Append box to document
            document.body.appendChild(scrollbox);

            // Measure inner width of box
            _scrollbarwidth = scrollbox.offsetWidth - scrollbox.clientWidth;

            // Remove box
            document.body.removeChild(scrollbox);
        }
        return _scrollbarwidth;
    }

    function toggleBodyScroll(modalOn) {
        const body = document.querySelector('body')
        if (modalOn) {
            body.classList.add('noscroll')
            body.style.paddingRight = scrollbarwidth() + 'px';
        } else {
            body.classList.remove('noscroll')
            body.style.paddingRight = '0px';
        }
    }


    document.addEventListener('keydown', function (evt) {

        evt = evt || window.event
        let isEscape = false
        if ("key" in evt) {
            isEscape = (evt.key === "Escape" || evt.key === "Esc")
        } else {
            isEscape = (evt.keyCode === 27)
        }
        if (isEscape && document.body.classList.contains('menu-open')) {
            if (menuOpen) {
                toggleMenu();
            } else if (menuObj.search) {
                toggleSearch();
            }

        }
    });
})));