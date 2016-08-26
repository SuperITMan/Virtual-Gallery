<!-- All users page -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $iniLang["USERS"]["ALL_USERS"];?>  <a id="addUser" href="index.php?p=users&c=add" class="btn btn-default"><?php echo $iniLang["COMMON"]["VERBS"]["ADD"];?></a></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <?php
    if (!empty($_GET["id"]))
        displaySuccessMessage("L'ajout s'est passé avec succès !! id:".htmlspecialchars($_GET["id"]));
    ?>

    <?php $allUsers = getAllUsers(); $id="id"?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
<!--                <div class="panel-heading">-->
<!--                    --><?php //echo $iniLang["USERS"]["ALL_USERS"];?>
<!--                </div>-->
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="dataTable_wrapper">
                        <table width="100%" class="table table-striped table-bordered table-hover" id="all-users-table">
                            <thead>
                            <tr style="height = 50px;">
                                <th class="cb"></th>
                                <?php $keys = array_keys($allUsers[0]);
                                for ($i = 0; $i <count($keys); $i++) :?>
                                    <?php if($keys[$i] != $id) :?>
                                        <th><?php echo $iniLang["USERS"][strtoupper($keys[$i])];?></th>
                                    <?php endif;?>
                                <?php endfor;?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = 1;
                            foreach ($allUsers as $user):?>
                                <tr>
                                    <td class="cb"><input type="checkbox" name='users[]' value="<?php echo $user[$id];?>"/></td>
                                    <?php
                                    foreach ($user as $key => $value) {
                                        if($key != $id) {
                                            echo "<td>" . $value . "</td>";
                                        }
                                    }
                                    ?>
                                </tr>
                                <?php $i++;
                            endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#all-users-table')
                .DataTable({
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.10.12/i18n/French.json"
                    },
                    columnDefs: [
                        {"orderable": true,"orderDataType": "dom-checkbox", targets: 0},
                        {"orderable": true, "searchable": true, targets: 1},
                        {"orderable": true, "searchable": true, targets: 2}
                    ],
                    responsive: true
                });

            /*Launch script for checkboxes*/
            $('#all-users-table tr')
                .filter(':has(:checkbox:checked)')
                .addClass('selected')
                .end()
                .click(function(event) {
                    $(this).toggleClass('selected');
                    if (event.target.type !== 'checkbox') {
                        $(':checkbox', this).attr('checked', function() {
                            return !this.checked;
                        });
                    }
                });
            /*Lancement du script dataTable dans la bonne langue. Peut se changer par la suite :)*/
        });
    </script>
</div>
<!-- ./ All users page -->