
if (document.readyState === "complete" || document.readyState === "loaded") {
    initAdditionalEvents();
} else {
    document.addEventListener("DOMContentLoaded", initAdditionalEvents);
}

let events = [];
    
function initAdditionalEvents() {
    if(typeof jQuery !== 'undefined' && jQuery != null) {
        jQuery( document ).on( 'elementor/popup/show', ( event, id, instance ) => {
            init();
        } );
    }
    init();
}

function init() {
    //startARButtons = document.querySelectorAll(".pausAR-UI-ArButton[pausar_version='pro']");
    let isEditmode = false;
    let firstArButton = document.querySelector(".pausAR-UI-ArButton[editmode]");
    if(firstArButton) {
        isEditmode = firstArButton.getAttribute('editmode') == "false" ? false : true;
    }
    if(isEditmode == true) {
        return ;
    }

    let widgetContainers = document.querySelectorAll(".pausAR-UI-widgetContainer");
    for(let i = 0; i < widgetContainers.length; i++) {
        addLoaderVisualizer(widgetContainers[i]);
    }     
      
}

function addLoaderVisualizer(domElement) {

    if(typeof domElement !== 'object') {
        return ;
    }

    let modelViewerElement = domElement.querySelector("model-viewer");
    let watermarkElement = domElement.querySelector(".pausAR-UI-watermark");   

    if(!modelViewerElement || !watermarkElement) {
        return;
    }

    if(modelViewerElement.tagName !== 'MODEL-VIEWER') {
        return ;
    }
    
    //---

    if(modelViewerElement.modelIsVisible) {

        let watermarkStatic = watermarkElement.getAttribute('pausar_src_static');
        if(typeof watermarkStatic === 'string') {
            if(watermarkStatic != '') {
                watermarkElement.setAttribute("src", watermarkStatic);
            }
        }
        //modelViewerElement.removeEventListener('model-visibility', changeWatermark);
        for(let y = 0; y < events.length; y++) {
            if(events[y].element == modelViewerElement) {
                modelViewerElement.removeEventListener('model-visibility', events[y].callback);
                events = events.splice(y,1);
            }
        }
        return;
    } else {

        let watermarkActive = watermarkElement.getAttribute('pausar_src_active');
        if(typeof watermarkActive === 'string') {
            if(watermarkActive != '') {
                watermarkElement.setAttribute("src", watermarkActive);
            }
        }        
        modelViewerElement.addEventListener("model-visibility", changeWatermark);
        events.push({"element": modelViewerElement, "callback": changeWatermark});
        return;
    }

    function changeWatermark() {

        let mv = modelViewerElement;
        let watermarkStatic = watermarkElement.getAttribute('pausar_src_static');
        if(typeof watermarkStatic === 'string') {
            if(watermarkStatic != '') {
                watermarkElement.setAttribute("src", watermarkStatic);
            }
        }
        for(let y = 0; y < events.length; y++) {
            if(events[y].element == modelViewerElement) {
                mv.removeEventListener('model-visibility', events[y].callback);
                events = events.splice(y,1);
            }
        }
    }
    
}