(function($){


    /**
     * Login form
     */

    $("#loginForm").validate({
        rules:{
            email:{
                required:true
            },
            password:{
                required:true
            }

        },
        messages: {
            email: {
                required: 'This field is required'
            },
            password: {
                required: 'This field is required'
            }


        }

    });

    /**
     * Add store form
     */

    $("#storeForm").validate({
        rules:{
            store_name:{
                required:true
            },
            store_url:{
                required:true,
                url:true
            }

        },
        messages: {
            store_name: {
                required: 'This field is required'
            },
            store_url: {
                required: 'This field is required'

            }


        }

    });


    /**
     *  validate the import form
     */
    $("#importFeedForm").validate({
        rules:{
            feed_url:{
                required:true,
                url:true
            },
            feed_name:{
                required:true

            }
        }
    });


    $("#channel_settings").validate({
        rules:{
            name:{
                required:true

            }
        }
    });





    /**
     * Validate adwords
     */
    $("#adwords_feed_settings").validate({
        rules:{
            name:{
                required:true
            },
            adwords_account_id:{
                required:true
            }
        }
    });


    /**
     * Validate bol
     */
    $("#bol_feed_settings").validate({
        rules:{
            name:{
                required:true
            },
            public_key:{
                required:true
            },
            private_key:{
                required:true
            }
        }
    });






})(jQuery);