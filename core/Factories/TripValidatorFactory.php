<?php

namespace Core\Factories;

use App\Repositories\ActionRepository;
use App\Repositories\DayRepository;
use App\Repositories\TripRepository;
use Core\Language\Language;
use Core\Validation\TripValidator;

/**
 * Class TripValidatorFactory
 * @package Core\Factories
 * @author Martin Brom
 */
class TripValidatorFactory
{
    /** @var TripRepository */
    private $tripRepository;

    /** @var DayRepository */
    private $dayRepository;

    /** @var ActionRepository */
    private $actionRepository;

    /** @var ResponseFactory */
    private $responseFactory;

    /** @var Language */
    private $lang;

    /**
     * TripValidatorFactory constructor.
     * @param TripRepository $tripRepository
     * @param DayRepository $dayRepository
     * @param ActionRepository $actionRepository
     * @param ResponseFactory $responseFactory
     * @param Language $lang
     */
    function __construct(
            TripRepository $tripRepository,
            DayRepository $dayRepository,
            ActionRepository $actionRepository,
            ResponseFactory $responseFactory,
            Language $lang) {
        $this->tripRepository = $tripRepository;
        $this->dayRepository = $dayRepository;
        $this->actionRepository = $actionRepository;
        $this->responseFactory = $responseFactory;
        $this->lang = $lang;
    }

    /**
     * @param array $args
     * @return TripValidator
     */
    public function make($args = []) {
        return new TripValidator($this->tripRepository, $this->dayRepository, $this->actionRepository, $this->responseFactory, $this->lang);
    }
}