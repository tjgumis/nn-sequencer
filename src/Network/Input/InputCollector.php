<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Network\Input;

use Paneric\NNOptimizer\Core\Traits\ArrayTrait;
use Paneric\NNOptimizer\Core\Traits\FileTrait;
use Paneric\NNOptimizer\DataStorage\DataStorage;

class InputCollector
{
    use FileTrait;
    use ArrayTrait;

    private string $trainInputFileName;
    private string $trainTargetFileName;
    private string $testInputFileName;
    private string $testTargetFileName;
    private string $predictInputFileName;
    private string $predictTargetFileName;

    public function __construct(
        protected DataStorage $dataStorage
    ) {
    }

    public function init(): void
    {
        $uploadFolder = $this->dataStorage->getSettingsParameter(
            'general_looper',
            "upload_folder"
        );
        $sScopeFolder = $this->dataStorage->getSettingsParameter(
            'input_scope_looper',
            "scope_folder"
        );
        $sRangeFolder = $this->dataStorage->getSettingsParameter(
            'input_transformation_looper',
            "range_folder"
        );
        $sResultsFolder = $this->dataStorage->getSettingsParameter(
            'input_transformation_looper',
            "results_folder"
        );

        $this->dataStorage->setUploadFolder($uploadFolder);
        $this->dataStorage->setsScopeFolder($sScopeFolder);
        $this->dataStorage->setsRangeFolder($sRangeFolder);
        $this->dataStorage->setsResultsFolder($sResultsFolder);


        $resultsTrainValuesFileName = $sResultsFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "results_train_values_file_name"
            );
        $resultsTestValuesFileName = $sResultsFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "results_test_values_file_name"
            );
        $resultsPredictValuesFileName = $sResultsFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "results_predict_values_file_name"
            );

        $resultsTrainSettingsFileName = $sResultsFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "results_train_settings_file_name"
            );
        $resultsTrainWeightsFileName = $sResultsFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "results_train_weights_file_name"
            );
        $resultsTrainAllMseFileName = $sResultsFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "results_train_all_mse_file_name"
            );

        $resultsTestSettingsFileName = $sResultsFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "results_test_settings_file_name"
            );
        $resultsTestWeightsFileName = $sResultsFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "results_test_weights_file_name"
            );
        $resultsTestAllMseFileName = $sResultsFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "results_test_all_mse_file_name"
            );

        $resultsPredictSettingsFileName = $sResultsFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "results_predict_settings_file_name"
            );
        $resultsPredictWeightsFileName = $sResultsFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "results_predict_weights_file_name"
            );
        $resultsPredictAllMseFileName = $sResultsFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "results_predict_all_mse_file_name"
            );

        $this->dataStorage->setResultsTrainValuesFileName($resultsTrainValuesFileName);
        $this->dataStorage->setResultsTestValuesFileName($resultsTestValuesFileName);
        $this->dataStorage->setResultsPredictValuesFileName($resultsPredictValuesFileName);

        $this->dataStorage->setResultsTrainSettingsFileName($resultsTrainSettingsFileName);
        $this->dataStorage->setResultsTrainWeightsFileName($resultsTrainWeightsFileName);
        $this->dataStorage->setResultsTrainAllMseFileName($resultsTrainAllMseFileName);

        $this->dataStorage->setResultsTestSettingsFileName($resultsTestSettingsFileName);
        $this->dataStorage->setResultsTestWeightsFileName($resultsTestWeightsFileName);
        $this->dataStorage->setResultsTestAllMseFileName($resultsTestAllMseFileName);

        $this->dataStorage->setResultsPredictSettingsFileName($resultsPredictSettingsFileName);
        $this->dataStorage->setResultsPredictWeightsFileName($resultsPredictWeightsFileName);
        $this->dataStorage->setResultsPredictAllMseFileName($resultsPredictAllMseFileName);


        $resultsTrainValuesFileNameCsv = $sResultsFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "results_train_values_file_name_csv"
            );
        $resultsTestValuesFileNameCsv = $sResultsFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "results_test_values_file_name_csv"
            );
        $resultsPredictValuesFileNameCsv = $sResultsFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "results_predict_values_file_name_csv"
            );

        $resultsTrainSettingsFileNameCsv = $sResultsFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "results_train_settings_file_name_csv"
            );
        $resultsTrainWeightsFileNameCsv = $sResultsFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "results_train_weights_file_name_csv"
            );
        $resultsTrainAllMseFileNameCsv = $sResultsFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "results_train_all_mse_file_name_csv"
            );

        $resultsTestSettingsFileNameCsv = $sResultsFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "results_test_settings_file_name_csv"
            );
        $resultsTestWeightsFileNameCsv = $sResultsFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "results_test_weights_file_name_csv"
            );
        $resultsTestAllMseFileNameCsv = $sResultsFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "results_test_all_mse_file_name_csv"
            );

        $resultsPredictSettingsFileNameCsv = $sResultsFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "results_predict_settings_file_name_csv"
            );
        $resultsPredictWeightsFileNameCsv = $sResultsFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "results_predict_weights_file_name_csv"
            );
        $resultsPredictAllMseFileNameCsv = $sResultsFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "results_predict_all_mse_file_name_csv"
            );

        $this->dataStorage->setResultsTrainValuesFileNameCsv($resultsTrainValuesFileNameCsv);
        $this->dataStorage->setResultsTestValuesFileNameCsv($resultsTestValuesFileNameCsv);
        $this->dataStorage->setResultsPredictValuesFileNameCsv($resultsPredictValuesFileNameCsv);

        $this->dataStorage->setResultsTrainSettingsFileNameCsv($resultsTrainSettingsFileNameCsv);
        $this->dataStorage->setResultsTrainWeightsFileNameCsv($resultsTrainWeightsFileNameCsv);
        $this->dataStorage->setResultsTrainAllMseFileNameCsv($resultsTrainAllMseFileNameCsv);

        $this->dataStorage->setResultsTestSettingsFileNameCsv($resultsTestSettingsFileNameCsv);
        $this->dataStorage->setResultsTestWeightsFileNameCsv($resultsTestWeightsFileNameCsv);
        $this->dataStorage->setResultsTestAllMseFileNameCsv($resultsTestAllMseFileNameCsv);

        $this->dataStorage->setResultsPredictSettingsFileNameCsv($resultsPredictSettingsFileNameCsv);
        $this->dataStorage->setResultsPredictWeightsFileNameCsv($resultsPredictWeightsFileNameCsv);
        $this->dataStorage->setResultsPredictAllMseFileNameCsv($resultsPredictAllMseFileNameCsv);


        $this->trainInputFileName = $sRangeFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "train_input_file_name"
            );
        $this->trainTargetFileName = $sRangeFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "train_target_file_name"
            );

        $this->testInputFileName = $sRangeFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "test_input_file_name"
            );
        $this->testTargetFileName = $sRangeFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "test_target_file_name"
            );

        $this->predictInputFileName = $sRangeFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "predict_input_file_name"
            );
        $this->predictTargetFileName = $sRangeFolder . $this->dataStorage->getSettingsParameter(
                'general_looper',
                "predict_target_file_name"
            );


        $this->dataStorage->setViTrainInputColumnKeys(
            $this->dataStorage->getSettingsParameter(
                'input_column_looper',
                "train_input_column_keys"
            )
        );
        $this->dataStorage->setViTrainTargetColumnKeys(
            $this->dataStorage->getSettingsParameter(
                'input_column_looper',
                "train_target_column_keys"
            )
        );

        $this->dataStorage->setViTestInputColumnKeys(
            $this->dataStorage->getSettingsParameter(
                'input_column_looper',
                "test_input_column_keys"
            )
        );
        $this->dataStorage->setViTestTargetColumnKeys(
            $this->dataStorage->getSettingsParameter(
                'input_column_looper',
                "test_target_column_keys"
            )
        );

        $this->dataStorage->setViPredictInputColumnKeys(
            $this->dataStorage->getSettingsParameter(
                'input_column_looper',
                "predict_input_column_keys"
            )
        );
        $this->dataStorage->setViPredictTargetColumnKeys(
            $this->dataStorage->getSettingsParameter(
                'input_column_looper',
                "predict_target_column_keys"
            )
        );

        $this->setAdTrainInput();
        $this->setAdTrainTarget();
        $this->setAdTestInput();
        $this->setAdTestTarget();
        $this->setAdPredictInput();
        $this->setAdPredictTarget();
    }

    private function setAdTrainInput(): void
    {
        $adTrainInputCore = $this->get2DoubleArray($this->trainInputFileName);

        $viTrainInputColumnKeys = $this->dataStorage->getViTrainInputColumnKeys();

        $this->dataStorage->setAdTrainInput(
            $this->fetchArrayColumns($adTrainInputCore, $viTrainInputColumnKeys)
        );
    }

    private function setAdTrainTarget(): void
    {
        $adTrainTargetCore = $this->get2DoubleArray($this->trainTargetFileName);

        $viTrainTargetColumnKeys = $this->dataStorage->getViTrainTargetColumnKeys();

        $this->dataStorage->setAdTrainTarget(
            $this->fetchArrayColumns($adTrainTargetCore, $viTrainTargetColumnKeys)
        );
    }

    private function setAdTestInput(): void
    {
        $adTestInputCore = $this->get2DoubleArray($this->testInputFileName);

        $viTestInputColumnKeys = $this->dataStorage->getViTestInputColumnKeys();

        $this->dataStorage->setAdTestInput(
            $this->fetchArrayColumns($adTestInputCore, $viTestInputColumnKeys)
        );
    }

    private function setAdTestTarget(): void
    {
        $adTestTargetCore = $this->get2DoubleArray($this->testTargetFileName);

        $viTestTargetColumnKeys = $this->dataStorage->getViTestTargetColumnKeys();

        $this->dataStorage->setAdTestTarget(
            $this->fetchArrayColumns($adTestTargetCore, $viTestTargetColumnKeys)
        );
    }

    private function setAdPredictInput(): void
    {
        $adPredictInputCore = $this->get2DoubleArray($this->predictInputFileName);

        $viPredictInputColumnKeys = $this->dataStorage->getViPredictInputColumnKeys();

        $this->dataStorage->setAdPredictInput(
            $this->fetchArrayColumns($adPredictInputCore, $viPredictInputColumnKeys)
        );
    }

    private function setAdPredictTarget(): void
    {
        $adPredictTargetCore = $this->get2DoubleArray($this->predictTargetFileName);

        $viPredictTargetColumnKeys = $this->dataStorage->getViPredictTargetColumnKeys();

        $this->dataStorage->setAdPredictTarget(
            $this->fetchArrayColumns($adPredictTargetCore, $viPredictTargetColumnKeys)
        );
    }
}
