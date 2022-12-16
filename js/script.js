(function($) {
    $(document).ready(function() {
        $('.top__button').click(function() {
            $("html, body").animate({
                scrollTop: 0
            }, "fast");
        })

        $(document).scroll(function(e) {
            if (jQuery(this).scrollTop() > jQuery(document).height() - window.innerHeight * 1.5) {
                $('.top__button').fadeOut('fast');
            } else if ($(this).scrollTop() > window.innerHeight / 2) {
                if (!$('.top__button').is(':visible')) {
                    $('.top__button').fadeIn('fast');
                }
            } else {
                $('.top__button').fadeOut('fast');
            }
        })

        MicroModal.init({
            disableScroll: false,
            disableFocus: true,
            onShow: modal => $('.regular-page_container').css('opacity', '0.3'),
            onClose: modal => $('.regular-page_container').css('opacity', '1')
        });

        let $mNavHeight = $('#headerfix').height() ? $('#headerfix').height() : $('.mobile-top-panel').height();
        $(document).ready(function() {
            $('.tags-container').hScroll(100); // You can pass (optionally) scrolling amount
        });

        $('.fileuploadfield').change(function() {
            if ($(this).val().length) {
                let filename = $(this).val().substring($(this).val().lastIndexOf('\\') + 1);
                $(this).parents().eq(2).find('.add-file-text').text(filename.length < 20 ? filename : `${filename.substring(0,20)}...`);
            } else {
                $(this).parents().eq(2).find('.add-file-text').text('Добавить файл');
            }
        });

        $('.read-more-posts .posts-grid').masonry({
            columnWidth: '.grid-item',
            itemSelector: '.grid-item'
        });

        if (document.querySelector('.stk-theme_43709__slider-card')) {
            $('.stk-theme_43709__slider-card').wrapAll("<div id='cards-slider'></div>");
            $('<div class="slider-arrows"></div>').insertBefore('#cards-slider');
            $('#cards-slider').wrap("<div id='cards-slider-container'></div>")
            for (let i = 0; i < $('#cards-slider').children().length; i++) {
                $('#cards-slider').find(`.stk-theme_43709__slider-card:eq(${i})`).prepend(`<div class='card-number'><span>${i+1}</span></div>`);
            }

            $cardsSlider = $('#cards-slider');
            $cardsSlider.on('init reInit afterChange', function(event, slick, currentSlide, nextSlide) {
                //currentSlide is undefined on init -- set it to 0 in this case (currentSlide is 0 based)
                if ($('.pagingInfo').length) {
                    var i = (currentSlide ? currentSlide : 0) + 1;
                    $('.pagingInfo').text(i + '/' + slick.slideCount);
                }
            });
            $cardsSlider.slick({
                infinite: false,
                centerMode: true,
                centerPadding: '0',
                slidesToShow: 3.2,
                responsive: [{
                        breakpoint: 1700,
                        settings: {
                            slidesToShow: 3.2,
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            centerMode: false,
                            slidesToShow: 1.2,
                        }
                    }
                ],
                arrows: true,
                appendArrows: $('.slider-arrows'),
                slidesToScroll: 1,
                prevArrow: '<button id="prev" type="button" class="slider-button"><i class="fa fa-angle-left" aria-hidden="true"></i></button><span class="pagingInfo"></span>',
                nextArrow: '<button id="next" type="button" class="slider-button"><i class="fa fa-angle-right" aria-hidden="true"></i></button>'
            });

            $('#cards-slider-container').height($('#cards-slider').height());

            $(window).resize(function() {
                $('#cards-slider-container').height($('#cards-slider').height());
            });
        }

        if ($('body').hasClass('single-post') && $('.fca_qc_quiz').length) {

            $('.regular-page_container .top-block-area').hide();
            $('.addtoany_container').insertAfter('#fca_qc_share_link_facebook');
            $('.addtoany_container .a2a_kit').children().not('.a2a_button_vk').hide();
            $('#fca_qc_restart_button').insertBefore('#fca_qc_share_link_facebook');
        }

        if ($('body').hasClass('page-template-events')) {
            $('.event_card').on('click', '.event_about-button', function() {
                $(this).parent().parent().siblings().show();
                $(this).parent().parent().hide();
            })
        }

        if (document.querySelector('.addtoany_shortcode')) {
            const observer = new MutationObserver(function(mutations) {
                if ($('.a2a_label').length) {
                    const socialsText = $('.addtoany_shortcode').children().find('.a2a_label');
                    for (let i = 0; i < socialsText.length; i++) {
                        let text = $(socialsText[i]).text().trim();
                        $(socialsText[i]).text(text == 'Twitter' ? 'Твитнуть' : 'Поделиться');
                    }
                    observer.disconnect();
                }
            });

            const secondObserver = new MutationObserver(function(mutations) {
                if ($('.wpd-thread-head').children().length) {
                    $("#wpdcom .ql-editor.ql-blank").removeAttr("data-placeholder");
                    $('.wpd-thread-head').prependTo('#wpdcom');
                    //$('.wpd-thread-info .wpdtc').insertAfter('.wpd-thread-info');
                    $('#comments-icon').insertAfter('.wpd-thread-info');
                    secondObserver.disconnect();
                }
            });

            observer.observe(document.querySelector('.addtoany_shortcode'), {
                childList: true,
                subtree: true,
            });

            secondObserver.observe(document.body, {
                childList: true,
                subtree: true,
            });
        }

        $('.search-icon').click(() => {
            $('#st-container').hide();
        })

        $('.closeit').click(() => {
            $('#st-container').show();
        })

        if ($('body').hasClass('single-post') && $('.fca_qc_quiz').length == 0) {
            const elementsTarget = document.querySelectorAll("article.unique-post");
            window.addEventListener("scroll", function() {
                var post_viewed_data = get_current_article_id();
                const post_viewed_id = post_viewed_data[0];
                const post_link = post_viewed_data[1];
                const post_title = post_viewed_data[2] + " – Рея";
                var post_referer = History.getState().url;
                History.pushState({
                    'post_id': post_viewed_id
                }, post_title, post_link);
                //location.hash = post_viewed_href;
            });
        }

        function get_current_article_id() {

            if ($('article').length > 0) {
                var y = $(window).scrollTop();

                var article = $('article').eq(0);

                if (y > 0) {
                    $('article.unique-post').each(function() {

                        if ($(this).offset().top > y) return false;
                        article = $(this);
                    });
                }
                return [article.data('id'), article.data('href'), article.data('title')];
            }

        }

        window.almEmpty = function(alm) {
            $('.nothing-found').show();
        };

        if ($('.accordion-container').length) {
            const accordionSources = new Accordion('.accordion-container', {
                openOnInit: [0],
                duration: 400,
                showMultiple: true,
            });
        }

        if ($(document).find('.alm-masonry').length) {

            let searchParams = new URLSearchParams(window.location.search);
            let param = searchParams.get('tag')
            var buttons = document.querySelectorAll('.tags-container .tag-name');
            if (buttons) {
                // Loop each button and add click event
                [].forEach.call(buttons, function(button) {
                    button.addEventListener('click', clickHandler);
                });
            }
            if (param) {
                /*window.almComplete = function(alm){
                	$('.tags-container .tag-name[data-taxonomy-terms="' + param + '"]').click();
                };*/
            }

            function clickHandler() {
                const obj = {};
                var transition = 'fade';
                var speed = 250;
                var data = this.dataset;
                if (data.taxonomyTerms == '*') {
                    ajaxloadmore.reset();
                    obj['taxonomy'] = data.taxonomy;
                    obj['taxonomy-terms'] = data.taxonomyTerms;
                    obj['taxonomy-operator'] = data.taxonomyOperator;
                    obj['taxonomy-relation'] = 'OR';
                    ajaxloadmore.filter(transition, speed, 'all');
                } else {
                    obj['taxonomy'] = data.taxonomy;
                    obj['taxonomy-terms'] = data.taxonomyTerms;
                    obj['taxonomy-operator'] = data.taxonomyOperator;
                    obj['taxonomy-relation'] = 'OR';
                    ajaxloadmore.filter(transition, speed, obj);
                }
            }
        }

        $('.tags-container').on('click', 'button', function() {
            $(this).addClass('active');
            $(this).siblings().removeClass('active');
            var filterValue = $(this).attr('data-taxonomy-terms');
        });

        $('.docs-tags-container').on('click', 'button', function() {
            $(this).addClass('active');
            $(this).siblings().removeClass('active');
            var filterValue = $(this).attr('data-filter');
            $grid.isotope({
                filter: filterValue
            });
        });

        $(window).resize(function() {
            $mNavHeight = $('#headerfix').height() ? $('#headerfix').height() : $('.mobile-top-panel').height();
        });

        $('.top-block-pointer .click-box').click(function() {
            $('html, body').animate({
                scrollTop: $('#ajax-load-more').offset().top - $mNavHeight * 2.5,
            }, 400);
        });

        $(document).on('click', 'article sup', function() {
            $('html, body').animate({
                scrollTop: $(this).closest('article').find('.sources_container').offset().top - $mNavHeight * 2.5,
            }, 400);
        });

        $(document).on('click', '.top-block-cat-views_comments', function() {
            $('html, body').animate({
                scrollTop: $(this).closest('article').find('#comments').length ? $(this).closest('article').find('#comments').offset().top - $mNavHeight * 2.5 : $(this).closest('article').find('.show_comments-block').offset().top - $mNavHeight * 2.5,
            }, 400);
        });

        $(document).on('mouseover', '.grid-item_post-image', (event) => {
            event.target.style.backgroundSize = '110%';
        })

        $(document).on('mouseleave', '.grid-item_post-image', (event) => {
            event.target.style.backgroundSize = '100%';
        })

        $('.sbi_photo').on('mouseover', (event) => {
            //event.target.style.backgroundSize = '105%';
            $(event.target).animate({
                backgroundSize: '105%',
            }, 20);
        })

        $('.sbi_photo').on('mouseleave', (event) => {
            event.target.style.backgroundSize = '100%';
        })
    });
})(jQuery);

jQuery(function($) {
    $.fn.hScroll = function(amount) {
        amount = amount || 120;
        $(this).bind("DOMMouseScroll mousewheel", function(event) {
            var oEvent = event.originalEvent,
                direction = oEvent.detail ? oEvent.detail * -amount : oEvent.wheelDelta,
                position = $(this).scrollLeft();
            position += direction > 0 ? -amount : amount;
            $(this).scrollLeft(position);
            event.preventDefault();
        })
    };
});