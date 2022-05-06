<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Network;

use Exception;
use Paneric\NNOptimizer\Core\Traits\ArrayTrait;
use Paneric\NNOptimizer\Core\Traits\FileTrait;
use Paneric\NNOptimizer\DataStorage\DataStorage;

class NetworkResultsCollector
{
    use FileTrait;
    use ArrayTrait;

    private const SETTINGS_COLUMN_NUMBER = 6;
    private const ALL_MSE_COLUMN_NUMBER = 3;

    private string $sSeparator;

    private string $sScopeFolder;

    private int $iRanges;

    private int $iResultsNumber;

    private array $jaResultsFolders;

    private array $jaTrainMaxWeightsNumbers;
    private array $jaTestMaxWeightsNumbers;
    private array $jaPredictMaxWeightsNumbers;

    private int $iTrainResults;
    private int $iTestResults;
    private int $iPredictResults;

    private array $vsTrainResultsFileNames;
    private array $vsTestResultsFileNames;
    private array $vsPredictResultsFileNames;

    public function __construct(
        protected DataStorage $dataStorage
    ) {
    }

    public function init(): void
    {
        $this->jaResultsFolders = [];

        $this->iRanges = -1;

        $this->jaTrainMaxWeightsNumbers = [];
        $this->jaTestMaxWeightsNumbers = [];
        $this->jaPredictMaxWeightsNumbers = [];

        $this->iTrainResults = -1;
        $this->iTestResults = -1;
        $this->iPredictResults = -1;

        $this->vsTrainResultsFileNames = [];
        $this->vsTestResultsFileNames = [];
        $this->vsPredictResultsFileNames = [];
    }

