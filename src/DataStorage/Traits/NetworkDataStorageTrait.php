<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\DataStorage\Traits;

trait NetworkDataStorageTrait
{
    protected array $vdMSE;
    protected array $adMAE;
    protected float $dMSE;
    protected float $dMAE;

    private float $dLambda;
    private bool $shiftedDLambda;

    public function getShiftedDLambda(): bool
    {
        return $this->shiftedDLambda;
    }

    public function setShiftedDLambda(bool $shiftedDLambda): void
    {
        $this->shiftedDLambda = $shiftedDLambda;
    }

    public function getDMSE(): float
    {
        return $this->dMSE;
    }
    public function setDMSE(float $dMSE): void
    {
        $this->dMSE = $dMSE;
    }

    public function getDMAE(): float
    {
        return $this->dMAE;
    }
    public function setDMAE(float $dMAE): void
    {
        $this->dMAE = $dMAE;
    }

    public function getVdMSE(): array
    {
        return $this->vdMSE;
    }
    public function setVdMSE(array $vdMSE): void
    {
        $this->vdMSE = $vdMSE;
    }

    public function getAdMAE(): array
    {
        return $this->adMAE;
    }
    public function setAdMAE(array $adMAE): void
    {
        $this->adMAE = $adMAE;
    }

    public function getDLambda(): float
    {
        return $this->dLambda;
    }

    public function setDLambda(float $dLambda): void
    {
        $this->dLambda = $dLambda;
    }

    private array $viTrainInputColumnKeys;
    private array $viTrainTargetColumnKeys;
    private array $viTestInputColumnKeys;
    private array $viTestTargetColumnKeys;
    private array $viPredictInputColumnKeys;
    private array $viPredictTargetColumnKeys;

    private array $adTrainInput;
    private array $adTrainTarget;
    private array $adTestInput;
    private array $adTestTarget;
    private array $adPredictInput;
    private array $adPredictTarget;

    private int $iSequenceRowsNumber;

    private int $iTrainSequencesNumber;
    private int $iTestSequencesNumber;
    private int $iPredictSequencesNumber;

    private array $viNodesInLayers;
    private array $aiNodesIndexesInLayers;

    public function getAiNodesIndexesInLayers(): array
    {
        return $this->aiNodesIndexesInLayers;
    }

    public function setAiNodesIndexesInLayers(array $aiNodesIndexesInLayers): void
    {
        $this->aiNodesIndexesInLayers = $aiNodesIndexesInLayers;
    }

    private array $adTrainInputSequence;
    private array $adTrainTargetSequence;
    private array $adTestInputSequence;
    private array $adTestTargetSequence;
    private array $adPredictInputSequence;
    private array $adPredictTargetSequence;

    public function getVdTrainRecurrencyInit(): array
    {
        return $this->vdTrainRecurrencyInit;
    }
    public function setVdTrainRecurrencyInit(array $vdTrainRecurrencyInit): void
    {
        $this->vdTrainRecurrencyInit = $vdTrainRecurrencyInit;
    }
    public function getVdTestRecurrencyInit(): array
    {
        return $this->vdTestRecurrencyInit;
    }
    public function setVdTestRecurrencyInit(array $vdTestRecurrencyInit): void
    {
        $this->vdTestRecurrencyInit = $vdTestRecurrencyInit;
    }
    public function getVdPredictRecurrencyInit(): array
    {
        return $this->vdPredictRecurrencyInit;
    }
    public function setVdPredictRecurrencyInit(array $vdPredictRecurrencyInit): void
    {
        $this->vdPredictRecurrencyInit = $vdPredictRecurrencyInit;
    }

    private array $vdTrainRecurrencyInit;
    private array $vdTestRecurrencyInit;
    private array $vdPredictRecurrencyInit;

    private array $vdTrainInputRow;
    private array $vdTrainTargetRow;
    private array $vdTestInputRow;
    private array $vdTestTargetRow;
    private array $vdPredictInputRow;
    private array $vdPredictTargetRow;

    private array $vdThresholds;
    private array $a3dWeights;
    private array $a3dWeightsBefore;
    private int $iWeightsNumber;
    private array $a2sActivationFunction;
    private array $a2dActivationFunctionParameter;

