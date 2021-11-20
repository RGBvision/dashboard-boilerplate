const $window = $(window);
const $body = $('body');
const $locale = $('html').attr('lang') || navigator.language;

const DashboardCommon = {

    initialize() {
        this.build();
        this.events();
    },

    build() {
        this.setTimezoneCookie();
        this.setThemeCookie();
        this.addValidateMethods();
        this.initializeClipboardPlugin();
        this.initializeBootstrapTooltip();
        this.initializeBootstrapPopover();
        this.setSidebarScrollbar();
        this.foldSidebar();
        this.stickHorizontalMenu();
    },

    events() {
        this.onThemeChange();
        this.onSidebarToggle();
        this.onSidebarHover();
        this.onSidebarOutsideClick();
        this.onHorizontalMenuToggle();
    },

    // set browser timezone cookie
    setTimezoneCookie() {
        const timezone = $body.data('timezone');
        const browser_timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

        if (timezone?.length && browser_timezone && (browser_timezone !== timezone)) {
            Cookies.set('browser_timezone', browser_timezone, {expires: 365, path: '/'});
            location.reload();
        }
    },

    // set theme cookie
    setThemeCookie() {
        if (!Cookies.get('theme')) {
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                Cookies.set('theme', 'dark', {expires: 365});
            } else {
                Cookies.set('theme', 'light', {expires: 365});
            }
            location.reload();
        }
    },

    // switch theme handler
    onThemeChange() {
        $('.themeChange').on('click', (e) => {
            e.preventDefault;

            let newTheme = (Cookies.get('theme') === 'dark') ? 'light' : 'dark';
            let cssElement = $('#themeCSS');
            let cssURL = cssElement.attr('href');

            Cookies.set('theme', newTheme, {expires: 365});
            cssElement.attr('href', cssURL.replace(/(dark|light)/g, newTheme));
            $body.data('theme', newTheme);
            $('#themeSwitch').prop('checked', (newTheme === 'dark'));

            window.dispatchEvent(new CustomEvent('themechange', {detail: {theme: newTheme}}));

            return false;
        });
    },

    // initialize clipboard plugin
    initializeClipboardPlugin() {

        const clipboardButtons = $('.btn-clipboard');

        if (clipboardButtons.length) {

            // Enabling tooltip to all clipboard buttons
            clipboardButtons.attr('data-bs-toggle', 'tooltip').attr('title', buttonClipboard);

            const clipboard = new ClipboardJS('.btn-clipboard');

            clipboard.on('success', function (e) {
                e.trigger.innerHTML = 'copied';
                setTimeout(function () {
                    e.trigger.innerHTML = 'copy';
                    e.clearSelection();
                }, 700)
            });
        }
    },

    // initialize bootstrap tooltip
    initializeBootstrapTooltip() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    },

    // initialize bootstrap popover
    initializeBootstrapPopover() {
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        });
    },

    // apply perfect-scrollbar to sidebar
    setSidebarScrollbar() {
        if ($('.sidebar .sidebar-body').length) {
            const sidebarBodyScroll = new PerfectScrollbar('.sidebar-body', {
                wheelPropagation: false,
                minScrollbarLength: 20
            });
        }
    },

    // sidebar toggle handler
    onSidebarToggle() {
        $('.sidebar-toggler').on('click', function (e) {
            e.preventDefault();
            $('.sidebar-header .sidebar-toggler').toggleClass('active not-active');
            if (window.matchMedia('(min-width: 992px)').matches) {
                e.preventDefault();
                $body.toggleClass('sidebar-folded');
            } else if (window.matchMedia('(max-width: 991px)').matches) {
                e.preventDefault();
                $body.toggleClass('sidebar-open');
            }
        });
    },

    // sidebar hover handler
    onSidebarHover() {

        //  open sidebar-folded when hover
        $(".sidebar .sidebar-body").hover(
            function () {
                if ($body.hasClass('sidebar-folded')) {
                    $body.addClass("open-sidebar-folded");
                }
            },
            function () {
                if ($body.hasClass('sidebar-folded')) {
                    $body.removeClass("open-sidebar-folded");
                }
            });
    },

    // sidebar outside click/touch handler
    onSidebarOutsideClick() {
        $(document).on('click touchstart', function (e) {
            e.stopPropagation();

            // closing off sidebar menu when clicking outside of it
            if (!$(e.target).closest('.sidebar-toggler').length) {
                var sidebar = $(e.target).closest('.sidebar').length;
                var sidebarBody = $(e.target).closest('.sidebar-body').length;
                if (!sidebar && !sidebarBody) {
                    if ($body.hasClass('sidebar-open')) {
                        $body.removeClass('sidebar-open');
                    }
                }
            }
        });
    },

    // sidebar-folded on large devices
    foldSidebar() {

        function iconSidebar(e) {
            if (e.matches) {
                $body.addClass('sidebar-folded');
            } else {
                $body.removeClass('sidebar-folded');
            }

            // fix charts width
            setTimeout(() => window.dispatchEvent(new Event('resize')), 200);
        }

        const desktopMedium = window.matchMedia('(min-width:992px) and (max-width: 1199px)');
        desktopMedium.addEventListener("change", iconSidebar);
        iconSidebar(desktopMedium);
    },

    // horizontal menu toggle handler
    onHorizontalMenuToggle() {
        $('[data-toggle="horizontal-menu-toggle"]').on("click", function () {
            $(".horizontal-menu .bottom-navbar").toggleClass("header-toggled");
        });
    },

    // horizontal submenu click handler
    onHorizontalMenuShow() {
        const navItemClicked = $('.horizontal-menu .page-navigation > .nav-item');
        navItemClicked.on("click", function (event) {
            if (window.matchMedia('(max-width: 991px)').matches) {
                if (!($(this).hasClass('show-submenu'))) {
                    navItemClicked.removeClass('show-submenu');
                }
                $(this).toggleClass('show-submenu');
            }
        })
    },

    // stick horizontal menu on scroll down
    stickHorizontalMenu() {
        $window.scroll(function () {
            if (window.matchMedia('(min-width: 992px)').matches) {
                const menu = $('.horizontal-menu');
                if ($window.scrollTop() >= 60) {
                    menu.addClass('fixed-on-scroll');
                } else {
                    menu.removeClass('fixed-on-scroll');
                }
            }
        });
    },

    // add custom jq validate methods
    addValidateMethods() {
        $.validator.addMethod(
            'regex',
            (value, element, regexp) => {
                var re = new RegExp(regexp);
                return re.test(value);
            },
            validator_i18n.required
        );
    },

    // show form validation messages as tooltip
    validateCustomErrorMessage(errorMap, errorList) {

        // Removing tooltips and `is-invalid` class for valid elements
        $.each(this.validElements(), (index, element) => {
            const $element = $(element);
            if ($element.hasClass('select2-hidden-accessible')) {
                $('#select2-' + $element.attr('id') + '-container').parent().removeClass('border-danger');
            } else {
                $element.removeClass('is-invalid');
            }
            $element
                .attr('title', '') // Clear title 'cause no error
                .tooltip('dispose');
        });

        // Creating new tooltips and set `is-invalid` class for invalid elements
        $.each(errorList, (index, error) => {
            var $element = $(error.element);
            if ($element.hasClass('select2-hidden-accessible')) {
                $('#select2-' + $element.attr('id') + '-container').parent().addClass('border-danger');
            } else {
                $element.addClass('is-invalid');
            }
            $element.tooltip('dispose') // Remove old tooltip
                .attr('title', error.message)
                .tooltip(); // New tooltip with error message
        });

    },

};

$(document).ready(() => {
    DashboardCommon.initialize();
});