    /**
     * @throws Exception
     */
    public function writeResultsToFile(): void
    {
        if ($this->iRanges === -1) {

            $joGeneralSettings = $this->dataStorage->getSettings('general_looper');

            $this->sSeparator = $joGeneralSettings['separator'];

            $this->sScopeFolder = $this->dataStorage->getSettingsParameter(
                    'input_scope_looper',
                    'scope_folder'
            );

            $this->iResultsNumber = (int) $this->dataStorage->getSettingsParameter(
                    'process_looper',
                    'results_number'
            );

            $this->setResultsFilesNames($joGeneralSettings);
        }

        $jaResultsSettings = $this->setJaResultsSettings(
            'train',
            $this->dataStorage->getVlsTrainResultsSettings(),
            $this->dataStorage->getResultsTrainSettingsFileName(),
            $this->dataStorage->getResultsTrainSettingsFileNameCsv()
        );

        $this->writeJaResultsWeights(
            'train',
            $jaResultsSettings,
            $this->dataStorage->getVla3dTrainResultsWeights(),
            $this->dataStorage->getResultsTrainWeightsFileName(),
            $this->dataStorage->getResultsTrainWeightsFileNameCsv()
        );

        $this->writeJaResultsAllMSE(
            $this->dataStorage->getVlvdTrainResultsAllMSE(),
            $this->dataStorage->getResultsTrainAllMseFileName(),
            $this->dataStorage->getResultsTrainAllMseFileNameCsv()
        );


        $jaResultsSettings = $this->setJaResultsSettings(
            'test',
            $this->dataStorage->getVlsTestResultsSettings(),
            $this->dataStorage->getResultsTestSettingsFileName(),
            $this->dataStorage->getResultsTestSettingsFileNameCsv()
        );

        $this->writeJaResultsWeights(
            'test',
            $jaResultsSettings,
            $this->dataStorage->getVla3dTestResultsWeights(),
            $this->dataStorage->getResultsTestWeightsFileName(),
            $this->dataStorage->getResultsTestWeightsFileNameCsv()
        );

        $this->writeJaResultsAllMSE(
            $this->dataStorage->getVlvdTestResultsAllMSE(),
            $this->dataStorage->getResultsTestAllMseFileName(),
            $this->dataStorage->getResultsTestAllMseFileNameCsv()
        );


        $jaResultsSettings = $this->setJaResultsSettings(
            'predict',
            $this->dataStorage->getVlsPredictResultsSettings(),
            $this->dataStorage->getResultsPredictSettingsFileName(),
            $this->dataStorage->getResultsPredictSettingsFileNameCsv()
        );

        $this->writeJaResultsWeights(
            'predict',
            $jaResultsSettings,
            $this->dataStorage->getVla3dPredictResultsWeights(),
            $this->dataStorage->getResultsPredictWeightsFileName(),
            $this->dataStorage->getResultsPredictWeightsFileNameCsv()
        );

        $this->writeJaResultsAllMSE(
            $this->dataStorage->getVlvdPredictResultsAllMSE(),
            $this->dataStorage->getResultsPredictAllMseFileName(),
            $this->dataStorage->getResultsPredictAllMseFileNameCsv()
        );


        $this->iRanges++;
        $this->jaResultsFolders[$this->iRanges] = $this->dataStorage->getsResultsFolder();


        $this->writeJaToJsonFile(
            $this->dataStorage->getVla2dTrainResults(),
            $this->dataStorage->getResultsTrainValuesFileName()
        );
        $this->writeJaToJsonFile(
            $this->dataStorage->getVla2dTestResults(),
            $this->dataStorage->getResultsTestValuesFileName()
        );
        $this->writeJaToJsonFile(
            $this->dataStorage->getVla2dPredictResults(),
            $this->dataStorage->getResultsPredictValuesFileName()
        );
    }
    /**
     * @throws Exception
     */
    private function setJaResultsSettings(
            string $sProcess,
            array $vlsResultsSettings,
            string $sFileNameJSON,
            string $sFileNameCSV
    ): array {
        $jaResultsSettings = $vlsResultsSettings;

        $this->writeJaToJsonFile($jaResultsSettings, $sFileNameJSON);

        $a2sResultsSettings = $this->setA2sResultsSettings($sProcess, $jaResultsSettings);

        $this->writeA2s($a2sResultsSettings, $this->sSeparator, $sFileNameCSV);

        return $jaResultsSettings;
    }
    /**
     * @throws Exception
     */
    private function setA2sResultsSettings(string $sProcess, array $jaResultsSettings): array
    {
        $iL = count($jaResultsSettings);

        $a2sResultsSettings = [];

        for ($i = 0; $i < $iL; $i++) {
            $joSettings = $jaResultsSettings[$i];

            $a2sResultsSettings[$i][0] = json_encode(
                $joSettings['input_column_looper'][$sProcess . '_target_column_keys'],
                JSON_THROW_ON_ERROR
            );
            $a2sResultsSettings[$i][1] = json_encode(
                $joSettings['input_column_looper'][$sProcess . '_input_column_keys'],
                JSON_THROW_ON_ERROR
            );


            $vsSequence = [];
            $vsSequence [0] = $joSettings['input_sequence_looper']["rows_number"];
            $vsSequence [1] = $joSettings['input_sequence_looper']["shift"];
            $vsSequence [2] = $joSettings['input_sequence_looper']["delay"];

            $a2sResultsSettings[$i][2] = json_encode($vsSequence, JSON_THROW_ON_ERROR);


            $vsScope = [];
            $vsScope [0] = $joSettings['input_scope_looper']['scope_begin'];
            $vsScope [1] = $joSettings['input_scope_looper']['scope_end'];

            $a2sResultsSettings[$i][3] = json_encode($vsScope, JSON_THROW_ON_ERROR);


            $a2sRanges = [];
            $a2sRanges[0][0] = $joSettings['input_scope_looper']['train_input_begin'];
            $a2sRanges[0][1] = $joSettings['input_scope_looper']['train_input_end'];
            $a2sRanges[1][0] = $joSettings['input_scope_looper']['test_input_begin'];
            $a2sRanges[1][1] = $joSettings['input_scope_looper']['test_input_end'];
            $a2sRanges[2][0] = $joSettings['input_scope_looper']['predict_input_begin'];
            $a2sRanges[2][1] = $joSettings['input_scope_looper']['predict_input_end'];

            $a2sResultsSettings[$i][4] = json_encode($a2sRanges, JSON_THROW_ON_ERROR);

            $a2sResultsSettings[$i][5] = json_encode(
                $joSettings['structure_looper']['nodes_in_layers'],
                JSON_THROW_ON_ERROR
            );
        }

        return $a2sResultsSettings;
    }
    /**
     * @throws Exception
     */
    private function writeJaResultsWeights(
            string $sProcess,
            array $jaResultsSettings,
            array $vla3dResultsWeights,
            string $sFileNameJSON,
            string $sFileNameCSV
    ): void {
        $jaResultsWeights = $vla3dResultsWeights;

        $this->writeJaToJsonFile($jaResultsWeights, $sFileNameJSON);

        $a2sResultsWeights = $this->setA2sResultsWeights($sProcess, $vla3dResultsWeights, $jaResultsSettings);

        $this->writeA2s($a2sResultsWeights, $this->sSeparator, $sFileNameCSV);
    }

