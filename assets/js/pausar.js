import { QueryList } from "./queryModule.js?ver=1.1";//{ QueryList as queryList }
import { PausAR_Popup as Popup } from "./popupModule.js?ver=1.1";

//Global variables, which are used throughout all AR functions
let startARButtons = [];         //Array of all available AR Buttons (or for current version)
let widgetContainers = [];       //Array of all available Widgets (or for current version)
let modelPreviews = [];          //JSON Array with ALL ModelViewers and sources (needed for fullscreen mode)
let currentLoadingPreviews = new Map(); //A KeyMap of all "modelPreviews" 3D viewers (not just the current widget) that are currently loading a 3D model
let queryPopup;
let xrarSupport = false;
let ignoreAutostart = false;    //Set to FALSE, if the autostart was already triggert or a other ar scene did start
//fullscreen Variables
let originalOverflow = null;
let originalTouchAction = null;
let escapeListener;
let fullscreenListener;
let fullscreenTransition = false;

//window.onmessage = function(event) {
//    iframeLinkOverwrite = event.data;
//};

if (document.readyState === "complete" || document.readyState === "loaded") {
    initAdditionalEvents();
} else {
    document.addEventListener("DOMContentLoaded", initAdditionalEvents);
}

function initAdditionalEvents() {

    QueryList.isXRARDevice().then(function(result) {
        xrarSupport = result;
        //Add Elementor Popup EventListener
        if(typeof jQuery !== 'undefined' && jQuery != null) {
            jQuery( document ).on( 'elementor/popup/show', ( event, id, instance ) => {
                init();
            } );
        }
        //run init() once, after DOMContentLoaded
        init();
    }).catch(
        function(err) {
            xrarSupport = false;
            console.warn("XR Query could not perform a navigator.xr test");
            init();
        }
    );
    
}

/**
 * Adds Eventlisteners to all PausAR Elements
 */