    private array $a2dFnc;
    private array $a2dDrv;
    private array $a2dDrv2;
    private array $a2dSum;

    private array $vla2dTrainResults;
    private array $vla2dTestResults;
    private array $vla2dPredictResults;

    private float $dTrainMSE = 100000.0;

    public function getdTrainMSE(): float
    {
        return $this->dTrainMSE;
    }

    public function setdTrainMSE(float $dTrainMSE): void
    {
        $this->dTrainMSE = $dTrainMSE;
    }

    private array $vldTrainResultsMSE;
    private array $vldTestResultsMSE;
    private array $vldPredictResultsMSE;

    private array $vlsTrainResultsSettings;
    private array $vla3dTrainResultsWeights;
    private array $vlvdTrainResultsAllMSE;

    private array $vlsTestResultsSettings;
    private array $vla3dTestResultsWeights;
    private array $vlvdTestResultsAllMSE;

    private array $vlsPredictResultsSettings;
    private array $vla3dPredictResultsWeights;
    private array $vlvdPredictResultsAllMSE;

    private string $uploadFolder;
    private string $sScopeFolder;
    private string $sRangeFolder;
    private string $sResultsFolder;


    private string $resultsTrainValuesFileName;
    private string $resultsTestValuesFileName;
    private string $resultsPredictValuesFileName;

    private ?string $resultsTrainSettingsFileName = null;
    private string $resultsTrainWeightsFileName;
    private string $resultsTrainAllMseFileName;

    private string $resultsTestSettingsFileName;
    private string $resultsTestWeightsFileName;
    private string $resultsTestAllMseFileName;

    private string $resultsPredictSettingsFileName;
    private string $resultsPredictWeightsFileName;
    private string $resultsPredictAllMseFileName;


    private string $resultsTrainValuesFileNameCsv;
    private string $resultsTestValuesFileNameCsv;
    private string $resultsPredictValuesFileNameCsv;

    private ?string $resultsTrainSettingsFileNameCsv = null;
    private string $resultsTrainWeightsFileNameCsv;
    private string $resultsTrainAllMseFileNameCsv;

    private string $resultsTestSettingsFileNameCsv;
    private string $resultsTestWeightsFileNameCsv;
    private string $resultsTestAllMseFileNameCsv;

    private string $resultsPredictSettingsFileNameCsv;
    private string $resultsPredictWeightsFileNameCsv;
    private string $resultsPredictAllMseFileNameCsv;

    public function initResultsCollections(): void
    {
        $this->vla2dTrainResults = [];
        $this->vla2dTestResults = [];
        $this->vla2dPredictResults = [];

        $this->vldTrainResultsMSE = [];
        $this->vldTestResultsMSE = [];
        $this->vldPredictResultsMSE = [];

        $this->vlsTrainResultsSettings = [];
        $this->vla3dTrainResultsWeights = [];
        $this->vlvdTrainResultsAllMSE = [];

        $this->vlsTestResultsSettings = [];
        $this->vla3dTestResultsWeights = [];
        $this->vlvdTestResultsAllMSE = [];

        $this->vlsPredictResultsSettings = [];
        $this->vla3dPredictResultsWeights = [];
        $this->vlvdPredictResultsAllMSE = [];
    }

    public function getViTrainInputColumnKeys(): array {return  $this->viTrainInputColumnKeys;}

    public function getViTrainTargetColumnKeys(): array {return  $this->viTrainTargetColumnKeys;}

    public function getViTestInputColumnKeys(): array {return  $this->viTestInputColumnKeys;}

    public function getViTestTargetColumnKeys(): array {return  $this->viTestTargetColumnKeys;}

    public function getViPredictInputColumnKeys(): array {return  $this->viPredictInputColumnKeys;}

    public function getViPredictTargetColumnKeys(): array {return  $this->viPredictTargetColumnKeys;}


    public function getAdTrainInput(): array {return $this->adTrainInput;}

    public function getAdTrainTarget(): array {return $this->adTrainTarget;}

    public function getAdTestInput(): array {return $this->adTestInput;}

    public function getAdTestTarget(): array {return $this->adTestTarget;}

