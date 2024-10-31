
class QueryList {

    static version = "1.1";

    static autostartParameterName = "pausar-autostart";

    /**
     * UserAgent + Platform Query (Only on HTTPS).
     * Detects if Devices runs on the iPhone OS, iPad OS and older iOS Versions for iPad etc. Also tries to estimate, if a modern iPad Pro is present.
     * @returns Boolean
     */
    static isIOS() {
        let webkit = false;
        let MacWithTouch = false;
        let platformUA;
        //WebKit
        webkit = /\b(iPad|iPhone|iPod)\b/.test(navigator.userAgent) &&
            /WebKit/.test(navigator.userAgent) &&
            !/Edge/.test(navigator.userAgent) &&
            !window.MSStream;
        //iPad Pro with Desktop-Mode (Safari-Default since >= iPadOS 13)
        if ("platform" in navigator) {
            platformUA = navigator.platform;
        } else if ("userAgentData" in navigator) {
            if ("platform" in navigator.userAgentData) {
                platformUA = navigator.userAgentData.platform;
            }
        }
        if (platformUA) {
            if (this.hasTouchScreen(3)) {
                if (platformUA.toString().includes("Mac") || platformUA.toString().includes("iPad")) {
                    MacWithTouch = true;
                }
            }
        }
        return (webkit || MacWithTouch) ? true : false;
    }

    static isLinkedIn() {
        return navigator.userAgent.indexOf("LinkedInApp") >= 0;
    }

    static isInstagram() {
        return navigator.userAgent.indexOf("Instagram") >= 0;
    }

    static isFacebook() {
        return ((navigator.userAgent.indexOf("FBAN") >= 0) || (navigator.userAgent.indexOf("FBIOS") >= 0));
    }

    static isDesktop() {
        return !/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Windows Phone/i.test(navigator.userAgent);
    }

    static isXRDevice() {
        return 'xr' in navigator;
    }

    /**
     * async
     */
    static async isARDevice() {

        let result = false;
        await navigator.xr.isSessionSupported('immersive-ar').then(function (support) {
            result = support;
        });
        return result;
    }

    /**
     * async
     */
    static async isXRARDevice() {
        if ("xr" in navigator) {
            let result = false;
            try {
                await navigator.xr.isSessionSupported('immersive-ar').then(function (support) {
                    result = support;
                });
            } catch (err) {
                result = false;
                console.warn("navigator.xr support could not be detected.");
            }
            return result;
        }
        return false;
    }

    static hasTouchScreen(minTouchPoints) {

        if(typeof minTouchPoints !== 'number') {
            minTouchPoints = 1;
        }
        if(minTouchPoints < 1) {
            minTouchPoints = 1;
        }

        let result = false;
        if ("maxTouchPoints" in navigator) {
            result = navigator.maxTouchPoints >= minTouchPoints;
        } else if ("msMaxTouchPoints" in navigator) {
            result = navigator.msMaxTouchPoints >= minTouchPoints;
        } else {
            const mQ = matchMedia?.("(pointer:coarse)");
            if (mQ?.media === "(pointer:coarse)") {
                result = !!mQ.matches;
            } else if ("orientation" in window) {
                result = true; // deprecated, but good fallback
            }
        }

        return result;
    }

    static isAndroid() {
        return /android/i.test(navigator.userAgent);
    }

    /**
     * UserAgent Query. Returns only the main version as a number. Returns 0, if the OS is not found or the userAgent is invalid.
     * @returns Number : Version of Android
     */
    static getAndroidVersion() {
        let userA = navigator.userAgent.toLowerCase();
        var match = userA.match(/android\s([0-9\.]*)/i);

        return match ? Number(match[1]) : 0;
    }

    /**
     * CAUTION - Detects only the Version of iPhones,iPods and old iPads Devices. Modern iPad Pro Devices do not show their iOS Version inside the userAgent anymore.
     * UserAgent Query. Returns only the main version as a number. Returns 0, if the OS is not found or the userAgent is invalid.
     * @returns Number : Version of iOS
     */
    