function init() {

    let arSupport = true;//WebXR exluded
    queryPopup = new Popup();
    
    //Non-Support Query (Device and OS)
    if(QueryList.isAndroid()) {
        if(QueryList.getAndroidVersion() < 7) {
            arSupport = false;
        }
    } else if(QueryList.isIOS()) {
        //if(!QueryList.isQuicklook()) {
        //    arSupport = false;
        //}
    } else if(!QueryList.isDesktop() && QueryList.hasTouchScreen()) {
        //Windows-Phone or other misc mobile devices
        arSupport = false;
    }

    //startARButtons = document.querySelectorAll(".pausAR-UI-ArButton[pausar_version='elementor']");
    //widgetContainers = document.querySelectorAll("div.pausAR-UI-widgetContainer[pausar_version='elementor']");
    startARButtons = document.querySelectorAll(".pausAR-UI-ArButton[pausar]");
    widgetContainers = document.querySelectorAll("div.pausAR-UI-widgetContainer[pausar]");
    for(let x = 0; x < widgetContainers.length; x++) {
        let modelPreview = widgetContainers[x].querySelector("model-viewer.pausAR-UI-modelViewer[pausar]");
        if(modelPreview) {
            modelPreviews.push({
                "viewer": modelPreview,
                "widget": widgetContainers[x],
                "touchAction" : modelPreview.getAttribute('touch-action'),
            });
        }
    }

    //-------------
    //Start Button Handler
    //-------------
    
    for(let i = 0; i < startARButtons.length; i++) {

        validatePaths(startARButtons[i]);

        let androidModel = (startARButtons[i].getAttribute("paus_glb") != null && startARButtons[i].getAttribute("paus_glb") != "") ? true : false;
        let iosModel = (startARButtons[i].getAttribute("paus_ios") != null && startARButtons[i].getAttribute("paus_ios") != "") ? true : false;
        

        //3D Model Query (Missing files?)
        if((QueryList.isAndroid() && !androidModel) || (QueryList.isIOS() && !iosModel)) {
            startARButtons[i].remove();
        }
        
        if(!arSupport) {
            startARButtons[i].remove();
        } else {
            startARButtons[i].addEventListener('click', startModelSession);
        }

    }

    //-------------
    //Fullscreen Handler
    //-------------

    for(let j = 0; j < widgetContainers.length; j++) {
        let innerFullscreenButton = widgetContainers[j].querySelector("div.pausAR-UI-modelPreviewContainer .pausAR-UI-modelPreviewInterface button.pausAR-UI-fullscreenButton[pausar]");
        if(innerFullscreenButton) {
            innerFullscreenButton.addEventListener('click', function() {
                toggleFullscreen(widgetContainers[j], innerFullscreenButton);
            });
            innerFullscreenButton.classList.remove('pausAR-UI-elementHide');
            innerFullscreenButton.disabled = false;
        }
        
    }

    //-------------
    //Autostart
    //-------------
    
    let autoStartButton = null;
    if(QueryList.getSearchParameter) {
        let searchParameter = QueryList.getSearchParameter(false);
        for(let q = 0; q < searchParameter.length; q++) {
            if(searchParameter[q].name == QueryList.autostartParameterName) {
                //Remove parameter
                let sanatizedSearchParameter = removeArrayElement(searchParameter, q);
                let replacedSearchParameter = "";
                for(let j = 0; j < sanatizedSearchParameter.length; j++) {

                    if(sanatizedSearchParameter[j].value != "") {
                        replacedSearchParameter += sanatizedSearchParameter[j].name + "=" + sanatizedSearchParameter[j].value;
                    } else {
                        replacedSearchParameter += sanatizedSearchParameter[j].name;
                    }

                    if(j < (sanatizedSearchParameter.length-1)) {
                        replacedSearchParameter += "&";
                    }
                }
                if(replacedSearchParameter != "") {
                    replacedSearchParameter = "?" + replacedSearchParameter;
                }
                window.history.replaceState( {} , "", window.location.origin + window.location.pathname + replacedSearchParameter + window.location.hash );
                for(let r = 0; r < startARButtons.length; r++) {
                    if(startARButtons[r].getAttribute('id') == searchParameter[q].value) {
                        autoStartButton = startARButtons[r];
                        break;
                    }
                }
            }
        }
    }
    //Check if autostart can be activated
    let autostartSupport = false;
    if(QueryList.supportAutostart && autoStartButton != null) {
        autostartSupport = QueryList.supportAutostart(autoStartButton);
    }
    if(autostartSupport == true) {
        console.log("[PausAR]: Start AR scene \"" + autoStartButton.getAttribute("id") + "\" by autostart parameter.");
        if(document.body.contains(autoStartButton)) {
            if(modelPreviews.length > 0) {
                queryPopup.openPopup('autostartLoader');
                queryPopup.addExternalCloseEventListener(function () {
                    ignoreAutostart = true;//aborting autostart
                });
                function detectPreviewLoading(event) {
                    if (event.detail.reason == 'model-load') {
                        currentLoadingPreviews.set(event.target, true);
                        event.target.removeEventListener('progress', detectPreviewLoading);//Remove itself as a listener
                    }
                }
                function detectPreviewLoadingFinished(event) {
                    if (currentLoadingPreviews.has(event.target)) {//Loading of target has finished
                        currentLoadingPreviews.delete(event.target);
                    }
                    if (currentLoadingPreviews.size <= 0 && event.detail.visible == true) {
                        queryPopup.closePopup();
                        if (!ignoreAutostart) {
                            startModelSession(autoStartButton);
                        }
                        event.target.removeEventListener('model-visibility', detectPreviewLoadingFinished);//Remove itself as a listener
                    }
                }
                for (let xy = 0; xy < modelPreviews.length; xy++) {
                    modelPreviews[xy].viewer.addEventListener("progress", detectPreviewLoading);
                    modelPreviews[xy].viewer.addEventListener("model-visibility", detectPreviewLoadingFinished);
                }
            } else {
                //No 3D Preview on the website
                if(!ignoreAutostart) {
                    startModelSession(autoStartButton);
                }
            }
        } else {
            console.warn("[PausAR]: Autostart failed. The ar scene is not fully configured.");
        }
    }


}//init

