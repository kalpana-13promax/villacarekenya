<?php

// error();
class CustomFields extends db
{
    private $db;

    public function __construct()
    {

        parent::__construct();
        // global $boj;
        // $this->mysqli = $this->mysqli;

    }

    /**
     * Get all custom fields
     */
    public function getAllFields()
    {
        $query = "SELECT * FROM custom_fields ORDER BY sort_order, field_label";
        $result = $this->mysqli->query($query);

        $fields = [];
        while ($row = $result->fetch_assoc()) {
            if ($row['field_type'] === 'select' || $row['field_type'] === 'multiselect') {
                $row['options'] = json_decode($row['options'], true);
            }
            $fields[] = $row;
        }
        return $fields;
    }

    /**
     * Get field by ID
     */
    public function getField($id)
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM custom_fields WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if ($row['field_type'] === 'select' || $row['field_type'] === 'multiselect') {
                $row['options'] = json_decode($row['options'], true);
            }
            return $row;
        }
        return null;
    }

    /**
     * Create new custom field
     */
    public function createField($data)
    {
        $stmt = $this->mysqli->prepare("
            INSERT INTO custom_fields 
            (field_name, field_label, field_type, placeholder, default_value, options, 
             is_required, min_value, max_value, min_length, max_length, file_types, file_max_size, sort_order) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $options = null;
        if (in_array($data['field_type'], ['select', 'multiselect']) && !empty($data['options'])) {
            $options = json_encode($data['options']);
        }

        $stmt->bind_param(
            "ssssssiiiiisii",
            $data['field_name'],
            $data['field_label'],
            $data['field_type'],
            $data['placeholder'],
            $data['default_value'],
            $options,
            $data['is_required'],
            $data['min_value'],
            $data['max_value'],
            $data['min_length'],
            $data['max_length'],
            $data['file_types'],
            $data['file_max_size'],
            $data['sort_order']
        );

        return $stmt->execute();
    }

    /**
     * Update custom field
     */
    public function updateField($id, $data)
    {
        $stmt = $this->mysqli->prepare("
            UPDATE custom_fields 
            SET field_label = ?, field_type = ?, placeholder = ?, default_value = ?, options = ?,
                is_required = ?, min_value = ?, max_value = ?, min_length = ?, max_length = ?,
                file_types = ?, file_max_size = ?, sort_order = ?
            WHERE id = ?
        ");

        $options = null;
        if (in_array($data['field_type'], ['select', 'multiselect']) && !empty($data['options'])) {
            $options = json_encode($data['options']);
        }

        $stmt->bind_param(
            "sssssiiiiisiii",
            $data['field_label'],
            $data['field_type'],
            $data['placeholder'],
            $data['default_value'],
            $options,
            $data['is_required'],
            $data['min_value'],
            $data['max_value'],
            $data['min_length'],
            $data['max_length'],
            $data['file_types'],
            $data['file_max_size'],
            $data['sort_order'],
            $id
        );

        return $stmt->execute();
    }

    /**
     * Delete custom field
     */
    public function deleteField($id)
    {
        $stmt = $this->mysqli->prepare("DELETE FROM custom_fields WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Assign field to module
     */
    public function assignFieldToModule($module, $fieldId, $sortOrder = 0)
    {
        $stmt = $this->mysqli->prepare("
            INSERT INTO module_custom_fields (module_name, custom_field_id, sort_order) 
            VALUES (?, ?, ?) 
            ON DUPLICATE KEY UPDATE sort_order = ?, is_active = 1
        ");
        $stmt->bind_param("siii", $module, $fieldId, $sortOrder, $sortOrder);
        return $stmt->execute();
    }

    /**
     * Remove field from module
     */
    public function removeFieldFromModule($module, $fieldId)
    {
        $stmt = $this->mysqli->prepare("
            UPDATE module_custom_fields 
            SET is_active = 0 
            WHERE module_name = ? AND custom_field_id = ?
        ");
        $stmt->bind_param("si", $module, $fieldId);
        return $stmt->execute();
    }

    /**
     * Get fields for specific module
     */
    public function getModuleFields($module)
    {
        $stmt = $this->mysqli->prepare("
            SELECT cf.*, mcf.sort_order 
            FROM custom_fields cf
            INNER JOIN module_custom_fields mcf ON cf.id = mcf.custom_field_id
            WHERE mcf.module_name = ? AND mcf.is_active = 1
            ORDER BY mcf.sort_order, cf.field_label
        ");
        $stmt->bind_param("s", $module);
        $stmt->execute();
        $result = $stmt->get_result();

        $fields = [];
        while ($row = $result->fetch_assoc()) {
            if ($row['field_type'] === 'select' || $row['field_type'] === 'multiselect') {
                $row['options'] = json_decode($row['options'], true);
            }
            $fields[] = $row;
        }
        return $fields;
    }

    /**
     * Save custom field value
     */
    public function saveFieldValue($module, $recordId, $fieldId, $value)
    {
        // Handle multiselect as JSON

        if (is_array($value)) {
            $value = json_encode($value);
        }

        $stmt = $this->mysqli->prepare("
            INSERT INTO custom_field_values (module_name, record_id, custom_field_id, field_value) 
            VALUES (?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE field_value = ?
            ");
        if (!$stmt) {
            error_log("err" . $this->mysqli->error, 3, 'logs/custom_field.log');
        }
        $stmt->bind_param("siiss", $module, $recordId, $fieldId, $value, $value);
        return $stmt->execute();
    }

    /**
     * Get field values for record
     */
    public function getFieldValues($module, $recordId)
    {
        $stmt = $this->mysqli->prepare("
            SELECT cfv.id,cfv.custom_field_id,cfv.field_value,cf.field_type
            FROM custom_field_values cfv
        left JOIN custom_fields cf ON cfv.custom_field_id = cf.id
            WHERE cfv.module_name = ? AND cfv.record_id = ?
        ");
        $stmt->bind_param("si", $module, $recordId);
        $stmt->execute();
        $result = $stmt->get_result();

        $values = [];
        while ($row = $result->fetch_assoc()) {
            if ($row['custom_field_id'] === 'multiselect') {
                $row['field_value'] = json_decode($row['field_value'], true);
            }
            $values[$row['custom_field_id']] = $row;
        }
        return $values;
    }

    function displayFields($module, $recordId = null)
    {
        // echo '<pre>';
        if (!empty($recordId)) {
            $leadFiel = $this->getFieldValues($module, $recordId);
        }
        // print_r($leadFiel);
        $leadFields = $this->getModuleFields($module);
        // print_r($leadFields);
        $panel = '<div class="panel-body" style="border: 1px solid #ccc; padding: 10px; border-radius: 5px; margin-bottom: 20px;">';

        // Toggle button with class instead of id
        $panel .= '<button type="button" class="toggle-section btn-toggle btn btn-xs" style="padding: 8px 15px; cursor: pointer; background-color: #3089e8; color: #fff; border: none; border-radius: 4px; margin-bottom:10px;">
                    <i class="fa fa-caret-down"></i> Additional Information
               </button>';

        $panel .= '<div class="field-container" style=" margin-top: 10px;">';

        foreach ($leadFields as $field) {

            if (!empty($leadFiel[$field['id']])) {
                $value = $leadFiel[$field['id']]['field_value'];
            } else {
                $value = $field['default_value'];
            }
            $field['field_value'] = $value;
            // print_r($field);
            $panel .= $this->renderField($field);
        }

        $panel .= '</div></div>';

        return $panel;
    }

    /**
     * Generate field HTML for forms
     */
    public function renderField($field = [])
    {
        $value = !empty($field['field_value']) ? $field['field_value'] : $field['default_value'];

        $required = $field['is_required'] ? 'required' : '';
        $name = "custom_field[{$field['id']}]";

        switch ($field['field_type']) {
            case 'text':
            case 'email':
            case 'number':
                return "
                    <div class='form-group'>
                        <label class='col-sm-4 control-label' for='field_{$field['id']}'>{$field['field_label']}</label>
                        <div class='col-sm-8'>
                        <input type='{$field['field_type']}' 
                               id='field_{$field['id']}' 
                               name='{$name}' 
                               class='form-control' 
                               placeholder='{$field['placeholder']}' 
                               value='{$value}' 
                               {$required}
                               maxlength='{$field['max_length']}'>
                    </div>
                    </div>
                ";

            case 'textarea':
                return "
                    <div class='form-group'>
                        <label class='col-sm-4 control-label' for='field_{$field['id']}'>{$field['field_label']}</label>
                        <div class='col-sm-8'>
                        <textarea id='field_{$field['id']}' 
                                  name='{$name}' 
                                  class='form-control' 
                                  placeholder='{$field['placeholder']}' 
                                  {$required}
                                  rows='3'>{$value}</textarea>
                    </div>
                    </div>
                ";

            case 'select':
                $options = '';
                foreach ($field['options'] as $option) {
                    $selected = $option['value'] == $value ? 'selected' : '';
                    $options .= "<option value='{$option['value']}' {$selected}>{$option['label']}</option>";
                }
                return "
                    <div class='form-group'>
                        <label class='col-sm-4 control-label' for='field_{$field['id']}'>{$field['field_label']}</label>
                        <div class='col-sm-8'>
                        <select id='field_{$field['id']}' name='{$name}' class='form-control' {$required}>
                            <option value=''>Select {$field['field_label']}</option>
                            {$options}
                        </select>
                    </div>
                    </div>
                ";

            case 'multiselect':
                $options = '';
                $defaultValues = is_array($value) ? $value : [];
                foreach ($field['options'] as $option) {
                    $selected = in_array($option['value'], $defaultValues) ? 'selected' : '';
                    $options .= "<option value='{$option['value']}' {$selected}>{$option['label']}</option>";
                }
                return "
                    <div class='form-group'>
                        <label class='col-sm-4 control-label' for='field_{$field['id']}'>{$field['field_label']}</label>
                        <div class='col-sm-8'>
                        <select id='field_{$field['id']}' name='{$name}[]' class='form-control' multiple {$required}>
                            {$options}
                        </select>
                        </div>
                    </div>
                ";

            case 'checkbox':
                $checked = $value ? 'checked' : '';
                return "
                    <div class='form-group'>
                        <label class='col-sm-4 control-label' for='field_{$field['id']}'>{$field['field_label']}</label>
                        <div class='col-sm-8'>
                            <label>
                                <input type='checkbox' id='field_{$field['id']}' name='{$name}' value='1' {$checked} {$required}>
                                {$field['field_label']}
                            </label>
                        </div>
                    </div>
                ";

            case 'date':
            case 'datetime':
                $type = $field['field_type'] === 'datetime' ? 'datetime-local' : 'date';
                return "
                    <div class='form-group'>
                        <label class='col-sm-4 control-label' for='field_{$field['id']}'>{$field['field_label']}</label>
                        <div class='col-sm-8'>
                        <input type='{$type}' 
                               id='field_{$field['id']}' 
                               name='{$name}' 
                               class='form-control' 
                               value='{$value}' 
                               {$required}>
                    </div>
                    </div>
                ";

            case 'file':
                return "
                    <div class='form-group'>
                        <label class='col-sm-4 control-label' for='field_{$field['id']}'>{$field['field_label']}</label>
                        <div class='col-sm-8'>
                        <input type='file' 
                               id='field_{$field['id']}' 
                               name='{$name}' 
                               class='form-control' 
                               accept='{$field['file_types']}' 
                               {$required}>
                        <small class='text-muted'>Max file size: " . ($field['file_max_size'] / 1024) . "MB</small>
                    </div>
                    </div>
                ";
        }
    }
}
