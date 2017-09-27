"use strict";

const { mix } = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.less('resources/assets/less/AdminLTE.less', 'public/css/all.css')

    .js('resources/assets/js/app.js', 'public/js/app.js')

    .combine(
        [
            'resources/assets/css/custom_style.css',
            'resources/assets/css/dfbuilder.css',
            'resources/assets/css/swal/sweetalert.css',
            'resources/assets/css/datatable.css',
            'resources/assets/css/select2.min.css',
            'resources/assets/css/jquery.ui.css',
            'resources/assets/css/jquery.customselect.css',
            'resources/assets/css/easy_autocomplete.css',
            'resources/assets/css/easy_autocomplete_styles.css',
            'node_modules/handsontable/dist/handsontable.full.min.css'
        ]
        ,'public/css/custom_styles.css')

    .combine(
        [
            'resources/assets/css/css_after_bootstrap.css'

        ]
        ,'public/css/after_bootstrap.css')


    .combine(
        [
            'resources/assets/js/dfbuilder/config.js',
            'resources/assets/js/admin-lte/plugins/JQuery/jquery-2.2.3.min.js',
            'resources/assets/js/dfbuilder/jquery.ui.js',
            'resources/assets/js/admin-lte/datatables.js',
            'resources/assets/js/admin-lte/circle-progress.js',
            'resources/assets/js/admin-lte/plugins/knob/jquery.knob.js',
            'resources/assets/js/admin-lte/jquery.validate.min.js',
            'resources/assets/js/admin-lte/bootstrap.js',
            'resources/assets/js/admin-lte/plugins/fastclick/fastclick.js',
            'resources/assets/js/admin-lte/plugins/select2/select2.full.js',
            'resources/assets/js/admin-lte/app.js',
            'resources/assets/js/admin-lte/demo.js',
            'resources/assets/js/swal/sweetalert.min.js',
            'resources/assets/js/admin-lte/noty.min.js',
            'resources/assets/js/admin-lte/pusher.min.js',
            'resources/assets/js/my_custom.js',
            'resources/assets/js/dfbuilder/jquery.customselect.js',
            'resources/assets/js/dfbuilder/gen_validatorv4.js',
            'resources/assets/js/form-validators.js',
            'resources/assets/js/functions.js',
            'resources/assets/js/dfbuilder/enums.js',
            'resources/assets/js/dfbuilder/global_functions.js',
            'resources/assets/js/dfbuilder/categorize.js',
            'resources/assets/js/dfbuilder/rules.js',
            'resources/assets/js/dfbuilder/hot.js',
            'resources/assets/js/dfbuilder/feed_hot_preview.js',
            'resources/assets/js/dfbuilder/browse_uncategorized.js',
            'resources/assets/js/dfbuilder/mapping.js',
            'resources/assets/js/dfbuilder/import_feed.js',
            'resources/assets/js/dfbuilder/manage_feeds.js',
            'resources/assets/js/dfbuilder/channel.js',
            'resources/assets/js/dfbuilder/adwords.js',
            'resources/assets/js/dfbuilder/init_scripts.js',
            'resources/assets/js/admin-lte/jquery.autocomplete.js'

        ],
        'public/js/dfbuilder.js'

    )


;