    private function setA2sResultsWeights(
            string $sProcess,
            array $vla3dResultsWeights,
            array $jaResultsSettings
    ): array {
        $iL = count($jaResultsSettings);

        $viWeightsNumbers = [];

        for ($i = 0; $i < $iL; $i++) {
            $joSettings = $jaResultsSettings[$i];

            $viWeightsNumbers[$i] = (int) $joSettings['weight_generation_looper']['weights_number'];
        }

        $jL = $this->maxViValue($viWeightsNumbers);

        if ($sProcess === 'train') {
            $this->iTrainResults++;
            $this->jaTrainMaxWeightsNumbers[$this->iTrainResults] = $jL;
        }

        if ($sProcess === 'test') {
            $this->iTestResults++;
            $this->jaTestMaxWeightsNumbers[$this->iTestResults] = $jL;
        }

        if ($sProcess === 'predict') {
            $this->iPredictResults++;
            $this->jaPredictMaxWeightsNumbers[$this->iPredictResults] = $jL;
        }

        $a2sResultsWeights = [];

        for ($i = 0; $i < $iL; $i++) {
            $a3dWeights = $vla3dResultsWeights[$i];

            $j = -1;

            foreach ($a3dWeights as $a3dWeights0) {
                foreach ($a3dWeights0 as $a3dWeights00) {
                    foreach ($a3dWeights00 as $a3dWeights000) {
                        if ($a3dWeights000 !== 0.0 && $a3dWeights000 !== null) {
                            $j++;
                            $a2sResultsWeights[$i][$j] = $a3dWeights000;
                        }
                    }
                }
            }
        }

        return $a2sResultsWeights;
    }
    /**
     * @throws Exception
     */
    private function writeJaResultsAllMSE(
            array $vlvdResultsAllMSE,
            string $sFileNameJSON,
            string $sFileNameCSV
    ): void {
        $jaResultsAllMSE = $vlvdResultsAllMSE;

        $this->writeJaToJsonFile($jaResultsAllMSE, $sFileNameJSON);

        $a2sResultsAllMSE = $this->setA2sResultsAllMSE($vlvdResultsAllMSE);

        $this->writeA2s($a2sResultsAllMSE, $this->sSeparator, $sFileNameCSV);
    }

    private function setA2sResultsAllMSE(array $vlvdResultsAllMSE): array
    {
        $iL = count($vlvdResultsAllMSE);

        $a2sResultsAllMSE = [];

        for ($i = 0; $i < $iL; $i++) {

            $vdResultsAllMSE = $vlvdResultsAllMSE[$i];

            $a2sResultsAllMSE[$i][0] = $vdResultsAllMSE[0];
            $a2sResultsAllMSE[$i][1] = $vdResultsAllMSE[1];
            $a2sResultsAllMSE[$i][2] = $vdResultsAllMSE[2];
        }

        return $a2sResultsAllMSE;
    }

