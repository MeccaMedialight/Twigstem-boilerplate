import {liveBind, loadLink} from '@kiralt/bjax';

const autojax = {
    init: function (ajaxContainerID, linkSeletor) {


        if (!ajaxContainerID) {
            console.error('Cannot set up autojax - no valid selector for a container')
        }

        // Select the node that will be observed for mutations
        const targetNode = document.getElementById(ajaxContainerID);
        if (!targetNode) {
            console.error('Cannot set up autojax - no valid ID for a container')
        }

        if (!linkSeletor) {
            linkSeletor = 'a.ajax';
        }


        liveBind(linkSeletor, 'click', function (event, element) {
            event.preventDefault()
            const cevent = new CustomEvent('content-changing');
            targetNode.dispatchEvent(cevent);
            loadLink(element.href, {
                source: '#' + ajaxContainerID,
                target: '#' + ajaxContainerID
            })


        });

        document.addEventListener('DOMContentLoaded', function () {
            // fancy animations
            const loadevent = new CustomEvent('content-loaded');
            targetNode.dispatchEvent(loadevent);

            // Options for the observer (which mutations to observe)
            const config = {attributes: true, childList: true, subtree: true};

            // Callback function to execute when mutations are observed
            const callback = function (mutationsList, observer) {
                // Dispatch the event.
                targetNode.dispatchEvent(loadevent);
            };

            // Create an observer instance linked to the callback function
            const observer = new MutationObserver(callback);

            // Start observing the target node for configured mutations
            observer.observe(targetNode, config);


        });
    },


}

export {
    autojax
}
