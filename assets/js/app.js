/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
import Routing from "./Routing";

require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

import $ from 'jquery';
import 'popper.js';
import 'bootstrap';
import 'bootstrap/dist/js/bootstrap.min';
import 'perfect-scrollbar';
import 'perfect-scrollbar/dist/perfect-scrollbar.min';
import '@coreui/coreui/dist/js/coreui.min';
import '@coreui/coreui-plugin-chartjs-custom-tooltips/js/custom-tooltips';

//import mobilePhoneNumber from 'jquery.mobilephonenumber/lib/jquery.mobilePhoneNumber';
// require('main');
// import './main'
// import jQuery from 'jquery';
require('../img/photo-deco.png');

require('./jquery.ajaxloader');
require('./bootstrap.datepicker.1.6.min');

/**
 * get form user edit
 */
$('#btn-dismiss').click(function (e) {
    $('#flash').fadeToggle("slow");
})
$(document).ready(function() {
    $.fn.datepicker.dates['fr'] = {
        days: ["dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi"],
        daysShort: ["dim.", "lun.", "mar.", "mer.", "jeu.", "ven.", "sam."],
        daysMin: ["d", "l", "ma", "me", "j", "v", "s"],
        months: ["janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre"],
        monthsShort: ["janv.", "févr.", "mars", "avril", "mai", "juin", "juil.", "août", "sept.", "oct.", "nov.", "déc."],
        today: "Aujourd'hui",
        monthsTitle: "Mois",
        clear: "Effacer",
        weekStart: 1,
        format: "dd/mm/yyyy"
    };
    $('.js-datepicker').datepicker({
        language: 'fr',
        uiLibrary: 'bootstrap4',
        format: 'dd/mm/yyyy'
    });
    //$('body .phone-num').mobilePhoneNumber({allowPhoneWithoutPrefix: '+1'});
});

//$.fn.datepicker.defaults.format = "mm-dd-yyyy";

/*

    "jquery/dist/jquery.min.js";
    "popper.js/dist/umd/popper.min.js";
    "bootstrap/dist/js/bootstrap.min.js";
    "pace-progress/pace.min.js";
    "perfect-scrollbar/dist/perfect-scrollbar.min.js";
    "@coreui/coreui/dist/js/coreui.min.js";
    <!-- Plugins and scripts required by this view-->
    "chart.js/dist/Chart.min.js";
    "@coreui/coreui-plugin-chartjs-custom-tooltips/dist/js/custom-tooltips.min.js";
    "js/mainPersMorale.js";
*/


