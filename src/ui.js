

(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
        typeof define === 'function' && define.amd ? define(factory) :
            (global = global || self, global.Ui = factory());
}(this, function () {
    'use strict';



    //



    /**
     * Animation helper
     * https://devdojo.com/tnylea/animating-tailwind-transitions-on-page-load
     * This will allow you to add the following data attribute to any element:

     data-replace="{ 'replace-this': 'with-this' }"
     And it will automatically replace the first value with the second one. You can also comma separate the attribute like so:

     data-replace="{ 'replace-this': 'with-this', 'and-replace-this': 'with-this' }"
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

    // function initOnLoads() {
    //     var loads = document.querySelectorAll('[data-onload]');
    //     for (var i = 0; i < loads.length; i++) {
    //         let replaceClasses = JSON.parse(replacers[i].dataset.replace.replace(/'/g, '"'));
    //         Object.keys(replaceClasses).forEach(function (key) {
    //             replacers[i].classList.remove(key);
    //             replacers[i].classList.add(replaceClasses[key]);
    //         });
    //     }
    // }


    function onReady() {
        initReplacements();
       // initOnLoads();


        // cleanup
        let clean = document.querySelectorAll('.clearstyle')
        Array.prototype.forEach.call(clean, function (element) {
            element.removeAttribute('style');
        });
    }

    document.addEventListener('DOMContentLoaded', onReady);

}));
