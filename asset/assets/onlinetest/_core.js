function _typeof(e) {
    return (_typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
        return typeof e
    } : function(e) {
        return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
    })(e)
}! function() {
    for (var r = 0, e = ["ms", "moz", "webkit", "o"], t = 0; t < e.length && !window.requestAnimationFrame; ++t) window.requestAnimationFrame = window[e[t] + "RequestAnimationFrame"], window.cancelAnimationFrame = window[e[t] + "CancelAnimationFrame"] || window[e[t] + "CancelRequestAnimationFrame"];
    window.requestAnimationFrame || (window.requestAnimationFrame = function(e, t) {
        var n = (new Date).getTime(),
            o = Math.max(0, 16 - (n - r)),
            i = window.setTimeout(function() {
                e(n + o)
            }, o);
        return r = n + o, i
    }), window.cancelAnimationFrame || (window.cancelAnimationFrame = function(e) {
        clearTimeout(e)
    }), Array.prototype.includes || (Array.prototype.includes = function(e) {
        "use strict";
        var t = Object(this),
            n = parseInt(t.length, 10) || 0;
        if (0 === n) return !1;
        var o, i = parseInt(arguments[1], 10) || 0;
        for (0 <= i ? o = i : (o = n + i) < 0 && (o = 0); o < n;) {
            var r = t[o];
            if (e === r || e != e && r != r) return !0;
            o++
        }
        return !1
    }), Object.values || Object.defineProperty(Object, "values", {
        enumerable: !1,
        configurable: !0,
        writable: !0,
        value: function(t) {
            return "string" == typeof t ? t.split("") : Array.isArray(t) ? t : "object" !== _typeof(t) ? [] : Object.keys(t).map(function(e) {
                return t[e]
            })
        }
    })
}(),
function(t) {
    t.svg4everybody = function() {
        function p(e, t) {
            if (t) {
                var n = document.createDocumentFragment(),
                    o = t.getAttribute("viewBox");
                e.setAttribute("viewBox", o);
                for (var i = t.cloneNode(!0); i.childNodes.length;) n.appendChild(i.firstChild);
                e.appendChild(n)
            }
        }

        function v(o) {
            o.onreadystatechange = function() {
                if (4 === o.readyState) {
                    var n = o._cachedDocument;
                    n || ((n = o._cachedDocument = document.implementation.createHTMLDocument("")).body.innerHTML = o.responseText, o._cachedTarget = {}), o._embeds.splice(0).map(function(e) {
                        var t = o._cachedTarget[e.id];
                        t || (t = o._cachedTarget[e.id] = n.getElementById(e.id)), p(e.svg, t)
                    })
                }
            }, o.onreadystatechange()
        }
        return function(e) {
            var d, s = Object(e);
            d = "polyfill" in s ? s.polyfill : /\bTrident\/[5-7]\b|\bMSIE (?:9|10)\.0\b/.test(navigator.userAgent) || /\bEdge\/(\d+)\.(\d+)\b/.test(navigator.userAgent) || (navigator.userAgent.match(/\bAp{2}leWebKit\/(\d+)\b/) || [])[1] < 537;
            var m = {},
                l = t.requestAnimationFrame || setTimeout,
                f = document.getElementsByTagName("use");
            d && function e() {
                for (var t = 0; t < f.length;) {
                    var n = f[t],
                        o = n.parentNode;
                    if (o && /svg/i.test(o.nodeName)) {
                        var i = n.getAttribute("xlink:href");
                        if (d && (!s.validate || s.validate(i, o, n))) {
                            o.removeChild(n);
                            var r = i.split("#"),
                                a = r.shift(),
                                c = r.join("#");
                            if (a.length) {
                                var u = m[a];
                                u || ((u = m[a] = new XMLHttpRequest).open("GET", a), u.send(), u._embeds = []), u._embeds.push({
                                    svg: o,
                                    id: c
                                }), v(u)
                            } else p(o, document.getElementById(c))
                        }
                    } else ++t
                }
                l(e, 67)
            }()
        }
    }()
}(window),
function(e) {
    function t(e) {
        e.preventDefault()
    }
    e.videoImgFallback = function(e) {
        console.warn("video load error, gif fallback used");
        var t = e.querySelector("img");
        t && e.parentNode.replaceChild(t, e)
    }, e.getCookie = function(e) {
        var t = document.cookie.match(new RegExp("(?:^|; )" + e.replace(/([$()*+./?[\\\]^{|}])/g, "\\$1") + "=([^;]*)"));
        return t ? decodeURIComponent(t[1]) : void 0
    }, e.setCookie = function(e, t, n) {
        void 0 === n && (n = {});
        var o = n.expires;
        if ("number" == typeof o && o) {
            var i = new Date;
            i.setTime(i.getTime() + 1e3 * o), o = n.expires = i
        }
        o && o.toUTCString && (n.expires = o.toUTCString());
        var r = e + "=" + (t = encodeURIComponent(t));
        for (var a in n) {
            r += "; " + a;
            var c = n[a];
            !0 !== c && (r += "=" + c)
        }
        document.cookie = r
    }, e.deleteCookie = function(e) {
        setCookie(e, "", {
            expires: -1
        })
    }, e.getJsonFromUrl = function(e) {
        var t;
        if (e) {
            var n = location.href.indexOf("?");
            if (-1 === n) return [];
            t = location.href.substr(n + 1)
        } else t = location.search.substr(1);
        var c = {};
        return t.split("&").forEach(function(e) {
            if (e) {
                var t = (e = e.split("+").join(" ")).indexOf("="),
                    n = -1 < t ? e.substr(0, t) : e,
                    o = -1 < t ? decodeURIComponent(e.substr(t + 1)) : "",
                    i = n.indexOf("[");
                if (-1 === i) c[decodeURIComponent(n)] = o;
                else {
                    var r = n.indexOf("]", i),
                        a = decodeURIComponent(n.substring(i + 1, r));
                    n = decodeURIComponent(n.substring(0, i)), c[n] || (c[n] = []), a ? c[n][a] = o : c[n].push(o)
                }
            }
        }), c
    }, e.chunkSplit = function(e, t, n) {
        return void 0 === t && (t = 3), void 0 === n && (n = " "), (e += "").split("").reverse().join("").match(new RegExp(".{0," + t + "}", "g")).join(n).split("").reverse().join("").trim()
    }, e.preventBodyScrolling = function(e) {
        void 0 === e && (e = !1), document.body[!0 === e ? "addEventListener" : "removeEventListener"]("touchmove", t, !1)
    }
}(window);

function _typeof(e) {
    return (_typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
        return typeof e
    } : function(e) {
        return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
    })(e)
}
var is_dev = !0;
"undefined" == typeof flexbe_cli && (flexbe_cli = {}), spaced_cli = flexbe_cli, flexbe_cli.run = {
    _init: function() {
        if (this.deviceInfo(), !flexbe_cli.p_id) {
            var e = {
                pId: flexbe_cli.p_id || "not set",
                groupId: flexbe_cli.group_id || "not set",
                sId: flexbe_cli.s_id || "not set",
                themeId: flexbe_cli.theme_id || "not set",
                location: location.href,
                userAgent: navigator && navigator.userAgent || "unknown",
                isBot: this.is_bot,
                scriptInitContent: document.getElementById("flexbe-vars").innerHTML
            };
            return this.is_bot || flexbe_cli.message({
                title: "Page loaded with empty pId",
                text: "Collected data: ```" + JSON.stringify(e, null, 4) + "```"
            }), !1
        }
        this.init(), svg4everybody({
            polyfill: !flexbe_cli.is_admin && (flexbe_cli.run.is_ie || flexbe_cli.run.is_EDGE)
        })
    },
    deviceInfo: function() {
        var e = navigator.userAgent.toLowerCase();
        this.is_screen_mobile = window.innerWidth < 570, this.is_screen_tablet_s = 570 <= window.innerWidth && window.innerWidth < 768, this.is_screen_tablet = 768 <= window.innerWidth && window.innerWidth <= 1024, this.is_screen_small_pc = 1024 < window.innerWidth && window.innerWidth < 1200, this.is_screen_pc = 1200 <= window.innerWidth, this.is_bot = /bot|aolbuild|bingpreview|msnbot|baidu|duckduckgo|mediapartners-google|teoma|slurp/gi.test(e), this.is_mobile = -1 !== e.indexOf("mobile") || this.is_screen_mobile || this.is_screen_tablet_s, this.is_desktop = !this.is_mobile, this.is_touch = "ontouchstart" in window, this.is_webkit = -1 !== e.indexOf("webkit"), this.is_safari = -1 !== e.indexOf("safari") && e.match(/version\/(\d+)/), this.is_firefox = -1 !== e.indexOf("firefox"), this.is_mobile_ie = -1 !== e.indexOf("iemobile"), this.is_ie = -1 !== e.indexOf("trident") || -1 !== e.indexOf("msie"), this.is_EDGE = -1 !== e.indexOf("edge"), this.is_OSX = /iPad|iPhone|iPod|Macintosh/gi.test(e), this.is_android = -1 !== e.indexOf("android"), this.is_ios = this.is_touch && this.is_mobile && this.is_OSX, this.is_screenshoter = -1 < e.indexOf("slimerjs") || -1 < e.indexOf("phantomjs"), this.is_screen_mobile ? this.device_type = "mobile" : this.is_screen_tablet_s || this.is_screen_tablet ? this.device_type = "tablet" : this.device_type = "desktop"
    },
    init: function() {}
}, flexbe_cli.message = function(e) {
    var i = e.channel,
        t = e.title,
        n = e.text,
        s = e.msg;
    1 === arguments.length && "object" !== _typeof(e) && (s = e), $.get("/mod/log", {
        channel: i,
        title: t,
        text: n,
        msg: s
    })
}, flexbe_cli.run.is_screenshoter || (window.onpopstate = function() {
    flexbe_cli.modal.popstate()
}), document.addEventListener("DOMContentLoaded", function() {
    ! function e() {
        if ("undefined" != typeof jQuery) {
            flexbe_cli.events._init(), flexbe_cli.run._init();
            try {
                document.body.classList.add("ready")
            } catch (e) {
                document.body.className += " ready"
            }
            flexbe_cli.isInited = !0
        } else setTimeout(e, 10)
    }()
}), window.addEventListener("load", function() {
    flexbe_cli.isLoaded = !0
});

function _typeof(t) {
    return (_typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
        return typeof t
    } : function(t) {
        return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
    })(t)
}
flexbe_cli.events = {
    _init: function() {
        $.observable = function(n) {
            var i = {
                _JQInit: function() {
                    this._JQ = $(this)
                },
                emit: function(t, n) {
                    return !this._JQ && this._JQInit(), this._JQ.trigger(t, n), this
                },
                once: function(t, n) {
                    return !this._JQ && this._JQInit(), this._JQ.one(t, n), this
                },
                on: function(t, n) {
                    return !this._JQ && this._JQInit(), this._JQ.bind(t, n), this
                },
                off: function(t, n) {
                    return !this._JQ && this._JQInit(), this._JQ.unbind(t, n), this
                }
            };
            return "object" !== _typeof(n) && (n = this), Object.keys(i).forEach(function(t) {
                n[t] = i[t].bind(n)
            }), i
        }, $.observable(this)
    }
};
flexbe_cli.lib = {
    init: function() {
        var e = this;
        $(window).one("load.flexbe_lib", function() {
            e.lg()
        })
    },
    lg: function() {
        var e = this;
        if (!flexbe_cli.is_admin && 99 != flexbe_cli.theme_id) {
            var a = ".img_popup, [data-lg]:not(.swiper-slide-duplicate [data-lg])",
                t = $(".lg-init, .component-media").filter(function() {
                    return !$(e).parents(".lg-init, .component-media")[0]
                });
            flexbe_cli.run.is_screen_pc && t.hasClass("lg-prevent-pc") || flexbe_cli.run.is_mobile && t.hasClass("lg-prevent-mobile") || t.find(a).length && flexbe_cli.require(["/_s/lib/jquery/lightGallery/css/lightgallery.css", "/_s/lib/jquery/lightGallery/js/lg-spaced-bundle.min.js"], function() {
                var i = !1;

                function n() {
                    history.state && history.state.lg && history.replaceState(null, null, "#")
                }
                t.each(function(e, t) {
                    var l = $(t);
                    l.lightGallery({
                        selector: a,
                        counter: !1,
                        download: !1,
                        slideEndAnimation: !1,
                        getCaptionFromTitleOrAlt: !0,
                        closable: !0,
                        loop: "1" === l.find(".component-slider").attr("data-loop"),
                        easing: "ease-out",
                        hideBarsDelay: 6e3,
                        zoomIcons: !1,
                        actualSize: !1,
                        enableSlide: !$(t).attr("data-lg-single")
                    }), l.on("onBeforeOpen.lg", function() {
                        i = !0, n(), history.state && history.state.lg || history.pushState({
                            lg: !0
                        }, null, "#image")
                    }), l.on("onBeforeClose.lg", function() {
                        history.state && history.state.lg && (i = !1, history.back()), n()
                    })
                }), n(), window.addEventListener("popstate", function() {
                    !i || history.state && history.state.lg || t.each(function(e, t) {
                        var l = $(t),
                            i = l.data("lightGallery");
                        i && i.destroy()
                    })
                })
            })
        }
    }
};
flexbe_cli.require = function(r, t, i, e) {
    void 0 === r && (r = ""), void 0 === t && (t = function() {}), void 0 === i && (i = 15e3), void 0 === e && (e = !1), r && 0 !== r.length || t(!1);
    var n = flexbe_cli.require.loaded;
    if (!e) {
        "string" == typeof r && (r = [r]);
        var o = 0,
            d = function(e) {
                var i = r.every(function(e) {
                    return "boolean" == typeof n[e]
                });
                o += 1, i && o === r.length && t(e)
            };
        return Array.isArray(r) && r.forEach(function(e) {
            flexbe_cli.require(e, d, i, !0)
        }), !1
    }
    if (!0 === n[r]) t(!0);
    else if (Array.isArray(n[r])) n[r].push(t);
    else {
        var l, c = !1,
            a = function(i) {
                if (void 0 === i && (i = !0), !c) {
                    c = !0, clearTimeout(l);
                    var e = n[r];
                    n[r] = i, e.forEach(function(e) {
                        "function" == typeof e && e(i)
                    })
                }
            };
        if (n[r] = [t], /\.css$/.test(r)) {
            var u = document.createElement("link");
            u.onerror = a.bind(this, !1), u.onload = a.bind(this, !0), l = setTimeout(a.bind(this, "timeout"), i), u.rel = "stylesheet", u.href = r, document.body.appendChild(u)
        } else {
            var f = document.createElement("script");
			var newr= r.replace('_s/lib/anime/','asset/assets/onlinetest/');
            f.onload = a.bind(this, !0), f.onerror = a.bind(this, !1), l = setTimeout(a.bind(this, "timeout"), i), f.async = !0, f.src = newr, document.body.appendChild(f)
        }
    }
}, flexbe_cli.require.loaded = {};
flexbe_cli.lang = {
    basic: "en",
    current: window.lang && window.lang.current || "ru",
    currency: window.lang && window.lang.currency || "₽",
    ru: {
        menu: "Меню",
        form: {
            personal_data_text: '<p>Нажимая на кнопку, вы даете согласие на обработку <a href="%s" target="_blank">персональных данных</a></p>',
            required: "Поле должно быть заполнено",
            email: "Некорректный адрес электронной почты",
            digits: "Поле должно содержать только цифры",
            minlength: "Минимальная длина - 5 цифр",
            selectFiles: "Выберите файлы",
            remove: "Удалить"
        },
        quiz: {
            step: "Шаг",
            cancel: "Отмена",
            prev: "Назад",
            next: "Далее",
            pressEnter: "или нажмите Enter",
            from: "из",
            skip: "Пропустить шаг"
        },
        timer: {
            dd: "Дней",
            hh: "Часов",
            mm: "Минут",
            ss: "Секунд"
        },
        cart: {
            empty: "В корзине ничего нет"
        }
    },
    en: {
        form: {
            personal_data_text: "",
            required: "This field is required",
            email: "Please enter a valid email address",
            digits: "Please enter only digits",
            minlength: "Please enter at least 5 digits",
            selectFiles: "Select files",
            remove: "Remove"
        },
        quiz: {
            step: "Step",
            cancel: "Cancel",
            prev: "Back",
            next: "Next",
            pressEnter: "or press Enter",
            from: "from",
            skip: "Skip"
        },
        timer: {
            dd: "Days",
            hh: "Hours",
            mm: "Minutes",
            ss: "Seconds"
        },
        menu: "Menu",
        cart: {
            empty: "Cart is empty"
        }
    },
    de: {
        form: {
            required: "Dieses Feld ist ein Pflichtfeld",
            email: "Geben Sie bitte eine gültige E-Mail Adresse ein",
            digits: "Geben Sie bitte nur Ziffern ein",
            minlength: "Geben Sie bitte mindestens 5 Ziffern ein",
            selectFiles: "Wählen Sie die Dateien",
            remove: "Löschen"
        },
        quiz: {
            step: "Schritt",
            cancel: "Abbrechen",
            prev: "Zurück",
            next: "Weiter",
            pressEnter: "of druk op Enter",
            from: "aus",
            skip: "Überspringen"
        },
        timer: {
            dd: "Days",
            hh: "Stunden",
            mm: "Minutes",
            ss: "Sekunden"
        },
        menu: "Menü"
    },
    fr: {
        form: {
            required: "Ce champ est obligatoire",
            email: "Veuillez fournir une adresse électronique valide",
            digits: "Veuillez fournir seulement des chiffres",
            minlength: "Veuillez fournir au moins 5 chiffres",
            selectFiles: "Sélectionnez les fichiers",
            remove: "Effacer"
        },
        quiz: {
            step: "Étape",
            cancel: "Annuler",
            prev: "Retour",
            next: "Suivant",
            pressEnter: "ou appuyez sur Entrée",
            from: "de",
            skip: "sauter"
        },
        timer: {
            dd: "Jours",
            hh: "Heures",
            mm: "Minutes",
            ss: "Secondes"
        },
        menu: "Menu"
    },
    es: {
        form: {
            required: "Este campo es obligatorio",
            email: "Escribe una dirección de correo válida",
            digits: "Escribe sólo dígitos",
            minlength: "Por favor, no escribas menos de 5 dígitos",
            selectFiles: "Seleccione los archivos",
            remove: "Borrar"
        },
        timer: {
            dd: "Días",
            hh: "Horas",
            mm: "Minutos",
            ss: "Segundos"
        },
        menu: "Menú"
    },
    it: {
        form: {
            required: "Campo obbligatorio",
            email: "Inserisci un indirizzo email valido",
            digits: "Inserisci solo numeri",
            minlength: "Inserisci almeno 5 numeri",
            selectFiles: "Selezionare i file",
            remove: "Cancellare"
        },
        timer: {
            dd: "Giorni",
            hh: "Ore",
            mm: "Minuti",
            ss: "Secondi"
        },
        menu: "Menu"
    },
    pl: {
        form: {
            required: "To pole jest wymagane",
            email: "Proszę o podanie prawidłowego adresu email",
            digits: "Proszę o podanie samych cyfr",
            minlength: "Proszę o podanie przynajmniej 5 cyfr",
            selectFiles: "Wybierz pliki",
            remove: "Usunąć"
        },
        timer: {
            dd: "Dni",
            hh: "Godziny",
            mm: "Minuty",
            ss: "Sekundy"
        },
        menu: "Menu"
    },
    ge: {
        form: {
            required: "ეს ველი სავალდებულოა",
            email: "გთხოვთ შეიყვანოთ სწორი ფორმატით",
            digits: "დაშვებულია მხოლოდ ციფრები",
            minlength: "შეიყვანეთ მინიმუმ 5 ციფრი",
            selectFiles: "აირჩიეთ ფაილი",
            remove: "წაშლა"
        },
        timer: {
            dd: "დღეები",
            hh: "საათი",
            "მმ": "ოქმი",
            ss: "წამი"
        },
        menu: "მენიუ"
    },
    ua: {
        form: {
            personal_data_text: '<p>Натискаючи на кнопку, ви даєте згоду на обробку <a href="%s" target="_blank">персональних даних</a></p>',
            required: "Поле має бути заповнено",
            email: "Некоректна адреса електронної пошти",
            digits: "Поле повинно містити тільки цифри",
            minlength: "Мінімальна довжина - 5 цифр",
            selectFiles: "Виберіть файли",
            remove: "Видалити"
        },
        quiz: {
            step: "Крок",
            cancel: "Скасувати",
            prev: "Назад",
            next: "Далі",
            pressEnter: "або натисніть Enter",
            from: "з",
            skip: "Пропустити крок"
        },
        timer: {
            dd: "Дні",
            hh: "Години",
            mm: "Хвилини",
            ss: "Секунди"
        },
        menu: "Меню"
    },
    by: {
        form: {
            required: "Поле павінна быць запоўнена",
            email: "Некарэктны адрас электроннай пошты",
            digits: "Поле павінна ўтрымліваць толькі лічбы",
            minlength: "Мінімальны даўжыня - 5 лічбаў",
            selectFiles: "Выберыце файлы",
            remove: "Выдаліць"
        },
        quiz: {
            step: "Крок",
            cancel: "Адмена",
            prev: "Назад",
            next: "Вперед",
            pressEnter: "або націсніце клавішу Enter",
            from: "ад",
            skip: "Прапусціць"
        },
        timer: {
            dd: "Дні",
            hh: "Гадзіннік",
            mm: "Хвіліны",
            ss: "Секунды"
        },
        menu: "Меню"
    },
    kz: {
        form: {
            required: "Міндетті өріс",
            email: "Жарамсыз электрондық пошта мекенжайы",
            digits: "Далалық тек сандардан тұруы тиіс",
            minlength: "Ең аз ұзындығы - 5 сандар",
            selectFiles: "Файлдарды таңдаңыз",
            remove: "Жою"
        },
        timer: {
            dd: "Күндері",
            hh: "Сағаттар",
            mm: "Минут",
            cc: "Секунд"
        },
        menu: "Мәзір"
    },
    get: function(e, i) {
        if (e) {
            if (i && this[i] || (i = this.current || this.basic), "currency" === e) return this.currency;
            var r = e.split(".").reduce(function(e, i) {
                return e && e[i]
            }, this[i]);
            return r || i === this.basic ? r : this.get(e, this.basic)
        }
    },
    init: function(e) {
        var t = this;
        void 0 === e && (e = "body"), $("[data-lang]", e).each(function(e, i) {
            var r = $(i).attr("data-lang"),
                n = $(i).attr("data-lang-content"),
                s = String(t.get(r));
            $(i).html(s.replace(/%s/, n))
        })
    }
};
flexbe_cli.resize = {
    old_h: window.innerHeight,
    width: window.innerWidth,
    height: window.innerHeight,
    docHeight: document.body.scrollHeight,
    init: function() {
        this.simulateWindowResized(), this.simulateDocumentResize()
    },
    simulateWindowResized: function() {
        var e, i = this;
        $(window).on("resize orientationchange", function() {
            i.width = window.innerWidth, i.height = window.innerHeight, clearTimeout(e), e = setTimeout(function() {
                flexbe_cli.run.deviceInfo(), $(window).trigger("resized")
            }, 80)
        })
    },
    simulateDocumentResize: function() {
        var t = this,
            o = flexbe_cli.is_admin ? 350 : 150,
            d = (new Date).getTime();
        requestAnimationFrame(function e() {
            var i, n = (new Date).getTime();
            o <= n - d && (d = (new Date).getTime(), flexbe_cli.scroll.inScroll || (i = document.body.scrollHeight) != t.documentHeight && (t.documentHeight = i, $(window).trigger("documentresize"))), requestAnimationFrame(e)
        })
    }
};
flexbe_cli.scroll = {
    inScroll: !1,
    latest: window.pageYOffset,
    init: function() {
        var t = this;
        this.latest = window.pageYOffset, $(window).on("scroll.coreScroll", function() {
            t.latest = window.pageYOffset, t.inScroll = !0
        }), $(window).on("scrollend.coreScroll", function() {
            t.inScroll = !1
        }), this.scrollImprovement.init()
    },
    scrollImprovement: {
        scrollTimer: 0,
        pointerState: !1,
        init: function() {
            this.stopScrollEvent(), this.pointerEvents()
        },
        stopScrollEvent: function() {
            var e = this,
                o = flexbe_cli.scroll.latest;
            $(window).on("scroll.scrollendEvent", function() {
                var t = o > flexbe_cli.scroll.latest ? "up" : "down";
                clearTimeout(e.scrollTimer), e.scrollTimer = setTimeout(function() {
                    o = flexbe_cli.scroll.latest, $(window).trigger("scrollend", {
                        direction: t
                    })
                }, 200)
            })
        },
        pointerEvents: function() {
            var t = this;
            flexbe_cli.is_admin || ($(window).on("scroll.pointerEventsDisable", function() {
                t.pointerState || (t.pointerState = !0, document.body.classList.add("disable-pointer-events"))
            }), $(window).on("scrollend.pointerEventsDisable", function() {
                t.pointerState && (t.pointerState = !1, document.body.classList.remove("disable-pointer-events"))
            }))
        }
    }
};

