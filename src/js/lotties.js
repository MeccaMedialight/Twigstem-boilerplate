import "@lottiefiles/lottie-player";
import { create } from '@lottiefiles/lottie-interactivity';

document.addEventListener('DOMContentLoaded', function(){

    let lotties = document.querySelectorAll('.lottie')
    for (let i = 0; i < lotties.length; i++) {
        lotties[i].addEventListener("load", function() {
            let frames = lotties[i].dataset.frames;
            let mode = (lotties[i].dataset.mode)? mode: 'scroll';
            create({
                mode: mode,
                player: '#' + lotties[i].id,

                actions: [
                    {
                        visibility:[0,0.1],
                        type: "stop",
                        frames: [0]
                    },
                    {
                        visibility: [0.1,1],
                        type: 'seek',
                        frames: [0, frames],
                    },
                ],
            });
        });

    }
    // let $el = document.getElementById('firstLottie');
    // $el.addEventListener("load", function() {
    //     create({
    //         mode: 'scroll',
    //         player: '#firstLottie',
    //         actions: [
    //             {
    //                 visibility: [0,1],
    //                 type: 'seek',
    //                 frames: [0, 78],
    //             },
    //         ],
    //     });
    // });
});