function startModelSession(ev) {

    ignoreAutostart = true;

    let arButton = null

    if(typeof ev.getAttribute === 'function') {
        if(typeof ev.getAttribute("pausar") === "string") {
            arButton = ev;
        } else {
            return ;
        }
    } else {
        arButton = getTargetButton(ev.target);
    }

    if(arButton == null) {
        return ;
    }
    
    let widgetID = arButton.getAttribute("pausar");
    if(!widgetID) {
        return ;
    }

    if(arButton.getAttribute('editmode') == 'true' || arButton.getAttribute('editmode') == true) {
        console.info("[PausAR] AR Session could not be started inside the Editor");
        return ;
    }

    queryPopup.cloneColor(arButton);

    if(arButton.getAttribute('paus_iframe') !== null) {
        queryPopup.setFullscreen();
        let iframeHostLink = arButton.getAttribute('paus_iframe');
        if(typeof iframeHostLink === 'string' && iframeHostLink != "") {
            if(QueryList.validateURL) {
                iframeHostLink = QueryList.validateURL(decodeURIComponent(iframeHostLink));
            } else {
                iframeHostLink = decodeURIComponent(iframeHostLink);
            }
            if(typeof iframeHostLink === 'string') {
                queryPopup.setAnchor(arButton, iframeHostLink);//Overwrite Anchor for usage inside an iframe
            } else {
                queryPopup.setAnchor(arButton);
            }
        } else {
            queryPopup.setAnchor(arButton);
        }
    } else {
        queryPopup.setAnchor(arButton);
    }    
    
    //Query
    
    if(QueryList.isAndroid()) {
        if(QueryList.getAndroidVersion() >= 7) {
            //AR Core Device (Android)
            
            if(QueryList.isFirefox()) {
                queryPopup.openPopup("missingSupportAndroid");
            } else {
                startSceneViewer();
            }
        } else {
            //Android Device without AR Core (no support) (not reachble)
            queryPopup.openPopup("missingSupport");
        }
    } else {
        if(QueryList.isIOS()) {
            //IOSDevice
            if(QueryList.isLinkedIn() || QueryList.isInstagram() || QueryList.isFacebook()) {
                queryPopup.openPopup("missingSupportQuicklookSocial");
            } else {
                //iOS nonSocialBrowsers
                //if(QueryList.isQuicklook()) {
                    //Quicklook
                    startQuicklook();
                //} else {
                //    //nonQuicklook (not reachable)
                //    queryPopup.openPopup("missingSupportQuicklook");
                //}
            }
            
        } else {
            if(QueryList.isDesktop() || !QueryList.hasTouchScreen()) {
                //Desktop Hint (QR Code)
                queryPopup.openPopup("desktop");
            } else {
                //Windows-Phones, misc Handheld devices, etc. (not reachable)
                //queryPopup.setPopupState();
                queryPopup.openPopup("missingSupport");
            }
        }
    }
    

     //internal Helpers
     
     function startSceneViewer() {

        let androidModelFile = (arButton.getAttribute("paus_glb") != null && arButton.getAttribute("paus_glb") != "") ? "file=" + arButton.getAttribute("paus_glb") : "";
        let androidModelTitle = (arButton.getAttribute("paus_scene_title") != null && arButton.getAttribute("paus_scene_title") != "") ? "title=" + (arButton.getAttribute("paus_scene_title")) + "&" : "";   //UserInput -> Encoded
        let androidModelLink = (arButton.getAttribute("paus_scene_link") != null && arButton.getAttribute("paus_scene_link") != "") ? (arButton.getAttribute("paus_scene_link")) : "";                        //UserInput -> Encoded
        let androidModelResizable = (arButton.getAttribute("paus_scene_resizable") == true || arButton.getAttribute("paus_scene_resizable") == 'true' || arButton.getAttribute("paus_scene_resizable") == 1) ? "resizable=true&" : "resizable=false&";
        let verticalPlacement = arButton.getAttribute("paus_scene_placement") == 'vertical' ? "enable_vertical_placement=true&" : "enable_vertical_placement=false&";
        let androidSoundFile = (arButton.getAttribute("paus_scene_sound") != null && arButton.getAttribute("paus_scene_sound") != "") ? ("sound=" + arButton.getAttribute("paus_scene_sound") + "&") : "";
        let androidModelOcclusion = (arButton.getAttribute("paus_scene_occlusion") == true || arButton.getAttribute("paus_scene_occlusion") == 'true' || arButton.getAttribute("paus_scene_occlusion") == 1) ? "disable_occlusion=false" : "disable_occlusion=true";

        if(androidModelFile == "") {
            return ;
        }

        //Validate the CallToAction Link:
        
        //===================================
        //temporarily removed, due to an error when using the “contains” condition. Naive validation is therefore preferred for the URL.
        //-----------------------------------
        /*
        if(QueryList.validateURL) {
            androidModelLink = QueryList.validateURL(decodeURIComponent(androidModelLink), false, true);
            if(androidModelLink != null) {
                androidModelLink = "link=" + encodeURIComponent(androidModelLink) + "&";
            } else {
                androidModelLink = "";
            }
        } else {
            androidModelLink = "";
        }
        */
        //-----------------------------------
        //temporary replaced with naive validation (add protocol, if missing from string)
        //-----------------------------------
        androidModelLink = decodeURIComponent(androidModelLink);
        if(androidModelLink.indexOf('http://') != 0 && androidModelLink.indexOf('https://') != 0) {
            //adding missing https:// protocol
            androidModelLink = "https://" + androidModelLink;
        }
        if(QueryList.validateURL) {
            androidModelLink = QueryList.validateURL(androidModelLink, false, true);
            if(androidModelLink != null && androidModelLink != "") {
                androidModelLink = "link=" + androidModelLink + "&";
            } else {
                androidModelLink = "";
            }
        } else {
            androidModelLink = "";
        }
        //===================================
        
        const intentURL = "arvr.google.com/scene-viewer/1.1";
        const mode = "mode=ar_preferred";
        const scheme = "scheme=https";
        const svPackage = "package=com.google.ar.core";//"package=com.google.android.googlequicksearchbox;";
        const action = "action=android.intent.action.VIEW";
        const fallbackURL = "https://play.google.com/store/apps/details?id=com.google.ar.core&pcampaignid=web_share";

        //Link
        let intentLink =
            "intent://" +
            intentURL + "?" +
            androidModelFile + "&" +
            mode + "&" +
            androidModelTitle + //& included
            androidModelLink + //& included
            androidModelResizable + //& included
            verticalPlacement + //& included
            androidSoundFile + //& included
            androidModelOcclusion +
            "#Intent;" +
            scheme + ";" +
            svPackage + ";" +
            action + ";" +
            "end;";
            window.location.href = intentLink;
    }

    function startQuicklook() {

        let iosModelFile = (arButton.getAttribute("paus_ios") != null && arButton.getAttribute("paus_ios") != "") ? arButton.getAttribute("paus_ios") : "";
        let iOSModelTitle = (arButton.getAttribute("paus_scene_title") != null && arButton.getAttribute("paus_scene_title") != "") ? "checkoutTitle=" + (arButton.getAttribute("paus_scene_title")) : "";                           //UserInput -> Encoded
        let iOSModelDescription = (arButton.getAttribute("paus_scene_description") != null && arButton.getAttribute("paus_scene_description") != "") ? "checkoutSubtitle=" + (arButton.getAttribute("paus_scene_description")) : "";//UserInput -> Encoded
        let iOSModelLink = (arButton.getAttribute("paus_scene_link") != null && arButton.getAttribute("paus_scene_link") != "") ? arButton.getAttribute("paus_scene_link") : "";                                                    //UserInput -> Encoded
        let buttonText = (arButton.getAttribute("paus_scene_cta") != null && arButton.getAttribute("paus_scene_cta") != "") ? "callToAction=" + (arButton.getAttribute("paus_scene_cta")) : "callToAction=Visit";                   //UserInput -> Encoded
        let iosModelResizable = (arButton.getAttribute("paus_scene_resizable") == true || arButton.getAttribute("paus_scene_resizable") == 'true' || arButton.getAttribute("paus_scene_resizable") == 1) ? "allowsContentScaling=1" : "allowsContentScaling=0";
        if(iosModelFile == "") {
            return ;
        }

        //Validate the CallToAction Link:

        //===================================
        //temporarily removed, due to an error when using the “contains” condition. Naive validation is therefore preferred for the URL.
        //-----------------------------------
        /*
        if(QueryList.validateURL) {
            iOSModelLink = QueryList.validateURL(decodeURIComponent(iOSModelLink), false, true);
            if(iOSModelLink == null) {
                buttonText = "callToAction=Close";
            }
        } else {
            buttonText = "callToAction=Close";
        }
        */
        //-----------------------------------
        //temporary replaced with naive validation (add protocol, if missing from string)
        //-----------------------------------
        iOSModelLink = decodeURIComponent(iOSModelLink);
        if(iOSModelLink.indexOf('http://') != 0 && iOSModelLink.indexOf('https://') != 0) {
            //adding missing https:// protocol
            iOSModelLink = "https://" + iOSModelLink;
        }
        if(QueryList.validateURL) {
            iOSModelLink = QueryList.validateURL(iOSModelLink, false, true);
            if(iOSModelLink == null) {
                buttonText = "callToAction=Close";
            }
        } else {
            iOSModelLink = null;
            buttonText = "callToAction=Close";
        }
        //===================================
                
        let filename = iosModelFile;

        for(let i = filename.length-1; i >= 0; i--) {
            if(filename[i] == "/" || filename[i] == "\\") {
                filename = filename.substring(i+1, filename.length);
                break;
            }    
        }

        let usdzURL = 
        iosModelFile            +"#"+
        iosModelResizable       +"&"+
        iOSModelTitle           +"&"+
        iOSModelDescription     +"&"+
        buttonText;
        
        let downloadElement = null;
        downloadElement = document.querySelector(".pausAR-UI-HiddenButtonQuicklook[pausar=" + arButton.getAttribute("pausar") + "]");

        if(downloadElement == null || typeof downloadElement === 'undefined') {
            downloadElement = document.createElement("a");
            downloadElement.className = "pausAR-UI-HiddenButtonQuicklook";
            downloadElement.setAttribute("pausar", arButton.getAttribute("pausar"));
            downloadElement.style.display = "none";
            downloadElement.style.zIndex = -1;
            downloadElement.style.position = "fixed";
            downloadElement.style.left = -1000;
            downloadElement.style.top = -1000;
            downloadElement.style.visibility = "hidden";
            downloadElement.style.height = 0;
            downloadElement.style.width = 0;
            downloadElement.style.opacity = 0;

            arButton.parentNode.appendChild(downloadElement);
            downloadElement.appendChild(document.createElement("img"));
            
        }
        downloadElement.addEventListener("message", customCallToAction, false);
        downloadElement.setAttribute("rel", "ar");
        downloadElement.href = usdzURL;
        downloadElement.download = filename;
        downloadElement.dispatchEvent(
            new MouseEvent('click', {
                bubbles: true,
                cancelable: true,
                view: window
            })
        );
        
        function customCallToAction(event) {
            if (event.data == "_apple_ar_quicklook_button_tapped") {
                if(typeof iOSModelLink === 'string' && iOSModelLink != "") {

                    if(arButton.getAttribute('paus_iframe') !== null) {
                        //iframe mode
                        if (typeof top === 'object') {
                            try {
                                top.window.location.href = iOSModelLink;
                                return ;
                            } catch (err) {
                                console.warn("[PausAR]: iframe could not open link in the top window.");                                
                            }

                        }
                        if (window.parent == window.top) {//iframe is embedded directly on the host page and not nested in other iframes
                            try {
                                window.parent.location.href = iOSModelLink;
                                return ;
                            } catch (err) {
                                console.warn("[PausAR]: iframe could not open link in the parent window.");
                            }
                        }
                    } else {
                        //regular
                        window.location.href = iOSModelLink;
                    }
                    
                }
                //downloadElement.remove();
            }
        }
    }

}
//===