function _typeof(t) {
    return (_typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
        return typeof t
    } : function(t) {
        return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
    })(t)
}
var EntityCore = function() {
    function t(t, i) {
        var n = this;
        void 0 === i && (i = {});
        var e = $(t),
            s = e.attr("data-is"),
            o = e.attr("data-id"),
            r = e.attr("data-mod-id") || 0,
            a = "zone" === s ? "e" : s[0],
            c = e.attr("data-" + a + "-id"),
            h = e.data(a + "Type") || [],
            f = e.data("components") || !0;
        this.require = [], this.$area = e, this.area = e[0], this.$root = e, this.root = e[0], this.is = s, this.id = o, this.template_id = c, this.mod_id = r, this.type = h, this.components = f, this.isVisible = !1, this.isBeside = !1, this.isView = !1, this.isFocused = !1, Object.keys(i).forEach(function(t) {
            var e = i[t];
            "object" === _typeof(e) ? n[t] = $.extend(!0, Array.isArray(e) ? [] : {}, e) : n[t] = e
        })
    }
    var e = t.prototype;
    return e.onInit = function() {}, e.onUpdate = function() {}, e.onLoad = function() {}, e.onReady = function() {}, e.onBeside = function() {}, e.onFocus = function() {}, e.onScreen = function() {}, e.onView = function() {}, e.onMsg = function() {}, e.onResize = function() {}, e._onResize = function() {}, e.getNested = function() {
        if (4 !== flexbe_cli.theme_id) return [];
        if (this._nestedElementsCache) return this._nestedElementsCache;
        var t = $(".content-zone, .element-item", this.area).toArray().map(function(t) {
            return flexbe_cli.element.bind(t).core || !1
        }).filter(function(t) {
            return t
        });
        return flexbe_cli.is_admin || (this._nestedElementsCache = t), t
    }, e.init = function(t) {
        var e = this,
            i = this.inited ? "update" : "init";
        "init" === i ? this._onInit(t) : "update" === i && this._onUpdate(t), ("init" === i || t.templateRendered) && flexbe_cli.require(this.require, function() {
            e._onLoad(t)
        })
    }, e._onLoad = function(t) {
        this.loaded = !0, this.onLoad(t), this.onReady(t)
    }, e._onInit = function(t) {
        this.inited = !0, this.onInit(t), flexbe_cli.events.emit("entity_event", {
            type: "inited",
            core: this,
            params: t
        })
    }, e._onUpdate = function(t) {
        this.updated = !0, this.onUpdate(t), flexbe_cli.events.emit("entity_event", {
            type: "updated",
            core: this,
            params: t
        })
    }, e._onMsg = function(t, e) {
        this.onMsg(t, e)
    }, e._onFocus = function(t) {
        var e = t.state;
        if (this.isFocused !== e) {
            this.isFocused = e, this.onFocus(e), flexbe_cli.events.emit("entity_event", {
                type: "focus",
                core: this,
                state: e
            }), this.$area.trigger("onFocus", {
                state: e
            })
        }
    }, e._onScreen = function(t) {
        var e = t.state;
        if (this.isVisible !== e) {
            this.isVisible = e, this.onScreen(e), flexbe_cli.events.emit("entity_event", {
                type: "screen",
                core: this,
                state: e
            }), this.$area.trigger("onScreen", {
                state: e
            })
        }
    }, e._onView = function(t) {
        var e = t.state;
        if (this.isView !== e) {
            this.isView = e, this.onView(e), flexbe_cli.events.emit("entity_event", {
                type: "view",
                core: this,
                state: e
            }), this.$area.trigger("onView", {
                state: e
            })
        }
    }, e._onBeside = function(t) {
        var e = t.state;
        if (this.isBeside !== e) {
            this.isBeside = e, this.onBeside(e), flexbe_cli.events.emit("entity_event", {
                type: "beside",
                core: this,
                state: e
            }), this.$area.trigger("onBeside", {
                state: e
            })
        }
    }, t
}();

function _inheritsLoose(e, t) {
    e.prototype = Object.create(t.prototype), (e.prototype.constructor = e).__proto__ = t
}
var BlockCore = function(n) {
    function e(e, t) {
        var i;
        return (i = n.call(this, e, t) || this).is = "block", i.$block = i.$area, i
    }
    _inheritsLoose(e, n);
    var t = e.prototype;
    return t._onInit = function(e) {
        flexbe_cli.block.fixCoverHeight(this.$area), this._tween(), n.prototype._onInit.call(this, e)
    }, t._onUpdate = function(e) {
        e.templateRendered && flexbe_cli.block.fixCoverHeight(this.$area), (e.templateRendered || e.styleRendered) && this._tween(), n.prototype._onUpdate.call(this, e)
    }, t._onLoad = function(e) {
        n.prototype._onLoad.call(this, e), this._tween({
            force: !0
        })
    }, t._tween = function(e) {
        var t = (void 0 === e ? {} : e).force,
            i = void 0 !== t && t,
            l = this,
            n = this.area.offsetWidth,
            c = this.area.offsetHeight,
            o = this.area.offsetTop;
        this.tween || (this.tween = {}), this.tween.width = n, this.tween.height = c, this.tween.start = o, this.tween.end = o + c, i && (this.isVisible = null, this.isBeside = null, this.isView = null, this.isFocused = null);
        var s = this.$area.find(".container")[0];

        function r() {
            if (!l.inited) return !1;
            var e, t, i, n = Math.max(flexbe_cli.resize.height - c, 0),
                o = 1 / l.tween.height * (window.pageYOffset - l.tween.start),
                s = (e = window.pageYOffset + flexbe_cli.resize.height / 2, t = l.tween.start + l.tween.height / 2, (e - t + l.tween.height / 2) / l.tween.height),
                r = (i = l.tween.start - flexbe_cli.resize.height, 1 / (l.tween.end - i) * (window.pageYOffset - i));
            l.tween.fix = n, l.tween.position = o, l.tween.positionAbs = r;
            var h = 0 < (l.tween.positionCenter = s) && s < 1,
                w = function() {
                    if (!l.tween.width) return !1;
                    var e = flexbe_cli.resize.height >= l.tween.height ? l.tween.height / 4 : flexbe_cli.resize.height / 3;
                    return window.pageYOffset + flexbe_cli.resize.height > l.tween.start + e && window.pageYOffset < l.tween.end - e
                }(),
                f = function() {
                    if (!l.tween.width) return !1;
                    return 0 <= l.tween.positionAbs && l.tween.positionAbs <= 1
                }(),
                a = function() {
                    if (!l.tween.width) return !1;
                    return -.2 <= l.tween.positionAbs && l.tween.positionAbs <= 1.2
                }();
            l.tween.focus = h, l.tween.view = w, l.tween.onscreen = f, l.tween.beside = a, l._onBeside({
                state: a
            }), l._onScreen({
                state: f
            }), l._onView({
                state: w
            }), l._onFocus({
                state: h
            })
        }
        this.tween.color = s && getComputedStyle(s).color || "#fff", r(), $(window).off("scroll.tween_" + this.id).on("scroll.tween_" + this.id, $.throttle(r, 50))
    }, e
}(EntityCore);

function _inheritsLoose(t, e) {
    t.prototype = Object.create(e.prototype), (t.prototype.constructor = t).__proto__ = e
}
var ModalCore = function(s) {
    function t(t, e) {
        var o;
        return (o = s.call(this, t, e) || this).is = "modal", o.isOpen = !1, o.$modal = o.$area, o.$list = flexbe_cli.modal.$list, o
    }
    _inheritsLoose(t, s);
    var e = t.prototype;
    return e.open = function(t, e) {
        var o = this;
        void 0 === e && (e = {}), this.isOpen = !0, this.lastOptions = e, this.lastScroll = flexbe_cli.scroll.latest;
        var s = this.$area.find(".modal-data > ._anchor").attr("name");
        s && (flexbe_cli.lockPopstate = !0, location.hash = "#" + s, flexbe_cli.lockPopstate = !1), $("body").addClass("overflow"), this.$list.addClass("show"), this.$area.addClass("show"), setTimeout(function() {
            o.$list.addClass("overlay"), o._onOpen(t), o._onScreen({
                state: !0
            }), o._onBeside({
                state: !0
            }), o._onView({
                state: !0
            }), o._onFocus({
                state: !0
            }), "function" == typeof e.onOpen && e.onOpen(o)
        }, 30), flexbe_cli.run.is_mobile && ($(".container-list, .widget-list, .mobile-menu, header, footer").css({
            display: "none"
        }), this.$list.css({
            position: "relative"
        }), this.$area.css({
            position: "relative"
        }), $("body, html").scrollTop(0))
    }, e.close = function(t) {
        var e = (void 0 === t ? {} : t).from,
            o = this.lastOptions;
        this.lastOptions = {}, this.isOpen = !1, /^#{1,2}/.test(location.hash) && (flexbe_cli.lockPopstate = !0, location.hash = "##", flexbe_cli.lockPopstate = !1), this.$area.removeClass("show"), e || ($("body").removeClass("overflow"), this.$list.removeClass("show overlay")), this._onClose(), this._onScreen({
            state: !1
        }), this._onBeside({
            state: !1
        }), this._onView({
            state: !1
        }), this._onFocus({
            state: !1
        }), "function" == typeof o.onClose && o.onClose(this), flexbe_cli.run.is_mobile && (this.$list[0].style.cssText = "", this.$area[0].style.cssText = "", $(".container-list, .widget-list, .mobile-menu, header, footer").css({
            display: ""
        }), $("body, html").scrollTop(this.lastScroll), console.log("modal return display and scrollback"))
    }, e._onOpen = function(t) {
        this.onOpen(t), flexbe_cli.events.emit("entity_event", {
            type: "opened",
            core: this
        })
    }, e._onClose = function() {
        this.onClose(), flexbe_cli.events.emit("entity_event", {
            type: "closed",
            core: this
        })
    }, e.onOpen = function() {}, e.onClose = function() {}, t
}(EntityCore);

