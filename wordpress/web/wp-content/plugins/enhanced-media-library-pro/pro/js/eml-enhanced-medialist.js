window.wp = window.wp || {};
window.eml = window.eml || { l10n: {} };



( function( $, _ ) {

    var media = wp.media,
        l10n = media.view.l10n,
        original = {};



    _.extend( eml.l10n, wpuxss_eml_pro_enhanced_medialist_l10n );



    /**
     * eml.changeSorting
     *
     * for:
     * wp.media.view.Settings.Gallery
     * wp.media.view.Settings.Playlist
     *
     */
    eml.changeSorting = {

        changeOrder: function( event ) {

            var library = this.controller.frame.state().get('library'),
                order = $( event.target ).val();


            library.props.set( 'order', order, { silent: true } );
            library.reset( library.toArray().reverse() );
            library.saveMenuOrder();
        },

        changeOrderby: function( event ) {

            var library = this.controller.frame.state().get('library'),
                orderby = $( event.target ).val(),
                content = this.controller.frame.content,
                reverse = content.get().toolbar.get( 'reverse' );


            if ( reverse ) {
                reverse.model.set( 'disabled', 'rand' === orderby );
            }

            if ( 'rand' !== orderby && 'menuOrder' !== orderby ) {
                $( event.target ).parent().next().show();
            }
            else {
                $( event.target ).parent().next().hide();
            }

            library.props.set( 'orderby', orderby );
            
            if ( 'rand' !== orderby ) {
                library.sortBy( orderby );
                library.reset( library.models );
            }
        }
    };



    /**
     * wp.media.view.Settings
     *
     */
    original.Settings = {

        Gallery: {
            render: media.view.Settings.Gallery.prototype.render
        },
        Playlist: {
            render: media.view.Settings.Playlist.prototype.render
        }
    };



    /**
     * wp.media.view.Settings.Gallery
     *
     */
    _.extend( media.view.Settings.Gallery.prototype.events, {
        'change:order'   : 'changeOrder',
        'change:orderby' : 'changeOrderby'
    });

    _.extend( media.view.Settings.Gallery.prototype, eml.changeSorting );

    _.extend( media.view.Settings.Gallery.prototype, {

        render: function() {

            var $el = this.$el,
                state = this.controller.frame.state(),
                library = state.get('library'),

                orderby = library.gallery.get( 'orderby') || library.props.get( 'orderby'),
                order = library.gallery.get( 'order') || library.props.get( 'order'),
                limit = library.gallery.get( 'limit' );


            original.Settings.Gallery.render.apply( this, arguments );

            library[ media.gallery.tag ].unset( '_orderbyRandom' );

            state.set({
                sortable: false,
                dragInfo: false
            });

            $el.find( 'input[data-setting=_orderbyRandom]' ).parent( 'span.setting' ).replaceWith( media.template( 'eml-pro-gallery-order' )(this.options) );

            if ( $el.find( '.setting.eml-filter-based' ).length ) {
                $el.find( '.setting.eml-filter-based' ).prevAll( 'span.setting' ).first().after( media.template( 'eml-pro-gallery-additional-params' )(this.options) );
            }
            else {
                $el.find( 'span.setting' ).last().after( media.template( 'eml-pro-gallery-additional-params' )(this.options) );
            }

            library[ media.gallery.tag ].set( 'orderby', orderby );
            library[ media.gallery.tag ].set( 'order', order );
            library[ media.gallery.tag ].set( 'limit', limit );

            this.update.apply( this, ['orderby'] );
            this.update.apply( this, ['order'] );
            this.update.apply( this, ['limit'] );

            if ( 'rand' === orderby || 'menuOrder' === orderby ) {
                $el.find( '.eml-order' ).hide();
            }

            return this;
        }
    });



    /**
     * wp.media.view.Settings.Playlist
     *
     */
    _.extend( media.view.Settings.Playlist.prototype.events, {
        'change .order'   : 'changeOrder',
        'change .orderby' : 'changeOrderby'
    });

    _.extend( media.view.Settings.Playlist.prototype, eml.changeSorting );

    _.extend( media.view.Settings.Playlist.prototype, {

        render: function() {

            var $el = this.$el,
                library = this.controller.frame.state().get('library'),

                orderby = library.playlist.get( 'orderby') || library.props.get( 'orderby'),
                order = library.playlist.get( 'order') || library.props.get( 'order'),
                limit = library.playlist.get( 'limit' );


            original.Settings.Playlist.render.apply( this, arguments );

            if ( $el.find( '.setting.eml-filter-based' ).length ) {
                $el.find( '.setting.eml-filter-based' ).prevAll( 'span.setting' ).first().after( media.template( 'eml-pro-playlist-additional-params' )(this.options) );
            }
            else {
                $el.find( 'span.setting' ).last().after( media.template( 'eml-pro-playlist-additional-params' )(this.options) );
            }

            library[ media.playlist.tag ].set( 'orderby', orderby );
            library[ media.playlist.tag ].set( 'order', order );
            library[ media.playlist.tag ].set( 'limit', limit );

            this.update.apply( this, ['orderby'] );
            this.update.apply( this, ['order'] );
            this.update.apply( this, ['limit'] );

            if ( 'rand' === orderby || 'menuOrder' === orderby ) {
                $el.find( '.eml-order' ).hide();
            }

            return this;
        }
    });



    /**
     * wp.media.view.Toolbar
     *
     */
    _.extend( media.view.Toolbar.prototype, {

        refresh: function() {

            var controller = this.controller,
                state = this.controller.state(),
                stateID = state.get( 'id' ),
                library = state.get('library'),
                selection = state.get('selection'),
                attrs = library ? library.props.toJSON() : {},
                isFilterBased;


            _.each( this._views, function( button ) {

                if ( ! button.model || ! button.options || ! button.options.requires ) {
                    return;
                }

                var requires = button.options.requires,
                    disabled = false,
                    text = button.model.get( 'text' );


                // Prevent insertion of attachments if any of them are still uploading
                disabled = _.some( selection.models, function( attachment ) {
                    return attachment.get('uploading') === true;
                });

                if ( requires.selection && selection && ! selection.length  ) {
                    disabled = true;
                }
                else if ( requires.library && library && ! library.length ) {
                    disabled = true;
                }

                if ( 'gallery' === stateID || 'playlist' === stateID || 'video-playlist' === stateID ) {

                    isFilterBased = emlIsFilterBased( attrs );

                    if ( isFilterBased && selection && ! selection.length ) {

                        if ( 'gallery' === stateID ) {
                            text = eml.l10n.createNewGallery;
                        } else if ( 'playlist' === stateID ) {
                            text = eml.l10n.createNewPlaylist;
                        } else if ( 'video-playlist' === stateID ) {
                            text = eml.l10n.createNewVideoPlaylist;
                        }
                    }
                    else {

                        if ( 'gallery' === stateID ) {
                            text = l10n.createNewGallery;
                        } else if ( 'playlist' === stateID ) {
                            text = l10n.createNewPlaylist;
                        } else if ( 'video-playlist' === stateID ) {
                            text = l10n.createNewVideoPlaylist;
                        }
                    }

                    if ( isFilterBased && library.length ) {
                        disabled = false;
                    }

                    _.each( eml.l10n.taxonomies, function( terms, taxonomy ) {

                        if ( ( 'in' === attrs[taxonomy] || 'not_in' === attrs[taxonomy] ) &&
                             selection && ! selection.length ) {
                            disabled = true;
                        }
                    });

                    button.model.set( 'text', text );
                }

                button.model.set( 'disabled', disabled );
            });
        }
    });



    /**
     * wp.media.view.MediaFrame.Post
     *
     */
    original.MediaFrame = {

        Post: {
            mainGalleryToolbar: media.view.MediaFrame.Post.prototype.mainGalleryToolbar,
            mainPlaylistToolbar: media.view.MediaFrame.Post.prototype.mainPlaylistToolbar,
            mainVideoPlaylistToolbar: media.view.MediaFrame.Post.prototype.mainVideoPlaylistToolbar
        }
    };

    _.extend( media.view.MediaFrame.Post.prototype, {

        mainGalleryToolbar: function( view ) {

            original.MediaFrame.Post.mainGalleryToolbar.apply( this, arguments );

            var controller = this,
                gallery = view.get( 'gallery' );

            gallery.options.click = function() {

                var selection = controller.state().get('selection'),
                    library = controller.state().get('library'),
                    edit = controller.state('gallery-edit'),
                    models,
                    isFilterBased = emlIsFilterBased( library.props.toJSON() );


                if ( isFilterBased && selection && ! selection.length ) {

                    models = library.where({ type: 'image' });

                    edit.set( 'library', new wp.media.model.Selection( models, {
                        props:    library.props.toJSON(),
                        multiple: true
                    }) );
                }
                else {

                    selection.props.set( 'orderby', library.props.get( 'orderby' ), { silent: true } );
                    selection.props.set( 'order', library.props.get( 'order' ), { silent: true } );

                    models = selection.where({ type: 'image' });

                    edit.set( 'library', new wp.media.model.Selection( models, {
                        props:    selection.props.toJSON(),
                        multiple: true
                    }) );
                }

                this.controller.setState('gallery-edit');

                // Keep focus inside media modal
                // after jumping to gallery view
                this.controller.modal.focusManager.focus();
            }
        },

        mainPlaylistToolbar: function( view ) {

            original.MediaFrame.Post.mainPlaylistToolbar.apply( this, arguments );

            var controller = this,
                playlist = view.get( 'playlist' );

            playlist.options.click = function() {

                var selection = controller.state().get('selection'),
                    library = controller.state().get('library'),
                    edit = controller.state('playlist-edit'),
                    models,
                    isFilterBased = emlIsFilterBased( library.props.toJSON() );


                if ( isFilterBased && selection && ! selection.length ) {

                    models = library.where({ type: 'audio' });

                    edit.set( 'library', new wp.media.model.Selection( models, {
                        props:    library.props.toJSON(),
                        multiple: true
                    }) );
                }
                else {

                    selection.props.set( 'orderby', library.props.get( 'orderby' ), { silent: true } );
                    selection.props.set( 'order', library.props.get( 'order' ), { silent: true } );

                    models = selection.where({ type: 'audio' });

                    edit.set( 'library', new wp.media.model.Selection( models, {
                        props:    selection.props.toJSON(),
                        multiple: true
                    }) );
                }

                this.controller.setState('playlist-edit');

                // Keep focus inside media modal
                // after jumping to gallery view
                this.controller.modal.focusManager.focus();
            }
        },

        mainVideoPlaylistToolbar: function( view ) {

            original.MediaFrame.Post.mainVideoPlaylistToolbar.apply( this, arguments );

            var controller = this,
                playlist = view.get( 'video-playlist' );

            playlist.options.click = function() {

                var selection = controller.state().get('selection'),
                    library = controller.state().get('library'),
                    edit = controller.state('video-playlist-edit'),
                    models,
                    isFilterBased = emlIsFilterBased( library.props.toJSON() );


                if ( isFilterBased && selection && ! selection.length ) {

                    models = library.where({ type: 'video' });

                    edit.set( 'library', new wp.media.model.Selection( models, {
                        props:    library.props.toJSON(),
                        multiple: true
                    }) );
                }
                else {

                    selection.props.set( 'orderby', library.props.get( 'orderby' ), { silent: true } );
                    selection.props.set( 'order', library.props.get( 'order' ), { silent: true } );

                    models = selection.where({ type: 'video' });

                    edit.set( 'library', new wp.media.model.Selection( models, {
                        props:    selection.props.toJSON(),
                        multiple: true
                    }) );
                }

                this.controller.setState('video-playlist-edit');

                // Keep focus inside media modal
                // after jumping to gallery view
                this.controller.modal.focusManager.focus();
            }
        }
    });

})( jQuery, _ );