    public function getAdPredictInput(): array {return $this->adPredictInput;}

    public function getAdPredictTarget(): array {return $this->adPredictTarget;}


    public function getIsequenceRowsNumber(): int {return $this->iSequenceRowsNumber;}

    public function getItrainSequencesNumber(): int {return $this->iTrainSequencesNumber;}

    public function getItestSequencesNumber(): int {return $this->iTestSequencesNumber;}

    public function getIpredictSequencesNumber(): int {return $this->iPredictSequencesNumber;}


    public function getViNodesInLayers(): array {return $this->viNodesInLayers;}


    public function getAdTrainInputSequence(): array {return $this->adTrainInputSequence;}

    public function getAdTrainTargetSequence(): array {return $this->adTrainTargetSequence;}

    public function getAdTestInputSequence(): array {return $this->adTestInputSequence;}

    public function getAdTestTargetSequence(): array {return $this->adTestTargetSequence;}

    public function getAdPredictInputSequence(): array {return $this->adPredictInputSequence;}

    public function getAdPredictTargetSequence(): array {return $this->adPredictTargetSequence;}


    public function getVdTrainInputRow(): array {return $this->vdTrainInputRow;}

    public function getVdTrainTargetRow(): array {return $this->vdTrainTargetRow;}

    public function getVdTestInputRow(): array {return $this->vdTestInputRow;}

    public function getVdTestTargetRow(): array {return $this->vdTestTargetRow;}

    public function getVdPredictInputRow(): array {return $this->vdPredictInputRow;}

    public function getVdPredictTargetRow(): array {return $this->vdPredictTargetRow;}


    public function getVdThresholds(): array {return $this->vdThresholds;}

    public function getA3dWeights(): array {return $this->a3dWeights;}

    public function getA3dWeightsBefore(): array {return $this->a3dWeightsBefore;}

    public function getIweightsNumber(): int {return $this->iWeightsNumber;}

    public function getA2sActivationFunction(): array {return $this->a2sActivationFunction;}

    public function getA2dActivationFunctionParameter(): array {return $this->a2dActivationFunctionParameter;}


    public function getA2dFnc(): array {return $this->a2dFnc;}

    public function getA2dDrv(): array {return $this->a2dDrv;}

    public function getA2dDrv2(): array {return $this->a2dDrv2;}

    public function getA2dSum(): array {return $this->a2dSum;}


    public function getVla2dTrainResults(): array {return $this->vla2dTrainResults;}

    public function getVla2dTestResults(): array {return $this->vla2dTestResults;}

    public function getVla2dPredictResults(): array {return $this->vla2dPredictResults;}


    public function getVldTrainResultsMSE(): array {return $this->vldTrainResultsMSE;}

    public function getVldTestResultsMSE(): array {return $this->vldTestResultsMSE;}

    public function getVldPredictResultsMSE(): array {return $this->vldPredictResultsMSE;}


    public function getVlsTrainResultsSettings(): array {return $this->vlsTrainResultsSettings;}

    public function getVla3dTrainResultsWeights(): array {return $this->vla3dTrainResultsWeights;}

    public function getVlvdTrainResultsAllMSE(): array {return $this->vlvdTrainResultsAllMSE;}


    public function getVlsTestResultsSettings(): array {return $this->vlsTestResultsSettings;}

    public function getVla3dTestResultsWeights(): array {return $this->vla3dTestResultsWeights;}

    public function getVlvdTestResultsAllMSE(): array {return $this->vlvdTestResultsAllMSE;}


    public function getVlsPredictResultsSettings(): array {return $this->vlsPredictResultsSettings;}

    public function getVla3dPredictResultsWeights(): array {return $this->vla3dPredictResultsWeights;}

    public function getVlvdPredictResultsAllMSE(): array {return $this->vlvdPredictResultsAllMSE;}


    public function getUploadFolder(): string { return $this->uploadFolder; }

    public function getsScopeFolder(): string { return $this->sScopeFolder; }

    public function getsRangeFolder(): string { return $this->sRangeFolder; }

    public function getsResultsFolder(): string { return $this->sResultsFolder; }


