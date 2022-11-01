<?php

namespace DvBusiness\WizardResult;

use DvBusiness\DostavistaAuth\DostavistaApiTokenVerifier;
use DvBusiness\DvOptions;
use DvBusiness\ModuleMetric\ModuleMetricManager;

class WizardResultManager
{
    const WIZARD_MAX_STEP_NUMBER = 4;

    /** @var DvOptions */
    private $dvOptions;

    /** @var ModuleMetricManager */
    private $moduleMetricManager;

    public function __construct(DvOptions $dvOptions)
    {
        $this->dvOptions           = $dvOptions;
    }

    public function getIsWizardFinished(): bool
    {
        // Визард считается пройденным, если все шаги закончены и токен существует
        return $this->getLastFinishedStep() === static::WIZARD_MAX_STEP_NUMBER;
    }

    public function setLastFinishedStep(int $stepNum, ModuleMetricManager $moduleMetricManager)
    {
        $settings  = $this->dvOptions->getSettings();

        if ($this->dvOptions->getWizardLastFinishedStep() < $stepNum && $stepNum <= static::WIZARD_MAX_STEP_NUMBER) {
            $settings['shipping_dvbusiness_wizard_last_finished_step'] = $stepNum;
            $this->dvOptions->updateSettings($settings);
        }

        // Отправим событие
        $moduleMetricManager->wizardStepCompleted($stepNum);
    }

    public function getLastFinishedStep(): int
    {
        $dvApiTokenVerifier = new DostavistaApiTokenVerifier($this->dvOptions);

        return $dvApiTokenVerifier->isCmsModuleApiTokenValid() ?  $this->dvOptions->getWizardLastFinishedStep() : 0;

    }
}
