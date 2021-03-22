(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
        typeof define === 'function' && define.amd ? define(factory) :
            (global = global || self, global.Shuffle = factory());
}(this, function () {
    'use strict';

    //


    const modalOn = false;

    /**
     * Replace classes on elements (handy for load animations)
     */
    function initReplacements() {
        var replacers = document.querySelectorAll('[data-replace]');
        for (var i = 0; i < replacers.length; i++) {
            let replaceClasses = JSON.parse(replacers[i].dataset.replace.replace(/'/g, '"'));
            Object.keys(replaceClasses).forEach(function (key) {
                replacers[i].classList.remove(key);
                replacers[i].classList.add(replaceClasses[key]);
            });
        }
    }

    function initScrollMenu() {
        // classes
        const scrollUp = "scroll-up";
        const scrollDown = "scroll-down";
        let lastScroll = 0;
        //check for Header
        let header = document.getElementById('mainheader');
        let headHeight = 0;
        if (header) {
            headHeight = document.getElementById('mainheader').offsetHeight;
        }
        // scrolly events
        window.addEventListener("scroll", () => {
            const body = document.querySelector('body')
            const currentScroll = window.pageYOffset;
            const tolerance = 5;
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

    function toggleBodyScroll(mondalOn) {
        const body = document.querySelector('body')

        if (modalOn) {
            body.classList.add('noscroll')
            body.style.paddingRight = menuObj.scrollbarwidth() + 'px';
            // body.style.height = '100%';
            // body.style.overflowY = 'hidden';
        } else {
            body.classList.remove('noscroll')
            body.style.paddingRight = '0px';
            // body.style.height = 'auto';
            // body.style.overflowY = 'auto';
        }
    }

    function initOverlayMenu() {
        const menuObj = {
            menu: false,
            scrollbarwidth: function () {
                if (!this._scrollbarwidth) {
                    // Add temporary box to wrapper
                    let scrollbox = document.createElement('div');

                    // Make box scrollable
                    scrollbox.style.overflow = 'scroll';

                    // Append box to document
                    document.body.appendChild(scrollbox);

                    // Measure inner width of box
                    this._scrollbarwidth = scrollbox.offsetWidth - scrollbox.clientWidth;

                    // Remove box
                    document.body.removeChild(scrollbox);
                }
                return this._scrollbarwidth;
            }
        };

        let openmenu = document.querySelectorAll('.opens-menu')
        for (let i = 0; i < openmenu.length; i++) {
            openmenu[i].addEventListener('click', function (event) {
                event.preventDefault()
                toggleMenu()
            })
        }

        let closemenu = document.querySelectorAll('.menu-close')
        for (let i = 0; i < closemenu.length; i++) {
            closemenu[i].addEventListener('click', toggleMenu)
        }

        function toggleMenu() {
            const body = document.querySelector('body')
            body.classList.toggle('menu-open')
            const modal = document.querySelector('.overlaymenu')
            //modal.classList.toggle('pointer-events-none')
            menuObj.menu = (body.classList.contains('menu-open'))
            toggleBodyScroll(menuObj.menu)

            // setup asides
            let transEls = document.querySelectorAll('.aside')

            for (let i = 0; i < transEls.length; i++) {
                let el = transEls[i];
                el.addEventListener(transitionEndEventName, function (e) {
                    if (e.propertyName == 'visibility') {
                        el.classList.toggle('aside-open', menuObj.menu)
                    }
                })
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
                if (menuObj.menu) {
                    toggleMenu();
                } else if (menuObj.search) {
                    toggleSearch();
                }

            }
        });
    }

    function onReady() {
        initReplacements();
        initScrollMenu();
        initOverlayMenu();


        // cleanup
        let clean = document.querySelectorAll('.clearstyle')
        Array.prototype.forEach.call(clean, function (element) {
            element.removeAttribute('style');
        });
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

    /**
     * Animation helper
     * https://devdojo.com/tnylea/animating-tailwind-transitions-on-page-load
     * This will allow you to add the following data attribute to any element:

     data-replace="{ 'replace-this': 'with-this' }"
     And it will automatically replace the first value with the second one. You can also comma separate the attribute like so:

     data-replace="{ 'replace-this': 'with-this', 'and-replace-this': 'with-this' }"
     */
    document.addEventListener('DOMContentLoaded', onReady);

}));
