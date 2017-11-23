<?php

namespace App\Controllers;

use App\Repositories\ActionTypeRepository;
use Core\Http\Controller;
use Core\Http\Response\HtmlResponse;

/**
 * Class ActionTypeController
 * @package App\Controllers
 * @author Martin Brom
 */
class ActionTypeController extends Controller
{
    /** @var ActionTypeRepository */
    private $actionTypeRepository;

    /**
     * ActionTypeController constructor.
     * @param ActionTypeRepository $actionTypeRepository
     */
    function __construct(ActionTypeRepository $actionTypeRepository) {
        $this->actionTypeRepository = $actionTypeRepository;
    }

    /**
     * Returns a html response with all action types
     * @return HtmlResponse All action types
     */
    public function index() {
        $action_types = $this->actionTypeRepository->getAll();
        return $this->responseFactory->html('trip/actionTypesSelect.html.twig', ['action_types' => $action_types]);
    }
}