    public function getResultsTrainValuesFileName(): string { return $this->resultsTrainValuesFileName; }

    public function getResultsTestValuesFileName(): string { return $this->resultsTestValuesFileName; }

    public function getResultsPredictValuesFileName(): string { return $this->resultsPredictValuesFileName; }


    public function getResultsTrainSettingsFileName(): ?string { return $this->resultsTrainSettingsFileName; }

    public function getResultsTrainWeightsFileName(): string { return $this->resultsTrainWeightsFileName; }

    public function getResultsTrainAllMseFileName(): string { return $this->resultsTrainAllMseFileName; }


    public function getResultsTestSettingsFileName(): string { return $this->resultsTestSettingsFileName; }

    public function getResultsTestWeightsFileName(): string { return $this->resultsTestWeightsFileName; }

    public function getResultsTestAllMseFileName(): string { return $this->resultsTestAllMseFileName; }


    public function getResultsPredictSettingsFileName(): string { return $this->resultsPredictSettingsFileName; }

    public function getResultsPredictWeightsFileName(): string { return $this->resultsPredictWeightsFileName; }

    public function getResultsPredictAllMseFileName(): string { return $this->resultsPredictAllMseFileName; }


    public function setViTrainInputColumnKeys(array $viTrainInputColumnKeys): void
    {
        $this->viTrainInputColumnKeys = $viTrainInputColumnKeys;
    }
    public function setViTrainTargetColumnKeys(array $viTrainTargetColumnKeys): void
    {
        $this->viTrainTargetColumnKeys = $viTrainTargetColumnKeys;
    }
    public function setViTestInputColumnKeys(array $viTestInputColumnKeys): void
    {
        $this->viTestInputColumnKeys = $viTestInputColumnKeys;
    }
    public function setViTestTargetColumnKeys(array $viTestTargetColumnKeys): void
    {
        $this->viTestTargetColumnKeys = $viTestTargetColumnKeys;
    }
    public function setViPredictInputColumnKeys(array $viPredictInputColumnKeys): void
    {
        $this->viPredictInputColumnKeys = $viPredictInputColumnKeys;
    }
    public function setViPredictTargetColumnKeys(array $viPredictTargetColumnKeys): void
    {
        $this->viPredictTargetColumnKeys = $viPredictTargetColumnKeys;
    }


    public function setAdTrainInput(array $adTrainInput): void {$this->adTrainInput = $adTrainInput;}

    public function setAdTrainTarget(array $adTrainTarget): void {$this->adTrainTarget = $adTrainTarget;}

    public function setAdTestInput(array $adTestInput): void {$this->adTestInput = $adTestInput;}

    public function setAdTestTarget(array $adTestTarget): void {$this->adTestTarget = $adTestTarget;}

    public function setAdPredictInput(array $adPredictInput): void {$this->adPredictInput = $adPredictInput;}

    public function setAdPredictTarget(array $adPredictTarget): void {$this->adPredictTarget = $adPredictTarget;}


    public function setIsequenceRowsNumber(int $iSequenceRowsNumber): void
    {
        $this->iSequenceRowsNumber = $iSequenceRowsNumber;
    }


    public function setItrainSequencesNumber(int $iTrainSequencesNumber): void
    {
        $this->iTrainSequencesNumber = $iTrainSequencesNumber;
    }
    public function setItestSequencesNumber(int $iTestSequencesNumber): void
    {
        $this->iTestSequencesNumber = $iTestSequencesNumber;
    }
    public function setIpredictSequencesNumber(int $iPredictSequencesNumber): void
    {
        $this->iPredictSequencesNumber = $iPredictSequencesNumber;
    }


    public function setViNodesInLayers(array $viNodesInLayers) {$this->viNodesInLayers = $viNodesInLayers;}