//==================================================================
// Helpers
//==================================================================


/**
 * Recursively searches for the reference to the selected AR button.
 * Clicking on an icon or label does not return the correct target-element.
 * @param {Element} target Target element that started the event.
 * @returns 
 */
function getTargetButton(target) {

    let bodyReached = false;
    do {
        for(let i = 0; i < startARButtons.length; i++) {
            if(target === startARButtons[i]) {
                return target;
            }
        }
        if(target.parentNode) {
            if(typeof target.parentNode === 'object') {
                target = target.parentNode;
                if(target === document.body) {
                    bodyReached = true;
                }
            } else {
                bodyReached = true;
            }
        } else {
            bodyReached = true;
        }
    } while(!bodyReached);
    return null;
}

/**
 * Recursively searches for the reference to the 3D preview of the selected AR button.
 * @param {Element} target AR Button (DOM element) inside the pausAR widget
 * @returns 
 */
function getTargetPreview(target) {
    //Anchor
    if(typeof target !== 'object') {
        return null;
    }
    let validTarget = false;
    for(let i = 0; i < startARButtons.length; i++) {
        if(target === startARButtons[i]) {
            validTarget = true;
        }
    }
    if(!validTarget) {
        return null;
    }
    //---
    let arButton = null;
    let currentWidgetID;
    arButton = target;
    currentWidgetID = arButton.getAttribute('pausar');

    //get widget
    let widgetContainer = null;
    do {
        for(let j = 0; j < target.classList.length; j++) {
            if(target.classList[j] == "pausAR-UI-widgetContainer") {
                widgetContainer = target;
                break;
            }
        }
        
        if(target.parentNode) {
            if(typeof target.parentNode === 'object') {
                target = target.parentNode;
                if(target === document.body) {
                    return null;
                }
            } else {
                return null;
            }
        } else {
            return null;
        }
    } while(widgetContainer == null);

    let modelPreview = widgetContainer.querySelector("model-viewer[pausar=" + currentWidgetID + "]");
    if(modelPreview != null) {
        return modelPreview;
    }
    let imagePreview = widgetContainer.querySelector(".pausAR-UI-previewContainer");
    if(imagePreview != null) {
        return imagePreview;
    }
    return null;
    
}

