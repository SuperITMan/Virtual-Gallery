<!-- Add user page -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $iniLang["CREATIONS"]["ADD_A_CREATION"];?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

<?php
if (isset($_POST["creationName"], $_POST["creationType"])) {
    if($creationId = addCreation($_POST))
        displaySuccessMessage("L'ajout s'est passé avec succès !! id:".$creationId);
//        echo "<script>window.location = \"index.php?p=creations&c=all&id=".$creationId."\";</script>";
    else
        displayErrorMessage("Il y a eu un problème...");
}
?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php echo $iniLang["CREATIONS"]["ADDING_OF_A_CREATION_ON_YOUR_WEBSITE"];?>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <!--                    <div class="container"-->
                    <form id="newCreationForm" class="form-horizontal add-creation" role="form" method="POST" action="index.php?p=creations&c=add">
                        <div class="form-group">
                            <label for="creationName" class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">
                                <?php echo $iniLang["CREATIONS"]["CREATION_NAME"];?> *
                            </label>
                            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-7">
                                <input type="text"
                                       id="creationName"
                                       name="creationName"
                                       placeholder="<?php echo $iniLang["CREATIONS"]["CREATION_NAME"];?>"
                                       class="form-control"
                                       autofocus
                                       value="<?php echo empty($_POST["creationName"])?"":htmlspecialchars($_POST["creationName"]);?>"
                                       required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="shortDescription" class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">
                                <?php echo $iniLang["CREATIONS"]["SHORT_DESCRIPTION"];?>
                            </label>
                            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-7">
                                <textarea rows="2"
                                          id="shortDescription"
                                          name="shortDescription"
                                          placeholder="<?php echo $iniLang["CREATIONS"]["SHORT_DESCRIPTION"];?>"
                                          class="form-control"
                                          autofocus><?php
                                    echo empty($_POST["shortDescription"])?"":htmlspecialchars($_POST["shortDescription"]);
                                ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="longDescription" class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">
                                <?php echo $iniLang["CREATIONS"]["LONG_DESCRIPTION"];?>
                            </label>
                            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-7">
                                <textarea rows="4"
                                          id="longDescription"
                                          name="longDescription"
                                          placeholder="<?php echo $iniLang["CREATIONS"]["LONG_DESCRIPTION"];?>"
                                          class="form-control"
                                          autofocus><?php
                                    echo empty($_POST["longDescription"])?"":htmlspecialchars($_POST["longDescription"]);
                                    ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="creationType" class="col-sm-12 col-md-3 col-lg-2 control-label">
                                <?php echo $iniLang["CREATIONS"]["CREATION_TYPE"];?> *
                            </label>
                            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-7">
                                <div class="radio">
                                    <label>
                                        <input value="BEJEWELD" type="radio" name="creationType" required>
                                        <?php echo $iniLang["CREATIONS"]["TYPES"]["BEJEWELED"];?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input value="PAINT" type="radio" name="creationType" required>
                                        <?php echo $iniLang["CREATIONS"]["TYPES"]["PAINT"];?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input value="SCULPTURE" type="radio" name="creationType" required>
                                        <?php echo $iniLang["CREATIONS"]["TYPES"]["SCULPTURE"];?>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="usedMaterials" class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">
                                <?php echo $iniLang["CREATIONS"]["USED_MATERIALS"];?>
                            </label>
                            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-7">
                                <input type="text"
                                       id="usedMaterials"
                                       name="usedMaterials"
                                       placeholder="<?php echo $iniLang["CREATIONS"]["USED_MATERIALS"];?>"
                                       class="form-control"
                                       autofocus
                                       value="<?php echo empty($_POST["usedMaterials"])?"":htmlspecialchars($_POST["usedMaterials"]);?>"
                                       >
                                <span class="help-block"><?php echo $iniLang["FORMS"]["PUT_COMMA_TO_SEPARATE"];?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="images" class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">
                                <?php echo $iniLang["CREATIONS"]["IMAGES"];?>
                            </label>
                            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-7">
                                <button type="button" class="btn btn-primary" id="buttonUpload">
                                    <?php echo $iniLang["CREATIONS"]["UPLOAD_IMAGES"];?>
                                </button>
                                <div id="progress-wrp" class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0;">
                                        0%
                                    </div>
                                </div>
                                <div id="imagePreview"></div>
                            </div>
                            <input type="hidden" name="imagesIds" id="imagesIds">
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-9">
                                <button type="submit" class="btn btn-primary btn-block"><?php echo $iniLang["COMMON"]["VERBS"]["ADD"];?></button>
                            </div>
                        </div>
                    </form>

                    <form id="modalUploadImages" class="hidden" action="" method="POST" enctype="multipart/form-data">
                        <input hidden type="file" name="imageInput[]" id="imageInput" class="hidden" multiple>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="upload-form" class="hidden" method="post"></form>
