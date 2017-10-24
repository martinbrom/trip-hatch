<?php

namespace App\Controllers;

use \Core\Controller;

class TripController extends Controller {
    public function tripShow($id) {
        echo "showing trip with id " . $id;
    }

    public function tripCommentShow($id, $comment_id) {
        echo "showing trip with id " . $id . " and comment_id " . $comment_id;
    }
}