    static getiOSVersion() {
        
       let result = 0;        
        if(navigator.userAgent.match(/ipad|iphone|ipod/i)){ //if the current device is an iDevice

            //Strings (e.g. OS 15)
            //result = (navigator.userAgent).match(/OS (\d)?\d_\d(_\d)?/i)[0]; //FullRepot (String)
            //result = (navigator.userAgent).match(/OS (\d)?\d_\d(_\d)?/i)[0].split('_')[0]; //Major Release (String)
            //result = (navigator.userAgent).match(/OS (\d)?\d_\d(_\d)?/i)[0].replace(/_/g,"."); //Full Release (String)

            //Numerics
            result = (navigator.userAgent).match(/OS (\d)?\d_\d(_\d)?/i)[0].split('_')[0].replace("OS ",""); //Major Release (Numeric)
            //Full_Release_Numeric=+(navigator.userAgent).match(/OS (\d)?\d_\d(_\d)?/i)[0].replace("_",".").replace("_","").replace("OS ",""); //Full Release (Numeric) -> converts versions like 4.3.3 to numeric value 4.33 for ease of numeric comparisons
        }
        return Number(result) ? Number(result) : 0;        
    }

    static isQuicklook() {
        return /Safari/i.test(navigator.userAgent) && this.isIOS();
        /*
        const dummyLink = document.createElement("a");
        if('relList' in dummyLink) {
            return dummyLink.relList.supports("ar");
        }
        return false;
        */
    }

    /**
     * Checks for support of a clipboard API, to execute copy-commands.
     * @returns {boolean} clipboardAPI support
     */
    static isClippboardPossible() {
        let result = false;
        if ('clipboard' in navigator) {
            if ('write' in navigator.clipboard || 'writeText' in navigator.clipboard) {
                result = true;
            }

        }
        if ('execCommand' in document) {
            result = true;
        }
        return result;
    }

    /**
     * Checks for available clipboard APIs, to execute copy-commands. Returns a string of the available API or null.
     * @returns {string | null} "navigator.clipboard.write" | "navigator.clipboard.writeText" | "document.execCommand" | null
     */
    static getClipboardApi() {

        if('clipboard' in navigator) { //Only available with SSL
            if('writeText' in navigator.clipboard) {//New Version
                return "navigator.clipboard.writeText";
            } else if('write' in navigator.clipboard) {//Often used by Opera
                return "navigator.clipboard.write";
            }
    
        } else if('execCommand' in document) {
            return "document.execCommand";
        }
        
        return null;
    }

    /**
     * Identical function as 'getClipboardApi()', but with an include check for permission to write to the clipboardAPI or actually executing commands. The permission can be denied on some browsers, if used inside iframes.
     * @returns {string | null} "navigator.clipboard.write" | "navigator.clipboard.writeText" | "document.execCommand" | null
     */
    static async getClipboardApiPermission() {
        const self = this;
        let result = new Promise(async function(resolve) {
            if(self.getClipboardApi() == "navigator.clipboard.write" || self.getClipboardApi() == "navigator.clipboard.writeText") {
                if("permissions" in navigator) {
                    let permission = await navigator.permissions.query({ name: 'clipboard-write' });
                    if(permission.state === 'granted') {
                        resolve(self.getClipboardApi());
                    } else if('execCommand' in document) {
                        resolve("document.execCommand");
                    }
                } else {
                    resolve(self.getClipboardApi());
                }
                
            } else if(self.getClipboardApi() == "document.execCommand") {
                resolve("document.execCommand");
            }
            resolve(null);
        });
        return result;
    }

    //Browser Queries (low reliability)
    //Keep them up to date:
    //https://stackoverflow.com/questions/9847580/how-to-detect-safari-chrome-ie-firefox-and-opera-browsers

