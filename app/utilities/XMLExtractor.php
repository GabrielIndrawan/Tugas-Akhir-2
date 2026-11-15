<?php 

namespace App\Utilities;
class XMLExtractor{
    public static function extractXML(string $xmlContent):array {
        $xml = simplexml_load_string($xmlContent);

        if ($xml === false) {
            throw new \Exception('Invalid WebDAV XML response.');
        }

        $xml->registerXPathNamespace('d','DAV:');

        $files = [];

        $responses = $xml->xpath("//d:response");

        foreach ($responses as $response) {

            $href = (string) $response->xpath('./d:href')[0];

            $path = parse_url($href, PHP_URL_PATH);
            $name = basename(rtrim(
                rawurldecode($path ?? ''),
                '/'
            ));

            // Skip the directory itself
            if ($name === '') {
                continue;
            }

            $propstats = $response->xpath('./d:propstat');

            foreach ($propstats as $propstat) {

                $status = (string) $propstat->xpath('./d:status')[0];

                if (!str_contains($status, '200')) {
                    continue;
                }

                $prop = $propstat->xpath('./d:prop')[0];

                $resourceType = $prop->xpath('./d:resourcetype')[0];

                $collection = $resourceType->xpath('./d:collection');

                $files[] = [
                    'name' => $name,
                    'path' => $href,
                    'size' => (int) ($prop->xpath('./d:getcontentlength')[0] ?? 0),
                    'type' => !empty($collection)
                        ? 'directory'
                        : 'file',
                    'mime_type' => (string) ($prop->xpath('./d:getcontenttype')[0] ?? ''),
                    'last_modified' => (string) ($prop->xpath('./d:getlastmodified')[0] ?? ''),
                ];

                break;
            }
        }

        return $files;
    }
}
?>