function _inheritsLoose(t, o) {
    t.prototype = Object.create(o.prototype), (t.prototype.constructor = t).__proto__ = o
}
var WidgetCore = function(r) {
    function t(t, o) {
        var e;
        return (e = r.call(this, t, o) || this).is = "widget", e.$widget = e.$area, e
    }
    return _inheritsLoose(t, r), t
}(EntityCore);
flexbe_cli.components = {
    instances: {},
    classes: {},
    helpers: {},
    init: function() {
        var o = this,
            i = this.instances;
        this.links(), flexbe_cli.lang.init(), flexbe_cli.events.on("entity_event.components_init", function(e, n) {
            if (!(n && n.type && n.core && n.core.id)) return !1;
            var t = i[n.core.id] || [];
            "inited" === n.type || "updated" === n.type && n.params.templateRendered ? (o.initComponents({
                core: n.core,
                reason: n.type,
                params: n.params
            }), flexbe_cli.lang.init(n.core.area)) : /resize/.test(n.type) ? t.forEach(function(e) {
                return e._onResize({
                    core: n.core,
                    reason: n.type,
                    params: n.params
                })
            }) : /screen/.test(n.type) ? t.forEach(function(e) {
                return e._onView({
                    state: n.state
                })
            }) : /beside/.test(n.type) && t.forEach(function(e) {
                return e._onBeside({
                    state: n.state
                })
            })
        }), $(window).off("load.core_components").one("load.core_components", function() {
            Object.values(i).forEach(function(e) {
                e.forEach(function(e) {
                    return e._onPageLoad()
                })
            })
        })
    },
    initComponents: function(e) {
        var s = this,
            n = void 0 === e ? {} : e,
            l = n.core,
            t = n.reason,
            f = void 0 === t ? "inited" : t,
            o = l.id,
            i = l.$area,
            a = l.components,
            r = !1;
        !0 === a ? r = "[data-component]" : Array.isArray(a) && (r = a.map(function(e) {
            return '[data-component^="' + e + '"]'
        }).join(", "));
        var c = r ? i.find(r).toArray() : [],
            m = {};
        if (this.instances[o] = c.map(function(e) {
                var n = [],
                    t = String(e.getAttribute("data-component")).trim().split(":"),
                    o = t[0],
                    i = t[1];
                i && (n = String(i).replace(/^\[|\]$/g, "").split(",")), void 0 === m[o] && (m[o] = 0);
                var a = m[o];
                if (m[o] += 1, e.componentInstance) return e.componentInstance.index = a, e.componentInstance;
                var r = s.classes[o];
                if ("function" == typeof r) {
                    var c = new r({
                        args: n,
                        component: e,
                        index: a,
                        core: l,
                        reason: f
                    });
                    if (c instanceof BaseComponent) return (e.componentInstance = c)._onInit(), l.isVisible && c._onView({
                        state: !0
                    }), c
                }
                return !1
            }).filter(function(e) {
                return e
            }), !this.instances[o].length) return delete this.instances[o], !1
    },
    links: function(e) {
        void 0 === e && (e = "body"), $(e).off("click.component-links").on("click.component-links", "a[href], a[data-action]", function(e) {
            var n = $(e.currentTarget).attr("href"),
                t = $(e.currentTarget).attr("data-action");
            if (n || t) {
                if (/#{1,2}.+/.test(n)) {
                    if (1 === flexbe_cli.theme_id) $(e.currentTarget).closest(".mobile-menu").removeClass("active");
                    else $("body > .mobile-menu").trigger("close"), $("body").removeClass("overflow fixed");
                    var o = (n = n.replace(/^(?:\.\/)#{2}/, "#").trim()).trim().split("#"),
                        i = "" === o[0] || "./" === o[0] ? location.pathname : o[0],
                        a = o[o.length - 1];
                    if (i !== location.pathname) return !0;
                    var r = flexbe_cli.block.$list.find(".b_" + a + ', ._anchor[name="' + a + '"]').eq(0),
                        c = flexbe_cli.modal.$list.find(".m_" + a + ', ._anchor[name="' + a + '"]').eq(0);
                    if (r.length) {
                        var s = r.closest(".b_block");
                        flexbe_cli.events.emit("modal_command", {
                            command: "close"
                        }), flexbe_cli.block.scrollTo(s)
                    } else if (c.length) {
                        if (flexbe_cli.is_admin) return !0;
                        var l = c.closest(".m_modal").data("id");
                        flexbe_cli.events.emit("modal_command", {
                            command: "open",
                            id: l,
                            data: {}
                        })
                    }
                    e.preventDefault(), e.stopPropagation()
                } else if ("button" === t) {
                    var f = $(e.currentTarget).closest("[data-item-id]").find(".component-button");
                    f[0] && f[0].click()
                }
                return n && flexbe_cli.is_admin && (e.preventDefault(), e.stopPropagation()), !0
            }
        })
    }
};
var BaseComponent = function() {
    function i(i) {
        var e = this,
            t = i.component,
            n = i.index,
            o = i.core,
            s = i.reason;
        this.owner = o.area, this.root = o.root, this.index = n || 0, this.component = t, this.$component = $(t), this.require = [], this.isPageLoaded = flexbe_cli.isLoaded || !1, this.isUpdated = "updated" === s, this.isLoaded = !1, this.isInited = !1, this.isViewed = !1, this.isBesided = !1, this.inView = !1, this.inBeside = !1, Object.defineProperty(this, "isDisplayed", {
            get: function() {
                return e.$component.is(":visible")
            },
            enumerable: !0,
            configurable: !0
        })
    }
    var e = i.prototype;
    return e.onInit = function() {}, e.onLoad = function() {}, e.onView = function() {}, e.onBeside = function() {}, e.onPageLoad = function() {}, e.onResize = function() {}, e._onInit = function() {
        var i = this;
        flexbe_cli.require(this.require, function() {
            i._onLoad()
        }), "function" == typeof this.onInit && this.onInit(), this.isInited = !0
    }, e._onLoad = function() {
        "function" == typeof this.onLoad && this.onLoad(), this.isLoaded = !0
    }, e._onView = function(i) {
        var e = i.state;
        this.inView = e, this.onView({
            state: e,
            isFirst: e && !this.isViewed
        }), e && (this.isViewed = !0)
    }, e._onResize = function(i) {
        this.onResize(i)
    }, e._onBeside = function(i) {
        var e = i.state;
        this.inBeside = e, this.onBeside({
            state: e,
            isFirst: e && !this.isBesided
        }), e && (this.isBesided = !0)
    }, e._onPageLoad = function() {
        this.onPageLoad(), this.isPageLoaded = !0
    }, i
}();

function _typeof(i) {
    return (_typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(i) {
        return typeof i
    } : function(i) {
        return i && "function" == typeof Symbol && i.constructor === Symbol && i !== Symbol.prototype ? "symbol" : typeof i
    })(i)
}

function _inheritsLoose(i, t) {
    i.prototype = Object.create(t.prototype), (i.prototype.constructor = i).__proto__ = t
}! function() {
    var s = function() {
            function i(i, t, e) {
                var o = this;
                this.id = i, this.params = e, this.pending = !1, this.visible = !1, this.size = !1, this.imSize = {
                    w: 0,
                    h: 0
                }, this.$outer = $(".parallax-outer", t), this.overlay = $(".overlay", t).get(0), this.canvas = $("canvas", this.$outer).get(0), this.offScreen = document.createElement("canvas"), this.ovOp = +$(this.overlay).css("opacity"), this.factor = 1 === e.parallax || 2 === e.parallax ? .3 : 0, this.zoomRatio = 3 === e.parallax || 4 === e.parallax ? .2 : 0, this.zoomType = 3 === e.parallax || 4 === e.parallax ? 2 : 0, this.zoomD = 3 === e.parallax ? .5 : 4 === e.parallax ? 1 : 0, this.fadeout = 1 === e.parallax ? 0 : 3 === e.parallax ? .5 : 1;
                var s = 1.5 <= window.devicePixelRatio ? 1960 / window.devicePixelRatio : 1960,
                    a = this.$outer.width(),
                    n = this.$outer.height(),
                    h = this.$outer.offset() || {};
                this.blSize = {
                    w: a <= s ? a : s
                }, this.blSize.r = this.blSize.w / a, this.blSize.h = this.blSize.r * n, this.blSize.x = this.blSize.r * h.top, this.winSize = {
                    w: $("window").width() * this.blSize.r,
                    h: $("window").height() * this.blSize.r
                }, this.blSizeOr = {
                    w: a,
                    h: n,
                    x: h.top
                }, this.offCtx = this.offScreen.getContext("2d", {
                    alpha: !1
                }), this.canvas.width = 0, this.canvas.height = 0, this.ctx = this.canvas.getContext("2d", {
                    alpha: !1
                }), this.img = document.createElement("img"), this.img.onload = function() {
                    o.loaded = !0, o.imSize = {
                        w: o.img.width,
                        h: o.img.height
                    }, o.imSize.r = o.imSize.h / o.imSize.w, o.updateCanvasSource()
                }, this.img.src = this.$outer.attr("data-image"), this.position = {
                    x: +this.$outer.data("bg-pos-x").replace("%", "") / 100,
                    y: +this.$outer.data("bg-pos-y").replace("%", "") / 100
                }, this.dispatchEvents()
            }
            var t = i.prototype;
            return t.dispatchEvents = function() {
                var e = this;
                $(window).off("resize." + this.id + " documentresize." + this.id).on("resize." + this.id + " documentresize." + this.id, function() {
                    e.updateCanvasSource()
                }), $(window).off("scroll.component-bg-" + this.id).on("scroll.component-bg-" + this.id, function() {
                    e.pending || (e.pending = !0, e.updateInst())
                }), flexbe_cli.events.off("editor_change.bl.bg_" + this.id).on("editor_change.bl.bg_" + this.id, function(i, t) {
                    t.entity && +t.entity.id == +e.id && "editor_settings" === t.reason && (e.ovOp = .01 * t.entity.data.background.opacity, e.pending || (e.pending = !0, e.updateInst()))
                })
            }, t.toggleRendering = function(i) {
                if ("boolean" != typeof i) return !1;
                i !== this.visible && (this.visible = i, $(this.overlay).toggleClass("will-change", i))
            }, t.getCosPoint = function(i, t, e) {
                return void 0 === e && (e = 1), e < 1 - i && (i = e), (1 - Math.cos(Math.PI * i * t)) / 2
            }, t.getZoomCoords = function(i) {
                var t = 1 === this.zoomType ? i : -1 === this.zoomType ? 100 - i : 2 === this.zoomType ? 100 - 100 * this.getCosPoint(.01 * i, 2, this.zoomD) : 0,
                    e = {
                        ratio: this.zoomRatio / 100 * t
                    };
                return e.w = this.blSize.w * (1 + e.ratio), e.h = this.blSize.h * (1 + e.ratio), e.x = (e.w - this.canvas.width) / 2, e.y = (e.h - this.blSize.h) / 2, e
            }, t.draw = function(i, t) {
                var e, o = this;
                if (this.fadeout && (e = 1 - (1 - this.ovOp) * this.getCosPoint(.01 * t, 2, this.fadeout)), this.zoomRatio) {
                    var s = this.getZoomCoords(t);
                    requestAnimationFrame(function() {
                        o.overlay.style.opacity = e, o.ctx.drawImage(o.offScreen, Math.ceil(-1 * s.x), Math.ceil(i * o.factor - o.winSize.h * o.factor - s.y), s.w, s.h), o.pending = !1
                    })
                } else {
                    var a = Math.ceil(i * this.factor - this.winSize.h * this.factor);
                    requestAnimationFrame(function() {
                        o.overlay.style.opacity = e, o.ctx.drawImage(o.offScreen, 0, a), o.pending = !1
                    })
                }
            }, t.updateInst = function() {
                if (this.winSize.x = (window.scrollY || window.pageYOffset) * this.blSize.r, this.winSize.x + this.winSize.h > this.blSize.x - 200 && this.winSize.x < this.blSize.x + this.blSize.h) {
                    this.toggleRendering(!0);
                    var i = this.winSize.x + this.winSize.h - this.blSize.x,
                        t = 100 - i / (this.blSize.h + this.winSize.h) * 100;
                    this.draw(i, t)
                } else this.toggleRendering(!1), this.pending = !1
            }, t.prerender = function() {
                this.loaded && (this.fitToOuter(), this.offScreen.width = this.blSize.w, this.offScreen.height = this.zoomRatio ? this.blSize.h : Math.ceil(this.size.offH), this.drawOffscreenImage(this.position.x, this.position.y))
            }, t.updateCanvasSource = function() {
                this.prerender(), this.updateInst()
            }, t.drawOffscreenImage = function(i, t) {
                (i = "number" == typeof i ? i : .5) < 0 && (i = 0), (t = "number" == typeof t ? t : .5) < 0 && (t = 0), 1 < i && (i = 1), 1 < t && (t = 1);
                var e, o, s, a, n = this.offCtx.canvas.width,
                    h = this.offCtx.canvas.height;
                h / n <= this.imSize.r ? (e = 0, o = ((a = (s = n) * this.imSize.r) - h) * t * -1) : (o = 0, e = ((s = (a = h) / this.imSize.r) - n) * i * -1), this.offCtx.drawImage(this.img, e, o, s, a)
            }, t.fitToOuter = function() {
                var i = 1.5 <= window.devicePixelRatio ? 1600 : 1960;
                this.blSize = {
                    w: this.$outer.width() <= i ? this.$outer.width() : i
                }, this.blSize.r = this.blSize.w / this.$outer.width(), this.blSize.h = this.blSize.r * this.$outer.height(), this.blSize.x = this.blSize.r * this.$outer.offset().top, this.winSize = {
                    w: $(window).width() * this.blSize.r,
                    h: $(window).height() * this.blSize.r
                }, this.canvas.width = this.blSize.w, this.canvas.height = this.blSize.h, this.canvas.style.transform = "scale(" + 1 / this.blSize.r + ")";
                var t, e = Math.max(this.winSize.h, this.blSize.h),
                    o = e - (e - Math.min(this.winSize.h, this.blSize.h)) * (this.blSize.h > this.winSize.h ? this.factor : 1 - this.factor),
                    s = o / this.blSize.w;
                (t = this.imSize.r >= s ? {
                    w: this.blSize.w,
                    h: this.blSize.w * this.imSize.r
                } : {
                    h: o,
                    w: o / this.imSize.r
                }).offH = o, t.x = (t.w - this.blSize.w) / 2, this.size = t
            }, t.destroy = function() {
                this.destroyed = !0, $(window).off("resize." + this.id + " documentresize." + this.id), $(window).off("scroll.component-bg-" + this.id), flexbe_cli.events.off("editor_change.bl.bg_" + this.id), this.offScreen.remove(), this.img.remove()
            }, i
        }(),
        i = function(a) {
            function i() {
                for (var i, t = arguments.length, e = new Array(t), o = 0; o < t; o++) e[o] = arguments[o];
                var s = (i = a.call.apply(a, [this].concat(e)) || this).$component;
                return i.data = {
                    type: s.data("type") || "color",
                    parallax: s.data("parallax") || 0,
                    video: s.data("video") || !1,
                    videoParallaxFactor: .6
                }, i
            }
            _inheritsLoose(i, a);
            var t = i.prototype;
            return t.onInit = function() {
                this.loadImage(), this.imageParallaxInit(), this.videoParallaxInit()
            }, t.onView = function(i) {
                if (!i.state || this.isViewed || this.isBesided) return !1;
                this.playVideo()
            }, t.onBeside = function(i) {
                if (!i.state || this.isViewed || this.isBesided) return !1;
                this.playVideo()
            }, t.loadImage = function() {
                if (!flexbe_cli.is_admin) {
                    var i = this.$component,
                        t = i.find(".image, .parallax-outer"),
                        e = t.find(".loader-image"),
                        o = e.attr("data-src");
                    if (o) {
                        var s = new Image;
                        s.onload = function() {
                            t.css("backgroundImage", ""), i.removeClass("loading"), setTimeout(function() {
                                e.remove()
                            }, 300)
                        }, s.src = o
                    }
                }
            }, t.imageParallaxInit = function() {
                if ("image" !== this.data.type || !this.data.parallax) return !1;
                var i = this.owner,
                    t = this.$component,
                    e = this.data,
                    o = i.getAttribute("data-id");
                "object" === _typeof(i._bgEffects) && i._bgEffects.destroy(), i._bgEffects = new s(o, t, e)
            }, t.videoParallaxInit = function() {
                var e = this;
                if ("video" !== this.data.type || !this.data.parallax || !this.owner._core) return !1;
                var i = $(".image-holder, .video_bg_container", this.$component),
                    o = i.find(".image, .video_bg_player"),
                    s = this.owner._core,
                    t = function() {
                        var i = s.tween.start,
                            t = 1 - (1 - e.data.videoParallaxFactor) * (s.tween.height / flexbe_cli.resize.height);
                        t < 0 && (t = e.data.videoParallaxFactor / 2), o.css("transform", "translate3d(0, " + -(window.pageYOffset - i) * t + "px, 0)")
                    };
                if (flexbe_cli.resize.height < s.tween.height && 500 < s.tween.width ? i.css("height", s.tween.height + "px") : i.css("height", ""), flexbe_cli.run.is_desktop && (flexbe_cli.run.is_screen_pc || flexbe_cli.run.is_screen_small_pc)) {
                    var a = !1;
                    t(), $(window).off("scroll.component-bg-" + s.id).on("scroll.component-bg-" + s.id, function() {
                        !a && s.tween.onscreen && (a = !0, requestAnimationFrame(function() {
                            t(), a = !1
                        }))
                    })
                } else $(window).off("scroll.component-bg-" + s.id), o.css("transform", "")
            }, t.playVideo = function() {
                var i = this,
                    t = this.$component,
                    e = this.data,
                    o = e.video;
                if ("video" !== e.type || !o || "youtube" !== o.type || !o.id) return !1;
                if (flexbe_cli.run.is_screen_mobile || flexbe_cli.run.is_screen_tablet_s) return !1;
                if (t.data("video_bg_played")) {
                    if (o.id === t.data("video_bg_played")) return;
                    this.destroyVideo()
                }
                t.data("video_bg_played", o.id), flexbe_cli.require(["/_s/lib/jquery/youtubebackground/jquery.youtubebackground.js"], function() {
                    t.YTPlayer({
                        videoId: o.id,
                        videoURL: o.url,
                        callback: function() {
                            i.videoParallaxInit(), setTimeout(function() {
                                i.videoParallaxInit()
                            }, 1e3)
                        }
                    })
                })
            }, t.destroyVideo = function() {
                var i = this.$component;
                i.data("ytPlayer") && i.data("ytPlayer").destroy(), i.removeData("video_bg_played")
            }, i
        }(BaseComponent);
    flexbe_cli.components.classes.background = i
}();

function _inheritsLoose(t, e) {
    t.prototype = Object.create(e.prototype), (t.prototype.constructor = t).__proto__ = e
}! function() {
    var t = function(n) {
        function t() {
            for (var t, e = arguments.length, i = new Array(e), a = 0; a < e; a++) i[a] = arguments[a];
            (t = n.call.apply(n, [this].concat(i)) || this).is = "image", t.autoSet = !0, t.img = t.$component.find("img").get(0), t.loader = t.$component.find(".loading-img").get(0), t.params = {};
            var o = t.$component.parent();
            o.hasClass("slider-item") && (o.closest(".component-slider").attr("data-current-index") == o.attr("data-real-index") || (t.autoSet = !1));
            return t.$component.on("setImage", function() {
                t.set()
            }), t
        }
        _inheritsLoose(t, n);
        var e = t.prototype;
        return e.onInit = function() {
            this.isUpdated && this.set()
        }, e.onPageLoad = function() {
            this.owner._core;
            if (!this.autoSet || this.isViewed) return !1;
            this.set()
        }, e.onView = function(t) {
            if (!t.state || !this.autoSet || this.isViewed || this.isBesided) return !1;
            this.set()
        }, e.onBeside = function(t) {
            if (!t.state || !this.autoSet || this.isViewed || this.isBesided) return !1;
            this.set()
        }, e.set = function() {
            var t = this;
            if (!this.isSet && this.isDisplayed) {
                this.updateParams();
                var e = this.params;
                this.isSet = !0, e.loaded || flexbe_cli.is_admin || !e.resizable || e.offsetWidth < 200 ? e.loaded ? (this.updateParams(), this.preloadImage(e, function() {
                    t.setImage(), t.removeLoading()
                })) : (this.setImage(), this.removeLoading()) : (this.addLoading(), this.setImage({
                    width: 50
                }), this.preloadImage({
                    width: 50
                }, function() {
                    t.updateParams(), t.setImage(), t.preloadImage(e, function() {
                        t.removeLoading()
                    })
                }))
            }
        }, e.setImage = function(t) {
            var e = this.getUrl(t),
                i = "url(" + e + ")",
                a = this.params.x + " " + this.params.y;
            this.img && (this.img.src = e), "background" === this.params.type && (this.component.style.backgroundImage = i, this.component.style.backgroundPosition = a), this.loader && !this.loader.style.backgroundImage && (this.loader.style.backgroundImage = i, this.loader.style.backgroundPosition = a)
        }, e.addLoading = function() {
            var t = this;
            requestAnimationFrame(function() {
                t.component.classList.add("loading")
            })
        }, e.removeLoading = function() {
            var t = this;
            requestAnimationFrame(function() {
                t.component.classList.remove("loading")
            })
        }, e.updateParams = function() {
            var t, e, i, a, o, n = this.component,
                s = this.img,
                r = this.params;
            if (!r.id) {
                if (r.x = n.getAttribute("data-img-x"), r.y = n.getAttribute("data-img-y"), r.id = n.getAttribute("data-img-id"), r.type = n.getAttribute("data-img-type"), r.name = n.getAttribute("data-img-name"), r.ext = n.getAttribute("data-img-ext"), !r.ext) {
                    var d = r.name.match(/\.((!:jpeg|jpg|png|gif|bmp|webp|svg){1,4})/) || [];
                    r.ext = d[1] || "jpg"
                }
                r.resizable = !["svg", "gif"].includes(r.ext) || "big" === r.type
            }
            if (r.offsetWidth = Math.round(n.offsetWidth), r.offsetHeight = Math.round(n.offsetHeight), !r.proportion && s.src && (r.proportion = s.naturalHeight / s.naturalWidth), r.proportion) {
                var h = (t = r.offsetWidth, e = r.offsetHeight, (i = r.proportion) < e / t ? (a = e / i, o = e) : o = (a = t) * i, {
                        width: a = Math.round(a),
                        height: o = Math.round(o)
                    }),
                    m = h.width,
                    c = h.height;
                r.optimalWidth = m, r.optimalHeight = c, r.loaded = !0
            }
            return r
        }, e.getUrl = function(t) {
            var e = (void 0 === t ? {} : t).width;
            this.params.id || this.updateParams();
            var i = this.params,
                a = i.offsetWidth,
                o = i.optimalWidth,
                n = i.id,
                s = i.name,
                r = i.ext,
                d = i.resizable;
            e = e || o || a || 50;
            var h = Math.max(e <= 160 ? 2 : 1, window.devicePixelRatio || 1);
            return e = Math.round(Math.min(2560, e * h)), ["/img/" + n, d ? "_" + e : "", r ? "." + r : "/" + s].join("")
        }, e.preloadImage = function(t, e) {
            void 0 === e && (e = function() {});
            var i = this.getUrl(t),
                a = new Image;
            return a.onload = function() {
                "function" == typeof a.remove && a.remove(), e()
            }, a.src = i, a
        }, t
    }(BaseComponent);
    flexbe_cli.components.classes.image = t, flexbe_cli.components.helpers.replacePlaceholder = function(t) {
        var e = $(t);
        e && 0 !== e.length && e.each(function(t, e) {
            var i = $(e).data("src"),
                a = $(e).data("srcset"),
                o = new Image;
            o.onload = function() {
                i && (e.src = i), a && (e.srcset = a), $(e).addClass("loaded")
            }, i && (o.src = i), a && (o.srcset = a)
        })
    }
}();

function _inheritsLoose(t, e) {
    t.prototype = Object.create(e.prototype), (t.prototype.constructor = t).__proto__ = e
}! function() {
    var t = function(a) {
        function t() {
            for (var t, e = arguments.length, n = new Array(e), i = 0; i < e; i++) n[i] = arguments[i];
            (t = a.call.apply(a, [this].concat(n)) || this).is = "video", t.type = t.$component.data("type"), t.autoplay = !!+t.$component.data("autoplay"), t.$imageBtn = $(t.$component).find(".component-image > .play-controll"), t.custumPlayBtn = t.$imageBtn.length, t.frameLoaded = !1, t.autoSet = !0;
            var o = t.$component.parent();
            o.hasClass("slider-item") && (o.closest(".component-slider").attr("data-current-index") === o.attr("data-real-index") || (t.autoSet = !1));
            return t.$component.off("sliderActivate").on("sliderActivate", function() {
                t.set()
            }), t.$component.off("sliderDeactivate").on("sliderDeactivate", function() {
                t.pause()
            }), t
        }
        _inheritsLoose(t, a);
        var e = t.prototype;
        return e.onInit = function() {
            this.bindEvents()
        }, e.onView = function(t) {
            var e = this,
                n = t.state;
            if (n ? this.autoplay && this.onFrameLoaded(function() {
                    e.play()
                }) : this.pause(), !n || !this.autoSet || this.isViewed || this.isBesided) return !1;
            this.custumPlayBtn || this.set()
        }, e.onBeside = function(t) {
            if (!t.state || !this.autoSet || this.isViewed || this.isBesided) return !1;
            this.custumPlayBtn || this.set()
        }, e.onPageLoad = function() {
            var t = this;
            setTimeout(function() {
                if (!t.autoSet || t.isViewed || t.isBesided) return !1;
                t.custumPlayBtn || t.set()
            }, 2e3)
        }, e.bindEvents = function() {
            var o = this;
            $(".video-preview", this.$component).on("click", function(t) {
                var e = $(t.currentTarget).parents(".slider-wrapper").get(0);
                t.preventDefault(), e && e.swiper && e.swiper.autoplay.pause();
                var n = o.$component.find("iframe"),
                    i = n.attr("data-src") || n.attr("src");
                i && (n[0].src = i + "&autoplay=1"), setTimeout(function() {
                    o.$component.attr("data-state", "play")
                }, 250)
            }), this.custumPlayBtn && this.$imageBtn.on("click", function() {
                if (o.isSet) return !1;
                o.isSet = !0;
                var t = o.$component.find("iframe"),
                    e = t.attr("data-src");
                t[0].src = e + "&autoplay=1&enablejsapi=1", o.$component.find(".component-image").addClass("play")
            })
        }, e.set = function() {
            if (this.isSet) return !1;
            this.isSet = !0;
            var t = this.component.querySelector("iframe");
            if (!t) return !1;
            var e = t.getAttribute("data-src"),
                n = t.getAttribute("src");
            e && !n && (t.src = "" + e)
        }, e.play = function() {
            var t = this.component.querySelector("iframe"),
                e = t && t.contentWindow;
            if (!e) return !1;
            this.isPaused = !1, "vimeo" === this.type ? e.postMessage({
                method: "play"
            }, "*") : e.postMessage('{"event":"command","func":"playVideo","args":""}', "*")
        }, e.pause = function() {
            var t = this.component.querySelector("iframe"),
                e = t && t.contentWindow;
            if (!e) return !1;
            this.isPaused = !0, "vimeo" === this.type ? e.postMessage({
                method: "pause"
            }, "*") : e.postMessage('{"event":"command","func":"pauseVideo","args":""}', "*")
        }, e.onFrameLoaded = function(t) {
            var e = this;
            this.frameLoaded ? "function" == typeof t && t() : this.component.querySelector("iframe").onload = function() {
                e.frameLoaded = !0, "function" == typeof t && t()
            }
        }, t
    }(BaseComponent);
    flexbe_cli.components.classes.video = t
}();

function _inheritsLoose(t, e) {
    t.prototype = Object.create(e.prototype), (t.prototype.constructor = t).__proto__ = e
}

function _typeof(t) {
    return (_typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
        return typeof t
    } : function(t) {
        return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
    })(t)
}

function _extends() {
    return (_extends = Object.assign || function(t) {
        for (var e = 1; e < arguments.length; e++) {
            var i = arguments[e];
            for (var n in i) Object.prototype.hasOwnProperty.call(i, n) && (t[n] = i[n])
        }
        return t
    }).apply(this, arguments)
}! function() {
    function m(t, e, i, n) {
        var a = $(t),
            o = e ? '[data-component="' + e + '"]' : "[data-component]";
        a.is(o) ? a.trigger(i, n) : $(t).find(o).each(function(t, e) {
            $(e).trigger(i, n)
        })
    }

    function y() {
        for (var t = arguments.length, e = new Array(t), i = 0; i < t; i++) e[i] = arguments[i];
        e.filter(function(t) {
            return t
        }).forEach(function(t) {
            m(t, "image", "setImage")
        })
    }

    function c(t) {
        return ["normal", "active"].includes(t)
    }
    var b = function() {
            function t(t) {
                var e = t = _extends({
                        init: !0,
                        pagination: !1,
                        targets: "span",
                        visible: 3,
                        active: 0
                    }, t),
                    i = e.active,
                    n = e.pagination;
                if (!t.visible || "object" !== _typeof(n) || !n.querySelectorAll) return !1;
                Array.isArray(t.targets) || t.targets instanceof NodeList || t.targets instanceof HTMLCollection ? this.targets = Array.from(t.targets) : this.targets = Array.from(n.querySelectorAll(t.targets)), this.options = t, this.active = i, this.pagination = n, t.init && this.setActive(this.active, !0)
            }
            return t.prototype.setActive = function(t, e) {
                var o, s, i = this.targets,
                    n = this.options,
                    a = this.active,
                    r = this.oldMap,
                    l = this.newMap;
                if (t = Math.max(Math.min(t, i.length - 1), 0), r = r && r.length ? r : i.map(function(t) {
                        return {
                            target: t,
                            state: t.getAttribute("data-state") || "hidden"
                        }
                    }), (e ? 0 : r.reduce(function(t, e) {
                        return t + (c(e.state) ? 1 : 0)
                    }, 0)) !== n.visible) s = Math.min(t + n.visible - 1, i.length - 1), o = Math.max(s - n.visible + 1, 0), l = r.map(function(t, e) {
                    var i = t.target;
                    t.state;
                    return {
                        target: i,
                        state: o <= e && e <= s ? "normal" : "hidden"
                    }
                });
                else {
                    var p = function t(e) {
                        var i = e.from,
                            n = e.to,
                            a = e.map,
                            o = e.move,
                            s = void 0 === o ? 0 : o;
                        return c(a[n - s].state) ? s : i < n ? t({
                            from: i,
                            to: n,
                            map: a,
                            move: s + 1
                        }) : n < i ? t({
                            from: i,
                            to: n,
                            map: a,
                            move: s - 1
                        }) : void 0
                    }({
                        from: a,
                        to: t,
                        map: r
                    });
                    l = r.map(function(t, e) {
                        var i = t.target,
                            n = t.state,
                            a = r[e - p];
                        return "normal" === (n = a && a.state && c(a.state) ? "normal" : "hidden") && (void 0 === o && (o = e), s = e), {
                            target: i,
                            state: n
                        }
                    })
                }
                return l[t].state = "active", l[o - 1] && (l[o - 1].state = "next"), l[o - 2] && (l[o - 2].state = "more"), l[s + 1] && (l[s + 1].state = "next"), l[s + 2] && (l[s + 2].state = "more"), i.forEach(function(t, e) {
                    t.setAttribute("data-state", l[e].state)
                }), this.active = t, this.oldMap = l, t
            }, t
        }(),
        t = function() {
            function t(t) {
                var e = t = _extends({
                        init: !0,
                        pagination: !1,
                        tag: "span"
                    }, t),
                    i = e.pagination,
                    n = e.tag;
                this.filled = !1, this.pagination = i, this.tag = n, this.states = ["hidden", "more", "next", "active", "next", "more", "hidden"], t.init && this.fillPagination({
                    states: this.states,
                    force: !0
                })
            }
            var e = t.prototype;
            return e.setActive = function(i, t, e) {
                var n = this.states,
                    a = this.filled && i;
                if (this.fillPagination({
                        states: n,
                        force: !0,
                        activeIndex: t,
                        inRow: e
                    }), a) {
                    var o = n.map(function(t, e) {
                        return ("prev" === i ? n[e + 1] : n[e - 1]) || "hidden"
                    });
                    this.pagination.offsetWidth;
                    this.fillPagination({
                        states: o
                    })
                }
            }, e.fillPagination = function(t) {
                var e = void 0 === t ? {} : t,
                    n = e.states,
                    i = e.force,
                    a = $(this.pagination),
                    o = this.tag;
                if (this.filled = !0, i) {
                    var s = n.reduce(function(t, e, i) {
                        return t + "<" + o + ' data-index="' + i + '" class="swiper-pagination-bullet" data-state="' + e + '"></' + o + ">"
                    }, "");
                    a.html(s)
                } else a.find(o).each(function(t, e) {
                    var i = $(e);
                    i.attr("data-state", n[t] || "hidden"), i.attr("data-index", t)
                })
            }, t
        }(),
        e = function(o) {
            function t() {
                for (var t, e = arguments.length, i = new Array(e), n = 0; n < e; n++) i[n] = arguments[n];
                var a = (t = o.call.apply(o, [this].concat(i)) || this).component;
                return t.require = ["/_s/lib/swiper/swiper.v4.js"], t.is = "slider", t.swiper = null, t.wrapperEl = a.querySelector(".slider-wrapper"), t.paginationEl = a.querySelector(".slider-pagination"), t.prevEl = a.querySelector('.slider-prev, [data-direction="prev"]'), t.nextEl = a.querySelector('.slider-next, [data-direction="next"]'), t.options = {
                    count: a.getAttribute("data-count"),
                    pagination: t.paginationEl && t.paginationEl.getAttribute("data-type") || "bullets",
                    loop: Boolean(!flexbe_cli.is_admin && Math.floor(a.getAttribute("data-loop"))),
                    autoplay: !flexbe_cli.is_admin && Math.floor(a.getAttribute("data-autoplay")) || !1
                }, t
            }
            _inheritsLoose(t, o);
            var e = t.prototype;
            return e.onInit = function() {
                this.isUpdated && this.isLoaded && this.initSwiper()
            }, e.onLoad = function() {
                if (!this.inView && !this.inBeside) return !1;
                this.initSwiper()
            }, e.onView = function(t) {
                var e = t.state;
                if (!this.isLoaded) return !1;
                this.swiper || this.initSwiper(), this.toggleAutoplay({
                    state: e
                })
            }, e.onBeside = function(t) {
                if (!t.state || !this.isLoaded || this.swiper) return !1;
                this.initSwiper()
            }, e.onResize = function() {
                this.swiper && this.swiper.update()
            }, e.initSwiper = function() {
                var s = this;
                if (this.swiper || this.options.count <= 1 || "undefined" == typeof Swiper) return !1;
                var t = this.options,
                    r = this.component,
                    e = this.owner,
                    l = this.root,
                    i = this.index,
                    n = this.wrapperEl,
                    a = this.paginationEl,
                    o = this.prevEl,
                    p = this.nextEl,
                    c = (e._core && e._core.id || "-") + ":" + i,
                    u = t.loop,
                    d = {
                        prevEl: o,
                        nextEl: p
                    },
                    f = {
                        el: a,
                        clickable: !0,
                        type: t.pagination,
                        modifierClass: "slider-pagination-"
                    },
                    h = !!t.autoplay && {
                        delay: 1e3 * t.autoplay,
                        stopOnLastSlide: !u
                    },
                    g = 0;
                if (flexbe_cli.is_admin) {
                    l._sliderState || (l._sliderState = {});
                    var v = l._sliderState;
                    v[c] && (g = Math.max(0, Math.min(t.count - 1, v[c]) || 0)), v[c] = g
                }
                this.$component.one("mouseenter.loadImages", function() {
                    y(s.$component)
                }), this.swiper = new Swiper(n, {
                    init: !1,
                    speed: 450,
                    wrapperClass: "slider",
                    slideClass: "slider-item",
                    slideActiveClass: "active",
                    noSwipingClass: "redactor-box",
                    initialSlide: g,
                    navigation: d,
                    pagination: f,
                    autoplay: h,
                    loop: u,
                    simulateTouch: !flexbe_cli.is_admin,
                    roundLengths: !0,
                    touchMoveStopPropagation: !1,
                    preventClicksPropagation: !1,
                    preventClicks: !1,
                    runCallbacksOnInit: !1
                }), this.swiper.on("init", function() {
                    $(o).add(p).removeClass("disabled"), $(".swiper-slide-duplicate .img-popup", r).on("click", function(t) {
                        var e = $(t.currentTarget).parents(".swiper-slide-duplicate");
                        $(".img-popup", e.siblings(".slider-item[data-real-index=" + e.attr("data-real-index") + "]")).trigger("click"), t.preventDefault()
                    })
                }), this.swiper.on("slideChange", function() {
                    var t = s.swiper,
                        e = t.slides[t.previousIndex],
                        i = t.slides[t.activeIndex],
                        n = t.slides[t.activeIndex - 1],
                        a = t.slides[t.activeIndex + 1],
                        o = i && i.getAttribute("data-type") || "image";
                    r.setAttribute("data-current-content", o), flexbe_cli.is_admin && (l._sliderState[c] = t.realIndex), m(e, "", "sliderDeactivate"), m(i, "", "sliderActivate"), y(i, a, n)
                }), this.swiper.on("paginationRender", function() {
                    if ("bullets" === f.type) {
                        var t = f.el && +f.el.getAttribute("data-visible") || !1;
                        t && (s.bullets = new b({
                            init: !1,
                            pagination: f.el,
                            targets: "span",
                            visible: t,
                            active: s.swiper.realIndex
                        }))
                    }
                    $(a).removeClass("disabled")
                }), this.swiper.on("paginationUpdate", function() {
                    s.bullets && s.bullets.setActive(s.swiper.realIndex)
                }), this.swiper.init(), this.toggleAutoplay({
                    state: this.inView
                }), u && flexbe_cli.components.initComponents({
                    core: this.owner._core
                })
            }, e.toggleAutoplay = function(t) {
                var e = t.state;
                if (!this.swiper) return !1;
                var i = this.swiper;
                this.options.autoplay && i.autoplay && (e && !i.autoplay.running ? i.autoplay.start() : !e && i.autoplay.running && i.autoplay.stop())
            }, t
        }(BaseComponent);
    flexbe_cli.components.classes.slider = e, flexbe_cli.components.classes.bulletsPagination = b, flexbe_cli.components.classes.bulletsLoopPagination = t
}();

function _typeof(t) {
    return (_typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
        return typeof t
    } : function(t) {
        return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
    })(t)
}

