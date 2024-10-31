import "../../lib/qrjs2.min.js";//https://github.com/englishextra/qrjs2
import { QueryList } from "./queryModule.js?ver=1.1";

class PausAR_Popup {
    static version = "1.1";

    #originalBodyOverflow;//Saves the docment.body inline-style before overwriting
    #originalBodyTouchAction;//Saves the docment.body inline-style before overwriting

    #popupElement; //Hosts the entire DialogPopup and Background and will be removed or added dynamicly
    #popupContent; //MainWindowContent
    #popupScrollbox; //MainWindowContent
    #popupEventListeners = [];
    #popupObserver = null;
    #clipboardAPI; //Query
    #clipboardAPIPermission; //Query
    static #containerClassName = "pausAR-UI-popupContainerDialog";
    static #popupWindowClassName = "pausAR-UI-popupWindow";
    static #contentContainerClassName = "pausAR-UI-popupContent";
    static #scrollboxClassName = "pausAR-UI-popupScrollbox";
    static #closeButtonClassName = "pausAR-UI-popupCloseButton";
    static #backdropClassName = "pausAR-UI-popupContainerBackdrop";
    static #copyStatusClassName = "pausAR-UI-copyStatus";
    static #copyTextfieldClassName = "pausAR-UI-copyTextfield";
    static #copyButtonClassName = "pausAR-UI-copyButton";
    static #mainButtonClassName = "pausAR-UI-popupMainButton";
    static #textboxWrapper = "pausAR-UI-popupTextboxWrapper";
    static #chromeStatusClassname = "pausAR-chromeStatus";
    static #chromeButtonID = "pausAR-UI-chromeButton";
    static #safariButtonID = "pausAR-UI-safariButton";
    static #chromeLinkStatus = "Open the link in the following browser:";
    static #copyStatusText = "Copy the following link:";
    static #scrollBarMaskWidth = 42; //Margin of mask-gradient
    static #chromeiTunesLink = "https://apps.apple.com/de/app/google-chrome/id535886823";
    //Clonable Elements
    //Popup Frame
    #popupElementTemplate; //Hosts the entire DialogPopup and Background and will be removed or added dynamicly
    //Content
    #popupHeaderElement;
    #popupParagraphElement;
    #popupBoxElement;
    #popupCopyElement;
    #popupSeperatorLineElement;
    #popupSeperatorLabelElement;
    #popupQRContainerElement;
    #popupChromeLink;

    #popupLinkQR = window.location.origin + window.location.pathname + window.location.search;
    #popupLink = window.location.origin + window.location.pathname + window.location.search;

    #clipboardTimeout;
    #chromeTimeout;
    #chromeInnerTimeout;
   
