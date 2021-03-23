require('./bootstrap.js');
import Overlaymenu from './js/overlaymenu.js';


//require('./ui.js');
const axios = require('axios');

// Make a request for a user with a given ID
axios.get('/api/movies.json', {
    params: {
        render: "_partials/_moviegrid.twig"
    }
})
    .then(function (response) {
        // handle success
        console.log(response);
        document.getElementById("ajaxcontent").innerHTML=response.data.html;
    })
    .catch(function (error) {
        // handle error
        console.log(error);
    })
    .then(function () {
        // always executed
    });


