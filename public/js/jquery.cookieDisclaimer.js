//! jquery.cookieDisclaimer.js
//! version : 0.1.0
//! authors : Kleis
//! license : GNU
;(function($) {
    $.cookieDisclaimer = function(options) {
        var defaults = {
            domain: '',
            window: window.document,
            siteName: 'kleis.it',
            customDescription: null,
            description: 'I cookie aiutano %s a fornire servizi di qualit\u00e0. Navigando sul sito accetti il loro utilizzo.',
            readMore: 'Informazioni',
            readMoreUrl: '/privacy',
            accept: 'OK',
            position: 'bottom',
            bgColor: 'white',
            textColor: '#444',
            btnBgColor: 'red',
            btnBgHoverColor: 'orange',
            btnTextColor: 'white',
            fontFamily: 'sans-serif',
            fontSize: '13px',
            shadow: '0px 0px 12px rgba(0,0,0,.4)',
            prefix: 'cookiescript',
            cookieName: 'cookiescriptaccept',
            cookieLife: 30 //days
        }
        var self = this;
        self.params = {};

        var init = function() {
            self.params = $.extend({}, defaults, options);
            InjectCookieScript();
        }

        var InjectCookieScript = function() {
            var element = [
                '<div id="'+self.params.prefix+'_injected">',
                    '<div id="'+self.params.prefix+'_wrapper">',
                        (self.params.customDescription
                            ? self.params.customDescription
                            : printF(self.params.description, self.params.siteName)
                        ),
                        '<a id="'+self.params.prefix+'_readmore">',
                            self.params.readMore,
                        '</a>',
                        '<div id="'+self.params.prefix+'_accept">',
                            self.params.accept,
                        '</div>',
                    '</div>',
                '</div>'
            ];
            $(function(){
                var w = self.params.window;
                $('#'+self.params.prefix+'_injected', w).remove();
                if ('visit' == ReadCookie( self.params.cookieName ))
                    return !1;
                $('body', w).append( element.join('') );

                var
                    $outer      = $('#'+self.params.prefix+'_injected', w),
                    $wrapper    = $('#'+self.params.prefix+'_wrapper', $outer),
                    $a          = $('a', $outer),
                    $accept     = $('#'+self.params.prefix+'_accept', $outer),
                    $readmore   = $('#'+self.params.prefix+'_readmore', $outer);

                $wrapper.css({
                    color: self.params.textColor,
                    fontFamily: self.params.fontFamily,
                    fontSize: "100%",
                    fontWeight: "normal",
                    lineHeight: "1.75",
                    textAlign: "center"
                });
                $a.css({
                    color: self.params.textColor,
                    textDecoration: 'underline',
                });
                $accept.css({
                    backgroundColor: self.params.btnBgColor,
                    border: 0,
                    borderRadius: '1em',
                    color: self.params.btnTextColor,
                    cursor: 'pointer',
                    display: 'inline',
                    fontWeight: 'bold',
                    margin: '0 1em',
                    padding: "0.4em 2em",
                    "-moz-transition": "0.25s",
                    "-webkit-transition": "0.25s",
                    transition: "0.25s",
                    whiteSpace: "nowrap"
                }).hover(function() {
                    $(this).css( 'background-color', self.params.btnBgHoverColor)
                }, function() {
                    $(this).css( 'background-color', self.params.btnBgColor)

                }).click(function() {
                    $outer.fadeOut(200);
                    CreateCookie(self.params.cookieName, 'visit', self.params.cookieLife);
                });
                $readmore.css({
                    color: self.params.textColor,
                    cursor: 'pointer',
                    margin: '0 1em',
                    padding: 0,
                    textDecoration: 'underline',
                    whiteSpace: 'nowrap'
                }).click(function() {
                    window.open( self.params.readMoreUrl, '_self' );
                    return !1
                });
                $outer.css({
                    backgroundColor: self.params.bgColor,
                    "-moz-box-shadow": self.params.shadow,
                    "-webkit-box-shadow": self.params.shadow,
                    boxShadow: self.params.shadow,
                    color: self.params.textColor,
                    display: 'none',
                    fontFamily: self.params.fontFamily,
                    fontSize: self.params.fontSize,
                    fontWeight: 'normal',
                    left: 0,
                    opacity: 1,
                    padding: '0.5% 0',
                    position: 'fixed',
                    right: 0,
                    textAlign: 'left',
                    width: '100%',
                    zIndex: 999999
                });
                if ('top' == self.params.position) {
                    $outer.css('top', 0);
                } else {
                    $outer.css('bottom', 0);
                }
                $outer.fadeIn(1E3);

            });
            return 'visit' == ReadCookie( self.params.cookieName );
        }
        var CreateCookie = function(b, c, a) {
            var d = "",
                e;
            a && (e = new Date, e.setTime(e.getTime() + 864E5 * a), d = "; expires=" + e.toGMTString());
            a = "";
            "" != self.params.domain && (a = "; domain=" + self.params.domain);
            document.cookie = b + "=" + c + d + a + "; path=/"
        }
        var ReadCookie = function(b) {
            b += "=";
            for (var c = document.cookie.split(";"), a, d = 0; d < c.length; d++) {
                for (a = c[d]; " " == a.charAt(0);)
                    a = a.substring(1, a.length);
                if (0 == a.indexOf(b))
                    return a.substring(b.length, a.length)
            }
            return null;
        }
        var printF = function(string, etc){
            var arg = arguments,
                i = 1;
            return string.replace(/%((%)|s)/g, function (m) {
                return m[2] || arg[i++]
            })
        }
        init();
    }
})(jQuery);