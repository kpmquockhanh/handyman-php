<?php
function get_setting($code, $default = null) {
  global $conn;
  $stmt = $conn->prepare("SELECT value FROM settings WHERE code = :code");
  $stmt->execute(['code' => $code]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  return $result ? $result['value'] : $default;
}

function get_services($limit = 9) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT name FROM services ORDER BY name LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching services: " . $e->getMessage());
        return [];
    }
}

function get_locations() {
    $default_locations = 'New South Wales,Sydney,Blue Mountains,Central Coast,Newcastle,Wollongong,Coffs Harbour';
    $locations_string = get_setting('LOCATIONS', $default_locations);
    return array_map('trim', explode(',', $locations_string));
}

function get_image_cdn($path) {
    $cdn_url = get_setting('CDN_URL', 'https://ict726-cdn.kpmquockhanh.site');
    // Remove leading slash if present to avoid double slashes
    $path = ltrim($path, '/');
    return $cdn_url . '/' . $path;
} 