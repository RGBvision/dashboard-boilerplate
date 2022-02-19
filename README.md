# Dashboard boilerplate

#### Multipurpose Admin / Dashboard template 

## Table of contents

- [Features](#features)
- [Requirements](#requirements)
- [Project structure](#project-structure)
- [Demo](#demo)
- [Credits](#credits)

## Features

### Back-end
- Easy to set up and customize
- Easy to extend
- Ready to use as API
- Automatic timezone handling
- Multilanguage support

### Front-end
- Bootstrap 5.1.1
- jQuery 3.6.0
- Light / Dark theme
- Fully Responsive layout
- And many more…

## Requirements

- PHP 7.4+
- MySQL 5.7.8+ / MariaDB 10.2.7+

## Project structure

    .
    ├── /app                    # Application files
        ├── /api                # API classes
        ├── /classes            # Common application classes
        ├── /modules            # Application modules
        └── /templates          # Template files (each subdirectory for system template base)
    ├── /assets                 # Front-end files
        ├── /css                # Application stylesheets (each subdirectory for system template base)
        ├── /i18n               # Front-end translations
        ├── /images             # Images
        ├── /js                 # Application JavaScript files
        └── /vendors            # 3rd party JS plugins
    ├── /configs                # System configuration files (autogenerated)
        ├── db.config.php       # DB connection config
        ├── environment.php     # Environment confil
        └── routes.php          # Custom routes config
    ├── /libraries              # 3rd party PHP plugins
    ├── /system                 # System files
        ├── /common             # Common classes
        ├── /core               # Core classes
        ├── /drivers            # Drivers (DB, Session, etc.)
        ├── /functions          # Common functios
        ├── /i18n               # Back-end translations
        ├── /loader             # System loader
        ├── config.php          # Default system configuration 
        ├── Core.php            # Core main class 
        ├── errors.php          # Runtime errors handler
        └── init.php            # System initialization 
    ├── /tmp                    # Cache and temporary files
    ├── /uploads                # Users files
    └── index.php               # Application launcher

### Modules

    .
    └── /controller
        └── Controller.php     # Controller
    ├── /i18n                  # Translations
    └── /model
        └── Model.php          # Model
    ├── /view                  # View (templates)
    └── Module.php             # Module main class

## Demo

[https://dashboard.rgbvision.net](https://dashboard.rgbvision.net)

test@rgbvision.net / demo12345

## Credits

- [Ace Editor](https://ace.c9.io/)
- [Animate.css](https://daneden.github.io/animate.css/)
- [Apex Charts](https://apexcharts.com/)
- [Bootstrap](https://getbootstrap.com/)
- [Bootstrap Colorpicker](https://itsjavi.com/bootstrap-colorpicker/)
- [Bootstrap-datepicker](https://bootstrap-datepicker.readthedocs.io/)
- [Bootstrap MaxLength](http://mimo84.github.io/bootstrap-maxlength/)
- [Clipboard.js](https://clipboardjs.com/)
- [Cropper.js](https://github.com/fengyuanchen/cropperjs)
- [DataTables](https://datatables.net/)
- [Dropzone](https://www.dropzonejs.com/)
- [EasyDB](https://github.com/paragonie/easydb)
- [Flag Icons](https://flagicons.lipis.dev/)
- [FullCalendar](https://fullcalendar.io/)
- [jQuery](https://jquery.com/)
- [jQuery.inputmask](https://robinherbots.github.io/Inputmask/)
- [jQuery Mouse Wheel Plugin](https://github.com/jquery/jquery-mousewheel)
- [jQuery Steps](http://www.jquery-steps.com/GettingStarted)
- [jQuery Tags Input](https://github.com/xoxco/jQuery-Tags-Input)
- [jQuery Validation](https://jqueryvalidation.org/)
- [Libphonenumber-js](https://catamphetamine.github.io/libphonenumber-js/)
- [Libphonenumber for PHP](https://github.com/giggsey/libphonenumber-for-php)
- [MDI Icons](https://materialdesignicons.com/)
- [Moment.js](https://momentjs.com/)
- [Owl Carousel 2](https://owlcarousel2.github.io/OwlCarousel2/)
- [Perfect-scrollbar](https://github.com/mdbootstrap/perfect-scrollbar)
- [Popper](https://popper.js.org/)
- [ProgressBar.js](https://kimmobrunfeldt.github.io/progressbar.js/)
- [Select2](https://select2.org/)
- [SMARTY](https://www.smarty.net/)
- [Sweetalert2](https://sweetalert2.github.io/)
- [Swift Mailer](https://github.com/swiftmailer/swiftmailer)
- [Tempus Dominus](https://getdatepicker.com/5-4/)
- [TinyMCE](https://www.tiny.cloud/)
- [Typeahead](http://twitter.github.io/typeahead.js/)
- [WideImage](https://github.com/smottt/WideImage)

## More info: SOON™
