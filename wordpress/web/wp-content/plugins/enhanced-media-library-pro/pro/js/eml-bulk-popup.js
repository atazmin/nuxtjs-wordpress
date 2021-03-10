window.wp = window.wp || {};
window.eml = window.eml || { l10n: {} };



( function( $, _ ) {

    var ids = [],
        media = wp.media;



    media.view.MediaFrame.emlBulkEdit = media.view.MediaFrame.Select.extend({

        initialize: function() {

            _.defaults( this.options, {
                multiple : true,
                editing  : false,
                state    : 'library',
                mode     : [ 'eml-bulk-edit' ]
            });

            media.view.MediaFrame.Select.prototype.initialize.apply( this, arguments );
        },

        createStates: function() {

            var options = this.options;

            if ( this.options.states ) {
                return;
            }

            // Add the default states.
            this.states.add([
                // Main states.
                new media.controller.Library({
                    library       : media.query( options.library ),
                    title         : options.title,
                    priority      : 20,

                    multiple      : options.multiple,

                    content       : 'browse',
                    menu          : false,
                    router        : options.router,
                    toolbar       : 'bulk-edit',

                    searchable    : options.searchable,
                    filterable    : options.filterable,

                    editable      : false,

                    allowLocalEdits: true,
                    displaySettings: true,
                    displayUserSettings: true
                })
            ]);
        },

        bindHandlers: function() {

            media.view.MediaFrame.Select.prototype.bindHandlers.apply( this, arguments );

            this.on( 'toolbar:create:bulk-edit', this.createToolbar, this );
            this.on( 'toolbar:render:bulk-edit', this.bulkEditToolbar, this );

            this.on( 'open', this.selectAll, this );
        },

        selectionStatusToolbar: function( view ) {

            view.set( 'selection', new media.view.Selection({
                controller: this,
                collection: this.state().get('selection'),
                priority:   -40,
            }).render() );
        },

        bulkEditToolbar: function( view ) {

            var controller = this;

            this.selectionStatusToolbar( view );

            view.set( 'bulk-edit', {

                style    : 'primary',
                priority : 80,
                text     : eml.l10n.media_new_close,

                click: function() {
                    controller.close();
                }
            });
        }
    });



    function emlUploadSuccess( fileObj, serverData ) {

        serverData = serverData.replace( /^<pre>(\d+)<\/pre>$/, '$1' );

        if ( serverData ) {
            ids.push( serverData );
        }
    }

    function emlUploadComplete( files ) {

        if ( files.length >= 2 && ! $('.eml-bulk-edit-button-container').length ) {

            $('.media-upload-form').after('<div class="eml-bulk-edit-button-container"><a href="#" class="button media-button button-primary button-large eml-bulk-edit-button">'+eml.l10n.media_new_button+'</a></div>');
        }
    }

    function emlUploadStart() {

        if ( $('.eml-bulk-edit-button-container').length ) {
            $('.eml-bulk-edit-button-container').remove();
        }
    }




    $( document ).ready( function() {

        var frame;



        if ( typeof uploader !== 'undefined' ) {

            uploader.bind('FilesAdded', function( up, files ) {
                emlUploadStart();
            });

            uploader.bind('FileUploaded', function( up, file, response ) {
                emlUploadSuccess( file, response.response );
            });

            uploader.bind('UploadComplete', function( up, files ) {
                emlUploadComplete( files );
            });
        }



        $( document ).on( 'click', '.eml-bulk-edit-button', function( event ) {

            if ( event ) {
                event.preventDefault();
            }

            if ( ids.length )
            {
                frame = media.frame = new media.view.MediaFrame.emlBulkEdit({
                    title         : eml.l10n.media_new_title,
                    library       : { post__in: ids },
                    router        : typeof acf != 'undefined', // router only if ACF is active
                    searchable    : false,
                    filterable    : false,
                    uploader      : false
                }).open();
            }
        });



        $( document ).on( 'click', '#doaction, #doaction2', function( event ) {

            if ( 'emlbulkedit' == $( '#bulk-action-selector-top' ).val() || 'emlbulkedit' == $( '#bulk-action-selector-bottom' ).val() ) {

                if ( event ) {
                    event.preventDefault();
                }

                var ids = [];

                $( 'input[name="media[]"]:checked' ).each( function() {
                    ids.push( $(this).val() );
                });

                if ( ids.length ) {

                    frame = media.frame = new media.view.MediaFrame.emlBulkEdit({
                        title         : eml.l10n.media_new_title,
                        library       : { post__in: ids },
                        router        : typeof acf != 'undefined', // router only if ACF is active
                        searchable    : false,
                        filterable    : false,
                        uploader      : false
                    }).open();
                }
            }
        });



        // TODO: all code below should be deleted, this will cut off < 4.7 compatibility
        $( document ).on( 'change', '#bulk-action-selector-top, #bulk-action-selector-bottom', function( event ) {

            $( '#bulk-action-selector-top, #bulk-action-selector-bottom' ).val( $( this ).val() );
        });


        $( document ).on( 'change', 'input[name="media[]"]', function( event ) {

            var $bulkEditOption = $( '#bulk-action-selector-top option[value="emlbulkedit"], #bulk-action-selector-bottom option[value="emlbulkedit"]' );

            if ( $( 'input[name="media[]"]:checked' ).length && ! $bulkEditOption.length && 'trash' !== $( '#attachment-filter' ).val() ) {
                $( '#bulk-action-selector-top option:first, #bulk-action-selector-bottom option:first' ).after( '<option value="emlbulkedit">'+eml.l10n.media_new_button+'</option>' );
            }

            if ( ( ! $( 'input[name="media[]"]:checked' ).length || 'trash' === $( '#attachment-filter' ).val() ) && $bulkEditOption.length ) {
                $bulkEditOption.remove();
            }
        });


        $( document ).on( 'change', '#cb-select-all-1, #cb-select-all-2', function( event ) {

            var $bulkEditOption = $( '#bulk-action-selector-top option[value="emlbulkedit"], #bulk-action-selector-bottom option[value="emlbulkedit"]' );

            if ( $( this ).prop('checked') && ! $bulkEditOption.length && 'trash' !== $( '#attachment-filter' ).val() ) {
                $( '#bulk-action-selector-top option:first, #bulk-action-selector-bottom option:first' ).after( '<option value="emlbulkedit">'+eml.l10n.media_new_button+'</option>' );
            }

            if ( ( ! $( this ).prop('checked') || 'trash' === $( '#attachment-filter' ).val() ) && $bulkEditOption.length ) {
                $bulkEditOption.remove();
            }
        });

    });

})( jQuery, _ );
