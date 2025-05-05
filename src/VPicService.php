<?php
namespace MarcinJean\LaravelVPic;

use GuzzleHttp\Client;
use MarcinJean\LaravelVPic\Models\Vehicle;
use MarcinJean\LaravelVPic\Exceptions\VPicException;

class VPicService
{
    protected Client $http;
    protected string $base = 'https://vpic.nhtsa.dot.gov/api/vehicles/';

    public function __construct()
    {
        $this->http = new Client(['base_uri' => $this->base]);
    }

    /**
     * Decode a VIN or fetch from cache, returning the Vehicle model.
     *
     * @throws VPicException if API returns an application-level error
     */
    public function decode(string $vin): Vehicle
    {
        $vin = strtoupper($vin);

        // Return cached or new
        if ($vehicle = Vehicle::find($vin)) {
            return $vehicle;
        }

        $results = $this->callApi("decodevinextended/{$vin}");
        return $this->storeFromApi($vin, $results);
    }

    /**
     * Decode a WMI code via vPic.
     *
     * @param string $wmi
     * @return array
     * @throws VPicException on API error
     */
    public function decodeWmi(string $wmi): array
    {
        $wmi = strtoupper($wmi);
        $results = $this->callApi("decodewmi/{$wmi}");
        return $results;
    }

    /**
     * Unified API caller with error handling.
     *
     * @param string $endpoint
     * @return array
     * @throws VPicException
     */
    protected function callApi(string $endpoint): array
    {
        $response = $this->http->get($endpoint, ['query' => ['format' => 'json']]);
        $data = json_decode($response->getBody()->getContents(), true)['Results'] ?? [];

        // Handle ErrorCode
        $error = collect($data)->firstWhere('Variable', 'ErrorCode');
        if ($error && (int)$error['Value'] !== 0) {
            $code = (int)$error['Value'];
            $text = collect($data)->firstWhere('Variable', 'ErrorText')['Value']
                     ?? 'VPic API error';
            throw new VPicException($code, $text);
        }

        return $data;
    }

    /**
     * Persist API data and return the Vehicle instance.
     */
    protected function storeFromApi(string $vin, array $data): Vehicle
    {
        $extras = collect($data)
            ->reject(fn($item) => in_array($item['Variable'], ['ErrorCode','ErrorText'])
                || trim((string)$item['Value']) === '')
            ->mapWithKeys(fn($item) => [$item['Variable'] => $item['Value']])
            ->toArray();

        return Vehicle::create([
            'vin'   => $vin,
            'year'  => $extras['ModelYear'] ?? null,
            'make'  => $extras['Make']      ?? null,
            'model' => $extras['Model']     ?? null,
            'extra' => $extras,
        ]);
    }
}