/**
 * Checks all file paths for protocol mismatches and corrects them. Helps reduce cross origin issues when adding paths manually.
 * Only applied to paths when the same hostname is used and SSL is used.
 * @param {Element} arButton DOM reference of a the AR Button
 * @returns 
 */
function validatePaths(arButton) {

    if(typeof arButton !== 'object') {
        return ;
    }
    let validTarget = false;
    for(let i = 0; i < startARButtons.length; i++) {
        if(arButton === startARButtons[i]) {
            validTarget = true;
        }
    }
    if(!validTarget) {
        return null;
    }
    
    if(arButton.getAttribute('editmode') == 'true' || arButton.getAttribute('editmode') == true) {
        return ;
    }
    //---

    
    if(location.protocol === 'https:') {

        //paus_glb
        /*
        let pausAndroidSrc = arButton.getAttribute('paus_glb');
        if(pausAndroidSrc != null) {
            if(pausAndroidSrc.indexOf('http://' + window.location.host) == 0) {
                arButton.setAttribute('paus_glb', ('https://' + pausAndroidSrc.substring(7, pausAndroidSrc.length)));
            }
        }
        */

        //paus_ios
        /*
        let pausIosSrc = arButton.getAttribute('paus_ios');
        if(pausIosSrc != null) {
            if(pausIosSrc.indexOf('http://' + window.location.host) == 0) {
                arButton.setAttribute('paus_ios', ('https://' + pausIosSrc.substring(7, pausIosSrc.length)));
            }
        }
        */

        //Preview src
        let pausPreviewElement = getTargetPreview(arButton);
        if(pausPreviewElement != null && typeof pausPreviewElement !== 'undefined') {
            if(pausPreviewElement.tagName == 'MODEL-VIEWER') {
                let pausPreviewSrc = pausPreviewElement.getAttribute("src");
                if(pausPreviewSrc != null) {
                    if(pausPreviewSrc.indexOf('http://' + window.location.host) == 0) {
                        pausPreviewElement.setAttribute('src', ('https://' + pausPreviewSrc.substring(7, pausPreviewSrc.length)));           
                    }
                }
            }
        }
    }
}

