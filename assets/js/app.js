'use strict';

//-- Config data
var AppSettings = {
  name: 'RGB.dashboard',
  version: '1.7',
  settings: {
    useAjax: true,
    leftFixed: false,
    leftFolded: false,
    leftHeaders: false,
    headerFixed: true,
    boxedLayout: false
  }
};

window.onload = function () {
  if (typeof history.pushState === "function") {
    history.pushState("jibberish", null, null);
    window.onpopstate = function () {
      history.pushState('newjibberish', null, null);
      console.log('history.pushState');
      $('.modal.show').modal('hide');
    };
  }
};

function isLocalStorage() {

  var testKey = 'testLocalStorage', storage = window.localStorage;

  try {
    storage.setItem(testKey, '1');
    storage.removeItem(testKey);

    return true;

  } catch (error) {

    return false;
  }
}

if (isLocalStorage()) {

  if (!localStorage.AppSettings) {
    localStorage.AppSettings = JSON.stringify(AppSettings);
  }

  AppSettings = JSON.parse(localStorage.AppSettings);
}

var $html = $('html'),
  $win = $(window),
  $body = $('body'),
  app = $('#app'),
  navbar = $('#navbar'),
  navbarheader = $('.navbar-header'),
  leftside = $('#left-side'),
  leftheaders = leftside.find('.left_header'),
  leftnav = leftside.find('#left-menu > .nav'),
  historyState = false, //ToDo
  codemirrorTheme = 'dracula';

var loader = {
  show: function () {
    $('body').css('cursor', 'wait');
    $('.page-loader').fadeIn(50);
  },
  fade: function () {
    $('body').css('cursor', 'auto');
    $('.page-loader').fadeOut(500);
  },
  hide: function () {
    loader.fade();
  }
};

