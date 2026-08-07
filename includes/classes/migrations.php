<?php

class migrations extends db
{
    public function up()
    {
        $query = "CREATE TABLE IF NOT EXISTS `quota` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `quota_name` varchar(200) NOT NULL,
              `uploader` varchar(100) NOT NULL,
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
              PRIMARY KEY (`id`),
              CONSTRAINT FK_quotaId FOREIGN KEY (id)
            REFERENCES property_listing(quota_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



             CREATE TABLE IF NOT EXISTS `priority`(
          `id` INT PRIMARY KEY AUTO_INCREMENT,
          `priority` VARCHAR(200) NOT NULL,
          `uploader` VARCHAR(100) NOT NULL,
          `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
          CONSTRAINT FK_preorityId FOREIGN KEY (id)
            REFERENCES property_listing(priority)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


        ALTER TABLE property_listing
    ADD COLUMN IF NOT EXISTS `quota_id` INT DEFAULT NULL AFTER category,
    ADD COLUMN IF NOT EXISTS `priority` INT DEFAULT NULL AFTER quota_id;

    ALTER TABLE priority
ADD CONSTRAINT FK_priority
FOREIGN KEY (id)
REFERENCES property_listing(priority);
    
    
    ";
        


session_start();





    if ($this->mysqli->multi_query($query)) {
        $_SESSION['suc'] = ' Migrated Successfully';
} else {
    $_SESSION['fal'] = 'Something wrong!';
}
header("location: ?nav=setting");
        die;

    }


    public function down()
    {
        $query = "DROP TABLE IF EXISTS `quota`";
        $query = "DROP TABLE IF EXISTS `priority`";
        $query = "ALTER TABLE property_listing
DROP COLUMN quota_id,
DROP COLUMN priority";

        if ($this->mysqli->multi_query($query)) {
            echo "Table `quota` dropped successfully.";
        } else {
            echo "Error dropping table: " . $this->mysqli->error;
        }
    }
}





if(isset($_POST['migration-up']))
{
	$obj = new migrations();
	$obj->up ();
}
if(isset($_POST['migration-down']))
{
	$obj = new migrations();
	$obj->down ();
}


    
    ?>