    // Opera 8.0+
    static isOpera() {
        return (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
    }

    static isOperaGXMobile() {
        return /OPX/i.test(navigator.userAgent);
    }

    // Firefox 1.0+
    static isFirefox() {
        return typeof InstallTrigger !== 'undefined';
    }

    // Safari 3.0+ "[object HTMLElementConstructor]" 
    static isSafari() {
        return /constructor/i.test(window.HTMLElement) || (function (p) { return p.toString() === "[object SafariRemoteNotification]"; })(!window['safari'] || (typeof safari !== 'undefined' && window['safari'].pushNotification));
    }

    // Internet Explorer 6-11
    static isIE() {
         /*@cc_on!@*/
         return false || !!document.documentMode;
    }

    // Edge 20+
    static isEdge() {
        return !isIE && !!window.StyleMedia;
    }

    // Chrome 1 - 79
    static isChrome() {
        return !!window.chrome && (!!window.chrome.webstore || !!window.chrome.runtime);
    }

    // Edge (based on chromium) detection
    static isEdgeChromium() {
        return isChrome && (navigator.userAgent.indexOf("Edg") != -1);
    }

    // Blink engine detection
    static isBlink() {
        return (isChrome || isOpera) && !!window.CSS;
    }

    //----------------------------------
    //Helper and util functions
    //----------------------------------

    /**
     * Converts the current search parameter or the parameter of a given URL into a JSON object.
     * @param {Boolean} filterDuplicates Duplicate parameters are filtered out. Only the last of all removed parameters remains.
     * @param {String} customURL An optional URL string can override the examination of the current tab's URL. If the URL is invalid, the current tab's URL is examined.
     * @returns {JSON[]} The collected search parameters as a JSON array [name, value]
     */
    static getSearchParameter(filterDuplicates, customURL) {
        let result = [];
        let targetURL = null;//.search or .searchParams

        if(typeof filterDuplicates !== "boolean") {
            filterDuplicates = false;
        }
        if(filterDuplicates == true) {
            result = new Map();
        }

        if(typeof customURL === 'string' && customURL != "") {
            try {
                targetURL = new URL(customURL);
            } catch (error) {
                console.warn("[PausAR]: URL parameters could not be recognized. The specified iframe host URL is invalid. The current (host) URL will be used.");
                targetURL = new URL(window.location.href);
            }
        } else {
            targetURL = new URL(window.location.href);
        }

        let paramJSON = null;
        targetURL.searchParams.forEach((value, key) => {
            if(filterDuplicates == true) {
                result.set(key, value);
            } else {
                paramJSON = {
                    "name": key,
                    "value": value
                };
                result.push(paramJSON);
            }
        });

        if(filterDuplicates == true) {
            return Array.from(result, ([name, value]) => ({ name, value }));
        } else {
            return result;
        }
    }

    static supportAutostart(arButtonElement) {
        if(typeof arButtonElement !== 'object') {
            return false;
        }
        if(typeof arButtonElement.tagName !== "string") {
            return false;
        }
        if(typeof arButtonElement.getAttribute("pausar") !== "string") {
            return false;
        }
        //if(typeof arButtonElement.getAttribute("paus_iframe") === "string") {
            //return false;
        //}
        if(this.isAndroid()) {
            let paus_file = arButtonElement.getAttribute("paus_file") != null ? (typeof arButtonElement.getAttribute("paus_file") === 'string' ? (this.stringEndsWith(arButtonElement.getAttribute('paus_file'), '.pausar') ? true : false) : false) : false;
            if(paus_file == true) {
                return false;
            }
        }        
        return typeof arButtonElement.getAttribute("paus_autostart") === "string" ? true : false;

    }

    static stringEndsWith(haystack, needle) {
        if(typeof haystack !== "string" || typeof needle !== "string") {
            return false;
        }
        if(needle.length > haystack.length) {
        return false;
        }
        if(haystack.substring((haystack.length - needle.length), haystack.length) == needle) {
            return true;
        }  
        return false;
    }

    /**
    * Converts and checks if a string has correct URL syntax. Returns either the validated string or NULL.
    * @param {String} linkStr The string to be checked
    * @param {Boolean} removeSearch remove the searchParams from the URL
    * @param {Boolean} removeHash remove the hash from the URL
    * @returns {String | null}
    */
    static validateURL(linkStr, removeSearch, removeHash) {
        if(typeof linkStr !== "string") {
            return null;
        }
        if(linkStr == "" || linkStr.length == 0) {
            return null;
        }
        if(typeof removeSearch !== 'boolean') {
            removeSearch = false;
        }
        if(typeof removeHash !== 'boolean') {
            removeHash = false;
        }
        try {
            let result = new URL(linkStr);
            if(result.protocol == "http:" || result.protocol == "https:") {

                if(removeSearch && removeHash) {
                    return result.origin + result.pathname;
                } else if(removeSearch && !removeHash) {
                    return result.origin + result.pathname + result.hash;
                } else if(!removeSearch && removeHash) {
                    return result.origin + result.pathname + result.search;
                }
                return result.href;
            }
            return null;
        } catch (error) {
            return null;
        }
   }

}

export {QueryList};