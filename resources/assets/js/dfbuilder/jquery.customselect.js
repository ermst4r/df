/*
 jquery custom select plugin
 http://www.jhnerd.com
 */

/*
 * Dependencies:
 *   jQuery v1.4+
 *   jQuery UI v1.12.1+
 */

(function($) {

    $.widget('ui.customSelectIT', {
        options: {
            allowDuplicates   : true,
            caseSensitive     : true,
            fieldName         : 'customselect',
            placeholderText   : null,   // Sets `placeholder` attr on input field.
            readOnly          : false,  // Disables editing.
            removeConfirmation: false,  // Require confirmation to remove tags.
            tagLimit          : null,   // Max number of tags allowed (null for unlimited).

            // Used for autocomplete, unless you override `autocomplete.source`.
            availableTags     : ['option1','option2','option3','option4','option5','option6','option7','option8','option9','option10'],

            // The below options are for using a single field instead of several
            // for our form values.
            //
            // When enabled, will use a single hidden field for the form,
            // rather than one per tag. It will delimit tags in the field
            // with singleFieldDelimiter.
            //
            // The easiest way to use singleField is to just instantiate tag-it
            // on an INPUT element, in which case singleField is automatically
            // set to true, and singleFieldNode is set to that element. This
            // way, you don't need to fiddle with these options.
            singleField: true,

            // This is just used when preloading data from the field, and for
            // populating the field with delimited tags as the user adds them.
            singleFieldDelimiter: ',',

            // Set this to an input DOM node to use an existing form field.
            // Any text in it will be erased on init. But it will be
            // populated with the text of tags as they are created,
            // delimited by singleFieldDelimiter.
            //
            // If this is not set, we create an input node for it,
            // with the name given in settings.fieldName.
            singleFieldNode: true,

            // Whether to animate tag removals or not.
            animate: true,

            onTagKeyUp  : null,
        },
        _create: function() {
            // for handling static scoping inside callbacks
            var that = this;

            // There are 2 kinds of DOM nodes this widget can be instantiated on:
            //     1. UL, OL, or some element containing either of these.
            //     2. INPUT, in which case 'singleField' is overridden to true,
            //        a UL is created and the INPUT is hidden.
            if (this.element.is('input')) {
                this.tagList = $('<ul></ul>').insertAfter(this.element);
                this.tagButton = $('<button>', {
                    text: "",
                    "class": "btn btn-default customselect-button"
                });
                this.tagButton.append('<i class="glyphicon glyphicon-plus"></i>');
                this.tagButton.appendTo( this.tagList );
                this.tagButton.click(function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    that.createTag(that.options.availableTags[0],'', true);
                    that.tagButton.css('height',that.tagList.css('height')-20);
                });

                this.options.singleField = true;
                this.options.singleFieldNode = this.element;
                this.element.addClass('customselect-hidden-field');
            } else {
                this.tagList = this.element.find('ul, ol').andSelf().last();
            }

            this.tagInput = $('<div contenteditable="true" ></div>').addClass('ui-widget-content');

            this.tagInput.keyup(function (e) {
                e.stopPropagation();
                if(that._trigger('onTagKeyUp', e, that.tagInput) ==false) {
                    return false;
                }
                tags = [];
                that._updateSingleTagsField(tags);
            });

            if (this.options.readOnly) this.tagInput.attr('disabled', 'disabled');

            if (this.options.tabIndex) {
                this.tagInput.attr('tabindex', this.options.tabIndex);
            }

            if (this.options.placeholderText) {
                this.tagInput.attr('placeholder', this.options.placeholderText);
            }


            this.tagList
                .addClass('customselect')
                .addClass('ui-widget ui-widget-content ui-corner-all')
                // Create the input field.
                .prepend($('<li class="customselect-new customselect-choice"></li>').prepend(this.tagInput))

            // Single field support.
            var addedExistingFromSingleFieldNode = false;
            if (this.options.singleField) {
                if (this.options.singleFieldNode) {
                    // Add existing tags from the input field.
                    var node = $(this.options.singleFieldNode);
                    var allnodes = node.val();

                    if(allnodes.length == 0) {
                        this.tagInput.before($('<span>Please enter your input</span>').addClass('customeselect-placeholder'));
                        this.tagInput.parent().addClass('customeselect-showplaceholder');
                        this.tagInput.keypress(function(e){
                            $(this).parent().removeClass('customeselect-showplaceholder');
                            $(this).parent().find('.customeselect-placeholder').remove();
                        });
                        this.tagInput.on('paste',function(e) {
                            $(this).parent().removeClass('customeselect-showplaceholder');
                            $(this).parent().find('.customeselect-placeholder').remove();
                        });
                    }

                    while (allnodes.length > 0) {

                        if(allnodes.indexOf('{') == -1){
                            this.tagInput.html(allnodes);
                            break;
                        }
                        else if (allnodes.indexOf('{') == 0) {
                            length_val = allnodes.indexOf('}');
                            nodeval = allnodes.substr(1,length_val-1);
                            allnodes = allnodes.substr(length_val+1);

                            that.createTag(nodeval);

                        }else{
                            position = allnodes.indexOf('{');
                            length_val = allnodes.indexOf('}') - position;
                            textval = allnodes.substr(0,position);
                            nodeval = allnodes.substr(position+1,length_val-1);
                            allnodes = allnodes.substr(position+length_val+1);

                            that.createTag(nodeval,'',false,textval);
                        }

                    }

                    //added to ensure that tags are retrieved
                    addedExistingFromSingleFieldNode = true;
                } else {
                    // Create our single field input after our list.
                    this.options.singleFieldNode = $('<input type="hidden" style="display:none;" value="" name="' + this.options.fieldName + '" />');
                    this.tagList.after(this.options.singleFieldNode);
                }

                //added for button
                this.tagButton.css('height',this.tagList.css('height')-20);
            }
        },
        destroy: function() {
            $.Widget.prototype.destroy.call(this);

            this.element.unbind('.customselect');
            this.tagList.unbind('.customselect');

            this.tagList.removeClass([
                'customselect',
                'ui-widget',
                'ui-widget-content',
                'ui-corner-all',
                'customselect-hidden-field'
            ].join(' '));

            if (this.element.is('input')) {
                this.element.removeClass('customselect-hidden-field');
                this.tagList.remove();
            }
            return this;
        },

        _effectExists: function(name) {
            return Boolean($.effects && ($.effects[name] || ($.effects.effect && $.effects.effect[name])));
        },

        assignedTags: function() {
            // Returns an array of tag string values
            var that = this;
            var tags = [];
            if (this.options.singleField) {
                tags = $(this.options.singleFieldNode).val().split(this.options.singleFieldDelimiter);
                if (tags[0] === '') {
                    tags = [];
                }
            } else {
                this._tags().each(function() {
                    tags.push(that.tagLabel(this));
                });
            }
            return tags;
        },

        _updateSingleTagsField: function(tags) {
            // Takes a list of tag string values, updates this.options.singleFieldNode.val to the tags delimited by this.options.singleFieldDelimiter
            var taginfo = '';
            this.tagList.find('.customselect-choice').each(function (e) {
                if($(this).hasClass('ui-widget-content'))
                    taginfo += '{' + $(this).find('.customselect-label :selected').text()+'}';
                else
                    taginfo += $(this).find('.ui-widget-content').html();
            });
            $(this.options.singleFieldNode).val(taginfo).trigger('change');
        },

        createTag: function(value, additionalClass, duringInitialization,textset) {
            //check for removing placeholders
            if(this.tagInput.parent().hasClass('customeselect-showplaceholder')) {
                this.tagInput.parent().removeClass('customeselect-showplaceholder');
                this.tagInput.parent().find('.customeselect-placeholder').remove();
            }

            var that = this;
            value = $.trim(value);
            if(value =='')
                return;

            var label = $('<select></select>');
            this.options.availableTags.forEach(function (e) {
                if(e==value)
                    label.append('<option selected="selected">'+e+'</option>');
                else
                    label.append('<option>'+e+'</option>');
            });
            label.addClass('customselect-label');
            label.change(function (e) {
                tags = [];
                that._updateSingleTagsField(tags);
            });

            // Create tag.
            var tag = $('<li></li>')
                .addClass('customselect-choice ui-widget-content ui-state-default ui-corner-all')
                .addClass(additionalClass)
                .append(label);

            if (this.options.readOnly){
                tag.addClass('customselect-choice-read-only');
            } else {
                tag.addClass('customselect-choice-editable');
                // Button for removing the tag.
                var removeTagIcon = $('<span></span>')
                    .addClass('fa fa-close');
                var removeTag = $('<a><span class="text-icon">\xd7</span></a>') // \xd7 is an X
                    .addClass('customselect-close')
                    .append(removeTagIcon)
                    .click(function(e) {
                        // Removes a tag when the little 'x' is clicked.
                        that.removeTag(tag);
                    });
                tag.append(removeTag);
            }
            label.selectmenu({
                change: function( event, data ) {
                    that._updateSingleTagsField(tags);
                }
            });
            // Unless options.singleField is set, each tag has a hidden input field inline.
            if (!this.options.singleField) {
                var escapedValue = label.html();
                tag.append('<input type="hidden" value="' + escapedValue + '" name="' + this.options.fieldName + '" class="customselect-hidden-field" />');
            }


            if (this.options.singleField) {
                var tags = this.assignedTags();
                tags.push(value);
                this._updateSingleTagsField(tags);
            }

            this.tagInput.parent().after(tag);
            var clone = this.tagInput.parent().clone();

            if(textset != null)
                this.tagInput.html(textset);


            this.tagInput.parent().removeClass('customselect-new').addClass('customselect-old');
            clone.children('div').html('');
            this.tagInput = clone.children('div');

            this.tagInput.unbind('keyup');
            this.tagInput.keyup(function (e) {
                e.stopPropagation();
                if(that._trigger('onTagKeyUp', e, that.tagInput) ==false) {
                    return false;
                }
                that._updateSingleTagsField(tags);
            });

            tag.after(clone);
            this.tagInput.focus();

            that._updateSingleTagsField(tags);
        },

        removeTag: function(tag, animate) {
            var that = this;
            animate = typeof animate === 'undefined' ? this.options.animate : animate;

            tag = $(tag);

            if (animate) {

                tag.addClass('removed'); // Excludes this tag from _tags.
                var hide_args = this._effectExists('blind') ? ['blind', {direction: 'horizontal'}, 'fast'] : ['fast'];

                var thisTag = this;
                hide_args.push(function() {
                    if(tag.next().hasClass('ui-widget-content')){
                        //continue
                    }
                    else {
                        if (tag.prev().hasClass('ui-widget-content')) {
                            //continue
                        }
                        else {
                            tag.next().find('.ui-widget-content').html(tag.prev().find('.ui-widget-content').html() +tag.next().find('.ui-widget-content').html());
                            tag.prev().remove();
                            tags = [];
                            that._updateSingleTagsField(tags);
                        }
                    }
                    tag.remove();
                    that._updateSingleTagsField(tags);
                });

                tag.fadeOut('fast').hide.apply(tag, hide_args).dequeue();
                tags = [];
                that._updateSingleTagsField(tags);

            } else {
                if(tag.next().hasClass('ui-widget-content')){
                    //continue
                }
                else {
                    if (tag.prev().hasClass('ui-widget-content')) {
                        //continue
                    }
                    else {
                        tag.next().find('.ui-widget-content').html(tag.prev().find('.ui-widget-content').html() +tag.next().find('.ui-widget-content').html());
                        tag.prev().remove();
                    }
                }

                tag.remove();
                tags = [];
                that._updateSingleTagsField(tags);
            }

        },
    });

})(jQuery);