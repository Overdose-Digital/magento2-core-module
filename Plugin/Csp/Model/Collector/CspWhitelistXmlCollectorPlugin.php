<?php

declare(strict_types=1);

namespace Overdose\Core\Plugin\Csp\Model\Collector;

use Magento\Csp\Model\Collector\CspWhitelistXmlCollector;
use Magento\Csp\Model\Policy\FetchPolicy;
use Overdose\Core\Helper\CspHelper;

/**
 * Class CspWhitelistXmlCollectorPlugin
 */
class CspWhitelistXmlCollectorPlugin
{
    /**
     * @var CspHelper
     */
    protected $cspHelper;

    public function __construct(CspHelper $cspHelper) {
        $this->cspHelper = $cspHelper;
    }

    /**
     * @ingeritdoc
     */
    public function afterCollect(CspWhitelistXmlCollector $subject, array $defaultPolicies = []) :array
    {

        $resources = $this->cspHelper->getCspSources();

        foreach ($resources as $resource) {
            if (!empty($resource['directives'])) {
                $sources = explode(',', $resource['source']);
                foreach ($resource['directives'] as $src) {
                    foreach ($sources as $source) {
                        $defaultPolicies[] = new FetchPolicy(
                            $src,
                            false,
                            [$source],
                            [],
                            false,
                            false,
                            false,
                            [],
                            [],
                            false,
                            false
                        );
                    }
                }
            }
        }

        return $defaultPolicies;
    }
}