//-- Application
var App = {

  initialized: false,

  //-- Initialize
  initialize: function () {
    if (this.initialized)
      return;

    this.initialized = true;

    this.load();			//-- Load settings from LocalStorage
    this.set();				//-- Set settings in LocalStorage
    this.dataJquery();
    this.dataPlugin();
    this.build();			//-- Main functions
    this.events();			//-- Events functions
    this.plugins();			//-- Plugins
    this.ajaxSettings();	//-- Ajax Settings
  },

  //-- Build
  build: function () {
    this.setScrollers();
    this.toggleLeftSide();
    this.collapseMenu();
    this.fullScreen();
    this.mobileToggle();
    this.tooltipShow();
    this.navTabs();
    this.refreshTabs();
    this.cardMinimize();
    this.editLayout();
  },

  //-- Events
  events: function () {
    this.onClick();
    this.menuLoad();
    this.cardFullscreen();
    this.cardCollapse();
    this.scrollHeight();
    this.resizeLeftSide();
    this.HiddenModal();
    this.HideTooltipOnModal();
  },

  //-- Plugins
  plugins: function () {
    $(window).resize(function () {
      setTimeout(function () {
        //$('.dt-responsive').DataTable().responsive.recalc(); //.columns.adjust() //.draw()
      }, 500);
    });
  },

  //-- Plugins
  editLayout: function () {

    $(document).on('click', '#change-layout', function (event) {
      event.preventDefault();
      $('.edit-layout').addClass('edit');
      $('#change-layout').addClass('hidden');
      $('#save-layout, #dismiss-layout').removeClass('hidden');
      $(window).resize().trigger('resize');
    });

    $(document).on('click', '#save-layout', function (event) {
      event.preventDefault();
      $('.edit-layout').removeClass('edit');
      $('#change-layout').removeClass('hidden');
      $('#save-layout, #dismiss-layout').addClass('hidden');
      $(window).resize().trigger('resize');
    });

    $(document).on('click', '#dismiss-layout', function (event) {
      event.preventDefault();
      $('.edit-layout').removeClass('edit');
      $('#change-layout').removeClass('hidden');
      $('#save-layout, #dismiss-layout').addClass('hidden');
      $(window).resize().trigger('resize');
    });

  },

  //-- After ajax
  ajax: function () {
    this.onClick();
  },

  //-- Ajax Settings
  ajaxSettings: function () {
    $.ajaxSetup({
      cache: false,
      error: function (jqXHR, exception) {
        if (jqXHR.status === 0) {
          loader.hide();
          $.jGrowl(ajaxErrorStatus, {
            header: ajaxErrorStatusMess,
            theme: 'danger'
          });
        } else if (jqXHR.status === 404) {
          loader.hide();
          $.jGrowl(ajaxErrorStatus404, {
            header: ajaxErrorStatusMess,
            theme: 'danger'
          });
        } else if (jqXHR.status === 401) {
          loader.hide();
          $.jGrowl(ajaxErrorStatus401, {
            header: ajaxErrorStatusMess,
            theme: 'danger'
          });
        } else if (jqXHR.status === 500) {
          loader.hide();
          $.jGrowl(ajaxErrorStatus500, {
            header: ajaxErrorStatusMess,
            theme: 'danger'
          });
        } else if (exception === 'parsererror') {
          loader.hide();
          $.jGrowl(ajaxErrorStatusJSON, {
            header: ajaxErrorStatusMess,
            theme: 'danger'
          });
        } else if (exception === 'timeout') {
          loader.hide();
          $.jGrowl(ajaxErrorStatusTimeOut, {
            theme: 'danger'
          });
        } else if (exception === 'abort') {
          loader.hide();
          $.jGrowl(ajaxErrorStatusAbort, {
            header: ajaxErrorStatusMess,
            theme: 'danger'
          });
        } else {
          loader.hide();
          $.jGrowl(jqXHR.responseText, {
            header: ajaxErrorStatusMess,
            theme: 'danger'
          });
        }
      }
    });
  },

  //-- Settings
  settings: function () {

  },

  //-- Set settings
  set: function ($config, $data) {
    if (isLocalStorage()) {
      AppSettings.settings[$config] = $data;
      localStorage.AppSettings = JSON.stringify(AppSettings);
    }
  },

  //-- Load settings
  load: function () {
    if (AppSettings.settings.leftFixed) {
      app.toggleClass('left-fixed');
    } else {
      app.removeClass('left-fixed');
    }

    if (AppSettings.settings.leftFolded) {
      app.addClass('left-folded');
      $body.addClass('nav-collapsed');
    } else {
      app.removeClass('left-folded');
      $body.removeClass('nav-collapsed');
    }

    if (AppSettings.settings.leftHeaders) {
      leftheaders.toggleClass('hidden');
    } else {
      leftheaders.removeClass('hidden');
    }

    if (AppSettings.settings.headerFixed) {
      app.toggleClass('header-fixed');
    }

    if (AppSettings.settings.boxedLayout) {
      app.toggleClass('boxed');
    }
  },


  // ------------------------------------------------------------------------------------------------
  // Remove Bootstrap CSS keep :active
  // ------------------------------------------------------------------------------------------------
  onClick: function () {
    $body.on('click', '.btn, a, button, .switch, .switch input', function () {
      $('.btn, a, button, .switch, .switch input').blur();
    });
    if (jQuery.browser.mobile) {
      $body.on('focus', '.btn, a, button, .switch, .switch input', function () {
        $(this).blur();
      });
    }
  },


  // ------------------------------------------------------------------------------------------------
  // Data jQuery functions lazy load
  // ------------------------------------------------------------------------------------------------
  dataJquery: function () {
    $("[data-jquery]").each(function () {
      var self = $(this);
      var options = eval('[' + self.attr('data-options') + ']');

      if ($.isPlainObject(options[0])) {
        options[0] = $.extend({}, options[0]);
      }

      sourceLoad.load(jquery_config[self.attr('data-jquery')]).then(function () {
        self[self.attr('data-jquery')].apply(self, options);
        self.removeAttr('data-jquery');
      });
    });
  },


  // ------------------------------------------------------------------------------------------------
  // Data jQuery functions lazy load
  // ------------------------------------------------------------------------------------------------
  dataPlugin: function () {
    $('[data-plugin]').each(function () {
      var self = $(this);
      sourceLoad.load(jquery_plugin[self.attr('data-plugin')]);
    });
  },


  // ------------------------------------------------------------------------------------------------
  // Load page and mark active menu element
  // ------------------------------------------------------------------------------------------------
  menuLoad: function () {
    var $name = $(document).find('div[data-page-id]');

    var $link = $('#nav-' + $name.data('page-id'));

    if ($link !== undefined) {

      $(".nav li").not('.tabs').removeClass('active open').find('.nav-link').attr('aria-expanded', false);
      $(".nav li").not('.tabs').find('.sub-menu').removeClass('mm-show').attr('aria-expanded', false);

      $link.closest('li').addClass('active');

      $link.attr('aria-expanded', true);

      if ($link.parents('li.nav-item').length) {
        $link.parents('li.nav-item').addClass('active mm-active').find('.nav-link').attr('aria-expanded', true);
      }
      if ($link.parents('ul.nav-second-level').length) {
        $link.parents('ul.nav-second-level').addClass('mm-show').attr('aria-expanded', true);
      }

      var offset = $link.offset();
      if ((offset !== undefined) && (offset.top > ($win.height() * 0.7))) $(".nano").nanoScroller({scrollTo: $link});
    }

  },

  // ------------------------------------------------------------------------------------------------
  // Scrollers
  // ------------------------------------------------------------------------------------------------
  setScrollers: function () {
    //Left nav scroll
    $(".nano").nanoScroller();
    //slim scroll
    $('.scrollDiv').nanoScroller();
  },


  // ------------------------------------------------------------------------------------------------
  // Toggle main menu
  // ------------------------------------------------------------------------------------------------
  toggleLeftSide: function () {
    $(document).on('click', '#left-folded', function (event) {

      event.preventDefault();

      $body.toggleClass("nav-collapsed");

      $body.hasClass('nav-collapsed')
        ? App.set('leftFolded', true)
        : App.set('leftFolded', false);

      $(window).resize().trigger('resize');
    });

    $(document).on('click', '#left-collapse, #left-collapse-overlay', function (event) {

      event.preventDefault();

      $body.toggleClass("nav-toggle");

      if ($body.hasClass('nav-toggle')) {
        $('#left-collapse-overlay').fadeIn(250);
      } else {
        $('#left-collapse-overlay').fadeOut(250);
      }

      $(window).resize().trigger('resize');
    });

    if (jQuery.browser.mobile && ($(window).width() < 992)) {
      var openMenuElement = document.getElementById('left-pan-toggler');
      var omH = new Hammer(openMenuElement);
      omH.get('pan').set({direction: Hammer.DIRECTION_RIGHT, threshold: 25});
      omH.on("panright", function (ev) {
        if (!$body.hasClass('nav-toggle')) {
          $body.toggleClass("nav-toggle");
          $('#left-collapse-overlay').fadeIn(250);
        }
      });
      var closeMenuElement = document.getElementById('left-collapse-overlay');
      var cmH = new Hammer(closeMenuElement);
      cmH.get('pan').set({direction: Hammer.DIRECTION_LEFT, threshold: 25});
      cmH.on("panleft", function (ev) {
        if ($body.hasClass('nav-toggle')) {
          $body.toggleClass("nav-toggle");
          $('#left-collapse-overlay').fadeOut(250);
        }
      });
    } else {
      $('#left-pan-toggler').remove();
    }

  },


  // ------------------------------------------------------------------------------------------------
  // Toggle resize main menu
  // ------------------------------------------------------------------------------------------------
  resizeLeftSide: function () {
    $(window).resize().trigger('resize');
  },


  // ------------------------------------------------------------------------------------------------
  // Collapsible main menu
  // ------------------------------------------------------------------------------------------------
  collapseMenu: function () {
    $('#menu').metisMenu({
      triggerElement: '.nav-link',
      parentTrigger: '.nav-item',
      subMenu: '.nav.nav-second-level',
      toggle: true
    });
  },


  // ------------------------------------------------------------------------------------------------
  // Full screen mode
  // ------------------------------------------------------------------------------------------------
  fullScreen: function () {

    if (!screenfull.enabled) {
      return false;
    }

    $('body').on('click', '#full-screen', function (event) {
      event.preventDefault();
      screenfull.toggle();
      if (screenfull.isFullscreen) {
        $(this).find('i').addClass('sli-resize-move-expand-1').removeClass('sli-resize-move-shrink-1');
      } else {
        $(this).find('i').addClass('sli-resize-move-shrink-1').removeClass('sli-resize-move-expand-1');
      }
      $(window).resize().trigger('resize');
    });

  },

  cardMinimize: function () {
    $('.card').each(function (i, e) {
      var $this = $(this),
        pageID = $('div[data-page-id]').data('page-id'),
        cardID = $this.attr('id');
      if (pageID !== undefined && cardID !== undefined && isLocalStorage()) {
        if (localStorage.getItem(pageID + '-' + cardID + '-minimized') == "true") {
          $this.find('i.sli-arrows-arrow-down-12').addClass('sli-arrows-arrow-up-12').removeClass('sli-arrows-arrow-down-12');
          $this.addClass('minimized');
        }
      }
    });
  },

  cardCollapse: function () {

    $('body').on('click', 'a.minimize', function (event) {
      event.preventDefault();
      var $this = $(this),
        pageID = $('div[data-page-id]').data('page-id'),
        cardID = $this.closest('.card').attr('id'),
        portlet = $this.closest('div[data-portlet]');

      if ($this.closest('.card').hasClass('minimized')) {
        $this.find('i').addClass('sli-arrows-arrow-down-12').removeClass('sli-arrows-arrow-up-12');
        $this.closest('.card').removeClass('minimized');
      } else {
        $this.find('i').addClass('sli-arrows-arrow-up-12').removeClass('sli-arrows-arrow-down-12');
        $this.closest('.card').addClass('minimized');
      }

      if (pageID !== undefined && cardID !== undefined && isLocalStorage()) {
        localStorage.setItem(pageID + '-' + cardID + '-minimized', $this.closest('.card').hasClass('minimized'));
      }

      $(window).resize().trigger('resize');
    })

  },

  // ------------------------------------------------------------------------------------------------
  // Mobile toggle (XS navbar)
  // ------------------------------------------------------------------------------------------------
  mobileToggle: function () {
    $(document).on('click', '[data-mobile]', function (event) {
      event.preventDefault();

      var $this = $(event.target);
      $this.attr('data-mobile') || ($this = $this.closest('[data-mobile]'));

      var $target = $($this.attr('data-target')) || $this;
      $target.toggleClass($this.attr('data-mobile'));

      app.removeClass('left-folded');
    });
  },

  // ------------------------------------------------------------------------------------------------
  // Full screen card
  // ------------------------------------------------------------------------------------------------
  cardFullscreen: function () {

    $('body').on('click', 'a.full-screen', function (event) {
      event.preventDefault();

      var $this = $(this);

      $this.closest('.card').hasClass('fullscreen')
        ? $this.find('i').addClass('sli-resize-move-expand-1').removeClass('sli-resize-move-shrink-1')
        : $this.find('i').addClass('sli-resize-move-shrink-1').removeClass('sli-resize-move-expand-1');

      $this.closest('.card').toggleClass('fullscreen');

      $(window).resize().trigger('resize');
    });
  },


  // ------------------------------------------------------------------------------------------------
  // Scroll height
  // ------------------------------------------------------------------------------------------------
  scrollHeight: function () {
    var scrollHeight;

    scrollHeight = $win.height();

    $('.right-scroll').attr('style', 'height:' + (scrollHeight + 35 - 100) + 'px; overflow: hidden; outline: none;');

    $win.resize(function () {
      scrollHeight = $win.height();
      $('.right-scroll').attr('style', 'height: ' + (scrollHeight + 35 - 100) + 'px; overflow: hidden; outline: none;');
    });
  },


  // ------------------------------------------------------------------------------------------------
  // Showing tooltips
  // ------------------------------------------------------------------------------------------------
  tooltipShow: function () {
    $('.js-tooltip').tooltip({
      container: 'body'
    }).on('click', function (event) {
      event.preventDefault();
    });
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();
  },

  // ------------------------------------------------------------------------------------------------
  // Showing tabs
  // ------------------------------------------------------------------------------------------------
  navTabs: function () {
    $('.nav-tabs a').on('click', function (event) {
      event.preventDefault();
      $(this).tab('show');
    })
  },

  // ------------------------------------------------------------------------------------------------
  // Refresh tabs content
  // ------------------------------------------------------------------------------------------------
  refreshTabs: function () {
    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (event) {
      var target = $(event.target).attr('href');
      // CodeMirror
      $(target + ' [data-codemirror]').each(function (i, el) {
        var name = $(el).data('codemirror');
        refreshCodemirror(name);
      });
    });
  },

  // ------------------------------------------------------------------------------------------------
  // Add dynamic modal
  // ------------------------------------------------------------------------------------------------
  AddModal: function (name, header, data, size, theme, buttons = null) {
    var template;

    template = '<div id="' + name + '" class="modal dynamic fade" tabindex="-1" role="dialog">';
    template += '	<div class="modal-dialog ' + size + '">';
    template += '		<div class="modal-content">';
    if (header) {
      template += '			<div class="modal-header ' + theme + '">';
      template += '				<h4 class="modal-title">' + header + '</h4>';
      template += '			</div>';
    }
    template += '			<div class="modal-body">' + data + '</div>';
    template += '			<div class="modal-footer">';
    template += '			  <button type="button" class="btn btn-primary" data-dismiss="modal">'+buttonClose+'</button>';
    if (buttons) {
      template += buttons;
    }
    template += '		  </div>';
    template += '		</div>';
    template += '	</div>';
    template += '</div>';

    $body.append(template);

    $('#' + name).modal({backdrop: 'static', keyboard: true, show: true});
  },

  // ------------------------------------------------------------------------------------------------
  // Hidden modal events
  // ------------------------------------------------------------------------------------------------
  HiddenModal: function () {
    $(document).on('hidden.bs.modal', '.modal.dynamic', function () {
      setTimeout(function () {
        $('.modal.dynamic').remove();
      }, 500);
    });
  },

  // ------------------------------------------------------------------------------------------------
  // Blur on modal show
  // ------------------------------------------------------------------------------------------------
  HideTooltipOnModal: function () {
    $(document).on('show.bs.modal', '.modal', function () {
      $('[data-toggle="tooltip"], .tooltip').tooltip("hide");
    });
    $(document).on('hidden.bs.modal', '.modal', function () {
      $(':focus').blur();
    });
  }

};

$(document).ready(function () {
  App.initialize();
  $('.page-loader').fadeOut(500);
});