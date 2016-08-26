<!-- Add user page -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $iniLang["CREATIONS"]["ADD_A_CREATION"];?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

<?php
if (isset($_POST["username"], $_POST["password"])) {
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
                    <form id="newCreationForm" class="form-horizontal add-user" role="form" method="POST" action="index.php?p=users&c=add">
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
                                        <input value="0" type="radio" name="creationType" required>
                                        <?php echo $iniLang["CREATIONS"]["TYPES"]["BEJEWELED"];?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input value="1" type="radio" name="creationType" required>
                                        <?php echo $iniLang["CREATIONS"]["TYPES"]["PAINT"];?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input value="2" type="radio" name="creationType" required>
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
                                       required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="images" class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">
                                <?php echo $iniLang["CREATIONS"]["IMAGES"];?>
                            </label>
                            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-7">
<!--                                <input id="imageInput" type="file" class="file[]" data-preview-file-type="text" multiple>-->
<!--                                <button id="buttonUpload">Envoyer mes fichiers</button>-->
<!--                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">-->
<!--                                    --><?php //echo $iniLang["CREATIONS"]["UPLOAD_IMAGES"];?>
<!--                                </button>-->
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

<!--                        <div class="form-group">-->
<!--                            <label for="images" class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">-->
<!--                                --><?php //echo $iniLang["CREATIONS"]["IMAGES"];?>
<!--                            </label>-->
<!--                            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-7">-->
<!--                                <div id="images_thumbnails"></div>-->
<!--                                <input id="input-fr" name="inputfr[]" type="file" multiple class="file-loading">-->
<!--                                <!-- Button trigger modal -->-->
<!--<!--                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">-->-->
<!--<!--                                    -->--><?php ////echo $iniLang["CREATIONS"]["UPLOAD_IMAGES"];?>
<!--<!--                                </button>-->-->
<!--                            </div>-->
<!--<!--                            <input type="hidden" name="imagesIds" id="imagesIds">-->-->
<!--                        </div>-->

                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-9">
                                <button type="submit" class="btn btn-primary btn-block"><?php echo $iniLang["COMMON"]["VERBS"]["ADD"];?></button>
                            </div>
                        </div>
                    </form>
                    <form id="modalUploadImages" class="hidden" action="" method="POST" enctype="multipart/form-data">
                        <input hidden="true" type="file" name="imageInput[]" id="imageInput" class="hidden" multiple>


                        <!--                    <button type="submit" name="submit" class="btn btn-primary submit">-->
                        <!--                        Envoyer votre fichier-->
                        <!--                    </button>-->
                        <!--                    <input type="submit" value="Upload" class="submit" />-->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal-->
<!--<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">-->
<!--    <div class="modal-dialog" role="document">-->
<!--        <div class="modal-content">-->
<!--            <div class="modal-header">-->
<!--                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
<!--                <h4 class="modal-title" id="myModalLabel">--><?php //echo $iniLang["CREATIONS"]["UPLOAD_IMAGES"];?><!--</h4>-->
<!--            </div>-->
<!--            <div class="modal-body">-->
<!--                -->
<!--                <div id="progress-wrp" class="progress">-->
<!--                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0;">-->
<!--                        0%-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div id="imagePreview"></div>-->
<!--            </div>-->
<!--            <h4 id='loading' class="hide">Loading..</h4>-->
<!--            <div id="message"></div>-->
<!--            <div class="modal-footer">-->
<!--                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
<!--                <button type="button" class="apply-changes btn btn-primary">Save changes</button>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<script>
    var uploadedImages = [];
    $(document).ready(function() {
        $("#buttonUpload").on("click", function(e) {
            e.preventDefault();
            $("#imageInput").click();
        });

        // initialize with defaults
//        $("#input-id").fileinput();

        // with plugin options
//        $("#input-id").fileinput(
//            {'showUpload':false,
//              language: "fr", uploadUrl: "/file-upload-batch/2",
//                allowedFileExtensions: ["jpg", "png", "gif"], 'previewFileType':'any'});

//        $("#myModal .apply-changes").on("click", function(){
//            $("#imagesIds").val(JSON.stringify(uploadedImages));
//            $("#images_thumbnails").html($("#imagePreview").html());
//            $('#myModal').modal('hide');
//        });

        $("#imageInput").on("change", (function(e) {
            console.log("Commençons");
            //submit the form here
//        });
//        $("#modalUploadImages").on("submit", (function (e) {
           e.preventDefault();
           $("message").empty();
           $("loading").show();

            var $this = $(this);
            if (typeof this.files[count] === 'undefined') { return false; }

            console.log($("#modalUploadImages")[0]);

           $.ajax({
               type: "POST",
               url: "/upload.php",
               data: new FormData($("#modalUploadImages")[0]),
               contentType: false,
//               crossDomain: true,
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
                           console.log("Ca semble progresser");
                           console.log(percent);
                           //update progressbar
                           $("#progress-wrp .progress-bar").attr('aria-valuenow', percent).text(percent + "%").css("width", percent + "%");
                       }, true);
                   }
                   return xhr;
               },
               success: function(data, textStatus) {
                   setTimeout(function(){
                       $("#progress-wrp .progress-bar").attr('aria-valuenow', 0).text(0 + "%").css("width", +0 + "%");
                       data = jQuery.parseJSON(data);
                       uploadedImages.push(data["id"]);
//                       $("#modalUploadImages")[0].reset();
                       $("#imagePreview").append("<img class='preview-thumbnail-image' src='"+data["link"]+"'/>");
                   }, 500);
               }
               error: function(result, textStatus, errorThrown) {
                   console.log(result);
                   console.log(textStatus);
                   console.log(errorThrown);
               }
           }).trigger('ajax').done(function (data) {
//               $("#progress-wrp .progress-bar").attr('aria-valuenow', 100).text(100 + "%").css("width", +100 + "%");
//               setTimeout(function(){
//                   $("#progress-wrp .progress-bar").attr('aria-valuenow', 0).text(0 + "%").css("width", +0 + "%");
//                   data = jQuery.parseJSON(data);
//                   uploadedImages.push(data["id"]);
//                   $("#modalUploadImages")[0].reset();
//                   $("#imagePreview").append("<img class='preview-thumbnail-image' src='"+data["link"]+"'/>");
//               }, 500);
                $("#modalUploadImages")[0].reset();

//               $("#progress-wrp .progress-bar").css("width", + 0 + "%");
//               $("#progress-wrp .status").text("");


           });
        }));
    });
</script>