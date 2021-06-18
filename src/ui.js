import popoverMaker from './js/popover';

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
/**
 * Popovt helper
 * Requires import { createPopper } from '@popperjs/core';

 data-popover="{ 'target': '#target-id' }"
 */

function initPopovers() {
    let popovers = document.querySelectorAll('[data-popover]');

    for (var i = 0; i < popovers.length; i++) {
        let trigger = popovers[i];
        let config = JSON.parse(trigger.dataset.popover.replace(/'/g, '"'));
        let target = document.querySelector(config.target);
        popoverMaker(trigger, target);

    }
}


function onReady() {
    initReplacements();
    initPopovers();
    // cleanup
    let clean = document.querySelectorAll('.clearstyle')
    Array.prototype.forEach.call(clean, function (element) {
        element.removeAttribute('style');
    });
}

document.addEventListener('DOMContentLoaded', onReady);