<script>
    var uploadedImages = [];
    var $formHTML = $("#upload-form");

    $(document).ready(function() {
        $("#buttonUpload").on("click", function(e) {
            e.preventDefault();
            $("#imageInput").click();
        });

        $("#imageInput").on("change", (function(e) {
            e.preventDefault();
            $("message").empty();
            $("loading").show();

            var $this = $(this);
            var files = $this[0].files;
            var $file;

           for (i=0; i<files.length; i++) {

               $file = new FormData($formHTML);
               $file.append("imageInput", files[i]);

               $.ajax({
                    type: "POST",
                    url: "upload.php",
                    data: $file,
                    contentType: false,
                    cache: false,
                    processData: false,
                    mimeType: "multipart/form-data",
                    headers: {
                        "Authorization": "Bearer <?php echo $_SESSION["token"];?>"
                    },
                    xhr: function () {
                        var xhr = $.ajaxSettings.xhr();
                        if (xhr.upload) {
                            xhr.upload.addEventListener("progress", function (event) {
                                var percent = 0;
                                var position = event.loaded || event.position;
                                var total = event.total;
                                if (event.lengthComputable) {
                                    percent = Math.ceil(position / total * 100);
                                }
                                //update progressbar
                                $("#progress-wrp .progress-bar").attr('aria-valuenow', percent).text(percent + "%").css("width", percent + "%");
                            }, true);
                        }
                        return xhr;
                    },
                    success: function (data, textStatus) {
                        setTimeout(function () {
                            $("#progress-wrp .progress-bar").attr('aria-valuenow', 0).text(0 + "%").css("width", +0 + "%");
                            console.log(data);
                            data = jQuery.parseJSON(data);
                            uploadedImages.push(data["id"]);
                            $("#imagesIds").val(uploadedImages);
                            $("#modalUploadImages")[0].reset();
                            $("#imagePreview").append("<img class='preview-thumbnail-image' src='" + data["link"] + "'/>");
                        }, 500);
                    },
                   error: function(data) {
                       console.log(data);
                   }
                });
           }
           if (uploadedImages.length > 0) {
               $("#imagesIds").val(uploadedImages.serializeArray());
           }
//            $.ajax({
//                type: "POST",
//                url: "/upload.php",
//                data: new FormData($("#modalUploadImages")[0]),
//                contentType: false,
////               crossDomain: true,
//                cache: false,
//                processData: false,
//                mimeType: "multipart/form-data",
//                headers: {
//                    "Authorization": "Bearer <?php //echo $_SESSION["token"];?>//"
//                },
//                xhr: function () {
//                    var xhr = $.ajaxSettings.xhr();
//                    if (xhr.upload) {
//                        xhr.upload.addEventListener("progress", function (event) {
//                            var percent = 0;
//                            var position = event.loaded || event.position;
//                            var total = event.total;
//                            if (event.lengthComputable) {
//                                percent = Math.ceil(position / total * 100);
//                            }
//                            console.log("Ca semble progresser");
//                            console.log(percent);
//                            //update progressbar
//                            $("#progress-wrp .progress-bar").attr('aria-valuenow', percent).text(percent + "%").css("width", percent + "%");
//                        }, true);
//                    }
//                    return xhr;
//                },
//                success: function (data, textStatus) {
//                    setTimeout(function () {
//                        $("#progress-wrp .progress-bar").attr('aria-valuenow', 0).text(0 + "%").css("width", +0 + "%");
//                        data = jQuery.parseJSON(data);
//                        uploadedImages.push(data["id"]);
////                       $("#modalUploadImages")[0].reset();
//                        $("#imagePreview").append("<img class='preview-thumbnail-image' src='" + data["link"] + "'/>");
//                    }, 500);
//                }
////                error: function (result, textStatus, errorThrown) {
////                    console.log(result);
////                    console.log(textStatus);
////                    console.log(errorThrown);
////                }
//            });
//           }).trigger('ajax').done(function (data) {
//               $("#progress-wrp .progress-bar").attr('aria-valuenow', 100).text(100 + "%").css("width", +100 + "%");
//               setTimeout(function(){
//                   $("#progress-wrp .progress-bar").attr('aria-valuenow', 0).text(0 + "%").css("width", +0 + "%");
//                   data = jQuery.parseJSON(data);
//                   uploadedImages.push(data["id"]);
//                   $("#modalUploadImages")[0].reset();
//                   $("#imagePreview").append("<img class='preview-thumbnail-image' src='"+data["link"]+"'/>");
//               }, 500);
//                $("#modalUploadImages")[0].reset();

//               $("#progress-wrp .progress-bar").css("width", + 0 + "%");
//               $("#progress-wrp .status").text("");


//           });
        }));
    });
</script>