    public function setAdTrainInputSequence(array $adTrainInputSequence): void
    {
        $this->adTrainInputSequence = $adTrainInputSequence;
    }
    public function setAdTrainTargetSequence(array $adTrainTargetSequence): void
    {
        $this->adTrainTargetSequence = $adTrainTargetSequence;
    }
    public function setAdTestInputSequence(array $adTestInputSequence): void
    {
        $this->adTestInputSequence = $adTestInputSequence;
    }
    public function setAdTestTargetSequence(array $adTestTargetSequence): void
    {
        $this->adTestTargetSequence = $adTestTargetSequence;
    }
    public function setAdPredictInputSequence(array $adPredictInputSequence): void
    {
        $this->adPredictInputSequence = $adPredictInputSequence;
    }
    public function setAdPredictTargetSequence(array $adPredictTargetSequence): void
    {
        $this->adPredictTargetSequence = $adPredictTargetSequence;
    }


    public function setVdTrainInputRow(array $vdTrainInputRow): void
    {
        $this->vdTrainInputRow = $vdTrainInputRow;
    }
    public function setVdTrainTargetRow(array $vdTrainTargetRow): void
    {
        $this->vdTrainTargetRow = $vdTrainTargetRow;
    }
    public function setVdTestInputRow(array $vdTestInputRow): void
    {
        $this->vdTestInputRow = $vdTestInputRow;
    }
    public function setVdTestTargetRow(array $vdTestTargetRow): void
    {
        $this->vdTestTargetRow = $vdTestTargetRow;
    }
    public function setVdPredictInputRow(array $vdPredictInputRow): void
    {
        $this->vdPredictInputRow = $vdPredictInputRow;
    }
    public function setVdPredictTargetRow(array $vdPredictTargetRow): void
    {
        $this->vdPredictTargetRow = $vdPredictTargetRow;
    }


    public function setVdThresholds(array $vdThresholds): void
    {
        $this->vdThresholds = $vdThresholds;
    }
    public function setA3dWeights(array $a3dWeights): void
    {
        $this->a3dWeights = $a3dWeights;
    }
    public function setA3dWeightsBefore(): void
    {
        $this->a3dWeightsBefore = $this->a3dWeights;
    }
    public function setIWeightsNumber(): void
    {
        $this->iWeightsNumber = 0; // number of weights

        $iL = count($this->viNodesInLayers);
        for ($il = 1; $il <= $iL - 1; $il++){ // weights in hidden layers
            $this->iWeightsNumber = $this->iWeightsNumber + $this->viNodesInLayers[$il] * ($this->viNodesInLayers[$il - 1] + 1);
        }
    }


    public function setA2sActivationFunction(array $a2sActivationFunction): void
    {
        $this->a2sActivationFunction = $a2sActivationFunction;
    }
    public function setA2dActivationFunctionParameter(array $asActivationFunctionParameter): void
    {
        $this->a2dActivationFunctionParameter = $asActivationFunctionParameter;
    }


    public function setA2dFnc(array $a2dFnc): void {$this->a2dFnc = $a2dFnc;}

    public function setA2dDrv(array $a2dDrv): void {$this->a2dDrv = $a2dDrv;}

    public function setA2dDrv2(array $a2dDrv2): void {$this->a2dDrv2 = $a2dDrv2;}

    public function setA2dSum(array $a2dSum): void {$this->a2dSum = $a2dSum;}


    public function setVla2dTrainResults(array $vla2dTrainResults): void
    {
        $this->vla2dTrainResults = $vla2dTrainResults;
    }
    public function setVla2dTestResults(array $vla2dTestResults): void
    {
        $this->vla2dTestResults = $vla2dTestResults;
    }
    public function setVla2dPredictResults(array $vla2dPredictResults): void
    {
        $this->vla2dPredictResults = $vla2dPredictResults;
    }


    public function setVldTrainResultsMSE(array $vdTrainResultsMSE): void
    {
        $this->vldTrainResultsMSE = $vdTrainResultsMSE;
    }
    public function setVldTestResultsMSE(array $vdTestResultsMSE): void
    {
        $this->vldTestResultsMSE = $vdTestResultsMSE;
    }
    public function setVldPredictResultsMSE(array $vdPredictResultsMSE): void
    {
        $this->vldPredictResultsMSE = $vdPredictResultsMSE;
    }