    private function setResultsFilesNames(array $joGeneralSettings): void
    {
        $this->vsTrainResultsFileNames[0] = $joGeneralSettings['results_train_summary_file_name'];
        $this->vsTrainResultsFileNames[1] = $joGeneralSettings['results_train_settings_file_name_csv'];
        $this->vsTrainResultsFileNames[2] = $joGeneralSettings['results_train_all_mse_file_name_csv'];
        $this->vsTrainResultsFileNames[3] = $joGeneralSettings['results_train_weights_file_name_csv'];
        $this->vsTrainResultsFileNames[4] = $joGeneralSettings['results_train_values_file_name_csv'];

        $this->vsTestResultsFileNames[0] = $joGeneralSettings['results_test_summary_file_name'];
        $this->vsTestResultsFileNames[1] = $joGeneralSettings['results_test_settings_file_name_csv'];
        $this->vsTestResultsFileNames[2] = $joGeneralSettings['results_test_all_mse_file_name_csv'];
        $this->vsTestResultsFileNames[3] = $joGeneralSettings['results_test_weights_file_name_csv'];
        $this->vsTestResultsFileNames[4] = $joGeneralSettings['results_test_values_file_name_csv'];

        $this->vsPredictResultsFileNames[0] = $joGeneralSettings['results_predict_summary_file_name'];
        $this->vsPredictResultsFileNames[1] = $joGeneralSettings['results_predict_settings_file_name_csv'];
        $this->vsPredictResultsFileNames[2] = $joGeneralSettings['results_predict_all_mse_file_name_csv'];
        $this->vsPredictResultsFileNames[3] = $joGeneralSettings['results_predict_weights_file_name_csv'];
        $this->vsPredictResultsFileNames[4] = $joGeneralSettings['results_predict_values_file_name_csv'];
    }
    /**
     * @throws Exception
     */
    public function writeResultsCompactedToFile(): void
    {
        $a2sResultsSummary = [];

        $iL = $this->iResultsNumber * ($this->iRanges + 1);
        $jL = self::SETTINGS_COLUMN_NUMBER + self::ALL_MSE_COLUMN_NUMBER;

        $iLTrainWeights = $this->getMaxPositiveIntValue($this->jaTrainMaxWeightsNumbers);
        $iLTestWeights = $this->getMaxPositiveIntValue($this->jaTestMaxWeightsNumbers);
        $iLPredictWeights = $this->getMaxPositiveIntValue($this->jaPredictMaxWeightsNumbers);

        $a2sTrainSummaryResults = [];
        $a2sTestSummaryResults = [];
        $a2sPredictSummaryResults = [];

        $sTrainSummaryFileName = $this->sScopeFolder . $this->vsTrainResultsFileNames[0];
        $sTestSummaryFileName = $this->sScopeFolder . $this->vsTestResultsFileNames[0];
        $sPredictSummaryFileName = $this->sScopeFolder . $this->vsPredictResultsFileNames[0];

        for ($i = 0; $i < $this->iRanges + 1; $i++) {
            $iInsertPosition = $i * $this->iResultsNumber;

            $sResultsFolder = $this->jaResultsFolders[$i];


            $sSettingsFileName = $sResultsFolder . $this->vsTrainResultsFileNames[1];
            $sAllMseFileName = $sResultsFolder . $this->vsTrainResultsFileNames[2];
            $sWeightsFileName = $sResultsFolder . $this->vsTrainResultsFileNames[3];

            $a2sResultsSummary = $this->fuseResultsSummary(
                    $sSettingsFileName,
                    $sAllMseFileName,
                    $sWeightsFileName,
                    $iLTrainWeights
            );
            $a2sTrainSummaryResults = $this->arrayCopy(
                $a2sResultsSummary,
                0,
                $a2sTrainSummaryResults,
                $iInsertPosition,
                1
            );

            $sSettingsFileName = $sResultsFolder . $this->vsTestResultsFileNames[1];
            $sAllMseFileName = $sResultsFolder . $this->vsTestResultsFileNames[2];
            $sWeightsFileName = $sResultsFolder . $this->vsTestResultsFileNames[3];

            $a2sResultsSummary = $this->fuseResultsSummary(
                    $sSettingsFileName,
                    $sAllMseFileName,
                    $sWeightsFileName,
                    $iLTestWeights
            );
            $a2sTestSummaryResults = $this->arrayCopy(
                $a2sResultsSummary,
                0,
                $a2sTestSummaryResults,
                $iInsertPosition,
                1,
            );

            $sSettingsFileName = $sResultsFolder . $this->vsPredictResultsFileNames[1];
            $sAllMseFileName = $sResultsFolder . $this->vsPredictResultsFileNames[2];
            $sWeightsFileName = $sResultsFolder . $this->vsPredictResultsFileNames[3];

            $a2sResultsSummary = $this->fuseResultsSummary(
                    $sSettingsFileName,
                    $sAllMseFileName,
                    $sWeightsFileName,
                    $iLPredictWeights
            );
            $a2sPredictSummaryResults = $this->arrayCopy(
                $a2sResultsSummary,
                0,
                $a2sPredictSummaryResults,
                $iInsertPosition,
                1
            );
        }

        $this->writeJaToJsonFile($a2sTrainSummaryResults, $sTrainSummaryFileName);
        $this->writeJaToJsonFile($a2sTestSummaryResults, $sTestSummaryFileName);
        $this->writeJaToJsonFile($a2sPredictSummaryResults, $sPredictSummaryFileName);
    }
}