/**
 * Removes the element with the specified index from an array
 * @param {Array} arr The corresponding array
 * @param {Number} index Index of the element to be removed
 * @returns {Array | undefined} The reduced array | Invalid parameters return nothing
 */
function removeArrayElement(arr, index) {

    if(typeof arr === 'undefined' || arr == null) {
        return ;
    }
    if(typeof arr.length !== 'number' || typeof arr === "string") {
        return ;
    }
    if(typeof index !== 'number') {
        return arr;
    }
    if(index < 0 || index > arr.length) {
        return arr;
    }  
  
    return arr.slice(0, index).concat(arr.slice(index+1, arr.length));
    
  }

//===================================
// Fullscreen Functions
//===================================


async function toggleFullscreen(container, targetButton) {


    if(fullscreenTransition == true) {
        //console.warn("[PausAR]: Fullscreen in transition");
        return ;
    }

    let popupInstance = document.querySelector(".pausAR-UI-popupContainerDialog");
    if(popupInstance) {
        return ;
    }

    if(typeof container === 'undefined' || container == null) {return ;}
    if(typeof targetButton === 'undefined' || targetButton == null) {return ;}
    if(container.getAttribute('editmode') == 'true' || container.getAttribute('editmode') == true) {return ;}
    if(container.getAttribute('paus_iframe') !== null) {return ;}

    fullscreenTransition = true;

    if(isWidgetFullscreenActive(container)) {//Deactivate fullscreen
        
        if(isFullscreenClassActive()) {
            closeFullscreenClasses();
        }
        if(isFullscreenAPIActive()) {
            await closeFullscreenAPI();
        }

        loadViewers();
        changePanY(container);
        
        fullscreenTransition = false;

    } else {//Activate (another) fullscreen

        //Check, if other widgets are in fullscreen mode
        if(isFullscreenClassActive()) {
            closeFullscreenClasses();
        }
        if(isFullscreenAPIActive()) {
            await closeFullscreenAPI();
        }

        if(await openFullscreenAPI(container) == false) {
            openFullscreenClasses(container);//Fallback, if fullscreen API is not available
        } else {
            openFullscreenClasses(container, true);//Just add the classes, without the 'fallback'
        }

        unloadViewers(container);
        changePanY(container, true);

        fullscreenTransition = false;

    }
}

