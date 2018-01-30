<?php

namespace App\Controllers;

use App\Enums\UserTripRoles;
use App\Repositories\InviteRepository;
use App\Repositories\TripRepository;
use App\Repositories\UserTripRepository;
use Core\AlertHelper;
use Core\Http\Controller;
use Core\Http\Request;
use Core\Http\Response\RedirectResponse;
use Core\Http\Response\Response;
use Core\Language\Language;
use Core\Mail\Mailer;
use Core\Session;

/**
 * Class InviteController
 * @package App\Controllers
 * @author Martin Brom
 */
class InviteController extends Controller
{
    /** @var TripRepository */
    private $tripRepository;

    /** @var InviteRepository */
    private $inviteRepository;

    /** @var Mailer */
    private $mailer;

    /** @var AlertHelper */
    private $alertHelper;

    /** @var Language */
    private $lang;

    /** @var UserTripRepository */
    private $userTripRepository;

    /** @var Session */
    private $session;

    /**
     * InviteController constructor.
     * @param TripRepository $tripRepository
     * @param InviteRepository $inviteRepository
     * @param UserTripRepository $userTripRepository
     * @param AlertHelper $alertHelper
     * @param Session $session
     * @param Language $lang
     * @param Mailer $mailer
     */
    function __construct(
            TripRepository $tripRepository,
            InviteRepository $inviteRepository,
            UserTripRepository $userTripRepository,
            AlertHelper $alertHelper,
            Session $session,
            Language $lang,
            Mailer $mailer) {
        $this->tripRepository = $tripRepository;
        $this->inviteRepository = $inviteRepository;
        $this->mailer = $mailer;
        $this->alertHelper = $alertHelper;
        $this->lang = $lang;
        $this->userTripRepository = $userTripRepository;
        $this->session = $session;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function invitePage(Request $request) {
        $trip = $this->tripRepository->getTrip($request->getInput('trip_id'));

        return $this->responseFactory->html('trip/invite.html.twig', ['trip' => $trip]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function invite(Request $request) {
        $trip_id = $request->getParameter('trip_id');
        $trip = $this->tripRepository->getTrip($trip_id);

        $recipient = $request->getInput('invite_email');
        $message = $request->getInput('invite_message');

        if (!$this->inviteRepository->canInvite($trip_id, $recipient)) {

            $this->alertHelper->warning($this->lang->get('alerts.trip-invite.exists'));
            return $this->route('trip.invite', ['trip_id' => $trip_id]);
        }

        $token = token(32);     // token must be unique but 62^32 combinations is way too many...
        if (!$this->inviteRepository->create($trip_id, $recipient, $message, $token)) {
            $this->alertHelper->error($this->lang->get('alerts.trip-invite.error'));
            return $this->route('trip.invite', ['trip_id' => $trip_id]);
        }

        $this->mailer->invite($recipient, $token, $message, $trip['title']);
        $this->alertHelper->success($this->lang->get('alerts.trip-invite.success'));
        return $this->route('trip.invite', ['trip_id' => $trip_id]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function inviteAccept(Request $request) {
        $token = $request->getParameter('token');
        $invite = $this->inviteRepository->getInvite($token);

        if ($invite == NULL) {
            $this->alertHelper->error($this->lang->get('alerts.trip-invite.missing'));
            return $this->route('dashboard');
        }

        $trip_id = $invite['trip_id'];
        $trip = $this->tripRepository->getTrip($trip_id);

        if ($trip == NULL) {
            $this->alertHelper->error($this->lang->get('alerts.trip.missing'));
            return $this->route('dashboard');
        }

        $user_id = $this->session->get('user.id');

        if (!$this->inviteRepository->delete($invite['id'])) {
            $this->alertHelper->error($this->lang->get('alerts.trip-invite-accept.error'));
            return $this->route('dashboard');
        }

        if ($this->userTripRepository->hasAccess($user_id, $trip_id)) {
            $this->alertHelper->info($this->lang->get('alerts.trip-invite-accept.access'));
            return $this->route('trip.show', ['trip_id' => $trip_id]);
        }

        if (!$this->userTripRepository->create($user_id, $trip_id, UserTripRoles::TRAVELLER)) {
            $this->alertHelper->error($this->lang->get('alerts.trip-invite-accept.error'));
            return $this->route('dashboard');
        }

        $this->alertHelper->success($this->lang->get('alerts.trip-invite-accept.success'));
        return $this->route('trip.show', ['trip_id' => $trip_id]);
    }
}