function _extends() {
    return (_extends = Object.assign || function(t) {
        for (var e = 1; e < arguments.length; e++) {
            var i = arguments[e];
            for (var o in i) Object.prototype.hasOwnProperty.call(i, o) && (t[o] = i[o])
        }
        return t
    }).apply(this, arguments)
}

function _inheritsLoose(t, e) {
    t.prototype = Object.create(e.prototype), (t.prototype.constructor = t).__proto__ = e
}! function() {
    var t = function(a) {
        function t() {
            for (var t, e = arguments.length, i = new Array(e), o = 0; o < e; o++) i[o] = arguments[o];
            return (t = a.call.apply(a, [this].concat(i)) || this).is = "button", t
        }
        _inheritsLoose(t, a);
        var e = t.prototype;
        return e.onInit = function() {
            this.$component.off("click.core-component").on("click.core-component", this.onClick.bind(this))
        }, e.onClick = function(t) {
            if (flexbe_cli.is_admin) return t.preventDefault(), !0;
            var e = this.$component,
                i = e.closest("[data-item-id], .modal-data").eq(0),
                o = e.attr("data-action"),
                a = this.owner.getAttribute("data-id");
            if (!o) return !0;
            var n = e.attr("data-modal-id"),
                l = flexbe_cli.stat.getGoal(o, n);
            flexbe_cli.stat.reach_goal(l);
            try {
                var r = e.find('[name="goal"]').val();
                r && flexbe_cli.stat.reach_goal(r);
                var c = e.find('[name="goal_html"]').val();
                c && ($("body").find(".button-html-goal").detach(), $("body").eq(0).append('<div class="button-html-goal" style="display:none"></div>'), $("body").find(".button-html-goal").html(c))
            } catch (t) {
                console.warn(t.message)
            }
            var m = _extends({}, e.data("product"));
            if (("object" !== _typeof(m) || Array.isArray(m)) && (m = {}), !m.id) {
                var d = flexbe_cli.p_id,
                    s = i.attr("data-item-id") || 0,
                    f = this.owner.getAttribute("data-multivar") || "";
                m.id = d + "_" + a + "_" + s + f
            }
            if (i[0] && !["link", "file", "close"].includes(o)) {
                if (m.count || (m.count = 1), !m.price) {
                    var p = i.find(".price, .item-price, .main-price").eq(0).clone();
                    p.find("del, s").remove(), m.price = parseFloat(p.text().replace(/[,.]/g, ".").replace(/\.$/, "").replace(/[^\d.]/g, "")) || 0
                }
                if (!m.title) {
                    var u = i.find(".name, .title, .item-title, .text_title").eq(0);
                    m.title = u.text().trim() || "-"
                }
                if (!m.img) {
                    var _ = i.find("[data-img-id]").filter(function(t, e) {
                            return !$(e).closest(".swiper-slide-duplicate").length
                        }).eq(0),
                        b = _.attr("data-img-id"),
                        y = _.attr("data-img-name");
                    m.img = !(!b || !y) && "/img/" + b + "_200/" + y
                }
            }
            if (m.title && "-" !== m.title || m.price || (m = !1), n && o.match(/^modal|^form/)) {
                if (!flexbe_cli.modal.find(n)) n = parseInt(String(a).split("_")[0], 10) + "_" + n;
                flexbe_cli.events.emit("modal_command", {
                    command: "open",
                    id: n,
                    data: {
                        items: m ? [m] : []
                    }
                })
            } else if (m && o.match(/^cart$/)) {
                var v = e.closest(".m_modal").length;
                e.addClass("animate-add-to-cart"), setTimeout(function() {
                    e.removeClass("animate-add-to-cart"), v && (flexbe_cli.events.emit("modal_command", {
                        command: "close"
                    }), flexbe_cli.events.emit("cart_command", {
                        command: "add",
                        item: m
                    }))
                }, 1600), v || flexbe_cli.events.emit("cart_command", {
                    command: "add",
                    item: m
                })
            } else "quiz" === o ? flexbe_cli.events.emit("quiz_command", {
                command: "start",
                id: a
            }) : "close" === o && flexbe_cli.events.emit("modal_command", {
                command: "close"
            })
        }, t
    }(BaseComponent);
    flexbe_cli.components.classes.button = t
}();