    public function setVlsTrainResultsSettings(array $vlsTrainResultsSettings): void
    {
        $this->vlsTrainResultsSettings = $vlsTrainResultsSettings;
    }
    public function setVla3dTrainResultsWeights(array $vla3dTrainResultsWeights): void
    {
        $this->vla3dTrainResultsWeights = $vla3dTrainResultsWeights;
    }
    public function setVlvdTrainResultsAllMSE(array $vlvdTrainResultsAllMSE): void
    {
        $this->vlvdTrainResultsAllMSE = $vlvdTrainResultsAllMSE;
    }


    public function setVlsTestResultsSettings(array $vlsTestResultsSettings): void
    {
        $this->vlsTestResultsSettings = $vlsTestResultsSettings;
    }
    public function setVla3dTestResultsWeights(array $vla3dTestResultsWeights): void
    {
        $this->vla3dTestResultsWeights = $vla3dTestResultsWeights;
    }
    public function setVlvdTestResultsAllMSE(array $vlvdTestResultsAllMSE): void
    {
        $this->vlvdTestResultsAllMSE = $vlvdTestResultsAllMSE;
    }


    public function setVlsPredictResultsSettings(array $vlsPredictResultsSettings): void
    {
        $this->vlsPredictResultsSettings = $vlsPredictResultsSettings;
    }
    public function setVla3dPredictResultsWeights(array $vla3dPredictResultsWeights): void
    {
        $this->vla3dPredictResultsWeights = $vla3dPredictResultsWeights;
    }
    public function setVlvdPredictResultsAllMSE(array $vlvdPredictResultsAllMSE): void
    {
        $this->vlvdPredictResultsAllMSE = $vlvdPredictResultsAllMSE;
    }


    public function setUploadFolder(string $uploadFolder): void {$this->uploadFolder = $uploadFolder;}

    public function setsScopeFolder(string $sScopeFolder): void {$this->sScopeFolder = $sScopeFolder;}

    public function setsRangeFolder(string $sRangeFolder): void {$this->sRangeFolder = $sRangeFolder;}

    public function setsResultsFolder(string $sResultsFolder): void {$this->sResultsFolder = $sResultsFolder;}


    public function setResultsTrainValuesFileName(string $resultsTrainValuesFileName): void
    {
        $this->resultsTrainValuesFileName = $resultsTrainValuesFileName;
    }
    public function setResultsTestValuesFileName(string $resultsTestValuesFileName): void
    {
        $this->resultsTestValuesFileName = $resultsTestValuesFileName;
    }
    public function setResultsPredictValuesFileName(string $resultsPredictValuesFileName): void
    {
        $this->resultsPredictValuesFileName = $resultsPredictValuesFileName;
    }


    public function setResultsTrainSettingsFileName(?string $resultsTrainSettingsFileName): void
    {
        $this->resultsTrainSettingsFileName = $resultsTrainSettingsFileName;
    }
    public function setResultsTrainWeightsFileName(string $resultsTrainWeightsFileName): void
    {
        $this->resultsTrainWeightsFileName = $resultsTrainWeightsFileName;
    }
    public function setResultsTrainAllMseFileName(string $resultsTrainAllMseFileName): void
    {
        $this->resultsTrainAllMseFileName = $resultsTrainAllMseFileName;
    }


    public function setResultsTestSettingsFileName(string $resultsTestSettingsFileName): void
    {
        $this->resultsTestSettingsFileName = $resultsTestSettingsFileName;
    }
    public function setResultsTestWeightsFileName(string $resultsTestWeightsFileName): void
    {
        $this->resultsTestWeightsFileName = $resultsTestWeightsFileName;
    }
    public function setResultsTestAllMseFileName(string $resultsTestAllMseFileName): void
    {
        $this->resultsTestAllMseFileName = $resultsTestAllMseFileName;
    }


    public function setResultsPredictSettingsFileName(string $resultsPredictSettingsFileName): void
    {
        $this->resultsPredictSettingsFileName = $resultsPredictSettingsFileName;
    }
    public function setResultsPredictWeightsFileName(string $resultsPredictWeightsFileName): void
    {
        $this->resultsPredictWeightsFileName = $resultsPredictWeightsFileName;
    }
    public function setResultsPredictAllMseFileName(string $resultsPredictAllMseFileName): void
    {
        $this->resultsPredictAllMseFileName = $resultsPredictAllMseFileName;
    }


