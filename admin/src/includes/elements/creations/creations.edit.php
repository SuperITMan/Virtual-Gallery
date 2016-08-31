<!-- Add user page -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $iniLang["CREATIONS"]["EDIT_A_CREATION"];?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <?php
    if (!empty($_POST["creationName"]) && !empty($_POST["creationType"]) && !empty($_POST["imagesIds"])){
        editCreation($_POST, $_GET["id"]);
    } elseif (!empty($_POST["action"]) && !empty($_POST["creationId"])) {
        if (strcasecmp(htmlspecialchars($_POST["action"]),"delete") == 0) {
            $deleteStatus = deleteCreation($_POST["creationId"]);
        }
    }

    if (empty($deleteStatus)):?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php echo $iniLang["CREATIONS"]["EDITING_OF_A_CREATION_OF_YOUR_WEBSITE"];?>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <?php
                    if (!empty($_GET["id"])) :
                        $creation = getCreation($_GET["id"]);
                        if (!empty($creation)) :
                            $creationId = htmlspecialchars($_GET["id"]);
                    ?>
                    <!--                    <div class="container"-->
                    <form id="newCreationForm"
                          class="form-horizontal add-creation"
                          role="form"
                          method="POST"
                          action="index.php?p=creations&c=edit&id=<?php echo $creationId;?>">
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
                                       value="<?php echo empty($_POST["creationName"])?$creation["name"]:htmlspecialchars($_POST["creationName"]);?>"
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
                                    echo empty($_POST["shortDescription"])?$creation["shortDescription"]:htmlspecialchars($_POST["shortDescription"]);
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
                                    echo empty($_POST["longDescription"])?$creation["longDescription"]:htmlspecialchars($_POST["longDescription"]);
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
                                        <input value="BEJEWELED" type="radio" name="creationType"
                                            <?php echo strcmp("BEJEWELED",$creation["creationType"])==0?"checked":"";?>
                                               required>
                                        <?php echo $iniLang["CREATIONS"]["TYPES"]["BEJEWELED"];?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input value="PAINT" type="radio" name="creationType"
                                            <?php echo strcmp("PAINT",$creation["creationType"])==0?"checked":"";?>
                                               required>
                                        <?php echo $iniLang["CREATIONS"]["TYPES"]["PAINT"];?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input value="SCULPTURE" type="radio" name="creationType"
                                            <?php echo strcmp("SCULPTURE",$creation["creationType"])==0?"checked":"";?>
                                               required>
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
                                       value="<?php echo empty($_POST["usedMaterials"])?$creation["usedMaterials"]:htmlspecialchars($_POST["usedMaterials"]);?>"
                                >
                                <span class="help-block"><?php echo $iniLang["FORMS"]["PUT_COMMA_TO_SEPARATE"];?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="images" class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">
                                <?php echo $iniLang["CREATIONS"]["IMAGES"];?> *
                            </label>
                            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-7">
                                <button type="button" class="btn btn-primary" id="buttonUpload">
                                    <?php echo $iniLang["CREATIONS"]["UPLOAD_IMAGES"];?>
                                </button>
                                <span class="help-block"><?php echo $iniLang["FORMS"]["FORMATS_IMAGE_SUPPORTED"];?></span>
                                <div id="progress-wrp" class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0;">
                                        0%
                                    </div>
                                </div>
                                <div id="imagePreview">
                                    <?php if (!empty($creation["images"])):
                                        foreach ($creation["images"] as $image):?>
                                            <img class="preview-thumbnail-image"
                                                 src="/uploads/<?php echo $image["serverFileName"];?>"
                                                 alt="<?php echo $image["fileName"];?>"/>
                                    <?php
                                        endforeach;
                                    endif;?>
                                </div>
                            </div>
                            <input type="hidden"
                                   value="<?php echo $creation["imageIds"];?>"
                                   name="imagesIds" id="imagesIds" required>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-9">
                                <button type="submit" class="btn btn-primary btn-block"><?php echo $iniLang["COMMON"]["VERBS"]["EDIT"];?></button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-9">
                                <a href="#" onclick="this.preventDefault()"
                                   data-target="#myModal"
                                   data-toggle="modal"
                                   class="btn btn-danger btn-block">
                                    <?php echo $iniLang["COMMON"]["VERBS"]["REMOVE"];?>
                                </a>
                            </div>
                        </div>
                    </form>

                    <form id="modalUploadImages" class="hidden" action="" method="POST" enctype="multipart/form-data">
                        <input hidden type="file" name="imageInput[]" id="imageInput" class="hidden" multiple>
                    </form>

                    <div id="myModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title"><?php echo $iniLang["FORMS"]["ASK_CONFIRMATION"];?></h4>
                                </div>
                                <div class="modal-body">
                                    <p><?php echo $iniLang["FORMS"]["ASK_CONFIRMATION_DELETE"];?></p>
                                </div>
                                <div class="modal-footer">
                                    <form id="modalForm"
                                          method="post"
                                          action="index.php?p=creations&c=edit&id=<?php echo $creationId;?>">
                                        <input hidden type="text" name="action" value="delete">
                                        <input hidden type="text" name="creationId" value="<?php echo $creationId;?>">
                                        <button type="submit" class="btn btn-danger">
                                            <?php echo $iniLang["FORMS"]["YES_I_CONFIRM"]; ?>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <?php
                        else :
                            displayErrorMessage($iniLang["ERROR_MESSAGES"]["PASSED_ID_INCORRECT"]);
                        endif;
                    else :
                        displayErrorMessage($iniLang["ERROR_MESSAGES"]["NO_ID_IN_URL"]);
                    endif;?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<form id="upload-form" class="hidden" method="post"></form>
<script>
    var uploadedImages = <?php echo empty($creation)?"[]":json_encode(explode(",",htmlspecialchars_decode($creation["imageIds"])));?>;
    var $formHTML = document.getElementById("upload-form")[0];

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
                            data = jQuery.parseJSON(data);
                            uploadedImages.push(data["id"]);
                            $("#imagesIds").val(uploadedImages);
                            $("#modalUploadImages")[0].reset();
                            $("#imagePreview").append("<img class='preview-thumbnail-image' src='" + data["link"] + "'/>");
                        }, 500);
                    },
                    error: function(data, eee) {
                        console.log(data);
                        console.log(eee);
                    }
                });
            }
            if (uploadedImages.length > 0) {
                $("#imagesIds").val(uploadedImages);
            }
        }));
    });
</script>