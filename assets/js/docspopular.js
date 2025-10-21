/**
 * DocsPopular JavaScript
 * Interactive functionality for documentation navigation
 */

(function($) {
    'use strict';

    /**
     * Initialize DocsPopular
     */
    function initDocsPopular() {
        handleDocNavigation();
        handleSmoothScroll();
        highlightActiveNav();
    }

    /**
     * Handle documentation navigation clicks
     */
    function handleDocNavigation() {
        // Handle sidebar navigation
        $('.docspopular-nav-link').on('click', function(e) {
            e.preventDefault();
            navigateToDoc($(this));
        });
        
        // Handle prev/next buttons
        $('.docspopular-nav-button').on('click', function(e) {
            e.preventDefault();
            navigateToDoc($(this));
        });
    }
    
    /**
     * Navigate to a specific doc
     */
    function navigateToDoc($link) {
        const docId = $link.data('doc-id');
        const targetDoc = $('#doc-' + docId);
        
        if (targetDoc.length) {
            // Update sidebar active states
            $('.docspopular-nav-link').removeClass('active');
            $('.docspopular-nav-link[data-doc-id="' + docId + '"]').addClass('active');
            
            // Hide all articles and show target
            $('.docspopular-article').removeClass('active');
            targetDoc.addClass('active');
            
            // Scroll to top of content area smoothly
            $('html, body').animate({
                scrollTop: $('.docspopular-content').offset().top - 20
            }, 400);
            
            // Update URL hash without jumping
            if (history.pushState) {
                history.pushState(null, null, '#doc-' + docId);
            }
        }
    }

    /**
     * Handle smooth scrolling for anchor links
     */
    function handleSmoothScroll() {
        $('a[href^="#"]').on('click', function(e) {
            const target = $(this.getAttribute('href'));
            
            if (target.length && !$(this).hasClass('docspopular-nav-link')) {
                e.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 80
                }, 600);
            }
        });
    }

    /**
     * Highlight active navigation based on URL hash or first item
     */
    function highlightActiveNav() {
        const hash = window.location.hash;
        
        if (hash) {
            const $targetLink = $('.docspopular-nav-link[href="' + hash + '"]');
            if ($targetLink.length) {
                $targetLink.trigger('click');
                return;
            }
        }
        
        // Activate first item if no hash
        const $firstLink = $('.docspopular-nav-link').first();
        if ($firstLink.length) {
            $firstLink.addClass('active');
        }
    }

    /**
     * Handle scroll-based navigation highlighting (optional enhancement)
     */
    function handleScrollHighlight() {
        let scrollTimeout;
        
        $(window).on('scroll', function() {
            clearTimeout(scrollTimeout);
            
            scrollTimeout = setTimeout(function() {
                let current = '';
                
                $('.docspopular-article').each(function() {
                    const $article = $(this);
                    const articleTop = $article.offset().top;
                    
                    if ($(window).scrollTop() >= articleTop - 100) {
                        current = $article.attr('id');
                    }
                });
                
                if (current) {
                    $('.docspopular-nav-link').removeClass('active');
                    $('.docspopular-nav-link[href="#' + current + '"]').addClass('active');
                }
            }, 100);
        });
    }

    /**
     * Add search functionality (optional enhancement)
     */
    function addSearchFunctionality() {
        // Add search input to sidebar
        const searchHTML = `
            <div class="docspopular-search" style="margin-bottom: 1.5rem;">
                <input type="text" 
                       class="docspopular-search-input" 
                       placeholder="Search documentation..." 
                       style="width: 100%; padding: 0.75rem; border: 1px solid var(--docspopular-border); border-radius: 8px; font-size: 0.95rem;">
            </div>
        `;
        
        $('.docspopular-nav').before(searchHTML);
        
        // Handle search
        $('.docspopular-search-input').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            
            $('.docspopular-nav-item').each(function() {
                const $item = $(this);
                const text = $item.text().toLowerCase();
                
                if (text.indexOf(searchTerm) > -1) {
                    $item.show();
                } else {
                    $item.hide();
                }
            });
        });
    }

    /**
     * Add keyboard navigation
     */
    function addKeyboardNavigation() {
        $(document).on('keydown', function(e) {
            const $activeLink = $('.docspopular-nav-link.active');
            
            if (!$activeLink.length) return;
            
            // Arrow Down - Next item
            if (e.keyCode === 40) {
                e.preventDefault();
                const $next = $activeLink.parent().next().find('.docspopular-nav-link');
                if ($next.length) {
                    $next.trigger('click');
                }
            }
            
            // Arrow Up - Previous item
            if (e.keyCode === 38) {
                e.preventDefault();
                const $prev = $activeLink.parent().prev().find('.docspopular-nav-link');
                if ($prev.length) {
                    $prev.trigger('click');
                }
            }
        });
    }

    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        if ($('.docspopular-wrapper').length) {
            initDocsPopular();
            
            // Optional enhancements
            if ($('.docspopular-sidebar').length) {
                addSearchFunctionality();
                addKeyboardNavigation();
            }
        }
    });

})(jQuery);

