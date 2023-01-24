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

//Création du tableau pour les graphiques
const data = [
  {name: 'Apple', data: {'Tuesday': 3, 'Friday': 4}, stack: 'fruit'},
  {name: 'Pear', data: {'Tuesday': 1, 'Friday': 8}, stack: 'fruit'},
  {name: 'Carrot', data: {'Tuesday': 3, 'Friday': 4}, stack: 'vegetable'},
  {name: 'Beet', data: {'Tuesday': 1, 'Friday': 8}, stack: 'vegetable'},
];

new Chartkick.LineChart("chart", data);