/**
 * Checks if the given widget Container contains the 'fullscreen' class.
 * @param {Element} widgetElement Reference to the widget container
 * @returns 
 */
function isWidgetFullscreenActive(widgetElement) {
    if(typeof widgetElement === 'undefined' || widgetElement == null) {
        return false;
    }
    //
    for(let j = 0; j < widgetElement.classList.length; j++) {
        if(widgetElement.classList[j] == "pausAR-UI-widgetFullscreen") {
            return true;
        }
    }
    return false;
}

function isFullscreenClassActive() {
    for(let i = 0; i < widgetContainers.length; i++) {
        for(let j = 0; j < widgetContainers[i].classList.length; j++) {
            if(widgetContainers[i].classList[j] == "pausAR-UI-widgetFullscreen") {
                return true;
            }
        }
    }
    return false;
}

function isFullscreenAPIActive() {
    
    if(document.fullscreenElement) {
        if(document.fullscreenElement.getAttribute("pausar") != null) {
            return true;
        }
    }
    if(document.webkitFullscreenElement) {
        if(document.webkitFullscreenElement.getAttribute("pausar") != null) {
            return true;
        }
    }
    if(document.msFullscreenElement) {
        if(document.msFullscreenElement.getAttribute("pausar") != null) {
            return true;
        }
    }
    return false;
}

/**
 * Removes all fullscreen classes from all widgets and removes the eventListeners.
 */
function closeFullscreenClasses() {
    for(let i = 0; i < widgetContainers.length; i++) {
        for(let j = 0; j < widgetContainers[i].classList.length; j++) {
            if(widgetContainers[i].classList[j] == "pausAR-UI-widgetFullscreen") {
                widgetContainers[i].classList.remove("pausAR-UI-widgetFullscreen");
                break;
            }
        }
    }

    if(typeof originalOverflow === 'string') {
        document.body.style.overflow = originalOverflow;
        originalOverflow = null;
    }
    if(typeof originalTouchAction === 'string') {
        document.body.style.touchAction = originalTouchAction;
        originalTouchAction = null;    
    }

    if(escapeListener != null) {
        document.removeEventListener('keydown', escapeListener);
        escapeListener = null;
    }
}

/**
 * Async Function.
 * Closes all active fullscreen widgets and removes the eventListeners.
 */
async function closeFullscreenAPI() {
    if(document.fullscreenElement || document.webkitFullscreenElement || document.msFullscreenElement) {

        if (document.exitFullscreen) {
            await document.exitFullscreen();
        } else if (document.webkitExitFullscreen) { /* Safari */
            await document.webkitExitFullscreen();
        } else if(document.webkitCancelFullScreen) { /* Safari (alternative) */
            await document.webkitCancelFullScreen();
        } else if (document.msExitFullscreen) { /* IE11 */
            await document.msExitFullscreen();
        }

    }

    if(fullscreenListener != null) {
        document.removeEventListener('fullscreenchange', fullscreenListener);
        document.removeEventListener('webkitfullscreenchange', fullscreenListener);
        fullscreenListener = null;
    }
}