function _inheritsLoose(e, o) {
    e.prototype = Object.create(o.prototype), (e.prototype.constructor = e).__proto__ = o
}! function() {
    var e = function(a) {
            function e() {
                for (var e, o = arguments.length, t = new Array(o), n = 0; n < o; n++) t[n] = arguments[n];
                return (e = a.call.apply(a, [this].concat(t)) || this).is = "map", e.$map = e.$component, e.data = e.$component.data("data"), e.$component.removeAttr("data-data"), e
            }
            _inheritsLoose(e, a);
            var o = e.prototype;
            return o.onInit = function() {
                this.isUpdated && this.isLoaded && this.createMap()
            }, o.onLoad = function() {
                (this.inView || this.inBeside) && this.createMap()
            }, o.onView = function(e) {
                var o = e.state;
                if (!this.isLoaded) return !1;
                !o || this.isViewed || this.isBesided || this.createMap()
            }, o.onBeside = function(e) {
                var o = e.state;
                if (!this.isLoaded) return !1;
                !o || this.isViewed || this.isBesided || this.createMap()
            }, o.createMap = function() {}, e
        }(BaseComponent),
        t = function(a) {
            function e() {
                for (var e, o = arguments.length, t = new Array(o), n = 0; n < o; n++) t[n] = arguments[n];
                return (e = a.call.apply(a, [this].concat(t)) || this).require = ["//api-maps.yandex.ru/2.1/?lang=ru_RU"], e
            }
            _inheritsLoose(e, a);
            var o = e.prototype;
            return o.onResize = function() {
                var e = this.map;
                e && e.container.fitToViewport()
            }, o.createMap = function() {
                var e = this;
                "undefined" != typeof ymaps && ymaps.ready(function() {
                    e.createVendor(), e.setPlaces(), e.dispatchEvents(), e.fixBehavior(), e.$component.trigger("mapInit"), e.$component.removeClass("loading")
                })
            }, o.createVendor = function() {
                this.map && this.map.destroy(), this.map = new ymaps.Map(this.$component.find(".map")[0], {
                    center: this.data.center,
                    zoom: this.data.zoom,
                    controls: ["zoomControl"],
                    behaviors: ["default", "scrollZoom"],
                    type: "yandex#map"
                })
            }, o.setPlaces = function() {
                var n = this,
                    a = this.map,
                    e = this.data;
                if (a) {
                    a.geoObjects.removeAll();
                    var o = ymaps.templateLayoutFactory.createClass('<svg title="$[properties.balloonContent]" class="placemark" width="32" height="48" viewBox="0 0 24 32" xmlns="http://www.w3.org/2000/svg"><path d="M12 0C5.36 0 0 5.36 0 12c0 8 12 20 12 20s12-12 12-20c0-6.64-5.36-12-12-12zm0 8a4 4 0 0 1 4 4c0 2.22-1.78 4-4 4a4 4 0 0 1-4-4c0-2.2 1.8-4 4-4z" fill="$[properties.color]" fill-rule="evenodd"/></svg>');
                    ymaps.layout.storage.get("custom#placemark") || ymaps.layout.storage.add("custom#placemark", o), e.places.forEach(function(e, o) {
                        e.color = e.color || "#3D52B0";
                        var t = new ymaps.Placemark(e.coords, {
                            balloonContentHeader: e.name || !1,
                            balloonContent: e.address,
                            color: e.color
                        }, {
                            hideIconOnBalloonOpen: !1,
                            balloonOffset: [0, -48],
                            balloonCloseButton: !1,
                            iconLayout: "custom#placemark",
                            iconShape: {
                                type: "Rectangle",
                                coordinates: [
                                    [-16, -48],
                                    [16, 0]
                                ]
                            }
                        });
                        t.events.add("balloonopen", function() {
                            n.$component.trigger("balloonOpen", o, e)
                        }), a.geoObjects.add(t), e._mark = t
                    })
                }
            }, o.dispatchEvents = function() {
                var n = this.data,
                    a = this.map;
                a && (this.$component.on("selectMark", function(e, o) {
                    if (n.places.length && n.places[o]) {
                        var t = n.places[o];
                        a.setCenter(t.coords, n.zoom, {
                            duration: 350,
                            checkZoomRange: !0
                        }).then(function() {
                            a.setCenter(t.coords)
                        }), t._mark && t._mark.balloon.open()
                    }
                }), this.$component.on("resizeMap", function() {
                    a.container.fitToViewport()
                }))
            }, o.fixBehavior = function() {
                var e, o = this.map;
                o && (o.behaviors.disable("scrollZoom"), this.$component.off("mouseenter.preventzoom").on("mouseenter.preventzoom", function() {
                    e = setTimeout(function() {
                        o.behaviors.enable("scrollZoom")
                    }, 500)
                }), this.$component.off("mouseleave.preventzoom").on("mouseleave.preventzoom", function() {
                    e && (clearTimeout(e), o.behaviors.disable("scrollZoom"))
                }), flexbe_cli.run.is_mobile && o.behaviors.disable("drag"))
            }, e
        }(e),
        n = function(i) {
            function e() {
                for (var e, o = arguments.length, t = new Array(o), n = 0; n < o; n++) t[n] = arguments[n];
                e = i.call.apply(i, [this].concat(t)) || this;
                var a = flexbe_cli.google_maps_api_key || "AIzaSyBZ5MufayEgZaNJ-dDo6epfouAZr5wATEs";
                return e.require = ["//maps.googleapis.com/maps/api/js?key=" + a], e
            }
            _inheritsLoose(e, i);
            var o = e.prototype;
            return o.createMap = function() {
                "undefined" != typeof google && (this.createVendor(), this.styleMap(), this.setPlaces(), this.dispatchEvents(), this.fixBehavior(), this.$component.trigger("mapInit"), this.$component.removeClass("loading"))
            }, o.createVendor = function() {
                var e = this.data;
                this.map = new google.maps.Map(this.$component.find(".map")[0], {
                    center: {
                        lat: e.center[0],
                        lng: e.center[1]
                    },
                    zoom: e.zoom,
                    disableDefaultUI: !0,
                    panControl: !0,
                    zoomControl: !0,
                    mapTypeControl: !1,
                    streetViewControl: !1,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    scrollwheel: !1
                })
            }, o.dispatchEvents = function() {
                var t = this,
                    e = this.$component,
                    o = this.map;
                o && (e.on("selectMark", function(e, o) {
                    t.selectMark(o, !0)
                }), e.on("resizeMap", function() {
                    google.maps.event.trigger(o, "resize")
                }))
            }, o.setPlaces = function() {
                var a = this,
                    e = this.data,
                    i = this.map;
                i && e.places.forEach(function(e, o) {
                    var t = '<svg width="32" height="48" viewBox="0 0 24 32" xmlns="http://www.w3.org/2000/svg"><path d="M12 0C5.36 0 0 5.36 0 12c0 8 12 20 12 20s12-12 12-20c0-6.64-5.36-12-12-12zm0 8a4 4 0 0 1 4 4c0 2.22-1.78 4-4 4a4 4 0 0 1-4-4c0-2.2 1.8-4 4-4z" fill="' + (e.color || "#222") + '" fill-rule="evenodd"/></svg>',
                        n = new google.maps.Marker({
                            position: {
                                lat: e.coords[0],
                                lng: e.coords[1]
                            },
                            map: i,
                            visible: !0,
                            animation: google.maps.Animation.DROP,
                            icon: {
                                url: "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(t)
                            }
                        });
                    (e._mark = n).addListener("click", function() {
                        a.selectMark(o)
                    })
                })
            }, o.styleMap = function() {
                var e = this.data,
                    o = this.map;
                if (o)
                    if ("default" !== e.style && e.style) {
                        var t = "/_s/lib/google/maps/styles/" + e.style + ".json";
                        $.getJSON(t, function(e) {
                            e && Array.isArray(e) && o.setOptions({
                                styles: e
                            })
                        })
                    } else o.setOptions({
                        styles: []
                    })
            }, o.selectMark = function(e, o) {
                void 0 === o && (o = !1);
                var t = this.data,
                    n = this.map,
                    a = t.places;
                if (n && a && a.length && a[e]) {
                    a.forEach(function(e) {
                        return e._info && e._info.close()
                    });
                    var i = a[e],
                        s = "";
                    i.name && (s += '<div style="margin-bottom: 3px;"><strong>' + i.name + "</strong></div>"), i.address && (s += "<div>" + i.address + "</div>"), i._info = new google.maps.InfoWindow({
                        content: s
                    }), i._info.open(n, i._mark), o && n.panTo({
                        lat: i.coords[0],
                        lng: i.coords[1]
                    }), this.$component.trigger("balloonOpen", e, i)
                }
            }, o.fixBehavior = function() {
                var e, o = this.$component,
                    t = this.map;
                t && (o.off("mouseenter.preventzoom").on("mouseenter.preventzoom", function() {
                    e = setTimeout(function() {
                        t.setOptions({
                            scrollwheel: !0
                        })
                    }, 500)
                }), o.off("mouseleave.preventzoom").on("mouseleave.preventzoom", function() {
                    e && (clearTimeout(e), t.setOptions({
                        scrollwheel: !1
                    }))
                }))
            }, e
        }(e);
    flexbe_cli.components.classes.map = function(e) {
        var o = e.args[0] || "yandex";
        return "google" === o && !flexbe_cli.is_admin && String(flexbe_cli.google_maps_api_key.length) < 32 && (o = "yandex"), new("google" === o ? n : t)(e)
    }
}();

function _typeof(e) {
    return (_typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
        return typeof e
    } : function(e) {
        return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
    })(e)
}

function _inheritsLoose(e, t) {
    e.prototype = Object.create(t.prototype), (e.prototype.constructor = e).__proto__ = t
}! function() {
    var e = function(o) {
        function e() {
            for (var e, t = arguments.length, i = new Array(t), n = 0; n < t; n++) i[n] = arguments[n];
            return (e = o.call.apply(o, [this].concat(i)) || this).is = "form", e.ownerId = e.owner.getAttribute("data-id"), e.eventId = "." + e.ownerId, e.$form = e.$component.find("form"), e.action = e.$form.find('input[name="action"]').val(), e
        }
        _inheritsLoose(e, o);
        var t = e.prototype;
        return t.onView = function(e) {
            if (!e.state || this.isViewed || this.isBesided) return !1;
            this.formInit()
        }, t.onBeside = function(e) {
            if (!e.state || this.isViewed || this.isBesided) return !1;
            this.formInit()
        }, t.formInit = function() {
            if (this.formInited) return !1;
            this.formInited = !0, this.unbindEvents(), this.bindEvents(), this.customize()
        }, t.customize = function() {
            this.fileInput(), this.customSelect(), this.enterSubmit(), this.clearError(), this.textResize()
        }, t.unbindEvents = function() {
            this.$component.off(this.eventId), this.$form.off(this.eventId)
        }, t.bindEvents = function() {
            var e = this;
            this.$form.find('input[name="p_id"]').val(flexbe_cli.p_id), this.$form.on("click" + this.eventId, ".form_field_submit, .form-submit, .quiz-submit", function() {
                e.$form.submit()
            }), this.$form.on("submit" + this.eventId, function() {
                if (e.busy || !e.validation()) return !1;
                if (e.busy = !0, flexbe_cli.stat.u_id && e.addFields([{
                        name: "f_uid",
                        type: "hidden",
                        value: flexbe_cli.stat.u_id
                    }], !1), "function" == typeof e.beforeSend && !1 === e.beforeSend()) return e.busy = !0, !1;
                if (e.addFields([{
                        name: "jsform",
                        type: "hidden",
                        value: parseInt(448312, 10)
                    }], !1), "undefined" != typeof FormData) e.sendFormdata();
                else {
                    if (!(e.$form.find('input[type="file"]').length < 1)) return !0;
                    e.sendAjax()
                }
                return !1
            })
        }, t.setData = function(e) {
            if (!e) return !1;
            e && e.fields && this.addFields(e.fields), e && e.items && this.addItems(e.items)
        }, t.onBeforeSend = function(e) {
            "function" == typeof e && (this.beforeSend = e)
        }, t.onAfterSent = function(e) {
            "function" == typeof e && (this.afterSent = e)
        }, t.addFields = function(e, t) {
            void 0 === t && (t = !0);
            var i = this.$form.find(".form_fields_advanced");
            e.length && i[0] && (t && i.empty(), e.forEach(function(e) {
                i.find('input[name="' + e.name + '"]').remove();
                var t = $("<input>").attr("type", e.type).attr("name", e.name).attr("value", e.value);
                i.append(t)
            }))
        }, t.addItems = function(e) {
            if (void 0 === e && (e = []), e && e.length) {
                var t = 0,
                    i = 0,
                    n = "",
                    o = [];
                e = e.map(function(e) {
                    return "object" !== _typeof(e) ? {} : (e.count = parseInt(e.count, 10) || 1, e.price = parseFloat(e.price) || 0, e.title = "string" == typeof e.title && e.title.trim() || e.title || "", t += e.price * e.count || 0, i += e.count, e)
                });
                try {
                    n = JSON.stringify(e)
                } catch (e) {}
                o.push({
                    type: "hidden",
                    name: "product[items]",
                    value: n
                }), o.push({
                    type: "hidden",
                    name: "product[price]",
                    value: t
                }), o.push({
                    type: "hidden",
                    name: "product[total]",
                    value: i
                }), t && (o.push({
                    type: "hidden",
                    name: "pay[price]",
                    value: t
                }), o.push({
                    type: "hidden",
                    name: "pay[desc]",
                    value: 1 < i ? "Товаров в корзине: " + i : e[0].title
                })), this.addFields(o)
            }
        }, t.sendFormdata = function() {
            var t = this,
                e = new FormData(this.$form.get(0));
            if (e.append("is_ajax", "true"), "undefined" != typeof flexbeAPI && void 0 !== flexbeAPI.customLeadData && e.append("customLeadData", JSON.stringify(flexbeAPI.customLeadData)), flexbe_cli.run.is_OSX && "function" == typeof e.entries) {
                var i = e.entries(),
                    n = Array.isArray(i),
                    o = 0;
                for (i = n ? i : i[Symbol.iterator]();;) {
                    var a;
                    if (n) {
                        if (o >= i.length) break;
                        a = i[o++]
                    } else {
                        if ((o = i.next()).done) break;
                        a = o.value
                    }
                    var r = a,
                        s = r[0],
                        l = r[1];
                    "object" === _typeof(l) && l instanceof File && 0 === l.size && e.delete(s)
                }
            }
            e.append("f_ab", JSON.stringify(flexbe_cli.stat.AB.getcookie())), this.$component.addClass("submitting"), $.ajax({
                url: this.$form.attr("action"),
                type: "POST",
                dataType: "json",
                processData: !1,
                contentType: !1,
                data: e,
                xhr: function() {
                    var e = $.ajaxSettings.xhr();
                    return e.upload, e
                }
            }).done(function(e) {
                setTimeout(function() {
                    t.$component.addClass("submit-ok step-1"), setTimeout(function() {
                        t.$component.addClass("submit-ok step-2"), setTimeout(function() {
                            t.$component.addClass("submit-ok step-3"), setTimeout(function() {
                                t.$component.removeClass("submitting submit-ok step-1 step-2 step-3"), t.$component.find(".form_field_file_holder").removeClass("active"), t.$form.get(0).reset(), e.send_formdata = !0, void 0 !== e.pay && (t.pay = e.pay), t.showDone()
                            }, 1e3)
                        }, 300)
                    }, 400)
                }, 500)
            }).fail(function(e) {
                console.error("sendFormdata: Ошибка при отправке формы", e), t.busy = !1, t.$component.removeClass("submitting")
            })
        }, t.sendAjax = function() {
            var t = this,
                e = this.$form.serialize();
            $.ajax({
                url: this.$form.attr("action"),
                type: "POST",
                dataType: "json",
                data: e + "&is_ajax=true"
            }).done(function(e) {
                t.$form.get(0).reset(), e.send_ajax = !0, void 0 !== e.pay && (t.pay = e.pay), t.showDone()
            }).fail(function(e) {
                console.error("sendAjax: Ошибка при отправке формы", e), t.busy = !1, t.$component.removeClass("submitting")
            })
        }, t.validation = function() {
            var l = !0;
            return this.$form.find("[data-type]").each(function(e, t) {
                var i, n, o = $(t),
                    a = o.attr("data-type"),
                    r = o.attr("data-is-required"),
                    s = isNaN(1 * r) ? "true" == r : Boolean(1 * r);
                o.removeClass("is_error"), i = o.find("input,textarea,select").not('[type="hidden"]'), -1 != $.inArray(a, ["text", "textarea", "email", "phone", "name"]) ? n = $.trim(i.val()) : "file" == a ? n = i.get(0).files : "checkbox" === a && (n = i.prop("checked"));
                try {
                    if (s && void 0 !== n && n.length < 1) throw flexbe_cli.lang.get("form.required");
                    if (s && "checkbox" === a && !n) throw flexbe_cli.lang.get("form.required");
                    if ("email" == a && "email" == i.attr("data-check") && 0 < n.length) {
                        if (!/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Zа-яёА-ЯЁ\-0-9]+\.)+[a-zA-Zа-яёА-ЯЁ]{2,}))$/.test(n)) throw flexbe_cli.lang.get("form.email")
                    }
                    if ("phone" == a && "phone" == i.attr("data-check") && 0 < n.length) {
                        if (/[^0-9+\(\)\-\s]/.test(n)) throw flexbe_cli.lang.get("form.digits");
                        if (n.replace(/[^0-9]/g, "").length < 5) throw flexbe_cli.lang.get("form.minlength")
                    }
                } catch (e) {
                    o.addClass("is_error"), "checkbox" === a ? o.find(".checkbox, .check-box").attr("title", e) : o.find(".error").attr("title", e).html('<svg viewBox="0 0 2 8"><path d="M0 1c0-.552.444-1 1-1 .552 0 1 .444 1 1v3c0 .552-.444 1-1 1-.552 0-1-.444-1-1V1zm1 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" fill="currentColor" fill-rule="evenodd"/></svg>'), l = !1
                }
            }), l
        }, t.showDone = function() {
            "function" == typeof this.afterSent && this.afterSent(), this.busy = !1, flexbe_cli.stat.reach_goal("order_done");
            try {
                var e = this.$form.find('input[name="goal"]').val();
                void 0 !== e && "" !== e && flexbe_cli.stat.reach_goal(e)
            } catch (e) {}
            try {
                var t = this.$form.find('textarea[name="goal_html"]').val();
                if ("string" == typeof t && t.trim()) {
                    var i = document.write;
                    document.write = function(e) {
                        $("body").eq(0).append(e)
                    }, $("body").eq(0).append('<div style="display:none">' + t + "</div>"), setTimeout(function() {
                        document.write = i
                    }, 1e4)
                }
            } catch (e) {}
            if ("pay" === this.action && void 0 !== this.pay && null !== this.pay) {
                if (0 < this.pay.pay_link.length) {
                    var n = window.location.origin + window.location.pathname + (window.location.search ? window.location.search + "&" : "?") + "pay_id=" + this.pay.pay_id + "&h=" + this.pay.pay_hash;
                    try {
                        history.pushState(null, null, n), setTimeout(function() {
                            flexbe_cli.events.emit("pay", {
                                action: "init"
                            })
                        }, 200)
                    } catch (e) {
                        setTimeout(function() {
                            document.location = n
                        }, 500)
                    }
                }
            } else if ("redirect" === this.action) {
                var o = this.$form.find('input[name="action_redirect"]').val();
                0 < o.length && setTimeout(function() {
                    flexbe_cli.modal.close(), document.location = o
                }, 500)
            } else {
                var a = this.$component.find("[data-modal-id]").attr("data-modal-id");
                if (!flexbe_cli.modal.find(a)) {
                    var r = String(this.ownerId);
                    a = (r && r.split("_")[0]) + "_" + a
                }
                flexbe_cli.events.emit("modal_command", {
                    command: "open",
                    id: a
                })
            }
        }, t.fileInput = function() {
            this.$component.on("change" + this.eventId, ".file-input", function(e) {
                var t = $(e.currentTarget),
                    i = t.parents(".form_field_file_holder"),
                    n = t.val(),
                    o = e.currentTarget.files.length;
                i.addClass("active"), t.parents(".form_field").removeClass("is_error"), 1 === o && i.find(".files_name_holder_text").text(n), 1 < o && i.find(".files_name_holder_text").text(o + " файлов"), 0 === o && i.removeClass("active")
            }), this.$component.on("click" + this.eventId, ".clear_files", function(e) {
                var t = $(e.currentTarget).parents(".form_field_file_holder");
                t.find(".file-input").val(""), t.removeClass("active")
            })
        }, t.enterSubmit = function() {
            flexbe_cli.is_admin || $(window).on("keyup" + this.eventId, function(e) {
                if (13 == e.which) {
                    var t = $(".m_modal.show").eq(0);
                    if (t.find(".form_field_textarea").is(":focus")) return !1;
                    t.find("form").submit()
                }
            })
        }, t.clearError = function() {
            this.$component.on("click" + this.eventId, ".form_field", function(e) {
                $(e.currentTarget).removeClass("is_error").find("input, textarea").focus()
            })
        }, t.textResize = function() {
            this.$component.find(".autosize").each(function(e, t) {
                var i = t.offsetHeight - t.clientHeight,
                    n = $(t);
                n.removeAttr("data-autoresize"), n.off("keyup input").on("keyup input", function(e) {
                    e.currentTarget.style.height = e.currentTarget.scrollHeight + i + "px"
                })
            })
        }, t.customSelect = function() {
            this.$component.find(".dropdown-container").remove(), this.$component.find("select.custom-select").each(function(e, t) {
                var n = $(t),
                    i = n.children("optgroup"),
                    o = n.closest('[data-type="select"]'),
                    r = "",
                    s = "";
                i.length && i.each(function(e, t) {
                    var i = $(t),
                        n = i.attr("label");
                    s += '<li class="optgroup">' + n + "</li>", i.children("option").each(function(e, t) {
                        var i = $(t),
                            n = i.attr("value").replace(/"/g, "&quot;"),
                            o = i.text() || "—",
                            a = i.attr("selected");
                        s += "selected" === a ? '<li class="selected" data-value="' + n + '">' + (r = o) + "</li>" : '<li data-value="' + n + '">' + o + "</li>"
                    })
                }), n.children("option").each(function(e, t) {
                    var i = $(t),
                        n = i.val().replace(/"/g, "&quot;"),
                        o = i.text() || "—",
                        a = i.attr("selected");
                    s += "selected" === a ? '<li class="selected" data-value="' + n + '">' + (r = o) + "</li>" : '<li data-value="' + n + '">' + o + "</li>"
                });
                var a = $('<div class="dropdown-container">\n                        <div class="dropdown-select">\n                            <span>' + r + '</span>\n                        </div>\n                        <ul class="dropdown-select-ul">' + s + "</ul>\n                    </div>");
                n.after(a), a.off("click.selectdd").on("click.selectdd", ".dropdown-select", function() {
                    var e = a.hasClass("active");
                    a.toggleClass("active", !e), o.toggleClass("active", !e), e || o.siblings('[data-type="select"]').removeClass("active").find(".dropdown-container").removeClass("active")
                }), a.off("click.selectli").on("click.selectli", ".dropdown-select-ul li", function(e) {
                    var t = $(e.currentTarget);
                    if (!t.hasClass("optgroup")) {
                        var i = t.closest("ul").siblings(".dropdown-select");
                        a.toggleClass("active"), a.closest('[data-type="select"]').toggleClass("active"), t.siblings("li").removeClass("selected"), t.addClass("selected"), n.val(t.attr("data-value")), i.children("span").html(t.text())
                    }
                })
            })
        }, e
    }(BaseComponent);
    flexbe_cli.components.classes.form = e
}();

