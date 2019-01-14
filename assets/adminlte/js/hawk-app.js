(function ($) {
    'use strict';

    /**
     * List of all the available skins
     *
     * @type Array
     */
    var mySkins = [
        'skin-blue',
        'skin-black',
        'skin-red',
        'skin-yellow',
        'skin-purple',
        'skin-green',
        'skin-blue-light',
        'skin-black-light',
        'skin-red-light',
        'skin-yellow-light',
        'skin-purple-light',
        'skin-green-light'
    ]

    window.app = {
        name: 'Biactiva',
        version: '1.3.10',
        setting: {
            fixed: false,
            boxed: false,
            bg: 'skin-blue'
        }
    };

    var setting = 'jqStorage-'+app.name+'-Setting',
        storage = $.localStorage;
    
    if( storage.isEmpty(setting)){
        storage.set(setting, app.setting);
    }else{
        app.setting = storage.get(setting);
    }

    //init
    function setTheme(){
        $.each(mySkins, function (i) {
            $('body').removeClass(mySkins[i])
        })
        $('body').addClass(app.setting.bg)   
    }
    
    // click to switch
    $(document).on('click.setting', '.switcher input', function(e){
        var $this = $(this), $target;
        $target = $this.parent().attr('data-target') ? $this.parent().attr('data-target') : $this.parent().parent().attr('data-target');
        app.setting[$target] = $this.is(':checkbox') ? $this.prop('checked') : $(this).val();
        storage.set(setting, app.setting);
        setTheme(app.setting);
    });

    setTheme();
})(jQuery);