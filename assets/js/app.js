/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

// jQuery
global.$ = global.jQuery = window.$ = window.jQuery = require('jquery');

require('bootstrap');

require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

// require('imports-loader?define=>false,this=>window!datatables.net')(window, $)
// require('imports-loader?define=>false,this=>window!datatables.net-bs4')(window, $)
// require('imports-loader?define=>false,this=>window!datatables.net-responsive-bs4')

// require('tinymce/tinymce');

// require('tinymce/themes/silver');

// require('tinymce/plugins/link/plugin');
// require('tinymce/plugins/image/plugin');
// require('tinymce/plugins/charmap/plugin');
// require('tinymce/plugins/textcolor/plugin');
// require('tinymce/plugins/media/plugin');
// require('tinymce/plugins/code/plugin');
// require('tinymce/plugins/lists/plugin');

// import 'tinymce/skins/ui/oxide/skin.min.css';
// import 'tinymce/skins/content/default/content.css';

// require("./tinymce/langs/fr_FR.js");
// require("./tinymce/langs/it.js");
// require("./tinymce/langs/pt_PT.js");


require('./BackToTop/BackToTop')
require('./BackToTop/arrow-up.png');
require('./BackToTop/BackToTop.css');

$(function() {
	$(document).BackToTop();
});