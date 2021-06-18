import {createPopper} from '@popperjs/core';

export default function popoverMaker(trigger, dialog,) {
    // const trigger = document.querySelector(trigger);
    // const dialog = document.querySelector(dialog);
    let viewstate = 'init';
    const popperInstance = createPopper(trigger, dialog, {
        modifiers: [
            {
                name: 'offset',
                options: {
                    offset: [0, 8],
                },
            },
        ],
    });

    function show() {

        // Make the dialog visible
        dialog.classList.remove("hidden");
        // Enable the event listeners
        popperInstance.setOptions({
            modifiers: [{name: 'eventListeners', enabled: true}],
        });

        // Update its position
        popperInstance.update();
        viewstate = 'visible';
    }

    function hide() {
        // Hide the dialog
        dialog.classList.add("hidden");
        // Disable the event listeners
        popperInstance.setOptions({
            modifiers: [{name: 'eventListeners', enabled: false}],
        });
        viewstate = 'hidden';
    }

    function toggle() {
        if (viewstate == 'visible') {
            hide();
        } else {
            show();
        }
    }

    trigger.addEventListener('click', toggle);
    dialog.addEventListener('click', hide);

    if (dialog.classList.contains('hidden')){
        hide();
    }


    // const showEvents = ['click', 'focus'];
    // const hideEvents = ['blur'];
    //
    // showEvents.forEach(event => {
    //     trigger.addEventListener(event, show);
    // });
    //
    // hideEvents.forEach(event => {
    //     trigger.addEventListener(event, hide);
    // });
    return this;
}

