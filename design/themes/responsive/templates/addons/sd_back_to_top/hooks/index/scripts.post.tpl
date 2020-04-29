{if !$sd_back_to_topEmbedded}
    <script>
        (function(_, $) {
            $(_.doc).on('ready', function() {
                var btn = $('.sd-back-to-top'),
                    active = 'is-visible',
                    // elevator mode: btn can return to previous position
                    returnable = 'is-returnable',
                    scrollDelay = 100,
                    scrollTimeout,
                    windowOffsetTop,
                    $scrollElm = $('html, body'),
                    position = 'position',
                    animationType = '{$addons.sd_back_to_top.animation}',
                    distance = '{$addons.sd_back_to_top.distance|default:0}',
                    langUp = '{__("up")}',
                    langBottom = '{__("bottom")}',
                    elevator = '{$addons.sd_back_to_top.elevator}',
                    // elevator mode: save current position 
                    btnOffsetTop;

                function rotateIcon() {
                    if (btnOffsetTop) {
                        btn.removeData(position).removeClass(returnable);
                    } else {
                        btn.addClass(returnable + ' ' + active);
                        btn.attr('title', langBottom);
                    }
                }

                var initBackToTopBtn = {
                    getState: function() {
                        windowOffsetTop = $(window).scrollTop();
                        btnOffsetTop = btn.data(position);

                        if (windowOffsetTop > distance) {
                            btn.addClass(active);

                            if (btn.hasClass(returnable)) {
                                btn.removeData(position).removeClass(returnable);
                            }

                        } else if (!btn.hasClass(returnable) && (windowOffsetTop < (btnOffsetTop || distance)) || (btn.hasClass(returnable) && (windowOffsetTop < distance && windowOffsetTop != 0))) {
                            btn.removeClass(active).removeClass(returnable);

                        }

                        if (btnOffsetTop) {
                            btn.data(position, 0);
                            $scrollElm.stop();
                        }
                    }, 

                    setDefault: function() {
                        btn.on('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            btn.data(position, 0);

                            if (animationType != 'none') {
                                $scrollElm.animate({
                                    scrollTop: 0 },
                                    animationType
                                );
                            } else {
                                $(window).scrollTop(0);
                            }
                        });
                    }, 

                    setElevator: function() {
                        btn.on('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();

                            if (!btnOffsetTop) {
                                btn.data(position, windowOffsetTop);
                                btnOffsetTop = 0;
                            }

                            if (animationType != 'none') {
                                $scrollElm.animate({
                                    scrollTop: btnOffsetTop }, animationType, function() {
                                }).promise().then(rotateIcon());  
                            } else { 
                                rotateIcon();
                                $(window).scrollTop(btnOffsetTop);
                            }
                        });
                    }
                }

                initBackToTopBtn.getState();
                
                if (elevator == 'Y') {
                    initBackToTopBtn.setElevator();
                } else {
                    initBackToTopBtn.setDefault();
                }

                $(window).scroll(function () {
                    if (scrollTimeout) {
                        clearTimeout(scrollTimeout);
                        scrollTimeout = null;
                    }

                    scrollTimeout = setTimeout( function() {
                        initBackToTopBtn.getState();
                    }, scrollDelay);
                });
            });
        }(Tygh, Tygh.$));
    </script>
{/if}
