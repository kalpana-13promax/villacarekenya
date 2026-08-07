<?php
if (isset($_GET['property_id'])) {
    $propertyId = $_GET['property_id'];

    // Fetch all remarks related to this property ID
    $remarksResult = $boj->getQuery("SELECT * FROM remarks_table WHERE property_id = '$propertyId'");

    // Display the remarks
    if (mysqli_num_rows($remarksResult) > 0) {
        while ($remark = mysqli_fetch_assoc($remarksResult)) {
            echo "<p>{$remark['remark']}</p>";
        }
    } else {
        echo "<p>No remarks available for this property.</p>";
}
}
?>