<?php
require_once('../config.php');
$boj->check_session();

header('Content-Type: application/json');

try {
    // Get POST data
    $property_id = $_POST['property_id'] ?? null;
    $template_id = $_POST['template_id'] ?? null;

    // Validate inputs
    if (!$property_id) {
        echo json_encode([
            'success' => false,
            'message' => 'Property ID is required'
        ]);
        exit;
    }

    // Fetch property details
    $property = $boj->getQuery("
        SELECT 
            pl.*,
            c.city,
            l.location,
            s.sub_location,
            o.name AS owner_name,
            o.contact AS owner_contact,
            pr.pro_name AS project_name,
            sd.slug,
            ps.status_name
        FROM property_listing pl
        LEFT JOIN city c ON c.id = pl.city
        LEFT JOIN locations l ON l.id = pl.location
        LEFT JOIN sub_location s ON s.id = pl.sub_location
        LEFT JOIN owner o ON o.id = pl.owner_id
        LEFT JOIN project pr ON pr.id = pl.project_id
        LEFT JOIN seo_data sd ON sd.related_id = pl.id AND sd.type = 'property'
        LEFT JOIN property_status ps ON ps.id = pl.status
        WHERE pl.id = $property_id
    ");


    if (empty($property)) {
        echo json_encode([
            'success' => false,
            'message' => 'Property not found'
        ]);
        exit;
    }

    $prop = $property[0];



    // Build full address
    $address_parts = array_filter([
        $prop->address,
        $prop->sub_location,
        $prop->location,
        $prop->city
    ]);
    $full_address = implode(', ', $address_parts);

    // Build property URL
    $property_url = BASEURL . 'property/property-info/?view=' . $property_id;



    // Get amenities if available
    $amenities = '';
    if (!empty($prop->property_amenities)) {
        $amenity_ids = explode(',', $prop->property_amenities);
        $amenity_list = [];
        foreach ($amenity_ids as $aid) {
            $am = $boj->getQuery("SELECT amenity_name FROM amenity WHERE id = $aid");
            if (!empty($am)) {
                $amenity_list[] = $am[0]->amenity_name;
            }
        }
        $amenities = implode(', ', $amenity_list);
    }
    // print_r($amenities);
    // Build property features text
    $features = [];
    if (!empty($prop->bedrooms))
        $features[] = $prop->bedrooms . ' BHK';
    if (!empty($prop->bathrooms))
        $features[] = $prop->bathrooms . ' Bath';
    if (!empty($prop->property_size))
        $features[] = $prop->property_size . ' ' . ($prop->measurement ?? 'Sq.ft');
    $features_text = implode(' | ', $features);


    //if perunit=0 then whole price of property and if 1 then per unit price
    // $price= $boj->price($prop->property_price);
    if ($prop->is_perunit == 1) {
        $price = $boj->price($prop->property_price . '/' . $prop->measurement);
    } else {
        $price = $boj->price($prop->property_price);
    }

    // Replace placeholders
    $placeholders = [
        '{{property_title}}' => $prop->property_title ?? '',
        '{{property_price}}' => $price ?? '',
        '{{address}}' => $full_address ?? '',
        '{{property_link}}' => $property_url ?? '',
        '{{property_type}}' => $prop->property_type ?? '',
        '{{available_for}}' => $boj->contract('property', $prop->available_for) ?? '',

        '{{size}}' => $prop->size ?? '',
        '{{measurement}}' => $prop->measurement ?? 'Sq.ft',
        '{{furnished_status}}' => $prop->furnished_status ?? '',
        '{{property_status}}' => $prop->status_name ?? '',
        '{{property_description}}' => html_entity_decode(strip_tags($prop->property_description ?? '')),

        '{{city}}' => $prop->city ?? '',
        '{{location}}' => $prop->location ?? '',
        '{{sub_location}}' => $prop->sub_location ?? '',
        '{{project_name}}' => $prop->project_name ?? '',
        '{{owner_name}}' => $prop->owner_name ?? '',
        '{{owner_contact}}' => $prop->owner_contact ?? '',
        '{{features}}' => $features_text ?? '',
        '{{property_aminities}}' => $amenities ?? '',
        '{{remark}}' => $prop->remark ?? '',

        '{{sitename}}' => SITENAME ?? '',
        '{{company_name}}' => SITENAME ?? '',
        '{{company_phone}}' => $company->phone ?? '',
        '{{company_email}}' => $company->mail ?? '',
        '{{company_address}}' => $company->address ?? '',
    ];


    $action = $_POST['action'] ?? '';

    if ($action == 'ai-generate') {


        // Build property info for AI prompt
        $propertyInfo = json_encode([
            'property_title' => $prop->property_title,
            'property_price' => $price,
            'address' => $full_address,
            'property_link' => $property_url,
            'property_type' => $prop->property_type,
            'available_for' => $boj->contract('property', $prop->available_for),
            'size' => $prop->size,
            'measurement' => $prop->measurement,
            'furnished_status' => $prop->furnished_status,
            'property_status' => $prop->status_name,
            'property_description' => html_entity_decode(strip_tags($prop->property_description ?? '')),
            'city' => $prop->city,
            'location' => $prop->location,
            'sub_location' => $prop->sub_location,
            'project_name' => $prop->project_name,
            'owner_name' => $prop->owner_name,
            'owner_contact' => $prop->owner_contact,
            'features' => $features_text,
            'property_aminities' => $amenities,
            'remark' => $prop->remark,
            'sitename' => SITENAME,
            'company_name' => SITENAME,
            'company_phone' => $company->phone,
            'company_email' => $company->mail,
            'company_address' => $company->address_line_1 . ', ' . $company->address_line_2 . ', ' . $company->city . ', ' . $company->state . ', ' . $company->pin,
        ]);


        // Get template body if selected
        $templateBody = $template_id ? ($temp->body ?? '') : '';

        // Build AI prompt
        $prompt = "You are a professional real estate marketing expert. Generate a WhatsApp marketing message for this property.

PROPERTY DETAILS:
{$propertyInfo}

" . ($templateBody ? "REFERENCE TEMPLATE:\n{$templateBody}\n\n" : "") . "
INSTRUCTIONS:
1. Create an engaging WhatsApp message in a professional tone
2. Use WhatsApp formatting: *bold* for highlights, _italic_ for emphasis
3. Include relevant emojis (🏠 🛏️ 📍 💰 📞 etc.)
4. Keep it concise but informative
5. End with a call-to-action
6. Format nicely with line breaks

Generate the WhatsApp message:";



        // Call AI API
        $ch = curl_init('https://ai.itways.in/api/generate');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'model' => 'qwen2.5:1.5b',
            'prompt' => $prompt,
            'stream' => false,
            'options' => [
                'temperature' => 0.7,
                'top_p' => 0.9,
                'max_tokens' => 500
            ]
        ]));
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200) {
            $result = json_decode($response, true);
            if (isset($result['response'])) {
                echo json_encode([
                    'success' => true,
                    'message' => trim($result['response']),
                    'property_data' => [
                        'title' => $prop->property_title,

                        'img' => !empty($prop->property_image) ? IMGPATH . $prop->property_image : LOGO,
                        'image' => !empty($prop->property_image) ? IMGPATH . $prop->property_image : LOGO,
                    ]
                ]);
                exit;
            }
        }

        echo json_encode([
            'success' => false,
            'message' => 'AI generation failed. Please try again.'
        ]);
        exit;
    } else {

        // Fetch template
        $template = $boj->getQuery("SELECT * FROM templates WHERE id = $template_id");

        if (empty($template)) {
            echo json_encode([
                'success' => false,
                'message' => 'Template not found'
            ]);
            exit;
        }


        $temp = $template[0];
        $message = $temp->body ?? '';
        // Replace all placeholders in message
        foreach ($placeholders as $placeholder => $value) {
            $message = str_replace($placeholder, $value, $message);
        }

        // Return success response
        echo json_encode([
            'success' => true,
            'message' => html_entity_decode($message),
            'property_data' => [
                'title' => $prop->property_title,
                'price' => $price,
                'address' => $full_address,
                'url' => $property_url,
                'image' => !empty($prop->property_image) ? IMGPATH . $prop->property_image : LOGO,
            ]
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>