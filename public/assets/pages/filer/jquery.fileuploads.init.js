
/**
* Theme: Codefox Admin Template
* Author: Coderthemes
 * Email: coderthemes@gmail.com
* File Uploads
*/

$(document).ready(function(){

	'use-strict';

    //Example single
    $('#filer_input_single').filer({
        extensions: ['jpg', 'jpeg', 'png', 'gif', 'pdf'],
     //   extensions: [ 'pdf'],
        changeInput: true,
        showThumbs: true,
        addMore: false
    });

    //Example 2
    $('#filer_input').filer({
        limit: 1,
        maxSize: 1,
        extensions: ['jpg', 'jpeg', 'png', 'gif', 'pdf'],
      //  extensions: ['pdf'],
        changeInput: true,
        showThumbs: true,
        addMore: true,
        captions: {
            button: "Seleccione Archivo",
            feedback: "selecccione archivo a subir",
            feedback2: "archivos seleccionados",
            drop: "Drop file here to Upload",
            removeConfirmation: "Esta seguro de eliminar el archivo?",
            errors: {
                filesLimit: "Solo {{fi-limit}} archivo esta permitido subir.",
                filesType: "solo archivos jpg,jpeg,png,gif,pdf esta permitido subir",
                filesSize: "{{fi-name}} es demasiado grande! Suba archivos menores a {{fi-maxSize}} MB.",
                filesSizeAll: "Los archivos son demasiado grandes ! Suba archivos menores a {{fi-maxSize}} MB."
            }
        }
    });
    
        //Example 2
    $('#filer_input_pdf').filer({
        limit: 1,
        maxSize: 1,
       //S extensions: ['jpg', 'jpeg', 'png', 'gif', 'pdf'],
        extensions: ['pdf'],
        changeInput: true,
        showThumbs: true,
        addMore: true,
        captions: {
            button: "Seleccione Archivo",
            feedback: "selecccione archivo a subir",
            feedback2: "archivos seleccionados",
            drop: "Drop file here to Upload",
            removeConfirmation: "Esta seguro de eliminar el archivo?",
            errors: {
                filesLimit: "Solo {{fi-limit}} archivo esta permitido subir.",
//                filesType: "solo archivos jpg,jpeg,png,gif,pdf esta permitido subir",
                filesType: "solo archivos pdf esta permitido subir",
                filesSize: "{{fi-name}} es demasiado grande! Suba archivos menores a {{fi-maxSize}} MB.",
                filesSizeAll: "Los archivos son demasiado grandes ! Suba archivos menores a {{fi-maxSize}} MB."
            }
        }
    });

	//Example 1
    $("#filer_input45").filer({
        limit: null,
        maxSize: null,
        extensions: null,
        changeInput: '<div class="jFiler-input-dragDrop"><div class="jFiler-input-inner"><div class="jFiler-input-icon"><i class="icon-jfi-cloud-up-o"></i></div><div class="jFiler-input-text"><h3>Drag & Drop files here</h3> <span style="display:inline-block; margin: 15px 0">or</span></div><a class="jFiler-input-choose-btn btn btn-primary waves-effect waves-light">Browse Files</a></div></div>',
        showThumbs: true,
        theme: "dragdropbox",
        templates: {
            box: '<ul class="jFiler-items-list jFiler-items-grid"></ul>',
            item: '<li class="jFiler-item">\
                        <div class="jFiler-item-container">\
                            <div class="jFiler-item-inner">\
                                <div class="jFiler-item-thumb">\
                                    <div class="jFiler-item-status"></div>\
                                    <div class="jFiler-item-info">\
                                        <span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name | limitTo: 25}}</b></span>\
                                        <span class="jFiler-item-others">{{fi-size2}}</span>\
                                    </div>\
                                    {{fi-image}}\
                                </div>\
                                <div class="jFiler-item-assets jFiler-row">\
                                    <ul class="list-inline pull-left">\
                                        <li>{{fi-progressBar}}</li>\
                                    </ul>\
                                    <ul class="list-inline pull-right">\
                                        <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
                                    </ul>\
                                </div>\
                            </div>\
                        </div>\
                    </li>',
            itemAppend: '<li class="jFiler-item">\
                            <div class="jFiler-item-container">\
                                <div class="jFiler-item-inner">\
                                    <div class="jFiler-item-thumb">\
                                        <div class="jFiler-item-status"></div>\
                                        <div class="jFiler-item-info">\
                                            <span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name | limitTo: 25}}</b></span>\
                                            <span class="jFiler-item-others">{{fi-size2}}</span>\
                                        </div>\
                                        {{fi-image}}\
                                    </div>\
                                    <div class="jFiler-item-assets jFiler-row">\
                                        <ul class="list-inline pull-left">\
                                            <li><span class="jFiler-item-others">{{fi-icon}}</span></li>\
                                        </ul>\
                                        <ul class="list-inline pull-right">\
                                            <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
                                        </ul>\
                                    </div>\
                                </div>\
                            </div>\
                        </li>',
            progressBar: '<div class="bar"></div>',
            itemAppendToEnd: false,
            removeConfirmation: true,
            _selectors: {
                list: '.jFiler-items-list',
                item: '.jFiler-item',
                progressBar: '.bar',
                remove: '.jFiler-item-trash-action'
            }
        },
        dragDrop: {
            dragEnter: null,
            dragLeave: null,
            drop: null,
        },
        uploadFile: {
            url: "../plugins/jquery.filer/php/upload.php",
            data: null,
            type: 'POST',
            enctype: 'multipart/form-data',
            beforeSend: function(){},
            success: function(data, el){
                var parent = el.find(".jFiler-jProgressBar").parent();
                el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                    $("<div class=\"jFiler-item-others text-success\"><i class=\"icon-jfi-check-circle\"></i> Success</div>").hide().appendTo(parent).fadeIn("slow");
                });
            },
            error: function(el){
                var parent = el.find(".jFiler-jProgressBar").parent();
                el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                    $("<div class=\"jFiler-item-others text-error\"><i class=\"icon-jfi-minus-circle\"></i> Error</div>").hide().appendTo(parent).fadeIn("slow");
                });
            },
            statusCode: null,
            onProgress: null,
            onComplete: null
        },
		files: [
			{
				name: "Desert.jpg",
				size: 145,
				type: "image/jpg",
				file: "assets/images/file-upload/Desert.jpg"
			},
			{
				name: "overflow.jpg",
				size: 145,
				type: "image/jpg",
				file: "assets/images/file-upload/Desert.jpg"
			}
		],
        addMore: false,
        clipBoardPaste: true,
        excludeName: null,
        beforeRender: null,
        afterRender: null,
        beforeShow: null,
        beforeSelect: null,
        onSelect: null,
        afterShow: null,
        onRemove: function(itemEl, file, id, listEl, boxEl, newInputEl, inputEl){
            var file = file.name;
            $.post('../plugins/jquery.filer/php/remove_file.php', {file: file});
        },
        onEmpty: null,
        options: null,
        captions: {
            button: "Seleccione Archivo",
            feedback: "selecccione archivo a subir",
            feedback2: "archivos seleccionados",
            drop: "Drop file here to Upload",
            removeConfirmation: "Esta seguro de eliminar el archivo?",
            errors: {
                filesLimit: "Solo {{fi-limit}} archivo esta permitido subir.",
                filesType: "solo archivos jpg,jpeg,png,gif,pdf esta permitido subir",
                filesSize: "{{fi-name}} es demasiado grande! Suba archivos menores a {{fi-maxSize}} MB.",
                filesSizeAll: "Los archivos son demasiado grandes ! Suba archivos menores a {{fi-maxSize}} MB."
            }
        }
    });
});