<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Core\Traits;

use Exception;
use JsonException;
use RuntimeException;

trait FileTrait
{
    /**
     * @throws Exception
     */
    protected function getArrayFromJson(string $path, bool $associative = false): array
    {
        $jsonData = file_get_contents($path);

        return json_decode($jsonData, $associative, 512, JSON_THROW_ON_ERROR);
    }

    protected function get2DoubleArray(string $path): array
    {
        $a2d = [];

        $csvData = file_get_contents($path);
        $lines = explode(PHP_EOL, $csvData);

        foreach ($lines as $line) {
            $vd = str_getcsv($line, ';');

            foreach ($vd as $j => $v) {
                $vd[$j] = (float) $v;
            }
            $a2d[] = $vd;

        }

        return $a2d;
    }

    protected function createDir($path): void
    {
        if (!file_exists($path) && !mkdir($path, 0777, true) && !is_dir($path)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
        }
    }

    protected function writeA2s(array $a2d, string $separator, $path): void
    {
        $csvData = '';

        foreach ($a2d as $line) {
            foreach ($line as $key => $value) {
                if (is_array($value)) {
                    $line[$key] = implode(',', $value);
                }
            }

            $csvData .= implode($separator, $line) . PHP_EOL;
        }

        file_put_contents($path, rtrim($csvData));
    }

    protected function writeA2d(array $a2d, string $separator, $path): void
    {
        $this->writeA2s($a2d, $separator, $path);
    }

    public function get2DoubleArrayPartially(string $path, int $idxStart, int $idxStop): array
    {
        $a2d = [];

        $csvData = file_get_contents($path);
        $lines = explode(PHP_EOL, $csvData);

        for ($i = $idxStart; $i < $idxStop + 1; $i++) {
            $vd = str_getcsv($lines[$i], ';');

            foreach ($vd as $j => $v) {
                $vd[$j] = (float) $v;
            }
            $a2d[] = $vd;
        }

        return $a2d;
    }
    /**
     * @throws Exception
     */
    public function writeJaToJsonFile(array $arr, string $path): void
    {
        $json = json_encode($arr, JSON_THROW_ON_ERROR);

        if (!file_put_contents($path, $json)) {
            throw new Exception('Error writing array to json file');
        }
    }
    /**
     * @throws Exception
     */
    public function fuseResultsSummary(
        string $sSettingsPath,
        string $sAllMsePath,
        string $sWeightsPath,
        int $iLWeights
    ): array {
        $viSettingsDimensions = $this->readArrayDimensions($sSettingsPath);
        $viAllMSEDimensions = $this->readArrayDimensions($sAllMsePath);
        $viWeightsDimensions = [$viSettingsDimensions[0], $iLWeights];

        return $this->readResultsSummaryFused(
            $sSettingsPath,
            $sAllMsePath,
            $sWeightsPath,
            $viSettingsDimensions,
            $viAllMSEDimensions,
            $viWeightsDimensions
        );
    }
    /**
     * @throws Exception
     */
    private function readResultsSummaryFused(
            string $sSettingsPath,
            string $sAllMsePath,
            string $sWeightsPath,
            array $viSettingsDimensions,
            array $viAllMseDimensions,
            array $viWeightsDimensions
    ): ?array {
        $fileSettings = $sSettingsPath;
        $fileAllMse = $sAllMsePath;
        $fileWeights = $sWeightsPath;

        if (
            ($viSettingsDimensions[0] === $viWeightsDimensions[0]) &&
            ($viSettingsDimensions[0] === $viAllMseDimensions[0]) &&
            (file_exists($fileSettings) && is_readable($fileSettings)) &&
            (file_exists($fileAllMse) && is_readable($fileAllMse)) &&
            (file_exists($fileWeights) && is_readable($fileWeights))
        ) {
            $iL = $viSettingsDimensions[0];
            $jL = $viSettingsDimensions[1] + $viAllMseDimensions[1] + $viWeightsDimensions[1];

            $settings = $this->readArrayCSV($fileSettings, 'int');
            $iLStageSettings = $viSettingsDimensions[1];

            $allMse = $this->readArrayCSV($fileAllMse, 'float');
            $iLStageAllMse = $viSettingsDimensions[1] + $viAllMseDimensions[1];

            $weights = $this->readArrayCSV($fileWeights, 'float');
            $iLStageWeights = $viSettingsDimensions[1] + $viAllMseDimensions[1] + $viWeightsDimensions[1];

            $arrSummary = [];

            for ($iRows = 0; $iRows < $iL; $iRows++) {
                $partsSettings = $settings[$iRows];
                $partsAllMse = $allMse[$iRows];
                $partsWeights = $weights[$iRows];

                for ($iCols = 0; $iCols < $iLStageSettings; $iCols++) {
                    $arrSummary[$iRows][$iCols] = $partsSettings[$iCols];
                }

                $j = -1;

                for ($iCols = $iLStageSettings; $iCols < $iLStageAllMse; $iCols++) {
                    $j++;

                    $arrSummary[$iRows][$iCols] = $partsAllMse[$j];
                }

                $j = -1;

                for ($iCols = $iLStageAllMse; $iCols < $iLStageWeights; $iCols++) {

                    $j++;

                    if ($j < count($partsWeights)) {
                        if (!empty($partsWeights[$j])) {
                            $arrSummary[$iRows][$iCols] = $partsWeights[$j];
                        } else {
                            $arrSummary[$iRows][$iCols] = '';
                        }
                    } else {
                        $arrSummary[$iRows][$iCols] = '';
                    }
                }
            }

            return $arrSummary;
        }

        return null;
    }
    /**
     * @throws JsonException
     */
    private function readArrayCSV(string $path, string $type): array
    {
        $a2d = [];

        $csvData = file_get_contents($path);
        $lines = explode(PHP_EOL, $csvData);

        foreach ($lines as $line) {
            $json = str_replace(';', ',', sprintf('[%s]', $line));
            $json = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

            if ($type === 'int') {
                $a2d[] = $this->convertArrayValuesToInt($json);
                continue;
            }
            if ($type === 'float') {
                $a2d[] = $this->convertArrayValuesToFloat($json);
                continue;
            }
            $a2d[] = $this->convertArrayValuesToString($json);
        }

        return $a2d;
    }

    private function convertArrayValuesToInt(array $arr): array
    {
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $arr[$key] = $this->convertArrayValuesToInt($value);
            } else {
                $arr[$key] = (int) $value;
            }
        }

        return $arr;
    }

    private function convertArrayValuesToFloat(array $arr): array
    {
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $arr[$key] = $this->convertArrayValuesToFloat($value);
            } else {
                $arr[$key] = (float) $value;
            }
        }

        return $arr;
    }

    private function convertArrayValuesToString(array $arr): array
    {
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $arr[$key] = $this->convertArrayValuesToString($value);
            } else {
                $arr[$key] = (string) $value;
            }
        }

        return $arr;
    }

    private function readArrayDimensions(string $path): array
    {
        $csvData = file_get_contents($path);
        $lines = explode(PHP_EOL, $csvData);

        $iL = 0;
        $jLMax = 0;

        foreach ($lines as $line) {
            $iL++;
            $vd = str_getcsv($line, ';');

            $jL = 0;
            foreach ($vd as $v) {
                $jL++;
            }

            $jLMax = max($jL, $jLMax);
        }

        return [$iL, $jLMax];
    }
}
