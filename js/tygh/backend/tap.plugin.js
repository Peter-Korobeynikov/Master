(function ($) {

    $.fn.extend({
        /**
         * Calculate actually unique selector
         * @returns {string} selector
         */
        getPath: function() {
            var pathes = [];
    
            this.each(function(index, element) {
                var path, $node = jQuery(element);
    
                while ($node.length) {
                    var realNode = $node.get(0), name = realNode.localName;
                    if (!name) { break; }
    
                    name = name.toLowerCase();
                    var parent = $node.parent();
                    var sameTagSiblings = parent.children(name);
    
                    if (sameTagSiblings.length > 1)
                    {
                        allSiblings = parent.children();
                        var index = allSiblings.index(realNode) +1;
                        if (index > 0) {
                            name += ':nth-child(' + index + ')';
                        }
                    }
    
                    path = name + (path ? ' > ' + path : '');
                    $node = parent;
                }
    
                pathes.push(path);
            });
    
            return pathes.join(',');
        }
    });

    $.fn.ceTap = function (args) {

        var plugin = function () {

            var pluginInstanceStorage = {
                selected: 0,
                quickMode: false,
                allowQuickMode: args.allowQuickMode || true,
                mouseMultipleSelection: args.mouseMultipleSelection || false,
                elements: [],
                currentPointer: ''
            }

            var preventClick = args.preventClick || false;
            var preventSelect = args.preventSelect || true;
            var preventContext = args.preventContext || true;

            var calcPosition = function (pointer) {
                var _index = undefined;

                pluginInstanceStorage
                    .elements
                    .forEach(function(item, index) {
                        _index = item.pointer == pointer ? index : _index
                    });

                return _index;
            }

            var eachFunction = function (index, self) {
                var $self = $(self);
                $self.storage = pluginInstanceStorage;

                var timer = undefined;
                var startTimer = undefined;

                var isSelected = false;
                var isPreStart = false;

                var isTapStart = false;
                var isTapStartStamp = 0;

                var shouldIPreventClick = false;

                var clearingTimers = function (variable, clearCallback, event, stopFlag) {
                    if (!!variable) {
                        clearCallback();

                        if (args.onStop) {
                            if (stopFlag) {
                                args.onStop(event, $self);
                            }
                        }
                    }
                };

                var timeouts = {
                    mainDelay: args.timeout || 1000,
                    mainDelayClear: function () {
                        clearTimeout(timer);
                        timer = undefined;
                    },

                    onStartDelay: args.onStartDelay || 10,
                    onStartDelayClear: function () {
                        clearTimeout(startTimer);
                        startTimer = undefined;
                    },
                };

                var handlersSuccess = {
                    select: function (event) {
                        // additional check before selecting
                        if (args.preSuccess) {
                            if (args.preSuccess(event, $self)) {
                                return;
                            }
                        }

                        isSelected = !isSelected;

                        pluginInstanceStorage.selected++;
                        pluginInstanceStorage.currentPointer = $self.getPath();
                        pluginInstanceStorage.elements[calcPosition($self.getPath())].selected = true;

                        if (args.onSuccess) {
                            args.onSuccess(event, $self);
                        }

                        if (pluginInstanceStorage.allowQuickMode) {
                            if (pluginInstanceStorage.selected > 0) {
                                pluginInstanceStorage.quickMode = true;
                            }
                        }
                    },

                    reject: function (event) {
                        // additional check before rejecting
                        if (args.preReject) {
                            if (args.preReject(event, $self)) {
                                return;
                            }
                        }

                        isSelected = !isSelected;

                        pluginInstanceStorage.selected--;
                        pluginInstanceStorage.currentPointer = $self.getPath();
                        pluginInstanceStorage.elements[calcPosition($self.getPath())].selected = false;

                        if (args.onReject) {
                            args.onReject(event, $self);
                        }

                        if (pluginInstanceStorage.selected == 0) {
                            pluginInstanceStorage.quickMode = false;
                        }
                    }
                }

                var handlers = {

                    success: function (event, forceReject, mouseMultipleSelectionParams) {

                        var oldPointer = '';
                        var newPointer = '';

                        var oldPosition = 0;
                        var newPosition = 0;

                        var selectMode = false;
                        
                        // save previous value of previously selected item 'position in list'
                        if (mouseMultipleSelectionParams) {
                            oldPointer = pluginInstanceStorage.currentPointer;
                            oldPosition = calcPosition(oldPointer);

                            newPointer = mouseMultipleSelectionParams.sel;
                            newPosition = calcPosition(newPointer);

                            selectMode = !pluginInstanceStorage.elements[newPosition].selected;
                            forceReject = !selectMode;
                        }

                        if (isSelected || forceReject) {
                            handlersSuccess.reject(event);
                        } else {
                            handlersSuccess.select(event);
                        }

                        isPreStart = false;

                        timeouts.mainDelayClear();

                        // additional logic for multiple selecting
                        if (mouseMultipleSelectionParams) {

                            if (!mouseMultipleSelectionParams.shiftKey) {
                                return;
                            }
                            
                            // non-handling behavior, just ignore
                            if (oldPosition == undefined || newPosition == undefined) {
                                return;
                            }

                            // calculate all blocks that must be selected
                            // and [un]select them
                            var delta = oldPosition < newPosition ? oldPosition : newPosition;
                            var elmStorage = $self.storage.elements;

                            for (var i = 0; i <= Math.abs(oldPosition - newPosition); i++) {

                                var _elmStorage = elmStorage[i+delta];

                                if (selectMode) {
                                    // normal logic, select all unselected
                                    _elmStorage.handlersSuccess.select(event);
                                } else {
                                    // inverse logic, unselect all selected
                                    _elmStorage.handlersSuccess.reject(event);
                                }
                            }

                            // force saving current position
                            pluginInstanceStorage.currentPointer = newPointer;

                        }
                    },


                    stop: function (event) {
                        clearingTimers(startTimer, timeouts.onStartDelayClear, event);
                        clearingTimers(timer, timeouts.mainDelayClear, event, true);
                    },


                    override: function (callback, preventFlag) {
                        return function (event) {
                            if (callback) {
                                callback(event, $self);
                            }

                            if (preventFlag) {
                                events.killEvent(event);
                            }
                        };
                    }
                };

                var events = {
                    killEvent: function (event) {
                        event.preventDefault();
                        return;
                    },

                    scrolling: function (event) {

                        if (isPreStart == false) {
                            handlers.stop(event);
                        }


                        if (isTapStart) {
                            isTapStart = false;
                        }
                    },

                    tapStart: function (event) {
                        isTapStart = true;
                        isTapStartStamp = performance.now();

                        var focusableElements = $self.find(':focusable');
                        var eventTarget = $(event.target);
                        var eventTargetParent = eventTarget.parent();


                        if (eventTarget.find('i[class*=icon]')
                            && (eventTargetParent.is('a, button'))) {
                                shouldIPreventClick = focusableElements.is(eventTargetParent);
                        } else {
                            shouldIPreventClick = focusableElements.is(eventTarget);
                        }

                        // if `shouldIPreventClick == true` this timeout will be cleaned in `tapEnd` event
                        startTimer = setTimeout(function () {
                            event.preventDefault();
                            isPreStart = true;

                            if (args.onStart) {
                                args.onStart(event, $self);
                            }

                            timer = setTimeout(handlers.success, timeouts.mainDelay, event);
                            timeouts.onStartDelayClear();
                        }, timeouts.onStartDelay);
                    },

                    tapEnd: function (event) {
                        if (isTapStart) {
                            isTapStart = false;


                            if ((performance.now() - isTapStartStamp) <= 300) {
                                if (shouldIPreventClick) {
                                    handlers.stop(event);
                                    return;
                                }

                                if (pluginInstanceStorage.quickMode) {
                                    handlers.success(event);
                                }
                            }
                        }

                        if (event.cancelable) {
                            event.preventDefault();
                        }
                        handlers.stop(event);
                    }
                };

                $self.storage.elements.push({
                    timeouts: timeouts,
                    handlers: handlers,
                    handlersSuccess: handlersSuccess,
                    events: events,
                    pointer: $self.getPath(),
                    selected: false
                });

                if (Modernizr.touchevents) {
                    $self.on('contextmenu',
                        handlers.override(args.onContext, preventContext)
                    );
                    $self.on('selectstart',
                        handlers.override(args.onSelect, preventSelect)
                    );
                }

                $self.on('touchmove', events.scrolling);
                $self.on('touchstart', events.tapStart);
                $self.on('touchend', events.tapEnd);

                // force mouse selecting, else prevent
                if ($self.storage.mouseMultipleSelection) {
                    $self.on('click', function(e) {
                        if ($(e.target).is(':focusable') || $(e.target).is('a') || $(e.target).parents().is('a')) {
                            return;
                        }

                        handlers.success(e, false, {
                            shiftKey: e.shiftKey,
                            data: $self.data(),
                            sel: $self.getPath()
                        });
                    });
                } else {
                    $self.on('click',
                        handlers.override(args.onClick, preventClick)
                    );
                }
            }

            return {
                each: eachFunction,
                storage: pluginInstanceStorage,

                selectObject: function (index, reverse) {
                    pluginInstanceStorage.elements[index].handlers.success();
                },

                rejectObject: function (index, reverse) {
                    // force rejecting
                    pluginInstanceStorage.elements[index].handlers.success(true);
                }
            }
        }

        var ceTap = plugin();

        this.each(ceTap.each);

        return ceTap;

    }

})(jQuery);
