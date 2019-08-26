/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

    import $ from 'jquery';
    // import jQuery from 'jquery';

// console.log('Hello Webpack Encore! Edit me in assets/js/app.js');



    import "admin-lte/bower_components/jquery/dist/jquery.min.js";
    // //-- jQuery UI 1.11.4 --;
    import "admin-lte/bower_components/jquery-ui/jquery-ui.min.js";
    // //-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip --;
    
    $.widget.bridge('uibutton', $.ui.button);

// //-- Bootstrap 3.3.7 --;
//     import "admin-lte/bower_components/bootstrap/dist/js/bootstrap.min.js";
    require("bootstrap/dist/js/bootstrap.min.js");
    // //-- Morris.js charts --;
    import "admin-lte/bower_components/raphael/raphael.min.js";
    import "admin-lte/bower_components/morris.js/morris.min.js";
    // //-- Sparkline --;
    import "admin-lte/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js";
    // //-- jvectormap --;
    import "admin-lte/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js";
    import "admin-lte/plugins/jvectormap/jquery-jvectormap-world-mill-en.js";
    // //-- jQuery Knob Chart --;
    import "admin-lte/bower_components/jquery-knob/dist/jquery.knob.min.js";
    // //-- daterangepicker --;
    // import "admin-lte/bower_components/moment/min/moment.min.js";
    import "admin-lte/bower_components/bootstrap-daterangepicker/daterangepicker.js";
    // //-- datepicker --;
    import "admin-lte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js";
    //-- Bootstrap WYSIHTML5 --;
    // import "admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js";
    //-- Slimscroll --;
    import "admin-lte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js";
    //-- FastClick --;
    import "admin-lte/bower_components/fastclick/lib/fastclick.js";
    //-- AdminLTE App --;
    import "admin-lte/dist/js/adminlte.min.js";
    //-- AdminLTE dashboard demo (This is only for demo purposes) --;
    import "admin-lte/dist/js/pages/dashboard.js";
    //-- AdminLTE for demo purposes --;
    import "admin-lte/dist/js/demo.js";



console.log($('.example-wrapper').text())