    public function setResultsTrainValuesFileNameCsv(string $resultsTrainValuesFileNameCsv): void
    {
        $this->resultsTrainValuesFileNameCsv = $resultsTrainValuesFileNameCsv;
    }
    public function setResultsTestValuesFileNameCsv(string $resultsTestValuesFileNameCsv): void
    {
        $this->resultsTestValuesFileNameCsv = $resultsTestValuesFileNameCsv;
    }
    public function setResultsPredictValuesFileNameCsv(string $resultsPredictValuesFileNameCsv): void
    {
        $this->resultsPredictValuesFileNameCsv = $resultsPredictValuesFileNameCsv;
    }


    public function setResultsTrainSettingsFileNameCsv(?string $resultsTrainSettingsFileNameCsv): void
    {
        $this->resultsTrainSettingsFileNameCsv = $resultsTrainSettingsFileNameCsv;
    }
    public function setResultsTrainWeightsFileNameCsv(string $resultsTrainWeightsFileNameCsv): void
    {
        $this->resultsTrainWeightsFileNameCsv = $resultsTrainWeightsFileNameCsv;
    }
    public function setResultsTrainAllMseFileNameCsv(string $resultsTrainAllMseFileNameCsv): void
    {
        $this->resultsTrainAllMseFileNameCsv = $resultsTrainAllMseFileNameCsv;
    }


    public function setResultsTestSettingsFileNameCsv(string $resultsTestSettingsFileNameCsv): void
    {
        $this->resultsTestSettingsFileNameCsv = $resultsTestSettingsFileNameCsv;
    }
    public function setResultsTestWeightsFileNameCsv(string $resultsTestWeightsFileNameCsv): void
    {
        $this->resultsTestWeightsFileNameCsv = $resultsTestWeightsFileNameCsv;
    }
    public function setResultsTestAllMseFileNameCsv(string $resultsTestAllMseFileNameCsv): void
    {
        $this->resultsTestAllMseFileNameCsv = $resultsTestAllMseFileNameCsv;
    }


    public function setResultsPredictSettingsFileNameCsv(string $resultsPredictSettingsFileNameCsv): void
    {
        $this->resultsPredictSettingsFileNameCsv = $resultsPredictSettingsFileNameCsv;
    }
    public function setResultsPredictWeightsFileNameCsv(string $resultsPredictWeightsFileNameCsv): void
    {
        $this->resultsPredictWeightsFileNameCsv = $resultsPredictWeightsFileNameCsv;
    }
    public function setResultsPredictAllMseFileNameCsv(string $resultsPredictAllMseFileNameCsv): void
    {
        $this->resultsPredictAllMseFileNameCsv = $resultsPredictAllMseFileNameCsv;
    }


    public function getResultsTrainValuesFileNameCsv():string {return $this->resultsTrainValuesFileNameCsv;}

    public function getResultsTestValuesFileNameCsv():string {return $this->resultsTestValuesFileNameCsv;}

    public function getResultsPredictValuesFileNameCsv():string {return $this->resultsPredictValuesFileNameCsv;}


    public function getResultsTrainSettingsFileNameCsv():?string {return $this->resultsTrainSettingsFileNameCsv;}

    public function getResultsTrainWeightsFileNameCsv():string {return $this->resultsTrainWeightsFileNameCsv;}

    public function getResultsTrainAllMseFileNameCsv():string {return $this->resultsTrainAllMseFileNameCsv;}


    public function getResultsTestSettingsFileNameCsv():string {return $this->resultsTestSettingsFileNameCsv;}

    public function getResultsTestWeightsFileNameCsv():string {return $this->resultsTestWeightsFileNameCsv;}

    public function getResultsTestAllMseFileNameCsv():string {return $this->resultsTestAllMseFileNameCsv;}


    public function getResultsPredictSettingsFileNameCsv():string {return $this->resultsPredictSettingsFileNameCsv;}

    public function getResultsPredictWeightsFileNameCsv():string {return $this->resultsPredictWeightsFileNameCsv;}

    public function getResultsPredictAllMseFileNameCsv():string {return $this->resultsPredictAllMseFileNameCsv;}
}