function openFullscreenClasses(widget, ignoreEvents) {

    if(typeof widget === 'undefined' || widget == null) {
        return ;
    }
    //
    widget.classList.add('pausAR-UI-widgetFullscreen');

    originalOverflow = document.body.style.overflow;
    originalTouchAction = document.body.style.touchAction;
    //document.body.style.overflow = "hidden";
    //document.body.style.touchAction = "none";
    document.body.style.setProperty("overflow", "hidden", "important");
    document.body.style.setProperty("touch-action", "none", "important");

    //Events
    if (escapeListener == null && !ignoreEvents) {
        escapeListener = async function (evt) {
            evt = evt || window.event;
            var isEscape = false;
            if ("key" in evt) {
                isEscape = (evt.key === "Escape" || evt.key === "Esc");
            } else {
                isEscape = (evt.keyCode === 27);
            }
            if (isEscape) {
                //Close Fullscreen
                if(fullscreenTransition == true) {
                    return ;
                }
                fullscreenTransition = true;
                if(isFullscreenClassActive()) {
                    closeFullscreenClasses();
                }
                if(isFullscreenAPIActive()) {
                    await closeFullscreenAPI();
                }
                loadViewers();
                fullscreenTransition = false;
            }
        };
        document.addEventListener("keydown", escapeListener);
    }
}

async function openFullscreenAPI(widget) {
    if(typeof widget === 'undefined' || widget == null) {
        return ;
    }
    //
    if (document.fullscreenEnabled || /* Standard syntax */
        document.webkitFullscreenEnabled || /* Safari */
        document.msFullscreenEnabled/* IE11 */
    ) {
        if (widget.requestFullscreen) {
            try {
                await widget.requestFullscreen();
            } catch (error) {
                return false;
            }
            fullscreenAPIRequested();
        } else if (widget.webkitRequestFullscreen) { /* Safari */
            try {
                await widget.webkitRequestFullscreen();
            } catch (error) {
                return false;
            }
            fullscreenAPIRequested("webkit");
        } else if (widget.msRequestFullscreen) { /* IE11 */
            try {
                await widget.msRequestFullscreen();
            } catch (error) {
                return false;
            }
            fullscreenAPIRequested();
        } else {
            return false;//No Fullscreen API found
        }

        //Listener
        function fullscreenAPIRequested(apiType) {
            /*//Lock orientation to landscape
            if(typeof screen !== 'undefined' || screen != null) {
                if(typeof screen.oriantation !== 'undefined' || screen.orientation != null) {
                    if(typeof screen.orientation.lock === 'function') {
                        screen.orientation.lock('landscape-primary').catch(function() {console.warn("[PausAR] Screen.orientation.lock is not supported");});
                    }
                }
            }
            */
            
            if(fullscreenListener == null) {
                fullscreenListener = async function() {
                    if(!document.fullscreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) { //fullscreen API closed
                        //Clean up style changes
                        if(isFullscreenClassActive()) {
                            closeFullscreenClasses();
                        }
                        loadViewers();
                        fullscreenTransition = false;
                    }
                };
                switch (apiType) {
                    case "webkit":
                        document.addEventListener('webkitfullscreenchange', fullscreenListener);
                        break;
                
                    default:
                        document.addEventListener('fullscreenchange', fullscreenListener);
                        break;
                }   
            }            
        }
    }
}

function unloadViewers(widget) {
    if(typeof widget === 'undefined' || widget == null) {
        return ;
    }
    //
    for(let i = 0; i < modelPreviews.length; i++) {
        if(widget != modelPreviews[i].widget) {
            modelPreviews[i].viewer.showPoster();
        } else if(modelPreviews[i].viewer.loaded ) {
            modelPreviews[i].viewer.dismissPoster();
        }
    }
}

function loadViewers() {
    for(let i = 0; i < modelPreviews.length; i++) {
        modelPreviews[i].viewer.dismissPoster();
    }
}

function changePanY(widget, remove) {
    if(typeof widget === 'undefined' || widget == null) {
        return ;
    }
    if(typeof remove !== 'boolean') {
        remove = false;
    }
    //
    for(let i = 0; i < modelPreviews.length; i++) {
        if(widget == modelPreviews[i].widget) {            
            if(remove) {
                modelPreviews[i].touchAction = modelPreviews[i].viewer.getAttribute("touch-action");
                modelPreviews[i].viewer.removeAttribute("touch-action");
            } else if(modelPreviews[i].touchAction != null) {
                modelPreviews[i].viewer.setAttribute("touch-action", modelPreviews[i].touchAction);
            }
        }
    }
}