    constructor()  {
        const self = this;
        this.#clipboardAPI = null;
        this.#clipboardAPIPermission = null;
        if(typeof QueryList === 'function') {
            if(QueryList.getClipboardApi) {
                this.#clipboardAPI = QueryList.getClipboardApi();
            }
        }
        //===========================================================
        // Build kit (Clonable Elements)
        //===========================================================

        //Popup Frame
        this.#popupElementTemplate = document.createElement("div"); //Parent Container
        this.#popupElementTemplate.className = PausAR_Popup.#containerClassName;

        let popupBackdrop = document.createElement("div");
        popupBackdrop.className = PausAR_Popup.#backdropClassName;

        let popupWindow = document.createElement("div");
        popupWindow.className = PausAR_Popup.#popupWindowClassName;
        
        let popupCloseButton = document.createElement("button");
        popupCloseButton.className = PausAR_Popup.#closeButtonClassName;

        let popupScrollbox = document.createElement("div");//this.#popupScrollbox
        popupScrollbox.className = PausAR_Popup.#scrollboxClassName;
        
        let popupContent = document.createElement("div");//this.#popupContent
        popupContent.className = PausAR_Popup.#contentContainerClassName;

        //popupBackdrop.onclick = popupCloseButton.onclick = this.closePopup.bind(this);

        this.#popupElementTemplate.appendChild(popupBackdrop);
        this.#popupElementTemplate.appendChild(popupWindow);
        popupWindow.appendChild(popupCloseButton);
        popupWindow.appendChild(popupScrollbox);//this.#popupContent
        popupScrollbox.appendChild(popupContent);//this.#popupScrollbox

        //Content Elements

        //Header (single)
        this.#popupHeaderElement = document.createElement("h4");
        //Text-Paragraph (single)
        this.#popupParagraphElement = document.createElement("p");
        //Textbox (single/container)
        this.#popupBoxElement = document.createElement("div");
        this.#popupBoxElement.className = "pausAR-UI-popupTextBox";
        let boxWrapper = document.createElement("div");
        boxWrapper.className = PausAR_Popup.#textboxWrapper;
        this.#popupBoxElement.appendChild(boxWrapper);
        //Horizontal Line (single)
        this.#popupSeperatorLineElement = document.createElement("hr");
        //Horizontal Or-Seperator (single)
        this.#popupSeperatorLabelElement = document.createElement("span");
        this.#popupSeperatorLabelElement.innerHTML = "or";
        //QR Container (single/container)
        this.#popupQRContainerElement = document.createElement("div");
        this.#popupQRContainerElement.className = "pausAR-UI-popupQRCode";
        //Copy-URL (multi/container)
        this.#popupCopyElement = this.#popupBoxElement.cloneNode(true);
        let copyBoxStatus = document.createElement("p");
        copyBoxStatus.className = PausAR_Popup.#copyStatusClassName;
        copyBoxStatus.innerHTML = PausAR_Popup.#copyStatusText;
        let copyBoxWrapper = this.#popupCopyElement.querySelector("." + PausAR_Popup.#textboxWrapper);
        if(copyBoxWrapper) {
            copyBoxWrapper.appendChild(copyBoxStatus);
            let copyWrapper = document.createElement("div");
            copyWrapper.className = "pausAR-UI-copyWrapper";
            copyBoxWrapper.appendChild(copyWrapper);
            let textfield = document.createElement("div");
            textfield.className = PausAR_Popup.#copyTextfieldClassName;
            copyWrapper.appendChild(textfield);
            if(this.#clipboardAPI != null) {
                let copyButtonWrapper = document.createElement("div");
                copyButtonWrapper.className = "pausAR-UI-copyButtonWrapper";
                copyWrapper.appendChild(copyButtonWrapper);
                let copyButton = document.createElement("button");
                copyButton.className = PausAR_Popup.#copyButtonClassName;
                copyButtonWrapper.appendChild(copyButton);
                //copyButton.onclick = this.#copyURL.bind(this);
            }
        }
        //ChromeLink(multi/container)
        this.#popupChromeLink = this.#popupBoxElement.cloneNode(true);
        let chromeLinkBoxWrapper = this.#popupChromeLink.querySelector("." + PausAR_Popup.#textboxWrapper);
        if(chromeLinkBoxWrapper) {
            let chromeLinkParagraph = document.createElement("p");
            chromeLinkParagraph.innerHTML = PausAR_Popup.#chromeLinkStatus;
            chromeLinkParagraph.className = PausAR_Popup.#chromeStatusClassname;
            chromeLinkBoxWrapper.appendChild(chromeLinkParagraph);
            let chromeButtonFlexbox = document.createElement("div");
            chromeButtonFlexbox.className = "pausAR-UI-popupButtonFlexbox";
            chromeLinkBoxWrapper.appendChild(chromeButtonFlexbox);
            let chromeButton = document.createElement("button");
            chromeButton.className = PausAR_Popup.#mainButtonClassName;
            chromeButton.innerHTML = "Chrome";
            chromeButton.setAttribute("id", PausAR_Popup.#chromeButtonID);
            chromeButtonFlexbox.appendChild(chromeButton);
        }
    }

    
    
    cloneColor(arButtonElement) {
        if(typeof arButtonElement === 'undefined' && arButtonElement == null) {
            return ;
        }

        let backgroundColor = window.getComputedStyle(arButtonElement).getPropertyValue("background-color");
        let textColor = window.getComputedStyle(arButtonElement).getPropertyValue("color");
        let backgroundImage = window.getComputedStyle(arButtonElement).getPropertyValue("background-image");
       
        let buttons = this.#popupChromeLink.querySelectorAll("button");
        for(let i = 0; i < buttons.length; i++) {
            
            buttons[i].style.color = textColor;
            buttons[i].style.backgroundColor = backgroundColor;
            buttons[i].style.backgroundImage = backgroundImage;
                        
        }
    }

    setFullscreen() {
        if(this.#popupElement) {//If popup is already opend/cloned
            this.#popupElement.classList.add("pausAR-UI-popupFullscreen");
        }
        this.#popupElementTemplate.classList.add("pausAR-UI-popupFullscreen");
    }

    exitFullscreen() {
        if(this.#popupElement) {
            this.#popupElement.classList.remove("pausAR-UI-popupFullscreen");
        }
        this.#popupElementTemplate.classList.remove("pausAR-UI-popupFullscreen");
    }

    /**
     * 
     * @param {Element} arButtonElement Reference to the 'Start AR' button of the widget.
     * @param {String} customURL A custom URL to overwrite the QR Code Link. Disables the creation of a autostart search parameter
     * @returns 
     */
    setAnchor(arButtonElement, customURL) {

        this.#popupLinkQR = this.#popupLink = window.location.origin + window.location.pathname + window.location.search;//default

        if(typeof customURL === 'string' && customURL != "") {//Overwrite URL (e.g. iframe host url)
            //Validate customURL
            let convertedLink = null;
            if(QueryList.validateURL) {
                convertedLink = QueryList.validateURL(customURL);
            }
            if(convertedLink != null) {
                this.#popupLinkQR = this.#popupLink = convertedLink;
                return ;
            } else {
                console.warn("[PausAR]: The specified iframe host URL is invalid. The current (host) URL will be used.");
                this.#popupLinkQR = this.#popupLink = window.location.origin + window.location.pathname + window.location.search;
            }
        }

        if(typeof arButtonElement === 'undefined' && arButtonElement == null) {
            return ;//ID, customID and "autostart" can not be configured, if no DOM Element is given
        }       

        //Define Links/Anchors
        let customAnchor = arButtonElement.getAttribute("paus_anchor");
        let defaultAnchor = arButtonElement.getAttribute("id");

        let searchParameterJSON = [];
        let searchParameterString = window.location.search;

        //--------------------------------
        //Check autostart attribute
        //--------------------------------
        let autostartSupport = false;
        if(QueryList.supportAutostart) {
            autostartSupport = QueryList.supportAutostart(arButtonElement);
        }        

        //Overwrite/CleanUp the searchParameter
        if(autostartSupport == true) {
            if((typeof defaultAnchor === 'string' && defaultAnchor != "")) {
                searchParameterJSON = QueryList.getSearchParameter(false);
            
                //Search for autostart value
                let autostartIndexes = [];
                for(let i = 0; i < searchParameterJSON.length; i++) {
                    if(searchParameterJSON[i].name == QueryList.autostartParameterName) {
                        autostartIndexes.push(i);
                    }
                }
                if(autostartIndexes.length == 0) {
                    autostartIndexes.push(searchParameterJSON.length);
                    searchParameterJSON.push({
                        "name": QueryList.autostartParameterName,
                        "value": defaultAnchor
                    });
                } else {
                    searchParameterJSON[autostartIndexes[autostartIndexes.length-1]].value = defaultAnchor;
                }

                //remove all duplicated autostart parameter
                let parameterRemovedCounter = autostartIndexes.length-1;
                for(let d = 0; d < searchParameterJSON.length; d++) {
                    if(searchParameterJSON[d].name == QueryList.autostartParameterName && parameterRemovedCounter > 0) {
                        parameterRemovedCounter -= 1;
                        if(d >= 0 || d > searchParameterJSON.length) {
                            searchParameterJSON = searchParameterJSON.slice(0, d).concat(searchParameterJSON.slice(d+1, searchParameterJSON.length));
                        }
                    }
                }     
                
                
            } else {
                //Widget ID not found. Remove all autostart from the url sreach
                searchParameterJSON = QueryList.getSearchParameter(false);
                for(let d = 0; d < searchParameterJSON.length; d++) {
                    if(searchParameterJSON[d].name == QueryList.autostartParameterName) {
                        if(d >= 0 || d > searchParameterJSON.length) {
                            searchParameterJSON = searchParameterJSON.slice(0, d).concat(searchParameterJSON.slice(d+1, searchParameterJSON.length));
                            d -= 1; 
                        }
                    }
                }
            }

            //convert JSON back to String
            searchParameterString = "";
            for(let j = 0; j < searchParameterJSON.length; j++) {
                if(searchParameterJSON[j].value != "") {
                    searchParameterString += searchParameterJSON[j].name + "=" + searchParameterJSON[j].value;
                } else {
                    searchParameterString += searchParameterJSON[j].name;
                }
                
                if(j < (searchParameterJSON.length-1)) {
                    searchParameterString += "&";
                }
            }
            if(searchParameterString != "") {
                searchParameterString = "?" + searchParameterString;
            }
            
        }
        
        //Check if customAnchor is valid
        if(typeof customAnchor === 'string' && customAnchor != "") {
            //Custom Anchor overwrite
            //if(this.#isStringEncoded(customAnchor)) {
                //customAnchor = this.#finalDecodeURI(customAnchor);
            //}
            customAnchor = decodeURIComponent(customAnchor);
            if(customAnchor.indexOf("'") >= 0 || customAnchor.indexOf('"') >= 0 || customAnchor.indexOf(".") >= 0 || customAnchor.indexOf(",") >= 0 || customAnchor == '#') {
                this.#popupLinkQR = window.location.origin + window.location.pathname + searchParameterString + "#" + defaultAnchor;
                this.#popupLink = window.location.origin + window.location.pathname + window.location.search + "#" + defaultAnchor;
                return ;
            }
            this.#popupLinkQR = window.location.origin + window.location.pathname + searchParameterString + "#" + encodeURIComponent(customAnchor);
            this.#popupLink = window.location.origin + window.location.pathname + window.location.search + "#" + encodeURIComponent(customAnchor);
            return ;

        } else if(typeof defaultAnchor === 'string' && defaultAnchor != "") {
            this.#popupLinkQR = window.location.origin + window.location.pathname + searchParameterString + "#" + defaultAnchor;
            this.#popupLink = window.location.origin + window.location.pathname + window.location.search + "#" + defaultAnchor;
            return ;
        }
    }

    
    /**
     * Populates the popup with cloned elements (templates) according to the given enumeration and adds it to the DOM.
     * @param {String} stateEnum 
     * @returns 
     */
    openPopup(stateEnum) {
        if(this.#isOpen()) {
            return ;
        }

        const self = this;

        this.#clearEvents();

        //Setting popupState

        if(typeof stateEnum !== 'string') {
            console.warn("[PausAR]: popupStateName is invalid");
            return ;
        }
        //Constructing new Popup Frame
        this.#popupElement = this.#popupElementTemplate.cloneNode(true);
        this.#popupContent = this.#popupElement.querySelector("." + PausAR_Popup.#contentContainerClassName);
        this.#popupScrollbox = this.#popupElement.querySelector("." + PausAR_Popup.#scrollboxClassName);
        
        if(typeof this.#popupContent === 'undefined' || this.#popupContent == null) {
            console.warn("[PausAR]: popupState could not be set");
            return ;
        }

        switch (stateEnum) {
            
            //desktop                           //Prompt, to switch to a supported mobile device (QR + CopyURL)
            //missingSupport                    //DefaultPrompt for all mobile devices with no AR support (Quicklook or WebXR). (recommendation to switch to Android or iOS) || Also used for all other devices not covered by a specific case
            //missingSupportAndroid             //DefaultPromot for all Android devices (recommendation + copyURL)
            //missingSupportQuicklook           //DefaultPrompt for all iOS devices with no AR support (Quicklook) (recommendation to switch browser + copyURL)

            //missingSupportQuicklookSocial     //SpecificPrompt for iOS devices inside Facebook- or Instagramm-browser (recommendation to switch browser + copyURL + "•••" hint + specialLink>Chrome)
            //missingSupportQuicklookLinkedin   //SpecificPrompt for iOS devices inside LinkedIn-browser (recommendation to switch browser + copyURL + "•••" hint + specialLink>PDF)
           
            case 'desktop':
                //Cloning Elements and setting Values
                var header = this.#popupHeaderElement.cloneNode(true);
                header.innerHTML = "Your device doesn't support AR";
                this.#popupContent.appendChild(header);

                var recommendations = this.#popupParagraphElement.cloneNode(true);
                recommendations.innerHTML = "<b>iOS</b> or <b>Android</b> devices are recommended.";
                this.#popupContent.appendChild(recommendations);

                this.#popupContent.appendChild(this.#popupSeperatorLineElement.cloneNode(true));
                
                var subHeader = this.#popupParagraphElement.cloneNode(true);
                subHeader.innerHTML = "Please scan this QR Code and open it on a mobile device.";
                this.#popupContent.appendChild(subHeader);
                
                var qr = this.#popupQRContainerElement.cloneNode(true);
                qr.appendChild(QRCode.generateSVG(this.#popupLinkQR, {
                    ecclevel: "M",
                    fillcolor: "#FFFFFF",
                    textcolor: "#000000",
                    margin: 2,
                    modulesize: 8
                }));
                this.#popupContent.appendChild(qr);
                
                this.#popupContent.appendChild(this.#popupSeperatorLabelElement.cloneNode(true));
                
                var copyURLContainer = this.#popupCopyElement.cloneNode(true);
                var currentURL = copyURLContainer.querySelector("." + PausAR_Popup.#copyTextfieldClassName);
                if(currentURL) {
                    currentURL.innerHTML = this.#popupLink;
                }
                this.#addClipboardFunctions(copyURLContainer);
                this.#popupContent.appendChild(copyURLContainer);
                break;
            case 'missingSupport':
                //Cloning Elements and setting Values
                var header = this.#popupHeaderElement.cloneNode(true);
                header.innerHTML = "Your device doesn't support AR";
                this.#popupContent.appendChild(header);

                var recommendations = this.#popupParagraphElement.cloneNode(true);
                recommendations.innerHTML = "<b>iOS</b> or <b>Android</b> devices are recommended.";
                this.#popupContent.appendChild(recommendations);

                this.#popupContent.appendChild(this.#popupSeperatorLineElement.cloneNode(true));

                var subHeader = this.#popupParagraphElement.cloneNode(true);
                subHeader.innerHTML = "Please open this URL on a supported device.";
                this.#popupContent.appendChild(subHeader);
                break;
            case 'missingSupportAndroid':
                //Cloning Elements and setting Values
                var header = this.#popupHeaderElement.cloneNode(true);
                header.innerHTML = "Your browser doesn't support AR";
                this.#popupContent.appendChild(header);

                var recommendations = this.#popupParagraphElement.cloneNode(true);
                recommendations.innerHTML = "<b>Chrome</b> is recommended.";
                this.#popupContent.appendChild(recommendations);

                this.#popupContent.appendChild(this.#popupSeperatorLineElement.cloneNode(true));

                var subHeader = this.#popupParagraphElement.cloneNode(true);
                subHeader.innerHTML = "Please open this URL in a supported browser of your choice.";
                this.#popupContent.appendChild(subHeader);

                var copyURLContainer = this.#popupCopyElement.cloneNode(true);
                var currentURL = copyURLContainer.querySelector("." + PausAR_Popup.#copyTextfieldClassName);
                if(currentURL) {
                    currentURL.innerHTML = this.#popupLink;
                }
                this.#addClipboardFunctions(copyURLContainer);
                this.#popupContent.appendChild(copyURLContainer);
                break;
            case 'missingSupportQuicklook':
                //Cloning Elements and setting Values
                var header = this.#popupHeaderElement.cloneNode(true);
                header.innerHTML = "Your browser doesn't support AR";
                this.#popupContent.appendChild(header);

                var recommendations = this.#popupParagraphElement.cloneNode(true);
                recommendations.innerHTML = "<b>Safari</b>, <b>Chrome</b> or <b>Firefox</b> are recommended.";
                this.#popupContent.appendChild(recommendations);

                this.#popupContent.appendChild(this.#popupSeperatorLineElement.cloneNode(true));

                var subHeader = this.#popupParagraphElement.cloneNode(true);
                subHeader.innerHTML = "Please open this URL in a supported browser of your choice.";
                this.#popupContent.appendChild(subHeader);

                var copyURLContainer = this.#popupCopyElement.cloneNode(true);
                var currentURL = copyURLContainer.querySelector("." + PausAR_Popup.#copyTextfieldClassName);
                if(currentURL) {
                    currentURL.innerHTML = this.#popupLink;
                }
                this.#addClipboardFunctions(copyURLContainer);
                this.#popupContent.appendChild(copyURLContainer);
                break;
            case 'missingSupportQuicklookSocial':

                //Cloning Elements and setting Values
                var header = this.#popupHeaderElement.cloneNode(true);
                header.innerHTML = "Your browser doesn't support AR";
                this.#popupContent.appendChild(header);

                var recommendations = this.#popupParagraphElement.cloneNode(true);
                recommendations.innerHTML = "<b>Safari</b>, <b>Chrome</b> or <b>Firefox</b> are recommended.";
                this.#popupContent.appendChild(recommendations);

                this.#popupContent.appendChild(this.#popupSeperatorLineElement.cloneNode(true));
                //Menu
                var menuHint = this.#popupBoxElement.cloneNode(true);
                var menuHintBoxWrapper = menuHint.querySelector("." + PausAR_Popup.#textboxWrapper);
                if(menuHintBoxWrapper) {
                    var menuHintParagraph = this.#popupParagraphElement.cloneNode(true);
                    menuHintParagraph.innerHTML = "Click on the Options (•••) Menu and open this website in your default browser";
                    menuHintBoxWrapper.appendChild(menuHintParagraph);
                    this.#popupContent.appendChild(menuHint);
                }

                this.#popupContent.appendChild(this.#popupSeperatorLabelElement.cloneNode(true));
                //Chrome
                var subHeader = this.#popupParagraphElement.cloneNode(true);
                subHeader.innerHTML = "Please open this URL in a supported browser of your choice.";
                this.#popupContent.appendChild(subHeader);
                var chromeLinkBox = this.#popupChromeLink.cloneNode(true);
                let chromeButton = chromeLinkBox.querySelector("#"+PausAR_Popup.#chromeButtonID);
                if(chromeButton) {
                    this.#addPopupEventListener('click', chromeButton, this.#startChrome, true);
                }
                this.#popupContent.appendChild(chromeLinkBox);

                this.#popupContent.appendChild(this.#popupSeperatorLabelElement.cloneNode(true));
                
                //Link
                var copyURLContainer = this.#popupCopyElement.cloneNode(true);
                var currentURL = copyURLContainer.querySelector("." + PausAR_Popup.#copyTextfieldClassName);
                if(currentURL) {
                    currentURL.innerHTML = this.#popupLink;
                }
                this.#addClipboardFunctions(copyURLContainer);
                this.#popupContent.appendChild(copyURLContainer);
                break;
            //case 'missingSupportQuicklookLinkedin':
                //break;

            case 'autostartLoader': 
                //Cloning Elements and setting Values
                var header = this.#popupHeaderElement.cloneNode(true);
                header.innerHTML = "AR Scene is loading...";
                this.#popupContent.appendChild(header);

                this.#popupContent.appendChild(this.#popupSeperatorLineElement.cloneNode(true));

                var subHeader = this.#popupParagraphElement.cloneNode(true);
                subHeader.innerHTML = "Please wait for the scene to start automatically or abort the loading process";
                this.#popupContent.appendChild(subHeader);
                break;
        
            default:
                console.warn("[PausAR]: popupStateName is invalid");
                break;
        }

        //Open Popup

        document.body.appendChild(this.#popupElement);

        //Adding additional EventHandlers
        let popupBackdrop = this.#popupElement.querySelector("." + PausAR_Popup.#backdropClassName);
        let popupCloseButton = this.#popupElement.querySelector("." + PausAR_Popup.#closeButtonClassName);
        if(popupBackdrop) {
            this.#addPopupEventListener('click', popupBackdrop, this.closePopup, true);
        }
        if(popupCloseButton) {
            this.#addPopupEventListener('click', popupCloseButton, this.closePopup, true);
        }
        if(this.#popupScrollbox) {
            this.#addPopupEventListener('scroll', this.#popupScrollbox, this.#checkScrollbarHorizontal, true);
            this.#addPopupEventListener('resize', window, function() {self.#checkScrollbarHorizontal(self.#popupScrollbox);});
            this.#checkScrollbarHorizontal(this.#popupScrollbox);            
        }        
        //---
        if(this.#popupObserver == null) {
            if(typeof MutationObserver === 'function') {
                this.#popupObserver = new MutationObserver(function(mutations_list) {
                    mutations_list.forEach(function(mutation) {
                        mutation.removedNodes.forEach(function(removed_node) {
                            if(removed_node == self.#popupElement) {
                                console.warn('[PausAR]: Popup was forcibly removed. Event listeners are being cleaned up');
                                self.closePopup(true);
                            }
                        });
                    });
                });
                this.#popupObserver.observe(document.body, { subtree: false, childList: true });
            }            
        }        
        //---

        //this.#originalBodyOverflow = document.body.style.overflow;
        //this.#originalBodyTouchAction = document.body.style.touchAction;
        //document.body.style.overflow = "hidden";
        //document.body.style.touchAction = "none";


        
    }

    closePopup(ignoreOpen) {
        if(ignoreOpen == true) {

        } else {
            if(!this.#isOpen()) {
                return ;
            }
            this.#popupElement.remove();
        }

        //this.#popupContent.innerHTML = "";

        //document.body.style.overflow = this.#originalBodyOverflow;
        //document.body.style.touchAction = this.#originalBodyTouchAction;
        
        this.#clearEvents();
        
        this.#popupElement = this.#popupScrollbox = this.#popupContent = null;
        
    }

    #clearEvents() {
        for(let i = 0; i < this.#popupEventListeners.length; i++) {
            this.#popupEventListeners[i].target.removeEventListener(this.#popupEventListeners[i].type, this.#popupEventListeners[i].callback);
        }
        this.#popupEventListeners = [];

        //Scroll Event
        if(this.#popupContent) {
            this.#popupContent.style.webkitMaskImage = null;
            this.#popupContent.style.maskImage = null;
        }
        

        //Abort timeouts
        if (this.#clipboardTimeout) {
            clearTimeout(this.#clipboardTimeout);
            this.#clipboardTimeout = null;
        }
        if(this.#chromeTimeout) {
            clearTimeout(this.#chromeTimeout);
            this.#chromeTimeout = null;
        }
        if(this.#chromeInnerTimeout) {
            clearTimeout(this.#chromeInnerTimeout);
            this.#chromeInnerTimeout = null;
        }

        if(this.#popupObserver != null) {
            if(typeof this.#popupObserver.disconnect === 'function') {
                this.#popupObserver.disconnect();
            }            
            this.#popupObserver = null;
        }
    }

    #addClipboardFunctions(copyURLContainer) {
        const self = this;

        if(!copyURLContainer) {
            return ;
        }

        if(QueryList.getClipboardApiPermission) {
            QueryList.getClipboardApiPermission().then(function(res) {
                self.#clipboardAPIPermission = res;
                
                let copyButton = copyURLContainer.querySelector("."+PausAR_Popup.#copyButtonClassName);
                let copyTextfield = copyURLContainer.querySelector("."+PausAR_Popup.#copyTextfieldClassName);

                if(self.#clipboardAPIPermission == null) {
                    if(copyButton) {
                        if(copyURLContainer.contains(copyButton)) {
                            copyButton.remove();
                            copyButton = null;
                        }                        
                    }
                    return ;
                }
                
                if(copyButton) {
                    self.#addPopupEventListener('click', copyButton, self.#copyURL, true);
                }
                if(copyTextfield) {
                    self.#addPopupEventListener('click', copyTextfield, self.#copyURL, true);
                }

            });
        }
    }

    #addPopupEventListener(eventType, target, callback, binding) {
        const self = this;
        if(typeof eventType !== 'string') {
            return ;
        }
        if(eventType == "") {
            return ;
        }
        if(typeof target !== 'object') {
            return ;
        }
        
        /*
        if(window !== target && document !== target && top.window !== target && top.document !== target) {
            if(!target.tagName) {
                return ;
            }
        }
        */
        
        if(typeof callback !== 'function') {
            return ;
        }

        if(binding == true) {
            try {
                target.addEventListener(eventType, callback.bind(this));
            } catch (error) {
                console.warn("[PausAR]: EventListener could not be added to target element");
            }            
        } else {
            try {
                target.addEventListener(eventType, callback);
            } catch (error) {
                console.warn("[PausAR]: EventListener could not be added to target element");
            }
        }
        
        this.#popupEventListeners.push({
            "type": eventType,
            "target": target,
            "callback": callback
        });
    }

    addExternalCloseEventListener(callback) {

        if(typeof callback !== 'function') {
            return ;
        }
        if(typeof this.#popupElement === 'undefined' || this.#popupElement == null) {
            console.warn("[PausAR]: Could not add EventListener before opening a popup template.");
            return ;
        }

        let popupBackdrop = this.#popupElement.querySelector("." + PausAR_Popup.#backdropClassName);
        let popupCloseButton = this.#popupElement.querySelector("." + PausAR_Popup.#closeButtonClassName);
        if(popupBackdrop) {
            this.#addPopupEventListener('click', popupBackdrop, callback, true);
        }
        if(popupCloseButton) {
            this.#addPopupEventListener('click', popupCloseButton, callback, true);
        }
    }

    #isOpen() {
        let popupInstance = document.querySelector("."+PausAR_Popup.#containerClassName);
        return popupInstance != null;
    }

    /**
     * Returns a reference to the DOM Element of the entire Popup, but only while it is opened. Each instance gets deletet after closing.
     * @returns DOM Element
     */
    getPopupElement() {
        return this.#popupElement;
    }

    #copyURL() {

        if(!this.#isOpen()) {
            this.#clearEvents();
            return ;
        }

        const self = this;

        if(this.#clipboardAPIPermission == null) {
            return ;
        }

        let copyStatus = this.#popupContent.querySelector("."+PausAR_Popup.#copyStatusClassName);
        switch (this.#clipboardAPIPermission) {
            case "navigator.clipboard.write":
                const type = "text/plain";
                const blob = new Blob([this.#popupLink], { type });
                const data = [new ClipboardItem({ [type]: blob })];
                navigator.clipboard.write(data).then(() => {
                        //success
                        if(copyStatus) {    
                            copyStatus.innerHTML = "✔️ Link copied";
                            if (self.#clipboardTimeout) {
                                clearTimeout(self.#clipboardTimeout);
                                self.#clipboardTimeout = null;
                            }
                            self.#clipboardTimeout = setTimeout(function () {
                                if (copyStatus) {
                                    copyStatus.innerHTML = PausAR_Popup.#copyStatusText;
                                }
                                clearTimeout(self.#clipboardTimeout);
                                self.#clipboardTimeout = null;
                            }, 4000);
                        }
                    },
                    () => {
                        //failure
                    }
                );
                break;
            case "navigator.clipboard.writeText":
                navigator.clipboard.writeText(this.#popupLink).then(() => {
                    //success
                    if(copyStatus) {
                        copyStatus.innerHTML = "✔️ Link copied";
                        if (self.#clipboardTimeout) {
                            clearTimeout(self.#clipboardTimeout);
                            self.#clipboardTimeout = null;
                        }
                        self.#clipboardTimeout = setTimeout(function () {
                            if (copyStatus) {
                                copyStatus.innerHTML = PausAR_Popup.#copyStatusText;
                            }
                            clearTimeout(self.#clipboardTimeout);
                            self.#clipboardTimeout = null;
                        }, 4000);
                    }
                },
                () => {
                    //failure
                });
                break;
            case "document.execCommand":
                let dummyTextInput = document.createElement("input");
                dummyTextInput.setAttribute("type", "text");
                dummyTextInput.style.zIndex = "-99999";
                dummyTextInput.style.position = "fixed";
                dummyTextInput.style.top = "-99999px";
                dummyTextInput.style.left = "-99999px";
                dummyTextInput.value = this.#popupLink;
                if(!this.#popupContent) {
                    return ;
                }
                self.#popupContent.appendChild(dummyTextInput);
                dummyTextInput.select();
                document.execCommand("copy");
                dummyTextInput.blur();
                dummyTextInput.remove();
                if (copyStatus) {
        
                    copyStatus.innerHTML = "✔️ Link copied";
        
                    if (this.#clipboardTimeout) {
                        clearTimeout(this.#clipboardTimeout);
                        this.#clipboardTimeout = null;
                    }
                    this.#clipboardTimeout = setTimeout(function () {
                        if (copyStatus) {
                            copyStatus.innerHTML = PausAR_Popup.#copyStatusText;
                        }
                        clearTimeout(self.#clipboardTimeout);
                        self.#clipboardTimeout = null;
                    }, 4000);
                }
                break;
        
            default:
                break;
        }
        
    }


    /**
     * iOS only (iPhone, iPad, iPod) - Opens the Link in Chrome, if installed. (Works for Safari and most popular (non Chrome) Browsers)
     */
    #startChrome() {

        if(!this.#isOpen()) {
            this.#clearEvents();
            return ;
        }

        const self = this;
        window.location.replace("googlechrome://" + window.location.hostname + window.location.pathname);
        //Fallback causes trouble on firefox

        let chromeLinkParagraph = self.#popupContent.querySelector("."+PausAR_Popup.#chromeStatusClassname);
        let chromeButton = self.#popupContent.querySelector("#"+PausAR_Popup.#chromeButtonID);
        let chromeButtonOpacity = 1;
        if(chromeButton) {
            chromeButtonOpacity = chromeButton.style.opacity;
        }

        if(this.#chromeTimeout) {
            return ;
        }

        this.#chromeTimeout = setTimeout(function () {
            //if(confirm("Chrome could not be startet or is not installed. Would you like copy the URL?")) {
                //window.location = PausAR_Popup.#chromeiTunesLink;
            //}
            //alert("Chrome could not be startet or is not installed. Please use another option.");

            if(chromeLinkParagraph && chromeButton) {
                
                chromeLinkParagraph.innerHTML = "❌ Chrome could not be startet or is not installed. Please use another option.";
                chromeButton.disabled = true;
                chromeButton.style.opacity = .25;

                if(self.#chromeInnerTimeout) {
                    clearTimeout(self.#chromeInnerTimeout);
                    self.#chromeInnerTimeout = null;
                }

                self.#chromeInnerTimeout = setTimeout(function() {
                    chromeLinkParagraph.innerHTML = PausAR_Popup.#chromeLinkStatus;
                    chromeButton.disabled = false;
                    chromeButton.style.opacity = chromeButtonOpacity;

                    clearTimeout(self.#chromeInnerTimeout);
                    self.#chromeInnerTimeout = null;
                    
                }, 8000);
            }
            clearTimeout(self.#chromeTimeout);
            self.#chromeTimeout = null;
        },1500);
        
    }

    /**
   * 
   * @param {Event} e 
   */
    #checkScrollbarVertical(e) {

        if(!this.#isOpen()) {
            this.#clearEvents();
            return ;
        }

        if (typeof e === 'undefined' || e == null) {
            console.warn("\'e\' is not defined");
            return;
        }



        let target = e;

        if (e.type) {
            if (e.type != "scroll") {
                return;
            }
            target = e.target;
        }




        if (target.scrollWidth <= target.offsetWidth) {
            //removes mask (not essential, but reduces calculations)
            target.style.webkitMaskImage = "linear-gradient(to right, black 0%, black 100%)";
            target.style.maskImage = "linear-gradient(to right, black 0%, black 100%)";

        } else {

            let scrollValue = target.scrollWidth - target.scrollLeft;
            let leftGradient = (((target.scrollWidth - scrollValue) / PausAR_Popup.#scrollBarMaskWidth) - 1) * (-1);
            let rightGradient = (((scrollValue - target.offsetWidth) / PausAR_Popup.#scrollBarMaskWidth) - 1) * (-1);

            if (leftGradient < 0) {
                leftGradient = 0;
            }
            if (rightGradient < 0) {
                rightGradient = 0;
            }
            target.style.webkitMaskImage = "linear-gradient(to right, rgba(0,0,0," + leftGradient + ") 0px, black " + PausAR_Popup.#scrollBarMaskWidth + "px, black calc(100% - " + PausAR_Popup.#scrollBarMaskWidth + "px), rgba(0,0,0," + rightGradient + "))";
            target.style.maskImage = "linear-gradient(to right, rgba(0,0,0," + leftGradient + ") 0px, black " + PausAR_Popup.#scrollBarMaskWidth + "px, black calc(100% - " + PausAR_Popup.#scrollBarMaskWidth + "px), rgba(0,0,0," + rightGradient + "))";

        }


    }

#checkScrollbarHorizontal(e) {

    if(!this.#isOpen()) {
        this.#clearEvents();
        return ;
    }

    if(typeof e === 'undefined' || e == null) {
        return ;
    }
      
    let target = e;
    
    if(e.type) {
       if(e.type != "scroll") {
           return ;
       }
       target = e.target;
    } 
   
   


   if(target.scrollHeight <= target.offsetHeight) {
       //removes mask (not essential, but reduces calculations)
       target.style.webkitMaskImage = "linear-gradient(to bottom, black 0%, black 100%)";
       target.style.maskImage = "linear-gradient(to bottom, black 0%, black 100%)";
       
   } else {

       let scrollValue = target.scrollHeight - target.scrollTop;
       let topGradient = (((target.scrollHeight - scrollValue) / PausAR_Popup.#scrollBarMaskWidth) - 1) * (-1);
       let bottomGradient = (((scrollValue - target.offsetHeight) / PausAR_Popup.#scrollBarMaskWidth) - 1) * (-1);

       if(topGradient < 0) {
           topGradient = 0;
       }
       if(bottomGradient < 0) {
            bottomGradient = 0;
       }  
       target.style.webkitMaskImage = "linear-gradient(to bottom, rgba(0,0,0,"+ topGradient + ") 0px, black " + PausAR_Popup.#scrollBarMaskWidth + "px, black calc(100% - " + PausAR_Popup.#scrollBarMaskWidth + "px), rgba(0,0,0," + bottomGradient + "))";
       target.style.maskImage = "linear-gradient(to bottom, rgba(0,0,0,"+ topGradient + ") 0px, black " + PausAR_Popup.#scrollBarMaskWidth + "px, black calc(100% - " + PausAR_Popup.#scrollBarMaskWidth + "px), rgba(0,0,0," + bottomGradient + "))";
       
   }
   
   
}

//Helper

/**
 * Checks, if a string is encoded using encodeURIComponent()
 * @param {String} str 
 * @returns 
 */
#isStringEncoded(str) {
    if(typeof str !== 'string') {
        return false;
    }
    try {
        return decodeURIComponent(str) !== str;
    } catch(e) {
        return false;
    }

}

/**
 * Decodes encoded URI strings. Multiple encodings are also removed recursively until a fully decoded string is returned.
 * @param {String} str encoded URI
 * @returns 
 */
#finalDecodeURI(str) {

    if (typeof str !== 'string') {
        return "";
    }

    let lastState = str;

    while (this.#isStringEncoded(str) == true) {
        lastState = str;
        try {
            str = decodeURIComponent(str);
        } catch (err) {
            return lastState;
        }
    }
    return str;
}



}
export {PausAR_Popup};