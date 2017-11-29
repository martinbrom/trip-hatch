<?php

namespace App\Controllers;

use App\Repositories\ActionRepository;
use Core\Http\Controller;
use Core\Http\Response\HtmlResponse;

/**
 * Class ActionController
 * @package App\Controllers
 * @author Martin Brom
 */
class ActionController extends Controller
{
    /** @var ActionRepository */
    private $actionRepository;

    /**
     * ActionController constructor.
     * @param ActionRepository $actionRepository
     */
    function __construct(ActionRepository $actionRepository) {
        $this->actionRepository = $actionRepository;
    }

    /**
     * Returns a html response with all actions for given day
     * @param int $trip_id ID of a trip
     * @param int $day_id ID of a day
     * @return HtmlResponse All actions for given day
     */
    public function actions($trip_id, $day_id) {
        $actions = $this->actionRepository->getActions($day_id);
        return $this->responseFactory->html('trip/actions.html.twig', ['actions' => $actions]);
    }
}