<?php require_once('../../config.php');
$boj->check_session();
// include('../header.php');


$id = $_POST['projectId'];

if (intVal($id) && !empty($id)) {

    $sql = "select p.*,c.city,l.location,s.sub_location from project p left join city c on p.city= c.id left join
    locations l on p.location= l.id left join sub_location s on p.sub_location= s.id where p.id = $id";
    $qry = $boj->getQuery($sql);
} else {
    // header('location:??');
}
// echo $sql;
$value = $qry[0] ?? [];
?>



<section class="panel">
    <header class="panel-heading">

        <div class="container-fluid">
            <div class="row">

                <h1 class="panel-title " style="font-size:24px; color:brown">
                    <?php echo ucfirst($value->pro_name ?? '') ?>
                </h1>



            </div>
        </div>
    </header>
    <div id="hello">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="2" class="head-bg">Project Basic Info</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Project ID</strong></td>
                                <td><?= $value->id ?? ''; ?></td>
                            </tr>

                            <tr>
                                <td><strong>City</strong></td>
                                <td><?= $value->city ?? ''; ?></td>
                            </tr>

                            <tr>
                                <td><strong>Location</strong></td>
                                <td><?= $value->location ?? ''; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Sub Location</strong></td>
                                <td><?= $value->sub_location ?? ''; ?></td>
                            </tr>


                            <tr>
                                <td><strong>Project Area</strong></td>
                                <td><?= $value->pro_area ?? ''; ?></td>
                            </tr>


                            <tr>
                                <td><strong>Project Maximum Prize<strong></td>
                                <td><?= $value->max_prize ?? ''; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Project Minimum Prize<strong></td>
                                <td><?= $value->min_prize ?? ''; ?>
                                </td>
                            </tr>

                            <tr>
                                <td><strong>Launch Date<strong></td>
                                <td><?= $value->start_date ?? ''; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Possession Date<strong></td>
                                <td><?= $value->end_date ?? ''; ?>
                                </td>
                            </tr>





                        </tbody>
                    </table>
                </div>

                <div class="col-md-6">

                    <div id="myCarousel" class="carousel slide" data-ride="carousel">

                        <!-- Wrapper for slides -->
                        <div class="carousel-inner">

                            <?php
                            //$value=$mainResult->property_image;
                            
                            $galImg = json_decode($value->gallery ?? '') ?? [];
                            $gal = array();
                            if (!empty($galImg)) {

                                foreach ($galImg as $val) {
                                    $file = IMGPATH . $val;
                                    if (file_exists($file)) {
                                        continue;
                                    }
                                    $gal[] = $file;


                                }
                            } elseif (!empty($value->pro_image) && file_exists(IMGPATH . $value->pro_image)) {
                                $gal[] = IMGPATH . $value->pro_image;
                            } else {
                                $deflt = true;
                                $gal[] = DEFAULTIMG;
                            }

                            $active = true;
                            foreach ($gal as $sc) {


                                ?>
                                <div class="item <?= ($active) ? 'active' : '' ?>">
                                    <img src="<?= $sc ?? '' ?>"
                                        style="width:100%; height:365px;<?= ($deflt) ? 'object-fit: contain; border:1px solid blue' : 'object-fit: cover;' ?> ">
                                </div>

                                <?php $active = false;
                            } ?>
                        </div>

                        <?php if (count($gal ?? []) > 1) {

                            ?>
                            <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                <span class="glyphicon glyphicon-chevron-left"></span>

                            </a>
                            <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right"></span>

                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php if (!empty($value->pro_description)) { ?>
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <h3>Project Description: </h3>

                    <?php

                    echo $value->pro_description ?? '';
                    ?>
                </div><?php } ?>

            <br>


            <?php
            // Fetch all project type data with their field values
            $sql = "SELECT 
            pd.id as project_data_id,
            pd.project_type_id,
            pd.project_type_name,
            pt.name as type_name
        FROM project_data pd
        INNER JOIN project_types pt ON pd.project_type_id = pt.id
        WHERE pd.project_id = '{$id}'";
            $projectData = $boj->getQuery($sql) ?? [];

            // Group by project type
            $groupedByType = [];
            foreach ($projectData as $row) {
                $type = $row->type_name;
                if (!isset($groupedByType[$type])) {
                    $groupedByType[$type] = [];
                }
                $groupedByType[$type][] = $row;
            }
            ?>

            <div class="row">
                <ul class="nav nav-tabs" role="tablist">
                    <?php $typeIndex = 0;
                    foreach ($groupedByType as $typeName => $typeEntries): ?>
                        <li class="<?= $typeIndex === 0 ? 'active' : '' ?>">
                            <a href="#type<?= $typeIndex ?>" role="tab" data-toggle="tab">
                                <?= ucwords($typeName) ?>
                            </a>
                        </li>
                        <?php $typeIndex++;
                    endforeach; ?>
                </ul>

                <div class="tab-content" style="margin-top: 20px;">
                    <?php $typeIndex = 0;
                    foreach ($groupedByType as $typeName => $typeEntries): ?>
                        <div class="tab-pane fade <?= $typeIndex === 0 ? 'active in' : '' ?>" id="type<?= $typeIndex ?>">
                            <!-- Inner Tabs for each item of this type -->
                            <ul class="nav nav-tabs" role="tablist" style="margin-bottom: 15px;">
                                <?php foreach ($typeEntries as $i => $entry): ?>
                                    <li class="<?= $i === 0 ? 'active' : '' ?>">
                                        <a href="#entry<?= $entry->project_data_id ?>" role="tab" data-toggle="tab">
                                            <?= ucwords($entry->project_type_name ?: 'Default') ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>

                            <!-- Entry Content -->
                            <div class="tab-content">
                                <?php foreach ($typeEntries as $i => $entry):
                                    // Get project field values
                                    $projectFieldsQuery = "SELECT 
                            pfv.value, 
                            fl.name, 
                            fl.label, 
                            fl.type 
                        FROM project_field_value pfv
                        JOIN field_library fl ON pfv.field_id = fl.id
                        WHERE pfv.project_data_id = '{$entry->project_data_id}'
                        AND fl.field_type = 'project'";
                                    $projectFields = $boj->getQuery($projectFieldsQuery) ?? [];

                                    // Get unit data for this project data entry
                                    $unitsQuery = "SELECT 
                            ud.id as unit_data_id,
                            ut.name as unit_type_name
                        FROM unit_data ud
                        JOIN pro_unit_types ut ON ud.unit_type_id = ut.id
                        WHERE ud.project_data_id = '{$entry->project_data_id}'";
                                    $units = $boj->getQuery($unitsQuery) ?? [];

                                    // Get unit field definitions
                                    $unitFieldDefsQuery = "SELECT * FROM field_library WHERE field_type = 'unit'";
                                    $unitFieldDefs = $boj->getQuery($unitFieldDefsQuery) ?? [];
                                    ?>

                                    <div class="tab-pane fade <?= $i === 0 ? 'active in' : '' ?>"
                                        id="entry<?= $entry->project_data_id ?>">
                                        <!-- Project Field Info -->
                                        <h4><?= $entry->project_type_name ?: 'Default' ?> Details</h4>
                                        <table class="table table-bordered">
                                            <tbody>
                                                <?php foreach ($projectFields as $field): ?>
                                                    <tr>
                                                        <td><strong><?= $field->label ?? $field->name ?></strong></td>
                                                        <td>
                                                            <?php if ($field->type === 'file' && $field->value): ?>
                                                                <a href="<?= IMGPATH . 'projects/' . $field->value ?>"
                                                                    class="image-link">
                                                                    <img src="<?= IMGPATH . 'projects/' . $field->value ?>" width="100"
                                                                        style="width:100px;height:100px">
                                                                </a>
                                                            <?php else: ?>
                                                                <?= !empty($field->value) ? htmlspecialchars($field->value) : '-' ?>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>

                                        <!-- Units Info -->
                                        <?php if (!empty($unitFieldDefs) && !empty($units)): ?>
                                            <h4><?= $entry->project_type_name ?: 'Default' ?> Units</h4>
                                            <div class="row">
                                                <?php foreach ($units as $uIndex => $unit):
                                                    // Get unit field values
                                                    $unitFieldsQuery = "SELECT 
                                            ufv.value, 
                                            fl.name, 
                                            fl.label, 
                                            fl.type 
                                        FROM unit_field_value ufv
                                        JOIN field_library fl ON ufv.field_id = fl.id
                                        WHERE ufv.unit_data_id = '{$unit->unit_data_id}'";
                                                    $unitFields = $boj->getQuery($unitFieldsQuery) ?? [];
                                                    ?>

                                                    <div class="col-lg-4 col-md-3 col-sm-6 col-12">
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">
                                                                <?= $unit->unit_type_name ?> (Unit <?= $uIndex + 1 ?>)
                                                            </div>
                                                            <div class="panel-body" style="padding: 0;">
                                                                <table class="table table-bordered mb-0">
                                                                    <tbody>
                                                                        <?php foreach ($unitFieldDefs as $unitField):
                                                                            $value = '';
                                                                            foreach ($unitFields as $uf) {
                                                                                if ($uf->name === $unitField->name) {
                                                                                    $value = $uf->value;
                                                                                    break;
                                                                                }
                                                                            }
                                                                            ?>
                                                                            <tr>
                                                                                <td><strong><?= $unitField->label ?? $unitField->name ?></strong>
                                                                                </td>
                                                                                <td>
                                                                                    <?php if ($unitField->type === 'file' && !empty($value)): ?>
                                                                                        <a href="<?= IMGPATH . 'projects/' . $value ?>"
                                                                                            class="image-link">
                                                                                            <img src="<?= IMGPATH . 'projects/' . $value ?>"
                                                                                                width="100" style="width:100px;height:100px">
                                                                                        </a>
                                                                                    <?php else: ?>
                                                                                        <?= !empty($value) ? htmlspecialchars($value) : '-' ?>
                                                                                    <?php endif; ?>
                                                                                </td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php $typeIndex++;
                    endforeach; ?>
                </div>
            </div>

        </div>
    </div>
    </div>
</section>
</div>
<!--page end here-->
</section>
</div>
<?php //include_once '../footer.php' ?>
<script src="assets/vendor/jquery-validation/jquery.validate.js"></script>
<!-- Examples -->
<script src="assets/javascripts/forms/examples.validation.js"></script>
<script>
    $(document).ready(function () {
        $('.image-link').magnificPopup({ type: 'image' });
    });
</script>
<script type="text/javascript">
    function codespeedy() {
        var print_div = document.getElementById("hello");
        var print_area = window.open();
        print_area.document.write(print_div.innerHTML);
        print_area.document.close();
        print_area.focus();
        print_area.print();
        print_area.close();
    }
</script>