function _typeof(e) {
    return (_typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
        return typeof e
    } : function(e) {
        return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
    })(e)
}

function _inheritsLoose(e, t) {
    e.prototype = Object.create(t.prototype), (e.prototype.constructor = e).__proto__ = t
}! function() {
    var e = function(s) {
        function e() {
            for (var e, t = arguments.length, i = new Array(t), a = 0; a < t; a++) i[a] = arguments[a];
            e = s.call.apply(s, [this].concat(i)) || this;
            var n = i[0].core;
            return e.is = "quiz", e.$component = $(i[0].component), e.id = n.id, e.eventId = "." + n.id, e.$quiz = null, e.current = 0, e.total = 0, e.bind(n.$area), e
        }
        _inheritsLoose(e, s);
        var t = e.prototype;
        return t.onView = function(e) {
            if (!e.state || this.isViewed || this.isBesided) return !1;
            this.init()
        }, t.onLoaded = function(e) {
            e.state && this.init()
        }, t.onBeside = function(e) {
            if (!e.state || this.isViewed || this.isBesided) return !1;
            this.init()
        }, t.bind = function(e) {
            this.$block = e, this.$container = e.find(".quiz-container"), this.$quiz = e.find(".component-quiz"), this.$form = this.$quiz.find("form"), this.$steps = this.$quiz.find(".quiz-steps"), this.$progress = this.$quiz.find('[data-quiz-part="progress"]'), this.$progress[0] || (this.$progress = e.find('[data-quiz-part="progress"]')), this.current = parseInt(this.$quiz.attr("data-active-step"), 10) || 0, this.total = parseInt(this.$quiz.attr("data-total-steps"), 10), this.hasWellcome = !!parseInt(this.$quiz.attr("data-wellcome"), 10), this.hasDone = !!parseInt(this.$quiz.attr("data-done"), 10), this.init()
        }, t.init = function() {
            this.control(), this.formInit(), this.bindEvents(), this.toStep(this.$block.data("quiz-step") || 0, !0)
        }, t.bindEvents = function() {
            flexbe_cli.events.off("quiz_command").on("quiz_command", function(e, t) {
                var i;
                flexbe_cli.components.instances[t.id] && flexbe_cli.components.instances[t.id].forEach(function(e) {
                    "quiz" === e.is && (i = e)
                }), i && ("start" === t.command ? i.toStep(1) : "step" === t.command && i.toStep(t.step))
            })
        }, t.control = function() {
            var a = this;
            if (!flexbe_cli.is_admin && (this.$quiz.off("keydown" + this.eventId).on("keydown" + this.eventId, function(e) {
                    var t = !!a.$component.find('[data-step-id="' + a.current + '"] select, [data-step-id="' + a.current + '"] textarea').length,
                        i = a.$component.find('[data-step-id="' + (a.current + 1) + '"]');
                    if (-1 !== [9, 13].indexOf(e.which) && !t) return i.find('.field[data-type="radio"],\n                        .field[data-type="checkbox"],\n                        .field[data-type="select"],\n                        .field[data-type="textarea"],\n                        .field[data-type="text"],\n                        .field[data-type="email"],\n                        .field[data-type="phone"],\n                        .field[data-type="name"],\n                        .field[data-type="file"]')[0] ? (a.toStep(a.current + 1), setTimeout(function() {
                        i.find("input, textarea, select")[0].focus()
                    }, 100)) : (a.toStep(a.current + 1), i.find("input, textarea, select")[0].focus()), e.preventDefault(), !1
                }), this.$quiz.off("click").on("click", "[data-quiz-action]", function(e) {
                    var t = $(e.currentTarget).data("quizAction"),
                        i = a.current;
                    "wellcome" === t ? i = 1 : "next" === t ? i += 1 : "prev" === t ? i -= 1 : "send" === t && (i = a.total + 1), a.toStep(i)
                }), (flexbe_cli.run.is_screen_mobile || flexbe_cli.run.is_screen_tablet_s) && this.$quiz.find(".form-field-image-group").on("change", "input", function(e) {
                    var t = $(e.currentTarget).closest(".form-field-image-item"),
                        i = t.closest(".form-field-image-group"),
                        a = t[0].offsetLeft,
                        n = i.innerWidth(),
                        s = t.outerWidth(!0);
                    i.animate({
                        scrollLeft: a - (n - s) / 2
                    })
                }), flexbe_cli.run.is_ios)) {
                var o = !1,
                    r = !1,
                    l = !1;
                this.$quiz.on("touchstart", ".form-field-image-group", function(e) {
                    var t = e.touches[0];
                    o = !!t && t.screenX, r = !!t && t.screenY, e.currentTarget.focus()
                }), this.$quiz.on("touchmove", ".form-field-image-group", function(e) {
                    var t = e.touches[0],
                        i = !!t && t.screenX,
                        a = !!t && t.screenY,
                        n = Math.abs(o - i),
                        s = Math.abs(r - a);
                    10 < n && s < n && (l = !0), l && (e.currentTarget.focus(), n < s && e.preventDefault(), o = i, r = a)
                }), this.$quiz.on("touchend", ".form-field-image-group", function() {
                    l = r = o = !1
                })
            }
        }, t.toStep = function(e, t) {
            var s = this;
            if (flexbe_cli.is_admin && e > this.total && (e = this.total), void 0 === e || e < (this.hasWellcome ? 0 : 1)) e = this.hasWellcome ? 0 : 1;
            else {
                if (!flexbe_cli.is_admin && e > this.current && !this.validateStep()) return !1;
                if (e > this.total) return this.$form.submit(), !0
            }
            var i = this.$block.find(".b_head").length,
                a = this.hasWellcome && 0 === e,
                n = "done" === e,
                o = n ? 100 : parseInt(100 * e / this.total, 10),
                r = this.$progress.find(".progress-loader-bar .current"),
                l = r.data("unit");
            if (t && this.$block.addClass("noanimate"), this.current = e, this.$block.data("quiz-step", e), this.$quiz.attr("data-active-step", e), this.$container.attr("data-active-step", e), this.$progress.attr("data-current", e), this.$progress.attr("data-current-progress", o), r && l) {
                var c = o + "%";
                "step" === l ? c = e + " / " + this.total : "none" === l && (c = ""), r.attr("data-value", c)
            }
            if (this.$container.toggleClass("quiz-state-wellcome", a), this.$container.toggleClass("quiz-state-done", n), this.$container.toggleClass("quiz-state-started", !a && !n), a || n) {
                if (this.$container.length) {
                    var f = this.$container.find('[data-quiz-part="' + (a ? "wellcome" : "done") + '"]').outerHeight() || 0;
                    this.$container.css("minHeight", f), flexbe_cli.run.is_ie && flexbe_cli.block.fixCoverHeight(this.$block)
                }
            } else {
                var d = this.$steps.find('[data-step-id="' + e + '"]'),
                    u = d.outerHeight(),
                    h = Boolean(d.find(".form-field-text-input").length);
                if (d.prevAll().removeClass("active next").addClass("prev"), d.nextAll().removeClass("active prev").addClass("next"), d.removeClass("next prev").addClass("active"), !flexbe_cli.is_admin) {
                    var p = d.find(".form-field-image-group")[0];
                    p || (p = d.find(".form-field-text-input").find("input, textarea")[0]), p && (p.focus(), setTimeout(function() {
                        p.focus()
                    }, 30))
                }
                this.$progress.find(".progress-text .current").text(e), this.$progress.find(".progress-percent .current").text(o), this.$progress.find(".progress-loader-bar .current").css("width", o + "%"), this.$progress.find(".progress-loader-circle .current").attr("stroke-dashoffset", "" + Math.abs(100 - o)), this.$container.css("minHeight", u + "px"), flexbe_cli.run.is_ie && this.$block.find(".cover").css("height", "auto"), i && flexbe_cli.block.setHeaders(), setTimeout(function() {
                    if (!(flexbe_cli.is_admin || flexbe_cli.run.is_mobile && h)) {
                        var e = s.$block[0]._core,
                            t = window.pageYOffset,
                            i = s.$quiz.offset().top,
                            a = i + u,
                            n = !1;
                        e && (Math.abs(flexbe_cli.resize.height - e.tween.height) < 5 ? n = e.tween.start : flexbe_cli.resize.height > e.tween.height && (e.tween.start < t || e.tween.end > t + flexbe_cli.resize.height) ? n = e.tween.start - e.tween.fix / 2 : (i < t || a > t + flexbe_cli.resize.height) && (n = flexbe_cli.resize.height > u ? i - (flexbe_cli.resize.height - u) / 2 : i), "number" == typeof n && $("body, html").animate({
                            scrollTop: n
                        }))
                    }
                }, 350)
            }
            return t && setTimeout(function() {
                s.$block.removeClass("noanimate")
            }, 150), flexbe_cli.events.emit("quiz_event", {
                event: "to_step",
                sender: "core",
                quiz: this
            }), !0
        }, t.validateStep = function(e) {
            e || (e = this.current);
            var c = !0;
            return this.$steps.find('[data-step-id="' + e + '"]').find("[data-type]").each(function(t, e) {
                var i, a = $(e),
                    n = a.attr("data-type"),
                    s = a.attr("data-is-required"),
                    o = Number.isNaN(1 * s) ? "true" === s : Boolean(1 * s);
                a.removeClass("is_error");
                var r = a.find("input, textarea, select").not('[type="hidden"]');
                0 <= ["text", "textarea", "email", "phone", "name"].indexOf(n) ? i = r.val() : 0 <= ["checkbox", "radio", "image"].indexOf(n) ? i = !!r.closest(":checked").length : "file" === n && (i = r.get(0).files);
                try {
                    if (o && void 0 !== i && (!1 === i || 0 === i.length)) throw flexbe_cli.lang.get("form.required");
                    if ("email" == n && "email" == r.attr("data-check") && 0 < i.length) {
                        if (!/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Zа-яёА-ЯЁ\-0-9]+\.)+[a-zA-Zа-яёА-ЯЁ]{2,}))$/.test(i)) throw flexbe_cli.lang.get("form.email")
                    }
                    if ("phone" == n && "phone" == r.attr("data-check") && 0 < i.length) {
                        if (/[^0-9+\(\)\-\s]/.test(i)) throw flexbe_cli.lang.get("form.digits");
                        if (i.replace(/[^0-9]/g, "").length < 5) throw flexbe_cli.lang.get("form.minlength")
                    }
                } catch (e) {
                    var l = a.offset().top;
                    (0 === t && l < window.pageYOffset || l > window.pageYOffset + flexbe_cli.resize.height) && $("body, html").animate({
                        scrollTop: l - 200 + "px"
                    }, 300), a.addClass("is_error"), a.find(".error").text(e), a.find("input, textarea").focus(), c = !1
                }
            }), c
        }, t.fileInput = function() {
            this.$quiz.on("change", ".file-input", function(e) {
                var t = $(e.currentTarget),
                    i = t.parents(".form-field-file"),
                    a = i.find(".form-field-file-files"),
                    n = t.val(),
                    s = e.currentTarget.files.length;
                i.addClass("active"), t.parents(".field").removeClass("is_error"), 0 === s ? i.removeClass("active") : 1 === s ? a.find(".text").text(n) : 1 < s && a.find(".text").text(s + " " + (1 < s && s < 5 ? "файла" : "файлов"))
            }), this.$quiz.on("click", ".form-field-file-files .clear-files", function(e) {
                var t = $(e.currentTarget).parents(".form-field-file");
                t.find(".file-input").val(""), t.removeClass("active")
            })
        }, t.customSelect = function() {
            var d = this;
            this.$quiz.find(".dropdown-container").remove(), this.$quiz.find("select").each(function(e, t) {
                var a = $(t),
                    i = a.children("optgroup"),
                    r = a.closest('[data-type="select"]'),
                    o = "",
                    l = "";
                i.length && i.each(function(e, t) {
                    var i = $(t),
                        a = i.attr("label");
                    l += '<li class="optgroup">' + a + "</li>", i.children("option").each(function(e, t) {
                        var i = $(t),
                            a = i.attr("value").replace(/"/g, "&quot;"),
                            n = i.text() || "—",
                            s = i.attr("selected");
                        l += "selected" === s ? '<li class="selected" data-value="' + a + '">' + (o = n) + "</li>" : '<li data-value="' + a + '">' + n + "</li>"
                    })
                }), a.children("option").each(function(e, t) {
                    var i = $(t),
                        a = i.val().replace(/"/g, "&quot;"),
                        n = i.text() || "—",
                        s = i.attr("selected");
                    l += "selected" === s ? '<li class="selected" data-value="' + a + '">' + (o = n) + "</li>" : '<li data-value="' + a + '">' + n + "</li>"
                });
                var c = $('<div class="dropdown-container">\n                    <div class="dropdown-select">\n                        <span>' + o + '</span>\n                        <i></i>\n                    </div>\n                    <ul class="dropdown-select-ul">' + l + "</ul>\n                </div>"),
                    f = c.find(".dropdown-select-ul");
                a.after(c), c.off("click.selectdd").on("click.selectdd", ".dropdown-select", function() {
                    var e = d.$quiz.closest(".b_block"),
                        t = e[0]._core,
                        i = e.outerHeight() - 60,
                        a = f.outerHeight(),
                        n = Math.min(i, a),
                        s = c.offset().top,
                        o = t.tween.end - 30;
                    o < s + n && f.css({
                        top: o - (s + n) + "px",
                        maxHeight: n + "px"
                    }), setTimeout(function() {
                        var e = !c.hasClass("active");
                        c.toggleClass("active", e), r.toggleClass("active", e), e && $("body").on("click.quiz-select-element", function(e) {
                            $(e.target).is(c) || $(e.target).closest(c).length || (c.removeClass("active"), r.removeClass("active"), $("body").off("click.quiz-select-element"))
                        })
                    }, 50)
                }), c.off("click.selectli").on("click.selectli", ".dropdown-select-ul li", function(e) {
                    var t = $(e.currentTarget);
                    if (!t.hasClass("optgroup")) {
                        var i = t.closest("ul").siblings(".dropdown-select");
                        c.removeClass("active"), r.removeClass("active"), t.siblings("li").removeClass("selected"), t.addClass("selected"), a.val(t.attr("data-value")), i.children("span").html(t.text())
                    }
                })
            })
        }, t.textareaAutoresize = function() {
            this.$quiz.find("[data-autoresize]").each(function(e, t) {
                var i = t.offsetHeight - t.clientHeight,
                    a = $(t);
                a.removeAttr("data-autoresize"), a.off("keyup input").on("keyup input", function(e) {
                    e.currentTarget.style.height = e.currentTarget.scrollHeight + i + "px"
                })
            })
        }, t.formInit = function() {
            var e = this;
            this.customSelect(), this.fileInput(), this.textareaAutoresize(), this.$quiz.off("click.clear_error").on("click.clear_error", ".field", function(e) {
                $(e.currentTarget).removeClass("is_error").find("input, textarea").focus()
            }), this.$form.off("submit" + this.eventId).on("submit" + this.eventId, function() {
                return !e.busy && !flexbe_cli.is_admin && (e.busy = !0, flexbe_cli.stat.u_id && e.assFields([{
                    name: "f_uid",
                    type: "hidden",
                    value: flexbe_cli.stat.u_id
                }], !1), e.assFields([{
                    name: "jsform",
                    type: "hidden",
                    value: parseInt(448312, 10)
                }], !1), "undefined" != typeof FormData ? (e.sendFormdata(), !1) : !(e.$form.find('input[type="file"]').length < 1) || (e.sendAjax(), !1))
            })
        }, t.sendFormdata = function() {
            var t = this,
                e = new FormData(this.$form.get(0));
            if (e.append("is_ajax", "true"), "undefined" != typeof flexbeAPI && void 0 !== flexbeAPI.customLeadData && e.append("customLeadData", JSON.stringify(flexbeAPI.customLeadData)), e.append("f_ab", JSON.stringify(flexbe_cli.stat.AB.getcookie())), flexbe_cli.run.is_OSX && "function" == typeof e.entries) {
                var i = e.entries(),
                    a = Array.isArray(i),
                    n = 0;
                for (i = a ? i : i[Symbol.iterator]();;) {
                    var s;
                    if (a) {
                        if (n >= i.length) break;
                        s = i[n++]
                    } else {
                        if ((n = i.next()).done) break;
                        s = n.value
                    }
                    var o = s,
                        r = o[0],
                        l = o[1];
                    "object" === _typeof(l) && l instanceof File && 0 === l.size && e.delete(r)
                }
            }
            this.$form.addClass("submitting"), $.ajax({
                url: this.$form.attr("action"),
                type: "POST",
                dataType: "json",
                processData: !1,
                contentType: !1,
                data: e,
                xhr: function() {
                    var e = $.ajaxSettings.xhr();
                    return e.upload, e
                }
            }).done(function(e) {
                t.$form.removeClass("submitting"), t.$form.find(".form-field-file-holder").removeClass("active"), e.send_formdata = !0, void 0 !== e.pay && (t.pay = e.pay), t.afterSend()
            }).fail(function() {
                t.$form.removeClass("submitting"), t.busy = !1
            })
        }, t.sendAjax = function() {
            var t = this;
            this.$form.addClass("submitting");
            var e = this.$form.serialize();
            $.ajax({
                url: this.$form.attr("action"),
                type: "POST",
                dataType: "json",
                data: e + "&is_ajax=true"
            }).done(function(e) {
                t.$form.removeClass("submitting"), t.$form.find(".form-field-file-holder").removeClass("active"), e.send_ajax = !0, void 0 !== e.pay && (t.pay = e.pay), t.afterSend()
            }).fail(function() {
                t.$form.removeClass("submitting")
            })
        }, t.afterSend = function() {
            var e = this,
                t = this.$form.find('input[name="action"]').val();
            flexbe_cli.stat.reach_goal("order_done");
            try {
                var i = this.$form.find('input[name="goal"]').val();
                void 0 !== i && "" !== i && flexbe_cli.stat.reach_goal(i)
            } catch (e) {}
            try {
                var a = this.$form.find('textarea[name="goal_html"]').val();
                if ("string" == typeof a && a.trim()) {
                    var n = document.write;
                    document.write = function(e) {
                        $("body").eq(0).append(e)
                    }, $("body").eq(0).append('<div style="display:none">' + a + "</div>"), setTimeout(function() {
                        document.write = n
                    }, 1e4)
                }
            } catch (e) {}
            if ("quiz_done" === t && this.hasDone) this.busy = !1, this.toStep("done");
            else if ("pay" === t && void 0 !== this.pay && null !== this.pay && 0 < this.pay.pay_link.length) {
                var s = window.location.origin + window.location.pathname + (window.location.search ? window.location.search + "&" : "?") + "pay_id=" + this.pay.pay_id + "&h=" + this.pay.pay_hash;
                try {
                    window.history.pushState(null, null, s), setTimeout(function() {
                        flexbe_cli.events.emit("pay", {
                            action: "init"
                        })
                    }, 200)
                } catch (e) {
                    setTimeout(function() {
                        document.location = s
                    }, 500)
                }
                this.busy = !1, this.toStep(0), this.$form.get(0).reset()
            } else if ("redirect" === t) {
                var o = this.$form.find('input[name="action_redirect"]').val();
                0 < o.length && setTimeout(function() {
                    document.location = o
                }, 500)
            } else {
                var r = this.$form.find(".quiz-submit").attr("data-modal-id");
                if (!flexbe_cli.modal.find(r)) {
                    var l = this.$quiz.closest("[data-id]").attr("data-id");
                    r = (l && l.split("_")[0]) + "_" + r
                }
                flexbe_cli.modal.open(r), setTimeout(function() {
                    e.toStep(0), e.$form.get(0).reset(), e.busy = !1
                }, 150)
            }
        }, t.assFields = function(e, t) {
            void 0 === t && (t = !0);
            var i = this.$quiz.find(".form-fields-advanced");
            e.length && i[0] && (t && i.empty(), e.forEach(function(e) {
                i.find('input[name="' + e.name + '"]').remove();
                var t = $("<input>").attr("type", e.type).attr("name", e.name).attr("value", e.value);
                i.append(t)
            }))
        }, e
    }(BaseComponent);
    flexbe_cli.components.classes.quiz = e
}();

