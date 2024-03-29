<?php
echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:b3mn="http://b3mn.org/2007/b3mn"
      xmlns:ext="http://b3mn.org/2007/ext"
      xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
      xmlns:atom="http://b3mn.org/2007/atom+xhtml">
    <head profile="http://purl.org/NET/erdf/profile">
        <title>Oryx-Editor - Oryx</title>
        <!-- libraries -->
        <script src="{base_url}jscript/bpm/lib/prototype-1.5.1.js" type="text/javascript" ></script>
        <script src="{base_url}jscript/bpm/lib/path_parser.js" type="text/javascript" ></script>
        <script src="{base_url}jscript/bpm/lib/ext-2.0.2/adapter/ext/ext-base.js" type="text/javascript" ></script>
        <script src="{base_url}jscript/bpm/lib/ext-2.0.2/ext-all.js" type="text/javascript" ></script>
        <script src="{base_url}jscript/bpm/lib/ext-2.0.2/color-field.js" type="text/javascript" ></script>
        <style media="screen" type="text/css">
            @import url("{base_url}jscript/bpm/lib/ext-2.0.2/resources/css/ext-all.css");
            @import url("{base_url}jscript/bpm/lib/ext-2.0.2/resources/css/xtheme-gray.css");
        </style>
        <!-- oryx editor -->
        <!-- language files -->
        <script src="{base_url}jscript/bpm/i18n/translation_en_us.js" type="text/javascript" ></script>
        <script src="{base_url}jscript/bpm-dna2/profiles/oryx.core.uncompressed.js" type="text/javascript" ></script>
        <script src="{base_url}jscript/bpm-dna2/profiles/bpmn2.0.js" type="text/javascript" ></script>
        <!-- custom functions here -->
        <!--
        <script src="{base_url}jscript/bpm-dna2/plugins/scripts/file.js" type="text/javascript" />
        <script src="{base_url}jscript/bpm-dna2/plugins/scripts/feedback.js" type="text/javascript" />
        -->
        <link rel="Stylesheet" media="screen" href="{base_url}jscript/bpm/css/theme_norm.css" type="text/css" />
        <!-- erdf schemas -->
        <link rel="schema.dc" href="http://purl.org/dc/elements/1.1/" />
        <link rel="schema.dcTerms" href="http://purl.org/dc/terms/" />
        <link rel="schema.b3mn" href="http://b3mn.org" />
        <link rel="schema.oryx" href="http://oryx-editor.org/" />
        <link rel="schema.raziel" href="http://raziel.org/" />
        <script type='text/javascript'>

            if(!ORYX.CONFIG) ORYX.CONFIG = {};
            //console.log('ORYX.CONFIG.ROOT_PATH',ORYX.CONFIG.ROOT_PATH);
            //ORYX.CONFIG.ROOT_PATH='../../jscript/';
            //console.log('ORYX.CONFIG.PROFILE_PATH',ORYX.CONFIG.PROFILE_PATH);
            //ORYX.CONFIG.PATH='../../picoto';
            //ORYX.CONFIG.PLUGINS_CONFIG=ORYX.CONFIG.PROFILE_PATH + 'bpm-dna2/bpmn2.0.xml';

            ORYX.CONFIG.PLUGINS_CONFIG='{base_url}jscript/bpm-dna2/profiles/bpmn2.0.xml';
            ORYX.CONFIG.SSET='{base_url}jscript/bpm-dna2/stencilsets/bpmn2.0/bpmn2.0.json';
            ORYX.CONFIG.SERVER_HANDLER_ROOT ='./';
            ORYX.CONFIG.SSEXTS=[];

            if ('undefined' == typeof(window.onOryxResourcesLoaded)) {
                ORYX.Log.warn('No adapter to repository specified, default used. You need a function window.onOryxResourcesLoaded that obtains model-JSON from your repository');

                window.onOryxResourcesLoaded = function() {
                    
                    if (location.hash.slice(1).length == 0 || location.hash.slice(1).indexOf('new')!=-1){
                        var stencilset=ORYX.Utils.getParamFromUrl('stencilset')?ORYX.Utils.getParamFromUrl('stencilset'):'stencilsets/bpmn2.0/bpmn2.0.json';

                        new ORYX.Editor({id: 'oryx-canvas123',stencilset: {url: ''+stencilset}});
                    }else{
                        ORYX.Editor.createByUrl('{module_url}respository/load/{idwf}/json', {id: 'oryx-canvas123'});
                    }
                     
                    ORYX.Editor.createByUrl('{module_url}respository/load/{idwf}/json', {id: 'oryx-canvas123'});
                } //---end function
            }
            ORYX.Editor.createByUrl('{module_url}repository/load/model/{idwf}/json', {id: 'oryx-canvas123'});
        </script>
    </head>
    <body style="overflow:hidden;">
        <div class='processdata' style='display:none'>
        </div>
    </body>
</html>