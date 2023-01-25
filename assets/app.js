/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';
//Import de Chartkick pour les graphiques
import 'chartkick/chart.js';

const $ = require('jquery');
require('bootstrap');

let data;

if (window.location.href.indexOf("streamer") > -1){
  data = [
    {name: 'Followers',
      data: {
        'Monday': 0,
        'Tuesday': 1000,
        'Wednesday': 3540,
        'Thursday': 6700,
        'Friday': 5432,
      },
    },
  ];
}
if (window.location.href.indexOf("company") > -1){
  data = [
    {name: 'Contrats',
      data: {
        'Monday': 0,
        'Tuesday': 2,
        'Wednesday': 5,
        'Thursday': 6,
        'Friday': 9,
      },
    },
  ];
}

new Chartkick.LineChart('chart', data);