function _typeof(e) {
    return (_typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
        return typeof e
    } : function(e) {
        return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
    })(e)
}! function() {
    var r = {};
    flexbe_cli.block = {
        init: function() {
            var t = this;
            this.$list = $(".container-list, header, footer"), this.$list.find(".b_block").toArray().forEach(function(e) {
                return t.bind(e, {}, "init")
            }), this.dispathEvents()
        },
        dispathEvents: function() {
            var i, n = this;
            flexbe_cli.events.on("block_render.flexbe_block block_change.flexbe_block", function(e, t) {
                if (t && t.id && (/render/.test(e.type) || t.name && !t.templateRendered && !t.styleRendered)) {
                    var i = $('[data-id="' + t.id + '"]', n.$list)[0];
                    n.bind(i, t, "render")
                }
            }), flexbe_cli.events.on("client_msg.flexbe_block", function(e, t) {
                if (t && t.id && "block" === t.is) {
                    var i = $('[data-id="' + t.id + '"]', n.$list)[0];
                    n.bind(i, t, "message")
                }
            }), $(window).on("resize orientationchange documentresize", $.debounce(this.updateTweens, 80, this)), flexbe_cli.events.off("layout_change.flexbe_block").on("layout_change.flexbe_block", function(e, t) {
                t && "block" !== !t.is && (clearTimeout(i), i = setTimeout(function() {
                    n.updateTweens()
                }, 100))
            }), $(window).on("load.flexbe_block", function() {
                if (/^#/.test(location.hash)) {
                    var e = String(location.hash).replace(/^#{1,2}/, ""),
                        t = n.$list.find(".b_" + e + ', .b_block ._anchor[name="' + e + '"]').eq(0);
                    t && n.scrollTo(t.closest(".b_block"))
                }
            })
        },
        updateTweens: function() {
            this.$list.find("[data-b-id]").each(function(e, t) {
                t._core && t._core._tween()
            })
        },
        scrollTo: function(e, t, i) {
            if (void 0 === t && (t = !0), void 0 === i && (i = !1), e = ("object" !== _typeof(e) ? $('.b_block[data-id="' + e + '"]', this.$list).eq(0) : $(e))[0]) {
                var n, o = e,
                    a = o.offsetTop,
                    r = o.offsetHeight,
                    d = flexbe_cli.resize.height - r;
                t && r < flexbe_cli.resize.height ? (n = a - d / 2, d < 180 && flexbe_cli.run.is_screen_pc && $('[data-b-type*="header"][data-b-type*="floating"]').length && (n -= 80)) : n = i && r > flexbe_cli.resize.height ? a + (r - flexbe_cli.resize.height) / 2 : a, $("body, html").animate({
                    scrollTop: n
                })
            }
        },
        initHeaders: function() {
            var i = this;
            return 2 === flexbe_cli.theme_id && (this.setHeaders(), !!flexbe_cli.is_admin && (flexbe_cli.events.off("editor_change.core_headers").on("editor_change.core_headers", function(e, t) {
                t && t.reason && (t.entity && t.entity.hasType("allow_head") || "editor_settings" === t.reason && "overflow" === t.name) && i.setHeaders(0)
            }), void flexbe_cli.events.off("layout_change.core_headers").on("layout_change.core_headers", function(e, t) {
                i.setHeaders()
            })))
        },
        _setHeadersDebounce: 0,
        setHeaders: function(e) {
            var h = this;
            void 0 === e && (e = 30), clearTimeout(this._setHeadersDebounce), this._setHeadersDebounce = setTimeout(function() {
                var e = $("header"),
                    r = $(".container-list"),
                    s = '[data-b-type*="header"][data-b-type*="overflow"]',
                    c = '[data-b-type*="allow_head"]',
                    f = "b_head";
                if (e[0]) {
                    var t = r.find('[data-header-type="site"], [data-site-block-type="header"]');
                    t.removeClass(f).removeAttr("data-header-type"), e.prepend(t)
                }
                r.find("." + f).each(function(e, t) {
                    var i = $(t),
                        n = i.parents(".b_block"),
                        o = n.attr("data-owner"),
                        a = n.attr("data-id"),
                        r = o && o == a,
                        d = i.is(s),
                        l = n.is(c);
                    !r || !d || !l ? (i.removeClass(f).removeAttr("data-header-type"), i.parents(".b_block, .flexbe-block-replace-container").last().before(i)) : h.fixHeaderHeight(n, i)
                }), r.find("" + s).not("." + f).each(function(e, t) {
                    var i = $(t),
                        n = i.attr("data-owner");
                    if (!n) {
                        var o = i.nextAll(".b_block").eq(0);
                        o.is(c) && (n = o.attr("data-id"))
                    }
                    var a = r.find(".b_block[data-id=" + n + "]").filter(c).eq(0);
                    a.length && (a.prepend(t), i.addClass(f), h.fixHeaderHeight(a, i))
                }), e.find(s).each(function(e, t) {
                    var i = ["> .b_block:not([data-abtest-variant])", " > .b_block[data-abtest-active]", " > .flexbe-block-replace-container > .flexbe-block-replace-new"].join(", "),
                        n = $(t),
                        o = r.find(i).eq(0).filter(c);
                    o && o.length && !o.find("> ." + f).length && !o.prev().is('[data-b-type*="overflow"]') && (o.find("> ." + f).remove(), o.prepend(t), n.attr("data-header-type", "site").addClass(f), h.fixHeaderHeight(o, n))
                })
            }, e)
        },
        fixHeaderHeight: function(e, t) {
            var i = this;
            if (e[0] && t[0]) {
                var n = e.attr("data-id"),
                    o = e.find("> .container-fluid").eq(0).find("> .container").eq(0),
                    a = o.children().eq(0);
                o.css("paddingTop", "");
                var r = parseInt(o.css("paddingTop"), 10),
                    d = t.outerHeight(),
                    l = a.offset().top - r - o.offset().top,
                    s = d - l,
                    c = a.outerHeight(!0) - a.outerHeight();
                0 <= l && l < d && o.css("paddingTop", s + c - l + "px"), $(window).off("resized.fixHeaderHeight" + n).on("resized.fixHeaderHeight" + n, function() {
                    i.fixHeaderHeight(e, t)
                })
            }
        },
        fixCoverHeight: function(e, t) {
            var i = this,
                n = e.find(".cover"),
                o = n.length && e.prevAll(".b_block").eq(0).filter('[data-b-type*="header"]').not('[data-b-type*="overflow"]');
            if (n.length) {
                var a = e.attr("data-id"),
                    r = flexbe_cli.resize.height,
                    d = o.outerHeight() || 0;
                5 < d && (d -= 5);
                var l = r - d + 2;
                flexbe_cli.run.is_ie && l > n.outerHeight() && n.css("height", l + "px"), n.css("min-height", l + "px"), t || $(window).off("resized.fixCoverHeight" + a).on("resized.fixCoverHeight" + a, function() {
                    100 < Math.abs(flexbe_cli.resize.height - r) && (r = flexbe_cli.resize.height, i.fixCoverHeight(e, !0))
                })
            }
        },
        bind: function(e, t, i) {
            if (void 0 === t && (t = {}), !e) return !1;
            var n = e.getAttribute("data-b-id"),
                o = e._core;
            if (!o) {
                var a = r[n] || {};
                o = new BlockCore(e, a), e._core = o, ["init", "render"].includes(i) || o.init(t)
            }
            return ["init", "render"].includes(i) ? o.init(t) : "message" === i && o._onMsg(t.msg, t.data), {
                core: o
            }
        },
        register: function(e, t) {
            void 0 === t && (t = {}), e || console.warn("Element register error: Element must have templateId"), r[e] = t
        }
    }
}();
! function() {
    var l = {};
    flexbe_cli.modal = {
        opened: {},
        init: function() {
            var t = this;
            this.list = document.querySelector(".modal-list"), this.$list = $(this.list), this.$list.find(".m_modal").toArray().forEach(function(e) {
                return t.bind(e, {}, "init")
            });
            var e = getJsonFromUrl();
            if (e.service && e.m_id) return this.$list.addClass("noanimate"), this.open(e.m_id), !1;
            this.popstate(), this.dispathEvents()
        },
        dispathEvents: function() {
            var o = this;
            flexbe_cli.events.on("modal_render.flexbe_modal modal_change.flexbe_modal", function(e, t) {
                if (t && t.id && (/render/.test(e.type) || t.name && !t.templateRendered && !t.styleRendered)) {
                    var i = (o.find(t.id) || {}).modal;
                    o.bind(i, t, "render")
                }
            }), flexbe_cli.events.on("client_msg.flexbe_modal", function(e, t) {
                if (t && t.id && "modal" === t.is) {
                    var i = (o.find(t.id) || {}).modal;
                    o.bind(i, t, "message")
                }
            }), flexbe_cli.events.on("modal_command.flexbe_modal", function(e, t) {
                if (t) switch (t.command) {
                    case "open":
                        o.open(t.id, t.data);
                        break;
                    case "close":
                        o.close(t.id)
                }
            }), $("body").on("click.modal-close", ".m_modal .close", function(e) {
                e.preventDefault(), e.stopPropagation();
                var t = $(e.currentTarget).closest(".m_modal").data("id");
                o.close(t)
            }), flexbe_cli.is_admin || $("body").off("click.modal-close-overlay").on("click.modal-close-overlay", ".modal-data", function(e) {
                if (!flexbe_cli.run.is_mobile && Object.keys(o.opened).length && ($(e.target).is(".modal-data") || 3 === flexbe_cli.theme_id && $(e.target).is(".modal-data > .scroller"))) {
                    var t = $(".m_modal.show").eq(0).attr("data-id");
                    o.close(t)
                }
            }), flexbe_cli.is_admin || $(window).off("keyup.modal-close-esc").on("keyup.modal-close-esc", function(e) {
                if (27 !== e.keyCode) return !0;
                var t = $(".m_modal.show").eq(0).attr("data-id");
                o.close(t)
            })
        },
        popstate: function() {
            var i = this;
            if (flexbe_cli.is_admin || flexbe_cli.lockPopstate) return !1;
            setTimeout(function() {
                if (/^#{1,2}/.test(location.hash)) {
                    var e = location.hash.replace(/^#{1,2}/, ""),
                        t = i.$list.find('._anchor[name="' + e + '"], .m_modal[data-id="' + e + '"]').closest(".m_modal").attr("data-id");
                    i.opened[t] || i.open(t)
                } else i.close()
            }, 50)
        },
        find: function(e) {
            var t = this.$list.find('[data-id="' + e + '"]').toArray(),
                i = t[0] || !1;
            return !!i && {
                modals: t,
                modal: i
            }
        },
        open: function(e, t, i) {
            if (void 0 === t && (t = {}), void 0 === i && (i = {}), !e) return !1;
            var o = this.find(e) || {},
                a = o.modals,
                n = o.modal,
                l = $(a);
            if (1 < l.length) {
                var d = i.multivar;
                if (!d) {
                    var r = String(e).split("_")[0];
                    d = $('[data-id="' + r + '"]').attr("data-multivar")
                }
                var s = [];
                d && "default" !== d && (s = l.filter('[data-multivar="' + d + '"]').eq(0)), n = (l = s.length ? s.eq(0) : l.eq(0))[0]
            }
            var c = (n && this.bind(n) || {}).core;
            return c ? (this.close(null, e), c.open(t, i), this.opened[e] = c, !0) : (console.warn("Try to open modal without core object,", "id: " + e + ",", "modal: ", n), !1)
        },
        close: function(e, t) {
            var i = this;
            if (!e) return Object.keys(this.opened).map(function(e) {
                if (i.opened[e] && e != t) return i.close(e, t)
            });
            if (!this.opened[e]) return !1;
            delete this.opened[e];
            var o = (this.find(e) || {}).modal,
                a = (o && this.bind(o) || {}).core;
            return a ? (a.close({
                from: t
            }), !0) : (console.warn("Try to close modal without core object"), !1)
        },
        bind: function(e, t, i) {
            if (void 0 === t && (t = {}), !e) return !1;
            var o = e.getAttribute("data-m-id"),
                a = e._core;
            if (!a) {
                var n = l[o] || {};
                a = new ModalCore(e, n), e._core = a, ["init", "render"].includes(i) || a.init(t)
            }
            return ["init", "render"].includes(i) ? a.init(t) : "message" === i && a._onMsg(t.msg, t.data), {
                core: a
            }
        },
        register: function(e, t) {
            void 0 === t && (t = {}), e || console.warn("Element register error: Element must have templateId"), l[e] = t
        }
    }
}();
! function() {
    var s = {};
    flexbe_cli.widget = {
        init: function() {
            var i = this;
            this.$list = $(".widget-list"), this.list = this.$list.get(0), this.$list.find(".w_widget").toArray().forEach(function(e) {
                return i.bind(e, {}, "init")
            }), this.dispathEvents()
        },
        dispathEvents: function() {
            var n = this;
            flexbe_cli.events.on("widget_render.flexbe_widget widget_change.flexbe_widget", function(e, i) {
                if (i && i.id && (/render/.test(e.type) || i.name && !i.templateRendered && !i.styleRendered)) {
                    var t = $('[data-id="' + i.id + '"]', n.$list)[0];
                    n.bind(t, i, "render")
                }
            }), flexbe_cli.events.on("client_msg.flexbe_widget", function(e, i) {
                if (i && i.id && "widget" === i.is) {
                    var t = n.list.querySelector('[data-id="' + i.id + '"]');
                    n.bind(t, i, "message")
                }
            })
        },
        bind: function(e, i, t) {
            if (void 0 === i && (i = {}), !e) return !1;
            var n = e.getAttribute("data-w-id"),
                r = e._core;
            if (!r) {
                var d = s[n] || {};
                r = new WidgetCore(e, d), e._core = r, ["init", "render"].includes(t) || r.init(i)
            }
            return ["init", "render"].includes(t) ? r.init(i) : "message" === t && r._onMsg(i.msg, i.data), {
                core: r
            }
        },
        register: function(e, i) {
            void 0 === i && (i = {}), e || console.warn("Element register error: Element must have templateId"), s[e] = i
        }
    }
}();
flexbe_cli.menu = {
    anchors: [],
    floating: !1,
    offset: 0,
    init: function() {
        this.createMobileMenu(), this.floatingMenu()
    },
    floatingMenu: function() {
        if (!flexbe_cli.is_admin && !this.floating) {
            var i = [];
            if ((i = $("[data-floating=true]").eq(0))[0]) {
                var t, o = i.parents(".b_block").last()[0]._core,
                    a = i.outerHeight(),
                    l = parseInt(i.attr("data-floating-type"), 10) || 1;
                this.floating = o.id, i.siblings(".fixer").height(a), flexbe_cli.run.is_screen_mobile || flexbe_cli.run.is_screen_tablet_s ? (i = $(".mobile-menu .menu-burger"), l = 1) : i = i.add(".mobile-menu .menu-burger"),
                    function e() {
                        var n;
                        requestAnimationFrame(e), n = (o && o.tween ? o.tween.position : window.pageYOffset / (a / 2)) >= r, s !== n && (s = n, i.off("transitionend"), n ? (i.addClass("floating"), clearTimeout(t), t = setTimeout(function() {
                            i.addClass("animate show")
                        }, 50)) : 1 === l ? (i.removeClass("show"), i.one("transitionend", function() {
                            i.removeClass("animate floating")
                        })) : i.removeClass("show animate floating"))
                    }();
                var s = !1,
                    r = .02;
                1 === l && (r = (o && o.tween && o.tween.height) < 200 ? 2 : 1)
            }
        }
    },
    createMobileMenu: function() {
        if (!flexbe_cli.adaptive || 99 === flexbe_cli.theme_id) return !1;
        var e = $(".b_block:not(.hidden) .menu-burger");
        if (!this.$mobileMenu && (this.$mobileMenu = $('<div class="mobile-menu">\n                    <div class="menu-burger">\n                        <span class="menu"><i></i><i></i><i></i></span>\n                    </div>\n                    <div class="menu-wrapper">\n                        <div class="overlay"></div>\n                        <div class="menu-content">\n                            <div class="scroll-wrap">\n                                <div class="logo-holder"></div>\n                                <div class="menu-holder"></div>\n                                <div class="text-holder"></div>\n                                <div class="socials-holder"></div>\n                            </div>\n                        </div>\n                    </div>\n                </div>').appendTo("body"), e[0])) {
            var n = e.eq(0).closest(".b_block").data("bId"),
                i = e.eq(0).closest(".b_block").data("id");
            e.not(":first").remove(), this.$mobileMenu.attr("data-id", i).attr("data-b-id", n)
        }
        var a = this.$mobileMenu.find(".logo-holder"),
            l = this.$mobileMenu.find(".menu-holder"),
            s = this.$mobileMenu.find(".text-holder"),
            r = this.$mobileMenu.find(".socials-holder");

        function c(e, n) {
            var i = d(e),
                l = i.url,
                s = i.search,
                r = i.hash,
                c = 0;
            return n.find("a").each(function(e, n) {
                var i = d(n.href),
                    t = i.url,
                    o = i.hash,
                    a = i.search;
                t === l && a === s && o === r && (c += 1)
            }), !c
        }

        function d(e) {
            var n = String(e).trim().replace(/http(?:s)?:\/\//, "//").split("?"),
                i = (n[0] || "").replace(/^\.\//, "").replace(/\/$/, ""),
                t = (n[1] || "").split("#"),
                o = t[0],
                a = t[1];
            return i && location.host + location.pathname !== i.replace(/^\/\//, "") || (i = "./"), {
                url: i,
                search: o,
                hash: a
            }
        }
        $(".b_block").not(".hidden").find('[data-role="mobile-menu"]').each(function(e, n) {
            $(n).find("[data-role]").each(function(e, n) {
                var i, t, o = $(n);
                switch (o.attr("data-role")) {
                    case "logo":
                        t = o, a.contents().length || a.html(t.contents());
                        break;
                    case "text":
                        i = o, s.contents().length || s.html(i.contents());
                        break;
                    case "menu":
                        ! function(e) {
                            l.contents().length || (l.append(e.clone().contents()), l.find("li").remove());
                            var o = l.find("ul");
                            e.find("a[href]").each(function(e, n) {
                                var i = c(n.href, o);
                                if (i) {
                                    var t = $(n).closest("li");
                                    o.append(t)
                                }
                            })
                        }(o);
                        break;
                    case "socials":
                        ! function(e) {
                            if (r.contents().length) {
                                var o = r.find(".socials-wrap");
                                e.find("a[href]").each(function(e, n) {
                                    var i = $(n).attr("href"),
                                        t = c(i, o);
                                    t && o.append(n)
                                })
                            } else r.append(e.contents())
                        }(o)
                }
            })
        }), l.find(".component-menu").removeAttr("data-style"), this.toggleMobileMenu()
    },
    toggleMobileMenu: function() {
        var n, i = $("body > .mobile-menu").eq(0);

        function t(e) {
            clearTimeout(n), n = setTimeout(function() {
                $("body").toggleClass("overflow fixed", e), i.toggleClass("show", e).off("click.mobile-menu-close"), e && i.on("click.mobile-menu-close", function(e) {
                    var n = $(e.target);
                    console.log(n), (2 === flexbe_cli.theme_id && n.is(".overlay") || 3 === flexbe_cli.theme_id && !n.closest(".menu-holder").length) && t(!1)
                })
            }, 100)
        }
        $(".menu-burger").off("click.mobile-menu-toggle").on("click.mobile-menu-toggle", function() {
            t(!i.hasClass("show"))
        }), i.on("close", function() {
            t(!1)
        }), i.on("open", function() {
            t(!0)
        })
    }
};

function _typeof(e) {
    return (_typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
        return typeof e
    } : function(e) {
        return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
    })(e)
}
flexbe_cli.stat = {
    u_id: 0,
    time: 0,
    goals: {
        quiz: "quiz_start",
        modal: "modal_open",
        modal_form: "form_open",
        modal_product: "product_show",
        modal_done: "order_done",
        cart: "add_to_cart",
        link: "link_open",
        file: "file_load",
        close: "modal_close"
    },
    init: function() {
        var e = getCookie("user_id");
        if (flexbe_cli.is_admin || flexbe_cli.run.is_bot || flexbe_cli.run.is_screenshoter || !flexbe_cli.p_id || e) return !1;
        var t = getCookie("f_uid");
        t ? (this.u_id = t, this.user_visit()) : this.user_create(), this.AB.init(), this.ecommerce.init()
    },
    getGoal: function(e, t) {
        return "modal" === e && (/form/.test(t) && (e = "modal_form"), /done/.test(t) ? e = "modal_done" : /product/.test(t) && (e = "modal_product")), this.goals[e] || !1
    },
    reach_goal: function(e, t) {
        if (t = void 0 !== t ? t : {}, !flexbe_cli.is_admin) try {
            if (flexbe_cli.yandex_id) var i = setInterval(function() {
                if ("undefined" == typeof Ya || "object" !== _typeof(Ya._metrika.counter)) return !1;
                clearInterval(i), Ya._metrika.counter.reachGoal(e, t)
            }, 50);
            "function" == typeof ga && ga("send", "event", e, "send")
        } catch (e) {}
    },
    ecommerce: {
        currencyCodes: {
            "€": {
                code: "EUR",
                name: "Евро"
            },
            Br: {
                code: "BYR",
                name: "Белорусский Рубль"
            },
            "₴": {
                code: "UAH",
                name: "Гривна"
            },
            "грн.": {
                code: "UAH",
                name: "Гривна"
            },
            "₸": {
                code: "KZT",
                name: "Тенге"
            },
            "тңг": {
                code: "KZT",
                name: "Тенге"
            }
        },
        currencyCode: "RUB",
        initialized: !1,
        init: function() {
            flexbe_cli.lang.currency && this.currencyCodes[flexbe_cli.lang.currency] && (this.currencyCode = this.currencyCodes[flexbe_cli.lang.currency].code), this.initialized = !0, window.dataLayer || (window.dataLayer = [])
        },
        add: function(e, t, i, o) {
            if (this.initialized) {
                window.dataLayer.push({
                    ecommerce: {
                        currencyCode: this.currencyCode,
                        add: {
                            products: [{
                                id: e,
                                name: t,
                                price: o,
                                quantity: i
                            }]
                        }
                    }
                })
            }
        },
        remove: function(e, t) {
            if (this.initialized) {
                window.dataLayer.push({
                    ecommerce: {
                        currencyCode: this.currencyCode,
                        remove: {
                            products: [{
                                id: e,
                                name: t
                            }]
                        }
                    }
                })
            }
        },
        purchase: function(e, t) {
            if (void 0 === t && (t = !1), this.initialized && (e || 0 != e.length)) {
                t || (t = Math.ceil(1e4 * Math.random()));
                try {
                    window.dataLayer.push({
                        ecommerce: {
                            currencyCode: this.currencyCode,
                            purchase: {
                                actionField: {
                                    id: t
                                },
                                products: e.map(function(e) {
                                    return {
                                        id: e.id,
                                        name: e.title,
                                        price: e.price,
                                        quantity: e.count
                                    }
                                })
                            }
                        }
                    })
                } catch (e) {}
            }
        }
    },
    get_utm: function() {
        var e = function(e) {
                if ("" === e) return {};
                for (var t = {}, i = 0; i < e.length; ++i) {
                    var o = e[i].split("=");
                    2 == o.length && (t[o[0]] = decodeURIComponent(o[1].replace(/\+/g, " ")))
                }
                return t
            }(window.location.search.substr(1).split("&")),
            i = {};
        return $.each(e, function(e, t) {
            "utm_" === e.substring(0, 4) && (i[e] = t)
        }), document.referrer && (i.url = document.referrer), i
    },
    user_create: function() {
        var t = this;
        $.ajax({
            url: "/requester",
            type: "POST",
            dataType: "json",
            data: {
                s_id: flexbe_cli.s_id,
                group_id: flexbe_cli.group_id,
                p_id: flexbe_cli.p_id,
                utm_data: this.get_utm(),
                device: {
                    type: flexbe_cli.run.device_type,
                    width: window.innerWidth,
                    browser: navigator.userAgent
                }
            }
        }).done(function(e) {
			console.log(e);
            "object" == _typeof(e) && null !== e && e.u_id ? (setCookie("f_uid", e.u_id, {
                Path: "/"
            }), t.u_id = e.u_id) : console.warn("cookie не установлена", e)
        })
    },
    user_visit: function() {
        $.ajax({
            url: "/mod/stat/visit/",
            type: "POST",
            dataType: "json",
            data: {
                s_id: flexbe_cli.s_id,
                group_id: flexbe_cli.group_id,
                p_id: flexbe_cli.p_id,
                u_id: this.u_id
            }
        }).done($.proxy(function(e) {
			console.log(e);
            e.v_id || console.warn("cookie визита не установлена", e)
        }, this))
    },
    AB: {
        init: function() {
            var o = this;
            flexbe_cli.events.off("entity_event.abstat").on("entity_event.abstat", function(e, t) {
                if (t && "screen" === t.type && t.state && t.core && "block" === t.core.is) {
                    var i = t.core.$area;
                    o.fixview(i.attr("data-abtest-id"), i.attr("data-abtest-variant"))
                }
            })
        },
        setcookie: function(e) {
            setCookie("f_ab", JSON.stringify(e), {
                expires: 604800,
                path: "/",
                domain: document.location.hostname
            })
        },
        getcookie: function() {
            var t = !0,
                e = getCookie("f_ab");
            if (e) try {
                e = JSON.parse(decodeURIComponent(e)), t = !1
            } catch (e) {
                console.warn("cant parse abtest cookie", e), t = !0
            }
            return t && (e = {
                view: {},
                lead: []
            }), e
        },
        proccess: {},
        fixview: function(i, o) {
            if (void 0 === i || void 0 === o || "a" != o && "b" != o) return !1;
            void 0 !== this.getcookie().view[i] || this.proccess[i] || (this.proccess[i] = !0, $.ajax({
                url: "/mod/stat/abtest",
                type: "POST",
                dataType: "json",
                data: {
                    test_id: i,
                    variant: o,
                    s_id: flexbe_cli.s_id,
                    p_id: flexbe_cli.p_id
                }
            }).done($.proxy(function(e) {
                if (this.proccess[i] = !1, 1 == e.status) {
                    var t = flexbe_cli.stat.AB.getcookie();
                    t.view[i] = o, flexbe_cli.stat.AB.setcookie(t)
                }
            }, this)))
        },
        fixlead: function(e) {
            if (flexbe_cli.bill && 1 != flexbe_cli.bill.abtest) return !1;
            if (0 < e.length) {
                var t = this.getcookie(),
                    i = $.merge(t.lead, e);
                t.lead = $.grep(i, function(e, t) {
                    return $.inArray(e, i) === t
                }), this.setcookie(t)
            }
        }
    }
};
flexbe_cli.timer = {
    list: {},
    create: function(t) {
        return this.list[t.id] = new flexbe_cli.timer.Timer(t), this.list[t.id]
    }
}, flexbe_cli.timer.Timer = function(t) {
    this.o = t, this.create()
}, flexbe_cli.timer.Timer.prototype = {
    o: {},
    create: function() {
        var t = $(this.o.block).find(this.o.item),
            e = t.data("time"),
            i = new Date;
        if (this.lang(t), "date" == e.type) {
            var a = e.my ? e.my.toString().split(".") : [1, 2018];
            this.final_date = new Date(a[1], parseInt(a[0], 10) - 1, e.d, e.h, e.m)
        } else if ("monthly" == e.type) this.final_date = new Date(i.getFullYear(), i.getMonth(), e.d, e.h, e.m), i.getTime() > this.final_date.getTime() && (this.final_date = new Date(i.getFullYear(), i.getMonth() + 1, e.d, e.h, e.m)), parseInt(e.d, 10) != this.final_date.getDate() && (this.final_date.setDate(0), i.getTime() > this.final_date.getTime() && (this.final_date = new Date(this.final_date.getFullYear(), this.final_date.getMonth() + 2, 0, e.h, e.m)));
        else if ("weekly" == e.type) {
            var s = parseInt(i.getDate(), 10) - parseInt(i.getDay(), 10) + parseInt(e.dw, 10);
            this.final_date = new Date(i.getFullYear(), i.getMonth(), s, e.h, e.m), i.getTime() > this.final_date.getTime() && this.final_date.setDate(this.final_date.getDate() + 7)
        } else "daily" == e.type ? (this.final_date = new Date(i.getFullYear(), i.getMonth(), i.getDate(), e.h, e.m), i.getTime() > this.final_date.getTime() && this.final_date.setDate(this.final_date.getDate() + 1)) : (this.final_date = new Date, this.final_date.setMonth(this.final_date.getMonth() + 1, 15));
        this.item_d_1 = t.find(".d [data-value]").eq(0), this.item_d_2 = t.find(".d [data-value]").eq(1), this.item_d_3 = t.find(".d [data-value]").eq(2), this.item_h_1 = t.find(".h [data-value]").eq(0), this.item_h_2 = t.find(".h [data-value]").eq(1), this.item_m_1 = t.find(".m [data-value]").eq(0), this.item_m_2 = t.find(".m [data-value]").eq(1), this.item_s_1 = t.find(".s [data-value]").eq(0), this.item_s_2 = t.find(".s [data-value]").eq(1), this.last_offset = {
            d: void 0,
            h: void 0,
            m: void 0,
            s: void 0
        }, !flexbe_cli.is_admin && this.final_date.getTime() < (new Date).getTime() ? $(this.o.block).hide() : this.start()
    },
    update: function() {
        if (this.second_left = this.final_date.getTime() - (new Date).getTime(), this.second_left = Math.ceil(this.second_left / 1e3), this.second_left = this.second_left < 0 ? 0 : this.second_left, this.offset = {
                d: Math.floor(this.second_left / 60 / 60 / 24),
                h: Math.floor(this.second_left / 60 / 60) % 24,
                m: Math.floor(this.second_left / 60) % 60,
                s: this.second_left % 60
            }, this.last_offset.d != this.offset.d) {
            var t = this.offset.d.toString().split("");
            t.length < 2 && t.unshift(0), t.length < 3 && t.unshift(0), this.item_d_1.attr("data-value", t[0]).text(t[0]), this.item_d_2.attr("data-value", t[1]).text(t[1]), this.item_d_3.attr("data-value", t[2]).text(t[2])
        }
        if (this.last_offset.h != this.offset.h) {
            var e = this.offset.h.toString().split("");
            e.length < 2 && e.unshift(0), this.item_h_1.attr("data-value", e[0]).text(e[0]), this.item_h_2.attr("data-value", e[1]).text(e[1])
        }
        if (this.last_offset.m != this.offset.m) {
            var i = this.offset.m.toString().split("");
            i.length < 2 && i.unshift(0), this.item_m_1.attr("data-value", i[0]).text(i[0]), this.item_m_2.attr("data-value", i[1]).text(i[1])
        }
        if (this.last_offset.s != this.offset.s) {
            var a = this.offset.s.toString().split("");
            a.length < 2 && a.unshift(0), this.item_s_1.attr("data-value", a[0]).text(a[0]), this.item_s_2.attr("data-value", a[1]).text(a[1])
        }
        this.last_offset = this.offset, this.second_left < 0 && this.stop()
    },
    start: function() {
        null !== this.interval && clearInterval(this.interval), this.update(), this.interval = setInterval($.proxy(function() {
            this.update()
        }, this), 200)
    },
    stop: function() {
        clearInterval(this.interval), this.interval = null
    },
    lang: function(t) {
        t.find("[data-timer-text]").each(function(t, e) {
            var i = $(e).attr("data-timer-text"),
                a = flexbe_cli.lang.get("timer." + i);
            $(e).text(a)
        })
    }
};
flexbe_cli.run.init = function() {
    flexbe_cli.block.initHeaders(), flexbe_cli.lang.init(), flexbe_cli.stat.init(), flexbe_cli.scroll.init(), flexbe_cli.resize.init(), flexbe_cli.components.init(), flexbe_cli.block.init(), flexbe_cli.modal.init(), flexbe_cli.widget.init(), flexbe_cli.menu.init(), flexbe_cli.lib.init()
};